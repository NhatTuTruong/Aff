@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Latest articles, coupon updates and deals.')

@push('styles')
<style>
    .container { max-width: 1000px; margin: 0 auto; padding: 0 1.5rem; }
    .page-header {
        padding: 3rem 0 2rem;
        text-align: center;
    }
    .page-header h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .page-header p { color: var(--text-muted); margin-top: 0.5rem; }
    .posts-list { padding: 1rem 0 3rem; }
    .post-card {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border);
        text-decoration: none;
        color: inherit;
        transition: opacity 0.2s;
    }
    .post-card:hover { opacity: 0.9; }
    .post-card:first-child { padding-top: 0; }
    .post-card .thumb {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        background: var(--surface);
        flex-shrink: 0;
    }
    .post-card .thumb-placeholder {
        width: 120px;
        height: 80px;
        border-radius: var(--radius-sm);
        background: var(--surface);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.75rem;
    }
    .post-card .content { flex: 1; min-width: 0; }
    .post-card h2 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 0.35rem;
        line-height: 1.35;
    }
    .post-card .excerpt {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .post-card .meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }
    .pagination-wrap {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    .pagination-wrap nav a, .pagination-wrap nav span {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        margin: 0 0.15rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        text-decoration: none;
        font-size: 0.9rem;
    }
    .pagination-wrap nav a:hover { border-color: var(--accent); color: var(--accent); }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
    <header class="page-header">
        <div class="container">
            <h1 class="font-heading">Blog</h1>
            <p>Latest articles, coupon updates and deals.</p>
        </div>
    </header>

    <div class="posts-list">
        <div class="container">
            @if($posts->count() > 0)
                @foreach($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="post-card">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="thumb" loading="lazy">
                        @else
                            <div class="thumb-placeholder">Blog</div>
                        @endif
                        <div class="content">
                            <h2>{{ $post->title }}</h2>
                            @if($post->content)
                                <p class="excerpt">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                            @endif
                            <p class="meta">{{ $post->created_at?->format('d/m/Y') }}</p>
                        </div>
                    </a>
                @endforeach
                <div class="pagination-wrap">
                    {{ $posts->links('vendor.pagination.simple') }}
                </div>
            @else
                <div class="empty-state">
                    No blog posts available yet. Please check back later!
                </div>
            @endif
        </div>
    </div>
@endsection
