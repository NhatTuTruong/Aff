<?php

namespace App\Support;

/**
 * Chuẩn hóa text SEO trước khi escape HTML (tránh hiển thị &amp; trên tab trình duyệt).
 */
final class SeoText
{
    public static function plain(?string $value): string
    {
        $text = trim((string) $value);
        if ($text === '') {
            return '';
        }

        $prev = null;
        while ($prev !== $text) {
            $prev = $text;
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $text;
    }
}
