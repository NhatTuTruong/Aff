<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $coupons = $campaign->couponItems ?? collect();
        $maxPercent = 0;
        foreach ($coupons as $c) {
            $offerText = (string) ($c->offer ?? '');
            if (preg_match('/(\d+)\s*%/i', $offerText, $m)) {
                $val = (int) $m[1];
                if ($val > $maxPercent) {
                    $maxPercent = $val;
                }
            }
        }
        if ($maxPercent <= 0) {
            $maxPercent = 75;
        }
    @endphp
    <title>{{ $campaign->title }} - Exclusive Deals & Coupons</title>
    <meta name="description" content="{{ $campaign->subtitle ?? $campaign->intro }}">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if(config('app.ga4_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga4_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('app.ga4_id') }}');
    </script>
    @endif

    <style>
        /* ===== BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary:#22c55e;
            --primary-dark:#16a34a;
            --primary-soft:#bbf7d0;
            --accent:#f97316;
            --text-dark:#111827;
            --text-light:#6b7280;
            --bg-page:#f4f5f7;
            --bg-card:#ffffff;
            --border:#e5e7eb;
            --shadow:0 1px 3px rgba(15,23,42,0.12);
            --shadow-lg:0 18px 40px rgba(15,23,42,0.18);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont,
                        'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 0.9rem;
            line-height: 1.65;
            color: var(--text-dark);
            background: var(--bg-page);
        }
        a { text-decoration:none;color:inherit; }

        /* ===== SHELL LAYOUT (CENTER WHITE PANEL) ===== */
        .shell {
            max-width: 1180px;
            margin: 0 auto 40px;
            padding: 20px 16px 40px;
        }
        .page-panel {
            background: var(--bg-card);
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
            padding: 24px 26px 30px;
        }

        /* ===== HERO (TOP TITLE LIKE TENERE) ===== */
        .hero {
            text-align: center;
            margin-bottom: 18px;
        }
        .hero-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }
        .hero-sub {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        .hero-sub strong {
            color: var(--text-dark);
            font-weight: 700;
        }

        /* ===== MAIN GRID (LEFT STORE CARD + RIGHT CONTENT) ===== */
        .page {
            margin-top: 22px;
            display:grid;
            grid-template-columns:320px 1fr;
            gap:24px;
        }
        .left-card {
            background: #ffffff;
            border-radius:16px;
            padding:24px 22px 24px;
            box-shadow: var(--shadow);
            border: 1px solid #e5e7eb;
            position:relative;
            display:flex;
            flex-direction:column;
        }
        .brand-logo {
            max-width:180px;height:auto;object-fit:contain;
            display:block;margin:0 auto 12px;
            border-radius: 999px;
            background:#fff;
            padding: 6px;
            box-shadow:0 8px 20px rgba(0,0,0,0.18);
        }
        .brand-name {
            font-size:1.1rem;
            font-weight: 800;
            text-align:center;
            margin-bottom:6px;
        }
        .brand-rating {
            display:flex;align-items:center;gap:8px;
            justify-content:center;
            margin-bottom:10px;font-size:0.8rem;
        }
        .stars { color:#fde047; }
        .brand-meta { font-size:0.75rem;opacity:0.9;text-align:center;margin-bottom:4px; }
        .stats-list {
            border-top:1px solid #e5e7eb;
            margin-top:10px;
            padding-top:10px;
            font-size:0.8rem;
        }
        .stats-row {
            display:flex;
            justify-content:space-between;
            padding:4px 0;
        }
        .stats-label { color:var(--text-light); }
        .stats-value { font-weight:600; }
        .sidebar-actions {
            margin-top:14px;
            display:flex;
            gap:8px;
        }
        .btn-outline,
        .btn-solid {
            flex:1;
            border-radius:999px;
            padding:8px 10px;
            font-size:0.8rem;
            font-weight:700;
            cursor:pointer;
            border:1px solid transparent;
            text-align:center;
        }
        .btn-outline {
            border-color:#d1d5db;
            background:#fff;
        }
        .btn-solid {
            background:var(--primary);
            color:#fff;
        }
        .btn-solid:hover { background:var(--primary-dark); }

        .right-column { display:flex;flex-direction:column;gap:18px; }

        /* NOTE BAR & FILTERS */
        .note-bar {
            font-size:0.78rem;
            color:var(--text-light);
            background:#f9fafb;
            border-radius:999px;
            padding:6px 14px;
            margin-bottom:12px;
        }
        .coupon-header {
            margin-bottom:10px;
        }
        .coupon-header-title {
            font-size:1rem;
            font-weight:800;
            letter-spacing:-0.02em;
            margin-bottom:4px;
        }
        .coupon-header-meta {
            font-size:0.8rem;
            color:var(--text-light);
        }
        .filter-tabs {
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            margin-top:10px;
        }
        .filter-pill {
            border-radius:999px;
            padding:6px 12px;
            font-size:0.78rem;
            border:1px solid #e5e7eb;
            background:#fff;
            cursor:pointer;
        }
        .filter-pill.active {
            background:var(--primary-soft);
            border-color:var(--primary);
            color:var(--primary-dark);
            font-weight:600;
        }

        .coupon-list { display:flex;flex-direction:column;gap:10px;margin-top:6px; }
        .coupon-row {
            background:#fff;border-radius:12px;
            box-shadow:0 8px 26px rgba(15,23,42,0.12);
            padding:16px 18px;
            display:grid;grid-template-columns:minmax(0,1fr) auto;
            gap:14px;align-items:center;
            border-left:4px solid var(--primary);
        }
        .coupon-info { display:flex;flex-direction:column;gap:4px; }
        .coupon-title { font-weight:700;font-size:0.98rem; }
        .coupon-offer { font-size:0.9rem;color:var(--primary);font-weight:700; }
        .coupon-desc { font-size:0.85rem;color:var(--text-light); }
        .coupon-actions {
            display:flex;flex-direction:column;
            align-items:flex-end;gap:6px;
        }
        .coupon-code {
            font-family:ui-monospace,monospace;
            font-size:0.85rem;padding:4px 10px;
            border-radius:999px;border:1px dashed #d4d4d8;
            background:#f9fafb;
        }
        .btn-copy {
            min-width:150px;padding:8px 12px;
            border-radius:999px;border:none;cursor:pointer;
            font-size:0.8rem;font-weight:700;
            color:#fff;background:var(--primary);
        }
        .btn-copy:hover { background:var(--primary-dark); }

        .section {
            background:#fff;border-radius:12px;
            padding:18px 20px 20px;box-shadow:var(--shadow);
        }
        .section-title { font-size:1rem;font-weight:700;margin-bottom:8px; }
        .section-body { font-size:0.85rem;color:var(--text-light); }
        .section-body ul { padding-left:18px;margin:6px 0; }
        .section-body li { margin-bottom:4px; }

        /* ===== ADDED (NO CONFLICT) ===== */
        .coupon-code.peek {
            filter:blur(3px);
            opacity:0.75;
            pointer-events:none;
        }

        .coupon-modal {
            position:fixed;inset:0;
            background:rgba(0,0,0,0.55);
            display:none;align-items:center;justify-content:center;
            z-index:9999;
        }
        .coupon-modal.active { display:flex; }
        .modal-box {
            background:#fff;border-radius:14px;
            width:420px;max-width:92%;
            padding:24px;text-align:center;
            position:relative;
        }
        .modal-close {
            position:absolute;top:10px;right:14px;
            cursor:pointer;font-size:20px;
        }
        .modal-logo img {
            max-width:80px;border-radius:50%;
            margin-bottom:12px;
        }
        .modal-code {
            display:flex;justify-content:center;
            margin:14px 0;
        }
        .modal-code span {
            padding:10px 18px;
            border:2px dashed #22c55e;
            font-weight:700;
        }
        .modal-code button {
            margin-left:6px;
            padding:10px 14px;
            background:#22c55e;color:#fff;
            border:none;cursor:pointer;font-weight:700;
        }
        .modal-link { color:#22c55e;font-weight:600; }
        @media(max-width:960px){ .page{grid-template-columns:1fr;} }

        /* ===== Coupon Popup ===== */
    .coupon-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }

    .coupon-modal.active {
        opacity: 1;
        pointer-events: auto;
    }

    .coupon-modal-content {
        background: #ffffff;
        width: 100%;
        max-width: 520px;
        border-radius: 18px;
        padding: 26px 28px 28px;
        box-shadow: 0 25px 60px rgba(15,23,42,.35);
        animation: popupScale .3s ease;
        position: relative;
    }

    @keyframes popupScale {
        from { transform: scale(.92); opacity: .5; }
        to { transform: scale(1); opacity: 1; }
    }

    .coupon-modal-close {
        position: absolute;
        top: 14px;
        right: 16px;
        font-size: 20px;
        cursor: pointer;
        color: #9ca3af;
    }

    .coupon-modal-close:hover {
        color: #111827;
    }

    .coupon-modal-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .coupon-modal-sub {
        font-size: .9rem;
        color: #6b7280;
        margin-bottom: 18px;
    }

    .coupon-code-box {
        background: #f9fafb;
        border: 2px dashed #6366f1;
        border-radius: 14px;
        padding: 14px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 18px;
    }

    .coupon-modal-actions {
        display: flex;
        gap: 12px;
    }

    .coupon-btn {
        flex: 1;
        padding: 12px 14px;
        border-radius: 999px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
    }

    .coupon-btn.copy {
        background: #6366f1;
        color: #ffffff;
    }

    .coupon-btn.copy:hover {
        background: #4f46e5;
    }

    .coupon-btn.store {
        background: #111827;
        color: #ffffff;
    }

    .coupon-btn.store:hover {
        background: #000000;
    }

    /* Reveal coupon code after copy */
    .coupon-row.revealed .coupon-code {
        opacity: 1;
        filter: none;
        max-height: 100px;
    }

    /* Q&A Section */
    .qa-section {
        padding-top: 20px;
    }

    .qa-item {
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        background: #fff;
    }

    .qa-question {
        width: 100%;
        background: none;
        border: none;
        padding: 14px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-dark);
    }

    .qa-question:hover {
        background: var(--bg-light);
    }

    .qa-icon {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .qa-answer {
        max-height: 0;
        overflow: hidden;
        padding: 0 16px;
        font-size: 0.85rem;
        color: var(--text-light);
        transition: all 0.35s ease;
    }

    .qa-item.active .qa-answer {
        max-height: 200px;
        padding: 10px 16px 16px;
    }

    .qa-item.active .qa-icon {
        transform: rotate(45deg);
    }

    /* Popup Header */
    .popup-header {
        text-align: center;
        margin-bottom: 16px;
    }

    .popup-logo {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 50%;
        margin: 0 auto 10px;
        display: block;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        background: #fff;
    }

    .popup-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--text-dark);
    }

    .popup-subtitle {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    h1, h2, h3, .brand-name, .section-title, .popup-title {
        letter-spacing: -0.015em;
    }

    .brand-name {
        font-weight: 800;
    }

    .section-title {
        font-weight: 700;
    }

    .coupon-title {
        font-weight: 600;
    }

    /* ===== Review Block Improvements ===== */
.review-center {
    text-align: center;
    margin-bottom: 14px;
}

.review-center .brand-logo {
    margin: 0 auto 12px;
    border-radius: 50%;
    background: #fff;
    padding: 6px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
}

.review-stars {
    font-size: 1.25rem; /* ⭐ to hơn */
    color: #fde047;
    margin-bottom: 6px;
}

.review-rating-text {
    font-size: 0.85rem;
    opacity: 0.95;
    margin-bottom: 14px;
}

.btn-get-coupon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 18px;
    border-radius: 999px;
    background: #22c55e;
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    box-shadow: 0 10px 24px rgba(34,197,94,0.35);
}

.btn-get-coupon:hover {
    transform: translateY(-1px);
    background: #16a34a;
    cursor: pointer;
}

/* Coupon Intro Header */
.coupon-intro {
    padding: 18px 20px;
}

.coupon-intro-title {
    font-size: 1.15rem;
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.coupon-intro-desc {
    font-size: 0.85rem;
    color: var(--text-light);
    line-height: 1.6;
}

/* Go to Store highlight animation */
@keyframes pulseGlow {
    0% {
        box-shadow: 0 0 0 rgba(34,197,94,0.0);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 25px rgba(34,197,94,0.65);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 0 0 rgba(34,197,94,0.0);
        transform: scale(1);
    }
}

.go-store-attention {
    animation: pulseGlow 0.9s ease-in-out 3;
}

        @media(max-width:960px){
            .page{
                grid-template-columns:1fr;
            }
        }


</style>
</head>

<body>
<div class="shell">
    <div class="page-panel">
    <header class="hero">
        <h1 class="hero-title">
            {{ $campaign->brand->name ?? $campaign->title }} Coupons &amp; Promo Codes
        </h1>
        <p class="hero-sub">
            Save up to <strong>{{ $maxPercent }}% off</strong> with verified discount codes &amp; exclusive deals.
        </p>
    </header>

    <div class="page">
    <!-- LEFT CARD -->
    <aside class="left-card">
        @if($campaign->brand && $campaign->brand->image)
            <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                alt="{{ $campaign->brand->name }}"
                class="brand-logo">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}"
                alt="{{ $campaign->title }}"
                class="brand-logo">
        @endif

        <div class="brand-name">
            {{ $campaign->brand->name ?? $campaign->title }}
        </div>
        <div class="brand-rating">
            <span class="stars">★★★★★</span>
            <span>4.8 rating • 1,200+ reviews</span>
        </div>
        <div class="stats-list">
            <div class="stats-row">
                <span class="stats-label">Working codes</span>
                <span class="stats-value">{{ ($campaign->couponItems ?? collect())->count() }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">Success rate</span>
                <span class="stats-value">94%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">Total saved</span>
                <span class="stats-value">$7,229</span>
            </div>
        </div>
        <div class="sidebar-actions">
            <button class="btn-outline" type="button">
                Reviews &amp; Info
            </button>
            <button class="btn-solid" type="button"
                onclick="window.location.href='{{ route('click.redirect',$campaign->slug) }}'">
                Shop Now
            </button>
        </div>
    </aside>

    <!-- RIGHT COLUMN -->
    <main class="right-column">
        <section>
            <div class="note-bar">
                Purchases through our links may earn us a commission, which helps us keep finding the best deals for you.
            </div>
            <div class="coupon-header">
                <div class="coupon-header-title">
                    Active {{ $campaign->brand->name ?? $campaign->title }} coupons
                </div>
                <div class="coupon-header-meta">
                    Last checked: 18 hours ago • Tracking coupons in the last 7 days.
                </div>
                <div class="filter-tabs">
                    <button class="filter-pill active" type="button">All Deals</button>
                    <button class="filter-pill" type="button">Codes</button>
                    <button class="filter-pill" type="button">Deals</button>
                    <button class="filter-pill" type="button">Free Shipping</button>
                    <button class="filter-pill" type="button">First Order</button>
                </div>
            </div>

        <section class="coupon-list">
            @forelse($coupons as $coupon)
            @php
                $hasCode = !empty($coupon->code);
                $offerText = (string) ($coupon->offer ?? '');
                $isFreeShipping = stripos($offerText, 'free shipping') !== false;
                $tags = [];
                if ($isFreeShipping) {
                    $tags[] = 'free-shipping';
                }
                $tagAttr = implode(',', $tags);
            @endphp
            <article
                class="coupon-row"
                data-type="{{ $hasCode ? 'code' : 'deal' }}"
                data-tags="{{ $tagAttr }}"
            >
                <div class="coupon-info">
                    <div class="coupon-title">
                        {{ $coupon->description ?: 'Exclusive coupon from '.($campaign->brand->name ?? $campaign->title) }}
                    </div>
                    @if($coupon->offer)
                        <div class="coupon-offer">{{ $coupon->offer }}</div>
                    @endif
                    <div class="coupon-desc">
                        @if($hasCode)
                            Click “Copy Code” to copy and apply it at checkout.
                        @else
                            Click “Get Deal” to activate this offer and shop now.
                        @endif
                    </div>
                </div>
                <div class="coupon-actions">
                    @if($hasCode)
                        <div class="coupon-code peek">
                        {{ $coupon->code }}
                        </div>
                    @endif
                    <button class="btn-copy"
                        data-type="{{ $hasCode ? 'code' : 'deal' }}"
                        data-code="{{ $coupon->code }}"
                        data-url="{{ route('click.redirect',$campaign->slug) }}"
                        onclick="handleCouponClick(this)">
                        {{ $hasCode ? 'Copy code' : 'Get deal' }}
                    </button>
                </div>
            </article>
            @empty
            <article class="section">
                <div class="section-title">No coupons available</div>
                <div class="section-body">
                    Please check back later. We are updating new deals.
                </div>
            </article>
            @endforelse
        </section>

        <!-- ABOUT -->
        <section class="section">
            <h2 class="section-title">About this campaign</h2>
            <div class="section-body">
                @if($campaign->intro)
                    <p>{{ $campaign->intro }}</p>
                @else
                    <p>
                        Exclusive deals from {{ $campaign->brand->name ?? $campaign->title }}
                        to help you save more when shopping online.
                    </p>
                @endif
            </div>
        </section>

        <!-- HOW TO USE -->
        <section class="section">
            <h2 class="section-title">How to use a coupon code</h2>
            <div class="section-body">
                <ul>
                    <li>Select a coupon from the list above.</li>
                    <li>Click <strong>Get Deal</strong> to copy the code and visit the store.</li>
                    <li>Add products to your cart as usual.</li>
                    <li>Paste the coupon code at checkout and apply.</li>
                </ul>
            </div>
        </section>

         <!-- q&A -->

         <section class="section qa-section">
            <h2 class="section-title">Questions & Answers</h2>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    How do I use this coupon code?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Simply click “Get Deal”, copy the coupon code, then apply it at checkout on the store’s website.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Why doesn’t my coupon work?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Some coupons require a minimum order value, specific products, or may have expired. Please double-check the terms before checkout.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Can I use more than one coupon?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Most stores allow only one coupon per order. Combining multiple offers is usually not supported.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Do you earn a commission from these deals?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Yes, we may earn a small commission when you make a purchase through our links, at no extra cost to you.
                </div>
            </div>
        </section>


        <!-- POLICY -->
        <section class="section">
            <h2 class="section-title">Policies & notes</h2>
            <div class="section-body">
                <ul>
                    <li>Some coupons may require a minimum order value.</li>
                    <li>Validity and conditions may change without notice.</li>
                    <li>Please double-check your discount before checkout.</li>
                    <li>We may earn a commission when you shop through our links.</li>
                </ul>
            </div>
        </section>

    </main>
    </div><!-- /.page -->
    </div><!-- /.page-panel -->
</div><!-- /.shell -->

<div id="couponModal" class="coupon-modal">
    <div class="coupon-modal-content">
        <div class="coupon-modal-close" onclick="closeCouponPopup()">✕</div>
        <div class="popup-header">
        @if($campaign->brand && $campaign->brand->image)
            <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                alt="{{ $campaign->brand->name }}"
                class="popup-logo">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}"
                alt="{{ $campaign->title }}"
                class="popup-logo">
        @endif

        <h3 class="popup-title">
            {{ $campaign->brand->name ?? $campaign->title }}
        </h3>

        <p class="popup-subtitle">
            Copy the code below and apply it at checkout
        </p>
    </div>
        <div id="modalCode" class="coupon-code-box">
            CODE123
        </div>

        <div class="coupon-modal-actions">
            <button class="coupon-btn copy" id="copyCouponBtn" onclick="copyCoupon(this)">
                Copy Code
            </button>
            <a href="{{ route('click.redirect',$campaign->slug) }}"
            target="_blank"
            class="btn-get-coupon go-to-store-btn">
                Go to Store
            </a>
        </div>
    </div>
</div>


<script>
let currentCode = '';
let currentCouponRow = null;

function handleCouponClick(btn){
    const type = btn.dataset.type;
    const code = btn.dataset.code || '';
    const url = btn.dataset.url;

    const activeTabBtn = document.querySelector('.filter-pill.active');
    const activeTab = activeTabBtn ? (activeTabBtn.dataset.tab || 'all') : 'all';

    // Nếu là deal (không có code) hoặc đang ở tab Deals → đi thẳng tới link aff
    if (type === 'deal' || activeTab === 'deals') {
        if (url) {
            window.open(url, '_blank');
        }
        return;
    }

    // Codes: mở modal + chuẩn bị copy + mở tab aff
    currentCode = code;
    currentCouponRow = btn.closest('.coupon-row');

    const codeBox = document.getElementById('modalCode');
    if (codeBox) {
        codeBox.innerText = code;
    }

    const modal = document.getElementById('couponModal');
    if (modal) {
        modal.classList.add('active');
    }

    const goBtn = document.querySelector('.go-to-store-btn');
    if (goBtn && url) {
        goBtn.href = url;
    }

    if (url) {
        window.open(url, '_blank');
    }
}

function closeCouponPopup(){
    document.getElementById('couponModal').classList.remove('active');
}

function copyCoupon(btn){
    if(!currentCode) return;

    navigator.clipboard.writeText(currentCode).then(()=>{
        btn.innerText = 'Copied ✓';
        btn.disabled = true;
           // 👉 highlight nút Go to Store
        const goBtn = document.querySelector('.go-to-store-btn');
        if(goBtn){
            goBtn.classList.remove('go-store-attention');
            void goBtn.offsetWidth; // force reflow
            goBtn.classList.add('go-store-attention');
        }

        // mở mã coupon đúng block
        if(currentCouponRow){
            currentCouponRow.classList.add('revealed');
        }
    });
}
function toggleQA(el){
    const item = el.closest('.qa-item');
    item.classList.toggle('active');
}

// Tabs filter
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.coupon-row');

    tabs.forEach((tab) => {
        const label = tab.textContent.trim().toLowerCase();
        if (label === 'all deals') tab.dataset.tab = 'all';
        if (label === 'codes') tab.dataset.tab = 'codes';
        if (label === 'deals') tab.dataset.tab = 'deals';
        if (label === 'free shipping') tab.dataset.tab = 'free-shipping';
    });

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const current = tab.dataset.tab || 'all';

            rows.forEach(row => {
                const type = row.dataset.type;
                const tags = (row.dataset.tags || '').split(',').filter(Boolean);
                let show = true;

                if (current === 'codes') {
                    show = type === 'code';
                } else if (current === 'deals') {
                    show = type === 'deal';
                } else if (current === 'free-shipping') {
                    show = tags.includes('free-shipping');
                } else {
                    show = true;
                }

                row.style.display = show ? 'grid' : 'none';
            });
        });
    });
});

// Track time on page and bounce rate
@if(isset($pageView) && $pageView)
(function() {
    const pageViewId = {{ $pageView->id }};
    const startTime = Date.now();
    let timeOnPage = 0;
    let isBounce = true;
    let hasInteracted = false;
    
    // Track user interactions to determine if it's a bounce
    ['click', 'scroll', 'keydown', 'touchstart'].forEach(event => {
        document.addEventListener(event, function() {
            hasInteracted = true;
            isBounce = false;
        }, { once: true });
    });
    
    // Update time on page periodically
    setInterval(function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
    }, 1000);
    
    // Send data when page is about to unload
    window.addEventListener('beforeunload', function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
        
        // Use sendBeacon for reliable delivery
        const data = JSON.stringify({
            time_on_page: timeOnPage,
            is_bounce: isBounce && !hasInteracted && timeOnPage < 30
        });
        
        navigator.sendBeacon(
            '{{ route("analytics.update-page-view", ":id") }}'.replace(':id', pageViewId),
            data
        );
    });
    
    // Also send on visibility change (tab switch)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            timeOnPage = Math.floor((Date.now() - startTime) / 1000);
            fetch('{{ route("analytics.update-page-view", ":id") }}'.replace(':id', pageViewId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    time_on_page: timeOnPage,
                    is_bounce: isBounce && !hasInteracted && timeOnPage < 30
                })
            }).catch(() => {}); // Ignore errors
        }
    });
})();
@endif
</script>



</body>
</html>
