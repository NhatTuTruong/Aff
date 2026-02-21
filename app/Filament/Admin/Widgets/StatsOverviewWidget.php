<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    /** 6 block → 2 hàng x 3 cột, không thừa chỗ trống */
    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $userId = Filament::auth()->id();
        $userScope = fn (Builder $q) => $q->where('user_id', $userId);
        $clickScope = fn (Builder $q) => $q->whereHas('campaign.brand', $userScope);
        $viewScope = fn (Builder $q) => $q->whereHas('campaign.brand', $userScope);

        $activeCampaigns = Campaign::where('status', 'active')
            ->whereHas('brand', $userScope)
            ->count();
        $totalBrands = Brand::when($userId, fn ($q) => $q->where('user_id', $userId))->count();

        $totalClicks = Click::when($userId, $clickScope)->count();
        $totalViews = PageView::when($userId, $viewScope)->count();

        // Clicks và Views hôm nay
        $clicksToday = Click::when($userId, $clickScope)->whereDate('created_at', today())->count();
        $viewsToday = PageView::when($userId, $viewScope)->whereDate('created_at', today())->count();

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

            Stat::make('Số bài blog', Blog::count())
                ->description('Bài viết blog')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
        ];
    }

    protected function getActiveCampaignsChart(): array
    {
        $userId = Filament::auth()->id();
        $userScope = fn (Builder $q) => $q->where('user_id', $userId);
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Campaign::where('status', 'active')
                ->whereHas('brand', $userScope)
                ->whereDate('created_at', '<=', $date)
                ->count();
        }
        return $data;
    }

    protected function getClicksChart(): array
    {
        $userId = Filament::auth()->id();
        $clickScope = fn (Builder $q) => $q->whereHas('campaign.brand', fn ($b) => $b->where('user_id', $userId));
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Click::when($userId, $clickScope)->whereDate('created_at', $date)->count();
        }
        return $data;
    }

    protected function getViewsChart(): array
    {
        $userId = Filament::auth()->id();
        $viewScope = fn (Builder $q) => $q->whereHas('campaign.brand', fn ($b) => $b->where('user_id', $userId));
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = PageView::when($userId, $viewScope)->whereDate('created_at', $date)->count();
        }
        return $data;
    }
}

