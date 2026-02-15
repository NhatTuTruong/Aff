@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))
@section('description', 'Learn about our mission to provide the best deals and coupons.')

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
    .legal-container p:last-child { margin-bottom: 0; }
</style>
@endpush

@section('content')
<div class="legal-container">
    <h1 class="font-heading">About Us</h1>
    <p>We are dedicated to providing you with the best deals and offers available online.</p>
    <p>Our mission is to help you save money while discovering great products and services.</p>
    <p>We carefully review and curate all offers to ensure quality and value for our visitors.</p>
</div>
@endsection
