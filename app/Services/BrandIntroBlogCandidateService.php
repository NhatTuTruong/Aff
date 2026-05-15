<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Chọn store (brand) + chiến dịch để viết blog giới thiệu (theo engagement hoặc theo gợi ý admin).
 */
class BrandIntroBlogCandidateService
{
    /**
     * Tìm brand + một campaign có affiliate_url theo tên brand hoặc domain (dùng nút AI trong admin).
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

        $campaign = $this->pickTopImportedCampaignForBrand($brand);
        if (! $campaign) {
            return null;
        }

        return [$brand, $campaign];
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

    /**
     * Chuẩn hóa domain từ chuỗi người dùng (có thể là URL hoặc example.com).
     */
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

    /**
     * @return array{0: Brand, 1: Campaign}|null
     */
    public function findBrandAndCampaignForIntro(): ?array
    {
        $pvSub = DB::table('page_views')
            ->select('campaign_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('campaign_id');

        $ckSub = DB::table('clicks')
            ->whereNull('deleted_at')
            ->select('campaign_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('campaign_id');

        $brandRows = DB::table('campaigns')
            ->leftJoinSub($pvSub, 'pv', 'pv.campaign_id', '=', 'campaigns.id')
            ->leftJoinSub($ckSub, 'ck', 'ck.campaign_id', '=', 'campaigns.id')
            ->join('brands', 'brands.id', '=', 'campaigns.brand_id')
            ->whereNull('campaigns.deleted_at')
            ->whereNotNull('campaigns.affiliate_url')
            ->where('campaigns.affiliate_url', '!=', '')
            ->whereNull('brands.deleted_at')
            ->when(app()->environment('production'), fn ($q) => $q->where('campaigns.status', 'active'))
            ->select('campaigns.brand_id', DB::raw('SUM(COALESCE(pv.cnt, 0) + COALESCE(ck.cnt, 0)) AS total_eng'))
            ->groupBy('campaigns.brand_id')
            ->orderByDesc('total_eng')
            ->orderBy('campaigns.brand_id')
            ->get();

        if ($brandRows->isEmpty()) {
            return null;
        }

        // Mỗi ngày trong năm chọn 1 brand khác nhau (xoay vòng theo day-of-year)
        $index = now()->dayOfYear() % $brandRows->count();
        $row = $brandRows[$index];

        $brandId = (int) $row->brand_id;
        $brand = Brand::query()->with('category')->whereKey($brandId)->first();
        if (! $brand) {
            return null;
        }

        $campaign = $this->pickTopImportedCampaignForBrand($brand);
        if (! $campaign) {
            return null;
        }

        return [$brand, $campaign];
    }

    /**
     * Chiến dịch đã import + affiliate_url, engagement (views + clicks) cao nhất trong brand.
     */
    public function pickTopImportedCampaignForBrand(Brand $brand): ?Campaign
    {
        $pvSub = DB::table('page_views')
            ->select('campaign_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('campaign_id');

        $ckSub = DB::table('clicks')
            ->whereNull('deleted_at')
            ->select('campaign_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('campaign_id');

        return Campaign::query()
            ->leftJoinSub($pvSub, 'pv', 'pv.campaign_id', '=', 'campaigns.id')
            ->leftJoinSub($ckSub, 'ck', 'ck.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.brand_id', $brand->id)
            ->whereNull('campaigns.deleted_at')
            ->whereNotNull('campaigns.affiliate_url')
            ->where('campaigns.affiliate_url', '!=', '')
            ->when(app()->environment('production'), fn ($q) => $q->where('campaigns.status', 'active'))
            ->select('campaigns.*')
            ->selectRaw('(COALESCE(pv.cnt, 0) + COALESCE(ck.cnt, 0)) AS engagement')
            ->orderByDesc('engagement')
            ->orderBy('campaigns.id')
            ->first();
    }
}
