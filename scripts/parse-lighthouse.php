<?php

$path = __DIR__.'/../storage/app/lighthouse-mobile.json';
if (! file_exists($path)) {
    fwrite(STDERR, "Missing lighthouse report\n");
    exit(1);
}

$data = json_decode(file_get_contents($path), true);
$score = round(($data['categories']['performance']['score'] ?? 0) * 100);
echo "Performance score (mobile): {$score}\n\n";

foreach ($data['audits'] ?? [] as $id => $audit) {
    $title = (string) ($audit['title'] ?? '');
    if (stripos($id, 'forced') === false && stripos($title, 'reflow') === false) {
        continue;
    }

    echo "{$id}: {$title}\n";
    echo '  display: '.($audit['displayValue'] ?? 'n/a')."\n";

    foreach ($audit['details']['items'] ?? [] as $item) {
        $source = $item['source'] ?? $item['url'] ?? 'unknown';
        $reflow = $item['reflowTime'] ?? null;
        echo "  - {$source}";
        if ($reflow !== null) {
            echo " ({$reflow} ms)";
        }
        echo "\n";
    }

    echo "\n";
}
