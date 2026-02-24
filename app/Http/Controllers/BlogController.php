<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        $category = $request->string('category')->toString();

        $posts = Blog::query()
            ->where('is_published', true)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($qq) use ($query) {
                    $qq->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Blog::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('blog.index', [
            'posts' => $posts,
            'searchQuery' => $query,
            'selectedCategory' => $category,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Blog::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('views_count');

        // Deal bên phải: từ brand thuộc cùng danh mục bài viết (tất cả user), hot và mới
        $sidebarDeals = Coupon::query()
            ->with(['campaign.brand'])
            ->whereHas('campaign', fn ($q) => $q->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active')))
            ->whereHas('campaign.brand', function ($q) use ($post) {
                $q->where('approved', true);
                if (filled($post->category)) {
                    $q->whereHas('category', fn ($c) => $c->where('name', $post->category));
                }
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->orderByRaw('
                (SELECT COUNT(*) FROM clicks WHERE clicks.campaign_id = coupons.campaign_id AND clicks.deleted_at IS NULL) +
                (SELECT COUNT(*) FROM page_views WHERE page_views.campaign_id = coupons.campaign_id) DESC,
                coupons.created_at DESC
            ')
            ->limit(10)
            ->get();

        // Blog liên quan: cùng category hoặc blog mới nhất (trừ bài hiện tại)
        $relatedBlogs = Blog::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category, function ($q) use ($post) {
                $q->where('category', $post->category);
            })
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        // Nếu không đủ 4 bài cùng category, bổ sung bằng blog mới nhất
        if ($relatedBlogs->count() < 4) {
            $additionalBlogs = Blog::query()
                ->where('is_published', true)
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->orderByDesc('created_at')
                ->limit(4 - $relatedBlogs->count())
                ->get();
            $relatedBlogs = $relatedBlogs->merge($additionalBlogs);
        }

        return view('blog.show', [
            'post' => $post,
            'sidebarDeals' => $sidebarDeals,
            'relatedBlogs' => $relatedBlogs,
        ]);
    }
}
