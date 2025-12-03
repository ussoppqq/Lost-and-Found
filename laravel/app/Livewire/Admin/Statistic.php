<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Report;
use App\Models\Item;
use App\Models\Claim;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class Statistic extends Component
{
    #[Url(keep: true)]
    public $periodType = 'weekly';
    
    #[Url(keep: true)]
    public $startDate;
    
    #[Url(keep: true)]
    public $endDate;
    
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
        
        if (!$this->startDate || !$this->endDate) {
            $this->setDefaultDates();
        }
        
        $this->loadStatistics();
    }

    private function setDefaultDates()
    {
        $now = now();
        
        switch ($this->periodType) {
            case 'weekly':
                $this->startDate = $now->copy()->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfWeek()->format('Y-m-d');
                break;
            case 'monthly':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'yearly':
                $this->startDate = $now->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function updatedPeriodType()
    {
        \Log::info('Period type updated to: ' . $this->periodType);
        $this->setDefaultDates();
        $this->loadStatistics();
    }

    public function updatedStartDate()
    {
        // Validate that end date is after start date
        if ($this->endDate && $this->startDate > $this->endDate) {
            $this->endDate = $this->startDate;
        }
        $this->loadStatistics();
    }

    public function updatedEndDate()
    {
        // Validate that end date is after start date
        if ($this->startDate && $this->endDate < $this->startDate) {
            $this->startDate = $this->endDate;
        }
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        \Log::info('Loading statistics', [
            'period' => $this->periodType,
            'start' => $this->startDate,
            'end' => $this->endDate
        ]);
        
        $dateRange = $this->getDateRange();
        $companyId = auth()->user()->company_id;

        // Load all statistics
        $this->loadReportStatistics($dateRange, $companyId);
        $this->loadItemStatistics($dateRange, $companyId);
        $this->loadClaimStatistics($dateRange, $companyId);
        $this->prepareChartData($dateRange, $companyId);
        
        // Dispatch event to update charts
        $this->dispatch('statisticsUpdated', [
            'reportChartData' => $this->reportChartData,
            'itemStatusChartData' => $this->itemStatusChartData,
            'claimStatusChartData' => $this->claimStatusChartData,
            'categoryChartData' => $this->categoryChartData,
            'trendChartData' => $this->trendChartData
        ]);
    }

    private function getDateRange()
    {
        return [
            'start' => Carbon::parse($this->startDate)->startOfDay(),
            'end' => Carbon::parse($this->endDate)->endOfDay(),
        ];
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
        $this->itemStatusChartData = array_filter([
            'REGISTERED' => $this->registeredItems,
            'STORED' => $this->storedItems,
            'CLAIMED' => $this->claimedItems,
            'RETURNED' => $this->returnedItems,
            'DISPOSED' => $this->disposedItems,
        ]);

        // Claim Status Chart Data
        $this->claimStatusChartData = [
            'pending' => $this->pendingClaims,
            'rejected' => $this->rejectedClaims,
            'released' => $this->releasedClaims,
        ];

        // Top Categories from REPORTS
        $categoryReports = Report::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $categories = Category::whereIn('category_id', $categoryReports->pluck('category_id'))
            ->get(['category_id', 'category_name'])
            ->keyBy('category_id');

        $this->categoryChartData = $categoryReports->mapWithKeys(function($item) use ($categories) {
            $categoryName = $categories[$item->category_id]->category_name ?? 'Uncategorized';
            return [$categoryName => $item->count];
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
                'total' => (int)$item->total,
                'lost' => (int)$item->lost,
                'found' => (int)$item->found,
            ];
        })->toArray();

        \Log::info('Chart data prepared', [
            'categories' => count($this->categoryChartData),
            'trendPoints' => count($this->trendChartData)
        ]);
    }

    public function downloadPdf()
    {
        return redirect()->route('admin.statistic.pdf', [
            'period_type' => $this->periodType,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate
        ]);
    }

    public function render()
    {
        $dateRange = $this->getDateRange();
        
        return view('livewire.admin.statistic', [
            'formattedStartDate' => $dateRange['start']->format('d M Y'),
            'formattedEndDate' => $dateRange['end']->format('d M Y'),
        ])->layout('components.layouts.admin');
    }
}