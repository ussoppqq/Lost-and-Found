<?php

namespace App\Livewire;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Forgot Password')]

class Forgotpassword extends Component
{
    public function render()
    {
        return view('livewire.auth.forgotpassword')
            ->layout('components.layouts.app', ['title' => 'Forgot Password']);
    }
}
