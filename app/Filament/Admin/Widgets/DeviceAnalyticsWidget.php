<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Click;
use Filament\Widgets\ChartWidget;

class DeviceAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Phân bố theo thiết bị';
    
    protected static ?string $description = 'Tỷ lệ clicks theo loại thiết bị';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];
    
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getData(): array
    {
        $desktop = Click::where('device_type', 'desktop')->count();
        $mobile = Click::where('device_type', 'mobile')->count();
        $tablet = Click::where('device_type', 'tablet')->count();
        $other = Click::whereNotIn('device_type', ['desktop', 'mobile', 'tablet'])->count();

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng',
                    'data' => [$desktop, $mobile, $tablet, $other],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(156, 163, 175, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Desktop', 'Mobile', 'Tablet', 'Khác'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

