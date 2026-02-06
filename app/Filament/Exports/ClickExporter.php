<?php

namespace App\Filament\Exports;

use App\Models\Click;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClickExporter extends Exporter
{
    protected static ?string $model = Click::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('campaign.title')
                ->label('Chiến dịch'),
            ExportColumn::make('campaign.brand.name')
                ->label('Cửa hàng'),
            ExportColumn::make('ip')
                ->label('IP Address'),
            ExportColumn::make('device_type')
                ->label('Thiết bị'),
            ExportColumn::make('browser')
                ->label('Trình duyệt'),
            ExportColumn::make('os')
                ->label('Hệ điều hành'),
            ExportColumn::make('country')
                ->label('Quốc gia'),
            ExportColumn::make('city')
                ->label('Thành phố'),
            ExportColumn::make('referer')
                ->label('Referer'),
            ExportColumn::make('sub_id')
                ->label('Sub ID'),
            ExportColumn::make('user_agent')
                ->label('User Agent'),
            ExportColumn::make('created_at')
                ->label('Thời gian')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Xuất dữ liệu clicks đã hoàn thành. Đã xuất ' . number_format($export->successful_rows) . ' dòng.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' dòng không thể xuất.';
        }

        return $body;
    }
}
