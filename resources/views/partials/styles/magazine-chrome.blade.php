<style>
:root {
    --magazine-shell: min(1320px, calc(100% - 2rem));
    --magazine-sticky-offset: 7.25rem;
}

body.magazine-site:has(.magazine-crumb-bar),
body:has(.home-magazine):has(.magazine-crumb-bar) {
    --magazine-sticky-offset: 9.25rem;
}

body:has(.home-magazine) main,
body.magazine-site main {
    padding-top: 0;
}

.magazine-scroll-sentinel {
    position: absolute;
    top: 0;
    left: 0;
    width: 1px;
    height: 1px;
    pointer-events: none;
    visibility: hidden;
    overflow: hidden;
}

.magazine-shell {
    width: var(--magazine-shell);
    margin-inline: auto;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* ── Sticky header shell ── */
.magazine-header {
    position: sticky;
    top: 0;
    z-index: 120;
    transition: box-shadow 0.25s ease;
    border-top: 3px solid #2563eb;
}

.magazine-header.magazine-header--compact {
    --magazine-sticky-offset: 5.5rem;
    box-shadow: 0 6px 24px rgba(15, 23, 42, 0.14);
}

.magazine-header.magazine-header--compact .magazine-topbar {

}

.magazine-header.magazine-header--compact .magazine-crumb-bar {
    display: none;
}

/* ── Top bar ── */
.magazine-topbar {
    background: linear-gradient(90deg, #0f172a 0%, #1e3a8a 100%);
    color: rgba(255, 255, 255, 0.88);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    transition: max-height 0.28s ease, opacity 0.22s ease, padding 0.28s ease;
}

.magazine-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.6rem 0;
    min-height: 38px;
}

.magazine-topbar-nav {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1.5rem 1.2rem;
}

.magazine-topbar-nav a {
    color: rgba(255, 255, 255, 0.82);
    text-decoration: none;
    font-size: 0.775rem;
    font-weight: 600;
    line-height: 1.3;
    transition: color 0.2s;
    white-space: nowrap;
}

.magazine-topbar-nav a:hover {
    color: #fff;
}

.magazine-topbar-social {
    flex-shrink: 0;
}

/* ── Main bar ── */
.magazine-mainbar {
    background: #fff;
    border-bottom: 2px solid rgba(37, 99, 235, 0.12);
    box-shadow: 0 4px 18px rgba(37, 99, 235, 0.06);
    position: relative;
}

.magazine-mainbar-inner {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 0.95rem 0;
    min-height: 76px;
}

.magazine-nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    padding: 0.35rem;
    cursor: pointer;
    flex-shrink: 0;
}

.magazine-nav-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background: #111;
    border-radius: 1px;
}

.magazine-logo--main {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    flex-shrink: 0;
    color: #111 !important;
}

.magazine-logo-mark {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.75rem;
    height: 2.75rem;
    border: 2px solid rgba(37, 99, 235, 0.2);
    border-radius: 12px;
    background: #eff6ff;
    overflow: hidden;
    flex-shrink: 0;
}

.magazine-logo--text-only {
    gap: 0;
}

.magazine-logo--text-only .magazine-logo-text {
    font-size: clamp(1.15rem, 2.4vw, 1.45rem);
}

.magazine-logo-mark img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.magazine-logo-text {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.05rem, 2vw, 1.25rem);
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.1;
    background: linear-gradient(135deg, #1e40af 0%, #2563eb 55%, #60a5fa 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: #2563eb;
}

.magazine-main-nav {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.35rem 1.1rem;
    flex: 1;
    min-width: 0;
}

.magazine-main-nav-link {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    color: #334155;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    transition: color 0.2s, background 0.2s;
}

.magazine-main-nav-link:hover,
.magazine-main-nav-link.is-active,
.magazine-nav-dropdown-wrap.is-active > .magazine-main-nav-link {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.1);
}

.magazine-nav-chevron {
    width: 0.85rem;
    height: 0.85rem;
    margin-top: 0.05rem;
    transition: transform 0.2s;
}

.magazine-nav-dropdown-wrap {
    position: relative;
}

.magazine-nav-dropdown-wrap:hover .magazine-nav-dropdown,
.magazine-nav-dropdown-wrap:focus-within .magazine-nav-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.magazine-nav-dropdown-wrap:hover .magazine-nav-chevron,
.magazine-nav-dropdown-wrap:focus-within .magazine-nav-chevron {
    transform: rotate(180deg);
}

.magazine-nav-dropdown {
    position: absolute;
    top: calc(100% + 0.65rem);
    left: 0;
    min-width: 220px;
    max-height: min(60vh, 360px);
    overflow-y: auto;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 8px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    padding: 0.45rem 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(6px);
    transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
    z-index: 130;
}

.magazine-nav-dropdown a {
    display: block;
    padding: 0.55rem 1rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: none;
    transition: background 0.15s, color 0.15s;
}

.magazine-nav-dropdown a:hover,
.magazine-nav-dropdown a.is-active {
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
}

.magazine-main-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
    margin-left: auto;
}

.magazine-search--main {
    color: #2563eb !important;
    background: rgba(37, 99, 235, 0.08) !important;
}

.magazine-search--main:hover,
.magazine-search-wrap--open .magazine-search--main {
    color: #fff !important;
    background: #2563eb !important;
}

/* ── Breadcrumb bar ── */
.magazine-crumb-bar {
    background: linear-gradient(90deg, #0f172a 0%, #1e3a8a 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.magazine-crumb-bar .magazine-shell {
    padding: 0.45rem 0;
}

.magazine-breadcrumb-list {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 0;
    margin: 0;
    padding: 0;
    list-style: none;
    min-width: 0;
    overflow: hidden;
}

.magazine-breadcrumb-item {
    display: inline-flex;
    align-items: center;
    min-width: 0;
    flex-shrink: 1;
}

.magazine-breadcrumb-sep {
    margin: 0 0.45rem;
    color: rgba(255, 255, 255, 0.35);
    font-size: 0.72rem;
    flex-shrink: 0;
}

.magazine-breadcrumb a,
.magazine-breadcrumb-current {
    display: inline-block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    line-height: 1.3;
}

.magazine-breadcrumb a {
    color: rgba(255, 255, 255, 0.72);
    text-decoration: none;
    transition: color 0.2s;
}

.magazine-breadcrumb a:hover {
    color: #fff;
    text-decoration: underline;
    text-underline-offset: 2px;
}

.magazine-breadcrumb-current {
    color: #fff;
}

/* ── Mobile nav drawer ── */
.magazine-mobile-nav {
    background: #fff;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    max-height: min(70vh, 420px);
    overflow-y: auto;
}

.magazine-mobile-nav[hidden] {
    display: none;
}

.magazine-mobile-nav .magazine-shell {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    padding: 0.75rem 0 1rem;
}

.magazine-mobile-nav > .magazine-shell > a {
    display: block;
    padding: 0.55rem 0;
    color: #111;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.magazine-mobile-nav > .magazine-shell > a.is-active {
    color: #2563eb;
}

.magazine-mobile-nav-label {
    margin: 0.75rem 0 0.25rem;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #9ca3af;
}

.magazine-mobile-nav-sub {
    display: block;
    padding: 0.4rem 0;
    color: #374151;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
}

.magazine-mobile-nav-sub.is-active {
    color: #2563eb;
    font-weight: 600;
}

/* ── Search ── */
.magazine-search-panel {
    display: none;
    background: #f9fafb;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    padding: 0.65rem 0 0.75rem;
}

.magazine-search-panel:not([hidden]) {
    display: block;
}

.magazine-search-form--panel {
    display: flex;
    align-items: stretch;
    gap: 0.5rem;
    width: 100%;
}

.magazine-search-form--panel input[type="search"] {
    flex: 1;
    min-width: 0;
    width: 100%;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 8px;
    color: #111;
    padding: 0.65rem 0.85rem;
    font-size: 1rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form--panel input[type="search"]:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.magazine-search-form--panel button {
    flex-shrink: 0;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0 1.1rem;
    font-size: 0.92rem;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    white-space: nowrap;
}

.magazine-search-form--panel button:hover {
    background: #1d4ed8;
}

.magazine-search-wrap {
    position: relative;
    flex-shrink: 0;
}

.magazine-search {
    flex-shrink: 0;
    width: 2.35rem;
    height: 2.35rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
    background: transparent;
    border-radius: 50%;
    cursor: pointer;
    transition: color 0.2s, background 0.2s;
    padding: 0;
}

.magazine-search svg {
    width: 1.2rem;
    height: 1.2rem;
}

.magazine-search-dropdown {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    width: min(320px, calc(100vw - 2rem));
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    z-index: 130;
}

.magazine-search-dropdown[hidden] {
    display: none;
}

.magazine-search-form {
    display: flex;
    gap: 0.5rem;
}

.magazine-search-form input[type="search"] {
    flex: 1;
    min-width: 0;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 6px;
    color: #111;
    padding: 0.55rem 0.75rem;
    font-size: 0.95rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form input[type="search"]:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.magazine-search-form button {
    flex-shrink: 0;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 0.55rem 0.9rem;
    font-size: 1rem;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
}

.magazine-search-form button:hover {
    background: #1d4ed8;
}

/* ── Social links ── */
.site-social {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.site-social--topbar {
    gap: 0.85rem;
}

.site-social--topbar .site-social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: auto;
    height: auto;
    padding: 0;
    border-radius: 0;
    background: none !important;
    color: rgba(255, 255, 255, 0.82);
    text-decoration: none;
    transition: color 0.2s, transform 0.2s;
}

.site-social--topbar .site-social-link svg {
    width: 1rem;
    height: 1rem;
}

.site-social--topbar .site-social-link:hover {
    color: #fff;
    transform: translateY(-1px);
}

.site-social--icons {
    gap: 0.45rem;
}

.site-social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    text-decoration: none;
    transition: transform 0.2s, opacity 0.2s;
}

.site-social-link svg {
    width: 1.1rem;
    height: 1.1rem;
}

.site-social--icons .site-social-link {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    color: #fff;
}

.site-social--icons .site-social-link--instagram { background: linear-gradient(135deg, #833ab4, #fd1d1d, #fcb045); }
.site-social--icons .site-social-link--youtube { background: #ff0000; }
.site-social--icons .site-social-link--facebook { background: #1877f2; }
.site-social--icons .site-social-link--twitter { background: #000; }
.site-social--icons .site-social-link--tiktok { background: #010101; }
.site-social--icons .site-social-link--pinterest { background: #bd081c; }
.site-social--icons .site-social-link--linkedin { background: #0a66c2; }
.site-social--icons .site-social-link--telegram { background: #229ed9; }

.site-social--icons .site-social-link:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.site-social--pills {
    gap: 0.55rem;
    margin-top: 1rem;
}

.site-social--pills .site-social-link {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    color: #fff;
    font-size: 0.82rem;
    font-weight: 600;
}

.site-social--pills .site-social-link--instagram { background: #0095b3; }
.site-social--pills .site-social-link--youtube { background: #e62117; }
.site-social--pills .site-social-link--facebook { background: #3b5998; }
.site-social--pills .site-social-link--twitter { background: #111; }
.site-social--pills .site-social-link--tiktok { background: #010101; }
.site-social--pills .site-social-link--pinterest { background: #bd081c; }
.site-social--pills .site-social-link--linkedin { background: #0a66c2; }
.site-social--pills .site-social-link--telegram { background: #229ed9; }

.site-social--pills .site-social-link svg {
    width: 1rem;
    height: 1rem;
}

@media (min-width: 769px) {
    .magazine-mobile-nav {
        display: none !important;
    }

    .magazine-search-panel {
        display: none !important;
    }
}

/* Magazine footer */
.site-footer--magazine {
    background: linear-gradient(180deg, #0f172a 0%, #172554 55%, #1e3a8a 100%);
    border-top: 3px solid #2563eb;
    margin-top: 0;
    color: #e2e8f0;
}

.footer-magazine-main {
    padding: 3rem 0 2.25rem;
}

.footer-magazine-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.75fr 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

.footer-magazine-menu-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding-left: 0.75rem;
    border-left: 3px solid #2563eb;
}

.footer-magazine-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}

.footer-magazine-menu a {
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.92rem;
    line-height: 1.5;
    transition: color 0.2s, padding-left 0.2s;
}

.footer-magazine-menu a:hover {
    color: #fff;
    padding-left: 0.25rem;
}

.footer-magazine-logo {
    display: inline-block;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    background: linear-gradient(135deg, #fff 0%, #93c5fd 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: #fff !important;
    text-decoration: none;
    margin-bottom: 0.85rem;
}

.footer-magazine-brand p {
    font-size: 0.98rem;
    line-height: 1.65;
    color: #94a3b8;
    margin: 0;
    max-width: 340px;
}

.footer-magazine-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.35rem;
}

.footer-gallery-item {
    display: block;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.footer-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.footer-gallery-item:hover img {
    transform: scale(1.06);
}

.footer-magazine-recent-title {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    margin: 0 0 1rem;
    padding-bottom: 0.5rem;
    padding-left: 0.75rem;
    border-left: 3px solid #2563eb;
    border-bottom: none;
}

.footer-recent-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-recent-item {
    display: grid;
    grid-template-columns: 72px 1fr;
    gap: 0.75rem;
    text-decoration: none;
    color: inherit;
    align-items: start;
}

.footer-recent-thumb {
    width: 72px;
    height: 72px;
    border-radius: 8px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.footer-recent-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.footer-recent-tag {
    display: block;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    margin-bottom: 0.2rem;
}

.footer-recent-name {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.35;
    margin-bottom: 0.2rem;
    transition: color 0.2s;
}

.footer-recent-meta {
    display: block;
    font-size: 0.72rem;
    color: #9ca3af;
}

.footer-recent-item:hover .footer-recent-name {
    color: #93c5fd;
}

.footer-magazine-bar {
    background: rgba(15, 23, 42, 0.55);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: 1rem 0;
    backdrop-filter: blur(8px);
}

.footer-magazine-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.footer-magazine-bar p {
    margin: 0;
    font-size: 0.78rem;
    color: #94a3b8;
}

.footer-magazine-bar .site-social--icons .site-social-link {
    width: 1.65rem;
    height: 1.65rem;
}

.footer-magazine-bar .site-social--icons .site-social-link svg {
    width: 0.85rem;
    height: 0.85rem;
}

.site-header__actions .site-social--icons {
    margin-right: 0.25rem;
}

.site-header__actions .site-social--icons .site-social-link {
    width: 1.75rem;
    height: 1.75rem;
}

@media (max-width: 1024px) {
    .footer-magazine-grid {
        grid-template-columns: 1fr 1fr;
    }

    .footer-magazine-brand,
    .footer-magazine-menu {
        grid-column: 1 / -1;
    }
}

@media (max-width: 768px) {
    :root {
        --magazine-sticky-offset: 4.75rem;
    }

    .magazine-header.magazine-header--compact {
        --magazine-sticky-offset: 4.25rem;
    }

    body:has(.magazine-mainbar--search-open) {
        --magazine-sticky-offset: 7.25rem;
    }

    body.magazine-site:has(.magazine-crumb-bar),
    body:has(.home-magazine):has(.magazine-crumb-bar) {
        --magazine-sticky-offset: 7.75rem;
    }

    .magazine-topbar-inner {
        padding: 0.5rem 0;
    }

    .magazine-topbar-nav {
        gap: 0.85rem 0.7rem;
    }

    .magazine-topbar-nav a {
        font-size: 0.78rem;
    }

    .magazine-mainbar-inner {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 0.65rem;
        padding: 0.65rem 0;
        min-height: 58px;
    }

    .magazine-nav-toggle {
        display: flex;
        grid-column: 1;
        width: 2.75rem;
        height: 2.75rem;
        align-items: center;
        justify-content: center;
    }

    .magazine-logo--main {
        grid-column: 2;
        min-width: 0;
    }

    .magazine-logo-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .magazine-main-nav {
        display: none;
    }

    .magazine-main-actions {
        grid-column: 3;
        margin-left: 0;
    }

    .magazine-nav-dropdown-wrap.is-open .magazine-nav-dropdown {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border: none;
        padding: 0;
        margin-top: 0.35rem;
        max-height: none;
    }

    .magazine-search-dropdown {
        display: none !important;
    }

    .magazine-search-panel {
        z-index: 124;
    }

    .magazine-search-form--panel input[type="search"] {
        min-height: 2.75rem;
        -webkit-appearance: none;
        appearance: none;
    }

    .magazine-search-form--panel button {
        min-height: 2.75rem;
        padding-inline: 1.25rem;
    }

    .footer-magazine-grid {
        grid-template-columns: 1fr;
    }

    .footer-magazine-gallery {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    :root {
        --magazine-shell: calc(100% - 1.25rem);
    }

    .magazine-logo-text {
        font-size: 1rem;
    }

    .footer-magazine-bar-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.65rem;
    }
}
</style>
