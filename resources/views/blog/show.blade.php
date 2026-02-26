@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content ?? ''), 160))

@push('styles')
<style>
    :root {
        --blog-bg: #ffffff;
        --blog-surface: #ffffff;
        --blog-border: rgba(15, 23, 42, 0.12);
        --blog-text: #0f172a;
        --blog-muted: #64748b;
        --blog-accent: #16a34a;
        --blog-accent-soft: rgba(22, 163, 74, 0.10);
    }

    body {
        background: #ffffff;
    }

    .blog-shell {
        max-width: 1120px;
        margin: 0 auto 3.5rem;
        padding: 1.75rem 1.25rem 3rem;
    }

    @media (min-width: 1024px) {
        .blog-shell {
            padding-top: 2.5rem;
        }
    }

    .blog-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--blog-muted);
        margin-bottom: 1.25rem;
    }

    .blog-breadcrumb a {
        color: var(--blog-muted);
        text-decoration: none;
    }
    .blog-breadcrumb a:hover {
        color: var(--blog-accent);
    }

    .blog-hero {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid var(--blog-border);
        background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.10), transparent 55%),
                    radial-gradient(circle at bottom right, rgba(56, 189, 248, 0.08), transparent 60%),
                    #ffffff;
        display: grid;
        grid-template-columns: minmax(0, 3fr) minmax(0, 2.5fr);
        gap: 0;
    }

    @media (max-width: 900px) {
        .blog-hero {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    .blog-hero-main {
        padding: 1.75rem 1.75rem 1.75rem;
        position: relative;
        z-index: 1;
    }

    .blog-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.2rem 0.75rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid var(--blog-border);
        font-size: 0.75rem;
        color: var(--blog-muted);
        margin-bottom: 0.85rem;
        backdrop-filter: blur(10px);
    }

    .blog-hero-eyebrow span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .blog-title {
        font-family: 'Space Grotesk', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-size: clamp(1.9rem, 3vw, 2.5rem);
        font-weight: 750;
        letter-spacing: -0.03em;
        line-height: 1.25;
        color: var(--blog-text);
        margin-bottom: 0.85rem;
    }

    .blog-meta {
        font-size: 0.9rem;
        color: var(--blog-muted);
        max-width: 38rem;
    }

    .blog-meta a {
        color: var(--blog-accent);
        text-decoration: underline;
    }

    .blog-hero-media {
        position: relative;
        min-height: 200px;
        background: radial-gradient(circle at center, rgba(2, 132, 199, 0.08), rgba(22, 163, 74, 0.06));
        overflow: hidden;
    }

    .blog-hero-media-inner {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: stretch;
        justify-content: center;
    }

    .blog-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.03);
        filter: saturate(1.1);
    }

    .blog-hero-media-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(255, 255, 255, 0.92), transparent 55%),
                    linear-gradient(to top, rgba(255, 255, 255, 0.5), transparent 45%);
    }

    .blog-hero-media-fallback {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--blog-muted);
        font-size: 0.8rem;
    }

    .blog-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
        gap: 2.25rem;
        margin-top: 2rem;
    }

    @media (max-width: 900px) {
        .blog-main-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 2rem;
        }
    }

    .blog-main {
        min-width: 0;
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid var(--blog-border);
        padding: 1.75rem 1.75rem 2rem;
        box-shadow: 0 18px 60px rgba(15, 23, 42, 0.08);
    }

    @media (max-width: 640px) {
        .blog-main {
            padding: 1.5rem 1.25rem 1.75rem;
        }
    }

    .blog-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
        color: var(--blog-muted);
        text-decoration: none;
        font-size: 0.82rem;
    }

    .blog-back span.icon {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        border: 1px solid var(--blog-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
    }

    .blog-back:hover {
        color: var(--blog-accent);
    }

    .blog-chip-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .blog-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        border: 1px solid var(--blog-border);
        background: #ffffff;
        color: var(--blog-muted);
        font-size: 0.8rem;
    }

    .blog-chip-accent {
        border-color: rgba(22, 163, 74, 0.35);
        background: var(--blog-accent-soft);
        color: #166534;
    }

    .blog-share-button {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        border: 1px solid var(--blog-border);
        background: #ffffff;
        cursor: pointer;
        font-size: 0.8rem;
        color: var(--blog-muted);
    }

    .blog-share-button:hover {
        border-color: var(--blog-accent);
        color: var(--blog-accent);
    }

    @media (max-width: 640px) {
        .blog-share-button {
            margin-left: 0;
        }
    }

    .blog-content.prose {
        color: var(--blog-text);
        font-size: 0.98rem;
        line-height: 1.8;
    }

    .blog-content.prose h2,
    .blog-content.prose h3,
    .blog-content.prose h4 {
        font-family: 'Space Grotesk', system-ui, sans-serif;
        font-weight: 750;
        letter-spacing: -0.02em;
        margin: 1.75rem 0 0.75rem;
        line-height: 1.3;
        color: var(--blog-text);
    }

    .blog-content.prose h2 { font-size: 1.25rem; }
    .blog-content.prose h3 { font-size: 1.05rem; }

    .blog-content.prose p { margin: 0.9rem 0; }
    .blog-content.prose ul,
    .blog-content.prose ol { margin: 0.75rem 0 1.1rem; padding-left: 1.3rem; }
    .blog-content.prose li { margin: 0.35rem 0; }

    .blog-content.prose a {
        color: var(--blog-accent);
        text-decoration: underline;
    }

    .blog-content.prose img {
        max-width: 100%;
        border-radius: 0.9rem;
        border: 1px solid var(--blog-border);
    }

    .blog-side-media {
        margin-top: 1.75rem;
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    }

    .blog-side-media img {
        width: 100%;
        height: auto;
        border-radius: 0.75rem;
        border: 1px solid var(--blog-border);
    }

    .blog-side-media video {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid var(--blog-border);
    }

    .blog-aside {
        min-width: 0;
        border-radius: 1.25rem;
        border: 1px solid var(--blog-border);
        background: #ffffff;
        padding: 1.5rem 1.5rem 1.75rem;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        position: sticky;
        top: 1.5rem;
        height: fit-content;
    }

    @media (max-width: 900px) {
        .blog-aside {
            position: static;
        }
    }

    .blog-aside-title {
        font-family: 'Space Grotesk', system-ui, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--blog-text);
    }

    .blog-aside-deals {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .blog-deal-card {
        background: #ffffff;
        border: 1px solid var(--blog-border);
        border-radius: 0.9rem;
        padding: 0.85rem 0.95rem;
        position: relative;
        overflow: hidden;
    }

    .blog-deal-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), transparent 55%);
        opacity: 0;
        transition: opacity 0.18s;
        pointer-events: none;
    }

    .blog-deal-card:hover::before {
        opacity: 1;
    }

    .blog-deal-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.45rem;
    }

    .blog-deal-logo,
    .blog-deal-logo-placeholder {
        width: 34px;
        height: 34px;
        border-radius: 0.6rem;
        background: #ffffff;
        border: 1px solid var(--blog-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--blog-muted);
        flex-shrink: 0;
        overflow: hidden;
    }

    .blog-deal-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .blog-deal-brand {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--blog-text);
        max-width: 170px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .blog-deal-offer {
        font-size: 0.8rem;
        color: var(--blog-muted);
        margin-bottom: 0.55rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-deal-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    .blog-deal-code {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.6rem;
        border-radius: 0.45rem;
        border: 1px dashed rgba(253, 224, 71, 0.9);
        background: rgba(250, 250, 46, 0.10);
        font-size: 0.75rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
        color: #facc15;
        cursor: pointer;
    }

    .blog-deal-code-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.9;
    }

    .blog-deal-code.copied {
        background: var(--blog-accent-soft);
        border-color: var(--blog-accent);
        color: #166534;
    }

    .blog-deal-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--blog-accent), #22c55e);
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
    }

    .blog-deal-cta:hover {
        filter: brightness(1.03);
    }

    .blog-aside-empty {
        font-size: 0.85rem;
        color: var(--blog-muted);
        padding-top: 0.5rem;
    }

    .related-blogs {
        margin-top: 2.75rem;
        padding-top: 2.25rem;
        border-top: 1px solid rgba(148, 163, 184, 0.35);
    }

    .related-blogs-title {
        font-family: 'Space Grotesk', system-ui, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        color: var(--blog-text);
    }

    .related-blogs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.2rem;
    }

    .related-blog-card {
        text-decoration: none;
        color: inherit;
        border-radius: 0.9rem;
        border: 1px solid var(--blog-border);
        background: #ffffff;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    }

    .related-blog-card:hover {
        border-color: var(--blog-accent);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.7);
        transform: translateY(-1px);
    }

    .related-blog-card-thumb,
    .related-blog-card-thumb-placeholder {
        width: 100%;
        height: 140px;
        object-fit: cover;
        background: radial-gradient(circle at center, rgba(15, 23, 42, 0.06), rgba(15, 23, 42, 0.02));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--blog-muted);
        font-size: 0.78rem;
    }

    .related-blog-card-body {
        padding: 0.9rem 1rem 1rem;
    }

    .related-blog-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--blog-text);
        margin-bottom: 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-blog-card-meta {
        font-size: 0.8rem;
        color: var(--blog-muted);
    }
</style>
@endpush

@section('content')
    @php
        $wordCount = str_word_count(strip_tags($post->content ?? ''));
        $readingMinutes = max(1, (int) ceil($wordCount / 220));
    @endphp

    <div class="blog-shell">
        <div class="blog-breadcrumb">
            <a href="{{ route('blog.index') }}">Blog</a>
            <span>/</span>
            <span>{{ Str::limit($post->title, 40) }}</span>
        </div>

        <section class="blog-hero">
            <div class="blog-hero-main">
                <div class="blog-hero-eyebrow">
                    @if($post->category)
                        <span><span>🗂</span><span>{{ $post->category }}</span></span>
                    @endif
                    <span><span>📅</span><span>{{ $post->created_at?->format('d/m/Y') }}</span></span>
                    <span><span>⏱</span><span>{{ $readingMinutes }} min read</span></span>
                </div>
                <h1 class="blog-title">{{ $post->title }}</h1>
                <p class="blog-meta">
                    Offers may expire. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.
                </p>
            </div>

            <div class="blog-hero-media">
                <div class="blog-hero-media-inner">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" loading="eager">
                    @else
                        <div class="blog-hero-media-fallback">
                            Article · {{ config('app.name') }}
                        </div>
                    @endif
                </div>
                <div class="blog-hero-media-overlay"></div>
            </div>
        </section>

        <div class="blog-main-grid">
            <article class="blog-main">
                <a href="{{ route('blog.index') }}" class="blog-back">
                    <span class="icon">←</span>
                    <span>Back to all articles</span>
                </a>

                <div class="blog-chip-row">
                    @if(!empty($post->category))
                        <span class="blog-chip blog-chip-accent">{{ $post->category }}</span>
                    @endif
                    <span class="blog-chip">{{ $post->created_at?->format('d/m/Y') }}</span>
                    <span class="blog-chip">{{ $readingMinutes }} min read</span>
                    <button type="button" class="blog-share-button"
                        onclick="navigator.clipboard.writeText(window.location.href); this.textContent='Link copied'; setTimeout(() => this.textContent='Copy link', 1200);">
                        <span>🔗</span>
                        <span>Copy link</span>
                    </button>
                </div>

                <div class="blog-content prose">
                    {!! $post->content !!}
                </div>

                @if($post->images && count($post->images) > 0)
                    <div class="blog-side-media">
                        @foreach($post->images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="" loading="lazy">
                        @endforeach
                    </div>
                @endif

                @if($post->videos && count($post->videos) > 0)
                    <div class="blog-side-media">
                        @foreach($post->videos as $video)
                            <video controls preload="metadata">
                                <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                            </video>
                        @endforeach
                    </div>
                @endif
            </article>

            <aside class="blog-aside">
                <h2 class="blog-aside-title">
                    @if($post->category)
                        Deals for {{ $post->category }}
                    @else
                        Hot deals for this topic
                    @endif
                </h2>

                @if(isset($sidebarDeals) && $sidebarDeals->isNotEmpty())
                    <div class="blog-aside-deals">
                        @foreach($sidebarDeals as $coupon)
                            @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                            @if($brand)
                                @php
                                    $slugParts = $campaign ? explode('/', $campaign->slug, 2) : ['00000', ''];
                                    $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
                                    $slugPart = count($slugParts) === 2 ? $slugParts[1] : '';
                                    $dealUrl = $campaign && $campaign->affiliate_url
                                        ? route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart])
                                        : '#';
                                @endphp
                                <article class="blog-deal-card">
                                    <div class="blog-deal-header">
                                        <div class="blog-deal-logo">
                                            @if($brand->image)
                                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}">
                                            @else
                                                <span class="blog-deal-logo-placeholder">
                                                    {{ Str::upper(Str::substr($brand->name, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="blog-deal-brand">{{ $brand->name }}</div>
                                    </div>
                                    @if($coupon->offer)
                                        <p class="blog-deal-offer">{{ $coupon->offer }}</p>
                                    @endif
                                    <div class="blog-deal-actions">
                                        @if($coupon->code)
                                            <button type="button" class="blog-deal-code"
                                                onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);"
                                                title="Click to copy">
                                                <span class="blog-deal-code-label">Code</span>
                                                <span class="blog-deal-code-value">{{ $coupon->code }}</span>
                                            </button>
                                        @endif
                                        @if($dealUrl !== '#')
                                            <a href="{{ $dealUrl }}" class="blog-deal-cta" target="_blank" rel="nofollow sponsored noopener">
                                                <span>Get deal</span>
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="blog-aside-empty">
                        @if($post->category)
                            There are no deals in this category yet: “{{ $post->category }}”.
                        @else
                            There are no highlighted deals yet for this article.
                        @endif
                    </p>
                @endif
            </aside>
        </div>

        @if(isset($relatedBlogs) && $relatedBlogs->isNotEmpty())
            <section class="related-blogs">
                <h2 class="related-blogs-title">Related articles</h2>
                <div class="related-blogs-grid">
                    @foreach($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="related-blog-card">
                            @if($related->featured_image)
                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="related-blog-card-thumb" loading="lazy">
                            @else
                                <div class="related-blog-card-thumb-placeholder">Blog</div>
                            @endif
                            <div class="related-blog-card-body">
                                <h3 class="related-blog-card-title">{{ $related->title }}</h3>
                                <p class="related-blog-card-meta">{{ $related->created_at?->format('d/m/Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
