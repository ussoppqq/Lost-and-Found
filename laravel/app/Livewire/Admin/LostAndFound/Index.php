<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;

class Index extends Component
{
    use WithPagination;

    // Search & Filter
    public $search = '';
    public $reportTypeFilter = 'all';
    public $reportStatusFilter = 'all';
    public $itemStatusFilter = 'all';
    
    // Sorting
    public $sortBy = 'report_datetime';
    public $sortDirection = 'desc';
    
    // Modal states
    public $showDeleteModal = false;
    public $selectedReportId = null;

    protected $queryString = ['search', 'reportTypeFilter', 'reportStatusFilter', 'itemStatusFilter'];

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

    public function updatedItemStatusFilter()
    {
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

    public function deleteReport($reportId)
    {
        $this->selectedReportId = $reportId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedReportId) {
            Report::findOrFail($this->selectedReportId)->delete();
            
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
        // Get statistics for cards
        $totalReports = Report::count();
        $lostReports = Report::where('report_type', 'LOST')->count();
        $foundReports = Report::where('report_type', 'FOUND')->count();
        $matchedReports = Report::where('report_status', 'MATCHED')->count();

        // Build query for reports
        $query = Report::with(['user', 'item.category', 'company'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('report_description', 'like', '%' . $this->search . '%')
                          ->orWhere('report_location', 'like', '%' . $this->search . '%')
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
            ->when($this->itemStatusFilter !== 'all', function ($q) {
                $q->whereHas('item', function ($itemQuery) {
                    $itemQuery->where('item_status', $this->itemStatusFilter);
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $reports = $query->paginate(10);

        return view('livewire.admin.lost-and-found.index', [
            'reports' => $reports,
            'totalReports' => $totalReports,
            'lostReports' => $lostReports,
            'foundReports' => $foundReports,
            'matchedReports' => $matchedReports,
        ])->layout('components.layouts.admin', [
            'title' => 'Lost & Found Management',
            'pageTitle' => 'Lost & Found Management',
            'pageDescription' => 'Manage lost and found reports in Kebun Raya'
        ]);
    }
}