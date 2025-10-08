<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

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

class LostForm extends Component
{
    use WithFileUploads;

    // ===== Form fields =====
    public string $item_name = '';
    public string $category = '';
    public string $description = '';
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

    /** Ambil kategori untuk dropdown */
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
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
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
                    'phone_number' => $this->phone,
                    'password'     => null,
                    'is_verified'  => false,
                ]);
            }

            // 2) Upload foto (jika ada)
            $photoUrl = null;
            $storedPath = null;
            if ($this->photo) {
                $tempName = Str::uuid().'.'.$this->photo->getClientOriginalExtension();
                $storedPath = $this->photo->storeAs('items/tmp', $tempName, 'public');
                $photoUrl = Storage::url($storedPath); // /storage/items/tmp/xxxx.jpg
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
            ]);

            DB::commit();

            session()->flash('status', '✓ Lost item reported successfully!');

            // Reset form (biar bersih)
            $this->reset([
                'item_name', 'category', 'description', 'location',
                'date_lost', 'phone', 'user_name', 'photo'
            ]);
            $this->nameLocked = false;

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Lost Item Report Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            session()->flash('error', 'Failed to submit report. Please try again. Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => $this->getCategories(),
        ])->layout('components.layouts.app', [
            'title' => 'Report Lost Item',
        ]);
    }
}
