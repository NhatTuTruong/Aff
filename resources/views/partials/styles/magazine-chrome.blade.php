<style>
/* Magazine header, footer, social — homepage */
:root {
    --magazine-shell: min(1320px, calc(100% - 2rem));
    --magazine-sticky-offset: 9rem;
}

body:has(.home-magazine) main,
body.magazine-site main {
    padding-top: 0;
}

.magazine-shell {
    width: var(--magazine-shell);
    margin-inline: auto;
}

/* Header banner */
.magazine-header {
    position: sticky;
    top: 0;
    z-index: 120;
    transition: box-shadow 0.25s ease;
}

.magazine-header.magazine-header--compact {
    --magazine-sticky-offset: 4.75rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
}

.magazine-banner-collapsible {
    overflow: hidden;
    max-height: 120px;
    opacity: 1;
    transition: max-height 0.28s ease, opacity 0.22s ease, margin 0.28s ease;
}

.magazine-banner-nav.magazine-banner-collapsible {
    max-height: 80px;
}

.magazine-header.magazine-header--compact .magazine-banner-collapsible {
    max-height: 0;
    opacity: 0;
    margin: 0;
    pointer-events: none;
}

.magazine-header.magazine-header--compact .magazine-banner-grid {
    padding: 0.4rem 0;
    gap: 0.75rem;
}

.magazine-header.magazine-header--compact .magazine-logo {
    font-size: clamp(1.1rem, 2vw, 1.35rem);
}

.magazine-header.magazine-header--compact .magazine-nav-inner {
    padding: 0.3rem 0;
}

.magazine-header.magazine-header--compact .magazine-nav a {
    font-size: 0.72rem;
    padding: 0.1rem 0;
}

.magazine-header.magazine-header--compact .magazine-search {
    width: 1.9rem;
    height: 1.9rem;
}

.magazine-banner {
    background: linear-gradient(135deg, #8b1538 0%, #b91c1c 45%, #991b1b 100%);
    border-bottom: 1px solid rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.magazine-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
    pointer-events: none;
}

.magazine-banner-grid {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 1.5rem;
    align-items: center;
    padding: 1.1rem 0;
    position: relative;
    z-index: 1;
    transition: padding 0.25s ease, gap 0.25s ease;
}

.magazine-brand {
    min-width: 0;
}

.magazine-logo {
    display: inline-block;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.35rem, 2.5vw, 1.85rem);
    font-weight: 800;
    color: #fff !important;
    text-decoration: none;
    letter-spacing: -0.02em;
    line-height: 1.1;
    transition: font-size 0.25s ease;
}

.magazine-tagline {
    margin: 0.35rem 0 0.65rem;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.82);
    line-height: 1.4;
}

.magazine-banner-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem 1.25rem;
    justify-content: flex-end;
    align-items: center;
    align-content: center;
    min-height: 60px;
}

.magazine-banner-nav a {
    color: rgba(255,255,255,0.88);
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    transition: color 0.2s;
    white-space: nowrap;
}

.magazine-banner-nav a:hover,
.magazine-banner-nav a.is-active {
    color: #fff;
    text-decoration: underline;
    text-underline-offset: 3px;
}

/* Category nav */
.magazine-nav-wrap {
    background: #111;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    position: relative;
}

.magazine-nav-inner {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.55rem 0;
    position: relative;
    transition: padding 0.25s ease;
}

@media (min-width: 769px) {
    .magazine-nav-inner {
        align-items: center;
    }
}

.magazine-nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    padding: 0.35rem;
    cursor: pointer;
}

.magazine-nav-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background: #fff;
    border-radius: 1px;
}

.magazine-nav {
    flex: 1;
    min-width: 0;
}

@media (min-width: 769px) {
    .magazine-nav {
        display: flex;
        align-items: center;
    }
}

.magazine-nav-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.15rem 1.1rem;
    align-items: center;
    row-gap: 0.35rem;
}

.magazine-nav-row + .magazine-nav-row {
    margin-top: 0;
}

.magazine-nav a {
    color: rgba(255,255,255,0.78);
    text-decoration: none;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
    transition: color 0.2s;
    padding: 0.15rem 0;
}

.magazine-nav a:hover,
.magazine-nav a.is-active {
    color: #fff;
}

.magazine-nav-actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-shrink: 0;
    margin-left: auto;
}

.magazine-nav-social {
    display: flex;
    align-items: center;
}

.magazine-nav-social .site-social--icons {
    gap: 0.4rem;
}

.magazine-nav-social .site-social--icons .site-social-link {
    width: 1.85rem;
    height: 1.85rem;
}

.magazine-nav-social .site-social--icons .site-social-link svg {
    width: 0.9rem;
    height: 0.9rem;
}

.magazine-nav-section-label {
    display: none;
    margin: 0 0 0.5rem;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
}

.magazine-search-panel {
    display: none;
    background: #141414;
    border-bottom: 1px solid rgba(255,255,255,0.08);
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
    background: #111;
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 8px;
    color: #fff;
    padding: 0.65rem 0.85rem;
    font-size: 1rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form--panel input[type="search"]:focus {
    border-color: #e91e8c;
    box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.15);
}

.magazine-search-form--panel button {
    flex-shrink: 0;
    background: #e91e8c;
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
    background: #c2185b;
}

.magazine-search-wrap {
    position: relative;
    flex-shrink: 0;
}

.magazine-search {
    flex-shrink: 0;
    width: 2.15rem;
    height: 2.15rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    border: none;
    background: transparent;
    border-radius: 50%;
    cursor: pointer;
    transition: color 0.2s, background 0.2s;
    padding: 0;
}

.magazine-search svg {
    width: 1.1rem;
    height: 1.1rem;
}

.magazine-search:hover,
.magazine-search-wrap--open .magazine-search,
.magazine-nav-wrap--search-open .magazine-search {
    color: #fff;
    background: rgba(255,255,255,0.08);
}

.magazine-nav-wrap--open .magazine-nav-toggle {
    background: rgba(255,255,255,0.08);
    border-radius: 6px;
}

.magazine-search-dropdown {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    width: min(320px, calc(100vw - 2rem));
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 16px 40px rgba(0,0,0,0.45);
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
    background: #111;
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 6px;
    color: #fff;
    padding: 0.55rem 0.75rem;
    font-size: 0.95rem;
    font-family: inherit;
    outline: none;
}

.magazine-search-form input[type="search"]:focus {
    border-color: #e91e8c;
    box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.15);
}

.magazine-search-form button {
    flex-shrink: 0;
    background: #e91e8c;
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
    background: #c2185b;
}

/* Social links */
.site-social {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
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
    .magazine-nav-section--mobile {
        display: none;
    }

    .magazine-nav-section-label--mobile {
        display: none;
    }

    .magazine-nav-section--categories {
        flex: 1;
        min-width: 0;
    }

    .magazine-nav-inner {
        gap: 1.25rem;
    }

    .magazine-search-panel {
        display: none !important;
    }
}

/* Magazine footer */
.site-footer--magazine {
    background: #1a1a1a;
    border-top: 1px solid rgba(255,255,255,0.08);
    margin-top: 0;
    color: #e5e5e5;
}

.footer-magazine-main {
    padding: 2.5rem 0 2rem;
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
}

.footer-magazine-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.footer-magazine-menu a {
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.92rem;
    line-height: 1.5;
    transition: color 0.2s;
}

.footer-magazine-menu a:hover {
    color: #fff;
}

.footer-magazine-logo {
    display: inline-block;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff !important;
    text-decoration: none;
    margin-bottom: 0.75rem;
}

.footer-magazine-brand p {
    font-size: 1rem;
    line-height: 1.65;
    color: #9ca3af;
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
    border-radius: 2px;
    background: #222;
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
    color: #9ca3af;
    margin: 0 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
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
    border-radius: 4px;
    overflow: hidden;
    background: #222;
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
}

.footer-recent-meta {
    display: block;
    font-size: 0.72rem;
    color: #9ca3af;
}

.footer-magazine-bar {
    background: #111;
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 0.85rem 0;
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
    color: #9ca3af;
}

.footer-magazine-bar .site-social--icons .site-social-link {
    width: 1.65rem;
    height: 1.65rem;
}

.footer-magazine-bar .site-social--icons .site-social-link svg {
    width: 0.85rem;
    height: 0.85rem;
}

/* Default header social */
.site-header__actions .site-social--icons {
    margin-right: 0.25rem;
}

.site-header__actions .site-social--icons .site-social-link {
    width: 1.75rem;
    height: 1.75rem;
}

@media (max-width: 1024px) {
    .magazine-banner-grid {
        grid-template-columns: 1fr;
        gap: 0;
        padding: 0.2rem 0;
    }

    .magazine-banner-nav {
        justify-content: flex-start;
        min-height: 30px;
    }

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
        --magazine-sticky-offset: 4.25rem;
    }

    .magazine-header.magazine-header--compact {
        --magazine-sticky-offset: 3.35rem;
    }

    body:has(.magazine-nav-wrap--search-open) {
        --magazine-sticky-offset: 6.75rem;
    }

    .magazine-banner-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.55rem 0;
    }

    .magazine-brand {
        flex: 1;
        min-width: 0;
    }

    .magazine-tagline,
    .magazine-banner-nav {
        display: none;
    }

    .magazine-nav-social {
        display: none;
    }

    .magazine-search-dropdown {
        display: none !important;
    }

    .magazine-nav-inner {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        padding: 0.4rem 0;
        gap: 0.5rem;
        position: relative;
    }

    .magazine-nav-toggle {
        display: flex;
        width: 2.75rem;
        height: 2.75rem;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        grid-column: 1;
        border: none;
    }

    .magazine-search {
        width: 2.75rem;
        height: 2.75rem;
    }

    .magazine-nav-actions {
        gap: 0.35rem;
        grid-column: 3;
        justify-self: end;
    }

    .magazine-header.magazine-header--compact .magazine-banner-grid {
        padding: 0.35rem 0;
    }

    .magazine-header.magazine-header--compact .magazine-nav-inner {
        padding: 0.25rem 0;
    }

    .magazine-nav {
        display: none;
        grid-column: 1 / -1;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        width: 100%;
        background: #111;
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 0.75rem 1rem 1rem;
        box-shadow: 0 12px 24px rgba(0,0,0,0.4);
        max-height: min(70vh, 420px);
        overflow-y: auto;
        z-index: 125;
    }

    .magazine-nav-wrap--open .magazine-nav {
        display: block;
    }

    .magazine-nav-section {
        padding-inline: 0;
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

    .magazine-nav-section + .magazine-nav-section {
        margin-top: 0.85rem;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(255,255,255,0.08);
    }

    .magazine-nav-section-label {
        display: block;
    }

    .magazine-nav-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.35rem;
    }

    .magazine-nav-row a {
        display: block;
        width: 100%;
        padding: 0.35rem 0;
        font-size: 0.82rem;
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

    .magazine-logo {
        font-size: 1.15rem;
    }

    .magazine-header.magazine-header--compact .magazine-logo {
        font-size: 1.05rem;
    }

    .footer-magazine-bar-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.65rem;
    }
}
</style>
