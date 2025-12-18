<?php

namespace App\Livewire\Admin\Matches;

use App\Models\Claim;
use App\Models\MatchedItem;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProcessClaim extends Component
{
    use WithFileUploads;

    public $matchId;
    public $match;
    public $claim;
    public $brand = '';
    public $color = '';
    public $claimNotes = '';
    public $rejectionReason = '';
    public $tempPhotos = [];
    public $showRejectModal = false;

    protected function rules()
    {
        return [
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'claimNotes' => 'nullable|string|max:1000',
            'tempPhotos.*' => 'nullable|image|max:5120',
            'rejectionReason' => $this->showRejectModal ? 'required|string|min:10|max:500' : 'nullable',
        ];
    }

    protected $messages = [
        'tempPhotos.*.image' => 'Each file must be an image',
        'tempPhotos.*.max' => 'Each photo must not exceed 5MB',
        'rejectionReason.required' => 'Rejection reason is required',
        'rejectionReason.min' => 'Rejection reason must be at least 10 characters',
    ];

    public function mount($matchId)
    {
        $this->matchId = $matchId;
        $this->match = MatchedItem::with([
            'lostReport.user', 
            'foundReport.item', 
            'claim'
        ])->findOrFail($matchId);
        
        // Load existing claim
        $this->claim = $this->match->claim;
        
        if (!$this->claim) {
            session()->flash('error', 'No claim found for this match!');
            $this->closeModal();
            return;
        }

        // Cek apakah claim masih PENDING
        if (!$this->claim->isPending()) {
            session()->flash('error', 'This claim has already been processed!');
            $this->closeModal();
            return;
        }

        // Pre-fill data dari existing claim atau item
        $this->brand = $this->claim->brand ?? $this->match->foundReport->item->item_name ?? '';
        $this->color = $this->claim->color ?? '';
        $this->claimNotes = $this->claim->claim_notes ?? '';
    }

    public function updatedTempPhotos()
    {
        $this->validate([
            'tempPhotos.*' => 'image|max:5120',
        ]);
    }

    public function removePhoto($index)
    {
        array_splice($this->tempPhotos, $index, 1);
    }

    public function releaseClaim()
    {
        $this->validate([
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'claimNotes' => 'nullable|string|max:1000',
            'tempPhotos.*' => 'nullable|image|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Upload photos
            $existingPhotos = $this->claim->claim_photos ?? [];
            $uploadedPhotos = $existingPhotos;
            
            foreach ($this->tempPhotos as $photo) {
                $path = $photo->store('claims', 'public');
                $uploadedPhotos[] = $path;
            }

            // Update claim ke RELEASED
            $this->claim->update([
                'claim_status' => 'RELEASED',
                'brand' => $this->brand,
                'color' => $this->color,
                'claim_notes' => $this->claimNotes,
                'claim_photos' => $uploadedPhotos,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update reports ke CLOSED
            $this->match->lostReport->update(['report_status' => 'CLOSED']);
            $this->match->foundReport->update(['report_status' => 'CLOSED']);

            // Update item ke CLAIMED
            if ($this->match->foundReport->item) {
                $this->match->foundReport->item->update(['item_status' => 'CLAIMED']);
            }

            DB::commit();

            session()->flash('success', 'Claim released successfully! Item has been returned to the owner.');
            $this->dispatch('claim-processed');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to release claim: ' . $e->getMessage());
        }
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectionReason = '';
        $this->resetValidation('rejectionReason');
    }

    public function rejectClaim()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Update claim ke REJECTED
            $this->claim->update([
                'claim_status' => 'REJECTED',
                'rejection_reason' => $this->rejectionReason,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update reports ke CLOSED (case selesai, tidak bisa match/claim lagi)
            $this->match->lostReport->update(['report_status' => 'CLOSED']);
            $this->match->foundReport->update(['report_status' => 'CLOSED']);

            // Item kembali ke STORED (bisa di-match ke user lain)
            if ($this->match->foundReport->item) {
                $this->match->foundReport->item->update(['item_status' => 'STORED']);
            }

            DB::commit();

            session()->flash('success', 'Claim rejected successfully. Reports have been closed and item returned to storage.');
            $this->dispatch('claim-processed');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to reject claim: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatch('closeProcessClaimModal');
    }

    public function render()
    {
        return view('livewire.admin.matches.process-claim');
    }
}