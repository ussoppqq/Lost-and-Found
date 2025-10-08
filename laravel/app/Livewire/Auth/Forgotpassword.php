<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPassword extends Component
{
    public $email;
    public $emailSent = false;

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
            // Send password reset link
            $status = Password::sendResetLink(
                ['email' => $this->email]
            );

            if ($status === Password::RESET_LINK_SENT) {
                $this->emailSent = true;
                session()->flash('success', 'We have sent a password reset link to your email!');
                
                Log::info('Password reset link sent', ['email' => $this->email]);
                
                // Reset form setelah 3 detik
                $this->dispatch('reset-form-delayed');
            } else {
                $this->addError('email', 'Unable to send reset link. Please try again.');
                Log::error('Failed to send password reset link', ['email' => $this->email, 'status' => $status]);
            }
        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred. Please try again later.');
            Log::error('Password reset error: ' . $e->getMessage(), [
                'email' => $this->email,
                'exception' => $e
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset(['email', 'emailSent']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.auth', [
                'title' => 'Forgot Password'
            ]);
    }
}