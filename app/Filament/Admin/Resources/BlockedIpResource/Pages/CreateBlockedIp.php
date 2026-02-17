<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedIp extends CreateRecord
{
    protected static string $resource = BlockedIpResource::class;
}
