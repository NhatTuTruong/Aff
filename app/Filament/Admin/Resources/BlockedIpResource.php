<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BlockedIpResource\Pages;
use App\Filament\Admin\Resources\BlockedIpResource\RelationManagers;
use App\Models\BlockedIp;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlockedIpResource extends Resource
{
    protected static ?string $model = BlockedIp::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationLabel = 'Chặn IP truy cập';
    protected static ?string $modelLabel = 'Chặn IP truy cập';
    protected static ?string $pluralModelLabel = 'Chặn IP truy cập';
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
                Forms\Components\Toggle::make('block_public')
                    ->label('Chặn hẳn truy cập trang public')
                    ->helperText('Bật: IP không thể truy cập trang public, chỉ còn truy cập /admin')
                    ->default(false)
                    ->visible(fn () => static::userIsAdmin()),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->visible(fn () => static::userIsAdmin())
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Lý do')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('block_public')
                    ->label('Chặn public')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->visible(fn () => static::userIsAdmin()),
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
                Tables\Actions\Action::make('toggleBlockPublic')
                    ->visible(fn () => static::userIsAdmin())
                    ->label('')
                    ->icon(fn (BlockedIp $record): string => $record->block_public ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                    ->color(fn (BlockedIp $record): string => $record->block_public ? 'danger' : 'gray')
                    ->tooltip(fn (BlockedIp $record): string => $record->block_public ? 'Đang chặn public (bấm để bỏ)' : 'Chặn hẳn public')
                    ->requiresConfirmation()
                    ->modalHeading(fn (BlockedIp $record): string => $record->block_public ? 'Bỏ chặn truy cập public?' : 'Chặn hẳn IP truy cập trang public?')
                    ->modalDescription(fn (BlockedIp $record): string => $record->block_public
                        ? 'IP có thể truy cập trang public nhưng vẫn không được thống kê.'
                        : 'IP sẽ không thể truy cập trang public, chỉ còn truy cập /admin.')
                    ->action(function (BlockedIp $record): void {
                        $record->update(['block_public' => ! $record->block_public]);
                    }),
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

    public static function getEloquentQuery(): Builder
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        return parent::getEloquentQuery()
            ->forUser($user?->id, $isAdmin);
    }

    protected static function userIsAdmin(): bool
    {
        $user = Filament::auth()->user();

        return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
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
