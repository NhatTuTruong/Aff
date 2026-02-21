<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlockedIpResource\Pages;
use App\Filament\Admin\Resources\BlockedIpResource\RelationManagers;
use App\Models\BlockedIp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlockedIpResource extends Resource
{
    protected static ?string $model = BlockedIp::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationLabel = 'IP bị chặn';
    protected static ?string $modelLabel = 'IP bị chặn';
    protected static ?string $pluralModelLabel = 'IP bị chặn';
    protected static ?string $navigationGroup = 'Thống Kê';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip')
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('reason')
                    ->label('Lý do (tùy chọn)')
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Lý do')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBlockedIps::route('/'),
            'create' => Pages\CreateBlockedIp::route('/create'),
            'view' => Pages\ViewBlockedIp::route('/{record}'),
            'edit' => Pages\EditBlockedIp::route('/{record}/edit'),
        ];
    }
}
