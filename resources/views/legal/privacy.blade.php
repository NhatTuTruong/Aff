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
    
    <p>
    At <strong>ReviewHays</strong>, we value your privacy and are committed to protecting your personal information.
    This Privacy Policy explains how we collect, use, and safeguard your data when you visit our website.
  </p>

  <p>
    By using this website, you agree to the practices described in this Privacy Policy.
  </p>

  <h2>Information We Collect</h2>

  <p>We may collect the following types of information:</p>
  <ul>
    <li>Non-personal information such as browser type, device information, operating system, and referral sources</li>
    <li>Usage data including pages visited, time spent on the site, and interactions with content</li>
    <li>Personal information only when voluntarily provided (for example, via a contact form)</li>
  </ul>

  <h2>How We Use Your Information</h2>

  <p>The information we collect may be used to:</p>
  <ul>
    <li>Improve website performance, content quality, and user experience</li>
    <li>Analyze traffic patterns and audience behavior</li>
    <li>Respond to inquiries or communications you initiate</li>
    <li>Maintain website security and prevent fraudulent activity</li>
  </ul>

  <h2>Cookies and Tracking Technologies</h2>

  <p>
    ReviewHays uses cookies and similar tracking technologies to enhance user experience and analyze website traffic.
  </p>

  <p>
    Cookies may be used by third-party services such as analytics providers and affiliate networks to track referrals
    and conversions.
  </p>

  <p>
    You can choose to disable cookies through your browser settings. Please note that doing so may affect
    certain website functionalities.
  </p>

  <h2>Affiliate Links and Third-Party Services</h2>

  <p>
    ReviewHays participates in affiliate marketing programs. Some links on this website are affiliate links,
    which may track clicks or purchases for commission purposes.
  </p>

  <p>
    We do not control how third-party websites collect or use your data.
    We encourage you to review the privacy policies of any external sites you visit through our links.
  </p>

  <h2>Third-Party Advertising</h2>

  <p>
    Third-party vendors, including advertising and analytics partners, may use cookies or similar technologies
    to serve ads or measure performance based on your visits to this and other websites.
  </p>

  <p>
    Users may opt out of personalized advertising by adjusting their browser settings or using industry opt-out tools.
  </p>

  <h2>Data Protection and Security</h2>

  <p>
    We implement reasonable security measures to protect your information.
    However, no method of transmission over the internet or electronic storage is 100% secure.
  </p>

  <p>
    We cannot guarantee absolute security, but we strive to protect your data using commercially acceptable means.
  </p>

  <h2>Your Privacy Rights</h2>

  <p>
    Depending on your location, you may have certain rights regarding your personal data, including:
  </p>
  <ul>
    <li>The right to access the personal data we hold about you</li>
    <li>The right to request correction or deletion of your data</li>
    <li>The right to restrict or object to certain data processing activities</li>
  </ul>

  <p>
    To exercise these rights, please contact us through the information provided on our Contact page.
  </p>

  <h2>Children’s Information</h2>

  <p>
    ReviewHays does not knowingly collect personal information from children under the age of 13.
    If you believe that a child has provided personal information on our website, please contact us
    so we can promptly remove such data.
  </p>

  <h2>Changes to This Privacy Policy</h2>

  <p>
    We may update this Privacy Policy from time to time to reflect changes in legal requirements or website practices.
    Any updates will be posted on this page with a revised effective date.
  </p>

  <h2>Contact Us</h2>

  <p>
    If you have any questions about this Privacy Policy or how we handle your data,
    please visit our Contact page to get in touch.
  </p>
</div>
@endsection
