<div class="idx-home">
    <section class="idx-banner">
        <div class="idx-wrap idx-banner__layout">
            <div>
                <p class="idx-banner__tag">Trusted offers, clearly explained</p>
                <h1 class="font-heading">Shop smarter with vetted codes and honest store guides</h1>
                <p class="idx-banner__lead">Look up checked deals, browse reliable brands, and skim fresh notes from our blog — updated regularly so worthwhile savings stay on your radar.</p>
                <p class="idx-banner__legal">We operate independently as a deal guide. Purchases through our links may earn us a commission. <a href="{{ url('/affiliate-disclosure') }}">Read our disclosure</a>.</p>
                <form action="{{ url('/') }}" method="get" class="idx-banner__search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search a brand, store, or deal…" autocomplete="off">
                    <button type="submit">Search</button>
                </form>
            </div>
            <aside class="idx-banner__spotlight" aria-label="Site highlights">
                <div class="idx-spotlight-slider" id="idx-spotlight-slider">
                    <div class="idx-spotlight-track" id="idx-spotlight-track">
                        <div class="idx-spotlight-slide">
                            <p class="idx-spotlight-label">Why people return</p>
                            <p class="idx-spotlight-stat">Hand-picked</p>
                            <p class="idx-spotlight-caption">Offers we review before listing — less guesswork, fewer expired codes, clearer paths to checkout.</p>
                        </div>
                        <div class="idx-spotlight-slide">
                            <p class="idx-spotlight-label">Kept current</p>
                            <p class="idx-spotlight-stat">Fresh</p>
                            <p class="idx-spotlight-caption">Promotions and store pages are rechecked so you see what still works — not stale links from last season.</p>
                        </div>
                        <div class="idx-spotlight-slide">
                            <p class="idx-spotlight-label">Transparency first</p>
                            <p class="idx-spotlight-stat">Straightforward</p>
                            <p class="idx-spotlight-caption">Open affiliate notes, balanced store write-ups, and buttons that lead directly to the offer — no hidden detours.</p>
                        </div>
                    </div>
                    <nav class="idx-spotlight-dots" id="idx-spotlight-dots" aria-label="Highlight slides">
                        <button type="button" class="idx-spotlight-dot is-active" aria-label="Slide 1" aria-current="true" data-slide="0"></button>
                        <button type="button" class="idx-spotlight-dot" aria-label="Slide 2" data-slide="1"></button>
                        <button type="button" class="idx-spotlight-dot" aria-label="Slide 3" data-slide="2"></button>
                    </nav>
                </div>
            </aside>
        </div>
    </section>

    @if(($verifiedBrandsCount ?? 0) > 0 || $hotCoupons->isNotEmpty())
    <section class="idx-metrics">
        <div class="idx-wrap">
            <div class="idx-metrics__list">
                <div class="idx-metric__item">
                    <div class="idx-metric__value">{{ $verifiedBrandsCount ?? 0 }}+</div>
                    <div class="idx-metric__label">Verified brands</div>
                </div>
                <div class="idx-metric__item">
                    <div class="idx-metric__value">{{ $activeCouponsCount ?? $hotCoupons->count() }}+</div>
                    <div class="idx-metric__label">Active coupons</div>
                </div>
                <div class="idx-metric__item">
                    <div class="idx-metric__value">Editorial</div>
                    <div class="idx-metric__label">Guides &amp; picks</div>
                </div>
                <div class="idx-metric__item">
                    <div class="idx-metric__value">Daily</div>
                    <div class="idx-metric__label">Fresh checks</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="idx-block" id="stores">
        <div class="idx-wrap">
            <header class="idx-block__header">
                <p class="idx-block__label">Stores in focus</p>
                <h2 class="idx-block__title">Featured destinations</h2>
                <p class="idx-block__subtitle">Tap a logo to jump straight into coupons and campaign details for that brand.</p>
            </header>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="idx-brands__box">
                    <div class="idx-brands__viewport">
                        <div class="idx-brands__track">
                            <div class="idx-brands__lane">
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
                                    <a href="{{ $reviewUrl }}" class="idx-brand__item" title="{{ $campaign->title }}">
                                        <span class="idx-brand__logo">
                                            @if($brand)
                                                <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                            @endif
                                        </span>
                                        <span class="idx-brand__name">{{ $brand?->name ?? $campaign->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="idx-brands__lane">
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
                                    <a href="{{ $reviewUrl }}" class="idx-brand__item" title="{{ $campaign->title }}">
                                        <span class="idx-brand__logo">
                                            @if($brand)
                                                <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                            @endif
                                        </span>
                                        <span class="idx-brand__name">{{ $brand?->name ?? $campaign->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="idx-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3>No campaigns yet</h3>
                    <p>Check back soon — new partner stores and offers land here first.</p>
                </div>
            @endif
        </div>
    </section>

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="idx-block idx-block--tint" id="blog">
        <div class="idx-wrap">
            <header class="idx-block__header">
                <p class="idx-block__label">Editorial</p>
                <h2 class="idx-block__title">Latest from the blog</h2>
                <p class="idx-block__subtitle">Short reads on saving tactics, store notes, and what changed this week.</p>
            </header>
            <div class="idx-articles">
                @foreach($latestPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="idx-article__card">
                        <div class="idx-article__thumb">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                        </div>
                        <div class="idx-article__body">
                            <h3 class="idx-article__title">{{ $post->title }}</h3>
                            <p class="idx-article__date">{{ $post->created_at?->format('d M Y') }}</p>
                            <span class="idx-article__action">Open story</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="idx-block__more"><a href="{{ route('blog.index') }}">Browse the full archive</a></p>
        </div>
    </section>
    @endif

    @if($hotCoupons->isNotEmpty())
    <section class="idx-block idx-block--tint" id="coupons">
        <div class="idx-wrap">
            <header class="idx-block__header">
                <p class="idx-block__label">Limited windows</p>
                <h2 class="idx-block__title">Hot coupons &amp; standout deals</h2>
                <p class="idx-block__subtitle">High-signal picks from brands we track — copy a code or open the offer in one tap.</p>
            </header>
            <p class="idx-block__legal">Promotions can change or expire at any time. Always confirm at checkout. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.</p>
            <div class="idx-deals__list">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <article class="idx-deal">
                        <div class="idx-deal__strip" aria-hidden="true">
                            <span class="idx-deal__strip-icon">%</span>
                            <span>{{ $coupon->code ? 'Code' : 'Deal' }}</span>
                        </div>
                        <div class="idx-deal__main">
                            <div class="idx-deal__header">
                                <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" class="idx-deal__logo" loading="lazy">
                                <div class="idx-deal__brand">{{ $brand->name }}</div>
                            </div>
                            @if($coupon->offer)
                                <p class="idx-deal__offer">{{ $coupon->offer }}</p>
                            @endif
                            <div class="idx-deal__actions">
                                @if($coupon->code)
                                    <button type="button" class="idx-deal__code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);" title="Click to copy">
                                        <span class="idx-deal__code-label">Code</span>
                                        <span class="idx-deal__code-value">{{ $coupon->code }}</span>
                                        <span class="idx-deal__code-copy">Copy</span>
                                    </button>
                                @endif
                                @if($campaign && $campaign->affiliate_url)
                                    <a href="{{ route('click.redirect', ['slug' => $campaign->slug]) }}" class="idx-deal__cta" target="_blank" rel="noopener">Get deal</a>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($popularCategories) && $popularCategories->isNotEmpty())
    <section class="idx-topics" id="categories">
        <div class="idx-wrap">
            <header class="idx-block__header" style="text-align:center;margin-left:auto;margin-right:auto;">
                <p class="idx-block__label">Topics</p>
                <h2 class="idx-block__title">Browse by category</h2>
                <p class="idx-block__subtitle" style="margin-left:auto;margin-right:auto;">Jump into the verticals we cover most — each link filters the featured strip.</p>
            </header>
            <div class="idx-topics__row">
                @foreach($popularCategories as $cat)
                    @php
                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : ($cat['slug'] ?? '');
                        $url = $catSlug ? url('/?cat=' . $catSlug) . '#stores' : url('/') . '#stores';
                    @endphp
                    <a href="{{ $url }}" class="idx-topics__chip">{{ $catName }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
(function () {
    var slider = document.getElementById('idx-spotlight-slider');
    var track = document.getElementById('idx-spotlight-track');
    var dotsWrap = document.getElementById('idx-spotlight-dots');
    if (!slider || !track || !dotsWrap) return;

    var dots = dotsWrap.querySelectorAll('.idx-spotlight-dot');
    var n = dots.length;
    if (n === 0) return;

    var i = 0;
    var timer = null;
    var delay = 3000;

    function setActive() {
        track.style.transform = 'translateX(' + (-i * 100) + '%)';
        dots.forEach(function (d, j) {
            var on = j === i;
            d.classList.toggle('is-active', on);
            d.setAttribute('aria-current', on ? 'true' : 'false');
        });
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
@if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
<script>
(function() {
    var wrap = document.querySelector('.idx-brands__viewport');
    var track = document.querySelector('.idx-brands__track');
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
@endif
@endpush
