<style>
/* Public chrome — header + footer (synced with home sky-blue palette) */
:root {
    --chrome-ink: #0f172a;
    --chrome-muted: #64748b;
    --chrome-muted-soft: #94a3b8;
    --chrome-line: rgba(15, 23, 42, 0.08);
    --chrome-violet: #7c3aed;
    --chrome-violet-deep: #6d28d9;
    --chrome-rose: #6d28d9;
    --chrome-cream: #f5f5f5;
    --chrome-white: #ffffff;
    --chrome-footer-0: #0f172a;
    --chrome-footer-1: #1e293b;

    /* Public form controls */
    --chrome-radius-input: 14px;
    --chrome-input-bg: #ffffff;
    --chrome-input-border: rgba(15, 23, 42, 0.14);
    --chrome-input-border-focus: rgba(13, 110, 253, 0.65);
    --chrome-input-ring: rgba(13, 110, 253, 0.18);
}

.site-chrome-topbar {
    background: linear-gradient(92deg, #111827 0%, #0f172a 50%, #1e293b 100%);
    color: rgba(226, 232, 240, 0.96);
    font-size: 0.8125rem;
    text-align: center;
    padding: 0.45rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    letter-spacing: 0.01em;
}
.site-chrome-topbar a {
    color: #a78bfa;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-chrome-topbar a:hover {
    color: #e0e7ff;
}

.site-header {
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid var(--chrome-line);
    box-shadow: 0 8px 32px -20px rgba(11, 23, 36, 0.2);
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(14px);
}
.site-header .header-inner {
    max-width: 1180px;
    margin: 0 auto;
    padding: 0.8rem 1.25rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
}
.site-header .logo {
    font-family: 'Space Grotesk', 'DM Sans', system-ui, sans-serif;
    font-weight: 700;
    font-size: 1.35rem;
    color: var(--chrome-ink) !important;
    text-decoration: none;
    letter-spacing: -0.035em;
    flex: 1 1 auto;
    min-width: 0;
}
.site-header .logo span {
    background: linear-gradient(135deg, var(--chrome-violet) 0%, var(--chrome-rose) 100%) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    color: var(--chrome-violet) !important;
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
    background: linear-gradient(135deg, var(--chrome-violet) 0%, var(--chrome-rose) 100%);
    color: #fff !important;
    font-weight: 700;
    font-size: 0.8125rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    border-radius: 999px;
    text-decoration: none;
    box-shadow: 0 8px 22px -8px rgba(47, 194, 169, 0.5);
    transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
}
.site-header__cta:hover {
    color: #fff !important;
    transform: translateY(-1px);
    box-shadow: 0 12px 28px -8px rgba(47, 194, 169, 0.45);
    filter: brightness(1.07) saturate(1.04);
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
    border-radius: 12px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    color: var(--chrome-ink);
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s;
    -webkit-tap-highlight-color: transparent;
}
.site-header__toggle:hover {
    background: #e2e8f0;
    border-color: rgba(47, 194, 169, 0.35);
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
    color: var(--chrome-violet-deep) !important;
    border-bottom-color: #834bdd;
}
.site-header .nav-links a.is-active {
    color: var(--chrome-violet) !important;
    border-bottom-color: var(--chrome-violet);
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
        margin: 0 -1.25rem -0.8rem;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
        padding-bottom: 0.65rem;
        border-top: 1px solid var(--chrome-line);
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }
    .site-header--nav-open .nav-links { display: flex; }
    .site-header .nav-links a {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(11, 23, 36, 0.06);
        font-size: 1rem;
        color: var(--chrome-ink) !important;
    }
    .site-header .nav-links a:hover {
        color: var(--chrome-violet-deep) !important;
        border-bottom-color: rgba(11, 23, 36, 0.06);
    }
    .site-header .nav-links a:last-child { border-bottom: none; }
}

/* Public inputs — make them rounded consistently */
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
    color: rgba(100, 116, 139, 0.85);
}

.site-footer {
    background: #0a1525;
    border-top: 1px solid rgba(148, 163, 184, 0.24);
    margin-top: auto;
    color: #e0f2fe;
}
.site-footer .footer-inner {
    max-width: 1180px;
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
    font-family: 'Space Grotesk', 'DM Sans', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #f0f9ff !important;
}
.site-footer .footer-brand .logo span {
    background: linear-gradient(135deg, #a78bfa 0%, #6d28d9 100%) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    color: #a78bfa !important;
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
    color: #93c5fd;
    transition: color 0.2s, transform 0.2s;
}
.site-footer .footer-social-link:hover {
    color: #dbeafe;
    transform: translateY(-1px);
}
.site-footer .footer-col h4 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(148, 163, 184, 0.9);
    margin-bottom: 1rem;
}
.site-footer .footer-col ul { list-style: none; margin: 0; padding: 0; }
.site-footer .footer-col li { margin-bottom: 0.55rem; }
.site-footer .footer-col a {
    color: #f0f9ff;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: color 0.2s;
}
.site-footer .footer-col a:hover {
    color: #93c5fd;
}
.site-footer .footer-disclosure {
    padding: 1.15rem 0;
    border-top: 1px solid rgba(125, 211, 252, 0.14);
}
.site-footer .footer-disclosure-text {
    font-size: 0.8125rem;
    color: var(--chrome-muted-soft);
    line-height: 1.65;
    max-width: 760px;
}
.site-footer .footer-disclosure-text a {
    color: #e0e7ff;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-footer .footer-disclosure-text a:hover {
    color: #e0f2fe;
}
.site-footer .footer-bottom {
    padding-top: 1.35rem;
    border-top: 1px solid rgba(125, 211, 252, 0.14);
}
.site-footer .footer-bottom p {
    color: rgba(125, 211, 252, 0.76);
    font-size: 0.8125rem;
    line-height: 1.5;
}

/* Back to top — circular floating button */
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
    background: linear-gradient(145deg, #7c3aed 0%, #6d28d9 100%);
    box-shadow: 0 8px 24px -6px rgba(124, 58, 237, 0.55);
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
    background: linear-gradient(145deg, #6d28d9 0%, #7c3aed 100%);
    box-shadow: 0 12px 28px -6px rgba(124, 58, 237, 0.6);
    transform: translateY(-2px) scale(1);
}
.back-to-top:focus-visible {
    outline: 2px solid #7c3aed;
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
