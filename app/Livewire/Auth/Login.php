<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->resetErrorBag();

        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $this->remember)) {

            logger()->warning('Login failed', [
                'email' => $this->email,
            ]);

            $this->addError('email', 'Неверный email или пароль.');

            return;
        }

        request()->session()->regenerate();

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}