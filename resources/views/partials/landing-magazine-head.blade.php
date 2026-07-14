@if(\App\Support\MagazineLayout::usesMagazineChrome())
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap" rel="stylesheet">
@include('partials.styles.magazine-chrome')
<style>
.font-heading {
    font-family: 'DM Sans', system-ui, sans-serif;
}

body:has(.magazine-header) .site-footer--magazine {
    margin-top: 0;
}

@media (min-width: 769px) {
    .magazine-nav-row {
        flex-wrap: wrap;
        align-content: flex-start;
    }
}
</style>
@endif
