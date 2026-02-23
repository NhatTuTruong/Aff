<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Coupon;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');
        $categorySlug = $request->get('cat');

        // Popular Categories: luôn hiển thị theo list danh mục mặc định (affiliate)
        $names = config('default_categories.names', \App\Models\User::defaultCategoryNames());
        $popularCategories = collect($names)->map(fn ($name) => (object)['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)]);

        // Featured Campaigns (thay cho Featured Stores):
        // - Chiến dịch active (production), brand được duyệt, user của brand chưa bị xóa
        // - Sắp xếp theo tổng lượt click + page view cao nhất
        // - Không lấy dữ liệu đã xóa mềm (SoftDeletes)
        $featuredCampaigns = \App\Models\Campaign::query()
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
            ->when($query, function ($q) use ($query) {
                $q->where(function ($qq) use ($query) {
                    $qq->where('title', 'like', "%{$query}%")
                        ->orWhereHas('brand', fn ($b) => $b
                            ->where('name', 'like', "%{$query}%")
                            ->orWhere('slug', 'like', "%{$query}%")
                        );
                });
            })
            ->when(app()->environment('production'), fn ($q) => $q->where('status', 'active'))
            ->whereNotNull('slug')
            ->orderByRaw('
                (SELECT COUNT(*) FROM clicks WHERE clicks.campaign_id = campaigns.id AND clicks.deleted_at IS NULL) +
                (SELECT COUNT(*) FROM page_views WHERE page_views.campaign_id = campaigns.id)
            DESC')
            ->limit(80)
            ->get()
            ->unique(fn ($c) => $c->brand?->name ?? $c->title)
            ->values();

        // Hot Coupons: chỉ deal còn hiện hữu (campaign + brand chưa xóa mềm, active trên production, user chưa xóa),
        // sắp xếp theo lượt tương tác
        $hotCoupons = Coupon::query()
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
            ->when($query, function ($q) use ($query) {
                $q->where(function ($qq) use ($query) {
                    $qq->where('offer', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('campaign', fn ($c) => $c->where('title', 'like', "%{$query}%"))
                        ->orWhereHas('campaign.brand', fn ($b) => $b->where('name', 'like', "%{$query}%"));
                });
            })
            ->orderByRaw('
                (SELECT COUNT(*) FROM clicks WHERE clicks.campaign_id = coupons.campaign_id AND clicks.deleted_at IS NULL) +
                (SELECT COUNT(*) FROM page_views WHERE page_views.campaign_id = coupons.campaign_id) DESC
            ')
            ->limit(12)
            ->get();

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
            ->limit(6)
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
}
