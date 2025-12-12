<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class Verify extends Component
{
    public $showModal = false;
    
    public $userId;
    public $phone_number;
    public $selectedUser;
    
    // OTP fields
    public $otp;
    public $otpSent = false;

    protected $listeners = ['openVerifyModal'];

    public function openVerifyModal($userId)
    {
        $this->resetForm();
        
        $this->selectedUser = User::findOrFail($userId);
        $this->userId = $userId;
        $this->phone_number = $this->selectedUser->phone_number;
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'phone_number',
            'selectedUser',
            'otp',
            'otpSent',
        ]);
        $this->resetErrorBag();
    }

    public function sendOtp()
    {
        $this->resetErrorBag(['otp']);

        if (empty($this->phone_number)) {
            $this->addError('phone_number', 'Nomor HP tidak ditemukan.');
            return;
        }

        $otp = random_int(100000, 999999);

        session([
            'admin_otp_code' => $otp,
            'admin_otp_phone' => $this->phone_number,
            'admin_otp_time' => time(),
            'admin_otp_user_id' => $this->userId,
        ]);

        try {
            $message = "Kode OTP untuk verifikasi akun adalah: {$otp}\n\nJangan bagikan kode ini ke siapapun.\n\nKode akan kadaluarsa dalam 5 menit.";
            $response = FonnteService::sendMessage($this->phone_number, $message);

            Log::info('Admin Verify OTP Fonnte Response', ['response' => $response]);

            $isSuccess = $this->checkFonnteResponse($response);

            if ($isSuccess) {
                session()->flash('otp_success', 'OTP berhasil dikirim ke WhatsApp ' . $this->phone_number);
                $this->otpSent = true;
                $this->resetErrorBag(['otp']);
            } else {
                $errorMsg = $response['reason'] ?? $response['error'] ?? $response['message'] ?? 'Unknown error';
                $this->addError('otp', 'Gagal mengirim OTP: ' . $errorMsg);
                $this->otpSent = false;
            }

        } catch (\Exception $e) {
            Log::error("Error Verify OTP: " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $this->phone_number,
            ]);
            
            if (config('app.debug')) {
                $this->addError('otp', 'Error OTP: ' . $e->getMessage());
            } else {
                $this->addError('otp', 'Terjadi error saat mengirim OTP. Silakan coba lagi.');
            }
            $this->otpSent = false;
        }
    }

    private function checkFonnteResponse($response)
    {
        if (is_array($response)) {
            if (isset($response['status']) && $response['status'] === true) {
                return true;
            }
            if (isset($response['status']) && strtolower($response['status']) === 'success') {
                return true;
            }
            if (!isset($response['error']) && !isset($response['reason'])) {
                return true;
            }
            if (isset($response['detail']) && stripos($response['detail'], 'success') !== false) {
                return true;
            }
        } 
        if (is_string($response) && (strtolower($response) === 'ok' || stripos($response, 'success') !== false)) {
            return true;
        }
        
        return false;
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!session('admin_otp_code')) {
            $this->addError('otp', 'Silakan kirim OTP terlebih dahulu.');
            return;
        }

        if (session('admin_otp_phone') !== $this->phone_number) {
            $this->addError('otp', 'Nomor HP tidak sesuai dengan yang digunakan untuk OTP.');
            return;
        }

        if (time() - session('admin_otp_time', 0) > 300) {
            session()->forget(['admin_otp_code', 'admin_otp_phone', 'admin_otp_time', 'admin_otp_user_id']);
            $this->addError('otp', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
            $this->otpSent = false;
            return;
        }

        if ($this->otp != session('admin_otp_code')) {
            $this->addError('otp', 'Kode OTP salah.');
            return;
        }

        $user = User::find($this->userId);
        
        if ($user) {
            $user->update([
                'is_verified' => true,
                'phone_verified_at' => now(),
            ]);

            session()->forget(['admin_otp_code', 'admin_otp_phone', 'admin_otp_time', 'admin_otp_user_id']);
            
            $this->dispatch('userVerified');
            $this->closeModal();
        } else {
            $this->addError('otp', 'User tidak ditemukan.');
        }
    }

    public function manualVerify()
    {
        $user = User::findOrFail($this->userId);
        
        $user->update([
            'is_verified' => true,
            'phone_verified_at' => now(),
        ]);

        $this->dispatch('userVerified');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.users.verify');
    }
}