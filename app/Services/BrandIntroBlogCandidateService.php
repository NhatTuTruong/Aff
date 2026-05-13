<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

/**
 * Chọn store (brand) + chiến dịch import để viết blog giới thiệu (theo engagement).
 */
class BrandIntroBlogCandidateService
{
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
