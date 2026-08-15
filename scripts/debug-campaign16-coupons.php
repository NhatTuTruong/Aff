<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(App\Services\GeminiBlogService::class);
$ref = new ReflectionClass($service);
$build = $ref->getMethod('buildStoreBlogCouponSectionHtml');
$build->setAccessible(true);
$inject = $ref->getMethod('injectStoreBlogCouponSection');
$inject->setAccessible(true);

$campaign = App\Models\Campaign::with('couponItems')->find(16);
$section = $build->invoke($service, $campaign);
echo "SECTION:\n{$section}\n\n";

$blog = App\Models\Blog::find(37);
$fixed = $inject->invoke($service, (string) $blog->content, $section);
echo "INJECTED HAS COUPONS: ".(str_contains($fixed, 'Available Coupons') ? 'yes' : 'no')."\n";
echo "INJECTED HAS CODE: ".(str_contains($fixed, 'JACOBWILSON') ? 'yes' : 'no')."\n";
