<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Campaign;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class TopCampaignsByClicksWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.top-campaigns-by-clicks-widget';

    protected static ?string $heading = 'Top chiến dịch theo lượt click';

    protected static ?string $description = 'Các chiến dịch có lượt click cao nhất';

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected static ?int $sort = 3;

    protected static bool $isDiscovered = true;

    protected function getViewData(): array
    {
        $userId = Filament::auth()->id();
        $userScope = fn (Builder $q) => $q->where('user_id', $userId);

        $campaigns = Campaign::query()
            ->withCount('clicks')
            ->whereHas('brand', $userScope)
            ->orderByDesc('clicks_count')
            ->take(10)
            ->get()
            ->map(fn (Campaign $c) => [
                'id' => $c->id,
                'title' => $c->title,
                'brand' => $c->brand?->name ?? '-',
                'clicks' => $c->clicks_count,
                'status' => $c->status,
            ])
            ->values()
            ->toArray();

        return [
            'campaigns' => $campaigns,
        ];
    }
}
