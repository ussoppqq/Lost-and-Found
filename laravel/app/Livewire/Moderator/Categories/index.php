<?php

namespace App\Livewire\Moderator\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $categoryToDelete = null;

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
            session()->flash('message', 'Category berhasil dihapus.');
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
        $categories = Category::query()
            ->withCount('items')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(12);

        return view('livewire.moderator.categories.index', [
            'categories' => $categories,
        ])->layout('components.layouts.moderator', [
            'pageTitle' => 'Categories Management',
            'pageDescription' => 'Manage categories as a moderator',
        ]);
    }
}
