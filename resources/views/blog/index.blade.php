@extends('layouts.app')

@section('title', 'Savings Journal - ' . config('app.name'))
@section('description', 'Insights, coupon updates, and practical saving tactics.')

@push('styles')
@include('partials.peel-sticker-styles')
<style>
    .blog-page {
        --bp-ink: #0f172a;
        --bp-muted: #64748b;
        --bp-line: rgba(15, 23, 42, 0.08);
        --bp-violet: #2fc2a9;
        --bp-violet-deep: #24a892;
        --bp-rose: #1e9680;
        --bp-surface: #ffffff;
        --bp-cream: #f0faf8;
        background: #f0faf8;
        background-image:
            radial-gradient(120% 80% at 80% 0%, rgba(47, 194, 169, 0.18) 0%, transparent 58%),
            radial-gradient(90% 60% at 10% 100%, rgba(47, 194, 169, 0.12) 0%, transparent 55%);
        color: var(--bp-ink);
    }
    .blog-page .bp-shell {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .bp-hero {
        position: relative;
        padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(1.75rem, 3vw, 2.5rem);
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        background:
            linear-gradient(145deg, #1e9680 0%, #24a892 48%, #2fc2a9 100%);
        backdrop-filter: blur(4px);
        color: #f8fafc;
    }
    .bp-hero-grid {
        display: grid;
        gap: 2rem;
        align-items: end;
    }
    @media (min-width: 880px) {
        .bp-hero-grid {
            grid-template-columns: 1fr minmax(280px, 38%);
            gap: 2.5rem;
            align-items: end;
        }
    }
    .bp-hero-kicker {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #bae6fd;
        margin-bottom: 0.65rem;
    }
    .bp-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        font-weight: 700;
        letter-spacing: -0.045em;
        line-height: 1.05;
        margin: 0 0 0.9rem;
        max-width: 12ch;
        color: #ffffff;
    }
    .bp-hero-lead {
        margin: 0;
        font-size: clamp(1rem, 1.25vw, 1.1rem);
        color: rgba(241, 245, 249, 0.92);
        line-height: 1.6;
        max-width: 36rem;
    }

    .bp-search-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(148, 163, 184, 0.35);
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 16px 36px -22px rgba(2, 6, 23, 0.5);
    }
    .bp-search-form {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }
    @media (min-width: 520px) {
        .bp-search-form { flex-direction: row; align-items: stretch; }
    }
    .bp-search-form input {
        flex: 1;
        border: 1px solid var(--bp-line);
        border-radius: 14px;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        outline: none;
        background: #fafafa;
        color: var(--bp-ink);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .bp-search-form input:focus {
        border-color: rgba(47, 194, 169, 0.45);
        box-shadow: 0 0 0 3px rgba(47, 194, 169, 0.12);
        background: #fff;
    }
    .bp-search-form button {
        border: none;
        border-radius: 14px;
        padding: 0.85rem 1.25rem;
        font-weight: 700;
        cursor: pointer;
        color: #fff;
        background: linear-gradient(135deg, #2fc2a9 0%, #24a892 100%);
        white-space: nowrap;
        box-shadow: 0 10px 26px -10px rgba(47, 194, 169, 0.5);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .bp-search-form button:hover {
        transform: translateY(-1px);
        filter: brightness(1.06) saturate(1.02);
        box-shadow: 0 14px 30px -10px rgba(47, 194, 169, 0.45);
    }

    .bp-chips {
        margin-top: 1.35rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        justify-content: flex-start;
    }
    @media (min-width: 880px) {
        .bp-chips { justify-content: flex-end; }
    }
    .bp-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.42rem 0.95rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--bp-ink);
        background: rgba(15, 23, 42, 0.38);
        border: 1px solid rgba(186, 230, 253, 0.28);
        color: #e2e8f0;
        transition: border-color 0.2s, background 0.2s, color 0.2s;
    }
    .bp-chip:hover {
        border-color: rgba(186, 230, 253, 0.7);
        color: #ffffff;
    }
    .bp-chip--on {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(186, 230, 253, 0.8);
        color: #0f172a;
    }

    .bp-main {
        padding: clamp(2rem, 4vw, 3rem) 0 clamp(2.5rem, 5vw, 4rem);
    }
    .bp-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.15rem;
    }
    @media (min-width: 720px) {
        .bp-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .bp-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .bp-card {
        display: flex;
        flex-direction: column;
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background: var(--bp-surface);
        border: 1px solid var(--bp-line);
        box-shadow: 0 14px 28px -18px rgba(15, 23, 42, 0.32);
        min-height: 100%;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.2s;
    }
    .bp-card:hover {
        transform: translateY(-3px);
        border-color: rgba(47, 194, 169, 0.38);
        box-shadow: 0 18px 34px -18px rgba(47, 194, 169, 0.28);
    }
    .bp-card--feature {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: 1fr;
    }
    @media (min-width: 800px) {
        .bp-card--feature {
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
            min-height: 280px;
        }
    }
    .bp-card-media {
        position: relative;
        background: #dbe7f4;
        min-height: 200px;
    }
    @media (min-width: 800px) {
        .bp-card--feature .bp-card-media { min-height: 100%; }
    }
    .bp-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        min-height: 200px;
    }
    .bp-card--feature .bp-card-media img {
        min-height: 280px;
    }
    @media (max-width: 799px) {
        .bp-card--feature .bp-card-media img { min-height: 220px; }
    }
    .bp-card-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, var(--bp-violet) 0%, var(--bp-rose) 100%);
        box-shadow: 0 8px 20px -8px rgba(11, 23, 36, 0.45);
    }
    .bp-card-date {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        padding: 0.35rem 0.65rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--bp-ink);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(6px);
    }
    .bp-card-body {
        padding: 1.2rem 1.25rem 1.3rem;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        flex: 1;
    }
    .bp-card--feature .bp-card-body {
        justify-content: center;
        padding: clamp(1.25rem, 3vw, 2rem);
    }
    .bp-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .bp-card-tag {
        display: inline-flex;
        max-width: 70%;
        padding: 0.28rem 0.6rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--bp-violet-deep);
        background: rgba(47, 194, 169, 0.1);
        border: 1px solid rgba(47, 194, 169, 0.22);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .bp-card-date-inline {
        font-size: 0.78rem;
        color: var(--bp-muted);
        white-space: nowrap;
        font-weight: 600;
    }
    .bp-card-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.12rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        line-height: 1.28;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bp-card--feature .bp-card-title {
        font-size: clamp(1.35rem, 2.5vw, 1.75rem);
        -webkit-line-clamp: 3;
    }
    .bp-card-excerpt {
        font-size: 0.95rem;
        color: var(--bp-muted);
        line-height: 1.55;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bp-card--feature .bp-card-excerpt {
        -webkit-line-clamp: 4;
    }
    .bp-card-cta {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--bp-violet-deep);
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .bp-card:hover .bp-card-cta { color: var(--bp-rose); }

    .bp-pagination {
        margin-top: 2.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .bp-pagination .pagination-list {
        gap: 0.35rem !important;
    }
    .bp-pagination .pagination-item:not(.pagination-ellipsis) {
        border-radius: 12px !important;
        border-color: var(--bp-line) !important;
        background: var(--bp-surface) !important;
        font-weight: 600 !important;
    }
    .bp-pagination .pagination-item:hover:not(.pagination-disabled):not(.pagination-current) {
        border-color: rgba(47, 194, 169, 0.35) !important;
        color: var(--bp-violet-deep) !important;
    }
    .bp-pagination .pagination-current {
        border-color: rgba(47, 194, 169, 0.45) !important;
        color: var(--bp-violet-deep) !important;
    }
    .bp-pagination .pagination-info {
        color: var(--bp-muted) !important;
    }

    .bp-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        border-radius: 24px;
        border: 1px dashed var(--bp-line);
        background: rgba(255, 255, 255, 0.75);
        color: var(--bp-muted);
        max-width: 520px;
        margin: 0 auto;
    }
    .bp-empty strong {
        display: block;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.2rem;
        color: var(--bp-ink);
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="blog-page">
    <header class="bp-hero">
        <div class="bp-shell bp-hero-grid">
            <div>
                <p class="bp-hero-kicker">Insights &amp; updates</p>
                <h1 class="font-heading">Savings Journal</h1>
                <p class="bp-hero-lead">Actionable deal breakdowns, shopping tactics, and weekly updates to help you save with less guesswork.</p>
            </div>
            <div>
                <div class="bp-search-card">
                    <form action="{{ route('blog.index') }}" method="get" class="bp-search-form">
                        <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search articles…" autocomplete="off">
                        @if(!empty($selectedCategory))
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                        @endif
                        <button type="submit">Search</button>
                    </form>
                </div>
                @if(isset($categories) && $categories->count() > 0)
                    @php
                        $baseParams = [];
                        if (!empty($searchQuery)) {
                            $baseParams['q'] = $searchQuery;
                        }
                    @endphp
                    <nav class="bp-chips" aria-label="Blog categories">
                        <a class="bp-chip {{ empty($selectedCategory) ? 'bp-chip--on' : '' }}" href="{{ route('blog.index', $baseParams) }}">All</a>
                        @foreach($categories as $cat)
                            <a class="bp-chip {{ ($selectedCategory ?? '') === $cat ? 'bp-chip--on' : '' }}"
                               href="{{ route('blog.index', array_merge($baseParams, ['category' => $cat])) }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <div class="bp-main">
        <div class="bp-shell">
            @if($posts->count() > 0)
                <div class="bp-grid">
                    @foreach($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="bp-card {{ $loop->first ? 'bp-card--feature' : '' }}">
                            <div class="bp-card-media">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                @if($loop->first)
                                    <span class="bp-card-badge">Editor's pick</span>
                                @endif
                                <span class="bp-card-date">{{ $post->created_at?->format('d M Y') }}</span>
                            </div>
                            <div class="bp-card-body">
                                @unless($loop->first)
                                    <div class="bp-card-top">
                                        @if(!empty($post->category))
                                            <span class="bp-card-tag">{{ $post->category }}</span>
                                        @else
                                            <span class="bp-card-tag">Update</span>
                                        @endif
                                        <span class="bp-card-date-inline">{{ $post->created_at?->format('d/m/Y') }}</span>
                                    </div>
                                @endunless
                                <h2 class="bp-card-title">{{ $post->title }}</h2>
                                @if($post->content)
                                    <p class="bp-card-excerpt">{{ Str::limit(trim(strip_tags($post->content)), $loop->first ? 280 : 160) }}</p>
                                @endif
                                <span class="bp-card-cta">Open insight →</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="bp-pagination">
                    {{ $posts->links('vendor.pagination.simple') }}
                </div>
            @else
                <div class="bp-empty">
                    <strong>No stories yet</strong>
                    New articles will appear here once they are published. Check back soon.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
