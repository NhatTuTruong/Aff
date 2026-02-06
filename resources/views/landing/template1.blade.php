<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->title }} - Exclusive Deals & Coupons</title>
    <meta name="description" content="{{ $campaign->subtitle ?? $campaign->intro }}">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>


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
        /* ===== ORIGINAL STYLE (UNCHANGED) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary:#6366f1;--primary-dark:#4f46e5;--secondary:#8b5cf6;
            --accent:#ec4899;--text-dark:#1f2937;--text-light:#6b7280;
            --bg-light:#f9fafb;--bg-white:#ffffff;--border:#e5e7eb;
            --shadow:0 2px 4px rgba(0,0,0,0.1);--shadow-lg:0 4px 12px rgba(0,0,0,0.15);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont,
                        'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 0.9rem;
            line-height: 1.65;
            color: var(--text-dark);
            background: var(--bg-light);
        }
        a { text-decoration:none;color:inherit; }
        .page {
            max-width:1200px;margin:0 auto;
            padding:24px 16px 40px;
            display:grid;grid-template-columns:320px 1fr;gap:24px;
        }
        .left-card {
            background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);
            border-radius:16px;padding:24px 22px 28px;color:#fff;
            box-shadow:0 18px 45px rgba(79,70,229,0.35);
            position:relative;overflow:hidden;
        }
        .left-inner { position:relative;z-index:1; }
        .brand-logo {
            max-width:180px;height:auto;object-fit:contain;
            display:block;margin-bottom:16px;
        }
        .brand-name { font-size:1.8rem;font-weight:800;margin-bottom:6px; }
        .brand-subtitle { font-size:0.95rem;opacity:0.95;margin-bottom:14px; }
        .brand-rating {
            display:flex;align-items:center;gap:8px;
            margin-bottom:10px;font-size:0.8rem;
        }
        .stars { color:#fde047; }
        .brand-meta { font-size:0.8rem;opacity:0.9;margin-bottom:4px; }
        .brand-extra {
            margin-top:12px;padding-top:10px;
            border-top:1px solid rgba(255,255,255,0.25);
            font-size:0.8rem;opacity:0.95;
        }
        .right-column { display:flex;flex-direction:column;gap:18px; }
        .coupon-list { display:flex;flex-direction:column;gap:10px; }
        .coupon-row {
            background:#fff;border-radius:12px;
            box-shadow:0 10px 30px rgba(15,23,42,0.12);
            padding:16px 18px;
            display:grid;grid-template-columns:minmax(0,1fr) auto;
            gap:14px;align-items:center;
            border-left:4px solid var(--primary);
        }
        .coupon-info { display:flex;flex-direction:column;gap:4px; }
        .coupon-title { font-weight:700;font-size:0.98rem; }
        .coupon-offer { font-size:0.9rem;color:var(--primary);font-weight:700; }
        .coupon-desc { font-size:0.85rem;color:var(--text-light); }
        .coupon-actions {
            display:flex;flex-direction:column;
            align-items:flex-end;gap:6px;
        }
        .coupon-code {
            font-family:ui-monospace,monospace;
            font-size:0.85rem;padding:4px 10px;
            border-radius:999px;border:1px dashed #d4d4d8;
            background:#f9fafb;
        }
        .btn-copy {
            min-width:150px;padding:8px 12px;
            border-radius:999px;border:none;cursor:pointer;
            font-size:0.8rem;font-weight:700;
            color:#fff;background:var(--primary);
        }
        .btn-copy:hover { background:var(--primary-dark); }

        .section {
            background:#fff;border-radius:12px;
            padding:18px 20px 20px;box-shadow:var(--shadow);
        }
        .section-title { font-size:1rem;font-weight:700;margin-bottom:8px; }
        .section-body { font-size:0.85rem;color:var(--text-light); }
        .section-body ul { padding-left:18px;margin:6px 0; }
        .section-body li { margin-bottom:4px; }

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
        max-width: 520px;
        border-radius: 18px;
        padding: 26px 28px 28px;
        box-shadow: 0 25px 60px rgba(15,23,42,.35);
        animation: popupScale .3s ease;
        position: relative;
    }

    @keyframes popupScale {
        from { transform: scale(.92); opacity: .5; }
        to { transform: scale(1); opacity: 1; }
    }

    .coupon-modal-close {
        position: absolute;
        top: 14px;
        right: 16px;
        font-size: 20px;
        cursor: pointer;
        color: #9ca3af;
    }

    .coupon-modal-close:hover {
        color: #111827;
    }

    .coupon-modal-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .coupon-modal-sub {
        font-size: .9rem;
        color: #6b7280;
        margin-bottom: 18px;
    }

    .coupon-code-box {
        background: #f9fafb;
        border: 2px dashed #6366f1;
        border-radius: 14px;
        padding: 14px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 18px;
    }

    .coupon-modal-actions {
        display: flex;
        gap: 12px;
    }

    .coupon-btn {
        flex: 1;
        padding: 12px 14px;
        border-radius: 999px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
    }

    .coupon-btn.copy {
        background: #6366f1;
        color: #ffffff;
    }

    .coupon-btn.copy:hover {
        background: #4f46e5;
    }

    .coupon-btn.store {
        background: #111827;
        color: #ffffff;
    }

    .coupon-btn.store:hover {
        background: #000000;
    }

    /* Reveal coupon code after copy */
    .coupon-row.revealed .coupon-code {
        opacity: 1;
        filter: none;
        max-height: 100px;
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
        color: var(--text-light);
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


</style>
</head>

<body>
<div class="page">
    <!-- LEFT CARD (UNCHANGED, TEXT TRANSLATED) -->
    <aside class="left-card">
    <div class="review-center">
        @if($campaign->brand && $campaign->brand->image)
            <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                alt="{{ $campaign->brand->name }}"
                class="brand-logo">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}"
                alt="{{ $campaign->title }}"
                class="brand-logo">
        @endif

        <div class="review-stars">★★★★★</div>

        <div class="review-rating-text">
            4.8 / 5 based on 1,200+ verified reviews
        </div>

        <button class="btn-get-coupon"
            onclick="window.location.href='{{ route('click.redirect',$campaign->slug) }}'">
            Get Coupon Alert
        </button>
    </div>

    </aside>

    <!-- RIGHT COLUMN (FULL, NOT REMOVED) -->
    <main class="right-column">
        <section class="section coupon-intro">
            <h2 class="coupon-intro-title">
                {{ $campaign->brand->name ?? $campaign->title }} Coupons & Promo Codes
            </h2>
            <p class="coupon-intro-desc">
                Save money with the latest verified coupon codes, deals, and special offers from
                {{ $campaign->brand->name ?? $campaign->title }}. All coupons are tested and updated regularly.
            </p>
        </section>

        <section class="coupon-list">
            @php $coupons = $campaign->couponItems ?? collect(); @endphp

            @forelse($coupons as $coupon)
            <article class="coupon-row" data-code="{{ $coupon->code }}">
                <div class="coupon-info">
                    <div class="coupon-title">
                        {{ $coupon->description ?: 'Exclusive coupon from '.($campaign->brand->name ?? $campaign->title) }}
                    </div>
                    @if($coupon->offer)
                        <div class="coupon-offer">{{ $coupon->offer }}</div>
                    @endif
                    <div class="coupon-desc">
                        Click “Get Deal” to reveal the code and visit the store.
                    </div>
                </div>
                <div class="coupon-actions">
                    @if($coupon->code)
                        <div class="coupon-code peek">
                        {{ $coupon->code }}
                        </div>
                    @endif
                    <button class="btn-copy"
                        onclick="openCouponPopup('{{ $coupon->code }}', this)">
                        Get Deal
                    </button>
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
            <div class="section-body">
                @if($campaign->intro)
                    <p>{{ $campaign->intro }}</p>
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
</div>

<div id="couponModal" class="coupon-modal">
    <div class="coupon-modal-content">
        <div class="coupon-modal-close" onclick="closeCouponPopup()">✕</div>
        <div class="popup-header">
        @if($campaign->brand && $campaign->brand->image)
            <img src="{{ asset('storage/' . $campaign->brand->image) }}"
                alt="{{ $campaign->brand->name }}"
                class="popup-logo">
        @elseif($campaign->logo)
            <img src="{{ asset('storage/' . $campaign->logo) }}"
                alt="{{ $campaign->title }}"
                class="popup-logo">
        @endif

        <h3 class="popup-title">
            {{ $campaign->brand->name ?? $campaign->title }}
        </h3>

        <p class="popup-subtitle">
            Copy the code below and apply it at checkout
        </p>
    </div>
        <div id="modalCode" class="coupon-code-box">
            CODE123
        </div>

        <div class="coupon-modal-actions">
            <button class="coupon-btn copy" id="copyCouponBtn" onclick="copyCoupon(this)">
                Copy Code
            </button>
            <a href="{{ route('click.redirect',$campaign->slug) }}"
            target="_blank"
            class="btn-get-coupon go-to-store-btn">
                Go to Store
            </a>
        </div>
    </div>
</div>


<script>
let currentCode = '';
let currentCouponRow = null;

function openCouponPopup(code, el){
    // set lại context coupon
    currentCode = code;
    currentCouponRow = el.closest('.coupon-row');

    // set code trong popup
    document.getElementById('modalCode').innerText = code;

    // RESET nút copy
    const copyBtn = document.getElementById('copyCouponBtn');
    if(copyBtn){
        copyBtn.innerText = 'Copy code';
        copyBtn.disabled = false;
    }

    // mở popup
    document.getElementById('couponModal').classList.add('active');
}

function closeCouponPopup(){
    document.getElementById('couponModal').classList.remove('active');
}

function copyCoupon(btn){
    if(!currentCode) return;

    navigator.clipboard.writeText(currentCode).then(()=>{
        btn.innerText = 'Copied ✓';
        btn.disabled = true;
           // 👉 highlight nút Go to Store
        const goBtn = document.querySelector('.go-to-store-btn');
        if(goBtn){
            goBtn.classList.remove('go-store-attention');
            void goBtn.offsetWidth; // force reflow
            goBtn.classList.add('go-store-attention');
        }

        // mở mã coupon đúng block
        if(currentCouponRow){
            currentCouponRow.classList.add('revealed');
        }
    });
}
function toggleQA(el){
    const item = el.closest('.qa-item');
    item.classList.toggle('active');
}
</script>



</body>
</html>
