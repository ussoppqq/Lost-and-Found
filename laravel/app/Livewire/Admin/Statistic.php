<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Report;
use App\Models\Item;
use App\Models\Claim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class Statistic extends Component
{
    #[Url(keep: true)]
    public $periodType = 'weekly';
    
    #[Url(keep: true)]
    public $selectedDate;
    
    public $maxDate;
    
    // Statistics data
    public $totalReports = 0;
    public $lostReports = 0;
    public $foundReports = 0;
    public $matchedReports = 0;
    public $openReports = 0;
    public $closedReports = 0;
    
    public $totalItems = 0;
    public $storedItems = 0;
    public $claimedItems = 0;
    public $disposedItems = 0;
    public $registeredItems = 0;
    public $returnedItems = 0;
    
    public $totalClaims = 0;
    public $pendingClaims = 0;
    public $approvedClaims = 0;
    public $rejectedClaims = 0;
    public $releasedClaims = 0;
    
    // Chart data
    public $reportChartData = [];
    public $itemStatusChartData = [];
    public $claimStatusChartData = [];
    public $categoryChartData = [];
    public $trendChartData = [];

    public function mount()
    {
        $this->maxDate = now()->format('Y-m-d');
        
        if (!$this->selectedDate) {
            $this->selectedDate = now()->format('Y-m-d');
        }
        
        $this->loadStatistics();
    }

    public function updatedPeriodType()
    {
        \Log::info('Period type updated to: ' . $this->periodType);
        $this->loadStatistics();
    }

    public function updatedSelectedDate()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        \Log::info('Loading statistics for period: ' . $this->periodType . ', date: ' . $this->selectedDate);
        $dateRange = $this->getDateRange();
        $companyId = auth()->user()->company_id;

        // Load all statistics
        $this->loadReportStatistics($dateRange, $companyId);
        $this->loadItemStatistics($dateRange, $companyId);
        $this->loadClaimStatistics($dateRange, $companyId);
        $this->prepareChartData($dateRange, $companyId);
        
        // Dispatch event to update charts
        $this->dispatch('statisticsUpdated');
        \Log::info('Statistics loaded and event dispatched');
    }

    private function getDateRange()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        
        switch ($this->periodType) {
            case 'weekly':
                return [
                    'start' => $selectedDate->copy()->startOfWeek(),
                    'end' => $selectedDate->copy()->endOfWeek(),
                ];
            case 'monthly':
                return [
                    'start' => $selectedDate->copy()->startOfMonth(),
                    'end' => $selectedDate->copy()->endOfMonth(),
                ];
            case 'yearly':
                return [
                    'start' => $selectedDate->copy()->startOfYear(),
                    'end' => $selectedDate->copy()->endOfYear(),
                ];
            default:
                return [
                    'start' => $selectedDate->copy()->startOfWeek(),
                    'end' => $selectedDate->copy()->endOfWeek(),
                ];
        }
    }

    private function loadReportStatistics($dateRange, $companyId)
    {
        $reports = Report::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();

        $this->totalReports = $reports->count();
        $this->lostReports = $reports->where('report_type', 'LOST')->count();
        $this->foundReports = $reports->where('report_type', 'FOUND')->count();
        $this->matchedReports = $reports->where('report_status', 'MATCHED')->count();
        $this->openReports = $reports->where('report_status', 'OPEN')->count();
        $this->closedReports = $reports->where('report_status', 'CLOSED')->count();
    }

    private function loadItemStatistics($dateRange, $companyId)
    {
        $items = Item::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();

        $this->totalItems = $items->count();
        $this->registeredItems = $items->where('item_status', 'REGISTERED')->count();
        $this->storedItems = $items->where('item_status', 'STORED')->count();
        $this->claimedItems = $items->where('item_status', 'CLAIMED')->count();
        $this->disposedItems = $items->where('item_status', 'DISPOSED')->count();
        $this->returnedItems = $items->where('item_status', 'RETURNED')->count();
    }

    private function loadClaimStatistics($dateRange, $companyId)
    {
        $claims = Claim::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();

        $this->totalClaims = $claims->count();
        $this->pendingClaims = $claims->where('claim_status', 'PENDING')->count();
        $this->approvedClaims = $claims->where('claim_status', 'APPROVED')->count();
        $this->rejectedClaims = $claims->where('claim_status', 'REJECTED')->count();
        $this->releasedClaims = $claims->where('claim_status', 'RELEASED')->count();
    }

    private function prepareChartData($dateRange, $companyId)
    {
        // Report Type Chart Data
        $this->reportChartData = [
            'lost' => $this->lostReports,
            'found' => $this->foundReports,
        ];

        // Item Status Chart Data
        $items = Item::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('item_status', DB::raw('COUNT(*) as count'))
            ->groupBy('item_status')
            ->get();

        $this->itemStatusChartData = $items->pluck('count', 'item_status')->toArray();

        // Claim Status Chart Data
        $this->claimStatusChartData = [
            'pending' => $this->pendingClaims,
            'approved' => $this->approvedClaims,
            'rejected' => $this->rejectedClaims,
            'released' => $this->releasedClaims,
        ];

        // Top Categories
        $topCategories = Item::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('COUNT(*) as total'))
            ->with('category:category_id,category_name')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $this->categoryChartData = $topCategories->mapWithKeys(function($item) {
            return [$item->category->category_name ?? 'Unknown' => $item->total];
        })->toArray();

        // Trend Chart - Reports per day
        $reportsByDay = Report::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN report_type = "LOST" THEN 1 ELSE 0 END) as lost'),
                DB::raw('SUM(CASE WHEN report_type = "FOUND" THEN 1 ELSE 0 END) as found')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->trendChartData = $reportsByDay->map(function($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M d'),
                'total' => $item->total,
                'lost' => $item->lost,
                'found' => $item->found,
            ];
        })->toArray();
    }

    public function downloadPdf()
    {
        return redirect()->route('admin.statistic.pdf', [
            'period_type' => $this->periodType,
            'selected_date' => $this->selectedDate
        ]);
    }

    public function render()
    {
        $dateRange = $this->getDateRange();
        
        return view('livewire.admin.statistic', [
            'startDate' => $dateRange['start']->format('d M Y'),
            'endDate' => $dateRange['end']->format('d M Y'),
        ])->layout('components.layouts.admin');
    }
}