<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function mount(): void
    {
        if (! Filament::getCurrentPanel()) {
            Filament::setCurrentPanel(Filament::getPanel('admin'));
        }

        parent::mount();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        $email = trim($data['email'] ?? '');

        // 1. Cek ketersediaan email di database (case-insensitive)
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'data.email' => 'Email belum terdaftar',
            ]);
        }

        // 2. Cek password (attempt login)
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.password' => 'Password yang Anda masukkan salah. Silakan periksa kembali.',
            ]);
        }

        session()->regenerate();

        return app(LoginResponse::class);
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
