<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Support\AdminSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Chọn store (brand) + chiến dịch để viết blog giới thiệu.
 */
class BrandIntroBlogCandidateService
{
    public const INTRO_QUEUE_INDEX_KEY = 'auto_blog.intro_campaign_queue_index';

    /**
     * Danh sách chiến dịch theo thứ tự cũ → mới (xoay vòng khi hết list).
     *
     * @return Collection<int, Campaign>
     */
    public function orderedCampaignsForIntro(): Collection
    {
        return Campaign::query()
            ->with(['brand.category', 'couponItems'])
            ->whereNull('deleted_at')
            ->whereHas('brand', fn ($query) => $query->whereNull('deleted_at'))
            ->when(app()->environment('production'), fn ($query) => $query->where('status', 'active'))
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Lấy brand + campaign tiếp theo trong hàng đợi (chưa tăng index).
     *
     * @return array{0: Brand, 1: Campaign}|null
     */
    public function peekNextForIntroQueue(): ?array
    {
        $campaigns = $this->orderedCampaignsForIntro();
        if ($campaigns->isEmpty()) {
            return null;
        }

        $index = $this->currentQueueIndex() % $campaigns->count();
        $campaign = $campaigns->get($index);
        if (! $campaign instanceof Campaign) {
            return null;
        }

        $brand = $campaign->brand;
        if (! $brand instanceof Brand) {
            return null;
        }

        return [$brand, $campaign];
    }

    /**
     * Tăng index hàng đợi sau khi tạo bài thành công.
     */
    public function advanceIntroQueue(): void
    {
        $count = $this->orderedCampaignsForIntro()->count();
        if ($count === 0) {
            return;
        }

        $index = $this->currentQueueIndex();
        AdminSettings::set(self::INTRO_QUEUE_INDEX_KEY, ($index + 1) % $count);
    }

    /**
     * @return array{0: Brand, 1: Campaign}|null
     */
    public function findBrandAndCampaignForIntro(): ?array
    {
        return $this->peekNextForIntroQueue();
    }

    /**
     * Tìm brand + campaign theo gợi ý admin (tên/domain).
     *
     * @return array{0: Brand, 1: Campaign}|null
     */
    public function findBrandAndCampaignByHint(string $hint): ?array
    {
        $hint = trim($hint);
        if ($hint === '') {
            return null;
        }

        $brand = $this->findBrandByHint($hint);
        if (! $brand) {
            return null;
        }

        $campaign = $this->pickCampaignForBrand($brand);
        if (! $campaign) {
            return null;
        }

        return [$brand, $campaign];
    }

    public function resolveBrandCategoryLabel(Brand $brand): string
    {
        $brand->loadMissing('category');

        if (filled($brand->category?->name)) {
            return (string) $brand->category->name;
        }

        if ($brand->category_id) {
            $category = Category::withTrashed()->find($brand->category_id);
            if (filled($category?->name)) {
                return (string) $category->name;
            }
        }

        $legacyCategory = trim((string) $brand->getAttribute('category'));
        if ($legacyCategory !== '') {
            $raw = (string) Str::of($legacyCategory)->afterLast('/')->replace('-', ' ')->replace('_', ' ');
            $normalized = Str::title(trim($raw));
            if ($normalized !== '') {
                return $normalized;
            }
        }

        return (string) (config('default_categories.names.0') ?? 'General');
    }

    protected function currentQueueIndex(): int
    {
        return max(0, (int) AdminSettings::get(self::INTRO_QUEUE_INDEX_KEY, 0));
    }

    protected function findBrandByHint(string $hint): ?Brand
    {
        $base = Brand::query()->whereNull('deleted_at');

        $lower = Str::lower($hint);
        $brand = (clone $base)->whereRaw('LOWER(name) = ?', [$lower])->first();
        if ($brand) {
            return $brand;
        }

        $escaped = addcslashes($hint, '%_\\');
        $brand = (clone $base)
            ->where('name', 'like', '%'.$escaped.'%')
            ->orderByDesc('id')
            ->first();
        if ($brand) {
            return $brand;
        }

        $host = $this->normalizeHostForLookup($hint);
        if ($host === null || $host === '') {
            return null;
        }

        return (clone $base)
            ->whereNotNull('domain')
            ->where('domain', '!=', '')
            ->whereRaw('LOWER(domain) LIKE ?', ['%'.$host.'%'])
            ->orderByDesc('id')
            ->first();
    }

    protected function normalizeHostForLookup(string $input): ?string
    {
        $t = trim($input);
        if ($t === '') {
            return null;
        }

        if (! preg_match('~^https?://~i', $t)) {
            $t = 'https://'.$t;
        }

        $host = parse_url($t, PHP_URL_HOST);
        if (is_string($host) && $host !== '') {
            return Str::lower(preg_replace('/^www\./i', '', $host));
        }

        $plain = Str::lower(preg_replace('/^www\./i', '', rtrim($t, '/')));
        if (preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $plain)) {
            return $plain;
        }

        return null;
    }

    protected function pickCampaignForBrand(Brand $brand): ?Campaign
    {
        return Campaign::query()
            ->with(['brand.category', 'couponItems'])
            ->where('brand_id', $brand->id)
            ->whereNull('deleted_at')
            ->when(app()->environment('production'), fn ($query) => $query->where('status', 'active'))
            ->orderBy('created_at')
            ->orderBy('id')
            ->first();
    }
}
