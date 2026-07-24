@extends('layouts.app')

@section('title', 'Exclusive Deals & Discounts')
@section('description', 'Handpicked deals and discount codes from trusted retailers across multiple categories.')
@section('robots', 'noindex, nofollow')

@push('styles')
<style>
    .container { max-width: 800px; margin: 0 auto; padding: 0 1.5rem; }

    .intro-section {
        padding: 4rem 0 3rem;
    }
    .intro-section h1 {
        font-family: 'DM Sans', system-ui, sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 1.25rem;
    }
    .intro-section p {
        font-size: 1.05rem;
        line-height: 1.75;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    .intro-section p:last-child { margin-bottom: 0; }
</style>
@endpush

@section('content')
    <section class="intro-section">
        <div class="container">
            <h1>Exclusive Deals & Discounts</h1>

            <p>We curate and share the best deals, discount codes, and special offers from trusted retailers across a wide range of categories — including fashion, electronics, travel, home, health, and more.</p>

            <p>Every deal is carefully reviewed to ensure it comes from legitimate, established brands. We work with affiliate partners so you can save money while supporting the platforms you love.</p>

            <p>Bookmark this page and check back regularly — new offers are added and updated frequently throughout the week.</p>
        </div>
    </section>
@endsection
