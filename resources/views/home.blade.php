@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
<style>
    .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    
    /* Hero Section - Modern & Professional */
    .hero {
        position: relative;
        padding: 6rem 0 5rem;
        text-align: center;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 25%, #ffffff 50%, #f0f9ff 75%, #e0f2fe 100%);
        background-size: 200% 200%;
        animation: gradientShift 15s ease infinite;
        overflow: hidden;
    }
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    .hero .container {
        position: relative;
        z-index: 1;
    }
    .hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.5rem, 6vw, 4rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1.1;
        margin-bottom: 1.25rem;
        background: linear-gradient(135deg, #111827 0%, #16a34a 50%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% auto;
        animation: gradientText 3s ease infinite;
    }
    @keyframes gradientText {
        0%, 100% { background-position: 0% center; }
        50% { background-position: 100% center; }
    }
    .hero p {
        color: var(--text-muted);
        font-size: clamp(1rem, 2vw, 1.25rem);
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }
    .hero-trust-line {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: -1.5rem;
        margin-bottom: 1.5rem;
    }
    .hero-trust-line a { color: var(--accent); text-decoration: underline; }
    .hero-trust-line a:hover { color: var(--accent-hover); }
    
    /* Enhanced Search Box */
    .search-box {
        max-width: 640px;
        margin: 0 auto;
        display: flex;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 2px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .search-box:focus-within {
        border-color: var(--accent);
        box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.2), 0 4px 6px -2px rgba(34, 197, 94, 0.1);
        transform: translateY(-2px);
    }
    .search-box input {
        flex: 1;
        padding: 1.25rem 1.5rem;
        background: transparent;
        border: none;
        color: var(--text);
        font-size: 1.05rem;
        outline: none;
    }
    .search-box input::placeholder { 
        color: var(--text-muted);
        opacity: 0.7;
    }
    .search-box button {
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: white;
        border: none;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .search-box button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    .search-box button:hover::before {
        left: 100%;
    }
    .search-box button:hover { 
        background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent) 100%);
        transform: scale(1.02);
    }
    
    /* Stats Section */
    .stats-section {
        padding: 3rem 0;
        background: var(--surface);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        text-align: center;
    }
    .stat-item {
        padding: 1.5rem;
    }
    .stat-number {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        color: var(--text-muted);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .section { 
        padding: 4rem 0;
        position: relative;
    }
    .section-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.25rem);
        font-weight: 700;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--text-dark);
    }
    .section-title::before {
        content: '';
        width: 5px;
        height: 2rem;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        border-radius: 3px;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }
    .section-subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-top: -1rem;
        margin-bottom: 2rem;
        max-width: 600px;
    }
    .deals-disclaimer {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: -1rem;
        margin-bottom: 1.5rem;
        max-width: 720px;
    }
    .deals-disclaimer a { color: var(--accent); text-decoration: underline; }
    .deals-disclaimer a:hover { color: var(--accent-hover); }
    #coupons { scroll-margin-top: 5rem; }
    #stores { scroll-margin-top: 5rem; }
    #blog { scroll-margin-top: 5rem; }
    #categories { scroll-margin-top: 5rem; }

    /* Popular Categories - dark section, pill tags */
    .popular-categories {
        padding: 4rem 0;
        background: #0a0a0a;
        color: #fff;
    }
    .popular-categories .section-title { color: #fff; }
    .popular-categories .section-title::before { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .categories-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem 1.25rem;
    }
    .category-pill {
        display: inline-block;
        padding: 0.6rem 1.25rem;
        border: 1px solid rgba(255,255,255,0.6);
        border-radius: 9999px;
        color: #fff;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: border-color 0.2s, background 0.2s;
    }
    .category-pill:hover {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.1);
    }

    /* Featured Stores - carousel 1 row, only image rounded, name below */
    .stores-carousel-wrap {
        overflow: hidden;
        margin: 0 -1.5rem;
        padding: 0 1.5rem;
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
        width: 90px;
        transition: transform 0.2s ease;
    }
    .store-carousel-item:hover {
        transform: translateY(-2px);
    }
    .store-carousel-img-wrap {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        flex-shrink: 0;
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
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dark);
        text-align: center;
        line-height: 1.25;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Latest Blog Posts - home grid */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .post-card-home {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .post-card-home:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
    }
    .post-card-home-thumb {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
        background: var(--surface);
    }
    .post-card-home-thumb-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.75rem;
    }
    .post-card-home-content { flex: 1; min-width: 0; }
    .post-card-home-brand {
        font-size: 0.8rem;
        color: var(--accent);
        font-weight: 500;
    }
    .post-card-home-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0.25rem 0 0.35rem;
        line-height: 1.35;
        color: var(--text-dark);
    }
    .post-card-home-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Hot Coupons - compact professional cards */
    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .coupon-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .coupon-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--accent), var(--accent-hover));
        border-radius: 4px 0 0 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .coupon-card:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(34, 197, 94, 0.08);
    }
    .coupon-card:hover::before {
        opacity: 1;
    }
    .coupon-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .coupon-card-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 10px;
        background: var(--surface);
        padding: 4px;
        border: 1px solid var(--border);
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
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-dark);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coupon-card-offer {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0 0 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .coupon-card-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .coupon-card-code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.65rem;
        background: #fefce8;
        border: 1px dashed #ca8a04;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #a16207;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-family: ui-monospace, monospace;
    }
    .coupon-card-code:hover {
        background: #fef9c3;
        border-color: var(--accent);
        color: #854d0e;
    }
    .coupon-card-code.copied {
        background: #dcfce7;
        border-color: var(--accent);
        color: #166534;
    }
    .coupon-card-code-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        opacity: 0.9;
    }
    .coupon-card-code-value {
        letter-spacing: 0.02em;
    }
    .coupon-card-code-copy {
        font-size: 0.7rem;
        opacity: 0.8;
    }
    .coupon-card-code.copied .coupon-card-code-copy {
        display: none;
    }
    .coupon-card-code.copied::after {
        content: '✓';
        margin-left: 0.25rem;
        color: var(--accent);
    }
    .coupon-card-cta {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.85rem;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 6px;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.2s;
    }
    .coupon-card-cta:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    .pagination-wrap {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    .pagination-wrap nav a, .pagination-wrap nav span {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        margin: 0 0.15rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        text-decoration: none;
        font-size: 0.9rem;
    }
    .pagination-wrap nav a:hover { border-color: var(--accent); color: var(--accent); }
    .pagination-wrap nav span { color: var(--text-muted); }
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--text-muted);
    }
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        opacity: 0.5;
    }
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }
    
    /* Responsive Improvements */
    @media (max-width: 768px) {
        .hero {
            padding: 4rem 0 3rem;
        }
        .hero h1 {
            font-size: 2rem;
        }
        .search-box {
            flex-direction: column;
            border-radius: 12px;
        }
        .search-box input {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .search-box button {
            padding: 1rem;
            border-radius: 0 0 12px 12px;
        }
        .stores-carousel {
            gap: 1.25rem;
        }
        .store-carousel-item {
            width: 76px;
        }
        .store-carousel-img-wrap {
            width: 60px;
            height: 60px;
        }
        .store-carousel-name {
            font-size: 0.75rem;
        }
        .categories-wrap {
            gap: 0.75rem;
        }
        .category-pill {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .coupons-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }
    
    /* Loading Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .coupon-card {
        animation: fadeInUp 0.5s ease-out backwards;
    }
    .coupon-card:nth-child(1) { animation-delay: 0.05s; }
    .coupon-card:nth-child(2) { animation-delay: 0.1s; }
    .coupon-card:nth-child(3) { animation-delay: 0.15s; }
</style>
@endpush

@section('content')
    <section class="hero">
        <div class="container">
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
    <section class="section" id="blog">
        <div class="container">
            <h2 class="section-title">Latest Blog Posts</h2>
            <p class="section-subtitle">Recent articles and updates</p>
            <div class="posts-grid">
                @foreach($latestPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="post-card-home">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="post-card-home-thumb" loading="lazy">
                        @else
                            <div class="post-card-home-thumb post-card-home-thumb-placeholder">Blog</div>
                        @endif
                        <div class="post-card-home-content">
                            <h3 class="post-card-home-title">{{ $post->title }}</h3>
                            <p class="post-card-home-meta">{{ $post->created_at?->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="section-subtitle" style="margin-top:1.5rem;">
                <a href="{{ route('blog.index') }}" style="color:var(--accent);font-weight:600;">View all posts →</a>
            </p>
        </div>
    </section>
    @endif

    <section class="section" id="stores">
        <div class="container">
            <h2 class="section-title">Featured Stores</h2>
            <p class="section-subtitle">Click a campaign to go straight to its coupon page</p>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="stores-carousel-wrap">
                    <div class="stores-carousel-track">
                        <div class="stores-carousel">
                            @foreach($featuredCampaigns as $campaign)
                                @php 
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $slugParts = explode('/', $reviewSlug, 2);
                                        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $reviewSlug;
                                        $reviewUrl = route('landing.show', ['userCode' => $userCode, 'slug' => $slugPart]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand && $brand->image)
                                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" alt="{{ $brand?->name ?? $campaign->title }}" loading="lazy">
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
                                        $slugParts = explode('/', $reviewSlug, 2);
                                        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $reviewSlug;
                                        $reviewUrl = route('landing.show', ['userCode' => $userCode, 'slug' => $slugPart]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand && $brand->image)
                                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" alt="{{ $brand?->name ?? $campaign->title }}" loading="lazy">
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
    <section class="section" id="coupons">
        <div class="container">
            <h2 class="section-title">🔥 Hot Coupons & Deals</h2>
            <p class="section-subtitle">Don't miss out on these exclusive offers and limited-time promotions</p>
            <p class="deals-disclaimer">Offers may expire or change. Verify discount at the store checkout. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.</p>
            <div class="coupons-grid">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <article class="coupon-card">
                        <div class="coupon-card-header">
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="coupon-card-logo" loading="lazy">
                            @else
                                <img src="{{ asset('images/placeholder.svg') }}" alt="{{ $brand->name }}" class="coupon-card-logo" loading="lazy">
                            @endif
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
                                @php
                                    $slugParts = explode('/', $campaign->slug, 2);
                                    $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                    $slugPart = count($slugParts) === 2 ? $slugParts[1] : $campaign->slug;
                                @endphp
                                <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}" class="coupon-card-cta" target="_blank" rel="noopener">Get Deal</a>
                            @endif
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

        function clamp(x, min, max) { return Math.min(Math.max(x, min), max); }

        wrap.addEventListener('pointerdown', function(e) {
            dragging = true;
            didDrag = false;
            startX = e.clientX;
            startTx = currentTx;
            wrap.classList.add('dragging');
        });
        document.addEventListener('pointermove', function(e) {
            if (!dragging) return;
            var dx = e.clientX - startX;
            if (Math.abs(dx) > 4) didDrag = true;
            e.preventDefault();
            var maxTx = 0;
            var minTx = -(track.offsetWidth - wrap.offsetWidth);
            if (minTx > 0) minTx = 0;
            currentTx = clamp(startTx + dx, minTx, maxTx);
            track.style.transform = 'translateX(' + currentTx + 'px)';
        });
        document.addEventListener('pointerup', function() {
            dragging = false;
            wrap.classList.remove('dragging');
        });
        document.addEventListener('pointercancel', function() {
            dragging = false;
            wrap.classList.remove('dragging');
        });
        wrap.addEventListener('click', function(e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);
    })();
    </script>
    @endpush
    @endif
@endsection
