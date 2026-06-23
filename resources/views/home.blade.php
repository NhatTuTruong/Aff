@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
@include('partials.peel-sticker-styles')
<style>
    body:has(.home-page) { background: #f0faf8; }
    .home-page {
        --hp-ink: #0f172a;
        --hp-muted: #64748b;
        --hp-line: rgba(15, 23, 42, 0.08);
        --hp-accent: #2fc2a9;
        --hp-accent-deep: #24a892;
        --hp-accent-darker: #1e9680;
        --hp-violet: #2fc2a9;
        --hp-violet-deep: #24a892;
        --hp-rose: #2fc2a9;
        --hp-rose-deep: #1e9680;
        --hp-surface: #ffffff;
        --hp-cream: #f0faf8;
        --hp-glow: radial-gradient(120% 80% at 80% 0%, rgba(47, 194, 169, 0.18) 0%, transparent 58%),
            radial-gradient(90% 60% at 10% 100%, rgba(47, 194, 169, 0.12) 0%, transparent 55%);
        background: #f0faf8;
        background-image: var(--hp-glow);
        color: var(--hp-ink);
    }
    .home-page .hp-shell {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .hp-hero {
        position: relative;
        padding: clamp(2.5rem, 6vw, 4.25rem) 0 clamp(2rem, 4vw, 3rem);
        overflow: hidden;
        border-bottom: 1px solid rgba(47, 194, 169, 0.12);
    }
    .hp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(80% 90% at 8% 0%, rgba(47, 194, 169, 0.2) 0%, transparent 78%),
            radial-gradient(68% 78% at 92% 12%, rgba(47, 194, 169, 0.14) 0%, transparent 80%),
            radial-gradient(55% 65% at 50% 85%, rgba(47, 194, 169, 0.08) 0%, transparent 70%),
            linear-gradient(160deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 250, 248, 0.92) 100%);
        pointer-events: none;
    }
    .hp-hero::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, #2fc2a9 0%, #24a892 50%, #1e9680 100%);
        z-index: 2;
    }
    .hp-hero-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.25rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .hp-hero-grid {
            grid-template-columns: 1.05fr 0.95fr;
            gap: 3rem;
        }
    }
    .hp-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 1rem;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #2fc2a9 0%, #24a892 100%);
        border: none;
        box-shadow: 0 6px 18px -8px rgba(47, 194, 169, 0.55);
    }
    .hp-kicker::before {
        content: '';
        width: 2rem;
        height: 2px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 2px;
    }
    .hp-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.15rem, 5vw, 3.45rem);
        font-weight: 700;
        letter-spacing: -0.045em;
        line-height: 1.05;
        color: var(--hp-ink);
        margin: 0 0 1.1rem;
        max-width: 18ch;
    }
    .hp-hero-lead {
        font-size: clamp(1rem, 1.35vw, 1.125rem);
        color: var(--hp-muted);
        line-height: 1.65;
        max-width: 38rem;
        margin: 0 0 1.25rem;
    }
    .hp-trust {
        font-size: 0.8125rem;
        color: var(--hp-muted);
        margin-bottom: 1.75rem;
        max-width: 36rem;
    }
    .hp-hero-accent {
        color: var(--hp-accent);
    }
    .hp-trust a {
        color: var(--hp-violet);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .hp-trust a:hover { color: var(--hp-violet-deep); }

    .hp-search {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.86);
        border: 1px solid rgba(148, 163, 184, 0.34);
        border-radius: 999px;
        box-shadow: 0 16px 34px -24px rgba(15, 23, 42, 0.45);
        max-width: 520px;
        backdrop-filter: blur(9px);
    }
    .hp-search:focus-within {
        border-color: rgba(47, 194, 169, 0.55);
        box-shadow: 0 18px 40px -24px rgba(47, 194, 169, 0.28);
    }
    .hp-search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.85rem 1rem 0.85rem 1.15rem;
        font-size: 1rem;
        color: var(--hp-ink);
        outline: none;
    }
    .hp-search input::placeholder { color: #9ca3af; }
    .hp-search button {
        border: none;
        cursor: pointer;
        padding: 0.85rem 1.35rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.8125rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, #2fc2a9 0%, #24a892 100%);
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 10px 24px -10px rgba(47, 194, 169, 0.5);
    }
    .hp-search button:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px -8px rgba(47, 194, 169, 0.45);
    }

    .hp-hero-aside {
        position: relative;
        min-height: 220px;
        border-radius: 24px;
        background:
            rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(16px);
        padding: 1.75rem;
        color: var(--hp-ink);
        overflow: hidden;
        border: 1px solid rgba(47, 194, 169, 0.28);
        box-shadow: 0 26px 44px -24px rgba(47, 194, 169, 0.25);
    }
    .hp-hero-aside::after {
        content: '';
        position: absolute;
        right: -20%;
        top: -30%;
        width: 70%;
        height: 90%;
        background: radial-gradient(circle, rgba(47, 194, 169, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hp-aside-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--hp-accent-deep);
        opacity: 0.95;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }
    .hp-aside-stat {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.25rem, 4vw, 2.85rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 0.35rem;
        position: relative;
        z-index: 1;
    }
    .hp-aside-caption {
        font-size: 0.9rem;
        color: var(--hp-muted);
        max-width: 16rem;
        line-height: 1.45;
        position: relative;
        z-index: 1;
    }
    .hp-aside-slider {
        position: relative;
        z-index: 1;
        min-height: 200px;
        overflow: hidden;
    }
    .hp-aside-track {
        display: flex;
        width: 100%;
        transition: transform 0.45s ease;
        will-change: transform;
    }
    .hp-aside-slide {
        flex: 0 0 100%;
        min-width: 0;
        box-sizing: border-box;
    }
    .hp-aside-dots {
        position: absolute;
        bottom: 1.25rem;
        right: 1.25rem;
        display: flex;
        gap: 0.4rem;
        z-index: 2;
        align-items: center;
    }
    .hp-aside-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        padding: 0;
        border: none;
        cursor: pointer;
        background: rgba(47, 194, 169, 0.25);
        transition: transform 0.2s, background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .hp-aside-dot:hover {
        background: rgba(47, 194, 169, 0.45);
    }
    .hp-aside-dot.is-active {
        background: #2fc2a9;
        transform: scale(1.2);
    }

    .hp-stats {
        padding: 2.5rem 0 2.5rem;
        position: relative;
        z-index: 1;
    }
    .hp-stats-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .hp-stats-row { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .hp-stat-card {
        background: #ffffff;
        border: 1px solid rgba(47, 194, 169, 0.15);
        border-left: 4px solid #2fc2a9;
        border-radius: 18px;
        padding: 1.1rem 1rem;
        text-align: left;
        box-shadow: 0 12px 32px -22px rgba(12, 10, 18, 0.2);
    }
    .hp-stat-num {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--hp-accent-deep);
    }
    .hp-stat-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--hp-muted);
        margin-top: 0.35rem;
    }

    .hp-section {
        padding: clamp(2.5rem, 5vw, 3.75rem) 0;
    }
    .hp-section--tint {
        background: var(--hp-surface);
        border-top: 1px solid var(--hp-line);
        border-bottom: 1px solid var(--hp-line);
    }
    .hp-sec-head {
        margin-bottom: 1.75rem;
        max-width: 40rem;
    }
    .hp-sec-eyebrow {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--hp-accent);
        margin-bottom: 0.4rem;
    }
    .hp-sec-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.45rem, 3vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.035em;
        color: var(--hp-ink);
        margin: 0 0 0.5rem;
    }
    .hp-sec-desc {
        margin: 0;
        color: var(--hp-muted);
        font-size: 1rem;
        line-height: 1.55;
    }
    .hp-disclaimer {
        font-size: 0.8125rem;
        color: var(--hp-muted);
        margin: -0.25rem 0 1.5rem;
        padding: 0.85rem 1rem;
        border-radius: 14px;
        border: 1px dashed var(--hp-line);
        background: rgba(47, 194, 169, 0.06);
        max-width: 720px;
    }
    .hp-disclaimer a {
        color: var(--hp-violet);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    #coupons, #stores, #blog, #categories { scroll-margin-top: 5rem; }

    .hp-posts {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.1rem;
    }
    .hp-post-card {
        display: flex;
        flex-direction: column;
        border-radius: 20px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        box-shadow: 0 16px 40px -30px rgba(11, 23, 36, 0.3);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.2s;
    }
    .hp-post-card:hover {
        transform: translateY(-4px);
        border-color: rgba(47, 194, 169, 0.3);
        box-shadow: 0 24px 50px -28px rgba(47, 194, 169, 0.2);
    }
    .hp-post-media {
        aspect-ratio: 16 / 10;
        background: #e0f2fe;
        overflow: hidden;
    }
    .hp-post-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hp-post-body {
        padding: 1.15rem 1.2rem 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .hp-post-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0 0 0.5rem;
        color: var(--hp-ink);
    }
    .hp-post-meta {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin-top: auto;
    }
    .hp-post-link {
        margin-top: 0.65rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--hp-violet);
    }
    .hp-post-card:hover .hp-post-link { color: var(--hp-rose); }
    .hp-all-posts {
        margin-top: 1.5rem;
    }
    .hp-all-posts a {
        font-weight: 700;
        color: var(--hp-violet);
        text-decoration: none;
        border-bottom: 2px solid rgba(47, 194, 169, 0.35);
        padding-bottom: 2px;
    }
    .hp-all-posts a:hover { color: var(--hp-accent-deep); border-bottom-color: rgba(47, 194, 169, 0.5); }

    .hp-stores-panel {
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        border-radius: 24px;
        padding: 1.5rem 1rem 1.35rem;
        box-shadow: 0 20px 50px -32px rgba(12, 10, 18, 0.25);
    }
    .stores-carousel-wrap {
        overflow: hidden;
        margin: 0 -0.25rem;
        padding: 0 0.25rem;
        cursor: grab;
        user-select: none;
    }
    .stores-carousel-wrap:active { cursor: grabbing; }
    .stores-carousel-track {
        display: flex;
        width: max-content;
        transition: transform 0.1s ease-out;
    }
    .stores-carousel-wrap.dragging .stores-carousel-track { transition: none; }
    .stores-carousel {
        display: flex;
        align-items: flex-start;
        gap: 1.75rem;
        padding: 0.5rem 1rem 0.5rem 0;
    }
    .store-carousel-item {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: inherit;
        width: 100px;
        transition: transform 0.2s ease;
    }
    .store-carousel-item:hover { transform: translateY(-4px); }
    .store-carousel-img-wrap {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        overflow: hidden;
        background: linear-gradient(180deg, #ecfeff 0%, #e0f2fe 100%);
        border: 1px solid var(--hp-line);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.55rem;
        box-shadow: 0 10px 24px -14px rgba(47, 194, 169, 0.25);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .store-carousel-item:hover .store-carousel-img-wrap {
        border-color: rgba(47, 194, 169, 0.45);
        box-shadow: 0 14px 28px -12px rgba(47, 194, 169, 0.22);
    }
    .store-carousel-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .store-carousel-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: #433d4d;
        text-align: center;
        line-height: 1.25;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .hp-coupons {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    .coupon-card {
        display: grid;
        grid-template-columns: 68px 1fr;
        background: #ffffff;
        border: 1px solid rgba(47, 194, 169, 0.2);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 24px -10px rgba(47, 194, 169, 0.16);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
        animation: hpFadeUp 0.55s ease backwards;
    }
    .coupon-card:hover {
        transform: translateY(-3px);
        border-color: rgba(47, 194, 169, 0.42);
        box-shadow: 0 14px 36px -12px rgba(47, 194, 169, 0.24);
    }
    .coupon-card-strip {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.75rem 0.35rem;
        background: linear-gradient(180deg, #1e9680 0%, #24a892 50%, #2fc2a9 100%);
        border-right: 2px dashed rgba(47, 194, 169, 0.45);
    }
    .coupon-card-strip::before,
    .coupon-card-strip::after {
        content: '';
        position: absolute;
        right: -7px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border: 1px solid rgba(47, 194, 169, 0.2);
        border-radius: 50%;
        z-index: 1;
    }
    .coupon-card-strip::before { top: -7px; border-bottom-color: transparent; }
    .coupon-card-strip::after { bottom: -7px; border-top-color: transparent; }
    .coupon-card-strip-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--hp-accent);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 800;
        line-height: 1;
        box-shadow: 0 4px 12px -4px rgba(47, 194, 169, 0.6);
    }
    .coupon-card-strip-label {
        font-size: 0.58rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #fff;
        text-align: center;
        line-height: 1.2;
    }
    .coupon-card-main {
        padding: 0.95rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .coupon-card-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.4rem;
    }
    .coupon-card-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 10px;
        background: #f5fdfb;
        padding: 4px;
        border: 1px solid rgba(47, 194, 169, 0.15);
        flex-shrink: 0;
    }
    .coupon-card-brand {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--hp-ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coupon-card-offer {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--hp-muted);
        margin: 0 0 0.7rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .coupon-card-actions {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: auto;
    }
    .coupon-card-code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        flex: 1;
        min-width: 0;
        padding: 0.48rem 0.65rem;
        background: #f5fdfb;
        border: 1.5px dashed var(--hp-accent);
        border-radius: 10px;
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--hp-accent-deep);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-family: ui-monospace, monospace;
    }
    .coupon-card-code:hover {
        background: #e0f2fe;
        border-color: var(--hp-accent-deep);
    }
    .coupon-card-code.copied {
        background: #e0f2fe;
        border-color: var(--hp-accent);
        color: var(--hp-accent-deep);
    }
    .coupon-card-code-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
        flex-shrink: 0;
    }
    .coupon-card-code-value {
        letter-spacing: 0.02em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coupon-card-code-copy {
        font-size: 0.6rem;
        opacity: 0.8;
        flex-shrink: 0;
        margin-left: auto;
    }
    .coupon-card-code.copied .coupon-card-code-copy { display: none; }
    .coupon-card-code.copied::after {
        content: '✓';
        margin-left: 0.2rem;
        color: var(--hp-accent);
        flex-shrink: 0;
    }
    .coupon-card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.95rem;
        background: var(--hp-accent);
        color: #fff;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        border-radius: 10px;
        text-decoration: none;
        transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 14px -6px rgba(47, 194, 169, 0.55);
        flex-shrink: 0;
    }
    .coupon-card-cta:hover {
        transform: translateY(-1px);
        background: var(--hp-accent-deep);
        box-shadow: 0 8px 18px -6px rgba(47, 194, 169, 0.45);
        color: #fff;
    }
    .coupon-card--no-code .coupon-card-cta {
        flex: 1;
    }

    .hp-cats {
        padding: clamp(2.75rem, 5vw, 3.75rem) 0;
        background:
            radial-gradient(80% 90% at 8% 10%, rgba(47, 194, 169, 0.1) 0%, transparent 58%),
            radial-gradient(70% 90% at 92% 88%, rgba(47, 194, 169, 0.08) 0%, transparent 55%),
            linear-gradient(140deg, #ffffff 0%, #f5fdfb 48%, #f0faf8 100%);
        border-top: 1px solid rgba(47, 194, 169, 0.15);
        position: relative;
        overflow: hidden;
    }
    .hp-cats::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            repeating-linear-gradient(-18deg, transparent, transparent 42px, rgba(47, 194, 169, 0.04) 42px, rgba(47, 194, 169, 0.04) 43px);
        pointer-events: none;
    }
    .hp-cats .hp-shell { position: relative; z-index: 1; }
    .hp-cats .hp-sec-head { max-width: 760px; }
    .hp-cats .hp-sec-eyebrow { color: var(--hp-accent); }
    .hp-cats .hp-sec-title { color: var(--hp-ink); }
    .hp-cats .hp-sec-desc { color: var(--hp-muted); }
    .hp-cat-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem 0.65rem;
        justify-content: flex-start;
    }
    @media (min-width: 720px) {
        .hp-cat-row { justify-content: center; }
    }
    .hp-cat-pill {
        display: inline-block;
        padding: 0.6rem 1.15rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 700;
        color: var(--hp-ink);
        background: #ffffff;
        border: 1px solid rgba(47, 194, 169, 0.35);
        backdrop-filter: blur(8px);
        box-shadow: 0 8px 22px -14px rgba(47, 194, 169, 0.15);
        transition: background 0.2s, border-color 0.2s, transform 0.2s, color 0.2s;
    }
    .hp-cat-pill:hover {
        background: var(--hp-accent);
        color: #ffffff;
        border-color: var(--hp-accent);
        transform: translateY(-2px);
    }

    .hp-empty {
        text-align: center;
        padding: 3rem 1.25rem;
        border-radius: 22px;
        border: 1px dashed var(--hp-line);
        background: rgba(255, 255, 255, 0.7);
        color: var(--hp-muted);
    }
    .hp-empty svg {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        opacity: 0.4;
        color: #2fc2a9;
    }
    .hp-empty h3 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--hp-ink);
        margin-bottom: 0.5rem;
    }

    @keyframes hpFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .coupon-card:nth-child(1) { animation-delay: 0.04s; }
    .coupon-card:nth-child(2) { animation-delay: 0.1s; }
    .coupon-card:nth-child(3) { animation-delay: 0.16s; }

    @media (max-width: 768px) {
        .hp-search { border-radius: 18px; }
        .hp-search button { width: 100%; }
        .stores-carousel { gap: 1.25rem; }
        .store-carousel-item { width: 88px; }
        .store-carousel-img-wrap { width: 72px; height: 72px; }
        .hp-coupons { grid-template-columns: 1fr; }
    }
    @media (max-width: 520px) {
        .coupon-card {
            grid-template-columns: 1fr;
        }
        .coupon-card-strip {
            flex-direction: row;
            border-right: none;
            border-bottom: 2px dashed rgba(47, 194, 169, 0.45);
            padding: 0.65rem 1rem;
        }
        .coupon-card-strip::before,
        .coupon-card-strip::after {
            top: auto;
            bottom: -7px;
            right: auto;
        }
        .coupon-card-strip::before { left: -7px; border-bottom-color: transparent; border-right-color: transparent; }
        .coupon-card-strip::after { right: -7px; left: auto; border-bottom-color: transparent; border-left-color: transparent; }
        .coupon-card-actions {
            flex-direction: column;
        }
        .coupon-card-cta {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="home-page">
    <section class="hp-hero">
        <div class="hp-shell hp-hero-grid">
            <div>
                <p class="hp-kicker">Deals you can trust</p>
                <h1 class="font-heading">Save smarter with <span class="hp-hero-accent">curated coupons</span> &amp; honest store picks</h1>
                <p class="hp-hero-lead">Search verified promotions, explore top stores, and read updates from our blog — refreshed often so you never miss a strong offer.</p>
                <p class="hp-trust">We are an independent deal finder. We may earn from qualifying purchases. <a href="{{ url('/affiliate-disclosure') }}">Read our disclosure</a>.</p>
                <form action="{{ url('/') }}" method="get" class="hp-search">
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search brands, stores, or offers…" autocomplete="off">
                    <button type="submit">Search</button>
                </form>
            </div>
            <aside class="hp-hero-aside" aria-label="Site highlights">
                <div class="hp-aside-slider" id="hp-hero-aside-slider">
                    <div class="hp-aside-track" id="hp-aside-track">
                        <div class="hp-aside-slide">
                            <p class="hp-aside-label">Why shoppers stay</p>
                            <p class="hp-aside-stat">Curated</p>
                            <p class="hp-aside-caption">Human-reviewed paths to real savings — fewer dead codes, clearer next steps.</p>
                        </div>
                        <div class="hp-aside-slide">
                            <p class="hp-aside-label">Always in motion</p>
                            <p class="hp-aside-stat">Updated</p>
                            <p class="hp-aside-caption">We refresh offers and landing details often so you see what still works — not yesterday’s leftovers.</p>
                        </div>
                        <div class="hp-aside-slide">
                            <p class="hp-aside-label">Built for trust</p>
                            <p class="hp-aside-stat">Verified</p>
                            <p class="hp-aside-caption">Clear affiliate disclosure, honest pros &amp; cons on store pages, and CTAs that take you straight to the deal.</p>
                        </div>
                    </div>
                    <nav class="hp-aside-dots" id="hp-aside-dots" aria-label="Highlight slides">
                        <button type="button" class="hp-aside-dot is-active" aria-label="Slide 1" aria-current="true" data-slide="0"></button>
                        <button type="button" class="hp-aside-dot" aria-label="Slide 2" data-slide="1"></button>
                        <button type="button" class="hp-aside-dot" aria-label="Slide 3" data-slide="2"></button>
                    </nav>
                </div>
            </aside>
        </div>
    </section>

    @push('scripts')
    <script>
    (function () {
        var slider = document.getElementById('hp-hero-aside-slider');
        var track = document.getElementById('hp-aside-track');
        var dotsWrap = document.getElementById('hp-aside-dots');
        if (!slider || !track || !dotsWrap) return;

        var dots = dotsWrap.querySelectorAll('.hp-aside-dot');
        var n = dots.length;
        if (n === 0) return;

        var i = 0;
        var timer = null;
        var delay = 3000;

        function setActive() {
            track.style.transform = 'translateX(' + (-i * 100) + '%)';
            dots.forEach(function (d, j) {
                var on = j === i;
                d.classList.toggle('is-active', on);
                d.setAttribute('aria-current', on ? 'true' : 'false');
            });
        }

        function go(to) {
            i = (to % n + n) % n;
            setActive();
        }

        function start() {
            stop();
            timer = setInterval(function () {
                if (document.hidden) return;
                if (slider.matches(':hover')) return;
                go(i + 1);
            }, delay);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        dots.forEach(function (d) {
            d.addEventListener('click', function () {
                var idx = parseInt(d.getAttribute('data-slide') || '0', 10);
                if (!isNaN(idx)) {
                    go(idx);
                    stop();
                    start();
                }
            });
        });

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stop();
            else start();
        });

        setActive();
        start();
    })();
    </script>
    @endpush

    @if(($verifiedBrandsCount ?? 0) > 0 || $hotCoupons->isNotEmpty())
    <section class="hp-stats">
        <div class="hp-shell">
            <div class="hp-stats-row">
                <div class="hp-stat-card">
                    <div class="hp-stat-num">{{ $verifiedBrandsCount ?? 0 }}+</div>
                    <div class="hp-stat-label">Verified brands</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">{{ $activeCouponsCount ?? $hotCoupons->count() }}+</div>
                    <div class="hp-stat-label">Active coupons</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">Editorial</div>
                    <div class="hp-stat-label">Guides &amp; picks</div>
                </div>
                <div class="hp-stat-card">
                    <div class="hp-stat-num">Daily</div>
                    <div class="hp-stat-label">Fresh checks</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($hotCoupons->isNotEmpty())
    <section class="hp-section hp-section--tint" id="coupons">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Limited windows</p>
                <h2 class="hp-sec-title">Hot coupons &amp; standout deals</h2>
                <p class="hp-sec-desc">High-signal picks from brands we track — copy a code or open the offer in one tap.</p>
            </header>
            <p class="hp-disclaimer">Promotions can change or expire at any time. Always confirm at checkout. We may earn a commission when you use our links — <a href="{{ url('/affiliate-disclosure') }}">see disclosure</a>.</p>
            <div class="hp-coupons">
                @foreach($hotCoupons as $coupon)
                    @php $campaign = $coupon->campaign; $brand = $campaign?->brand; @endphp
                    @if($brand)
                    <article class="coupon-card{{ $coupon->code ? '' : ' coupon-card--no-code' }}">
                        <div class="coupon-card-strip" aria-hidden="true">
                            <span class="coupon-card-strip-icon">%</span>
                            <span class="coupon-card-strip-label">{{ $coupon->code ? 'Code' : 'Deal' }}</span>
                        </div>
                        <div class="coupon-card-main">
                            <div class="coupon-card-header">
                                <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" class="coupon-card-logo" loading="lazy">
                                <div class="coupon-card-brand">{{ $brand->name }}</div>
                            </div>
                            @if($coupon->offer)
                                <p class="coupon-card-offer">{{ $coupon->offer }}</p>
                            @endif
                            <div class="coupon-card-actions">
                                @if($coupon->code)
                                    <button type="button" class="coupon-card-code" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 1200);" title="Click to copy">
                                        <span class="coupon-card-code-label">Code</span>
                                        <span class="coupon-card-code-value">{{ $coupon->code }}</span>
                                        <span class="coupon-card-code-copy">Copy</span>
                                    </button>
                                @endif
                                @if($campaign && $campaign->affiliate_url)
                                    <a href="{{ route('click.redirect', ['slug' => $campaign->slug]) }}" class="coupon-card-cta" target="_blank" rel="noopener">Get deal</a>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="hp-section hp-section--tint" id="blog">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Editorial</p>
                <h2 class="hp-sec-title">Latest from the blog</h2>
                <p class="hp-sec-desc">Short reads on saving tactics, store notes, and what changed this week.</p>
            </header>
            <div class="hp-posts">
                @foreach($latestPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="hp-post-card">
                        <div class="hp-post-media">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                        </div>
                        <div class="hp-post-body">
                            <h3 class="hp-post-title">{{ $post->title }}</h3>
                            <p class="hp-post-meta">{{ $post->created_at?->format('d M Y') }}</p>
                            <span class="hp-post-link">Open story</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="hp-all-posts"><a href="{{ route('blog.index') }}">Browse the full archive</a></p>
        </div>
    </section>
    @endif

    <section class="hp-section" id="stores">
        <div class="hp-shell">
            <header class="hp-sec-head">
                <p class="hp-sec-eyebrow">Stores in focus</p>
                <h2 class="hp-sec-title">Featured destinations</h2>
                <p class="hp-sec-desc">Tap a logo to jump straight into coupons and campaign details for that brand.</p>
            </header>
            @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
                <div class="hp-stores-panel">
                <div class="stores-carousel-wrap">
                    <div class="stores-carousel-track">
                        <div class="stores-carousel">
                            @foreach($featuredCampaigns as $campaign)
                                @php
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $reviewUrl = route('landing.show', ['slug' => $reviewSlug]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand)
                                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                        @endif
                                    </span>
                                    <span class="store-carousel-name">
                                        {{ $brand?->name ?? $campaign->title }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                        <div class="stores-carousel">
                            @foreach($featuredCampaigns as $campaign)
                                @php
                                    $brand = $campaign->brand;
                                    $reviewSlug = $campaign->slug;
                                    if ($reviewSlug) {
                                        $reviewUrl = route('landing.show', ['slug' => $reviewSlug]);
                                    } else {
                                        $reviewUrl = url('/') . '?q=' . urlencode($brand?->name ?? $campaign->title);
                                    }
                                @endphp
                                <a href="{{ $reviewUrl }}" class="store-carousel-item" title="{{ $campaign->title }}">
                                    <span class="store-carousel-img-wrap">
                                        @if($brand)
                                            <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $campaign->title }}" loading="lazy">
                                        @endif
                                    </span>
                                    <span class="store-carousel-name">
                                        {{ $brand?->name ?? $campaign->title }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
            @else
                <div class="hp-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3>No campaigns yet</h3>
                    <p>Check back soon — new partner stores and offers land here first.</p>
                </div>
            @endif
        </div>
    </section>

    @if(isset($popularCategories) && $popularCategories->isNotEmpty())
    <section class="hp-cats" id="categories">
        <div class="hp-shell">
            <header class="hp-sec-head" style="text-align:center;margin-left:auto;margin-right:auto;">
                <p class="hp-sec-eyebrow">Topics</p>
                <h2 class="hp-sec-title">Browse by category</h2>
                <p class="hp-sec-desc" style="margin-left:auto;margin-right:auto;">Jump into the verticals we cover most — each link filters the featured strip.</p>
            </header>
            <div class="hp-cat-row">
                @foreach($popularCategories as $cat)
                    @php
                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : ($cat['slug'] ?? '');
                        $url = $catSlug ? url('/?cat=' . $catSlug) . '#stores' : url('/') . '#stores';
                    @endphp
                    <a href="{{ $url }}" class="hp-cat-pill">{{ $catName }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($featuredCampaigns) && $featuredCampaigns->count() > 0)
    @push('scripts')
    <script>
    (function() {
        var wrap = document.querySelector('.stores-carousel-wrap');
        var track = document.querySelector('.stores-carousel-track');
        if (!wrap || !track) return;
        var currentTx = 0;
        var startX = 0;
        var startTx = 0;
        var dragging = false;
        var didDrag = false;
        var direction = -1;
        var step = 0.6;
        var autoPlayTimer = null;

        function clamp(x, min, max) { return Math.min(Math.max(x, min), max); }

        function getBounds() {
            var maxTx = 0;
            var minTx = -(track.offsetWidth - wrap.offsetWidth);
            if (minTx > 0) minTx = 0;
            return { minTx: minTx, maxTx: maxTx };
        }

        function applyTransform() {
            track.style.transform = 'translateX(' + currentTx + 'px)';
        }

        function startAutoPlay() {
            if (autoPlayTimer) return;
            autoPlayTimer = setInterval(function() {
                if (dragging) return;
                if (wrap.matches(':hover')) return;
                if (document.hidden) return;

                var bounds = getBounds();
                currentTx += direction * step;
                if (currentTx <= bounds.minTx || currentTx >= bounds.maxTx) {
                    direction *= -1;
                    currentTx = clamp(currentTx, bounds.minTx, bounds.maxTx);
                }
                applyTransform();
            }, 20);
        }

        function stopAutoPlay() {
            if (!autoPlayTimer) return;
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }

        wrap.addEventListener('pointerdown', function(e) {
            dragging = true;
            didDrag = false;
            startX = e.clientX;
            startTx = currentTx;
            wrap.classList.add('dragging');
            stopAutoPlay();
        });
        document.addEventListener('pointermove', function(e) {
            if (!dragging) return;
            var dx = e.clientX - startX;
            if (Math.abs(dx) > 4) didDrag = true;
            e.preventDefault();
            var bounds = getBounds();
            currentTx = clamp(startTx + dx, bounds.minTx, bounds.maxTx);
            applyTransform();
        });
        document.addEventListener('pointerup', function() {
            dragging = false;
            wrap.classList.remove('dragging');
            startAutoPlay();
        });
        document.addEventListener('pointercancel', function() {
            dragging = false;
            wrap.classList.remove('dragging');
            startAutoPlay();
        });
        wrap.addEventListener('click', function(e) {
            if (didDrag) {
                e.preventDefault();
                e.stopPropagation();
                didDrag = false;
            }
        }, true);

        startAutoPlay();
    })();
    </script>
    @endpush
    @endif
</div>
@endsection
