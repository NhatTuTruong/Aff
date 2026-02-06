<?php

namespace App\Filament\Admin\Resources\ClickResource\Pages;

use App\Filament\Admin\Resources\ClickResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClicks extends ListRecords
{
    protected static string $resource = ClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Clicks are created automatically, no manual creation needed
        ];
    }
}
