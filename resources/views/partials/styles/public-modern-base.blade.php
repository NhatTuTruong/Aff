<style>
/* ── Public design tokens (#00405d brand) ── */
:root {
    --pub-accent: #00405d;
    --pub-accent-2: #003347;
    --pub-accent-3: #006688;
    --pub-accent-light: #4da8c4;
    --pub-accent-soft: rgba(0, 64, 93, 0.07);
    --pub-accent-mid: rgba(0, 64, 93, 0.14);
    --pub-accent-glow: rgba(0, 64, 93, 0.22);
    --pub-ink: #0c1924;
    --pub-muted: #5a7184;
    --pub-line: rgba(12, 25, 36, 0.09);
    --pub-bg: #f2f6f8;
    --pub-surface: #ffffff;
    --pub-dark: #001a26;
    --pub-dark-2: #002534;
    --pub-font: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --pub-heading: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --pub-radius-xl: 24px;
    --pub-radius-lg: 16px;
    --pub-radius-md: 10px;
    --pub-shadow: 0 4px 24px -8px rgba(0, 26, 38, 0.1);
    --pub-shadow-lg: 0 20px 50px -24px rgba(0, 26, 38, 0.16);
}

/* Sync heading/body font across public pages */
body:not([data-fi-theme]) {
    font-family: var(--pub-font);
    --primary: var(--pub-accent);
    --primary-dark: var(--pub-accent-2);
    --accent: var(--pub-accent);
    --accent-hover: var(--pub-accent-2);
    --accent-rose: var(--pub-accent-light);
}

body:not([data-fi-theme]) .font-heading,
body:not([data-fi-theme]) h1,
body:not([data-fi-theme]) h2,
body:not([data-fi-theme]) h3,
body:not([data-fi-theme]) h4 {
    font-family: var(--pub-font);
}

input:focus,
select:focus,
textarea:focus {
    border-color: var(--pub-accent) !important;
    box-shadow: 0 0 0 3px var(--pub-accent-soft) !important;
    outline: none;
}
</style>
