<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class VerifyOtp extends Component
{
    public $otp;

    public function verify()
    {
        $this->validate([
            'otp' => 'required|numeric',
        ]);

        if ($this->otp == session('otp')) {
            $user = User::where('phone', session('phone'))->first();
            if ($user) {
                $user->update(['is_verified' => true]);
            }

            return redirect()->route('register-extra');
        } else {
            $this->addError('otp', 'OTP salah, coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-otp');
    }
}
