<?php

namespace App\Support;

class RasterWebpConverter
{
    public const DEFAULT_MAX_WIDTH = 960;

    public const DEFAULT_QUALITY = 82;

    public static function isSupported(): bool
    {
        return function_exists('imagewebp') && function_exists('imagecreatefromstring');
    }

    /**
     * Convert raster image bytes (JPEG, PNG, GIF, WebP) to WebP.
     */
    public static function convertBinary(
        string $binary,
        int $maxWidth = self::DEFAULT_MAX_WIDTH,
        int $quality = self::DEFAULT_QUALITY
    ): ?string {
        if ($binary === '' || ! self::isSupported()) {
            return null;
        }

        $info = @getimagesizefromstring($binary);
        if ($info === false) {
            return null;
        }

        $type = (int) ($info[2] ?? 0);
        if (! in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)) {
            return null;
        }

        $image = @imagecreatefromstring($binary);
        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= 0 || $height <= 0) {
            imagedestroy($image);

            return null;
        }

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            if ($resized === false) {
                imagedestroy($image);

                return null;
            }

            if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                if ($transparent !== false) {
                    imagefill($resized, 0, 0, $transparent);
                }
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        $ok = imagewebp($image, null, max(1, min(100, $quality)));
        $webp = ob_get_clean();
        imagedestroy($image);

        if (! $ok || ! is_string($webp) || $webp === '') {
            return null;
        }

        return $webp;
    }
}
