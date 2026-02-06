<!DOCTYPE html>
<html lang="<?php echo e($campaign->language ?? 'en'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($campaign->title); ?> - Exclusive Deal</title>
    <meta name="description" content="<?php echo e($campaign->subtitle ?? $campaign->intro); ?>">
    <meta name="robots" content="index, follow">
    
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #f59e0b; --primary-dark: #d97706; --secondary: #fbbf24;
            --accent: #ef4444; --text-dark: #1f2937; --text-light: #6b7280;
            --bg-light: #f9fafb; --bg-white: #ffffff; --border: #e5e7eb;
            --shadow: 0 2px 4px rgba(0,0,0,0.1); --shadow-lg: 0 4px 12px rgba(0,0,0,0.15);
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.875rem; line-height: 1.5; color: var(--text-dark); background: var(--bg-white);
        }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .top-section {
            display: grid; grid-template-columns: 1fr 350px; gap: 22px; margin-bottom: 40px;
        }
        .banner-left {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border-radius: 24px;
            padding: 48px; color: white; position: relative; overflow: hidden;
        }
        .banner-left::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.05) 10px, rgba(255,255,255,0.05) 20px);
        }
        .banner-content { position: relative; z-index: 1; }
        .brand-logo { max-width: 170px; height: auto; margin-bottom: 22px; object-fit: contain; }
        .brand-name { font-size: 2.75rem; font-weight: 900; margin-bottom: 18px; letter-spacing: -0.5px; }
        .banner-subtitle { font-size: 1.25rem; opacity: 0.95; margin-bottom: 26px; font-weight: 500; }
        .banner-image { width: 100%; height: auto; border-radius: 18px; margin-top: 26px; box-shadow: 0 16px 48px rgba(0,0,0,0.3); }
        .coupons-right {
            display: flex; flex-direction: column; gap: 10px;
        }
        .coupon-card {
            background: #fff7ed; border-radius: 18px; padding: 24px; box-shadow: 0 6px 24px rgba(245,158,11,0.25);
            border: 4px solid var(--primary); text-align: center; transition: all 0.3s;
            position: relative;
        }
        .coupon-card::after {
            content: '🎉'; position: absolute; top: -12px; right: -12px; font-size: 2rem;
            background: var(--primary); width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .coupon-card:hover { transform: translateY(-8px) rotate(1deg); box-shadow: 0 12px 40px rgba(245,158,11,0.4); }
        .coupon-badge {
            display: inline-block; background: var(--primary); color: white; padding: 7px 18px;
            border-radius: 25px; font-size: 0.7rem; font-weight: 800; margin-bottom: 16px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .coupon-discount {
            font-size: 2.75rem; font-weight: 900; color: var(--primary-dark); margin: 18px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .coupon-description { font-size: 0.9rem; color: var(--text-dark); margin-bottom: 12px; font-weight: 700; }
        .coupon-disclaimer { font-size: 0.75rem; color: var(--text-light); margin-bottom: 20px; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px; padding: 16px 36px;
            background: var(--primary); color: white; text-decoration: none; border-radius: 12px;
            font-size: 0.875rem; font-weight: 800; transition: all 0.3s; width: 100%;
            justify-content: center; box-shadow: 0 6px 20px rgba(245,158,11,0.5);
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btn-cta:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 8px 28px rgba(245,158,11,0.6); }
        .content-section {
            padding: 55px 45px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 24px; margin-bottom: 40px; box-shadow: var(--shadow-lg);
        }
        .section-title {
            font-size: 2.25rem; font-weight: 900; margin-bottom: 36px; text-align: center;
            color: var(--primary-dark); text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .intro-text {
            font-size: 0.95rem; color: var(--text-dark); line-height: 1.8; text-align: center;
            max-width: 800px; margin: 0 auto 44px; font-weight: 500;
        }
        .product-gallery {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 32px;
            margin-top: 44px;
        }
        .product-card {
            background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            transition: transform 0.3s; border: 4px solid transparent;
        }
        .product-card:hover { transform: translateY(-12px) scale(1.03); box-shadow: 0 16px 48px rgba(245,158,11,0.3); border-color: var(--primary); }
        .product-card img { width: 100%; height: auto; display: block; }
        .trust-section {
            display: flex; justify-content: center; gap: 70px; flex-wrap: wrap; padding: 55px 20px;
            background: white; border-radius: 24px; box-shadow: var(--shadow);
        }
        .trust-item {
            display: flex; align-items: center; gap: 14px; color: var(--text-light); font-size: 0.9rem;
            font-weight: 600;
        }
        .trust-icon { width: 32px; height: 32px; color: var(--primary); }
        footer {
            background: var(--text-dark); color: white; padding: 45px 20px; text-align: center;
            margin-top: 55px; border-radius: 24px 24px 0 0;
        }
        .footer-links {
            display: flex; justify-content: center; gap: 32px; margin-bottom: 24px; flex-wrap: wrap;
        }
        .footer-links a { color: white; text-decoration: none; font-size: 0.85rem; }
        .footer-disclaimer { font-size: 0.75rem; opacity: 0.7; }
        @media (max-width: 1024px) {
            .top-section { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .product-gallery { grid-template-columns: 1fr; }
            .trust-section { gap: 35px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-section">
            <div class="banner-left">
                <div class="banner-content">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
                    <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>" alt="<?php echo e($campaign->brand->name); ?>" class="brand-logo">
                    <?php elseif($campaign->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>" alt="<?php echo e($campaign->title); ?>" class="brand-logo">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 class="brand-name"><?php echo e($campaign->brand->name ?? $campaign->title); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->subtitle): ?>
                    <p class="banner-subtitle"><?php echo e($campaign->subtitle); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->cover_image): ?>
                    <img src="<?php echo e(asset('storage/' . $campaign->cover_image)); ?>" alt="<?php echo e($campaign->title); ?>" class="banner-image">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="coupons-right">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->coupons && count($campaign->coupons) > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $campaign->coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $code = is_array($coupon) ? ($coupon['code'] ?? null) : $coupon;
                        $offer = is_array($coupon) ? ($coupon['offer'] ?? null) : null;
                        $description = is_array($coupon)
                            ? ($coupon['description'] ?? 'Get Up To Discount on Orders Site-wide')
                            : 'Get Up To Discount on Orders Site-wide';
                        $isDeal = stripos($description, 'free shipping') !== false || stripos($description, 'free') !== false;
                        $discountText = $offer ? ($offer . ' OFF') : '10% OFF';
                    ?>
                    <div class="coupon-card">
                        <span class="coupon-badge"><?php echo e($isDeal ? 'Verified Deal' : 'Verified Code'); ?></span>
                        <div class="coupon-discount"><?php echo e($isDeal ? 'Free Shipping' : $discountText); ?></div>
                        <div class="coupon-description"><?php echo e($description); ?></div>
                        <div class="coupon-disclaimer">Don't miss this chance to save money.</div>
                        <a href="<?php echo e(route('click.redirect', $campaign->slug)); ?>" class="btn-cta">
                            <?php echo e($isDeal ? 'Get Deal' : 'Get Code'); ?>

                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($campaign->coupon_code): ?>
                    <div class="coupon-card">
                        <span class="coupon-badge">Verified Code</span>
                        <div class="coupon-discount">10% OFF</div>
                        <div class="coupon-description">Get Up To Discount on Orders Site-wide</div>
                        <div class="coupon-disclaimer">Don't miss this chance to save money.</div>
                        <a href="<?php echo e(route('click.redirect', $campaign->slug)); ?>" class="btn-cta">Get Code</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="content-section">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->intro): ?>
            <h2 class="section-title">About This Deal</h2>
            <p class="intro-text"><?php echo e($campaign->intro); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->product_images && count($campaign->product_images) > 0): ?>
            <h2 class="section-title">Product Images</h2>
            <div class="product-gallery">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $campaign->product_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="product-card">
                    <img src="<?php echo e(asset('storage/' . $image)); ?>" alt="<?php echo e($campaign->title); ?>">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <section class="trust-section">
            <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Secure Checkout</span>
            </div>
            <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Money Back Guarantee</span>
            </div>
            <div class="trust-item">
                <svg class="trust-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>24/7 Support</span>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="<?php echo e(route('legal.about')); ?>">About Us</a>
                <a href="<?php echo e(route('legal.contact')); ?>">Contact</a>
                <a href="<?php echo e(route('legal.privacy')); ?>">Privacy Policy</a>
            </div>
            <div class="footer-disclaimer">
                <p>This page contains affiliate links. We may earn a commission if you make a purchase through our links.</p>
                <p style="margin-top: 8px;">© <?php echo e(date('Y')); ?> All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH D:\CampAff\resources\views/landing/template4.blade.php ENDPATH**/ ?>