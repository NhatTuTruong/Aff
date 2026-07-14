@php
    use App\Models\Blog;

    $isMagazineLayout = \App\Support\MagazineLayout::usesMagazineChrome();

    if ($isMagazineLayout) {
        $brandDescription = \App\Models\SiteContent::get(
            'footer_brand_description',
            config('app.name') . ' is a US-focused news and deal discovery platform. We publish product reviews, shopping guides, and curated savings tips to help readers make smarter purchase decisions every day.'
        );
        $copyright = \App\Models\SiteContent::get('footer_copyright', 'Copyright © ' . date('Y') . ' ' . config('app.name') . '.');

        $galleryPosts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $recentViewed = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $categoryColors = ['#e91e8c', '#00b4a6', '#f59e0b', '#7c3aed', '#ff5c35', '#3b82f6'];
        $colorFor = function (string $label) use ($categoryColors): string {
            return $categoryColors[crc32($label) % count($categoryColors)];
        };
    } else {
        $brandDescription = \App\Models\SiteContent::get('footer_brand_description', 'Coupons, promotions and trusted store reviews. Updated regularly.');
        $columns = \App\Models\SiteContent::get('footer_columns', \App\Models\SiteContent::defaultFooterColumns());
        $copyright = \App\Models\SiteContent::get('footer_copyright', '© ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.');
    }

    $normalizeUrl = function ($url) {
        if (empty($url)) return url('/');
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
        $url = (string) $url;
        if (str_starts_with($url, '#')) return url('/') . $url;
        if (str_contains($url, '#')) {
            [$path, $hash] = array_pad(explode('#', $url, 2), 2, '');
            $path = trim((string) $path);
            $hash = trim((string) $hash);
            $base = $path === '' ? url('/') : url(ltrim($path, '/'));
            return $hash !== '' ? ($base . '#' . $hash) : $base;
        }
        return url(ltrim($url, '/'));
    };
@endphp

@if($isMagazineLayout)
<footer class="site-footer site-footer--magazine">
    <div class="magazine-shell footer-magazine-main">
        <div class="footer-magazine-grid">
            <div class="footer-magazine-brand">
                <a href="{{ url('/') }}" class="footer-magazine-logo font-heading">{{ config('app.name') }}</a>
                <p>{{ $brandDescription }}</p>
                @include('partials.social-links', ['variant' => 'pills'])
            </div>

            <div class="footer-magazine-menu">
                <h3 class="footer-magazine-menu-title">Menu</h3>
                <ul>
                    <li><a href="{{ url('/terms') }}">Terms of Use</a></li>
                    <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('/cookie-policy') }}">Cookie Policy</a></li>
                </ul>
            </div>

            @if($galleryPosts->isNotEmpty())
            <div class="footer-magazine-gallery" aria-label="Recent article images">
                @foreach($galleryPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="footer-gallery-item">
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                    </a>
                @endforeach
            </div>
            @endif

            @if($recentViewed->isNotEmpty())
            <div class="footer-magazine-recent">
                <h3 class="footer-magazine-recent-title">Recent Viewed</h3>
                <div class="footer-recent-list">
                    @foreach($recentViewed as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="footer-recent-item">
                            <span class="footer-recent-thumb">
                                <img src="{{ $post->featured_image_url }}" alt="" loading="lazy">
                            </span>
                            <span class="footer-recent-body">
                                @if($post->category)
                                    <span class="footer-recent-tag" style="color: {{ $colorFor($post->category) }}">{{ strtoupper($post->category) }}</span>
                                @endif
                                <span class="footer-recent-name">{{ $post->title }}</span>
                                <span class="footer-recent-meta">Admin · {{ $post->created_at?->format('F j, Y') }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer-magazine-bar">
        <div class="magazine-shell footer-magazine-bar-inner">
            <p>{!! nl2br(e($copyright)) !!}</p>
            @include('partials.social-links', ['variant' => 'icons'])
        </div>
    </div>
</footer>
@else
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                @include('partials.site-logo')
                <p>{{ $brandDescription }}</p>
                @include('partials.social-links', ['variant' => 'icons'])
            </div>
            @foreach($columns as $col)
                <div class="footer-col">
                    <h4>{{ $col['title'] ?? 'Links' }}</h4>
                    <ul>
                        @foreach($col['links'] ?? [] as $link)
                            <li><a href="{{ $normalizeUrl($link['url'] ?? '/') }}">{{ $link['label'] ?? 'Link' }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="footer-disclosure">
            <p class="footer-disclosure-text">
                We may earn a commission when you use our links, at no extra cost to you. See our
                <a href="{{ url('/affiliate-disclosure') }}">Affiliate Disclosure</a> and
                <a href="{{ url('/privacy') }}">Privacy Policy</a>.
            </p>
        </div>
        <div class="footer-bottom">
            <p>{!! nl2br(e($copyright)) !!}</p>
        </div>
    </div>
</footer>
@endif
