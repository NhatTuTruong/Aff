<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClickResource\Pages;
use App\Filament\Admin\Resources\ClickResource\RelationManagers;
use App\Filament\Exports\ClickExporter;
use App\Models\BlockedIp;
use App\Models\Click;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClickResource extends Resource
{
    protected static ?string $model = Click::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    
    protected static ?string $navigationLabel = 'Chi tiết Clicks';
    
    protected static ?string $modelLabel = 'Click';
    
    protected static ?string $pluralModelLabel = 'Clicks';
    
    protected static ?string $navigationGroup = 'Thống Kê';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('campaign_id')
                    ->relationship('campaign', 'title')
                    ->required(),
                Forms\Components\TextInput::make('ip')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('user_agent')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('referer')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('sub_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('device_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('browser')
                    ->maxLength(255),
                Forms\Components\TextInput::make('os')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('campaign.title')
                    ->label('Chiến dịch')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('campaign.brand.name')
                    ->limit(20)
                    ->label('Cửa hàng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->label('Thiết bị')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mobile' => 'info',
                        'tablet' => 'warning',
                        'desktop' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                        'desktop' => 'Desktop',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('browser')
                    ->label('Trình duyệt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('os')
                    ->label('Hệ điều hành')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('Quốc gia')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('sub_id')
                    ->label('Sub ID')
                    ->searchable()
                    ->placeholder('—')
                    ->copyable(),
                Tables\Columns\TextColumn::make('referer')
                    ->label('Referer')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->label('Quốc gia')
                    ->options(function () {
                        $userId = Filament::auth()->id();

                        return Click::query()
                            ->whereHas(
                                'campaign.brand',
                                fn (Builder $brandQuery) => $brandQuery->when(
                                    $userId,
                                    fn (Builder $q) => $q->where('user_id', $userId),
                                ),
                            )
                            ->whereNotNull('country')
                            ->where('country', '!=', '')
                            ->distinct()
                            ->pluck('country', 'country')
                            ->sort()
                            ->toArray();
                    })
                    ->searchable(),
                Tables\Filters\SelectFilter::make('campaign_id')
                    ->label('Chiến dịch')
                    ->relationship('campaign', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('device_type')
                    ->label('Thiết bị')
                    ->options([
                        'desktop' => 'Desktop',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                    ]),
                Tables\Filters\SelectFilter::make('browser')
                    ->label('Trình duyệt')
                    ->options(function () {
                        $userId = Filament::auth()->id();

                        return Click::query()
                            ->whereHas(
                                'campaign.brand',
                                fn (Builder $brandQuery) => $brandQuery->when(
                                    $userId,
                                    fn (Builder $q) => $q->where('user_id', $userId),
                                ),
                            )
                            ->whereNotNull('browser')
                            ->distinct()
                            ->pluck('browser', 'browser')
                            ->toArray();
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->label('Ngày tạo')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('ip')
                    ->form([
                        Forms\Components\TextInput::make('ip')
                            ->label('IP Address'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['ip'],
                            fn (Builder $query, $ip): Builder => $query->where('ip', 'like', "%{$ip}%"),
                        );
                    }),
                Tables\Filters\Filter::make('sub_id')
                    ->form([
                        Forms\Components\TextInput::make('sub_id')
                            ->label('Sub ID'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['sub_id'],
                            fn (Builder $query, $subId): Builder => $query->where('sub_id', 'like', "%{$subId}%"),
                        );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                ExportAction::make()
                    ->exporter(ClickExporter::class)
                    ->label('')
                    ->icon('heroicon-o-document-arrow-down')
                    ->tooltip('Xuất XLSX (yêu cầu đăng nhập & queue)')
                    ->visible(fn () => auth()->check()),
            ])
            ->actions([
                Action::make('blockIp')
                    ->label('')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->tooltip('Chặn IP')
                    ->requiresConfirmation()
                    ->modalHeading('Chặn IP này?')
                    ->modalDescription(fn (Click $record): string => "IP {$record->ip} sẽ bị chặn. Các click/view từ IP này sẽ không được thống kê.")
                    ->action(function (Click $record): void {
                        $userId = $record->campaign?->brand?->user_id ?? auth()->id();
                        BlockedIp::firstOrCreate(
                            ['ip' => $record->ip, 'user_id' => $userId],
                            ['reason' => 'Chặn từ danh sách Clicks', 'block_public' => false]
                        );
                        Notification::make()
                            ->title("Đã chặn IP {$record->ip}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Xem'),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Xóa'),
                Tables\Actions\RestoreAction::make()
                    ->label('')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->tooltip('Khôi phục'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Xóa vĩnh viễn'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ClickExporter::class),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClicks::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Filament::auth()->id();

        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class)
            ->when(
                $userId,
                fn (Builder $query) => $query->whereHas(
                    'campaign.brand',
                    fn (Builder $brandQuery) => $brandQuery->where('user_id', $userId),
                ),
            );
    }
}
