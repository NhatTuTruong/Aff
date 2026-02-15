<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', 'Best coupons, deals and store reviews.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0c0c0f;
            --surface: #16161a;
            --surface-hover: #1e1e24;
            --text: #f4f4f5;
            --text-muted: #a1a1aa;
            --accent: #f59e0b;
            --accent-hover: #d97706;
            --border: #27272a;
            --radius: 12px;
            --radius-sm: 8px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }

        /* Header */
        .site-header {
            background: rgba(12, 12, 15, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .logo span { color: var(--accent); }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.75rem;
        }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--accent); }

        /* Main */
        main { flex: 1; }

        /* Footer */
        .site-footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 1.5rem 2rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 2.5rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
        .footer-brand .logo { font-size: 1.2rem; }
        .footer-brand p {
            margin-top: 0.75rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            max-width: 260px;
        }
        .footer-col h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 0.5rem; }
        .footer-col a {
            color: var(--text);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .footer-col a:hover { color: var(--accent); }
        .footer-bottom {
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-bottom p { color: var(--text-muted); font-size: 0.875rem; }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="logo font-heading">{{ config('app.name') }}<span>.</span></a>
            <nav class="nav-links">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                <a href="{{ route('legal.about') }}">About Us</a>
                <a href="{{ route('legal.contact') }}">Contact</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="logo font-heading">{{ config('app.name') }}<span>.</span></a>
                    <p>Coupons, promotions and trusted store reviews. Updated regularly.</p>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('blog.index') }}">Review Blog</a></li>
                        <li><a href="{{ url('/') }}#stores">Stores</a></li>
                        <li><a href="{{ url('/') }}#coupons">Coupons</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('legal.about') }}">About Us</a></li>
                        <li><a href="{{ route('legal.contact') }}">Contact</a></li>
                        <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Links</h4>
                    <ul>
                        <li><a href="{{ route('legal.contact') }}">Feedback</a></li>
                        <li><a href="{{ route('legal.privacy') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
