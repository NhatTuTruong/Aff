<?php

namespace App\Support;

/**
 * Các toggle Auto Blog trong Cài đặt hệ thống (dùng chung cron + nút AI trong admin).
 */
class AutoBlogSettings
{
    /**
     * @return array<int, string> best|guide|comparison
     */
    public static function enabledCategoryVariants(): array
    {
        $variants = [];
        if ((bool) AdminSettings::get('auto_blog_variant_best', true)) {
            $variants[] = 'best';
        }
        if ((bool) AdminSettings::get('auto_blog_variant_guide', true)) {
            $variants[] = 'guide';
        }
        if ((bool) AdminSettings::get('auto_blog_variant_comparison', true)) {
            $variants[] = 'comparison';
        }

        return $variants;
    }

    public static function brandIntroEnabled(): bool
    {
        return (bool) AdminSettings::get('auto_blog_brand_intro_enabled', true);
    }

    public static function brandIntroIntervalHours(): float
    {
        $hours = (float) AdminSettings::get('auto_blog_brand_intro_interval_hours', 1);

        return $hours > 0 ? $hours : 1;
    }

    public static function globalIdea(): string
    {
        return trim((string) AdminSettings::get('auto_blog_global_idea', ''));
    }

    /**
     * Gộp ý tưởng chung (cài đặt) vào extras khi popup/modal chưa nhập idea.
     *
     * @param  array{idea?: string, affiliate_url?: string, coupon_code?: string}  $extras
     * @return array{idea?: string, affiliate_url?: string, coupon_code?: string}
     */
    public static function mergeExtras(array $extras = []): array
    {
        $idea = trim((string) ($extras['idea'] ?? ''));
        if ($idea === '') {
            $global = static::globalIdea();
            if ($global !== '') {
                $extras['idea'] = $global;
            }
        }

        return $extras;
    }

    /**
     * Các chế độ nút "Tạo bài bằng AI" / phân bổ nội dung: intro + các variant category đang bật.
     *
     * @return array<int, string>
     */
    public static function enabledManualAiModes(): array
    {
        $modes = [];
        if (static::brandIntroEnabled()) {
            $modes[] = 'intro';
        }
        foreach (static::enabledCategoryVariants() as $v) {
            $modes[] = $v;
        }

        return $modes;
    }
}
