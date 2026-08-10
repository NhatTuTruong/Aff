<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Số ngày coi là "mới" cho Featured destinations & Hot coupons. */
    private const RECENT_DAYS = 5;

    public function index(Request $request): View
    {
        $query = $request->get('q');
        $categorySlug = $request->get('cat');

        // Popular Categories: luôn hiển thị theo list danh mục mặc định (affiliate)
        $names = config('default_categories.names', User::defaultCategoryNames());
        $popularCategories = collect($names)->map(fn ($name) => (object) ['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)]);

        $cutoff = now()->subDays(self::RECENT_DAYS);

        $featuredCampaigns = $this->resolveFeaturedCampaigns($query, $categorySlug, $cutoff);
        $hotCoupons = $this->resolveHotCoupons($query, $categorySlug, $cutoff);

        // Global stats cho trang chủ
        $verifiedBrandsCount = Brand::query()
            ->where('approved', true)
            ->whereNull('deleted_at')
            ->whereExists(function ($sub) {
                $sub->selectRaw(1)
                    ->from('users')
                    ->whereColumn('users.id', 'brands.user_id');
            })
            ->count();

        $activeCouponsCount = Coupon::query()
            ->whereNull('deleted_at')
            ->whereHas('campaign', fn ($q) => $q
                ->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active'))
            )
            ->whereHas('campaign.brand', function ($q) {
                $q->where('approved', true)
                    ->whereNull('deleted_at')
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('users')
                            ->whereColumn('users.id', 'brands.user_id');
                    });
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->count();

        // Latest Blog Posts - từ Blog do admin tạo, chỉ bài đã xuất bản
        $latestPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('home', [
            'featuredCampaigns' => $featuredCampaigns,
            'hotCoupons' => $hotCoupons,
            'latestPosts' => $latestPosts,
            'popularCategories' => $popularCategories,
            'searchQuery' => $query,
            'categorySlug' => $categorySlug,
            'verifiedBrandsCount' => $verifiedBrandsCount,
            'activeCouponsCount' => $activeCouponsCount,
        ]);
    }

    /**
     * Featured destinations: ưu tiên campaign tạo trong RECENT_DAYS ngày, sắp xếp theo ngày tạo (mới nhất trước).
     * Không dùng view/click. Nếu không có campaign nào trong cửa sổ đó thì lấy campaign cũ mới nhất.
     *
     * @return Collection<int, Campaign>
     */
    protected function resolveFeaturedCampaigns(?string $searchQuery, ?string $categorySlug, Carbon $cutoff): Collection
    {
        $recent = $this->featuredCampaignsBaseQuery($searchQuery, $categorySlug)
            ->where('campaigns.created_at', '>=', $cutoff)
            ->orderByDesc('campaigns.created_at')
            ->limit(150)
            ->get();

        $rows = $recent->isNotEmpty()
            ? $recent
            : $this->featuredCampaignsBaseQuery($searchQuery, $categorySlug)
                ->orderByDesc('campaigns.created_at')
                ->limit(150)
                ->get();

        return $rows
            ->unique(fn (Campaign $c) => $c->brand_id ?? $c->id)
            ->take(80)
            ->values();
    }

    protected function featuredCampaignsBaseQuery(?string $searchQuery, ?string $categorySlug): Builder
    {
        return Campaign::query()
            ->with(['brand'])
            ->whereHas('brand', function ($q) use ($categorySlug) {
                $q->where('approved', true)
                    ->whereNull('deleted_at')
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('users')
                            ->whereColumn('users.id', 'brands.user_id');
                    })
                    ->when($categorySlug, fn ($q2) => $q2->whereHas('category', fn ($c) => $c
                        ->where('slug', $categorySlug)
                        ->orWhere('slug', 'like', "%/{$categorySlug}")
                    ));
            })
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where(function ($qq) use ($searchQuery) {
                    $qq->where('title', 'like', "%{$searchQuery}%")
                        ->orWhereHas('brand', fn ($b) => $b
                            ->where('name', 'like', "%{$searchQuery}%")
                            ->orWhere('slug', 'like', "%{$searchQuery}%")
                        );
                });
            })
            ->when(app()->environment('production'), fn ($q) => $q->where('status', 'active'))
            ->whereNotNull('slug');
    }

    /**
     * Hot coupons: ưu tiên coupon tạo trong RECENT_DAYS ngày, sắp xếp theo ngày tạo (mới nhất trước).
     * Không dùng view/click. Nếu không có coupon nào trong cửa sổ đó thì lấy coupon cũ mới nhất.
     *
     * @return Collection<int, Coupon>
     */
    protected function resolveHotCoupons(?string $searchQuery, ?string $categorySlug, Carbon $cutoff): Collection
    {
        $recent = $this->hotCouponsBaseQuery($searchQuery, $categorySlug)
            ->where('coupons.created_at', '>=', $cutoff)
            ->orderByDesc('coupons.created_at')
            ->limit(12)
            ->get();

        if ($recent->isNotEmpty()) {
            return $recent->values();
        }

        return $this->hotCouponsBaseQuery($searchQuery, $categorySlug)
            ->orderByDesc('coupons.created_at')
            ->limit(12)
            ->get();
    }

    protected function hotCouponsBaseQuery(?string $searchQuery, ?string $categorySlug): Builder
    {
        return Coupon::query()
            ->with(['campaign.brand'])
            ->whereNull('deleted_at')
            ->whereHas('campaign', fn ($q) => $q
                ->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active'))
            )
            ->whereHas('campaign.brand', function ($q) use ($categorySlug) {
                $q->where('approved', true)
                    ->whereNull('deleted_at')
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('users')
                            ->whereColumn('users.id', 'brands.user_id');
                    })
                    ->when($categorySlug, fn ($q2) => $q2->whereHas('category', fn ($c) => $c
                        ->where('slug', $categorySlug)
                        ->orWhere('slug', 'like', "%/{$categorySlug}")
                    ));
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where(function ($qq) use ($searchQuery) {
                    $qq->where('offer', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%")
                        ->orWhereHas('campaign', fn ($c) => $c->where('title', 'like', "%{$searchQuery}%"))
                        ->orWhereHas('campaign.brand', fn ($b) => $b->where('name', 'like', "%{$searchQuery}%"));
                });
            });
    }
}
