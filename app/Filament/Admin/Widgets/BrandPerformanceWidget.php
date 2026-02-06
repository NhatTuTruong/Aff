<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Brand;
use App\Models\Click;
use App\Models\PageView;
use App\Services\AnalyticsService;
use Filament\Widgets\Widget;

class BrandPerformanceWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.brand-performance-widget';
    
    protected static ?string $heading = 'Hiệu suất theo cửa hàng';
    
    protected static ?string $description = 'Thống kê clicks, views và CTR theo từng cửa hàng';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getViewData(): array
    {
        $analyticsService = app(AnalyticsService::class);
        
        $brands = Brand::with(['campaigns.clicks', 'campaigns.pageViews'])
            ->has('campaigns')
            ->get()
            ->map(function ($brand) use ($analyticsService) {
                $totalClicks = $brand->campaigns->sum(function ($campaign) {
                    return $campaign->clicks->count();
                });
                
                $totalViews = $brand->campaigns->sum(function ($campaign) {
                    return $campaign->pageViews->count();
                });
                
                $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0;
                
                return [
                    'name' => $brand->name,
                    'clicks' => $totalClicks,
                    'views' => $totalViews,
                    'ctr' => $ctr,
                    'campaigns_count' => $brand->campaigns->count(),
                ];
            })
            ->sortByDesc('clicks')
            ->take(10)
            ->values()
            ->toArray();

        return [
            'brands' => $brands,
        ];
    }
}

