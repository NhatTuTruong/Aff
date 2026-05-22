<style>
    .blg-index {
        --blg-ink: #0f172a;
        --blg-muted: #64748b;
        --blg-line: rgba(5, 150, 105, 0.12);
        --blg-primary: #059669;
        --blg-primary-dark: #047857;
        --blg-accent: #10b981;
        --blg-surface: #ffffff;
        --blg-bg: #ecfdf5;
        --blg-soft: #d1fae5;
        background: var(--blg-bg);
        color: var(--blg-ink);
        min-height: 50vh;
    }
    .blg-index .blg-wrap {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .blg-top {
        padding: 1.25rem 0 1rem;
        border-bottom: 1px solid var(--blg-line);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.85) 0%, transparent 100%);
    }
    .blg-top__label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--blg-primary);
        margin: 0 0 0.35rem;
    }
    .blg-top__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.35rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1.1;
        margin: 0 0 0.4rem;
    }
    .blg-top__desc {
        margin: 0 0 1.15rem;
        max-width: 36rem;
        color: var(--blg-muted);
        font-size: 0.98rem;
        line-height: 1.55;
    }

    .blg-toolbar {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .blg-toolbar {
            flex-direction: row;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem 1rem;
        }
    }
    .blg-search {
        display: flex;
        flex: 1;
        min-width: 0;
        max-width: 100%;
        gap: 0.45rem;
        padding: 0.35rem;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        box-shadow: 0 8px 24px -16px rgba(5, 150, 105, 0.2);
    }
    @media (min-width: 640px) {
        .blg-search { max-width: 420px; }
    }
    .blg-search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.65rem 0.75rem;
        font-size: 0.95rem;
        color: var(--blg-ink);
        outline: none;
    }
    .blg-search input::placeholder { color: #94a3b8; }
    .blg-search button {
        border: none;
        border-radius: 5px;
        padding: 0.65rem 1rem;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        cursor: pointer;
        background: linear-gradient(135deg, var(--blg-primary) 0%, var(--blg-accent) 100%);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .blg-search button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px -6px rgba(5, 150, 105, 0.45);
    }
    .blg-count {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--blg-muted);
        white-space: nowrap;
    }

    .blg-tabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.35rem;
        overflow-x: auto;
        padding-bottom: 0.15rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .blg-tabs::-webkit-scrollbar { display: none; }
    @media (min-width: 960px) {
        .blg-tabs { display: none; }
    }
    .blg-tab {
        flex-shrink: 0;
        padding: 0.45rem 0.9rem;
        border-radius: 5px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--blg-muted);
        border: 1px solid transparent;
        transition: color 0.2s, background 0.2s, border-color 0.2s;
    }
    .blg-tab:hover {
        color: var(--blg-primary-dark);
        background: rgba(255, 255, 255, 0.8);
        border-color: var(--blg-line);
    }
    .blg-tab--active {
        color: var(--blg-primary-dark);
        background: var(--blg-surface);
        border-color: rgba(5, 150, 105, 0.3);
        box-shadow: 0 2px 8px -4px rgba(5, 150, 105, 0.2);
    }

    .blg-body {
        padding: 1.5rem 0 3rem;
    }
    .blg-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media (min-width: 960px) {
        .blg-layout {
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 2rem;
        }
    }

    .blg-sidebar {
        display: none;
    }
    @media (min-width: 960px) {
        .blg-sidebar {
            display: block;
            position: sticky;
            top: 5.5rem;
        }
        .blg-side-panel {
            background: var(--blg-surface);
            border: 1px solid var(--blg-line);
            border-radius: 5px;
            padding: 1.1rem 1rem;
            box-shadow: 0 12px 32px -24px rgba(5, 150, 105, 0.2);
        }
        .blg-side-panel__title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--blg-muted);
            margin: 0 0 0.75rem;
        }
        .blg-side-nav {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .blg-side-nav a {
            display: block;
            padding: 0.5rem 0.65rem;
            border-radius: 5px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--blg-ink);
            transition: background 0.2s, color 0.2s;
        }
        .blg-side-nav a:hover {
            background: var(--blg-soft);
            color: var(--blg-primary-dark);
        }
        .blg-side-nav a.blg-side-nav--active {
            background: rgba(5, 150, 105, 0.1);
            color: var(--blg-primary-dark);
        }
        .blg-side-tip {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--blg-line);
            font-size: 0.78rem;
            color: var(--blg-muted);
            line-height: 1.5;
        }
    }

    .blg-feed__label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--blg-primary);
        margin: 0 0 0.75rem;
    }
    .blg-feed__label--spaced { margin-top: 1.25rem; }

    .blg-feature {
        display: grid;
        grid-template-columns: 1fr;
        text-decoration: none;
        color: inherit;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 16px 40px -28px rgba(5, 150, 105, 0.25);
        transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
    }
    @media (min-width: 700px) {
        .blg-feature {
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            min-height: 240px;
        }
    }
    .blg-feature:hover {
        transform: translateY(-2px);
        border-color: rgba(5, 150, 105, 0.28);
        box-shadow: 0 22px 48px -24px rgba(5, 150, 105, 0.28);
    }
    .blg-feature__media {
        position: relative;
        min-height: 200px;
        background: var(--blg-soft);
    }
    @media (min-width: 700px) {
        .blg-feature__media { min-height: 100%; }
    }
    .blg-feature__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        min-height: 200px;
    }
    .blg-feature__badge {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        padding: 0.3rem 0.65rem;
        border-radius: 5px;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #fff;
        background: var(--blg-primary);
    }
    .blg-feature__body {
        padding: 1.25rem 1.35rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.5rem;
    }
    .blg-feature__meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem 0.75rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--blg-muted);
    }
    .blg-feature__cat {
        color: var(--blg-primary-dark);
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid rgba(5, 150, 105, 0.2);
        padding: 0.15rem 0.5rem;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.65rem;
    }
    .blg-feature__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.2rem, 2.2vw, 1.55rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.25;
        margin: 0;
    }
    .blg-feature__excerpt {
        margin: 0;
        font-size: 0.92rem;
        color: var(--blg-muted);
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-feature__link {
        margin-top: 0.25rem;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--blg-primary);
    }
    .blg-feature:hover .blg-feature__link { color: var(--blg-accent); }

    .blg-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }
    .blg-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
        text-decoration: none;
        color: inherit;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    @media (min-width: 560px) {
        .blg-row {
            grid-template-columns: 140px minmax(0, 1fr);
            min-height: 118px;
        }
    }
    .blg-row:hover {
        border-color: rgba(5, 150, 105, 0.25);
        box-shadow: 0 10px 28px -18px rgba(5, 150, 105, 0.22);
        transform: translateX(3px);
    }
    .blg-row__thumb {
        aspect-ratio: 16 / 10;
        background: var(--blg-soft);
        overflow: hidden;
    }
    @media (min-width: 560px) {
        .blg-row__thumb {
            aspect-ratio: auto;
            min-height: 100%;
        }
    }
    .blg-row__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        min-height: 100%;
        transition: transform 0.35s ease;
    }
    .blg-row:hover .blg-row__thumb img { transform: scale(1.04); }
    .blg-row__body {
        padding: 0.95rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.35rem;
        min-width: 0;
    }
    @media (min-width: 560px) {
        .blg-row__body { padding: 0.85rem 1.15rem; }
    }
    .blg-row__top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem 0.65rem;
    }
    .blg-row__cat {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--blg-primary-dark);
    }
    .blg-row__date {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--blg-muted);
    }
    .blg-row__date::before {
        content: '·';
        margin-right: 0.35rem;
        color: var(--blg-line);
    }
    .blg-row__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.02rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-row__excerpt {
        margin: 0;
        font-size: 0.84rem;
        color: var(--blg-muted);
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-row__more {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--blg-primary);
        margin-top: 0.15rem;
    }
    .blg-row:hover .blg-row__more { color: var(--blg-accent); }

    .blg-pagination {
        margin-top: 1.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .blg-pagination .pagination-list { gap: 0.35rem !important; }
    .blg-pagination .pagination-item:not(.pagination-ellipsis) {
        border-radius: 5px !important;
        border-color: var(--blg-line) !important;
        background: var(--blg-surface) !important;
        font-weight: 600 !important;
    }
    .blg-pagination .pagination-item:hover:not(.pagination-disabled):not(.pagination-current) {
        border-color: rgba(5, 150, 105, 0.35) !important;
        color: var(--blg-primary) !important;
    }
    .blg-pagination .pagination-current {
        border-color: rgba(5, 150, 105, 0.45) !important;
        color: var(--blg-primary) !important;
    }
    .blg-pagination .pagination-info { color: var(--blg-muted) !important; }

    .blg-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        border-radius: 5px;
        border: 1px dashed var(--blg-line);
        background: rgba(255, 255, 255, 0.85);
        max-width: 440px;
        margin: 0 auto;
    }
    .blg-empty__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0 0 0.45rem;
    }
    .blg-empty__text {
        margin: 0;
        color: var(--blg-muted);
        font-size: 0.92rem;
    }
</style>
