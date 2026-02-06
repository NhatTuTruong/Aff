<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class CampaignAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Phân tích chiến dịch theo thời gian';
    
    protected static ?string $description = 'So sánh lượt xem và lượt click theo thời gian';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 999; // Ẩn widget này
    
    protected static bool $isDiscovered = false; // Không hiển thị trên dashboard

    protected function getData(): array
    {
        $months = [];
        $viewsData = [];
        $clicksData = [];
        
        // Lấy dữ liệu 6 tháng gần nhất
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $viewsData[] = PageView::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $clicksData[] = Click::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lượt xem',
                    'data' => $viewsData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Lượt click',
                    'data' => $clicksData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
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
}

