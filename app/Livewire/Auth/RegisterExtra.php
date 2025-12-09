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
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class RegisterExtra extends Component
{
    public $full_name;
    public $phone_number;
    public $email;
    public $password;
    public $password_confirmation;
    public $otp;
    public $otpSent = false;

    // Email verification properties
    public $email_verification_code;
    public $emailVerificationSent = false;
    public $step = 1; // 1: phone OTP, 2: email verification, 3: complete registration

    public function mount()
    {
        $this->otpSent = false;
        $this->emailVerificationSent = false;
        $this->step = 1;
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

            $isSuccess = false;
            
            if (is_array($response)) {
                if (isset($response['status']) && $response['status'] === true) {
                    $isSuccess = true;
                }
                elseif (isset($response['status']) && strtolower($response['status']) === 'success') {
                    $isSuccess = true;
                }
                elseif (!isset($response['error']) && !isset($response['reason'])) {
                    $isSuccess = true;
                }
                elseif (isset($response['detail']) && stripos($response['detail'], 'success') !== false) {
                    $isSuccess = true;
                }
            } 
            elseif (is_string($response) && (strtolower($response) === 'ok' || stripos($response, 'success') !== false)) {
                $isSuccess = true;
            }

            if ($isSuccess) {
                session()->flash('success', 'OTP berhasil dikirim ke WhatsApp ' . $this->phone_number);
                $this->otpSent = true;
                $this->resetErrorBag(['otp']);
                $this->dispatch('otp-sent');
            } else {
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
            
            if (config('app.debug')) {
                $this->addError('otp', 'Error OTP: ' . $e->getMessage());
            } else {
                $this->addError('otp', 'Terjadi error saat mengirim OTP. Silakan coba lagi.');
            }
            $this->otpSent = false;
        }
    }

    /**
     * Kirim kode verifikasi email
     */
    public function sendEmailVerification()
    {
        $this->resetErrorBag(['email', 'email_verification_code']);

        // Validate email first
        $this->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in session
        session([
            'email_verification_code' => $verificationCode,
            'email_verification_email' => $this->email,
            'email_verification_time' => time(),
        ]);

        try {
            // Send email
            Mail::to($this->email)->send(new EmailVerificationMail($verificationCode, $this->full_name ?? 'User'));

            session()->flash('success', 'Kode verifikasi telah dikirim ke email ' . $this->email);
            $this->emailVerificationSent = true;
            $this->step = 2;
            $this->resetErrorBag(['email_verification_code']);
            $this->dispatch('email-verification-sent');

        } catch (\Exception $e) {
            Log::error("Error sending email verification: " . $e->getMessage(), [
                'exception' => $e,
                'email' => $this->email,
                'trace' => $e->getTraceAsString()
            ]);

            if (config('app.debug')) {
                $this->addError('email', 'Error mengirim email: ' . $e->getMessage());
            } else {
                $this->addError('email', 'Terjadi error saat mengirim email verifikasi. Silakan coba lagi.');
            }
            $this->emailVerificationSent = false;
        }
    }

    /**
     * Verifikasi kode email
     */
    public function verifyEmail()
    {
        $this->resetErrorBag(['email_verification_code']);

        $this->validate([
            'email_verification_code' => 'required|digits:6',
        ]);

        if (!session('email_verification_code')) {
            $this->addError('email_verification_code', 'Silakan kirim kode verifikasi terlebih dahulu.');
            return;
        }

        if (session('email_verification_email') !== $this->email) {
            $this->addError('email_verification_code', 'Email tidak sesuai dengan yang digunakan untuk verifikasi.');
            return;
        }

        // Check if code expired (10 minutes)
        if (time() - session('email_verification_time', 0) > 600) {
            session()->forget(['email_verification_code', 'email_verification_email', 'email_verification_time']);
            $this->addError('email_verification_code', 'Kode verifikasi sudah kadaluarsa. Silakan kirim ulang.');
            $this->emailVerificationSent = false;
            $this->step = 1;
            return;
        }

        if ($this->email_verification_code != session('email_verification_code')) {
            $this->addError('email_verification_code', 'Kode verifikasi salah.');
            return;
        }

        // Email verified, move to final step
        $this->step = 3;
        session()->flash('success', 'Email berhasil diverifikasi!');
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
                'company_id' => null,
                'role_id' => $role->role_id,
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_verified' => true,
                'phone_verified_at' => now(),
                'email_verified_at' => $this->email ? now() : null,
            ]);

            session()->forget(['otp_code', 'otp_phone', 'otp_time', 'email_verification_code', 'email_verification_email', 'email_verification_time']);

            Auth::login($user);

            session()->flash('success', 'Registrasi berhasil! Selamat datang.');
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), ['exception' => $e]);
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