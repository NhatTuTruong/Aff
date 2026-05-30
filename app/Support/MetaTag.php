<?php

namespace App\Support;

class MetaTag
{
    /**
     * Decode HTML entities once so Blade {{ }} escapes cleanly for title/OG tags.
     */
    public static function plain(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
