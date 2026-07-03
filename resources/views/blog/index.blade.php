@extends('layouts.app')

@section('title', 'Savings Journal - ' . config('app.name'))
@section('description', 'Insights, coupon updates, and practical saving tactics.')

@push('styles')
@include('partials.peel-sticker-styles')
@include('partials.styles.public-modern-base')
@include('partials.styles.blog-modern')
@endpush

@section('content')
<div class="blog-page">
    <header class="bp-hero">
        <div class="bp-shell">
            <span class="bp-kicker">Savings Journal</span>
            <h1 class="font-heading">Fresh reads for smarter shopping</h1>
            <p class="bp-lead">Actionable deal breakdowns, shopping tactics, and weekly updates to help you save with less guesswork.</p>

            <div class="bp-toolbar">
                <div class="bp-search-wrap">
                    <form action="{{ route('blog.index') }}" method="get" class="bp-search-form">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
                        <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search articles…" autocomplete="off">
                        @if(!empty($selectedCategory))
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                        @endif
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="bp-meta-pills">
                    <span class="bp-meta-pill"><strong>{{ $posts->total() }}</strong> articles</span>
                    @if(isset($categories))
                        <span class="bp-meta-pill"><strong>{{ $categories->count() }}</strong> categories</span>
                    @endif
                    <span class="bp-meta-pill">Weekly updates</span>
                </div>
            </div>

            @if(isset($categories) && $categories->count() > 0)
                @php
                    $baseParams = [];
                    if (!empty($searchQuery)) {
                        $baseParams['q'] = $searchQuery;
                    }
                @endphp
                <nav class="bp-tabs" aria-label="Blog categories">
                    <a class="bp-tab {{ empty($selectedCategory) ? 'bp-tab--on' : '' }}" href="{{ route('blog.index', $baseParams) }}">All</a>
                    @foreach($categories as $cat)
                        <a class="bp-tab {{ ($selectedCategory ?? '') === $cat ? 'bp-tab--on' : '' }}"
                           href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>
    </header>

    <div class="bp-main">
        <div class="bp-shell">
            @if($posts->count() > 0)
                <div class="bp-feed">
                    @foreach($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="bp-article {{ $loop->first ? 'bp-article--featured' : '' }}">
                            <div class="bp-article-media">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                @if($loop->first)
                                    <span class="bp-article-badge">Editor's pick</span>
                                @endif
                            </div>
                            <div class="bp-article-body">
                                <div class="bp-article-meta">
                                    @if(!empty($post->category))
                                        <span class="bp-article-cat">{{ $post->category }}</span>
                                    @else
                                        <span class="bp-article-cat">Update</span>
                                    @endif
                                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->format('d M Y') }}</time>
                                </div>
                                <h2 class="bp-article-title">{{ $post->title }}</h2>
                                @if($post->content)
                                    <p class="bp-article-excerpt">{{ Str::limit(trim(strip_tags($post->content)), $loop->first ? 260 : 140) }}</p>
                                @endif
                                <span class="bp-article-cta">Read article →</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="bp-pagination">
                    {{ $posts->links('vendor.pagination.simple') }}
                </div>
            @else
                <div class="bp-empty">
                    <strong>No stories yet</strong>
                    New articles will appear here once they are published. Check back soon.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
