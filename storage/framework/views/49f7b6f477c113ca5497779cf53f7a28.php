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
            --primary: #3b82f6; --primary-dark: #2563eb; --secondary: #60a5fa;
            --accent: #8b5cf6; --text-dark: #1f2937; --text-light: #6b7280;
            --bg-light: #f9fafb; --bg-white: #ffffff; --border: #e5e7eb;
            --shadow: 0 2px 4px rgba(0,0,0,0.1); --shadow-lg: 0 4px 12px rgba(0,0,0,0.15);
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.875rem; line-height: 1.5; color: var(--text-dark); background: var(--bg-white);
        }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .top-section {
            display: grid; grid-template-columns: 1fr 330px; gap: 18px; margin-bottom: 40px;
        }
        .banner-left {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border-radius: 12px;
            padding: 40px; color: white; position: relative; overflow: hidden;
        }
        .banner-left::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg width="40" height="40" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="1.5" fill="rgba(255,255,255,0.2)"/></svg>');
        }
        .banner-content { position: relative; z-index: 1; }
        .brand-logo { max-width: 140px; height: auto; margin-bottom: 16px; object-fit: contain; }
        .brand-name { font-size: 2rem; font-weight: 800; margin-bottom: 12px; }
        .banner-subtitle { font-size: 0.95rem; opacity: 0.95; margin-bottom: 20px; }
        .banner-image { width: 100%; height: auto; border-radius: 10px; margin-top: 20px; box-shadow: 0 8px 28px rgba(0,0,0,0.25); }
        .coupons-right {
            display: flex; flex-direction: column; gap: 6px;
        }
        .coupon-card {
            background: white; border-radius: 10px; padding: 16px; box-shadow: 0 3px 18px rgba(59,130,246,0.15);
            border-top: 4px solid var(--primary); text-align: center; transition: all 0.3s;
        }
        .coupon-card:hover { transform: translateY(-3px); box-shadow: 0 6px 26px rgba(59,130,246,0.25); }
        .coupon-badge {
            display: inline-block; background: var(--primary); color: white; padding: 3px 10px;
            border-radius: 3px; font-size: 0.65rem; font-weight: 700; margin-bottom: 10px;
            text-transform: uppercase;
        }
        .coupon-discount {
            font-size: 1.875rem; font-weight: 800; color: var(--primary); margin: 10px 0;
        }
        .coupon-description { font-size: 0.8rem; color: var(--text-dark); margin-bottom: 6px; }
        .coupon-disclaimer { font-size: 0.7rem; color: var(--text-light); margin-bottom: 14px; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 6px; padding: 10px 24px;
            background: var(--primary); color: white; text-decoration: none; border-radius: 6px;
            font-size: 0.8rem; font-weight: 700; transition: all 0.3s; width: 100%;
            justify-content: center;
        }
        .btn-cta:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .content-section {
            padding: 40px 30px; background: var(--bg-light); border-radius: 12px; margin-bottom: 40px;
        }
        .section-title {
            font-size: 1.75rem; font-weight: 700; margin-bottom: 24px; text-align: center;
            color: var(--primary-dark);
        }
        .intro-text {
            font-size: 0.875rem; color: var(--text-light); line-height: 1.7; text-align: center;
            max-width: 800px; margin: 0 auto 28px;
        }
        .product-gallery {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;
            margin-top: 28px;
        }
        .product-card {
            background: white; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow);
            transition: transform 0.3s; border: 2px solid transparent;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
        .product-card img { width: 100%; height: auto; display: block; }
        .trust-section {
            display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; padding: 40px 20px;
            background: white; border-radius: 12px; box-shadow: var(--shadow);
        }
        .trust-item {
            display: flex; align-items: center; gap: 8px; color: var(--text-light); font-size: 0.8rem;
        }
        .trust-icon { width: 20px; height: 20px; color: var(--primary); }
        footer {
            background: var(--text-dark); color: white; padding: 35px 20px; text-align: center;
            margin-top: 40px;
        }
        .footer-links {
            display: flex; justify-content: center; gap: 24px; margin-bottom: 16px; flex-wrap: wrap;
        }
        .footer-links a { color: white; text-decoration: none; font-size: 0.8rem; }
        .footer-disclaimer { font-size: 0.75rem; opacity: 0.7; }
        @media (max-width: 1024px) {
            .top-section { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .product-gallery { grid-template-columns: 1fr; }
            .trust-section { gap: 20px; }
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

<?php /**PATH D:\CampAff\resources\views/landing/template6.blade.php ENDPATH**/ ?>