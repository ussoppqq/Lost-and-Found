<?php

namespace App\Livewire;

use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        session()->flash('success', 'Reset link sent!');
    }

    public function render()
    {
        return view('livewire.auth.forgotpassword');
    }
}
