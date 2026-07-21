<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(App\Services\GeminiBlogService::class);
$method = new ReflectionMethod($service, 'normalizeStoreBlogAffiliateLinks');
$method->setAccessible(true);

$affiliate = 'https://reviewvera.com/out/demo-store';
$html = '<p><a href="https://reviewvera.com/visit/demo-store">Coupons</a></p>'
    .'<p><a href="/visit/demo-store">More</a></p>'
    .'<p><a href="/store/demo-store">Legacy</a></p>';

$result = $method->invoke($service, $html, 'demo-store', $affiliate);

$ok = ! str_contains($result, '/visit/demo-store') && ! str_contains($result, '/store/demo-store') && str_contains($result, '/out/demo-store');
echo $ok ? "PASS\n" : "FAIL\n{$result}\n";
exit($ok ? 0 : 1);
