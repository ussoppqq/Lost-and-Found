<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Item;
use App\Models\Report;
use App\Models\ReportPhoto;
use App\Models\Category;
use App\Models\Post;
use App\Models\ItemPhoto;
use App\Models\User;
use Carbon\Carbon;
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
    public $reporterMode = 'user';

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

    // Photos - PERBAIKAN: Gunakan konsep seperti FoundForm
    public $photos = [];        // Array untuk preview photos
    public $newPhotos = [];     // Array untuk upload baru
    public $uploadKey = 0;      // Key untuk reset input
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

            if ($this->report_type === 'FOUND') {
                $rules['reporterMode'] = 'required|in:user,moderator';

                if ($this->reporterMode === 'user') {
                    $rules['reporter_name'] = 'required|string|max:255';
                    $rules['reporter_phone'] = 'required|string|max:20';
                    $rules['reporter_email'] = 'nullable|email|max:255';
                }
            } else {
                $rules['reporter_name'] = 'required|string|max:255';
                $rules['reporter_phone'] = 'required|string|max:20';
                $rules['reporter_email'] = 'nullable|email|max:255';
            }

            // PERBAIKAN: Validasi untuk photos dan newPhotos
            $rules['photos'] = 'nullable|array|max:5';
            $rules['photos.*'] = 'nullable|image|max:5120';
            $rules['newPhotos'] = 'nullable|array';
            $rules['newPhotos.*'] = 'nullable|image|max:5120';
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
            'photos.*.max' => 'Each photo must not exceed 5MB.',
            'photos.max' => 'You can upload a maximum of 5 photos.',
            'newPhotos.*.image' => 'All files must be valid images.',
            'newPhotos.*.max' => 'Each photo must not exceed 5MB.',
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

    // PERBAIKAN: Gunakan konsep seperti FoundForm
    public function updatedNewPhotos(): void
    {
        $this->validateOnly('newPhotos.*');

        // Hitung berapa slot yang masih tersedia (max 5 photos)
        $allowed = max(0, 5 - count($this->photos));

        // Tambahkan photos baru ke array photos (untuk preview)
        foreach (array_slice($this->newPhotos, 0, $allowed) as $file) {
            $this->photos[] = $file;
        }

        // Reset newPhotos array
        $this->newPhotos = [];

        // Increment uploadKey untuk reset input file
        $this->uploadKey++;

        Log::info('Photos updated successfully', [
            'total_photos' => count($this->photos),
            'upload_key' => $this->uploadKey
        ]);
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

        $this->item_status = 'STORED';
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
            'item_name',
            'brand',
            'color',
            'item_description',
            'storage',
            'category_id',
            'post_id',
            'photos',
            'newPhotos',
            'reportPhotos',
            'report_type',
            'report_description',
            'report_location',
            'reporter_name',
            'reporter_phone',
            'reporter_email',
            'reporterMode',
        ]);

        $this->item_status = 'STORED';
        $this->sensitivity_level = 'NORMAL';
        $this->report_type = 'FOUND';
        $this->reporterMode = 'user';
        $this->report_datetime = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->uploadKey++;
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
            $this->uploadKey++;

            Log::info('Photo removed', [
                'index' => $index,
                'remaining_photos' => count($this->photos)
            ]);
        }
    }

    private function getOrCreateWalkInUser($companyId)
    {
        if ($this->reporterMode === 'moderator') {
            return auth()->user();
        }

        $user = User::where('phone_number', $this->reporter_phone)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            $updateData = ['full_name' => $this->reporter_name];

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

        $roleId = \App\Models\Role::where('role_code', 'USER')->first()?->role_id
            ?? \App\Models\Role::where('role_code', 'GUEST')->first()?->role_id;

        if (!$roleId) {
            throw new \Exception('Default role not found.');
        }

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

                // ✅ UPLOAD MULTIPLE PHOTOS
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
                            'alt_text' => $this->item_name . ' - Photo ' . ($photoOrder + 1),
                            'display_order' => $photoOrder++,
                        ]);
                    }

                    Log::info('Item photos uploaded successfully', [
                        'count' => $photoOrder
                    ]);
                }

                // Copy report photos if from-report mode
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

            if ($this->report_type === 'LOST' && $this->mode === 'from-report') {
                $itemId = null;
            }

            if ($this->mode === 'standalone') {
                // Parse datetime WIB
                $reportDateTime = $this->report_datetime
                    ? Carbon::parse($this->report_datetime, 'Asia/Jakarta')
                    : Carbon::now('Asia/Jakarta');

                $reporterName = $this->reporterMode === 'moderator'
                    ? auth()->user()->full_name
                    : $this->reporter_name;

                $reporterPhone = $this->reporterMode === 'moderator'
                    ? auth()->user()->phone_number ?? '-'
                    : $this->reporter_phone;

                $reporterEmail = $this->reporterMode === 'moderator'
                    ? auth()->user()->email
                    : ($this->reporter_email ?? '');

                // Create report (without photo_url first)
                $report = Report::create([
                    'report_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'category_id' => $this->category_id,
                    'report_type' => $this->report_type,
                    'item_name' => $this->item_name,
                    'report_description' => $this->report_description,
                    'report_datetime' => $reportDateTime,
                    'report_location' => $this->report_location,
                    'report_status' => $this->report_type === 'FOUND' ? 'STORED' : 'OPEN',
                    'photo_url' => null, // Will be set from first photo
                    'reporter_name' => $reporterName,
                    'reporter_phone' => $reporterPhone,
                    'reporter_email' => $reporterEmail,
                ]);

                // ✅ SIMPAN MULTIPLE PHOTOS KE REPORT_PHOTOS
                if (!empty($this->photos)) {
                    foreach ($this->photos as $index => $photo) {
                        $folder = $this->report_type === 'FOUND' ? 'reports/found' : 'reports/lost';
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $photoPath = $photo->storeAs($folder . '/' . $userId, $filename, 'public');

                        \App\Models\ReportPhoto::create([
                            'photo_id' => Str::uuid(),
                            'report_id' => $report->report_id,
                            'photo_url' => $photoPath,
                            'is_primary' => $index === 0, // First photo is primary
                            'photo_order' => $index,
                        ]);

                        // Set primary photo URL di report
                        if ($index === 0) {
                            $report->update(['photo_url' => $photoPath]);
                        }
                    }

                    Log::info('Report photos uploaded successfully', [
                        'count' => count($this->photos),
                        'report_type' => $this->report_type
                    ]);
                }
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
        $companyId = auth()->user()->company_id;
        $categories = Category::where('company_id', $companyId)->get();
        $posts = Post::where('company_id', $companyId)->get();
        $locations = \App\Models\Location::where('company_id', $companyId)
            ->orderBy('area')
            ->orderBy('name')
            ->get();

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
            'locations' => $locations,
            'statusOptions' => $statusOptions,
        ]);
    }
}