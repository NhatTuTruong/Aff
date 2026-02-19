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
    #coupons { scroll-margin-top: 5rem; }
    #stores { scroll-margin-top: 5rem; }

    .stores-carousel-wrap {
        overflow: hidden;
        margin: 0 -1.5rem;
        padding: 0 1.5rem;
    }
    .stores-carousel-track {
        display: flex;
        width: max-content;
        animation: storesScroll 30s linear infinite;
    }
    .stores-carousel-track:hover {
        animation-play-state: paused;
    }
    @keyframes storesScroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .stores-carousel {
        display: flex;
        gap: 1rem;
        padding: 0.5rem 0;
    }
    .store-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
        width: 160px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    .store-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent), var(--accent-hover));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    .store-card:hover::before {
        transform: scaleX(1);
    }
    .store-card:hover {
        border-color: var(--accent);
        background: white;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.15), 0 4px 6px -2px rgba(34, 197, 94, 0.1);
    }
    .store-card img {
        width: 72px;
        height: 72px;
        object-fit: contain;
        border-radius: 12px;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 0.5rem;
        transition: transform 0.3s ease;
        border: 1px solid var(--border);
    }
    .store-card:hover img {
        transform: scale(1.1);
    }
    .store-card .name {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.35rem;
        color: var(--text-dark);
    }
    .store-card .category {
        font-size: 0.85rem;
        color: var(--text-muted);
        padding: 0.25rem 0.75rem;
        background: var(--surface);
        border-radius: 20px;
        display: inline-block;
    }

    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .coupon-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    .coupon-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%);
        border-radius: 0 16px 0 100%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .coupon-card:hover::after {
        opacity: 1;
    }
    .coupon-card:hover { 
        border-color: var(--accent); 
        background: white;
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -8px rgba(34, 197, 94, 0.2), 0 4px 6px -2px rgba(34, 197, 94, 0.1);
    }
    .coupon-card .brand-logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        border-radius: 12px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        flex-shrink: 0;
        padding: 0.5rem;
        border: 1px solid var(--border);
        transition: transform 0.3s ease;
    }
    .coupon-card:hover .brand-logo {
        transform: scale(1.1);
    }
    .coupon-card .body { 
        flex: 1; 
        min-width: 0;
        position: relative;
        z-index: 1;
    }
    .coupon-card .brand-name {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }
    .coupon-card .code {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px dashed var(--accent);
        color: var(--accent);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        margin-top: 0.75rem;
        font-family: 'Space Grotesk', monospace;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .coupon-card .code:hover {
        background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .coupon-card .code::after {
        content: '📋';
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .coupon-card .code:hover::after {
        opacity: 1;
    }
    .coupon-card .offer {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        font-weight: 500;
    }
    .coupon-card .link {
        color: var(--accent);
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        margin-top: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        padding: 0.5rem 0;
    }
    .coupon-card .link:hover { 
        color: var(--accent-hover);
        gap: 0.75rem;
    }
    .coupon-card .link::after {
        content: '→';
        transition: transform 0.3s ease;
    }
    .coupon-card .link:hover::after {
        transform: translateX(4px);
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
            gap: 0.75rem;
        }
        .store-card {
            width: 140px;
            padding: 1.25rem;
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
    .store-card, .coupon-card {
        animation: fadeInUp 0.5s ease-out backwards;
    }
    .store-card:nth-child(1) { animation-delay: 0.05s; }
    .store-card:nth-child(2) { animation-delay: 0.1s; }
    .store-card:nth-child(3) { animation-delay: 0.15s; }
    .store-card:nth-child(4) { animation-delay: 0.2s; }
    .coupon-card:nth-child(1) { animation-delay: 0.1s; }
    .coupon-card:nth-child(2) { animation-delay: 0.15s; }
    .coupon-card:nth-child(3) { animation-delay: 0.2s; }
</style>
@endpush

@section('content')
    <section class="hero">
        <div class="container">
            <h1 class="font-heading">Discover Amazing Deals & Store Reviews</h1>
            <p>Find the best coupon codes, exclusive promotions, and trusted store reviews. Save more with verified deals updated daily.</p>
            <form action="{{ url('/') }}" method="get" class="search-box">
                <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search stores, brands, or deals..." autocomplete="off">
                <button type="submit">
                    <span>🔍</span> Search
                </button>
            </form>
        </div>
    </section>

    @if($brands->count() > 0 || $hotCoupons->isNotEmpty())
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $brands->count() }}+</div>
                    <div class="stat-label">Verified Stores</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $hotCoupons->count() }}+</div>
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

    <section class="section" id="stores">
        <div class="container">
            <h2 class="section-title">Featured Stores</h2>
            <p class="section-subtitle">Browse through our curated collection of trusted stores and discover exclusive deals</p>
            @if($brands->count() > 0)
                <div class="stores-carousel-wrap">
                    <div class="stores-carousel-track">
                        <div class="stores-carousel">
                            @foreach($brands as $brand)
                                @php 
                                    $reviewSlug = $brand->campaigns->first()?->slug;
                                    if ($reviewSlug) {
                                        $slugParts = explode('/', $reviewSlug, 2);
                                        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $reviewSlug;
                                        $reviewUrl = route('landing.show', ['userCode' => $userCode, 'slug' => $slugPart]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand->name);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-card">
                                    @if($brand->image)
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                                    @else
                                        <div style="width:72px;height:72px;border-radius:12px;background:linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:1.25rem;font-weight:700;margin-bottom:1rem;border:1px solid var(--border);">{{ Str::limit($brand->name, 2) }}</div>
                                    @endif
                                    <span class="name">{{ $brand->name }}</span>
                                    @if($brand->category)
                                        <span class="category">{{ $brand->category->name }}</span>
                                    @endif
                                    @if($reviewSlug)
                                        <span style="margin-top:0.5rem;font-size:0.75rem;color:var(--accent);font-weight:600;">View Deal →</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        <div class="stores-carousel">
                            @foreach($brands as $brand)
                                @php 
                                    $reviewSlug = $brand->campaigns->first()?->slug;
                                    if ($reviewSlug) {
                                        $slugParts = explode('/', $reviewSlug, 2);
                                        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $reviewSlug;
                                        $reviewUrl = route('landing.show', ['userCode' => $userCode, 'slug' => $slugPart]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand->name);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-card">
                                    @if($brand->image)
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                                    @else
                                        <div style="width:72px;height:72px;border-radius:12px;background:linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:1.25rem;font-weight:700;margin-bottom:1rem;border:1px solid var(--border);">{{ Str::limit($brand->name, 2) }}</div>
                                    @endif
                                    <span class="name">{{ $brand->name }}</span>
                                    @if($brand->category)
                                        <span class="category">{{ $brand->category->name }}</span>
                                    @endif
                                    @if($reviewSlug)
                                        <span style="margin-top:0.5rem;font-size:0.75rem;color:var(--accent);font-weight:600;">View Deal →</span>
                                    @endif
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
                    <h3>
                        @if($searchQuery)
                            No stores found for "{{ $searchQuery }}"
                        @else
                            No stores available yet
                        @endif
                    </h3>
                    <p>
                        @if($searchQuery)
                            Try searching with a different keyword or browse all stores.
                        @else
                            Check back soon for amazing deals and store reviews!
                        @endif
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
            <div class="coupons-grid">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <div class="coupon-card">
                        @if($brand->image)
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="brand-logo" loading="lazy">
                        @else
                            <div class="brand-logo" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.875rem;font-weight:600;">{{ Str::limit($brand->name, 2) }}</div>
                        @endif
                        <div class="body">
                            <div class="brand-name">{{ $brand->name }}</div>
                            @if($coupon->code)
                                <code class="code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.style.background='linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)'; setTimeout(() => this.style.background='', 1000);" title="Click to copy">
                                    {{ $coupon->code }}
                                </code>
                            @endif
                            @if($coupon->offer)
                                <div class="offer">🎁 {{ $coupon->offer }}</div>
                            @endif
                            @if($campaign && $campaign->affiliate_url)
                                @php
                                    $slugParts = explode('/', $campaign->slug, 2);
                                    $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                    $slugPart = count($slugParts) === 2 ? $slugParts[1] : $campaign->slug;
                                @endphp
                                <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}" class="link" target="_blank" rel="noopener">Get Deal</a>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
