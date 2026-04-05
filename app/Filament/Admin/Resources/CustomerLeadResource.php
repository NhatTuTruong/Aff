<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerLeadResource\Pages;
use App\Models\CustomerLead;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use App\Models\Campaign;

class CustomerLeadResource extends Resource
{
    protected static ?string $model = CustomerLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Khách hàng';

    protected static ?string $modelLabel = 'Khách hàng';

    protected static ?string $pluralModelLabel = 'Khách hàng';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('campaign.title')
                    ->label('Chiến dịch')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('campaign.brand.domain')
                    ->label('Domain')
                    ->searchable()
                    ->limit(28),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User sở hữu')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->label('Quốc gia')
                    ->badge()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('campaign_id')
                    ->label('Chiến dịch')
                    ->options(function (): array {
                        $user = Filament::auth()->user();
                        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

                        $q = Campaign::query()->with('brand')->orderBy('title');
                        if (! $isAdmin && $user) {
                            $q->whereHas('brand', fn (Builder $b) => $b->where('user_id', $user->id));
                        }

                        return $q->pluck('title', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        return $value ? $query->where('campaign_id', $value) : $query;
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->label('Khoảng thời gian')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Từ ngày'),
                        Forms\Components\DatePicker::make('to')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['to'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('country')
                    ->label('Quốc gia')
                    ->options(function (): array {
                        $user = Filament::auth()->user();
                        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

                        $q = CustomerLead::query();
                        if (! $isAdmin && $user) {
                            $q->whereHas('campaign.brand', fn (Builder $b) => $b->where('user_id', $user->id));
                        }

                        return $q->whereNotNull('country')
                            ->where('country', '!=', '')
                            ->distinct()
                            ->pluck('country', 'country')
                            ->sort()
                            ->toArray();
                    })
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['campaign.brand', 'user']);

        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        if (! $isAdmin && $user) {
            $query->whereHas('campaign.brand', fn (Builder $q) => $q->where('user_id', $user->id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerLeads::route('/'),
        ];
    }
}

