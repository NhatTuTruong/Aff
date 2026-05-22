<style>
/* Public chrome — header + footer (emerald primary #059669) */
:root {
    --chrome-ink: #0f172a;
    --chrome-muted: #64748b;
    --chrome-muted-soft: #94a3b8;
    --chrome-line: rgba(5, 150, 105, 0.12);
    --chrome-violet: #059669;
    --chrome-violet-deep: #047857;
    --chrome-rose: #10b981;
    --chrome-cream: #ecfdf5;
    --chrome-white: #ffffff;
    --chrome-footer-0: #064e3b;
    --chrome-footer-1: #047857;
}

.site-chrome-topbar {
    margin: 0;
    background: linear-gradient(92deg, #064e3b 0%, #047857 48%, #059669 100%);
    color: rgba(236, 253, 245, 0.95);
    font-size: 0.8125rem;
    text-align: center;
    padding: 0.28rem 1rem;
    line-height: 1.35;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    letter-spacing: 0.01em;
}
.site-chrome-topbar a {
    color: #a7f3d0;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-chrome-topbar a:hover {
    color: #ecfdf5;
}

.site-header {
    background: rgba(255, 255, 255, 0.92);
    border-bottom: 1px solid var(--chrome-line);
    box-shadow: 0 8px 32px -20px rgba(5, 150, 105, 0.15);
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
    border-radius: 5px;
    text-decoration: none;
    box-shadow: 0 8px 22px -8px rgba(5, 150, 105, 0.45);
    transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
}
.site-header__cta:hover {
    color: #fff !important;
    transform: translateY(-1px);
    box-shadow: 0 12px 28px -8px rgba(5, 150, 105, 0.4);
    filter: brightness(1.03);
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
    border-radius: 5px;
    background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
    color: var(--chrome-ink);
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s;
    -webkit-tap-highlight-color: transparent;
}
.site-header__toggle:hover {
    background: #d1fae5;
    border-color: rgba(5, 150, 105, 0.35);
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
    border-bottom-color: rgba(5, 150, 105, 0.55);
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
        background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
    }
    .site-header--nav-open .nav-links { display: flex; }
    .site-header .nav-links a {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        font-size: 1rem;
        color: var(--chrome-ink) !important;
    }
    .site-header .nav-links a:hover {
        color: var(--chrome-violet-deep) !important;
        border-bottom-color: rgba(15, 23, 42, 0.06);
    }
    .site-header .nav-links a:last-child { border-bottom: none; }
}

.site-footer {
    background: linear-gradient(180deg, var(--chrome-footer-0) 0%, var(--chrome-footer-1) 55%, #022c22 100%);
    border-top: 1px solid rgba(110, 231, 183, 0.15);
    margin-top: auto;
    color: #d1fae5;
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
    .site-footer .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 520px) {
    .site-footer .footer-grid { grid-template-columns: 1fr; }
}
.site-footer .footer-brand .logo {
    font-family: 'Space Grotesk', 'DM Sans', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #ecfdf5 !important;
}
.site-footer .footer-brand .logo span {
    background: linear-gradient(135deg, #6ee7b7 0%, #34d399 100%) !important;
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    color: #6ee7b7 !important;
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
    color: #6ee7b7;
    transition: color 0.2s, transform 0.2s;
}
.site-footer .footer-social-link:hover {
    color: #a7f3d0;
    transform: translateY(-1px);
}
.site-footer .footer-col h4 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(110, 231, 183, 0.75);
    margin-bottom: 1rem;
}
.site-footer .footer-col ul { list-style: none; margin: 0; padding: 0; }
.site-footer .footer-col li { margin-bottom: 0.55rem; }
.site-footer .footer-col a {
    color: #ecfdf5;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: color 0.2s;
}
.site-footer .footer-col a:hover {
    color: #6ee7b7;
}
.site-footer .footer-disclosure {
    padding: 1.15rem 0;
    border-top: 1px solid rgba(110, 231, 183, 0.15);
}
.site-footer .footer-disclosure-text {
    font-size: 0.8125rem;
    color: var(--chrome-muted-soft);
    line-height: 1.65;
    max-width: 760px;
}
.site-footer .footer-disclosure-text a {
    color: #a7f3d0;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.site-footer .footer-disclosure-text a:hover {
    color: #ecfdf5;
}
.site-footer .footer-bottom {
    padding-top: 1.35rem;
    border-top: 1px solid rgba(110, 231, 183, 0.15);
}
.site-footer .footer-bottom p {
    color: rgba(110, 231, 183, 0.75);
    font-size: 0.8125rem;
    line-height: 1.5;
}
</style>
