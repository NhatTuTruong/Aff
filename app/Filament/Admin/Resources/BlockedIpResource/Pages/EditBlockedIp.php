<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlockedIp extends EditRecord
{
    protected static string $resource = BlockedIpResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();
        if (! ($user && method_exists($user, 'isAdmin') && $user->isAdmin())) {
            unset($data['block_public']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
