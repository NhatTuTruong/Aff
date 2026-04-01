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
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        // Admin: không lọc theo user. User thường: chỉ brand của họ.
        // Tránh where('user_id', null) → chỉ khớp brand không có chủ (sai, thường = 0).
        $brandScope = fn (Builder $q) => $userId !== null
            ? $q->where('user_id', $userId)
            : $q;
        $clickScope = fn (Builder $q) => $q->whereHas('campaign.brand', $brandScope);
        $viewScope = fn (Builder $q) => $q->whereHas('campaign.brand', $brandScope);

        $activeCampaigns = Campaign::query()
            ->where('status', 'active')
            ->when(
                $userId !== null,
                fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('user_id', $userId)),
            )
            ->count();
        $totalBrands = Brand::when($userId, fn ($q) => $q->where('user_id', $userId))->count();

        $totalClicks = Click::when($userId, $clickScope)
            ->where('is_bot', false)
            ->forAdminStats()
            ->count();
        $totalViews = PageView::when($userId, $viewScope)
            ->where('is_bot', false)
            ->forAdminStats()
            ->count();

        // Clicks và Views hôm nay
        $clicksToday = Click::when($userId, $clickScope)
            ->where('is_bot', false)
            ->forAdminStats()
            ->whereDate('created_at', today())
            ->count();
        $viewsToday = PageView::when($userId, $viewScope)
            ->where('is_bot', false)
            ->forAdminStats()
            ->whereDate('created_at', today())
            ->count();

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
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Campaign::query()
                ->where('status', 'active')
                ->when(
                    $userId !== null,
                    fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('user_id', $userId)),
                )
                ->whereDate('created_at', '<=', $date)
                ->count();
        }
        return $data;
    }

    protected function getClicksChart(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        $clickScope = fn (Builder $q) => $q->whereHas(
            'campaign.brand',
            fn (Builder $b) => $userId !== null ? $b->where('user_id', $userId) : $b,
        );
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = Click::when($userId, $clickScope)
                ->where('is_bot', false)
                ->forAdminStats()
                ->whereDate('created_at', $date)
                ->count();
        }
        return $data;
    }

    protected function getViewsChart(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        $viewScope = fn (Builder $q) => $q->whereHas(
            'campaign.brand',
            fn (Builder $b) => $userId !== null ? $b->where('user_id', $userId) : $b,
        );
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $data[] = PageView::when($userId, $viewScope)
                ->where('is_bot', false)
                ->forAdminStats()
                ->whereDate('created_at', $date)
                ->count();
        }
        return $data;
    }
}

