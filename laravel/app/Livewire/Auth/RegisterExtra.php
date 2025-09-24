<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class RegisterExtra extends Component
{
    public $phone;
    public $email;
    public $password;
    public $password_confirmation;
    public $otp;
    public $otpSent = false;

    public function mount()
    {
        $this->otpSent = false;
    }

    /**
     * Kirim OTP ke WhatsApp user via Fonnte API
     */
    public function sendOtp()
    {
        $this->resetErrorBag(['phone', 'otp']);

        if (empty($this->phone)) {
            $this->addError('phone', 'Nomor HP harus diisi sebelum mengirim OTP.');
            return;
        }

        if (!preg_match('/^62[0-9]{9,13}$/', $this->phone)) {
            $this->addError('phone', 'Nomor HP harus diawali 62 dan minimal 10 digit.');
            return;
        }

        $otp = rand(100000, 999999);

        session([
            'otp_code' => $otp,
            'otp_phone' => $this->phone,
            'otp_time' => time()
        ]);

        try {
            $message = "Kode OTP kamu adalah: {$otp}\n\nJangan bagikan kode ini ke siapapun.\n\nKode akan kadaluarsa dalam 5 menit.";
            $response = FonnteService::sendMessage($this->phone, $message);

            Log::info('Fonnte Response:', $response);

            if (isset($response['status']) && $response['status'] === true) {
                session()->flash('success', 'OTP berhasil dikirim ke WhatsApp ' . $this->phone);
                $this->dispatch('otp-sent');
            } else {
                $errorMsg = $response['reason'] ?? 'Unknown error';
                Log::error("Gagal kirim OTP ke {$this->phone}: " . json_encode($response));
                $this->addError('otp', 'Gagal mengirim OTP: ' . $errorMsg);
            }
        } catch (\Exception $e) {
            Log::error("Error OTP: " . $e->getMessage());
            $this->addError('otp', 'Terjadi error saat mengirim OTP: ' . $e->getMessage());
            $this->otpSent = false;
        }
    }

    /**
     * Register user setelah OTP benar
     */
    public function register()
    {
        $this->validate([
            'phone' => 'required|string|regex:/^62[0-9]{9,13}$/|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'otp' => 'required|digits:6'
        ]);

        if (!session('otp_code')) {
            $this->addError('otp', 'Silakan kirim OTP terlebih dahulu.');
            return;
        }

        if (session('otp_phone') !== $this->phone) {
            $this->addError('otp', 'Nomor HP tidak sesuai dengan yang digunakan untuk OTP.');
            return;
        }

        if (time() - session('otp_time', 0) > 300) {
            session()->forget(['otp_code', 'otp_phone', 'otp_time']);
            $this->addError('otp', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
            $this->otpSent = false;
            return;
        }

        if ($this->otp != session('otp_code')) {
            $this->addError('otp', 'Kode OTP salah.');
            return;
        }

        try {
            $user = User::create([
                'phone' => $this->phone,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_verified' => true,
            ]);

            session()->forget(['otp_code', 'otp_phone', 'otp_time']);

            Auth::login($user);

            session()->flash('success', 'Registrasi berhasil! Selamat datang.');
            return redirect('/dashboard');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            $this->addError('email', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register-extra')
            ->layout('components.layouts.app', [
                'title' => 'Register Extra'
            ]);
    }
}
