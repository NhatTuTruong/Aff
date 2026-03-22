<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Brand;
use App\Models\Click;
use App\Models\PageView;
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
        $brands = Brand::with(['campaigns.clicks', 'campaigns.pageViews'])
            ->has('campaigns')
            ->get()
            ->map(function ($brand) {
                $totalClicks = $brand->campaigns->sum(function ($campaign) {
                    return $campaign->clicks
                        ->filter(fn ($c) => ! $c->is_bot && ! Click::countryIsVietnam($c->country))
                        ->count();
                });
                
                $totalViews = $brand->campaigns->sum(function ($campaign) {
                    return $campaign->pageViews
                        ->filter(fn ($c) => ! $c->is_bot && ! PageView::countryIsVietnam($c->country))
                        ->count();
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

