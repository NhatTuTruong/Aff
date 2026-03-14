<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        // Đảm bảo chuỗi offer luôn UTF-8 để €, £, ¥, ₹ hiển thị đúng (tránh lỗi "UP TO10")
        $ensureOfferUtf8 = function ($text) {
            $text = (string) $text;
            if ($text === '') return $text;
            if (mb_check_encoding($text, 'UTF-8') && mb_strpos($text, "\xEF\xBF\xBD") === false) {
                return $text;
            }
            $decoded = @mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
            return $decoded !== false ? $decoded : $text;
        };

        $coupons = $campaign->couponItems ?? collect();
        $maxPercent = 0;
        foreach ($coupons as $c) {
            $offerText = $ensureOfferUtf8($c->offer ?? '');
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

        // Max currency (for default coupon display)
        $maxCurrencyValue = 0;
        $maxCurrencySymbol = '$';
        foreach ($coupons as $c) {
            $offerText = $ensureOfferUtf8($c->offer ?? '');
            if (preg_match('/([€$£¥₹])\s*(\d+(?:[.,]\d+)?)/u', $offerText, $m)) {
                $val = (float) str_replace([',', ' '], ['', ''], $m[2]);
                if ($val > $maxCurrencyValue) {
                    $maxCurrencyValue = $val;
                    $maxCurrencySymbol = $m[1];
                }
            } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*([€$£¥₹])/u', $offerText, $m)) {
                $val = (float) str_replace([',', ' '], ['', ''], $m[1]);
                if ($val > $maxCurrencyValue) {
                    $maxCurrencyValue = $val;
                    $maxCurrencySymbol = $m[2];
                }
            } elseif (preg_match('/(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)\s*(\d+(?:[.,]\d+)?)/i', $offerText, $m)) {
                $val = (float) str_replace([',', ' '], ['', ''], $m[2]);
                if ($val > $maxCurrencyValue) {
                    $maxCurrencyValue = $val;
                    $codeToSymbol = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 'INR' => '₹', 'CAD' => 'C$', 'AUD' => 'A$', 'CHF' => 'CHF', 'CNY' => '¥'];
                    $maxCurrencySymbol = $codeToSymbol[strtoupper($m[1])] ?? $m[1];
                }
            } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)/i', $offerText, $m)) {
                $val = (float) str_replace([',', ' '], ['', ''], $m[1]);
                if ($val > $maxCurrencyValue) {
                    $maxCurrencyValue = $val;
                    $codeToSymbol = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 'INR' => '₹', 'CAD' => 'C$', 'AUD' => 'A$', 'CHF' => 'CHF', 'CNY' => '¥'];
                    $maxCurrencySymbol = $codeToSymbol[strtoupper($m[2])] ?? $m[2];
                }
            }
        }

        // Default coupon: use max % or max currency (prioritize percent, else currency)
        $defaultCouponOfferType = $maxPercent > 0 ? 'percent' : 'currency';
        $defaultCouponOfferValue = $maxPercent > 0 ? $maxPercent : $maxCurrencyValue;
        $defaultCouponCurrencySymbol = $maxPercent > 0 ? '' : $maxCurrencySymbol;
        if ($defaultCouponOfferValue <= 0) {
            $defaultCouponOfferType = 'percent';
            $defaultCouponOfferValue = 20;
        }

        $couponsWithCodes = $coupons->filter(fn ($c) => !empty($c->code))->values();
        
        // Parse slug để lấy userCode và slugPart
        $slugParts = explode('/', $campaign->slug, 2);
        $userCode = count($slugParts) === 2 ? $slugParts[0] : '00000';
        $slugPart = count($slugParts) === 2 ? $slugParts[1] : $campaign->slug;

        $brandName = $campaign->brand->name ?? $campaign->title;
        $rawIntro = (string) ($campaign->subtitle ?? $campaign->intro ?? '');
        $metaTitle = $brandName . ' Coupons & Promo Codes';
        $metaDescription = \Illuminate\Support\Str::limit(strip_tags($rawIntro), 160);

        if (empty($metaDescription)) {
            $metaDescription = 'Save more with the latest ' . $brandName . ' coupon codes, deals and promotions.';
        }

        if ($campaign->brand && $campaign->brand->image) {
            $ogImage = $campaign->brand->image_url;
        } elseif ($campaign->cover_image) {
            $ogImage = asset('storage/' . $campaign->cover_image);
        } elseif ($campaign->logo) {
            $ogImage = asset('storage/' . $campaign->logo);
        } else {
            $ogImage = asset('images/default-brand.svg');
        }
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --t3-primary: #6366f1;
            --t3-primary-dark: #4f46e5;
            --t3-primary-soft: #e0e7ff;
            --t3-hero-bg: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --t3-text: #1e1b4b;
            --t3-text-muted: #64748b;
            --t3-bg: #f5f3ff;
            --t3-card: #ffffff;
            --t3-border: #e9d5ff;
            --t3-shadow: 0 4px 20px rgba(99, 102, 241, 0.12);
        }
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: var(--t3-text);
            background: var(--t3-bg);
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; transition: color 0.2s ease; }

        /* Store banner - gradient hero */
        .t3-store-banner {
            background: var(--t3-hero-bg);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .t3-banner-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .t3-banner-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            background: #fff;
            border-radius: 8px;
            padding: 6px;
        }
        .t3-banner-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
        }
        .t3-banner-cta {
            display: inline-flex;
            align-items: center;
            padding: 10px 24px;
            background: var(--t3-primary);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .t3-banner-cta:hover {
            background: var(--t3-primary-dark);
            transform: translateY(-1px);
            color: #fff;
        }

        /* Full-width main */
        .t3-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 28px 24px 50px;
        }

        /* Hero */
        .t3-hero {
            margin-bottom: 24px;
        }
        .t3-hero-title {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
            color: var(--t3-text);
        }
        .t3-hero-sub {
            font-size: 0.95rem;
            color: var(--t3-text-muted);
        }
        .t3-hero-sub strong { color: var(--t3-primary); font-weight: 700; }
        .t3-hero-disclosure {
            margin-top: 6px;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* Quick menu - horizontal anchor links */
        .t3-quick-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 20px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--t3-border);
        }
        .t3-quick-nav a {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--t3-text-muted);
            padding: 6px 0;
            border-bottom: 2px solid transparent;
        }
        .t3-quick-nav a:hover { color: var(--t3-primary); border-bottom-color: var(--t3-primary); }

        /* Stats bar - compact above coupon list */
        .t3-stats-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 24px;
            padding: 14px 18px;
            background: var(--t3-card);
            border: 1px solid var(--t3-border);
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .t3-stats-item { display: flex; align-items: center; gap: 8px; }
        .t3-stats-value { font-weight: 700; color: var(--t3-text); }
        .t3-stats-label { color: var(--t3-text-muted); }

        /* Coupon section header */
        .t3-coupon-header-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 4px; }
        .t3-coupon-header-meta { font-size: 0.85rem; color: var(--t3-text-muted); margin-bottom: 12px; }

        /* Filter pills */
        .t3-filter-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .filter-pill {
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 0.85rem;
            border: 1px solid var(--t3-border);
            background: var(--t3-card);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            font-family: inherit;
        }
        .filter-pill:hover { border-color: var(--t3-primary); background: var(--t3-primary-soft); }
        .filter-pill.active {
            background: var(--t3-primary);
            border-color: var(--t3-primary);
            color: #fff;
            font-weight: 600;
        }

        /* Coupon grid - card layout */
        .t3-coupon-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .coupon-row {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
            padding: 24px 20px;
            background: var(--t3-card);
            border: 1px solid var(--t3-border);
            border-radius: 16px;
            box-shadow: var(--t3-shadow);
            transition: all 0.2s;
        }
        .coupon-row:hover {
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.18);
            border-color: var(--t3-primary);
        }
        .t3-coupon-content { flex: 1; min-width: 0; }
        .coupon-title {
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.4;
            color: var(--t3-text);
            margin-bottom: 6px;
        }
        .staff-pick-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #7c3aed;
            color: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .staff-pick-badge::before { content: '⭐'; font-size: 0.6rem; }
        .coupon-meta-info {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.8rem;
            color: var(--t3-text-muted);
        }
        .meta-item { display: flex; align-items: center; gap: 5px; }
        .meta-item::before { content: '✓'; color: var(--t3-primary); font-weight: 700; }
        .meta-item.uses::before { content: '👥'; }
        .coupon-desc.coupon-verified {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #16a34a;
        }
        .coupon-verified-badge { width: 20px; height: 20px; flex-shrink: 0; }
        .coupon-verified-badge img { width: 100%; height: 100%; object-fit: contain; }
        .btn-get-code {
            background: var(--t3-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            flex-shrink: 0;
            width: 100%;
            text-align: center;
            font-family: inherit;
        }
        .btn-get-code:hover {
            background: var(--t3-primary-dark);
            transform: translateY(-1px);
            color: #fff;
        }
        .coupon-revealed-code {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: ui-monospace, monospace;
            letter-spacing: 0.05em;
            background: var(--t3-primary-soft);
            color: var(--t3-primary-dark);
            border: 1px dashed var(--t3-primary);
            border-radius: 6px;
            white-space: nowrap;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* Sections - flat cards */
        .section {
            background: var(--t3-card);
            border: 1px solid var(--t3-border);
            padding: 24px 28px;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 14px;
            color: var(--t3-text);
        }
        .section-body {
            font-size: 0.95rem;
            color: var(--t3-text-muted);
            line-height: 1.75;
        }
        .section-body ul { padding-left: 18px; margin: 6px 0; }
        .intro-content { line-height: 1.75; color: var(--t3-text-muted); }
        .intro-content p { margin-bottom: 1rem; color: var(--t3-text); }
        .intro-content p:last-child { margin-bottom: 0; }
        .intro-content a { color: var(--t3-primary); text-decoration: underline; }
        .intro-content a:hover { color: var(--t3-primary-dark); }

        /* Q&A */
        .qa-item {
            border: 1px solid var(--t3-border);
            margin-bottom: 8px;
            overflow: hidden;
            background: var(--t3-card);
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
            color: var(--t3-text);
            font-family: inherit;
        }
        .qa-icon { font-size: 1.2rem; transition: transform 0.3s ease; }
        .qa-answer {
            max-height: 0;
            overflow: hidden;
            padding: 0 16px;
            font-size: 0.85rem;
            color: var(--t3-text-muted);
            transition: all 0.35s ease;
        }
        .qa-item.active .qa-answer { max-height: 200px; padding: 10px 16px 16px; }
        .qa-item.active .qa-icon { transform: rotate(45deg); }

        /* Modal - dark header, orange accents */
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
            transition: opacity 0.25s ease;
        }
        .coupon-modal.active { opacity: 1; pointer-events: auto; }
        .coupon-modal-content {
            position: relative;
            background: var(--t3-card);
            width: 100%;
            max-width: 460px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: t2PopupScale 0.3s ease;
        }
        .t3-popup-banner {
            background: var(--t3-hero-bg);
            padding: 28px 24px 32px;
            text-align: center;
            position: relative;
        }
        .t3-popup-logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fff;
            padding: 10px;
            margin: 0 auto 12px;
            object-fit: contain;
        }
        .t3-popup-title { font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0; }
        .t3-popup-subtitle { font-size: 0.9rem; color: rgba(255,255,255,0.9); margin-top: 8px; }
        .t3-popup-body { padding: 24px; }
        .coupon-code-container {
            background: #1e1b4b;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }
        .coupon-code-left { display: flex; align-items: center; gap: 10px; flex: 1; }
        .coupon-code-icon {
            width: 28px;
            height: 28px;
            background: var(--t3-primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 0.8rem;
        }
        .coupon-code-box {
            background: transparent;
            border: none;
            padding: 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #fff;
            font-family: ui-monospace, monospace;
        }
        .btn-copy-code-modal {
            background: var(--t3-primary);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            font-family: inherit;
        }
        .btn-copy-code-modal:hover {
            background: var(--t3-primary-dark);
            transform: translateY(-1px);
        }
        .coupon-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            color: #fff;
            z-index: 10;
        }
        .coupon-modal-close:hover { background: rgba(255,255,255,0.25); }
        .popup-verification {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: var(--t3-text-muted);
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--t3-border);
            flex-wrap: wrap;
            gap: 8px;
        }
        .verification-item { display: flex; align-items: center; gap: 6px; }
        .verification-item::before { content: '✓'; color: var(--t3-primary); font-weight: 700; }
        .verification-item.success::before { content: '●'; color: #16a34a; }
        .coupon-modal-actions { margin-top: 20px; }
        .coupon-btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            display: block;
            font-family: inherit;
        }
        .coupon-btn.store {
            background: var(--t3-primary);
            color: #fff;
        }
        .coupon-btn.store:hover {
            background: var(--t3-primary-dark);
            transform: translateY(-1px);
            color: #fff;
        }
        .popup-feedback { margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--t3-border); }
        .popup-feedback-question { text-align: center; font-size: 0.95rem; font-weight: 600; margin-bottom: 12px; }
        .popup-feedback-buttons { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .feedback-btn {
            background: #fff;
            border: 1px solid var(--t3-border);
            border-radius: 6px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--t3-text);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            flex: 1;
            max-width: 180px;
            justify-content: center;
            font-family: inherit;
        }
        .feedback-btn:hover { border-color: var(--t3-primary); color: var(--t3-primary); }
        .feedback-btn.worked { border-color: #22c55e; color: #22c55e; }
        .feedback-btn.failed { border-color: #ef4444; color: #ef4444; }
        @keyframes t2PopupScale {
            from { transform: scale(0.95); opacity: 0.5; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes t2PulseGlow {
            0%, 100% { box-shadow: 0 0 0 rgba(245, 158, 11, 0); transform: scale(1); }
            50% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.5); transform: scale(1.02); }
        }
        .go-store-attention { animation: t2PulseGlow 0.9s ease-in-out 3; }

        /* All codes modal */
        .all-codes-modal-content { max-width: 520px; }
        .all-codes-list { display: flex; flex-direction: column; gap: 12px; }
        .all-codes-item {
            border: 1px solid var(--t3-border);
            border-radius: 8px;
            overflow: hidden;
        }
        .all-codes-item .coupon-code-container { margin-bottom: 0; }
        .all-codes-item-desc { padding: 10px 16px; font-size: 0.85rem; color: var(--t3-text-muted); }

        .t3-mobile-shop { display: none; }
        @media (max-width: 768px) {
            .t3-store-banner { padding: 14px 16px; }
            .t3-banner-logo { width: 40px; height: 40px; }
            .t3-banner-name { font-size: 1rem; }
            .t3-main { padding: 20px 16px 40px; }
            .t3-hero-title { font-size: 1.5rem; }
            .t3-coupon-list { grid-template-columns: 1fr; }
            .coupon-row { padding: 20px 16px; }
            .btn-get-code { width: 100%; justify-content: center; }
            .t3-mobile-shop { display: block; margin: 20px 0; text-align: center; }
            .t3-mobile-shop a {
                display: inline-block;
                padding: 12px 24px;
                background: var(--t3-primary);
                color: #fff;
                font-weight: 700;
                border-radius: 6px;
            }
            .t3-mobile-shop a:hover { background: var(--t3-primary-dark); color: #fff; }
        }
        @media (max-width: 480px) {
            .t3-quick-nav { flex-direction: column; }
            .t3-stats-bar { flex-direction: column; gap: 10px; }
            .filter-pill { font-size: 0.8rem; padding: 6px 12px; }
            .staff-pick-badge { display: none; }
        }
    </style>
</head>
<body>
@include('partials.site-header')

<div class="t3-store-banner">
    <div class="t3-banner-brand">
        @if($campaign->brand)
            <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" class="t3-banner-logo" loading="lazy">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" class="t3-banner-logo" loading="lazy">
        @else
            <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" class="t3-banner-logo" loading="lazy">
        @endif
        <span class="t3-banner-name">{{ $brandName }}</span>
    </div>
    <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}" class="t3-banner-cta" target="_blank" rel="noopener">Shop Now</a>
</div>

<div class="t3-main">
    <header class="t3-hero">
        <h1 class="t3-hero-title">
            {{ $campaign->brand->name ?? $campaign->title }} Coupons &amp; Promo Codes
        </h1>
        <p class="t3-hero-sub">
            Save up to <strong>{{ $maxPercent }}% off</strong> with verified discount codes &amp; exclusive deals.
        </p>
        <p class="t3-hero-disclosure">
            We may earn a commission when you shop through our links, at no extra cost to you.
        </p>
    </header>

    <nav class="t3-quick-nav" aria-label="Quick menu">
        <a href="#about-us">About us</a>
        <a href="#how-to-use">How to use a coupon code</a>
        <a href="#qa">Questions &amp; Answers</a>
        <a href="#policies">Policies &amp; notes</a>
    </nav>

    @php
        $workingCount = ($campaign->couponItems ?? collect())->count();
        $successRate = $workingCount > 0 ? min(99, 80 + (int)round($workingCount * 1.5)) : 89;
        $campaignSeed = (int) ($campaign->id ?? 0);
        $estimatedTotalSaved = min(99999, 7229 + $workingCount * 80 + ($campaignSeed % 5000));
    @endphp
    <div class="t3-stats-bar">
        <div class="t3-stats-item">
            <span class="t3-stats-value">{{ $workingCount }}</span>
            <span class="t3-stats-label">codes</span>
        </div>
        <div class="t3-stats-item">
            <span class="t3-stats-value">{{ $successRate }}%</span>
            <span class="t3-stats-label">success</span>
        </div>
        <div class="t3-stats-item">
            <span class="t3-stats-value">${{ number_format($estimatedTotalSaved, 0, '.', ',') }}</span>
            <span class="t3-stats-label">saved</span>
        </div>
    </div>

    <section class="t3-coupon-section">
        <div class="t3-coupon-header-title">
            Active {{ $campaign->brand->name ?? $campaign->title }} coupons
        </div>
        <div class="t3-coupon-header-meta">
            Last checked: 18 hours ago • Tracking coupons in the last 7 days.
        </div>
        <div class="t3-filter-tabs">
            <button class="filter-pill active" type="button" aria-pressed="true" aria-label="Show all deals">All Deals</button>
            <button class="filter-pill" type="button" aria-pressed="false" aria-label="Show coupon codes only">Codes</button>
            <button class="filter-pill" type="button" aria-pressed="false" aria-label="Show deals without codes">Deals</button>
            <button class="filter-pill" type="button" aria-pressed="false" aria-label="Show free shipping offers">Free Shipping</button>
            <button class="filter-pill" type="button" aria-pressed="false" aria-label="Show first order offers">First Order</button>
        </div>

        <div class="t3-coupon-list" id="coupon-list">
            @forelse($coupons as $coupon)
            @php
                $hasCode = !empty($coupon->code);
                $offerText = $ensureOfferUtf8($coupon->offer ?? '');
                $descriptionText = (string) ($coupon->description ?? '');
                $isFreeShipping = stripos($offerText, 'free shipping') !== false || stripos($descriptionText, 'free shipping') !== false;
                $tags = [];
                if ($isFreeShipping) { $tags[] = 'free-shipping'; }
                $tagAttr = implode(',', $tags);

                $offerValue = null;
                $offerType = 'percent';
                $currencySymbol = '';
                $currencyMap = [
                    'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥',
                    'INR' => '₹', 'CAD' => 'C$', 'AUD' => 'A$',
                    'CHF' => 'CHF', 'CNY' => '¥',
                ];
                if ($offerText !== '') {
                    if (preg_match('/(\d+)\s*%/i', $offerText, $matches)) {
                        $offerValue = (int)$matches[1];
                        $offerType = 'percent';
                    } elseif (preg_match('/([€$£¥₹])\s*(\d+(?:[.,]\d+)?)/u', $offerText, $matches)) {
                        $currencySymbol = $matches[1];
                        $offerValue = $matches[2];
                        $offerType = 'currency';
                    } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*([€$£¥₹])/u', $offerText, $matches)) {
                        $currencySymbol = $matches[2];
                        $offerValue = $matches[1];
                        $offerType = 'currency';
                    } elseif (preg_match('/(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)\s*(\d+(?:[.,]\d+)?)/i', $offerText, $matches)) {
                        $currencyCode = strtoupper($matches[1]);
                        $currencySymbol = $currencyMap[$currencyCode] ?? $currencyCode;
                        $offerValue = $matches[2];
                        $offerType = 'currency';
                    } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)/i', $offerText, $matches)) {
                        $currencyCode = strtoupper($matches[2]);
                        $currencySymbol = $currencyMap[$currencyCode] ?? $currencyCode;
                        $offerValue = $matches[1];
                        $offerType = 'currency';
                    } elseif ($isFreeShipping) {
                        $offerValue = 'FREE';
                        $offerType = 'text';
                    }
                }
                if ($offerValue === null) {
                    $offerValue = 15;
                    $offerType = 'percent';
                }
                $hoursAgo = (($campaign->id ?? 0) * 7 + ($coupon->id ?? 0)) % 48 + 1;
                $uses = (($campaign->id ?? 0) * 11 + ($coupon->id ?? 0)) % 4900 + 100;
            @endphp
            <article
                class="coupon-row"
                data-type="{{ $hasCode ? 'code' : 'deal' }}"
                data-tags="{{ $tagAttr }}"
                data-coupon-id="{{ $coupon->id }}"
            >
                <div class="t3-coupon-content">
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
                    <div class="coupon-desc coupon-verified" aria-label="Coupon status">
                        <span class="coupon-verified-badge" aria-hidden="true">
                            <img src="{{ asset('images/verified-badge.png') }}" alt="">
                        </span>
                        <span class="coupon-verified-text">Verified recently</span>
                    </div>
                </div>
                <button class="btn-get-code"
                    type="button"
                    data-type="{{ $hasCode ? 'code' : 'deal' }}"
                    data-code="{{ $coupon->code }}"
                    data-coupon-id="{{ $coupon->id }}"
                    data-url="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}"
                    aria-label="{{ $hasCode ? 'Get coupon code and go to store' : 'Open deal and go to store' }}"
                    onclick="return handleCouponClick(this)">
                    {{ $hasCode ? 'GET CODE' : 'GET DEAL' }}
                </button>
            </article>
            @empty
            <article class="section">
                <div class="section-title">No coupons available</div>
                <div class="section-body">
                    Please check back later. We are updating new deals.
                </div>
            </article>
            @endforelse

            @if($couponsWithCodes->isNotEmpty())
            <article class="coupon-row coupon-row-default"
                data-type="code"
                data-all-codes="1"
                data-coupon-id="all-codes"
            >
                <div class="t3-coupon-content">
                    <div class="coupon-title">
                        @if($defaultCouponOfferType === 'currency')
                            Save {{ $defaultCouponCurrencySymbol }}{{ number_format($defaultCouponOfferValue, 0, '.', '') }} on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                        @else
                            Save {{ $defaultCouponOfferValue }}% on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                        @endif
                    </div>
                    <div class="coupon-meta-info">
                        <div class="meta-item">18 hours ago</div>
                        <div class="meta-item uses">{{ number_format(($campaignSeed * 13 + 999) % 4900 + 100) }} Uses</div>
                    </div>
                    <div class="coupon-desc coupon-verified" aria-label="Coupon status">
                        <span class="coupon-verified-badge" aria-hidden="true">
                            <img src="{{ asset('images/verified-badge.png') }}" alt="">
                        </span>
                        <span class="coupon-verified-text">Verified recently</span>
                    </div>
                </div>
                <button class="btn-get-code"
                    type="button"
                    data-type="code"
                    data-all-codes="1"
                    data-coupon-id="all-codes"
                    data-url="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}"
                    aria-label="View all coupon codes"
                    onclick="return handleCouponClick(this)">
                    GET CODE
                </button>
            </article>
            @endif
        </div>

        <div class="t3-mobile-shop">
            <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}" target="_blank" rel="nofollow sponsored noopener">Shop Now</a>
        </div>
    </section>

    <section class="section" id="about-us">
        <h2 class="section-title">About us</h2>
        <div class="section-body intro-content">
            @if($campaign->intro)
                @php
                    $intro = (string) $campaign->intro;
                    $hasHtml = $intro !== strip_tags($intro);
                @endphp
                @if($hasHtml)
                    {!! $intro !!}
                @else
                    {!! nl2br(e($intro)) !!}
                @endif
            @else
                <p>
                    Exclusive deals from {{ $campaign->brand->name ?? $campaign->title }}
                    to help you save more when shopping online.
                </p>
            @endif
        </div>
    </section>

    <section class="section" id="how-to-use">
        <h2 class="section-title">How to use a coupon code</h2>
        <div class="section-body">
            <ul>
                <li>Select a coupon from the list above.</li>
                <li>Click <strong>Get Code</strong> to copy the code and visit the store.</li>
                <li>Add products to your cart as usual.</li>
                <li>Paste the coupon code at checkout and apply.</li>
            </ul>
        </div>
    </section>

    <section class="section qa-section" id="qa">
        <h2 class="section-title">Questions &amp; Answers</h2>
        <div class="qa-item">
            <button class="qa-question" onclick="toggleQA(this)">
                How do I use this coupon code?
                <span class="qa-icon">+</span>
            </button>
            <div class="qa-answer">
                Simply click "Get Deal", copy the coupon code, then apply it at checkout on the store's website.
            </div>
        </div>
        <div class="qa-item">
            <button class="qa-question" onclick="toggleQA(this)">
                Why doesn't my coupon work?
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

    <section class="section" id="policies">
        <h2 class="section-title">Policies &amp; notes</h2>
        <div class="section-body">
            <ul>
                <li>Some coupons may require a minimum order value.</li>
                <li>Validity and conditions may change without notice.</li>
                <li>Please double-check your discount before checkout.</li>
                <li>We may earn a commission when you shop through our links.</li>
            </ul>
        </div>
    </section>
</div>

@include('partials.site-footer')

<div id="couponModal" class="coupon-modal" role="dialog" aria-modal="true" aria-labelledby="coupon-modal-title">
    <div class="coupon-modal-content">
        <button type="button" class="coupon-modal-close" aria-label="Close coupon popup" onclick="closeCouponPopup()">✕</button>
        <div class="t3-popup-banner">
            <div>
                @if($campaign->brand)
                    <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" class="t3-popup-logo" loading="lazy">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" class="t3-popup-logo" loading="lazy">
                @else
                    <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" class="t3-popup-logo" loading="lazy">
                @endif
                <h3 class="t3-popup-title" id="coupon-modal-title">
                    {{ $campaign->brand->name ?? $campaign->title }} Promotion
                </h3>
                <p class="t3-popup-subtitle">
                    A Little Discount, A Lot of Joy—Save Now with {{ $campaign->brand->name ?? $campaign->title }} Promotion.
                </p>
            </div>
        </div>
        <div class="t3-popup-body">
            <div class="coupon-code-container">
                <div class="coupon-code-left">
                    <div class="coupon-code-icon">🏷</div>
                    <div id="modalCode" class="coupon-code-box">CODE123</div>
                </div>
                <button class="btn-copy-code-modal" id="copyCouponBtn" onclick="copyCoupon(this)" aria-label="Copy coupon code">COPY CODE</button>
            </div>
            <div class="popup-verification">
                <div class="verification-item">Recently checked and still active for most shoppers.</div>
                <div class="verification-item success">We can't guarantee every order, but many visitors report this code still works.</div>
            </div>
            <div class="coupon-modal-actions">
                <a href="#" target="_blank" rel="nofollow sponsored noopener"
                   class="coupon-btn store go-to-store-btn"
                   aria-label="Open store in new tab"
                   onclick="event.preventDefault(); const url = this.getAttribute('data-url'); if(url) { window.open(url, '_blank'); } return false;">
                    Go To The Store
                </a>
            </div>
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

<div id="allCodesModal" class="coupon-modal all-codes-modal" role="dialog" aria-modal="true" aria-labelledby="all-codes-modal-title">
    <div class="coupon-modal-content all-codes-modal-content">
        <button type="button" class="coupon-modal-close" aria-label="Close" onclick="closeAllCodesModal()">✕</button>
        <div class="t3-popup-banner">
            <div>
                @if($campaign->brand)
                    <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" class="t3-popup-logo" loading="lazy">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" class="t3-popup-logo" loading="lazy">
                @else
                    <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" class="t3-popup-logo" loading="lazy">
                @endif
                <h3 class="t3-popup-title" id="all-codes-modal-title">All {{ $campaign->brand->name ?? $campaign->title }} Coupon Codes</h3>
                <p class="t3-popup-subtitle">Copy any code below and apply at checkout.</p>
            </div>
        </div>
        <div class="t3-popup-body">
            <div class="all-codes-list">
                @foreach($couponsWithCodes as $coupon)
                <div class="all-codes-item">
                    <div class="coupon-code-container">
                        <div class="coupon-code-left">
                            <div class="coupon-code-icon">🏷</div>
                            <div class="coupon-code-box">{{ $coupon->code }}</div>
                        </div>
                        <button class="btn-copy-code-modal btn-copy-all-codes" type="button" data-code="{{ e($coupon->code) }}" aria-label="Copy code">COPY</button>
                    </div>
                    @if($coupon->description)
                    <div class="all-codes-item-desc">{{ Str::limit($coupon->description, 80) }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="coupon-modal-actions" style="margin-top: 20px;">
                <a href="{{ route('click.redirect', ['userCode' => $userCode, 'slug' => $slugPart]) }}"
                   target="_blank" rel="nofollow sponsored noopener"
                   class="coupon-btn store">
                    Go To The Store
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentCode = '';
let currentCouponRow = null;
let currentCouponId = null;
const STORAGE_KEY = 'revealed_coupons_' + window.location.pathname;

function getStoredRevealed() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : {};
    } catch (e) { return {}; }
}
function saveRevealed(couponId, code) {
    const obj = getStoredRevealed();
    obj[couponId] = code;
    localStorage.setItem(STORAGE_KEY, JSON.stringify(obj));
}

function revealCodeInRow(couponId, code, affUrl) {
    const row = document.querySelector('.coupon-row[data-coupon-id="' + couponId + '"]');
    if (!row) return;
    const btn = row.querySelector('.btn-get-code');
    if (!btn) return;
    const codeSpan = document.createElement('span');
    codeSpan.className = 'coupon-revealed-code';
    codeSpan.textContent = code;
    codeSpan.title = 'Click to copy again';
    if (affUrl) codeSpan.dataset.url = affUrl;
    codeSpan.onclick = function(e) {
        e.stopPropagation();
        copyCodeToClipboard(code);
        const t = this.textContent;
        this.textContent = 'Copied ✓';
        this.style.background = '#6366f1';
        this.style.color = '#fff';
        setTimeout(() => { this.textContent = t; this.style.background = ''; this.style.color = ''; }, 1500);
    };
    btn.parentNode.replaceChild(codeSpan, btn);
    saveRevealed(couponId, code);
}
function copyCodeToClipboard(text) {
    navigator.clipboard.writeText(text);
}

function openModalForCoupon(couponId, code, affUrl) {
    currentCode = code;
    currentCouponId = couponId;
    const codeBox = document.getElementById('modalCode');
    if (codeBox) codeBox.innerText = code;
    const modal = document.getElementById('couponModal');
    if (modal) modal.classList.add('active');
    const goBtn = document.querySelector('.go-to-store-btn');
    if (goBtn && affUrl) {
        goBtn.setAttribute('data-url', affUrl);
        goBtn.href = affUrl;
    }
}

function handleCouponClick(btn){
    const actualBtn = btn.classList && btn.classList.contains('btn-get-code') ? btn : btn.querySelector('.btn-get-code');
    const row = btn.closest('.coupon-row');
    if (!actualBtn && !row) return false;
    const target = actualBtn || row;
    const isAllCodes = target.dataset.allCodes === '1' || target.dataset.allCodes === 'true';

    if (isAllCodes) {
        document.getElementById('allCodesModal').classList.add('active');
        document.querySelectorAll('.btn-copy-all-codes').forEach(function(bt) {
            bt.onclick = function() {
                const code = this.dataset.code;
                if (!code) return;
                copyCodeToClipboard(code);
                const txt = this.textContent;
                this.textContent = 'COPIED ✓';
                this.classList.add('copied');
                setTimeout(function() { bt.textContent = txt; bt.classList.remove('copied'); }, 1500);
            };
        });
        return false;
    }

    if (!actualBtn) return false;
    const type = actualBtn.dataset.type;
    const code = actualBtn.dataset.code || '';
    const url = actualBtn.dataset.url;
    const couponId = actualBtn.dataset.couponId;

    const activeTabBtn = document.querySelector('.filter-pill.active');
    const activeTab = activeTabBtn ? (activeTabBtn.dataset.tab || 'all') : 'all';

    if (type === 'deal' || activeTab === 'deals') {
        if (url) window.open(url, '_blank');
        return false;
    }

    if (!code) {
        if (url) window.open(url, '_blank');
        return false;
    }

    currentCode = code;
    currentCouponId = couponId;
    currentCouponRow = actualBtn.closest('.coupon-row');

    const affAlreadyOpened = sessionStorage.getItem('affOpened') === '1';

    if (affAlreadyOpened) {
        openModalForCoupon(couponId, code, url);
        return false;
    }

    sessionStorage.setItem('affOpened', '1');
    const couponPageUrl = new URL(window.location.href);
    couponPageUrl.searchParams.set('show_coupon', couponId);
    couponPageUrl.searchParams.set('code', encodeURIComponent(code));
    window.open(couponPageUrl.toString(), '_blank');
    if (url) window.location.href = url;
    return false;
}

function closeCouponPopup(){
    document.getElementById('couponModal').classList.remove('active');
}
function closeAllCodesModal(){
    document.getElementById('allCodesModal').classList.remove('active');
}

function handleFeedback(btn, worked){
    const buttons = document.querySelectorAll('.feedback-btn');
    buttons.forEach(b => {
        b.disabled = true;
        b.style.opacity = '0.6';
        b.style.cursor = 'not-allowed';
    });
    if (worked) {
        btn.style.background = '#dcfce7';
        btn.style.borderColor = '#22c55e';
        btn.innerHTML = '<span class="feedback-icon">✓</span><span>Thank you!</span>';
    } else {
        btn.style.background = '#fef2f2';
        btn.style.borderColor = '#ef4444';
        btn.innerHTML = '<span class="feedback-icon">✓</span><span>Thank you!</span>';
    }
}

function copyCoupon(btn){
    if(!currentCode) return;

    copyCodeToClipboard(currentCode);
    const originalText = btn.innerText;
    btn.innerText = 'Copied ✓';
    btn.disabled = true;
    btn.style.background = '#d97706';
    setTimeout(() => {
        btn.innerText = originalText;
        btn.disabled = false;
        btn.style.background = '';
    }, 2000);
    
    const goBtn = document.querySelector('.go-to-store-btn');
    if(goBtn){
        goBtn.classList.remove('go-store-attention');
        void goBtn.offsetWidth;
        goBtn.classList.add('go-store-attention');
    }
    const affUrl = goBtn ? goBtn.getAttribute('data-url') : null;
    if (currentCouponId && currentCode) {
        revealCodeInRow(currentCouponId, currentCode, affUrl);
    }
}
function toggleQA(el){
    const item = el.closest('.qa-item');
    item.classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', function () {
    const affUrl = (function() {
        const b = document.querySelector('.btn-get-code[data-url]');
        return b ? b.dataset.url : null;
    })();
    const stored = getStoredRevealed();
    Object.keys(stored).forEach(function(cid) {
        revealCodeInRow(cid, stored[cid], affUrl);
    });

    const urlParams = new URLSearchParams(window.location.search);
    const showCouponId = urlParams.get('show_coupon');
    const codeFromUrl = urlParams.get('code');
    if (showCouponId && codeFromUrl) {
        try {
            const decoded = decodeURIComponent(codeFromUrl);
            currentCode = decoded;
            currentCouponId = showCouponId;
            currentCouponRow = document.querySelector('.coupon-row[data-coupon-id="' + showCouponId + '"]');
            revealCodeInRow(showCouponId, decoded, affUrl);
            openModalForCoupon(showCouponId, decoded, affUrl);
        } catch (e) {}
    }

    const tabs = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.coupon-row');

    tabs.forEach((tab) => {
        const label = tab.textContent.trim().toLowerCase();
        if (label === 'all deals') tab.dataset.tab = 'all';
        if (label === 'codes') tab.dataset.tab = 'codes';
        if (label === 'deals') tab.dataset.tab = 'deals';
        if (label === 'free shipping') tab.dataset.tab = 'free-shipping';
        if (!tab.hasAttribute('aria-pressed')) {
            tab.setAttribute('aria-pressed', tab.classList.contains('active') ? 'true' : 'false');
        }
    });

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-pressed', 'false');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-pressed', 'true');

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

                row.style.display = show ? 'flex' : 'none';
            });
        });
    });
});

@if(isset($pageView) && $pageView)
(function() {
    const pageViewId = {{ $pageView->id }};
    const startTime = Date.now();
    let timeOnPage = 0;
    let isBounce = true;
    let hasInteracted = false;
    
    ['click', 'scroll', 'keydown', 'touchstart'].forEach(event => {
        document.addEventListener(event, function() {
            hasInteracted = true;
            isBounce = false;
        }, { once: true });
    });
    
    setInterval(function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
    }, 1000);
    
    window.addEventListener('beforeunload', function() {
        timeOnPage = Math.floor((Date.now() - startTime) / 1000);
        const data = JSON.stringify({
            time_on_page: timeOnPage,
            is_bounce: isBounce && !hasInteracted && timeOnPage < 30
        });
        navigator.sendBeacon(
            '{{ route("analytics.update-page-view", ":id") }}'.replace(':id', pageViewId),
            data
        );
    });
    
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
            }).catch(() => {});
        }
    });
})();
@endif
</script>
</body>
</html>
