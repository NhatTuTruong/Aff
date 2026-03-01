<?php

namespace App\Jobs;

use Exception;
use Filament\Actions\Imports\Events\ImportChunkProcessed;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\Jobs\ImportCsv as BaseImportCsv;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class ImportCsvWithNullUser extends BaseImportCsv
{
    public function handle(): void
    {
        $this->import->refresh();
        if ($this->import->cancelled_at ?? false) {
            return;
        }

        $user = $this->import->user;

        // if ($user instanceof Authenticatable) {
        //     if (method_exists(auth()->guard(), 'login')) {
        //         auth()->login($user);
        //     } else {
        //         auth()->setUser($user);
        //     }
        // }

        $exceptions = [];
        $processedRows = 0;
        $successfulRows = 0;

        $rows = $this->rows;
        if (! is_array($rows)) {
            $decoded = @unserialize(base64_decode($rows));
            $rows = is_array($decoded) ? $decoded : [];
        }

        foreach ($rows as $row) {
            $this->import->refresh();
            if ($this->import->cancelled_at ?? false) {
                return;
            }

            $row = $this->utf8Encode($row);

            try {
                DB::transaction(fn () => ($this->importer)($row));
                $successfulRows++;
            } catch (RowImportFailedException $exception) {
                $this->logFailedRow($row, $exception->getMessage());
            } catch (ValidationException $exception) {
                $this->logFailedRow($row, collect($exception->errors())->flatten()->implode(' '));
            } catch (Throwable $exception) {
                $exceptions[$exception::class] = $exception;
                $this->logFailedRow($row, $exception->getMessage());
            }

            $processedRows++;
        }

        $this->import::query()
            ->whereKey($this->import)
            ->update([
                'processed_rows' => DB::raw('processed_rows + ' . $processedRows),
                'successful_rows' => DB::raw('successful_rows + ' . $successfulRows),
            ]);

        $this->import::query()
            ->whereKey($this->import)
            ->whereColumn('processed_rows', '>', 'total_rows')
            ->update([
                'processed_rows' => DB::raw('total_rows'),
            ]);

        $this->import::query()
            ->whereKey($this->import)
            ->whereColumn('successful_rows', '>', 'total_rows')
            ->update([
                'successful_rows' => DB::raw('total_rows'),
            ]);

        $this->import->refresh();

        event(new ImportChunkProcessed(
            $this->import,
            $this->columnMap,
            $this->options,
            $processedRows,
            $successfulRows,
            $exceptions,
        ));

        $this->handleExceptions($exceptions);
        
        // Restore lại user gốc để tránh logout
        // if ($this->originalUser instanceof Authenticatable && $user instanceof Authenticatable && $user->id !== $this->originalUser->id) {
        //     if (method_exists(auth()->guard(), 'login')) {
        //         auth()->login($this->originalUser);
        //     } else {
        //         auth()->setUser($this->originalUser);
        //     }
        // }
    }

    /**
     * Không re-throw - ghi log lỗi và tiếp tục, không làm dừng tiến trình.
     *
     * @param  array<Throwable>  $exceptions
     */
    protected function handleExceptions(array $exceptions): void
    {
    }

    protected function utf8Encode(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map($this->utf8Encode(...), $value);
        }
        if (is_string($value)) {
            return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        return $value;
    }

}
