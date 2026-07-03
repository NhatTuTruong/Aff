@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
@include('partials.peel-sticker-styles')
@include('partials.styles.public-modern-base')
@include('partials.styles.home-modern')
@endpush

@section('content')
<div class="home-page">
    <section class="hp-hero">
        <div class="hp-shell">
            <div class="hp-hero-layout">
                <div class="hp-hero-main">
                    <span class="hp-eyebrow">Deals you can trust</span>
                    <h1 class="font-heading">Curated coupons &amp; <em>honest</em> store picks</h1>
                    <p class="hp-lead">Search verified promotions, explore top stores, and read updates from our blog — refreshed often so you never miss a strong offer.</p>
                    <form action="{{ url('/') }}" method="get" class="hp-search">
                        <svg class="hp-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
                        <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search brands, stores, or offers…" autocomplete="off">
                        <button type="submit">Search</button>
                    </form>
                    <p class="hp-trust">We are an independent deal finder. We may earn from qualifying purchases. <a href="{{ url('/affiliate-disclosure') }}">Read our disclosure</a>.</p>
                </div>
                <div class="hp-hero-cards" aria-label="Why shoppers stay">
                    <article class="hp-mini-card">
                        <span class="hp-mini-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <h3>Verified paths</h3>
                        <p>Human-reviewed savings — fewer dead codes, clearer next steps.</p>
                    </article>
                    <article class="hp-mini-card">
                        <span class="hp-mini-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
                        </span>
                        <h3>Always updated</h3>
                        <p>Offers and landing details refreshed often — not yesterday's leftovers.</p>
                    </article>
                    <article class="hp-mini-card">
                        <span class="hp-mini-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"/></svg>
                        </span>
                        <h3>Built for trust</h3>
                        <p>Clear disclosure, honest pros &amp; cons, CTAs that take you straight to the deal.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @if(($verifiedBrandsCount ?? 0) > 0 || $hotCoupons->isNotEmpty())
    <section class="hp-band">
        <div class="hp-shell">
            <div class="hp-band-inner">
                <div class="hp-band-item">
                    <strong>{{ $verifiedBrandsCount ?? 0 }}+</strong>
                    <span>Verified brands</span>
                </div>
                <div class="hp-band-divider" aria-hidden="true"></div>
                <div class="hp-band-item">
                    <strong>{{ $activeCouponsCount ?? $hotCoupons->count() }}+</strong>
                    <span>Active coupons</span>
                </div>
                <div class="hp-band-divider" aria-hidden="true"></div>
                <div class="hp-band-item">
                    <strong>Editorial</strong>
                    <span>Guides &amp; picks</span>
                </div>
                <div class="hp-band-divider" aria-hidden="true"></div>
                <div class="hp-band-item">
                    <strong>Daily</strong>
                    <span>Fresh checks</span>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="hp-section" id="blog">
        <div class="hp-shell">
            <header class="hp-head">
                <div>
                    <span class="hp-head-tag">Editorial</span>
                    <h2 class="hp-head-title">Latest from the blog</h2>
                </div>
                <p class="hp-head-desc">Short reads on saving tactics, store notes, and what changed this week.</p>
            </header>

            <div class="hp-carousel hp-carousel--blog" id="hp-blog-carousel">
                <div class="hp-carousel-viewport hp-carousel-viewport--inset" data-carousel-viewport>
                    <div class="hp-carousel-track" data-carousel-track>
                        @foreach($latestPosts->take(6) as $post)
                            <div class="hp-carousel-slide" data-carousel-slide>
                                <a href="{{ route('blog.show', $post->slug) }}" class="hp-blog-card">
                                    <div class="hp-blog-card-media">
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                    </div>
                                    <div class="hp-blog-card-body">
                                        <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->format('d M Y') }}</time>
                                        <h3>{{ $post->title }}</h3>
                                        <span class="hp-blog-card-link">Read article →</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="hp-carousel-arrow hp-carousel-arrow--inset hp-carousel-arrow--prev" aria-label="Previous article" data-carousel-prev>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="hp-carousel-arrow hp-carousel-arrow--inset hp-carousel-arrow--next" aria-label="Next article" data-carousel-next>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <p class="hp-more-link"><a href="{{ route('blog.index') }}">Browse the full archive</a></p>
        </div>
    </section>
    @endif

    @if($hotCoupons->isNotEmpty())
    <section class="hp-section hp-section--alt" id="coupons">
        <div class="hp-shell">
            <header class="hp-head">
                <div>
                    <span class="hp-head-tag">Limited windows</span>
                    <h2 class="hp-head-title"><a href="{{ route('deals.index') }}">Hot coupons &amp; standout deals</a></h2>
                </div>
                <p class="hp-head-desc">High-signal picks from brands we track — copy a code or open the offer in one tap.</p>
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
                        <div class="coupon-card-accent" aria-hidden="true"></div>
                        <div class="coupon-card-main">
                            <div class="coupon-card-header">
                                <img src="{{ $brand->image_url }}" alt="{{ $campaign->title }}" class="coupon-card-logo" loading="lazy">
                                <div>
                                    <div class="coupon-card-brand">{{ $campaign->title }}</div>
                                    <span class="coupon-card-type">{{ $coupon->code ? 'Promo code' : 'Deal link' }}</span>
                                </div>
                            </div>
                            @if($coupon->offer)
                                <p class="coupon-card-offer">{{ $coupon->offer }}</p>
                            @endif
                            <div class="coupon-card-actions">
                                @if($coupon->code)
                                    <button type="button" class="coupon-card-code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);" title="Click to copy">
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
                <div class="hp-load-more-wrap hp-load-more-wrap--mobile">
                    <button type="button" class="hp-load-more" id="hp-coupons-load-more" aria-controls="hp-coupons-grid">See more deals</button>
                </div>
            @endif
            <p class="hp-deals-more"><a href="{{ route('deals.index') }}">View all deals →</a></p>
        </div>
    </section>
    @endif

    <section class="hp-section" id="stores">
        <div class="hp-shell">
            <header class="hp-head">
                <div>
                    <span class="hp-head-tag">Stores in focus</span>
                    <h2 class="hp-head-title">Featured destinations</h2>
                </div>
                <p class="hp-head-desc">Tap a logo to jump straight into coupons and campaign details for that brand.</p>
            </header>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="hp-carousel hp-carousel--stores" id="hp-stores-carousel">
                    <button type="button" class="hp-carousel-arrow hp-carousel-arrow--prev hp-stores-arrow" aria-label="Previous stores" data-carousel-prev>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <div class="hp-carousel-viewport hp-stores-viewport" data-carousel-viewport>
                        <div class="hp-carousel-track hp-stores-track" data-carousel-track>
                            @foreach($featuredCampaigns as $campaign)
                                @php
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $reviewUrl = route('landing.show', ['slug' => $reviewSlug]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                    $brandLabel = $brand?->name ?? $campaign->title;
                                @endphp
                                <div class="hp-carousel-slide hp-store-slide" data-carousel-slide>
                                    <a href="{{ $reviewUrl }}" class="hp-store-tile" title="{{ $campaign->title }}">
                                        <span class="hp-store-tile-img">
                                            @if($brand)
                                                <img src="{{ $brand->image_url }}" alt="{{ $brandLabel }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                            @endif
                                        </span>
                                        <span class="hp-store-tile-name">{{ $brandLabel }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="hp-carousel-arrow hp-carousel-arrow--next hp-stores-arrow" aria-label="Next stores" data-carousel-next>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
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
            <header class="hp-head hp-head--center">
                <div>
                    <span class="hp-head-tag">Topics</span>
                    <h2 class="hp-head-title">Browse by category</h2>
                </div>
                <p class="hp-head-desc">Jump into the verticals we cover most — each link filters the featured strip.</p>
            </header>
            <div class="hp-cat-grid">
                @foreach($popularCategories as $cat)
                    @php
                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : ($cat['slug'] ?? '');
                        $url = $catSlug ? url('/?cat=' . $catSlug) . '#stores' : url('/') . '#stores';
                    @endphp
                    <a href="{{ $url }}" class="hp-cat-chip">{{ $catName }}</a>
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
    var mobileMq = window.matchMedia('(max-width: 768px)');

    function initCarousel(root, options) {
        if (!root) return null;

        var viewport = root.querySelector('[data-carousel-viewport]');
        var track = root.querySelector('[data-carousel-track]');
        var prevBtn = root.querySelector('[data-carousel-prev]');
        var nextBtn = root.querySelector('[data-carousel-next]');
        var slides = track ? track.querySelectorAll('[data-carousel-slide]') : [];
        if (!viewport || !track || slides.length === 0) return null;

        var index = 0;
        var timer = null;
        var dragging = false;
        var didDrag = false;
        var startX = 0;
        var currentX = 0;
        var autoplayMs = options.autoplayMs || 0;
        var mq = options.mq || null;
        var destroyed = false;

        function enabled() {
            return !destroyed && (!mq || mq.matches);
        }

        function perView() {
            if (typeof options.perView === 'function') {
                return options.perView();
            }
            return options.perView || 1;
        }

        function maxIndex() {
            return Math.max(0, slides.length - perView());
        }

        function slideStepPx() {
            var slide = slides[0];
            if (!slide) return 0;
            var style = window.getComputedStyle(track);
            var gap = parseFloat(style.columnGap || style.gap || '0') || 0;
            return slide.offsetWidth + gap;
        }

        function applyTransform() {
            if (!enabled()) {
                track.style.transform = '';
                track.style.transition = '';
                return;
            }
            track.style.transform = 'translateX(' + (-index * slideStepPx()) + 'px)';
        }

        function goTo(nextIndex) {
            index = Math.max(0, Math.min(nextIndex, maxIndex()));
            applyTransform();
            syncButtons();
        }

        function syncButtons() {
            if (!prevBtn || !nextBtn) return;
            var on = enabled();
            prevBtn.disabled = !on || index <= 0;
            nextBtn.disabled = !on || index >= maxIndex();
        }

        function stopAuto() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function startAuto() {
            stopAuto();
            if (!autoplayMs || !enabled()) return;
            timer = setInterval(function () {
                if (document.hidden || root.matches(':hover')) return;
                if (index >= maxIndex()) goTo(0);
                else goTo(index + 1);
            }, autoplayMs);
        }

        function resetAuto() {
            stopAuto();
            startAuto();
        }

        function onPrev() {
            goTo(index - 1);
            resetAuto();
        }

        function onNext() {
            goTo(index + 1);
            resetAuto();
        }

        if (prevBtn) prevBtn.addEventListener('click', onPrev);
        if (nextBtn) nextBtn.addEventListener('click', onNext);

        function onPointerDown(clientX) {
            if (!enabled()) return;
            dragging = true;
            didDrag = false;
            startX = clientX;
            currentX = clientX;
            stopAuto();
            track.style.transition = 'none';
        }

        function onPointerMove(clientX) {
            if (!dragging || !enabled()) return;
            currentX = clientX;
            var delta = currentX - startX;
            if (Math.abs(delta) > 5) didDrag = true;
            track.style.transform = 'translateX(' + (-index * slideStepPx() + delta) + 'px)';
        }

        function onPointerUp() {
            if (!dragging || !enabled()) return;
            dragging = false;
            track.style.transition = 'transform 0.4s cubic-bezier(0.22, 1, 0.36, 1)';
            var delta = currentX - startX;
            if (Math.abs(delta) > viewport.offsetWidth * 0.15) {
                if (delta > 0) goTo(index - 1);
                else goTo(index + 1);
            } else {
                applyTransform();
            }
            resetAuto();
        }

        viewport.addEventListener('touchstart', function (e) {
            onPointerDown(e.touches[0].clientX);
        }, { passive: true });

        viewport.addEventListener('touchmove', function (e) {
            onPointerMove(e.touches[0].clientX);
        }, { passive: true });

        viewport.addEventListener('touchend', onPointerUp);

        viewport.addEventListener('mousedown', function (e) {
            if (e.button !== 0) return;
            onPointerDown(e.clientX);
            e.preventDefault();
        });

        window.addEventListener('mousemove', function (e) {
            if (!dragging) return;
            onPointerMove(e.clientX);
        });

        window.addEventListener('mouseup', function () {
            if (dragging) onPointerUp();
        });

        viewport.addEventListener('click', function (e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);

        function refresh() {
            if (index > maxIndex()) index = maxIndex();
            track.style.transition = 'transform 0.4s cubic-bezier(0.22, 1, 0.36, 1)';
            applyTransform();
            syncButtons();
            if (enabled()) startAuto();
            else stopAuto();
        }

        function onResize() { refresh(); }

        window.addEventListener('resize', onResize);
        if (mq && mq.addEventListener) mq.addEventListener('change', refresh);
        else if (mq && mq.addListener) mq.addListener(refresh);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAuto();
            else resetAuto();
        });

        refresh();

        return {
            refresh: refresh,
            destroy: function () {
                destroyed = true;
                stopAuto();
                track.style.transform = '';
                track.style.transition = '';
                window.removeEventListener('resize', onResize);
            }
        };
    }

    function initStoresMarquee(root) {
        if (!root) return null;

        var viewport = root.querySelector('[data-carousel-viewport]');
        var track = root.querySelector('[data-carousel-track]');
        if (!viewport || !track) return null;

        var originalHtml = track.innerHTML;
        var currentTx = 0;
        var direction = -1;
        var step = 0.55;
        var dragging = false;
        var didDrag = false;
        var startX = 0;
        var startTx = 0;
        var timer = null;
        var destroyed = false;
        var loopWidth = 0;

        function duplicateTrack() {
            track.innerHTML = originalHtml + originalHtml;
            loopWidth = track.scrollWidth / 2;
        }

        function restoreTrack() {
            track.innerHTML = originalHtml;
            track.style.transform = '';
            track.style.transition = '';
            loopWidth = 0;
        }

        function applyTransform() {
            track.style.transform = 'translateX(' + currentTx + 'px)';
        }

        function normalizePosition() {
            if (loopWidth <= 0) return;
            if (currentTx <= -loopWidth) currentTx += loopWidth;
            if (currentTx > 0) currentTx -= loopWidth;
        }

        function stopAuto() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function startAuto() {
            stopAuto();
            timer = setInterval(function () {
                if (destroyed || dragging || document.hidden || root.matches(':hover')) return;
                currentTx += direction * step;
                normalizePosition();
                applyTransform();
            }, 20);
        }

        function enable() {
            duplicateTrack();
            currentTx = 0;
            track.style.transition = 'none';
            applyTransform();

            function measureAndStart() {
                loopWidth = track.scrollWidth / 2;
                if (loopWidth <= 0 && track.children.length > 0) {
                    requestAnimationFrame(measureAndStart);
                    return;
                }
                startAuto();
            }

            requestAnimationFrame(measureAndStart);
        }

        function disable() {
            stopAuto();
            restoreTrack();
            currentTx = 0;
            dragging = false;
        }

        var pending = false;

        viewport.addEventListener('pointerdown', function (e) {
            if (destroyed) return;
            pending = true;
            didDrag = false;
            dragging = false;
            startX = e.clientX;
            startTx = currentTx;
            stopAuto();
        }, { passive: true });

        viewport.addEventListener('pointermove', function (e) {
            if (!pending && !dragging) return;
            var dx = e.clientX - startX;
            if (!dragging && Math.abs(dx) > 10) {
                dragging = true;
                didDrag = true;
                track.style.transition = 'none';
            }
            if (!dragging) return;
            currentTx = startTx + dx;
            applyTransform();
        }, { passive: true });

        viewport.addEventListener('pointerup', function () {
            if (!pending && !dragging) return;
            pending = false;
            if (dragging) {
                dragging = false;
                track.style.transition = 'transform 0.15s ease-out';
                normalizePosition();
                applyTransform();
            }
            startAuto();
        });

        viewport.addEventListener('pointercancel', function () {
            pending = false;
            dragging = false;
            startAuto();
        });

        viewport.addEventListener('click', function (e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAuto();
            else if (!destroyed && mobileMq.matches) startAuto();
        });

        return {
            enable: enable,
            disable: disable,
            destroy: function () {
                destroyed = true;
                disable();
            }
        };
    }

    var storesRoot = document.getElementById('hp-stores-carousel');
    var storesCarousel = null;
    var storesMarquee = initStoresMarquee(storesRoot);

    function setupStores() {
        if (!storesRoot) return;
        if (storesCarousel) {
            storesCarousel.destroy();
            storesCarousel = null;
        }
        if (storesMarquee) storesMarquee.disable();

        if (mobileMq.matches) {
            if (storesMarquee) storesMarquee.enable();
        } else {
            storesCarousel = initCarousel(storesRoot, {
                perView: function () {
                    if (window.innerWidth >= 1200) return 7;
                    if (window.innerWidth >= 960) return 6;
                    return 5;
                }
            });
        }
    }

    setupStores();
    window.addEventListener('load', setupStores);
    if (mobileMq.addEventListener) mobileMq.addEventListener('change', setupStores);
    else if (mobileMq.addListener) mobileMq.addListener(setupStores);

    initCarousel(document.getElementById('hp-blog-carousel'), {
        mq: mobileMq,
        perView: 1
    });

    var couponsBtn = document.getElementById('hp-coupons-load-more');
    var couponCards = document.querySelectorAll('#hp-coupons-grid [data-coupon-card]');
    if (couponCards.length > 0) {
        var maxCoupons = Math.min(12, couponCards.length);
        var visibleCoupons = mobileMq.matches ? 4 : 6;

        function syncCoupons() {
            if (!mobileMq.matches) {
                couponCards.forEach(function (card) {
                    card.classList.remove('is-coupon-hidden');
                });
                if (couponsBtn) couponsBtn.closest('.hp-load-more-wrap').style.display = 'none';
                return;
            }
            couponCards.forEach(function (card, index) {
                card.classList.toggle('is-coupon-hidden', index >= visibleCoupons);
            });
            if (couponsBtn) {
                couponsBtn.hidden = visibleCoupons >= maxCoupons;
                couponsBtn.closest('.hp-load-more-wrap').style.display = '';
            }
        }

        if (couponsBtn) {
            couponsBtn.addEventListener('click', function () {
                visibleCoupons = Math.min(visibleCoupons + 4, maxCoupons);
                syncCoupons();
            });
        }

        function onCouponMqChange() {
            visibleCoupons = mobileMq.matches ? 4 : 6;
            syncCoupons();
        }

        syncCoupons();
        if (mobileMq.addEventListener) mobileMq.addEventListener('change', onCouponMqChange);
        else if (mobileMq.addListener) mobileMq.addListener(onCouponMqChange);
    }
})();
</script>
@endpush
