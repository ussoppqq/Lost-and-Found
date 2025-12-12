<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportReceiptController extends Controller
{
    public function generatePdf(Request $request, string $reportId)
    {
        // Validasi signed URL
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired link');
        }

        $report = Report::with(['category', 'user'])
            ->findOrFail($reportId);

        $data = [
            'report' => $report,
            'reportType' => $report->report_type === 'LOST' ? 'Lost Item' : 'Found Item',
            'generatedAt' => now()->timezone('Asia/Jakarta')->format('d M Y H:i'),
        ];

        $pdf = Pdf::loadView('pdf.report-receipt', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'receipt_' . strtolower($report->report_type) . '_' . $report->report_id . '.pdf';

        return $pdf->download($filename);
    }
} 