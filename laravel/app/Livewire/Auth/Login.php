<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;     // bisa email / phone_number
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Coba login dengan email
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect('/dashboard');
        }

        // Coba login dengan nomor HP
        if (Auth::attempt(['phone_number' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect('/dashboard');
        }

        $this->addError('email', 'Email/No HP atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth', ['title' => 'Login']);
    }
}
