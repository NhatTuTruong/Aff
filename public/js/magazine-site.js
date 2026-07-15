(function () {
    'use strict';

    function getOrCreateSentinel() {
        var existing = document.querySelector('.magazine-scroll-sentinel');
        if (existing) {
            return existing;
        }

        var sentinel = document.createElement('div');
        sentinel.className = 'magazine-scroll-sentinel';
        sentinel.setAttribute('aria-hidden', 'true');
        document.body.prepend(sentinel);

        return sentinel;
    }

    function initBackToTop() {
        var btn = document.getElementById('back-to-top');
        if (!btn) {
            return;
        }

        var sentinel = getOrCreateSentinel();
        var isVisible = false;

        new IntersectionObserver(function (entries) {
            var show = !entries[0].isIntersecting;
            if (show === isVisible) {
                return;
            }

            isVisible = show;
            btn.classList.toggle('is-visible', show);
            btn.setAttribute('aria-hidden', show ? 'false' : 'true');
            btn.tabIndex = show ? 0 : -1;
        }, {
            threshold: 0,
            rootMargin: '-320px 0px 0px 0px',
        }).observe(sentinel);

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    function initMagazineHeader() {
        var header = document.querySelector('.magazine-header');
        var mainbar = document.getElementById('magazine-mainbar');
        var toggle = document.getElementById('magazine-nav-toggle');
        var mobileNav = document.getElementById('magazine-mobile-nav');
        var searchWrap = document.getElementById('magazine-search-wrap');
        var searchToggle = document.getElementById('magazine-search-toggle');
        var searchDropdown = document.getElementById('magazine-search-dropdown');
        var searchPanel = document.getElementById('magazine-search-panel');

        if (!header) {
            return;
        }

        var mobileMq = window.matchMedia('(max-width: 768px)');
        var menuOpen = false;
        var searchOpen = false;
        var usesMobileSearch = mobileMq.matches;
        var isCompact = false;
        var compactObservers = [];

        function closeSearch() {
            if (!searchOpen) {
                return;
            }

            searchOpen = false;
            if (searchWrap) {
                searchWrap.classList.remove('magazine-search-wrap--open');
            }
            if (searchToggle) {
                searchToggle.setAttribute('aria-expanded', 'false');
            }
            if (searchDropdown) {
                searchDropdown.setAttribute('hidden', '');
            }
            if (searchPanel) {
                searchPanel.setAttribute('hidden', '');
            }
            if (mainbar) {
                mainbar.classList.remove('magazine-mainbar--search-open');
            }
        }

        function openSearch() {
            if (searchOpen) {
                return;
            }

            closeMenu();
            searchOpen = true;

            if (mainbar) {
                mainbar.classList.add('magazine-mainbar--search-open');
            }
            if (searchToggle) {
                searchToggle.setAttribute('aria-expanded', 'true');
            }

            if (usesMobileSearch && searchPanel) {
                if (searchDropdown) {
                    searchDropdown.setAttribute('hidden', '');
                }
                if (searchWrap) {
                    searchWrap.classList.remove('magazine-search-wrap--open');
                }
                searchPanel.removeAttribute('hidden');
            } else if (searchWrap && searchDropdown) {
                if (searchPanel) {
                    searchPanel.setAttribute('hidden', '');
                }
                if (mainbar) {
                    mainbar.classList.remove('magazine-mainbar--search-open');
                }
                searchWrap.classList.add('magazine-search-wrap--open');
                searchDropdown.removeAttribute('hidden');
            }
        }

        function closeMenu() {
            if (!menuOpen || !mainbar || !mobileNav) {
                return;
            }

            menuOpen = false;
            mainbar.classList.remove('magazine-mainbar--nav-open');
            mobileNav.setAttribute('hidden', '');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        }

        function openMenu() {
            if (menuOpen || !mainbar || !mobileNav) {
                return;
            }

            closeSearch();
            menuOpen = true;
            mainbar.classList.add('magazine-mainbar--nav-open');
            mobileNav.removeAttribute('hidden');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'true');
            }
        }

        function setCompact(next) {
            if (next === isCompact) {
                return;
            }

            isCompact = next;
            header.classList.toggle('magazine-header--compact', isCompact);
        }

        function scheduleCompact(next) {
            if (next === isCompact) {
                return;
            }

            if (next) {
                closeMenu();
                closeSearch();
            }

            setCompact(next);
        }

        function disconnectCompactObservers() {
            compactObservers.forEach(function (observer) {
                observer.disconnect();
            });
            compactObservers = [];
        }

        function bindCompactObservers() {
            disconnectCompactObservers();

            var margins = usesMobileSearch
                ? { on: '-72px 0px 0px 0px', off: '-16px 0px 0px 0px' }
                : { on: '-120px 0px 0px 0px', off: '-40px 0px 0px 0px' };
            var sentinel = getOrCreateSentinel();

            compactObservers.push(new IntersectionObserver(function (entries) {
                if (!entries[0].isIntersecting) {
                    scheduleCompact(true);
                }
            }, {
                threshold: 0,
                rootMargin: margins.on,
            }));

            compactObservers.push(new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) {
                    scheduleCompact(false);
                }
            }, {
                threshold: 0,
                rootMargin: margins.off,
            }));

            compactObservers.forEach(function (observer) {
                observer.observe(sentinel);
            });
        }

        bindCompactObservers();

        if (mainbar && toggle && mobileNav) {
            toggle.addEventListener('click', function () {
                if (menuOpen) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            mobileNav.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closeMenu);
            });

            document.addEventListener('click', function (event) {
                if (!menuOpen) {
                    return;
                }

                if (mainbar.contains(event.target)) {
                    return;
                }

                closeMenu();
            });
        }

        document.querySelectorAll('.magazine-nav-dropdown-wrap').forEach(function (wrap) {
            var trigger = wrap.querySelector('.magazine-main-nav-link--dropdown');
            if (!trigger) {
                return;
            }

            var dropdownOpen = false;

            trigger.addEventListener('click', function (event) {
                if (!usesMobileSearch) {
                    return;
                }

                event.preventDefault();
                dropdownOpen = !dropdownOpen;
                wrap.classList.toggle('is-open', dropdownOpen);
                trigger.setAttribute('aria-expanded', dropdownOpen ? 'true' : 'false');
            });
        });

        if (searchToggle) {
            searchToggle.addEventListener('click', function (event) {
                event.stopPropagation();
                if (searchOpen) {
                    closeSearch();
                } else {
                    openSearch();
                }
            });
        }

        document.addEventListener('click', function (event) {
            if (!searchOpen || !searchWrap || !searchPanel) {
                return;
            }

            if (searchWrap.contains(event.target) || searchPanel.contains(event.target)) {
                return;
            }

            closeSearch();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
                closeSearch();
            }
        });

        function onMqChange(event) {
            usesMobileSearch = event.matches;
            closeSearch();
            closeMenu();
            bindCompactObservers();
        }

        if (mobileMq.addEventListener) {
            mobileMq.addEventListener('change', onMqChange);
        } else {
            mobileMq.addListener(onMqChange);
        }
    }

    function boot() {
        initBackToTop();
        initMagazineHeader();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    } else {
        boot();
    }
})();
