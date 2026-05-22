@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content ?? ''), 160))

@push('styles')
    @include('partials.blog-show-styles')
@endpush

@section('content')
    @php
        $wordCount = str_word_count(strip_tags($post->content ?? ''));
        $readingMinutes = max(1, (int) ceil($wordCount / 220));
    @endphp

    <article class="blg-article">
        <div class="blg-wrap">
            <nav class="blg-article__crumb" aria-label="Breadcrumb">
                <a href="{{ route('blog.index') }}">Blog</a>
                <span aria-hidden="true">/</span>
                <span>{{ Str::limit($post->title, 48) }}</span>
            </nav>

            <div class="blg-article__cover">
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="eager">
            </div>

            <header class="blg-article__head">
                <div class="blg-article__chips">
                    @if($post->category)
                        <span class="blg-article__chip blg-article__chip--accent">{{ $post->category }}</span>
                    @endif
                    <span class="blg-article__chip">{{ $post->created_at?->format('d M Y') }}</span>
                    <span class="blg-article__chip">{{ $readingMinutes }} min read</span>
                    <button type="button" class="blg-article__share"
                        onclick="navigator.clipboard.writeText(window.location.href); this.textContent='Copied'; setTimeout(() => this.textContent='Copy link', 1500);">
                        Copy link
                    </button>
                </div>
                <h1 class="blg-article__title font-heading">{{ $post->title }}</h1>
                <p class="blg-article__legal">
                    Offers may expire. We may earn a commission when you use our links —
                    <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.
                </p>
            </header>

            <div class="blg-article__layout">
                <div class="blg-article__main">
                    <a href="{{ route('blog.index') }}" class="blg-article__back">
                        <span class="blg-article__back-icon" aria-hidden="true">←</span>
                        <span>All articles</span>
                    </a>

                    <div class="blg-prose">
                        {!! $post->content !!}
                    </div>

                    @if($post->images && count($post->images) > 0)
                        <div class="blg-article__media-grid">
                            @foreach($post->images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="" loading="lazy">
                            @endforeach
                        </div>
                    @endif

                    @if($post->videos && count($post->videos) > 0)
                        <div class="blg-article__media-grid">
                            @foreach($post->videos as $video)
                                <video controls preload="metadata">
                                    <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                </video>
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside class="blg-sidebar">
                    <div class="blg-sidebar__panel">
                        <h2 class="blg-sidebar__title">
                            @if($post->category)
                                Deals in {{ $post->category }}
                            @else
                                Hot deals
                            @endif
                        </h2>

                        @if(isset($sidebarDeals) && $sidebarDeals->isNotEmpty())
                            <div class="blg-sidebar__list">
                                @foreach($sidebarDeals as $coupon)
                                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                                    @if($brand)
                                        @php
                                            $dealUrl = $campaign && $campaign->affiliate_url
                                                ? route('click.redirect', ['slug' => $campaign->slug])
                                                : null;
                                        @endphp
                                        <article class="blg-deal">
                                            <div class="blg-deal__head">
                                                <div class="blg-deal__logo">
                                                    <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                                </div>
                                                <div class="blg-deal__brand">{{ $brand->name }}</div>
                                            </div>
                                            @if($coupon->offer)
                                                <p class="blg-deal__offer">{{ $coupon->offer }}</p>
                                            @endif
                                            <div class="blg-deal__actions">
                                                @if($coupon->code)
                                                    <button type="button" class="blg-deal__code"
                                                        onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);"
                                                        title="Click to copy">
                                                        <span class="blg-deal__code-label">Code</span>
                                                        <span>{{ $coupon->code }}</span>
                                                    </button>
                                                @endif
                                                @if($dealUrl)
                                                    <a href="{{ $dealUrl }}" class="blg-deal__cta" target="_blank" rel="nofollow sponsored noopener">Get deal</a>
                                                @endif
                                            </div>
                                        </article>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="blg-sidebar__empty">
                                @if($post->category)
                                    No active deals in “{{ $post->category }}” right now.
                                @else
                                    No highlighted deals for this article yet.
                                @endif
                            </p>
                        @endif
                    </div>
                </aside>
            </div>

            @if(isset($relatedBlogs) && $relatedBlogs->isNotEmpty())
                <section class="blg-related">
                    <h2 class="blg-related__title">More to read</h2>
                    <div class="blg-related__grid">
                        @foreach($relatedBlogs as $related)
                            <a href="{{ route('blog.show', $related->slug) }}" class="blg-related__card">
                                <div class="blg-related__thumb">
                                    <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" loading="lazy">
                                </div>
                                <div class="blg-related__body">
                                    <h3 class="blg-related__name">{{ $related->title }}</h3>
                                    <p class="blg-related__date">{{ $related->created_at?->format('d M Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </article>
@endsection
