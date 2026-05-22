@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))
@section('description', 'Latest articles, coupon updates and deals.')

@push('styles')
    @include('partials.blog-index-styles')
@endpush

@section('content')
<div class="blg-index">
    <header class="blg-masthead">
        <div class="blg-wrap">
            <div class="blg-masthead__row">
                <div>
                    <p class="blg-masthead__label">Editorial</p>
                    <h1 class="blg-masthead__title font-heading">Blog</h1>
                    <p class="blg-masthead__desc">Guides, store notes, and deal context — written so you spend less time guessing and more time saving.</p>
                </div>
                <form action="{{ route('blog.index') }}" method="get" class="blg-masthead__search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search articles…" autocomplete="off">
                    @if(!empty($selectedCategory))
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif
                    <button type="submit">Search</button>
                </form>
            </div>

            @if(isset($categories) && $categories->count() > 0)
                @php
                    $baseParams = [];
                    if (!empty($searchQuery)) {
                        $baseParams['q'] = $searchQuery;
                    }
                @endphp
                <nav class="blg-tabs" aria-label="Blog categories">
                    <div class="blg-tabs__inner">
                        <a class="blg-tab {{ empty($selectedCategory) ? 'blg-tab--active' : '' }}" href="{{ route('blog.index', $baseParams) }}">All</a>
                        @foreach($categories as $cat)
                            <a class="blg-tab {{ ($selectedCategory ?? '') === $cat ? 'blg-tab--active' : '' }}"
                               href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            @endif
        </div>
    </header>

    <div class="blg-body">
        <div class="blg-wrap">
            @if($posts->count() > 0)
                @php $featured = $posts->first(); @endphp
                <a href="{{ route('blog.show', $featured->slug) }}" class="blg-feature">
                    <div class="blg-feature__media">
                        <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}" loading="eager">
                        <span class="blg-feature__badge">Featured</span>
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
                            <p class="blg-feature__excerpt">{{ Str::limit(trim(strip_tags($featured->content)), 280) }}</p>
                        @endif
                        <span class="blg-feature__cta">Read featured story →</span>
                    </div>
                </a>

                @if($posts->count() > 1)
                    <div class="blg-grid">
                        @foreach($posts->skip(1) as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="blg-card">
                                <div class="blg-card__media">
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                </div>
                                <div class="blg-card__body">
                                    <div class="blg-card__top">
                                        <span class="blg-card__cat">{{ $post->category ?: 'News' }}</span>
                                        <span class="blg-card__date">{{ $post->created_at?->format('d/m/Y') }}</span>
                                    </div>
                                    <h2 class="blg-card__title">{{ $post->title }}</h2>
                                    @if($post->content)
                                        <p class="blg-card__excerpt">{{ Str::limit(trim(strip_tags($post->content)), 120) }}</p>
                                    @endif
                                    <span class="blg-card__link">Read more</span>
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
                    <p class="blg-empty__title">No posts yet</p>
                    <p class="blg-empty__text">New articles will appear here once they are published. Check back soon.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
