<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(App\Services\GeminiBlogService::class);
$aff = 'https://example.com/out/track';

$html = '<h1>Title</h1><p>Intro about Brand Name.</p>'
    .'<p><a href="https://merchant.com">Brand Name</a> offers deals.</p>'
    .'<h2>Section</h2><p>More text here.</p>'
    .'<p><strong>Code:</strong> SA45</p>'
    .'<p><a href="https://other.com">Other</a></p>';

$out = $service->prepareAffiliateBlogHtml($html, $aff);

$linkCount = preg_match_all('/<a\b[^>]*href=/i', $out);
$ctaCount = substr_count($out, 'blog-aff-cta');
$okLinks = $linkCount === 2 && $ctaCount === 2;
$okNoBrandLink = ! preg_match('/<a\b[^>]*>\s*Brand Name\s*<\/a>/i', $out);
$okCoupon = str_contains($out, 'blog-coupon-code') && str_contains($out, 'data-code="SA45"');
$okNoMerchant = ! str_contains($out, 'merchant.com') && ! str_contains($out, 'other.com');

echo ($okLinks ? 'OK' : 'FAIL')." | exactly 2 affiliate CTAs (links={$linkCount}, cta={$ctaCount})\n";
echo ($okNoBrandLink ? 'OK' : 'FAIL')." | brand name not linked\n";
echo ($okCoupon ? 'OK' : 'FAIL')." | coupon button in body\n";
echo ($okNoMerchant ? 'OK' : 'FAIL')." | non-aff links removed\n";

// Images evenly
$apify = app(App\Services\BlogApifyImageService::class);
$long = '<h1>T</h1><p>P1</p><h2>A</h2><p>P2</p><p>P3</p><h2>B</h2><p>P4</p><p>P5</p><p>P6</p>';
$withImg = $apify->insertImagesEvenly($long, [
    'https://cdn.test/a.webp',
    'https://cdn.test/b.webp',
]);
$okKeepH2 = str_contains($withImg, '<h2>A</h2>') && str_contains($withImg, '<h2>B</h2>');
$imgPositions = [];
if (preg_match_all('/<p\b[^>]*>.*?<\/p>/is', $withImg, $pm)) {
    foreach ($pm[0] as $i => $block) {
        if (str_contains($block, '<img')) {
            $imgPositions[] = $i;
        }
    }
}
$okSpread = count($imgPositions) === 2 && $imgPositions[0] !== $imgPositions[1];
echo ($okKeepH2 ? 'OK' : 'FAIL')." | image insert keeps headings\n";
echo ($okSpread ? 'OK' : 'FAIL')." | images spread (pos=".implode(',', $imgPositions).")\n";

exit(($okLinks && $okNoBrandLink && $okCoupon && $okNoMerchant && $okKeepH2 && $okSpread) ? 0 : 1);
