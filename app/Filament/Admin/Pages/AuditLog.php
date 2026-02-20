<?php

namespace App\Filament\Admin\Pages;

use App\Models\ActivityLog;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string $view = 'filament.admin.pages.audit-log';

    protected static ?string $navigationLabel = 'Nhật ký hoạt động';

    protected static ?string $title = 'Nhật ký hoạt động (Audit Log)';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 10;

    public function table(Table $table): Table
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && $user->isAdmin();

        return $table
            ->query(
                ActivityLog::query()
                    ->when(! $isAdmin, fn (Builder $q) => $q->where('causer_type', User::class)->where('causer_id', $user?->id))
                    ->with(['causer', 'subject'])
                    ->orderByDesc('created_at')
            )
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Hành động')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('causer.name')
                    ->label('User')
                    ->formatStateUsing(fn ($record) => $record->causer?->name ?? $record->causer?->email ?? '-')
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Loại')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'created' => 'Tạo mới',
                        'updated' => 'Cập nhật',
                        'deleted' => 'Xóa',
                        'login' => 'Đăng nhập',
                        'import' => 'Import',
                        default => $state ?? '-',
                    }),
                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '-'),
                TextColumn::make('subject_id')
                    ->label('ID'),
            ])
            ->filters([
                SelectFilter::make('causer_id')
                    ->label('User')
                    ->options(fn () => $isAdmin ? User::orderBy('name')->pluck('name', 'id') : [])
                    ->query(function (Builder $query, array $data) use ($isAdmin): Builder {
                        if (! $isAdmin || empty($data['value'])) {
                            return $query;
                        }
                        return $query->where('causer_type', User::class)->where('causer_id', $data['value']);
                    })
                    ->visible($isAdmin),
                SelectFilter::make('event')
                    ->label('Loại')
                    ->options([
                        'created' => 'Tạo mới',
                        'updated' => 'Cập nhật',
                        'deleted' => 'Xóa',
                        'login' => 'Đăng nhập',
                        'import' => 'Import',
                    ]),
            ]);
    }

    protected function getTableRelations(): array
    {
        return [];
    }
}
