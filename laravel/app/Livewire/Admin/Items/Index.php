<?php

namespace App\Livewire\Admin\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $showDeleteModal = false;
    public $itemToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function confirmDelete($itemId)
    {
        $this->itemToDelete = Item::findOrFail($itemId);
        $this->showDeleteModal = true;
    }

    public function deleteItem()
    {
        if ($this->itemToDelete) {
            $this->itemToDelete->delete();
            session()->flash('message', 'Item berhasil dihapus.');
            $this->showDeleteModal = false;
            $this->itemToDelete = null;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->itemToDelete = null;
    }

    public function render()
    {
        $items = Item::query()
            ->with(['category', 'report'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('item_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('storage_location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        $stats = [
            'total' => Item::count(),
            'stored' => Item::where('status', 'stored')->count(),
            'claimed' => Item::where('status', 'claimed')->count(),
            'disposed' => Item::where('status', 'disposed')->count(),
        ];

        return view('livewire.admin.items.index', [
            'items' => $items,
            'categories' => $categories,
            'stats' => $stats,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Items Management',
            'pageDescription' => 'Manage all physical items in storage'
        ]);
    }
}