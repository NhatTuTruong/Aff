<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$html = app(App\Http\Controllers\HomeController::class)->index(request())->render();
$issues = [];

if (preg_match('/window\.addEventListener\(\s*[\'"]scroll[\'"]/', $html)) {
    $issues[] = 'Inline scroll listener still present in rendered HTML';
}

if (preg_match('/function updateHeaderCompact/', $html)) {
    $issues[] = 'updateHeaderCompact still present (scrollY reflow risk)';
}

if (preg_match('/function updateVisibility/', $html)) {
    $issues[] = 'updateVisibility still present (scrollY reflow risk)';
}

if (! str_contains($html, 'js/magazine-site.js')) {
    $issues[] = 'magazine-site.js not referenced';
}

if (! str_contains($html, 'js/home-carousel.js')) {
    $issues[] = 'home-carousel.js not referenced';
}

if (str_contains(file_get_contents(public_path('js/magazine-site.js')), 'window.scrollY')) {
    $issues[] = 'magazine-site.js still reads window.scrollY';
}

if (str_contains(file_get_contents(public_path('js/magazine-site.js')), 'addEventListener(\'scroll\'')) {
    $issues[] = 'magazine-site.js still uses scroll listener';
}

if ($issues === []) {
    echo "PASS: no scroll-driven reflow patterns detected\n";
    exit(0);
}

echo "FAIL:\n- ".implode("\n- ", $issues)."\n";
exit(1);
