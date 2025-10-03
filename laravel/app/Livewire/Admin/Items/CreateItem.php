<?php

namespace App\Livewire\Admin\Items;

use Livewire\Component;
use App\Models\Item;
use App\Models\Category;

class CreateItem extends Component
{
    public $item_name = '';
    public $description = '';
    public $category_id = '';
    public $storage_location = '';
    public $retention_days = 90;
    public $found_date = '';
    public $found_location = '';
    public $notes = '';

    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'storage_location' => 'required|string|max:255',
            'retention_days' => 'required|integer|min:1',
            'found_date' => 'nullable|date',
            'found_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->found_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $validated = $this->validate();

        $retentionUntil = now()->addDays($this->retention_days);

        Item::create([
            'item_name' => $validated['item_name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'storage_location' => $validated['storage_location'],
            'retention_until' => $retentionUntil,
            'found_date' => $validated['found_date'] ?? now(),
            'found_location' => $validated['found_location'],
            'notes' => $validated['notes'],
            'status' => 'stored',
        ]);

        session()->flash('message', 'Item berhasil ditambahkan.');

        return redirect()->route('admin.items');
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.admin.items.create-item', [
            'categories' => $categories,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Create New Item',
            'pageDescription' => 'Add a new item to inventory'
        ]);
    }
}