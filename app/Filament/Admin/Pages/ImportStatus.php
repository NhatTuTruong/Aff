<?php

namespace App\Filament\Admin\Pages;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Coupon;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ImportStatus extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public function mount(): void
    {
        $this->notifyStuckImportsIfAny();
    }

    /** Gửi thông báo nếu có import bị kẹt (hàng đợi chưa xử lý). */
    protected function notifyStuckImportsIfAny(): void
    {
        $stuck = Import::query()
            ->where('importer', \App\Filament\Imports\CampaignImporter::class)
            ->whereNull('completed_at')
            ->whereNull('cancelled_at')
            ->where('processed_rows', 0)
            ->where('created_at', '<=', now()->subMinutes(5))
            ->exists();

        if ($stuck) {
            Notification::make()
                ->title('Có import đang chờ xử lý')
                ->body('Một hoặc vài file import đã tải lên nhưng chưa được xử lý (đứng hàng đợi). Hãy kiểm tra queue worker đang chạy: php artisan queue:work.')
                ->warning()
                ->persistent()
                ->send();
        }
    }

    protected static string $view = 'filament.admin.pages.import-status';

    protected static ?string $navigationLabel = 'Trạng thái Import';

    protected static ?string $title = 'Trạng thái Import chiến dịch';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 5;

    public function table(Table $table): Table
    {
        $userId = Filament::auth()->id();
        
        return $table
            ->defaultPaginationPageOption(25)
            ->query(Import::query()
                ->where('importer', \App\Filament\Imports\CampaignImporter::class)
                ->when($userId, function (Builder $query) use ($userId) {
                    // Lọc import theo user_id thông qua import_id trong campaign/brand/category
                    $query->where(function ($q) use ($userId) {
                        // Import có user_id trùng với user hiện tại
                        $q->where('user_id', $userId)
                          // Hoặc import tạo ra campaign của user hiện tại (qua brand.user_id)
                          ->orWhereIn('id', function ($subQuery) use ($userId) {
                              $subQuery->select('campaigns.import_id')
                                  ->from('campaigns')
                                  ->join('brands', 'campaigns.brand_id', '=', 'brands.id')
                                  ->whereNotNull('campaigns.import_id')
                                  ->where('brands.user_id', $userId);
                          })
                          // Hoặc import tạo ra brand của user hiện tại
                          ->orWhereIn('id', function ($subQuery) use ($userId) {
                              $subQuery->select('import_id')
                                  ->from('brands')
                                  ->whereNotNull('import_id')
                                  ->where('user_id', $userId);
                          })
                          // Hoặc import tạo ra category của user hiện tại
                          ->orWhereIn('id', function ($subQuery) use ($userId) {
                              $subQuery->select('import_id')
                                  ->from('categories')
                                  ->whereNotNull('import_id')
                                  ->where('user_id', $userId);
                          });
                    });
                })
                ->orderByDesc('created_at'))
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
                    ->getStateUsing(fn (Import $record): int => $record->failedRows()->count())
                    ->numeric()
                    ->color('danger'),
                TextColumn::make('progress')
                    ->label('Tiến độ')
                    ->getStateUsing(function (Import $record): string {
                        if ($record->total_rows <= 0) {
                            return '0%';
                        }
                        $pct = min(100, round((int) $record->processed_rows / (int) $record->total_rows * 100));

                        return "{$pct}%";
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->getStateUsing(function (Import $record): string {
                        if ($record->cancelled_at ?? false) {
                            return 'Đã hủy';
                        }
                        if ($record->completed_at) {
                            return 'Hoàn thành';
                        }
                        return 'Đang xử lý';
                    })
                    ->color(fn (Import $record): string => ($record->cancelled_at ?? false) ? 'gray' : (($record->completed_at) ? 'success' : 'warning')),
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
                    ->visible(fn (Import $record): bool => $record->failedRows()->count() > 0)
                    ->slideOver()
                    ->modalHeading(fn (Import $record): string => 'Dòng lỗi: ' . \Illuminate\Support\Str::limit($record->file_name, 40))
                    ->modalContent(fn (Import $record) => view('filament.admin.pages.import-failed-rows', ['failedRows' => $record->failedRows()->orderBy('id')->get()])),
                Action::make('downloadFailed')
                    ->label('Tải CSV lỗi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (Import $record): bool => $record->failedRows()->count() > 0)
                    ->url(fn (Import $record): string => route('admin.imports.failed-rows.download', ['import' => $record]))
                    ->openUrlInNewTab(),
                Action::make('cancel')
                    ->label('Hủy tiến trình')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Import $record): bool => ! $record->completed_at && ! ($record->cancelled_at ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận hủy import')
                    ->modalDescription('Hủy sẽ dừng tiến trình và xóa toàn bộ dữ liệu đã tạo trong lần import này (chiến dịch, cửa hàng, danh mục). Bạn có chắc?')
                    ->modalSubmitActionLabel('Hủy tiến trình')
                    ->action(fn (Import $record) => static::cancelImport($record)),
                Action::make('rollback')
                    ->label(fn (Import $record): string => $record->rollback_at ? 'Đã khôi phục' : 'Khôi phục')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color(fn (Import $record): string => $record->rollback_at ? 'gray' : 'danger')
                    ->visible(fn (Import $record): bool => (bool) $record->completed_at && ! ($record->cancelled_at ?? false))
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

    protected static function deleteImportData(Import $import): void
    {
        $importId = $import->getKey();
        $campaignIds = Campaign::where('import_id', $importId)->pluck('id');
        Coupon::whereIn('campaign_id', $campaignIds)->forceDelete();
        Campaign::where('import_id', $importId)->forceDelete();
        Brand::where('import_id', $importId)->forceDelete();
        Category::where('import_id', $importId)->forceDelete();
    }

    protected static function cancelImport(Import $import): void
    {
        DB::transaction(function () use ($import): void {
            $import->update(['cancelled_at' => now()]);
            static::deleteImportData($import);
            $import->update(['rollback_at' => now()]);
        });

        Notification::make()
            ->title('Đã hủy tiến trình import')
            ->body('Tiến trình đã dừng và dữ liệu đã tạo trong lần import này đã bị xóa.')
            ->success()
            ->send();
    }

    protected static function rollbackImport(Import $import): void
    {
        DB::transaction(function () use ($import): void {
            static::deleteImportData($import);
            $import->update(['rollback_at' => now()]);
        });

        Notification::make()
            ->title('Khôi phục thành công')
            ->body('Đã xóa các chiến dịch, cửa hàng, danh mục được tạo trong tiến trình import.')
            ->success()
            ->send();
    }

}
