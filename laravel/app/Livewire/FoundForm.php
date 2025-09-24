<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;

#[Title('Report Found Item')]

class FoundForm extends Component
{
    use WithFileUploads;

    public $name;
    public $phone;
    public $email;
    public $location;
    public $description;
    public $photo;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:10240', // max 10MB
        ]);



        session()->flash('success', 'Your report has been submitted successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.found-form')
            ->layout('components.layouts.app');
    }
}
