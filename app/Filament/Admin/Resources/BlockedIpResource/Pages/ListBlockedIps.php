<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlockedIps extends ListRecords
{
    protected static string $resource = BlockedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
