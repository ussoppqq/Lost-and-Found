<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class LostItems extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Report::with(['item.photos', 'item.category', 'user'])
            ->where('report_type', 'LOST')
            ->where('report_status', 'OPEN');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('report_location', 'like', '%' . $this->search . '%')
                  ->orWhere('report_description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('item', function ($itemQuery) {
                      $itemQuery->where('item_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $lostItems = $query->orderBy('report_datetime', 'desc')
            ->paginate(6);

        $categories = \App\Models\Category::orderBy('category_name')
            ->get();

        return view('livewire.lost-items', [
            'lostItems' => $lostItems,
            'categories' => $categories,
        ])->layout('components.layouts.user');
    }
}
