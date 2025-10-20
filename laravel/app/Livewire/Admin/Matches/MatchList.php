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
    public $showClaimModal = false;
    public $selectedMatchForClaim = null;

    protected $queryString = ['search', 'statusFilter'];
    
    protected $listeners = [
        'match-created' => 'handleMatchCreated',
        'claim-processed' => 'handleClaimProcessed',
    ];

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

    public function handleClaimProcessed()
    {
        $this->showClaimModal = false;
        $this->selectedMatchForClaim = null;
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
            $match = MatchedItem::with(['lostReport', 'foundReport'])->findOrFail($matchId);
            
            // VALIDASI: Found report HARUS punya item
            if (!$match->foundReport->item_id) {
                session()->flash('error', 'Cannot confirm match: Found report must have a registered item first!');
                return;
            }

            $match->update([
                'match_status' => 'CONFIRMED',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Update report status
            $match->lostReport->update(['report_status' => 'MATCHED']);
            $match->foundReport->update(['report_status' => 'MATCHED']);

            session()->flash('success', 'Match confirmed successfully! You can now process the claim.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to confirm match: ' . $e->getMessage());
        }
    }

    public function rejectMatch($matchId)
    {
        try {
            $match = MatchedItem::findOrFail($matchId);
            
            $match->update([
                'match_status' => 'REJECTED',
            ]);

            session()->flash('success', 'Match rejected successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject match: ' . $e->getMessage());
        }
    }

    public function processClaim($matchId)
    {
        // Validasi sebelum buka modal
        $match = MatchedItem::with(['foundReport'])->findOrFail($matchId);
        
        if (!$match->foundReport->item_id) {
            session()->flash('error', 'Cannot process claim: Found report must have a registered item first!');
            return;
        }

        // Check jika sudah ada claim
        if ($match->hasClaim()) {
            session()->flash('error', 'This match already has a claim!');
            return;
        }

        $this->selectedMatchForClaim = $matchId;
        $this->showClaimModal = true;
    }

    public function closeClaimModal()
    {
        $this->showClaimModal = false;
        $this->selectedMatchForClaim = null;
    }

    public function deleteMatch($matchId)
    {
        try {
            MatchedItem::findOrFail($matchId)->delete();
            session()->flash('success', 'Match deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete match: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $matches = MatchedItem::with([
            'lostReport.category', 
            'foundReport.category', 
            'foundReport.item', // Eager load item dari found report
            'matcher', 
            'confirmer',
            'claim'
        ])
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
            'pageTitle' => 'Match Management',
            'pageDescription' => 'Manage all matches in the system'
        ]);
    }
}