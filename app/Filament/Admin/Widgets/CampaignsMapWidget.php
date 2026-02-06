<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use App\Models\Brand;
use Filament\Widgets\Widget;

class CampaignsMapWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.campaigns-map-widget';
    
    protected static ?string $heading = 'Bản đồ thống kê chiến dịch';
    
    protected static ?string $description = 'Phân bố chiến dịch theo danh mục và cửa hàng';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getViewData(): array
    {
        // Thống kê chiến dịch theo danh mục
        $campaignsByCategory = Brand::with('category', 'campaigns')
            ->get()
            ->groupBy(function ($brand) {
                return $brand->category ? $brand->category->name : 'Chưa phân loại';
            })
            ->map(function ($brands) {
                return $brands->sum(function ($brand) {
                    return $brand->campaigns->count();
                });
            })
            ->sortDesc()
            ->take(10)
            ->toArray();

        // Thống kê chiến dịch theo cửa hàng
        $campaignsByBrand = Brand::withCount('campaigns')
            ->orderBy('campaigns_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($brand) {
                return [
                    'name' => $brand->name,
                    'count' => $brand->campaigns_count,
                ];
            })
            ->values()
            ->toArray();

        // Thống kê theo trạng thái
        $campaignsByStatus = Campaign::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalCampaigns = Campaign::count();

        return [
            'campaignsByCategory' => $campaignsByCategory,
            'campaignsByBrand' => $campaignsByBrand,
            'campaignsByStatus' => $campaignsByStatus,
            'totalCampaigns' => $totalCampaigns,
            'totalBrands' => Brand::count(),
        ];
    }
}

