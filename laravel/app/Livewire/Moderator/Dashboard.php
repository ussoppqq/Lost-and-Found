<?php

namespace App\Livewire\Moderator;

use App\Models\Item;
use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalItems;

    public $lostReports;

    public $foundReports;

    public $claimedItems;

    public $recentReports;

    public function mount()
    {
        try {
            // Statistik dasar
            $this->totalItems = Item::count();
            $this->lostReports = Report::where('report_type', 'LOST')->count();
            $this->foundReports = Report::where('report_type', 'FOUND')->count();
            $this->claimedItems = Report::where('report_status', 'CLOSED')->count();

            // Laporan terbaru (ambil 5 terakhir)
            $this->recentReports = Report::with(['item', 'user'])
                ->latest()
                ->take(5)
                ->get();

            Log::info('Moderator Dashboard Data Loaded', [
                'totalItems' => $this->totalItems,
                'lostReports' => $this->lostReports,
                'foundReports' => $this->foundReports,
                'claimedItems' => $this->claimedItems,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading moderator dashboard: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.moderator.dashboard')
            ->layout('components.layouts.moderator', [
                'pageTitle' => 'Moderator Dashboard',
                'pageDescription' => 'Overview of lost and found reports',
            ]);
    }
}
