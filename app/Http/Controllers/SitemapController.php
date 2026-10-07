<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Campaign;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    private const NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * Sitemap index (Google: split when many URLs or logical sections).
     */
    public function index(): Response
    {
        $now = now()->toAtomString();
        $entries = [
            ['loc' => $this->absoluteUrl('/sitemap-pages.xml'), 'lastmod' => $now],
            ['loc' => $this->absoluteUrl('/sitemap-blog.xml'), 'lastmod' => $now],
            ['loc' => $this->absoluteUrl('/sitemap-stores.xml'), 'lastmod' => $now],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="' . self::NS . '">' . "\n";
        foreach ($entries as $entry) {
            $xml .= "  <sitemap>\n";
            $xml .= '    <loc>' . $this->escape($entry['loc']) . "</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= '    <lastmod>' . $this->escape($entry['lastmod']) . "</lastmod>\n";
            }
            $xml .= "  </sitemap>\n";
        }
        $xml .= '</sitemapindex>';

        return $this->xmlResponse($xml);
    }

    /**
     * Static and listing pages (no coupon landings).
     */
    public function pages(): Response
    {
        $urls = [
            ['path' => '/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['path' => '/deals', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['path' => '/blog', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['path' => '/about', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['path' => '/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/cookie-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/affiliate-disclosure', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $rows = [];
        foreach ($urls as $u) {
            $rows[] = [
                'loc' => $this->absoluteUrl($u['path']),
                'changefreq' => $u['changefreq'],
                'priority' => $u['priority'],
            ];
        }

        return $this->xmlResponse($this->buildUrlset($rows));
    }

    /**
     * Published blog posts only.
     */
    public function blog(): Response
    {
        $rows = [];

        $posts = Blog::query()
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        foreach ($posts as $post) {
            $rows[] = [
                'loc' => $this->absoluteUrl('/blog/' . $post->slug),
                'lastmod' => $post->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return $this->xmlResponse($this->buildUrlset($rows));
    }

    /**
     * Active coupon landing pages (/store/{slug}).
     */
    public function stores(): Response
    {
        $rows = [];

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
            if (! Route::has('landing.show')) {
                continue;
            }
            $rows[] = [
                'loc' => route('landing.show', ['slug' => $slug], true),
                'lastmod' => $campaign->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return $this->xmlResponse($this->buildUrlset($rows));
    }

    /**
     * Canonical site origin (APP_URL), e.g. https://couponfindslab.com
     */
    private function absoluteUrl(string $path): string
    {
        $base = rtrim((string) config('app.url'), '/');
        $path = '/' . ltrim($path, '/');

        return $base . ($path === '/' ? '' : $path) . ($path === '/' ? '/' : '');
    }

    /**
     * @param  list<array{loc: string, lastmod?: string|null, changefreq?: string, priority?: string}>  $urls
     */
    private function buildUrlset(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="' . self::NS . '">' . "\n";

        foreach ($urls as $u) {
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

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function xmlResponse(string $xml): Response
    {
        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
