<style>
    .idx-home {
        --idx-ink: #0f172a;
        --idx-muted: #64748b;
        --idx-line: rgba(14, 116, 144, 0.1);
        --idx-violet: #38bdf8;
        --idx-violet-deep: #0284c7;
        --idx-rose: #0ea5e9;
        --idx-rose-deep: #0369a1;
        --idx-surface: #ffffff;
        --idx-cream: #f0f9ff;
        --idx-glow: radial-gradient(120% 80% at 80% 0%, rgba(56, 189, 248, 0.2) 0%, transparent 55%),
            radial-gradient(90% 60% at 10% 100%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
        background: var(--idx-cream);
        background-image: var(--idx-glow);
        color: var(--idx-ink);
    }
    .idx-home .idx-wrap {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .idx-banner {
        position: relative;
        padding: clamp(1.5rem, 4vw, 2.75rem) 0 clamp(2rem, 4vw, 3rem);
        overflow: hidden;
    }
    .idx-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(165deg, rgba(255, 255, 255, 0.92) 0%, rgba(247, 245, 251, 0.65) 45%, transparent 100%),
            repeating-linear-gradient(-12deg, transparent, transparent 48px, rgba(56, 189, 248, 0.03) 48px, rgba(56, 189, 248, 0.03) 49px);
        pointer-events: none;
    }
    .idx-banner__layout {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .idx-banner__layout {
            grid-template-columns: 1.05fr 0.95fr;
            gap: 3rem;
        }
    }
    .idx-banner__tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--idx-violet-deep);
        margin-bottom: 1rem;
    }
    .idx-banner__tag::before {
        content: '';
        width: 2rem;
        height: 2px;
        background: linear-gradient(90deg, var(--idx-violet), var(--idx-rose));
        border-radius: 5px;
    }
    .idx-banner h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.1rem, 5vw, 3.35rem);
        font-weight: 700;
        letter-spacing: -0.045em;
        line-height: 1.08;
        color: var(--idx-ink);
        margin: 0 0 1.1rem;
        max-width: 16ch;
    }
    .idx-banner__lead {
        font-size: clamp(1rem, 1.35vw, 1.125rem);
        color: var(--idx-muted);
        line-height: 1.65;
        max-width: 38rem;
        margin: 0 0 1.25rem;
    }
    .idx-banner__legal {
        font-size: 0.8125rem;
        color: var(--idx-muted);
        margin-bottom: 1.75rem;
        max-width: 36rem;
    }
    .idx-banner__legal a {
        color: var(--idx-violet);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .idx-banner__legal a:hover { color: var(--idx-violet-deep); }

    .idx-banner__search {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        padding: 0.45rem;
        background: var(--idx-surface);
        border: 1px solid var(--idx-line);
        border-radius: 5px;
        box-shadow: 0 18px 50px -28px rgba(12, 10, 18, 0.35);
        max-width: 520px;
    }
    .idx-banner__search:focus-within {
        border-color: rgba(56, 189, 248, 0.35);
        box-shadow: 0 18px 50px -28px rgba(56, 189, 248, 0.25);
    }
    .idx-banner__search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.85rem 1rem 0.85rem 1.15rem;
        font-size: 1rem;
        color: var(--idx-ink);
        outline: none;
    }
    .idx-banner__search input::placeholder { color: #9ca3af; }
    .idx-banner__search button {
        border: none;
        cursor: pointer;
        padding: 0.85rem 1.35rem;
        border-radius: 5px;
        font-weight: 700;
        font-size: 0.8125rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, var(--idx-violet) 0%, var(--idx-rose) 100%);
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 8px 24px -8px rgba(56, 189, 248, 0.55);
    }
    .idx-banner__search button:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
        transform: translateY(-1px);
        box-shadow: 0 12px 28px -8px rgba(14, 165, 233, 0.45);
    }

    .idx-banner__spotlight {
        position: relative;
        min-height: 220px;
        border-radius: 5px;
        background: linear-gradient(145deg, #0c4a6e 0%, #0369a1 48%, #0ea5e9 100%);
        padding: 1.75rem;
        color: #f0f9ff;
        overflow: hidden;
        box-shadow: 0 28px 60px -24px rgba(49, 46, 129, 0.65);
    }
    .idx-banner__spotlight::after {
        content: '';
        position: absolute;
        right: -20%;
        top: -30%;
        width: 70%;
        height: 90%;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.35) 0%, transparent 70%);
        pointer-events: none;
    }
    .idx-spotlight-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        opacity: 0.75;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }
    .idx-spotlight-stat {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.25rem, 4vw, 2.85rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 0.35rem;
        position: relative;
        z-index: 1;
    }
    .idx-spotlight-caption {
        font-size: 0.9rem;
        opacity: 0.88;
        max-width: 16rem;
        line-height: 1.45;
        position: relative;
        z-index: 1;
    }
    .idx-spotlight-slider {
        position: relative;
        z-index: 1;
        min-height: 200px;
        overflow: hidden;
    }
    .idx-spotlight-track {
        display: flex;
        width: 100%;
        transition: transform 0.45s ease;
        will-change: transform;
    }
    .idx-spotlight-slide {
        flex: 0 0 100%;
        min-width: 0;
        box-sizing: border-box;
    }
    .idx-spotlight-dots {
        position: absolute;
        bottom: 1.25rem;
        right: 1.25rem;
        display: flex;
        gap: 0.4rem;
        z-index: 2;
        align-items: center;
    }
    .idx-spotlight-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        padding: 0;
        border: none;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.32);
        transition: transform 0.2s, background 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .idx-spotlight-dot:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    .idx-spotlight-dot.is-active {
        background: rgba(56, 189, 248, 0.95);
        transform: scale(1.2);
    }

    .idx-metrics {
        padding: 0 0 2.5rem;
        position: relative;
        z-index: 1;
    }
    .idx-metrics__list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .idx-metrics__list { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .idx-metric__item {
        background: var(--idx-surface);
        border: 1px solid var(--idx-line);
        border-radius: 5px;
        padding: 1.1rem 1rem;
        text-align: center;
        box-shadow: 0 12px 32px -22px rgba(12, 10, 18, 0.2);
    }
    .idx-metric__value {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--idx-ink);
    }
    .idx-metric__label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--idx-muted);
        margin-top: 0.35rem;
    }

    .idx-block {
        padding: clamp(2.5rem, 5vw, 3.75rem) 0;
    }
    .idx-block--tint {
        background: var(--idx-surface);
        border-top: 1px solid var(--idx-line);
        border-bottom: 1px solid var(--idx-line);
    }
    .idx-block__header {
        margin-bottom: 1.75rem;
        max-width: 40rem;
    }
    .idx-block__label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--idx-rose);
        margin-bottom: 0.4rem;
    }
    .idx-block__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.45rem, 3vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.035em;
        color: var(--idx-ink);
        margin: 0 0 0.5rem;
    }
    .idx-block__subtitle {
        margin: 0;
        color: var(--idx-muted);
        font-size: 1rem;
        line-height: 1.55;
    }
    .idx-block__legal {
        font-size: 0.8125rem;
        color: var(--idx-muted);
        margin: -0.25rem 0 1.5rem;
        padding: 0.85rem 1rem;
        border-radius: 5px;
        border: 1px dashed var(--idx-line);
        background: rgba(56, 189, 248, 0.04);
        max-width: 720px;
    }
    .idx-block__legal a {
        color: var(--idx-violet);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .idx-articles {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.1rem;
    }
    .idx-article__card {
        display: flex;
        flex-direction: column;
        border-radius: 5px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background: var(--idx-surface);
        border: 1px solid var(--idx-line);
        box-shadow: 0 16px 40px -30px rgba(12, 10, 18, 0.35);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.2s;
    }
    .idx-article__card:hover {
        transform: translateY(-4px);
        border-color: rgba(56, 189, 248, 0.22);
        box-shadow: 0 24px 50px -28px rgba(56, 189, 248, 0.28);
    }
    .idx-article__thumb {
        aspect-ratio: 16 / 10;
        background: #e0f2fe;
        overflow: hidden;
    }
    .idx-article__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .idx-article__body {
        padding: 1.15rem 1.2rem 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .idx-article__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0 0 0.5rem;
        color: var(--idx-ink);
    }
    .idx-article__date {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--idx-muted);
        margin-top: auto;
    }
    .idx-article__action {
        margin-top: 0.65rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--idx-violet);
    }
    .idx-article__card:hover .idx-article__action { color: var(--idx-rose); }
    .idx-block__more {
        margin-top: 1.5rem;
    }
    .idx-block__more a {
        font-weight: 700;
        color: var(--idx-violet);
        text-decoration: none;
        border-bottom: 2px solid rgba(56, 189, 248, 0.25);
        padding-bottom: 2px;
    }
    .idx-block__more a:hover { color: var(--idx-rose); border-bottom-color: rgba(14, 165, 233, 0.35); }

    .idx-brands__box {
        background: var(--idx-surface);
        border: 1px solid var(--idx-line);
        border-radius: 5px;
        padding: 1.5rem 1rem 1.35rem;
        box-shadow: 0 20px 50px -32px rgba(12, 10, 18, 0.25);
    }
    .idx-brands__viewport {
        overflow: hidden;
        margin: 0 -0.25rem;
        padding: 0 0.25rem;
        cursor: grab;
        user-select: none;
    }
    .idx-brands__viewport:active { cursor: grabbing; }
    .idx-brands__track {
        display: flex;
        width: max-content;
        transition: transform 0.1s ease-out;
    }
    .idx-brands__viewport.dragging .idx-brands__track { transition: none; }
    .idx-brands__lane {
        display: flex;
        align-items: flex-start;
        gap: 1.75rem;
        padding: 0.5rem 1rem 0.5rem 0;
    }
    .idx-brand__item {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: inherit;
        width: 100px;
        transition: transform 0.2s ease;
    }
    .idx-brand__item:hover { transform: translateY(-4px); }
    .idx-brand__logo {
        width: 80px;
        height: 80px;
        border-radius: 5px;
        overflow: hidden;
        background: linear-gradient(180deg, #f0f9ff 0%, #bae6fd 100%);
        border: 1px solid var(--idx-line);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.55rem;
        box-shadow: 0 10px 24px -14px rgba(56, 189, 248, 0.35);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .idx-brand__item:hover .idx-brand__logo {
        border-color: rgba(56, 189, 248, 0.35);
        box-shadow: 0 14px 28px -12px rgba(14, 165, 233, 0.2);
    }
    .idx-brand__logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .idx-brand__name {
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

    .idx-deals__list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    .idx-deal {
        display: grid;
        grid-template-columns: 76px 1fr;
        background: var(--idx-surface);
        border: 1px solid var(--idx-line);
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 14px 36px -24px rgba(12, 10, 18, 0.22);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        animation: idxFadeUp 0.55s ease backwards;
    }
    .idx-deal:hover {
        transform: translateY(-3px);
        border-color: rgba(56, 189, 248, 0.2);
        box-shadow: 0 22px 44px -22px rgba(56, 189, 248, 0.22);
    }
    .idx-deal__strip {
        background: linear-gradient(180deg, #0284c7 0%, #0ea5e9 50%, #38bdf8 100%);
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
    .idx-deal__strip-icon {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }
    .idx-deal__main {
        padding: 1rem 1rem 1rem 0.9rem;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .idx-deal__header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.45rem;
    }
    .idx-deal__logo {
        width: 44px;
        height: 44px;
        object-fit: contain;
        border-radius: 5px;
        background: #f0f9ff;
        padding: 4px;
        border: 1px solid var(--idx-line);
        flex-shrink: 0;
    }
    .idx-deal__brand {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--idx-ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .idx-deal__offer {
        font-size: 0.84rem;
        color: var(--idx-muted);
        margin: 0 0 0.75rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .idx-deal__actions {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: auto;
    }
    .idx-deal__code {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.7rem;
        background: #f0f9ff;
        border: 1px dashed var(--idx-violet);
        border-radius: 5px;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--idx-violet-deep);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        font-family: ui-monospace, monospace;
    }
    .idx-deal__code:hover {
        background: #e0f2fe;
        border-color: var(--idx-violet-deep);
        color: #0369a1;
    }
    .idx-deal__code.copied {
        background: #e0f2fe;
        border-color: var(--idx-violet);
        color: #0284c7;
    }
    .idx-deal__code-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
    }
    .idx-deal__code-value { letter-spacing: 0.02em; }
    .idx-deal__code-copy { font-size: 0.62rem; opacity: 0.85; }
    .idx-deal__code.copied .idx-deal__code-copy { display: none; }
    .idx-deal__code.copied::after {
        content: '?';
        margin-left: 0.25rem;
        color: var(--idx-violet);
    }
    .idx-deal__cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.48rem 1rem;
        background: linear-gradient(135deg, var(--idx-violet) 0%, var(--idx-rose) 100%);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        border-radius: 5px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 6px 18px -6px rgba(56, 189, 248, 0.55);
    }
    .idx-deal__cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px -6px rgba(14, 165, 233, 0.45);
        color: #fff;
    }

    .idx-topics {
        padding: clamp(2.75rem, 5vw, 3.75rem) 0;
        background: linear-gradient(135deg, #e0f2fe 0%, #e0f2fe 55%, #f0f9ff 100%);
        border-top: 1px solid var(--idx-line);
        position: relative;
        overflow: hidden;
    }
    .idx-topics::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(60% 80% at 100% 0%, rgba(56, 189, 248, 0.12) 0%, transparent 55%);
        pointer-events: none;
    }
    .idx-topics .idx-wrap { position: relative; z-index: 1; }
    .idx-topics .idx-block__label { color: var(--idx-violet-deep); }
    .idx-topics .idx-block__title { color: var(--idx-ink); }
    .idx-topics__row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem 0.65rem;
        justify-content: flex-start;
    }
    @media (min-width: 720px) {
        .idx-topics__row { justify-content: center; }
    }
    .idx-topics__chip {
        display: inline-block;
        padding: 0.55rem 1.2rem;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--idx-ink);
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid rgba(12, 10, 18, 0.08);
        backdrop-filter: blur(8px);
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
    }
    .idx-topics__chip:hover {
        background: #fff;
        border-color: rgba(56, 189, 248, 0.35);
        transform: translateY(-2px);
    }

    .idx-empty {
        text-align: center;
        padding: 3rem 1.25rem;
        border-radius: 5px;
        border: 1px dashed var(--idx-line);
        background: rgba(255, 255, 255, 0.7);
        color: var(--idx-muted);
    }
    .idx-empty svg {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        opacity: 0.4;
        color: #38bdf8;
    }
    .idx-empty h3 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--idx-ink);
        margin-bottom: 0.5rem;
    }

    @keyframes idxFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .idx-deal:nth-child(1) { animation-delay: 0.04s; }
    .idx-deal:nth-child(2) { animation-delay: 0.1s; }
    .idx-deal:nth-child(3) { animation-delay: 0.16s; }

    @media (max-width: 768px) {
        .idx-banner__search { border-radius: 5px; }
        .idx-banner__search button { width: 100%; }
        .idx-brands__lane { gap: 1.25rem; }
        .idx-brand__item { width: 88px; }
        .idx-brand__logo { width: 72px; height: 72px; }
        .idx-deals__list { grid-template-columns: 1fr; }
    }
    @media (max-width: 520px) {
        .idx-deal { grid-template-columns: 1fr; }
        .idx-deal__strip {
            flex-direction: row;
            gap: 0.5rem;
            padding: 0.65rem 1rem;
        }
        .idx-deal__main { padding: 1rem 1rem 1.1rem; }
    }

    #coupons, #stores, #blog, #categories { scroll-margin-top: 5rem; }
</style>
