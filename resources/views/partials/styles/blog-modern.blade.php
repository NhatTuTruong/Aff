<style>
/* ── Shared tokens ── */
:root {
    --blog-bg: #f0f2f5;
    --blog-surface: #ffffff;
    --blog-surface-2: #f8f9fb;
    --blog-border: rgba(15, 23, 42, 0.07);
    --blog-text: #0f172a;
    --blog-muted: #64748b;
    --blog-accent: #7c3aed;
    --blog-accent-deep: #6d28d9;
    --blog-accent-soft: rgba(124, 58, 237, 0.08);
    --blog-accent-mid: rgba(124, 58, 237, 0.14);
    --blog-dark: #0f172a;
    --blog-dark-2: #1e293b;
    --blog-radius-xl: 28px;
    --blog-radius-lg: 20px;
    --blog-radius-md: 12px;
    --blog-radius-sm: 8px;
    --blog-shadow: 0 4px 24px -8px rgba(15, 23, 42, 0.12);
    --blog-shadow-lg: 0 16px 48px -20px rgba(15, 23, 42, 0.18);
    --blog-font: 'DM Sans', system-ui, sans-serif;
    --blog-heading: 'DM Sans', system-ui, sans-serif;
}

body:has(.blog-page),
body:has(.blog-shell) {
    background: var(--blog-bg);
    font-family: var(--blog-font);
    color: var(--blog-text);
}

/* ═══════════════════════════════════════
   BLOG INDEX — .blog-page, .bp-*
   ═══════════════════════════════════════ */

.blog-page {
    background: var(--blog-bg);
    color: var(--blog-text);
    font-family: var(--blog-font);
}

/* ── Full-bleed hero ── */
.bp-hero {
    position: relative;
    min-height: 420px;
    padding: clamp(3.5rem, 7vw, 5.5rem) 0 clamp(4rem, 7vw, 6rem);
    overflow: hidden;
    display: flex;
    align-items: center;
    background:
        radial-gradient(ellipse 70% 80% at 0% 50%, rgba(124, 58, 237, 0.18) 0%, transparent 60%),
        radial-gradient(ellipse 50% 60% at 100% 20%, rgba(109, 40, 217, 0.12) 0%, transparent 55%),
        linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f172a 100%);
    border-bottom: 1px solid rgba(124, 58, 237, 0.2);
}

/* Subtle noise texture overlay */
.bp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    opacity: 0.6;
}

.bp-hero::after {
    content: '';
    position: absolute;
    inset: auto 0 0;
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, rgba(124, 58, 237, 0.5) 40%, rgba(109, 40, 217, 0.5) 60%, transparent 100%);
}

.bp-shell {
    width: 100%;
    max-width: 1220px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2rem);
    position: relative;
    z-index: 1;
}

/* Hero left + right layout */
.bp-hero-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    align-items: center;
}

@media (min-width: 900px) {
    .bp-hero-grid {
        grid-template-columns: 1.15fr 1fr;
        gap: 4rem;
    }
}

/* Hero left — text */
.bp-hero-left {
    max-width: 580px;
}

.bp-hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #a78bfa;
    margin-bottom: 1.25rem;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    background: rgba(124, 58, 237, 0.15);
    border: 1px solid rgba(124, 58, 237, 0.3);
}

.bp-hero-kicker::before {
    content: '';
    width: 18px;
    height: 2px;
    background: var(--blog-accent);
    border-radius: 2px;
}

.bp-hero h1 {
    font-family: var(--blog-heading);
    font-size: clamp(2.75rem, 6vw, 4.5rem);
    font-weight: 800;
    letter-spacing: -0.045em;
    line-height: 1.0;
    margin: 0 0 1.25rem;
    color: #ffffff;
}

.bp-hero h1 span {
    background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.bp-hero-lead {
    font-size: clamp(1rem, 1.5vw, 1.125rem);
    color: rgba(226, 232, 240, 0.7);
    line-height: 1.75;
    max-width: 480px;
    margin-bottom: 2rem;
}

/* Hero stats row */
.bp-hero-stats {
    display: flex;
    gap: 2.5rem;
    flex-wrap: wrap;
}

.bp-hero-stat {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.bp-hero-stat-num {
    font-family: var(--blog-heading);
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: #ffffff;
    line-height: 1;
}

.bp-hero-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(167, 139, 250, 0.8);
}

/* Hero right — search + chips */
.bp-hero-right {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.bp-search-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: var(--blog-radius-lg);
    padding: 1.25rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow:
        0 32px 64px -24px rgba(0, 0, 0, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

.bp-search-form {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

@media (min-width: 480px) {
    .bp-search-form {
        flex-direction: row;
        align-items: stretch;
    }
}

.bp-search-form input {
    flex: 1;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: var(--blog-radius-md);
    padding: 0.85rem 1rem;
    font-size: 0.9375rem;
    font-family: var(--blog-font);
    outline: none;
    background: rgba(255, 255, 255, 0.95);
    color: #0f172a;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.bp-search-form input::placeholder {
    color: #94a3b8;
}

.bp-search-form input:focus {
    border-color: var(--blog-accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.18);
}

.bp-search-form button {
    border: none;
    border-radius: var(--blog-radius-md);
    padding: 0.85rem 1.5rem;
    font-weight: 700;
    font-size: 0.875rem;
    font-family: var(--blog-font);
    cursor: pointer;
    color: #fff;
    background: linear-gradient(135deg, var(--blog-accent) 0%, var(--blog-accent-deep) 100%);
    white-space: nowrap;
    box-shadow: 0 8px 24px -8px rgba(124, 58, 237, 0.55);
    transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
}

.bp-search-form button:hover {
    transform: translateY(-1px);
    filter: brightness(1.08);
    box-shadow: 0 12px 32px -8px rgba(124, 58, 237, 0.5);
}

/* Category chips */
.bp-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.bp-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.42rem 0.9rem;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: var(--blog-font);
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.14);
    color: rgba(226, 232, 240, 0.8);
    transition: all 0.2s;
}

.bp-chip:hover {
    border-color: rgba(124, 58, 237, 0.5);
    color: #fff;
    background: rgba(124, 58, 237, 0.14);
    transform: translateY(-1px);
}

.bp-chip--on {
    background: linear-gradient(135deg, var(--blog-accent) 0%, var(--blog-accent-deep) 100%);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 6px 18px -6px rgba(124, 58, 237, 0.55);
}

/* ── Main content area ── */
.bp-main {
    padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(3rem, 6vw, 5rem);
}

/* Section header */
.bp-section-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem 2rem;
    margin-bottom: 2rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--blog-border);
}

.bp-section-eyebrow {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--blog-accent-deep);
    margin-bottom: 0.35rem;
}

.bp-section-title {
    font-family: var(--blog-heading);
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 700;
    letter-spacing: -0.03em;
    margin: 0;
    color: var(--blog-text);
    line-height: 1.2;
}

.bp-section-desc {
    margin: 0;
    font-size: 0.9375rem;
    color: var(--blog-muted);
    max-width: 28rem;
    line-height: 1.6;
}

/* Magazine grid */
.bp-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 700px) {
    .bp-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.75rem;
    }
}

@media (min-width: 1080px) {
    .bp-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Featured card spans full width */
.bp-card--feature {
    grid-column: 1 / -1;
}

@media (min-width: 900px) {
    .bp-card--feature {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 0.7fr);
        gap: 0;
        align-items: stretch;
        border-radius: var(--blog-radius-xl) !important;
        overflow: hidden;
    }

    .bp-card--feature .bp-card-media {
        min-height: 280px;
        max-height: 320px;
        border-radius: 0 !important;
    }

    .bp-card--feature .bp-card-body {
        margin: 0;
        border-radius: 0 !important;
        justify-content: center;
        padding: clamp(1.75rem, 3.5vw, 2.75rem);
        box-shadow: none;
    }
}

@media (max-width: 899px) {
    .bp-card--feature .bp-card-media {
        min-height: 220px;
        max-height: 280px;
    }
}

/* Card base */
.bp-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    border-radius: var(--blog-radius-lg);
    overflow: visible;
    position: relative;
    transition: transform 0.28s ease;
}

.bp-card:hover {
    transform: translateY(-6px);
}

/* Card media */
.bp-card-media {
        position: relative;
        background: var(--blog-dark-2);
        min-height: 180px;
        max-height: 360px;
        border-radius: var(--blog-radius-lg);
        overflow: hidden;
        flex-shrink: 0;
    }

.bp-card-media::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(15, 23, 42, 0) 30%,
        rgba(15, 23, 42, 0.65) 100%
    );
    pointer-events: none;
    z-index: 1;
}

.bp-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    min-height: 180px;
    max-height: 360px;
    transition: transform 0.5s ease;
}

.bp-card:hover .bp-card-media img {
    transform: scale(1.06);
}

/* Card badges */
.bp-card-badge {
    position: absolute;
    top: 0.9rem;
    left: 0.9rem;
    z-index: 3;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #fff;
    background: linear-gradient(135deg, var(--blog-accent) 0%, var(--blog-accent-deep) 100%);
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.45);
}

.bp-card-date {
    position: absolute;
    bottom: 0.85rem;
    right: 0.85rem;
    z-index: 3;
    padding: 0.28rem 0.65rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.92);
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
}

/* Card body — floating card style */
.bp-card-body {
    position: relative;
    z-index: 3;
    margin: -2.5rem 0.85rem 0;
    padding: 1.25rem 1.35rem 1.35rem;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    flex: 1;
    background: var(--blog-surface);
    border-radius: var(--blog-radius-md);
    box-shadow: var(--blog-shadow);
}

.bp-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.bp-card-tag {
    display: inline-flex;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--blog-accent-deep);
    background: var(--blog-accent-soft);
    border: 1px solid rgba(124, 58, 237, 0.18);
    max-width: 65%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.bp-card-date-inline {
    font-size: 0.75rem;
    color: var(--blog-muted);
    white-space: nowrap;
    font-weight: 500;
}

.bp-card-title {
    font-family: var(--blog-heading);
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: -0.025em;
    line-height: 1.3;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: var(--blog-text);
}

.bp-card--feature .bp-card-title {
    font-size: clamp(1.35rem, 2.5vw, 1.8rem);
    -webkit-line-clamp: 3;
}

.bp-card-excerpt {
    font-size: 0.9rem;
    color: var(--blog-muted);
    line-height: 1.65;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bp-card--feature .bp-card-excerpt {
    font-size: 0.9875rem;
    -webkit-line-clamp: 4;
}

.bp-card-cta {
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 700;
    font-size: 0.8rem;
    letter-spacing: 0.02em;
    color: var(--blog-accent-deep);
    padding: 0.45rem 0.85rem;
    border-radius: 999px;
    background: var(--blog-accent-soft);
    border: 1px solid rgba(124, 58, 237, 0.16);
    width: fit-content;
    transition: all 0.2s;
}

.bp-card:hover .bp-card-cta {
    background: var(--blog-accent-mid);
    color: var(--blog-accent);
    border-color: rgba(124, 58, 237, 0.28);
}

/* Pagination */
.bp-pagination {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.bp-pagination .pagination-list {
    gap: 0.4rem !important;
}

.bp-pagination .pagination-item:not(.pagination-ellipsis) {
    border-radius: var(--blog-radius-sm) !important;
    border-color: var(--blog-border) !important;
    background: var(--blog-surface) !important;
    font-weight: 600 !important;
    font-family: var(--blog-font) !important;
    color: var(--blog-text) !important;
}

.bp-pagination .pagination-item:hover:not(.pagination-disabled):not(.pagination-current) {
    border-color: var(--blog-accent) !important;
    color: var(--blog-accent-deep) !important;
}

.bp-pagination .pagination-current {
    border-color: rgba(124, 58, 237, 0.4) !important;
    color: var(--blog-accent-deep) !important;
    background: var(--blog-accent-soft) !important;
}

/* Empty state */
.bp-empty {
    text-align: center;
    padding: 4rem 2rem;
    border-radius: var(--blog-radius-xl);
    border: 1px dashed rgba(15, 23, 42, 0.12);
    background: var(--blog-surface);
    color: var(--blog-muted);
    max-width: 480px;
    margin: 0 auto;
    box-shadow: var(--blog-shadow-sm);
}

.bp-empty strong {
    display: block;
    font-family: var(--blog-heading);
    font-size: 1.35rem;
    color: var(--blog-text);
    margin-bottom: 0.6rem;
}

/* ═══════════════════════════════════════
   BLOG SHOW — .blog-shell, .blog-*
   ═══════════════════════════════════════ */

.blog-shell {
    max-width: 1320px;
    margin: 0 auto;
    padding: 2rem clamp(1rem, 3vw, 2rem) 4rem;
    font-family: var(--blog-font);
}

/* Breadcrumb */
.blog-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--blog-muted);
    margin-bottom: 1.75rem;
    text-decoration: none;
}

.blog-breadcrumb a {
    color: var(--blog-muted);
    text-decoration: none;
    transition: color 0.2s;
}

.blog-breadcrumb a:hover {
    color: var(--blog-accent);
}

.blog-breadcrumb span {
    opacity: 0.4;
}

/* Hero banner — full width dark panel */
.blog-hero {
    position: relative;
    border-radius: var(--blog-radius-xl);
    overflow: hidden;
    border: none;
    background: var(--blog-dark);
    box-shadow: var(--blog-shadow-lg);
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr);
    min-height: 380px;
    margin-bottom: 2.5rem;
}

.blog-hero::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--blog-accent) 0%, var(--blog-accent-deep) 100%);
    z-index: 3;
}

@media (max-width: 900px) {
    .blog-hero {
        grid-template-columns: 1fr;
        min-height: auto;
    }
}

.blog-hero-main {
    padding: clamp(2rem, 4vw, 3.25rem);
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background:
        radial-gradient(ellipse 80% 70% at 0% 50%, rgba(124, 58, 237, 0.14) 0%, transparent 60%),
        linear-gradient(150deg, var(--blog-dark) 0%, var(--blog-dark-2) 100%);
}

.blog-hero-eyebrow {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.blog-hero-eyebrow > span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    background: rgba(124, 58, 237, 0.12);
    border: 1px solid rgba(124, 58, 237, 0.25);
    font-size: 0.72rem;
    font-weight: 600;
    color: #a78bfa;
}

.blog-title {
    font-family: var(--blog-heading);
    font-size: clamp(1.9rem, 3.5vw, 2.85rem);
    font-weight: 800;
    letter-spacing: -0.04em;
    line-height: 1.12;
    color: #ffffff;
    margin-bottom: 1rem;
}

.blog-meta {
    font-size: 0.875rem;
    color: rgba(226, 232, 240, 0.6);
    line-height: 1.65;
    max-width: 42rem;
}

.blog-meta a {
    color: #a78bfa;
    text-decoration: underline;
    text-underline-offset: 2px;
    font-weight: 600;
}

.blog-meta a:hover {
    color: #fff;
}

.blog-hero-media {
    position: relative;
    min-height: 300px;
    background: var(--blog-dark-2);
    overflow: hidden;
}

@media (max-width: 900px) {
    .blog-hero-media {
        min-height: 260px;
        order: -1;
    }
}

.blog-hero-media-inner {
    position: absolute;
    inset: 0;
}

.blog-hero-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: saturate(1.1);
}

.blog-hero-media-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to right, rgba(15, 23, 42, 0.6) 0%, transparent 50%),
        linear-gradient(to top, rgba(15, 23, 42, 0.3), transparent 40%);
    z-index: 1;
}

@media (max-width: 900px) {
    .blog-hero-media-overlay {
        background: linear-gradient(to bottom, transparent 35%, rgba(15, 23, 42, 0.5) 100%);
    }
}

/* Main grid: article + sidebar */
.blog-main-grid {
    display: block;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 960px) {
    .blog-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Article card */
.blog-main {
    min-width: 0;
    background: var(--blog-surface);
    border-radius: var(--blog-radius-lg);
    border: 1px solid var(--blog-border);
    padding: clamp(1.75rem, 3.5vw, 2.75rem) clamp(1.5rem, 3.5vw, 3rem);
    box-shadow: var(--blog-shadow-sm);
    position: relative;
    overflow: hidden;
}

.blog-main::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--blog-accent), var(--blog-accent-deep));
}

@media (max-width: 640px) {
    .blog-main {
        padding: 1.5rem 1.25rem 1.75rem;
    }
}

.blog-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    color: var(--blog-muted);
    text-decoration: none;
    font-size: 0.8125rem;
    font-weight: 500;
    transition: color 0.2s;
}

.blog-back:hover {
    color: var(--blog-accent);
}

.blog-back .icon {
    width: 28px;
    height: 28px;
    border-radius: 999px;
    border: 1px solid rgba(124, 58, 237, 0.22);
    background: var(--blog-accent-soft);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: var(--blog-accent);
    transition: all 0.2s;
}

.blog-back:hover .icon {
    background: var(--blog-accent-mid);
    border-color: rgba(124, 58, 237, 0.4);
}

/* Chip row */
.blog-chip-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--blog-border);
}

.blog-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.32rem 0.75rem;
    border-radius: 999px;
    border: 1px solid var(--blog-border);
    background: var(--blog-surface-2);
    color: var(--blog-muted);
    font-size: 0.78rem;
    font-weight: 500;
}

.blog-chip-accent {
    border-color: rgba(124, 58, 237, 0.3);
    background: var(--blog-accent-soft);
    color: var(--blog-accent-deep);
    font-weight: 600;
}

.blog-share-button {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    border: 1px solid var(--blog-border);
    background: var(--blog-surface);
    cursor: pointer;
    font-size: 0.78rem;
    font-family: var(--blog-font);
    color: var(--blog-muted);
    transition: all 0.2s;
}

.blog-share-button:hover {
    border-color: var(--blog-accent);
    color: var(--blog-accent);
    background: var(--blog-accent-soft);
}

@media (max-width: 640px) {
    .blog-share-button {
        margin-left: 0;
    }
}

/* Prose content */
.blog-content.prose {
    color: #1e293b;
    font-size: 1.0625rem;
    line-height: 1.9;
}

.blog-content.prose h2,
.blog-content.prose h3,
.blog-content.prose h4 {
    font-family: var(--blog-heading);
    font-weight: 700;
    letter-spacing: -0.025em;
    margin: 2.25rem 0 0.85rem;
    line-height: 1.25;
    color: var(--blog-text);
}

.blog-content.prose h2 { font-size: 1.4rem; }
.blog-content.prose h3 { font-size: 1.15rem; }
.blog-content.prose h4 { font-size: 1rem; }

.blog-content.prose p {
    margin: 1rem 0;
}

.blog-content.prose ul,
.blog-content.prose ol {
    margin: 0.85rem 0 1.25rem;
    padding-left: 1.5rem;
}

.blog-content.prose li {
    margin: 0.45rem 0;
}

.blog-content.prose li::marker {
    color: var(--blog-accent);
}

.blog-content.prose a {
    color: var(--blog-accent);
    text-decoration: underline;
    text-underline-offset: 2px;
    font-weight: 500;
}

.blog-content.prose a:hover {
    color: var(--blog-accent-deep);
}

.blog-content.prose img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: var(--blog-radius-md);
    border: 1px solid var(--blog-border);
    margin: 1.5rem 0;
}

.blog-content.prose figcaption,
.blog-content.prose .attachment__caption,
.blog-content.prose .attachment__name,
.blog-content.prose .attachment__size,
.blog-content.prose figure.attachment .attachment__caption {
    display: none !important;
}

.blog-content.prose blockquote {
    margin: 1.75rem 0;
    padding: 1.1rem 1.5rem;
    border-left: 3px solid var(--blog-accent);
    background: var(--blog-accent-soft);
    border-radius: 0 var(--blog-radius-md) var(--blog-radius-md) 0;
    color: var(--blog-muted);
    font-style: italic;
}

.blog-content.prose strong {
    font-weight: 650;
    color: var(--blog-text);
}

.blog-content.prose code {
    font-size: 0.87em;
    padding: 0.15rem 0.4rem;
    border-radius: 5px;
    background: rgba(15, 23, 42, 0.06);
}

.blog-side-media {
    margin-top: 1.75rem;
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
}

.blog-side-media img,
.blog-side-media video {
    width: 100%;
    border-radius: var(--blog-radius-md);
    border: 1px solid var(--blog-border);
}

/* Sidebar — dark card */
.blog-aside {
    min-width: 0;
    border-radius: var(--blog-radius-lg);
    border: 1px solid rgba(124, 58, 237, 0.15);
    background:
        radial-gradient(ellipse 80% 50% at 100% 0%, rgba(124, 58, 237, 0.12) 0%, transparent 60%),
        linear-gradient(160deg, var(--blog-dark) 0%, var(--blog-dark-2) 100%);
    padding: 1.5rem 1.35rem 1.75rem;
    box-shadow: var(--blog-shadow-lg);
    position: sticky;
    top: 1.5rem;
    height: fit-content;
}

@media (max-width: 960px) {
    .blog-aside {
        position: static;
    }
}

.blog-aside-title {
    font-family: var(--blog-heading);
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
    padding-bottom: 0.9rem;
    border-bottom: 1px solid rgba(124, 58, 237, 0.2);
    color: #ffffff;
    letter-spacing: -0.01em;
}

.blog-aside-deals {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.blog-deal-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(124, 58, 237, 0.15);
    border-radius: var(--blog-radius-md);
    padding: 0.9rem 1rem;
    position: relative;
    overflow: hidden;
    transition: all 0.2s;
}

.blog-deal-card:hover {
    border-color: rgba(124, 58, 237, 0.35);
    background: rgba(255, 255, 255, 0.07);
    transform: translateY(-2px);
}

.blog-deal-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), transparent 55%);
    opacity: 0;
    transition: opacity 0.2s;
    pointer-events: none;
}

.blog-deal-card:hover::before {
    opacity: 1;
}

.blog-deal-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-bottom: 0.5rem;
}

.blog-deal-logo {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.12);
    overflow: hidden;
    flex-shrink: 0;
}

.blog-deal-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.blog-deal-brand {
    font-size: 0.875rem;
    font-weight: 600;
    color: #ffffff;
    max-width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.blog-deal-offer {
    font-size: 0.8rem;
    color: rgba(226, 232, 240, 0.6);
    margin-bottom: 0.65rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-deal-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
}

.btn-copy-code {
    flex: 1;
    min-width: 0;
    padding: 0.5rem 1rem;
    border: 2px dashed var(--blog-accent);
    border-radius: 8px;
    background: var(--blog-accent-soft);
    color: var(--blog-accent);
    font-size: 0.82rem;
    font-weight: 700;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.btn-copy-code:hover {
    background: var(--blog-accent-mid);
    border-style: solid;
}

.btn-copy-code.copied {
    background: #10b981;
    border-color: #059669;
    border-style: solid;
    color: #fff;
    animation: pulse-copy 0.3s ease;
}

@keyframes pulse-copy {
    0% { transform: scale(1); }
    50% { transform: scale(1.04); }
    100% { transform: scale(1); }
}

.blog-deal-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--blog-accent), var(--blog-accent-deep));
    color: #ffffff;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: filter 0.2s, transform 0.2s;
    white-space: nowrap;
}

.blog-deal-cta:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
    color: #fff;
}

.blog-aside-empty {
    font-size: 0.85rem;
    color: rgba(226, 232, 240, 0.5);
    line-height: 1.6;
}

/* Related blogs */
.related-blogs {
    margin-top: 3.5rem;
    padding-top: 2.5rem;
    border-top: 1px solid var(--blog-border);
}

.related-blogs-title {
    font-family: var(--blog-heading);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--blog-text);
    display: flex;
    align-items: center;
    gap: 0.65rem;
    letter-spacing: -0.02em;
}

.related-blogs-title::before {
    content: '';
    width: 2.5rem;
    height: 3px;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--blog-accent), var(--blog-accent-deep));
    flex-shrink: 0;
}

.related-blogs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.25rem;
}

.related-blog-card {
    text-decoration: none;
    color: inherit;
    border-radius: var(--blog-radius-md);
    background: var(--blog-surface);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--blog-shadow-sm);
    border: 1px solid var(--blog-border);
    transition: box-shadow 0.25s, transform 0.25s;
}

.related-blog-card:hover {
    box-shadow: var(--blog-shadow-lg);
    transform: translateY(-4px);
}

.related-blog-card-thumb {
    width: 100%;
    height: 155px;
    object-fit: cover;
    display: block;
    background: var(--blog-dark-2);
}

.related-blog-card-body {
    padding: 1rem 1.1rem 1.15rem;
}

.related-blog-card-title {
    font-family: var(--blog-heading);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--blog-text);
    margin-bottom: 0.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.35;
    letter-spacing: -0.01em;
}

.related-blog-card-meta {
    font-size: 0.75rem;
    color: var(--blog-muted);
    font-weight: 500;
}
</style>
