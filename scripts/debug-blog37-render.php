<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$blog = App\Models\Blog::find(37);
if (! $blog) {
    echo "Blog 37 not found\n";
    exit(1);
}

$html = $blog->rendered_content;
echo 'rendered has Available Coupons: '.(str_contains($html, 'Available Coupons') ? 'yes' : 'no')."\n";
echo 'rendered has JACOBWILSON: '.(str_contains($html, 'JACOBWILSON') ? 'yes' : 'no')."\n";
