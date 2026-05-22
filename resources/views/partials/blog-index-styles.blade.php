<style>
    .blg-index {
        --blg-ink: #0f172a;
        --blg-muted: #64748b;
        --blg-line: rgba(14, 116, 144, 0.12);
        --blg-accent: #38bdf8;
        --blg-accent-deep: #0284c7;
        --blg-rose: #0ea5e9;
        --blg-surface: #ffffff;
        --blg-cream: #f0f9ff;
        --blg-glow: radial-gradient(90% 70% at 100% 0%, rgba(56, 189, 248, 0.18) 0%, transparent 55%),
            radial-gradient(70% 50% at 0% 100%, rgba(14, 165, 233, 0.08) 0%, transparent 50%);
        background: var(--blg-cream);
        background-image: var(--blg-glow);
        color: var(--blg-ink);
        min-height: 60vh;
    }
    .blg-index .blg-wrap {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .blg-masthead {
        padding: clamp(2rem, 4vw, 3rem) 0 1.25rem;
        border-bottom: 1px solid var(--blg-line);
    }
    .blg-masthead__row {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .blg-masthead__row {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            gap: 2rem;
        }
    }
    .blg-masthead__label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--blg-rose);
        margin: 0 0 0.5rem;
    }
    .blg-masthead__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2rem, 4.5vw, 3rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1.05;
        margin: 0 0 0.5rem;
    }
    .blg-masthead__desc {
        margin: 0;
        max-width: 32rem;
        color: var(--blg-muted);
        font-size: 1rem;
        line-height: 1.6;
    }
    .blg-masthead__search {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.4rem;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        border-radius: 5px;
        box-shadow: 0 16px 40px -28px rgba(15, 23, 42, 0.2);
        width: 100%;
        max-width: 420px;
    }
    @media (min-width: 768px) {
        .blg-masthead__search { flex-shrink: 0; }
    }
    .blg-masthead__search input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.75rem 0.85rem;
        font-size: 0.95rem;
        color: var(--blg-ink);
        outline: none;
    }
    .blg-masthead__search input::placeholder { color: #94a3b8; }
    .blg-masthead__search button {
        border: none;
        border-radius: 5px;
        padding: 0.75rem 1.1rem;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        cursor: pointer;
        background: linear-gradient(135deg, var(--blg-accent) 0%, var(--blg-rose) 100%);
        box-shadow: 0 8px 20px -8px rgba(56, 189, 248, 0.5);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .blg-masthead__search button:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
        transform: translateY(-1px);
        box-shadow: 0 12px 24px -8px rgba(14, 165, 233, 0.45);
    }

    .blg-tabs {
        padding: 1rem 0 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .blg-tabs::-webkit-scrollbar { display: none; }
    .blg-tabs__inner {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.35rem;
        min-width: min-content;
        padding-bottom: 0.15rem;
    }
    .blg-tab {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--blg-muted);
        border: 1px solid transparent;
        transition: color 0.2s, background 0.2s, border-color 0.2s;
    }
    .blg-tab:hover {
        color: var(--blg-accent-deep);
        background: rgba(255, 255, 255, 0.7);
        border-color: var(--blg-line);
    }
    .blg-tab--active {
        color: var(--blg-accent-deep);
        background: var(--blg-surface);
        border-color: rgba(56, 189, 248, 0.35);
        box-shadow: 0 4px 14px -6px rgba(56, 189, 248, 0.25);
    }

    .blg-body {
        padding: clamp(1.75rem, 3vw, 2.5rem) 0 clamp(3rem, 5vw, 4rem);
    }

    .blg-feature {
        display: block;
        text-decoration: none;
        color: inherit;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid var(--blg-line);
        background: var(--blg-surface);
        box-shadow: 0 24px 56px -32px rgba(15, 23, 42, 0.28);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .blg-feature:hover {
        transform: translateY(-3px);
        box-shadow: 0 32px 64px -28px rgba(56, 189, 248, 0.28);
    }
    @media (min-width: 800px) {
        .blg-feature {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            min-height: 300px;
        }
    }
    .blg-feature__media {
        position: relative;
        min-height: 220px;
        background: #e0f2fe;
    }
    @media (min-width: 800px) {
        .blg-feature__media { min-height: 100%; }
    }
    .blg-feature__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        min-height: 220px;
    }
    @media (min-width: 800px) {
        .blg-feature__media img { min-height: 300px; }
    }
    .blg-feature__badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.35rem 0.75rem;
        border-radius: 5px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, var(--blg-accent-deep), var(--blg-rose));
    }
    .blg-feature__body {
        padding: 1.5rem 1.5rem 1.65rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.65rem;
    }
    @media (min-width: 800px) {
        .blg-feature__body { padding: 2rem 2rem 2rem 1.5rem; }
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
        color: var(--blg-accent-deep);
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.68rem;
    }
    .blg-feature__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.35rem, 2.5vw, 1.85rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        margin: 0;
    }
    .blg-feature__excerpt {
        margin: 0;
        color: var(--blg-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-feature__cta {
        margin-top: 0.35rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--blg-accent-deep);
    }
    .blg-feature:hover .blg-feature__cta { color: var(--blg-rose); }

    .blg-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .blg-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .blg-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    .blg-card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        border-radius: 5px;
        overflow: hidden;
        background: var(--blg-surface);
        border: 1px solid var(--blg-line);
        box-shadow: 0 12px 36px -26px rgba(15, 23, 42, 0.22);
        transition: transform 0.2s ease, border-color 0.2s, box-shadow 0.2s;
    }
    .blg-card:hover {
        transform: translateY(-3px);
        border-color: rgba(56, 189, 248, 0.25);
        box-shadow: 0 20px 44px -24px rgba(56, 189, 248, 0.22);
    }
    .blg-card__media {
        aspect-ratio: 16 / 10;
        background: #e0f2fe;
        overflow: hidden;
    }
    .blg-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .blg-card:hover .blg-card__media img { transform: scale(1.04); }
    .blg-card__body {
        padding: 1rem 1.1rem 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.45rem;
    }
    .blg-card__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .blg-card__cat {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--blg-accent-deep);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .blg-card__date {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--blg-muted);
        white-space: nowrap;
    }
    .blg-card__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.3;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-card__excerpt {
        margin: 0;
        font-size: 0.88rem;
        color: var(--blg-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blg-card__link {
        margin-top: auto;
        padding-top: 0.35rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--blg-accent);
    }
    .blg-card:hover .blg-card__link { color: var(--blg-rose); }

    .blg-pagination {
        margin-top: 2.5rem;
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
        border-color: rgba(56, 189, 248, 0.35) !important;
        color: var(--blg-accent) !important;
    }
    .blg-pagination .pagination-current {
        border-color: rgba(56, 189, 248, 0.45) !important;
        color: var(--blg-accent-deep) !important;
    }
    .blg-pagination .pagination-info { color: var(--blg-muted) !important; }

    .blg-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        border-radius: 5px;
        border: 1px dashed var(--blg-line);
        background: rgba(255, 255, 255, 0.8);
        max-width: 480px;
        margin: 0 auto;
    }
    .blg-empty__title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem;
    }
    .blg-empty__text {
        margin: 0;
        color: var(--blg-muted);
        font-size: 0.95rem;
    }
</style>
