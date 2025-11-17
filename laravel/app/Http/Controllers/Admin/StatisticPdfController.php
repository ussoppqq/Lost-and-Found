<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticPdfController extends Controller
{
    public function export(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $companyId = auth()->user()->company_id;

        // Parse dates based on period type
        $periodType = $request->get('period_type', 'weekly');
        $selectedDate = Carbon::parse($request->get('selected_date', now($tz)), $tz);

        switch ($periodType) {
            case 'monthly':
                $start = $selectedDate->copy()->startOfMonth();
                $end = $selectedDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $start = $selectedDate->copy()->startOfYear();
                $end = $selectedDate->copy()->endOfYear();
                break;
            default: // weekly
                $start = $selectedDate->copy()->startOfWeek();
                $end = $selectedDate->copy()->endOfWeek();
        }

        // Clamp to today
        $today = now($tz)->endOfDay();
        if ($end->gt($today)) $end = $today;

        // ===== STATISTICS DATA =====
        
        // Reports Statistics
        $reports = Report::where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalReports = $reports->count();
        $lostReports = $reports->where('report_type', 'LOST')->count();
        $foundReports = $reports->where('report_type', 'FOUND')->count();
        $matchedReports = $reports->where('report_status', 'MATCHED')->count();
        $openReports = $reports->where('report_status', 'OPEN')->count();
        $closedReports = $reports->where('report_status', 'CLOSED')->count();

        // Items Statistics
        $items = Item::where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalItems = $items->count();
        $registeredItems = $items->where('item_status', 'REGISTERED')->count();
        $storedItems = $items->where('item_status', 'STORED')->count();
        $claimedItems = $items->where('item_status', 'CLAIMED')->count();
        $disposedItems = $items->where('item_status', 'DISPOSED')->count();
        $returnedItems = $items->where('item_status', 'RETURNED')->count();

        // Claims Statistics
        $claims = Claim::where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalClaims = $claims->count();
        $pendingClaims = $claims->where('claim_status', 'PENDING')->count();
        $approvedClaims = $claims->where('claim_status', 'APPROVED')->count();
        $rejectedClaims = $claims->where('claim_status', 'REJECTED')->count();
        $releasedClaims = $claims->where('claim_status', 'RELEASED')->count();

        // Category Distribution
        $topCategories = Item::where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('COUNT(*) as total'))
            ->with('category:category_id,category_name')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $categoryDistribution = $topCategories->map(function($item) {
            return [
                'name' => $item->category->category_name ?? 'Unknown',
                'count' => $item->total
            ];
        })->toArray();

        // Daily Trend Data
        $reportsByDay = Report::where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN report_type = "LOST" THEN 1 ELSE 0 END) as lost'),
                DB::raw('SUM(CASE WHEN report_type = "FOUND" THEN 1 ELSE 0 END) as found')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendData = $reportsByDay->map(function($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M d'),
                'total' => $item->total,
                'lost' => $item->lost,
                'found' => $item->found,
            ];
        })->toArray();

        // Recent Activities
        $recent = Report::with('user')
            ->where('company_id', $companyId)
            ->whereBetween('created_at', [$start, $end])
            ->latest('created_at')
            ->limit(15)
            ->get();

        // ===== CHART URLs using Google Charts API =====
        
        // 1. Report Types Donut Chart
        $reportTypeChartUrl = $this->generateDonutChart(
            $lostReports,
            $foundReports
        );

        // 2. Item Status Pie Chart
        $itemStatusChartUrl = $this->generateItemStatusPieChart(
            $registeredItems,
            $storedItems,
            $claimedItems,
            $disposedItems,
            $returnedItems
        );

        // 3. Claim Status Bar Chart
        $claimStatusChartUrl = $this->generateClaimBarChart(
            $pendingClaims,
            $approvedClaims,
            $rejectedClaims,
            $releasedClaims
        );

        // 4. Top Categories Bar Chart
        $categoryChartUrl = $this->generateCategoryBarChart($categoryDistribution);

        // 5. Trend Line Chart
        $trendChartUrl = $this->generateTrendLineChart($trendData);

        // Calculate success rate
        $totalItemsAll = $storedItems + $claimedItems + $disposedItems + $returnedItems + $registeredItems;
        $successRate = $totalItemsAll > 0 ? round(($claimedItems / $totalItemsAll) * 100, 1) : 0;

        $data = [
            'range' => [$start->format('d M Y'), $end->format('d M Y')],
            'periodType' => ucfirst($periodType),
            
            // Reports
            'totalReports' => $totalReports,
            'lostReports' => $lostReports,
            'foundReports' => $foundReports,
            'matchedReports' => $matchedReports,
            'openReports' => $openReports,
            'closedReports' => $closedReports,
            
            // Items
            'totalItems' => $totalItems,
            'registeredItems' => $registeredItems,
            'storedItems' => $storedItems,
            'claimedItems' => $claimedItems,
            'disposedItems' => $disposedItems,
            'returnedItems' => $returnedItems,
            
            // Claims
            'totalClaims' => $totalClaims,
            'pendingClaims' => $pendingClaims,
            'approvedClaims' => $approvedClaims,
            'rejectedClaims' => $rejectedClaims,
            'releasedClaims' => $releasedClaims,
            
            'successRate' => $successRate,
            'categoryDistribution' => $categoryDistribution,
            'trendChartData' => $trendData,
            'recent' => $recent,
            
            // Chart URLs
            'reportTypeChartUrl' => $reportTypeChartUrl,
            'itemStatusChartUrl' => $itemStatusChartUrl,
            'claimStatusChartUrl' => $claimStatusChartUrl,
            'categoryChartUrl' => $categoryChartUrl,
            'trendChartUrl' => $trendChartUrl,
        ];

        $pdf = Pdf::loadView('pdf.statistic', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('enable-local-file-access', true);
            
        $filename = 'statistics_' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate Report Types Donut Chart
     */
    private function generateDonutChart($lostCount, $foundCount)
    {
        if ($lostCount == 0 && $foundCount == 0) {
            return $this->generateEmptyChart('No Report Data');
        }

        $total = $lostCount + $foundCount;
        $lostPercent = round(($lostCount / $total) * 100, 1);
        $foundPercent = round(($foundCount / $total) * 100, 1);

        $chart = [
            'cht' => 'pd',  // pie donut
            'chs' => '350x250',
            'chd' => "t:{$lostCount},{$foundCount}",
            'chl' => "Lost ({$lostPercent}%)|Found ({$foundPercent}%)",
            'chco' => 'EF4444,10B981',
            'chf' => 'bg,s,FFFFFF',
            'chdl' => "Lost Reports|Found Reports",
            'chdlp' => 'b',
            'chds' => 'a',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }

    /**
     * Generate Item Status Pie Chart
     */
    private function generateItemStatusPieChart($registered, $stored, $claimed, $disposed, $returned)
    {
        $values = array_filter([$registered, $stored, $claimed, $disposed, $returned]);
        
        if (empty($values)) {
            return $this->generateEmptyChart('No Item Data');
        }

        $labels = [];
        $data = [];
        $colors = [];

        if ($registered > 0) {
            $labels[] = "Registered ({$registered})";
            $data[] = $registered;
            $colors[] = '3B82F6';
        }
        if ($stored > 0) {
            $labels[] = "Stored ({$stored})";
            $data[] = $stored;
            $colors[] = '10B981';
        }
        if ($claimed > 0) {
            $labels[] = "Claimed ({$claimed})";
            $data[] = $claimed;
            $colors[] = '8B5CF6';
        }
        if ($disposed > 0) {
            $labels[] = "Disposed ({$disposed})";
            $data[] = $disposed;
            $colors[] = 'EF4444';
        }
        if ($returned > 0) {
            $labels[] = "Returned ({$returned})";
            $data[] = $returned;
            $colors[] = 'F59E0B';
        }

        $chart = [
            'cht' => 'p3',  // 3D pie
            'chs' => '350x250',
            'chd' => 't:' . implode(',', $data),
            'chl' => implode('|', $labels),
            'chco' => implode('|', $colors),
            'chf' => 'bg,s,FFFFFF',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }

    /**
     * Generate Claim Status Bar Chart
     */
    private function generateClaimBarChart($pending, $approved, $rejected, $released)
    {
        if ($pending == 0 && $approved == 0 && $rejected == 0 && $released == 0) {
            return $this->generateEmptyChart('No Claim Data');
        }

        $maxValue = max($pending, $approved, $rejected, $released) + 5;
        
        $chart = [
            'cht' => 'bvs',  // vertical bar
            'chs' => '350x250',
            'chd' => "t:{$pending},{$approved},{$rejected},{$released}",
            'chds' => "0,{$maxValue}",
            'chco' => 'F59E0B,10B981,EF4444,8B5CF6',
            'chbh' => '40,15',
            'chxt' => 'x,y',
            'chxl' => '0:|Pending|Approved|Rejected|Released',
            'chf' => 'bg,s,FFFFFF',
            'chm' => 'N,000000,0,-1,11',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }

    /**
     * Generate Category Bar Chart
     */
    private function generateCategoryBarChart($categories)
    {
        if (empty($categories)) {
            return $this->generateEmptyChart('No Category Data');
        }

        $names = array_column($categories, 'name');
        $counts = array_column($categories, 'count');
        
        // Limit to top 8 for better visibility
        $names = array_slice($names, 0, 8);
        $counts = array_slice($counts, 0, 8);
        
        $maxValue = max($counts) + 5;

        // Reverse for horizontal bar (bottom to top)
        $names = array_reverse($names);
        $counts = array_reverse($counts);

        $chart = [
            'cht' => 'bhg',  // horizontal bar grouped
            'chs' => '350x250',
            'chd' => 't:' . implode(',', $counts),
            'chds' => "0,{$maxValue}",
            'chco' => '8B5CF6',
            'chbh' => '20,5',
            'chxt' => 'x,y',
            'chxl' => '1:|' . implode('|', $names),
            'chf' => 'bg,s,FFFFFF',
            'chm' => 'N,000000,0,-1,11',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }

    /**
     * Generate Trend Line Chart
     */
    private function generateTrendLineChart($trendData)
    {
        if (empty($trendData)) {
            return $this->generateEmptyChart('No Trend Data');
        }

        $dates = array_column($trendData, 'date');
        $totals = array_column($trendData, 'total');
        $losts = array_column($trendData, 'lost');
        $founds = array_column($trendData, 'found');

        $maxValue = max(array_merge($totals, $losts, $founds)) + 3;

        // Limit dates for readability (show every nth date)
        $showEveryNth = max(1, floor(count($dates) / 10));
        $dateLabels = [];
        foreach ($dates as $i => $date) {
            $dateLabels[] = ($i % $showEveryNth == 0) ? $date : '';
        }

        $chart = [
            'cht' => 'lc',  // line chart
            'chs' => '550x250',
            'chd' => 't:' . implode(',', $totals) . '|' . implode(',', $losts) . '|' . implode(',', $founds),
            'chds' => "0,{$maxValue}",
            'chco' => '3B82F6,EF4444,10B981',
            'chxt' => 'x,y',
            'chxl' => '0:|' . implode('|', $dateLabels),
            'chdl' => 'Total|Lost|Found',
            'chdlp' => 't',
            'chls' => '3|3|3',
            'chm' => 'o,3B82F6,0,-1,6|o,EF4444,1,-1,6|o,10B981,2,-1,6',
            'chf' => 'bg,s,FFFFFF',
            'chg' => '10,10,1,5',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }

    /**
     * Generate Empty Chart Placeholder
     */
    private function generateEmptyChart($message)
    {
        $chart = [
            'cht' => 'p',
            'chs' => '350x250',
            'chd' => 't:1',
            'chl' => $message,
            'chco' => 'E5E7EB',
            'chf' => 'bg,s,F9FAFB',
        ];

        return 'https://chart.googleapis.com/chart?' . http_build_query($chart);
    }
}