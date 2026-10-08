<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Campaign;
use Illuminate\Support\Facades\Route;

class SitemapGenerator
{
    private const NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /** @var list<array{loc: string, lastmod?: string|null, changefreq?: string, priority?: string}> */
    private array $urls = [];

    public function buildXml(): string
    {
        $this->urls = [];
        $this->collectUrls();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="' . self::NS . '">' . "\n";

        foreach ($this->urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $this->escape($u['loc']) . "</loc>\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $this->escape($u['lastmod']) . "</lastmod>\n";
            }
            if (! empty($u['changefreq'])) {
                $xml .= '    <changefreq>' . $this->escape($u['changefreq']) . "</changefreq>\n";
            }
            if (isset($u['priority'])) {
                $xml .= '    <priority>' . $this->escape((string) $u['priority']) . "</priority>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    public function urlCount(): int
    {
        if ($this->urls === []) {
            $this->collectUrls();
        }

        return count($this->urls);
    }

    public function write(): string
    {
        $path = (string) config('sitemap.write_path', public_path('sitemap.xml'));
        $xml = $this->buildXml();

        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, $xml);

        return $path;
    }

    private function collectUrls(): void
    {
        $this->addPage('/', 'daily', '1.0');
        $this->addPage('/deals', 'daily', '0.9');
        $this->addPage('/about', 'monthly', '0.5');
        $this->addPage('/contact', 'monthly', '0.5');
        $this->addPage('/privacy', 'yearly', '0.3');
        $this->addPage('/cookie-policy', 'yearly', '0.3');
        $this->addPage('/terms', 'yearly', '0.3');
        $this->addPage('/affiliate-disclosure', 'yearly', '0.3');

        $reviewIndex = (string) config('sitemap.review_index_path', '/review');
        $this->addPage($reviewIndex, 'daily', '0.9');

        // Legacy listing (still routed)
        $this->addPage('/blog', 'daily', '0.8');

        $posts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $reviewPrefix = rtrim((string) config('sitemap.review_post_path', '/review'), '/');
        $blogsPrefix = rtrim((string) config('sitemap.blogs_post_path', '/blogs'), '/');

        foreach ($posts as $post) {
            $slug = (string) $post->slug;
            if ($slug === '' || ! preg_match('/^[a-z0-9\-]+$/i', $slug)) {
                continue;
            }
            $lastmod = $post->updated_at?->toAtomString();
            $this->addLoc($this->absoluteUrl($reviewPrefix . '/' . $slug), $lastmod, 'weekly', '0.8');
            $this->addLoc($this->absoluteUrl($blogsPrefix . '/' . $slug), $lastmod, 'weekly', '0.8');
            $this->addLoc($this->absoluteUrl('/blog/' . $slug), $lastmod, 'weekly', '0.7');
        }

        if (config('sitemap.include_stores', true) && Route::has('landing.show')) {
            $campaigns = Campaign::query()
                ->where('status', 'active')
                ->whereHas('brand')
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at']);

            foreach ($campaigns as $campaign) {
                $slug = (string) $campaign->slug;
                if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
                    continue;
                }
                $this->addLoc(
                    route('landing.show', ['slug' => $slug], true),
                    $campaign->updated_at?->toAtomString(),
                    'weekly',
                    '0.7'
                );
            }
        }
    }

    private function addPage(string $path, string $changefreq, string $priority): void
    {
        $this->addLoc($this->absoluteUrl($path), null, $changefreq, $priority);
    }

    private function addLoc(string $loc, ?string $lastmod, string $changefreq, string $priority): void
    {
        foreach ($this->urls as $existing) {
            if ($existing['loc'] === $loc) {
                return;
            }
        }

        $this->urls[] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function absoluteUrl(string $path): string
    {
        $base = rtrim((string) config('app.url'), '/');
        $path = '/' . ltrim($path, '/');

        return $path === '/' ? $base . '/' : $base . $path;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
