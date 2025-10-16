<?php

namespace App\Livewire\Admin\Matches;

use App\Models\Match;
use Livewire\Component;
use App\Models\MatchedItem;

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
        $this->match = MatchedItem::with([
            'lostReport.category',
            'foundReport.category',
            'matcher',
            'confirmer'
        ])->findOrFail($this->matchId);
    }

    public function confirmMatch()
    {
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
        session()->flash('success', 'Match berhasil dikonfirmasi!');
    }

    public function rejectMatch()
    {
        $this->match->update([
            'match_status' => 'REJECTED',
        ]);

        $this->loadMatch();
        $this->dispatch('match-updated');
        session()->flash('success', 'Match berhasil ditolak!');
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