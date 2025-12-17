<?php

namespace App\Livewire\Auth;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public $emailSent = false;

    public $recovery_code;

    public $new_password;

    public $new_password_confirmation;

    public $step = 1; // 1: email input, 2: recovery code, 3: new password

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'We could not find a user with that email address.',
    ];

    public function submit()
    {
        $this->validate();

        try {
            // Find user
            $user = User::where('email', $this->email)->first();

            if (! $user) {
                $this->addError('email', 'We could not find a user with that email address.');

                return;
            }

            // Generate 6-digit recovery code
            $recoveryCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Update user with recovery code
            $user->update([
                'email_verification_code' => $recoveryCode,
                'email_verification_code_expires_at' => now()->addMinutes(10),
            ]);

            // Send email
            Mail::to($this->email)->send(new PasswordResetMail($recoveryCode, $user->full_name));

            $this->emailSent = true;
            $this->step = 2;
            session()->flash('success', 'Recovery code has been sent to your email!');

            Log::info('Password recovery code sent', ['email' => $this->email]);

        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred. Please try again later.');
            Log::error('Password reset error: '.$e->getMessage(), [
                'email' => $this->email,
                'exception' => $e,
            ]);
        }
    }

    public function verifyRecoveryCode()
    {
        $this->resetErrorBag(['recovery_code']);

        $this->validate([
            'recovery_code' => 'required|digits:6',
        ]);

        try {
            $user = User::where('email', $this->email)
                ->where('email_verification_code', $this->recovery_code)
                ->first();

            if (! $user) {
                $this->addError('recovery_code', 'Invalid recovery code.');

                return;
            }

            // Check if code expired
            if ($user->email_verification_code_expires_at < now()) {
                $this->addError('recovery_code', 'Recovery code has expired. Please request a new one.');

                return;
            }

            // Code is valid, move to password reset step
            $this->step = 3;
            session()->flash('success', 'Recovery code verified! Please enter your new password.');

        } catch (\Exception $e) {
            $this->addError('recovery_code', 'An error occurred. Please try again.');
            Log::error('Recovery code verification error: '.$e->getMessage());
        }
    }

    public function resetPassword()
    {
        $this->resetErrorBag(['new_password']);

        $this->validate([
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.required' => 'Password is required.',
            'new_password.min' => 'Password must be at least 6 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        try {
            $user = User::where('email', $this->email)
                ->where('email_verification_code', $this->recovery_code)
                ->first();

            if (! $user) {
                $this->addError('new_password', 'Invalid session. Please start over.');

                return;
            }

            // Update password and clear recovery code
            $user->update([
                'password' => Hash::make($this->new_password),
                'email_verification_code' => null,
                'email_verification_code_expires_at' => null,
            ]);

            session()->flash('success', 'Password has been reset successfully! You can now login with your new password.');

            Log::info('Password reset successful', ['email' => $this->email]);

            // Redirect to login page after 2 seconds
            return redirect()->to('/login');

        } catch (\Exception $e) {
            $this->addError('new_password', 'An error occurred. Please try again.');
            Log::error('Password reset error: '.$e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['email', 'emailSent', 'recovery_code', 'new_password', 'new_password_confirmation', 'step']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.auth', [
                'title' => 'Forgot Password',
            ]);
    }
}
