<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    public $showModal = false;
    
    // Form fields
    public $full_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;
    public $role_id;
    public $skip_otp_verification = false;
    
    // OTP fields
    public $otp;
    public $otpSent = false;
    public $pendingUserId; // Store as string

    protected $listeners = ['openCreateModal'];

    protected function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone_number' => 'required|string|regex:/^62[0-9]{9,13}$/|unique:users,phone_number',
            'role_id' => 'required|exists:roles,role_id',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
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
            'full_name',
            'email',
            'phone_number',
            'password',
            'password_confirmation',
            'role_id',
            'skip_otp_verification',
            'otp',
            'otpSent',
            'pendingUserId',
        ]);
        $this->resetErrorBag();
    }

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
            'admin_otp_code' => $otp,
            'admin_otp_phone' => $this->phone_number,
            'admin_otp_time' => time(),
            'admin_otp_user_id' => $this->pendingUserId,
        ]);

        try {
            $message = "Kode OTP untuk verifikasi akun adalah: {$otp}\n\nJangan bagikan kode ini ke siapapun.\n\nKode akan kadaluarsa dalam 5 menit.";
            $response = FonnteService::sendMessage($this->phone_number, $message);

            Log::info('Admin OTP Fonnte Response', ['response' => $response]);

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
            Log::error("Error Admin OTP: " . $e->getMessage(), [
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

        $userId = session('admin_otp_user_id');
        $user = User::find($userId);
        
        if ($user) {
            $user->update([
                'is_verified' => true,
                'phone_verified_at' => now(),
            ]);

            session()->forget(['admin_otp_code', 'admin_otp_phone', 'admin_otp_time', 'admin_otp_user_id']);
            session()->flash('success', 'User berhasil dibuat dan diverifikasi!');
            
            $this->dispatch('userCreated');
            $this->closeModal();
        } else {
            $this->addError('otp', 'User tidak ditemukan.');
        }
    }

    public function save()
    {
        $this->validate();

        $companyId = auth()->user()->company_id;

        if ($this->skip_otp_verification) {
            User::create([
                'user_id' => Str::uuid(),
                'company_id' => $companyId,
                'role_id' => $this->role_id,
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'password' => Hash::make($this->password),
                'is_verified' => true,
                'phone_verified_at' => now(),
            ]);

            session()->flash('success', 'User berhasil dibuat tanpa verifikasi OTP!');
            $this->dispatch('userCreated');
            $this->closeModal();
            return;
        }

        // Create user with is_verified = false
        $user = User::create([
            'user_id' => Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $this->role_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => Hash::make($this->password),
            'is_verified' => false,
        ]);

        // Store user_id as string
        $this->pendingUserId = (string) $user->user_id;
        
        // Automatically send OTP
        $this->sendOtp();
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.admin.users.create', compact('roles'));
    }
}