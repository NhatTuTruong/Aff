<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use Filament\Widgets\ChartWidget;

class CampaignsByStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Phân bố chiến dịch theo trạng thái';
    
    protected static ?string $description = 'Biểu đồ tròn thể hiện tỷ lệ các chiến dịch theo trạng thái';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];
    
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getData(): array
    {
        $active = Campaign::where('status', 'active')->count();
        $paused = Campaign::where('status', 'paused')->count();
        $draft = Campaign::where('status', 'draft')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng',
                    'data' => [$active, $paused, $draft],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)',  // Green for active
                        'rgba(251, 191, 36, 0.8)',  // Yellow for paused
                        'rgba(156, 163, 175, 0.8)', // Gray for draft
                    ],
                    'borderColor' => [
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(156, 163, 175, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Hoạt động', 'Tạm dừng', 'Bản nháp'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

