<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterExtra extends Component
{
    public $email;
    public $password;
    public $password_confirmation;

    public function register()
    {
        $phone = session('phone');

        if (!$phone) {
            return redirect()->route('register-phone')
                ->withErrors(['phone' => 'Nomor WhatsApp tidak ditemukan, silakan ulangi proses.']);
        }

        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('phone', $phone)->first();

        if ($user) {
            // Update user existing
            $user->update([
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_verified' => true, // pastikan user jadi verified
            ]);
        } else {
            // Fallback: buat user baru
            $user = User::create([
                'phone' => $phone,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_verified' => true,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register-extra');
    }
}
