<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Tracking Barang')]
class TrackingIndex extends Component
{
    #[Validate('required|string|min:10|max:15', message: [
        'required' => 'Nomor HP wajib diisi',
        'min' => 'Nomor HP minimal 10 digit',
        'max' => 'Nomor HP maksimal 15 digit'
    ])]
    public string $phoneNumber = '';

    public bool $showResults = false;
    public $reports = [];
    public string $errorMessage = '';

    public function trackItem()
    {
        $this->validate();

        $this->errorMessage = '';
        $this->showResults = false;

        // Get reports by reporter phone (guest reports)
        $guestReports = Report::where('reporter_phone', $this->phoneNumber)
            ->with(['item.photos', 'item.category', 'item.post', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get reports by registered user phone
        $userReports = Report::whereHas('user', function($query) {
                $query->where('phone_number', $this->phoneNumber);
            })
            ->with(['item.photos', 'item.category', 'item.post', 'user', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Merge both collections and remove duplicates by report_id
        $merged = $guestReports->merge($userReports)->unique('report_id')->sortByDesc('created_at')->values();

        if ($merged->isEmpty()) {
            $this->errorMessage = 'Tidak ada laporan ditemukan dengan nomor HP tersebut';
            $this->reports = [];
            return;
        }

        $this->reports = $merged;
        $this->showResults = true;
    }

    public function resetSearch()
    {
        $this->reset(['phoneNumber', 'showResults', 'reports', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.tracking-index');
    }
}