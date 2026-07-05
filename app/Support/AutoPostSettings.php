<?php

namespace App\Support;

class AutoPostSettings
{
    public const PLATFORM_OPTIONS = [
        'blog' => 'Blog',
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
    ];

    public const TYPE_OPTIONS = [
        'video' => 'Video',
        'image' => 'Image',
        'text' => 'Text',
    ];

    public static function apiUrl(): string
    {
        return trim((string) AdminSettings::get(
            'auto_post_api_url',
            'http://localhost:8000/api/coupons/sync'
        ));
    }

    public static function bearerToken(): ?string
    {
        return AdminSettings::getEncrypted('auto_post_bearer_token');
    }

    /** @return list<string> */
    public static function platforms(): array
    {
        $platforms = AdminSettings::get('auto_post_platforms', ['blog', 'instagram', 'facebook']);

        if (! is_array($platforms)) {
            return ['blog', 'instagram', 'facebook'];
        }

        return array_values(array_filter(
            $platforms,
            fn ($p) => is_string($p) && array_key_exists($p, self::PLATFORM_OPTIONS)
        ));
    }

    public static function type(): string
    {
        $type = (string) AdminSettings::get('auto_post_type', 'video');

        return array_key_exists($type, self::TYPE_OPTIONS) ? $type : 'video';
    }

    public static function allowRepeat(): bool
    {
        return (bool) AdminSettings::get('auto_post_allow_repeat', false);
    }

    public static function isConfigured(): bool
    {
        return self::apiUrl() !== '' && self::bearerToken() !== null && self::bearerToken() !== '';
    }
}
