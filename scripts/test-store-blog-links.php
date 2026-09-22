<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(App\Services\GeminiBlogService::class);

$affiliate = 'https://reviewvera.com/out/demo-store';
$html = '<h1>Store</h1><p>Intro</p>'
    .'<p><a href="https://reviewvera.com/store/demo-store">Brand</a></p>'
    .'<p>Body</p>';

$result = $service->normalizeAffiliateLinks($html, $affiliate, 'demo-store');

$linkCount = preg_match_all('/<a\b[^>]*href=/i', $result);
$ok = $linkCount === 2
    && substr_count($result, 'blog-aff-cta') === 2
    && ! str_contains($result, '/store/demo-store')
    && str_contains($result, '/out/demo-store')
    && ! preg_match('/<a\b[^>]*>\s*Brand\s*<\/a>/i', $result);

echo $ok ? "PASS\n" : "FAIL\n{$result}\n";
exit($ok ? 0 : 1);
