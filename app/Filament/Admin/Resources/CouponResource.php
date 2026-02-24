<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CouponResource\Pages;
use App\Models\Coupon;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Mã giảm giá';

    protected static ?string $modelLabel = 'Mã giảm giá';

    protected static ?string $pluralModelLabel = 'Mã giảm giá';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 4;

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('campaign_id')
                    ->label('Chiến dịch')
                    ->relationship(
                        'campaign',
                        'title',
                        modifyQueryUsing: function (Builder $query) {
                            $user = Filament::auth()->user();
                            $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
                            $userId = $isAdmin ? null : ($user?->id);

                            return $query->when(
                                $userId,
                                fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('user_id', $userId)),
                            );
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Mã giảm giá')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('offer')
                    ->label('Offer')
                    ->maxLength(50),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(3)
                    ->maxLength(500),
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
                    ->limit(25),
                Tables\Columns\TextColumn::make('campaign.brand.name')
                    ->label('Cửa hàng')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã giảm giá')
                    ->searchable(),
                Tables\Columns\TextColumn::make('offer')
                    ->label('Offer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->limit(25)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->visible(fn (): bool => (bool) (Filament::auth()->user()?->isAdmin()))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $userId): Builder => $q->whereHas('campaign.brand', fn (Builder $b) => $b->where('user_id', $userId)),
                        );
                    }),
                Tables\Filters\SelectFilter::make('campaign_id')
                    ->label('Chiến dịch')
                    ->relationship(
                        'campaign',
                        'title',
                        modifyQueryUsing: function (Builder $query) {
                            $user = Filament::auth()->user();
                            $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
                            $userId = $isAdmin ? null : ($user?->id);

                            return $query->when(
                                $userId,
                                fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('user_id', $userId)),
                            );
                        }
                    )
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at_range')
                    ->label('Ngày tạo')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $userId = $isAdmin ? null : ($user?->id);

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


