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

    protected int | string | array $columnSpan = ['default' => 'full', 'xl' => 6];

    protected static ?int $sort = 3;

    protected static bool $isDiscovered = true;

    protected function getViewData(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : Filament::auth()->id();
        $userScope = fn (Builder $q) => $q->where('user_id', $userId);

        $campaigns = Campaign::query()
            ->withCount('clicks')
            ->when($userId, fn ($q) => $q->whereHas('brand', $userScope))
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
