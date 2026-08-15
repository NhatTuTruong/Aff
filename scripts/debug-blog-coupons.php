<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slug = $argv[1] ?? 'gasbye-redefining-quality-home-accessories-for-the-modern-home';

$blog = App\Models\Blog::where('slug', $slug)->first();
if (! $blog) {
    echo "Blog not found: {$slug}\n";
    exit(1);
}

echo "id={$blog->id}\n";
echo "intro_type=".($blog->intro_type ?? 'null')."\n";
echo "campaign_id=".($blog->campaign_id ?? 'null')."\n";
echo "category=".($blog->category ?? 'null')."\n";
echo "has Available Coupons: ".(str_contains((string) $blog->content, 'Available Coupons') ? 'yes' : 'no')."\n";
echo "has <code>: ".(str_contains((string) $blog->content, '<code>') ? 'yes' : 'no')."\n";

if ($blog->campaign_id) {
    $campaign = App\Models\Campaign::with('couponItems')->find($blog->campaign_id);
    if ($campaign) {
        echo "campaign: {$campaign->title} (#{$campaign->id})\n";
        echo "coupon count: ".$campaign->couponItems->count()."\n";
        foreach ($campaign->couponItems->take(5) as $c) {
            echo "  - offer=".($c->offer ?? '')." code=".($c->code ?? '')."\n";
        }
    }
}

echo "\n--- content tail ---\n";
echo substr((string) $blog->content, -1500)."\n";
