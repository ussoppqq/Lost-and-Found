<?php

namespace App\Livewire\Admin\LostAndFound;

use App\Models\Report;
use App\Models\MatchedItem;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuickMatch extends Component
{
    public $sourceReportId;
    public $sourceReport;
    public $oppositeType;
    
    public $searchTerm = '';
    public $selectedCategoryId = '';
    public $selectedReportId = null;
    public $selectedReport = null;
    
    public $showMatchModal = false;
    public $showClaimModal = false;
    
    protected $listeners = [
        'open-quick-match' => 'openQuickMatch',
        'close-claim-modal' => 'closeClaimModal',
        'refresh-quick-match' => 'refreshQuickMatch',
    ];

    public function mount()
    {
        // Optional
    }

    public function openQuickMatch($reportId)
    {
        $this->sourceReportId = $reportId;
        $this->sourceReport = Report::with(['user', 'category', 'photos'])->findOrFail($reportId);
        
        $this->oppositeType = $this->sourceReport->report_type === 'LOST' ? 'FOUND' : 'LOST';
        
        $this->showMatchModal = true;
        $this->resetSearch();
        
        Log::info('QuickMatch modal opened', [
            'sourceReportId' => $reportId,
            'oppositeType' => $this->oppositeType,
        ]);
    }

    public function resetSearch()
    {
        $this->searchTerm = '';
        $this->selectedCategoryId = '';
        $this->selectedReportId = null;
        $this->selectedReport = null;
    }

    public function selectReport($reportId)
    {
        if ($reportId === null) {
            $this->selectedReportId = null;
            $this->selectedReport = null;
            return;
        }

        $this->selectedReportId = $reportId;
        $this->selectedReport = Report::with(['user', 'category', 'photos', 'item'])
            ->findOrFail($reportId);
            
        Log::info('Report selected', [
            'selectedReportId' => $reportId,
            'has_item' => $this->selectedReport->item_id ? 'yes' : 'no',
        ]);
    }

    public function createMatch()
    {
        if (!$this->selectedReportId) {
            session()->flash('error', 'Please select a report to match.');
            return;
        }

        if ($this->sourceReport->report_type === 'LOST' && 
            $this->selectedReport->report_type === 'FOUND' && 
            !$this->selectedReport->item_id) {
            session()->flash('error', 'The selected FOUND report must have a registered item before matching.');
            return;
        }

        try {
            DB::beginTransaction();

            $lostReportId = $this->sourceReport->report_type === 'LOST' 
                ? $this->sourceReportId 
                : $this->selectedReportId;
                
            $foundReportId = $this->sourceReport->report_type === 'FOUND' 
                ? $this->sourceReportId 
                : $this->selectedReportId;

            MatchedItem::create([
                'match_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'lost_report_id' => $lostReportId,
                'found_report_id' => $foundReportId,
                'matched_by' => auth()->id(),
                'match_status' => 'PENDING',
                'matched_at' => now(),
            ]);

            Report::whereIn('report_id', [$lostReportId, $foundReportId])
                ->update(['report_status' => 'MATCHED']);

            DB::commit();

            Log::info('Match created successfully', [
                'lostReportId' => $lostReportId,
                'foundReportId' => $foundReportId,
            ]);

            session()->flash('success', 'Match created successfully! Redirecting to matches page...');
            
            return redirect()->route('admin.matches');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create match', [
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to create match: ' . $e->getMessage());
        }
    }

    public function openClaimModal()
    {
        if (!$this->selectedReportId) {
            session()->flash('error', 'Please select a report first.');
            return;
        }

        if ($this->sourceReport->report_type === 'LOST') {
            if (!$this->selectedReport->item_id) {
                session()->flash('error', 'Cannot process claim: The selected FOUND report must have a registered item.');
                return;
            }
        } else {
            if (!$this->sourceReport->item_id) {
                session()->flash('error', 'Cannot process claim: This FOUND report must have a registered item.');
                return;
            }
        }

        $this->showMatchModal = false;
        $this->showClaimModal = true;
        
        Log::info('Opening claim modal', [
            'sourceReportId' => $this->sourceReportId,
            'targetReportId' => $this->selectedReportId,
        ]);
    }

    public function closeClaimModal()
    {
        $this->showClaimModal = false;
        $this->showMatchModal = true;
        
        Log::info('Claim modal closed, reopening match modal');
    }

    public function closeMatchModal()
    {
        $this->showMatchModal = false;
        $this->resetSearch();
        
        Log::info('Match modal closed');
    }

    public function openCreateAndMatchModal()
    {
        $this->dispatch('open-create-and-match-modal', 
            data: [
                'sourceReportId' => $this->sourceReportId,
                'oppositeType' => $this->oppositeType
            ]
        );
        
        Log::info('Dispatching create and match modal', [
            'sourceReportId' => $this->sourceReportId,
            'oppositeType' => $this->oppositeType,
        ]);
    }

    public function refreshQuickMatch()
    {
        $this->resetSearch();
        session()->flash('success', 'Report created successfully! Select it below to create a match or proceed with claim.');
        Log::info('Quick match refreshed after new report created');
    }

    public function getAvailableReportsProperty()
    {
        $query = Report::with(['user', 'category', 'photos', 'item'])
            ->where('company_id', auth()->user()->company_id)
            ->where('report_type', $this->oppositeType)
            ->whereIn('report_status', ['OPEN', 'STORED']);

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('report_description', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('report_location', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('report_number', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->selectedCategoryId) {
            $query->where('category_id', $this->selectedCategoryId);
        }

        return $query->latest()->get();
    }

    public function render()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)->get();
        
        return view('livewire.admin.lost-and-found.quick-match', [
            'categories' => $categories,
            'availableReports' => $this->availableReports,
        ]);
    }
}