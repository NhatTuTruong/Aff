@php
    $brandDescription = \App\Models\SiteContent::get('footer_brand_description', 'Coupons, promotions and trusted store reviews. Updated regularly.');
    $columns = \App\Models\SiteContent::get('footer_columns', \App\Models\SiteContent::defaultFooterColumns());
    $copyright = \App\Models\SiteContent::get('footer_copyright', '© ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.');
    $normalizeUrl = function ($url) {
        if (empty($url)) return url('/');
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
        return url(ltrim($url, '/'));
    };
@endphp
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="logo font-heading">{{ config('app.name') }}<span>.</span></a>
                <p>{{ $brandDescription }}</p>
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
        <div class="footer-bottom">
            <p>{!! nl2br(e($copyright)) !!}</p>
        </div>
    </div>
</footer>
