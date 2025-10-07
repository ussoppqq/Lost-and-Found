<?php

namespace App\Livewire\Moderator\Categories;

use Livewire\Component;
use App\Models\Category;

class Edit extends Component
{
    public Category $category;
    public $name;
    public $description;
    public $icon;

    public $availableIcons = [
        '👜', '💼', '🎒', '📱', '💻', '⌚', '🔑', '👓',
        '📄', '💳', '🎧', '📷', '🧳', '📦', '🏷️', '💍',
        '🎮', '📚', '⚽', '🎸'
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string|max:10',
        ];
    }

    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
        $this->name = $this->category->name;
        $this->description = $this->category->description;
        $this->icon = $this->category->icon ?? '📦';
    }

    public function update()
    {
        $validated = $this->validate();

        $this->category->update($validated);

        session()->flash('message', 'Category berhasil diupdate.');

        return redirect()->route('moderator.categories');
    }

    public function render()
    {
        return view('livewire.moderator.categories.edit')
            ->layout('components.layouts.moderator', [
                'pageTitle' => 'Edit Category',
                'pageDescription' => 'Update category (Moderator)',
            ]);
    }
}
