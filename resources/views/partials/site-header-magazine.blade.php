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
<script>
(function () {
    var header = document.querySelector('.magazine-header');
    var mainbar = document.getElementById('magazine-mainbar');
    var toggle = document.getElementById('magazine-nav-toggle');
    var mobileNav = document.getElementById('magazine-mobile-nav');
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
        if (mainbar) mainbar.classList.remove('magazine-mainbar--search-open');
    }

    function openSearch() {
        closeMenu();
        if (mainbar) mainbar.classList.add('magazine-mainbar--search-open');
        if (searchToggle) searchToggle.setAttribute('aria-expanded', 'true');

        if (usesMobileSearch() && searchPanel) {
            if (searchDropdown) searchDropdown.setAttribute('hidden', '');
            if (searchWrap) searchWrap.classList.remove('magazine-search-wrap--open');
            searchPanel.removeAttribute('hidden');
            var panelInput = searchPanel.querySelector('input[type="search"]');
            if (panelInput) setTimeout(function () { panelInput.focus(); }, 50);
        } else if (searchWrap && searchDropdown) {
            if (searchPanel) searchPanel.setAttribute('hidden', '');
            if (mainbar) mainbar.classList.remove('magazine-mainbar--search-open');
            searchWrap.classList.add('magazine-search-wrap--open');
            searchDropdown.removeAttribute('hidden');
            var input = searchDropdown.querySelector('input[type="search"]');
            if (input) setTimeout(function () { input.focus(); }, 50);
        }
    }

    function closeMenu() {
        if (!mainbar || !mobileNav) return;
        mainbar.classList.remove('magazine-mainbar--nav-open');
        mobileNav.setAttribute('hidden', '');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    }

    function openMenu() {
        closeSearch();
        if (!mainbar || !mobileNav) return;
        mainbar.classList.add('magazine-mainbar--nav-open');
        mobileNav.removeAttribute('hidden');
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
    }

    if (header) {
        var scrollTicking = false;
        var isCompact = false;
        var compactOn = mobileMq.matches ? 72 : 120;
        var compactOff = mobileMq.matches ? 16 : 40;

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
            compactOn = e.matches ? 72 : 120;
            compactOff = e.matches ? 16 : 40;
            closeSearch();
            closeMenu();
            updateHeaderCompact();
        }

        if (mobileMq.addEventListener) {
            mobileMq.addEventListener('change', onMqChange);
        } else {
            mobileMq.addListener(onMqChange);
        }

        updateHeaderCompact();
    }

    if (mainbar && toggle && mobileNav) {
        toggle.addEventListener('click', function () {
            if (mainbar.classList.contains('magazine-mainbar--nav-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        mobileNav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', closeMenu);
        });

        document.addEventListener('click', function (e) {
            if (!mainbar.classList.contains('magazine-mainbar--nav-open')) return;
            if (mainbar.contains(e.target)) return;
            closeMenu();
        });
    }

    document.querySelectorAll('.magazine-nav-dropdown-wrap').forEach(function (wrap) {
        var trigger = wrap.querySelector('.magazine-main-nav-link--dropdown');
        if (!trigger) return;

        trigger.addEventListener('click', function (e) {
            if (!mobileMq.matches) return;
            e.preventDefault();
            wrap.classList.toggle('is-open');
            trigger.setAttribute('aria-expanded', wrap.classList.contains('is-open') ? 'true' : 'false');
        });
    });

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
@endpush
