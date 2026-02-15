<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $coupons = $campaign->couponItems ?? collect();
        $maxPercent = 0;
        foreach ($coupons as $c) {
            $offerText = (string) ($c->offer ?? '');
            if (preg_match('/(\d+)\s*%/i', $offerText, $m)) {
                $val = (int) $m[1];
                if ($val > $maxPercent) {
                    $maxPercent = $val;
                }
            }
        }
        if ($maxPercent <= 0) {
            $maxPercent = 75;
        }
    @endphp
    <title>{{ $campaign->title }} - Exclusive Deals & Coupons</title>
    <meta name="description" content="{{ $campaign->subtitle ?? $campaign->intro }}">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
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
        /* ===== BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary:#22c55e;
            --primary-dark:#16a34a;
            --primary-soft:#bbf7d0;
            --accent:#f97316;
            --text-dark:#111827;
            --text-light:#6b7280;
            --bg-page:#f9fafb;
            --bg-card:#ffffff;
            --border:#e5e7eb;
            --shadow:0 1px 3px rgba(15,23,42,0.08);
            --shadow-md:0 4px 12px rgba(15,23,42,0.1);
            --shadow-lg:0 20px 50px rgba(15,23,42,0.15);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont,
                        'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-dark);
            background: var(--bg-page);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        a { text-decoration:none;color:inherit; transition: color 0.2s ease; }

        /* ===== SHELL LAYOUT (CENTER WHITE PANEL) ===== */
        .shell {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 30px 20px 50px;
        }
        .page-panel {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 32px 32px 40px;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        /* ===== HERO (TOP TITLE LIKE TENERE) ===== */
        .hero {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }
        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 10px;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-dark) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1rem;
            color: var(--text-light);
            line-height: 1.6;
        }
        .hero-sub strong {
            color: var(--primary);
            font-weight: 700;
        }

        /* ===== MAIN GRID (LEFT STORE CARD + RIGHT CONTENT) ===== */
        .page {
            margin-top: 28px;
            display:grid;
            grid-template-columns:340px 1fr;
            gap:28px;
        }
        .left-card {
            background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
            border-radius:20px;
            padding:28px 24px 28px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(229, 231, 235, 0.8);
            position:relative;
            display:flex;
            flex-direction:column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .left-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .brand-logo {
            max-width:200px;height:auto;object-fit:contain;
            display:block;margin:0 auto 16px;
            border-radius: 16px;
            background:#fff;
            padding: 12px;
            box-shadow:0 10px 30px rgba(0,0,0,0.12);
            transition: transform 0.3s ease;
        }
        .brand-logo:hover {
            transform: scale(1.05);
        }
        .brand-name {
            font-size:1.25rem;
            font-weight: 800;
            text-align:center;
            margin-bottom:8px;
            letter-spacing: -0.02em;
        }
        .brand-rating {
            display:flex;align-items:center;gap:8px;
            justify-content:center;
            margin-bottom:10px;font-size:0.8rem;
        }
        .stars { color:#fde047; }
        .brand-meta { font-size:0.75rem;opacity:0.9;text-align:center;margin-bottom:4px; }
        .stats-list {
            border-top:1px solid #e5e7eb;
            margin-top:10px;
            padding-top:10px;
            font-size:0.8rem;
        }
        .stats-row {
            display:flex;
            justify-content:space-between;
            padding:4px 0;
        }
        .stats-label { color:var(--text-light); }
        .stats-value { font-weight:600; }
        .sidebar-actions {
            margin-top:14px;
            display:flex;
            gap:8px;
        }
        .btn-outline,
        .btn-solid {
            flex:1;
            border-radius:999px;
            padding:10px 14px;
            font-size:0.9rem;
            font-weight:700;
            cursor:pointer;
            border:1px solid transparent;
            text-align:center;
            transition: all 0.2s ease;
        }
        .btn-outline {
            border-color:#d1d5db;
            background:#fff;
            color: var(--text-dark);
        }
        .btn-outline:hover {
            background:#f9fafb;
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-solid {
            background:var(--primary);
            color:#fff;
            box-shadow: 0 4px 12px rgba(34,197,94,0.3);
        }
        .btn-solid:hover { 
            background:var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(34,197,94,0.4);
        }

        .right-column { display:flex;flex-direction:column;gap:18px; }

        /* NOTE BAR & FILTERS */
        .note-bar {
            font-size:0.85rem;
            color:var(--text-light);
            background:linear-gradient(135deg, #f0fdf4 0%, #f9fafb 100%);
            border-radius:12px;
            padding:12px 18px;
            margin-bottom:16px;
            border: 1px solid rgba(34,197,94,0.1);
            line-height: 1.6;
        }
        .coupon-header {
            margin-bottom:10px;
        }
        .coupon-header-title {
            font-size:1.3rem;
            font-weight:800;
            letter-spacing:-0.03em;
            margin-bottom:6px;
            color: var(--text-dark);
        }
        .coupon-header-meta {
            font-size:0.9rem;
            color:var(--text-light);
            line-height: 1.6;
        }
        .filter-tabs {
            display:flex;
            flex-wrap:wrap;
            gap:8px;
            margin-top:10px;
        }
        .filter-pill {
            border-radius:999px;
            padding:8px 16px;
            font-size:0.85rem;
            border:1px solid #e5e7eb;
            background:#fff;
            cursor:pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            min-width: fit-content;
        }
        .filter-pill:hover {
            border-color: var(--primary);
            background: #f0fdf4;
        }
        .filter-pill.active {
            background:var(--primary);
            border-color:var(--primary);
            color:#fff;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(34,197,94,0.3);
        }

        .coupon-list { display:flex;flex-direction:column;gap:16px;margin-top:6px; }
        .coupon-row {
            background:#fff;border-radius:20px;
            box-shadow:0 4px 16px rgba(15,23,42,0.08);
            padding:0;
            display:grid;grid-template-columns:180px 1fr;
            gap:0;
            align-items:stretch;
            transition: all 0.3s ease;
            border: 1px solid rgba(229, 231, 235, 0.5);
            overflow: hidden;
            position: relative;
        }
        .coupon-row:hover {
            box-shadow:0 8px 24px rgba(15,23,42,0.12);
            transform: translateY(-2px);
        }
        
        /* Left section - Discount Visual */
        .coupon-discount-visual {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .coupon-discount-visual::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: repeating-linear-gradient(
                to bottom,
                #d1d5db 0px,
                #d1d5db 8px,
                transparent 8px,
                transparent 16px
            );
        }
        .discount-up-to {
            font-size: 0.7rem;
            font-weight: 600;
            color: #f97316;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .discount-percent {
            font-size: 3rem;
            font-weight: 900;
            color: #111827;
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -0.05em;
            word-break: break-word;
        }
        .discount-off-badge {
            background: #f97316;
            color: #fff;
            padding: 5px 14px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Right section - Coupon Details */
        .coupon-info { 
            display:flex;
            flex-direction:column;
            gap:8px;
            padding: 18px 20px;
            position: relative;
        }
        .coupon-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 2px;
        }
        .coupon-title { 
            font-weight:700;
            font-size:1rem;
            letter-spacing: -0.01em;
            line-height: 1.35;
            flex: 1;
            color: #111827;
        }
        .btn-get-code {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }
        .btn-get-code:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }
        .btn-get-code::after {
            content: '↗';
            font-size: 0.85rem;
        }
        .staff-pick-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #9333ea;
            color: #fff;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .staff-pick-badge::before {
            content: '⭐';
            font-size: 0.65rem;
        }
        .coupon-meta-info {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 6px;
            font-size: 0.8rem;
            color: #6b7280;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .meta-item::before {
            content: '✓';
            color: var(--primary);
            font-weight: 700;
            font-size: 0.85rem;
        }
        .meta-item.uses::before {
            content: '👥';
        }
        .coupon-desc { 
            font-size:0.85rem;
            color:#6b7280;
            line-height: 1.5;
            margin-bottom: 6px;
        }
        .coupon-desc .show-more {
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .coupon-desc .show-more:hover {
            text-decoration: underline;
        }
        .coupon-verification {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: auto;
            padding-top: 6px;
            border-top: 1px solid #f3f4f6;
        }
        .coupon-verification::before {
            content: '👤';
            font-size: 0.85rem;
        }
        .coupon-verification::after {
            content: '▼';
            font-size: 0.65rem;
            margin-left: auto;
            cursor: pointer;
        }
        
        .coupon-actions {
            display:none;
        }
        .coupon-code {
            display: none;
        }
        
        .section {
            background:#fff;border-radius:16px;
            padding:24px 26px 26px;box-shadow:var(--shadow-md);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }
        .section:hover {
            box-shadow: var(--shadow-lg);
        }
        .section-title { 
            font-size:1.2rem;
            font-weight:800;
            margin-bottom:14px;
            letter-spacing: -0.02em;
            color: var(--text-dark);
        }
        .section-body { 
            font-size:0.95rem;
            color:var(--text-dark);
            line-height: 1.75;
        }
        .section-body ul { padding-left:18px;margin:6px 0; }
        .section-body li { margin-bottom:4px; }
        
        /* Intro content styling */
        .intro-content {
            line-height: 1.75;
        }
        .intro-content p {
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        .intro-content p:last-child {
            margin-bottom: 0;
        }
        .intro-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .intro-content h1,
        .intro-content h2,
        .intro-content h3,
        .intro-content h4,
        .intro-content h5,
        .intro-content h6 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }
        .intro-content h1 { font-size: 1.5rem; }
        .intro-content h2 { font-size: 1.3rem; }
        .intro-content h3 { font-size: 1.15rem; }
        .intro-content ul,
        .intro-content ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }
        .intro-content li {
            margin-bottom: 0.5rem;
        }
        .intro-content a {
            color: var(--primary);
            text-decoration: underline;
        }
        .intro-content a:hover {
            color: var(--primary-dark);
        }
        .intro-content blockquote {
            border-left: 4px solid var(--primary);
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: var(--text-light);
        }
        .intro-content code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
            font-family: ui-monospace, monospace;
        }
        .intro-content pre {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1rem 0;
        }
        .intro-content pre code {
            background: none;
            padding: 0;
        }

        /* ===== ADDED (NO CONFLICT) ===== */
        .coupon-code.peek {
            filter:blur(3px);
            opacity:0.75;
            pointer-events:none;
        }

        .coupon-modal {
            position:fixed;inset:0;
            background:rgba(0,0,0,0.55);
            display:none;align-items:center;justify-content:center;
            z-index:9999;
        }
        .coupon-modal.active { display:flex; }
        .modal-box {
            background:#fff;border-radius:14px;
            width:420px;max-width:92%;
            padding:24px;text-align:center;
            position:relative;
        }
        .modal-close {
            position:absolute;top:10px;right:14px;
            cursor:pointer;font-size:20px;
        }
        .modal-logo img {
            max-width:80px;border-radius:50%;
            margin-bottom:12px;
        }
        .modal-code {
            display:flex;justify-content:center;
            margin:14px 0;
        }
        .modal-code span {
            padding:10px 18px;
            border:2px dashed #22c55e;
            font-weight:700;
        }
        .modal-code button {
            margin-left:6px;
            padding:10px 14px;
            background:#22c55e;color:#fff;
            border:none;cursor:pointer;font-weight:700;
        }
        .modal-link { color:#22c55e;font-weight:600; }
        @media(max-width:960px){ .page{grid-template-columns:1fr;} }

        /* ===== Coupon Popup ===== */
    .coupon-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }

    .coupon-modal.active {
        opacity: 1;
        pointer-events: auto;
    }

    .coupon-modal-content {
        background: #ffffff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(15,23,42,.35);
        animation: popupScale .3s ease;
        position: relative;
    }
    
    .popup-banner {
        background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
        padding: 32px 28px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .popup-confetti {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        overflow: hidden;
    }
    
    .confetti-piece {
        position: absolute;
        width: 8px;
        height: 8px;
        background: currentColor;
        opacity: 0.7;
        animation: confettiFall 3s linear infinite;
    }
    
    .confetti-piece:nth-child(1) { left: 10%; color: #fbbf24; animation-delay: 0s; }
    .confetti-piece:nth-child(2) { left: 20%; color: #a78bfa; animation-delay: 0.5s; }
    .confetti-piece:nth-child(3) { left: 30%; color: #60a5fa; animation-delay: 1s; }
    .confetti-piece:nth-child(4) { left: 40%; color: #f97316; animation-delay: 1.5s; }
    .confetti-piece:nth-child(5) { left: 50%; color: #fbbf24; animation-delay: 0.3s; }
    .confetti-piece:nth-child(6) { left: 60%; color: #a78bfa; animation-delay: 0.8s; }
    .confetti-piece:nth-child(7) { left: 70%; color: #60a5fa; animation-delay: 1.3s; }
    .confetti-piece:nth-child(8) { left: 80%; color: #f97316; animation-delay: 1.8s; }
    .confetti-piece:nth-child(9) { left: 90%; color: #fbbf24; animation-delay: 0.6s; }
    
    @keyframes confettiFall {
        0% {
            transform: translateY(-100px) rotate(0deg);
            opacity: 0.7;
        }
        100% {
            transform: translateY(200px) rotate(360deg);
            opacity: 0;
        }
    }
    
    .popup-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #fff;
        padding: 12px;
        margin: 0 auto 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        position: relative;
        z-index: 1;
        object-fit: contain;
    }
    
    .popup-header {
        position: relative;
        z-index: 1;
    }
    
    .popup-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .popup-subtitle {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.95);
        margin: 12px 0 0;
        line-height: 1.5;
    }
    
    .popup-body {
        padding: 24px 28px 28px;
    }

    @keyframes popupScale {
        from { transform: scale(.92); opacity: .5; }
        to { transform: scale(1); opacity: 1; }
    }

    .coupon-modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        color: #fff;
        z-index: 10;
        transition: all 0.2s ease;
    }

    .coupon-modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .coupon-code-container {
        background: #374151;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }
    
    .coupon-code-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .coupon-code-icon {
        width: 24px;
        height: 24px;
        background: #84cc16;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    
    .coupon-code-box {
        background: transparent;
        border: none;
        padding: 0;
        text-align: left;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #fff;
        font-family: ui-monospace, monospace;
    }
    
    .btn-copy-code-modal {
        background: #84cc16;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(132, 204, 22, 0.3);
    }
    
    .btn-copy-code-modal:hover {
        background: #65a30d;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(132, 204, 22, 0.4);
    }
    
    .btn-copy-code-modal:active {
        transform: translateY(0);
    }
    
    .popup-verification {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #9ca3af;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f3f4f6;
    }
    
    .verification-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .verification-item::before {
        content: '✓';
        color: #22c55e;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .verification-item.success::before {
        content: '●';
        color: #22c55e;
    }

    .coupon-modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .coupon-btn {
        flex: 1;
        padding: 12px 14px;
        border-radius: 999px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }

    .coupon-btn.store {
        background: var(--primary);
        color: #ffffff;
    }

    .coupon-btn.store:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .popup-feedback {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }
    
    .popup-feedback-question {
        text-align: center;
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }
    
    .popup-feedback-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    .feedback-btn {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        flex: 1;
        max-width: 180px;
        justify-content: center;
    }
    
    .feedback-btn:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .feedback-btn:active {
        transform: scale(0.98);
    }
    
    .feedback-btn.worked {
        border-color: #22c55e;
        color: #22c55e;
    }
    
    .feedback-btn.failed {
        border-color: #ef4444;
        color: #ef4444;
    }
    
    .feedback-icon {
        font-size: 1.1rem;
    }


    /* Q&A Section */
    .qa-section {
        padding-top: 20px;
    }

    .qa-item {
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        background: #fff;
    }

    .qa-question {
        width: 100%;
        background: none;
        border: none;
        padding: 14px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-dark);
    }

    .qa-question:hover {
        background: var(--bg-light);
    }

    .qa-icon {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .qa-answer {
        max-height: 0;
        overflow: hidden;
        padding: 0 16px;
        font-size: 0.85rem;
        color: var(--text-light);
        transition: all 0.35s ease;
    }

    .qa-item.active .qa-answer {
        max-height: 200px;
        padding: 10px 16px 16px;
    }

    .qa-item.active .qa-icon {
        transform: rotate(45deg);
    }

    /* Popup Header */
    .popup-header {
        text-align: center;
        margin-bottom: 16px;
    }

    .popup-logo {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 50%;
        margin: 0 auto 10px;
        display: block;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        background: #fff;
    }

    .popup-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--text-dark);
    }

    .popup-subtitle {
        font-size: 0.8rem;
        color: while;
    }

    h1, h2, h3, .brand-name, .section-title, .popup-title {
        letter-spacing: -0.015em;
    }

    .brand-name {
        font-weight: 800;
    }

    .section-title {
        font-weight: 700;
    }

    .coupon-title {
        font-weight: 600;
    }

    /* ===== Review Block Improvements ===== */
.review-center {
    text-align: center;
    margin-bottom: 14px;
}

.review-center .brand-logo {
    margin: 0 auto 12px;
    border-radius: 50%;
    background: #fff;
    padding: 6px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
}

.review-stars {
    font-size: 1.25rem; /* ⭐ to hơn */
    color: #fde047;
    margin-bottom: 6px;
}

.review-rating-text {
    font-size: 0.85rem;
    opacity: 0.95;
    margin-bottom: 14px;
}

.btn-get-coupon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 18px;
    border-radius: 999px;
    background: #22c55e;
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    box-shadow: 0 10px 24px rgba(34,197,94,0.35);
}

.btn-get-coupon:hover {
    transform: translateY(-1px);
    background: #16a34a;
    cursor: pointer;
}

/* Coupon Intro Header */
.coupon-intro {
    padding: 18px 20px;
}

.coupon-intro-title {
    font-size: 1.15rem;
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.coupon-intro-desc {
    font-size: 0.85rem;
    color: var(--text-light);
    line-height: 1.6;
}

/* Go to Store highlight animation */
@keyframes pulseGlow {
    0% {
        box-shadow: 0 0 0 rgba(34,197,94,0.0);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 0 25px rgba(34,197,94,0.65);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 0 0 rgba(34,197,94,0.0);
        transform: scale(1);
    }
}

.go-store-attention {
    animation: pulseGlow 0.9s ease-in-out 3;
}

        @media(max-width:1024px){
            .page{
                grid-template-columns:1fr;
            }
            .shell {
                padding: 20px 16px 40px;
            }
            .page-panel {
                padding: 24px 20px 32px;
            }
            .hero-title {
                font-size: 1.6rem;
            }
        }
        @media(max-width:768px){
            .coupon-row {
                grid-template-columns: 1fr;
            }
            .coupon-discount-visual::after {
                display: none;
            }
            .coupon-discount-visual {
                border-bottom: 2px dashed #d1d5db;
                padding: 16px;
            }
            .coupon-info {
                padding: 16px 18px;
            }
            .coupon-header-row {
                flex-direction: column;
                gap: 12px;
            }
            .btn-get-code {
                width: 100%;
                padding: 12px 20px;
            }
            .coupon-title {
                font-size: 0.95rem;
            }
            .discount-percent {
                font-size: 2.5rem;
            }
            .popup-banner {
                padding: 24px 20px 32px;
            }
            .popup-logo {
                width: 70px;
                height: 70px;
            }
            .popup-title {
                font-size: 1.2rem;
            }
            .popup-subtitle {
                font-size: 0.85rem;
            }
            .coupon-code-container {
                flex-direction: column;
                gap: 12px;
            }
            .coupon-code-left {
                width: 100%;
            }
            .btn-copy-code-modal {
                width: 100%;
            }
            .popup-feedback-buttons {
                flex-direction: column;
            }
            .feedback-btn {
                max-width: 100%;
            }
        }
        
        @media(max-width:640px){
            .hero-title {
                font-size: 1.4rem;
                line-height: 1.3;
            }
            .hero-sub {
                font-size: 0.9rem;
            }
            .shell {
                padding: 16px 12px 32px;
            }
            .page-panel {
                padding: 20px 16px 28px;
            }
            .section {
                padding: 18px 16px 20px;
                border-radius: 14px;
            }
            .section-title {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            .section-body {
                font-size: 0.9rem;
            }
            .coupon-row {
                border-radius: 16px;
            }
            .coupon-discount-visual {
                padding: 14px 12px;
            }
            .discount-up-to {
                font-size: 0.65rem;
                margin-bottom: 4px;
            }
            .discount-percent {
                font-size: 2.2rem;
                margin-bottom: 6px;
            }
            .discount-off-badge {
                padding: 4px 12px;
                font-size: 0.8rem;
            }
            .coupon-info {
                padding: 14px 16px;
                gap: 6px;
            }
            .coupon-title {
                font-size: 0.9rem;
                line-height: 1.3;
            }
            .staff-pick-badge {
                font-size: 0.65rem;
                padding: 2px 6px;
            }
            .coupon-meta-info {
                font-size: 0.75rem;
                gap: 10px;
            }
            .coupon-desc {
                font-size: 0.8rem;
            }
            .coupon-verification {
                font-size: 0.7rem;
                padding-top: 4px;
            }
            .btn-get-code {
                font-size: 0.85rem;
                padding: 10px 16px;
            }
            .filter-tabs {
                gap: 6px;
            }
            .filter-pill {
                font-size: 0.8rem;
                padding: 6px 12px;
            }
            .coupon-header-title {
                font-size: 1.15rem;
            }
            .coupon-header-meta {
                font-size: 0.85rem;
            }
            .coupon-modal-content {
                max-width: 95%;
                margin: 20px;
            }
            .popup-banner {
                padding: 20px 16px 28px;
            }
            .popup-logo {
                width: 60px;
                height: 60px;
                margin-bottom: 12px;
            }
            .popup-title {
                font-size: 1.1rem;
            }
            .popup-subtitle {
                font-size: 0.8rem;
                margin-top: 8px;
            }
            .popup-body {
                padding: 20px 16px 24px;
            }
            .coupon-code-container {
                padding: 12px 16px;
            }
            .coupon-code-box {
                font-size: 1rem;
            }
            .btn-copy-code-modal {
                font-size: 0.85rem;
                padding: 10px 16px;
            }
            .popup-verification {
                font-size: 0.75rem;
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }
            .coupon-modal-actions {
                margin-top: 16px;
            }
            .coupon-btn.store {
                width: 100%;
                padding: 12px 16px;
            }
            .popup-feedback {
                margin-top: 16px;
                padding-top: 16px;
            }
            .popup-feedback-question {
                font-size: 0.9rem;
                margin-bottom: 10px;
            }
            .feedback-btn {
                font-size: 0.85rem;
                padding: 10px 16px;
            }
            .qa-question {
                font-size: 0.9rem;
                padding: 12px 14px;
            }
            .qa-answer {
                font-size: 0.85rem;
                padding: 0 14px 12px;
            }
        }
        
        @media(max-width:480px){
            .hero-title {
                font-size: 1.25rem;
            }
            .hero-sub {
                font-size: 0.85rem;
            }
            .shell {
                padding: 12px 10px 24px;
            }
            .page-panel {
                padding: 16px 12px 24px;
            }
            .section {
                padding: 16px 14px 18px;
            }
            .discount-percent {
                font-size: 2rem;
            }
            .coupon-code-box {
                font-size: 0.95rem;
            }
            .popup-banner {
                padding: 18px 14px 24px;
            }
            .popup-body {
                padding: 18px 14px 20px;
            }
        }


</style>
</head>

<body>
@include('partials.site-header')
<div class="shell">
    <div class="page-panel">
    <header class="hero">
        <h1 class="hero-title">
            {{ $campaign->brand->name ?? $campaign->title }} Coupons &amp; Promo Codes
        </h1>
        <p class="hero-sub">
            Save up to <strong>{{ $maxPercent }}% off</strong> with verified discount codes &amp; exclusive deals.
        </p>
    </header>

    <div class="page">
    <!-- LEFT CARD -->
    <aside class="left-card">
        @if($campaign->brand && $campaign->brand->image)
            <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                alt="{{ $campaign->brand->name }}"
                class="brand-logo">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}"
                alt="{{ $campaign->title }}"
                class="brand-logo">
        @endif

        <div class="brand-name">
            {{ $campaign->brand->name ?? $campaign->title }}
        </div>
        <div class="brand-rating">
            <span class="stars">★★★★★</span>
            <span>4.8 rating • 1,200+ reviews</span>
        </div>
        <div class="stats-list">
            <div class="stats-row">
                <span class="stats-label">Working codes</span>
                <span class="stats-value">{{ ($campaign->couponItems ?? collect())->count() }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">Success rate</span>
                <span class="stats-value">94%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">Total saved</span>
                <span class="stats-value">$7,229</span>
            </div>
        </div>
        <div class="sidebar-actions">
            <button class="btn-outline" type="button">
                Reviews &amp; Info
            </button>
            <button class="btn-solid" type="button"
                onclick="window.location.href='{{ route('click.redirect',$campaign->slug) }}'">
                Shop Now
            </button>
        </div>
    </aside>

    <!-- RIGHT COLUMN -->
    <main class="right-column">
        <section>
            <div class="note-bar">
                Purchases through our links may earn us a commission, which helps us keep finding the best deals for you.
            </div>
            <div class="coupon-header">
                <div class="coupon-header-title">
                    Active {{ $campaign->brand->name ?? $campaign->title }} coupons
                </div>
                <div class="coupon-header-meta">
                    Last checked: 18 hours ago • Tracking coupons in the last 7 days.
                </div>
                <div class="filter-tabs">
                    <button class="filter-pill active" type="button">All Deals</button>
                    <button class="filter-pill" type="button">Codes</button>
                    <button class="filter-pill" type="button">Deals</button>
                    <button class="filter-pill" type="button">Free Shipping</button>
                    <button class="filter-pill" type="button">First Order</button>
                </div>
            </div>

        <section class="coupon-list">
            @forelse($coupons as $coupon)
            @php
                $hasCode = !empty($coupon->code);
                $offerText = (string) ($coupon->offer ?? '');
                $descriptionText = (string) ($coupon->description ?? '');
                
                // Check for Free Shipping in both offer and description
                $isFreeShipping = stripos($offerText, 'free shipping') !== false || 
                                  stripos($descriptionText, 'free shipping') !== false;
                $tags = [];
                if ($isFreeShipping) {
                    $tags[] = 'free-shipping';
                }
                $tagAttr = implode(',', $tags);
                
                // Extract offer value (percentage or currency)
                $offerValue = null;
                $offerType = 'percent'; // 'percent', 'currency', or 'text'
                $currencySymbol = '';
                
                if ($offerText) {
                    // Check for percentage first
                    if (preg_match('/(\d+)\s*%/i', $offerText, $matches)) {
                        $offerValue = (int)$matches[1];
                        $offerType = 'percent';
                    }
                    // Check for currency symbols before number: $100, €200, £50
                    elseif (preg_match('/([€$£¥₹])\s*(\d+(?:[.,]\d+)?)/i', $offerText, $matches)) {
                        $currencySymbol = $matches[1];
                        $offerValue = $matches[2];
                        $offerType = 'currency';
                    }
                    // Check for currency symbols after number: 100$, 200€
                    elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*([€$£¥₹])/i', $offerText, $matches)) {
                        $currencySymbol = $matches[2];
                        $offerValue = $matches[1];
                        $offerType = 'currency';
                    }
                    // Check for currency codes: USD, EUR, GBP, etc. followed by number
                    elseif (preg_match('/(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)\s*(\d+(?:[.,]\d+)?)/i', $offerText, $matches)) {
                        $currencyCode = strtoupper($matches[1]);
                        $currencyMap = [
                            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 
                            'INR' => '₹', 'CAD' => 'C$', 'AUD' => 'A$', 
                            'CHF' => 'CHF', 'CNY' => '¥'
                        ];
                        $currencySymbol = $currencyMap[$currencyCode] ?? $currencyCode;
                        $offerValue = $matches[2];
                        $offerType = 'currency';
                    }
                    // If Free Shipping, set as text type
                    elseif ($isFreeShipping) {
                        $offerValue = 'FREE';
                        $offerType = 'text';
                    }
                }
                
                // Default fallback
                if ($offerValue === null) {
                    $offerValue = 15;
                    $offerType = 'percent';
                }
                
                // Generate random usage stats
                $hoursAgo = rand(1, 48);
                $uses = rand(100, 5000);
            @endphp
            <article
                class="coupon-row"
                data-type="{{ $hasCode ? 'code' : 'deal' }}"
                data-tags="{{ $tagAttr }}"
                data-coupon-id="{{ $coupon->id }}"
            >
                <!-- Left: Discount Visual -->
                <div class="coupon-discount-visual">
                    @if($offerType === 'text' && $offerValue === 'FREE')
                        <div class="discount-up-to" style="font-size: 0.7rem; margin-bottom: 6px; color: #f97316;">FREE</div>
                        <div class="discount-percent" style="font-size: 2.2rem; margin-bottom: 8px; color: #111827;">SHIPPING</div>
                        <div class="discount-off-badge" style="display: none;"></div>
                    @elseif($offerType === 'currency')
                        <div class="discount-up-to">UP TO</div>
                        <div class="discount-percent" style="font-size: 2.5rem;">{{ $currencySymbol }}{{ number_format((float)str_replace([',', ' '], ['', ''], $offerValue), 0, '.', '') }}</div>
                        <div class="discount-off-badge">OFF</div>
                    @else
                        <div class="discount-up-to">UP TO</div>
                        <div class="discount-percent">{{ $offerValue }}%</div>
                        <div class="discount-off-badge">OFF</div>
                    @endif
                </div>
                
                <!-- Right: Coupon Details -->
                <div class="coupon-info">
                    <div class="coupon-header-row">
                        <div style="flex: 1;">
                            <div class="coupon-title">
                                @if($coupon->description)
                                    {{ $coupon->description }}
                                @else
                                    @if($offerType === 'currency')
                                        Save {{ $currencySymbol }}{{ number_format((float)str_replace([',', ' '], ['', ''], $offerValue), 0, '.', '') }} on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                                    @elseif($offerType === 'text' && $offerValue === 'FREE')
                                        Get Free Shipping on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                                    @else
                                        Save {{ $offerValue }}% on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                                    @endif
                                @endif
                            </div>
                            @if($loop->index < 2)
                            <div class="staff-pick-badge">STAFF PICK</div>
                            @endif
                            <div class="coupon-meta-info">
                                <div class="meta-item">{{ $hoursAgo }} hours ago</div>
                                <div class="meta-item uses">{{ number_format($uses) }} Uses</div>
                            </div>
                        </div>
                        <button class="btn-get-code"
                            type="button"
                            data-type="{{ $hasCode ? 'code' : 'deal' }}"
                            data-code="{{ $coupon->code }}"
                            data-coupon-id="{{ $coupon->id }}"
                            data-url="{{ route('click.redirect',$campaign->slug) }}"
                            onclick="return handleCouponClick(this)">
                            GET CODE
                        </button>
                    </div>
                    
                    <div class="coupon-desc">
                        @php
                            $fullDesc = $coupon->description ?: 'Exclusive deal from '.($campaign->brand->name ?? $campaign->title).' to help you save more when shopping online.';
                            $shortDesc = \Illuminate\Support\Str::limit($fullDesc, 120);
                        @endphp
                        {{ $shortDesc }}
                        @if(strlen($fullDesc) > 120)
                            <a href="#" class="show-more" onclick="event.preventDefault(); this.parentElement.innerHTML='{{ addslashes($fullDesc) }}'; return false;">Show more</a>
                        @endif
                    </div>
                    
                    <div class="coupon-verification">
                        Worked {{ $hoursAgo }} hours ago for {{ rand(1, 10) }} shoppers
                    </div>
                </div>
            </article>
            @empty
            <article class="section">
                <div class="section-title">No coupons available</div>
                <div class="section-body">
                    Please check back later. We are updating new deals.
                </div>
            </article>
            @endforelse
        </section>

        <!-- ABOUT -->
        <section class="section">
            <h2 class="section-title">About this campaign</h2>
            <div class="section-body intro-content">
                @if($campaign->intro)
                    {!! $campaign->intro !!}
                @else
                    <p>
                        Exclusive deals from {{ $campaign->brand->name ?? $campaign->title }}
                        to help you save more when shopping online.
                    </p>
                @endif
            </div>
        </section>

        <!-- HOW TO USE -->
        <section class="section">
            <h2 class="section-title">How to use a coupon code</h2>
            <div class="section-body">
                <ul>
                    <li>Select a coupon from the list above.</li>
                    <li>Click <strong>Get Deal</strong> to copy the code and visit the store.</li>
                    <li>Add products to your cart as usual.</li>
                    <li>Paste the coupon code at checkout and apply.</li>
                </ul>
            </div>
        </section>

         <!-- q&A -->

         <section class="section qa-section">
            <h2 class="section-title">Questions & Answers</h2>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    How do I use this coupon code?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Simply click “Get Deal”, copy the coupon code, then apply it at checkout on the store’s website.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Why doesn’t my coupon work?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Some coupons require a minimum order value, specific products, or may have expired. Please double-check the terms before checkout.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Can I use more than one coupon?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Most stores allow only one coupon per order. Combining multiple offers is usually not supported.
                </div>
            </div>

            <div class="qa-item">
                <button class="qa-question" onclick="toggleQA(this)">
                    Do you earn a commission from these deals?
                    <span class="qa-icon">+</span>
                </button>
                <div class="qa-answer">
                    Yes, we may earn a small commission when you make a purchase through our links, at no extra cost to you.
                </div>
            </div>
        </section>


        <!-- POLICY -->
        <section class="section">
            <h2 class="section-title">Policies & notes</h2>
            <div class="section-body">
                <ul>
                    <li>Some coupons may require a minimum order value.</li>
                    <li>Validity and conditions may change without notice.</li>
                    <li>Please double-check your discount before checkout.</li>
                    <li>We may earn a commission when you shop through our links.</li>
                </ul>
            </div>
        </section>

    </main>
    </div><!-- /.page -->
    </div><!-- /.page-panel -->
</div><!-- /.shell -->

@include('partials.site-footer')

<div id="couponModal" class="coupon-modal">
    <div class="coupon-modal-content">
        <div class="coupon-modal-close" onclick="closeCouponPopup()">✕</div>
        
        <!-- Banner Section -->
        <div class="popup-banner">
            <div class="popup-confetti">
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
            </div>
            <div class="popup-header">
                @if($campaign->brand && $campaign->brand->image)
                    <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                        alt="{{ $campaign->brand->name }}"
                        class="popup-logo">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}"
                        alt="{{ $campaign->title }}"
                        class="popup-logo">
                @else
                    <div class="popup-logo" style="display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 900; color: #111827;">
                        {{ substr(($campaign->brand->name ?? $campaign->title), 0, 1) }}
                    </div>
                @endif

                <h3 class="popup-title">
                    {{ $campaign->brand->name ?? $campaign->title }} Promotion
                </h3>

                <p class="popup-subtitle">
                    A Little Discount, A Lot of Joy—Save Now with {{ $campaign->brand->name ?? $campaign->title }} Promotion.
                </p>
            </div>
        </div>
        
        <!-- Body Section -->
        <div class="popup-body">
            <div class="coupon-code-container">
                <div class="coupon-code-left">
                    <div class="coupon-code-icon">🏷</div>
                    <div id="modalCode" class="coupon-code-box">
                        CODE123
                    </div>
                </div>
                <button class="btn-copy-code-modal" id="copyCouponBtn" onclick="copyCoupon(this)">
                    COPY CODE
                </button>
            </div>
            
            <div class="popup-verification">
                <div class="verification-item">
                    Verified <span id="verifiedDays">{{ rand(1, 30) }}</span> days ago
                </div>
                <div class="verification-item success">
                    <span id="successRate">{{ rand(85, 98) }}%</span> success
                </div>
            </div>

            <div class="coupon-modal-actions">
                <a href="#"
                target="_blank"
                class="coupon-btn store go-to-store-btn"
                onclick="event.preventDefault(); const url = this.getAttribute('data-url'); if(url) { window.open(url, '_blank'); } return false;">
                    Go to Store
                </a>
            </div>
            
            <!-- Feedback Section -->
            <div class="popup-feedback">
                <div class="popup-feedback-question">Did this code work?</div>
                <div class="popup-feedback-buttons">
                    <button class="feedback-btn worked" onclick="handleFeedback(this, true)">
                        <span class="feedback-icon">👍</span>
                        <span>Yes, worked!</span>
                    </button>
                    <button class="feedback-btn failed" onclick="handleFeedback(this, false)">
                        <span class="feedback-icon">👎</span>
                        <span>No, failed</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
let currentCode = '';
let currentCouponRow = null;
let currentCouponId = null;

function handleCouponClick(btn){
    const type = btn.dataset.type;
    const code = btn.dataset.code || '';
    const url = btn.dataset.url;
    const couponId = btn.dataset.couponId;

    const activeTabBtn = document.querySelector('.filter-pill.active');
    const activeTab = activeTabBtn ? (activeTabBtn.dataset.tab || 'all') : 'all';

    // Nếu là deal (không có code) hoặc đang ở tab Deals → đi thẳng tới link aff
    if (type === 'deal' || activeTab === 'deals') {
        if (url) {
            window.open(url, '_blank');
        }
        return false;
    }

    // Codes: mở modal + chuẩn bị copy + mở tab aff (không redirect trang hiện tại)
    if (!code) {
        // Nếu không có code, đi thẳng tới store
        if (url) {
            window.open(url, '_blank');
        }
        return false;
    }

    currentCode = code;
    currentCouponId = couponId;
    currentCouponRow = btn.closest('.coupon-row');

    const codeBox = document.getElementById('modalCode');
    if (codeBox) {
        codeBox.innerText = code;
    }

    const modal = document.getElementById('couponModal');
    if (modal) {
        modal.classList.add('active');
    }

    const goBtn = document.querySelector('.go-to-store-btn');
    if (goBtn && url) {
        goBtn.setAttribute('data-url', url);
        goBtn.href = url;
    }

    // Mở tab mới với affiliate link nhưng vẫn ở lại trang coupon
    if (url) {
        window.open(url, '_blank');
    }
    
    return false;
}

function closeCouponPopup(){
    document.getElementById('couponModal').classList.remove('active');
}

function handleFeedback(btn, worked){
    // Disable both buttons
    const buttons = document.querySelectorAll('.feedback-btn');
    buttons.forEach(b => {
        b.disabled = true;
        b.style.opacity = '0.6';
        b.style.cursor = 'not-allowed';
    });
    
    // Highlight selected button
    if (worked) {
        btn.style.background = '#dcfce7';
        btn.style.borderColor = '#22c55e';
        btn.innerHTML = '<span class="feedback-icon">✓</span><span>Thank you!</span>';
    } else {
        btn.style.background = '#fef2f2';
        btn.style.borderColor = '#ef4444';
        btn.innerHTML = '<span class="feedback-icon">✓</span><span>Thank you!</span>';
    }
    
    // Here you can send feedback to server if needed
    // fetch('/api/feedback', { method: 'POST', body: JSON.stringify({ couponId: currentCouponId, worked: worked }) });
}

function copyCoupon(btn){
    if(!currentCode) return;

    navigator.clipboard.writeText(currentCode).then(()=>{
        const originalText = btn.innerText;
        btn.innerText = 'Copied ✓';
        btn.disabled = true;
        btn.style.background = '#22c55e';
        
        // Reset button sau 2 giây
        setTimeout(() => {
            btn.innerText = originalText;
            btn.disabled = false;
            btn.style.background = '';
        }, 2000);
        
        // 👉 highlight nút Go to Store
        const goBtn = document.querySelector('.go-to-store-btn');
        if(goBtn){
            goBtn.classList.remove('go-store-attention');
            void goBtn.offsetWidth; // force reflow
            goBtn.classList.add('go-store-attention');
        }

        // mở mã coupon đúng block dựa trên coupon ID
        if(currentCouponRow && currentCouponId){
            // Tìm coupon row có cùng ID
            const targetRow = document.querySelector(`[data-coupon-id="${currentCouponId}"]`);
            if(targetRow){
                targetRow.classList.add('revealed');
                // Reveal code trong row đó
                const codeElement = targetRow.querySelector('.coupon-code.peek');
                if(codeElement){
                    codeElement.classList.remove('peek');
                }
            }
        }
    });
}
function toggleQA(el){
    const item = el.closest('.qa-item');
    item.classList.toggle('active');
}

// Tabs filter
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.coupon-row');

    tabs.forEach((tab) => {
        const label = tab.textContent.trim().toLowerCase();
        if (label === 'all deals') tab.dataset.tab = 'all';
        if (label === 'codes') tab.dataset.tab = 'codes';
        if (label === 'deals') tab.dataset.tab = 'deals';
        if (label === 'free shipping') tab.dataset.tab = 'free-shipping';
    });

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const current = tab.dataset.tab || 'all';

            rows.forEach(row => {
                const type = row.dataset.type;
                const tags = (row.dataset.tags || '').split(',').filter(Boolean);
                let show = true;

                if (current === 'codes') {
                    show = type === 'code';
                } else if (current === 'deals') {
                    show = type === 'deal';
                } else if (current === 'free-shipping') {
                    show = tags.includes('free-shipping');
                } else {
                    show = true;
                }

                row.style.display = show ? 'grid' : 'none';
            });
        });
    });
});

// Track time on page and bounce rate
@if(isset($pageView) && $pageView)
(function() {
    const pageViewId = {{ $pageView->id }};
    const startTime = Date.now();
    let timeOnPage = 0;
    let isBounce = true;
    let hasInteracted = false;
    
    // Track user interactions to determine if it's a bounce
    ['click', 'scroll', 'keydown', 'touchstart'].forEach(event => {
        document.addEventListener(event, function() {
            hasInteracted = true;
            isBounce = false;
        }, { once: true });
    });
    
    // Update time on page periodically
    setInterval(function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
    }, 1000);
    
    // Send data when page is about to unload
    window.addEventListener('beforeunload', function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
        
        // Use sendBeacon for reliable delivery
        const data = JSON.stringify({
            time_on_page: timeOnPage,
            is_bounce: isBounce && !hasInteracted && timeOnPage < 30
        });
        
        navigator.sendBeacon(
            '{{ route("analytics.update-page-view", ":id") }}'.replace(':id', pageViewId),
            data
        );
    });
    
    // Also send on visibility change (tab switch)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            timeOnPage = Math.floor((Date.now() - startTime) / 1000);
            fetch('{{ route("analytics.update-page-view", ":id") }}'.replace(':id', pageViewId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    time_on_page: timeOnPage,
                    is_bounce: isBounce && !hasInteracted && timeOnPage < 30
                })
            }).catch(() => {}); // Ignore errors
        }
    });
})();
@endif
</script>



</body>
</html>
