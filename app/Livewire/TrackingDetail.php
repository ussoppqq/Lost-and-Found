<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Detail Tracking')]
class TrackingDetail extends Component
{
    public $reportId;
    public $report;
    public $showImageModal = false;
    public $modalImageSrc = '';

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->loadReport();
    }

    public function loadReport()
    {
        try {
            $this->report = Report::with([
                'photos',              // Load report photos (from report_photos table)
                'item.photos',         // Load item photos
                'item.category',
                'item.post',
                'item.claims.user',
                'company',
                'user'
            ])->find($this->reportId);

            if (!$this->report) {
                // Redirect back to tracking page with error message
                return redirect()->route('tracking.index')
                    ->with('error', 'Report ID "' . substr($this->reportId, 0, 12) . '..." tidak ditemukan. Pastikan Anda memasukkan Report ID yang benar dari PDF receipt Anda.');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading report: ' . $e->getMessage());
            // Redirect back to tracking page with error message
            return redirect()->route('tracking.index')
                ->with('error', 'Terjadi kesalahan saat mencari laporan. Pastikan Anda memasukkan Report ID yang valid dari PDF receipt Anda.');
        }
    }

    public function openImageModal($imageSrc)
    {
        $this->modalImageSrc = $imageSrc;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->modalImageSrc = '';
    }

    /** ====== AKSI LIVEWIRE ====== */

    // Cetak via print CSS
    public function printPage()
    {
        // Dispatch event ke browser -> window.print()
        $this->dispatch('do-print');
    }

    // Unduh PDF via redirect ke route Dompdf
    public function downloadPdf()
    {
        // Pass the model instance; with getRouteKeyName this resolves to report_id
        $this->redirectRoute('reports.pdf', $this->report);
    }


    public function render()
    {
        return view('livewire.tracking-detail')
            ->layout('components.layouts.user');
    }
}
