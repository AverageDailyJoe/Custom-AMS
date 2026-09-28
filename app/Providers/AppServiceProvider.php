<?php

namespace App\Providers;

use App\Http\Responses\CustomLogoutResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, CustomLogoutResponse::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Force HTTPS & Server HTTPS flag - app berjalan di balik Nginx / Cloudflare reverse proxy
        URL::forceScheme('https');
        request()->server->set('HTTPS', 'on');
    }
}
