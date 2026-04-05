<?php

namespace App\Filament\Admin\Resources\CustomerLeadResource\Pages;

use App\Filament\Admin\Resources\CustomerLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerLeads extends ListRecords
{
    protected static string $resource = CustomerLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

