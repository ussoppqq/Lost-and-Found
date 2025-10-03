<?php

namespace App\Livewire\Admin\Items;

use Livewire\Component;
use App\Models\Item;
use App\Models\Category;

class EditItem extends Component
{
    public Item $item;
    
    public $item_name;
    public $description;
    public $category_id;
    public $storage_location;
    public $status;
    public $found_date;
    public $found_location;
    public $notes;

    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'storage_location' => 'required|string|max:255',
            'status' => 'required|in:stored,claimed,disposed,returned',
            'found_date' => 'nullable|date',
            'found_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function mount($id)
    {
        $this->item = Item::findOrFail($id);
        $this->item_name = $this->item->item_name;
        $this->description = $this->item->description;
        $this->category_id = $this->item->category_id;
        $this->storage_location = $this->item->storage_location;
        $this->status = $this->item->status;
        $this->found_date = $this->item->found_date ? $this->item->found_date->format('Y-m-d') : '';
        $this->found_location = $this->item->found_location;
        $this->notes = $this->item->notes;
    }

    public function update()
    {
        $validated = $this->validate();

        $this->item->update($validated);

        // If status changed to claimed/returned/disposed, update linked report
        if (in_array($this->status, ['claimed', 'returned', 'disposed']) && $this->item->report) {
            $this->item->report->update(['status' => 'resolved']);
        }

        session()->flash('message', 'Item berhasil diupdate.');

        return redirect()->route('admin.items');
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.admin.items.edit-item', [
            'categories' => $categories,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Edit Item',
            'pageDescription' => 'Update item information'
        ]);
    }
}