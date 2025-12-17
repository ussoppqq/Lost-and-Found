<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPassword extends Component
{
    public $step = 1;
    public $email;
    public $recovery_code;
    public $stored_code;
    public $code_expires_at;
    public $new_password;
    public $new_password_confirmation;

    protected function rules()
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];

        if ($this->step == 2) {
            $rules['recovery_code'] = 'required|digits:6';
        }

        if ($this->step == 3) {
            $rules['new_password'] = 'required|min:8|confirmed';
            $rules['new_password_confirmation'] = 'required';
        }

        return $rules;
    }

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'We could not find a user with that email address.',
        'recovery_code.required' => 'Recovery code is required.',
        'recovery_code.digits' => 'Recovery code must be 6 digits.',
        'new_password.required' => 'Password is required.',
        'new_password.min' => 'Password must be at least 8 characters.',
        'new_password.confirmed' => 'Password confirmation does not match.',
    ];

    public function submit()
    {
        $this->validate(['email' => 'required|email|exists:users,email']);

        // Generate 6-digit recovery code
        $this->stored_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->code_expires_at = now()->addMinutes(10);

        // Send email with recovery code
        try {
            Mail::raw("Your password recovery code is: {$this->stored_code}\n\nThis code will expire in 10 minutes.", function ($message) {
                $message->to($this->email)
                    ->subject('Password Recovery Code - Lost and Found');
            });

            session()->flash('success', 'Recovery code has been sent to your email.');
            $this->step = 2;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send recovery code. Please try again.');
        }
    }

    public function verifyRecoveryCode()
    {
        $this->validate(['recovery_code' => 'required|digits:6']);

        // Check if code expired
        if (now()->greaterThan($this->code_expires_at)) {
            session()->flash('error', 'Recovery code has expired. Please request a new one.');
            $this->resetForm();
            return;
        }

        // Verify code
        if ($this->recovery_code !== $this->stored_code) {
            $this->addError('recovery_code', 'Invalid recovery code. Please try again.');
            return;
        }

        session()->flash('success', 'Code verified! Please enter your new password.');
        $this->step = 3;
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        // Find user and update password
        $user = User::where('email', $this->email)->first();

        if ($user) {
            $user->password = Hash::make($this->new_password);
            $user->setRememberToken(Str::random(60));
            $user->save();

            session()->flash('success', 'Your password has been reset successfully!');

            // Dispatch event to reset form after delay
            $this->dispatch('reset-form-delayed');

            // Redirect to login after a short delay
            return redirect()->route('login')->with('success', 'Password reset successful! Please login with your new password.');
        }

        session()->flash('error', 'Failed to reset password. Please try again.');
    }

    public function resetForm()
    {
        $this->reset(['step', 'email', 'recovery_code', 'stored_code', 'code_expires_at', 'new_password', 'new_password_confirmation']);
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.auth.forgot-Password')
            ->layout('components.layouts.auth', [
                'title' => 'Forgot Password'
            ]);
    }
}
