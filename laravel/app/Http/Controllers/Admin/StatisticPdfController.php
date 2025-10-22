<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Post;
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

        $start = Carbon::parse($request->get('start', now($tz)->startOfWeek()), $tz)->startOfDay();
        $end   = Carbon::parse($request->get('end', now($tz)), $tz)->endOfDay();

        // clamp to today
        $today = now($tz)->endOfDay();
        if ($end->gt($today)) $end = $today;

        // headline
        $reports = Report::where('company_id', $companyId)
            ->whereBetween('report_datetime', [$start, $end])->get(['report_type','report_status','report_datetime']);
        $totalLost  = $reports->where('report_type','LOST')->count();
        $totalFound = $reports->where('report_type','FOUND')->count();
        $openLost   = $reports->where('report_type','LOST')->where('report_status','OPEN')->count();
        $matched    = $reports->where('report_type','FOUND')->where('report_status','MATCHED')->count();

        $itemQ = Item::where('company_id', $companyId);
        $totalStored     = (clone $itemQ)->where('item_status','STORED')->count();
        $totalRegistered = (clone $itemQ)->where('item_status','REGISTERED')->count();
        $totalClaimed    = (clone $itemQ)->where('item_status','CLAIMED')->count();
        $totalReturned   = (clone $itemQ)->where('item_status','RETURNED')->count();
        $totalDisposed   = (clone $itemQ)->where('item_status','DISPOSED')->count();
        $totalItemsAll   = $totalStored + $totalClaimed + $totalDisposed + $totalReturned;
        $successRate     = $totalItemsAll > 0 ? round(($totalClaimed / $totalItemsAll) * 100, 1) : 0;

        // category distribution
        $catRows = Report::where('company_id', $companyId)
            ->whereBetween('report_datetime', [$start, $end])
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')->orderByDesc('count')->limit(7)->get();
        $cats = Category::whereIn('category_id', $catRows->pluck('category_id')->filter())->get(['category_id','category_name'])->keyBy('category_id');
        $categoryDistribution = $catRows->map(fn($r)=>[
            'name'=>$cats[$r->category_id]->category_name ?? 'Uncategorized',
            'count'=>(int)$r->count
        ])->values()->toArray();

        // recent
        $recent = Report::with('user')
            ->where('company_id',$companyId)
            ->whereBetween('report_datetime', [$start, $end])
            ->latest('report_datetime')->limit(20)->get();

        $data = [
            'range' => [$start->format('d M Y'), $end->format('d M Y')],
            'totalLost' => $totalLost,
            'totalFound'=> $totalFound,
            'openLost'  => $openLost,
            'matched'   => $matched,
            'totalStored' => $totalStored,
            'totalRegistered' => $totalRegistered,
            'totalClaimed' => $totalClaimed,
            'totalReturned'=> $totalReturned,
            'totalDisposed'=> $totalDisposed,
            'successRate'  => $successRate,
            'categoryDistribution' => $categoryDistribution,
            'recent' => $recent,
        ];

        $pdf = Pdf::loadView('pdf.statistic', $data)->setPaper('a4', 'portrait');
        $filename = 'statistics_'.$start->format('Ymd').'-'.$end->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }
}
    