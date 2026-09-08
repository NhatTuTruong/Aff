<style>
:root {
    --magazine-shell: min(1280px, calc(100% - 2.5rem));
    --magazine-sticky-offset: 6.75rem;
    --mc-dark: #0c0c14;
    --mc-dark-2: #16161f;
    --mc-dark-3: #1e1e2a;
    --mc-accent: #198754;
    --mc-accent-dark: #157347;
    --mc-text: #f0f0f5;
    --mc-muted: rgba(255, 255, 255, 0.52);
    --mc-line: rgba(255, 255, 255, 0.1);
    --mc-font: 'Poppins', system-ui, sans-serif;
}

body.magazine-site:has(.magazine-crumb-bar),
body:has(.home-magazine):has(.magazine-crumb-bar) {
    --magazine-sticky-offset: 8.75rem;
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

/* ── Header shell ── */
.magazine-header {
    position: sticky;
    top: 0;
    z-index: 120;
    font-family: var(--mc-font);
    transition: box-shadow 0.25s ease;
}

.magazine-header.magazine-header--compact {
    --magazine-sticky-offset: 4.75rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
}

.magazine-header.magazine-header--compact .magazine-crumb-bar {
    display: none;
}

/* ── Top bar: coral strip ── */
.magazine-topbar {
    background: var(--mc-accent);
    color: var(--mc-dark);
    transition: max-height 0.28s ease, opacity 0.22s ease, padding 0.28s ease;
}

.magazine-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.45rem 0;
    min-height: 36px;
}

.magazine-topbar-tagline {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    color: rgba(12, 12, 20, 0.85);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.magazine-topbar-nav {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem 1.25rem;
    flex-shrink: 0;
}

.magazine-topbar-nav a {
    color: rgba(12, 12, 20, 0.72);
    text-decoration: none;
    font-size: 0.72rem;
    font-weight: 600;
    line-height: 1.3;
    transition: color 0.2s;
    white-space: nowrap;
}

.magazine-topbar-nav a:hover {
    color: var(--mc-dark);
}

.magazine-topbar-social {
    flex-shrink: 0;
}

/* ── Main bar: dark nav ── */
.magazine-mainbar {
    background: var(--mc-dark);
    border-bottom: 1px solid var(--mc-line);
    position: relative;
}

.magazine-mainbar-inner {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 0.85rem 0;
    min-height: 68px;
}

.magazine-nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: 1px solid var(--mc-line);
    border-radius: 10px;
    padding: 0.45rem;
    cursor: pointer;
    flex-shrink: 0;
}

.magazine-nav-toggle span {
    display: block;
    width: 20px;
    height: 2px;
    background: var(--mc-text);
    border-radius: 1px;
}

.magazine-logo--main {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    flex-shrink: 0;
    color: var(--mc-text) !important;
}

.magazine-logo-mark {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.65rem;
    height: 2.65rem;
    border: 2px solid var(--mc-accent);
    border-radius: 50%;
    background: var(--mc-dark-2);
    overflow: hidden;
    flex-shrink: 0;
}

.magazine-logo--text-only {
    gap: 0;
}

.magazine-logo--text-only .magazine-logo-text {
    font-size: clamp(1.15rem, 2.4vw, 1.4rem);
}

.magazine-logo-mark img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.magazine-logo-text {
    font-family: var(--mc-font);
    font-size: clamp(1.05rem, 2vw, 1.22rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.1;
    color: #fff !important;
    background: none !important;
    -webkit-background-clip: unset !important;
    background-clip: unset !important;
    -webkit-text-fill-color: #fff !important;
}

.magazine-main-nav {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.15rem 1.75rem;
    flex: 1;
    min-width: 0;
}

.magazine-main-nav-link {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    color: var(--mc-muted);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0;
    text-transform: none;
    line-height: 1.2;
    white-space: nowrap;
    padding: 0.45rem 0;
    border-radius: 0;
    position: relative;
    transition: color 0.2s;
}

.magazine-main-nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 2px;
    background: var(--mc-accent);
    border-radius: 999px;
    transition: width 0.25s ease;
}

.magazine-main-nav-link:hover,
.magazine-main-nav-link.is-active,
.magazine-nav-dropdown-wrap.is-active > .magazine-main-nav-link {
    color: #fff;
    background: transparent;
}

.magazine-main-nav-link:hover::after,
.magazine-main-nav-link.is-active::after,
.magazine-nav-dropdown-wrap.is-active > .magazine-main-nav-link::after {
    width: 100%;
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
    top: calc(100% + 0.75rem);
    left: 0;
    min-width: 220px;
    max-height: min(60vh, 360px);
    overflow-y: auto;
    background: var(--mc-dark-2);
    border: 1px solid var(--mc-line);
    border-radius: 12px;
    box-shadow: 0 20px 48px rgba(0, 0, 0, 0.45);
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
    color: var(--mc-muted);
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
    letter-spacing: 0;
    text-transform: none;
    transition: background 0.15s, color 0.15s;
}

.magazine-nav-dropdown a:hover,
.magazine-nav-dropdown a.is-active {
    background: rgba(25, 135, 84, 0.12);
    color: #fff;
}

.magazine-main-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
    margin-left: auto;
}

.magazine-search--main {
    color: var(--mc-text) !important;
    background: transparent !important;
    border: 1px solid var(--mc-line) !important;
}

.magazine-search--main:hover,
.magazine-search-wrap--open .magazine-search--main {
    color: #fff !important;
    background: var(--mc-accent) !important;
    border-color: var(--mc-accent) !important;
}

/* ── Breadcrumb ── */
.magazine-crumb-bar {
    background: var(--mc-dark-2);
    border-bottom: 1px solid var(--mc-line);
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
    color: rgba(255, 255, 255, 0.25);
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
    font-size: 0.76rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    line-height: 1.3;
}

.magazine-breadcrumb a {
    color: var(--mc-muted);
    text-decoration: none;
    transition: color 0.2s;
}

.magazine-breadcrumb a:hover {
    color: #fff;
}

.magazine-breadcrumb-current {
    color: var(--mc-accent);
}

/* ── Mobile nav ── */
.magazine-mobile-nav {
    background: var(--mc-dark-2);
    border-top: 1px solid var(--mc-line);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35);
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
    padding: 0.6rem 0;
    color: var(--mc-text);
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-transform: none;
}

.magazine-mobile-nav > .magazine-shell > a.is-active {
    color: var(--mc-accent);
}

.magazine-mobile-nav-label {
    margin: 0.75rem 0 0.25rem;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.35);
}

.magazine-mobile-nav-sub {
    display: block;
    padding: 0.4rem 0;
    color: var(--mc-muted);
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
}

.magazine-mobile-nav-sub.is-active {
    color: var(--mc-accent);
    font-weight: 600;
}

/* ── Search ── */
.magazine-search-panel {
    display: none;
    background: var(--mc-dark-2);
    border-top: 1px solid var(--mc-line);
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
    background: var(--mc-dark-3);
    border: 1px solid var(--mc-line);
    border-radius: 10px;
    color: var(--mc-text);
    padding: 0.65rem 0.85rem;
    font-size: 1rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form--panel input[type="search"]:focus {
    border-color: var(--mc-accent);
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
}

.magazine-search-form--panel button {
    flex-shrink: 0;
    background: var(--mc-accent);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0 1.1rem;
    font-size: 0.92rem;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    white-space: nowrap;
}

.magazine-search-form--panel button:hover {
    background: var(--mc-accent-dark);
}

.magazine-search-wrap {
    position: relative;
    flex-shrink: 0;
}

.magazine-search {
    flex-shrink: 0;
    width: 2.4rem;
    height: 2.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
    background: transparent;
    border-radius: 50%;
    cursor: pointer;
    transition: color 0.2s, background 0.2s, border-color 0.2s;
    padding: 0;
}

.magazine-search svg {
    width: 1.15rem;
    height: 1.15rem;
}

.magazine-search-dropdown {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    width: min(320px, calc(100vw - 2rem));
    background: var(--mc-dark-2);
    border: 1px solid var(--mc-line);
    border-radius: 12px;
    padding: 0.75rem;
    box-shadow: 0 20px 48px rgba(0, 0, 0, 0.45);
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
    background: var(--mc-dark-3);
    border: 1px solid var(--mc-line);
    border-radius: 8px;
    color: var(--mc-text);
    padding: 0.55rem 0.75rem;
    font-size: 0.95rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form input[type="search"]:focus {
    border-color: var(--mc-accent);
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
}

.magazine-search-form button {
    flex-shrink: 0;
    background: var(--mc-accent);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.55rem 0.9rem;
    font-size: 0.9rem;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
}

.magazine-search-form button:hover {
    background: var(--mc-accent-dark);
}

/* ── Social links ── */
.site-social {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.site-social--topbar {
    gap: 0.65rem;
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
    color: rgba(12, 12, 20, 0.72);
    text-decoration: none;
    transition: color 0.2s, transform 0.2s;
}

.site-social--topbar .site-social-link svg {
    width: 0.95rem;
    height: 0.95rem;
}

.site-social--topbar .site-social-link:hover {
    color: var(--mc-dark);
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
    transition: transform 0.2s, opacity 0.2s, border-color 0.2s;
}

.site-social-link svg {
    width: 1.1rem;
    height: 1.1rem;
}

.site-social--icons .site-social-link {
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 50%;
    color: #fff;
    border: 1px solid transparent;
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
    opacity: 0.92;
}

.site-social--pills {
    gap: 0.55rem;
    margin-top: 1rem;
}

.site-social--pills .site-social-link {
    padding: 0.5rem 1rem;
    border-radius: 999px;
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

/* ── Magazine footer ── */
.site-footer--magazine {
    background: var(--mc-dark);
    border-top: 4px solid var(--mc-accent);
    margin-top: 0;
    color: var(--mc-text);
    font-family: var(--mc-font);
}

.footer-magazine-main {
    padding: 3.5rem 0 2.5rem;
    position: relative;
}

.footer-magazine-main::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 45% 55% at 100% 0%, rgba(25, 135, 84, 0.1) 0%, transparent 65%);
    pointer-events: none;
}

.footer-magazine-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.7fr 0.95fr 1.1fr;
    gap: 2.5rem 2rem;
    align-items: start;
    position: relative;
}

.footer-magazine-menu-title,
.footer-magazine-recent-title {
    font-size: 0.72rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
}

.footer-magazine-menu-title::after,
.footer-magazine-recent-title::after {
    content: '';
    display: block;
    width: 2rem;
    height: 3px;
    background: var(--mc-accent);
    border-radius: 999px;
    margin-top: 0.45rem;
}

.footer-magazine-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}

.footer-magazine-menu a {
    color: var(--mc-muted);
    text-decoration: none;
    font-size: 0.9rem;
    line-height: 1.5;
    transition: color 0.2s, padding-left 0.2s;
}

.footer-magazine-menu a:hover {
    color: #fff;
    padding-left: 0.3rem;
}

.footer-magazine-logo {
    display: inline-block;
    font-family: var(--mc-font);
    font-size: 1.45rem;
    font-weight: 700;
    color: #fff !important;
    text-decoration: none;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.footer-magazine-brand p {
    font-size: 0.92rem;
    line-height: 1.7;
    color: var(--mc-muted);
    margin: 0;
    max-width: 320px;
}

.footer-magazine-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}

.footer-gallery-item {
    display: block;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 14px;
    background: var(--mc-dark-2);
    border: 1px solid var(--mc-line);
    transition: border-color 0.25s, transform 0.25s;
}

.footer-gallery-item:hover {
    border-color: var(--mc-accent);
    transform: translateY(-2px);
}

.footer-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.footer-gallery-item:hover img {
    transform: scale(1.08);
}

.footer-recent-list {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}

.footer-recent-item {
    display: grid;
    grid-template-columns: 64px 1fr;
    gap: 0.75rem;
    text-decoration: none;
    color: inherit;
    align-items: start;
    padding-bottom: 1.1rem;
    border-bottom: 1px solid var(--mc-line);
}

.footer-recent-item:last-child {
    padding-bottom: 0;
    border-bottom: none;
}

.footer-recent-thumb {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    overflow: hidden;
    background: var(--mc-dark-2);
    border: 1px solid var(--mc-line);
}

.footer-recent-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.footer-recent-tag {
    display: block;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    margin-bottom: 0.2rem;
}

.footer-recent-name {
    display: block;
    font-size: 0.92rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.4;
    margin-bottom: 0.2rem;
    transition: color 0.2s;
}

.footer-recent-meta {
    display: block;
    font-size: 0.68rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.35);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.footer-recent-item:hover .footer-recent-name {
    color: var(--mc-accent);
}

.footer-magazine-bar {
    background: #080810;
    border-top: 1px solid var(--mc-line);
    padding: 1.1rem 0;
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
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.4);
}

.footer-magazine-bar .site-social--icons .site-social-link {
    width: 1.75rem;
    height: 1.75rem;
    background: transparent !important;
    border: 1px solid var(--mc-line);
    color: var(--mc-muted);
}

.footer-magazine-bar .site-social--icons .site-social-link:hover {
    border-color: var(--mc-accent);
    color: #fff;
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
        --magazine-sticky-offset: 4.5rem;
    }

    .magazine-header.magazine-header--compact {
        --magazine-sticky-offset: 4rem;
    }

    body:has(.magazine-mainbar--search-open) {
        --magazine-sticky-offset: 7rem;
    }

    body.magazine-site:has(.magazine-crumb-bar),
    body:has(.home-magazine):has(.magazine-crumb-bar) {
        --magazine-sticky-offset: 7.5rem;
    }

    .magazine-topbar-tagline {
        display: none;
    }

    .magazine-topbar-inner {
        justify-content: center;
        padding: 0.4rem 0;
    }

    .magazine-topbar-nav {
        justify-content: center;
    }

    .magazine-topbar-nav a {
        font-size: 0.68rem;
    }

    .magazine-mainbar-inner {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 0.65rem;
        padding: 0.65rem 0;
        min-height: 56px;
    }

    .magazine-nav-toggle {
        display: flex;
        grid-column: 1;
        width: 2.65rem;
        height: 2.65rem;
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
        background: transparent;
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
        gap: 0.75rem;
    }

    .footer-magazine-main {
        padding: 2.5rem 0 2rem;
    }
}
</style>
