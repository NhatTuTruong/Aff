<?php

namespace App\Filament\Exports;

use App\Models\Campaign;
use App\Models\Category;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class CampaignExporter extends Exporter
{
    protected static ?string $model = Campaign::class;

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public function getFormats(): array
    {
        return [ExportFormat::Csv];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['brand.category', 'couponItems']);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('category')
                ->label('Danh mục')
                ->state(function (Campaign $record) {
                    $brand = $record->brand;
                    if (! $brand) {
                        return null;
                    }

                    if ($brand->category?->name) {
                        return $brand->category->name;
                    }

                    if ($brand->category_id) {
                        return Category::withTrashed()->find($brand->category_id)?->name;
                    }

                    return null;
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
                    // Slug một phần (URL /store/{slug}), dùng lại khi import
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
                    if ($record->couponItems->isEmpty()) {
                        return null;
                    }

                    return $record->couponItems
                        ->pluck('code')
                        ->filter(fn (?string $code) => $code !== null && $code !== '')
                        ->implode("\n");
                }),
            ExportColumn::make('coupon_offers')
                ->label('Offer (phân cách bằng xuống hàng)')
                ->state(function (Campaign $record) {
                    if ($record->couponItems->isEmpty()) {
                        return null;
                    }

                    return $record->couponItems
                        ->pluck('offer')
                        ->filter(fn (?string $offer) => $offer !== null && $offer !== '')
                        ->implode("\n");
                }),
            ExportColumn::make('coupon_descriptions')
                ->label('Mô tả mã giảm giá (phân cách bằng xuống hàng)')
                ->state(function (Campaign $record) {
                    if ($record->couponItems->isEmpty()) {
                        return null;
                    }

                    return $record->couponItems
                        ->pluck('description')
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

