<style>
/* Public chrome — header + footer (#00405d brand) */
:root {
    --chrome-ink: #0c1924;
    --chrome-muted: #5a7184;
    --chrome-muted-soft: #8fa3b3;
    --chrome-line: rgba(12, 25, 36, 0.09);
    --chrome-teal: #00405d;
    --chrome-teal-deep: #003347;
    --chrome-teal-light: #4da8c4;
    --chrome-cream: #f2f6f8;
    --chrome-white: #ffffff;
    --chrome-footer-0: #001a26;
    --chrome-footer-1: #002534;

    --chrome-radius-input: 12px;
    --chrome-input-bg: #ffffff;
    --chrome-input-border: rgba(12, 25, 36, 0.14);
    --chrome-input-border-focus: rgba(0, 64, 93, 0.65);
    --chrome-input-ring: rgba(0, 64, 93, 0.14);
}

.site-chrome-topbar {
    background: linear-gradient(92deg, #001a26 0%, #00405d 55%, #003347 100%);
    color: rgba(226, 236, 242, 0.96);
    font-size: 0.8125rem;
    text-align: center;
    padding: 0.45rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    letter-spacing: 0.01em;
}
.site-chrome-topbar a {
    color: #7ec8db;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-chrome-topbar a:hover {
    color: #b8e4ef;
}

.site-header {
    background: rgba(255, 255, 255, 0.97);
    border-bottom: 1px solid var(--chrome-line);
    box-shadow: 0 4px 24px -12px rgba(0, 26, 38, 0.12);
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(16px);
}
.site-header::after {
    content: '';
    display: block;
    height: 3px;
    background: linear-gradient(90deg, var(--chrome-teal) 0%, var(--chrome-teal-light) 50%, var(--chrome-teal) 100%);
}
.site-header .header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0.85rem 1.25rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
}
.site-header .logo {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-weight: 700;
    font-size: 1.35rem;
    color: var(--chrome-ink) !important;
    text-decoration: none;
    letter-spacing: -0.02em;
    flex: 1 1 auto;
    min-width: 0;
}
.site-header .logo span {
    background: linear-gradient(135deg, var(--chrome-teal) 0%, var(--chrome-teal-light) 100%) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    color: var(--chrome-teal) !important;
}
.site-header .logo--image,
.site-footer .logo--image {
    display: inline-flex;
    align-items: center;
    line-height: 0;
}
.site-logo-img {
    display: block;
    height: 2rem;
    width: auto;
    max-width: min(220px, 100%);
    object-fit: contain;
}
.site-footer .site-logo-img {
    height: 1.75rem;
    max-width: min(200px, 100%);
}
.site-header__actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-shrink: 0;
}
.site-header__cta {
    display: none;
    align-items: center;
    padding: 0.52rem 1.1rem;
    background: var(--chrome-teal);
    color: #fff !important;
    font-weight: 700;
    font-size: 0.8125rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    border-radius: 999px;
    text-decoration: none;
    box-shadow: 0 6px 18px -6px rgba(0, 64, 93, 0.45);
    transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
}
.site-header__cta:hover {
    color: #fff !important;
    background: var(--chrome-teal-deep);
    transform: translateY(-1px);
    box-shadow: 0 10px 24px -8px rgba(0, 64, 93, 0.4);
}
.site-header__toggle {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    width: 44px;
    height: 44px;
    padding: 0;
    border: 1px solid var(--chrome-line);
    border-radius: 10px;
    background: var(--chrome-cream);
    color: var(--chrome-ink);
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s;
    -webkit-tap-highlight-color: transparent;
}
.site-header__toggle:hover {
    background: #e8eef2;
    border-color: rgba(0, 64, 93, 0.25);
}
.site-header__toggle-bar {
    display: block;
    width: 20px;
    height: 2px;
    background: currentColor;
    border-radius: 1px;
    transition: transform 0.25s ease, opacity 0.2s ease;
}
.site-header--nav-open .site-header__toggle-bar:nth-child(1) { transform: translateY(8px) rotate(45deg); }
.site-header--nav-open .site-header__toggle-bar:nth-child(2) { opacity: 0; }
.site-header--nav-open .site-header__toggle-bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }
.site-header .nav-links {
    display: flex;
    align-items: center;
    gap: 0.25rem 1.15rem;
    flex-wrap: wrap;
}
.site-header .nav-links a {
    color: var(--chrome-muted) !important;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    padding: 0.35rem 0;
    border-bottom: 2px solid transparent;
    transition: color 0.2s, border-color 0.2s;
}
.site-header .nav-links a:hover {
    color: var(--chrome-teal) !important;
    border-bottom-color: var(--chrome-teal-light);
}
.site-header .nav-links a.is-active {
    color: var(--chrome-teal) !important;
    border-bottom-color: var(--chrome-teal);
}

@media (min-width: 769px) {
    .site-header__cta { display: inline-flex; }
    .site-header .header-inner { flex-wrap: nowrap; }
    .site-header .logo { flex: 0 1 auto; }
    .site-header__toggle { display: none !important; }
    .site-header .nav-links {
        display: flex !important;
        width: auto;
        flex-basis: auto;
        order: unset;
        margin: 0;
        padding: 0;
        border-top: none;
        background: transparent;
    }
    .site-header .nav-links a {
        padding: 0.35rem 0;
        border-bottom: 2px solid transparent;
    }
}

@media (max-width: 768px) {
    .site-header__toggle { display: flex; }
    .site-header .nav-links {
        display: none;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        width: 100%;
        flex-basis: 100%;
        order: 3;
        padding-top: 0.35rem;
        margin: 0 -1.25rem -0.85rem;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
        padding-bottom: 0.65rem;
        border-top: 1px solid var(--chrome-line);
        background: var(--chrome-cream);
    }
    .site-header--nav-open .nav-links { display: flex; }
    .site-header .nav-links a {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(12, 25, 36, 0.06);
        font-size: 1rem;
        color: var(--chrome-ink) !important;
    }
    .site-header .nav-links a:hover {
        color: var(--chrome-teal) !important;
        border-bottom-color: rgba(12, 25, 36, 0.06);
    }
    .site-header .nav-links a:last-child { border-bottom: none; }
}

input[type="text"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="number"],
input[type="password"],
input[type="date"],
input[type="time"],
input[type="datetime-local"],
textarea,
select {
    border-radius: var(--chrome-radius-input);
    background: var(--chrome-input-bg);
    border: 1px solid var(--chrome-input-border);
    padding: 0.65rem 0.85rem;
    color: var(--chrome-ink);
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

textarea { min-height: 110px; resize: vertical; }

input:focus,
textarea:focus,
select:focus {
    border-color: var(--chrome-input-border-focus);
    box-shadow: 0 0 0 4px var(--chrome-input-ring);
}

input::placeholder,
textarea::placeholder {
    color: rgba(90, 113, 132, 0.85);
}

.site-footer {
    background: linear-gradient(180deg, var(--chrome-footer-0) 0%, var(--chrome-footer-1) 100%);
    border-top: 1px solid rgba(77, 168, 196, 0.15);
    margin-top: auto;
    color: #d4e8ef;
}
.site-footer .footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3.25rem 1.25rem 1.75rem;
}
.site-footer .footer-grid {
    display: grid;
    grid-template-columns: 1.2fr repeat(3, minmax(0, 1fr));
    gap: 2.25rem 1.75rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 900px) {
    .site-footer .footer-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.75rem 1.25rem;
    }
    .site-footer .footer-brand {
        grid-column: 1 / -1;
    }
}
@media (max-width: 520px) {
    .site-footer .footer-inner {
        padding: 2.25rem 1rem 1.5rem;
    }
    .site-footer .footer-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.35rem 0.85rem;
        margin-bottom: 1.35rem;
    }
    .site-footer .footer-brand {
        grid-column: 1 / -1;
        padding-bottom: 0.25rem;
    }
    .site-footer .footer-brand p {
        max-width: none;
        font-size: 0.875rem;
    }
    .site-footer .footer-col h4 {
        font-size: 0.68rem;
        margin-bottom: 0.65rem;
    }
    .site-footer .footer-col li {
        margin-bottom: 0.4rem;
    }
    .site-footer .footer-col a {
        font-size: 0.875rem;
        line-height: 1.35;
    }
}
.site-footer .footer-brand .logo {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #e8f4f8 !important;
}
.site-footer .footer-brand .logo span {
    background: linear-gradient(135deg, #7ec8db 0%, #4da8c4 100%) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    color: #7ec8db !important;
}
.site-footer .footer-brand p {
    margin-top: 0.875rem;
    color: var(--chrome-muted-soft);
    font-size: 0.9rem;
    max-width: 300px;
    line-height: 1.65;
}
.site-footer .footer-social-link {
    display: inline-flex;
    margin-top: 1rem;
    color: #7ec8db;
    transition: color 0.2s, transform 0.2s;
}
.site-footer .footer-social-link:hover {
    color: #b8e4ef;
    transform: translateY(-1px);
}
.site-footer .footer-col h4 {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(126, 200, 219, 0.75);
    margin-bottom: 1rem;
}
.site-footer .footer-col ul { list-style: none; margin: 0; padding: 0; }
.site-footer .footer-col li { margin-bottom: 0.55rem; }
.site-footer .footer-col a {
    color: #e8f4f8;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: color 0.2s;
}
.site-footer .footer-col a:hover {
    color: #7ec8db;
}
.site-footer .footer-disclosure {
    padding: 1.15rem 0;
    border-top: 1px solid rgba(77, 168, 196, 0.12);
}
.site-footer .footer-disclosure-text {
    font-size: 0.8125rem;
    color: var(--chrome-muted-soft);
    line-height: 1.65;
    max-width: 760px;
}
.site-footer .footer-disclosure-text a {
    color: #b8e4ef;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-footer .footer-disclosure-text a:hover {
    color: #e8f4f8;
}
.site-footer .footer-bottom {
    padding-top: 1.35rem;
    border-top: 1px solid rgba(77, 168, 196, 0.12);
}
.site-footer .footer-bottom p {
    color: rgba(126, 200, 219, 0.65);
    font-size: 0.8125rem;
    line-height: 1.5;
}

.back-to-top {
    position: fixed;
    right: 1.25rem;
    bottom: 1.25rem;
    z-index: 990;
    width: 3rem;
    height: 3rem;
    border: none;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #fff;
    background: var(--chrome-teal);
    box-shadow: 0 8px 24px -6px rgba(0, 64, 93, 0.5);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(12px) scale(0.92);
    transition: opacity 0.25s ease, visibility 0.25s ease, transform 0.25s ease, background 0.2s ease, box-shadow 0.2s ease;
    -webkit-tap-highlight-color: transparent;
}
.back-to-top.is-visible {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0) scale(1);
}
.back-to-top:hover {
    background: var(--chrome-teal-deep);
    box-shadow: 0 12px 28px -6px rgba(0, 64, 93, 0.55);
    transform: translateY(-2px) scale(1);
}
.back-to-top:focus-visible {
    outline: 2px solid var(--chrome-teal-light);
    outline-offset: 3px;
}
.back-to-top svg {
    width: 1.15rem;
    height: 1.15rem;
    display: block;
}
body:has(#cookie-consent:not([hidden])) .back-to-top.is-visible {
    bottom: 5.5rem;
}
@media (max-width: 640px) {
    .back-to-top {
        right: 1rem;
        bottom: 1rem;
        width: 2.75rem;
        height: 2.75rem;
    }
    body:has(#cookie-consent:not([hidden])) .back-to-top.is-visible {
        bottom: 5rem;
    }
}
</style>
