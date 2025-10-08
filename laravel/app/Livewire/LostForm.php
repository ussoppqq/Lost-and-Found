<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
<<<<<<< HEAD

use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use App\Models\Company;
use App\Models\Item;
use App\Models\ItemPhoto;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
=======
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce

class LostForm extends Component
{
    use WithFileUploads;

    // ===== Form fields =====
    public string $item_name = '';

    public string $category = '';

    public string $description = '';
<<<<<<< HEAD
    public string $location = '';
    public ?string $date_lost = null;

    // Kontak
    public string $phone = '';
    public string $user_name = '';   // auto-filled jika nomor sudah ada
    public bool $nameLocked = false; // kunci input nama jika auto-filled

    // Upload
    public $photo;

    // Context
    public ?string $company_id = null;

    // ===== Validation =====
    protected array $rules = [
        'item_name'   => 'required|string|max:255',
        'category'    => 'required|exists:categories,category_id',
        'description' => 'required|string|min:100',
        'location'    => 'required|string|max:255',
        'date_lost'   => 'required|date|before_or_equal:today',
        'phone'       => 'required|string|max:30',
        'user_name'   => 'required|string|max:255',
        'photo'       => 'nullable|image|max:3072', // 3MB
=======

    public ?string $location = null;

    public ?string $date_lost = null;

    // Contact fields
    public ?string $phone = null;

    public ?string $user_name = null;

    public bool $is_existing_user = false; // Track if user exists

    // File upload (maximum 5 photos)
    public $photos = [];

    public $company_id;

    // Validation rules
    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'category' => 'required|uuid',
            'description' => 'required|string|min:10|max:200',
            'location' => 'nullable|string|max:255',
            'date_lost' => 'nullable|date|before_or_equal:today',
            'phone' => 'required|string|max:30',
            'user_name' => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'photos.*' => 'nullable|image|max:25600', 
            'photos' => 'nullable|array|max:5',
        ];
    }

    protected array $messages = [
        'item_name.required' => 'Item name is required.',
        'category.required' => 'Please select a category.',
        'description.required' => 'Please describe the item.',
        'description.min' => 'Description must be at least 10 characters.',
        'description.max' => 'Description cannot exceed 200 characters.',
        'phone.required' => 'Phone number is required.',
        'user_name.required' => 'Name is required for new users.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image must not exceed 3MB.',
        'photos.max' => 'You can upload maximum 5 photos.',
        'date_lost.before_or_equal' => 'Date lost cannot be in the future.',
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
    ];

    protected array $messages = [
        'item_name.required'   => 'Please fill this form.',
        'category.required'    => 'Please fill this form.',
        'category.exists'      => 'Kategori tidak valid.',
        'description.required' => 'Please fill this form.',
        'description.min'      => 'Description must be at least 100 characters.',
        'location.required'    => 'Please fill this form.',
        'date_lost.required'   => 'Please fill this form.',
        'date_lost.before_or_equal' => 'Mohon isi tanggal yang tepat (tidak boleh melebihi hari ini).',
        'phone.required'       => 'Please fill this form.',
        'user_name.required'   => 'Please fill this form.',
        'photo.image'          => 'Photo must be an image file.',
        'photo.max'            => 'Max photo size is 3MB.',
    ];

    public function mount(): void
    {
        // Ambil company_id dari session atau fallback ke company pertama
        $this->company_id = session('company_id') ?? Company::query()->value('company_id');
    }

<<<<<<< HEAD
    /** Ambil kategori untuk dropdown */
=======
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
    public function getCategories()
    {
        return Category::query()
            ->when($this->company_id, fn ($q) => $q->where('company_id', $this->company_id))
            ->orderBy('category_name')
            ->get();
    }

    /** Auto-fill nama saat phone diisi */
    public function updatedPhone($value): void
    {
<<<<<<< HEAD
        $value = trim((string) $value);

        if ($value === '') {
            $this->user_name = '';
            $this->nameLocked = false;
            return;
        }

        $user = User::where('phone_number', $value)->first();

        if ($user) {
            $this->user_name = (string) $user->full_name;
            $this->nameLocked = true;   // kunci input agar tidak berubah
        } else {
            $this->user_name = '';
            $this->nameLocked = false;
=======
        if (! empty($value)) {
            $user = User::where('phone_number', $value)->first();

            if ($user) {
                $this->user_name = $user->full_name;
                $this->is_existing_user = true;
            } else {
                $this->user_name = null;
                $this->is_existing_user = false;
            }
        } else {
            $this->user_name = null;
            $this->is_existing_user = false;
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
<<<<<<< HEAD
            // 1) Cari/buat user
            $user = User::where('phone_number', $this->phone)->first();

            if (! $user) {
                $userRole = Role::where('role_code', 'USER')->first();
                if (! $userRole) {
                    throw new \Exception('User role not found. Please seed roles.');
                }

                $user = User::create([
                    'user_id'      => (string) Str::uuid(),
                    'company_id'   => null,
                    'role_id'      => $userRole->role_id,
                    'full_name'    => $this->user_name ?: 'Guest User',
                    'email'        => null,
=======
            $user = User::where('phone_number', $this->phone)->first();

            if (! $user) {
                // Create new user
                $userRole = Role::where('role_code', 'USER')->first();

                if (! $userRole) {
                    throw new \Exception('User role not found. Please run database seeder.');
                }

                $user = User::create([
                    'user_id' => Str::uuid(),
                    'company_id' => null,
                    'role_id' => $userRole->role_id,
                    'full_name' => $this->user_name,
                    'email' => null,
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
                    'phone_number' => $this->phone,
                    'password'     => null,
                    'is_verified'  => false,
                ]);
            }

<<<<<<< HEAD
            // 2) Upload foto (jika ada)
            $photoUrl = null;
            $storedPath = null;
            if ($this->photo) {
                $tempName = Str::uuid().'.'.$this->photo->getClientOriginalExtension();
                $storedPath = $this->photo->storeAs('items/tmp', $tempName, 'public');
                $photoUrl = Storage::url($storedPath); // /storage/items/tmp/xxxx.jpg
=======

            $photoUrl = null;
            if (! empty($this->photos)) {
                $uploadedPhotos = [];
                foreach ($this->photos as $index => $photo) {
                    $filename = Str::uuid().'.'.$photo->getClientOriginalExtension();
                    $path = $photo->storeAs('reports/lost', $filename, 'public');
                    $uploadedPhotos[] = Storage::url($path);
                }
                
                $photoUrl = $uploadedPhotos[0]; 
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
            }

            // 3) Buat Item
            $itemId = (string) Str::uuid();

            $item = Item::create([
                'item_id'          => $itemId,
                'company_id'       => $this->company_id,
                'post_id'          => null,
                'category_id'      => $this->category,
                'item_name'        => $this->item_name,
                'brand'            => null,
                'color'            => null,
                'item_description' => $this->description,
                'storage'          => null,
                'item_status'      => 'REPORTED_LOST', // sesuaikan jika pakai enum lain
                'retention_until'  => null,
                'sensitivity_level'=> 'NORMAL',
            ]);

            // 4) Jika ada foto, pindahkan ke folder item & buat record di item_photos
            if ($storedPath) {
                // Pindahkan file dari tmp ke folder item_id
                $finalName = basename($storedPath);
                $finalPath = 'items/'.$itemId.'/'.$finalName;

                // Move file di disk 'public'
                Storage::disk('public')->move($storedPath, $finalPath);

                $finalUrl = Storage::url($finalPath); // /storage/items/{item_id}/file.jpg

                // Simpan ke item_photos
                ItemPhoto::create([
                    'item_photo_id' => (string) Str::uuid(),
                    'item_id'       => $item->item_id,
                    'photo_url'     => $finalUrl,   // ganti ke kolom yang sesuai (mis: 'path')
                ]);

                // Perbarui untuk dipakai juga di reports (jika diperlukan)
                $photoUrl = $finalUrl;
            }

            // 5) Buat Report LOST
            Report::create([
<<<<<<< HEAD
                'report_id'          => (string) Str::uuid(),
                'company_id'         => $this->company_id,
                'user_id'            => $user->user_id,
                'item_id'            => $item->item_id,
                'report_type'        => 'LOST',
                'report_description' => $this->description,
                'report_datetime'    => $this->date_lost ?? now(),
                'report_location'    => $this->location,
                'report_status'      => 'OPEN',
                // kolom tambahan opsional (sesuaikan skema table reports Anda):
                'reporter_name'      => $user->full_name ?? null,
                'reporter_phone'     => $user->phone_number ?? null,
                'reporter_email'     => $user->email ?? null,
                'category_id'        => $this->category,
                'item_name'          => $this->item_name,
                'photo_url'          => $photoUrl, // hanya jika kolom ini ada di table reports
=======
                'report_id' => Str::uuid(),
                'company_id' => $this->company_id,
                'user_id' => $user->user_id,
                'item_id' => null,
                'category_id' => $this->category,
                'report_type' => 'LOST',
                'item_name' => $this->item_name,
                'report_description' => $this->description,
                'report_datetime' => $this->date_lost ?? now(),
                'report_location' => $this->location ?? 'Not specified',
                'report_status' => 'OPEN',
                'photo_url' => $photoUrl,
                'reporter_name' => $user->full_name,
                'reporter_phone' => $user->phone_number,
                'reporter_email' => $user->email,
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
            ]);

            DB::commit();

            session()->flash('status', '✓ Lost item reported successfully!');

            // Reset form (biar bersih)
            $this->reset([
<<<<<<< HEAD
                'item_name', 'category', 'description', 'location',
                'date_lost', 'phone', 'user_name', 'photo'
=======
                'item_name', 'category', 'description',
                'location', 'date_lost', 'phone', 'user_name', 'photos',
                'is_existing_user',
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
            ]);
            $this->nameLocked = false;

        } catch (\Throwable $e) {
            DB::rollBack();
<<<<<<< HEAD
            \Log::error('Lost Item Report Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            session()->flash('error', 'Failed to submit report. Please try again. Error: '.$e->getMessage());
=======

            \Log::error('Lost Item Report Error: '.$e->getMessage());

            session()->flash('error', 'Failed to submit report. Please try again.');
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => $this->getCategories(),
<<<<<<< HEAD
        ])->layout('components.layouts.app', [
            'title' => 'Report Lost Item',
        ]);
=======
        ])
            ->layout('components.layouts.user', [
                'title' => 'Report Lost Item',
            ]);
>>>>>>> d1e78ab786fee8a8ff2f431bd1a03b6daa55ddce
    }
}
