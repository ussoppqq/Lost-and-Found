<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Detail Tracking')]
class TrackingDetail extends Component
{
    public string $reportId;
    public ?Report $report = null;
    public bool $showImageModal = false;
    public string $modalImageSrc = '';

    /**
     * Mount - dipanggil saat component pertama kali load
     */
    public function mount(string $reportId)
    {
        $this->reportId = $reportId;
        $this->loadReport();
    }

    /**
     * Load report dari database
     */
    public function loadReport(): void
    {
        try {
            $this->report = Report::with([
                'item.photos', 
                'item.category', 
                'item.post',
                'item.claims.user',
                'company',
                'user'
            ])->find($this->reportId);

            if (!$this->report) {
                abort(404, 'Report tidak ditemukan');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading report: ' . $e->getMessage());
            abort(404, 'Report tidak ditemukan');
        }
    }

    /**
     * Buka image modal
     */
    public function openImageModal($imageSrc): void
    {
        $this->modalImageSrc = $imageSrc;
        $this->showImageModal = true;
    }

    /**
     * Tutup image modal
     */
    public function closeImageModal(): void
    {
        $this->showImageModal = false;
        $this->modalImageSrc = '';
    }

    /**
     * Render view
     */
    public function render()
    {
        return view('livewire.tracking-detail');
    }
}
