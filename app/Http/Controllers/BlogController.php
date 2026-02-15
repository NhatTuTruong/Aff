<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Campaign::query()
            ->with('brand')
            ->whereHas('brand', fn ($q) => $q->where('approved', true))
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('blog.index', ['posts' => $posts]);
    }
}
