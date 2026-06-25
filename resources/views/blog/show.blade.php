@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content ?? ''), 160))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('partials.peel-sticker-styles')
@include('partials.styles.public-modern-base')
@include('partials.styles.blog-modern')
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
                        <span>🗂 {{ $post->category }}</span>
                    @endif
                    <span>📅 {{ $post->created_at?->format('d/m/Y') }}</span>
                    <span>⏱ {{ $readingMinutes }} min read</span>
                </div>
                <h1 class="blog-title">{{ $post->title }}</h1>
                <p class="blog-meta">
                    Offers may expire. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.
                </p>
            </div>

            <div class="blog-hero-media">
                <div class="blog-hero-media-inner">
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="eager">
                </div>
                <div class="blog-hero-media-overlay"></div>
            </div>
        </section>

        <div class="blog-main-grid">
            <article class="blog-main">
                <a href="{{ route('blog.index') }}" class="blog-back">
                    <span class="icon">←</span>
                    <span>Back to journal</span>
                </a>

                <div class="blog-chip-row">
                    @if(!empty($post->category))
                        <span class="blog-chip blog-chip-accent">{{ $post->category }}</span>
                    @endif
                    <span class="blog-chip">{{ $post->created_at?->format('d/m/Y') }}</span>
                    <span class="blog-chip">{{ $readingMinutes }} min read</span>
                    <button type="button" class="blog-share-button"
                        onclick="navigator.clipboard.writeText(window.location.href); this.textContent='Link copied'; setTimeout(() => this.textContent='Share link', 1200);">
                        <span>🔗</span>
                        <span>Share link</span>
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
                        Top picks for {{ $post->category }}
                    @else
                        Top picks for this topic
                    @endif
                </h2>

                @if(isset($sidebarDeals) && $sidebarDeals->isNotEmpty())
                    <div class="blog-aside-deals">
                        @foreach($sidebarDeals as $coupon)
                            @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                            @if($brand)
                                @php
                                    $dealUrl = $campaign && $campaign->affiliate_url
                                        ? route('click.redirect', ['slug' => $campaign->slug])
                                        : '#';
                                @endphp
                                <article class="blog-deal-card">
                                    <div class="blog-deal-header">
                                        <div class="blog-deal-logo">
                                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}">
                                        </div>
                                        <div class="blog-deal-brand">{{ $brand->name }}</div>
                                    </div>
                                    @if($coupon->offer)
                                        <p class="blog-deal-offer">{{ $coupon->offer }}</p>
                                    @endif
                                    <div class="blog-deal-actions">
                                        @if($coupon->code)
                                            <button type="button" class="btn-copy-code"
                                                onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); this.textContent='Copied!'; setTimeout(() => { this.classList.remove('copied'); this.textContent='Copy Code'; }, 1500);"
                                                title="Click to copy code">
                                                Copy Code
                                            </button>
                                        @endif
                                        @if($dealUrl !== '#')
                                            <a href="{{ $dealUrl }}" class="blog-deal-cta" target="_blank" rel="nofollow sponsored noopener">
                                                <span>Open deal</span>
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
                <h2 class="related-blogs-title">More from the journal</h2>
                <div class="related-blogs-grid">
                    @foreach($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="related-blog-card">
                            <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" class="related-blog-card-thumb" loading="lazy">
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
