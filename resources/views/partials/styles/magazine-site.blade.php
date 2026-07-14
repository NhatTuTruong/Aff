<style>
/* Magazine site — dark theme sync (all pages except coupon/landing) */
body.magazine-site {
    font-size: 17px;
}

body.magazine-site .font-heading {
    font-family: 'DM Sans', system-ui, sans-serif;
}

/* Blog tokens override */
body.magazine-site:has(.blog-page),
body.magazine-site:has(.blog-shell) {
    background: #0a0a0a;
    color: #e8e8e8;
}

body.magazine-site {
    --blog-bg: #0a0a0a;
    --blog-surface: #141414;
    --blog-surface-2: #1a1a1a;
    --blog-border: rgba(255, 255, 255, 0.08);
    --blog-text: #f5f5f5;
    --blog-muted: #9ca3af;
    --blog-accent: #e91e8c;
    --blog-accent-deep: #c2185b;
    --blog-accent-soft: rgba(233, 30, 140, 0.12);
    --blog-accent-mid: rgba(233, 30, 140, 0.2);
    --blog-dark: #111;
    --blog-dark-2: #1a1a1a;
    --blog-heading: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .blog-page,
body.magazine-site .blog-shell {
    background: #0a0a0a;
    color: #f5f5f5;
}

body.magazine-site .bp-shell,
body.magazine-site .blog-shell .blog-layout {
    max-width: 1320px;
}

body.magazine-site .bp-hero {
    background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    min-height: auto;
    padding: 2.5rem 0 2rem;
}

body.magazine-site .bp-hero h1,
body.magazine-site .bp-hero h1 span {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.85rem, 4vw, 2.75rem);
}

body.magazine-site .bp-hero-lead {
    color: #9ca3af;
    font-size: 1.05rem;
}

body.magazine-site .bp-hero-kicker {
    color: #f472b6;
    background: rgba(233, 30, 140, 0.15);
    border-color: rgba(233, 30, 140, 0.35);
}

body.magazine-site .bp-search-card {
    background: #141414;
    border-color: rgba(255,255,255,0.1);
}

body.magazine-site .bp-search-form input {
    background: #111;
    border-color: rgba(255,255,255,0.12);
    color: #fff;
    font-size: 1rem;
}

body.magazine-site .bp-search-form button {
    background: #e91e8c;
}

body.magazine-site .bp-chip {
    background: #141414;
    border-color: rgba(255,255,255,0.1);
    color: #d1d5db;
    font-size: 1rem;
}

body.magazine-site .bp-chip--on,
body.magazine-site .bp-chip:hover {
    background: rgba(233, 30, 140, 0.2);
    border-color: #e91e8c;
    color: #fff;
}

body.magazine-site .bp-main {
    background: #0a0a0a;
    padding: 2rem 0 3rem;
}

body.magazine-site .bp-section-title {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.65rem;
}

body.magazine-site .bp-section-desc,
body.magazine-site .bp-section-eyebrow {
    color: #9ca3af;
}

body.magazine-site .bp-card {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
}

body.magazine-site .bp-card-title {
    color: #fff;
    font-size: 1.12rem;
}

body.magazine-site .bp-card-excerpt,
body.magazine-site .bp-card-date-inline {
    color: #9ca3af;
}

body.magazine-site .bp-card-tag {
    background: rgba(233, 30, 140, 0.2);
    color: #f9a8d4;
}

body.magazine-site .bp-card-cta {
    color: #e91e8c;
}

body.magazine-site .bp-empty {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
    color: #9ca3af;
}

body.magazine-site .blog-hero,
body.magazine-site .blog-article,
body.magazine-site .blog-sidebar {
    color: #e8e8e8;
}

body.magazine-site .blog-hero-title,
body.magazine-site .blog-article h1,
body.magazine-site .blog-article h2,
body.magazine-site .blog-article h3 {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .blog-hero,
body.magazine-site .blog-layout-main,
body.magazine-site .blog-sidebar-card {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
}

body.magazine-site .blog-breadcrumb,
body.magazine-site .blog-breadcrumb a {
    color: #9ca3af;
}

body.magazine-site .blog-article-body,
body.magazine-site .blog-article-body p,
body.magazine-site .blog-article-body li {
    color: #d1d5db;
    font-size: 1.05rem;
    line-height: 1.75;
}

body.magazine-site .blog-related-card {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
}

body.magazine-site .blog-related-card h3 {
    color: #fff;
}

/* Blog archive */
body.magazine-site:has(.blog-archive) {
    background: #0a0a0a;
}

/* Blog article — magazine accent sync */
body.magazine-site .blog-hero-main {
    background: linear-gradient(150deg, #111 0%, #1a1a1a 100%);
}

body.magazine-site .blog-hero-eyebrow > span {
    background: rgba(233, 30, 140, 0.12);
    border-color: rgba(233, 30, 140, 0.25);
    color: #f9a8d4;
}

body.magazine-site .blog-title {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #fff;
}

body.magazine-site .blog-meta a {
    color: #f472b6;
}

body.magazine-site .blog-meta a:hover {
    color: #fff;
}

body.magazine-site .blog-main {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
    box-shadow: none;
}

body.magazine-site .blog-back:hover {
    color: #f472b6;
}

body.magazine-site .blog-back .icon {
    border-color: rgba(233, 30, 140, 0.25);
    background: rgba(233, 30, 140, 0.1);
    color: #f472b6;
}

body.magazine-site .blog-breadcrumb a:hover {
    color: #f472b6;
}

/* Blog show — full magazine sync */
body.magazine-site .blog-hero {
    background: #141414;
    box-shadow: none;
}

body.magazine-site .blog-hero::before {
    background: linear-gradient(180deg, #e91e8c 0%, #c2185b 100%);
}

body.magazine-site .blog-chip-row {
    border-bottom-color: rgba(255,255,255,0.08);
}

body.magazine-site .blog-chip {
    background: #1a1a1a;
    border-color: rgba(255,255,255,0.1);
    color: #9ca3af;
}

body.magazine-site .blog-chip-accent {
    border-color: rgba(233, 30, 140, 0.3);
    background: rgba(233, 30, 140, 0.12);
    color: #f9a8d4;
}

body.magazine-site .blog-share-button {
    background: #1a1a1a;
    border-color: rgba(255,255,255,0.1);
    color: #9ca3af;
}

body.magazine-site .blog-share-button:hover {
    border-color: #e91e8c;
    color: #f472b6;
    background: rgba(233, 30, 140, 0.1);
}

body.magazine-site .blog-content.prose {
    color: #d1d5db;
}

body.magazine-site .blog-content.prose h2,
body.magazine-site .blog-content.prose h3,
body.magazine-site .blog-content.prose h4,
body.magazine-site .blog-content.prose strong {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .blog-content.prose li::marker {
    color: #e91e8c;
}

body.magazine-site .blog-content.prose a {
    color: #f472b6;
}

body.magazine-site .blog-content.prose a:hover {
    color: #fff;
}

body.magazine-site .blog-content.prose img {
    border-color: rgba(255,255,255,0.08);
}

body.magazine-site .blog-content.prose blockquote {
    border-left-color: #e91e8c;
    background: rgba(233, 30, 140, 0.08);
    color: #9ca3af;
}

body.magazine-site .blog-content.prose code {
    background: rgba(255,255,255,0.06);
    color: #e8e8e8;
}

body.magazine-site .blog-aside {
    background: linear-gradient(160deg, #111 0%, #1a1a1a 100%);
    border-color: rgba(255,255,255,0.1);
    box-shadow: none;
    top: var(--magazine-sticky-offset, 9rem);
}

body.magazine-site .blog-aside-title {
    border-bottom-color: rgba(233, 30, 140, 0.2);
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .blog-deal-card {
    background: rgba(255,255,255,0.04);
    border-color: rgba(255,255,255,0.1);
}

body.magazine-site .blog-deal-card:hover {
    border-color: rgba(233, 30, 140, 0.35);
}

body.magazine-site .blog-deal-card::before {
    background: linear-gradient(135deg, rgba(233, 30, 140, 0.1), transparent 55%);
}

body.magazine-site .blog-deal-cta {
    background: #e91e8c;
}

body.magazine-site .blog-deal-cta:hover {
    background: #c2185b;
}

body.magazine-site .btn-copy-code {
    background: rgba(233, 30, 140, 0.15);
    border-color: rgba(233, 30, 140, 0.3);
    color: #f9a8d4;
}

body.magazine-site .btn-copy-code:hover {
    background: rgba(233, 30, 140, 0.25);
    border-color: #e91e8c;
}

body.magazine-site .related-blogs {
    border-top-color: rgba(255,255,255,0.08);
}

body.magazine-site .related-blogs-title {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .related-blogs-title::before {
    background: linear-gradient(90deg, #e91e8c, #c2185b);
}

body.magazine-site .related-blog-card {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
    box-shadow: none;
}

body.magazine-site .related-blog-card:hover {
    border-color: rgba(255,255,255,0.14);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}

body.magazine-site .related-blog-card-title {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .related-blog-card-meta {
    color: #9ca3af;
}

/* Legal pages */
body.magazine-site .legal-container {
    max-width: 1320px;
    padding: 2.5rem 1.5rem 3.5rem;
    color: #d1d5db;
}

body.magazine-site .legal-container h1 {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #fff;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
}

body.magazine-site .legal-container h2 {
    color: #fff;
    font-size: 1.25rem;
}

body.magazine-site .legal-container p,
body.magazine-site .legal-container li {
    color: #d1d5db;
    font-size: 1.02rem;
    line-height: 1.7;
}

body.magazine-site .legal-container a {
    color: #f472b6;
}

/* Error pages */
body.magazine-site .error-page .error-code {
    color: #e91e8c;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .error-page .error-title {
    color: #fff;
    font-size: 1.65rem;
}

body.magazine-site .error-page .error-message {
    color: #9ca3af;
    font-size: 1.02rem;
}

body.magazine-site .error-page .error-actions a {
    background: #e91e8c;
}

body.magazine-site .error-page .error-actions a:hover {
    background: #c2185b;
}

/* Deals page */
body.magazine-site .deals-hero {
    background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

body.magazine-site .deals-hero h1 {
    color: #fff;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .deals-hero p {
    color: #9ca3af;
}

body.magazine-site .container {
    max-width: 1320px;
}

body.magazine-site .deal-card,
body.magazine-site .coupon-card {
    background: #141414;
    border-color: rgba(255,255,255,0.08);
    color: #e8e8e8;
}

/* Pagination */
body.magazine-site .pagination-item {
    background: #141414;
    border-color: rgba(255,255,255,0.1);
    color: #e8e8e8;
    font-size: 0.95rem;
}

body.magazine-site .pagination-item:hover:not(.pagination-disabled):not(.pagination-current) {
    border-color: #e91e8c;
    color: #f472b6;
}

body.magazine-site .pagination-current {
    background: rgba(233, 30, 140, 0.2);
    border-color: #e91e8c;
    color: #fff;
}

body.magazine-site .pagination-info {
    color: #9ca3af;
}

/* Cookie bar on dark site */
body.magazine-site .cookie-consent {
    background: #111;
    border-top: 1px solid rgba(255,255,255,0.1);
}

body.magazine-site .cookie-consent-btn {
    background: #e91e8c;
}

body.magazine-site .back-to-top {
    background: linear-gradient(145deg, #e91e8c 0%, #c2185b 100%);
    box-shadow: 0 8px 24px -6px rgba(233, 30, 140, 0.5);
}
</style>
