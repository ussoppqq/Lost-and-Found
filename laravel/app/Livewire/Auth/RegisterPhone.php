<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class RegisterPhone extends Component
{
    public $phone;

    public function submit()
    {
        $this->validate([
            'phone' => 'required|string|min:10|max:15|unique:users,phone',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan OTP ke session (bisa juga pakai tabel OTP khusus)
        session([
            'otp' => $otp,
            'phone' => $this->phone,
        ]);

        // Buat user sementara kalau belum ada
        User::firstOrCreate(['phone' => $this->phone]);

        // Kirim OTP ke WhatsApp (contoh placeholder)
        // WhatsAppService::sendOtp($this->phone, $otp);

        return redirect()->route('verify-otp');
    }

    public function render()
    {
        return view('livewire.auth.register-phone');
    }
}
