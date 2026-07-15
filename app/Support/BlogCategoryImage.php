<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogCategoryImage
{
    public const CATEGORY_DIR = 'images/categories';

    /** @var list<string> */
    private const EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp', 'gif'];

    /** Lower value = higher priority when multiple formats exist for the same basename. */
    private const FORMAT_PRIORITY = [
        'webp' => 0,
        'jpg' => 1,
        'jpeg' => 1,
        'png' => 2,
        'gif' => 3,
        'svg' => 4,
    ];

    public static function slugFromCategory(?string $category): string
    {
        $category = trim((string) $category);

        return $category !== '' ? Str::slug($category) : 'default';
    }

    /**
     * @return list<string> Public-relative paths, e.g. images/categories/accessories1.webp
     */
    public static function matchingPathsForCategory(?string $category): array
    {
        $slug = self::slugFromCategory($category);
        if ($slug === '' || $slug === 'default') {
            return [];
        }

        return self::matchingPathsForSlug($slug);
    }

    /**
     * @return list<string>
     */
    public static function matchingPathsForSlug(string $slug): array
    {
        $dir = public_path(self::CATEGORY_DIR);
        if (! is_dir($dir)) {
            return [];
        }

        $extPattern = implode('|', self::EXTENSIONS);
        $pattern = '/^' . preg_quote($slug, '/') . '(\d*)\.(' . $extPattern . ')$/i';

        $matches = [];
        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (preg_match($pattern, $file)) {
                $matches[] = self::CATEGORY_DIR . '/' . $file;
            }
        }

        return self::preferOptimizedFormats($matches);
    }

    /**
     * @param  list<string>  $paths
     * @return list<string>
     */
    public static function preferOptimizedFormats(array $paths): array
    {
        $bestByBase = [];

        foreach ($paths as $path) {
            $base = preg_replace('/\.[^.]+$/', '', $path) ?? $path;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $priority = self::FORMAT_PRIORITY[$ext] ?? 99;

            if (
                ! isset($bestByBase[$base])
                || $priority < ($bestByBase[$base]['priority'] ?? 99)
            ) {
                $bestByBase[$base] = ['path' => $path, 'priority' => $priority];
            }
        }

        $result = array_column($bestByBase, 'path');
        sort($result, SORT_NATURAL | SORT_FLAG_CASE);

        return array_values($result);
    }

    public static function preferredPublicPath(string $path): string
    {
        if (! preg_match('/\.(png|jpe?g)$/i', $path)) {
            return $path;
        }

        $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);
        if (is_string($webp) && $webp !== $path && file_exists(public_path($webp))) {
            return $webp;
        }

        return $path;
    }

    public static function randomPathForCategory(?string $category): ?string
    {
        $paths = self::matchingPathsForCategory($category);
        if ($paths === []) {
            return null;
        }

        return $paths[array_rand($paths)];
    }

    public static function stablePathForCategory(?string $category, int $seed): ?string
    {
        $paths = self::matchingPathsForCategory($category);
        if ($paths === []) {
            return null;
        }

        return $paths[abs($seed) % count($paths)];
    }

    public static function defaultPath(): ?string
    {
        foreach (['webp', 'png', 'jpg', 'jpeg', 'svg', 'gif'] as $ext) {
            $path = 'category/default.'.$ext;
            if (file_exists(public_path($path))) {
                return $path;
            }
        }

        foreach (['webp', 'svg', 'png', 'jpg', 'jpeg', 'gif'] as $ext) {
            $path = self::CATEGORY_DIR.'/default.'.$ext;
            if (file_exists(public_path($path))) {
                return $path;
            }
        }

        return null;
    }

    public static function resolveUrl(?string $featuredImage, ?string $category, ?int $stableSeed = null): string
    {
        if (filled($featuredImage)) {
            if (Storage::disk('public')->exists($featuredImage)) {
                return self::absoluteUrl(Storage::disk('public')->url($featuredImage));
            }

            if (file_exists(public_path($featuredImage))) {
                return self::absoluteUrl(asset(self::preferredPublicPath($featuredImage)));
            }
        }

        $path = $stableSeed !== null
            ? self::stablePathForCategory($category, $stableSeed)
            : self::randomPathForCategory($category);

        if ($path !== null && file_exists(public_path($path))) {
            return self::absoluteUrl(asset(self::preferredPublicPath($path)));
        }

        $default = self::defaultPath();
        if ($default !== null) {
            return self::absoluteUrl(asset(self::preferredPublicPath($default)));
        }

        return self::absoluteUrl(asset('images/placeholder.svg'));
    }

    public static function absoluteUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return url('/images/placeholder.svg');
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        if (str_starts_with($url, '//')) {
            $scheme = parse_url((string) config('app.url'), PHP_URL_SCHEME) ?: 'https';

            return "{$scheme}:{$url}";
        }

        return url('/'.ltrim($url, '/'));
    }
}
