<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use App\Models\Claim;

class Dashboard extends Component
{
    public $totalItems;
    public $lostReports;
    public $foundReports;
    public $claimedItems;
    public $recentReports;
    public $totalUsers;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Hitung statistik berdasarkan model yang ada
        $this->totalItems = Item::count();
        $this->lostReports = Report::where('report_type', 'LOST')->count();
        $this->foundReports = Report::where('report_type', 'FOUND')->count();
        $this->claimedItems = Item::where('item_status', 'CLAIMED')->count();
        $this->totalUsers = User::count();
        
        // Ambil 5 laporan terbaru dengan relasi
        $this->recentReports = Report::with(['user', 'item', 'company'])
            ->latest('report_datetime')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin', [
                'title' => 'Admin Dashboard',
                'pageTitle' => 'Lost & Found Management',
                'pageDescription' => 'Manage lost and found items in Kebun Raya'
            ]);
    }
}