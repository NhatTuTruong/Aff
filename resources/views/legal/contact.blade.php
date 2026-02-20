@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))
@section('description', 'Get in touch with us for questions and feedback.')

@push('styles')
<style>
    .legal-container { max-width: 800px; margin: 0 auto; padding: 3rem 1.5rem; }
    .legal-container h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.75rem, 4vw, 2.25rem);
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    .legal-container p { margin-bottom: 1rem; color: var(--text); }
    .legal-container p:last-child { margin-bottom: 0; }
    .legal-container a { color: var(--accent); text-decoration: none; }
    .legal-container a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="legal-container">
    <h1 class="font-heading">Contact Us</h1>
    <p>If you have any questions or concerns, please feel free to reach out to us.</p>
    <p>Email: {{ config('mail.from.address', 'contact@reviewshays.com') }}</p>
    <p>We aim to respond to all inquiries within 24-48 hours.</p>
</div>
@endsection
