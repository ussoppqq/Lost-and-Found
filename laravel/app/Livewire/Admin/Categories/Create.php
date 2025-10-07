<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Create extends Component
{
    public $showModal = false;
    
    public $category_name = '';
    public $category_icon = '📦';
    public $retention_days = 30;

    public $availableIcons = [
        '👜', '💼', '🎒', '📱', '💻', '⌚', '🔑', '👓', 
        '📄', '💳', '🎧', '📷', '🧳', '📦', '🏷️', '💍', 
        '🎮', '📚', '⚽', '🎸'
    ];

    protected $listeners = [
        'open-create-category-modal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            'category_icon' => 'required|string|max:10',
            'retention_days' => 'required|integer|min:1|max:365',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->category_name = '';
        $this->category_icon = '📦';
        $this->retention_days = 30;
    }

    public function save()
    {
        $validated = $this->validate();
        
        $companyId = auth()->user()->company_id;

        Category::create([
            'category_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'category_name' => $validated['category_name'],
            'category_icon' => $validated['category_icon'],
            'retention_days' => $validated['retention_days'],
        ]);

        session()->flash('message', 'Category created successfully.');
        
        $this->closeModal();
        $this->dispatch('category-created');
    }

    public function render()
    {
        return view('livewire.admin.categories.create');
    }
}