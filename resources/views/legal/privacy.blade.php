@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('app.name'))
@section('description', 'Read our privacy policy and how we handle your data.')

@push('styles')
<style>
    .legal-container { max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem; }
    .legal-container h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.25rem);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .legal-container .updated { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; }
    .legal-container h2 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    .legal-container p { margin-bottom: 1rem; color: var(--text); }
    .legal-container p:last-child { margin-bottom: 0; }
</style>
@endpush

@section('content')
<div class="legal-container">
    <h1 class="font-heading">Privacy Policy</h1>
    <p class="updated">Last updated: {{ date('F d, Y') }}</p>
    
    <h2>Information We Collect</h2>
    <p>We collect information that you provide directly to us, including when you interact with our website.</p>
    
    <h2>How We Use Your Information</h2>
    <p>We use the information we collect to provide, maintain, and improve our services.</p>
    
    <h2>Affiliate Disclosure</h2>
    <p>This website contains affiliate links. We may earn a commission if you make a purchase through our links. This does not affect the price you pay.</p>
    
    <h2>Cookies</h2>
    <p>We use cookies to enhance your experience on our website. You can choose to disable cookies through your browser settings.</p>
    
    <h2>Contact Us</h2>
    <p>If you have any questions about this Privacy Policy, please contact us.</p>
</div>
@endsection
