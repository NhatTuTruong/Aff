<style>
/* Minimal overrides — header is dark by default in site-chrome-styles */
/* These only apply on top of that base */

/* Dark hero on home/blog pages */
body:has(.home-page) .hp-hero,
body:has(.blog-page) .bp-hero,
body:has(.blog-shell) .blog-hero {
    /* keep violet accent glow */
}

/* Form input/select focus color */
input:focus,
select:focus,
textarea:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.18) !important;
    outline: none;
}
</style>
