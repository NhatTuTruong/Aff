@extends('layouts.app')

@section('title', 'Savings Journal - ' . config('app.name'))
@section('description', 'Insights, coupon updates, and practical saving tactics.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('partials.peel-sticker-styles')
@include('partials.styles.public-modern-base')
@include('partials.styles.blog-modern')
@endpush

@section('content')
<div class="blog-page">
    <header class="bp-hero">
        <div class="bp-shell">
            <div class="bp-hero-grid">
                <div class="bp-hero-left">
                    <span class="bp-hero-kicker">Savings Journal</span>
                    <h1 class="font-heading">Fresh reads for <span>smarter shopping</span></h1>
                    <p class="bp-hero-lead">Actionable deal breakdowns, shopping tactics, and weekly updates to help you save with less guesswork.</p>
                    <div class="bp-hero-stats">
                        <div class="bp-hero-stat">
                            <span class="bp-hero-stat-num">{{ $posts->total() }}</span>
                            <span class="bp-hero-stat-label">Articles</span>
                        </div>
                        <div class="bp-hero-stat">
                            <span class="bp-hero-stat-num">{{ $categories->count() ?? '—' }}</span>
                            <span class="bp-hero-stat-label">Categories</span>
                        </div>
                        <div class="bp-hero-stat">
                            <span class="bp-hero-stat-num">Weekly</span>
                            <span class="bp-hero-stat-label">Updates</span>
                        </div>
                    </div>
                </div>
                <div class="bp-hero-right">
                    <div class="bp-search-card">
                        <form action="{{ route('blog.index') }}" method="get" class="bp-search-form">
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
                        <nav class="bp-chips" aria-label="Blog categories">
                            <a class="bp-chip {{ empty($selectedCategory) ? 'bp-chip--on' : '' }}" href="{{ route('blog.index', $baseParams) }}">All</a>
                            @foreach($categories as $cat)
                                <a class="bp-chip {{ ($selectedCategory ?? '') === $cat ? 'bp-chip--on' : '' }}"
                                   href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="bp-main">
        <div class="bp-shell">
            @if($posts->count() > 0)
                <header class="bp-section-head">
                    <div>
                        <p class="bp-section-eyebrow">Latest stories</p>
                        <h2 class="bp-section-title">Fresh reads for smarter shopping</h2>
                    </div>
                    <p class="bp-section-desc">Browse editor picks, category filters, and quick summaries before you dive in.</p>
                </header>
                <div class="bp-grid">
                    @foreach($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="bp-card {{ $loop->first ? 'bp-card--feature' : '' }}">
                            <div class="bp-card-media">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                @if($loop->first)
                                    <span class="bp-card-badge">Editor's pick</span>
                                @endif
                                <span class="bp-card-date">{{ $post->created_at?->format('d M Y') }}</span>
                            </div>
                            <div class="bp-card-body">
                                @unless($loop->first)
                                    <div class="bp-card-top">
                                        @if(!empty($post->category))
                                            <span class="bp-card-tag">{{ $post->category }}</span>
                                        @else
                                            <span class="bp-card-tag">Update</span>
                                        @endif
                                        <span class="bp-card-date-inline">{{ $post->created_at?->format('d/m/Y') }}</span>
                                    </div>
                                @endunless
                                <h2 class="bp-card-title">{{ $post->title }}</h2>
                                @if($post->content)
                                    <p class="bp-card-excerpt">{{ Str::limit(trim(strip_tags($post->content)), $loop->first ? 280 : 160) }}</p>
                                @endif
                                <span class="bp-card-cta">Open insight →</span>
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
