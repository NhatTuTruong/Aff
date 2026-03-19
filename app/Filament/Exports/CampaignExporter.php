<?php

namespace App\Filament\Exports;

use App\Models\Campaign;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CampaignExporter extends Exporter
{
    protected static ?string $model = Campaign::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('category')
                ->label('Danh mục')
                ->state(function (Campaign $record) {
                    return $record->brand?->category?->name;
                }),
            ExportColumn::make('brand')
                ->label('Cửa hàng')
                ->state(fn (Campaign $record) => $record->brand?->name),
            ExportColumn::make('domain')
                ->label('Domain (lấy logo)')
                ->state(function (Campaign $record) {
                    return $record->brand?->domain;
                }),
            ExportColumn::make('title')
                ->label('Tiêu đề'),
            ExportColumn::make('slug')
                ->label('Slug')
                ->state(function (Campaign $record) {
                    // Giữ nguyên phần slug đầy đủ (userCode/slug) để người dùng có thể tái sử dụng
                    return $record->slug;
                }),
            ExportColumn::make('intro')
                ->label('Giới thiệu (HTML)')
                ->state(fn (Campaign $record) => $record->intro),
            ExportColumn::make('status')
                ->label('Trạng thái'),
            ExportColumn::make('type')
                ->label('Loại chiến dịch'),
            ExportColumn::make('template')
                ->label('Template'),
            ExportColumn::make('affiliate_url')
                ->label('URL Affiliate'),
            ExportColumn::make('link_network')
                ->label('Link Network'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('coupon_codes')
                ->label('Mã giảm giá (phân cách bằng xuống hàng)')
                ->state(function (Campaign $record) {
                    $items = $record->couponItems()->get(['code']);
                    if ($items->isEmpty()) {
                        return null;
                    }
                    return $items->pluck('code')
                        ->filter(fn (?string $code) => $code !== null && $code !== '')
                        ->implode("\n");
                }),
            ExportColumn::make('coupon_offers')
                ->label('Offer (phân cách bằng xuống hàng)')
                ->state(function (Campaign $record) {
                    $items = $record->couponItems()->get(['offer']);
                    if ($items->isEmpty()) {
                        return null;
                    }
                    return $items->pluck('offer')
                        ->filter(fn (?string $offer) => $offer !== null && $offer !== '')
                        ->implode("\n");
                }),
            ExportColumn::make('coupon_descriptions')
                ->label('Mô tả mã giảm giá (phân cách bằng xuống hàng)')
                ->state(function (Campaign $record) {
                    $items = $record->couponItems()->get(['description']);
                    if ($items->isEmpty()) {
                        return null;
                    }
                    return $items->pluck('description')
                        ->filter(fn (?string $desc) => $desc !== null && $desc !== '')
                        ->implode("\n");
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Xuất dữ liệu chiến dịch đã hoàn thành. Đã xuất ' . number_format($export->successful_rows) . ' dòng.';
    }
}

