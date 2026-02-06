<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($campaign->title); ?> - Exclusive Deals & Coupons</title>
    <meta name="description" content="<?php echo e($campaign->subtitle ?? $campaign->intro); ?>">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>


    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.ga4_id')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(config('app.ga4_id')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo e(config('app.ga4_id')); ?>');
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <style>
/* ================= RESET ================= */
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Inter',system-ui;
    background:#f5f5f5;
    color:#0a0a0a;
    font-size:15px;
    line-height:1.75;
}
a{text-decoration:none;color:inherit}

/* ================= LAYOUT ================= */
.page{
    max-width:1280px;
    margin:auto;
    padding:40px 24px 80px;
    display:grid;
    grid-template-columns:380px 1fr;
    gap:40px;
}
@media(max-width:1024px){
    .page{grid-template-columns:1fr}
}

/* ================= LEFT = EDITORIAL COVER ================= */
.left-card{
    background:#0a0a0a;
    color:#fff;
    padding:40px 32px;
    position:sticky;
    top:40px;
    height:fit-content;
}

.review-center{text-align:left}

.brand-logo{
    width:64px;
    height:64px;
    border-radius:0;
    margin-bottom:24px;
    filter:invert(1);
}

.review-stars{
    font-size:1.6rem;
    letter-spacing:4px;
    margin-bottom:8px;
}

.review-rating-text{
    font-size:.85rem;
    opacity:.7;
    margin-bottom:28px;
}

.btn-get-coupon{
    width:100%;
    padding:16px;
    font-size:.9rem;
    font-weight:900;
    text-transform:uppercase;
    background:#22c55e;
    color:#000;
    border:none;
    cursor:pointer;
}

/* ================= RIGHT COLUMN ================= */
.right-column{
    display:flex;
    flex-direction:column;
    gap:50px;
}

/* ================= INTRO LIKE MAGAZINE ================= */
.coupon-intro{
    border-bottom:4px solid #000;
    padding-bottom:24px;
}

.coupon-intro-title{
    font-size:2.2rem;
    font-weight:900;
    letter-spacing:-.03em;
    line-height:1.2;
}

.coupon-intro-desc{
    font-size:1rem;
    color:#444;
    margin-top:12px;
    max-width:720px;
}

/* ================= COUPON = FEATURE BLOCK ================= */
.coupon-list{
    display:flex;
    flex-direction:column;
    gap:32px;
}

.coupon-row{
    background:#fff;
    border:3px solid #000;
    padding:28px;
    display:grid;
    grid-template-columns:1fr 220px;
    gap:24px;
    align-items:center;
}

.coupon-info{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.coupon-title{
    font-size:1.15rem;
    font-weight:800;
}

.coupon-offer{
    font-size:1.8rem;
    font-weight:900;
    line-height:1.1;
}

.coupon-desc{
    font-size:.9rem;
    color:#444;
}

.coupon-actions{
    display:flex;
    flex-direction:column;
    gap:14px;
    align-items:flex-start;
}

.coupon-code{
    padding:10px 14px;
    border:2px dashed #000;
    font-weight:700;
    font-size:.9rem;
}

.coupon-code.peek{
    filter:blur(6px);
    opacity:.4;
}

.btn-copy{
    width:100%;
    padding:14px;
    background:#000;
    color:#fff;
    font-weight:900;
    text-transform:uppercase;
    border:none;
    cursor:pointer;
}

/* ================= SECTIONS ================= */
.section{
    border-top:2px solid #000;
    padding-top:28px;
}

.section-title{
    font-size:1.4rem;
    font-weight:900;
    margin-bottom:14px;
}

.section-body{
    max-width:760px;
    color:#333;
}

/* ================= Q&A ================= */
.qa-item{
    border-bottom:1px solid #000;
}

.qa-question{
    padding:18px 0;
    font-size:1rem;
    font-weight:800;
    background:none;
    border:none;
    width:100%;
    text-align:left;
    cursor:pointer;
}

.qa-answer{
    max-height:0;
    overflow:hidden;
    transition:.3s;
    color:#444;
}

.qa-item.active .qa-answer{
    max-height:200px;
    padding-bottom:18px;
}

/* ================= POPUP = CAMPAIGN POSTER ================= */
.coupon-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.85);
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    pointer-events:none;
    transition:.3s;
    z-index:9999;
}

.coupon-modal.active{
    opacity:1;
    pointer-events:auto;
}

.coupon-modal-content{
    background:#fff;
    max-width:520px;
    width:92%;
    padding:36px;
    text-align:center;
}

.popup-logo{
    width:56px;
    margin-bottom:16px;
}

.popup-title{
    font-size:1.6rem;
    font-weight:900;
    margin-bottom:6px;
}

.popup-subtitle{
    font-size:.9rem;
    color:#555;
    margin-bottom:24px;
}

.coupon-code-box{
    border:3px dashed #000;
    padding:18px;
    font-size:1.4rem;
    font-weight:900;
    margin-bottom:24px;
}

.coupon-modal-actions{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.coupon-btn.copy{
    background:#000;
    color:#fff;
    padding:14px;
    font-weight:900;
}

.go-to-store-btn{
    background:#22c55e;
    color:#000;
    padding:14px;
    font-weight:900;
    text-transform:uppercase;
}

/* ================= ATTENTION ================= */
.go-store-attention{
    animation:shake .9s ease-in-out 2;
}
@keyframes shake{
    0%,100%{transform:translateX(0)}
    25%{transform:translateX(-6px)}
    75%{transform:translateX(6px)}
}
</style>


</head>

<body>
<div class="page">
    <!-- LEFT CARD (UNCHANGED, TEXT TRANSLATED) -->
    <aside class="left-card">
    <div class="review-center">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
            <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>"
                alt="<?php echo e($campaign->brand->name); ?>"
                class="brand-logo">
        <?php elseif($campaign->logo): ?>
            <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>"
                alt="<?php echo e($campaign->title); ?>"
                class="brand-logo">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="review-stars">★★★★★</div>

        <div class="review-rating-text">
            4.8 / 5 based on 1,200+ verified reviews
        </div>

        <button class="btn-get-coupon"
            onclick="window.location.href='<?php echo e(route('click.redirect',$campaign->slug)); ?>'">
            Get Coupon Alert
        </button>
    </div>

    </aside>

    <!-- RIGHT COLUMN (FULL, NOT REMOVED) -->
    <main class="right-column">
        <section class="section coupon-intro">
            <h2 class="coupon-intro-title">
                <?php echo e($campaign->brand->name ?? $campaign->title); ?> Coupons & Promo Codes
            </h2>
            <p class="coupon-intro-desc">
                Save money with the latest verified coupon codes, deals, and special offers from
                <?php echo e($campaign->brand->name ?? $campaign->title); ?>. All coupons are tested and updated regularly.
            </p>
        </section>

        <section class="coupon-list">
            <?php $coupons = $campaign->couponItems ?? collect(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="coupon-row" data-code="<?php echo e($coupon->code); ?>">
                <div class="coupon-info">
                    <div class="coupon-title">
                        <?php echo e($coupon->description ?: 'Exclusive coupon from '.($campaign->brand->name ?? $campaign->title)); ?>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coupon->offer): ?>
                        <div class="coupon-offer"><?php echo e($coupon->offer); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="coupon-desc">
                        Click “Get Deal” to reveal the code and visit the store.
                    </div>
                </div>
                <div class="coupon-actions">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coupon->code): ?>
                        <div class="coupon-code peek">
                        <?php echo e($coupon->code); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button class="btn-copy"
                        onclick="openCouponPopup('<?php echo e($coupon->code); ?>', this)">
                        Get Deal
                    </button>
                </div>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <article class="section">
                <div class="section-title">No coupons available</div>
                <div class="section-body">
                    Please check back later. We are updating new deals.
                </div>
            </article>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

        <!-- ABOUT -->
        <section class="section">
            <h2 class="section-title">About this campaign</h2>
            <div class="section-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->intro): ?>
                    <p><?php echo e($campaign->intro); ?></p>
                <?php else: ?>
                    <p>
                        Exclusive deals from <?php echo e($campaign->brand->name ?? $campaign->title); ?>

                        to help you save more when shopping online.
                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
            <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>"
                alt="<?php echo e($campaign->brand->name); ?>"
                class="popup-logo">
        <?php elseif($campaign->logo): ?>
            <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>"
                alt="<?php echo e($campaign->title); ?>"
                class="popup-logo">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h3 class="popup-title">
            <?php echo e($campaign->brand->name ?? $campaign->title); ?>

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
            <a href="<?php echo e(route('click.redirect',$campaign->slug)); ?>"
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
<?php /**PATH D:\CampAff\resources\views/landing/template2.blade.php ENDPATH**/ ?>