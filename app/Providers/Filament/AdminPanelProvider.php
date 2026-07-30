<?php

namespace App\Providers\Filament;

use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $middleware = [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];

        if (!app()->environment(['local', 'testing'])) {
            $middleware = array_merge($middleware, [PreventRequestForgery::class]);
        
FilamentView::registerRenderHook(
    PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
    fn (): string => view('auth.login-links')->render()
);
}


        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('AMS')
            ->brandLogo(fn () => asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(fn () => asset('images/logo.png'))
            ->colors([
                'primary' => Color::hex('#0d630d'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverviewWidget::class,
                \App\Filament\Widgets\TicketChartWidget::class,
                \App\Filament\Widgets\DailyTicketChartWidget::class,
                \App\Filament\Widgets\LatestTicketsWidget::class,
                \App\Filament\Widgets\RecentAssetActivityWidget::class,
                \App\Filament\Widgets\MaintenanceScheduleWidget::class,
            ])
            ->middleware($middleware)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
