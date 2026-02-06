<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use App\Services\AnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getStats(): array
    {
        $analyticsService = app(AnalyticsService::class);
        
        $totalClicks = Click::count();
        $totalViews = PageView::count();
        $totalCampaigns = Campaign::where('status', 'active')->count();
        
        // Calculate overall CTR
        $overallCTR = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0;
        
        // Calculate unique visitors
        $uniqueVisitors = PageView::selectRaw('COUNT(DISTINCT CONCAT(ip, "-", COALESCE(session_id, ""))) as unique_visitors')
            ->value('unique_visitors') ?? 0;
        
        // Calculate bounce rate
        $totalPageViews = PageView::count();
        $bounces = PageView::where('is_bounce', true)->count();
        $bounceRate = $totalPageViews > 0 ? round(($bounces / $totalPageViews) * 100, 2) : 0;
        
        // Clicks today
        $clicksToday = Click::whereDate('created_at', today())->count();
        $clicksYesterday = Click::whereDate('created_at', today()->subDay())->count();
        $clicksChange = $clicksYesterday > 0 
            ? round((($clicksToday - $clicksYesterday) / $clicksYesterday) * 100, 1)
            : ($clicksToday > 0 ? 100 : 0);
        
        // Views today
        $viewsToday = PageView::whereDate('created_at', today())->count();
        $viewsYesterday = PageView::whereDate('created_at', today()->subDay())->count();
        $viewsChange = $viewsYesterday > 0 
            ? round((($viewsToday - $viewsYesterday) / $viewsYesterday) * 100, 1)
            : ($viewsToday > 0 ? 100 : 0);

        return [
            Stat::make('Tổng lượt click', number_format($totalClicks))
                ->description($clicksToday . ' clicks hôm nay')
                ->descriptionIcon($clicksChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart($this->getClicksChartData()),
            
            Stat::make('Tổng lượt xem', number_format($totalViews))
                ->description($viewsToday . ' views hôm nay')
                ->descriptionIcon($viewsChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('info')
                ->chart($this->getViewsChartData()),
            
            Stat::make('CTR (Click Through Rate)', $overallCTR . '%')
                ->description('Tỷ lệ click/views')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
            
            Stat::make('Unique Visitors', number_format($uniqueVisitors))
                ->description('Người dùng duy nhất')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            
            Stat::make('Bounce Rate', $bounceRate . '%')
                ->description('Tỷ lệ thoát')
                ->descriptionIcon('heroicon-m-arrow-uturn-left')
                ->color($bounceRate > 70 ? 'danger' : ($bounceRate > 50 ? 'warning' : 'success')),
        ];
    }

    protected function getClicksChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Click::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    protected function getViewsChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = PageView::whereDate('created_at', $date)->count();
        }
        return $data;
    }
}

