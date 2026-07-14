@php
    use App\Models\Blog;
    use App\Support\MagazineLayout;

    $blogCategories = Blog::query()
        ->where('is_published', true)
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    if ($blogCategories->isEmpty()) {
        $blogCategories = collect(config('default_categories.names', []))->take(14);
    }

    $headerNavLinks = \App\Models\SiteContent::get('header_nav', \App\Models\SiteContent::defaultHeaderNav());
    $normalizeUrl = function ($url) {
        if (empty($url)) {
            return url('/');
        }
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url(ltrim($url, '/'));
    };

    $navItems = collect([['label' => 'HOME', 'url' => url('/')]])
        ->merge($blogCategories->map(fn ($cat) => [
            'label' => strtoupper($cat),
            'url' => route('blog.index', ['category' => $cat]),
        ]));

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
@endphp

<header class="magazine-header">
    <div class="magazine-banner">
        <div class="magazine-shell magazine-banner-grid">
            <div class="magazine-brand">
                <a href="{{ url('/') }}" class="magazine-logo font-heading">{{ config('app.name') }}</a>
                <div class="magazine-banner-collapsible">
                    <p class="magazine-tagline">{{ config('app.site_tagline', 'Smart News & Hot Deals – Save More Daily') }}</p>
                </div>
            </div>
            <nav class="magazine-banner-nav magazine-banner-collapsible" aria-label="Quick links">
                @foreach($headerNavLinks as $link)
                    @php $active = $isActive($link['url'] ?? '/'); @endphp
                    <a href="{{ $normalizeUrl($link['url'] ?? '/') }}" @if($active) class="is-active" @endif>{{ strtoupper($link['label'] ?? 'Link') }}</a>
                @endforeach
            </nav>
        </div>
    </div>

    <div class="magazine-nav-wrap" id="magazine-nav-wrap">
        <div class="magazine-shell magazine-nav-inner">
            <button type="button"
                class="magazine-nav-toggle"
                id="magazine-nav-toggle"
                aria-expanded="false"
                aria-controls="magazine-nav"
                aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>

            <nav class="magazine-nav" id="magazine-nav" aria-label="Site navigation">
                <div class="magazine-nav-section magazine-nav-section--mobile">
                    <p class="magazine-nav-section-label">Menu</p>
                    <div class="magazine-nav-row">
                        @foreach($headerNavLinks as $link)
                            @php $active = $isActive($link['url'] ?? '/'); @endphp
                            <a href="{{ $normalizeUrl($link['url'] ?? '/') }}" @if($active) class="is-active" @endif>{{ strtoupper($link['label'] ?? 'Link') }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="magazine-nav-section magazine-nav-section--categories">
                    <p class="magazine-nav-section-label magazine-nav-section-label--mobile">Categories</p>
                    <div class="magazine-nav-row">
                        @foreach($navItems as $item)
                            @php $active = $isActive($item['url']); @endphp
                            <a href="{{ $item['url'] }}" @if($active) class="is-active" @endif>{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </nav>

            <div class="magazine-nav-actions">
                <div class="magazine-search-wrap" id="magazine-search-wrap">
                    <button type="button" class="magazine-search" id="magazine-search-toggle" aria-expanded="false" aria-controls="magazine-search-panel" aria-label="Search articles">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>
                    </button>
                    <div class="magazine-search-dropdown" id="magazine-search-dropdown" hidden>
                        <form action="{{ route('blog.index') }}" method="get" class="magazine-search-form">
                            <input type="search" name="q" placeholder="Search articles…" autocomplete="off" aria-label="Search keyword">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                </div>
                <div class="magazine-nav-social">
                    @include('partials.social-links', ['variant' => 'icons'])
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
    </div>
</header>

<script>
(function () {
    var header = document.querySelector('.magazine-header');
    var navWrap = document.getElementById('magazine-nav-wrap');
    var toggle = document.getElementById('magazine-nav-toggle');
    var nav = document.getElementById('magazine-nav');
    var searchWrap = document.getElementById('magazine-search-wrap');
    var searchToggle = document.getElementById('magazine-search-toggle');
    var searchDropdown = document.getElementById('magazine-search-dropdown');
    var searchPanel = document.getElementById('magazine-search-panel');
    var mobileMq = window.matchMedia('(max-width: 768px)');

    function usesMobileSearch() {
        return mobileMq.matches;
    }

    function closeSearch() {
        if (searchWrap) searchWrap.classList.remove('magazine-search-wrap--open');
        if (searchToggle) searchToggle.setAttribute('aria-expanded', 'false');
        if (searchDropdown) searchDropdown.setAttribute('hidden', '');
        if (searchPanel) searchPanel.setAttribute('hidden', '');
        if (navWrap) navWrap.classList.remove('magazine-nav-wrap--search-open');
    }

    function openSearch() {
        if (navWrap) {
            navWrap.classList.remove('magazine-nav-wrap--open');
            navWrap.classList.add('magazine-nav-wrap--search-open');
        }
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        if (searchToggle) searchToggle.setAttribute('aria-expanded', 'true');

        if (usesMobileSearch() && searchPanel) {
            if (searchDropdown) searchDropdown.setAttribute('hidden', '');
            if (searchWrap) searchWrap.classList.remove('magazine-search-wrap--open');
            searchPanel.removeAttribute('hidden');
            var panelInput = searchPanel.querySelector('input[type="search"]');
            if (panelInput) setTimeout(function () { panelInput.focus(); }, 50);
        } else if (searchWrap && searchDropdown) {
            if (searchPanel) searchPanel.setAttribute('hidden', '');
            if (navWrap) navWrap.classList.remove('magazine-nav-wrap--search-open');
            searchWrap.classList.add('magazine-search-wrap--open');
            searchDropdown.removeAttribute('hidden');
            var input = searchDropdown.querySelector('input[type="search"]');
            if (input) setTimeout(function () { input.focus(); }, 50);
        }
    }

    function closeMenu() {
        if (!navWrap) return;
        navWrap.classList.remove('magazine-nav-wrap--open');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    }

    if (header) {
        var scrollTicking = false;
        var isCompact = false;
        var compactOn = mobileMq.matches ? 56 : 96;
        var compactOff = mobileMq.matches ? 12 : 28;

        function setCompact(next) {
            if (next === isCompact) return;
            isCompact = next;
            header.classList.toggle('magazine-header--compact', isCompact);
        }

        function updateHeaderCompact() {
            var y = window.scrollY;
            if (!isCompact && y > compactOn) {
                setCompact(true);
                closeMenu();
                closeSearch();
            } else if (isCompact && y < compactOff) {
                setCompact(false);
            }
            scrollTicking = false;
        }

        window.addEventListener('scroll', function () {
            if (!scrollTicking) {
                scrollTicking = true;
                requestAnimationFrame(updateHeaderCompact);
            }
        }, { passive: true });

        function onMqChange(e) {
            compactOn = e.matches ? 56 : 96;
            compactOff = e.matches ? 12 : 28;
            closeSearch();
            updateHeaderCompact();
        }

        if (mobileMq.addEventListener) {
            mobileMq.addEventListener('change', onMqChange);
        } else {
            mobileMq.addListener(onMqChange);
        }

        updateHeaderCompact();
    }

    if (navWrap && toggle && nav) {
        toggle.addEventListener('click', function () {
            closeSearch();
            var open = navWrap.classList.toggle('magazine-nav-wrap--open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        nav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', closeMenu);
        });

        document.addEventListener('click', function (e) {
            if (!navWrap.classList.contains('magazine-nav-wrap--open')) return;
            if (navWrap.contains(e.target)) return;
            closeMenu();
        });
    }

    if (searchToggle) {
        searchToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = (searchPanel && !searchPanel.hasAttribute('hidden')) ||
                (searchWrap && searchWrap.classList.contains('magazine-search-wrap--open'));
            if (isOpen) {
                closeSearch();
            } else {
                openSearch();
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (!searchWrap || !searchPanel) return;
        if (searchWrap.contains(e.target) || searchPanel.contains(e.target)) return;
        closeSearch();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMenu();
            closeSearch();
        }
    });
})();
</script>
