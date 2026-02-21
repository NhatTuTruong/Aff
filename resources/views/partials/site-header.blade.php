@php
    $navLinks = \App\Models\SiteContent::get('header_nav', \App\Models\SiteContent::defaultHeaderNav());
    $normalizeUrl = function ($url) {
        if (empty($url)) return url('/');
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
        return url(ltrim($url, '/'));
    };
@endphp
<header class="site-header">
    <div class="header-inner">
        <a href="{{ url('/') }}" class="logo font-heading">{{ config('app.name') }}<span>.</span></a>
        <nav class="nav-links">
            @foreach($navLinks as $link)
                <a href="{{ $normalizeUrl($link['url'] ?? '/') }}">{{ $link['label'] ?? 'Link' }}</a>
            @endforeach
        </nav>
    </div>
</header>
