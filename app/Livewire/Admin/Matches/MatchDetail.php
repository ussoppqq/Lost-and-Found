<?php

namespace App\Livewire\Admin\Matches;

use App\Models\MatchedItem;
use Livewire\Component;

class MatchDetail extends Component
{
    public $matchId;
    public $match;
    public $showImageModal = false;
    public $currentImage = '';
    public $currentImageTitle = '';

    public function mount($matchId)
    {
        $this->matchId = $matchId;
        $this->loadMatch();
    }

    public function loadMatch()
    {
        // PENTING: Gunakan withTrashed() agar bisa load rejected matches
        $this->match = MatchedItem::withTrashed()
            ->with([
                'lostReport.category',
                'foundReport.category',
                'matcher',
                'confirmer'
            ])
            ->findOrFail($this->matchId);
    }

    public function confirmMatch()
    {
        // Validasi: tidak bisa confirm match yang sudah rejected
        if ($this->match->trashed()) {
            session()->flash('error', 'Cannot confirm a rejected match! Please create a new match instead.');
            return;
        }

        $this->match->update([
            'match_status' => 'CONFIRMED',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);

        // Update report status
        $this->match->lostReport->update(['report_status' => 'MATCHED']);
        $this->match->foundReport->update(['report_status' => 'MATCHED']);

        $this->loadMatch();
        $this->dispatch('match-updated');
        session()->flash('success', 'Match successfully confirmed!');
    }

    public function rejectMatch()
    {
        // Validasi: tidak bisa reject match yang sudah rejected
        if ($this->match->trashed()) {
            session()->flash('error', 'This match is already rejected!');
            return;
        }

        $this->match->update([
            'match_status' => 'REJECTED',
        ]);

        // Kembalikan status reports ke STORED
        $this->match->lostReport->update(['report_status' => 'STORED']);
        $this->match->foundReport->update(['report_status' => 'STORED']);

        // Soft delete
        $this->match->delete();

        $this->loadMatch();
        $this->dispatch('match-updated');
        session()->flash('success', 'Match rejected! Reports are now available for new matching.');
    }

    public function openImageModal($imageUrl, $title)
    {
        $this->currentImage = $imageUrl;
        $this->currentImageTitle = $title;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = '';
        $this->currentImageTitle = '';
    }

    public function render()
    {
        return view('livewire.admin.matches.match-detail');
    }
}