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
use Illuminate\Support\Facades\Log;

class StatisticPdfController extends Controller
{
    public function export(Request $request)
    {
        try {
            $tz = 'Asia/Jakarta';
            $companyId = auth()->user()->company_id;

            $periodType = $request->get('period_type', 'weekly');
            $selectedDate = $request->get('selected_date', now($tz)->format('Y-m-d'));

            $dateRange = $this->getDateRange($periodType, $selectedDate, $tz);
            $start = $dateRange['start'];
            $end = $dateRange['end'];

            Log::info('PDF Export Started', [
                'period_type' => $periodType,
                'selected_date' => $selectedDate,
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'company_id' => $companyId,
                'user' => auth()->user()->email,
            ]);

            // Query reports
            $reports = Report::where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->get(['report_type', 'report_status', 'created_at', 'item_name', 'category_id']);

            $totalLost = $reports->where('report_type', 'LOST')->count();
            $totalFound = $reports->where('report_type', 'FOUND')->count();
            $openLost = $reports->where('report_type', 'LOST')->where('report_status', 'OPEN')->count();
            $matched = $reports->where('report_status', 'MATCHED')->count();

            // Query items
            $items = Item::where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->get(['item_status']);

            $totalStored = $items->where('item_status', 'STORED')->count();
            $totalRegistered = $items->where('item_status', 'REGISTERED')->count();
            $totalClaimed = $items->where('item_status', 'CLAIMED')->count();
            $totalReturned = $items->where('item_status', 'RETURNED')->count();
            $totalDisposed = $items->where('item_status', 'DISPOSED')->count();
            $totalItemsAll = $totalStored + $totalRegistered + $totalClaimed + $totalDisposed + $totalReturned;
            $successRate = $totalItemsAll > 0 ? round(($totalClaimed / $totalItemsAll) * 100, 1) : 0;

            // Item status distribution
            $itemStatusData = [
                'STORED' => $totalStored,
                'REGISTERED' => $totalRegistered,
                'CLAIMED' => $totalClaimed,
                'RETURNED' => $totalReturned,
                'DISPOSED' => $totalDisposed,
            ];
            $itemStatusData = array_filter($itemStatusData);

            // Category distribution
            $catRows = Report::where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->whereNotNull('category_id')
                ->select('category_id', DB::raw('COUNT(*) as count'))
                ->groupBy('category_id')
                ->orderByDesc('count')
                ->limit(7)
                ->get();

            $cats = Category::whereIn('category_id', $catRows->pluck('category_id')->filter())
                ->get(['category_id', 'category_name'])
                ->keyBy('category_id');

            $categoryDistribution = $catRows->map(fn ($r) => [
                'name' => $cats[$r->category_id]->category_name ?? 'Uncategorized',
                'count' => (int) $r->count,
            ])->values()->toArray();

            // Trend data
            $trendData = Report::where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(CASE WHEN report_type = "LOST" THEN 1 ELSE 0 END) as lost'),
                    DB::raw('SUM(CASE WHEN report_type = "FOUND" THEN 1 ELSE 0 END) as found')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(fn ($item) => [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'lost' => (int) $item->lost,
                    'found' => (int) $item->found,
                ])->toArray();

            // Claims data
            $claims = Claim::where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->get(['claim_status']);

            $claimStatusData = [
                'PENDING' => $claims->where('claim_status', 'PENDING')->count(),
                'REJECTED' => $claims->where('claim_status', 'REJECTED')->count(),
                'RELEASED' => $claims->where('claim_status', 'RELEASED')->count(),
            ];

            // Recent activities
            $recent = Report::with('user')
                ->where('company_id', $companyId)
                ->whereBetween('created_at', [$start, $end])
                ->latest('created_at')
                ->limit(15)
                ->get();

            // Generate charts - Always generate if there's any data
            $hasData = ($totalLost + $totalFound) > 0;
            $chartUrls = null;

            if ($hasData) {
                try {
                    $chartUrls = $this->generateChartUrls([
                        'reportType' => ['Lost' => max($totalLost, 0), 'Found' => max($totalFound, 0)],
                        'itemStatus' => $itemStatusData ?: [],
                        'categoryDist' => $categoryDistribution ?: [],
                        'trendData' => $trendData ?: [],
                        'claimStatus' => $claimStatusData ?: [],
                    ]);
                    Log::info('Chart URLs generated', ['urls' => array_keys($chartUrls)]);
                } catch (\Exception $e) {
                    Log::warning('Failed to generate charts: '.$e->getMessage());
                }
            }

            $data = [
                'range' => [$start->format('d M Y'), $end->format('d M Y')],
                'periodType' => ucfirst($periodType),
                'totalLost' => $totalLost,
                'totalFound' => $totalFound,
                'openLost' => $openLost,
                'matched' => $matched,
                'totalStored' => $totalStored,
                'totalRegistered' => $totalRegistered,
                'totalClaimed' => $totalClaimed,
                'totalReturned' => $totalReturned,
                'totalDisposed' => $totalDisposed,
                'successRate' => $successRate,
                'categoryDistribution' => $categoryDistribution,
                'recent' => $recent,
                'chartUrls' => $chartUrls,
                'hasData' => $hasData,
            ];

            Log::info('Generating PDF with data', [
                'hasData' => $hasData,
                'totalLost' => $totalLost,
                'totalFound' => $totalFound,
                'totalReports' => $totalLost + $totalFound,
                'categoriesCount' => count($categoryDistribution),
                'recentActivitiesCount' => count($recent),
                'chartUrls' => $chartUrls ? array_keys($chartUrls) : null,
            ]);

            // Generate PDF
            $pdf = Pdf::loadView('pdf.statistic', $data)
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('enable_local_file_access', true)
                ->setOption('isPhpEnabled', false)
                ->setOption('isHtml5ParserEnabled', true);

            $filename = 'statistics_'.$start->format('Ymd').'-'.$end->format('Ymd').'.pdf';

            Log::info('PDF Generated Successfully', ['filename' => $filename]);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('PDF Generation Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return error response instead of redirect
            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    private function getDateRange($periodType, $selectedDate, $tz)
    {
        $date = Carbon::parse($selectedDate, $tz);

        switch ($periodType) {
            case 'weekly':
                return [
                    'start' => $date->copy()->startOfWeek()->startOfDay(),
                    'end' => $date->copy()->endOfWeek()->endOfDay(),
                ];
            case 'monthly':
                return [
                    'start' => $date->copy()->startOfMonth()->startOfDay(),
                    'end' => $date->copy()->endOfMonth()->endOfDay(),
                ];
            case 'yearly':
                return [
                    'start' => $date->copy()->startOfYear()->startOfDay(),
                    'end' => $date->copy()->endOfYear()->endOfDay(),
                ];
            default:
                return [
                    'start' => $date->copy()->startOfWeek()->startOfDay(),
                    'end' => $date->copy()->endOfWeek()->endOfDay(),
                ];
        }
    }

    private function generateChartUrls($data)
    {
        $baseUrl = 'https://quickchart.io/chart';

        // Ensure we have at least some data
        $totalReports = ($data['reportType']['Lost'] ?? 0) + ($data['reportType']['Found'] ?? 0);
        if ($totalReports === 0) {
            return null;
        }

        $urls = [];

        // 1. Report Type Donut Chart
        if ($totalReports > 0) {
            $reportTypeChart = [
                'type' => 'doughnut',
                'data' => [
                    'labels' => ['Lost Reports', 'Found Reports'],
                    'datasets' => [[
                        'data' => [
                            $data['reportType']['Lost'] ?? 0,
                            $data['reportType']['Found'] ?? 0,
                        ],
                        'backgroundColor' => ['#EF4444', '#10B981'],
                    ]],
                ],
                'options' => [
                    'plugins' => [
                        'legend' => [
                            'position' => 'bottom',
                            'labels' => ['fontSize' => 12],
                        ],
                        'datalabels' => [
                            'color' => '#fff',
                            'font' => ['weight' => 'bold', 'size' => 14],
                        ],
                    ],
                ],
            ];
            $urls['reportType'] = $baseUrl.'?width=350&height=250&c='.urlencode(json_encode($reportTypeChart));
        }

        // 2. Item Status Pie Chart
        if (! empty($data['itemStatus'])) {
            $itemLabels = array_keys($data['itemStatus']);
            $itemValues = array_values($data['itemStatus']);
            $itemColors = ['#3B82F6', '#10B981', '#8B5CF6', '#EF4444', '#F59E0B'];

            $itemStatusChart = [
                'type' => 'pie',
                'data' => [
                    'labels' => $itemLabels,
                    'datasets' => [[
                        'data' => $itemValues,
                        'backgroundColor' => array_slice($itemColors, 0, count($itemLabels)),
                    ]],
                ],
                'options' => [
                    'plugins' => [
                        'legend' => [
                            'position' => 'bottom',
                            'labels' => ['fontSize' => 10],
                        ],
                        'datalabels' => [
                            'color' => '#fff',
                            'font' => ['weight' => 'bold', 'size' => 12],
                        ],
                    ],
                ],
            ];
            $urls['itemStatus'] = $baseUrl.'?width=350&height=250&c='.urlencode(json_encode($itemStatusChart));
        }

        // 3. Category Horizontal Bar Chart
        if (! empty($data['categoryDist'])) {
            $categoryChart = [
                'type' => 'horizontalBar',
                'data' => [
                    'labels' => array_column($data['categoryDist'], 'name'),
                    'datasets' => [[
                        'label' => 'Reports',
                        'data' => array_column($data['categoryDist'], 'count'),
                        'backgroundColor' => '#8B5CF6',
                    ]],
                ],
                'options' => [
                    'plugins' => [
                        'legend' => ['display' => false],
                    ],
                    'scales' => [
                        'xAxes' => [[
                            'ticks' => ['beginAtZero' => true],
                        ]],
                    ],
                ],
            ];
            $urls['category'] = $baseUrl.'?width=350&height=250&c='.urlencode(json_encode($categoryChart));
        }

        // 4. Trend Line Chart
        if (! empty($data['trendData'])) {
            $trendChart = [
                'type' => 'line',
                'data' => [
                    'labels' => array_column($data['trendData'], 'date'),
                    'datasets' => [
                        [
                            'label' => 'Lost',
                            'data' => array_column($data['trendData'], 'lost'),
                            'borderColor' => '#EF4444',
                            'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                            'fill' => true,
                            'tension' => 0.3,
                            'borderWidth' => 2,
                        ],
                        [
                            'label' => 'Found',
                            'data' => array_column($data['trendData'], 'found'),
                            'borderColor' => '#10B981',
                            'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                            'fill' => true,
                            'tension' => 0.3,
                            'borderWidth' => 2,
                        ],
                    ],
                ],
                'options' => [
                    'plugins' => [
                        'legend' => [
                            'position' => 'top',
                            'labels' => ['fontSize' => 11],
                        ],
                    ],
                    'scales' => [
                        'yAxes' => [[
                            'ticks' => ['beginAtZero' => true],
                        ]],
                    ],
                ],
            ];
            $urls['trend'] = $baseUrl.'?width=700&height=200&c='.urlencode(json_encode($trendChart));
        }

        // 5. Claim Status Bar Chart
        $claimTotal = array_sum($data['claimStatus'] ?? []);
        if ($claimTotal > 0) {
            $claimChart = [
                'type' => 'bar',
                'data' => [
                    'labels' => ['Pending', 'Rejected', 'Released'],
                    'datasets' => [[
                        'label' => 'Claims',
                        'data' => array_values($data['claimStatus']),
                        'backgroundColor' => '#3B82F6',
                    ]],
                ],
                'options' => [
                    'plugins' => [
                        'legend' => ['display' => false],
                    ],
                    'scales' => [
                        'yAxes' => [[
                            'ticks' => ['beginAtZero' => true],
                        ]],
                    ],
                ],
            ];
            $urls['claim'] = $baseUrl.'?width=350&height=250&c='.urlencode(json_encode($claimChart));
        }

        return ! empty($urls) ? $urls : null;
    }
}
