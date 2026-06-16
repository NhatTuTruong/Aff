<?php

namespace App\Jobs;

use App\Jobs\ExportCsvPreserveSession;
use Filament\Actions\Exports\Jobs\PrepareCsvExport as BasePrepareCsvExport;

class PrepareCsvExportPreserveSession extends BasePrepareCsvExport
{
    public function getExportCsvJob(): string
    {
        return ExportCsvPreserveSession::class;
    }
}
