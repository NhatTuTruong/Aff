<style>
.site-chrome-vars,:root {
    --chrome-bg: #ffffff;
    --chrome-surface: #f9fafb;
    --chrome-text: #111827;
    --chrome-text-muted: #6b7280;
    --chrome-accent: #f59e0b;
    --chrome-accent-hover: #d97706;
    --chrome-border: #e5e7eb;
    --chrome-radius: 12px;
    --chrome-radius-sm: 8px;
}
.site-header {
    background: linear-gradient(135deg, var(--text-dark, #111827) 0%, var(--primary-dark, #16a34a) 100%);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    position: sticky;
    top: 0;
    z-index: 100;
}
.site-header .header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 1.5rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
}
.site-header .logo {
    font-family: 'Space Grotesk', 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: 1.35rem;
    color: #ffffff;
    text-decoration: none;
    letter-spacing: -0.02em;
    flex: 1 1 auto;
    min-width: 0;
}
.site-header .logo span { color: #fde68a; }
.site-header__toggle {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    width: 44px;
    height: 44px;
    padding: 0;
    border: none;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s;
    -webkit-tap-highlight-color: transparent;
}
.site-header__toggle:hover {
    background: rgba(255, 255, 255, 0.2);
}
.site-header__toggle-bar {
    display: block;
    width: 22px;
    height: 2px;
    background: currentColor;
    border-radius: 1px;
    transition: transform 0.25s ease, opacity 0.2s ease;
}
.site-header--nav-open .site-header__toggle-bar:nth-child(1) {
    transform: translateY(8px) rotate(45deg);
}
.site-header--nav-open .site-header__toggle-bar:nth-child(2) {
    opacity: 0;
}
.site-header--nav-open .site-header__toggle-bar:nth-child(3) {
    transform: translateY(-8px) rotate(-45deg);
}
.site-header .nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
}
.site-header .nav-links a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: color 0.2s;
}
.site-header .nav-links a:hover { color: #fde68a; }
@media (max-width: 768px) {
    .site-header__toggle {
        display: flex;
    }
    .site-header .nav-links {
        display: none;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        width: 100%;
        flex-basis: 100%;
        order: 3;
        padding-top: 0.25rem;
        margin: 0 -1.5rem -1rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-bottom: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(0, 0, 0, 0.12);
    }
    .site-header--nav-open .nav-links {
        display: flex;
    }
    .site-header .nav-links a {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 1rem;
    }
    .site-header .nav-links a:last-child {
        border-bottom: none;
    }
}
@media (min-width: 769px) {
    .site-header .header-inner {
        flex-wrap: nowrap;
        gap: 1.5rem;
    }
    .site-header .logo {
        flex: 0 1 auto;
    }
    .site-header__toggle {
        display: none !important;
    }
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
        padding: 0;
        border-bottom: none;
    }
}

.site-footer {
    background: #eceeef;
    border-top: 1px solid var(--chrome-border);
    margin-top: auto;
}
.site-footer .footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 1.5rem 2rem;
}
.site-footer .footer-grid {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 2.5rem;
    margin-bottom: 2rem;
}
@media (max-width: 768px) {
    .site-footer .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
    .site-footer .footer-grid { grid-template-columns: 1fr; }
}
.site-footer .footer-brand .logo { font-size: 1.2rem; }
.site-footer .footer-brand p {
    margin-top: 0.75rem;
    color: var(--chrome-text-muted);
    font-size: 0.9rem;
    max-width: 260px;
}
.site-footer .footer-col h4 {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--chrome-text-muted);
    margin-bottom: 1rem;
}
.site-footer .footer-col ul { list-style: none; }
.site-footer .footer-col li { margin-bottom: 0.5rem; }
.site-footer .footer-col a {
    color: var(--chrome-text);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.2s;
}
.site-footer .footer-col a:hover { color: var(--chrome-accent); }
.site-footer .footer-bottom {
    padding-top: 1.5rem;
    border-top: 1px solid var(--chrome-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.site-footer .footer-bottom p { color: var(--chrome-text-muted); font-size: 0.875rem; }
</style>
