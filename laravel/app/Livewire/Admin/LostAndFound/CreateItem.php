<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Item;
use App\Models\Report;
use App\Models\Category;
use App\Models\Post;
use App\Models\ItemPhoto;
use App\Models\User;
use App\Models\Claim;
use App\Enums\ItemStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateItem extends Component
{
    use WithFileUploads;

    public $mode = 'standalone';
    public $reportId = null;
    public $report = null;

    // Report fields
    public $report_type = 'FOUND';
    public $report_description;
    public $report_location;
    public $report_datetime;

    // Reporter info
    public $reporter_name;
    public $reporter_phone;
    public $reporter_email;

    // Item fields
    public $item_name;
    public $brand;
    public $color;
    public $item_description;
    public $storage;
    public $item_status = 'STORED';
    public $sensitivity_level = 'NORMAL';
    public $category_id;
    public $post_id;

    // Photos
    public $photos = [];
    public $reportPhotos = [];

    public $showModal = false;

    protected $listeners = [
        'open-create-item-modal' => 'openModalFromReport',
        'open-create-item-modal-standalone' => 'openModalStandalone',
    ];

    protected function rules()
    {
        $rules = [];

        // Common rules
        if ($this->mode === 'standalone' || $this->mode === 'from-report') {
            $rules['report_type'] = 'required|in:LOST,FOUND';
            $rules['item_name'] = 'required|string|max:255';
            $rules['category_id'] = 'required|exists:categories,category_id';
        }

        if ($this->mode === 'standalone') {
            $rules['report_description'] = 'required|string';
            $rules['report_location'] = 'required|string|max:255';
            $rules['report_datetime'] = 'required|date';
            $rules['reporter_name'] = 'required|string|max:255';
            $rules['reporter_phone'] = 'required|string|max:20';
            $rules['reporter_email'] = 'required|email|max:255';
        }

        // FOUND items
        if ($this->report_type === 'FOUND') {
            $rules['brand'] = 'nullable|string|max:255';
            $rules['color'] = 'nullable|string|max:100';
            $rules['storage'] = 'nullable|string|max:255';
            $rules['item_status'] = 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED';
            $rules['sensitivity_level'] = 'required|in:NORMAL,RESTRICTED';
            $rules['post_id'] = 'required|exists:posts,post_id';
            $rules['photos.*'] = 'nullable|image|max:2048';
        }

        // LOST CLAIM (from-report): Require min 1 photo
        if ($this->report_type === 'LOST' && $this->mode === 'from-report') {
            $rules['photos'] = 'required|array|min:1';
            $rules['photos.*'] = 'required|image|max:2048';
            $rules['brand'] = 'nullable|string|max:255';
            $rules['color'] = 'nullable|string|max:100';
            $rules['item_description'] = 'nullable|string';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'photos.required' => 'At least one claim photo is required to document the return process.',
            'photos.min' => 'Please upload at least one photo showing the item return.',
            'photos.*.required' => 'Please select valid image files.',
            'photos.*.image' => 'All files must be valid images (jpg, png, jpeg, gif).',
            'photos.*.max' => 'Each photo must not exceed 2MB.',
            'category_id.required' => 'Please select a category.',
            'item_name.required' => 'Item name is required.',
        ];
    }

    public function mount()
    {
        $this->report_datetime = now()->format('Y-m-d\TH:i');
    }

    public function openModalFromReport($reportId)
    {
        $this->mode = 'from-report';
        $this->reportId = $reportId;
        $this->report = Report::with(['user'])->findOrFail($reportId);

        $this->item_name = $this->report->item_name ?? '';
        $this->report_description = $this->report->report_description;
        $this->report_location = $this->report->report_location;
        $this->report_datetime = $this->report->report_datetime;
        $this->report_type = $this->report->report_type;
        $this->category_id = $this->report->category_id;

        if ($this->report->user) {
            $this->reporter_name = $this->report->user->full_name;
            $this->reporter_phone = $this->report->user->phone_number ?? '-';
            $this->reporter_email = $this->report->user->email;
        } else {
            $this->reporter_name = $this->report->reporter_name ?? 'Anonymous';
            $this->reporter_phone = $this->report->reporter_phone ?? '-';
            $this->reporter_email = $this->report->reporter_email ?? '-';
        }

        $this->reportPhotos = [];
        if ($this->report->photo_url) {
            if (is_string($this->report->photo_url) && str_starts_with($this->report->photo_url, '[')) {
                $photosArray = json_decode($this->report->photo_url, true);
                $this->reportPhotos = is_array($photosArray) ? $photosArray : [$this->report->photo_url];
            } else {
                $this->reportPhotos = [$this->report->photo_url];
            }
        }

        if ($this->report_type === 'LOST') {
            $this->item_status = 'CLAIMED';
        } else {
            $this->item_status = 'STORED';
        }

        $this->showModal = true;

        Log::info('Modal opened from report', [
            'reportId' => $reportId,
            'report_type' => $this->report_type,
            'reportPhotos' => $this->reportPhotos
        ]);
    }

    public function openModalStandalone()
    {
        $this->mode = 'standalone';
        $this->reportId = null;
        $this->report = null;
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'item_name',
            'brand',
            'color',
            'item_description',
            'storage',
            'category_id',
            'post_id',
            'photos',
            'reportPhotos',
            'report_type',
            'report_description',
            'report_location',
            'reporter_name',
            'reporter_phone',
            'reporter_email',
        ]);

        $this->item_status = 'STORED';
        $this->sensitivity_level = 'NORMAL';
        $this->report_type = 'FOUND';
        $this->report_datetime = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    private function getOrCreateWalkInUser($companyId)
    {
        $user = User::where('email', $this->reporter_email)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            $user->update([
                'full_name' => $this->reporter_name,
                'phone_number' => $this->reporter_phone,
            ]);
            return $user;
        }

        $roleId = \App\Models\Role::where('role_code', 'USER')->first()?->role_id
            ?? \App\Models\Role::where('role_code', 'GUEST')->first()?->role_id;

        if (!$roleId) {
            throw new \Exception('Default role not found.');
        }

        $user = User::create([
            'user_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $roleId,
            'full_name' => $this->reporter_name,
            'email' => $this->reporter_email,
            'phone_number' => $this->reporter_phone,
            'password' => Hash::make(Str::random(16)),
            'is_verified' => false,
        ]);

        return $user;
    }

    public function save()
    {
        Log::info('Save method called!', [
            'mode' => $this->mode,
            'report_type' => $this->report_type,
            'photos_count' => count($this->photos),
            'photos_data' => $this->photos,
            'category_id' => $this->category_id,
        ]);

        try {
            $this->validate();
            Log::info('Validation passed!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            // Tampilkan error dengan lebih jelas
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            // Tambahkan error umum untuk user
            $this->addError('general', 'Please fix the validation errors above before submitting.');
            return;
        }

        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $userId = null;

            if ($this->mode === 'standalone') {
                $walkInUser = $this->getOrCreateWalkInUser($companyId);
                $userId = $walkInUser->user_id;
            } else {
                $userId = $this->report->user_id ?? null;
            }

            $itemId = null;

            if ($this->report_type === 'FOUND') {
                $category = Category::find($this->category_id);
                if (!$category) {
                    throw new \Exception('Category not found: ' . $this->category_id);
                }
                $retentionUntil = now()->addDays($category->retention_days ?? 30);

                $item = Item::create([
                    'item_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'post_id' => $this->post_id,
                    'category_id' => $this->category_id,
                    'item_name' => $this->item_name,
                    'brand' => $this->brand,
                    'color' => $this->color,
                    'item_description' => $this->item_description ?? $this->report_description,
                    'storage' => $this->storage,
                    'item_status' => $this->item_status,
                    'retention_until' => $retentionUntil,
                    'sensitivity_level' => $this->sensitivity_level,
                ]);

                $itemId = $item->item_id;

                Log::info('Found item created', ['item_id' => $itemId]);

                $photoOrder = 0;
                if (!empty($this->photos)) {
                    foreach ($this->photos as $photo) {
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('reports/found', $filename, 'public');

                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $itemId,
                            'photo_url' => $path,  // ✅ Simpan path, bukan Storage::url($path)
                            'alt_text' => $this->item_name . ' - Photo ' . ($photoOrder + 1),
                            'display_order' => $photoOrder,
                        ]);
                        $photoOrder++;
                    }
                }

                if ($this->mode === 'from-report' && !empty($this->reportPhotos)) {
                    foreach ($this->reportPhotos as $photoUrl) {
                        if (Storage::disk('public')->exists($photoUrl)) {
                            ItemPhoto::create([
                                'photo_id' => (string) Str::uuid(),
                                'company_id' => $companyId,
                                'item_id' => $itemId,
                                'photo_url' => $photoUrl,
                                'alt_text' => $this->item_name . ' - Report Photo ' . ($photoOrder + 1),
                                'display_order' => $photoOrder,
                            ]);
                            $photoOrder++;
                        }
                    }
                    Log::info('Report photos copied', ['count' => count($this->reportPhotos)]);
                }
            }

            if ($this->report_type === 'LOST' && $this->mode === 'from-report') {
                Log::info('Processing LOST claim', [
                    'photos_count' => count($this->photos),
                    'category_id' => $this->category_id
                ]);

                $category = Category::find($this->category_id);
                if (!$category) {
                    throw new \Exception('Category not found: ' . $this->category_id);
                }
                $retentionUntil = now()->addDays($category->retention_days ?? 30);

                $item = Item::create([
                    'item_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'post_id' => null, // LOST items tidak perlu post_id
                    'category_id' => $this->category_id,
                    'item_name' => $this->item_name,
                    'brand' => $this->brand,
                    'color' => $this->color,
                    'item_description' => $this->item_description ?? $this->report_description,
                    'storage' => null, // LOST items tidak ada storage
                    'item_status' => 'CLAIMED',
                    'retention_until' => $retentionUntil,
                    'sensitivity_level' => 'NORMAL',
                ]);

                $itemId = $item->item_id;
                Log::info('LOST item created', ['item_id' => $itemId]);

                // Upload claim photos dengan path reports/lost
                $claimPhotoPaths = [];
                if (!empty($this->photos)) {
                    foreach ($this->photos as $photo) {
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('reports/lost', $filename, 'public');
                        $claimPhotoPaths[] = $path;  
                    }
                }

                try {
                    Claim::create([
                        'claim_id' => (string) Str::uuid(),
                        'company_id' => $companyId,
                        'user_id' => $userId,
                        'item_id' => $itemId,
                        'report_id' => $this->reportId,
                        'brand' => $this->brand,
                        'color' => $this->color,
                        'claim_notes' => $this->item_description,
                        'claim_photos' => $claimPhotoPaths, // Simpan array foto di claims
                        'claim_status' => 'RELEASED',
                        'pickup_schedule' => now(),
                    ]);
                    Log::info('Claim created successfully with photos');
                } catch (\Exception $claimError) {
                    Log::error('Claim creation failed', [
                        'error' => $claimError->getMessage(),
                        'trace' => $claimError->getTraceAsString()
                    ]);
                    throw $claimError;
                }

                Log::info('Claim created and item marked as CLAIMED', ['item_id' => $itemId]);
            }

            if ($this->mode === 'standalone') {
                $photoUrl = null;
                if (!empty($this->photos)) {
                    if ($this->report_type === 'FOUND') {
                        $filename = Str::uuid() . '.' . $this->photos[0]->getClientOriginalExtension();
                        $photoUrl = $this->photos[0]->storeAs('reports/found', $filename, 'public');  
                    } elseif ($this->report_type === 'LOST') {
                        $filename = Str::uuid() . '.' . $this->photos[0]->getClientOriginalExtension();
                        $photoUrl = $this->photos[0]->storeAs('reports/lost', $filename, 'public');  
                    }
                }

                Report::create([
                    'report_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'category_id' => $this->category_id,
                    'report_type' => $this->report_type,
                    'item_name' => $this->item_name,
                    'report_description' => $this->report_description,
                    'report_datetime' => $this->report_datetime,
                    'report_location' => $this->report_location,
                    'report_status' => $this->report_type === 'FOUND' ? 'STORED' : 'OPEN',
                    'photo_url' => $photoUrl,
                    'reporter_name' => $this->reporter_name,
                    'reporter_phone' => $this->reporter_phone,
                    'reporter_email' => $this->reporter_email,
                ]);
            } else {
                $reportStatus = $this->report_type === 'FOUND' ? 'STORED' : 'CLOSED';

                Report::where('report_id', $this->reportId)->update([
                    'item_id' => $itemId,
                    'report_status' => $reportStatus,
                ]);

                Log::info('Report updated', ['report_id' => $this->reportId, 'status' => $reportStatus]);
            }

            DB::commit();

            $message = $this->report_type === 'FOUND'
                ? 'Found item registered successfully!'
                : 'Lost item claimed successfully! Status updated to CLAIMED.';

            session()->flash('success', $message);

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('item-created');

            Log::info('Item registration/claim completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Error: ' . $e->getMessage());
            Log::error('Error creating item/claim', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function render()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)->get();
        $posts = Post::where('company_id', auth()->user()->company_id)->get();

        $statusOptions = [];
        if ($this->report_type === 'FOUND') {
            $statusOptions = [
                ['value' => 'STORED', 'label' => 'Stored (In Storage) - Default'],
                ['value' => 'CLAIMED', 'label' => 'Claimed (Owner Found)'],
                ['value' => 'RETURNED', 'label' => 'Returned (Given Back)'],
                ['value' => 'DISPOSED', 'label' => 'Disposed (After Retention)'],
            ];
        }

        return view('livewire.admin.lost-and-found.create-item', [
            'categories' => $categories,
            'posts' => $posts,
            'statusOptions' => $statusOptions,
        ]);
    }
}