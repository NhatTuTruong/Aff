@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
<style>
    .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    .hero {
        padding: 4rem 0 3rem;
        text-align: center;
    }
    .hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }
    .hero p {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
    }
    .search-box {
        max-width: 560px;
        margin: 0 auto;
        display: flex;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-box:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    }
    .search-box input {
        flex: 1;
        padding: 1rem 1.25rem;
        background: transparent;
        border: none;
        color: var(--text);
        font-size: 1rem;
        outline: none;
    }
    .search-box input::placeholder { color: var(--text-muted); }
    .search-box button {
        padding: 1rem 1.5rem;
        background: var(--accent);
        color: var(--bg);
        border: none;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .search-box button:hover { background: var(--accent-hover); }

    .section { padding: 3rem 0; }
    .section-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-title::before {
        content: '';
        width: 4px;
        height: 1.5rem;
        background: var(--accent);
        border-radius: 2px;
    }
    #coupons { scroll-margin-top: 5rem; }
    #stores { scroll-margin-top: 5rem; }

    .stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1.25rem;
    }
    .store-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: border-color 0.2s, transform 0.2s, background 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .store-card:hover {
        border-color: var(--accent);
        background: var(--surface-hover);
        transform: translateY(-2px);
    }
    .store-card img {
        width: 64px;
        height: 64px;
        object-fit: contain;
        border-radius: var(--radius-sm);
        margin-bottom: 0.75rem;
        background: var(--bg);
    }
    .store-card .name {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    .store-card .category {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    .coupon-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: border-color 0.2s, background 0.2s;
    }
    .coupon-card:hover { border-color: var(--accent); background: var(--surface-hover); }
    .coupon-card .brand-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
        border-radius: var(--radius-sm);
        background: var(--bg);
        flex-shrink: 0;
    }
    .coupon-card .body { flex: 1; min-width: 0; }
    .coupon-card .brand-name {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    .coupon-card .code {
        display: inline-block;
        background: var(--bg);
        border: 1px dashed var(--accent);
        color: var(--accent);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    .coupon-card .offer {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 0.35rem;
    }
    .coupon-card .link {
        color: var(--accent);
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .coupon-card .link:hover { text-decoration: underline; }

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
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
    <section class="hero">
        <div class="container">
            <h1 class="font-heading">Coupons & Store Reviews</h1>
            <p>Search for stores, hot promo codes and trusted reviews. Updated daily.</p>
            <form action="{{ url('/') }}" method="get" class="search-box">
                <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search stores (name, slug)..." autocomplete="off">
                <button type="submit">Search</button>
            </form>
        </div>
    </section>

    @if($hotCoupons->isNotEmpty())
    <section class="section" id="coupons">
        <div class="container">
            <h2 class="section-title">Hot Coupons</h2>
            <div class="coupons-grid">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <div class="coupon-card">
                        @if($brand->image)
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="brand-logo">
                        @else
                            <div class="brand-logo" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.75rem;">Logo</div>
                        @endif
                        <div class="body">
                            <div class="brand-name">{{ $brand->name }}</div>
                            @if($coupon->code)<code class="code">{{ $coupon->code }}</code>@endif
                            @if($coupon->offer)<div class="offer">{{ $coupon->offer }}</div>@endif
                            @if($campaign && $campaign->affiliate_url)
                                <a href="{{ route('click.redirect', $campaign->slug) }}" class="link" target="_blank" rel="noopener">View Deal →</a>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="section" id="stores">
        <div class="container">
            <h2 class="section-title">Stores</h2>
            @if($brands->count() > 0)
                <div class="stores-grid">
                    @foreach($brands as $brand)
                        @php $reviewSlug = $brand->campaigns->first()?->slug; @endphp
                        <a href="{{ $reviewSlug ? route('landing.show', $reviewSlug) : url('/') . '?q=' . urlencode($brand->name) }}" class="store-card">
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}">
                            @else
                                <div style="width:64px;height:64px;border-radius:var(--radius-sm);background:var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.7rem;margin-bottom:0.75rem;">{{ Str::limit($brand->name, 2) }}</div>
                            @endif
                            <span class="name">{{ $brand->name }}</span>
                            @if($brand->category)<span class="category">{{ $brand->category->name }}</span>@endif
                        </a>
                    @endforeach
                </div>
                <div class="pagination-wrap">
                    {{ $brands->links() }}
                </div>
            @else
                <div class="empty-state">
                    @if($searchQuery)
                        No stores found for "{{ $searchQuery }}". Try a different keyword.
                    @else
                        No stores available yet. Please check back later!
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
