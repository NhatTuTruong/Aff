<?php

namespace App\Filament\Admin\Pages;

use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class CampaignStats extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.admin.pages.campaign-stats';

    protected static ?string $navigationLabel = 'Thống kê Chiến dịch';

    protected static ?string $title = 'Thống kê Chiến dịch theo Clicks';

    protected static ?string $navigationGroup = 'Clicks';

    protected static ?int $navigationSort = 2;

    public ?int $selectedCampaignId = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Campaign::query()
                    ->where('status', 'active')
                    ->withCount('clicks')
                    ->orderByDesc('clicks_count')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Chiến dịch')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('brand.name')
                    ->label('Cửa hàng')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('clicks_count')
                    ->label('Số Clicks')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('views_count')
                    ->label('Số Views')
                    ->getStateUsing(fn (Campaign $record): int => $record->pageViews()->count())
                    ->numeric()
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Action::make('viewChart')
                    ->label('Xem biểu đồ')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->slideOver()
                    ->modalHeading(fn (Campaign $record): string => "Biểu đồ: {$record->title}")
                    ->modalContent(fn (Campaign $record) => view('filament.admin.pages.campaign-stats-chart', [
                        'campaign' => $record,
                        'chartData' => $this->getChartData($record->id),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng'),
            ])
            ->striped();
    }

    protected function getChartData(int $campaignId): array
    {
        $clicks = Click::query()
            ->where('campaign_id', $campaignId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $views = PageView::query()
            ->where('campaign_id', $campaignId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = array_values(array_unique(array_merge(array_keys($clicks), array_keys($views))));
        sort($labels);

        return [
            'labels' => $labels,
            'clicks' => array_map(fn (string $d) => $clicks[$d] ?? 0, $labels),
            'views' => array_map(fn (string $d) => $views[$d] ?? 0, $labels),
        ];
    }

    public static function getSlug(): string
    {
        return 'campaign-stats';
    }
}
