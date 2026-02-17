<?php

namespace App\Http\Controllers;

use Filament\Actions\Imports\Models\FailedImportRow;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Http\Request;
use League\Csv\Bom;
use League\Csv\Writer;
use SplTempFileObject;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadImportFailedRowsCsvController extends Controller
{
    public function __invoke(Request $request, Import $import): StreamedResponse
    {
        // Cho phép: import thuộc user hiện tại; hoặc import có user_id null; hoặc panel không dùng auth
        $importUser = $import->user;
        if ($importUser !== null) {
            $currentUser = $request->user();
            if ($currentUser === null || ! $importUser->is($currentUser)) {
                abort(403, 'Bạn không có quyền tải file này.');
            }
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject);
        $csv->setOutputBOM(Bom::Utf8);

        $firstFailedRow = $import->failedRows()->first();
        $columnHeaders = $firstFailedRow ? array_keys($firstFailedRow->data) : [];
        $columnHeaders[] = __('filament-actions::import.failure_csv.error_header');
        $csv->insertOne($columnHeaders);

        $import->failedRows()
            ->lazyById(100)
            ->each(fn (FailedImportRow $failedImportRow) => $csv->insertOne([
                ...$failedImportRow->data,
                'error' => $failedImportRow->validation_error ?? __('filament-actions::import.failure_csv.system_error'),
            ]));

        $fileName = __('filament-actions::import.failure_csv.file_name', [
            'import_id' => $import->getKey(),
            'csv_name' => (string) str($import->file_name)->beforeLast('.')->remove('.'),
        ]) . '.csv';

        return response()->streamDownload(function () use ($csv) {
            foreach ($csv->chunk(1000) as $offset => $chunk) {
                echo $chunk;
                if ($offset % 1000) {
                    flush();
                }
            }
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
