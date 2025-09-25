<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class RegisterExtra extends Component
{
    public $full_name;
    public $phone_number;
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
        $this->resetErrorBag(['phone_number', 'otp']);

        if (empty($this->phone_number)) {
            $this->addError('phone_number', 'Nomor HP harus diisi sebelum mengirim OTP.');
            return;
        }

        if (!preg_match('/^62[0-9]{9,13}$/', $this->phone_number)) {
            $this->addError('phone_number', 'Nomor HP harus diawali 62 dan minimal 10 digit.');
            return;
        }

        $otp = random_int(100000, 999999);

        session([
            'otp_code' => $otp,
            'otp_phone' => $this->phone_number,
            'otp_time' => time(),
        ]);

        try {
            $message = "Kode OTP kamu adalah: {$otp}\n\nJangan bagikan kode ini ke siapapun.\n\nKode akan kadaluarsa dalam 5 menit.";
            $response = FonnteService::sendMessage($this->phone_number, $message);

            Log::info('Fonnte Response', ['response' => $response]);

            // Perbaikan: Cek berbagai kemungkinan format response yang sukses
            $isSuccess = false;
            
            if (is_array($response)) {
                // Format 1: response dengan status boolean true
                if (isset($response['status']) && $response['status'] === true) {
                    $isSuccess = true;
                }
                // Format 2: response dengan status string "success" 
                elseif (isset($response['status']) && strtolower($response['status']) === 'success') {
                    $isSuccess = true;
                }
                // Format 3: response tanpa error atau dengan detail yang menunjukkan sukses
                elseif (!isset($response['error']) && !isset($response['reason'])) {
                    $isSuccess = true;
                }
                // Format 4: response dengan key lain yang menunjukkan sukses
                elseif (isset($response['detail']) && stripos($response['detail'], 'success') !== false) {
                    $isSuccess = true;
                }
            } 
            // Jika response bukan array tapi berhasil (misalnya string "OK" atau response lain)
            elseif (is_string($response) && (strtolower($response) === 'ok' || stripos($response, 'success') !== false)) {
                $isSuccess = true;
            }

            if ($isSuccess) {
                session()->flash('success', 'OTP berhasil dikirim ke WhatsApp ' . $this->phone_number);
                $this->otpSent = true;
                $this->resetErrorBag(['otp']);
                
                // Perbaikan: Gunakan dispatch untuk Livewire 3
                $this->dispatch('otp-sent');
            } else {
                // Ambil pesan error dari berbagai kemungkinan key
                $errorMsg = $response['reason'] ?? $response['error'] ?? $response['message'] ?? 'Unknown error';
                $this->addError('otp', 'Gagal mengirim OTP: ' . $errorMsg);
                $this->otpSent = false;
            }

        } catch (\Exception $e) {
            Log::error("Error OTP: " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $this->phone_number,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Jika dalam debug mode, tampilkan error yang lebih detail
            if (config('app.debug')) {
                $this->addError('otp', 'Error OTP: ' . $e->getMessage());
            } else {
                $this->addError('otp', 'Terjadi error saat mengirim OTP. Silakan coba lagi.');
            }
            $this->otpSent = false;
        }
    }

    /**
     * Register user setelah OTP benar
     */
    public function register()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^62[0-9]{9,13}$/|unique:users,phone_number',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'otp' => 'required|digits:6',
        ]);

        if (!session('otp_code')) {
            $this->addError('otp', 'Silakan kirim OTP terlebih dahulu.');
            return;
        }

        if (session('otp_phone') !== $this->phone_number) {
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
            // Cari role user (cari by role_code atau role_name, fleksibel)
            $role = Role::where('role_code', 'USER')
                ->orWhere('role_name', 'User')
                ->first();

            if (!$role) {
                Log::error('Role "User" tidak ditemukan saat registrasi.');
                $this->addError('email', 'Role "User" belum terdaftar. Hubungi administrator.');
                return;
            }

            $user = User::create([
                'user_id' => (string) Str::uuid(),
                'company_id' => null,                     // user biasa -> null
                'role_id' => $role->role_id,
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_verified' => true,
            ]);

            session()->forget(['otp_code', 'otp_phone', 'otp_time']);

            Auth::login($user);

            session()->flash('success', 'Registrasi berhasil! Selamat datang.');
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), ['exception' => $e]);
            // kalau environment development, tampilkan pesan detil supaya mudah debug
            if (config('app.debug')) {
                $this->addError('email', 'Registration error: ' . $e->getMessage());
            } else {
                $this->addError('email', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.register-extra')
            ->layout('components.layouts.auth', [
                'title' => 'Register Extra'
            ]);
    }
}