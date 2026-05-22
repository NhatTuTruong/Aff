<style>
    .blg-article {
        --blg-ink: #0f172a;
        --blg-muted: #64748b;
        --blg-line: rgba(14, 116, 144, 0.12);
        --blg-accent: #38bdf8;
        --blg-accent-deep: #0284c7;
        --blg-rose: #0ea5e9;
        --blg-surface: #ffffff;
        --blg-cream: #f0f9ff;
        background: var(--blg-cream);
        color: var(--blg-ink);
        padding-bottom: 3.5rem;
    }
    .blg-article .blg-wrap {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .blg-article__crumb {
        padding: 1.25rem 0 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.4rem 0.5rem;
        font-size: 0.8rem;
        color: var(--blg-muted);
    }
    .blg-article__crumb a {
        color: var(--blg-muted);
        text-decoration: none;
        font-weight: 600;
    }
    .blg-article__crumb a:hover { color: var(--blg-accent-deep); }

    .blg-article__cover {
        margin: 1.25rem 0 0;
        border-radius: 5px;
        overflow: hidden;
        border: 1px solid var(--blg-line);
        background: #e0f2fe;
        box-shadow: 0 28px 60px -32px rgba(15, 23, 42, 0.25);
    }
    .blg-article__cover img {
        width: 100%;
        max-height: min(52vh, 420px);
        object-fit: cover;
        display: block;
    }

    .blg-article__head {
        padding: 1.75rem 0 0;
        max-width: 42rem;
    }
    .blg-article__chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 1rem;
    }
    .blg-article__chip {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 5px;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--blg-muted);
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
    }
    .blg-article__chip--accent {
        color: var(--blg-accent-deep);
        background: rgba(56, 189, 248, 0.1);
        border-color: rgba(56, 189, 248, 0.25);
    }
    .blg-article__share {
        margin-left: auto;
        border: 1px solid var(--blg-line);
        background: var(--blg-surface);
        border-radius: 5px;
        padding: 0.35rem 0.85rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--blg-muted);
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s;
    }
    .blg-article__share:hover {
        border-color: rgba(56, 189, 248, 0.35);
        color: var(--blg-accent-deep);
    }
    @media (max-width: 520px) {
        .blg-article__share { margin-left: 0; }
    }
    .blg-article__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.35rem);
        font-weight: 700;
        letter-spacing: -0.035em;
        line-height: 1.15;
        margin: 0 0 0.75rem;
    }
    .blg-article__legal {
        margin: 0;
        font-size: 0.85rem;
        color: var(--blg-muted);
        line-height: 1.55;
    }
    .blg-article__legal a {
        color: var(--blg-accent-deep);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .blg-article__layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-top: 2rem;
        align-items: start;
    }
    @media (min-width: 960px) {
        .blg-article__layout {
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 2.5rem;
        }
    }

    .blg-article__main {
        min-width: 0;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        padding: 1.5rem 1.5rem 2rem;
        box-shadow: 0 18px 48px -30px rgba(15, 23, 42, 0.18);
    }
    @media (max-width: 640px) {
        .blg-article__main { padding: 1.25rem 1.1rem 1.5rem; }
    }

    .blg-article__back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--blg-muted);
        text-decoration: none;
    }
    .blg-article__back-icon {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 5px;
        border: 1px solid var(--blg-line);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }
    .blg-article__back:hover { color: var(--blg-accent-deep); }

    .blg-prose {
        font-size: 1rem;
        line-height: 1.75;
        color: var(--blg-ink);
    }
    .blg-prose h2,
    .blg-prose h3,
    .blg-prose h4 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 1.75rem 0 0.65rem;
        color: var(--blg-ink);
    }
    .blg-prose h2 { font-size: 1.3rem; }
    .blg-prose h3 { font-size: 1.1rem; }
    .blg-prose p { margin: 0.85rem 0; }
    .blg-prose ul,
    .blg-prose ol { margin: 0.75rem 0 1rem; padding-left: 1.35rem; }
    .blg-prose li { margin: 0.3rem 0; }
    .blg-prose a {
        color: var(--blg-accent-deep);
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .blg-prose img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        border: 1px solid var(--blg-line);
    }
    .blg-prose figure.attachment .attachment__caption {
        display: none !important;
    }

    .blg-article__media-grid {
        margin-top: 1.5rem;
        display: grid;
        gap: 0.85rem;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
    .blg-article__media-grid img,
    .blg-article__media-grid video {
        width: 100%;
        border-radius: 5px;
        border: 1px solid var(--blg-line);
    }

    .blg-sidebar {
        min-width: 0;
    }
    @media (min-width: 960px) {
        .blg-sidebar {
            position: sticky;
            top: 1.25rem;
        }
    }
    .blg-sidebar__panel {
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        padding: 1.25rem 1.2rem 1.35rem;
        box-shadow: 0 16px 40px -28px rgba(15, 23, 42, 0.2);
    }
    .blg-sidebar__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 1rem;
        letter-spacing: -0.02em;
    }
    .blg-sidebar__list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .blg-sidebar__empty {
        margin: 0;
        font-size: 0.85rem;
        color: var(--blg-muted);
        line-height: 1.5;
    }

    .blg-deal {
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        padding: 0.85rem 0.9rem;
        background: linear-gradient(180deg, #fff 0%, #f8fcff 100%);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .blg-deal:hover {
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow: 0 8px 20px -12px rgba(56, 189, 248, 0.25);
    }
    .blg-deal__head {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.4rem;
    }
    .blg-deal__logo {
        width: 36px;
        height: 36px;
        border-radius: 5px;
        border: 1px solid var(--blg-line);
        background: #f0f9ff;
        overflow: hidden;
        flex-shrink: 0;
    }
    .blg-deal__logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 3px;
    }
    .blg-deal__brand {
        font-size: 0.88rem;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .blg-deal__offer {
        margin: 0 0 0.55rem;
        font-size: 0.8rem;
        color: var(--blg-muted);
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-deal__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        align-items: center;
    }
    .blg-deal__code {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.6rem;
        border-radius: 5px;
        border: 1px dashed var(--blg-accent);
        background: #f0f9ff;
        font-size: 0.75rem;
        font-weight: 700;
        font-family: ui-monospace, monospace;
        color: var(--blg-accent-deep);
        cursor: pointer;
    }
    .blg-deal__code:hover {
        background: #e0f2fe;
        border-color: var(--blg-accent-deep);
        color: #0369a1;
    }
    .blg-deal__code.copied {
        background: #e0f2fe;
        border-color: var(--blg-accent-deep);
    }
    .blg-deal__code-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.85;
    }
    .blg-deal__cta {
        display: inline-flex;
        align-items: center;
        padding: 0.38rem 0.8rem;
        border-radius: 5px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(135deg, var(--blg-accent), var(--blg-rose));
        box-shadow: 0 6px 16px -6px rgba(56, 189, 248, 0.5);
    }
    .blg-deal__cta:hover { filter: brightness(1.05); color: #fff; }

    .blg-related {
        margin-top: 2.75rem;
        padding-top: 2rem;
        border-top: 1px solid var(--blg-line);
    }
    .blg-related__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0 0 1.15rem;
        letter-spacing: -0.02em;
    }
    .blg-related__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    .blg-related__card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        border-radius: 5px;
        overflow: hidden;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
    }
    .blg-related__card:hover {
        transform: translateY(-2px);
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow: 0 14px 32px -20px rgba(56, 189, 248, 0.25);
    }
    .blg-related__thumb {
        aspect-ratio: 16 / 10;
        background: #e0f2fe;
        overflow: hidden;
    }
    .blg-related__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .blg-related__body {
        padding: 0.85rem 0.95rem 1rem;
    }
    .blg-related__name {
        font-size: 0.92rem;
        font-weight: 700;
        line-height: 1.35;
        margin: 0 0 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-related__date {
        margin: 0;
        font-size: 0.78rem;
        color: var(--blg-muted);
        font-weight: 600;
    }
</style>
