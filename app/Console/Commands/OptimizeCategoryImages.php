<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeCategoryImages extends Command
{
    protected $signature = 'images:optimize-categories
                            {--max-width=960 : Resize width cap for raster images}
                            {--quality=82 : WebP quality (1-100)}
                            {--force : Rebuild WebP even when it already exists}';

    protected $description = 'Convert category PNG/JPEG images to compressed WebP for faster page loads';

    /** @var list<string> */
    private const DIRECTORIES = [
        'images/categories',
        'category',
    ];

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('PHP GD extension is required.');

            return self::FAILURE;
        }

        if (! function_exists('imagewebp')) {
            $this->error('PHP GD was built without WebP support.');

            return self::FAILURE;
        }

        $maxWidth = max(320, (int) $this->option('max-width'));
        $quality = min(100, max(1, (int) $this->option('quality')));
        $force = (bool) $this->option('force');

        $converted = 0;
        $skipped = 0;
        $failed = 0;
        $savedBytes = 0;

        foreach (self::DIRECTORIES as $relativeDir) {
            $dir = public_path($relativeDir);
            if (! is_dir($dir)) {
                continue;
            }

            foreach (scandir($dir) ?: [] as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (! preg_match('/\.(png|jpe?g)$/i', $file)) {
                    continue;
                }

                $source = $dir.DIRECTORY_SEPARATOR.$file;
                $webpName = preg_replace('/\.(png|jpe?g)$/i', '.webp', $file);
                $target = $dir.DIRECTORY_SEPARATOR.$webpName;

                if (! $force && file_exists($target) && filemtime($target) >= filemtime($source)) {
                    $skipped++;

                    continue;
                }

                $before = filesize($source) ?: 0;
                if ($this->convertToWebp($source, $target, $maxWidth, $quality)) {
                    $after = filesize($target) ?: 0;
                    $savedBytes += max(0, $before - $after);
                    $converted++;
                    $this->line(sprintf(
                        '  %s/%s → %s (%s → %s)',
                        $relativeDir,
                        $file,
                        $webpName,
                        $this->formatBytes($before),
                        $this->formatBytes($after),
                    ));
                } else {
                    $failed++;
                    $this->warn("  Failed: {$relativeDir}/{$file}");
                }
            }
        }

        $this->newLine();
        $this->info("Converted: {$converted}, skipped: {$skipped}, failed: {$failed}");
        $this->info('Estimated savings: '.$this->formatBytes($savedBytes));

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function convertToWebp(string $source, string $target, int $maxWidth, int $quality): bool
    {
        $image = $this->loadImage($source);
        if ($image === null) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= 0 || $height <= 0) {
            imagedestroy($image);

            return false;
        }

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            if ($resized === false) {
                imagedestroy($image);

                return false;
            }

            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            if ($transparent !== false) {
                imagefill($resized, 0, 0, $transparent);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $ok = imagewebp($image, $target, $quality);
        imagedestroy($image);

        return $ok && file_exists($target);
    }

    /**
     * @return \GdImage|resource|null
     */
    private function loadImage(string $path)
    {
        $info = @getimagesize($path);
        if ($info === false) {
            return null;
        }

        return match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path) ?: null,
            IMAGETYPE_PNG => @imagecreatefrompng($path) ?: null,
            default => null,
        };
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1).' KiB';
        }

        return round($bytes / (1024 * 1024), 2).' MiB';
    }
}
