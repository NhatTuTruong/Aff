<style>
/* Homepage — Midnight Editorial (full visual redesign, same HTML structure) */
body:has(.home-magazine) {
    background: #eceae4 !important;
    color: #0f0f14;
}

.home-magazine {
    --hm-bg: #eceae4;
    --hm-surface: #ffffff;
    --hm-surface-2: #f5f3ee;
    --hm-dark: #0c0c14;
    --hm-dark-2: #16161f;
    --hm-dark-3: #1e1e2a;
    --hm-line: rgba(15, 15, 20, 0.1);
    --hm-line-light: rgba(255, 255, 255, 0.1);
    --hm-text: #0f0f14;
    --hm-muted: #5a5a6e;
    --hm-accent: #198754;
    --hm-accent-dark: #157347;
    --hm-accent-soft: rgba(25, 135, 84, 0.35);
    --hm-glow: rgba(25, 135, 84, 0.15);
    --hm-radius: 20px;
    --hm-radius-sm: 12px;
    --hm-shell: min(1280px, calc(100% - 2.5rem));
    --hm-font-body: 'Poppins', system-ui, sans-serif;
    --hm-font-head: 'Poppins', system-ui, sans-serif;
    font-family: var(--hm-font-body);
    background: var(--hm-bg);
    color: var(--hm-text);
    padding-bottom: 0;
}

.home-magazine .hm-shell {
    width: var(--hm-shell);
    margin-inline: auto;
}

/* ── Hero: full-bleed dark cinematic ── */
.home-magazine .hm-hero {
    padding: 0;
    background: var(--hm-dark);
    border-bottom: none;
    position: relative;
    overflow: hidden;
}

.home-magazine .hm-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 55% 45% at 85% 10%, rgba(25, 135, 84, 0.18) 0%, transparent 60%),
        radial-gradient(ellipse 40% 35% at 5% 80%, rgba(25, 135, 84, 0.08) 0%, transparent 55%);
    pointer-events: none;
    z-index: 0;
}

.home-magazine .hm-hero > .hm-shell {
    width: 100%;
    max-width: 100%;
    margin: 0;
    position: relative;
    z-index: 1;
}

.home-magazine .hm-hero-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    align-items: stretch;
}

@media (min-width: 960px) {
    .home-magazine .hm-hero-grid {
        grid-template-columns: 1.55fr 0.85fr;
        min-height: 520px;
    }
}

.home-magazine .hm-hero-carousel {
    position: relative;
    border-radius: 0;
    overflow: hidden;
    min-height: 420px;
    background: var(--hm-dark-2);
    border: none;
    box-shadow: none;
}

@media (min-width: 960px) {
    .home-magazine .hm-hero-carousel {
        min-height: 520px;
    }
}

.home-magazine .hm-hero-carousel-track {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    height: 100%;
    min-height: inherit;
}

.home-magazine .hm-hero-slide {
    flex: 0 0 100%;
    position: relative;
    display: block;
    min-height: inherit;
    text-decoration: none;
    color: inherit;
}

.home-magazine .hm-hero-slide .hm-hero-main-media {
    position: absolute;
    inset: 0;
}

.home-magazine .hm-hero-slide .hm-hero-main-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease, filter 0.5s ease;
    filter: brightness(0.75) saturate(1.1);
}

.home-magazine .hm-hero-slide:hover .hm-hero-main-media img {
    transform: scale(1.04);
    filter: brightness(0.82) saturate(1.15);
}

.home-magazine .hm-hero-main-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(105deg, rgba(12, 12, 20, 0.92) 0%, rgba(12, 12, 20, 0.55) 42%, rgba(12, 12, 20, 0.15) 100%),
        linear-gradient(to top, rgba(12, 12, 20, 0.7) 0%, transparent 50%);
}

.home-magazine .hm-hero-main-body {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    padding: clamp(1.5rem, 4vw, 3rem);
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    max-width: 720px;
}

.home-magazine .hm-hero-main-title {
    font-family: var(--hm-font-head);
    font-size: clamp(1.85rem, 4.5vw, 3.25rem);
    font-weight: 700;
    line-height: 1.08;
    color: #fff;
    margin: 0.65rem 0 0.5rem;
    letter-spacing: -0.02em;
}

.home-magazine .hm-hero-main-meta {
    font-size: 0.82rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.55);
    margin: 0;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.home-magazine .hm-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 4;
    width: 3rem;
    height: 3rem;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 50%;
    background: rgba(12, 12, 20, 0.6);
    backdrop-filter: blur(8px);
    color: #fff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
}

.home-magazine .hm-hero-arrow svg {
    width: 1.25rem;
    height: 1.25rem;
}

.home-magazine .hm-hero-arrow:hover {
    background: var(--hm-accent);
    border-color: var(--hm-accent);
    transform: translateY(-50%) scale(1.06);
}

.home-magazine .hm-hero-arrow--prev { left: 1.25rem; }
.home-magazine .hm-hero-arrow--next { right: 1.25rem; }

.home-magazine .hm-hero-dots {
    position: absolute;
    left: clamp(1.5rem, 4vw, 3rem);
    bottom: clamp(1.25rem, 3vw, 2rem);
    transform: none;
    z-index: 4;
    display: flex;
    gap: 0.5rem;
}

.home-magazine .hm-hero-dot {
    width: 28px;
    height: 3px;
    border-radius: 999px;
    border: none;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    padding: 0;
    transition: background 0.25s, width 0.25s;
}

.home-magazine .hm-hero-dot.is-active {
    background: var(--hm-accent);
    width: 44px;
}

/* Hero aside — vertical editorial stack on dark panel */
.home-magazine .hm-hero-aside {
    display: flex;
    flex-direction: column;
    gap: 0;
    background: var(--hm-dark-2);
    border-left: 1px solid var(--hm-line-light);
    padding: 1.25rem clamp(1rem, 3vw, 1.75rem);
    justify-content: center;
}

.home-magazine .hm-hero-mini {
    display: grid;
    grid-template-columns: 72px 1fr;
    gap: 1rem;
    align-items: center;
    padding: 1rem 0;
    background: transparent;
    border-radius: 0;
    border: none;
    border-bottom: 1px solid var(--hm-line-light);
    box-shadow: none;
    text-decoration: none;
    color: inherit;
    transition: opacity 0.2s;
    flex: 0 0 auto;
}

.home-magazine .hm-hero-mini:last-child {
    border-bottom: none;
}

.home-magazine .hm-hero-mini:hover {
    opacity: 0.85;
}

.home-magazine .hm-hero-mini-media {
    aspect-ratio: 1;
    border-radius: var(--hm-radius-sm);
    overflow: hidden;
    background: var(--hm-dark-3);
}

.home-magazine .hm-hero-mini-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.home-magazine .hm-hero-mini:hover .hm-hero-mini-media img {
    transform: scale(1.08);
}

.home-magazine .hm-hero-mini-title {
    font-size: 0.92rem;
    font-weight: 600;
    line-height: 1.4;
    color: #f0f0f5;
    margin: 0 0 0.3rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-hero-mini-meta {
    font-size: 0.72rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.4);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ── Trending: coral accent strip ── */
.home-magazine .hm-trending {
    background: var(--hm-accent);
    border: none;
    overflow: hidden;
    position: relative;
    z-index: 2;
}

.home-magazine .hm-trending-inner {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 0.85rem 0;
    min-height: 52px;
}

.home-magazine .hm-trending-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
    background: rgba(0, 0, 0, 0.2);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
}

.home-magazine .hm-trending-badge .hm-widget-icon {
    width: 0.9rem;
    height: 0.9rem;
    color: #fff;
}

.home-magazine .hm-trending-track {
    flex: 1;
    overflow: hidden;
    mask-image: linear-gradient(90deg, transparent, #000 3%, #000 97%, transparent);
}

.home-magazine .hm-trending-list {
    display: flex;
    gap: 2.5rem;
    animation: hm-ticker 35s linear infinite;
    white-space: nowrap;
}

.home-magazine .hm-trending-link {
    color: rgba(255, 255, 255, 0.92);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    transition: opacity 0.2s;
}

.home-magazine .hm-trending-link:hover {
    opacity: 0.75;
    color: #fff;
}

@keyframes hm-ticker {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ── Main layout ── */
.home-magazine .hm-layout {
    width: var(--hm-shell);
    margin: 2.5rem auto 0;
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 2.5rem;
    align-items: start;
}

.home-magazine .hm-main {
    min-width: 0;
}

/* ── Category sections: borderless magazine blocks ── */
.home-magazine .hm-cat-section {
    margin-bottom: 3rem;
    padding: 0 0 2.5rem;
    background: transparent;
    border: none;
    border-radius: 0;
    box-shadow: none;
    border-bottom: 1px solid var(--hm-line);
}

.home-magazine .hm-cat-section:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.home-magazine .hm-cat-section:nth-child(even) {
    padding-top: 0.5rem;
}

.home-magazine .hm-cat-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
    padding: 0;
    border-left: none;
    background: transparent;
    border-radius: 0;
}

.home-magazine .hm-cat-title {
    font-family: var(--hm-font-head);
    font-size: clamp(2rem, 4vw, 2rem);
    font-weight: 700;
    color: var(--hm-text);
    margin: 0;
    letter-spacing: -0.03em;
    line-height: 1;
    position: relative;
    padding-bottom: 0.5rem;
}

.home-magazine .hm-cat-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 3.5rem;
    height: 4px;
    background: var(--hm-cat-accent, var(--hm-accent));
    border-radius: 999px;
}

.home-magazine .hm-cat-more {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--hm-muted);
    text-decoration: none;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.45rem 1rem;
    border: 1.5px solid var(--hm-line);
    border-radius: 999px;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}

.home-magazine .hm-cat-more:hover {
    background: var(--hm-dark);
    color: #fff;
    border-color: var(--hm-dark);
}

/* Tags */
.home-magazine .hm-tag {
    display: inline-block;
    background: var(--hm-tag-color, var(--hm-accent));
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0.3rem 0.65rem;
    border-radius: 4px;
}

.home-magazine .hm-tag--on-image {
    position: absolute;
    left: 1rem;
    top: 1rem;
    bottom: auto;
    z-index: 2;
}

.home-magazine .hm-tag--ghost {
    background: transparent;
    color: var(--hm-tag-color, var(--hm-accent));
    border: 1.5px solid var(--hm-tag-color, var(--hm-accent));
    padding: 0.2rem 0.5rem;
}

/* ── Cards: borderless image-first ── */
.home-magazine .hm-grid {
    display: grid;
    gap: 1.5rem;
}

.home-magazine .hm-grid--3 {
    grid-template-columns: repeat(3, 1fr);
}

.home-magazine .hm-grid--2 {
    grid-template-columns: repeat(2, 1fr);
}

.home-magazine .hm-grid--4 {
    grid-template-columns: repeat(4, 1fr);
}

.home-magazine .hm-grid--minis {
    margin-top: 1.25rem;
}

.home-magazine .hm-grid--secondary {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px dashed var(--hm-line);
}

.home-magazine .hm-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    background: transparent;
    border-radius: 0;
    overflow: visible;
    border: none;
    transition: transform 0.25s;
}

.home-magazine .hm-card:hover {
    transform: translateY(-4px);
    border-color: transparent;
    box-shadow: none;
}

.home-magazine .hm-card-media {
    position: relative;
    aspect-ratio: 4/5;
    overflow: hidden;
    background: var(--hm-surface-2);
    border-radius: var(--hm-radius);
    box-shadow: 0 8px 32px rgba(15, 15, 20, 0.1);
}

.home-magazine .hm-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.home-magazine .hm-card:hover .hm-card-media img {
    transform: scale(1.06);
}

.home-magazine .hm-card-body {
    padding: 1rem 0 0;
}

.home-magazine .hm-card-title {
    font-family: var(--hm-font-head);
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
    color: var(--hm-text);
    margin: 0 0 0.35rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-card-title--lg {
    font-size: 1.5rem;
    -webkit-line-clamp: 3;
}

.home-magazine .hm-card-meta {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--hm-muted);
    margin: 0 0 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.home-magazine .hm-card-excerpt {
    font-size: 0.88rem;
    color: var(--hm-muted);
    line-height: 1.55;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Featured split */
.home-magazine .hm-split {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 1.5rem;
    align-items: stretch;
}

.home-magazine .hm-card--featured {
    position: relative;
    min-height: 400px;
    border-radius: var(--hm-radius);
    overflow: hidden;
    box-shadow: 0 16px 48px rgba(15, 15, 20, 0.15);
}

.home-magazine .hm-card--featured .hm-card-media {
    position: absolute;
    inset: 0;
    aspect-ratio: unset;
    border-radius: var(--hm-radius);
}

.home-magazine .hm-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(12, 12, 20, 0.95) 0%, rgba(12, 12, 20, 0.2) 55%, transparent 100%);
    z-index: 1;
}

.home-magazine .hm-card-body--overlay {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    padding: 1.75rem;
}

.home-magazine .hm-card-body--overlay .hm-card-title {
    color: #fff;
    font-size: 1.65rem;
}

.home-magazine .hm-card-body--overlay .hm-card-meta,
.home-magazine .hm-card-body--overlay .hm-card-excerpt {
    color: rgba(255, 255, 255, 0.65);
}

.home-magazine .hm-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(15, 15, 20, 0.06);
}

.home-magazine .hm-list-item {
    display: grid;
    grid-template-columns: 80px 1fr;
    gap: 1rem;
    align-items: center;
    padding: 1rem 1.15rem;
    background: transparent;
    border: none;
    border-bottom: 1px solid var(--hm-line);
    border-radius: 0;
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
    flex: 1;
}

.home-magazine .hm-list-item:last-child {
    border-bottom: none;
}

.home-magazine .hm-list-item:hover {
    background: var(--hm-surface-2);
}

.home-magazine .hm-list-media {
    aspect-ratio: 1;
    border-radius: var(--hm-radius-sm);
    overflow: hidden;
    background: var(--hm-surface-2);
}

.home-magazine .hm-list-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-list-title {
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1.4;
    color: var(--hm-text);
    margin: 0 0 0.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-list-meta {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--hm-muted);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Horizontal card */
.home-magazine .hm-card--horizontal {
    flex-direction: row;
    align-items: stretch;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(15, 15, 20, 0.06);
}

.home-magazine .hm-card--horizontal .hm-card-media {
    width: 38%;
    flex-shrink: 0;
    aspect-ratio: unset;
    min-height: 160px;
    border-radius: 0;
    box-shadow: none;
}

.home-magazine .hm-card--horizontal .hm-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 1.25rem 1.5rem;
}

.home-magazine .hm-card--horizontal:hover {
    box-shadow: 0 8px 32px rgba(15, 15, 20, 0.1);
}

/* Compact cards — bento squares */
.home-magazine .hm-card--compact .hm-card-media--square {
    aspect-ratio: 1;
}

.home-magazine .hm-card--compact .hm-card-media {
    aspect-ratio: 1;
}

.home-magazine .hm-card-title--sm {
    font-size: 1.05rem;
    -webkit-line-clamp: 3;
}

/* Banner */
.home-magazine .hm-banner-card {
    display: block;
    position: relative;
    border-radius: var(--hm-radius);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    min-height: 340px;
    margin-bottom: 1.25rem;
    background: var(--hm-dark);
    box-shadow: 0 20px 60px rgba(15, 15, 20, 0.18);
}

.home-magazine .hm-banner-media {
    position: absolute;
    inset: 0;
}

.home-magazine .hm-banner-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
    transition: transform 0.6s ease, filter 0.4s ease;
}

.home-magazine .hm-banner-card:hover .hm-banner-media img {
    transform: scale(1.04);
    filter: brightness(0.78);
}

.home-magazine .hm-banner-body {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    padding: 2rem;
    background: linear-gradient(to top, rgba(12, 12, 20, 0.95), transparent);
}

.home-magazine .hm-banner-title {
    font-family: var(--hm-font-head);
    font-size: clamp(1.5rem, 3vw, 2.25rem);
    font-weight: 700;
    color: #fff;
    margin: 0.5rem 0 0.35rem;
    line-height: 1.15;
    letter-spacing: -0.02em;
}

/* Mini cards */
.home-magazine .hm-mini-card {
    display: block;
    text-decoration: none;
    color: inherit;
    text-align: center;
}

.home-magazine .hm-mini-card-media {
    aspect-ratio: 1;
    border-radius: 50%;
    overflow: hidden;
    background: var(--hm-surface-2);
    margin-bottom: 0.65rem;
    box-shadow: 0 4px 16px rgba(15, 15, 20, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.home-magazine .hm-mini-card:hover .hm-mini-card-media {
    transform: scale(1.05);
    box-shadow: 0 8px 24px rgba(15, 15, 20, 0.12);
}

.home-magazine .hm-mini-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-mini-card-title {
    font-size: 0.8rem;
    font-weight: 600;
    line-height: 1.35;
    color: var(--hm-text);
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Masonry */
.home-magazine .hm-masonry {
    display: grid;
    grid-template-columns: 1fr 1fr 0.9fr;
    gap: 1.25rem;
    align-items: stretch;
}

.home-magazine .hm-masonry-large .hm-card-media--tall {
    aspect-ratio: 3/4;
}

.home-magazine .hm-masonry-stack {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    padding: 0.75rem;
    box-shadow: 0 4px 20px rgba(15, 15, 20, 0.06);
}

.home-magazine .hm-list-item--dense {
    padding: 0.65rem;
    border-radius: var(--hm-radius-sm);
}

.home-magazine .hm-list-item--dense .hm-list-media {
    width: 64px;
    aspect-ratio: 1;
}

/* Numbered list — editorial ranking */
.home-magazine .hm-numbered-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    counter-reset: hm-rank;
}

.home-magazine .hm-numbered-item {
    display: grid;
    grid-template-columns: 3.5rem 130px 1fr;
    gap: 1.25rem;
    align-items: center;
    padding: 1.25rem 0;
    background: transparent;
    border: none;
    border-bottom: 1px solid var(--hm-line);
    border-radius: 0;
    text-decoration: none;
    color: inherit;
    transition: padding-left 0.25s;
}

.home-magazine .hm-numbered-item:first-child {
    padding-top: 0;
}

.home-magazine .hm-numbered-item:last-child {
    border-bottom: none;
}

.home-magazine .hm-numbered-item:hover {
    padding-left: 0.5rem;
    background: transparent;
    border-color: var(--hm-line);
}

.home-magazine .hm-numbered-index {
    font-family: var(--hm-font-head);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--hm-accent);
    line-height: 1;
    text-align: center;
    opacity: 0.85;
}

.home-magazine .hm-numbered-media {
    aspect-ratio: 4/3;
    border-radius: var(--hm-radius-sm);
    overflow: hidden;
    background: var(--hm-surface-2);
}

.home-magazine .hm-numbered-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.home-magazine .hm-numbered-item:hover .hm-numbered-media img {
    transform: scale(1.05);
}

.home-magazine .hm-numbered-title {
    font-family: var(--hm-font-head);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--hm-text);
    margin: 0.25rem 0;
    line-height: 1.35;
}

.home-magazine .hm-numbered-meta {
    font-size: 0.78rem;
    color: var(--hm-muted);
    margin: 0;
    line-height: 1.5;
}

/* ── Sidebar: dark inverted panels ── */
.home-magazine .hm-sidebar {
    position: sticky;
    top: var(--magazine-sticky-offset, 7.25rem);
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.home-magazine .hm-widget {
    background: var(--hm-dark);
    border: none;
    border-radius: var(--hm-radius);
    padding: 1.35rem 1.25rem;
    box-shadow: 0 12px 40px rgba(12, 12, 20, 0.2);
    color: #e8e8ef;
}

.home-magazine .hm-widget-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--hm-font-head);
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1.15rem;
    padding-bottom: 0.65rem;
    border-bottom: 2px solid var(--hm-accent);
}

.home-magazine .hm-widget-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 1.15rem;
    height: 1.15rem;
    color: var(--hm-accent);
}

.home-magazine .hm-widget-icon svg {
    width: 100%;
    height: 100%;
}

.home-magazine .hm-cat-nav {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.home-magazine .hm-cat-nav-item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.6rem 0;
    color: rgba(255, 255, 255, 0.55);
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 500;
    border-bottom: 1px solid var(--hm-line-light);
    transition: color 0.2s, padding-left 0.2s;
}

.home-magazine .hm-cat-nav-item:last-child {
    border-bottom: none;
}

.home-magazine .hm-cat-nav-item:hover {
    color: #fff;
    padding-left: 0.35rem;
}

.home-magazine .hm-cat-nav-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.15);
}

.home-magazine .hm-trending-list-widget {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    counter-reset: hm-side-trend;
}

.home-magazine .hm-trending-list-widget li {
    counter-increment: hm-side-trend;
}

.home-magazine .hm-trending-widget-item {
    display: grid;
    grid-template-columns: 52px 1fr;
    gap: 0.75rem;
    text-decoration: none;
    color: inherit;
    align-items: start;
}

.home-magazine .hm-trending-widget-thumb {
    width: 52px;
    height: 52px;
    border-radius: var(--hm-radius-sm);
    overflow: hidden;
    background: var(--hm-dark-3);
    flex-shrink: 0;
}

.home-magazine .hm-trending-widget-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-trending-widget-title {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    line-height: 1.35;
    color: #f0f0f5;
    margin-bottom: 0.15rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-trending-widget-meta {
    font-size: 0.68rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.35);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.home-magazine .hm-latest-widget {
    list-style: none;
    margin: 0;
    padding: 0;
}

.home-magazine .hm-latest-widget li {
    border-bottom: 1px solid var(--hm-line-light);
}

.home-magazine .hm-latest-widget li:last-child {
    border-bottom: none;
}

.home-magazine .hm-latest-widget a {
    display: block;
    padding: 0.65rem 0;
    color: rgba(255, 255, 255, 0.55);
    text-decoration: none;
    font-size: 0.92rem;
    line-height: 1.45;
    transition: color 0.2s;
}

.home-magazine .hm-latest-widget a:hover {
    color: var(--hm-accent);
}

/* Empty state */
.home-magazine .hm-empty {
    text-align: center;
    padding: 5rem 2rem;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    border: 2px dashed var(--hm-line);
}

.home-magazine .hm-empty h2 {
    font-family: var(--hm-font-head);
    font-size: 2rem;
    color: var(--hm-text);
    margin-bottom: 0.5rem;
}

.home-magazine .hm-empty p {
    color: var(--hm-muted);
    margin-bottom: 1.5rem;
}

.home-magazine .hm-empty-link {
    display: inline-block;
    padding: 0.65rem 1.5rem;
    background: var(--hm-dark);
    color: #fff;
    text-decoration: none;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    transition: background 0.2s;
}

.home-magazine .hm-empty-link:hover {
    background: var(--hm-accent);
}

/* Category mobile carousel */
.home-magazine .hm-cat-carousel {
    display: none;
    position: relative;
    border-radius: var(--hm-radius);
    overflow: hidden;
    min-height: 320px;
    background: var(--hm-dark);
}

.home-magazine .hm-cat-carousel-track {
    display: flex;
    transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    height: 100%;
    min-height: 320px;
}

.home-magazine .hm-cat-slide {
    flex: 0 0 100%;
    position: relative;
    display: block;
    min-height: 320px;
    text-decoration: none;
    color: inherit;
}

.home-magazine .hm-cat-slide .hm-hero-main-media {
    position: absolute;
    inset: 0;
}

.home-magazine .hm-cat-slide .hm-hero-main-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-cat-slide .hm-hero-main-body {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 1.25rem 1.35rem;
    z-index: 1;
}

.home-magazine .hm-cat-slide .hm-hero-main-title {
    font-size: clamp(1.15rem, 4vw, 1.45rem);
}

/* ── Popular: full-width dark band ── */
.home-magazine .hm-popular {
    margin-top: 3rem;
    padding: 3.5rem 0;
    background: var(--hm-dark);
    border-top: none;
    position: relative;
    overflow: hidden;
}

.home-magazine .hm-popular::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 50% 60% at 100% 0%, rgba(25, 135, 84, 0.12) 0%, transparent 60%);
    pointer-events: none;
}

.home-magazine .hm-popular-head {
    margin-bottom: 2rem;
    position: relative;
}

.home-magazine .hm-popular-title {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-family: var(--hm-font-head);
    font-size: clamp(1.75rem, 3.5vw, 2.5rem);
    font-weight: 700;
    color: #fff;
    margin: 0;
    letter-spacing: -0.02em;
}

.home-magazine .hm-popular-title .hm-widget-icon {
    width: 1.35rem;
    height: 1.35rem;
    color: var(--hm-accent);
}

.home-magazine .hm-popular-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    position: relative;
}

.home-magazine .hm-popular-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    background: var(--hm-dark-2);
    border: 1px solid var(--hm-line-light);
    border-radius: var(--hm-radius);
    overflow: hidden;
    transition: border-color 0.25s, transform 0.25s;
}

.home-magazine .hm-popular-card:hover {
    border-color: var(--hm-accent-soft);
    transform: translateY(-5px);
    box-shadow: 0 20px 48px rgba(0, 0, 0, 0.35);
}

.home-magazine .hm-popular-media {
    aspect-ratio: 16/10;
    overflow: hidden;
    background: var(--hm-dark-3);
}

.home-magazine .hm-popular-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.45s ease, filter 0.35s ease;
    filter: brightness(0.85);
}

.home-magazine .hm-popular-card:hover .hm-popular-media img {
    transform: scale(1.05);
    filter: brightness(1);
}

.home-magazine .hm-popular-body {
    padding: 1.15rem 1.25rem 1.35rem;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    flex: 1;
}

.home-magazine .hm-popular-tag {
    display: inline-block;
    align-self: flex-start;
    background: var(--hm-tag-color, var(--hm-accent));
    color: #fff;
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.25rem 0.55rem;
    border-radius: 4px;
}

.home-magazine .hm-popular-name {
    font-family: var(--hm-font-head);
    font-size: 1.2rem;
    font-weight: 700;
    color: #f0f0f5;
    line-height: 1.35;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-popular-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.4);
    margin: 0.15rem 0 0;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.home-magazine .hm-popular-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.home-magazine .hm-popular-meta-item svg {
    width: 0.85rem;
    height: 0.85rem;
    flex-shrink: 0;
    opacity: 0.7;
}

/* Per-layout accents */
.home-magazine .hm-cat-section--layout-2 .hm-card-media {
    aspect-ratio: 1;
}

.home-magazine .hm-cat-section--layout-0 .hm-card-media {
    aspect-ratio: 4/5;
}

.home-magazine .hm-cat-section--layout-4 .hm-masonry-large .hm-card-media {
    aspect-ratio: 3/4;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .home-magazine .hm-layout {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
    }

    .home-magazine .hm-hero-grid {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-hero-aside {
        flex-direction: row;
        overflow-x: auto;
        border-left: none;
        border-top: 1px solid var(--hm-line-light);
        padding: 1rem;
        gap: 1rem;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .home-magazine .hm-hero-mini {
        flex: 0 0 260px;
        scroll-snap-align: start;
        border-bottom: none;
        border: 1px solid var(--hm-line-light);
        border-radius: var(--hm-radius-sm);
        padding: 0.85rem;
        grid-template-columns: 64px 1fr;
    }

    .home-magazine .hm-grid--3,
    .home-magazine .hm-grid--4 {
        grid-template-columns: repeat(2, 1fr);
    }

    .home-magazine .hm-split,
    .home-magazine .hm-masonry {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-masonry-stack {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .home-magazine .hm-popular-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .home-magazine .hm-cat-section__desktop {
        display: none;
    }

    .home-magazine .hm-cat-carousel {
        display: block;
    }
}

@media (max-width: 640px) {
    .home-magazine {
        --hm-shell: calc(100% - 1.25rem);
    }

    .home-magazine .hm-hero-carousel,
    .home-magazine .hm-hero-carousel-track,
    .home-magazine .hm-hero-slide {
        min-height: 360px;
    }

    .home-magazine .hm-hero-aside {
        flex-direction: column;
        overflow-x: visible;
    }

    .home-magazine .hm-hero-mini {
        flex: none;
        width: 100%;
    }

    .home-magazine .hm-grid--3,
    .home-magazine .hm-grid--2,
    .home-magazine .hm-grid--4 {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-card-media {
        aspect-ratio: 16/10;
    }

    .home-magazine .hm-numbered-item {
        grid-template-columns: 2.5rem 1fr;
        grid-template-rows: auto auto;
    }

    .home-magazine .hm-numbered-index {
        grid-row: span 2;
        font-size: 2rem;
    }

    .home-magazine .hm-numbered-media {
        display: none;
    }

    .home-magazine .hm-card--horizontal {
        flex-direction: column;
    }

    .home-magazine .hm-card--horizontal .hm-card-media {
        width: 100%;
        aspect-ratio: 16/10;
        min-height: unset;
    }

    .home-magazine .hm-trending-inner {
        flex-wrap: wrap;
    }

    .home-magazine .hm-sidebar {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-popular-grid {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-cat-title {
        font-size: 1.75rem;
    }
}
</style>
