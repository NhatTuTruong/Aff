<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$brands = App\Models\Brand::query()
    ->where('name', 'like', '%Hotel%Collection%')
    ->orWhere('domain', 'like', '%hotelcollection%')
    ->get(['id', 'name', 'domain']);

foreach ($brands as $b) {
    echo "brand id={$b->id} name={$b->name} domain={$b->domain}\n";
    $camps = App\Models\Campaign::query()->where('brand_id', $b->id)->get(['id', 'slug', 'title']);
    foreach ($camps as $c) {
        echo "  campaign id={$c->id} slug={$c->slug} title={$c->title}\n";
    }
}
