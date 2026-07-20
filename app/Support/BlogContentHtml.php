<?php

namespace App\Support;

class BlogContentHtml
{
    /**
     * Remove file name / size captions that Filament RichEditor adds under pasted or uploaded images.
     */
    public static function stripAttachmentCaptions(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return (string) $html;
        }

        $html = preg_replace('/<figcaption\b[^>]*>.*?<\/figcaption>/is', '', $html) ?? $html;
        $html = preg_replace('/<span\b[^>]*class="[^"]*attachment__caption[^"]*"[^>]*>.*?<\/span>/is', '', $html) ?? $html;
        $html = preg_replace('/<span\b[^>]*class="[^"]*attachment__name[^"]*"[^>]*>.*?<\/span>/is', '', $html) ?? $html;
        $html = preg_replace('/<span\b[^>]*class="[^"]*attachment__size[^"]*"[^>]*>.*?<\/span>/is', '', $html) ?? $html;

        $html = preg_replace(
            '/<a\b[^>]*>\s*[^<]*?\.(?:png|jpe?g|gif|webp|svg|bmp|avif|heic|heif)(?:\s+[\d.]+\s*(?:bytes|KB|MB|GB|KiB|MiB|GiB))?\s*<\/a>/iu',
            '',
            $html
        ) ?? $html;

        $html = preg_replace(
            '/<div\b[^>]*data-type="file"[^>]*>\s*<a\b[^>]*>.*?<\/a>\s*<\/div>/is',
            '',
            $html
        ) ?? $html;

        $html = preg_replace('/<figure\b[^>]*>\s*<\/figure>/is', '', $html) ?? $html;
        $html = preg_replace('/<p>\s*<\/p>/i', '', $html) ?? $html;

        return trim($html);
    }
}
