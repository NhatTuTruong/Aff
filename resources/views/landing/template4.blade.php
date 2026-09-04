<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
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
        // Sắp xếp coupon theo thứ tự tạo (cũ -> mới)
        $coupons = $coupons
            ->sortBy(fn ($c) => $c->created_at ?? $c->id ?? 0)
            ->values();
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
        
        $campaignSlug = $campaign->slug;

        $brandName = $campaign->brand->name ?? $campaign->title;
        $rawIntro = (string) ($campaign->subtitle ?? $campaign->intro ?? '');
        $metaTitle = $brandName . ' Coupons & Promo Codes – ' . now()->format('F Y');
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
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="preload" href="{{ asset('images/t4-get-code-peel.png') }}" as="image" type="image/png">
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
            --t4-primary: #019a04;
            --t4-primary-dark: #008f46;
            --t4-primary-fold: #006b35;
            --t4-orange: #f5a623;
            --t4-link: #337ab7;
            --t4-text: #222222;
            --t4-text-muted: #777777;
            --t4-bg: #f7f7f7;
            --t4-card: #ffffff;
            --t4-border: #e3e3e3;
            --t4-font: 'Lato', Arial, Helvetica, sans-serif;
        }
        body.t4-corelux {
            font-family: var(--t4-font);
            font-size: 14px;
            line-height: 1.5;
            color: var(--t4-text);
            background: var(--t4-bg);
        }
        body.t4-corelux .site-chrome-topbar,
        body.t4-corelux .site-header,
        body.t4-corelux .site-footer,
        body.t4-corelux .site-header .logo {
            font-family: var(--t4-font);
        }
        body.t4-corelux a { text-decoration: none; color: var(--t4-primary) }

        .t4-page { background: var(--t4-bg); }
        .t4-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0px 40px;
        }
        .t4-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        /* Sidebar — Corelux store box */
        .t4-sidebar { position: sticky; top: 12px; }
        .t4-store-card {
            background: var(--t4-card);
            border: 1px solid var(--t4-border);
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            padding: 16px 14px 18px;
            text-align: center;
            margin-bottom: 12px;
        }
        .t4-logo-caption {
            font-size: 12px;
            font-weight: 700;
            color: #b33a3a;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        .t4-sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--t4-primary);
            margin: 0 0 10px;
            line-height: 1.25;
        }
        .t4-store-logo {
            width: 130px;
            height: 130px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            cursor: pointer;
        }
        .t4-store-logo:hover {
            opacity: 0.92;
        }
        .t4-store-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .t4-rating {
            font-size: 13px;
            color: var(--t4-text-muted);
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .t4-rating-score { font-weight: 700; color: var(--t4-text); }
        .t4-rating-sep { margin: 0 4px; }
        .t4-rate-brand {
            display: block;
            margin-bottom: 4px;
            font-size: 17px;
            font-weight: 700;
            color: var(--t4-primary) !important;
            text-align: center;
            text-decoration: none;
        }
        .t4-rate-brand:hover { color: #095400 !important; }
        .t4-rate-it {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--t4-orange) !important  ;
            text-decoration: underline;
        }
        .t4-rate-it:hover { color: #d48806; }
        .t4-stars {
            color: var(--t4-orange);
            font-size: 20px;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .t4-alert-btn {
            display: block;
            width: 100%;
            padding: 10px 12px;
            background: #fff;
            color: var(--t4-primary);
            font-weight: 700;
            font-size: 14px;
            border: 2px solid var(--t4-primary);
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            margin-top: 14px;
        }
        .t4-alert-btn:hover {
            background: #f0faf4;
            color: var(--t4-primary-dark);
        }
        .t4-stats-card {
            background: var(--t4-card);
            border: 1px solid var(--t4-border);
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            padding: 14px 14px 6px;
        }
        .t4-stats-heading {
            font-size: 13px;
            font-weight: 700;
            color: var(--t4-text);
            margin: 0 0 10px;
            line-height: 1.4;
        }
        .t4-coupon-summary { display: none; }
        .t4-stats-table {
            width: 100%;
            margin-top: 0;
            border-collapse: collapse;
            background: transparent;
            border: none;
            font-size: 13px;
        }
        .t4-stats-table td {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .t4-stats-table tr:last-child td { border-bottom: none; }
        .t4-stats-table td:first-child { color: var(--t4-text-muted); }
        .t4-stats-table td:last-child {
            text-align: right;
            font-weight: 700;
            color: var(--t4-text);
        }

        /* Main column */
        .t4-main { min-width: 0; }
        .t4-page-title {
            font-size: 26px;
            font-weight: 500;
            color: #111;
            margin: 0 0 10px;
            line-height: 1.25;
        }
        .t4-page-intro {
            font-size: 14px;
            color: var(--t4-text-muted);
            margin: 0 0 18px;
            line-height: 1.6;
        }
        .t4-brand-green { color: var(--t4-primary); font-weight: 700; }
        .t4-brand-link {
            text-decoration: none !important;
            color: var(--t4-primary-dark) !important;
            font-weight: 700;
        }
        .t4-brand-link:hover {
            color: #095400 !important;
        }

        /* Filter tabs */
        .t4-filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        .filter-pill {
            border-radius: 5px;
            padding: 10px 16px;
            font-size: 16px;
            border: 1px solid var(--t4-primary);
            background: var(--t4-card);
            color: var(--t4-primary);
            cursor: pointer;
            font-weight: 500;
            font-family: inherit;
        }
        .filter-pill:hover { background: #f4faf3; }
        .filter-pill.active {
            background: var(--t4-primary);
            border-color: var(--t4-primary);
            color: #fff;
        }

        /* Coupon list */
        .t4-coupon-list { margin-bottom: 24px; }
        .coupon-row {
            display: grid;
            grid-template-columns: 110px minmax(0, 1fr) 180px;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            background: var(--t4-card);
            border: 1px solid var(--t4-border);
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 12px;
            position: relative;
        }
        .t4-offer-label {
            font-size: 24px;
            font-weight: 700;
            color: var(--t4-primary);
            line-height: 1.1;
            text-align: center;
            height: 100%;
            vertical-align: middle;
            display: flex;
            align-content: center;
            align-items: center;
            border-right: 1px dashed #e7e7e7;
        }
        .t4-offer-label--stack {
            font-size: 20px;
            line-height: 1.15;
        }
        .t4-coupon-content { min-width: 0; }
        .t4-verified-tag {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: var(--t4-primary);
            margin-bottom: 15px;
        }
        .coupon-title {
            font-weight: 700;
            font-size: 17px;
            line-height: 1.35;
            color: var(--t4-text);
            margin: 0 0 15px;
        }
        .t4-coupon-desc {
            font-size: 13px;
            color: var(--t4-text-muted);
            line-height: 1.55;
            margin: 0;
        }
        /* Get Code peel button — Corelux style */
        .get-code,
        .get-deal {
            width: 180px;
            height: 40px;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }
        .get-code span {
            padding-right: 5px;
            right: 0;
            height: 40px;
            border: 2px dashed #019a04;
            font-size: 18px;
            text-align: right;
            line-height: 36px;
            color: #019a04;
            font-weight: 500;
            background: #fff;
            box-sizing: border-box;
            pointer-events: none;
            z-index: 1;
        }
        .get-code span,
        .get-code .btn-get-code,
        .get-code a.btn-get-code {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            border-radius: 5px;
            font-weight: 700;
        }
        .get-code .btn-get-code,
        .get-code a.btn-get-code {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKoAAAAoCAYAAABuDmFRAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFF2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNy4wLWMwMDAgNzkuMTM1N2M5ZSwgMjAyMS8wNy8xNC0wMDozOTo1NiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIyLjUgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyMS0xMC0yN1QyMTowNToyMiswNzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjEtMTAtMjdUMjE6MDk6NTMrMDc6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjEtMTAtMjdUMjE6MDk6NTMrMDc6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M2ExNDZmZTctNWZhOC0zNjRmLWIyMmYtNjUyMjRlYjBjZmYyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjNhMTQ2ZmU3LTVmYTgtMzY0Zi1iMjJmLTY1MjI0ZWIwY2ZmMiIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjNhMTQ2ZmU3LTVmYTgtMzY0Zi1iMjJmLTY1MjI0ZWIwY2ZmMiI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6M2ExNDZmZTctNWZhOC0zNjRmLWIyMmYtNjUyMjRlYjBjZmYyIiBzdEV2dDp3aGVuPSIyMDIxLTEwLTI3VDIxOjA1OjIyKzA3OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjIuNSAoV2luZG93cykiLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+c0FYeAAAAbZJREFUeJzt3EFOwkAYhuFvCIgkJrrBDUsvIB7CQ3onI5cwIbJxJ1pwXNQiLaVO25lpm7xPQgIsmM2bf2ZIwJinsRXgznSx6LiLRTFM09VEdpS8j9a7m1hrbh/TRAkVTi5fJpIks95dS7KKPFlHMRfDMGWRavNx/HbUIyOhwk0+0ky0WAkVlQ7TtGOEirPObPlFUaYqoaJadaSZ4LESKko12PKDxkqoOOG45ZcJFiuholz9SIMiVOR4uOUHmaqEioMWW36R91gJFXn+tnyvsRIqJAX7Yt9brISKPz27QB0jVGhqFiE/3stUJVTo076mT+az9OFf61gJFZKk7TLRdpmkL8IE2ypWQkVO4GAbx0qoKBUw2EaxEioqRTgSOCFUOPEcbO2pSqioxWOwtWIlVDTiKVjnWAkVrXgI1ilWQoUXoS9dhAqvGgb771QlVATRINjKWAkVQdUM9myshIooagRbGiuhIirHYE9iJVR0ou4ZllDRqYpgc1OVUNELxWDtfGZ1FCt/5IteyWKdmoX51v7L2M3Kan9PqOil35/HXEzM7V1iN8+Eil5L7NuVpAfOqBgEQsUg/AAW0qSZu5D5DgAAAABJRU5ErkJggg==);
            background-repeat: no-repeat;
            background-position: left top;
            background-size: 168px 40px;
            color: #fff;
            transition: left 0.2s, right 0.2s;
            text-align: center;
            font-size: 16px;
            line-height: 40px;
            height: 40px;
            right: 12px;
            left: 0;
            border: none;
            cursor: pointer;
            font-family: inherit;
            padding: 0;
            min-width: 120px;
            z-index: 2;
            text-decoration: none;
        }
        .get-code .btn-get-code:hover,
        .get-code a.btn-get-code:hover {
            left: -10px;
            right: 22px;
        }
        .get-deal .btn-get-code,
        .get-deal a.btn-get-code {
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background: #019a04;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 700;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
            font-family: inherit;
            padding: 0;
            transition: background 0.2s;
            text-decoration: none;
            z-index: 2;
        }
        .get-deal .btn-get-code:hover,
        .get-deal a.btn-get-code:hover {
            background: #017a03;
        }
        .coupon-revealed-code { display: none !important; }
        .staff-pick-badge, .top-pic-badge, .badge-row, .coupon-meta-info, .coupon-desc.coupon-verified { display: none !important; }

        /* Sections — table layout like Corelux */
        .section { margin-bottom: 18px; }
        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px;
            color: var(--t4-text);
        }
        .t4-content-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--t4-card);
        }
        .t4-content-table td {
            border: 1px solid var(--t4-border);
            padding: 14px 16px;
            font-size: 14px;
            color: var(--t4-text);
            line-height: 1.7;
            vertical-align: top;
        }
        .t4-content-table a { color: var(--t4-link); text-decoration: underline; }
        .intro-brand-link {
            color: var(--t4-primary);
            text-decoration: none;
            font-weight: 600;
        }
        .intro-brand-link:hover {
            color: #095400;
        }
        .t4-content-table strong { font-weight: 700; }
        .intro-content p { margin: 0 0 12px; }
        .intro-content p:last-child { margin-bottom: 0; }
        .intro-content a { color: var(--t4-link); text-decoration: underline; }
        .t4-qa-table td { font-size: 13px; line-height: 1.65; }
        .t4-qa-table .t4-qa-q {
            font-weight: 700;
            color: var(--t4-text);
            margin: 0 0 6px;
        }
        .t4-qa-table .t4-qa-a {
            color: var(--t4-text-muted);
            margin: 0;
        }

        /* Popular brands */
        .t4-brands-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 6px 14px;
            padding: 14px 16px;
            background: var(--t4-card);
            border: 1px solid var(--t4-border);
        }
        .t4-brands-grid a {
            font-size: 13px;
            color: var(--t4-primary);
            font-weight: 600;
        }
        .t4-brands-grid a:hover { color: var(--t4-primary-dark); text-decoration: underline; }

        .t4-mobile-shop { display: none; }
        @media (max-width: 960px) {
            .t4-layout { grid-template-columns: 1fr; }
            .t4-sidebar { position: static; }
        }
        @media (max-width: 768px) {
            .t4-container { padding: 16px 12px 36px; }
            .coupon-row { grid-template-columns: 1fr; text-align: left; }
            .t4-offer-label { text-align: left; font-size: 1.2rem; border-right: none; }
            .get-code, .get-deal { width: 100%; max-width: 180px; }
            .t4-brands-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .t4-mobile-shop { display: block; margin: 16px 0; text-align: center; }
            .t4-mobile-shop a {
                display: inline-block;
                padding: 12px 24px;
                background: var(--t4-primary);
                color: #fff !important;
                font-weight: 700;
                border-radius: 3px;
            }
            .t4-mobile-shop a:hover { background: var(--t4-primary-dark); color: #fff; }
        }
        @media (max-width: 480px) {
            .filter-pill { font-size: 0.78rem; padding: 6px 12px; }
            .t4-page-title { font-size: 1.35rem; }
        }

        /* Modal */
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
        .coupon-modal-stack {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            max-width: 100%;
            max-height: calc(100vh - 24px);
            padding: 0 12px;
            box-sizing: border-box;
        }
        .coupon-copy-toast[hidden] { display: none !important; }
        .coupon-copy-toast {
            display: flex;
            flex-direction: column;
            background: #2c2c2e;
            color: #fff;
            padding: 0;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translate(-50%, -24px);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.26s ease, opacity 0.26s ease;
            z-index: 10050;
            overflow: hidden;
            width: min(420px, calc(100vw - 2rem));
            max-width: min(420px, calc(100vw - 2rem));
            min-width: 0;
            font-size: 0.95rem;
            font-weight: 500;
            font-family: inherit;
            -webkit-tap-highlight-color: transparent;
        }
        .coupon-copy-toast.is-visible {
            transform: translate(-50%, 0);
            opacity: 1;
            pointer-events: auto;
        }
        .coupon-copy-toast.is-hiding {
            transform: translate(-50%, -24px);
            opacity: 0;
            pointer-events: none;
        }
        .coupon-copy-toast-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 12px 14px 14px;
        }
        .coupon-copy-toast-icon { flex-shrink: 0; font-weight: 700; font-size: 1.05rem; line-height: 1.35; margin-top: 1px; }
        .coupon-copy-toast-text {
            flex: 1;
            margin: 0;
            text-align: center;
            line-height: 1.45;
            min-width: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .coupon-copy-toast-text strong { font-weight: 700; color: #fff; }
        .coupon-copy-toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            padding: 6px 8px;
            margin: -4px -4px 0 0;
            min-width: 40px;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border-radius: 8px;
            transition: background 0.15s;
            touch-action: manipulation;
        }
        .coupon-copy-toast-close:hover { background: rgba(255, 255, 255, 0.12); }
        @media (max-width: 480px) {
            .coupon-copy-toast { width: calc(100vw - 1.5rem); max-width: calc(100vw - 1.5rem); font-size: 0.88rem; }
            .coupon-copy-toast-row { padding: 10px 10px 12px 12px; gap: 8px; }
        }
        .coupon-copy-toast-progress {
            width: 100%;
            height: 3px;
            background: #22c55e;
            transform-origin: left center;
            animation: couponCopyToastProgress 2s linear forwards;
        }
        @keyframes couponCopyToastProgress {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }
        .coupon-modal-content {
            position: relative;
            background: var(--t4-card);
            width: 100%;
            max-width: 580px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: t2PopupScale 0.3s ease;
            max-height: calc(100vh - 24px);
            display: flex;
            flex-direction: column;
        }
        .t2-popup-banner {
            background: linear-gradient(180deg, var(--t4-primary-dark) 0%, var(--t4-primary) 100%);
            padding: 28px 24px 32px;
            text-align: center;
            position: relative;
        }
        .t2-popup-logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fff;
            padding: 10px;
            margin: 0 auto 12px;
            object-fit: contain;
        }
        .t2-popup-title { font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0; }
        .t2-popup-subtitle { font-size: 0.9rem; color: rgba(255,255,255,0.9); margin-top: 8px; }
        .t2-popup-banner { flex-shrink: 0; }
        .t2-popup-body {
            padding: 24px;
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }
        .coupon-code-container {
            background: var(--t4-primary-dark);
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
            background: var(--t4-primary);
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
            background: var(--t4-primary);
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
            background: var(--t4-primary-dark);
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
            color: var(--t4-text-muted);
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--t4-border);
            flex-wrap: wrap;
            gap: 8px;
        }
        .verification-item { display: flex; align-items: center; gap: 6px; }
        .verification-item::before { content: '✓'; color: var(--t4-primary); font-weight: 700; }
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
            background: var(--t4-primary);
            color: #fff;
        }
        .coupon-btn.store:hover {
            background: var(--t4-primary-dark);
            transform: translateY(-1px);
            color: #fff;
        }
        .popup-feedback { margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--t4-border); }
        .popup-feedback-question { text-align: center; font-size: 0.95rem; font-weight: 600; margin-bottom: 12px; }
        .popup-feedback-buttons { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .lead-capture {
            margin-top: 14px;
        }
        .lead-capture-label {
            margin: 0 0 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--t4-text);
            letter-spacing: -0.01em;
        }
        .lead-capture-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
        }
        .lead-capture-input {
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--t4-border);
            padding: 0 12px;
            font-size: 0.9rem;
            width: 100%;
            font-family: inherit;
            color: var(--t4-text);
            background: #fff;
        }
        .lead-capture-input:focus {
            outline: none;
            border-color: var(--t4-primary);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.18);
        }
        .lead-capture-btn {
            height: 42px;
            border: none;
            border-radius: 10px;
            padding: 0 16px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            background: var(--t4-primary);
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s ease;
            white-space: nowrap;
        }
        .lead-capture-btn:hover { background: var(--t4-primary-dark); }
        .lead-capture-msg {
            margin-top: 6px;
            min-height: 18px;
            font-size: 0.78rem;
            color: var(--t4-text-muted);
        }
        .lead-capture-msg.success { color: #16a34a; }
        .lead-capture-msg.error { color: #dc2626; }
        .feedback-btn {
            background: #fff;
            border: 1px solid var(--t4-border);
            border-radius: 6px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--t4-text);
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
        .feedback-btn:hover { border-color: var(--t4-primary); color: var(--t4-primary); }
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
        .all-codes-modal-content { max-width: 580px; }
        .all-codes-list { display: flex; flex-direction: column; gap: 12px; }
        .all-codes-item {
            border: 1px solid var(--t4-border);
            border-radius: 8px;
            overflow: hidden;
        }
        .all-codes-item .coupon-code-container { margin-bottom: 0; }
        .all-codes-item-desc { padding: 10px 16px; font-size: 0.85rem; color: var(--t4-text-muted); }
    </style>
</head>
<body class="t4-corelux">
@include('partials.site-header')

<div class="t4-page">
<div class="t4-container">
@php
    use App\Models\Campaign;
    use App\Services\CouponDescriptionGenerator;

    $couponDescGenerator = app(CouponDescriptionGenerator::class);
    $campaignSeed = (int) ($campaign->id ?? 0);
    $totalCoupons = $coupons->count();
    $codeCount = $coupons->filter(fn ($c) => !empty($c->code))->count();
    $dealCount = $coupons->filter(fn ($c) => empty($c->code))->count();
    $verifiedCount = $totalCoupons;
    $reviewCount = 150 + ($campaignSeed % 120);
    $bestOfferLabel = $maxPercent > 0
        ? $maxPercent . '% OFF'
        : ($maxCurrencyValue > 0 ? strtoupper($maxCurrencySymbol . number_format($maxCurrencyValue, 0, '.', '') . ' OFF') : 'Deals');

    $affiliateUrl = route('click.redirect', ['slug' => $campaignSlug]);
    $firstCodeCoupon = $couponsWithCodes->first();
    $alertBtnLabel = $codeCount > 0 ? 'Get Coupon Alert' : 'Get Deals';

    $popularBrands = Campaign::query()
        ->where('status', 'active')
        ->where('id', '!=', $campaign->id)
        ->where('type', 'coupon')
        ->with('brand')
        ->inRandomOrder()
        ->limit(24)
        ->get();

    $formatOfferLabel = function ($offerType, $offerValue, $currencySymbol, $isFreeShipping = false): string {
        if ($offerType === 'text' && $offerValue === 'FREE') {
            return 'Free Shipping';
        }
        if ($offerType === 'percent') {
            return $offerValue . '% OFF';
        }
        if ($offerType === 'currency') {
            return $currencySymbol . number_format((float) str_replace([',', ' '], ['', ''], (string) $offerValue), 0, '.', '') . ' OFF';
        }

        return 'Deal';
    };

    $formatHeadline = function ($coupon, $offerType, $offerValue, $currencySymbol, $isFreeShipping) use ($brandName): string {
        if ($offerType === 'currency') {
            return 'Get ' . $currencySymbol . number_format((float) str_replace([',', ' '], ['', ''], (string) $offerValue), 0, '.', '') . ' off your order';
        }
        if ($offerType === 'text' && $offerValue === 'FREE') {
            return 'Get free shipping on your order';
        }
        if ($offerType === 'percent') {
            return 'Get ' . $offerValue . '% off your order';
        }

        return 'Save with this ' . $brandName . ' offer';
    };

    $siteName = config('app.name');
    $displayBrand = trim($brandName) . ' .';
    $faqs = [
        ['q' => 'Why should I visit ' . $siteName . ' for ' . $displayBrand . ' coupons?', 'a' => $siteName . ' collects the top discounts from ' . $displayBrand . ', even at the last minute, and updates continually to help you save.'],
        ['q' => 'Where to find ' . $displayBrand . ' promo codes?', 'a' => 'On the official ' . $displayBrand . ' website or on ' . $siteName . ' for more verified options.'],
        ['q' => 'Will all ' . $displayBrand . ' discounts automatically be applied at checkout?', 'a' => 'No. Some deals require a code at checkout; others apply automatically depending on the offer.'],
        ['q' => 'Can you give me a guide for using ' . $displayBrand . ' coupon codes?', 'a' => 'Copy the code that fits your order, add items to cart at ' . $displayBrand . ', paste the code in the promo field, and apply before you pay.'],
        ['q' => 'How to use ' . $displayBrand . ' coupon code?', 'a' => 'Pick a coupon on this page, click GET CODE or GET DEAL, copy if needed, and complete checkout at the store.'],
        ['q' => 'How often does ' . $displayBrand . ' release a new coupon?', 'a' => 'New offers appear regularly; peak shopping seasons often bring larger and more frequent discounts.'],
        ['q' => 'How long are ' . $displayBrand . ' deals valid?', 'a' => 'Each promotion has its own expiry. Use codes soon after you find them for the best chance they still work.'],
        ['q' => 'How to get ' . $displayBrand . ' free shipping offer?', 'a' => 'Check the deals list above for free-shipping offers and read the minimum order requirements before checkout.'],
        ['q' => 'What is currently the best coupon of ' . $displayBrand . '?', 'a' => 'The best offer on this page is highlighted in the sidebar under Best Offer and in the coupon list.'],
        ['q' => 'How do I find ' . $displayBrand . ' coupons?', 'a' => 'Visit this page or check back on ' . $siteName . ' for newly verified codes and deals.'],
    ];
@endphp

<div class="t4-layout">
    <aside class="t4-sidebar">
        <div class="t4-store-card">
            <div class="t4-store-logo" role="button" tabindex="0" aria-label="Get coupon alert for {{ $brandName }}">
                @if($campaign->brand)
                    <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" loading="lazy">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" loading="lazy">
                @else
                    <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" loading="lazy">
                @endif
            </div>
            <a href="#coupon-list" class="t4-rate-brand" onclick="document.getElementById('t4-coupon-alert-btn').click(); return false;">{{ $brandName }}</a>
            <div class="t4-stars" aria-hidden="true">★★★★★</div>
            <div class="t4-rating">
                <span class="t4-rating-score">5.0</span>
                <span class="t4-rating-sep">/</span>
                <span class="t4-rating-votes">{{ $reviewCount }} votes</span>
                <a href="#coupon-list" class="t4-rate-it">Rate it</a>
            </div>
            <a href="#"
                class="t4-alert-btn"
                id="t4-coupon-alert-btn"
                data-has-codes="{{ $codeCount > 0 ? '1' : '0' }}"
                data-url="{{ $affiliateUrl }}"
                @if($firstCodeCoupon)
                data-first-coupon-id="{{ $firstCodeCoupon->id }}"
                data-first-code="{{ $firstCodeCoupon->code }}"
                @endif>{{ $alertBtnLabel }}</a>
        </div>
        <div class="t4-stats-card">
            <p class="t4-stats-heading">{{ $codeCount }} Coupons, {{ $verifiedCount }} Verified Coupons</p>
            <table class="t4-stats-table" aria-label="Store coupon statistics">
                <tbody>
                    <tr><td>Coupon Codes</td><td>{{ $codeCount }}</td></tr>
                    <tr><td>Deals</td><td>{{ $dealCount }}</td></tr>
                    <tr><td>Best Offer</td><td>{{ $bestOfferLabel }}</td></tr>
                </tbody>
            </table>
        </div>
    </aside>

    <main class="t4-main">
        <h1 class="t4-page-title">{{ $displayBrand }} Coupons and Promo Codes</h1>
        <p class="t4-page-intro">
            Great chance to save money at
            <a href="{{ $affiliateUrl }}" class="t4-brand-link" target="_blank" rel="nofollow sponsored noopener">{{ $brandName }}</a>
            because sale season is here. Time to go shopping!
        </p>

        <div class="t4-filter-tabs">
            <button class="filter-pill" type="button" data-tab="all" aria-pressed="false">All({{ $totalCoupons }})</button>
            <button class="filter-pill active" type="button" data-tab="verified" aria-pressed="true">Verified({{ $verifiedCount }})</button>
            <button class="filter-pill" type="button" data-tab="codes" aria-pressed="false">Codes({{ $codeCount }})</button>
            <button class="filter-pill" type="button" data-tab="deals" aria-pressed="false">Deals({{ $dealCount }})</button>
        </div>

        <div class="t4-coupon-list" id="coupon-list">
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

                $couponHeadline = $hasCode
                    ? ($descriptionText !== ''
                        ? $descriptionText
                        : $formatHeadline($coupon, $offerType, $offerValue, $currencySymbol, $isFreeShipping))
                    : ($descriptionText !== ''
                        ? $descriptionText
                        : $formatHeadline($coupon, $offerType, $offerValue, $currencySymbol, $isFreeShipping));

                $descSeed = (int) (($campaign->id ?? 0) * 1000 + ($coupon->id ?? 0) + ($coupon->sort_order ?? 0));

                if ($hasCode) {
                    $couponBody = $couponDescGenerator->generate($offerText, true, $brandName, $descSeed)
                        . ' – <b style="color: #313131;">Click "Get Code" – to get the code.</b>';
                } else {
                    $couponBody = ($offerText !== ''
                        ? $offerText
                        : ($descriptionText !== ''
                            ? $descriptionText
                            : $couponDescGenerator->generate($offerText, false, $brandName, $descSeed)))
                        . ' – <b style="color: #313131;">Click "Get Deal" – discount applied automatically.</b>';
                }

                $codeDisplay = '';
                if ($hasCode) {
                    $codeDisplay = strtoupper(preg_replace('/\s+/', '', (string) $coupon->code));
                }
            @endphp
            <article
                class="coupon-row"
                data-type="{{ $hasCode ? 'code' : 'deal' }}"
                data-tags="{{ $tagAttr }}"
                data-coupon-id="{{ $coupon->id }}"
            >
                <div class="t4-offer-label{{ ($isFreeShipping && ! $hasCode) ? ' t4-offer-label--stack' : '' }}">
                    @if($isFreeShipping && ! $hasCode)
                        Free<br>Shipping
                    @else
                        {{ $formatOfferLabel($offerType, $offerValue, $currencySymbol, $isFreeShipping) }}
                    @endif
                </div>
                <div class="t4-coupon-content">
                    <span class="t4-verified-tag">{{ $hasCode ? 'Verified Code' : 'Verified Deal' }}</span>
                    <h2 class="coupon-title">{{ $couponHeadline }}</h2>
                    <p class="t4-coupon-desc">{!! $couponBody !!}</p>
                </div>
                @if($hasCode)
                <div class="get-code">
                    <span>{{ $codeDisplay }}</span>
                    <a href="#"
                        class="btn-get-code"
                        role="button"
                        data-type="code"
                        data-code="{{ $coupon->code }}"
                        data-coupon-id="{{ $coupon->id }}"
                        data-url="{{ route('click.redirect', ['slug' => $campaignSlug]) }}"
                        aria-label="Get coupon code and go to store">Get Code</a>
                </div>
                @else
                <div class="get-deal">
                    <a href="#"
                        class="btn-get-code"
                        role="button"
                        data-type="deal"
                        data-coupon-id="{{ $coupon->id }}"
                        data-url="{{ route('click.redirect', ['slug' => $campaignSlug]) }}"
                        aria-label="Open deal and go to store">Get Deal</a>
                </div>
                @endif
            </article>
            @empty
            <article class="section">
                <div class="section-title">No coupons available</div>
                <div class="section-body">
                    Please check back later. We are updating new deals.
                </div>
            </article>
            @endforelse
        </div>

        <div class="t4-mobile-shop">
            <a href="{{ route('click.redirect', ['slug' => $campaignSlug]) }}" target="_blank" rel="nofollow sponsored noopener">Visit Store</a>
        </div>

        <section class="section" id="about-us">
            <h2 class="section-title">About store</h2>
            <table class="t4-content-table">
                <tbody>
                    <tr>
                        <td class="intro-content">
                @if($campaign->intro)
                    @php
                        $intro = (string) $campaign->intro;
                        $hasHtml = $intro !== strip_tags($intro);
                        // Replace brand name with affiliate link (case-insensitive, whole word)
                        $intro = preg_replace(
                            '/\b(' . preg_quote($brandName, '/') . ')\b/i',
                            '<a href="' . e($affiliateUrl) . '" target="_blank" rel="nofollow sponsored noopener" class="intro-brand-link">$1</a>',
                            $intro
                        );
                    @endphp
                    {!! $intro !!}
                @else
                <p>
                    <a href="{{ $affiliateUrl }}" target="_blank" rel="nofollow sponsored noopener" class="intro-brand-link">{{ $brandName }}</a> is a trusted online store with competitive prices, fast shipping, and reliable customer service.
                    Shoppers can save more by using the latest <a href="{{ $affiliateUrl }}" target="_blank" rel="nofollow sponsored noopener" class="intro-brand-link">{{ $brandName }}</a> coupon codes, promo codes, and exclusive discounts available before checkout.
                </p>
                @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="section" id="how-to-use">
            <h2 class="section-title">How to apply {{ $displayBrand }} coupon codes</h2>
            <table class="t4-content-table">
                <tbody>
                    <tr>
                        <td>
                            <p><strong>How to apply {{ $displayBrand }} coupon codes?</strong></p>
                            <p><strong>Step 1</strong>: Find your {{ $displayBrand }} coupons and discount codes on this page or {{ $siteName }}, click <strong>GET CODE</strong>, then copy the code to your clipboard.</p>
                            <p><strong>Step 2</strong>: Go to {{ $displayBrand }}, add items to your cart, and proceed to checkout.</p>
                            <p><strong>Step 3</strong>: Paste your {{ $displayBrand }} coupon in the Promo Code or Discount Code field and click <strong>Apply</strong> to see your savings.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="section" id="qa">
            <h2 class="section-title">{{ $displayBrand }} Questions &amp; Answers</h2>
            <table class="t4-content-table t4-qa-table">
                <tbody>
                    @foreach($faqs as $faq)
                    <tr>
                        <td>
                            <p class="t4-qa-q">Q: {{ $faq['q'] }}</p>
                            <p class="t4-qa-a">A: {{ $faq['a'] }}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        @if($popularBrands->isNotEmpty())
        <section class="section" id="popular-brands">
            <h2 class="section-title">Popular Brands</h2>
            <div class="t4-brands-grid">
                @foreach($popularBrands as $brandCampaign)
                    <a href="{{ route('landing.show', $brandCampaign->slug) }}">{{ $brandCampaign->brand->name ?? $brandCampaign->title }}</a>
                @endforeach
            </div>
        </section>
        @endif
    </main>
</div>
</div>
</div>

@include('partials.site-footer')

<div id="couponModal" class="coupon-modal" role="dialog" aria-modal="true" aria-labelledby="coupon-modal-title">
    <div class="coupon-modal-stack">
        <div id="couponCopyToast" class="coupon-copy-toast" role="status" aria-live="polite" hidden>
            <div class="coupon-copy-toast-row">
                <span class="coupon-copy-toast-icon" aria-hidden="true">✓</span>
                <p class="coupon-copy-toast-text">Code copied! Go to <strong>{{ e($brandName) }}</strong></p>
                <button type="button" class="coupon-copy-toast-close" aria-label="Đóng" onclick="dismissCouponCopyToast()">✕</button>
            </div>
            <div class="coupon-copy-toast-progress" aria-hidden="true"></div>
        </div>
    <div class="coupon-modal-content">
        <button type="button" class="coupon-modal-close" aria-label="Close coupon popup" onclick="closeCouponPopup()">✕</button>
        <div class="t2-popup-banner">
            <div>
                @if($campaign->brand)
                    <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" class="t2-popup-logo" loading="lazy">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" class="t2-popup-logo" loading="lazy">
                @else
                    <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" class="t2-popup-logo" loading="lazy">
                @endif
                <h3 class="t2-popup-title" id="coupon-modal-title">
                    {{ $campaign->brand->name ?? $campaign->title }} Promotion
                </h3>
                <p class="t2-popup-subtitle">
                    A Little Discount, A Lot of Joy—Save Now with {{ $campaign->brand->name ?? $campaign->title }} Promotion.
                </p>
            </div>
        </div>
        <div class="t2-popup-body">
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
            <div class="lead-capture">
                <p class="lead-capture-label">Get exclusive discount codes.</p>
                <div class="lead-capture-row">
                    <input id="leadEmailInput" type="email" class="lead-capture-input" placeholder="Enter your email">
                    <button id="leadSubmitBtn" type="button" class="lead-capture-btn" onclick="submitLeadEmail(this)">Send</button>
                </div>
                <p id="leadCaptureMsg" class="lead-capture-msg"></p>
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
</div>

<div id="allCodesModal" class="coupon-modal all-codes-modal" role="dialog" aria-modal="true" aria-labelledby="all-codes-modal-title">
    <div class="coupon-modal-content all-codes-modal-content">
        <button type="button" class="coupon-modal-close" aria-label="Close" onclick="closeAllCodesModal()">✕</button>
        <div class="t2-popup-banner">
            <div>
                @if($campaign->brand)
                    <img src="{{ $campaign->brand->image_url }}" alt="{{ $campaign->brand->name }}" class="t2-popup-logo" loading="lazy">
                @elseif($campaign->logo)
                    <img src="{{ asset('storage/' . $campaign->logo) }}" alt="{{ $campaign->title }}" class="t2-popup-logo" loading="lazy">
                @else
                    <img src="{{ asset('images/default-brand.svg') }}" alt="{{ $brandName }}" class="t2-popup-logo" loading="lazy">
                @endif
                <h3 class="t2-popup-title" id="all-codes-modal-title">All {{ $campaign->brand->name ?? $campaign->title }} Coupon Codes</h3>
                <p class="t2-popup-subtitle">Copy any code below and apply at checkout.</p>
            </div>
        </div>
        <div class="t2-popup-body">
            <div class="all-codes-list">
                @foreach($couponsWithCodes as $coupon)
                @php
                    $offerText = $ensureOfferUtf8($coupon->offer ?? '');
                    $offerValue = null;
                    $offerType = 'percent';
                    $currencySymbol = '';
                    $isFreeShipping = stripos($offerText, 'free shipping') !== false;
                    $currencyMap = [
                        'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥',
                        'INR' => '₹', 'CAD' => 'C$', 'AUD' => 'A$',
                        'CHF' => 'CHF', 'CNY' => '¥',
                    ];
                    if ($offerText !== '') {
                        if (preg_match('/(\d+)\s*%/i', $offerText, $m)) {
                            $offerValue = (int) $m[1];
                            $offerType = 'percent';
                        } elseif (preg_match('/([€$£¥₹])\s*(\d+(?:[.,]\d+)?)/u', $offerText, $m)) {
                            $currencySymbol = $m[1];
                            $offerValue = $m[2];
                            $offerType = 'currency';
                        } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*([€$£¥₹])/u', $offerText, $m)) {
                            $currencySymbol = $m[2];
                            $offerValue = $m[1];
                            $offerType = 'currency';
                        } elseif (preg_match('/(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)\s*(\d+(?:[.,]\d+)?)/i', $offerText, $m)) {
                            $code = strtoupper($m[1]);
                            $currencySymbol = $currencyMap[$code] ?? $code;
                            $offerValue = $m[2];
                            $offerType = 'currency';
                        } elseif (preg_match('/(\d+(?:[.,]\d+)?)\s*(USD|EUR|GBP|JPY|INR|CAD|AUD|CHF|CNY)/i', $offerText, $m)) {
                            $code = strtoupper($m[2]);
                            $currencySymbol = $currencyMap[$code] ?? $code;
                            $offerValue = $m[1];
                            $offerType = 'currency';
                        } elseif ($isFreeShipping) {
                            $offerValue = 'FREE';
                            $offerType = 'text';
                        }
                    } elseif ($isFreeShipping) {
                        $offerValue = 'FREE';
                        $offerType = 'text';
                    }
                @endphp
                <div class="all-codes-item">
                    <div class="coupon-code-container">
                        <div class="coupon-code-left">
                            <div class="coupon-code-icon">🏷</div>
                            <div class="coupon-code-box">{{ $coupon->code }}</div>
                        </div>
                        <button class="btn-copy-code-modal btn-copy-all-codes" type="button" data-code="{{ e($coupon->code) }}" aria-label="Copy code">COPY</button>
                    </div>
                    <div class="all-codes-item-desc">
                        @if($coupon->description)
                            {{ Str::limit($coupon->description, 80) }}
                        @elseif($offerType === 'currency' && $offerValue !== null)
                            Save {{ $currencySymbol }}{{ number_format((float)str_replace([',', ' '], ['', ''], $offerValue), 0, '.', '') }} on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                        @elseif($offerType === 'text' && $offerValue === 'FREE')
                            Get Free Shipping on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                        @elseif($offerValue !== null)
                            Save {{ $offerValue }}% on your order with this {{ $campaign->brand->name ?? $campaign->title }} promo code
                        @else
                            Save with this coupon code when you shop at {{ $campaign->brand->name ?? $campaign->title }} and apply it at checkout.
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="coupon-modal-actions" style="margin-top: 20px;">
                <a href="{{ route('click.redirect', ['slug' => $campaignSlug]) }}"
                   target="_blank" rel="nofollow sponsored noopener"
                   class="coupon-btn store">
                    Go To The Store
                </a>
            </div>
            <div class="lead-capture">
                <p class="lead-capture-label">Get exclusive discount codes.</p>
                <div class="lead-capture-row">
                    <input id="leadEmailInputAllCodes" type="email" class="lead-capture-input" placeholder="Enter your email">
                    <button id="leadSubmitBtnAllCodes" type="button" class="lead-capture-btn"
                            data-input-id="leadEmailInputAllCodes" data-msg-id="leadCaptureMsgAllCodes"
                            onclick="submitLeadEmail(this)">Send</button>
                </div>
                <p id="leadCaptureMsgAllCodes" class="lead-capture-msg"></p>
            </div>
        </div>
    </div>
</div>

<script>
let couponCopyRedirectTimer = null;
let couponCopyToastHideTimer = null;
let currentCode = '';
let currentCouponRow = null;
let currentCouponId = null;
let currentAffUrl = null;
let affiliateAlreadyOpened = false;
function getStoredRevealed() { return {}; }
function saveRevealed(couponId, code) { return; }

function revealCodeInRow(couponId, code, affUrl) {
    const row = document.querySelector('.coupon-row[data-coupon-id="' + couponId + '"]');
    if (!row) return;
    const codeSpan = row.querySelector('.get-code span');
    if (codeSpan) {
        codeSpan.textContent = String(code || '').toUpperCase().replace(/\s+/g, '');
        saveRevealed(couponId, code);
    }

    // Nếu popup all-codes đang mở, hiện luôn code thật cho item tương ứng
    document
        .querySelectorAll('#allCodesModal .all-codes-item .btn-copy-all-codes')
        .forEach(function (bt) {
            if (bt.dataset.code === code) {
                const box = bt
                    .closest('.all-codes-item')
                    ?.querySelector('.coupon-code-box');
                if (box && box.dataset.realCode) {
                    box.textContent = box.dataset.realCode;
                }
            }
        });
}
function copyCodeToClipboard(text) {
    navigator.clipboard.writeText(text);
}

function showCouponCopyToast() {
    const el = document.getElementById('couponCopyToast');
    if (!el) return;
    if (couponCopyToastHideTimer) {
        clearTimeout(couponCopyToastHideTimer);
        couponCopyToastHideTimer = null;
    }
    el.classList.remove('is-hiding');
    el.hidden = false;
    void el.offsetWidth;
    el.classList.add('is-visible');
    const bar = el.querySelector('.coupon-copy-toast-progress');
    if (bar) {
        bar.style.animation = 'none';
        void bar.offsetWidth;
        bar.style.animation = '';
    }
}

function hideCouponCopyToast() {
    const el = document.getElementById('couponCopyToast');
    if (!el) return;
    el.classList.remove('is-visible');
    el.classList.add('is-hiding');
    if (couponCopyToastHideTimer) {
        clearTimeout(couponCopyToastHideTimer);
    }
    couponCopyToastHideTimer = setTimeout(function() {
        el.hidden = true;
        el.classList.remove('is-hiding');
        couponCopyToastHideTimer = null;
    }, 260);
}

function dismissCouponCopyToast() {
    if (couponCopyRedirectTimer) {
        clearTimeout(couponCopyRedirectTimer);
        couponCopyRedirectTimer = null;
    }
    if (couponCopyToastHideTimer) {
        clearTimeout(couponCopyToastHideTimer);
        couponCopyToastHideTimer = null;
    }
    hideCouponCopyToast();
    const copyBtn = document.getElementById('copyCouponBtn');
    if (copyBtn) {
        copyBtn.disabled = false;
        if (copyBtn.dataset.copyLabelDefault) {
            copyBtn.innerText = copyBtn.dataset.copyLabelDefault;
        }
    }
}

function setCouponModalHash(couponId) {
    const id = String(couponId);
    const path = window.location.pathname + window.location.search;
    history.replaceState(null, '', path + '#coupon=' + encodeURIComponent(id));
}

function clearCouponModalHash() {
    if (!/^#coupon=/.test(window.location.hash)) return;
    history.replaceState(null, '', window.location.pathname + window.location.search);
}

function restoreCouponModalFromHash() {
    const m = /^#coupon=(.+)$/.exec(window.location.hash);
    if (!m) return;
    const id = decodeURIComponent(m[1]);
    const singleModal = document.getElementById('couponModal');
    const allModal = document.getElementById('allCodesModal');
    if (singleModal && singleModal.classList.contains('active')) return;
    if (allModal && allModal.classList.contains('active')) return;

    if (id === 'all-codes') {
        const allRow = document.querySelector('.coupon-row[data-coupon-id="all-codes"]') ||
            document.querySelector('.coupon-row[data-all-codes="1"]');
        if (allRow) handleCouponClick(allRow);
        return;
    }

    const btn = Array.prototype.find.call(document.querySelectorAll('.btn-get-code'), function (b) {
        return b.dataset.couponId === id;
    });
    if (!btn) return;
    if (btn.dataset.allCodes === '1' || btn.dataset.allCodes === 'true') {
        handleCouponClick(btn.closest('.coupon-row') || btn);
        return;
    }
    const type = btn.dataset.type;
    const code = btn.dataset.code || '';
    const url = btn.dataset.url;
    const couponId = btn.dataset.couponId;
    const activeTabBtn = document.querySelector('.filter-pill.active');
    const activeTab = activeTabBtn ? (activeTabBtn.dataset.tab || 'all') : 'all';
    if (type === 'deal' || activeTab === 'deals') {
        clearCouponModalHash();
        return;
    }
    if (!code) {
        clearCouponModalHash();
        return;
    }
    currentCouponRow = btn.closest('.coupon-row');
    openModalForCoupon(couponId, code, url);
}

function openModalForCoupon(couponId, code, affUrl) {
    currentCode = code;
    currentCouponId = couponId;
    currentAffUrl = affUrl || null;
    const codeBox = document.getElementById('modalCode');
    if (codeBox) {
        // Ẩn code cho tới khi user bấm Copy
        codeBox.innerText = '••••••••';
    }
    const modal = document.getElementById('couponModal');
    if (modal) modal.classList.add('active');
    const goBtn = document.querySelector('.go-to-store-btn');
    if (goBtn && affUrl) {
        goBtn.setAttribute('data-url', affUrl);
        goBtn.href = affUrl;
    }
    resetLeadCapture('leadEmailInput', 'leadCaptureMsg', 'leadSubmitBtn');
    setCouponModalHash(couponId);
}

function resetLeadCapture(inputId, msgId, btnId) {
    const input = document.getElementById(inputId);
    const msg = document.getElementById(msgId);
    const btn = document.getElementById(btnId);
    if (input) input.value = '';
    if (msg) { msg.textContent = ''; msg.className = 'lead-capture-msg'; }
    if (btn) btn.disabled = false;
}

function submitLeadEmail(btn){
    const inputId = (btn && btn.dataset && btn.dataset.inputId) ? btn.dataset.inputId : 'leadEmailInput';
    const msgId = (btn && btn.dataset && btn.dataset.msgId) ? btn.dataset.msgId : 'leadCaptureMsg';
    const input = document.getElementById(inputId);
    const msg = document.getElementById(msgId);
    if (!input || !msg) return;
    const email = (input.value || '').trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        msg.textContent = 'Please enter a valid email address.';
        msg.className = 'lead-capture-msg error';
        return;
    }

    btn.disabled = true;
    msg.textContent = 'Sending...';
    msg.className = 'lead-capture-msg';

    fetch('{{ route('customer-leads.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            campaign_id: {{ (int) $campaign->id }},
            email: email,
            source_template: 'template4'
        })
    })
    .then(async (res) => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.ok) throw new Error(data.message || 'Sending email failed');
        msg.textContent = 'Email submitted successfully.';
        msg.className = 'lead-capture-msg success';
        input.value = '';
    })
    .catch((e) => {
        msg.textContent = e.message || 'Unable to submit your email right now.';
        msg.className = 'lead-capture-msg error';
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function handleCouponAlertClick(alertBtn, e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const affUrl = alertBtn.dataset.url || null;
    const hasCodes = alertBtn.dataset.hasCodes === '1';
    if (hasCodes) {
        const couponId = alertBtn.dataset.firstCouponId;
        const code = alertBtn.dataset.firstCode || '';
        if (!couponId || !code) {
            return false;
        }

        if (!affiliateAlreadyOpened) {
            const couponUrl = new URL(window.location.href);
            couponUrl.searchParams.set('show_coupon', String(couponId));
            couponUrl.searchParams.set('code', String(code));
            couponUrl.searchParams.set('aff_opened', '1');
            couponUrl.hash = '';
            window.open(couponUrl.toString(), '_blank', 'noopener');
            if (affUrl) {
                window.location.href = affUrl;
            }
            return false;
        }

        revealCodeInRow(couponId, code, affUrl);
        openModalForCoupon(couponId, code, affUrl);
        return false;
    }
    if (affUrl) {
        window.location.href = affUrl;
    }
    return false;
}

function handleCouponClick(btn, e){
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const actualBtn = btn.classList && btn.classList.contains('btn-get-code') ? btn : btn.querySelector('.btn-get-code');
    const row = btn.closest('.coupon-row');
    if (!actualBtn && !row) return false;
    const target = actualBtn || row;
    const isAllCodes = target.dataset.allCodes === '1' || target.dataset.allCodes === 'true';

    if (isAllCodes) {
        const affUrl =
            target.dataset.url ||
            (document.querySelector('.btn-get-code[data-url]')?.dataset.url || null);

        if (!affiliateAlreadyOpened) {
            // Flow mới: tab hiện tại -> link aff, tab mới -> mở trang coupon + modal
            const couponUrl = new URL(window.location.href);
            couponUrl.searchParams.set('aff_opened', '1');
            couponUrl.hash = 'coupon=all-codes';
            window.open(couponUrl.toString(), '_blank', 'noopener');
            if (affUrl) window.location.href = affUrl;
            return false;
        }

        // Nếu đã mở affiliate trước đó (tab mới), chỉ mở modal để copy
        document.getElementById('allCodesModal').classList.add('active');
        resetLeadCapture('leadEmailInputAllCodes', 'leadCaptureMsgAllCodes', 'leadSubmitBtnAllCodes');
        setCouponModalHash('all-codes');
        document.querySelectorAll('.btn-copy-all-codes').forEach(function(bt) {
            bt.onclick = function() {
                const code = this.dataset.code;
                if (!code) return;
                copyCodeToClipboard(code);
                const originalText = this.textContent;

                // Hiện code thật trong list khi user copy
                const box = this
                    .closest('.all-codes-item')
                    ?.querySelector('.coupon-code-box');
                if (box && box.dataset.realCode) {
                    box.textContent = box.dataset.realCode;
                }

                this.textContent = 'Copied ✓';
                this.disabled = true;

                setTimeout(() => {
                    this.textContent = 'Go To Store';
                }, 600);

                setTimeout(() => {
                    if (affUrl && !affiliateAlreadyOpened) {
                        window.open(affUrl, '_blank');
                    }
                    this.textContent = originalText;
                    this.disabled = false;
                    this.classList.remove('copied');
                }, 1500);
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

    // Flow mới cho Get Code:
    // - Nếu chưa mở affiliate tab nào: tab hiện tại -> aff, tab mới -> trang coupon + modal
    // - Nếu affiliate đã mở trước đó (tab mới): chỉ mở modal để copy
    if (!affiliateAlreadyOpened) {
        const couponUrl = new URL(window.location.href);
        couponUrl.searchParams.set('show_coupon', String(couponId));
        couponUrl.searchParams.set('code', String(code));
        couponUrl.searchParams.set('aff_opened', '1');
        couponUrl.hash = '';
        window.open(couponUrl.toString(), '_blank', 'noopener');
        if (url) window.location.href = url;
        return false;
    }

    openModalForCoupon(couponId, code, url);
    return false;
}

function closeCouponPopup(){
    if (couponCopyRedirectTimer) {
        clearTimeout(couponCopyRedirectTimer);
        couponCopyRedirectTimer = null;
    }
    hideCouponCopyToast();
    const copyBtn = document.getElementById('copyCouponBtn');
    if (copyBtn) {
        copyBtn.disabled = false;
        if (copyBtn.dataset.copyLabelDefault) {
            copyBtn.innerText = copyBtn.dataset.copyLabelDefault;
        }
    }
    document.getElementById('couponModal').classList.remove('active');
    clearCouponModalHash();
}
function closeAllCodesModal(){
    document.getElementById('allCodesModal').classList.remove('active');
    clearCouponModalHash();
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

    if (!btn.dataset.copyLabelDefault) {
        btn.dataset.copyLabelDefault = btn.innerText.trim();
    }

    copyCodeToClipboard(currentCode);
    const originalText = btn.dataset.copyLabelDefault;

    const codeBox = document.getElementById('modalCode');
    if (codeBox) {
        codeBox.innerText = currentCode;
    }

    btn.innerText = 'Copied ✓';
    btn.disabled = true;
    showCouponCopyToast();

    const goBtn = document.querySelector('.go-to-store-btn');
    if(goBtn){
        goBtn.classList.remove('go-store-attention');
        void goBtn.offsetWidth;
        goBtn.classList.add('go-store-attention');
    }

    const affUrl = currentAffUrl || (goBtn ? goBtn.getAttribute('data-url') : null);
    if (currentCouponId && currentCode) {
        revealCodeInRow(currentCouponId, currentCode, affUrl);
    }

    if (couponCopyRedirectTimer) clearTimeout(couponCopyRedirectTimer);
    couponCopyRedirectTimer = setTimeout(function() {
        couponCopyRedirectTimer = null;
        hideCouponCopyToast();
        if (affUrl && !affiliateAlreadyOpened) {
            window.open(affUrl, '_blank');
        }
        btn.innerText = originalText;
        btn.disabled = false;
    }, 2300);
}
document.addEventListener('DOMContentLoaded', function () {
    const affUrl = (function() {
        const b = document.querySelector('.btn-get-code[data-url]');
        return b ? b.dataset.url : null;
    })();
    // Không khôi phục code đã reveal từ lần tải trước.

    // Ẩn toàn bộ mã trong popup all-codes cho tới khi user copy
    document
        .querySelectorAll('#allCodesModal .all-codes-item .coupon-code-box')
        .forEach(function (box) {
            const real = (box.textContent || '').trim();
            box.dataset.realCode = real;
            box.textContent = '••••••••';
        });

    const urlParams = new URLSearchParams(window.location.search);
    affiliateAlreadyOpened = urlParams.get('aff_opened') === '1';
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
    } else {
        restoreCouponModalFromHash();
    }

    window.addEventListener('hashchange', function () {
        restoreCouponModalFromHash();
    });

    document.addEventListener('click', function (e) {
        const logoTrigger = e.target.closest('.t4-store-logo');
        if (logoTrigger) {
            const alertBtn = document.getElementById('t4-coupon-alert-btn');
            if (alertBtn) {
                handleCouponAlertClick(alertBtn, e);
            }
            return;
        }

        const alertBtn = e.target.closest('.t4-alert-btn');
        if (alertBtn) {
            handleCouponAlertClick(alertBtn, e);
            return;
        }

        const couponBtn = e.target.closest('.get-code .btn-get-code, .get-deal .btn-get-code');
        if (couponBtn) {
            handleCouponClick(couponBtn, e);
            return;
        }

        const tab = e.target.closest('.t4-filter-tabs .filter-pill');
        if (!tab) return;

        e.preventDefault();
        const tabs = document.querySelectorAll('.t4-filter-tabs .filter-pill');
        const rows = document.querySelectorAll('.coupon-row');

        tabs.forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-pressed', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-pressed', 'true');

        const current = tab.dataset.tab || 'all';

        rows.forEach(row => {
            const type = row.dataset.type;
            let show = true;

            if (current === 'codes') {
                show = type === 'code';
            } else if (current === 'deals') {
                show = type === 'deal';
            } else {
                show = true;
            }

            row.style.display = show ? 'grid' : 'none';
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
