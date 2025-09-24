<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login')]

class Login extends Component
{
    public function render()
    {
        return view('livewire.auth.login');
    }
}
