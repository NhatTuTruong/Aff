<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoFromDomainService
{
    public static function fetchAndSave(string $domain): ?string
    {
        $domain = self::cleanDomain($domain);
        if (empty($domain)) {
            return null;
        }

        $logoUrls = [
            "https://logo.clearbit.com/{$domain}",
            "https://www.google.com/s2/favicons?domain={$domain}&sz=128",
            "https://icons.duckduckgo.com/ip3/{$domain}.ico",
        ];

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

                $extension = image_type_to_extension($imageInfo[2], false) ?: 'png';
                $filename = Str::slug($domain) . '_' . time() . '.' . $extension;
                $path = 'brands/' . $filename;

                Storage::disk('public')->put($path, $imageContent);

                return $path;
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
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
