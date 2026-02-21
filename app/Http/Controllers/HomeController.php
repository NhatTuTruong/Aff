<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Coupon;
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

        // Featured Stores: brand approved, có campaign (active trên production), sắp xếp theo lượt tương tác,
        // chỉ hiển thị top stores có tương tác cao nhất, tên trùng thì chỉ hiển thị 1 (đã xóa mềm tự loại)
        $brands = Brand::query()
            ->selectRaw('brands.*, (
                (SELECT COUNT(*) FROM clicks WHERE campaign_id IN (SELECT id FROM campaigns WHERE brand_id = brands.id AND campaigns.deleted_at IS NULL) AND (clicks.deleted_at IS NULL)) +
                (SELECT COUNT(*) FROM page_views WHERE campaign_id IN (SELECT id FROM campaigns WHERE brand_id = brands.id AND campaigns.deleted_at IS NULL))
            ) as total_interactions')
            ->where('approved', true)
            ->whereHas('campaigns', fn ($q) => $q->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active')))
            ->when($query, fn ($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            }))
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)->orWhere('slug', 'like', "%/{$categorySlug}")))
            ->with(['category', 'campaigns' => fn ($q) => $q->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active'))->orderByDesc('created_at')->limit(1)])
            ->orderByDesc('total_interactions')
            ->orderBy('name')
            ->limit(80)
            ->get()
            ->unique('name')
            ->take(32)
            ->values();

        // Hot Coupons: chỉ deal còn hiện hữu (campaign + brand chưa xóa mềm, active trên production), sắp xếp theo lượt tương tác
        $hotCoupons = Coupon::query()
            ->with(['campaign.brand'])
            ->whereHas('campaign', fn ($q) => $q->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active')))
            ->whereHas('campaign.brand', fn ($q) => $q->where('approved', true))
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->orderByRaw('
                (SELECT COUNT(*) FROM clicks WHERE clicks.campaign_id = coupons.campaign_id AND clicks.deleted_at IS NULL) +
                (SELECT COUNT(*) FROM page_views WHERE page_views.campaign_id = coupons.campaign_id) DESC
            ')
            ->limit(12)
            ->get();

        // Latest Blog Posts - từ Blog do admin tạo, chỉ bài đã xuất bản
        $latestPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('home', [
            'brands' => $brands,
            'hotCoupons' => $hotCoupons,
            'latestPosts' => $latestPosts,
            'popularCategories' => $popularCategories,
            'searchQuery' => $query,
            'categorySlug' => $categorySlug,
        ]);
    }
}
