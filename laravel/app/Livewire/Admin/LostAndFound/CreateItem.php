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
    public $reporterMode = 'user'; // 'user' or 'moderator'

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

        if ($this->mode === 'standalone' || $this->mode === 'from-report') {
            $rules['report_type'] = 'required|in:LOST,FOUND';
            $rules['item_name'] = 'required|string|max:255';
            $rules['category_id'] = 'required|exists:categories,category_id';
        }

        if ($this->mode === 'standalone') {
            $rules['report_description'] = 'required|string';
            $rules['report_location'] = 'required|string|max:255';
            $rules['report_datetime'] = 'required|date';
            
            // Reporter validation based on mode
            if ($this->report_type === 'FOUND') {
                $rules['reporterMode'] = 'required|in:user,moderator';
                
                if ($this->reporterMode === 'user') {
                    $rules['reporter_name'] = 'required|string|max:255';
                    $rules['reporter_phone'] = 'required|string|max:20';
                    $rules['reporter_email'] = 'nullable|email|max:255';
                }
            } else {
                // LOST standalone
                $rules['reporter_name'] = 'required|string|max:255';
                $rules['reporter_phone'] = 'required|string|max:20';
                $rules['reporter_email'] = 'nullable|email|max:255';
            }
            // Photos optional for all standalone
            $rules['photos'] = 'nullable|array';
            $rules['photos.*'] = 'nullable|image|max:2048';
        }

        if ($this->report_type === 'FOUND') {
            $rules['brand'] = 'nullable|string|max:255';
            $rules['color'] = 'nullable|string|max:100';
            $rules['storage'] = 'nullable|string|max:255';
            $rules['item_status'] = 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED';
            $rules['sensitivity_level'] = 'required|in:NORMAL,RESTRICTED';
            $rules['post_id'] = 'required|exists:posts,post_id';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'photos.*.image' => 'All files must be valid images (jpg, png, jpeg, gif).',
            'photos.*.max' => 'Each photo must not exceed 2MB.',
            'category_id.required' => 'Please select a category.',
            'item_name.required' => 'Item name is required.',
            'reporterMode.required' => 'Please select reporter type.',
            'reporter_name.required' => 'Reporter name is required.',
            'reporter_phone.required' => 'Reporter phone is required.',
        ];
    }

    public function mount()
    {
        $this->report_datetime = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
    }

    // Fix for multiple file uploads: Handle both single and multiple properly
    public function updatedPhotos($value)
    {
        Log::info('Photos updated', [
            'existing_count' => count($this->photos ?? []), 
            'new_value_type' => gettype($value),
            'is_array' => is_array($value)
        ]);
        
        // Jangan lakukan apa-apa di sini, biarkan Livewire handle secara natural
        // Livewire akan otomatis populate $this->photos dengan benar
        
        Log::info('Photos after update', ['final_count' => count($this->photos ?? [])]);
    }

    public function updatedReporterMode()
    {
        if ($this->reporterMode === 'moderator') {
            $this->reporter_name = '';
            $this->reporter_phone = '';
            $this->reporter_email = '';
        }
    }

    public function updatedReportType()
    {
        // Reset reporter mode when switching report type
        if ($this->report_type === 'LOST') {
            $this->reporterMode = 'user';
        }
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
            $this->reporter_email = $this->report->user->email ?? '';
        } else {
            $this->reporter_name = $this->report->reporter_name ?? 'Anonymous';
            $this->reporter_phone = $this->report->reporter_phone ?? '-';
            $this->reporter_email = $this->report->reporter_email ?? '';
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

        $this->item_status = 'STORED'; // Default for all now
        $this->showModal = true;

        Log::info('Modal opened from report', [
            'reportId' => $reportId,
            'report_type' => $this->report_type,
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
            'item_name', 'brand', 'color', 'item_description', 'storage',
            'category_id', 'post_id', 'photos', 'reportPhotos',
            'report_type', 'report_description', 'report_location',
            'reporter_name', 'reporter_phone', 'reporter_email', 'reporterMode',
        ]);

        $this->item_status = 'STORED';
        $this->sensitivity_level = 'NORMAL';
        $this->report_type = 'FOUND';
        $this->reporterMode = 'user';
        $this->report_datetime = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    private function getOrCreateWalkInUser($companyId)
    {
        // If moderator mode, return current moderator user
        if ($this->reporterMode === 'moderator') {
            return auth()->user();
        }

        // Find user by phone first
        $user = User::where('phone_number', $this->reporter_phone)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            $updateData = ['full_name' => $this->reporter_name];

            // Update email ONLY if provided and not already used by another user
            if (!empty($this->reporter_email)) {
                $emailExists = User::where('email', $this->reporter_email)
                    ->where('user_id', '!=', $user->user_id)
                    ->where('company_id', $companyId)
                    ->exists();

                if (!$emailExists) {
                    $updateData['email'] = $this->reporter_email;
                }
            }

            $user->update($updateData);
            
            Log::info('Walk-in user updated', [
                'user_id' => $user->user_id,
                'email_updated' => isset($updateData['email']),
            ]);

            return $user;
        }

        // Check by email if phone not found
        if (!empty($this->reporter_email)) {
            $user = User::where('email', $this->reporter_email)
                ->where('company_id', $companyId)
                ->first();

            if ($user) {
                if (empty($user->phone_number)) {
                    $user->update([
                        'full_name' => $this->reporter_name,
                        'phone_number' => $this->reporter_phone,
                    ]);
                }
                return $user;
            }
        }

        // Create new user
        $roleId = \App\Models\Role::where('role_code', 'USER')->first()?->role_id
            ?? \App\Models\Role::where('role_code', 'GUEST')->first()?->role_id;

        if (!$roleId) {
            throw new \Exception('Default role not found.');
        }

        // Use temp email if not provided
        $email = !empty($this->reporter_email) 
            ? $this->reporter_email 
            : 'walkin_' . Str::uuid() . '@temp.local';

        $user = User::create([
            'user_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $roleId,
            'full_name' => $this->reporter_name,
            'email' => $email,
            'phone_number' => $this->reporter_phone,
            'password' => Hash::make(Str::random(16)),
            'is_verified' => !empty($this->reporter_email),
        ]);

        Log::info('New walk-in user created', [
            'user_id' => $user->user_id,
            'has_real_email' => !empty($this->reporter_email),
        ]);

        return $user;
    }

    public function save()
    {
        Log::info('Save method called', [
            'mode' => $this->mode,
            'report_type' => $this->report_type,
            'reporter_mode' => $this->reporterMode,
            'photos_count' => count($this->photos),
            'photos_count_before_save' => count($this->photos ?? []),
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
            $userId = null;

            if ($this->mode === 'standalone') {
                $walkInUser = $this->getOrCreateWalkInUser($companyId);
                $userId = $walkInUser->user_id;
            } else {
                $userId = $this->report->user_id ?? null;
            }

            $itemId = null;

            // Handle FOUND items (both modes)
            if ($this->report_type === 'FOUND') {
                $category = Category::findOrFail($this->category_id);
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

                // Upload multiple photos
                $photoOrder = 0;
                if (!empty($this->photos)) {
                    foreach ($this->photos as $photo) {
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('reports/found', $filename, 'public');

                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $itemId,
                            'photo_url' => $path,
                            'alt_text' => $this->item_name . ' - Photo ' . ($photoOrder + 1),
                            'display_order' => $photoOrder++,
                        ]);
                    }
                }

                // Copy report photos if from report
                if ($this->mode === 'from-report' && !empty($this->reportPhotos)) {
                    foreach ($this->reportPhotos as $photoUrl) {
                        if (Storage::disk('public')->exists($photoUrl)) {
                            ItemPhoto::create([
                                'photo_id' => (string) Str::uuid(),
                                'company_id' => $companyId,
                                'item_id' => $itemId,
                                'photo_url' => $photoUrl,
                                'alt_text' => $this->item_name . ' - Report Photo ' . ($photoOrder + 1),
                                'display_order' => $photoOrder++,
                            ]);
                        }
                    }
                }
            }

            // For LOST from-report: Just close the report (no item creation)
            if ($this->report_type === 'LOST' && $this->mode === 'from-report') {
                // No item creation, just update report status
                $itemId = null;
            }

            // Create or update report
            if ($this->mode === 'standalone') {
                // Store multiple photos for report
                $photoUrls = [];
                if (!empty($this->photos)) {
                    foreach ($this->photos as $photo) {
                        $folder = $this->report_type === 'FOUND' ? 'reports/found' : 'reports/lost';
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $photoUrls[] = $photo->storeAs($folder, $filename, 'public');
                    }
                }

                $reporterName = $this->reporterMode === 'moderator' 
                    ? auth()->user()->full_name 
                    : $this->reporter_name;
                    
                $reporterPhone = $this->reporterMode === 'moderator' 
                    ? auth()->user()->phone_number ?? '-' 
                    : $this->reporter_phone;
                    
                $reporterEmail = $this->reporterMode === 'moderator' 
                    ? auth()->user()->email 
                    : ($this->reporter_email ?? '');

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
                    'photo_url' => !empty($photoUrls) ? json_encode($photoUrls) : null,
                    'reporter_name' => $reporterName,
                    'reporter_phone' => $reporterPhone,
                    'reporter_email' => $reporterEmail,
                ]);

                Log::info('Standalone report created', [
                    'report_type' => $this->report_type,
                    'photos_count' => count($photoUrls),
                ]);
            } else {
                $reportStatus = $this->report_type === 'FOUND' ? 'STORED' : 'CLOSED';

                Report::where('report_id', $this->reportId)->update([
                    'item_id' => $itemId,
                    'report_status' => $reportStatus,
                ]);
            }

            DB::commit();

            $message = $this->report_type === 'FOUND'
                ? 'Found item registered successfully!'
                : 'Lost report confirmed successfully!';

            session()->flash('success', $message);

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('item-created');

            Log::info('Item registration completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Error: ' . $e->getMessage());
            Log::error('Error creating item', [
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