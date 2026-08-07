<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

class CustomLogin extends BaseLogin
{
    public function mount(): void
    {
        if (! Filament::getCurrentPanel()) {
            Filament::setCurrentPanel(Filament::getPanel('admin'));
        }

        parent::mount();
    }
    protected function getRedirectUrl(): string
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return Filament::getPanel('admin')->getUrl();
        }

        return Filament::getPanel('user')->getUrl();
    }
}
