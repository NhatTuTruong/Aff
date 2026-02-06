<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($campaign->title); ?> - Coupons & Deals</title>
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
        body {
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
            background: #f3f4f6;
            color: #111827;
            font-size: 14px;
        }
        a { text-decoration: none; color: inherit; }
        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 16px 40px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
        }

        /* Left sidebar – brand card */
        .brand-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(15,23,42,0.1);
            padding: 20px 18px 18px;
        }
        .brand-logo {
            max-width: 180px;
            height: auto;
            object-fit: contain;
            display: block;
            margin-bottom: 12px;
        }
        .brand-domain {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #2563eb;
            margin-bottom: 8px;
        }
        .brand-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .stars {
            color: #facc15;
            font-size: 13px;
        }
        .brand-meta {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .brand-follow {
            margin: 14px 0;
        }
        .btn-follow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            font-size: 12px;
            cursor: pointer;
        }
        .brand-links {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .brand-links-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .brand-links a {
            font-size: 12px;
            color: #2563eb;
        }

        .brand-nav {
            margin-top: 18px;
            border-top: 1px solid #e5e7eb;
        }
        .brand-nav-item {
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }
        .brand-nav-item.active {
            color: #2563eb;
            font-weight: 600;
        }

        .brand-review {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #4b5563;
        }
        .brand-review h2 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .brand-review p {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        /* Right – header + coupon list */
        .content-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .page-heading {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .page-subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        .coupon-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .coupon-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: stretch;
            background: #ffffff;
            border-radius: 8px;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(15,23,42,0.08);
            border-left: 4px solid #2563eb;
        }
        .coupon-main {
            display: flex;
            gap: 12px;
        }
        .coupon-logo {
            width: 60px;
            flex-shrink: 0;
        }
        .coupon-logo img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        .coupon-info {
            flex: 1;
        }
        .coupon-type {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .coupon-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .coupon-meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .coupon-badges {
            margin-top: 6px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .badge {
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 11px;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }
        .badge-verified {
            border-color: #16a34a;
            color: #16a34a;
        }
        .badge-code {
            border-color: #0ea5e9;
            color: #0ea5e9;
        }
        .badge-deal {
            border-color: #f97316;
            color: #f97316;
        }

        .coupon-cta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            gap: 6px;
        }
        .btn-main {
            min-width: 150px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            background: #2563eb;
        }
        .btn-main.deal {
            background: #059669;
        }
        .coupon-code-chip {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px dashed #9ca3af;
            color: #374151;
            background: #f9fafb;
        }

        @media (max-width: 960px) {
            .page {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- LEFT SIDEBAR -->
        <aside class="brand-card">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
                <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>" alt="<?php echo e($campaign->brand->name); ?>" class="brand-logo">
            <?php elseif($campaign->logo): ?>
                <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>" alt="<?php echo e($campaign->title); ?>" class="brand-logo">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="<?php echo e(route('click.redirect', $campaign->slug)); ?>" class="brand-domain">
                <?php echo e($campaign->brand->name ?? $campaign->title); ?> coupons
            </a>

            <div class="brand-rating">
                <span class="stars">★★★★☆</span>
                <span>3.9 – 63 ratings</span>
            </div>

            <div class="brand-meta">
                <?php echo e($campaign->subtitle ?? 'Best coupons & promo codes to save on your next order.'); ?>

            </div>

            <div class="brand-follow">
                <button type="button" class="btn-follow">+ Follow</button>
            </div>

            <div class="brand-links">
                <div class="brand-links-title">More ways to save</div>
                <div><a href="#coupons">All Coupons & Deals</a></div>
            </div>

            <div class="brand-nav">
                <div class="brand-nav-item active">Promo Codes & Deals</div>
                <div class="brand-nav-item">Store Info & Tips</div>
            </div>

            <div class="brand-review">
                <h2>How to save with <?php echo e($campaign->brand->name ?? 'this store'); ?></h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->intro): ?>
                    <p><?php echo e($campaign->intro); ?></p>
                <?php else: ?>
                    <p>Use the latest coupon codes and exclusive deals above to save more when you shop. Click a code to reveal it and apply the discount at checkout.</p>
                    <p>Check back regularly – we update this page whenever new offers are verified.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </aside>

        <!-- RIGHT CONTENT -->
        <main class="content-right">
            <header>
                <h1 class="page-heading">
                    <?php echo e($campaign->title); ?>

                </h1>
                <p class="page-subtitle">
                    Latest verified coupons & exclusive deals for <?php echo e($campaign->brand->name ?? 'this store'); ?>.
                </p>
            </header>

            <section id="coupons" class="coupon-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->coupons && count($campaign->coupons) > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $campaign->coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $code = is_array($coupon) ? ($coupon['code'] ?? null) : $coupon;
                            $offer = is_array($coupon) ? ($coupon['offer'] ?? null) : null;
                            $description = is_array($coupon)
                                ? ($coupon['description'] ?? 'Save more on your next order.')
                                : 'Save more on your next order.';

                            $isDeal = empty($code);
                            $typeLabel = $isDeal ? 'Deal' : 'Code';
                            $ctaText = $isDeal ? 'Get This Deal' : 'Get This Code';
                            $discountText = $offer ? ($offer . ' OFF') : null;
                        ?>
                        <article class="coupon-row">
                            <div class="coupon-main">
                                <div class="coupon-logo">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>" alt="<?php echo e($campaign->brand->name); ?>">
                                    <?php elseif($campaign->logo): ?>
                                        <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>" alt="<?php echo e($campaign->title); ?>">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="coupon-info">
                                    <div class="coupon-type"><?php echo e($typeLabel); ?></div>
                                    <div class="coupon-title">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($discountText): ?>
                                            <?php echo e($discountText); ?> <?php echo e($campaign->brand->name ?? ''); ?>

                                        <?php else: ?>
                                            <?php echo e($campaign->brand->name ?? $campaign->title); ?> coupon
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="coupon-description">
                                        <?php echo e($description); ?>

                                    </div>
                                    <div class="coupon-badges">
                                        <span class="badge badge-verified">Verified</span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDeal): ?>
                                            <span class="badge badge-deal">Deal</span>
                                        <?php else: ?>
                                            <span class="badge badge-code">Code</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="coupon-cta">
                                <a href="<?php echo e(route('click.redirect', $campaign->slug)); ?>">
                                    <button type="button" class="btn-main <?php echo e($isDeal ? 'deal' : ''); ?>">
                                        <?php echo e($ctaText); ?>

                                    </button>
                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($code): ?>
                                    <div class="coupon-code-chip"><?php echo e($code); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($campaign->coupon_code): ?>
                    <article class="coupon-row">
                        <div class="coupon-main">
                            <div class="coupon-logo">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->brand && $campaign->brand->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $campaign->brand->image)); ?>" alt="<?php echo e($campaign->brand->name); ?>">
                                <?php elseif($campaign->logo): ?>
                                    <img src="<?php echo e(asset('storage/' . $campaign->logo)); ?>" alt="<?php echo e($campaign->title); ?>">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="coupon-info">
                                <div class="coupon-type">Code</div>
                                <div class="coupon-title"><?php echo e($campaign->title); ?> coupon</div>
                                <div class="coupon-description">
                                    Save on your next order with this exclusive discount code.
                                </div>
                                <div class="coupon-badges">
                                    <span class="badge badge-verified">Verified</span>
                                    <span class="badge badge-code">Code</span>
                                </div>
                            </div>
                        </div>
                        <div class="coupon-cta">
                            <a href="<?php echo e(route('click.redirect', $campaign->slug)); ?>">
                                <button type="button" class="btn-main">Get This Code</button>
                            </a>
                            <div class="coupon-code-chip"><?php echo e($campaign->coupon_code); ?></div>
                        </div>
                    </article>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->intro): ?>
                <section>
                    <h2 class="page-heading" style="font-size:18px;margin-top:16px;">How to save with <?php echo e($campaign->brand->name ?? 'this store'); ?></h2>
                    <p class="page-subtitle"><?php echo e($campaign->intro); ?></p>
                </section>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </main>
    </div>
</body>
</html>


<?php /**PATH D:\CampAff\resources\views/landing/template7.blade.php ENDPATH**/ ?>