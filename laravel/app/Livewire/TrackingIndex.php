<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Tracking Barang')]
class TrackingIndex extends Component
{
    public $trackingType = 'report_id'; // 'report_id' atau 'phone'
    
    #[Validate('required_if:trackingType,report_id|string|size:36', message: [
        'required_if' => 'Report ID wajib diisi',
        'size' => 'Format Report ID tidak valid'
    ])]
    public $reportId = '';

    #[Validate('required_if:trackingType,phone|string|min:10|max:15', message: [
        'required_if' => 'Nomor HP wajib diisi',
        'min' => 'Nomor HP minimal 10 digit',
        'max' => 'Nomor HP maksimal 15 digit'
    ])]
    public $phoneNumber = '';

    public $showResults = false;
    public $reports = [];
    public $errorMessage = '';

    public function trackByReportId()
    {
        $this->trackingType = 'report_id';
        $this->validate([
            'reportId' => 'required|string|size:36'
        ], [
            'reportId.required' => 'Report ID wajib diisi',
            'reportId.size' => 'Format Report ID tidak valid (harus 36 karakter)'
        ]);
        
        $this->errorMessage = '';
        $this->showResults = false;

        try {
            $report = Report::with([
                'item.photos', 
                'item.category', 
                'item.post', 
                'company',
                'user'
            ])->find($this->reportId);

            if (!$report) {
                $this->errorMessage = 'Report ID tidak ditemukan. Pastikan Anda memasukkan ID yang benar dari PDF receipt.';
                return;
            }

            $this->reports = collect([$report]);
            $this->showResults = true;

        } catch (\Exception $e) {
            \Log::error('Error tracking by report ID: ' . $e->getMessage());
            $this->errorMessage = 'Terjadi kesalahan saat mencari laporan. Silakan coba lagi.';
        }
    }

    public function trackByPhone()
    {
        $this->trackingType = 'phone';
        $this->validate([
            'phoneNumber' => 'required|string|min:10|max:15'
        ], [
            'phoneNumber.required' => 'Nomor HP wajib diisi',
            'phoneNumber.min' => 'Nomor HP minimal 10 digit',
            'phoneNumber.max' => 'Nomor HP maksimal 15 digit'
        ]);
        
        $this->errorMessage = '';
        $this->showResults = false;

        try {
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

            // Merge both collections
            $this->reports = $guestReports->merge($userReports)->sortByDesc('created_at');

            if ($this->reports->isEmpty()) {
                $this->errorMessage = 'Tidak ada laporan ditemukan dengan nomor HP tersebut.';
                return;
            }

            $this->showResults = true;

        } catch (\Exception $e) {
            \Log::error('Error tracking by phone: ' . $e->getMessage());
            $this->errorMessage = 'Terjadi kesalahan saat mencari laporan. Silakan coba lagi.';
        }
    }

    public function resetSearch()
    {
        $this->reset(['reportId', 'phoneNumber', 'showResults', 'reports', 'errorMessage']);
        $this->trackingType = 'report_id';
    }

    public function switchTrackingType($type)
    {
        $this->trackingType = $type;
        $this->reset(['reportId', 'phoneNumber', 'showResults', 'reports', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.tracking-index')
            ->layout('components.layouts.user');
    }
}