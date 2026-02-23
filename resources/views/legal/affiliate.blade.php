@extends('layouts.app')

@section('title', 'Affiliate Disclosure - ' . config('app.name'))
@section('description', 'Learn how we use affiliate links and how it supports our work.')

@push('styles')
<style>
    .legal-container { max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem; }
    .legal-container h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.25rem);
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    .legal-container h2 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    .legal-container p { margin-bottom: 1rem; color: var(--text); }
    .legal-container ul { margin: 0.75rem 0 1.5rem; padding-left: 1.5rem; }
    .legal-container li { margin-bottom: 0.4rem; }
</style>
@endpush

@section('content')
<div class="legal-container">
    @php
        $raw = \App\Models\SiteContent::get('page_affiliate', \App\Models\SiteContent::defaultPageAffiliateDisclosure());
        $content = is_string($raw) ? $raw : '';
        $content = str_replace('[SITE_NAME]', e(config('app.name')), $content);
    @endphp
    {!! $content !!}
</div>
@endsection

