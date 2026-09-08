<style>
/* Magazine site — synced with homepage (Poppins + green #198754) */
body.magazine-site {
    font-family: 'Poppins', system-ui, sans-serif;
    font-size: 17px;
    background: #eceae4;
    color: #0f0f14;
    --blog-bg: #eceae4;
    --blog-surface: #ffffff;
    --blog-surface-2: #f5f3ee;
    --blog-border: rgba(15, 15, 20, 0.1);
    --blog-text: #0f0f14;
    --blog-muted: #5a5a6e;
    --blog-accent: #198754;
    --blog-accent-deep: #157347;
    --blog-accent-soft: rgba(25, 135, 84, 0.1);
    --blog-accent-mid: rgba(25, 135, 84, 0.18);
    --blog-dark: #0c0c14;
    --blog-dark-2: #16161f;
    --blog-heading: 'Poppins', system-ui, sans-serif;
}
body.magazine-site .font-heading {
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site:has(.blog-page),
body.magazine-site:has(.blog-shell),
body.magazine-site:has(.blog-archive) {
    background: #eceae4;
    color: #0f0f14;
}

body.magazine-site .blog-page,
body.magazine-site .blog-shell {
    background: transparent;
    color: #0f0f14;
}

body.magazine-site .bp-shell,
body.magazine-site .blog-shell .blog-layout {
    max-width: 1320px;
}

body.magazine-site .bp-hero {
    background: var(--blog-dark);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    min-height: auto;
    padding: 2.5rem 0 2rem;
}

body.magazine-site .bp-hero h1,
body.magazine-site .bp-hero h1 span {
    color: #fff;
    font-family: 'Poppins', system-ui, sans-serif;
    font-size: clamp(1.85rem, 4vw, 2.75rem);
}

body.magazine-site .bp-hero-lead {
    color: rgba(255, 255, 255, 0.65);
    font-size: 1.05rem;
}

body.magazine-site .bp-hero-kicker {
    color: #fff;
    background: rgba(25, 135, 84, 0.2);
    border-color: rgba(25, 135, 84, 0.45);
}

body.magazine-site .bp-search-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.1);
}

body.magazine-site .bp-search-form input {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.12);
    color: #0f0f14;
    font-size: 1rem;
}

body.magazine-site .bp-search-form button {
    background: #198754;
}

body.magazine-site .bp-chip {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.1);
    color: #4b5563;
    font-size: 1rem;
}

body.magazine-site .bp-chip--on,
body.magazine-site .bp-chip:hover {
    background: rgba(25, 135, 84, 0.12);
    border-color: #198754;
    color: #157347;
}

body.magazine-site .bp-main {
    background: #ffffff;
    padding: 2rem 0 3rem;
}

body.magazine-site .bp-section-title {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
    font-size: 1.65rem;
}

body.magazine-site .bp-section-desc,
body.magazine-site .bp-section-eyebrow {
    color: #5a5a6e;
}

body.magazine-site .bp-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
}

body.magazine-site .bp-card-title {
    color: #0f0f14;
    font-size: 1.12rem;
}

body.magazine-site .bp-card-excerpt,
body.magazine-site .bp-card-date-inline {
    color: #5a5a6e;
}

body.magazine-site .bp-card-tag {
    background: rgba(25, 135, 84, 0.12);
    color: #198754;
}

body.magazine-site .bp-card-cta {
    color: #198754;
}

body.magazine-site .bp-empty {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.08);
    color: #5a5a6e;
}

body.magazine-site .blog-hero,
body.magazine-site .blog-article,
body.magazine-site .blog-sidebar {
    color: #374151;
}

body.magazine-site .blog-hero-title,
body.magazine-site .blog-article h1,
body.magazine-site .blog-article h2,
body.magazine-site .blog-article h3 {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .blog-hero,
body.magazine-site .blog-layout-main,
body.magazine-site .blog-sidebar-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .blog-breadcrumb,
body.magazine-site .blog-breadcrumb a {
    color: #5a5a6e;
}

body.magazine-site .blog-article-body,
body.magazine-site .blog-article-body p,
body.magazine-site .blog-article-body li {
    color: #374151;
    font-size: 1.05rem;
    line-height: 1.75;
}

body.magazine-site .blog-related-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .blog-related-card h3 {
    color: #0f0f14;
}

body.magazine-site .blog-hero-main {
    background: var(--blog-dark);
}

body.magazine-site .blog-hero-main .blog-title,
body.magazine-site .blog-hero-main .blog-meta,
body.magazine-site .blog-hero-main .blog-meta a {
    color: #fff;
}

body.magazine-site .blog-hero-main .blog-meta a:hover {
    color: #20c997;
}

body.magazine-site .blog-hero-eyebrow > span {
    background: rgba(25, 135, 84, 0.15);
    border-color: rgba(25, 135, 84, 0.35);
    color: #20c997;
}

body.magazine-site .blog-hero-eyebrow > span:first-child {
    background: rgba(25, 135, 84, 0.22);
    border-color: rgba(25, 135, 84, 0.45);
    color: #fff;
    font-weight: 700;
}

body.magazine-site .blog-title {
    font-family: 'Poppins', system-ui, sans-serif;
    color: #0f0f14;
}

body.magazine-site .blog-meta {
    color: #5a5a6e;
}

body.magazine-site .blog-meta a {
    color: #198754;
}

body.magazine-site .blog-meta a:hover {
    color: #157347;
}

body.magazine-site .blog-hero-media-overlay {
    background: linear-gradient(to right, rgba(15, 23, 42, 0.06) 0%, transparent 45%);
}

body.magazine-site .blog-deal-brand {
    color: #0f0f14;
}

body.magazine-site .blog-deal-offer {
    color: #5a5a6e;
}

body.magazine-site .blog-main {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .blog-back:hover {
    color: #198754;
}

body.magazine-site .blog-back .icon {
    border-color: rgba(25, 135, 84, 0.22);
    background: rgba(25, 135, 84, 0.08);
    color: #198754;
}

body.magazine-site .blog-breadcrumb a:hover {
    color: #198754;
}

body.magazine-site .blog-shell > .blog-breadcrumb {
    display: none;
}

body.magazine-site .blog-hero {
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .blog-hero::before {
    background: linear-gradient(180deg, #198754 0%, #157347 100%);
}

body.magazine-site .blog-chip-row {
    border-bottom-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .blog-chip {
    background: rgba(25, 135, 84, 0.06);
    border-color: rgba(25, 135, 84, 0.14);
    color: #475569;
}

body.magazine-site .blog-chip-accent {
    border-color: rgba(25, 135, 84, 0.28);
    background: rgba(25, 135, 84, 0.12);
    color: #157347;
    font-weight: 700;
}

body.magazine-site .blog-share-button {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.1);
    color: #5a5a6e;
}

body.magazine-site .blog-share-button:hover {
    border-color: #198754;
    color: #198754;
    background: rgba(25, 135, 84, 0.08);
}

body.magazine-site .blog-content.prose {
    color: #374151;
}

body.magazine-site .blog-content.prose h2,
body.magazine-site .blog-content.prose h3,
body.magazine-site .blog-content.prose h4,
body.magazine-site .blog-content.prose strong {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .blog-content.prose li::marker {
    color: #198754;
}

body.magazine-site .blog-content.prose a {
    color: #198754;
}

body.magazine-site .blog-content.prose a:hover {
    color: #157347;
}

body.magazine-site .blog-content.prose img {
    border-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .blog-content.prose blockquote {
    border-left-color: #198754;
    background: rgba(25, 135, 84, 0.06);
    color: #5a5a6e;
}

body.magazine-site .blog-content.prose code {
    background: #f3f4f6;
    color: #0f0f14;
}

body.magazine-site .blog-aside {
    background: linear-gradient(160deg, #f5f3ee 0%, #ffffff 100%);
    border-color: rgba(15, 23, 42, 0.1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
    top: var(--magazine-sticky-offset, 7.25rem);
}

body.magazine-site .blog-aside-title {
    border-bottom-color: rgba(25, 135, 84, 0.2);
    font-family: 'Poppins', system-ui, sans-serif;
    color: #0f0f14;
}

body.magazine-site .blog-deal-card {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.1);
}

body.magazine-site .blog-deal-card:hover {
    border-color: rgba(25, 135, 84, 0.35);
}

body.magazine-site .blog-deal-card::before {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.08), transparent 55%);
}

body.magazine-site .blog-deal-cta {
    background: #198754;
}

body.magazine-site .blog-deal-cta:hover {
    background: #157347;
}

body.magazine-site .btn-copy-code {
    background: rgba(25, 135, 84, 0.1);
    border-color: rgba(25, 135, 84, 0.28);
    color: #198754;
}

body.magazine-site .btn-copy-code:hover {
    background: rgba(25, 135, 84, 0.16);
    border-color: #198754;
}

body.magazine-site .related-blogs {
    border-top-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .related-blogs-title {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .related-blogs-title::before {
    background: linear-gradient(90deg, #198754, #157347);
}

body.magazine-site .related-blog-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .related-blog-card:hover {
    border-color: rgba(25, 135, 84, 0.25);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

body.magazine-site .related-blog-card-title {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .related-blog-card-meta {
    color: #5a5a6e;
}

/* Legal pages */
body.magazine-site .legal-container {
    max-width: 1320px;
    padding: 2.5rem 1.5rem 3.5rem;
    color: #374151;
    background: #ffffff;
}

body.magazine-site .legal-container h1 {
    font-family: 'Poppins', system-ui, sans-serif;
    color: #0f0f14;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
}

body.magazine-site .legal-container h2 {
    color: #0f0f14;
    font-size: 1.25rem;
}

body.magazine-site .legal-container p,
body.magazine-site .legal-container li {
    color: #374151;
    font-size: 1.02rem;
    line-height: 1.7;
}

body.magazine-site .legal-container a {
    color: #198754;
}

/* Error pages */
body.magazine-site .error-page {
    background: #ffffff;
}

body.magazine-site .error-page .error-code {
    color: #198754;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .error-page .error-title {
    color: #0f0f14;
    font-size: 1.65rem;
}

body.magazine-site .error-page .error-message {
    color: #5a5a6e;
    font-size: 1.02rem;
}

body.magazine-site .error-page .error-actions a {
    background: #198754;
}

body.magazine-site .error-page .error-actions a:hover {
    background: #157347;
}

/* Deals page */
body.magazine-site .deals-hero {
    background: linear-gradient(180deg, #eceae4 0%, #ffffff 100%);
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
}

body.magazine-site .deals-hero h1 {
    color: #0f0f14;
    font-family: 'Poppins', system-ui, sans-serif;
}

body.magazine-site .deals-hero p {
    color: #5a5a6e;
}

body.magazine-site .container {
    max-width: 1320px;
}

body.magazine-site .deal-card,
body.magazine-site .coupon-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    color: #374151;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

/* Pagination */
body.magazine-site .pagination-item {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.1);
    color: #374151;
    font-size: 0.95rem;
}

body.magazine-site .pagination-item:hover:not(.pagination-disabled):not(.pagination-current) {
    border-color: #198754;
    color: #198754;
}

body.magazine-site .pagination-current {
    background: rgba(25, 135, 84, 0.1);
    border-color: #198754;
    color: #157347;
}

body.magazine-site .pagination-info {
    color: #5a5a6e;
}

body.magazine-site .back-to-top {
    background: linear-gradient(145deg, #198754 0%, #157347 100%);
    box-shadow: 0 8px 24px -6px rgba(25, 135, 84, 0.4);
}

body.magazine-site .cookie-consent {
    background: #ffffff;
    color: #374151;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 -4px 12px rgba(15, 23, 42, 0.06);
}

body.magazine-site .cookie-consent a {
    color: #198754;
}

body.magazine-site .cookie-consent a:hover {
    color: #157347;
}
</style>
