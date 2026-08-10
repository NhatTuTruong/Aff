<?php

/**
 * Convert images in public/category and public/images/categories to WebP.
 * Usage: php scripts/convert-category-images-to-webp.php
 */

$base = dirname(__DIR__);
$dirs = [
    $base . '/public/category',
    $base . '/public/images/categories',
];

$converted = 0;
$skipped = 0;
$failed = 0;

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        continue;
    }

    foreach (glob($dir . '/*') ?: [] as $file) {
        if (! is_file($file)) {
            continue;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($ext === 'webp') {
            $skipped++;

            continue;
        }

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
            $skipped++;
            echo "SKIP (unsupported ext .{$ext}): {$file}\n";

            continue;
        }

        $dest = preg_replace('/\.[^.]+$/', '.webp', $file);
        $info = @getimagesize($file);
        if (! $info) {
            $failed++;
            echo "FAIL getimagesize: {$file}\n";

            continue;
        }

        $img = match ($info['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($file),
            'image/png' => @imagecreatefrompng($file),
            'image/gif' => @imagecreatefromgif($file),
            default => null,
        };

        if (! $img) {
            $failed++;
            echo "FAIL load ({$info['mime']}): {$file}\n";

            continue;
        }

        if ($info['mime'] === 'image/png') {
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
        }

        if (! imagewebp($img, $dest, 85)) {
            imagedestroy($img);
            $failed++;
            echo "FAIL write: {$dest}\n";

            continue;
        }

        imagedestroy($img);
        unlink($file);
        $converted++;
        echo "OK: {$file} -> {$dest}\n";
    }
}

echo "Done. converted={$converted} skipped={$skipped} failed={$failed}\n";
