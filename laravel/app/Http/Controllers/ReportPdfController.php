<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportPdfController extends Controller
{
    /**
     * Download a PDF for a single report (public/simple use).
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, Report $report)
    {

        $report->loadMissing(['item.photos', 'category', 'company', 'user']);

        $reportType = ucfirst(strtolower($report->report_type ?? 'Report'));
        $generatedAt = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i');

        $pdf = Pdf::loadView('pdf.report-receipt', [
            'report' => $report,
            'reportType' => $reportType,
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'portrait');

        $filename = 'Report-'.strtolower($report->report_type).'-'.$report->report_id.'.pdf';

      
        if ($request->query('download') === '1') {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
