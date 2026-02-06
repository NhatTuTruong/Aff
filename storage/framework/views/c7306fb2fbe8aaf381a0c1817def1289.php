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
/* ========== RESET ========== */
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Inter',system-ui,-apple-system;
    background:
        radial-gradient(1200px 600px at 10% -10%, #c7d2fe 0%, transparent 40%),
        radial-gradient(1000px 600px at 90% 10%, #fbcfe8 0%, transparent 45%),
        #f8fafc;
    color:#0f172a;
    font-size:15px;
    line-height:1.7;
}
a{text-decoration:none;color:inherit}

/* ========== LAYOUT ========== */
.page{
    max-width:1200px;
    margin:auto;
    padding:40px 20px 80px;
    display:grid;
    grid-template-columns:340px 1fr;
    gap:32px;
}
@media(max-width:1024px){
    .page{grid-template-columns:1fr}
}

/* ========== LEFT GLASS CARD ========== */
.left-card{
    background:rgba(255,255,255,.6);
    backdrop-filter:blur(20px);
    border-radius:24px;
    padding:32px 28px;
    box-shadow:
        0 20px 50px rgba(15,23,42,.15),
        inset 0 1px 0 rgba(255,255,255,.6);
    position:sticky;
    top:32px;
}

.review-center{text-align:center}

.brand-logo{
    width:76px;
    height:76px;
    border-radius:50%;
    margin:0 auto 14px;
    background:#fff;
    padding:6px;
    box-shadow:0 12px 30px rgba(0,0,0,.18);
}

.review-stars{
    font-size:1.4rem;
    color:#facc15;
    margin-bottom:6px;
}

.review-rating-text{
    font-size:.85rem;
    color:#475569;
    margin-bottom:18px;
}

.btn-get-coupon{
    width:100%;
    padding:14px;
    border-radius:14px;
    border:none;
    font-weight:800;
    font-size:.9rem;
    color:#fff;
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    cursor:pointer;
    box-shadow:0 12px 30px rgba(99,102,241,.45);
}
.btn-get-coupon:hover{
    transform:translateY(-1px);
}

/* ========== RIGHT COLUMN ========== */
.right-column{
    display:flex;
    flex-direction:column;
    gap:40px;
}

/* ========== HERO INTRO ========== */
.coupon-intro{
    background:rgba(255,255,255,.7);
    backdrop-filter:blur(16px);
    border-radius:28px;
    padding:32px;
    box-shadow:0 20px 60px rgba(15,23,42,.12);
}

.coupon-intro-title{
    font-size:2rem;
    font-weight:900;
    letter-spacing:-.03em;
}

.coupon-intro-desc{
    margin-top:12px;
    color:#475569;
    max-width:720px;
}

/* ========== COUPON CARDS ========== */
.coupon-list{
    display:grid;
    grid-template-columns:1fr;
    gap:22px;
}

.coupon-row{
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(14px);
    border-radius:22px;
    padding:26px;
    display:grid;
    grid-template-columns:1fr 200px;
    gap:24px;
    align-items:center;
    box-shadow:0 25px 60px rgba(15,23,42,.14);
    transition:.25s;
}
.coupon-row:hover{
    transform:translateY(-3px);
}

.coupon-title{
    font-weight:700;
    font-size:1rem;
}

.coupon-offer{
    font-size:1.4rem;
    font-weight:900;
    background:linear-gradient(135deg,#6366f1,#ec4899);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.coupon-desc{
    font-size:.85rem;
    color:#475569;
}

.coupon-actions{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.coupon-code{
    text-align:center;
    padding:10px;
    border-radius:999px;
    border:1px dashed #94a3b8;
    font-weight:700;
}
.coupon-code.peek{
    filter:blur(5px);
    opacity:.45;
}

.btn-copy{
    padding:12px;
    border-radius:999px;
    border:none;
    background:#0f172a;
    color:#fff;
    font-weight:800;
    cursor:pointer;
}

/* ========== SECTIONS ========== */
.section{
    background:rgba(255,255,255,.8);
    backdrop-filter:blur(14px);
    border-radius:22px;
    padding:28px;
    box-shadow:0 18px 50px rgba(15,23,42,.1);
}

.section-title{
    font-size:1.3rem;
    font-weight:800;
    margin-bottom:12px;
}

.section-body{
    color:#475569;
}

/* ========== Q&A ========== */
.qa-item{
    border-radius:14px;
    background:#fff;
    margin-bottom:10px;
    overflow:hidden;
    box-shadow:0 8px 22px rgba(0,0,0,.08);
}

.qa-question{
    width:100%;
    padding:16px 18px;
    font-weight:700;
    background:none;
    border:none;
    text-align:left;
    cursor:pointer;
}

.qa-answer{
    max-height:0;
    overflow:hidden;
    transition:.3s;
    padding:0 18px;
}
.qa-item.active .qa-answer{
    max-height:200px;
    padding:10px 18px 18px;
}

/* ========== POPUP ========== */
.coupon-modal{
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.6);
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    pointer-events:none;
    transition:.25s;
    z-index:9999;
}
.coupon-modal.active{
    opacity:1;
    pointer-events:auto;
}

.coupon-modal-content{
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(20px);
    border-radius:28px;
    padding:36px;
    width:92%;
    max-width:520px;
    text-align:center;
    box-shadow:0 30px 80px rgba(15,23,42,.35);
}

.popup-logo{
    width:72px;
    height:72px;
    border-radius:50%;
    margin-bottom:14px;
}

.popup-title{
    font-size:1.5rem;
    font-weight:900;
}

.popup-subtitle{
    color:#475569;
    margin-bottom:22px;
}

.coupon-code-box{
    padding:16px;
    border-radius:18px;
    border:2px dashed #6366f1;
    font-size:1.3rem;
    font-weight:900;
    margin-bottom:22px;
}

.coupon-modal-actions{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.coupon-btn.copy{
    background:linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    padding:14px;
    border-radius:999px;
    font-weight:900;
    border:none;
}

.go-to-store-btn{
    background:#0f172a;
    color:#fff;
    padding:14px;
    border-radius:999px;
    font-weight:900;
}

/* ========== ATTENTION ========== */
.go-store-attention{
    animation:pulse 1s ease-in-out 3;
}
@keyframes pulse{
    0%{box-shadow:0 0 0 rgba(99,102,241,0)}
    50%{box-shadow:0 0 28px rgba(99,102,241,.6)}
    100%{box-shadow:0 0 0 rgba(99,102,241,0)}
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
<?php /**PATH D:\CampAff\resources\views/landing/template3.blade.php ENDPATH**/ ?>