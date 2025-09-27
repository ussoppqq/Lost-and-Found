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

        // Admin → dashboard admin
        if ($user->role && $user->role->role_code === 'ADMIN') {
            return redirect()->route('dashboard');
        }

        // Moderator → dashboard moderator
        if ($user->role && $user->role->role_code === 'MODERATOR') {
            return redirect()->route('moderator');
        }

        // User biasa → home
        if ($user->role && $user->role->role_code === 'USER') {
            return redirect()->route('home');
        }

        // fallback kalau role tidak dikenali
        return redirect()->route('home');
    }

    $this->addError('email', 'Email atau password salah.');
}
    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth'); 
    }
}