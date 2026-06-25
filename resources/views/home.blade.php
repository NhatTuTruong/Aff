@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('partials.peel-sticker-styles')
@include('partials.styles.public-modern-base')
@include('partials.styles.home-modern')
@endpush

@section('content')
<div class="home-page">
    <section class="hp-hero">
        <div class="hp-shell hp-hero-grid">
            <div>
                <p class="hp-kicker">Deals you can trust</p>
                <h1 class="font-heading">Save smarter with <span class="hp-hero-accent">curated coupons</span> &amp; honest store picks</h1>
                <p class="hp-hero-lead">Search verified promotions, explore top stores, and read updates from our blog — refreshed often so you never miss a strong offer.</p>
                <p class="hp-trust">We are an independent deal finder. We may earn from qualifying purchases. <a href="{{ url('/affiliate-disclosure') }}">Read our disclosure</a>.</p>
                <form action="{{ url('/') }}" method="get" class="hp-search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search brands, stores, or offers…" autocomplete="off">
                    <button type="submit">Search</button>
                </form>
            </div>
            <aside class="hp-hero-aside" aria-label="Site highlights">
                <div class="hp-aside-deco" aria-hidden="true"></div>
                <div class="hp-aside-slider" id="hp-hero-aside-slider">
                    <div class="hp-aside-track-wrap">
                        <div class="hp-aside-track" id="hp-aside-track">
                            <div class="hp-aside-slide">
                                <div class="hp-aside-slide-inner">
                                    <span class="hp-aside-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"/><path d="M5 19l1 3 1-3 3-1-3-1-1-3-1 3-3 1 3 1z"/></svg>
                                    </span>
                                    <div class="hp-aside-slide-body">
                                        <p class="hp-aside-label">Why shoppers stay</p>
                                        <p class="hp-aside-stat">Curated</p>
                                        <p class="hp-aside-caption">Human-reviewed paths to real savings — fewer dead codes, clearer next steps.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="hp-aside-slide">
                                <div class="hp-aside-slide-inner">
                                    <span class="hp-aside-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
                                    </span>
                                    <div class="hp-aside-slide-body">
                                        <p class="hp-aside-label">Always in motion</p>
                                        <p class="hp-aside-stat">Updated</p>
                                        <p class="hp-aside-caption">We refresh offers and landing details often so you see what still works — not yesterday’s leftovers.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="hp-aside-slide">
                                <div class="hp-aside-slide-inner">
                                    <span class="hp-aside-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                    </span>
                                    <div class="hp-aside-slide-body">
                                        <p class="hp-aside-label">Built for trust</p>
                                        <p class="hp-aside-stat">Verified</p>
                                        <p class="hp-aside-caption">Clear affiliate disclosure, honest pros &amp; cons on store pages, and CTAs that take you straight to the deal.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hp-aside-footer">
                        <p class="hp-aside-counter" id="hp-aside-counter" aria-live="polite">
                            <span id="hp-aside-current">01</span><span class="hp-aside-counter-sep">/</span><span>03</span>
                        </p>
                        <nav class="hp-aside-dots" id="hp-aside-dots" aria-label="Highlight slides">
                            <button type="button" class="hp-aside-dot is-active" aria-label="Slide 1" aria-current="true" data-slide="0"></button>
                            <button type="button" class="hp-aside-dot" aria-label="Slide 2" data-slide="1"></button>
                            <button type="button" class="hp-aside-dot" aria-label="Slide 3" data-slide="2"></button>
                        </nav>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @push('scripts')
    <script>
    (function () {
        var slider = document.getElementById('hp-hero-aside-slider');
        var track = document.getElementById('hp-aside-track');
        var dotsWrap = document.getElementById('hp-aside-dots');
        if (!slider || !track || !dotsWrap) return;

        var dots = dotsWrap.querySelectorAll('.hp-aside-dot');
        var counterCurrent = document.getElementById('hp-aside-current');
        var n = dots.length;
        if (n === 0) return;

        var i = 0;
        var timer = null;
        var delay = 3000;

        function padSlide(num) {
            return String(num + 1).padStart(2, '0');
        }

        function setActive() {
            track.style.transform = 'translateX(' + (-i * 100) + '%)';
            dots.forEach(function (d, j) {
                var on = j === i;
                d.classList.toggle('is-active', on);
                d.setAttribute('aria-current', on ? 'true' : 'false');
            });
            if (counterCurrent) {
                counterCurrent.textContent = padSlide(i);
            }
        }

        function go(to) {
            i = (to % n + n) % n;
            setActive();
        }

        function start() {
            stop();
            timer = setInterval(function () {
                if (document.hidden) return;
                if (slider.matches(':hover')) return;
                go(i + 1);
            }, delay);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        dots.forEach(function (d) {
            d.addEventListener('click', function () {
                var idx = parseInt(d.getAttribute('data-slide') || '0', 10);
                if (!isNaN(idx)) {
                    go(idx);
                    stop();
                    start();
                }
            });
        });

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stop();
            else start();
        });

        setActive();
        start();
    })();
    </script>
    @endpush

    @if(($verifiedBrandsCount ?? 0) > 0 || $hotCoupons->isNotEmpty())
    <section class="hp-stats">
        <div class="hp-shell">
            <div class="hp-stats-row">
                <div class="hp-stat-card">
                    <div class="hp-stat-num">{{ $verifiedBrandsCount ?? 0 }}+</div>
                    <div class="hp-stat-label">Verified brands</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">{{ $activeCouponsCount ?? $hotCoupons->count() }}+</div>
                    <div class="hp-stat-label">Active coupons</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">Editorial</div>
                    <div class="hp-stat-label">Guides &amp; picks</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">Daily</div>
                    <div class="hp-stat-label">Fresh checks</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    @php
        $posts = $latestPosts->values();
        $leftPosts = $posts->slice(0, 2);
        $rightPosts = $posts->slice(0, 6);
    @endphp
    <section class="hp-section hp-section--tint" id="blog">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Editorial</p>
                <h2 class="hp-sec-title">Latest from the blog</h2>
                <p class="hp-sec-desc">Short reads on saving tactics, store notes, and what changed this week.</p>
            </header>

            <div class="hp-blog-modern">
                <div class="hp-blog-col hp-blog-col--left">
                    @foreach($leftPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="hp-blog-featured">
                            <div class="hp-blog-featured-media">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                            </div>
                            <div class="hp-blog-featured-body">
                                <span class="hp-blog-featured-label">Featured</span>
                                <h3 class="hp-blog-featured-title">{{ $post->title }}</h3>
                                <p class="hp-blog-featured-meta">{{ $post->created_at?->format('d M Y') }}</p>
                                <span class="hp-blog-featured-link">Read article <span aria-hidden="true">→</span></span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="hp-blog-col hp-blog-col--right">
                    @if($rightPosts->isNotEmpty())
                        {{-- Desktop: Grid layout (hidden on mobile) --}}
                        <div class="hp-blog-grid" id="hp-blog-desktop-grid">
                            @foreach($rightPosts as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="hp-blog-grid-card">
                                    <div class="hp-blog-grid-media">
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                    </div>
                                    <div class="hp-blog-grid-body">
                                        <h3 class="hp-blog-grid-title">{{ $post->title }}</h3>
                                        <p class="hp-blog-grid-meta">{{ $post->created_at?->format('d M Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Mobile: Carousel layout (only shown on mobile) --}}
            @if($rightPosts->isNotEmpty())
                <div class="hp-blog-mobile-carousel" id="hp-blog-mobile-carousel">
                    <div class="hp-blog-carousel-track" id="hp-blog-carousel-track">
                    @foreach($rightPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="hp-blog-carousel-slide">
                            <div class="hp-blog-grid-card">
                                <div class="hp-blog-grid-media">
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                </div>
                                <div class="hp-blog-grid-body">
                                    <h3 class="hp-blog-grid-title">{{ $post->title }}</h3>
                                    <p class="hp-blog-grid-meta">{{ $post->created_at?->format('d M Y') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    </div>
                    <div class="hp-blog-mobile-controls" id="hp-blog-mobile-controls" aria-label="Blog slides">
                        <button type="button" class="hp-blog-mobile-dot is-active" aria-label="Slide 1" aria-current="true" data-slide="0"></button>
                        <button type="button" class="hp-blog-mobile-dot" aria-label="Slide 2" data-slide="1"></button>
                        <button type="button" class="hp-blog-mobile-dot" aria-label="Slide 3" data-slide="2"></button>
                        <button type="button" class="hp-blog-mobile-dot" aria-label="Slide 4" data-slide="3"></button>
                        <button type="button" class="hp-blog-mobile-dot" aria-label="Slide 5" data-slide="4"></button>
                        <button type="button" class="hp-blog-mobile-dot" aria-label="Slide 6" data-slide="5"></button>
                    </div>
                </div>
            @endif

            <p class="hp-all-posts"><a href="{{ route('blog.index') }}">Browse the full archive</a></p>
        </div>
    </section>
    @endif

    @if($hotCoupons->isNotEmpty())
    <section class="hp-section hp-section--tint" id="coupons">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Limited windows</p>
                <h2 class="hp-sec-title">Hot coupons &amp; standout deals</h2>
                <p class="hp-sec-desc">High-signal picks from brands we track — copy a code or open the offer in one tap.</p>
            </header>
            <p class="hp-disclaimer">Promotions can change or expire at any time. Always confirm at checkout. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.</p>
            @php
                $hotCouponCards = $hotCoupons
                    ->filter(fn ($c) => $c->campaign?->brand)
                    ->take(12)
                    ->values();
            @endphp
            <div class="hp-coupons" id="hp-coupons-grid">
                @foreach($hotCouponCards as $index => $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign->brand; @endphp
                    <article class="coupon-card{{ $coupon->code ? '' : ' coupon-card--no-code' }}{{ $index >= 4 ? ' is-coupon-hidden' : '' }}" data-coupon-card>
                        <div class="coupon-card-strip" aria-hidden="true">
                            <span class="coupon-card-strip-icon">%</span>
                            <span class="coupon-card-strip-label">{{ $coupon->code ? 'Code' : 'Deal' }}</span>
                        </div>
                        <div class="coupon-card-main">
                            <div class="coupon-card-header">
                                <img src="{{ $brand->image_url }}" alt="{{ $campaign->title }}" class="coupon-card-logo" loading="lazy">
                                <div class="coupon-card-brand">{{ $campaign->title }}</div>
                            </div>
                            @if($coupon->offer)
                                <p class="coupon-card-offer">{{ $coupon->offer }}</p>
                            @endif
                            <div class="coupon-card-actions">
                                @if($coupon->code)
                                    <button type="button" class="coupon-card-code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);" title="Click to copy">
                                        <span class="coupon-card-code-label">Code</span>
                                        <span class="coupon-card-code-value">{{ $coupon->code }}</span>
                                        <span class="coupon-card-code-copy">Copy</span>
                                    </button>
                                @endif
                                @if($campaign && $campaign->affiliate_url)
                                    <a href="{{ route('click.redirect', ['slug' => $campaign->slug]) }}" class="coupon-card-cta" target="_blank" rel="noopener">Get deal</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @if($hotCouponCards->count() > 4)
                <div class="hp-coupons-load-more-wrap">
                    <button type="button" class="hp-coupons-load-more" id="hp-coupons-load-more" aria-controls="hp-coupons-grid">
                        See more
                    </button>
                </div>
            @endif
        </div>
    </section>
    @push('scripts')
    <script>
    (function () {
        var btn = document.getElementById('hp-coupons-load-more');
        var cards = document.querySelectorAll('#hp-coupons-grid [data-coupon-card]');
        if (cards.length === 0) return;

        var desktopMq = window.matchMedia('(min-width: 769px)');
        var max = Math.min(12, cards.length);
        var visible = desktopMq.matches ? 6 : 4;

        function getStep() {
            return desktopMq.matches ? 6 : 4;
        }

        function syncCards() {
            cards.forEach(function (card, index) {
                card.classList.toggle('is-coupon-hidden', index >= visible);
            });
            if (btn) {
                btn.hidden = visible >= max;
            }
        }

        if (btn) {
            btn.addEventListener('click', function () {
                visible = Math.min(visible + getStep(), max);
                syncCards();
            });
        }

        syncCards();
    })();
    </script>
    @endpush
    @endif

    <section class="hp-section" id="stores">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Stores in focus</p>
                <h2 class="hp-sec-title">Featured destinations</h2>
                <p class="hp-sec-desc">Tap a logo to jump straight into coupons and campaign details for that brand.</p>
            </header>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="hp-stores-panel">
                <div class="stores-carousel-wrap">
                    <div class="stores-carousel-track">
                        <div class="stores-carousel">
                            @foreach($featuredCampaigns as $campaign)
                                @php
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $reviewUrl = route('landing.show', ['slug' => $reviewSlug]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand)
                                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                        @endif
                                    </span>
                                    <span class="store-carousel-name">
                                        {{ $brand?->name ?? $campaign->title }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                        <div class="stores-carousel">
                            @foreach($featuredCampaigns as $campaign)
                                @php
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $reviewUrl = route('landing.show', ['slug' => $reviewSlug]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand)
                                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                        @endif
                                    </span>
                                    <span class="store-carousel-name">
                                        {{ $brand?->name ?? $campaign->title }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
            @else
                <div class="hp-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3>No campaigns yet</h3>
                    <p>Check back soon — new partner stores and offers land here first.</p>
                </div>
            @endif
        </div>
    </section>

    @if(isset($popularCategories) && $popularCategories->isNotEmpty())
    <section class="hp-cats" id="categories">
        <div class="hp-shell">
            <header class="hp-sec-head" style="text-align:center;margin-left:auto;margin-right:auto;">
                <p class="hp-sec-eyebrow">Topics</p>
                <h2 class="hp-sec-title">Browse by category</h2>
                <p class="hp-sec-desc" style="margin-left:auto;margin-right:auto;">Jump into the verticals we cover most — each link filters the featured strip.</p>
            </header>
            <div class="hp-cat-row">
                @foreach($popularCategories as $cat)
                    @php
                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : ($cat['slug'] ?? '');
                        $url = $catSlug ? url('/?cat=' . $catSlug) . '#stores' : url('/') . '#stores';
                    @endphp
                    <a href="{{ $url }}" class="hp-cat-pill">{{ $catName }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
    @push('scripts')
    <script>
    (function() {
        var wrap = document.querySelector('.stores-carousel-wrap');
        var track = document.querySelector('.stores-carousel-track');
        if (!wrap || !track) return;
        var currentTx = 0;
        var startX = 0;
        var startTx = 0;
        var dragging = false;
        var didDrag = false;
        var direction = -1;
        var step = 0.6;
        var autoPlayTimer = null;

        function clamp(x, min, max) { return Math.min(Math.max(x, min), max); }

        function getBounds() {
            var maxTx = 0;
            var minTx = -(track.offsetWidth - wrap.offsetWidth);
            if (minTx > 0) minTx = 0;
            return { minTx: minTx, maxTx: maxTx };
        }

        function applyTransform() {
            track.style.transform = 'translateX(' + currentTx + 'px)';
        }

        function startAutoPlay() {
            if (autoPlayTimer) return;
            autoPlayTimer = setInterval(function() {
                if (dragging) return;
                if (wrap.matches(':hover')) return;
                if (document.hidden) return;

                var bounds = getBounds();
                currentTx += direction * step;
                if (currentTx <= bounds.minTx || currentTx >= bounds.maxTx) {
                    direction *= -1;
                    currentTx = clamp(currentTx, bounds.minTx, bounds.maxTx);
                }
                applyTransform();
            }, 20);
        }

        function stopAutoPlay() {
            if (!autoPlayTimer) return;
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }

        wrap.addEventListener('pointerdown', function(e) {
            dragging = true;
            didDrag = false;
            startX = e.clientX;
            startTx = currentTx;
            wrap.classList.add('dragging');
            stopAutoPlay();
        });
        document.addEventListener('pointermove', function(e) {
            if (!dragging) return;
            var dx = e.clientX - startX;
            if (Math.abs(dx) > 4) didDrag = true;
            e.preventDefault();
            var bounds = getBounds();
            currentTx = clamp(startTx + dx, bounds.minTx, bounds.maxTx);
            applyTransform();
        });
        document.addEventListener('pointerup', function() {
            dragging = false;
            wrap.classList.remove('dragging');
            startAutoPlay();
        });
        document.addEventListener('pointercancel', function() {
            dragging = false;
            wrap.classList.remove('dragging');
            startAutoPlay();
        });
        wrap.addEventListener('click', function(e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);

        startAutoPlay();
    })();
    </script>
    @endpush
    @endif

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    @push('scripts')
    <script>
    (function () {
        var wrap = document.getElementById('hp-blog-mobile-carousel');
        var track = document.getElementById('hp-blog-carousel-track');
        var dotsWrap = document.getElementById('hp-blog-mobile-controls');
        if (!wrap || !track || !dotsWrap) return;

        var slides = track.querySelectorAll('.hp-blog-carousel-slide');
        var dots = dotsWrap.querySelectorAll('.hp-blog-mobile-dot');
        if (slides.length === 0) return;

        var currentIndex = 0;
        var autoTimer = null;
        var delay = 3000;
        var dragging = false;
        var didDrag = false;
        var startX = 0;
        var currentX = 0;

        function goTo(index) {
            currentIndex = ((index % slides.length) + slides.length) % slides.length;
            track.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
            dots.forEach(function (d, i) {
                d.classList.toggle('is-active', i === currentIndex);
                d.setAttribute('aria-current', i === currentIndex ? 'true' : 'false');
            });
            resetAuto();
        }

        function goNext() {
            goTo(currentIndex + 1);
        }

        function startAuto() {
            stopAuto();
            autoTimer = setInterval(function () {
                if (dragging) return;
                if (document.hidden) return;
                goNext();
            }, delay);
        }

        function stopAuto() {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        }

        function resetAuto() {
            stopAuto();
            startAuto();
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var idx = parseInt(dot.getAttribute('data-slide') || '0', 10);
                if (!isNaN(idx)) goTo(idx);
            });
        });

        function getClientX(e) {
            if (e.touches && e.touches.length > 0) return e.touches[0].clientX;
            if (e.changedTouches && e.changedTouches.length > 0) return e.changedTouches[0].clientX;
            return e.clientX;
        }

        track.addEventListener('touchstart', function (e) {
            dragging = true;
            didDrag = false;
            startX = getClientX(e);
            currentX = startX;
            stopAuto();
            track.style.transition = 'none';
        }, { passive: true });

        track.addEventListener('touchmove', function (e) {
            if (!dragging) return;
            currentX = getClientX(e);
            var delta = currentX - startX;
            if (Math.abs(delta) > 5) didDrag = true;
            var base = -currentIndex * 100;
            track.style.transform = 'translateX(calc(' + base + '% + ' + delta + 'px))';
        }, { passive: true });

        track.addEventListener('touchend', function () {
            if (!dragging) return;
            dragging = false;
            track.style.transition = 'transform 0.4s cubic-bezier(0.22, 1, 0.36, 1)';
            var delta = currentX - startX;
            if (Math.abs(delta) > wrap.offsetWidth * 0.2) {
                if (delta > 0) goTo(currentIndex - 1);
                else goTo(currentIndex + 1);
            } else {
                goTo(currentIndex);
            }
        });

        track.addEventListener('click', function (e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAuto();
            else startAuto();
        });

        startAuto();
    })();
    </script>
    @endpush
    @endif
</div>
@endsection
