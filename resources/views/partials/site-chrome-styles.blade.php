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
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--chrome-border);
    position: sticky;
    top: 0;
    z-index: 100;
}
.site-header .header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.site-header .logo {
    font-family: 'Space Grotesk', 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: 1.35rem;
    color: var(--chrome-text);
    text-decoration: none;
    letter-spacing: -0.02em;
}
.site-header .logo span { color: var(--chrome-accent); }
.site-header .nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
}
.site-header .nav-links a {
    color: var(--chrome-text-muted);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: color 0.2s;
}
.site-header .nav-links a:hover { color: var(--chrome-accent); }

.site-footer {
    background: var(--chrome-surface);
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
