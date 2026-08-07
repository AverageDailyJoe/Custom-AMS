<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserTicketResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
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
        }

        return $panel
            ->id('user')
            ->path('user')
            ->login()
            ->registration()
            ->passwordReset()
            ->brandName('GTK Portal - Service User')
            ->brandLogo(fn () => asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(fn () => asset('images/logo.png'))
            ->colors([
                'primary' => Color::hex('#0d630d'),
            ])
            ->resources([
                UserTicketResource::class,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\UserHelpdeskInfoWidget::class,
                \App\Filament\Widgets\UserStatsOverviewWidget::class,
                \App\Filament\Widgets\UserRecentTicketsWidget::class,
            ])
            ->middleware($middleware)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
