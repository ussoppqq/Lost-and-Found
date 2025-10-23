<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LostPdfController extends Controller
{
    public function download(Request $request, Report $report)
    {
        // Opsional: batasi akses berdasar company
        // abort_unless($report->company_id === session('company_id'), 403);

        $pdf = Pdf::loadView('pdf.report-receipt', [
            'report' => $report->loadMissing(['category', 'company']),
        ])->setPaper('a4', 'portrait');

        $name = 'Report-'.$report->report_type.'-'.$report->report_id.'.pdf';
        return $pdf->download($name);
    }
}
