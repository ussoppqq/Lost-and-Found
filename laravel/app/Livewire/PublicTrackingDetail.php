<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Public Tracking Detail')]
class PublicTrackingDetail extends Component
{
    public $reportId;
    public $report;
    public $showImageModal = false;
    public $currentImage = '';

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->loadReport();
    }

    public function loadReport()
    {
        try {
            // Only load basic information, no sensitive data
            $this->report = Report::with([
                'item.photos',
                'item.category',
                'category'
            ])->find($this->reportId);

            if (!$this->report) {
                abort(404, 'Report not found');
            }
        } catch (\Exception $e) {
            \Log::error('Error loading report: ' . $e->getMessage());
            abort(404, 'Report not found');
        }
    }

    public function openImageModal($imageSrc)
    {
        $this->currentImage = $imageSrc;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = '';
    }

    public function render()
    {
        return view('livewire.public-tracking-detail')
            ->layout('components.layouts.user');
    }
}
