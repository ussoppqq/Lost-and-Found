<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;
use App\Models\Item;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    // Search & Filter
    public $search = '';
    public $reportTypeFilter = 'all';
    public $reportStatusFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Sorting
    public $sortBy = 'report_datetime';
    public $sortDirection = 'desc';
    
    // Modal states
    public $showDeleteModal = false;
    public $selectedReportId = null;
    
    // Listeners for modal events
    protected $listeners = [
        'item-created' => '$refresh',
        'item-updated' => '$refresh',
    ];

    protected $queryString = ['search', 'reportTypeFilter', 'reportStatusFilter', 'dateFrom', 'dateTo'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedReportTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedReportStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'reportTypeFilter', 'reportStatusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortBy = $field;
        $this->resetPage();
    }

    public function updateReportStatus($reportId, $newStatus)
    {
        $report = Report::findOrFail($reportId);
        $report->update(['report_status' => $newStatus]);

        session()->flash('success', 'Report status updated successfully!');
    }

    // Buka modal untuk walk-in item (standalone - langsung buat report + item)
    public function openCreateItemModal()
    {
        $this->dispatch('open-create-item-modal-standalone');
    }

    // Buka modal untuk create item dari report yang sudah ada (report tanpa item)
    public function createItemFromReport($reportId)
    {
        $this->dispatch('open-create-item-modal', reportId: $reportId);
    }

    // Trigger untuk membuka modal Edit Item
    public function openEditItemModal($itemId)
    {
        $this->dispatch('open-edit-item-modal', itemId: $itemId);
    }

    public function deleteReport($reportId)
    {
        $this->selectedReportId = $reportId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedReportId) {
            $report = Report::findOrFail($this->selectedReportId);
            
            // Jika ada item terkait, hapus juga
            if ($report->item_id) {
                Item::where('item_id', $report->item_id)->delete();
            }
            
            $report->delete();
            
            session()->flash('success', 'Report deleted successfully!');
            $this->showDeleteModal = false;
            $this->selectedReportId = null;
            $this->resetPage();
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->selectedReportId = null;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        // Get statistics for cards
        $totalReports = Report::where('company_id', $companyId)->count();
        $lostReports = Report::where('company_id', $companyId)->where('report_type', 'LOST')->count();
        $foundReports = Report::where('company_id', $companyId)->where('report_type', 'FOUND')->count();
        $matchedReports = Report::where('company_id', $companyId)->where('report_status', 'MATCHED')->count();

        // Build query for reports
        $query = Report::with(['user', 'item.category', 'company'])
            ->where('company_id', $companyId)
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('report_description', 'like', '%' . $this->search . '%')
                          ->orWhere('report_location', 'like', '%' . $this->search . '%')
                          ->orWhere('item_name', 'like', '%' . $this->search . '%')
                          ->orWhereHas('user', function ($userQuery) {
                              $userQuery->where('full_name', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('item', function ($itemQuery) {
                              $itemQuery->where('item_name', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->reportTypeFilter !== 'all', function ($q) {
                $q->where('report_type', $this->reportTypeFilter);
            })
            ->when($this->reportStatusFilter !== 'all', function ($q) {
                $q->where('report_status', $this->reportStatusFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('report_datetime', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('report_datetime', '<=', $this->dateTo);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $reports = $query->paginate(10);

        return view('livewire.admin.lost-and-found.index', [
            'reports' => $reports,
            'totalReports' => $totalReports,
            'lostReports' => $lostReports,
            'foundReports' => $foundReports,
            'matchedReports' => $matchedReports,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'search' => $this->search,
            'reportTypeFilter' => $this->reportTypeFilter,
            'reportStatusFilter' => $this->reportStatusFilter,
        ])->layout('components.layouts.admin', [
            'title' => 'Lost & Found Management',
            'pageTitle' => 'Lost & Found Management',
            'pageDescription' => 'Manage lost and found reports in Kebun Raya'
        ]);
    }
}