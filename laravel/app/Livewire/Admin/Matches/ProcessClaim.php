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
        $this->match = MatchedItem::with(['lostReport.user', 'foundReport.item'])->findOrFail($matchId);
        
        // VALIDASI: Found report HARUS punya item
        if (!$this->match->foundReport->item_id) {
            session()->flash('error', 'Cannot process claim: Found report must have a registered item!');
            $this->closeModal();
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

            // Create claim dengan status RELEASED (langsung approved)
            Claim::create([
                'claim_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'user_id' => $this->match->lostReport->user_id, 
                'match_id' => $this->matchId,
                'item_id' => $this->match->foundReport->item_id, 
                'report_id' => $this->match->lostReport->report_id, 
                'claim_status' => 'RELEASED', 
                'brand' => $this->brand,
                'color' => $this->color,
                'claim_notes' => $this->claimNotes,
                'claim_photos' => $uploadedPhotos,
                'pickup_schedule' => now(), 
            ]);

            // Update report status ke CLOSED
            $this->match->lostReport->update(['report_status' => 'CLOSED']);
            $this->match->foundReport->update(['report_status' => 'CLOSED']);

            // Update item status ke CLAIMED (bukan RETURNED karena sudah diserahkan)
            if ($this->match->foundReport->item) {
                $this->match->foundReport->item->update(['item_status' => 'CLAIMED']);
            }

            DB::commit();

            session()->flash('success', 'Claim processed successfully! Item has been released to the owner.');
            
            // Emit event untuk refresh parent dan tutup modal
            $this->dispatch('claim-processed');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to process claim: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        // Dispatch event ke parent component untuk menutup modal
        $this->dispatch('closeProcessClaimModal');
    }

    public function render()
    {
        return view('livewire.admin.matches.process-claim');
    }
}