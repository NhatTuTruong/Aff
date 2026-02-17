<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBlockedIp extends ViewRecord
{
    protected static string $resource = BlockedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
