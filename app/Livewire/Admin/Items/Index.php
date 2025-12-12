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

    protected $listeners = [
        'item-created' => '$refresh',
        'item-updated' => '$refresh',
    ];

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
            // Hapus photos dulu
            foreach ($this->itemToDelete->photos as $photo) {
                \Storage::disk('public')->delete($photo->photo_url);
                $photo->delete();
            }
            
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
        $companyId = auth()->user()->company_id;

        // Statistics
        $stats = [
            'total' => Item::where('company_id', $companyId)->count(),
            'stored' => Item::where('company_id', $companyId)->where('item_status', 'STORED')->count(),
            'claimed' => Item::where('company_id', $companyId)->where('item_status', 'CLAIMED')->count(),
            'disposed' => Item::where('company_id', $companyId)->where('item_status', 'DISPOSED')->count(),
        ];

        // Build query
        $items = Item::query()
            ->with(['category', 'post', 'reports'])
            ->where('company_id', $companyId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('item_name', 'like', '%' . $this->search . '%')
                      ->orWhere('item_description', 'like', '%' . $this->search . '%')
                      ->orWhere('storage', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('item_status', strtoupper($this->statusFilter));
            })
            ->latest('created_at')
            ->paginate(10);

        $categories = Category::where('company_id', $companyId)->get();

        return view('livewire.admin.items.index', [
            'items' => $items,
            'categories' => $categories,
            'stats' => $stats,
        ])->layout('components.layouts.admin', [
            'title' => 'Storage Management',
            'pageTitle' => 'Storage Management',
            'pageDescription' => 'Manage all physical items in storage'
        ]);
    }
}