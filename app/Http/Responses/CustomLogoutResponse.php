<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect('/gondowangi/login');
    }
}
