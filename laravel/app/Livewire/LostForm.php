<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class LostForm extends Component
{
    use WithFileUploads;

    // ===== Form fields =====
    public string $item_name = '';
    public string $category = '';
    public ?string $category_other = null;

    // Color with "Other" support
    public string $color = '';
    public ?string $color_other = null;

    public string $description = '';
    public ?string $location = null;
    public ?string $date_lost = null;           // YYYY-MM-DD
    public ?string $sensitivity_level = null;   // NORMAL | RESTRICTED

    // Contact fields
    public ?string $email = null;
    public ?string $phone = null;

    // File upload (optional)
    public $photo;

    // ===== Validation rules =====
    protected array $rules = [
        'item_name'         => 'required|string|max:255',
        'category'          => 'required|string|max:100',
        'category_other'    => 'nullable|string|max:100',
        'color'             => 'nullable|string|max:50',
        'color_other'       => 'nullable|string|max:50',
        'description'       => 'required|string|min:10',
        'location'          => 'nullable|string|max:255',
        'date_lost'         => 'nullable|date',
        'sensitivity_level' => 'nullable|in:NORMAL,RESTRICTED',
        'email'             => 'nullable|email|max:255',
        'phone'             => 'nullable|string|max:30',
        'photo'             => 'nullable|image|max:3072', // 3MB
    ];

    // Optional: nicer messages
    protected array $messages = [
        'item_name.required'   => 'Item name is required.',
        'description.required' => 'Please describe the item.',
        'email.email'          => 'Please enter a valid email.',
        'photo.image'          => 'Photo must be an image file.',
        'photo.max'            => 'Max photo size is 3MB.',
    ];

    public function submit(): void
    {
        $this->validate();

        // Normalize category + color (use "Other" value if selected)
        $finalCategory = $this->category === 'Other'
            ? trim((string) $this->category_other)
            : $this->category;

        $finalColor = $this->color === 'Other'
            ? trim((string) $this->color_other)
            : $this->color;

        // If you plan to save to DB, do it here.
        // Example (uncomment and create a model/migration accordingly):
        //
        // LostItem::create([
        //     'item_name'         => $this->item_name,
        //     'category'          => $finalCategory,
        //     'color'             => $finalColor,
        //     'description'       => $this->description,
        //     'location'          => $this->location,
        //     'date_lost'         => $this->date_lost,
        //     'sensitivity_level' => $this->sensitivity_level,
        //     'email'             => $this->email,
        //     'phone'             => $this->phone,
        //     'photo_path'        => $this->photo ? $this->photo->store('lost-items', 'public') : null,
        // ]);

        // If you only want to simulate submit for now:
        if ($this->photo) {
            // store to public disk if needed
            $this->photo->store('lost-items-temp', 'public');
        }

        session()->flash('status', 'Lost item reported successfully!');

        // Reset form
        $this->reset([
            'item_name', 'category', 'category_other',
            'color', 'color_other', 'description',
            'location', 'date_lost', 'sensitivity_level',
            'email', 'phone', 'photo',
        ]);
    }

    public function render()
    {
        return view('livewire.lost-form')
            ->layout('components.layouts.app', [
                'title' => 'Lost Form',
            ]);
    }
}
