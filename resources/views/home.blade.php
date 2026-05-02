@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
<style>
    .home-page {
        --home-primary: #059669;
        --home-primary-dark: #047857;
        --home-primary-soft: #d1fae5;
        --home-accent: #ea580c;
        --home-accent-hover: #c2410c;
        --home-border: #e2e8f0;
        --home-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 12px 24px -8px rgba(15, 23, 42, 0.1);
        --home-ribbon: linear-gradient(180deg, #0d9488 0%, #059669 55%, #047857 100%);
        background: linear-gradient(180deg, #f1f5f9 0%, #f8fafc 40%, #eef2f7 100%);
    }
    .container { max-width: 1140px; margin: 0 auto; padding: 0 1.5rem; }

    .hero {
        position: relative;
        padding: 3rem 0 2.5rem;
        text-align: center;
        overflow: hidden;
    }
    .hero-inner {
        max-width: 920px;
        margin: 0 auto;
        padding: 2.75rem 2rem 2.5rem;
        background: #ffffff;
        border-radius: 22px;
        border: 1px solid var(--home-border);
        box-shadow: var(--home-shadow);
        position: relative;
        overflow: hidden;
    }
    .hero-inner::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: min(42%, 340px);
        height: 100%;
        background: radial-gradient(ellipse at 100% 0%, rgba(5, 150, 105, 0.1) 0%, transparent 65%);
        pointer-events: none;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 15% 40%, rgba(5, 150, 105, 0.08) 0%, transparent 45%),
                    radial-gradient(circle at 85% 70%, rgba(234, 88, 12, 0.06) 0%, transparent 50%);
        pointer-events: none;
    }
    .hero .container { position: relative; z-index: 1; }
    .hero-badge {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--home-primary-dark);
        background: var(--home-primary-soft);
        border: 1px solid rgba(5, 150, 105, 0.25);
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        margin-bottom: 1rem;
    }
    .hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.85rem, 4.5vw, 2.85rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.15;
        margin-bottom: 1rem;
        color: #0f172a;
        position: relative;
        max-width: 38rem;
        margin-left: auto;
        margin-right: auto;
    }
    .hero p {
        color: #64748b;
        font-size: clamp(1rem, 2vw, 1.08rem);
        margin-bottom: 1.5rem;
        max-width: 34rem;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
        position: relative;
    }
    .hero-trust-line {
        font-size: 0.8125rem;
        color: #94a3b8;
        margin-top: -0.75rem;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--home-border);
        position: relative;
    }
    .hero-trust-line a { color: var(--home-primary); font-weight: 600; text-decoration: underline; text-underline-offset: 2px; }
    .hero-trust-line a:hover { color: var(--home-primary-dark); }

    .search-box {
        max-width: 520px;
        margin: 0 auto;
        display: flex;
        background: #f8fafc;
        border: 2px solid var(--home-border);
        border-radius: 999px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-box:focus-within {
        border-color: var(--home-primary);
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
    }
    .search-box input {
        flex: 1;
        padding: 1.1rem 1.35rem;
        background: transparent;
        border: none;
        color: var(--text);
        font-size: 1rem;
        outline: none;
    }
    .search-box input::placeholder {
        color: var(--text-muted);
        opacity: 0.75;
    }
    .search-box button {
        padding: 0.95rem 1.5rem;
        margin: 4px;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--home-accent) 0%, var(--home-accent-hover) 100%);
        color: white;
        border: none;
        font-weight: 800;
        font-size: 0.875rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.35);
    }
    .search-box button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(234, 88, 12, 0.4);
    }

    .stats-section {
        padding: 1.5rem 0 2.5rem;
        background: transparent;
    }
    .stats-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.75rem 1rem;
    }
    .stats-section .container {
        background: transparent;
        padding: 0;
        border: none;
        box-shadow: none;
    }
    .stat-item {
        display: inline-flex;
        align-items: baseline;
        gap: 0.5rem;
        padding: 0.85rem 1.35rem;
        background: #fff;
        border: 1px solid var(--home-border);
        border-radius: 14px;
        box-shadow: var(--home-shadow);
    }
    .stat-number {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
    }
    .stat-label {
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .section {
        padding: 3.25rem 0;
        position: relative;
    }
    .section--surface {
        background: #fff;
        border-top: 1px solid var(--home-border);
        border-bottom: 1px solid var(--home-border);
        box-shadow: 0 1px 0 rgba(255,255,255,0.9);
    }
    .section-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.5rem, 3.5vw, 2rem);
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #0f172a;
        letter-spacing: -0.03em;
    }
    .section-title::before {
        content: '';
        width: 4px;
        height: 1.75rem;
        background: var(--home-ribbon);
        border-radius: 4px;
        flex-shrink: 0;
    }
    .section-eyebrow {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--home-primary);
        margin-bottom: 0.35rem;
    }
    .section-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 1.75rem;
        max-width: 36rem;
        line-height: 1.55;
    }
    .deals-disclaimer {
        font-size: 0.8125rem;
        color: #64748b;
        margin-top: -0.5rem;
        margin-bottom: 1.5rem;
        max-width: 720px;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid var(--home-border);
    }
    .deals-disclaimer a { color: var(--home-primary); font-weight: 600; text-decoration: underline; text-underline-offset: 2px; }
    .deals-disclaimer a:hover { color: var(--home-primary-dark); }
    #coupons { scroll-margin-top: 5rem; }
    #stores { scroll-margin-top: 5rem; }
    #blog { scroll-margin-top: 5rem; }
    #categories { scroll-margin-top: 5rem; }

    .popular-categories {
        padding: 3.5rem 0;
        background: linear-gradient(155deg, #0f172a 0%, #134e4a 45%, #0f172a 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .popular-categories::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 80% 50% at 20% 0%, rgba(16, 185, 129, 0.2) 0%, transparent 55%);
        pointer-events: none;
    }
    .popular-categories .container { position: relative; z-index: 1; }
    .popular-categories .section-title { color: #fff; }
    .popular-categories .section-title::before {
        background: linear-gradient(180deg, #34d399 0%, #059669 100%);
        box-shadow: 0 0 20px rgba(52, 211, 153, 0.35);
    }
    .categories-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.65rem 0.85rem;
    }
    .category-pill {
        display: inline-block;
        padding: 0.55rem 1.15rem;
        border: 1px solid rgba(255,255,255,0.22);
        border-radius: 9999px;
        color: #f1f5f9;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        transition: border-color 0.2s, background 0.2s, transform 0.2s;
    }
    .category-pill:hover {
        border-color: #34d399;
        background: rgba(16, 185, 129, 0.15);
        transform: translateY(-1px);
        color: #fff;
    }

    .stores-panel {
        background: #fff;
        border: 1px solid var(--home-border);
        border-radius: 20px;
        padding: 1.75rem 1.25rem 1.5rem;
        box-shadow: var(--home-shadow);
    }
    .stores-carousel-wrap {
        overflow: hidden;
        margin: 0 -0.25rem;
        padding: 0 0.25rem;
        cursor: grab;
        user-select: none;
    }
    .stores-carousel-wrap:active {
        cursor: grabbing;
    }
    .stores-carousel-track {
        display: flex;
        width: max-content;
        transition: transform 0.1s ease-out;
    }
    .stores-carousel-wrap.dragging .stores-carousel-track {
        transition: none;
    }
    .stores-carousel {
        display: flex;
        align-items: flex-start;
        gap: 2rem;
        padding: 0.5rem 1rem 0.5rem 0;
    }
    .store-carousel-item {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: inherit;
        width: 96px;
        transition: transform 0.2s ease;
    }
    .store-carousel-item:hover {
        transform: translateY(-3px);
    }
    .store-carousel-img-wrap {
        width: 76px;
        height: 76px;
        border-radius: 16px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid var(--home-border);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        flex-shrink: 0;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .store-carousel-item:hover .store-carousel-img-wrap {
        border-color: rgba(5, 150, 105, 0.35);
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.12);
    }
    .store-carousel-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 6px;
    }
    .store-carousel-placeholder {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-muted);
        background: #ffffff;
    }
    .store-carousel-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        text-align: center;
        line-height: 1.25;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    .post-card-home {
        display: flex;
        flex-direction: column;
        border: 1px solid var(--home-border);
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        background: #fff;
        box-shadow: var(--home-shadow);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .post-card-home:hover {
        border-color: rgba(5, 150, 105, 0.35);
        box-shadow: 0 16px 40px -12px rgba(15, 23, 42, 0.15);
        transform: translateY(-3px);
    }
    .post-card-home-img-wrap {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f1f5f9;
    }
    .post-card-home-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .post-card-home-thumb-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.75rem;
        height: 100%;
        min-height: 140px;
    }
    .post-card-home-content {
        padding: 1.1rem 1.15rem 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .post-card-home-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0 0 0.5rem;
        line-height: 1.35;
        color: #0f172a;
        letter-spacing: -0.02em;
    }
    .post-card-home-meta {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
        margin-top: auto;
        font-weight: 500;
    }
    .post-card-home-read {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--home-primary);
        margin-top: 0.65rem;
    }
    .post-card-home:hover .post-card-home-read {
        color: var(--home-primary-dark);
    }

    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    .coupon-card {
        display: grid;
        grid-template-columns: 72px 1fr;
        background: #fff;
        border: 1px solid var(--home-border);
        border-radius: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
        box-shadow: var(--home-shadow);
        animation: fadeInUp 0.5s ease-out backwards;
    }
    .coupon-card:hover {
        border-color: rgba(5, 150, 105, 0.35);
        box-shadow: 0 14px 36px -10px rgba(15, 23, 42, 0.14);
        transform: translateY(-2px);
    }
    .coupon-card-strip {
        background: var(--home-ribbon);
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 0.35rem;
        text-align: center;
        font-weight: 800;
        font-size: 0.65rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        line-height: 1.2;
        gap: 0.2rem;
    }
    .coupon-card-strip-icon {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
        line-height: 1;
    }
    .coupon-card-main {
        padding: 1rem 1rem 1rem 0.85rem;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .coupon-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.45rem;
    }
    .coupon-card-logo {
        width: 42px;
        height: 42px;
        object-fit: contain;
        border-radius: 10px;
        background: #f8fafc;
        padding: 4px;
        border: 1px solid var(--home-border);
        flex-shrink: 0;
    }
    .coupon-card-logo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
    }
    .coupon-card-brand {
        font-weight: 700;
        font-size: 0.92rem;
        color: #0f172a;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coupon-card-offer {
        font-size: 0.84rem;
        color: #64748b;
        margin: 0 0 0.75rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .coupon-card-actions {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: auto;
    }
    .coupon-card-code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.7rem;
        background: #ecfdf5;
        border: 1px dashed var(--home-primary);
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--home-primary-dark);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-family: ui-monospace, monospace;
    }
    .coupon-card-code:hover {
        background: #d1fae5;
        border-color: var(--home-primary-dark);
    }
    .coupon-card-code.copied {
        background: #d1fae5;
        border-color: var(--home-primary);
        color: #065f46;
    }
    .coupon-card-code-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.85;
    }
    .coupon-card-code-value {
        letter-spacing: 0.02em;
    }
    .coupon-card-code-copy {
        font-size: 0.65rem;
        opacity: 0.85;
    }
    .coupon-card-code.copied .coupon-card-code-copy {
        display: none;
    }
    .coupon-card-code.copied::after {
        content: '✓';
        margin-left: 0.25rem;
        color: var(--home-primary);
    }
    .coupon-card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 1rem;
        background: linear-gradient(135deg, var(--home-accent) 0%, var(--home-accent-hover) 100%);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-radius: 10px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.28);
    }
    .coupon-card-cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(234, 88, 12, 0.35);
        color: #fff;
    }

    .pagination-wrap {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    .pagination-wrap nav a, .pagination-wrap nav span {
        display: inline-block;
        padding: 0.45rem 0.8rem;
        margin: 0 0.12rem;
        background: #fff;
        border: 1px solid var(--home-border);
        border-radius: 10px;
        color: var(--text);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
    }
    .pagination-wrap nav a:hover {
        border-color: var(--home-primary);
        color: var(--home-primary);
    }
    .pagination-wrap nav span { color: #94a3b8; }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px dashed var(--home-border);
    }
    .empty-state svg {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        opacity: 0.45;
        color: #94a3b8;
    }
    .empty-state h3 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .coupon-card:nth-child(1) { animation-delay: 0.05s; }
    .coupon-card:nth-child(2) { animation-delay: 0.1s; }
    .coupon-card:nth-child(3) { animation-delay: 0.15s; }

    @media (max-width: 768px) {
        .hero {
            padding: 2.25rem 0 2rem;
        }
        .hero-inner {
            padding: 2rem 1.35rem;
            border-radius: 18px;
        }
        .search-box {
            flex-direction: column;
            border-radius: 14px;
        }
        .search-box button {
            margin: 0 4px 4px;
            width: calc(100% - 8px);
        }
        .stores-carousel {
            gap: 1.25rem;
        }
        .store-carousel-item {
            width: 84px;
        }
        .store-carousel-img-wrap {
            width: 64px;
            height: 64px;
        }
        .categories-wrap {
            gap: 0.5rem;
        }
        .category-pill {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .coupons-grid {
            grid-template-columns: 1fr;
        }
        .stat-item {
            flex: 1 1 calc(50% - 0.5rem);
            justify-content: center;
        }
    }
    @media (max-width: 520px) {
        .coupon-card {
            grid-template-columns: 1fr;
        }
        .coupon-card-strip {
            flex-direction: row;
            gap: 0.5rem;
            padding: 0.65rem 1rem;
        }
        .coupon-card-main {
            padding: 1rem 1rem 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="home-page">
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
            <p class="hero-badge">Verified deals &amp; reviews</p>
            <h1 class="font-heading">Discover Amazing Deals & Store Reviews</h1>
            <p>Find the best coupon codes, exclusive promotions, and trusted store reviews. Save more with verified deals updated daily.</p>
            <p class="hero-trust-line">Independent deal finder. We may earn from qualifying purchases. <a href="{{ url('/affiliate-disclosure') }}">Learn more</a>.</p>
            <form action="{{ url('/') }}" method="get" class="search-box">
                <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search stores, brands, or deals..." autocomplete="off">
                <button type="submit">
                    <span>🔍</span> Search
                </button>
            </form>
            </div>
        </div>
    </section>

    @if(($verifiedBrandsCount ?? 0) > 0 || $hotCoupons->isNotEmpty())
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">
                        {{ $verifiedBrandsCount ?? 0 }}+
                    </div>
                    <div class="stat-label">Verified Brands</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $activeCouponsCount ?? $hotCoupons->count() }}+</div>
                    <div class="stat-label">Active Coupons</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Trusted Reviews</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">Daily</div>
                    <div class="stat-label">Updated Deals</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="section section--surface" id="blog">
        <div class="container">
            <p class="section-eyebrow">From the blog</p>
            <h2 class="section-title">Latest Blog Posts</h2>
            <p class="section-subtitle">Recent articles and updates</p>
            <div class="posts-grid">
                @foreach($latestPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="post-card-home">
                        <div class="post-card-home-img-wrap">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="post-card-home-thumb" loading="lazy">
                        </div>
                        <div class="post-card-home-content">
                            <h3 class="post-card-home-title">{{ $post->title }}</h3>
                            <p class="post-card-home-meta">{{ $post->created_at?->format('d M Y') }}</p>
                            <span class="post-card-home-read">Read article →</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="section-subtitle" style="margin-top:1.5rem; margin-bottom:0;">
                <a href="{{ route('blog.index') }}" style="color:#059669;font-weight:700;">View all posts →</a>
            </p>
        </div>
    </section>
    @endif

    <section class="section" id="stores">
        <div class="container">
            <p class="section-eyebrow">Shop smarter</p>
            <h2 class="section-title">Featured Stores</h2>
            <p class="section-subtitle">Click a store to go straight to its coupon page</p>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="stores-panel">
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
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3>No campaigns available yet</h3>
                    <p>
                        Check back soon for new campaigns and deals!
                    </p>
                </div>
            @endif
        </div>
    </section>

    @if($hotCoupons->isNotEmpty())
    <section class="section section--surface" id="coupons">
        <div class="container">
            <p class="section-eyebrow">Limited time</p>
            <h2 class="section-title">🔥 Hot Coupons & Deals</h2>
            <p class="section-subtitle">Don't miss out on these exclusive offers and limited-time promotions</p>
            <p class="deals-disclaimer">Offers may expire or change. Verify discount at the store checkout. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.</p>
            <div class="coupons-grid">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <article class="coupon-card">
                        <div class="coupon-card-strip" aria-hidden="true">
                            <span class="coupon-card-strip-icon">%</span>
                            <span>{{ $coupon->code ? 'Code' : 'Deal' }}</span>
                        </div>
                        <div class="coupon-card-main">
                        <div class="coupon-card-header">
                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" class="coupon-card-logo" loading="lazy">
                            <div class="coupon-card-brand">{{ $brand->name }}</div>
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
                                <a href="{{ route('click.redirect', ['slug' => $campaign->slug]) }}" class="coupon-card-cta" target="_blank" rel="noopener">Get Deal</a>
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
    <section class="popular-categories" id="categories">
        <div class="container">
            <p class="section-eyebrow" style="color:rgba(255,255,255,0.75);">Browse by topic</p>
            <h2 class="section-title">Popular Categories</h2>
            <div class="categories-wrap">
                @foreach($popularCategories as $cat)
                    @php
                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : ($cat['slug'] ?? '');
                        $url = $catSlug ? url('/?cat=' . $catSlug) . '#stores' : url('/') . '#stores';
                    @endphp
                    <a href="{{ $url }}" class="category-pill">{{ $catName }}</a>
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
        var direction = -1; // -1: scroll left, 1: scroll right
        var step = 0.6; // pixels per tick
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
</div>
@endsection
