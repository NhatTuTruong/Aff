<?php

namespace App\Filament\Admin\Pages;

use App\Models\LandingPageCheck;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;

class LandingHealth extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'filament.admin.pages.landing-health';

    protected static ?string $navigationLabel = 'Kiểm tra Landing/Coupon';

    protected static ?string $title = 'Kiểm tra lỗi trang Landing/Coupon';

    protected static ?string $navigationGroup = 'Thống Kê';

    protected static ?int $navigationSort = 4;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runCheck')
                ->label('Chạy kiểm tra')
                ->icon('heroicon-o-play')
                ->action(function (): void {
                    $user = Filament::auth()->user();
                    $isAdmin = $user instanceof User && $user->isAdmin();

                    // For UI-triggered runs, keep it fast: check limited records.
                    $args = ['--limit' => 80, '--only-errors' => true];
                    if (! $isAdmin) {
                        $args['--user'] = $user?->id;
                    }

                    Artisan::call('health:check-landing', $args);

                    Notification::make()
                        ->title('Đã chạy kiểm tra landing/coupon')
                        ->body('Trang sẽ hiển thị các chiến dịch đang gặp lỗi (khác 200).')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $user = Filament::auth()->user();
        $isAdmin = $user instanceof User && $user->isAdmin();

        return $table
            ->query(
                LandingPageCheck::query()
                    ->with(['campaign.brand'])
                    ->when(! $isAdmin, fn (Builder $q) => $q->where('user_id', $user?->id))
                    ->where('status_code', '!=', 200)
                    ->orderByDesc('checked_at')
            )
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('checked_at')
                    ->label('Lần check')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('campaign.title')
                    ->label('Chiến dịch')
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('campaign', fn (Builder $q) => $q->where('title', 'like', "%{$search}%"));
                    }),
                TextColumn::make('campaign.brand.name')
                    ->label('Brand')
                    ->toggleable(),
                TextColumn::make('url_path')
                    ->label('URL')
                    ->copyable()
                    ->copyMessage('Đã copy')
                    ->wrap(),
                BadgeColumn::make('status_code')
                    ->label('Status')
                    ->colors([
                        'success' => 200,
                        'warning' => [301, 302, 401, 403, 429],
                        'danger' => [0, 404, 410, 500, 502, 503, 504],
                    ]),
                TextColumn::make('error')
                    ->label('Lỗi')
                    ->placeholder('—')
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('status_code')
                    ->label('Status code')
                    ->options([
                        0 => 'Invalid slug',
                        403 => '403',
                        404 => '404',
                        500 => '500',
                        503 => '503',
                    ]),
            ])
            ->actions([
                TableAction::make('open')
                    ->label('Mở')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (LandingPageCheck $record) => url($record->url_path))
                    ->openUrlInNewTab(),
            ]);
    }
}

