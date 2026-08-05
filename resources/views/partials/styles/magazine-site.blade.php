<style>
/* Magazine site — light content theme (header/footer stay dark) */
body.magazine-site {
    font-size: 17px;
    background: #ffffff;
    color: #111827;
    --blog-bg: #ffffff;
    --blog-surface: #ffffff;
    --blog-surface-2: #f3f4f6;
    --blog-border: rgba(15, 23, 42, 0.08);
    --blog-text: #111827;
    --blog-muted: #6b7280;
    --blog-accent: #2563eb;
    --blog-accent-deep: #1d4ed8;
    --blog-accent-soft: rgba(37, 99, 235, 0.1);
    --blog-accent-mid: rgba(37, 99, 235, 0.18);
    --blog-dark: #f3f4f6;
    --blog-dark-2: #e5e7eb;
    --blog-heading: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .font-heading {
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site:has(.blog-page),
body.magazine-site:has(.blog-shell),
body.magazine-site:has(.blog-archive) {
    background: #eff6ff;
    color: #111827;
}

body.magazine-site .blog-page,
body.magazine-site .blog-shell {
    background: transparent;
    color: #111827;
}

body.magazine-site .bp-shell,
body.magazine-site .blog-shell .blog-layout {
    max-width: 1320px;
}

body.magazine-site .bp-hero {
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    min-height: auto;
    padding: 2.5rem 0 2rem;
}

body.magazine-site .bp-hero h1,
body.magazine-site .bp-hero h1 span {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.85rem, 4vw, 2.75rem);
}

body.magazine-site .bp-hero-lead {
    color: #6b7280;
    font-size: 1.05rem;
}

body.magazine-site .bp-hero-kicker {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.1);
    border-color: rgba(37, 99, 235, 0.25);
}

body.magazine-site .bp-search-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.1);
}

body.magazine-site .bp-search-form input {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.12);
    color: #111827;
    font-size: 1rem;
}

body.magazine-site .bp-search-form button {
    background: #2563eb;
}

body.magazine-site .bp-chip {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.1);
    color: #4b5563;
    font-size: 1rem;
}

body.magazine-site .bp-chip--on,
body.magazine-site .bp-chip:hover {
    background: rgba(37, 99, 235, 0.12);
    border-color: #2563eb;
    color: #1d4ed8;
}

body.magazine-site .bp-main {
    background: #ffffff;
    padding: 2rem 0 3rem;
}

body.magazine-site .bp-section-title {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.65rem;
}

body.magazine-site .bp-section-desc,
body.magazine-site .bp-section-eyebrow {
    color: #6b7280;
}

body.magazine-site .bp-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
}

body.magazine-site .bp-card-title {
    color: #111827;
    font-size: 1.12rem;
}

body.magazine-site .bp-card-excerpt,
body.magazine-site .bp-card-date-inline {
    color: #6b7280;
}

body.magazine-site .bp-card-tag {
    background: rgba(37, 99, 235, 0.12);
    color: #2563eb;
}

body.magazine-site .bp-card-cta {
    color: #2563eb;
}

body.magazine-site .bp-empty {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.08);
    color: #6b7280;
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
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
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
    color: #6b7280;
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
    color: #111827;
}

body.magazine-site .blog-hero-main {
    background: linear-gradient(150deg, #eff6ff 0%, #ffffff 100%);
}

body.magazine-site .blog-hero-eyebrow > span {
    background: rgba(37, 99, 235, 0.1);
    border-color: rgba(37, 99, 235, 0.22);
    color: #2563eb;
}

body.magazine-site .blog-hero-eyebrow > span:first-child {
    background: rgba(37, 99, 235, 0.12);
    border-color: rgba(37, 99, 235, 0.28);
    color: #1d4ed8;
    font-weight: 700;
}

body.magazine-site .blog-title {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #111827;
}

body.magazine-site .blog-meta {
    color: #6b7280;
}

body.magazine-site .blog-meta a {
    color: #2563eb;
}

body.magazine-site .blog-meta a:hover {
    color: #1d4ed8;
}

body.magazine-site .blog-hero-media-overlay {
    background: linear-gradient(to right, rgba(15, 23, 42, 0.06) 0%, transparent 45%);
}

body.magazine-site .blog-deal-brand {
    color: #111827;
}

body.magazine-site .blog-deal-offer {
    color: #6b7280;
}

body.magazine-site .blog-main {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .blog-back:hover {
    color: #2563eb;
}

body.magazine-site .blog-back .icon {
    border-color: rgba(37, 99, 235, 0.22);
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
}

body.magazine-site .blog-breadcrumb a:hover {
    color: #2563eb;
}

body.magazine-site .blog-shell > .blog-breadcrumb {
    display: none;
}

body.magazine-site .blog-hero {
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .blog-hero::before {
    background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%);
}

body.magazine-site .blog-chip-row {
    border-bottom-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .blog-chip {
    background: rgba(37, 99, 235, 0.06);
    border-color: rgba(37, 99, 235, 0.14);
    color: #475569;
}

body.magazine-site .blog-chip-accent {
    border-color: rgba(37, 99, 235, 0.28);
    background: rgba(37, 99, 235, 0.12);
    color: #1d4ed8;
    font-weight: 700;
}

body.magazine-site .blog-share-button {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.1);
    color: #6b7280;
}

body.magazine-site .blog-share-button:hover {
    border-color: #2563eb;
    color: #2563eb;
    background: rgba(37, 99, 235, 0.08);
}

body.magazine-site .blog-content.prose {
    color: #374151;
}

body.magazine-site .blog-content.prose h2,
body.magazine-site .blog-content.prose h3,
body.magazine-site .blog-content.prose h4,
body.magazine-site .blog-content.prose strong {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .blog-content.prose li::marker {
    color: #2563eb;
}

body.magazine-site .blog-content.prose a {
    color: #2563eb;
}

body.magazine-site .blog-content.prose a:hover {
    color: #1d4ed8;
}

body.magazine-site .blog-content.prose img {
    border-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .blog-content.prose blockquote {
    border-left-color: #2563eb;
    background: rgba(37, 99, 235, 0.06);
    color: #6b7280;
}

body.magazine-site .blog-content.prose code {
    background: #f3f4f6;
    color: #111827;
}

body.magazine-site .blog-aside {
    background: linear-gradient(160deg, #eff6ff 0%, #ffffff 100%);
    border-color: rgba(15, 23, 42, 0.1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
    top: var(--magazine-sticky-offset, 7.25rem);
}

body.magazine-site .blog-aside-title {
    border-bottom-color: rgba(37, 99, 235, 0.2);
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #111827;
}

body.magazine-site .blog-deal-card {
    background: #f9fafb;
    border-color: rgba(15, 23, 42, 0.1);
}

body.magazine-site .blog-deal-card:hover {
    border-color: rgba(37, 99, 235, 0.35);
}

body.magazine-site .blog-deal-card::before {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), transparent 55%);
}

body.magazine-site .blog-deal-cta {
    background: #2563eb;
}

body.magazine-site .blog-deal-cta:hover {
    background: #1d4ed8;
}

body.magazine-site .btn-copy-code {
    background: rgba(37, 99, 235, 0.1);
    border-color: rgba(37, 99, 235, 0.28);
    color: #2563eb;
}

body.magazine-site .btn-copy-code:hover {
    background: rgba(37, 99, 235, 0.16);
    border-color: #2563eb;
}

body.magazine-site .related-blogs {
    border-top-color: rgba(15, 23, 42, 0.08);
}

body.magazine-site .related-blogs-title {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .related-blogs-title::before {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
}

body.magazine-site .related-blog-card {
    background: #ffffff;
    border-color: rgba(15, 23, 42, 0.08);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

body.magazine-site .related-blog-card:hover {
    border-color: rgba(37, 99, 235, 0.25);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

body.magazine-site .related-blog-card-title {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .related-blog-card-meta {
    color: #6b7280;
}

/* Legal pages */
body.magazine-site .legal-container {
    max-width: 1320px;
    padding: 2.5rem 1.5rem 3.5rem;
    color: #374151;
    background: #ffffff;
}

body.magazine-site .legal-container h1 {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #111827;
    font-size: clamp(1.85rem, 4vw, 2.5rem);
}

body.magazine-site .legal-container h2 {
    color: #111827;
    font-size: 1.25rem;
}

body.magazine-site .legal-container p,
body.magazine-site .legal-container li {
    color: #374151;
    font-size: 1.02rem;
    line-height: 1.7;
}

body.magazine-site .legal-container a {
    color: #2563eb;
}

/* Error pages */
body.magazine-site .error-page {
    background: #ffffff;
}

body.magazine-site .error-page .error-code {
    color: #2563eb;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .error-page .error-title {
    color: #111827;
    font-size: 1.65rem;
}

body.magazine-site .error-page .error-message {
    color: #6b7280;
    font-size: 1.02rem;
}

body.magazine-site .error-page .error-actions a {
    background: #2563eb;
}

body.magazine-site .error-page .error-actions a:hover {
    background: #1d4ed8;
}

/* Deals page */
body.magazine-site .deals-hero {
    background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
}

body.magazine-site .deals-hero h1 {
    color: #111827;
    font-family: 'DM Sans', system-ui, sans-serif;
}

body.magazine-site .deals-hero p {
    color: #6b7280;
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
    border-color: #2563eb;
    color: #2563eb;
}

body.magazine-site .pagination-current {
    background: rgba(37, 99, 235, 0.1);
    border-color: #2563eb;
    color: #1d4ed8;
}

body.magazine-site .pagination-info {
    color: #6b7280;
}

body.magazine-site .back-to-top {
    background: linear-gradient(145deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 8px 24px -6px rgba(37, 99, 235, 0.4);
}

body.magazine-site .cookie-consent {
    background: #ffffff;
    color: #374151;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 -4px 12px rgba(15, 23, 42, 0.06);
}

body.magazine-site .cookie-consent a {
    color: #2563eb;
}

body.magazine-site .cookie-consent a:hover {
    color: #1d4ed8;
}
</style>
