@extends('layouts.app')

@section('title', config('app.name') . ' - Reviews, Guides & Deals')
@section('description', 'Expert reviews, shopping guides, and deal insights — updated daily.')

@push('styles')
@include('partials.styles.home-magazine')
@endpush

@if($heroCarouselPosts->isNotEmpty())
@push('head')
<link rel="preload" as="image" href="{{ $heroCarouselPosts->first()->featured_image_url }}" fetchpriority="high">
@endpush
@endif

@section('content')
@php
    use Illuminate\Support\Str;

    $categoryColors = ['#e91e8c', '#00b4a6', '#ff5c35', '#7c3aed', '#f59e0b', '#3b82f6'];
    $colorFor = function (string $label, int $index = 0) use ($categoryColors): string {
        $hash = crc32($label);
        return $categoryColors[($hash + $index) % count($categoryColors)];
    };
    $excerpt = function ($post, int $limit = 110): string {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) ($post->content ?? '')))), $limit);
    };
@endphp

<div class="home-magazine">
    @if($heroCarouselPosts->isNotEmpty())
    <section class="hm-hero">
        <div class="hm-shell hm-hero-grid">
            <div class="hm-hero-carousel" id="hm-hero-carousel" data-hm-carousel>
                <div class="hm-hero-carousel-track" id="hm-hero-carousel-track">
                    @foreach($heroCarouselPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="hm-hero-slide">
                            <div class="hm-hero-main-media">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="800" height="450" @if($loop->first) loading="eager" fetchpriority="high" decoding="async" @else loading="lazy" decoding="async" @endif>
                                <div class="hm-hero-main-overlay"></div>
                            </div>
                            <div class="hm-hero-main-body">
                                @if($post->category)
                                    <span class="hm-tag" style="--hm-tag-color: {{ $colorFor($post->category) }}">{{ strtoupper($post->category) }}</span>
                                @endif
                                <h2 class="hm-hero-main-title">{{ $post->title }}</h2>
                                <p class="hm-hero-main-meta">{{ $post->created_at?->format('F j, Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($heroCarouselPosts->count() > 1)
                <button type="button" class="hm-hero-arrow hm-hero-arrow--prev" data-hm-carousel-prev aria-label="Previous slide">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" class="hm-hero-arrow hm-hero-arrow--next" data-hm-carousel-next aria-label="Next slide">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>
                <div class="hm-hero-dots" data-hm-carousel-dots aria-hidden="true">
                    @foreach($heroCarouselPosts as $post)
                        <button type="button" class="hm-hero-dot{{ $loop->first ? ' is-active' : '' }}" data-slide="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
                @endif
            </div>

            @if($heroSidebarPosts->isNotEmpty())
            <aside class="hm-hero-aside" aria-label="More featured articles">
                @foreach($heroSidebarPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="hm-hero-mini">
                        <div class="hm-hero-mini-media">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="220" height="165" loading="lazy" decoding="async">
                        </div>
                        <div class="hm-hero-mini-body">
                            <h3 class="hm-hero-mini-title">{{ $post->title }}</h3>
                            <p class="hm-hero-mini-meta">{{ $post->created_at?->format('M j, Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </aside>
            @endif
        </div>
    </section>
    @endif

    @if($trendingPosts->isNotEmpty())
    <section class="hm-trending" aria-label="Trending articles">
        <div class="hm-shell hm-trending-inner">
            <span class="hm-trending-badge">
                @include('partials.hm-widget-icon', ['name' => 'trending'])
                Trending
            </span>
            <div class="hm-trending-track">
                <div class="hm-trending-list">
                    @foreach($trendingPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="hm-trending-link">{{ $post->title }}</a>
                    @endforeach
                    @foreach($trendingPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="hm-trending-link" aria-hidden="true">{{ $post->title }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="hm-layout">
        <div class="hm-main">
            @forelse($postsByCategory as $categoryName => $catPosts)
                @if($catPosts->isEmpty())
                    @continue
                @endif
                @php
                    $layout = $loop->index % 6;
                    $catColor = $colorFor($categoryName);
                @endphp
                <section class="hm-cat-section hm-cat-section--layout-{{ $layout }}">
                    <header class="hm-cat-head" style="--hm-cat-accent: {{ $catColor }}">
                        <h2 class="hm-cat-title">{{ strtoupper($categoryName) }}</h2>
                        <a href="{{ route('blog.index', ['category' => $categoryName]) }}" class="hm-cat-more">View all →</a>
                    </header>

                    <div class="hm-cat-section__desktop">
                    @if($layout === 0)
                        {{-- 3-column cards --}}
                        <div class="hm-grid hm-grid--3">
                            @foreach($catPosts->take(3) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-card hm-card--vertical">
                                    <div class="hm-card-media">
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="220" height="165" loading="lazy" decoding="async">
                                        <span class="hm-tag hm-tag--on-image" style="--hm-tag-color: {{ $colorFor($post->category ?? $categoryName, $loop->index) }}">{{ strtoupper($post->category ?? $categoryName) }}</span>
                                    </div>
                                    <div class="hm-card-body">
                                        <h3 class="hm-card-title">{{ $post->title }}</h3>
                                        <p class="hm-card-meta">{{ $post->created_at?->format('F j, Y') }}</p>
                                        @if($excerpt($post))<p class="hm-card-excerpt">{{ $excerpt($post) }}</p>@endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if($catPosts->count() > 3)
                        <div class="hm-grid hm-grid--2 hm-grid--secondary">
                            @foreach($catPosts->slice(3, 2) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-card hm-card--horizontal">
                                    <div class="hm-card-media"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <div class="hm-card-body">
                                        <h3 class="hm-card-title">{{ $post->title }}</h3>
                                        <p class="hm-card-meta">{{ $post->created_at?->format('F j, Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @endif

                    @elseif($layout === 1)
                        {{-- Featured + list --}}
                        @php $featured = $catPosts->first(); $listPosts = $catPosts->slice(1, 4); @endphp
                        <div class="hm-split">
                            @if($featured)
                            <a href="{{ route('blog.show', $featured->slug) }}" class="hm-card hm-card--featured">
                                <div class="hm-card-media"><img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}" loading="lazy" decoding="async"><div class="hm-card-overlay"></div></div>
                                <div class="hm-card-body hm-card-body--overlay">
                                    <span class="hm-tag" style="--hm-tag-color: {{ $catColor }}">{{ strtoupper($categoryName) }}</span>
                                    <h3 class="hm-card-title hm-card-title--lg">{{ $featured->title }}</h3>
                                    <p class="hm-card-meta">{{ $featured->created_at?->format('F j, Y') }}</p>
                                </div>
                            </a>
                            @endif
                            <div class="hm-list">
                                @foreach($listPosts as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-list-item">
                                    <div class="hm-list-media"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <div class="hm-list-body">
                                        <h3 class="hm-list-title">{{ $post->title }}</h3>
                                        <p class="hm-list-meta">{{ $post->created_at?->format('M j, Y') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                    @elseif($layout === 2)
                        {{-- 4-column compact --}}
                        <div class="hm-grid hm-grid--4">
                            @foreach($catPosts->take(4) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-card hm-card--compact">
                                    <div class="hm-card-media hm-card-media--square"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <div class="hm-card-body">
                                        <h3 class="hm-card-title hm-card-title--sm">{{ $post->title }}</h3>
                                        <p class="hm-card-meta">{{ $post->created_at?->format('M j, Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($layout === 3)
                        {{-- Full-width banner + 4 mini --}}
                        @php $banner = $catPosts->first(); $minis = $catPosts->slice(1, 4); @endphp
                        @if($banner)
                        <a href="{{ route('blog.show', $banner->slug) }}" class="hm-banner-card">
                            <div class="hm-banner-media"><img src="{{ $banner->featured_image_url }}" alt="{{ $banner->title }}" loading="lazy" decoding="async"><div class="hm-card-overlay"></div></div>
                            <div class="hm-banner-body">
                                <span class="hm-tag" style="--hm-tag-color: {{ $catColor }}">{{ strtoupper($categoryName) }}</span>
                                <h3 class="hm-banner-title">{{ $banner->title }}</h3>
                                <p class="hm-card-meta">{{ $banner->created_at?->format('F j, Y') }} · @if($excerpt($banner, 80)){{ $excerpt($banner, 80) }}@endif</p>
                            </div>
                        </a>
                        @endif
                        @if($minis->isNotEmpty())
                        <div class="hm-grid hm-grid--4 hm-grid--minis">
                            @foreach($minis as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-mini-card">
                                    <div class="hm-mini-card-media"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <h4 class="hm-mini-card-title">{{ $post->title }}</h4>
                                </a>
                            @endforeach
                        </div>
                        @endif

                    @elseif($layout === 4)
                        {{-- Masonry: 2 large + 3 small --}}
                        <div class="hm-masonry">
                            @foreach($catPosts->take(2) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-card hm-card--vertical hm-masonry-large">
                                    <div class="hm-card-media hm-card-media--tall"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <div class="hm-card-body">
                                        <h3 class="hm-card-title">{{ $post->title }}</h3>
                                        <p class="hm-card-meta">{{ $post->created_at?->format('F j, Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                            <div class="hm-masonry-stack">
                                @foreach($catPosts->slice(2, 3) as $post)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hm-list-item hm-list-item--dense">
                                        <div class="hm-list-media"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                        <div class="hm-list-body">
                                            <h3 class="hm-list-title">{{ $post->title }}</h3>
                                            <p class="hm-list-meta">{{ $post->created_at?->format('M j, Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    @else
                        {{-- Numbered rows --}}
                        <div class="hm-numbered-list">
                            @foreach($catPosts->take(5) as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-numbered-item">
                                    <span class="hm-numbered-index">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="hm-numbered-media"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async"></div>
                                    <div class="hm-numbered-body">
                                        <span class="hm-tag hm-tag--ghost" style="--hm-tag-color: {{ $colorFor($post->category ?? $categoryName) }}">{{ strtoupper($post->category ?? $categoryName) }}</span>
                                        <h3 class="hm-numbered-title">{{ $post->title }}</h3>
                                        <p class="hm-numbered-meta">{{ $post->created_at?->format('F j, Y') }}@if($excerpt($post, 70)) · {{ $excerpt($post, 70) }}@endif</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    </div>

                    <div class="hm-cat-carousel" data-hm-carousel>
                        <div class="hm-cat-carousel-track">
                            @foreach($catPosts as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hm-cat-slide">
                                    <div class="hm-hero-main-media">
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="220" height="165" loading="lazy" decoding="async">
                                        <div class="hm-hero-main-overlay"></div>
                                    </div>
                                    <div class="hm-hero-main-body">
                                        <span class="hm-tag" style="--hm-tag-color: {{ $colorFor($post->category ?? $categoryName, $loop->index) }}">{{ strtoupper($post->category ?? $categoryName) }}</span>
                                        <h3 class="hm-hero-main-title">{{ $post->title }}</h3>
                                        <p class="hm-hero-main-meta">{{ $post->created_at?->format('F j, Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if($catPosts->count() > 1)
                        <button type="button" class="hm-hero-arrow hm-hero-arrow--prev" data-hm-carousel-prev aria-label="Previous slide">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="hm-hero-arrow hm-hero-arrow--next" data-hm-carousel-next aria-label="Next slide">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                        <div class="hm-hero-dots" data-hm-carousel-dots aria-hidden="true">
                            @foreach($catPosts as $post)
                                <button type="button" class="hm-hero-dot{{ $loop->first ? ' is-active' : '' }}" data-slide="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </section>
            @empty
                <section class="hm-empty">
                    <h2>No articles yet</h2>
                    <p>Check back soon for fresh reviews and guides.</p>
                    <a href="{{ route('blog.index') }}" class="hm-empty-link">Browse blog</a>
                </section>
            @endforelse
        </div>

        <aside class="hm-sidebar" aria-label="Sidebar">
            @if($categories->isNotEmpty())
            <div class="hm-widget">
                <h3 class="hm-widget-title">
                    @include('partials.hm-widget-icon', ['name' => 'categories'])
                    Categories
                </h3>
                <nav class="hm-cat-nav">
                    @foreach($categories as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat]) }}" class="hm-cat-nav-item">
                            <span class="hm-cat-nav-dot" style="background: {{ $colorFor($cat) }}"></span>
                            {{ $cat }}
                        </a>
                    @endforeach
                </nav>
            </div>
            @endif

            @if($trendingPosts->isNotEmpty())
            <div class="hm-widget">
                <h3 class="hm-widget-title">
                    @include('partials.hm-widget-icon', ['name' => 'trending-posts'])
                    Trending Posts
                </h3>
                <ol class="hm-trending-list-widget">
                    @foreach($trendingPosts->take(6) as $post)
                        <li>
                            <a href="{{ route('blog.show', $post->slug) }}" class="hm-trending-widget-item">
                                <span class="hm-trending-widget-thumb"><img src="{{ $post->featured_image_url }}" alt="" loading="lazy" decoding="async"></span>
                                <span class="hm-trending-widget-text">
                                    <span class="hm-trending-widget-title">{{ $post->title }}</span>
                                    <span class="hm-trending-widget-meta">{{ $post->created_at?->format('M j, Y') }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
            @endif

            @if($recentPosts->isNotEmpty())
            <div class="hm-widget">
                <h3 class="hm-widget-title">
                    @include('partials.hm-widget-icon', ['name' => 'latest'])
                    Latest
                </h3>
                <ul class="hm-latest-widget">
                    @foreach($recentPosts->take(6) as $post)
                        <li><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
        </aside>
    </div>

    @if(isset($popularPosts) && $popularPosts->isNotEmpty())
    <section class="hm-popular">
        <div class="hm-shell">
            <header class="hm-popular-head">
                <h2 class="hm-popular-title">
                    @include('partials.hm-widget-icon', ['name' => 'popular'])
                    Popular Posts
                </h2>
            </header>
            <div class="hm-popular-grid">
                @foreach($popularPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="hm-popular-card">
                        <div class="hm-popular-media">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="220" height="165" loading="lazy" decoding="async">
                        </div>
                        <div class="hm-popular-body">
                            @if($post->category)
                                <span class="hm-popular-tag" style="--hm-tag-color: {{ $colorFor($post->category, $loop->index) }}">{{ strtoupper($post->category) }}</span>
                            @endif
                            <h3 class="hm-popular-name">{{ $post->title }}</h3>
                            <p class="hm-popular-meta">
                                <span class="hm-popular-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Admin
                                </span>
                                <span class="hm-popular-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                                    {{ $post->created_at?->format('F j, Y') }}
                                </span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    function initHmCarousel(carousel) {
        var track = carousel.querySelector('.hm-hero-carousel-track, .hm-cat-carousel-track');
        var prev = carousel.querySelector('[data-hm-carousel-prev]');
        var next = carousel.querySelector('[data-hm-carousel-next]');
        var dotsWrap = carousel.querySelector('[data-hm-carousel-dots]');
        if (!track) return;

        var slides = track.querySelectorAll('.hm-hero-slide, .hm-cat-slide');
        var dots = dotsWrap ? dotsWrap.querySelectorAll('.hm-hero-dot') : [];
        var total = slides.length;
        if (total <= 1) return;

        var index = 0;
        var timer = null;
        var delay = 3000;
        var isHovered = false;
        var isPaused = false;

        function goTo(i) {
            index = (i % total + total) % total;
            track.style.transform = 'translate3d(' + (-index * 100) + '%,0,0)';
            for (var j = 0; j < dots.length; j++) {
                dots[j].classList.toggle('is-active', j === index);
            }
        }

        function nextSlide() { goTo(index + 1); }
        function prevSlide() { goTo(index - 1); }

        function startAuto() {
            if (isPaused || isHovered) return;
            stopAuto();
            timer = setInterval(function () {
                if (document.hidden || isHovered || isPaused) return;
                nextSlide();
            }, delay);
        }

        function stopAuto() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        function resetAuto() {
            stopAuto();
            startAuto();
        }

        if (prev) prev.addEventListener('click', function () { prevSlide(); resetAuto(); });
        if (next) next.addEventListener('click', function () { nextSlide(); resetAuto(); });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var i = parseInt(dot.getAttribute('data-slide') || '0', 10);
                if (!isNaN(i)) { goTo(i); resetAuto(); }
            });
        });

        carousel.addEventListener('mouseenter', function () {
            isHovered = true;
            stopAuto();
        });
        carousel.addEventListener('mouseleave', function () {
            isHovered = false;
            startAuto();
        });
        document.addEventListener('visibilitychange', function () {
            isPaused = document.hidden;
            if (isPaused) stopAuto();
            else startAuto();
        });

        requestAnimationFrame(function () {
            goTo(0);
            startAuto();
        });
    }

    function bootCarousels() {
        document.querySelectorAll('[data-hm-carousel]').forEach(initHmCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootCarousels, { once: true });
    } else {
        requestAnimationFrame(bootCarousels);
    }
})();
</script>
@endpush
