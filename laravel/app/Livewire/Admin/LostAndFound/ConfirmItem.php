<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Item;
use App\Models\Report;
use App\Models\Category;
use App\Models\Post;
use App\Models\ItemPhoto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConfirmItem extends Component
{
    use WithFileUploads;

    public $reportId = null;
    public $report = null;
    public $showModal = false;

    // Item fields untuk confirmation
    public $brand;
    public $color;
    public $storage;
    public $item_status = 'STORED';
    public $sensitivity_level = 'NORMAL';
    public $post_id;
    public $item_description;

    // Photos
    public $photos = [];
    public $newPhotos = [];
    public $uploadKey = 0;
    public $reportPhotos = [];

    // Lightbox state
    public $showLightbox = false;
    public $currentPhotoUrl = '';
    public $currentPhotoIndex = 0;
    public $lightboxPhotos = [];

    protected $listeners = [
        'open-confirm-item-modal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:255',
            'item_status' => 'required|in:STORED,CLAIMED,RETURNED,DISPOSED',
            'sensitivity_level' => 'required|in:NORMAL,RESTRICTED',
            'post_id' => 'required|exists:posts,post_id',
            'item_description' => 'nullable|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:25600',
            'newPhotos' => 'nullable|array',
            'newPhotos.*' => 'nullable|image|max:25600',
        ];
    }

    protected function messages()
    {
        return [
            'photos.*.image' => 'All files must be valid images (jpg, png, jpeg, gif).',
            'photos.*.max' => 'Each photo must not exceed 25MB.',
            'photos.max' => 'You can upload a maximum of 5 photos.',
            'newPhotos.*.image' => 'All files must be valid images.',
            'newPhotos.*.max' => 'Each photo must not exceed 25MB.',
            'post_id.required' => 'Please select a storage location.',
            'item_status.required' => 'Please select an item status.',
        ];
    }

    public function updatedNewPhotos(): void
    {
        $this->validateOnly('newPhotos.*');

        $allowed = max(0, 5 - count($this->photos));
        foreach (array_slice($this->newPhotos, 0, $allowed) as $file) {
            $this->photos[] = $file;
        }

        $this->newPhotos = [];
        $this->uploadKey++;

        Log::info('Photos updated in ConfirmItem', [
            'total_photos' => count($this->photos),
            'upload_key' => $this->uploadKey
        ]);
    }

    // Lightbox methods
    public function openLightbox($photoUrl, $index = 0)
    {
        $this->currentPhotoUrl = $photoUrl;
        $this->currentPhotoIndex = $index;
        $this->lightboxPhotos = $this->reportPhotos;
        $this->showLightbox = true;
    }

    public function closeLightbox()
    {
        $this->showLightbox = false;
        $this->currentPhotoUrl = '';
        $this->currentPhotoIndex = 0;
        $this->lightboxPhotos = [];
    }

    public function nextPhoto()
    {
        if ($this->currentPhotoIndex < count($this->lightboxPhotos) - 1) {
            $this->currentPhotoIndex++;
            $this->currentPhotoUrl = $this->lightboxPhotos[$this->currentPhotoIndex];
        }
    }

    public function previousPhoto()
    {
        if ($this->currentPhotoIndex > 0) {
            $this->currentPhotoIndex--;
            $this->currentPhotoUrl = $this->lightboxPhotos[$this->currentPhotoIndex];
        }
    }

    public function openModal($reportId)
    {
        $this->reportId = $reportId;
        $this->report = Report::with(['user', 'category', 'photos'])->findOrFail($reportId);

        // Validate that this is a FOUND report without item
        if ($this->report->report_type !== 'FOUND' || $this->report->item_id) {
            session()->flash('error', 'This report cannot be confirmed.');
            return;
        }

        // Load report photos dari relasi photos
        $this->reportPhotos = [];
        if ($this->report->photos && $this->report->photos->count() > 0) {
            $this->reportPhotos = $this->report->photos->pluck('photo_url')->toArray();
        } elseif ($this->report->photo_url) {
            // Fallback untuk format lama
            $this->reportPhotos = [$this->report->photo_url];
        }

        // Reset form
        $this->resetForm();
        $this->showModal = true;

        Log::info('Confirm Item modal opened', [
            'reportId' => $reportId,
            'report_type' => $this->report->report_type,
            'report_photos_count' => count($this->reportPhotos),
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->closeLightbox(); // Close lightbox if open
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'brand', 'color', 'storage', 'post_id', 
            'item_description', 'photos', 'newPhotos'
        ]);

        $this->item_status = 'STORED';
        $this->sensitivity_level = 'NORMAL';
        $this->uploadKey++;
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
            $this->uploadKey++;
            
            Log::info('Photo removed from ConfirmItem', [
                'index' => $index,
                'remaining_photos' => count($this->photos)
            ]);
        }
    }

    public function confirmItem()
    {
        Log::info('Confirm Item method called', [
            'reportId' => $this->reportId,
            'photos_count' => count($this->photos),
            'report_photos_count' => count($this->reportPhotos),
        ]);

        try {
            $this->validate();
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            $this->addError('general', 'Please fix the validation errors above.');
            return;
        }

        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $category = Category::findOrFail($this->report->category_id);
            $retentionUntil = now()->addDays($category->retention_days ?? 30);

            // Create Item
            $item = Item::create([
                'item_id' => (string) Str::uuid(),
                'company_id' => $companyId,
                'post_id' => $this->post_id,
                'category_id' => $this->report->category_id,
                'item_name' => $this->report->item_name,
                'brand' => $this->brand,
                'color' => $this->color,
                'item_description' => $this->item_description ?? $this->report->report_description,
                'storage' => $this->storage,
                'item_status' => $this->item_status,
                'retention_until' => $retentionUntil,
                'sensitivity_level' => $this->sensitivity_level,
            ]);

            $itemId = $item->item_id;

            // Upload new photos
            $photoOrder = 0;
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('items/' . $itemId, $filename, 'public');

                    ItemPhoto::create([
                        'photo_id' => (string) Str::uuid(),
                        'company_id' => $companyId,
                        'item_id' => $itemId,
                        'photo_url' => $path,
                        'alt_text' => $this->report->item_name . ' - Photo ' . ($photoOrder + 1),
                        'display_order' => $photoOrder++,
                    ]);
                }
                
                Log::info('New photos uploaded for item', [
                    'count' => count($this->photos),
                    'item_id' => $itemId
                ]);
            }

            // Copy report photos to item
            if (!empty($this->reportPhotos)) {
                foreach ($this->reportPhotos as $photoUrl) {
                    if (Storage::disk('public')->exists($photoUrl)) {
                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $itemId,
                            'photo_url' => $photoUrl,
                            'alt_text' => $this->report->item_name . ' - Report Photo ' . ($photoOrder + 1),
                            'display_order' => $photoOrder++,
                        ]);
                    }
                }
                
                Log::info('Report photos copied to item', [
                    'count' => count($this->reportPhotos),
                    'item_id' => $itemId
                ]);
            }

            // Update report with item_id and status
            Report::where('report_id', $this->reportId)->update([
                'item_id' => $itemId,
                'report_status' => 'STORED',
            ]);

            DB::commit();

            session()->flash('success', 'Item confirmed and registered successfully!');

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('item-confirmed');

            Log::info('Item confirmation completed successfully', [
                'item_id' => $itemId,
                'total_photos' => $photoOrder
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Error: ' . $e->getMessage());
            Log::error('Error confirming item', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        $posts = Post::where('company_id', auth()->user()->company_id)->get();

        $statusOptions = [
            ['value' => 'STORED', 'label' => 'Stored (In Storage) - Default'],
            ['value' => 'CLAIMED', 'label' => 'Claimed (Owner Found)'],
            ['value' => 'RETURNED', 'label' => 'Returned (Given Back)'],
            ['value' => 'DISPOSED', 'label' => 'Disposed (After Retention)'],
        ];

        return view('livewire.admin.lost-and-found.confirm-item', [
            'posts' => $posts,
            'statusOptions' => $statusOptions,
        ]);
    }
}