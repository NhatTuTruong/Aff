@extends('layouts.app')

@section('title', config('app.name') . ' - Coupons & Store Reviews')
@section('description', 'Find coupon codes, promotions and trusted store reviews. Updated daily.')

@push('styles')
<style>
    .home-page {
        --hp-ink: #0c0a12;
        --hp-muted: #5c5866;
        --hp-line: rgba(5, 150, 105, 0.12);
        --hp-violet: #059669;
        --hp-violet-deep: #047857;
        --hp-rose: #10b981;
        --hp-rose-deep: #047857;
        --hp-surface: #ffffff;
        --hp-cream: #ecfdf5;
        --hp-glow: radial-gradient(120% 80% at 80% 0%, rgba(5, 150, 105, 0.14) 0%, transparent 55%),
            radial-gradient(90% 60% at 10% 100%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
        background: var(--hp-cream);
        background-image: var(--hp-glow);
        color: var(--hp-ink);
        overflow-x: clip;
    }
    .home-page .hp-shell {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .hp-hero {
        position: relative;
        padding: clamp(1.25rem, 3vw, 2rem) 0 clamp(1.5rem, 3vw, 2.25rem);
    }
    .hp-hero-panel {
        position: relative;
        width: 100%;
        max-width: 100%;
        border-radius: 5px;
        border: 1px solid var(--hp-line);
        background: var(--hp-surface);
        box-shadow: 0 24px 56px -36px rgba(5, 150, 105, 0.28);
        overflow: hidden;
    }
    .hp-hero-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--hp-violet) 0%, var(--hp-rose) 55%, #34d399 100%);
    }
    .hp-hero-panel::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(90% 70% at 100% 0%, rgba(5, 150, 105, 0.08) 0%, transparent 55%),
            radial-gradient(60% 50% at 0% 100%, rgba(16, 185, 129, 0.06) 0%, transparent 50%);
        pointer-events: none;
    }
    .hp-hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 0;
        width: 100%;
        min-width: 0;
    }
    @media (min-width: 900px) {
        .hp-hero-inner {
            grid-template-columns: minmax(0, 1.08fr) minmax(280px, 0.92fr);
        }
    }
    .hp-hero-main {
        padding: clamp(1.5rem, 3vw, 2.25rem);
        min-width: 0;
        max-width: 100%;
    }
    @media (min-width: 900px) {
        .hp-hero-main {
            padding: clamp(1.75rem, 3vw, 2.5rem) 2rem clamp(1.75rem, 3vw, 2.5rem) clamp(1.75rem, 3vw, 2.5rem);
            border-right: 1px solid var(--hp-line);
        }
    }
    .hp-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        margin-bottom: 1rem;
        border-radius: 5px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--hp-violet-deep);
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.18);
    }
    .hp-badge__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--hp-rose);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        animation: hp-pulse 2s ease-in-out infinite;
    }
    @keyframes hp-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.65; transform: scale(0.85); }
    }
    .hp-hero h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.65rem, 4.5vw, 2.85rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1.15;
        color: var(--hp-ink);
        margin: 0 0 0.85rem;
        max-width: 100%;
        overflow-wrap: anywhere;
    }
    @media (min-width: 900px) {
        .hp-hero h1 { max-width: 14ch; overflow-wrap: normal; }
    }
    .hp-hero h1 em {
        font-style: normal;
        color: var(--hp-violet);
    }
    .hp-hero-lead {
        font-size: clamp(0.95rem, 1.2vw, 1.05rem);
        color: var(--hp-muted);
        line-height: 1.6;
        max-width: 100%;
        margin: 0 0 1.15rem;
        overflow-wrap: anywhere;
    }
    @media (min-width: 900px) {
        .hp-hero-lead { max-width: 34rem; overflow-wrap: normal; }
    }
    .hp-hero-actions {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .hp-search {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        padding: 0.35rem;
        background: #f8fafc;
        border: 1px solid var(--hp-line);
        border-radius: 5px;
        width: 100%;
        max-width: 100%;
    }
    @media (min-width: 520px) {
        .hp-search {
            flex-direction: row;
            flex-wrap: wrap;
            max-width: 480px;
        }
    }
    .hp-search:focus-within {
        border-color: rgba(5, 150, 105, 0.4);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    .hp-search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.75rem 0.85rem;
        font-size: 0.98rem;
        color: var(--hp-ink);
        outline: none;
    }
    .hp-search input::placeholder { color: #94a3b8; }
    .hp-search button {
        border: none;
        cursor: pointer;
        padding: 0.75rem 1.15rem;
        border-radius: 5px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #fff;
        background: var(--hp-violet);
        transition: background 0.2s, transform 0.2s;
        width: 100%;
    }
    @media (min-width: 520px) {
        .hp-search button { width: auto; }
    }
    .hp-search button:hover {
        background: var(--hp-violet-deep);
        transform: translateY(-1px);
    }
    .hp-quick {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        align-items: center;
    }
    .hp-quick__label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--hp-muted);
        margin-right: 0.15rem;
    }
    .hp-quick a {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.75rem;
        border-radius: 5px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--hp-ink);
        background: rgba(5, 150, 105, 0.06);
        border: 1px solid var(--hp-line);
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }
    .hp-quick a:hover {
        color: var(--hp-violet-deep);
        border-color: rgba(5, 150, 105, 0.35);
        background: rgba(5, 150, 105, 0.1);
    }
    .hp-hero-foot {
        padding: 0.75rem clamp(1rem, 3vw, 2rem);
        border-top: 1px solid var(--hp-line);
        background: rgba(236, 253, 245, 0.55);
        font-size: 0.78rem;
        color: var(--hp-muted);
        line-height: 1.5;
        overflow-wrap: anywhere;
    }
    .hp-hero-foot a {
        color: var(--hp-violet);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .hp-hero-foot a:hover { color: var(--hp-violet-deep); }

    .hp-hero-aside {
        position: relative;
        padding: clamp(1.25rem, 2.5vw, 1.75rem);
        background: linear-gradient(160deg, #064e3b 0%, #047857 50%, #059669 100%);
        color: #ecfdf5;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
    }
    @media (min-width: 900px) {
        .hp-hero-aside { min-height: 100%; }
    }
    .hp-hero-aside::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.5;
        pointer-events: none;
    }
    .hp-aside-slider {
        position: relative;
        z-index: 1;
        min-height: 168px;
        width: 100%;
        max-width: 100%;
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
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        padding-right: 0;
    }
    .hp-aside-icon {
        flex-shrink: 0;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hp-aside-body { min-width: 0; }
    .hp-aside-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        opacity: 0.7;
        margin: 0 0 0.35rem;
    }
    .hp-aside-stat {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.65rem, 3vw, 2.1rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.1;
        margin: 0 0 0.4rem;
    }
    .hp-aside-caption {
        font-size: 0.86rem;
        opacity: 0.9;
        margin: 0;
        line-height: 1.5;
        max-width: 100%;
        overflow-wrap: anywhere;
    }
    @media (max-width: 899px) {
        .home-page .hp-shell {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .hp-hero-main {
            padding: 1.25rem 1rem;
        }
        .hp-hero-aside {
            padding: 1.25rem 1rem;
        }
    }
    .hp-aside-dots {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 0.35rem;
        align-items: center;
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }
    .hp-aside-dot {
        flex: 1;
        height: 4px;
        border-radius: 5px;
        padding: 0;
        border: none;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.22);
        transition: background 0.25s, transform 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .hp-aside-dot:hover { background: rgba(255, 255, 255, 0.38); }
    .hp-aside-dot.is-active {
        background: #6ee7b7;
        transform: scaleY(1.35);
    }

    .hp-stats {
        padding: 0 0 2.5rem;
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
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        border-radius: 18px;
        padding: 1.1rem 1rem;
        text-align: center;
        box-shadow: 0 12px 32px -22px rgba(12, 10, 18, 0.2);
    }
    .hp-stat-num {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--hp-ink);
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
        color: var(--hp-rose);
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
        background: rgba(5, 150, 105, 0.04);
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
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.1rem;
    }
    @media (max-width: 1100px) {
        .hp-posts { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 560px) {
        .hp-posts { grid-template-columns: 1fr; }
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
        box-shadow: 0 16px 40px -30px rgba(12, 10, 18, 0.35);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.2s;
    }
    .hp-post-card:hover {
        transform: translateY(-4px);
        border-color: rgba(5, 150, 105, 0.22);
        box-shadow: 0 24px 50px -28px rgba(5, 150, 105, 0.28);
    }
    .hp-post-media {
        aspect-ratio: 16 / 10;
        background: #d1fae5;
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
        border-bottom: 2px solid rgba(5, 150, 105, 0.25);
        padding-bottom: 2px;
    }
    .hp-all-posts a:hover { color: var(--hp-rose); border-bottom-color: rgba(16, 185, 129, 0.35); }

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
        background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1px solid var(--hp-line);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.55rem;
        box-shadow: 0 10px 24px -14px rgba(5, 150, 105, 0.35);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .store-carousel-item:hover .store-carousel-img-wrap {
        border-color: rgba(5, 150, 105, 0.35);
        box-shadow: 0 14px 28px -12px rgba(16, 185, 129, 0.2);
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
        grid-template-columns: 76px 1fr;
        background: var(--hp-surface);
        border: 1px solid var(--hp-line);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 36px -24px rgba(12, 10, 18, 0.22);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        animation: hpFadeUp 0.55s ease backwards;
    }
    .coupon-card:hover {
        transform: translateY(-3px);
        border-color: rgba(5, 150, 105, 0.2);
        box-shadow: 0 22px 44px -22px rgba(5, 150, 105, 0.22);
    }
    .coupon-card-strip {
        background: linear-gradient(180deg, #047857 0%, #059669 50%, #047857 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 0.4rem;
        text-align: center;
        font-weight: 800;
        font-size: 0.62rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        line-height: 1.2;
        gap: 0.25rem;
    }
    .coupon-card-strip-icon {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }
    .coupon-card-main {
        padding: 1rem 1rem 1rem 0.9rem;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .coupon-card-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.45rem;
    }
    .coupon-card-logo {
        width: 44px;
        height: 44px;
        object-fit: contain;
        border-radius: 12px;
        background: #ecfdf5;
        padding: 4px;
        border: 1px solid var(--hp-line);
        flex-shrink: 0;
    }
    .coupon-card-brand {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--hp-ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .coupon-card-offer {
        font-size: 0.84rem;
        color: var(--hp-muted);
        margin: 0 0 0.75rem;
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
        padding: 0.45rem 0.7rem;
        background: #ecfdf5;
        border: 1px dashed var(--hp-violet);
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--hp-violet-deep);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-family: ui-monospace, monospace;
    }
    .coupon-card-code:hover {
        background: #d1fae5;
        border-color: var(--hp-violet-deep);
    }
    .coupon-card-code.copied {
        background: #d1fae5;
        border-color: var(--hp-violet);
        color: #047857;
    }
    .coupon-card-code-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
    }
    .coupon-card-code-value { letter-spacing: 0.02em; }
    .coupon-card-code-copy { font-size: 0.62rem; opacity: 0.85; }
    .coupon-card-code.copied .coupon-card-code-copy { display: none; }
    .coupon-card-code.copied::after {
        content: '✓';
        margin-left: 0.25rem;
        color: var(--hp-violet);
    }
    .coupon-card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.48rem 1rem;
        background: linear-gradient(135deg, var(--hp-violet) 0%, var(--hp-rose) 100%);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        border-radius: 11px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 6px 18px -6px rgba(5, 150, 105, 0.55);
    }
    .coupon-card-cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px -6px rgba(16, 185, 129, 0.45);
        color: #fff;
    }

    .hp-cats {
        padding: clamp(2.75rem, 5vw, 3.75rem) 0;
        background: linear-gradient(135deg, #d1fae5 0%, #d1fae5 55%, #ecfdf5 100%);
        border-top: 1px solid var(--hp-line);
        position: relative;
        overflow: hidden;
    }
    .hp-cats::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(60% 80% at 100% 0%, rgba(5, 150, 105, 0.12) 0%, transparent 55%);
        pointer-events: none;
    }
    .hp-cats .hp-shell { position: relative; z-index: 1; }
    .hp-cats .hp-sec-eyebrow { color: var(--hp-violet-deep); }
    .hp-cats .hp-sec-title { color: var(--hp-ink); }
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
        padding: 0.55rem 1.2rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--hp-ink);
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid rgba(12, 10, 18, 0.08);
        backdrop-filter: blur(8px);
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
    }
    .hp-cat-pill:hover {
        background: #fff;
        border-color: rgba(5, 150, 105, 0.35);
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
        color: #34d399;
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
        .coupon-card { grid-template-columns: 1fr; }
        .coupon-card-strip {
            flex-direction: row;
            gap: 0.5rem;
            padding: 0.65rem 1rem;
        }
        .coupon-card-main { padding: 1rem 1rem 1.1rem; }
    }
</style>
@endpush

@section('content')
<div class="home-page">
    <section class="hp-hero">
        <div class="hp-shell">
            <div class="hp-hero-panel">
                <div class="hp-hero-inner">
                    <div class="hp-hero-main">
                        <p class="hp-badge"><span class="hp-badge__dot" aria-hidden="true"></span> Deals you can trust</p>
                        <h1 class="font-heading">Save smarter with <em>curated coupons</em> &amp; honest store picks</h1>
                        <p class="hp-hero-lead">Search verified promotions, explore top stores, and read updates from our blog — refreshed often so you never miss a strong offer.</p>
                        <div class="hp-hero-actions">
                            <form action="{{ url('/') }}" method="get" class="hp-search">
                                <input type="search" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Search brands, stores, or offers…" autocomplete="off">
                                <button type="submit">Search</button>
                            </form>
                            <div class="hp-quick">
                                <span class="hp-quick__label">Jump to</span>
                                <a href="{{ route('deals.index') }}">Deals</a>
                                <a href="{{ url('/') }}#stores">Stores</a>
                                <a href="{{ route('blog.index') }}">Blog</a>
                            </div>
                        </div>
                    </div>
                    <aside class="hp-hero-aside" aria-label="Site highlights">
                        <div class="hp-aside-slider" id="hp-hero-aside-slider">
                            <div class="hp-aside-track" id="hp-aside-track">
                                <div class="hp-aside-slide">
                                    <span class="hp-aside-icon" aria-hidden="true">✓</span>
                                    <div class="hp-aside-body">
                                        <p class="hp-aside-label">Why shoppers stay</p>
                                        <p class="hp-aside-stat">Curated</p>
                                        <p class="hp-aside-caption">Human-reviewed paths to real savings — fewer dead codes, clearer next steps.</p>
                                    </div>
                                </div>
                                <div class="hp-aside-slide">
                                    <span class="hp-aside-icon" aria-hidden="true">↻</span>
                                    <div class="hp-aside-body">
                                        <p class="hp-aside-label">Always in motion</p>
                                        <p class="hp-aside-stat">Updated</p>
                                        <p class="hp-aside-caption">We refresh offers and landing details often so you see what still works — not yesterday’s leftovers.</p>
                                    </div>
                                </div>
                                <div class="hp-aside-slide">
                                    <span class="hp-aside-icon" aria-hidden="true">◎</span>
                                    <div class="hp-aside-body">
                                        <p class="hp-aside-label">Built for trust</p>
                                        <p class="hp-aside-stat">Verified</p>
                                        <p class="hp-aside-caption">Clear affiliate disclosure, honest pros &amp; cons on store pages, and CTAs that take you straight to the deal.</p>
                                    </div>
                                </div>
                            </div>
                            <nav class="hp-aside-dots" id="hp-aside-dots" aria-label="Highlight slides">
                                <button type="button" class="hp-aside-dot is-active" aria-label="Slide 1: Curated" aria-current="true" data-slide="0"></button>
                                <button type="button" class="hp-aside-dot" aria-label="Slide 2: Updated" data-slide="1"></button>
                                <button type="button" class="hp-aside-dot" aria-label="Slide 3: Verified" data-slide="2"></button>
                            </nav>
                        </div>
                    </aside>
                </div>
                <p class="hp-hero-foot">We are an independent deal finder. We may earn from qualifying purchases. <a href="{{ url('/affiliate-disclosure') }}">Read our disclosure</a>.</p>
            </div>
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
                    <article class="coupon-card">
                        <div class="coupon-card-strip" aria-hidden="true">
                            <span class="coupon-card-strip-icon">%</span>
                            <span>{{ $coupon->code ? 'Code' : 'Deal' }}</span>
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
