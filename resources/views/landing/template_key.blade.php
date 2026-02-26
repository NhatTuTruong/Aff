<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $slugParts = explode('/', $campaign->slug, 2);
        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $campaign->slug;
        $backgroundImage = $campaign->background_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($campaign->background_image) : null;
        $productImages = $campaign->key_product_images ?? [];
        $logoUrl = $campaign->logo ? \Illuminate\Support\Facades\Storage::disk('public')->url($campaign->logo) : ($campaign->brand?->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($campaign->brand->image) : asset('images/placeholder.svg'));
    @endphp
    <title>{{ $campaign->title }}</title>
    <meta name="description" content="{{ $campaign->subtitle ?? strip_tags($campaign->intro ?? '') }}">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.site-chrome-styles')
    @if(config('app.ga4_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga4_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('app.ga4_id') }}');
    </script>
    @endif

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --accent: #f59e0b;
            --text-dark: #111827;
            --text-light: #6b7280;
            --bg-page: #ffffff;
            --bg-overlay: rgba(0, 0, 0, 0.5);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }

        /* Background với overlay */
        .page-wrapper {
            position: relative;
            min-height: 100vh;
            @if($backgroundImage)
            background-image: url('{{ $backgroundImage }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            @else
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            @endif
        }
        .page-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Container */
        .container {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 60px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }
        .title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 16px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }
        .subtitle {
            font-size: clamp(1.1rem, 2vw, 1.5rem);
            color: rgba(255, 255, 255, 0.95);
            font-weight: 400;
            max-width: 800px;
            margin: 0 auto;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }
        .disclosure-line {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 16px;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto;
        }
        .disclosure-line a {
            color: #93c5fd;
            text-decoration: underline;
        }

        /* Content Section */
        .content-section {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 40px;
            backdrop-filter: blur(10px);
        }
        .intro {
            font-size: 1.125rem;
            color: var(--text-light);
            line-height: 1.8;
            margin-bottom: 40px;
        }
        .intro p {
            margin-bottom: 16px;
        }

        /* Product Images Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin: 40px 0;
        }
        .product-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
        }
        .product-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }
        .product-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 20px;
            color: #ffffff;
        }

        /* CTA Button */
        .cta-section {
            text-align: center;
            margin-top: 50px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
            padding: 20px 50px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
        }
        .cta-button:active {
            transform: scale(0.98);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 20px 15px; }
            .content-section { padding: 30px 20px; }
            .product-grid { grid-template-columns: 1fr; gap: 20px; }
            .product-image { height: 250px; }
            .cta-button { padding: 16px 40px; font-size: 1.1rem; }
        }

        /* Loading animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .content-section, .product-item {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <header class="header">
                <img src="{{ $logoUrl }}" alt="{{ $campaign->brand?->name ?? $campaign->title }}" class="logo">
                <h1 class="title">{{ $campaign->title }}</h1>
                @if($campaign->subtitle)
                <p class="subtitle">{{ $campaign->subtitle }}</p>
                @endif
                <p class="disclosure-line">We may earn a commission when you use our links, at no extra cost to you. <a href="{{ url('/affiliate-disclosure') }}" target="_blank" rel="noopener">See our disclosure</a>.</p>
            </header>

            <div class="content-section">
                @if($campaign->intro)
                    @php
                        $intro = (string) $campaign->intro;
                        $hasHtml = $intro !== strip_tags($intro);
                    @endphp
                    <div class="intro">
                        @if($hasHtml)
                            {!! $intro !!}
                        @else
                            {!! nl2br(e($intro)) !!}
                        @endif
                    </div>
                @endif

                @if(count($productImages) > 0)
                <div class="product-grid">
                    @foreach($productImages as $image)
                        @php
                            $imageUrl = is_string($image) ? \Illuminate\Support\Facades\Storage::disk('public')->url($image) : (\Illuminate\Support\Facades\Storage::disk('public')->url($image['path'] ?? $image) ?? '');
                        @endphp
                        @if($imageUrl)
                        <div class="product-item">
                            <img src="{{ $imageUrl }}" alt="Product" class="product-image" loading="lazy">
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif

                <div class="cta-section">
                    <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}" class="cta-button" target="_blank" rel="nofollow sponsored noopener">
                        {{ $campaign->cta_text ?? 'Nhận ngay' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Track page view time
        let startTime = Date.now();
        window.addEventListener('beforeunload', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_view_time', {
                    'value': Math.round((Date.now() - startTime) / 1000)
                });
            }
        });
    </script>
</body>
</html>
