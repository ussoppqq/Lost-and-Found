<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            session()->regenerate();

            $user = Auth::user();

            // Cek role user
            if ($user->role && $user->role->role_code === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            }

            // default redirect user biasa
            return redirect()->route('/');
        }

        // Kalau gagal login
        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth'); // layout khusus auth
    }
}
