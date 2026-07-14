<style>
/* Homepage magazine — black theme */
body:has(.home-magazine) {
    background: #0a0a0a !important;
    color: #e8e8e8;
}

.home-magazine {
    --hm-bg: #0a0a0a;
    --hm-surface: #141414;
    --hm-surface-2: #1a1a1a;
    --hm-line: rgba(255, 255, 255, 0.08);
    --hm-text: #f5f5f5;
    --hm-muted: #9ca3af;
    --hm-accent: #e91e8c;
    --hm-radius: 6px;
    --hm-shell: min(1320px, calc(100% - 2rem));
    font-family: 'DM Sans', system-ui, sans-serif;
    background: var(--hm-bg);
    color: var(--hm-text);
    padding-bottom: 3rem;
}

.home-magazine .hm-shell {
    width: var(--hm-shell);
    margin-inline: auto;
}

/* Hero */
.home-magazine .hm-hero {
    padding: 1.5rem 0 1rem;
    border-bottom: 1px solid var(--hm-line);
}

.home-magazine .hm-hero-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 1.25rem;
    align-items: stretch;
}

/* Hero carousel */
.home-magazine .hm-hero-carousel {
    position: relative;
    border-radius: var(--hm-radius);
    overflow: hidden;
    min-height: 380px;
    background: var(--hm-surface);
}

.home-magazine .hm-hero-carousel-track {
    display: flex;
    transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    height: 100%;
    min-height: 380px;
}

.home-magazine .hm-hero-slide {
    flex: 0 0 100%;
    position: relative;
    display: block;
    min-height: 380px;
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
    transition: transform 0.5s ease;
}

.home-magazine .hm-hero-slide:hover .hm-hero-main-media img {
    transform: scale(1.03);
}

.home-magazine .hm-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
    width: 2.5rem;
    height: 2.5rem;
    border: none;
    border-radius: 50%;
    background: rgba(0,0,0,0.55);
    color: #fff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.home-magazine .hm-hero-arrow svg {
    width: 1.25rem;
    height: 1.25rem;
}

.home-magazine .hm-hero-arrow:hover {
    background: rgba(233, 30, 140, 0.85);
}

.home-magazine .hm-hero-arrow--prev { left: 0.85rem; }
.home-magazine .hm-hero-arrow--next { right: 0.85rem; }

.home-magazine .hm-hero-dots {
    position: absolute;
    left: 50%;
    bottom: 0.85rem;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    gap: 0.4rem;
}

.home-magazine .hm-hero-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
    padding: 0;
}

.home-magazine .hm-hero-dot.is-active {
    background: #fff;
    transform: scale(1.15);
}

.home-magazine .hm-hero-main {
    position: relative;
    display: block;
    border-radius: var(--hm-radius);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    min-height: 380px;
    background: var(--hm-surface);
}

.home-magazine .hm-hero-main-media {
    position: absolute;
    inset: 0;
}

.home-magazine .hm-hero-main-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.home-magazine .hm-hero-main:hover .hm-hero-main-media img {
    transform: scale(1.04);
}

.home-magazine .hm-hero-main-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.35) 55%, rgba(0,0,0,0.1) 100%);
}

.home-magazine .hm-hero-main-body {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 1.5rem 1.75rem;
    z-index: 1;
}

.home-magazine .hm-hero-main-title {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.45rem, 2.6vw, 2.15rem);
    font-weight: 700;
    line-height: 1.2;
    color: #fff;
    margin: 0.5rem 0 0.35rem;
}

.home-magazine .hm-hero-main-meta {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.7);
    margin: 0;
}

.home-magazine .hm-hero-aside {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.home-magazine .hm-hero-mini {
    display: grid;
    grid-template-columns: 110px 1fr;
    gap: 0.85rem;
    align-items: center;
    padding: 0.65rem;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    border: 1px solid var(--hm-line);
    text-decoration: none;
    color: inherit;
    transition: background 0.2s, border-color 0.2s;
    flex: 1;
}

.home-magazine .hm-hero-mini:hover {
    background: var(--hm-surface-2);
    border-color: rgba(255,255,255,0.14);
}

.home-magazine .hm-hero-mini-media {
    aspect-ratio: 4/3;
    border-radius: 4px;
    overflow: hidden;
    background: #222;
}

.home-magazine .hm-hero-mini-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-hero-mini-title {
    font-size: 0.98rem;
    font-weight: 600;
    line-height: 1.35;
    color: var(--hm-text);
    margin: 0 0 0.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-hero-mini-meta {
    font-size: 0.78rem;
    color: var(--hm-muted);
    margin: 0;
}

/* Trending ticker */
.home-magazine .hm-trending {
    border-bottom: 1px solid var(--hm-line);
    background: #111;
    overflow: hidden;
}

.home-magazine .hm-trending-inner {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.65rem 0;
    min-height: 44px;
}

.home-magazine .hm-trending-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    flex-shrink: 0;
    background: #dc2626;
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.3rem 0.65rem;
    border-radius: 3px;
}

.home-magazine .hm-trending-badge .hm-widget-icon {
    width: 0.85rem;
    height: 0.85rem;
    color: #fff;
}

.home-magazine .hm-trending-track {
    flex: 1;
    overflow: hidden;
    mask-image: linear-gradient(90deg, transparent, #000 2%, #000 98%, transparent);
}

.home-magazine .hm-trending-list {
    display: flex;
    gap: 2rem;
    animation: hm-ticker 40s linear infinite;
    white-space: nowrap;
}

.home-magazine .hm-trending-link {
    color: var(--hm-muted);
    text-decoration: none;
    font-size: 1rem;
    transition: color 0.2s;
}

.home-magazine .hm-trending-link:hover {
    color: #fff;
}

@keyframes hm-ticker {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Layout */
.home-magazine .hm-layout {
    width: var(--hm-shell);
    margin: 2rem auto 0;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 2rem;
    align-items: start;
}

.home-magazine .hm-main {
    min-width: 0;
}

/* Category sections */
.home-magazine .hm-cat-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--hm-line);
}

.home-magazine .hm-cat-section:last-child {
    border-bottom: none;
}

.home-magazine .hm-cat-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding-bottom: 0.65rem;
    border-bottom: 2px solid var(--hm-cat-accent, var(--hm-accent));
}

.home-magazine .hm-cat-title {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.45rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.home-magazine .hm-cat-more {
    font-size: 0.82rem;
    color: var(--hm-muted);
    text-decoration: none;
    white-space: nowrap;
    transition: color 0.2s;
}

.home-magazine .hm-cat-more:hover {
    color: var(--hm-accent);
}

/* Category mobile carousel (hidden on desktop) */
.home-magazine .hm-cat-carousel {
    display: none;
    position: relative;
    border-radius: var(--hm-radius);
    overflow: hidden;
    min-height: 300px;
    background: var(--hm-surface);
}

.home-magazine .hm-cat-carousel-track {
    display: flex;
    transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    height: 100%;
    min-height: 300px;
}

.home-magazine .hm-cat-slide {
    flex: 0 0 100%;
    position: relative;
    display: block;
    min-height: 300px;
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

/* Tags */
.home-magazine .hm-tag {
    display: inline-block;
    background: var(--hm-tag-color, var(--hm-accent));
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.25rem 0.55rem;
    border-radius: 3px;
}

.home-magazine .hm-tag--on-image {
    position: absolute;
    left: 0.65rem;
    bottom: 0.65rem;
    z-index: 2;
}

.home-magazine .hm-tag--ghost {
    background: transparent;
    color: var(--hm-tag-color, var(--hm-accent));
    border: 1px solid var(--hm-tag-color, var(--hm-accent));
    padding: 0.15rem 0.45rem;
}

/* Cards */
.home-magazine .hm-grid {
    display: grid;
    gap: 1.1rem;
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
    margin-top: 1rem;
}

.home-magazine .hm-grid--secondary {
    margin-top: 1.1rem;
}

.home-magazine .hm-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    overflow: hidden;
    border: 1px solid var(--hm-line);
    transition: border-color 0.2s, transform 0.2s;
}

.home-magazine .hm-card:hover {
    border-color: rgba(255,255,255,0.16);
    transform: translateY(-2px);
}

.home-magazine .hm-card-media {
    position: relative;
    aspect-ratio: 16/10;
    overflow: hidden;
    background: #222;
}

.home-magazine .hm-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.home-magazine .hm-card:hover .hm-card-media img {
    transform: scale(1.05);
}

.home-magazine .hm-card-body {
    padding: 1rem 1.1rem 1.15rem;
}

.home-magazine .hm-card-title {
    font-size: 1.08rem;
    font-weight: 600;
    line-height: 1.35;
    color: #fff;
    margin: 0 0 0.35rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-card-title--lg {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.25rem;
    -webkit-line-clamp: 3;
}

.home-magazine .hm-card-meta {
    font-size: 0.78rem;
    color: var(--hm-muted);
    margin: 0 0 0.5rem;
}

.home-magazine .hm-card-excerpt {
    font-size: 0.85rem;
    color: var(--hm-muted);
    line-height: 1.5;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Featured split layout */
.home-magazine .hm-split {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 1.1rem;
    align-items: stretch;
}

.home-magazine .hm-card--featured {
    position: relative;
    min-height: 320px;
}

.home-magazine .hm-card--featured .hm-card-media {
    position: absolute;
    inset: 0;
    aspect-ratio: unset;
}

.home-magazine .hm-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 60%);
    z-index: 1;
}

.home-magazine .hm-card-body--overlay {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    padding: 1.25rem;
}

.home-magazine .hm-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.home-magazine .hm-list-item {
    display: grid;
    grid-template-columns: 90px 1fr;
    gap: 0.75rem;
    align-items: center;
    padding: 0.6rem;
    background: var(--hm-surface);
    border: 1px solid var(--hm-line);
    border-radius: var(--hm-radius);
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
    flex: 1;
}

.home-magazine .hm-list-item:hover {
    background: var(--hm-surface-2);
}

.home-magazine .hm-list-media {
    aspect-ratio: 1;
    border-radius: 4px;
    overflow: hidden;
    background: #222;
}

.home-magazine .hm-list-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-list-title {
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.35;
    color: #fff;
    margin: 0 0 0.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-list-meta {
    font-size: 0.75rem;
    color: var(--hm-muted);
    margin: 0;
}

/* Horizontal card */
.home-magazine .hm-card--horizontal {
    flex-direction: row;
}

.home-magazine .hm-card--horizontal .hm-card-media {
    width: 42%;
    flex-shrink: 0;
    aspect-ratio: unset;
    min-height: 140px;
}

.home-magazine .hm-card--horizontal .hm-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Sidebar */
.home-magazine .hm-sidebar {
    position: sticky;
    top: var(--magazine-sticky-offset, 9rem);
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.home-magazine .hm-widget {
    background: var(--hm-surface);
    border: 1px solid var(--hm-line);
    border-radius: var(--hm-radius);
    padding: 1.1rem 1.15rem;
}

.home-magazine .hm-widget-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 1rem;
    padding-bottom: 0.5rem;
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
    gap: 0.35rem;
}

.home-magazine .hm-cat-nav-item {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 0;
    color: var(--hm-muted);
    text-decoration: none;
    font-size: 1rem;
    border-bottom: 1px solid var(--hm-line);
    transition: color 0.2s;
}

.home-magazine .hm-cat-nav-item:last-child {
    border-bottom: none;
}

.home-magazine .hm-cat-nav-item:hover {
    color: #fff;
}

.home-magazine .hm-cat-nav-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.home-magazine .hm-trending-list-widget {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.home-magazine .hm-trending-list-widget li {
    counter-increment: hm-trend;
}

.home-magazine .hm-trending-widget-item {
    display: grid;
    grid-template-columns: 56px 1fr;
    gap: 0.65rem;
    text-decoration: none;
    color: inherit;
    align-items: start;
}

.home-magazine .hm-trending-widget-thumb {
    width: 56px;
    height: 56px;
    border-radius: 4px;
    overflow: hidden;
    background: #222;
    flex-shrink: 0;
}

.home-magazine .hm-trending-widget-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-trending-widget-title {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    line-height: 1.35;
    color: #fff;
    margin-bottom: 0.15rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-magazine .hm-trending-widget-meta {
    font-size: 0.72rem;
    color: var(--hm-muted);
}

.home-magazine .hm-latest-widget {
    list-style: none;
    margin: 0;
    padding: 0;
}

.home-magazine .hm-latest-widget li {
    border-bottom: 1px solid var(--hm-line);
}

.home-magazine .hm-latest-widget li:last-child {
    border-bottom: none;
}

.home-magazine .hm-latest-widget a {
    display: block;
    padding: 0.55rem 0;
    color: var(--hm-muted);
    text-decoration: none;
    font-size: 0.86rem;
    line-height: 1.4;
    transition: color 0.2s;
}

.home-magazine .hm-latest-widget a:hover {
    color: #fff;
}

/* Empty state */
.home-magazine .hm-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--hm-surface);
    border-radius: var(--hm-radius);
    border: 1px solid var(--hm-line);
}

.home-magazine .hm-empty h2 {
    font-family: 'DM Sans', system-ui, sans-serif;
    color: #fff;
    margin-bottom: 0.5rem;
}

.home-magazine .hm-empty p {
    color: var(--hm-muted);
    margin-bottom: 1.25rem;
}

.home-magazine .hm-empty-link {
    display: inline-block;
    padding: 0.55rem 1.25rem;
    background: var(--hm-accent);
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Compact 4-col cards */
.home-magazine .hm-card--compact .hm-card-media--square {
    aspect-ratio: 1;
}

.home-magazine .hm-card-title--sm {
    font-size: 1rem;
    -webkit-line-clamp: 3;
}

/* Banner card */
.home-magazine .hm-banner-card {
    display: block;
    position: relative;
    border-radius: var(--hm-radius);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    min-height: 280px;
    margin-bottom: 1rem;
    background: var(--hm-surface);
}

.home-magazine .hm-banner-media {
    position: absolute;
    inset: 0;
}

.home-magazine .hm-banner-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-banner-body {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.92), transparent);
}

.home-magazine .hm-banner-title {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.2rem, 2vw, 1.65rem);
    font-weight: 700;
    color: #fff;
    margin: 0.45rem 0 0.35rem;
    line-height: 1.25;
}

.home-magazine .hm-mini-card {
    display: block;
    text-decoration: none;
    color: inherit;
}

.home-magazine .hm-mini-card-media {
    aspect-ratio: 16/10;
    border-radius: var(--hm-radius);
    overflow: hidden;
    background: #222;
    margin-bottom: 0.5rem;
}

.home-magazine .hm-mini-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.home-magazine .hm-mini-card:hover .hm-mini-card-media img {
    transform: scale(1.05);
}

.home-magazine .hm-mini-card-title {
    font-size: 0.82rem;
    font-weight: 600;
    line-height: 1.35;
    color: #fff;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Masonry layout */
.home-magazine .hm-masonry {
    display: grid;
    grid-template-columns: 1fr 1fr 0.85fr;
    gap: 1rem;
    align-items: stretch;
}

.home-magazine .hm-masonry-large .hm-card-media--tall {
    aspect-ratio: 3/4;
}

.home-magazine .hm-masonry-stack {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

.home-magazine .hm-list-item--dense {
    padding: 0.5rem;
}

.home-magazine .hm-list-item--dense .hm-list-media {
    width: 70px;
    aspect-ratio: 1;
}

/* Numbered list */
.home-magazine .hm-numbered-list {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.home-magazine .hm-numbered-item {
    display: grid;
    grid-template-columns: 2.5rem 120px 1fr;
    gap: 1rem;
    align-items: center;
    padding: 0.85rem;
    background: var(--hm-surface);
    border: 1px solid var(--hm-line);
    border-radius: var(--hm-radius);
    text-decoration: none;
    color: inherit;
    transition: border-color 0.2s, background 0.2s;
}

.home-magazine .hm-numbered-item:hover {
    background: var(--hm-surface-2);
    border-color: rgba(255,255,255,0.14);
}

.home-magazine .hm-numbered-index {
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: rgba(255,255,255,0.15);
    line-height: 1;
    text-align: center;
}

.home-magazine .hm-numbered-media {
    aspect-ratio: 4/3;
    border-radius: 4px;
    overflow: hidden;
    background: #222;
}

.home-magazine .hm-numbered-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-magazine .hm-numbered-title {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    margin: 0.25rem 0;
    line-height: 1.35;
}

.home-magazine .hm-numbered-meta {
    font-size: 0.78rem;
    color: var(--hm-muted);
    margin: 0;
    line-height: 1.5;
}

/* Popular Posts */
.home-magazine .hm-popular {
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid var(--hm-line);
}

.home-magazine .hm-popular-head {
    margin-bottom: 1.5rem;
}

.home-magazine .hm-popular-title {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: clamp(1.35rem, 2.5vw, 1.65rem);
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: -0.02em;
}

.home-magazine .hm-popular-title .hm-widget-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.home-magazine .hm-popular-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.35rem;
}

.home-magazine .hm-popular-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    background: var(--hm-surface);
    border: 1px solid var(--hm-line);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
}

.home-magazine .hm-popular-card:hover {
    border-color: rgba(255,255,255,0.14);
    transform: translateY(-2px);
}

.home-magazine .hm-popular-media {
    aspect-ratio: 16/10;
    overflow: hidden;
    background: #222;
}

.home-magazine .hm-popular-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.home-magazine .hm-popular-card:hover .hm-popular-media img {
    transform: scale(1.04);
}

.home-magazine .hm-popular-body {
    padding: 1rem 1.1rem 1.15rem;
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
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
}

.home-magazine .hm-popular-name {
    font-size: 1.02rem;
    font-weight: 700;
    color: #fff;
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
    font-size: 0.78rem;
    color: var(--hm-muted);
    margin: 0.15rem 0 0;
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
    opacity: 0.85;
}

/* Responsive */
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

    .home-magazine .hm-hero-main {
        min-height: 300px;
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

    .home-magazine .hm-hero-aside {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
    }

    .home-magazine .hm-hero-mini {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-hero-mini-media {
        aspect-ratio: 16/9;
    }

    .home-magazine .hm-grid--3,
    .home-magazine .hm-grid--2,
    .home-magazine .hm-grid--4 {
        grid-template-columns: 1fr;
    }

    .home-magazine .hm-numbered-item {
        grid-template-columns: 2rem 1fr;
        grid-template-rows: auto auto;
    }

    .home-magazine .hm-numbered-index {
        grid-row: span 2;
        font-size: 1.35rem;
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
}
</style>
