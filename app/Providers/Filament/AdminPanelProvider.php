<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RememberAdminIpMiddleware;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('web')
            ->authMiddleware([Authenticate::class])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->darkMode(true, isForced: true)
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'primary' => Color::hex('#0d6efd'),
                'gray' => Color::Zinc,
            ])
            ->font('Inter')
            ->brandName(config('app.name'))
            ->sidebarWidth('14rem')
            ->favicon(asset('favicon.svg'))
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->navigationGroups([
                NavigationGroup::make()->label('Admin'),
                NavigationGroup::make()->label('Quản lý'),
                NavigationGroup::make()->label('Thống Kê'),
                NavigationGroup::make()->label('Tool'),
                NavigationGroup::make()->label('Cài đặt'),
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                RememberAdminIpMiddleware::class,
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn () => '<link rel="stylesheet" href="' . asset('css/filament/filament/admin-theme.css') . '">'
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn () => '<style>:root { --admin-bg: lab(2.51107% 0.242703 -0.886115); } body { background-color: var(--admin-bg) !important; }</style>'
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('components.filament-rich-editor-hide-attachment-caption')
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn () => view('components.import-notification-trigger')
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn () => view('components.rich-editor-paste-normalize')
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_BEFORE,
                fn () => view('components.chart-js-script')
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn () => view('components.admin-error-toast')
            );
    }
}
