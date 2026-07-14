<?php

namespace App\Support;

use App\Models\Blog;
use Illuminate\Support\Str;

class MagazineLayout
{
    public static function usesMagazineChrome(): bool
    {
        if (! request()->routeIs('landing.show')) {
            return true;
        }

        $template = (string) request()->attributes->get('landing_template', 'template1');

        return in_array($template, ['template1', 'template2', 'template3'], true);
    }

    /** Chuẩn hóa nhãn menu (vd: Blog cũ → Review). */
    public static function navLabel(string $label): string
    {
        $label = trim($label);

        if (strcasecmp($label, 'Blog') === 0 || strcasecmp($label, 'Review Blog') === 0) {
            return 'Review';
        }

        return $label !== '' ? $label : 'Link';
    }

    /**
     * Breadcrumb cho thanh nav đen: [{label, url?}, ...] — mục cuối không có url.
     *
     * @return array<int, array{label: string, url: string|null}>
     */
    public static function breadcrumbTrail(): array
    {
        $home = ['label' => 'Home', 'url' => route('home')];

        if (request()->routeIs('home')) {
            return [['label' => 'Home', 'url' => null]];
        }

        if (request()->routeIs('blog.index')) {
            $category = trim((string) request()->query('category', ''));
            $query = trim((string) request()->query('q', ''));

            if ($category !== '') {
                return [
                    $home,
                    ['label' => 'Review', 'url' => route('blog.index')],
                    ['label' => $category, 'url' => null],
                ];
            }

            if ($query !== '') {
                return [
                    $home,
                    ['label' => 'Review', 'url' => route('blog.index')],
                    ['label' => 'Search: ' . Str::limit($query, 36), 'url' => null],
                ];
            }

            return [$home, ['label' => 'Review', 'url' => null]];
        }

        if (request()->routeIs('blog.show')) {
            $slug = (string) request()->route('slug');
            $post = Blog::query()
                ->where('is_published', true)
                ->where('slug', $slug)
                ->first();

            $trail = [$home, ['label' => 'Review', 'url' => route('blog.index')]];

            if ($post?->category) {
                $trail[] = [
                    'label' => $post->category,
                    'url' => route('blog.index', ['category' => $post->category]),
                ];
            }

            $trail[] = [
                'label' => $post ? Str::limit($post->title, 48) : 'Article',
                'url' => null,
            ];

            return $trail;
        }

        if (request()->routeIs('deals.index')) {
            return [$home, ['label' => 'Deals', 'url' => null]];
        }

        if (request()->routeIs('landing.show')) {
            $campaign = request()->attributes->get('landing_campaign');
            $brandName = $campaign?->brand?->name;

            return [
                $home,
                ['label' => $brandName ? Str::limit($brandName, 48) : 'Store', 'url' => null],
            ];
        }

        $pageLabels = [
            'about' => 'About Us',
            'contact' => 'Contact',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Use',
            'cookie-policy' => 'Cookie Policy',
            'affiliate-disclosure' => 'Affiliate Disclosure',
        ];

        $path = trim(request()->path(), '/');
        if (isset($pageLabels[$path])) {
            return [$home, ['label' => $pageLabels[$path], 'url' => null]];
        }

        if ($path !== '') {
            return [$home, ['label' => Str::title(str_replace('-', ' ', $path)), 'url' => null]];
        }

        return [$home];
    }
}
