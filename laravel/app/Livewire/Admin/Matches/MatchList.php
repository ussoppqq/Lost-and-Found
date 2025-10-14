<?php

namespace App\Livewire\Admin\Matches;

use App\Models\MatchedItem;
use Livewire\Component;
use Livewire\WithPagination;


class MatchList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showCreateModal = false;
    public $selectedMatchId = null;

    protected $queryString = ['search', 'statusFilter'];
    
    // Listener untuk refresh setelah match dibuat
    protected $listeners = ['match-created' => 'handleMatchCreated'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function handleMatchCreated()
    {
        $this->showCreateModal = false;
        $this->dispatch('$refresh');
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function viewMatch($matchId)
    {
        $this->selectedMatchId = $matchId;
    }

    public function closeDetailModal()
    {
        $this->selectedMatchId = null;
        $this->dispatch('$refresh');
    }

    public function confirmMatch($matchId)
    {
        try {
            $match = MatchedItem::findOrFail($matchId);
            
            $match->update([
                'match_status' => 'CONFIRMED',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Update report status
            $match->lostReport->update(['report_status' => 'MATCHED']);
            $match->foundReport->update(['report_status' => 'MATCHED']);

            session()->flash('success', 'Match berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal konfirmasi match: ' . $e->getMessage());
        }
    }

    public function rejectMatch($matchId)
    {
        try {
            $match = MatchedItem::findOrFail($matchId);
            
            $match->update([
                'match_status' => 'REJECTED',
            ]);

            session()->flash('success', 'Match berhasil ditolak!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal tolak match: ' . $e->getMessage());
        }
    }

    public function deleteMatch($matchId)
    {
        try {
            MatchedItem::findOrFail($matchId)->delete();
            session()->flash('success', 'Match berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus match: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $matches = MatchedItem::with(['lostReport.category', 'foundReport.category', 'matcher', 'confirmer'])
            ->when($this->search, function($query) {
                $query->whereHas('lostReport', function($q) {
                    $q->where('item_name', 'like', '%'.$this->search.'%')
                      ->orWhere('report_description', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('foundReport', function($q) {
                    $q->where('item_name', 'like', '%'.$this->search.'%')
                      ->orWhere('report_description', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('match_status', $this->statusFilter);
            })
            ->latest('matched_at')
            ->paginate(10);

        $stats = [
            'total' => MatchedItem::count(),
            'pending' => MatchedItem::where('match_status', 'PENDING')->count(),
            'confirmed' => MatchedItem::where('match_status', 'CONFIRMED')->count(),
            'rejected' => MatchedItem::where('match_status', 'REJECTED')->count(),
        ];

        return view('livewire.admin.matches.match-list', [
            'matches' => $matches,
            'stats' => $stats,
        ])->layout('components.layouts.admin', [
            'title' => 'Matches',
            'pageTitle' => 'Matches Management',
            'pageDescription' => 'Manage all matches in the system'
        ]);
    }
}