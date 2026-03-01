<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoFromDomainService
{
    /** Kích thước tối thiểu (px) cho cạnh dài nhất khi upscale logo nhỏ. */
    public const MIN_LOGO_SIZE = 256;

    /**
     * Lưu logo vào users/{user_code}/brands/ để hiển thị trong File Manager của user.
     * Ưu tiên nguồn độ phân giải cao, và upscale ảnh nhỏ lên tối thiểu MIN_LOGO_SIZE px.
     *
     * @param  string  $domain  Domain để lấy logo (vd: example.com)
     * @param  string|null  $userCode  Mã user (5 chữ số). Null thì lấy từ Auth.
     */
    public static function fetchAndSave(string $domain, ?string $userCode = null): ?string
    {
        $domain = self::cleanDomain($domain);
        if (empty($domain)) {
            return null;
        }

        $userCode = $userCode ?? (Auth::check() ? (Auth::user()->code ?? '00000') : '00000');

        // Ưu tiên nguồn có độ phân giải cao trước
        $logoUrls = [
            "https://logo.clearbit.com/{$domain}?size=256",
            "https://www.google.com/s2/favicons?domain={$domain}&sz=256",
            "https://logo.clearbit.com/{$domain}",
            "https://www.google.com/s2/favicons?domain={$domain}&sz=128",
            "https://icons.duckduckgo.com/ip3/{$domain}.ico",
        ];

        $directory = "users/{$userCode}/brands";

        foreach ($logoUrls as $logoUrl) {
            try {
                $response = Http::timeout(5)
                    ->withOptions(['verify' => false, 'http_errors' => false])
                    ->get($logoUrl);

                if (! $response->successful()) {
                    continue;
                }

                $imageContent = $response->body();
                if (! is_string($imageContent) || empty($imageContent)) {
                    continue;
                }

                $imageInfo = @getimagesizefromstring($imageContent);
                if ($imageInfo === false || ! isset($imageInfo[2])) {
                    continue;
                }

                $width = (int) ($imageInfo[0] ?? 0);
                $height = (int) ($imageInfo[1] ?? 0);
                if ($width < 1 || $height < 1) {
                    continue;
                }

                // Upscale ảnh quá nhỏ để lưu ở độ phân giải tối thiểu
                $maxSide = max($width, $height);
                if ($maxSide < self::MIN_LOGO_SIZE) {
                    $upscaled = self::upscaleToMinSize($imageContent, $imageInfo);
                    if ($upscaled !== null) {
                        $imageContent = $upscaled;
                        $extension = 'png';
                    } else {
                        $extension = image_type_to_extension($imageInfo[2], false) ?: 'png';
                    }
                } else {
                    $extension = image_type_to_extension($imageInfo[2], false) ?: 'png';
                }

                $filename = Str::slug($domain) . '_' . time() . '.' . $extension;
                $path = $directory . '/' . $filename;

                if (! Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory, 0755, true);
                }
                Storage::disk('public')->put($path, $imageContent);

                return $path;
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }

    /**
     * Tăng kích thước ảnh nhỏ lên tối thiểu MIN_LOGO_SIZE (cạnh dài nhất), giữ tỉ lệ, xuất PNG.
     */
    public static function upscaleToMinSize(string $imageBinary, array $imageInfo): ?string
    {
        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        $type = (int) ($imageInfo[2] ?? 0);

        if ($width < 1 || $height < 1) {
            return null;
        }

        $src = @imagecreatefromstring($imageBinary);
        if ($src === false) {
            return null;
        }

        $maxSide = max($width, $height);
        $scale = self::MIN_LOGO_SIZE / $maxSide;
        $newW = (int) round($width * $scale);
        $newH = (int) round($height * $scale);
        if ($newW < 1) {
            $newW = 1;
        }
        if ($newH < 1) {
            $newH = 1;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            imagedestroy($src);
            return null;
        }

        // Giữ trong suốt cho PNG/GIF
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $transparent);

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($src, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
        imagedestroy($src);

        ob_start();
        imagepng($dst, null, 9);
        $png = ob_get_clean();
        imagedestroy($dst);

        return $png !== false ? $png : null;
    }

    public static function cleanDomain(?string $domain): ?string
    {
        if (empty($domain)) {
            return null;
        }
        $domain = preg_replace('/^https?:\/\//', '', (string) $domain);
        $domain = preg_replace('/^www\./', '', $domain);
        $domain = explode('/', $domain)[0];

        return trim($domain);
    }
}
