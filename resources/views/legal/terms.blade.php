@extends('layouts.app')

@section('title', 'Terms of Use - ' . config('app.name'))
@section('description', 'Terms of use for our website and services.')

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
    .legal-container h2 { font-size: 1.2rem; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; }
    .legal-container p { margin-bottom: 1rem; color: var(--text); }
    .legal-container ul { margin: 0.75rem 0 1.5rem; padding-left: 1.5rem; }
    .legal-container li { margin-bottom: 0.4rem; }
</style>
@endpush

@section('content')
<div class="legal-container">
    <h1 class="font-heading">Terms of Use</h1>
    <p class="updated">Last updated: {{ date('F d, Y') }}</p>

    <p>Welcome to <strong>{{ config('app.name') }}</strong>. By using this website, you agree to these Terms of Use.</p>

    <h2>Use of the Website</h2>
    <p>This site provides deal and coupon information for personal, non-commercial use. You may not scrape, copy, or redistribute our content for commercial purposes without permission.</p>

    <h2>Accuracy of Deals</h2>
    <p>We strive to list current and valid offers. Deals and coupons may expire or change without notice. We do not guarantee that every code will work at checkout. Please verify offers on the merchant’s site before purchase.</p>

    <h2>Affiliate Links</h2>
    <p>Some links on this site are affiliate links. We may earn a commission when you make a purchase through these links, at no extra cost to you. See our <a href="{{ url('/affiliate-disclosure') }}">Affiliate Disclosure</a> for details.</p>

    <h2>Third-Party Sites</h2>
    <p>We link to external merchants and sites. We are not responsible for their content, policies, or practices. Your use of third-party sites is at your own risk.</p>

    <h2>Limitation of Liability</h2>
    <p>We provide this site “as is.” We are not liable for any loss or damage arising from your use of our site or reliance on deal information.</p>

    <h2>Changes</h2>
    <p>We may update these Terms from time to time. Continued use of the site after changes means you accept the updated Terms.</p>

    <h2>Contact</h2>
    <p>Questions about these Terms? Please use our <a href="{{ url('/contact') }}">Contact</a> page.</p>
</div>
@endsection
