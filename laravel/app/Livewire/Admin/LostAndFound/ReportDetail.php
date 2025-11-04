<?php

namespace App\Livewire\Admin\LostAndFound;

use App\Models\Report;
use Livewire\Component;

class ReportDetail extends Component
{
    public $reportId;
    public $report;
    
    // Lightbox state
    public $showLightbox = false;
    public $currentPhotoUrl = '';
    public $currentPhotoIndex = 0;
    public $allPhotos = [];

    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'refresh-detail' => '$refresh',
    ];

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = Report::with(['user', 'item.category', 'item.photos', 'category', 'photos'])
            ->findOrFail($reportId);
    }

    public function openLightbox($photoUrl, $index = 0, $type = 'report')
    {
        $this->currentPhotoUrl = $photoUrl;
        $this->currentPhotoIndex = $index;
        
        // Collect all photos based on type
        if ($type === 'report' && $this->report->photos) {
            $this->allPhotos = $this->report->photos->map(fn($p) => $p->photo_url)->toArray();
        } elseif ($type === 'item' && $this->report->item?->photos) {
            $this->allPhotos = $this->report->item->photos->map(fn($p) => $p->photo_url)->toArray();
        } else {
            $this->allPhotos = [$photoUrl];
        }
        
        $this->showLightbox = true;
    }

    public function closeLightbox()
    {
        $this->showLightbox = false;
        $this->currentPhotoUrl = '';
        $this->currentPhotoIndex = 0;
        $this->allPhotos = [];
    }

    public function nextPhoto()
    {
        if ($this->currentPhotoIndex < count($this->allPhotos) - 1) {
            $this->currentPhotoIndex++;
            $this->currentPhotoUrl = $this->allPhotos[$this->currentPhotoIndex];
        }
    }

    public function previousPhoto()
    {
        if ($this->currentPhotoIndex > 0) {
            $this->currentPhotoIndex--;
            $this->currentPhotoUrl = $this->allPhotos[$this->currentPhotoIndex];
        }
    }

    public function openQuickMatch()
    {
        $this->dispatch('open-quick-match', reportId: $this->reportId);
    }

    public function closeModal()
    {
        $this->dispatch('closeDetailModal')->to(\App\Livewire\Admin\LostAndFound\Index::class);
    }

    public function render()
    {
        return view('livewire.admin.lost-and-found.report-detail');
    }
}