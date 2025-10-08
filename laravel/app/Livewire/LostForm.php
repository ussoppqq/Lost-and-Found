<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Item;
use App\Models\ItemPhoto;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class LostForm extends Component
{
    use WithFileUploads;

    // ===== Form fields =====
    public string $item_name = '';
    public string $category = '';
    public string $description = '';
    public ?string $location = null;
    public ?string $date_lost = null;

    // Contact
    public ?string $phone = null;
    public ?string $user_name = null;
    public ?string $email = null;
    public bool $is_existing_user = false;

    // Multiple uploads
    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[]|array */
    public array $photos = [];

    public ?string $company_id = null;

    // Validation rules
    protected function rules(): array
    {
        return [
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|uuid|exists:categories,category_id',
            'description' => 'required|string|min:100',
            'location'    => 'required|string|max:255',
            'date_lost'   => 'required|date|before_or_equal:today',
            'phone'       => 'required|string|max:30',
            'user_name'   => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'email'       => 'nullable|email',
            'photos'      => 'nullable|array|max:5',
            'photos.*'    => 'nullable|image|max:3072', // 3MB each
        ];
    }

    protected array $messages = [
        'item_name.required'   => 'Please fill this form.',
        'category.required'    => 'Please fill this form.',
        'category.uuid'        => 'Please select a valid category.',
        'category.exists'      => 'Selected category is invalid.',
        'description.required' => 'Please fill this form.',
        'description.min'      => 'Description must be at least 100 characters.',
        'location.required'    => 'Please fill this form.',
        'date_lost.required'   => 'Please fill this form.',
        'date_lost.before_or_equal' => 'Mohon isi tanggal yang tepat',
        'phone.required'       => 'Please fill this form.',
        'user_name.required'   => 'Please fill this form.',
        'photos.max'           => 'You can upload a maximum of 5 photos.',
        'photos.*.image'       => 'Each file must be an image.',
        'photos.*.max'         => 'Each image must not exceed 3MB.',
    ];

    public function mount(): void
    {
        // company scope
        $this->company_id = session('company_id') ?? Company::query()->value('company_id');
    }

    public function getCategories()
    {
        return Category::query()
            ->when($this->company_id, fn ($q) => $q->where('company_id', $this->company_id))
            ->orderBy('category_name')
            ->get();
    }

    /** Auto-fill name when phone is entered */
    public function updatedPhone($value): void
    {
        if (!empty($value)) {
            $user = User::where('phone_number', $value)->first();

            if ($user) {
                $this->user_name = $user->full_name;
                $this->email     = $user->email;
                $this->is_existing_user = true;
                return;
            }
        }

        $this->user_name = null;
        $this->email = null;
        $this->is_existing_user = false;
    }

    public function removePhoto(int $index): void
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // 1) Find or create user
            $user = User::where('phone_number', $this->phone)->first();

            if (!$user) {
                $userRole = Role::where('role_code', 'USER')->first();
                if (!$userRole) {
                    throw new \Exception('User role not found. Please run database seeder.');
                }

                $user = User::create([
                    'user_id'      => Str::uuid(),
                    'company_id'   => $this->company_id,
                    'role_id'      => $userRole->role_id,
                    'full_name'    => $this->user_name,
                    'email'        => $this->email,
                    'phone_number' => $this->phone,
                    'password'     => null,
                    'is_verified'  => false,
                ]);
            }

            // 2) Create Item (items table)
            $itemId = (string) Str::uuid();

            $item = Item::create([
                'item_id'           => $itemId,
                'company_id'        => $this->company_id,
                'post_id'           => null,
                'category_id'       => $this->category,
                'item_name'         => $this->item_name,
                'brand'             => null,
                'color'             => null,
                'item_description'  => $this->description,
                'storage'           => $this->location,   // simpan lokasi di kolom storage (atau ganti kolom sesuai DB)
                'item_status'       => 'LOST',
                'retention_until'   => null,
                'sensitivity_level' => 'NORMAL',
            ]);

            // 3) Upload photos + store in item_photos
            $firstPhotoUrl = null;
            if (!empty($this->photos)) {
                foreach ($this->photos as $index => $photo) {
                    $filename = Str::uuid().'.'.$photo->getClientOriginalExtension();
                    $path = $photo->storeAs('items/'.$itemId, $filename, 'public');
                    $url  = Storage::url($path);

                    ItemPhoto::create([
                        'item_photo_id' => (string) Str::uuid(),
                        'item_id'       => $itemId,
                        // Ganti ke 'file_path' jika kolommu bernama file_path:
                        'photo_url'     => $url,
                    ]);

                    if ($index === 0) {
                        $firstPhotoUrl = $url;
                    }
                }
            }

            // 4) Create Report (optional; kamu sudah pakai reports table)
            Report::create([
                'report_id'         => Str::uuid(),
                'company_id'        => $this->company_id,
                'user_id'           => $user->user_id,
                'item_id'           => $itemId,
                'category_id'       => $this->category,
                'report_type'       => 'LOST',
                'item_name'         => $this->item_name,
                'report_description'=> $this->description,
                'report_datetime'   => $this->date_lost ?? now(),
                'report_location'   => $this->location,
                'report_status'     => 'OPEN',
                'photo_url'         => $firstPhotoUrl,
                'reporter_name'     => $user->full_name,
                'reporter_phone'    => $user->phone_number,
                'reporter_email'    => $user->email,
            ]);

            DB::commit();

            session()->flash('status', '✓ Lost item reported successfully!');

            // Reset form (keep company)
            $this->reset([
                'item_name', 'category', 'description',
                'location', 'date_lost', 'phone', 'user_name', 'email',
                'photos', 'is_existing_user',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => $this->getCategories(),
        ])->layout('components.layouts.user', [
            'title' => 'Report Lost Item',
        ]);
    }
}
