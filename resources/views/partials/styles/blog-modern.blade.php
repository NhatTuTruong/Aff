<style>
:root {
    --blog-bg: var(--pub-bg, #f2f6f8);
    --blog-surface: var(--pub-surface, #ffffff);
    --blog-surface-2: #e8f2f6;
    --blog-border: var(--pub-line, rgba(12, 25, 36, 0.09));
    --blog-text: var(--pub-ink, #0c1924);
    --blog-muted: var(--pub-muted, #5a7184);
    --blog-accent: var(--pub-accent, #00405d);
    --blog-accent-deep: var(--pub-accent-2, #003347);
    --blog-accent-light: var(--pub-accent-light, #4da8c4);
    --blog-accent-soft: var(--pub-accent-soft, rgba(0, 64, 93, 0.07));
    --blog-accent-mid: var(--pub-accent-mid, rgba(0, 64, 93, 0.14));
    --blog-dark: var(--pub-dark, #001a26);
    --blog-dark-2: var(--pub-dark-2, #002534);
    --blog-radius-xl: 24px;
    --blog-radius-lg: 16px;
    --blog-radius-md: 10px;
    --blog-radius-sm: 8px;
    --blog-shadow: var(--pub-shadow, 0 4px 24px -8px rgba(0, 26, 38, 0.1));
    --blog-shadow-lg: var(--pub-shadow-lg, 0 20px 50px -24px rgba(0, 26, 38, 0.16));
    --blog-shadow-sm: 0 2px 12px -4px rgba(0, 26, 38, 0.08);
    --blog-font: var(--pub-font, 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif);
    --blog-heading: var(--pub-font, 'Plus Jakarta Sans', 'DM Sans', system-ui, sans-serif);
}

body:has(.blog-page),
body:has(.blog-shell) {
    background: var(--blog-bg);
    font-family: var(--blog-font);
    color: var(--blog-text);
}

/* ═══════════════════════════════════════
   BLOG INDEX
   ═══════════════════════════════════════ */

.blog-page {
    background: var(--blog-bg);
    color: var(--blog-text);
    font-family: var(--blog-font);
}

.bp-hero {
    position: relative;
    padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(2rem, 4vw, 3rem);
    background: var(--blog-surface);
    border-bottom: 1px solid var(--blog-border);
}


.bp-shell {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2rem);
}

.bp-kicker {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--blog-accent);
    margin-bottom: 0.85rem;
    padding: 0.3rem 0.75rem;
    background: var(--blog-accent-soft);
    border-radius: 6px;
    border-left: 3px solid var(--blog-accent);
}

.bp-hero h1 {
    font-family: var(--blog-heading);
    font-size: clamp(2rem, 4.5vw, 3rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.08;
    margin: 0 0 0.85rem;
    color: var(--blog-text);
    max-width: 18ch;
}

.bp-lead {
    font-size: clamp(1rem, 1.3vw, 1.075rem);
    color: var(--blog-muted);
    line-height: 1.7;
    max-width: 520px;
    margin: 0 0 1.75rem;
}

.bp-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.bp-search-wrap {
    flex: 1;
    min-width: min(100%, 380px);
}

.bp-search-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.35rem 0.35rem 1rem;
    background: var(--blog-bg);
    border: 2px solid var(--blog-border);
    border-radius: 12px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.bp-search-form:focus-within {
    border-color: var(--blog-accent);
    box-shadow: 0 0 0 4px var(--blog-accent-soft);
}

.bp-search-form svg {
    width: 1.1rem;
    height: 1.1rem;
    flex-shrink: 0;
    color: var(--blog-muted);
}

.bp-search-form input {
    flex: 1;
    min-width: 0;
    border: none;
    background: transparent;
    padding: 0.6rem 0;
    font-size: 0.9375rem;
    font-family: var(--blog-font);
    color: var(--blog-text);
    outline: none;
}

.bp-search-form input::placeholder {
    color: #94a3b8;
}

.bp-search-form button {
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.15rem;
    font-weight: 700;
    font-size: 0.8125rem;
    font-family: var(--blog-font);
    cursor: pointer;
    color: #fff;
    background: var(--blog-accent);
    white-space: nowrap;
    transition: background 0.2s;
}

.bp-search-form button:hover {
    background: var(--blog-accent-deep);
}

.bp-meta-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.bp-meta-pill {
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--blog-muted);
    padding: 0.4rem 0.75rem;
    background: var(--blog-bg);
    border: 1px solid var(--blog-border);
    border-radius: 999px;
}

.bp-meta-pill strong {
    color: var(--blog-accent);
    font-weight: 700;
}

.bp-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    padding-top: 0.25rem;
}

.bp-tab {
    display: inline-flex;
    padding: 0.45rem 0.95rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.8125rem;
    font-weight: 600;
    font-family: var(--blog-font);
    background: var(--blog-bg);
    border: 1px solid var(--blog-border);
    color: var(--blog-muted);
    transition: all 0.2s;
}

.bp-tab:hover {
    border-color: rgba(0, 64, 93, 0.25);
    color: var(--blog-accent);
}

.bp-tab--on {
    background: var(--blog-accent);
    border-color: var(--blog-accent);
    color: #fff;
}

.bp-main {
    padding: clamp(2rem, 4vw, 3rem) 0 clamp(3rem, 5vw, 4rem);
}

/* Feed — horizontal article cards */
.bp-feed {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.bp-article {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 1.25rem;
    align-items: start;
    padding: 1.15rem 1.25rem;
    background: var(--blog-surface);
    border: 1px solid var(--blog-border);
    border-radius: var(--blog-radius-lg);
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
}

.bp-article:hover {
    transform: translateX(4px);
    border-color: rgba(0, 64, 93, 0.22);
    box-shadow: var(--blog-shadow);
}

.bp-article--featured {
    grid-template-columns: minmax(200px, 320px) 1fr;
    padding: 0;
    overflow: hidden;
    border-width: 2px;
    border-color: rgba(0, 64, 93, 0.15);
}

.bp-article--featured .bp-article-media {
    min-height: 220px;
    height: 100%;
    border-radius: 0;
}

.bp-article--featured .bp-article-body {
    padding: 1.75rem 1.75rem 1.75rem 0;
    justify-content: center;
}

.bp-article--featured .bp-article-title {
    font-size: clamp(1.25rem, 2.5vw, 1.65rem);
    -webkit-line-clamp: 3;
}

.bp-article-media {
    position: relative;
    width: 100%;
    height: 100px;
    border-radius: var(--blog-radius-md);
    overflow: hidden;
    background: var(--blog-surface-2);
    flex-shrink: 0;
}

.bp-article-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.bp-article:hover .bp-article-media img {
    transform: scale(1.05);
}

.bp-article-badge {
    position: absolute;
    top: 0.65rem;
    left: 0.65rem;
    z-index: 2;
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #fff;
    background: var(--blog-accent);
}

.bp-article-body {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    min-width: 0;
}

.bp-article-meta {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-wrap: wrap;
}

.bp-article-cat {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--blog-accent);
    padding: 0.15rem 0.5rem;
    background: var(--blog-accent-soft);
    border-radius: 4px;
}

.bp-article-meta time {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--blog-muted);
}

.bp-article-title {
    font-family: var(--blog-font);
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.35;
    margin: 0;
    color: var(--blog-text);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bp-article-excerpt {
    font-size: 0.875rem;
    color: var(--blog-muted);
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bp-article--featured .bp-article-excerpt {
    -webkit-line-clamp: 3;
}

.bp-article-cta {
    margin-top: 0.35rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--blog-accent);
}

@media (max-width: 640px) {
    .bp-article,
    .bp-article--featured {
        grid-template-columns: 1fr;
        padding: 0;
        overflow: hidden;
    }

    .bp-article--featured .bp-article-body,
    .bp-article-body {
        padding: 1rem 1.15rem 1.15rem;
    }

    .bp-article-media {
        height: auto;
        aspect-ratio: 16/9;
        border-radius: 0;
    }

    .bp-article--featured .bp-article-media {
        min-height: auto;
    }
}

.bp-pagination {
    margin-top: 2.5rem;
    display: flex;
    justify-content: center;
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
    color: var(--blog-accent) !important;
}

.bp-pagination .pagination-current {
    border-color: var(--blog-accent) !important;
    color: var(--blog-accent) !important;
    background: var(--blog-accent-soft) !important;
}

.bp-empty {
    text-align: center;
    padding: 3.5rem 2rem;
    border-radius: var(--blog-radius-xl);
    border: 1px dashed var(--blog-border);
    background: var(--blog-surface);
    color: var(--blog-muted);
    max-width: 480px;
    margin: 0 auto;
}

.bp-empty strong {
    display: block;
    font-family: var(--blog-heading);
    font-size: 1.25rem;
    color: var(--blog-text);
    margin-bottom: 0.5rem;
}

/* ═══════════════════════════════════════
   BLOG SHOW
   ═══════════════════════════════════════ */

.blog-shell {
    max-width: 1280px;
    margin: 0 auto;
    padding: 2rem clamp(1rem, 3vw, 2rem) 4rem;
    font-family: var(--blog-font);
}

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

.blog-hero {
    position: relative;
    border-radius: var(--blog-radius-xl);
    overflow: hidden;
    border: 1px solid var(--blog-border);
    background: var(--blog-dark);
    box-shadow: var(--blog-shadow-lg);
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr);
    min-height: 360px;
    margin-bottom: 2.5rem;
}

.blog-hero::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--blog-accent-light), var(--blog-accent));
    z-index: 3;
}

@media (max-width: 900px) {
    .blog-hero {
        grid-template-columns: 1fr;
        min-height: auto;
    }
}

.blog-hero-main {
    padding: clamp(2rem, 4vw, 3rem);
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(150deg, var(--blog-dark) 0%, var(--blog-dark-2) 100%);
}

.blog-hero-eyebrow {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.15rem;
}

.blog-hero-eyebrow > span {
    display: inline-flex;
    padding: 0.28rem 0.7rem;
    border-radius: 6px;
    background: var(--blog-accent-soft);
    border: 1px solid rgba(0, 64, 93, 0.3);
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--blog-accent-light);
}

.blog-title {
    font-family: var(--blog-heading);
    font-size: clamp(1.85rem, 3.5vw, 2.75rem);
    font-weight: 800;
    letter-spacing: -0.035em;
    line-height: 1.12;
    color: #ffffff;
    margin-bottom: 1rem;
}

.blog-meta {
    font-size: 0.875rem;
    color: rgba(212, 232, 239, 0.65);
    line-height: 1.65;
    max-width: 42rem;
}

.blog-meta a {
    color: var(--blog-accent-light);
    text-decoration: underline;
    text-underline-offset: 2px;
    font-weight: 600;
}

.blog-meta a:hover {
    color: #fff;
}

.blog-hero-media {
    position: relative;
    min-height: 280px;
    background: var(--blog-dark-2);
    overflow: hidden;
}

@media (max-width: 900px) {
    .blog-hero-media {
        min-height: 240px;
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
}

.blog-hero-media-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0, 26, 38, 0.55) 0%, transparent 50%);
    z-index: 1;
}

@media (max-width: 900px) {
    .blog-hero-media-overlay {
        background: linear-gradient(to bottom, transparent 35%, rgba(0, 26, 38, 0.45) 100%);
    }
}

.blog-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(280px, 340px);
    gap: 2rem;
    align-items: start;
}

@media (max-width: 960px) {
    .blog-main-grid {
        grid-template-columns: 1fr;
    }
}

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
    background: linear-gradient(90deg, var(--blog-accent), var(--blog-accent-light));
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
    border: 1px solid rgba(0, 64, 93, 0.2);
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
    border-color: rgba(0, 64, 93, 0.35);
}

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
    padding: 0.32rem 0.75rem;
    border-radius: 6px;
    border: 1px solid var(--blog-border);
    background: var(--blog-surface-2);
    color: var(--blog-muted);
    font-size: 0.78rem;
    font-weight: 500;
}

.blog-chip-accent {
    border-color: rgba(0, 64, 93, 0.25);
    background: var(--blog-accent-soft);
    color: var(--blog-accent);
    font-weight: 600;
}

.blog-share-button {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 8px;
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

.blog-content.prose {
    color: #1a3040;
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

.blog-content.prose p { margin: 1rem 0; }

.blog-content.prose ul,
.blog-content.prose ol {
    margin: 0.85rem 0 1.25rem;
    padding-left: 1.5rem;
}

.blog-content.prose li { margin: 0.45rem 0; }

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
    background: rgba(12, 25, 36, 0.06);
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

.blog-aside {
    min-width: 0;
    border-radius: var(--blog-radius-lg);
    border: 1px solid rgba(0, 64, 93, 0.15);
    background: linear-gradient(160deg, var(--blog-dark) 0%, var(--blog-dark-2) 100%);
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
    border-bottom: 1px solid rgba(0, 64, 93, 0.25);
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
    border: 1px solid rgba(0, 64, 93, 0.2);
    border-radius: var(--blog-radius-md);
    padding: 0.9rem 1rem;
    transition: all 0.2s;
}

.blog-deal-card:hover {
    border-color: rgba(77, 168, 196, 0.35);
    background: rgba(255, 255, 255, 0.07);
    transform: translateY(-2px);
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
    color: rgba(212, 232, 239, 0.6);
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
    border: 2px dashed var(--blog-accent-light);
    border-radius: 8px;
    background: rgba(0, 64, 93, 0.2);
    color: var(--blog-accent-light);
    font-size: 0.82rem;
    font-weight: 700;
    font-family: ui-monospace, monospace;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.btn-copy-code:hover {
    background: rgba(0, 64, 93, 0.35);
    border-style: solid;
}

.btn-copy-code.copied {
    background: #10b981;
    border-color: #059669;
    border-style: solid;
    color: #fff;
}

.blog-deal-cta {
    display: inline-flex;
    padding: 0.4rem 0.9rem;
    border-radius: 8px;
    background: var(--blog-accent);
    color: #ffffff;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
    white-space: nowrap;
}

.blog-deal-cta:hover {
    background: var(--blog-accent-deep);
    transform: translateY(-1px);
    color: #fff;
}

.blog-aside-empty {
    font-size: 0.85rem;
    color: rgba(212, 232, 239, 0.5);
    line-height: 1.6;
}

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
}

.related-blogs-title::before {
    content: '';
    width: 2.5rem;
    height: 3px;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--blog-accent), var(--blog-accent-light));
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
    background: var(--blog-surface-2);
}

.related-blog-card-body {
    padding: 1rem 1.1rem 1.15rem;
}

.related-blog-card-title {
    font-family: var(--blog-font);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--blog-text);
    margin-bottom: 0.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.35;
}

.related-blog-card-meta {
    font-size: 0.75rem;
    color: var(--blog-muted);
    font-weight: 500;
}
</style>
