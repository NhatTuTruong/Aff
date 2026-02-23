@extends('layouts.app')

@section('title', 'Deals & Coupons - ' . config('app.name'))
@section('description', 'Browse the latest hot deals and coupons from verified brands.')

@push('styles')
<style>
    .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

    .deals-hero {
        padding: 3.5rem 0 2.5rem;
        background: radial-gradient(900px 350px at 50% 0%, rgba(34,197,94,0.16) 0%, rgba(34,197,94,0.00) 70%);
        border-bottom: 1px solid var(--border);
    }
    .deals-hero-inner { text-align: center; }
    .deals-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.03em;
    }
    .deals-hero p {
        color: var(--text-muted);
        margin: 0.75rem auto 0;
        max-width: 760px;
        font-size: 1.05rem;
    }
    .deals-disclaimer {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.75rem;
        max-width: 820px;
        margin-left: auto;
        margin-right: auto;
    }
    .deals-disclaimer a { color: var(--accent); text-decoration: underline; }

    .deals-toolbar { margin-top: 1.5rem; display: flex; justify-content: center; }
    .deals-search {
        width: 100%;
        max-width: 720px;
        display: flex;
        gap: 0.75rem;
        background: rgba(255,255,255,0.95);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 0.5rem;
        box-shadow: 0 6px 24px rgba(17,24,39,0.06);
    }
    .deals-search input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        padding: 0.75rem 0.9rem;
        font-size: 1rem;
        color: var(--text);
    }
    .deals-search button {
        border: none;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: #fff;
        padding: 0.75rem 1.1rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
    }
    .deals-search button:hover { opacity: 0.95; }

    .deals-wrap { padding: 2rem 0 3rem; }
    .deals-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
    }
    @media (max-width: 1024px) { .deals-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 640px) {
        .deals-grid { grid-template-columns: 1fr; }
        .deals-search { flex-direction: column; }
        .deals-search button { width: 100%; }
    }

    .deal-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        padding: 1.1rem 1.1rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
        min-height: 100%;
    }
    .deal-card:hover {
        border-color: rgba(34,197,94,0.5);
        box-shadow: 0 12px 28px rgba(17,24,39,0.08);
        transform: translateY(-2px);
    }
    .deal-topline { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
    .deal-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        min-width: 0;
    }
    .deal-logo {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .deal-logo img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
    .deal-logo-placeholder { font-size: 0.85rem; font-weight: 800; color: var(--text-muted); }
    .deal-brand-name {
        font-weight: 800;
        font-size: 0.95rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--text-dark);
    }
    .deal-date { font-size: 0.8rem; color: var(--text-muted); white-space: nowrap; }
    .deal-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -0.02em;
        margin-top: 0.1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .deal-offer {
        font-size: 0.95rem;
        color: var(--text);
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .deal-meta { font-size: 0.85rem; color: var(--text-muted); }
    .deal-actions {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .deal-code-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.7rem;
        border-radius: 999px;
        border: 1px dashed #ca8a04;
        background: #fefce8;
        color: #a16207;
        font-size: 0.82rem;
        cursor: pointer;
        font-family: ui-monospace, monospace;
        max-width: 100%;
    }
    .deal-code-btn strong { font-weight: 800; overflow: hidden; text-overflow: ellipsis; }
    .deal-code-btn.copied { border-color: var(--accent); background: #dcfce7; color: #166534; }
    .deal-code-btn.copied::after {
        content: '✓';
        margin-left: 0.35rem;
        color: var(--accent);
        font-weight: 900;
        font-family: system-ui, sans-serif;
    }
    .deal-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.55rem 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }
    .deal-cta:hover { opacity: 0.95; }
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
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
    <header class="deals-hero">
        <div class="container deals-hero-inner">
            <h1 class="font-heading">Deals & Coupons</h1>
            <p>Browse trending offers across verified brands — updated regularly.</p>
            <p class="deals-disclaimer">
                Offers may expire or change. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.
            </p>
            <div class="deals-toolbar">
                <form action="{{ route('deals.index') }}" method="get" class="deals-search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search deals, brands, or offers…" autocomplete="off">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </header>

    <div class="deals-wrap">
        <div class="container">
            @if($deals->count() > 0)
                <div class="deals-grid">
                    @foreach($deals as $coupon)
                        @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                        @if($campaign && $brand)
                            @php
                                $slugParts = explode('/', $campaign->slug, 2);
                                $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                $slugPart = count($slugParts) === 2 ? $slugParts[1] : $campaign->slug;
                                $landingUrl = route('landing.show', ['userCode' => $userCode, 'slug' => $slugPart]);
                            @endphp
                            <article class="deal-card">
                                <div class="deal-topline">
                                    <div class="deal-brand">
                                        <div class="deal-logo">
                                            @if($brand->image)
                                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('images/placeholder.svg') }}" alt="{{ $brand->name }}" loading="lazy">
                                            @endif
                                        </div>
                                        <span class="deal-brand-name">{{ $brand->name }}</span>
                                    </div>
                                    <span class="deal-date">{{ $coupon->created_at?->format('d/m/Y') }}</span>
                                </div>

                                <div class="deal-content">
                                    <div class="deal-title">{{ $campaign->title ?? $brand->name }}</div>
                                    @if($coupon->offer)
                                        <div class="deal-offer">{{ $coupon->offer }}</div>
                                    @elseif($coupon->description)
                                        <div class="deal-offer">{{ Str::limit($coupon->description, 160) }}</div>
                                    @endif
                                    <div class="deal-meta">View the coupon page to see details and how to use the offer.</div>
                                </div>

                                <div class="deal-actions">
                                    @if($coupon->code)
                                        <button type="button" class="deal-code-btn" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);">
                                            <span>Code:</span>
                                            <strong>{{ $coupon->code }}</strong>
                                        </button>
                                    @endif
                                    <a href="{{ $landingUrl }}" class="deal-cta">View coupon</a>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
                <div class="pagination-wrap">
                    {{ $deals->links('vendor.pagination.simple') }}
                </div>
            @else
                <div class="empty-state">
                    No deals or coupons available yet. Please check back later!
                </div>
            @endif
        </div>
    </div>
@endsection

