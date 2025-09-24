<?php

namespace App\Livewire;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Found Form')]

class FoundForm extends Component
{
    public function render()
    {
        return view('livewire.found-form');
    }
}