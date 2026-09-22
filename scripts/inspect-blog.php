<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slug = $argv[1] ?? 'the-hotel-collection-elevating-your-home-with-luxury-fragrances';
$blog = App\Models\Blog::query()->where('slug', $slug)->first();

if (! $blog) {
    echo "NOT FOUND: {$slug}\n";
    exit(1);
}

echo "id={$blog->id}\n";
echo "title={$blog->title}\n";
echo "intro_type=".($blog->intro_type ?? 'null')."\n";
echo "campaign_id=".($blog->campaign_id ?? 'null')."\n";
echo "category=".($blog->category ?? 'null')."\n";
echo 'content_len='.strlen((string) $blog->content)."\n";
echo 'rendered_len='.strlen($blog->rendered_content)."\n";
echo "has blog-coupon-code=". (str_contains($blog->rendered_content, 'blog-coupon-code') ? 'yes' : 'no')."\n";
echo "has Available Coupons=". (str_contains($blog->rendered_content, 'Available Coupons') ? 'yes' : 'no')."\n";
echo "--- rendered (image order) ---\n";
$rendered = $blog->rendered_content;
if (preg_match_all('/<p\b[^>]*>.*?<\/p>/is', $rendered, $pm)) {
    foreach ($pm[0] as $i => $block) {
        $kind = str_contains($block, '<img') ? 'IMG' : 'TXT';
        $preview = Str::limit(trim(strip_tags($block)), 55);
        echo "  p#{$i} {$kind}: {$preview}\n";
    }
} else {
    echo "(no paragraphs)\n";
}
