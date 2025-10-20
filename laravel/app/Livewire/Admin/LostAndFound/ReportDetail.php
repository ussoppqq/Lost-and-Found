<?php

namespace App\Livewire\Admin\LostAndFound;

use App\Models\Report;
use Livewire\Component;

class ReportDetail extends Component
{
    public $reportId;
    public $report;

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = Report::with(['user', 'item.category', 'item.photos', 'category'])
            ->findOrFail($reportId);
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