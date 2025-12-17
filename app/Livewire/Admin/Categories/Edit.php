<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;

class Edit extends Component
{
    public $showModal = false;
    public $categoryId;
    public $category;
    
    public $category_name;
    public $category_icon;
    public $retention_days;

    public $availableIcons = [
        '👜', '💼', '🎒', '📱', '💻', '⌚', '🔑', '👓', 
        '📄', '💳', '🎧', '📷', '🧳', '📦', '🏷️', '💍', 
        '🎮', '📚', '⚽', '🎸'
    ];

    protected $listeners = [
        'open-edit-category-modal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            'category_icon' => 'required|string|max:10',
            'retention_days' => 'required|integer|min:1|max:365',
        ];
    }

    public function openModal($categoryId)
    {
        $this->categoryId = $categoryId;
        $companyId = auth()->user()->company_id;
        $this->category = Category::where('company_id', $companyId)->findOrFail($categoryId);

        $this->category_name = $this->category->category_name;
        $this->category_icon = $this->category->category_icon ?? '📦';
        $this->retention_days = $this->category->retention_days ?? 30;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function update()
    {
        $validated = $this->validate();

        $this->category->update($validated);

        session()->flash('message', 'Category updated successfully.');
        
        $this->closeModal();
        $this->dispatch('category-updated');
    }

    public function render()
    {
        return view('livewire.admin.categories.edit');
    }
}