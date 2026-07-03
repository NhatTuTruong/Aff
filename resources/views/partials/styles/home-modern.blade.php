<style>
body:has(.home-page) {
    background: var(--pub-bg);
    font-family: var(--pub-font);
}

.home-page {
    color: var(--pub-ink);
    font-family: var(--pub-font);
}

.home-page .font-heading,
.home-page h1,
.home-page h2,
.home-page h3,
.home-page .hp-head-title {
    font-family: var(--pub-font);
}

.home-page .hp-shell {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 3vw, 2rem);
}

/* ── Hero ── */
.home-page .hp-hero {
    position: relative;
    padding: clamp(3rem, 6vw, 5rem) 0 clamp(2.5rem, 5vw, 3.5rem);
    background:
        linear-gradient(165deg, #e8f2f6 0%, var(--pub-bg) 45%, #ffffff 100%);
    overflow: hidden;
}

.home-page .hp-hero::before {
    content: '';
    position: absolute;
    top: -20%;
    right: -8%;
    width: min(520px, 60vw);
    height: min(520px, 60vw);
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0, 64, 93, 0.08) 0%, transparent 70%);
    pointer-events: none;
}

.home-page .hp-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--pub-accent) 30%, var(--pub-accent-light) 70%, transparent);
    opacity: 0.35;
}

.home-page .hp-hero-layout {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
    align-items: start;
}

@media (min-width: 960px) {
    .home-page .hp-hero-layout {
        grid-template-columns: 1.05fr 0.95fr;
        gap: 3rem;
        align-items: center;
    }
}

.home-page .hp-eyebrow {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--pub-accent);
    margin-bottom: 1rem;
    padding: 0.35rem 0.85rem;
    border-radius: 6px;
    background: var(--pub-accent-soft);
    border-left: 3px solid var(--pub-accent);
}

.home-page .hp-hero h1 {
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.08;
    color: var(--pub-ink);
    margin: 0 0 1rem;
    max-width: 14ch;
}

.home-page .hp-hero h1 em {
    font-style: normal;
    color: var(--pub-accent);
}

.home-page .hp-lead {
    font-size: clamp(1rem, 1.3vw, 1.1rem);
    color: var(--pub-muted);
    line-height: 1.75;
    max-width: 36rem;
    margin: 0 0 1.5rem;
}

.home-page .hp-trust {
    font-size: 0.8rem;
    color: var(--pub-muted);
    margin-top: 1rem;
    max-width: 34rem;
    line-height: 1.6;
}

.home-page .hp-trust a {
    color: var(--pub-accent);
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 2px;
}

.home-page .hp-search {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    max-width: 480px;
    padding: 0.4rem 0.4rem 0.4rem 1rem;
    background: var(--pub-surface);
    border: 2px solid var(--pub-line);
    border-radius: 14px;
    box-shadow: var(--pub-shadow);
    transition: border-color 0.2s, box-shadow 0.2s;
}

.home-page .hp-search:focus-within {
    border-color: var(--pub-accent);
    box-shadow: 0 0 0 4px var(--pub-accent-soft), var(--pub-shadow);
}

.home-page .hp-search-icon {
    width: 1.15rem;
    height: 1.15rem;
    flex-shrink: 0;
    color: var(--pub-muted);
}

.home-page .hp-search input {
    flex: 1;
    min-width: 0;
    border: none;
    background: transparent;
    padding: 0.65rem 0;
    font-size: 1rem;
    color: var(--pub-ink);
    outline: none;
}

.home-page .hp-search input::placeholder {
    color: #94a3b8;
}

.home-page .hp-search button {
    border: none;
    cursor: pointer;
    padding: 0.7rem 1.35rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.875rem;
    color: #fff;
    background: var(--pub-accent);
    transition: background 0.2s, transform 0.2s;
    white-space: nowrap;
}

.home-page .hp-search button:hover {
    background: var(--pub-accent-2);
    transform: translateY(-1px);
}

/* Hero mini cards */
.home-page .hp-hero-cards {
    display: grid;
    gap: 0.85rem;
}

@media (min-width: 520px) and (max-width: 959px) {
    .home-page .hp-hero-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}

.home-page .hp-mini-card {
    background: var(--pub-surface);
    border: 1px solid var(--pub-line);
    border-radius: var(--pub-radius-lg);
    padding: 1.25rem 1.35rem;
    box-shadow: var(--pub-shadow);
    transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
}

.home-page .hp-mini-card:hover {
    transform: translateY(-3px);
    border-color: rgba(0, 64, 93, 0.2);
    box-shadow: var(--pub-shadow-lg);
}

.home-page .hp-mini-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 10px;
    background: var(--pub-accent-soft);
    color: var(--pub-accent);
    margin-bottom: 0.75rem;
}

.home-page .hp-mini-icon svg {
    width: 1.1rem;
    height: 1.1rem;
}

.home-page .hp-mini-card h3 {
    font-family: var(--pub-font);
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--pub-ink);
    margin: 0 0 0.35rem;
}

.home-page .hp-mini-card p {
    font-size: 0.85rem;
    color: var(--pub-muted);
    line-height: 1.55;
    margin: 0;
}

/* ── Stats band ── */
.home-page .hp-band {
    padding: 0 0 2rem;
    margin-top: -0.5rem;
}

.home-page .hp-band-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.5rem 0;
    background: var(--pub-accent);
    color: #fff;
    border-radius: var(--pub-radius-lg);
    padding: 1.25rem 1.5rem;
    box-shadow: var(--pub-shadow-lg);
}

.home-page .hp-band-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 0.35rem 1.5rem;
    min-width: 120px;
}

.home-page .hp-band-item strong {
    font-family: var(--pub-font);
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.1;
}

.home-page .hp-band-item span {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    opacity: 0.75;
    margin-top: 0.25rem;
}

.home-page .hp-band-divider {
    width: 1px;
    height: 2.5rem;
    background: rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}

@media (max-width: 639px) {
    .home-page .hp-band-divider:nth-child(4),
    .home-page .hp-band-divider:nth-child(6) {
        display: none;
    }
    .home-page .hp-band-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .home-page .hp-band-divider {
        display: none;
    }
}

/* ── Sections ── */
.home-page .hp-section {
    padding: clamp(2.5rem, 5vw, 3.5rem) 0;
}

.home-page .hp-section--alt {
    background: var(--pub-surface);
    border-top: 1px solid var(--pub-line);
    border-bottom: 1px solid var(--pub-line);
}

.home-page .hp-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem 2rem;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 2px solid var(--pub-line);
}

.home-page .hp-head--center {
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.home-page .hp-head-tag {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--pub-accent);
    margin-bottom: 0.35rem;
}

.home-page .hp-head-title {
    font-size: clamp(1.5rem, 2.8vw, 2rem);
    font-weight: 700;
    letter-spacing: -0.025em;
    color: var(--pub-ink);
    margin: 0;
    line-height: 1.15;
}

.home-page .hp-head-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.home-page .hp-head-title a:hover {
    color: var(--pub-accent);
}

.home-page .hp-head-desc {
    margin: 0;
    font-size: 0.9375rem;
    color: var(--pub-muted);
    max-width: 28rem;
    line-height: 1.6;
}

.home-page .hp-disclaimer {
    font-size: 0.8rem;
    color: var(--pub-muted);
    margin: -0.5rem 0 1.5rem;
    padding: 0.85rem 1rem;
    border-radius: var(--pub-radius-md);
    border-left: 3px solid var(--pub-accent-light);
    background: var(--pub-accent-soft);
    max-width: 720px;
    line-height: 1.55;
}

.home-page .hp-disclaimer a {
    color: var(--pub-accent);
    font-weight: 600;
}

.home-page #coupons,
.home-page #stores,
.home-page #blog,
.home-page #categories {
    scroll-margin-top: 5rem;
}

/* ── Blog — desktop grid, mobile carousel ── */
.home-page .hp-carousel--blog .hp-carousel-viewport--inset {
    position: relative;
}

.home-page .hp-carousel--blog .hp-carousel-slide {
    flex: 0 0 100%;
}

.home-page .hp-carousel--blog .hp-blog-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: var(--pub-surface);
    border: 1px solid var(--pub-line);
    border-radius: var(--pub-radius-lg);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    box-shadow: var(--pub-shadow);
}

.home-page .hp-carousel--blog .hp-blog-card-media {
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #dce8ed;
}

.home-page .hp-carousel--blog .hp-blog-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-page .hp-carousel--blog .hp-blog-card-body {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    padding: 1.1rem 1.15rem 1.2rem;
    flex: 1;
}

.home-page .hp-carousel--blog .hp-blog-card-body time {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--pub-accent);
}

.home-page .hp-carousel--blog .hp-blog-card-body h3 {
    font-family: var(--pub-font);
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--pub-ink);
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-page .hp-carousel--blog .hp-blog-card-link {
    margin-top: auto;
    padding-top: 0.35rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--pub-accent);
}

.home-page .hp-carousel-arrow--inset {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
    width: 2.25rem;
    height: 2.25rem;
    box-shadow: 0 4px 16px rgba(0, 26, 38, 0.18);
}

.home-page .hp-carousel-arrow--inset.hp-carousel-arrow--prev {
    left: 0.65rem;
}

.home-page .hp-carousel-arrow--inset.hp-carousel-arrow--next {
    right: 0.65rem;
}

.home-page .hp-carousel-arrow--inset:hover:not(:disabled) {
    transform: translateY(-50%) scale(1.05);
}

@media (min-width: 769px) {
    .home-page .hp-carousel--blog .hp-carousel-viewport {
        overflow: visible;
    }

    .home-page .hp-carousel--blog .hp-carousel-track {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
        transform: none !important;
    }

    .home-page .hp-carousel--blog .hp-carousel-slide {
        flex: unset;
    }

    .home-page .hp-carousel--blog .hp-carousel-arrow--inset {
        display: none;
    }

    .home-page .hp-carousel--blog .hp-blog-card {
        transition: transform 0.22s ease, border-color 0.2s, box-shadow 0.22s;
    }

    .home-page .hp-carousel--blog .hp-blog-card:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 64, 93, 0.22);
        box-shadow: var(--pub-shadow-lg);
    }
}

@media (max-width: 768px) {
    .home-page .hp-carousel--blog .hp-carousel-arrow--inset {
        display: inline-flex;
    }
}

.home-page .hp-more-link {
    margin-top: 1.5rem;
    text-align: center;
}

.home-page .hp-more-link a {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--pub-accent);
    text-decoration: none;
    padding: 0.65rem 1.25rem;
    border: 2px solid var(--pub-accent);
    border-radius: 999px;
    transition: background 0.2s, color 0.2s;
}

.home-page .hp-more-link a:hover {
    background: var(--pub-accent);
    color: #fff;
}

/* ── Carousels ── */
.home-page .hp-carousel {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.65rem;
}

.home-page .hp-carousel-viewport {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    touch-action: pan-y;
}

.home-page .hp-carousel-track {
    display: flex;
    gap: 1rem;
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    will-change: transform;
}

.home-page .hp-carousel-slide {
    flex-shrink: 0;
    min-width: 0;
}

.home-page .hp-carousel-arrow {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border: 1px solid var(--pub-line);
    border-radius: 50%;
    background: var(--pub-surface);
    color: var(--pub-accent);
    cursor: pointer;
    box-shadow: var(--pub-shadow);
    transition: background 0.2s, border-color 0.2s, transform 0.2s, opacity 0.2s;
    -webkit-tap-highlight-color: transparent;
}

.home-page .hp-carousel-arrow svg {
    width: 1.1rem;
    height: 1.1rem;
}

.home-page .hp-carousel-arrow:hover:not(:disabled) {
    background: var(--pub-accent);
    border-color: var(--pub-accent);
    color: #fff;
    transform: scale(1.05);
}

.home-page .hp-carousel-arrow:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

/* Coupons — desktop 3-col grid, mobile 2-col grid */
.home-page .hp-coupons {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

.home-page .coupon-card.is-coupon-hidden {
    display: none;
}

.home-page .hp-load-more-wrap {
    display: none;
    justify-content: center;
    margin-top: 1.25rem;
}

.home-page .hp-load-more-wrap--mobile {
    display: none;
}

.home-page .hp-load-more {
    border: 2px solid var(--pub-accent);
    cursor: pointer;
    padding: 0.7rem 1.75rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--pub-accent);
    background: transparent;
    transition: all 0.2s;
}

.home-page .hp-load-more:hover {
    background: var(--pub-accent);
    color: #fff;
}

.home-page .hp-load-more[hidden] {
    display: none;
}

@media (max-width: 768px) {
    .home-page .hp-coupons {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.65rem;
    }

    .home-page .hp-load-more-wrap--mobile {
        display: flex;
    }

    .home-page .hp-load-more {
        width: 100%;
        max-width: 300px;
        background: var(--pub-accent);
        color: #fff;
        border-color: var(--pub-accent);
    }

    .home-page .coupon-card {
        min-width: 0;
    }

    .home-page .coupon-card-accent {
        width: 4px;
    }

    .home-page .coupon-card-main {
        padding: 0.65rem 0.55rem 0.7rem;
    }

    .home-page .coupon-card-header {
        gap: 0.4rem;
        margin-bottom: 0.35rem;
        align-items: flex-start;
    }

    .home-page .coupon-card-header > div {
        min-width: 0;
        flex: 1;
    }

    .home-page .coupon-card-logo {
        width: 34px;
        height: 34px;
        padding: 3px;
        border-radius: 50%;
    }

    .home-page .coupon-card-brand {
        font-size: 0.7rem;
        white-space: normal;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.25;
        word-break: break-word;
    }

    .home-page .coupon-card-type {
        font-size: 0.56rem;
        letter-spacing: 0.05em;
        margin-top: 0.12rem;
    }

    .home-page .coupon-card-offer {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--pub-ink);
        margin-bottom: 0.45rem;
        line-height: 1.3;
    }

    .home-page .coupon-card-actions {
        flex-direction: column;
        gap: 0.38rem;
    }

    .home-page .coupon-card-code {
        width: 100%;
        padding: 0.38rem 0.45rem;
        font-size: 0.62rem;
        gap: 0.25rem;
        box-sizing: border-box;
    }

    .home-page .coupon-card-code-value {
        font-size: 0.6rem;
        flex: 1;
        min-width: 0;
    }

    .home-page .coupon-card-code-copy {
        font-size: 0.56rem;
        margin-left: 0;
    }

    .home-page .coupon-card-cta {
        width: 100%;
        padding: 0.42rem 0.5rem;
        font-size: 0.6rem;
        border-radius: 999px;
        box-sizing: border-box;
    }

    .home-page .coupon-card--no-code .coupon-card-cta {
        width: 100%;
    }
}

.home-page .hp-deals-more {
    margin-top: 1.5rem;
    text-align: center;
}

.home-page .hp-deals-more a {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--pub-accent);
    text-decoration: none;
    padding: 0.65rem 1.35rem;
    border: 2px solid var(--pub-accent);
    border-radius: 999px;
    transition: background 0.2s, color 0.2s;
}

.home-page .hp-deals-more a:hover {
    background: var(--pub-accent);
    color: #fff;
}

/* ── Coupons cards ── */
.home-page .coupon-card {
    position: relative;
    display: flex;
    background: var(--pub-surface);
    border: 1px solid var(--pub-line);
    border-radius: var(--pub-radius-lg);
    overflow: hidden;
    box-shadow: var(--pub-shadow);
    transition: transform 0.2s, box-shadow 0.2s;
}

.home-page .coupon-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--pub-shadow-lg);
}

.home-page .coupon-card-accent {
    width: 5px;
    flex-shrink: 0;
    background: linear-gradient(180deg, var(--pub-accent-light), var(--pub-accent));
}

.home-page .coupon-card-main {
    flex: 1;
    padding: 1rem 1.15rem;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.home-page .coupon-card-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-bottom: 0.5rem;
}

.home-page .coupon-card-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 10px;
    background: var(--pub-bg);
    padding: 4px;
    border: 1px solid var(--pub-line);
    flex-shrink: 0;
}

.home-page .coupon-card-brand {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--pub-ink);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.home-page .coupon-card-type {
    display: block;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--pub-accent);
    margin-top: 0.1rem;
}

.home-page .coupon-card-offer {
    font-size: 0.85rem;
    color: var(--pub-muted);
    margin: 0 0 0.75rem;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-page .coupon-card-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: auto;
}

.home-page .coupon-card-code {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    min-width: 0;
    padding: 0.5rem 0.75rem;
    background: var(--pub-accent-soft);
    border: 1.5px dashed var(--pub-accent);
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--pub-accent);
    cursor: pointer;
    font-family: ui-monospace, monospace;
    transition: background 0.2s;
}

.home-page .coupon-card-code:hover {
    background: var(--pub-accent-mid);
}

.home-page .coupon-card-code.copied {
    background: rgba(16, 185, 129, 0.12);
    border-color: #059669;
    color: #059669;
}

.home-page .coupon-card-code-value {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.home-page .coupon-card-code-copy {
    font-size: 0.65rem;
    opacity: 0.75;
    margin-left: auto;
    flex-shrink: 0;
}

.home-page .coupon-card-code.copied .coupon-card-code-copy {
    display: none;
}

.home-page .coupon-card-code.copied::after {
    content: '✓ Copied';
    font-size: 0.65rem;
    flex-shrink: 0;
}

.home-page .coupon-card-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    background: var(--pub-accent);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
    flex-shrink: 0;
}

.home-page .coupon-card-cta:hover {
    background: var(--pub-accent-2);
    color: #fff;
}

.home-page .coupon-card--no-code .coupon-card-cta {
    flex: 1;
}

/* ── Stores carousel / mobile marquee ── */
.home-page .hp-carousel--stores .hp-stores-viewport {
    cursor: grab;
}

.home-page .hp-carousel--stores .hp-stores-viewport:active {
    cursor: grabbing;
}

.home-page .hp-carousel--stores .hp-stores-track {
    will-change: transform;
}

.home-page .hp-carousel--stores .hp-store-slide {
    flex: 0 0 calc((100% - 6rem) / 7);
}

@media (min-width: 960px) and (max-width: 1199px) {
    .home-page .hp-carousel--stores .hp-store-slide {
        flex: 0 0 calc((100% - 5rem) / 6);
    }
}

@media (min-width: 769px) and (max-width: 959px) {
    .home-page .hp-carousel--stores .hp-store-slide {
        flex: 0 0 calc((100% - 4rem) / 5);
    }
}

@media (max-width: 768px) {
    .home-page .hp-carousel--stores {
        gap: 0;
    }

    .home-page .hp-carousel--stores .hp-stores-arrow {
        display: none !important;
    }

    .home-page .hp-carousel--stores .hp-stores-viewport {
        overflow: hidden;
        touch-action: pan-y;
    }

    .home-page .hp-carousel--stores .hp-stores-track {
        display: flex;
        width: max-content;
        gap: 0.85rem;
        flex-wrap: nowrap;
    }

    .home-page .hp-carousel--stores .hp-store-slide {
        flex: 0 0 auto;
        width: 72px;
    }

    .home-page .hp-carousel--stores .hp-store-tile-name {
        display: none;
    }

    .home-page .hp-carousel--stores .hp-store-tile {
        padding: 0;
        border: none;
        background: transparent;
        box-shadow: none;
        pointer-events: auto;
        touch-action: manipulation;
    }

    .home-page .hp-carousel--stores .hp-store-tile:hover {
        transform: none;
        box-shadow: none;
    }

    .home-page .hp-carousel--stores .hp-store-tile-img {
        width: 72px;
        height: 72px;
        margin-bottom: 0;
        border-radius: 16px;
        box-shadow: var(--pub-shadow);
    }
}

.home-page .hp-store-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: inherit;
    padding: 1rem 0.5rem;
    background: var(--pub-surface);
    border: 1px solid var(--pub-line);
    border-radius: var(--pub-radius-lg);
    transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
    height: 100%;
}

.home-page .hp-store-tile:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 64, 93, 0.25);
    box-shadow: var(--pub-shadow);
}

.home-page .hp-store-tile-img {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    overflow: hidden;
    background: var(--pub-bg);
    border: 1px solid var(--pub-line);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.55rem;
}

.home-page .hp-store-tile-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 6px;
}

.home-page .hp-store-tile-name {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--pub-ink);
    text-align: center;
    line-height: 1.25;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ── Categories ── */
.home-page .hp-cats {
    padding: clamp(2.5rem, 5vw, 3.5rem) 0;
    background: linear-gradient(180deg, var(--pub-bg) 0%, #e8f2f6 100%);
}

.home-page .hp-cat-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    justify-content: center;
}

.home-page .hp-cat-chip {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--pub-accent);
    background: var(--pub-surface);
    border: 1.5px solid rgba(0, 64, 93, 0.2);
    transition: all 0.2s;
}

.home-page .hp-cat-chip:hover {
    background: var(--pub-accent);
    color: #fff;
    border-color: var(--pub-accent);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px -6px rgba(0, 64, 93, 0.35);
}

/* ── Empty ── */
.home-page .hp-empty {
    text-align: center;
    padding: 3rem 1.25rem;
    border-radius: var(--pub-radius-xl);
    border: 1px dashed var(--pub-line);
    background: var(--pub-surface);
    color: var(--pub-muted);
}

.home-page .hp-empty svg {
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    opacity: 0.35;
    color: var(--pub-accent);
}

.home-page .hp-empty h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--pub-ink);
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .home-page .hp-search {
        flex-wrap: wrap;
        border-radius: 12px;
        padding: 0.75rem;
    }

    .home-page .hp-search-icon {
        display: none;
    }

    .home-page .hp-search button {
        width: 100%;
    }
}
</style>
