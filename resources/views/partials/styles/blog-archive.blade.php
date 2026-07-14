<style>
.blog-archive {
    --ba-bg: #ffffff;
    --ba-surface: #ffffff;
    --ba-surface-2: #f3f4f6;
    --ba-line: rgba(15, 23, 42, 0.08);
    --ba-text: #111827;
    --ba-muted: #6b7280;
    --ba-accent: #e91e8c;
    --ba-shell: min(1320px, calc(100% - 2rem));
    background: var(--ba-bg);
    color: var(--ba-text);
    font-family: 'DM Sans', system-ui, sans-serif;
    padding: 2rem 0 3.5rem;
    min-height: 60vh;
}

.blog-archive .ba-shell {
    width: var(--ba-shell);
    margin-inline: auto;
}

.blog-archive .ba-header {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--ba-line);
}

.blog-archive .ba-kicker {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--ba-accent);
    margin-bottom: 0.35rem;
}

.blog-archive .ba-title {
    font-size: clamp(1.75rem, 3.5vw, 2.35rem);
    font-weight: 800;
    color: var(--ba-text);
    margin: 0 0 0.35rem;
    letter-spacing: -0.02em;
}

.blog-archive .ba-subtitle {
    color: var(--ba-muted);
    font-size: 1rem;
    margin: 0;
}

.blog-archive .ba-subtitle strong {
    color: var(--ba-text);
}

.blog-archive .ba-search {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--ba-surface);
    border: 1px solid var(--ba-line);
    border-radius: 999px;
    padding: 0.35rem 0.35rem 0.35rem 1rem;
    min-width: min(100%, 360px);
}

.blog-archive .ba-search svg {
    width: 1rem;
    height: 1rem;
    color: var(--ba-muted);
    flex-shrink: 0;
}

.blog-archive .ba-search input {
    flex: 1;
    min-width: 0;
    border: none;
    background: transparent;
    color: var(--ba-text);
    font-size: 0.95rem;
    outline: none;
    font-family: inherit;
}

.blog-archive .ba-search button {
    border: none;
    background: var(--ba-accent);
    color: #fff;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    cursor: pointer;
    font-family: inherit;
}

.blog-archive .ba-cats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin-bottom: 1.75rem;
}

.blog-archive .ba-cat {
    display: inline-flex;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    border: 1px solid var(--ba-line);
    background: var(--ba-surface);
    color: var(--ba-muted);
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.2s;
}

.blog-archive .ba-cat:hover,
.blog-archive .ba-cat.is-active {
    border-color: var(--ba-cat-color, var(--ba-accent));
    color: #fff;
    background: color-mix(in srgb, var(--ba-cat-color, var(--ba-accent)) 85%, #ffffff);
}

.blog-archive .ba-spotlight {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 0;
    background: var(--ba-surface);
    border: 1px solid var(--ba-line);
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    margin-bottom: 1.5rem;
    transition: border-color 0.2s;
}

.blog-archive .ba-spotlight:hover {
    border-color: rgba(233, 30, 140, 0.28);
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
}

.blog-archive .ba-spotlight-media {
    min-height: 280px;
    background: var(--ba-surface-2);
}

.blog-archive .ba-spotlight-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-height: 280px;
}

.blog-archive .ba-spotlight-body {
    padding: 1.75rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.blog-archive .ba-spotlight-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--ba-accent);
    margin-bottom: 0.5rem;
}

.blog-archive .ba-spotlight-tag {
    display: inline-block;
    align-self: flex-start;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: #fff;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
    margin-bottom: 0.65rem;
}

.blog-archive .ba-spotlight-title {
    font-size: clamp(1.25rem, 2.2vw, 1.65rem);
    font-weight: 800;
    color: var(--ba-text);
    line-height: 1.25;
    margin: 0 0 0.65rem;
}

.blog-archive .ba-spotlight-excerpt {
    color: var(--ba-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0 0 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-archive .ba-spotlight-meta {
    font-size: 0.82rem;
    color: var(--ba-muted);
    margin: 0;
}

.blog-archive .ba-feed {
    display: flex;
    flex-direction: column;
    gap: 0;
    border: 1px solid var(--ba-line);
    border-radius: 12px;
    overflow: hidden;
    background: var(--ba-surface);
}

.blog-archive .ba-feed-item {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 1.25rem;
    padding: 1.1rem 1.25rem;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid var(--ba-line);
    transition: background 0.2s;
}

.blog-archive .ba-feed-item:last-child {
    border-bottom: none;
}

.blog-archive .ba-feed-item:hover {
    background: #f9fafb;
}

.blog-archive .ba-feed-thumb {
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 16/10;
    background: var(--ba-surface-2);
}

.blog-archive .ba-feed-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-archive .ba-feed-tag {
    display: inline-block;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: var(--ba-tag-color, var(--ba-accent));
    background: color-mix(in srgb, var(--ba-tag-color, var(--ba-accent)) 15%, transparent);
    border: 1px solid color-mix(in srgb, var(--ba-tag-color, var(--ba-accent)) 35%, transparent);
    padding: 0.15rem 0.45rem;
    border-radius: 3px;
    margin-bottom: 0.45rem;
}

.blog-archive .ba-feed-title {
    font-size: 1.08rem;
    font-weight: 700;
    color: var(--ba-text);
    line-height: 1.35;
    margin: 0 0 0.4rem;
}

.blog-archive .ba-feed-excerpt {
    font-size: 0.92rem;
    color: var(--ba-muted);
    line-height: 1.55;
    margin: 0 0 0.55rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-archive .ba-feed-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.85rem;
    font-size: 0.78rem;
    color: var(--ba-muted);
    margin: 0;
}

.blog-archive .ba-feed-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.blog-archive .ba-feed-meta svg {
    width: 0.85rem;
    height: 0.85rem;
}

.blog-archive .ba-pagination {
    margin-top: 1.75rem;
    display: flex;
    justify-content: center;
}

.blog-archive .ba-pagination .pagination-nav {
    margin-top: 0;
    width: 100%;
}

.blog-archive .ba-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    background: var(--ba-surface);
    border: 1px solid var(--ba-line);
    border-radius: 12px;
    color: var(--ba-muted);
}

.blog-archive .ba-empty strong {
    display: block;
    color: var(--ba-text);
    font-size: 1.15rem;
    margin-bottom: 0.35rem;
}

@media (max-width: 900px) {
    .blog-archive .ba-spotlight {
        grid-template-columns: 1fr;
    }

    .blog-archive .ba-feed-item {
        grid-template-columns: 1fr;
    }

    .blog-archive .ba-feed-thumb {
        aspect-ratio: 16/9;
    }
}

@media (max-width: 640px) {
    .blog-archive {
        --ba-shell: calc(100% - 1.25rem);
        padding-top: 1.25rem;
    }

    .blog-archive .ba-header {
        flex-direction: column;
        align-items: stretch;
    }

    .blog-archive .ba-search {
        min-width: 100%;
    }
}
</style>
