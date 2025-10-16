<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportPdfController extends Controller
{
    public function download(Report $report)
    {
        $report->load([
            'user',
            'company',
            'item.photos',
            'item.category',
            'item.post',
            'item.claims.user',
        ]);

        $pdf = \PDF::loadView('reports.pdf', ['report' => $report])
                   ->setPaper('a4', 'portrait');

        $filename = 'Report-' . ($report->report_id ?? $report->id) . '.pdf';
        return $pdf->download($filename);
    }
}
