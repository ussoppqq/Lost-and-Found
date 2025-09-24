<?php

namespace App\Livewire;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Found Form')]

class FoundForm extends Component
{
    public function render()
    {
        return view('livewire.found-form')
            ->layout('components.layouts.app', [
                'title' => 'Submit Found Item Report'
            ]);
    }

}