<?php

namespace App\Livewire\Admin\Matches;

use App\Models\Claim;
use App\Models\MatchedItem;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProcessClaim extends Component
{
    use WithFileUploads;

    public $matchId;
    public $match;
    public $brand = '';
    public $color = '';
    public $claimNotes = '';
    public $tempPhotos = [];

    protected $rules = [
        'brand' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:255',
        'claimNotes' => 'nullable|string|max:1000',
        'tempPhotos.*' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'tempPhotos.*.image' => 'Each file must be an image',
        'tempPhotos.*.max' => 'Each photo must not exceed 2MB',
    ];

    public function mount($matchId)
    {
        $this->matchId = $matchId;
        $this->match = MatchedItem::with(['lostReport', 'foundReport.item'])->findOrFail($matchId);
        
        // VALIDASI: Found report HARUS punya item
        if (!$this->match->foundReport->item_id) {
            session()->flash('error', 'Cannot process claim: Found report must have a registered item!');
            $this->dispatch('claim-processed');
            return;
        }

        // Pre-fill data dari found item jika ada
        if ($this->match->foundReport->item) {
            $this->brand = $this->match->foundReport->item->item_name ?? '';
        }
    }

    public function updatedTempPhotos()
    {
        $this->validate([
            'tempPhotos.*' => 'image|max:2048',
        ]);
    }

    public function removePhoto($index)
    {
        array_splice($this->tempPhotos, $index, 1);
    }

    public function processClaim()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Upload photos
            $uploadedPhotos = [];
            foreach ($this->tempPhotos as $photo) {
                $path = $photo->store('claims', 'public');
                $uploadedPhotos[] = $path;
            }

            // Create claim
            Claim::create([
                'claim_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'user_id' => $this->match->lostReport->user_id, // User yang kehilangan
                'match_id' => $this->matchId,
                'item_id' => $this->match->foundReport->item_id, // REQUIRED: Item dari found report
                'report_id' => $this->match->lostReport->report_id, // Lost report reference
                'claim_status' => 'PENDING',
                'brand' => $this->brand,
                'color' => $this->color,
                'claim_notes' => $this->claimNotes,
                'claim_photos' => $uploadedPhotos,
            ]);

            // Update report status ke CLOSED
            $this->match->lostReport->update(['report_status' => 'CLOSED']);
            $this->match->foundReport->update(['report_status' => 'CLOSED']);

            // Update item status ke CLAIMED
            if ($this->match->foundReport->item) {
                $this->match->foundReport->item->update(['item_status' => 'CLAIMED']);
            }

            DB::commit();

            session()->flash('success', 'Claim processed successfully!');
            $this->dispatch('claim-processed');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to process claim: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatch('closeClaimModal')->to(\App\Livewire\Admin\Matches\MatchList::class);
    }

    public function render()
    {
        return view('livewire.admin.matches.process-claim');
    }
}