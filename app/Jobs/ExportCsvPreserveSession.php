<?php

namespace App\Jobs;

use Filament\Actions\Exports\Jobs\ExportCsv as BaseExportCsv;
use Illuminate\Contracts\Auth\Authenticatable;

class ExportCsvPreserveSession extends BaseExportCsv
{
    public function handle(): void
    {
        $originalUser = auth()->user();

        try {
            parent::handle();
        } finally {
            if ($originalUser instanceof Authenticatable) {
                if (method_exists(auth()->guard(), 'login')) {
                    auth()->login($originalUser);
                } else {
                    auth()->setUser($originalUser);
                }
            }
        }
    }
}
