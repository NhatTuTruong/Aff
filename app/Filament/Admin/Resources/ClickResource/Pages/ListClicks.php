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
            Actions\Action::make('exportCsv')
                ->label('Xuất CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $query = $this->getFilteredTableQuery()->with(['campaign.brand']);
                    $clicks = $query->get();
                    $filename = 'clicks_' . now()->format('Y-m-d_His') . '.csv';
                    return response()->streamDownload(function () use ($clicks): void {
                        $file = fopen('php://output', 'w');
                        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
                        fputcsv($file, ['ID', 'Chiến dịch', 'Cửa hàng', 'IP', 'Thiết bị', 'Trình duyệt', 'Hệ điều hành', 'Quốc gia', 'Sub ID', 'Thời gian']);
                        foreach ($clicks as $click) {
                            fputcsv($file, [
                                $click->id,
                                $click->campaign?->title ?? '',
                                $click->campaign?->brand?->name ?? '',
                                $click->ip ?? '',
                                $click->device_type ?? '',
                                $click->browser ?? '',
                                $click->os ?? '',
                                $click->country ?? '',
                                $click->sub_id ?? '',
                                $click->created_at?->format('d/m/Y H:i:s') ?? '',
                            ]);
                        }
                        fclose($file);
                    }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
                }),
        ];
    }
}
