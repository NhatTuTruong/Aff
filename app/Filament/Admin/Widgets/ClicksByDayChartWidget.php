<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Click;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class ClicksByDayChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Clicks theo ngày';

    protected static ?string $description = 'Biểu đồ lượt click theo từng ngày';

    /** Cùng hàng với chart khác trên xl để không thừa chỗ trống */
protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 6];

    protected static ?int $sort = 4;

    protected static bool $isDiscovered = true;

    public ?string $filter = '7';

    public function updatedFilter(): void
    {
        $this->cachedData = null;
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => '7 ngày',
            '30' => '30 ngày',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 7);
        $days = in_array($days, [7, 30]) ? $days : 7;

        $userId = Filament::auth()->id();
        $clickScope = fn (Builder $q) => $q->whereHas('campaign.brand', fn ($b) => $b->where('user_id', $userId));

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $labels[] = $date->format('d/m');
            $data[] = Click::query()
                ->when($userId, $clickScope)
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lượt click',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
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
