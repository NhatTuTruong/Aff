<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Campaign;
use App\Models\Coupon;
use App\Services\GeminiBlogService;

$service = app(GeminiBlogService::class);
$ref = new ReflectionClass($service);

$build = $ref->getMethod('buildStoreBlogCouponSectionHtml');
$build->setAccessible(true);
$inject = $ref->getMethod('injectStoreBlogCouponSection');
$inject->setAccessible(true);

$campaign = Campaign::query()->with('couponItems')->first();
if (! $campaign) {
    echo "SKIP | no campaign in DB\n";
    exit(0);
}

// Ensure at least one coupon with code for test
if ($campaign->couponItems->where(fn ($c) => filled($c->code))->isEmpty()) {
    Coupon::query()->create([
        'campaign_id' => $campaign->id,
        'code' => 'TESTCODE99',
        'offer' => '20% OFF',
        'description' => 'Test coupon for blog injection',
        'sort_order' => 9999,
    ]);
    $campaign->load('couponItems');
}

$section = $build->invoke($service, $campaign);
$okSection = str_contains($section, '<h2>Available Coupons</h2>')
    && str_contains($section, 'blog-coupon-code')
    && str_contains($section, '<li>');
echo ($okSection ? 'OK' : 'FAIL')." | buildStoreBlogCouponSectionHtml\n";

$aiHtml = '<h1>Brand Review</h1><p>Intro</p><h2>Pros and Cons</h2><p>Details</p>'
    .'<p><a href="https://example.com/out/x">Shop now at Brand</a></p>';
$result = $inject->invoke($service, $aiHtml, $section);
$okInject = str_contains($result, 'Available Coupons')
    && str_contains($result, 'blog-coupon-code')
    && strrpos($result, 'Shop now at Brand') > strrpos($result, 'Available Coupons');
echo ($okInject ? 'OK' : 'FAIL')." | injectStoreBlogCouponSection before closing CTA\n";

$dupHtml = $aiHtml.'<h2>Available Coupons</h2><ul><li><code>OLD</code></li></ul>';
$replaced = $inject->invoke($service, $dupHtml, $section);
$okReplace = substr_count($replaced, '<h2>Available Coupons</h2>') === 1
    && ! str_contains($replaced, 'OLD');
echo ($okReplace ? 'OK' : 'FAIL')." | replace duplicate AI coupon block\n";

// Apify insertImagesEvenly must keep headings/coupon blocks (not rebuild only <p>)
$apify = app(App\Services\BlogApifyImageService::class);
$apifyRef = new ReflectionClass($apify);
$insert = $apifyRef->getMethod('insertImagesEvenly');
$insert->setAccessible(true);
$withCoupons = $inject->invoke($service, $aiHtml, $section);
$withImages = $insert->invoke($apify, $withCoupons, ['https://example.com/img.jpg']);
$okApifyKeep = str_contains($withImages, 'Available Coupons')
    && str_contains($withImages, '<img')
    && str_contains($withImages, '<h1>Brand Review</h1>');
$restored = $service->ensureStoreBlogCouponSection($withImages, $campaign);
$okRestore = str_contains($restored, 'Available Coupons') && str_contains($restored, 'blog-coupon-code');
echo ($okApifyKeep ? 'OK' : 'FAIL')." | apify keeps coupon block + headings when inserting images\n";
echo ($okRestore ? 'OK' : 'FAIL')." | ensureStoreBlogCouponSection after apify\n";

$format = $ref->getMethod('formatStoreBlogCouponListItem');
$format->setAccessible(true);
$deal = new Coupon([
    'offer' => '$30 Off',
    'code' => '',
    'description' => '',
]);
$dealLine = $format->invoke($service, $deal, 'Konyks', 'https://example.com/out/test');
$okDeal = str_contains($dealLine, '$30 Off')
    && str_contains($dealLine, 'Save')
    && str_contains($dealLine, 'Konyks promo code');
echo ($okDeal ? 'OK' : 'FAIL')." | deal without code includes generated description\n";

$longHtml = '<h1>Title</h1>';
for ($i = 1; $i <= 8; $i++) {
    $longHtml .= "<p>Paragraph {$i} content here.</p>";
}
$longHtml .= '<p><a href="#">Shop now at Brand</a></p>';
$middleInject = $inject->invoke($service, $longHtml, $section);
$couponPos = strrpos($middleInject, 'Available Coupons');
$shopPos = strrpos($middleInject, 'Shop now at Brand');
$okMiddle = $couponPos !== false && $shopPos !== false && $couponPos < $shopPos;
echo ($okMiddle ? 'OK' : 'FAIL')." | long article injects coupons before closing CTA\n";

$prepare = $ref->getMethod('prepareStoreBlogHtml');
$prepare->setAccessible(true);
$mixedLinks = '<h1>Title</h1><p><a href="https://example.com/store/foo">Shop</a></p><p><a href="https://other.com">Other</a></p>';
$affUrl = route('click.redirect', ['slug' => $campaign->slug], true);
$prepared = $prepare->invoke($service, $mixedLinks, $campaign);
$okAffLinks = str_contains($prepared, 'href="'.htmlspecialchars($affUrl, ENT_QUOTES).'"')
    && ! str_contains($prepared, 'https://other.com')
    && ! str_contains($prepared, '/store/'.$campaign->slug);
echo ($okAffLinks ? 'OK' : 'FAIL')." | prepareStoreBlogHtml normalizes all anchor links\n";

$withInlineCode = '<h1>Title</h1><p>Intro paragraph one.</p><p>Intro two.</p>'
    .'<h2>Coupon Code</h2><p><strong>Code:</strong> OLDCODE</p>'
    .'<h2>Pros</h2><p>Body text here.</p>';
$preparedStore = $prepare->invoke($service, $withInlineCode, $campaign);
$okNoCouponCodeH2 = ! preg_match('/<h2[^>]*>\s*Coupon\s*Code/i', $preparedStore);
$okNoInlineCodeP = ! preg_match('/<strong>\s*Code:\s*<\/strong>\s*OLDCODE/i', $preparedStore);
$okOneAvailable = substr_count($preparedStore, '<h2>Available Coupons</h2>') === 1;
echo ($okNoCouponCodeH2 && $okNoInlineCodeP && $okOneAvailable ? 'OK' : 'FAIL')." | store blog strips Coupon Code, keeps one Available Coupons\n";

$splitUl = '<h1>T</h1><p>P1</p><p>P2</p><h2>Available Coupons</h2><p><img src="/x.webp"></p><ul><li><strong>12</strong> — Code: <button class="blog-coupon-code">FGH</button></li></ul>';
$twicePrepared = $prepare->invoke($service, $splitUl, $campaign);
$liCount = substr_count($twicePrepared, '<li>');
$okNoDupPrepare = substr_count($twicePrepared, '<h2>Available Coupons</h2>') === 1
    && $liCount === substr_count($section, '<li>');
echo ($okNoDupPrepare ? 'OK' : 'FAIL')." | double prepare does not duplicate coupon list (li={$liCount})\n";

exit(($okSection && $okInject && $okReplace && $okApifyKeep && $okRestore && $okDeal && $okMiddle && $okAffLinks && $okNoCouponCodeH2 && $okNoInlineCodeP && $okOneAvailable && $okNoDupPrepare) ? 0 : 1);
