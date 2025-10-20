<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    public $showDetailModal = false;
    public $selectedReportForDetail = null;

    // Listeners for modal events
    protected $listeners = [
        'item-created' => '$refresh',
        'item-updated' => '$refresh',
        'closeDetailModal' => 'closeDetailModal',
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

    // View report detail
    public function viewReportDetail($reportId)
    {
        $this->selectedReportForDetail = $reportId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedReportForDetail = null;
    }

    // Buka modal untuk walk-in item (standalone - langsung buat report + item)
    public function openCreateItemModal()
    {
        $this->dispatch('open-create-item-modal-standalone');
    }

    // Trigger untuk membuka modal Edit Item
    public function openEditItemModal($itemId)
    {
        $this->dispatch('open-edit-item-modal', itemId: $itemId)->to(EditItem::class);
    }

    public function deleteReport($reportId)
    {
        $this->selectedReportId = $reportId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->selectedReportId) {
            try {
                DB::beginTransaction();

                $report = Report::findOrFail($this->selectedReportId);

                // PENTING: Hapus Report DULU, baru Item
                $itemId = $report->item_id; // Simpan item_id dulu

                // 1. Hapus Report dulu (yang punya foreign key ke Item)
                $report->delete();

                // 2. Baru hapus Item (jika ada)
                if ($itemId) {
                    $item = Item::find($itemId);
                    if ($item) {
                        // Hapus photos dulu
                        foreach ($item->photos as $photo) {
                            Storage::disk('public')->delete($photo->photo_url);
                            $photo->delete();
                        }
                        // Hapus item
                        $item->delete();
                    }
                }

                DB::commit();

                session()->flash('success', 'Report and item deleted successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Failed to delete: ' . $e->getMessage());
            }

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