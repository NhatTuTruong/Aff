@extends('layouts.app')

@section('title', 'Review Archive - ' . config('app.name'))
@section('description', 'Browse reviews, guides, and expert articles.')

@push('styles')
@include('partials.styles.blog-archive')
@endpush

@section('content')
@php
    use Illuminate\Support\Str;

    $categoryColors = ['#0d9488', '#ea580c', '#7c3aed', '#db2777', '#ca8a04', '#2563eb'];
    $colorFor = function (string $label, int $index = 0) use ($categoryColors): string {
        return $categoryColors[(crc32($label) + $index) % count($categoryColors)];
    };
    $listPosts = $posts->getCollection();
    if ($featuredPost && $listPosts->isNotEmpty() && $listPosts->first()->id === $featuredPost->id) {
        $listPosts = $listPosts->slice(1);
    }
@endphp

<div class="blog-archive">
    <div class="ba-shell">
        <header class="ba-header">
            <div class="ba-header-main">
                <p class="ba-kicker">Archive</p>
                <h1 class="ba-title">All Articles</h1>
                @if($selectedCategory)
                    <p class="ba-subtitle">Category: <strong>{{ $selectedCategory }}</strong></p>
                @elseif($searchQuery)
                    <p class="ba-subtitle">Results for: <strong>{{ $searchQuery }}</strong></p>
                @else
                    <p class="ba-subtitle">Reviews, guides, and shopping insights updated regularly.</p>
                @endif
            </div>
            <form action="{{ route('blog.index') }}" method="get" class="ba-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
                <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search articles…" autocomplete="off">
                @if(!empty($selectedCategory))
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <button type="submit">Search</button>
            </form>
        </header>

        @if($categories->isNotEmpty())
            @php
                $baseParams = [];
                if (!empty($searchQuery)) {
                    $baseParams['q'] = $searchQuery;
                }
            @endphp
            <nav class="ba-cats" aria-label="Categories">
                <a href="{{ route('blog.index', $baseParams) }}" class="ba-cat{{ empty($selectedCategory) ? ' is-active' : '' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}"
                       class="ba-cat{{ ($selectedCategory ?? '') === $cat ? ' is-active' : '' }}"
                       style="--ba-cat-color: {{ $colorFor($cat) }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </nav>
        @endif

        @if($featuredPost)
            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="ba-spotlight">
                <div class="ba-spotlight-media">
                    <img src="{{ $featuredPost->featured_image_url }}" alt="{{ $featuredPost->title }}" loading="eager">
                </div>
                <div class="ba-spotlight-body">
                    <span class="ba-spotlight-label">Featured</span>
                    @if($featuredPost->category)
                        <span class="ba-spotlight-tag" style="background: {{ $colorFor($featuredPost->category) }}">{{ strtoupper($featuredPost->category) }}</span>
                    @endif
                    <h2 class="ba-spotlight-title">{{ $featuredPost->title }}</h2>
                    @if($featuredPost->content)
                        <p class="ba-spotlight-excerpt">{{ Str::limit(trim(strip_tags($featuredPost->content)), 200) }}</p>
                    @endif
                    <p class="ba-spotlight-meta">{{ $featuredPost->created_at?->format('F j, Y') }}</p>
                </div>
            </a>
        @endif

        @if($listPosts->count() > 0)
            <div class="ba-feed">
                @foreach($listPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="ba-feed-item">
                        <div class="ba-feed-thumb">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                        </div>
                        <div class="ba-feed-body">
                            <div class="ba-feed-tags">
                                @if($post->category)
                                    <span class="ba-feed-tag" style="--ba-tag-color: {{ $colorFor($post->category, $loop->index) }}">{{ strtoupper($post->category) }}</span>
                                @endif
                            </div>
                            <h3 class="ba-feed-title">{{ $post->title }}</h3>
                            @if($post->content)
                                <p class="ba-feed-excerpt">{{ Str::limit(trim(strip_tags($post->content)), 140) }}</p>
                            @endif
                            <p class="ba-feed-meta">
                                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Admin</span>
                                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg> {{ $post->created_at?->format('F j, Y') }}</span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="ba-pagination">
                {{ $posts->links('vendor.pagination.simple') }}
            </div>
        @elseif(! $featuredPost)
            <div class="ba-empty">
                <strong>No articles found</strong>
                <p>Try another keyword or browse all categories.</p>
            </div>
        @endif
    </div>
</div>
@endsection
