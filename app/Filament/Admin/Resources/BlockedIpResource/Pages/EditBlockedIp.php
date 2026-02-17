<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlockedIp extends EditRecord
{
    protected static string $resource = BlockedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
