@php
    use App\Support\AdminSettings;
    use App\Support\MagazineLayout;

    $siteLogoUrl = AdminSettings::siteLogoUrl();
    $blogCategories = MagazineLayout::blogNavCategories();

    $headerNavLinks = \App\Models\SiteContent::headerNav();
    $normalizeUrl = function ($url) {
        if (empty($url)) {
            return url('/');
        }
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url(ltrim($url, '/'));
    };

    $currentPath = '/' . trim(parse_url(url()->current(), PHP_URL_PATH) ?? '/', '/');
    $isHome = $currentPath === '' || $currentPath === '/';
    $currentCategory = (string) request()->query('category', '');

    $isActive = function ($url) use ($normalizeUrl, $currentPath, $isHome, $currentCategory) {
        $normalized = $normalizeUrl($url);
        $parsed = parse_url($normalized);
        $resolved = '/' . trim($parsed['path'] ?? '/', '/');

        if ($resolved === '/' || $resolved === '') {
            return $isHome;
        }

        if ($resolved === '/blog' || str_ends_with($resolved, '/blog')) {
            parse_str($parsed['query'] ?? '', $params);
            $itemCategory = (string) ($params['category'] ?? '');

            if ($itemCategory !== '') {
                return $currentPath === '/blog' && $currentCategory === $itemCategory;
            }

            return $currentPath === '/blog' && $currentCategory === '';
        }

        return $currentPath === $resolved || str_starts_with($currentPath, $resolved . '/');
    };

    $isBlogLink = function (array $link): bool {
        $label = strtolower(trim((string) ($link['label'] ?? '')));
        $url = strtolower(trim((string) ($link['url'] ?? '')));

        return in_array($label, ['review', 'blog'], true)
            || str_contains($url, '/blog');
    };

    $topbarLinks = [
        ['label' => 'Terms of Use', 'url' => url('/terms')],
        ['label' => 'Privacy Policy', 'url' => url('/privacy')],
        ['label' => 'Contact', 'url' => url('/contact')],
    ];

    $breadcrumbTrail = MagazineLayout::breadcrumbTrail();
    $showCrumbBar = ! ($isHome && count($breadcrumbTrail) === 1 && ($breadcrumbTrail[0]['label'] ?? '') === 'Home');
@endphp

<header class="magazine-header">
    <div class="magazine-topbar">
        <div class="magazine-shell magazine-topbar-inner">
            <nav class="magazine-topbar-nav" aria-label="Legal links">
                @foreach($topbarLinks as $link)
                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>
            <div class="magazine-topbar-social">
                @include('partials.social-links', ['variant' => 'topbar'])
            </div>
        </div>
    </div>

    <div class="magazine-mainbar" id="magazine-mainbar">
        <div class="magazine-shell magazine-mainbar-inner">
            <button type="button"
                class="magazine-nav-toggle"
                id="magazine-nav-toggle"
                aria-expanded="false"
                aria-controls="magazine-mobile-nav"
                aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>

            <a href="{{ url('/') }}" class="magazine-logo magazine-logo--main font-heading{{ $siteLogoUrl ? '' : ' magazine-logo--text-only' }}" aria-label="{{ config('app.name') }} home">
                @if($siteLogoUrl)
                    <span class="magazine-logo-mark">
                        <img src="{{ $siteLogoUrl }}" alt="" width="44" height="44" decoding="async">
                    </span>
                @endif
                <span class="magazine-logo-text">{{ config('app.name') }}</span>
            </a>

            <nav class="magazine-main-nav" aria-label="Primary navigation">
                @foreach($headerNavLinks as $link)
                    @php
                        $active = $isActive($link['url'] ?? '/');
                        $blog = $isBlogLink($link);
                    @endphp
                    @if($blog && $blogCategories->isNotEmpty())
                        <div class="magazine-nav-dropdown-wrap @if($active) is-active @endif">
                            <a href="{{ $normalizeUrl($link['url'] ?? '/blog') }}"
                               class="magazine-main-nav-link magazine-main-nav-link--dropdown @if($active) is-active @endif"
                               aria-haspopup="true"
                               aria-expanded="false">
                                {{ strtoupper($link['label'] ?? 'BLOG') }}
                                <svg class="magazine-nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                            </a>
                            <div class="magazine-nav-dropdown" role="menu">
                                <a href="{{ route('blog.index') }}" role="menuitem" @if($currentPath === '/blog' && $currentCategory === '') class="is-active" @endif>All Blogs</a>
                                @foreach($blogCategories as $category)
                                    <a href="{{ route('blog.index', ['category' => $category]) }}"
                                       role="menuitem"
                                       @if($currentPath === '/blog' && $currentCategory === $category) class="is-active" @endif>{{ $category }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $normalizeUrl($link['url'] ?? '/') }}"
                           class="magazine-main-nav-link @if($active) is-active @endif">{{ strtoupper($link['label'] ?? 'LINK') }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="magazine-main-actions">
                <div class="magazine-search-wrap" id="magazine-search-wrap">
                    <button type="button" class="magazine-search magazine-search--main" id="magazine-search-toggle" aria-expanded="false" aria-controls="magazine-search-panel" aria-label="Search articles">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
                    </button>
                    <div class="magazine-search-dropdown" id="magazine-search-dropdown" hidden>
                        <form action="{{ route('blog.index') }}" method="get" class="magazine-search-form">
                            <input type="search" name="q" placeholder="Search articles…" autocomplete="off" aria-label="Search keyword">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="magazine-search-panel" id="magazine-search-panel" hidden>
            <div class="magazine-shell">
                <form action="{{ route('blog.index') }}" method="get" class="magazine-search-form magazine-search-form--panel">
                    <input type="search" name="q" placeholder="Search articles…" autocomplete="off" aria-label="Search keyword">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <nav class="magazine-mobile-nav" id="magazine-mobile-nav" aria-label="Mobile navigation" hidden>
            <div class="magazine-shell">
                @foreach($headerNavLinks as $link)
                    @php $active = $isActive($link['url'] ?? '/'); @endphp
                    <a href="{{ $normalizeUrl($link['url'] ?? '/') }}" @if($active) class="is-active" @endif>{{ strtoupper($link['label'] ?? 'LINK') }}</a>
                @endforeach
                @if($blogCategories->isNotEmpty())
                    <p class="magazine-mobile-nav-label">Categories</p>
                    @foreach($blogCategories as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}"
                           class="magazine-mobile-nav-sub @if($currentPath === '/blog' && $currentCategory === $category) is-active @endif">{{ $category }}</a>
                    @endforeach
                @endif
                <p class="magazine-mobile-nav-label">Legal</p>
                @foreach($topbarLinks as $link)
                    <a href="{{ $link['url'] }}" class="magazine-mobile-nav-sub">{{ $link['label'] }}</a>
                @endforeach
            </div>
        </nav>
    </div>
</header>

@push('scripts')
<script src="{{ asset('js/magazine-site.js') }}" defer></script>
@endpush
