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
  <h1 class="font-heading" >About ReviewHays</h1>

  <p>
    <strong>ReviewHays</strong> is an independent review and deal discovery platform designed to help consumers make
    smarter purchasing decisions online.
  </p>

  <p>
    In a digital landscape crowded with promotions, discount codes, and sponsored content, our goal is simple:
    to provide clear, honest, and well-researched reviews alongside verified offers that genuinely add value.
  </p>

  <h2 class="font-heading" >Our Mission</h2>
  <p>At ReviewHays, our mission is to:</p>
  <ul>
    <li>Deliver transparent and unbiased product reviews</li>
    <li>Compare products, services, and brands based on real value, not hype</li>
    <li>Curate up-to-date deals, coupons, and promotions from trusted merchants</li>
    <li>Help readers save time and money while shopping online</li>
  </ul>

  <h2 class="font-heading" >How We Create Our Content</h2>
  <p>
    Our editorial process focuses on accuracy, relevance, and usefulness.
  </p>
  <p>
    We research and evaluate products and services using official brand information,
    market comparisons, user feedback, and real-world use cases.
  </p>

  <p>Each review clearly outlines:</p>
  <ul>
    <li>Key features and benefits</li>
    <li>Pros and cons</li>
    <li>Who the product or service is best suited for</li>
    <li>Overall value for money</li>
  </ul>

  <h2 class="font-heading">Affiliate Disclosure & Transparency</h2>
  <p>
    ReviewHays participates in various affiliate marketing programs.
    This means some links on our website may be affiliate links.
  </p>

  <p>
    If you make a purchase through these links, we may earn a small commission at no additional cost to you.
    Affiliate relationships do not influence our editorial decisions.
  </p>

  <p>
    We prioritize accuracy, transparency, and user trust above all else.
  </p>

  <h2 class="font-heading">Why Trust ReviewHays?</h2>
  <ul>
    <li>Independent and research-driven content</li>
    <li>Regularly updated reviews and deals</li>
    <li>Clear affiliate disclosure and transparency</li>
    <li>No misleading claims or exaggerated promotions</li>
    <li>Focused on long-term user value</li>
  </ul>

  <h2 class="font-heading">Get in Touch</h2>
  <p>
    We welcome feedback, questions, and collaboration inquiries.
    Please visit our Contact page to reach out to us.
  </p>

  <p>
    Thank you for choosing <strong>ReviewHays</strong> as your trusted source for reviews, comparisons, and savings.
  </p>
</section>
</div>
@endsection
