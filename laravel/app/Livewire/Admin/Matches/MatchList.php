<?php

namespace App\Livewire\Admin\Matches;

use App\Models\MatchedItem;
use App\Models\Claim;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatchList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showCreateModal = false;
    public $selectedMatchId = null;
    public $showClaimModal = false;
    public $selectedMatchForClaim = null;
    public $showClaimDetailModal = false;
    public $selectedClaimId = null;

    protected $queryString = ['search', 'statusFilter'];

    protected $listeners = [
        'match-created' => 'handleMatchCreated',
        'claim-processed' => 'handleClaimProcessed',
        'closeClaimDetailModal' => 'closeClaimDetailModal',
        'closeProcessClaimModal' => 'closeClaimModal',
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

            DB::transaction(function () use ($match) {
                // Update match status
                $match->update([
                    'match_status' => 'CONFIRMED',
                    'confirmed_at' => now(),
                    'confirmed_by' => auth()->id(),
                ]);

                // Update report status ke MATCHED
                $match->lostReport->update(['report_status' => 'MATCHED']);
                $match->foundReport->update(['report_status' => 'MATCHED']);

                // ✨ AUTO CREATE CLAIM dengan status PENDING
                Claim::create([
                    'claim_id' => Str::uuid(),
                    'company_id' => auth()->user()->company_id,
                    'user_id' => $match->lostReport->user_id,
                    'match_id' => $match->match_id,
                    'item_id' => $match->foundReport->item_id,
                    'report_id' => $match->lostReport->report_id,
                    'claim_status' => 'PENDING',
                    'pickup_schedule' => now()->addDays(3), // Default 3 hari dari sekarang
                ]);
            });

            session()->flash('success', 'Match confirmed successfully! A claim has been created and ready to process.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to confirm match: ' . $e->getMessage());
        }
    }

    public function rejectMatch($matchId)
    {
        try {
            $match = MatchedItem::with(['lostReport', 'foundReport'])->findOrFail($matchId);

            DB::transaction(function () use ($match) {
                // Update match status to REJECTED
                $match->update([
                    'match_status' => 'REJECTED',
                ]);

                // Kembalikan status reports ke STORED agar bisa di-match lagi
                $match->lostReport->update(['report_status' => 'STORED']);
                $match->foundReport->update(['report_status' => 'STORED']);

                // Soft delete: Hapus match (akan set deleted_at)
                $match->delete();
            });

            session()->flash('success', 'Match rejected! Reports are now available for new matching.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject match: ' . $e->getMessage());
        }
    }

    public function processClaim($matchId)
    {
        $match = MatchedItem::with(['foundReport', 'claim'])->findOrFail($matchId);

        // Cek apakah ada claim
        if (!$match->claim) {
            session()->flash('error', 'No claim found for this match!');
            return;
        }

        // Cek apakah claim sudah diproses
        if ($match->claim->isReleased() || $match->claim->isRejected()) {
            session()->flash('error', 'This claim has already been processed!');
            return;
        }

        $this->selectedMatchForClaim = $matchId;
        $this->showClaimModal = true;
    }

    public function viewClaim($matchId)
    {
        try {
            $match = MatchedItem::with('claim')->findOrFail($matchId);

            if (!$match->hasClaim()) {
                session()->flash('error', 'No claim found for this match!');
                return;
            }

            $this->selectedClaimId = $match->claim->claim_id;
            $this->showClaimDetailModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load claim: ' . $e->getMessage());
        }
    }

    public function closeClaimDetailModal()
    {
        $this->showClaimDetailModal = false;
        $this->selectedClaimId = null;
    }

    public function closeClaimModal()
    {
        $this->showClaimModal = false;
        $this->selectedMatchForClaim = null;
    }

    public function deleteMatch($matchId)
    {
        try {
            $match = MatchedItem::withTrashed()->with(['lostReport', 'foundReport', 'claim'])->findOrFail($matchId);

            // Cek apakah ada claim yang sudah RELEASED
            if ($match->hasClaim() && $match->claim->isReleased()) {
                session()->flash('error', 'Cannot delete match with released claim!');
                return;
            }

            DB::transaction(function () use ($match) {
                // Hapus claim jika ada dan masih pending/rejected
                if ($match->hasClaim()) {
                    $match->claim->delete();
                }

                // Kembalikan status reports ke STORED
                $match->lostReport->update(['report_status' => 'STORED']);
                $match->foundReport->update(['report_status' => 'STORED']);

                // PERMANENT DELETE
                $match->forceDelete();
            });

            session()->flash('success', 'Match permanently deleted! Reports returned to stored status.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete match: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Query SEMUA matches termasuk yang REJECTED
        $matches = MatchedItem::withTrashed()
            ->with([
                'lostReport.category',
                'foundReport.category',
                'foundReport.item',
                'matcher',
                'confirmer',
                'claim'
            ])
            ->when($this->search, function ($query) {
                $query->whereHas('lostReport', function ($q) {
                    $q->where('item_name', 'like', '%' . $this->search . '%')
                        ->orWhere('report_description', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('foundReport', function ($q) {
                        $q->where('item_name', 'like', '%' . $this->search . '%')
                            ->orWhere('report_description', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'REJECTED') {
                    $query->onlyTrashed()->where('match_status', 'REJECTED');
                } else {
                    $query->whereNull('deleted_at')->where('match_status', $this->statusFilter);
                }
            })
            ->latest('matched_at')
            ->paginate(10);

        $stats = [
            'total' => MatchedItem::count(),
            'pending' => MatchedItem::where('match_status', 'PENDING')->count(),
            'confirmed' => MatchedItem::where('match_status', 'CONFIRMED')->count(),
            'rejected' => MatchedItem::onlyTrashed()->where('match_status', 'REJECTED')->count(),
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