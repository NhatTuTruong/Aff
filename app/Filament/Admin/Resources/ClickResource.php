<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClickResource\Pages;
use App\Filament\Admin\Resources\ClickResource\RelationManagers;
use App\Filament\Exports\ClickExporter;
use App\Models\Click;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClickResource extends Resource
{
    protected static ?string $model = Click::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    
    protected static ?string $navigationLabel = 'Clicks & Analytics';
    
    protected static ?string $modelLabel = 'Click';
    
    protected static ?string $pluralModelLabel = 'Clicks';
    
    protected static ?string $navigationGroup = 'Quản lý';
    
    protected static ?int $navigationSort = 10; // Đặt dưới cùng

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
                    ->limit(30),
                Tables\Columns\TextColumn::make('campaign.brand.name')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('sub_id')
                    ->label('Sub ID')
                    ->searchable()
                    ->toggleable(),
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
                        return Click::query()
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
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                ExportAction::make()
                    ->exporter(ClickExporter::class)
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Xuất dữ liệu'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Xem'),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Sửa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}
