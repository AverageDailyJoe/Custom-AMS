<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public string $message = '';

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.app');
    }

    public function submit(): void
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->redirect('/admin');
            return;
        }

        $this->message = 'Invalid credentials.';
    }
}
