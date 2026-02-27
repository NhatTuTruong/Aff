<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use App\Models\BlockedIp;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlockedIps extends ListRecords
{
    protected static string $resource = BlockedIpResource::class;

    public function getSubheading(): ?string
    {
        $user = auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $count = BlockedIp::query()->forUser($user?->id, $isAdmin)->count();
        $max = config('blocked_ips.max_count', 500);

        return "";
    }

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $count = BlockedIp::query()->forUser($user?->id, $isAdmin)->count();
        $max = config('blocked_ips.max_count', 500);
        $atLimit = $count >= $max;

        return [
            Actions\CreateAction::make()
                ->disabled($atLimit)
                ->tooltip($atLimit ? "Đã đạt giới hạn {$max} IP." : null),
        ];
    }
}
