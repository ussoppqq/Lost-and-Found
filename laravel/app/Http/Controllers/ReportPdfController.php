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
        $report->loadMissing(['item.photos', 'item.category', 'item.post', 'item.claims.user', 'category', 'user']);

        $pdf = Pdf::loadView('pdf.report-detail', [
            'report' => $report,
        ])->setPaper('a4', 'portrait');

        $filename = 'Report-Detail-'.strtoupper(substr($report->report_id, 0, 8)).'.pdf';

        if ($request->query('download') === '1') {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
