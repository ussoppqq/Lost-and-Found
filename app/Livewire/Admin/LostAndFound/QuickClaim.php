<?php

namespace App\Livewire\Admin\LostAndFound;

use App\Models\Claim;
use App\Models\MatchedItem;
use App\Models\Report;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuickClaim extends Component
{
    use WithFileUploads;

    public $sourceReport;
    public $targetReport;
    public $lostReport;
    public $foundReport;
    
    public $brand = '';
    public $color = '';
    public $claimNotes = '';
    public $tempPhotos = [];
    public $uploadKey = 0;

    protected $rules = [
        'brand' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:255',
        'claimNotes' => 'nullable|string|max:1000',
        'tempPhotos.*' => 'nullable|image|max:5120',
    ];

    protected $messages = [
        'tempPhotos.*.image' => 'Each file must be an image',
        'tempPhotos.*.max' => 'Each photo must not exceed 5MB',
    ];

    public function mount($sourceReport, $targetReport)
    {
        $this->sourceReport = $sourceReport;
        $this->targetReport = $targetReport;
        
        if ($sourceReport->report_type === 'LOST') {
            $this->lostReport = $sourceReport;
            $this->foundReport = $targetReport;
        } else {
            $this->foundReport = $sourceReport;
            $this->lostReport = $targetReport;
        }
        
        if ($this->foundReport->item) {
            $this->brand = $this->foundReport->item->brand ?? $this->foundReport->item->item_name ?? '';
            $this->color = $this->foundReport->item->color ?? '';
        }
        
        Log::info('QuickClaim modal mounted', [
            'lostReportId' => $this->lostReport->report_id,
            'foundReportId' => $this->foundReport->report_id,
            'has_item' => $this->foundReport->item_id ? 'yes' : 'no',
        ]);
    }

    public function updatedTempPhotos()
    {
        $this->validate([
            'tempPhotos.*' => 'image|max:5120',
        ]);
    }

    public function removePhoto($index)
    {
        if (isset($this->tempPhotos[$index])) {
            unset($this->tempPhotos[$index]);
            $this->tempPhotos = array_values($this->tempPhotos);
            $this->uploadKey++;
            
            Log::info('Photo removed from claim', ['index' => $index]);
        }
    }

    public function processClaim()
    {
        $this->validate();

        if (!$this->foundReport->item_id) {
            session()->flash('error', 'Cannot process claim: Found report must have a registered item!');
            Log::error('Claim processing failed: No item registered', [
                'foundReportId' => $this->foundReport->report_id,
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $uploadedPhotos = [];
            if (!empty($this->tempPhotos)) {
                foreach ($this->tempPhotos as $photo) {
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('claims', $filename, 'public');
                    $uploadedPhotos[] = $path;
                }
                
                Log::info('Claim photos uploaded', ['count' => count($uploadedPhotos)]);
            }

            $match = MatchedItem::create([
                'match_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'lost_report_id' => $this->lostReport->report_id,
                'found_report_id' => $this->foundReport->report_id,
                'matched_by' => auth()->id(),
                'match_status' => 'CONFIRMED',
                'matched_at' => now(),
            ]);

            Log::info('Match created with CONFIRMED status', [
                'match_id' => $match->match_id,
            ]);

            $claim = Claim::create([
                'claim_id' => Str::uuid(),
                'company_id' => auth()->user()->company_id,
                'user_id' => $this->lostReport->user_id, 
                'match_id' => $match->match_id,
                'item_id' => $this->foundReport->item_id, 
                'report_id' => $this->lostReport->report_id, 
                'claim_status' => 'RELEASED', 
                'brand' => $this->brand,
                'color' => $this->color,
                'claim_notes' => $this->claimNotes,
                'claim_photos' => !empty($uploadedPhotos) ? $uploadedPhotos : null,
                'pickup_schedule' => now(), 
            ]);

            Log::info('Claim created with RELEASED status', [
                'claim_id' => $claim->claim_id,
            ]);

            $this->lostReport->update(['report_status' => 'CLOSED']);
            $this->foundReport->update(['report_status' => 'CLOSED']);

            Log::info('Reports updated to CLOSED');

            if ($this->foundReport->item) {
                $this->foundReport->item->update(['item_status' => 'CLAIMED']);
                Log::info('Item status updated to CLAIMED');
            }

            DB::commit();

            session()->flash('success', 'Claim processed successfully! Item has been released to the owner.');
            
            Log::info('Claim processing completed successfully');
            
            return redirect()->route('admin.matches');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process claim', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Failed to process claim: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatch('claim-modal-closed');
        
        Log::info('QuickClaim modal close event dispatched');
    }

    public function render()
    {
        return view('livewire.admin.lost-and-found.quick-claim');
    }
}