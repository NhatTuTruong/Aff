@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Discover checked coupon codes, current promotions, and straightforward store guides. Updated regularly.')

@push('styles')
    @include('partials.blog-index-styles')
@endpush

@section('content')
@php
    $baseParams = [];
    if (!empty($searchQuery)) {
        $baseParams['q'] = $searchQuery;
    }
@endphp

<div class="blg-index">
    <header class="blg-top">
        <div class="blg-wrap">
            <p class="blg-top__label">Editorial</p>
            <h1 class="blg-top__title font-heading">Blog</h1>
            <p class="blg-top__desc">Practical reads on saving, store updates, and how we evaluate deals — filter by topic or search for a specific brand.</p>

            <div class="blg-toolbar">
                <form action="{{ route('blog.index') }}" method="get" class="blg-search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search articles…" autocomplete="off">
                    @if(!empty($selectedCategory))
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif
                    <button type="submit">Search</button>
                </form>
                @if($posts->total() > 0)
                    <span class="blg-count">{{ $posts->total() }} {{ Str::plural('article', $posts->total()) }}</span>
                @endif
            </div>

            @if(isset($categories) && $categories->count() > 0)
                <nav class="blg-tabs" aria-label="Filter by category">
                    <a class="blg-tab {{ empty($selectedCategory) ? 'blg-tab--active' : '' }}" href="{{ route('blog.index', $baseParams) }}">All topics</a>
                    @foreach($categories as $cat)
                        <a class="blg-tab {{ ($selectedCategory ?? '') === $cat ? 'blg-tab--active' : '' }}"
                           href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>
    </header>

    <div class="blg-body">
        <div class="blg-wrap blg-layout">
            @if(isset($categories) && $categories->count() > 0)
                <aside class="blg-sidebar" aria-label="Categories">
                    <div class="blg-side-panel">
                        <p class="blg-side-panel__title">Browse topics</p>
                        <ul class="blg-side-nav">
                            <li>
                                <a href="{{ route('blog.index', $baseParams) }}"
                                   class="{{ empty($selectedCategory) ? 'blg-side-nav--active' : '' }}">All articles</a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}"
                                       class="{{ ($selectedCategory ?? '') === $cat ? 'blg-side-nav--active' : '' }}">{{ $cat }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <p class="blg-side-tip">Pick a category to narrow the feed, or use search to find a store or offer name.</p>
                    </div>
                </aside>
            @endif

            <div class="blg-feed">
                @if($posts->count() > 0)
                    @php $featured = $posts->first(); @endphp

                    <p class="blg-feed__label">{{ $posts->onFirstPage() ? 'Latest pick' : 'On this page' }}</p>
                    <a href="{{ route('blog.show', $featured->slug) }}" class="blg-feature">
                        <div class="blg-feature__media">
                            <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}" loading="eager">
                            @if($posts->onFirstPage())
                                <span class="blg-feature__badge">Featured</span>
                            @endif
                        </div>
                        <div class="blg-feature__body">
                            <div class="blg-feature__meta">
                                @if(!empty($featured->category))
                                    <span class="blg-feature__cat">{{ $featured->category }}</span>
                                @endif
                                <time datetime="{{ $featured->created_at?->toDateString() }}">{{ $featured->created_at?->format('d M Y') }}</time>
                            </div>
                            <h2 class="blg-feature__title">{{ $featured->title }}</h2>
                            @if($featured->content)
                                <p class="blg-feature__excerpt">{{ Str::limit(trim(strip_tags($featured->content)), 260) }}</p>
                            @endif
                            <span class="blg-feature__link">Read full story →</span>
                        </div>
                    </a>

                    @if($posts->count() > 1)
                        <p class="blg-feed__label blg-feed__label--spaced">More articles</p>
                        <div class="blg-list">
                            @foreach($posts->skip(1) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="blg-row">
                                    <div class="blg-row__thumb">
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                    </div>
                                    <div class="blg-row__body">
                                        <div class="blg-row__top">
                                            @if(!empty($post->category))
                                                <span class="blg-row__cat">{{ $post->category }}</span>
                                            @else
                                                <span class="blg-row__cat">News</span>
                                            @endif
                                            <span class="blg-row__date">{{ $post->created_at?->format('d M Y') }}</span>
                                        </div>
                                        <h2 class="blg-row__title">{{ $post->title }}</h2>
                                        @if($post->content)
                                            <p class="blg-row__excerpt">{{ Str::limit(trim(strip_tags($post->content)), 140) }}</p>
                                        @endif
                                        <span class="blg-row__more">Continue reading</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="blg-pagination">
                        {{ $posts->links('vendor.pagination.simple') }}
                    </div>
                @else
                    <div class="blg-empty">
                        <p class="blg-empty__title">No posts found</p>
                        <p class="blg-empty__text">
                            @if(!empty($searchQuery) || !empty($selectedCategory))
                                Try another keyword or browse all topics.
                            @else
                                New articles will appear here once they are published.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
