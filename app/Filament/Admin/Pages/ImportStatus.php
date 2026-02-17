<?php

namespace App\Filament\Admin\Pages;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Coupon;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ImportStatus extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static string $view = 'filament.admin.pages.import-status';

    protected static ?string $navigationLabel = 'Trạng thái Import';

    protected static ?string $title = 'Trạng thái Import chiến dịch';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->query(Import::query()->where('importer', \App\Filament\Imports\CampaignImporter::class)->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('file_name')
                    ->label('File')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('total_rows')
                    ->label('Tổng dòng')
                    ->numeric(),
                TextColumn::make('processed_rows')
                    ->label('Đã xử lý')
                    ->numeric(),
                TextColumn::make('successful_rows')
                    ->label('Thành công')
                    ->numeric()
                    ->color('success'),
                TextColumn::make('failed_rows_count')
                    ->label('Thất bại')
                    ->getStateUsing(fn (Import $record): int => $record->getFailedRowsCount())
                    ->numeric()
                    ->color('danger'),
                TextColumn::make('progress')
                    ->label('Tiến độ')
                    ->getStateUsing(function (Import $record): string {
                        if ($record->total_rows <= 0) {
                            return '0%';
                        }
                        $pct = round(($record->processed_rows / $record->total_rows) * 100);

                        return "{$pct}%";
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->getStateUsing(fn (Import $record): string => $record->completed_at ? 'Hoàn thành' : 'Đang xử lý')
                    ->color(fn (Import $record): string => $record->completed_at ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Bắt đầu')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Hoàn thành')
                    ->dateTime()
                    ->placeholder('–'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\Filter::make('created_at')
                    ->label('Ngày tạo')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Action::make('viewFailed')
                    ->label('Xem lỗi')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn (Import $record): bool => $record->getFailedRowsCount() > 0)
                    ->slideOver()
                    ->modalHeading(fn (Import $record): string => 'Dòng lỗi: ' . \Illuminate\Support\Str::limit($record->file_name, 40))
                    ->modalContent(fn (Import $record) => view('filament.admin.pages.import-failed-rows', ['failedRows' => $record->failedRows()->orderBy('id')->get()])),
                Action::make('downloadFailed')
                    ->label('Tải CSV lỗi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (Import $record): bool => $record->getFailedRowsCount() > 0)
                    ->url(fn (Import $record): string => route('admin.imports.failed-rows.download', ['import' => $record]))
                    ->openUrlInNewTab(),
                Action::make('rollback')
                    ->label(fn (Import $record): string => $record->rollback_at ? 'Đã khôi phục' : 'Khôi phục')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color(fn (Import $record): string => $record->rollback_at ? 'gray' : 'danger')
                    ->visible(fn (Import $record): bool => (bool) $record->completed_at)
                    ->disabled(fn (Import $record): bool => (bool) $record->rollback_at)
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận khôi phục')
                    ->modalDescription(fn (Import $record): string => static::getRollbackDescription($record))
                    ->modalSubmitActionLabel('Khôi phục (xóa dữ liệu đã import)')
                    ->action(fn (Import $record) => static::rollbackImport($record)),
            ])
            ->striped()
            ->poll('3s');
    }

    public static function getSlug(): string
    {
        return 'import-status';
    }

    protected static function getRollbackDescription(Import $import): string
    {
        $importId = $import->getKey();
        $campaignsCount = Campaign::where('import_id', $importId)->count();
        $brandsCount = Brand::where('import_id', $importId)->count();
        $categoriesCount = Category::where('import_id', $importId)->count();

        $parts = [];
        if ($campaignsCount > 0) {
            $parts[] = "{$campaignsCount} chiến dịch";
        }
        if ($brandsCount > 0) {
            $parts[] = "{$brandsCount} cửa hàng";
        }
        if ($categoriesCount > 0) {
            $parts[] = "{$categoriesCount} danh mục";
        }

        $list = empty($parts) ? 'không có dữ liệu' : implode(', ', $parts);

        return "Khôi phục sẽ XÓA vĩnh viễn các bản ghi được tạo trong tiến trình import này: {$list}. " .
            'Hành động không thể hoàn tác. Bạn có chắc chắn?';
    }

    protected static function rollbackImport(Import $import): void
    {
        $importId = $import->getKey();

        DB::transaction(function () use ($import, $importId): void {
            $campaignIds = Campaign::where('import_id', $importId)->pluck('id');

            Coupon::whereIn('campaign_id', $campaignIds)->forceDelete();
            Campaign::where('import_id', $importId)->forceDelete();
            Brand::where('import_id', $importId)->forceDelete();
            Category::where('import_id', $importId)->forceDelete();

            $import->update(['rollback_at' => now()]);
        });

        Notification::make()
            ->title('Khôi phục thành công')
            ->body('Đã xóa các chiến dịch, cửa hàng, danh mục được tạo trong tiến trình import.')
            ->success()
            ->send();
    }

}
