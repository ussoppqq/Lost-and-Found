<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $icon = '📦';

    public $availableIcons = [
        '👜', '💼', '🎒', '📱', '💻', '⌚', '🔑', '👓', 
        '📄', '💳', '🎧', '📷', '🧳', '📦', '🏷️', '💍', 
        '🎮', '📚', '⚽', '🎸'
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string|max:10',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        Category::create($validated);

        session()->flash('message', 'Category berhasil ditambahkan.');

        return redirect()->route('admin.categories');
    }

    public function render()
    {
        return view('livewire.admin.categories.create')->layout('layouts.admin', [
            'pageTitle' => 'Create Category',
            'pageDescription' => 'Add a new category'
        ]);
    }
}