<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Coupon;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('blog.index', ['posts' => $posts]);
    }

    public function show(string $slug): View
    {
        $post = Blog::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

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
