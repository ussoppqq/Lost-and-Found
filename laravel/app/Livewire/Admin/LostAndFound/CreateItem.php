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
use App\Enums\ItemStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class CreateItem extends Component
{
    use WithFileUploads;

    // Mode: 'standalone' atau 'from-report'
    public $mode = 'standalone';
    public $reportId = null;
    public $report = null;

    // Report fields (untuk standalone mode)
    public $report_type = 'FOUND';
    public $report_description;
    public $report_location;
    public $report_datetime;

    // User info dari report (read-only)
    public $reporter_name;
    public $reporter_phone;
    public $reporter_email;

    // Item fields
    public $item_name;
    public $brand;
    public $color;
    public $item_description;
    public $storage;
    public $item_status = 'REGISTERED';
    public $sensitivity_level = 'NORMAL';
    public $category_id;
    public $post_id;

    // Photo upload
    public $photos = [];
    public $existingPhotos = []; // Photos dari report

    // Modal state
    public $showModal = false;

    protected $listeners = [
        'open-create-item-modal' => 'openModalFromReport',
        'open-create-item-modal-standalone' => 'openModalStandalone',
    ];

    protected function rules()
    {
        $rules = [
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:255',
            'item_status' => 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED',
            'sensitivity_level' => 'required|in:NORMAL,RESTRICTED',
            'post_id' => 'nullable|exists:posts,post_id',
            'photos.*' => 'nullable|image|max:2048',
        ];

        if ($this->mode === 'standalone') {
            $rules['item_name'] = 'required|string|max:255';
            $rules['item_description'] = 'nullable|string';
            $rules['category_id'] = 'required|exists:categories,category_id';
            $rules['report_type'] = 'required|in:LOST,FOUND';
            $rules['report_description'] = 'required|string';
            $rules['report_location'] = 'required|string|max:255';
            $rules['report_datetime'] = 'required|date';
            $rules['reporter_name'] = 'required|string|max:255';
            $rules['reporter_phone'] = 'required|string|max:20';
            $rules['reporter_email'] = 'required|email|max:255';
        }

        return $rules;
    }

    public function mount()
    {
        $this->report_datetime = now()->format('Y-m-d\TH:i');
    }

    public function openModalFromReport($reportId)
    {
        $this->mode = 'from-report';
        $this->reportId = $reportId;
        $this->report = Report::with(['user', 'item.category'])->findOrFail($reportId);

        // Pre-fill data dari report (read-only)
        $this->item_name = $this->report->item_name ?? '';
        $this->item_description = $this->report->report_description;
        $this->report_location = $this->report->report_location;
        $this->report_datetime = $this->report->report_datetime;
        $this->report_type = $this->report->report_type;
        $this->category_id = $this->report->category_id;

        // User info dari report
        if ($this->report->user) {
            $this->reporter_name = $this->report->user->full_name;
            $this->reporter_phone = $this->report->user->phone_number ?? '-';
            $this->reporter_email = $this->report->user->email;
        } else {
            $this->reporter_name = $this->report->reporter_name ?? 'Anonymous';
            $this->reporter_phone = $this->report->reporter_phone ?? '-';
            $this->reporter_email = $this->report->reporter_email ?? '-';
        }

        // Load existing photos dari report
        $this->existingPhotos = $this->report->photos ?? [];

        $this->showModal = true;
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
            'existingPhotos',
            'report_type',
            'report_description',
            'report_location',
            'reporter_name',
            'reporter_phone',
            'reporter_email',
        ]);

        $this->item_status = 'REGISTERED';
        $this->sensitivity_level = 'NORMAL';
        $this->report_datetime = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    /**
     * Buat atau cari user walk-in berdasarkan email
     */
    private function getOrCreateWalkInUser($companyId)
    {
        // Cek apakah user dengan email ini sudah ada
        $user = User::where('email', $this->reporter_email)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            // Update data jika ada perubahan
            $user->update([
                'full_name' => $this->reporter_name,
                'phone_number' => $this->reporter_phone,
            ]);
            return $user;
        }

        // Ambil role_id untuk role 'user' atau 'guest'
        // Sesuaikan dengan struktur role di database Anda
        $roleId = \App\Models\Role::where('role_name', 'user')->first()?->role_id
            ?? \App\Models\Role::where('role_name', 'guest')->first()?->role_id;

        if (!$roleId) {
            throw new \Exception('Default role not found. Please create a "user" or "guest" role first.');
        }

        // Buat user baru untuk walk-in
        $user = User::create([
            'user_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $roleId, 
            'full_name' => $this->reporter_name,
            'email' => $this->reporter_email,
            'phone_number' => $this->reporter_phone,
            'password' => Hash::make(Str::random(16)),
            'is_verified' => false,
            'email_verified_at' => null,
        ]);

        return $user;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            if (!$companyId) {
                $this->addError('item_name', 'Company ID not found for current user.');
                return;
            }

            $userId = null;

            // Untuk mode standalone, buat/cari user walk-in
            if ($this->mode === 'standalone') {
                $walkInUser = $this->getOrCreateWalkInUser($companyId);
                $userId = $walkInUser->user_id;
            } else {
                // Mode from-report, ambil user_id dari report
                $userId = $this->report->user_id;
            }

            // 1. BUAT ITEM DULU
            $category = Category::find($this->category_id);
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

            // 2. BUAT/UPDATE REPORT
            if ($this->mode === 'standalone') {
                Report::create([
                    'report_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'user_id' => $userId, // ✅ Sekarang tidak null
                    'item_id' => $item->item_id,
                    'category_id' => $this->category_id,
                    'report_type' => $this->report_type,
                    'item_name' => $this->item_name,
                    'report_description' => $this->report_description,
                    'report_datetime' => $this->report_datetime,
                    'report_location' => $this->report_location,
                    'report_status' => 'STORED',
                ]);
            } else {
                // Mode from-report: update existing report
                Report::where('report_id', $this->reportId)->update([
                    'item_id' => $item->item_id,
                    'report_status' => 'STORED',
                ]);
            }

            // 3. Simpan foto
            if (!empty($this->photos)) {
                foreach ($this->photos as $index => $photo) {
                    $path = $photo->store('item-photos', 'public');
                    ItemPhoto::create([
                        'photo_id' => (string) Str::uuid(),
                        'company_id' => $companyId,
                        'item_id' => $item->item_id,
                        'photo_url' => $path,
                        'alt_text' => $this->item_name . ' - Photo ' . ($index + 1),
                        'display_order' => $index,
                    ]);
                }
            }

            // Copy existing photos dari report (mode from-report)
            if ($this->mode === 'from-report' && !empty($this->existingPhotos)) {
                foreach ($this->existingPhotos as $index => $photo) {
                    $photoUrl = is_object($photo) ? ($photo->photo_url ?? '') : $photo;
                    if ($photoUrl) {
                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $item->item_id,
                            'photo_url' => $photoUrl,
                            'alt_text' => $this->item_name . ' - Photo ' . ($index + 1),
                            'display_order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            session()->flash('success', 'Item created successfully!');
            $this->closeModal();
            $this->dispatch('item-created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('item_name', 'Error creating item: ' . $e->getMessage());
            \Log::error('Error creating item: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)->get();
        $posts = Post::where('company_id', auth()->user()->company_id)->get();

        return view('livewire.admin.lost-and-found.create-item', [
            'categories' => $categories,
            'posts' => $posts,
            'statusOptions' => ItemStatus::options(),
            'showModal' => $this->showModal,
        ]);
    }
}