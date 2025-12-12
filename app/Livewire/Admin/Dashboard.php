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
    public $stats = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $companyId = auth()->user()->company_id;

        // Samakan dengan perhitungan di App\Livewire\Admin\Items\Index
        $this->stats = [
            'total' => Item::where('company_id', $companyId)->count(),
            'stored' => Item::where('company_id', $companyId)->where('item_status', 'STORED')->count(),
            'claimed' => Item::where('company_id', $companyId)->where('item_status', 'CLAIMED')->count(),
            'disposed' => Item::where('company_id', $companyId)->where('item_status', 'DISPOSED')->count(),
        ];

        // pastikan fallback $totalItems tetap tersedia untuk view yang menggunakan itu
        $this->totalItems = $this->stats['total'];
            
        $this->lostReports = Report::where('company_id', $companyId)->where('report_type', 'LOST')->count();
        $this->foundReports = Report::where('company_id', $companyId)->where('report_type', 'FOUND')->count();
        $this->claimedItems = Item::where('company_id', $companyId)->where('item_status', 'CLAIMED')->count();
        $this->totalUsers = User::where('company_id', $companyId)->count();
        
        // Ambil 5 laporan terbaru dengan relasi
        $this->recentReports = Report::with(['user', 'item', 'company'])
            ->where('company_id', $companyId)
            ->latest('report_datetime')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin', [
                'title' => 'Admin Dashboard',
                'pageTitle' => 'Dashboard',
                'pageDescription' => 'Overview of Lost & Found Management System'
            ]);
    }
}