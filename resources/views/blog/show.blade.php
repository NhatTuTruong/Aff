@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content ?? ''), 160))

@push('styles')
<style>
    .blog-layout {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem 3rem;
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 2.5rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .blog-layout { grid-template-columns: 1fr; }
        .related-blogs-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
    }
    .blog-main { min-width: 0; }
    .blog-header { padding: 2rem 0 1.5rem; }
    .blog-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 0.75rem;
    }
    .blog-meta { font-size: 0.9rem; color: var(--text-muted); }
    .blog-featured {
        margin: 1.5rem 0;
        border-radius: 12px;
        overflow: hidden;
        background: var(--surface);
    }
    .blog-featured img {
        width: 100%;
        height: auto;
        display: block;
    }
    .blog-content {
        font-size: 1.05rem;
        line-height: 1.75;
    }
    .blog-content :deep(img) { max-width: 100%; height: auto; border-radius: 8px; }
    .blog-content :deep(video) { max-width: 100%; border-radius: 8px; }
    .blog-content :deep(blockquote) {
        border-left: 4px solid var(--accent);
        padding-left: 1rem;
        margin: 1.5rem 0;
        color: var(--text-muted);
    }
    .blog-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
    }
    .blog-back:hover { color: var(--accent-hover); }

    .blog-sidebar {
        position: sticky;
        top: 1.5rem;
    }
    .blog-sidebar-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }
    .blog-sidebar-deals {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .blog-deal-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
    }
    .blog-deal-card::before {
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
    .blog-deal-card:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(34, 197, 94, 0.08);
    }
    .blog-deal-card:hover::before {
        opacity: 1;
    }
    .blog-deal-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.5rem;
    }
    .blog-deal-card-logo {
        width: 36px;
        height: 36px;
        object-fit: contain;
        border-radius: 8px;
        background: var(--surface);
        padding: 3px;
        border: 1px solid var(--border);
        flex-shrink: 0;
    }
    .blog-deal-card-logo-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        flex-shrink: 0;
        border: 1px solid var(--border);
    }
    .blog-deal-card-brand {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-dark);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .blog-deal-card-offer {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0 0 0.6rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-deal-card-actions {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .blog-deal-card-code {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.55rem;
        background: #fefce8;
        border: 1px dashed #ca8a04;
        border-radius: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #a16207;
        font-family: ui-monospace, monospace;
    }
    .blog-deal-card-code-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        opacity: 0.9;
    }
    .blog-deal-card-code-value {
        letter-spacing: 0.02em;
    }
    .blog-deal-card-code.copied {
        background: #dcfce7;
        border-color: var(--accent);
        color: #166534;
    }
    .blog-deal-card-code.copied::after {
        content: '✓';
        margin-left: 0.25rem;
        color: var(--accent);
    }
    .blog-deal-card-cta {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.7rem;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 5px;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .blog-deal-card-cta:hover {
        opacity: 0.95;
    }
    .blog-sidebar-empty {
        font-size: 0.9rem;
        color: var(--text-muted);
        padding: 1rem 0;
    }

    /* Related Blogs */
    .related-blogs {
        margin-top: 3rem;
        padding-top: 2.5rem;
        border-top: 2px solid var(--border);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-bottom: 1.5rem;
    }
    .related-blogs-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
    }
    .related-blogs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    .related-blog-card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .related-blog-card:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(34, 197, 94, 0.08);
    }
    .related-blog-card-thumb {
        width: 100%;
        height: 140px;
        object-fit: cover;
        background: var(--surface);
    }
    .related-blog-card-thumb-placeholder {
        width: 100%;
        height: 140px;
        background: var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 0.75rem;
    }
    .related-blog-card-body {
        padding: 1rem;
    }
    .related-blog-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.35;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-blog-card-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
    <div class="blog-layout">
        <article class="blog-main">
            <a href="{{ route('blog.index') }}" class="blog-back">← List of articles</a>
            <header class="blog-header">
                <h1 class="blog-title">{{ $post->title }}</h1>
                <p class="blog-meta">{{ $post->created_at?->format('d/m/Y') }}</p>
            </header>
            @if($post->featured_image)
                <div class="blog-featured">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" loading="eager">
                </div>
            @endif
            <div class="blog-content prose">
                {!! $post->content !!}
            </div>
            @if($post->images && count($post->images) > 0)
                <div class="blog-gallery" style="margin-top: 2rem; display: grid; gap: 1rem; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                    @foreach($post->images as $img)
                        <img src="{{ asset('storage/' . $img) }}" alt="" loading="lazy" style="border-radius: 8px; width: 100%; height: auto;">
                    @endforeach
                </div>
            @endif
            @if($post->videos && count($post->videos) > 0)
                <div class="blog-videos" style="margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($post->videos as $video)
                        <video controls style="max-width: 100%; border-radius: 8px;">
                            <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                        </video>
                    @endforeach
                </div>
            @endif
        </article>

        <aside class="blog-sidebar">
            <h2 class="blog-sidebar-title">
                @if($post->category)
                    Deal {{ $post->category }}
                @else
                    Hot Deals
                @endif
            </h2>
            @if(isset($sidebarDeals) && $sidebarDeals->isNotEmpty())
                <div class="blog-sidebar-deals">
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
                                <div class="blog-deal-card-header">
                                    @if($brand->image)
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="blog-deal-card-logo">
                                    @else
                                        <span class="blog-deal-card-logo-placeholder">{{ Str::limit($brand->name, 2) }}</span>
                                    @endif
                                    <span class="blog-deal-card-brand">{{ $brand->name }}</span>
                                </div>
                                @if($coupon->offer)
                                    <p class="blog-deal-card-offer">{{ $coupon->offer }}</p>
                                @endif
                                <div class="blog-deal-card-actions">
                                    @if($coupon->code)
                                        <button type="button" class="blog-deal-card-code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);" title="Click to copy">
                                            <span class="blog-deal-card-code-label">Code</span>
                                            <span class="blog-deal-card-code-value">{{ $coupon->code }}</span>
                                        </button>
                                    @endif
                                    @if($dealUrl !== '#')
                                        <a href="{{ $dealUrl }}" class="blog-deal-card-cta" target="_blank" rel="noopener">Get Deal</a>
                                    @endif
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="blog-sidebar-empty">
                    @if($post->category)
                    There are no deals in this category. "{{ $post->category }}".
                    @else
                        
                        There are no hot deals yet.
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
@endsection
