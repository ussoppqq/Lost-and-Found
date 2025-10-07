<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $categoryToDelete = null;

    protected $listeners = [
        'category-created' => '$refresh',
        'category-updated' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = Category::withCount('items')->findOrFail($categoryId);
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            if ($this->categoryToDelete->items_count > 0) {
                session()->flash('error', 'Cannot delete category with existing items. Please reassign or delete the items first.');
                $this->closeDeleteModal();
                return;
            }

            $this->categoryToDelete->delete();
            session()->flash('message', 'Category deleted successfully.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $categories = Category::query()
            ->withCount('items')
            ->where('company_id', $companyId)
            ->when($this->search, function ($query) {
                $query->where('category_name', 'like', '%' . $this->search . '%')
                      ->orWhere('category_description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('category_name', 'asc') // Ganti dari latest() ke orderBy
            ->paginate(12);

        return view('livewire.admin.categories.index', [
            'categories' => $categories,
        ])->layout('components.layouts.admin', [
            'title' => 'Categories Management',
            'pageTitle' => 'Categories Management',
            'pageDescription' => 'Manage all item categories'
        ]);
    }
}