@extends('layouts.app')

@section('title', $post->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($post->content ?? ''), 160))

@push('styles')
@include('partials.peel-sticker-styles')
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
                    {!! $post->rendered_content !!}
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
