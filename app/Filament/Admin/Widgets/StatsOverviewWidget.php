<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalBrands = Brand::count();
        
        $totalClicks = Click::count();
        $totalViews = PageView::count();
        
        // Clicks và Views hôm nay
        $clicksToday = Click::whereDate('created_at', today())->count();
        $viewsToday = PageView::whereDate('created_at', today())->count();
        
        // Tính CTR
        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0;

        return [
            Stat::make('Chiến dịch đang hoạt động', $activeCampaigns)
                ->description('Đang chạy')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($this->getActiveCampaignsChart()),
            
            Stat::make('Tổng lượt click', number_format($totalClicks))
                ->description($clicksToday . ' clicks hôm nay')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color('primary')
                ->chart($this->getClicksChart()),
            
            Stat::make('Tổng lượt xem', number_format($totalViews))
                ->description($viewsToday . ' views hôm nay')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info')
                ->chart($this->getViewsChart()),
            
            Stat::make('Tổng số cửa hàng', $totalBrands)
                ->description('Brands đã đăng ký')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),
            
            Stat::make('CTR', $ctr . '%')
                ->description('Click Through Rate')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($ctr >= 5 ? 'success' : ($ctr >= 2 ? 'warning' : 'danger')),
        ];
    }

    protected function getActiveCampaignsChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Campaign::where('status', 'active')
                ->whereDate('created_at', '<=', $date)
                ->count();
        }
        return $data;
    }

    protected function getClicksChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Click::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    protected function getViewsChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = PageView::whereDate('created_at', $date)->count();
        }
        return $data;
    }
}

