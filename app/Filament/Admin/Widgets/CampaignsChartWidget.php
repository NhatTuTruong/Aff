<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CampaignsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Xu hướng chiến dịch theo tháng';
    
    protected static ?string $description = 'Biểu đồ thể hiện số lượng chiến dịch được tạo theo từng tháng';
    
    /** Nằm bên phải block Top chiến dịch trên xl */
    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 6];
    
    protected static ?int $sort = 4;
    
    protected static bool $isDiscovered = true;

    protected function getData(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        $userScope = fn (Builder $q) => $q->where('user_id', $userId);

        $months = [];
        $data = [];

        // Lấy dữ liệu 12 tháng gần nhất
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            $count = Campaign::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->when($userId, fn ($q) => $q->whereHas('brand', $userScope))
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng chiến dịch',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}

