<?php

namespace App\Livewire\Admin\Matches;

use App\Models\Claim;
use Livewire\Component;

class ClaimDetail extends Component
{
    public $claimId;
    public $claim;

    public function mount($claimId)
    {
        $this->claimId = $claimId;
        $this->claim = Claim::with([
            'match.lostReport.user',
            'match.foundReport.user',
            'item.category',
            'item.photos',
            'report',
            'user',
            'processor' 
        ])->findOrFail($claimId);
    }

    public function closeModal()
    {
        $this->dispatch('closeClaimDetailModal');
    }

    public function render()
    {
        return view('livewire.admin.matches.claim-detail');
    }
}