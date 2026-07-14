<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $recentPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->limit(40)
            ->get();

        $featuredPost = $recentPosts->first();
        $heroCarouselPosts = $recentPosts->take(5)->values();
        $heroSidebarPosts = $recentPosts->slice(1, 4)->values();

        $trendingPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $categories = Blog::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $postsByCategory = [];
        foreach ($categories as $category) {
            $postsByCategory[$category] = Blog::query()
                ->where('is_published', true)
                ->where('category', $category)
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();
        }

        if ($postsByCategory === [] && $recentPosts->isNotEmpty()) {
            $postsByCategory['Latest Articles'] = $recentPosts->take(12);
        }

        $popularPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('home', [
            'featuredPost' => $featuredPost,
            'heroCarouselPosts' => $heroCarouselPosts,
            'heroSidebarPosts' => $heroSidebarPosts,
            'trendingPosts' => $trendingPosts,
            'postsByCategory' => $postsByCategory,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
            'popularPosts' => $popularPosts,
        ]);
    }
}
