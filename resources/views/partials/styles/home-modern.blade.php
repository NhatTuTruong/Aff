<style>
    body:has(.home-page) {
        background: var(--pub-bg, #f0f2f5);
        font-family: var(--pub-font, 'Outfit', 'DM Sans', system-ui, sans-serif);
    }

    .home-page {
        --hp-ink: var(--pub-ink, #0f172a);
        --hp-muted: var(--pub-muted, #64748b);
        --hp-line: var(--pub-line, rgba(15, 23, 42, 0.08));
        --hp-accent: var(--pub-accent, #7c3aed);
        --hp-accent-deep: var(--pub-accent-2, #6d28d9);
        --hp-accent-darker: var(--pub-accent-3, #a78bfa);
        --hp-violet: var(--pub-accent, #7c3aed);
        --hp-violet-deep: var(--pub-accent-2, #6d28d9);
        --hp-rose: var(--pub-accent, #7c3aed);
        --hp-rose-deep: var(--pub-accent-3, #a78bfa);
        --hp-surface: var(--pub-surface, #ffffff);
        --hp-dark: var(--pub-dark, #0f172a);
        --hp-dark-2: var(--pub-dark-2, #1e293b);
        --hp-cats-bg: #0f172a;
        --hp-radius-xl: 28px;
        --hp-radius-lg: 20px;
        --hp-radius-md: 14px;
        --hp-shadow: 0 4px 24px -8px rgba(15, 23, 42, 0.12);
        --hp-shadow-lg: 0 16px 48px -20px rgba(15, 23, 42, 0.18);
        --hp-shadow-sm: 0 2px 12px -4px rgba(15, 23, 42, 0.1);
        --hp-font: var(--pub-font, 'Outfit', 'DM Sans', system-ui, sans-serif);
        --hp-heading: var(--pub-heading, 'Space Grotesk', system-ui, sans-serif);

        background: var(--pub-bg, #f0f2f5);
        color: var(--hp-ink);
        font-family: var(--hp-font);
    }

    .home-page .font-heading,
    .home-page h1,
    .home-page h2,
    .home-page h3,
    .home-page .hp-sec-title,
    .home-page .hp-aside-stat,
    .home-page .hp-stat-num,
    .home-page .hp-post-title,
    .home-page .hp-aside-counter {
        font-family: var(--hp-heading);
    }

    .home-page .hp-shell {
        width: 100%;
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 clamp(1rem, 3vw, 2rem);
    }

    /* ── Hero ── */
    .home-page .hp-hero {
        position: relative;
        padding: clamp(3.5rem, 7vw, 5.5rem) 0 clamp(5rem, 8vw, 6.5rem);
        overflow: hidden;
        background: var(--hp-dark);
        border-bottom: 1px solid rgba(124, 58, 237, 0.2);
    }

    .home-page .hp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 55% at 10% 20%, rgba(124, 58, 237, 0.22) 0%, transparent 65%),
            radial-gradient(ellipse 50% 45% at 90% 15%, rgba(124, 58, 237, 0.14) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 75% 90%, rgba(109, 40, 217, 0.1) 0%, transparent 55%);
        pointer-events: none;
    }

    .home-page .hp-hero::after {
        content: '';
        position: absolute;
        inset: auto 0 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 5%, rgba(124, 58, 237, 0.4) 35%, rgba(167, 139, 250, 0.3) 65%, transparent 95%);
        pointer-events: none;
    }

    .home-page .hp-hero-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.75rem;
        align-items: center;
    }

    @media (min-width: 960px) {
        .home-page .hp-hero-grid {
            grid-template-columns: 1.1fr 0.9fr;
            gap: 3.5rem;
        }
    }

    .home-page .hp-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.15rem;
        padding: 0.38rem 0.85rem;
        border-radius: 999px;
        background: rgba(124, 58, 237, 0.16);
        border: 1px solid rgba(124, 58, 237, 0.3);
    }

    .home-page .hp-kicker::before {
        content: '';
        width: 1.5rem;
        height: 2px;
        background: var(--hp-accent);
        border-radius: 2px;
    }

    .home-page .hp-hero h1 {
        font-size: clamp(2.4rem, 5.5vw, 3.8rem);
        font-weight: 800;
        letter-spacing: -0.045em;
        line-height: 1.02;
        color: #ffffff;
        margin: 0 0 1.15rem;
        max-width: 16ch;
    }

    .home-page .hp-hero-accent {
        background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 50%, #7c3aed 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    .home-page .hp-hero-lead {
        font-size: clamp(1rem, 1.35vw, 1.125rem);
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.75;
        max-width: 38rem;
        margin: 0 0 1.25rem;
    }

    .home-page .hp-trust {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 1.75rem;
        max-width: 36rem;
        line-height: 1.6;
    }

    .home-page .hp-trust a {
        color: var(--hp-accent-darker);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.2s;
    }

    .home-page .hp-trust a:hover {
        color: #e2e8f0;
    }

    /* Search bar */
    .home-page .hp-search {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.45rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        box-shadow:
            0 20px 48px -24px rgba(0, 0, 0, 0.5),
            inset 0 1px 0 rgba(255, 255, 255, 0.08);
        max-width: 520px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .home-page .hp-search:focus-within {
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow:
            0 20px 48px -24px rgba(124, 58, 237, 0.3),
            0 0 0 3px rgba(124, 58, 237, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .home-page .hp-search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.8rem 1rem 0.8rem 1.1rem;
        font-size: 1rem;
        color: #ffffff;
        outline: none;
    }

    .home-page .hp-search input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .home-page .hp-search button {
        border: none;
        cursor: pointer;
        padding: 0.8rem 1.5rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.8125rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, var(--hp-accent) 0%, var(--hp-accent-deep) 100%);
        transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
        box-shadow: 0 8px 22px -8px rgba(124, 58, 237, 0.55);
    }

    .home-page .hp-search button:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px -8px rgba(124, 58, 237, 0.5);
        filter: brightness(1.08);
    }

    /* Hero aside slider */
    .home-page .hp-hero-aside {
        position: relative;
        min-height: 300px;
        border-radius: var(--hp-radius-lg);
        background: rgba(255, 255, 255, 0.05);
        padding: 0;
        color: #ffffff;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow:
            0 32px 64px -32px rgba(0, 0, 0, 0.6),
            inset 0 1px 0 rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .home-page .hp-hero-aside::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(100% 80% at 100% 0%, rgba(124, 58, 237, 0.14) 0%, transparent 55%),
            radial-gradient(70% 60% at 0% 100%, rgba(124, 58, 237, 0.06) 0%, transparent 50%);
        pointer-events: none;
    }

    .home-page .hp-aside-deco {
        position: absolute;
        right: 1.25rem;
        top: 1.25rem;
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        background: rgba(124, 58, 237, 0.1);
        pointer-events: none;
        z-index: 0;
    }

    .home-page .hp-aside-deco::after {
        content: '';
        position: absolute;
        inset: 0.6rem;
        border-radius: 50%;
        border: 1px dashed rgba(124, 58, 237, 0.3);
    }

    .home-page .hp-aside-label {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #ffffff;
        margin-bottom: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 999px;
        background: rgba(30, 58, 138, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    .home-page .hp-aside-stat {
        font-size: clamp(1.85rem, 3.5vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.05;
        margin: 0 0 0.5rem;
        color: #ffffff;
        max-width: 14ch;
    }

    .home-page .hp-aside-caption {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.6);
        max-width: 22rem;
        line-height: 1.6;
        margin: 0;
    }

    .home-page .hp-aside-slider {
        position: relative;
        z-index: 1;
        min-height: 260px;
        display: flex;
        flex-direction: column;
    }

    .home-page .hp-aside-track-wrap {
        flex: 1;
        overflow: hidden;
        padding: 1.5rem 1.5rem 0.5rem;
    }

    .home-page .hp-aside-track {
        display: flex;
        width: 100%;
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        will-change: transform;
    }

    .home-page .hp-aside-slide {
        flex: 0 0 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .home-page .hp-aside-slide-inner {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 0.8rem 1rem;
        align-items: start;
    }

    .home-page .hp-aside-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #fff;
        background: linear-gradient(145deg, var(--hp-accent) 0%, var(--hp-accent-deep) 100%);
        box-shadow: 0 8px 20px -10px rgba(124, 58, 237, 0.55);
    }

    .home-page .hp-aside-icon svg {
        width: 1.15rem;
        height: 1.15rem;
        display: block;
    }

    .home-page .hp-aside-slide-body {
        min-width: 0;
        padding-top: 0.05rem;
    }

    .home-page .hp-aside-footer {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 1.25rem 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .home-page .hp-aside-counter {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.8);
    }

    .home-page .hp-aside-counter-sep {
        color: rgba(124, 58, 237, 0.5);
        margin: 0 0.15rem;
    }

    .home-page .hp-aside-dots {
        display: flex;
        gap: 0.35rem;
        align-items: center;
        padding: 0.3rem 0.4rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .home-page .hp-aside-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        padding: 0;
        border: none;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.25);
        transition: width 0.25s ease, background 0.25s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .home-page .hp-aside-dot:hover {
        background: rgba(124, 58, 237, 0.5);
    }

    .home-page .hp-aside-dot.is-active {
        width: 1.3rem;
        background: linear-gradient(90deg, var(--hp-accent), var(--hp-accent-deep));
    }

    /* ── Stats (overlap hero) ── */
    .home-page .hp-stats {
        padding: 0 0 2.25rem;
        position: relative;
        z-index: 2;
        margin-top: -3rem;
    }

    .home-page .hp-stats-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }

    @media (min-width: 640px) {
        .home-page .hp-stats-row {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    .home-page .hp-stat-card {
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        border-radius: var(--hp-radius-lg);
        padding: 1.4rem 1rem;
        text-align: center;
        box-shadow: var(--hp-shadow);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .home-page .hp-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--hp-shadow-lg);
    }

    .home-page .hp-stat-num {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -0.035em;
        color: var(--hp-ink);
        line-height: 1.1;
    }

    .home-page .hp-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--hp-muted);
        margin-top: 0.4rem;
    }

    /* ── Sections ── */
    .home-page .hp-section {
        padding: clamp(2.5rem, 5vw, 3.5rem) 0;
        background: transparent;
    }

    .home-page .hp-section--tint {
        background: transparent;
        border: none;
    }

    .home-page .hp-section--tint > .hp-shell {
        background: var(--hp-surface);
        border-radius: var(--hp-radius-xl);
        box-shadow: var(--hp-shadow);
        padding: clamp(1.75rem, 3.5vw, 2.5rem) clamp(1.25rem, 3vw, 2rem);
    }

    .home-page .hp-sec-head {
        margin-bottom: 1.75rem;
        max-width: 40rem;
        position: relative;
        padding-left: 1rem;
    }

    .home-page .hp-sec-head::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.25rem;
        bottom: 0.25rem;
        width: 3px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--hp-accent), var(--hp-accent-deep));
    }

    .home-page .hp-sec-eyebrow {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--hp-accent-deep);
        margin-bottom: 0.4rem;
    }

    .home-page .hp-sec-title {
        font-size: clamp(1.4rem, 2.8vw, 1.85rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--hp-ink);
        margin: 0 0 0.5rem;
        line-height: 1.2;
    }

    .home-page .hp-sec-desc {
        margin: 0;
        color: var(--hp-muted);
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    .home-page .hp-disclaimer {
        font-size: 0.8rem;
        color: var(--hp-muted);
        margin: -0.25rem 0 1.5rem;
        padding: 0.85rem 1rem;
        border-radius: var(--hp-radius-md);
        border: 1px dashed var(--hp-line);
        background: rgba(124, 58, 237, 0.04);
        max-width: 720px;
        line-height: 1.55;
    }

    .home-page .hp-disclaimer a {
        color: var(--hp-accent-deep);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
        transition: color 0.2s;
    }

    .home-page .hp-disclaimer a:hover {
        color: var(--hp-accent);
    }

    .home-page #coupons,
    .home-page #stores,
    .home-page #blog,
    .home-page #categories {
        scroll-margin-top: 5rem;
    }

    /* ── Blog posts ── */
    .home-page .hp-posts {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.25rem;
    }

    .home-page .hp-blog-wrap {
        overflow: hidden;
        width: 100%;
    }

    .home-page .hp-blog-track {
        display: flex;
        gap: 0;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .home-page .hp-blog-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .home-page .hp-blog-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 999px;
        border: 1px solid var(--hp-line);
        background: var(--hp-surface);
        color: var(--hp-ink);
        cursor: pointer;
        box-shadow: var(--hp-shadow-sm);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s, background 0.2s;
    }

    .home-page .hp-blog-btn:hover {
        transform: translateY(-1px);
        border-color: rgba(124, 58, 237, 0.35);
        box-shadow: var(--hp-shadow);
        background: #ffffff;
    }

    @media (max-width: 768px) {
        .home-page .hp-posts {
            display: none;
        }

        .home-page .hp-blog-wrap {
            display: block;
        }

        .home-page .hp-blog-controls {
            display: flex;
        }

        .home-page .hp-blog-track {
            width: 100%;
            box-sizing: border-box;
        }

        .home-page .hp-blog-wrap .hp-post-card {
            flex: 0 0 100%;
            max-width: none;
            margin: 0;
        }
    }

    @media (min-width: 769px) {
        .home-page .hp-blog-wrap {
            display: block;
        }

        .home-page .hp-blog-controls {
            display: none;
        }

        .home-page .hp-blog-track {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
            transform: none !important;
        }
    }

    .home-page .hp-post-card {
        display: flex;
        flex-direction: column;
        border-radius: var(--hp-radius-lg);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        box-shadow: var(--hp-shadow-sm);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.2s;
    }

    .home-page .hp-post-card:hover {
        transform: translateY(-5px);
        border-color: rgba(124, 58, 237, 0.25);
        box-shadow: var(--hp-shadow-lg);
    }

    .home-page .hp-post-media {
        aspect-ratio: 16 / 10;
        background: #e2e8f0;
        overflow: hidden;
    }

    .home-page .hp-post-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .home-page .hp-post-card:hover .hp-post-media img {
        transform: scale(1.04);
    }

    .home-page .hp-post-body {
        padding: 1.2rem 1.25rem 1.3rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .home-page .hp-post-title {
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0 0 0.4rem;
        color: var(--hp-ink);
    }

    .home-page .hp-post-meta {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin-top: auto;
    }

    .home-page .hp-post-link {
        margin-top: 0.6rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--hp-accent-deep);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: gap 0.2s, color 0.2s;
    }

    .home-page .hp-post-link::after {
        content: '→';
        transition: transform 0.2s;
    }

    .home-page .hp-post-card:hover .hp-post-link {
        color: var(--hp-accent);
        gap: 0.5rem;
    }

    .home-page .hp-post-card:hover .hp-post-link::after {
        transform: translateX(2px);
    }

    .home-page .hp-all-posts {
        margin-top: 1.5rem;
    }

    .home-page .hp-all-posts a {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--hp-accent-deep);
        text-decoration: none;
        border-bottom: 2px solid rgba(124, 58, 237, 0.3);
        padding-bottom: 2px;
        transition: color 0.2s, border-color 0.2s;
    }

    .home-page .hp-all-posts a:hover {
        color: var(--hp-accent);
        border-bottom-color: rgba(124, 58, 237, 0.5);
    }

    .home-page .hp-blog-modern {
        display: flex;
        gap: 1.25rem;
        align-items: stretch;
    }

    .home-page .hp-blog-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
    }

    .home-page .hp-blog-col--left {
        flex: 1.4;
    }

    .home-page .hp-blog-col--right {
        flex: 1;
    }

    .home-page .hp-blog-featured {
        display: grid;
        grid-template-rows: minmax(180px, 0.85fr) auto;
        border-radius: var(--hp-radius-xl);
        overflow: hidden;
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        box-shadow: var(--hp-shadow);
        text-decoration: none;
        color: inherit;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.2s;
    }

    .home-page .hp-blog-featured:hover {
        transform: translateY(-4px);
        border-color: rgba(124, 58, 237, 0.35);
        box-shadow: var(--hp-shadow-lg);
    }

    .home-page .hp-blog-featured-media {
        overflow: hidden;
        background: #e2e8f0;
    }

    .home-page .hp-blog-featured-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }

    .home-page .hp-blog-featured:hover .hp-blog-featured-media img {
        transform: scale(1.04);
    }

    .home-page .hp-blog-featured-body {
        padding: 0.1rem 1.4rem 0.35rem;
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .home-page .hp-blog-featured-label {
        display: inline-flex;
        align-self: flex-start;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #ffffff;
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.35);
    }

    .home-page .hp-blog-featured-title {
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0.2rem 0 0.1rem;
        color: var(--hp-ink);
    }

    .home-page .hp-blog-featured-meta {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin: 0;
    }

    .home-page .hp-blog-featured-link {
        margin-top: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--hp-accent-deep);
        transition: gap 0.2s, color 0.2s;
    }

    .home-page .hp-blog-featured:hover .hp-blog-featured-link {
        color: var(--hp-accent);
        gap: 0.55rem;
    }

    .home-page .hp-blog-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .home-page .hp-blog-grid-card {
        display: flex;
        flex-direction: column;
        min-height: 260px;
        border-radius: var(--hp-radius-lg);
        overflow: hidden;
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        box-shadow: var(--hp-shadow-sm);
        text-decoration: none;
        color: inherit;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.2s;
    }

    .home-page .hp-blog-grid-card .hp-blog-grid-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .home-page .hp-blog-grid-card:hover {
        transform: translateY(-3px);
        border-color: rgba(124, 58, 237, 0.35);
        box-shadow: var(--hp-shadow-lg);
    }

    .home-page .hp-blog-grid-media {
        aspect-ratio: 16 / 11;
        overflow: hidden;
        background: #e2e8f0;
    }

    .home-page .hp-blog-grid-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .home-page .hp-blog-grid-card:hover .hp-blog-grid-media img {
        transform: scale(1.04);
    }

    .home-page .hp-blog-grid-body {
        padding: 1rem 1.05rem 1.1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex: 1;
    }

    .home-page .hp-blog-grid-title {
        font-size: 0.98rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        line-height: 1.25;
        margin: 0;
        color: var(--hp-ink);
    }

    .home-page .hp-blog-grid-meta {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin-top: auto;
    }

    @media (max-width: 768px) {
        .home-page .hp-blog-modern {
            display: none;
        }

        .home-page .hp-blog-mobile-carousel {
            display: block;
            overflow: hidden;
            width: 100%;
            margin: 0 -1.25rem;
            padding: 0 1.25rem;
        }

        .home-page .hp-blog-carousel-track {
            display: flex;
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .home-page .hp-blog-carousel-slide {
            flex: 0 0 100%;
            min-width: 0;
            box-sizing: border-box;
            padding-right: 1rem;
        }

        .home-page .hp-blog-carousel-slide .hp-blog-grid-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 320px;
        }

        .home-page .hp-blog-carousel-slide .hp-blog-grid-media {
            flex: 0 0 160px;
            min-height: 160px;
        }

        .home-page .hp-blog-carousel-slide .hp-blog-grid-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .home-page .hp-blog-mobile-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1rem;
        }

        .home-page .hp-blog-mobile-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            padding: 0;
            border: none;
            cursor: pointer;
            background: rgba(124, 58, 237, 0.25);
            transition: width 0.25s ease, background 0.25s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .home-page .hp-blog-mobile-dot:hover {
            background: rgba(124, 58, 237, 0.45);
        }

        .home-page .hp-blog-mobile-dot.is-active {
            width: 1.5rem;
            background: linear-gradient(90deg, var(--hp-accent), var(--hp-accent-deep));
        }
    }

    @media (max-width: 480px) {
        .home-page .hp-blog-mobile-carousel {
            margin: 0 -1rem;
            padding: 0 1rem;
        }
    }

    @media (min-width: 769px) {
        .home-page .hp-blog-mobile-carousel,
        .home-page .hp-blog-mobile-controls {
            display: none !important;
        }

        .home-page .hp-blog-col--right .hp-blog-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            align-content: stretch;
        }

        .home-page .hp-blog-col--right .hp-blog-grid .hp-blog-grid-card {
            align-self: stretch;
        }
    }

    /* ── Stores panel ── */
    .home-page .hp-stores-panel {
        background: var(--hp-dark-2);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: var(--hp-radius-xl);
        padding: 1.75rem 1.25rem 1.5rem;
        box-shadow: 0 24px 56px -32px rgba(7, 13, 24, 0.5);
    }

    .home-page .stores-carousel-wrap {
        overflow: hidden;
        margin: 0 -0.25rem;
        padding: 0 0.25rem;
        cursor: grab;
        user-select: none;
    }

    .home-page .stores-carousel-wrap:active {
        cursor: grabbing;
    }

    .home-page .stores-carousel-track {
        display: flex;
        width: max-content;
        transition: transform 0.1s ease-out;
    }

    .home-page .stores-carousel-wrap.dragging .stores-carousel-track {
        transition: none;
    }

    .home-page .stores-carousel {
        display: flex;
        align-items: flex-start;
        gap: 1.75rem;
        padding: 0.5rem 0.75rem 0.5rem 0;
    }

    .home-page .store-carousel-item {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: inherit;
        width: 100px;
        transition: transform 0.25s ease;
    }

    .home-page .store-carousel-item:hover {
        transform: translateY(-4px);
    }

    .home-page .store-carousel-img-wrap {
        width: 78px;
        height: 78px;
        border-radius: 18px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        box-shadow: 0 10px 24px -14px rgba(0, 0, 0, 0.4);
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }

    .home-page .store-carousel-item:hover .store-carousel-img-wrap {
        border-color: rgba(124, 58, 237, 0.4);
        box-shadow: 0 14px 30px -12px rgba(124, 58, 237, 0.25);
        transform: scale(1.03);
    }

    .home-page .store-carousel-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }

    .home-page .store-carousel-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
        line-height: 1.25;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* ── Coupons ── */
    .home-page .hp-coupons {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .home-page .coupon-card.is-coupon-hidden {
        display: none;
    }

    .home-page .hp-coupons-load-more-wrap {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .home-page .hp-coupons-load-more {
        border: 1px solid rgba(124, 58, 237, 0.3);
        cursor: pointer;
        padding: 0.7rem 1.75rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.875rem;
        letter-spacing: 0.02em;
        color: #000000;
        background: transparent;
        transition: all 0.2s;
    }

    .home-page .hp-coupons-load-more:hover {
        background: rgba(124, 58, 237, 0.1);
        border-color: var(--hp-accent);
        transform: translateY(-1px);
    }

    .home-page .hp-coupons-load-more[hidden] {
        display: none;
    }

    .home-page .coupon-card {
        display: grid;
        grid-template-columns: 68px 1fr;
        background: #ffffff;
        border: 1px solid rgba(124, 58, 237, 0.18);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--hp-shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
        animation: hpFadeUp 0.55s ease backwards;
    }

    .home-page .coupon-card:hover {
        transform: translateY(-3px);
        border-color: rgba(124, 58, 237, 0.4);
        box-shadow: var(--hp-shadow);
    }

    .home-page .coupon-card-strip {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.7rem 0.3rem;
        background: linear-gradient(180deg, var(--hp-accent-darker) 0%, var(--hp-accent-deep) 50%, var(--hp-accent) 100%);
        border-right: 2px dashed rgba(124, 58, 237, 0.4);
    }

    .home-page .coupon-card-strip::before,
    .home-page .coupon-card-strip::after {
        content: '';
        position: absolute;
        right: -7px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border: 1px solid rgba(124, 58, 237, 0.18);
        border-radius: 50%;
        z-index: 1;
    }

    .home-page .coupon-card-strip::before { top: -7px; border-bottom-color: transparent; }
    .home-page .coupon-card-strip::after { bottom: -7px; border-top-color: transparent; }

    .home-page .coupon-card-strip-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 800;
        line-height: 1;
    }

    .home-page .coupon-card-strip-label {
        font-size: 0.55rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #fff;
        text-align: center;
        line-height: 1.2;
    }

    .home-page .coupon-card-main {
        padding: 0.9rem 0.95rem 1rem;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .home-page .coupon-card-header {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        margin-bottom: 0.35rem;
    }

    .home-page .coupon-card-logo {
        width: 38px;
        height: 38px;
        object-fit: contain;
        border-radius: 10px;
        background: #f8fafc;
        padding: 4px;
        border: 1px solid rgba(124, 58, 237, 0.12);
        flex-shrink: 0;
    }

    .home-page .coupon-card-brand {
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--hp-ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .home-page .coupon-card-offer {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin: 0 0 0.65rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .home-page .coupon-card-actions {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: auto;
    }

    .home-page .coupon-card-code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        flex: 1;
        min-width: 0;
        padding: 0.45rem 0.6rem;
        background: #f8fafc;
        border: 1.5px dashed var(--hp-accent);
        border-radius: 10px;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--hp-accent-deep);
        cursor: pointer;
        transition: all 0.2s;
        font-family: ui-monospace, monospace;
    }

    .home-page .coupon-card-code:hover {
        background: rgba(124, 58, 237, 0.08);
        border-color: var(--hp-accent-deep);
    }

    .home-page .coupon-card-code.copied {
        background: rgba(124, 58, 237, 0.12);
        border-color: var(--hp-accent);
        color: var(--hp-accent-deep);
    }

    .home-page .coupon-card-code-label {
        font-size: 0.58rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.8;
        flex-shrink: 0;
    }

    .home-page .coupon-card-code-value {
        letter-spacing: 0.02em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .home-page .coupon-card-code-copy {
        font-size: 0.58rem;
        opacity: 0.75;
        flex-shrink: 0;
        margin-left: auto;
    }

    .home-page .coupon-card-code.copied .coupon-card-code-copy {
        display: none;
    }

    .home-page .coupon-card-code.copied::after {
        content: '✓';
        margin-left: 0.2rem;
        color: var(--hp-accent);
        flex-shrink: 0;
    }

    .home-page .coupon-card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 0.9rem;
        background: var(--hp-accent);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 14px -6px rgba(124, 58, 237, 0.5);
        flex-shrink: 0;
    }

    .home-page .coupon-card-cta:hover {
        background: var(--hp-accent-deep);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -6px rgba(124, 58, 237, 0.45);
        color: #fff;
    }

    .home-page .coupon-card--no-code .coupon-card-cta {
        flex: 1;
    }

    /* ── Categories ── */
    .home-page .hp-cats {
        padding: clamp(2.75rem, 5vw, 3.75rem) 0;
        background: var(--hp-cats-bg);
        border-top: none;
        position: relative;
        overflow: hidden;
    }

    .home-page .hp-cats::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 15% 20%, rgba(124, 58, 237, 0.12) 0%, transparent 60%),
            radial-gradient(ellipse 50% 45% at 90% 80%, rgba(124, 58, 237, 0.08) 0%, transparent 55%);
        pointer-events: none;
    }

    .home-page .hp-cats .hp-shell {
        position: relative;
        z-index: 1;
    }

    .home-page .hp-cats .hp-sec-head {
        max-width: 760px;
    }

    .home-page .hp-cats .hp-sec-head::before {
        background: linear-gradient(180deg, #a78bfa, #7c3aed);
    }

    .home-page .hp-cats .hp-sec-eyebrow {
        color: var(--hp-accent);
    }

    .home-page .hp-cats .hp-sec-title {
        color: #ffffff;
    }

    .home-page .hp-cats .hp-sec-desc {
        color: rgba(255, 255, 255, 0.62);
    }

    .home-page .hp-cat-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem 0.65rem;
        justify-content: flex-start;
    }

    @media (min-width: 720px) {
        .home-page .hp-cat-row {
            justify-content: center;
        }
    }

    .home-page .hp-cat-pill {
        display: inline-block;
        padding: 0.6rem 1.15rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.88);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(124, 58, 237, 0.35);
        transition: all 0.2s;
    }

    .home-page .hp-cat-pill:hover {
        background: var(--hp-accent);
        color: #fff;
        border-color: var(--hp-accent);
        box-shadow: 0 8px 22px -8px rgba(124, 58, 237, 0.55);
    }

    /* ── Empty state ── */
    .home-page .hp-empty {
        text-align: center;
        padding: 3rem 1.25rem;
        border-radius: var(--hp-radius-xl);
        border: 1px dashed var(--hp-line);
        background: var(--hp-surface);
        color: var(--hp-muted);
        box-shadow: var(--hp-shadow-sm);
    }

    .home-page .hp-empty svg {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        opacity: 0.35;
        color: var(--hp-accent);
    }

    .home-page .hp-empty h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--hp-ink);
        margin-bottom: 0.5rem;
    }

    /* ── Animation ── */
    @keyframes hpFadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .home-page .coupon-card:nth-child(1) { animation-delay: 0.04s; }
    .home-page .coupon-card:nth-child(2) { animation-delay: 0.1s; }
    .home-page .coupon-card:nth-child(3) { animation-delay: 0.16s; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .home-page .hp-search {
            border-radius: 18px;
        }

        .home-page .hp-search button {
            width: 100%;
        }

        .home-page .stores-carousel {
            gap: 1.25rem;
        }

        .home-page .store-carousel-item {
            width: 88px;
        }

        .home-page .store-carousel-img-wrap {
            width: 70px;
            height: 70px;
        }

        .home-page .hp-coupons {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }
    }

    @media (max-width: 520px) {
        .home-page .coupon-card {
            grid-template-columns: 1fr;
        }

        .home-page .coupon-card-main {
            padding: 0.75rem 0.65rem 0.8rem;
        }

        .home-page .coupon-card-header {
            gap: 0.45rem;
            margin-bottom: 0.3rem;
        }

        .home-page .coupon-card-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            padding: 3px;
        }

        .home-page .coupon-card-brand {
            font-size: 0.78rem;
        }

        .home-page .coupon-card-offer {
            font-size: 0.75rem;
            margin-bottom: 0.55rem;
        }

        .home-page .coupon-card-strip-icon {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .home-page .coupon-card-strip-label {
            font-size: 0.52rem;
        }

        .home-page .coupon-card-cta {
            padding: 0.45rem 0.65rem;
            font-size: 0.62rem;
        }

        .home-page .coupon-card-code {
            padding: 0.4rem 0.5rem;
            font-size: 0.68rem;
        }

        .home-page .coupon-card-strip {
            flex-direction: row;
            border-right: none;
            border-bottom: 2px dashed rgba(124, 58, 237, 0.4);
            padding: 0.6rem 1rem;
        }

        .home-page .coupon-card-strip::before,
        .home-page .coupon-card-strip::after {
            top: auto;
            bottom: -7px;
            right: auto;
        }

        .home-page .coupon-card-strip::before {
            left: -7px;
            border-bottom-color: transparent;
            border-right-color: transparent;
        }

        .home-page .coupon-card-strip::after {
            right: -7px;
            left: auto;
            border-bottom-color: transparent;
            border-left-color: transparent;
        }

        .home-page .coupon-card-actions {
            flex-direction: column;
        }

        .home-page .coupon-card-cta {
            width: 100%;
        }
    }
</style>
