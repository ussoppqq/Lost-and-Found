<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Item;
use App\Models\ItemPhoto;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class LostForm extends Component
{
    use WithFileUploads;

<<<<<<< HEAD
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
=======
    public string $item_name = '';
    public string $description = '';
    public ?string $location = null;
    public ?string $date_lost = null;
    public ?string $category = null;
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool $is_existing_user = false;
    public $photos = [];
    public $company_id;

    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|uuid',
            'description' => 'required|string|min:10|max:200',
            'location' => 'nullable|string|max:255',
            'date_lost' => 'nullable|date|before_or_equal:today',
            'phone' => 'required|string|max:30',
            'user_name' => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'photos.*' => 'nullable|image|max:25600',
            'photos' => 'nullable|array|max:5',
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
        ];
    }

    protected array $messages = [
<<<<<<< HEAD
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
=======
        'item_name.required' => 'Item name is required.',
        'description.required' => 'Please describe your lost item.',
        'description.min' => 'Description must be at least 10 characters.',
        'description.max' => 'Description cannot exceed 200 characters.',
        'phone.required' => 'Phone number is required.',
        'user_name.required' => 'Name is required for new users.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image must not exceed 25MB.',
        'photos.max' => 'You can upload a maximum of 5 photos.',
        'date_lost.before_or_equal' => 'Date lost cannot be in the future.',
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
    ];

    public function mount(): void
    {
<<<<<<< HEAD
        // company scope
        $this->company_id = session('company_id') ?? Company::query()->value('company_id');
=======
        $this->company_id = session('company_id') ?? Company::first()?->company_id;

        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
    }

    public function fillFromAuthenticatedUser(): void
    {
<<<<<<< HEAD
        return Category::query()
            ->when($this->company_id, fn ($q) => $q->where('company_id', $this->company_id))
            ->orderBy('category_name')
            ->get();
=======
        $this->phone = Auth::user()->phone_number;
        $this->user_name = Auth::user()->full_name;
        $this->is_existing_user = true;
    }

    public function fillFromExistingPhone($phone): void
    {
        $user = User::where('phone_number', trim($phone))->first();

        if ($user) {
            $this->user_name = $user->full_name;
            $this->is_existing_user = true;
        } else {
            $this->reset(['user_name']);
            $this->is_existing_user = false;
        }
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
    }

    /** Auto-fill name when phone is entered */
    public function updatedPhone($value): void
    {
<<<<<<< HEAD
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
=======
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        } else {
            $this->fillFromExistingPhone($value);
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
        }
    }

    public function submit(): void
    {
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }

        $this->validate();

        DB::beginTransaction();

        try {
            // 1) Find or create user
            $user = User::where('phone_number', $this->phone)->first();

<<<<<<< HEAD
            if (!$user) {
                $userRole = Role::where('role_code', 'USER')->first();
                if (!$userRole) {
                    throw new \Exception('User role not found. Please run database seeder.');
=======
            if ($user) {
                if ($this->user_name && $user->full_name !== $this->user_name) {
                    $user->update(['full_name' => $this->user_name]);
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
                }
            } else {
                $userRole = Role::where('role_code', 'USER')->firstOrFail();

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

<<<<<<< HEAD
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
=======
            $photoUrl = null;
            if (!empty($this->photos)) {
                $uploadedPhotos = [];
                foreach ($this->photos as $photo) {
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('reports/lost', $filename, 'public');
                    $uploadedPhotos[] = Storage::url($path);
                }
                $photoUrl = $uploadedPhotos[0];
            }

>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
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
<<<<<<< HEAD

            // Reset form (keep company)
            $this->reset([
                'item_name', 'category', 'description',
                'location', 'date_lost', 'phone', 'user_name', 'email',
                'photos', 'is_existing_user',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
=======
            $this->reset(['item_name', 'category', 'description', 'location', 'date_lost', 'photos']);
        } catch (\Exception $e) {
            DB::rollBack();
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
<<<<<<< HEAD
            'categories' => $this->getCategories(),
=======
            'categories' => Category::where('company_id', $this->company_id)->orderBy('category_name')->get(),
>>>>>>> 4a599d494e74fbcaf92fc199a85d087d6516e1df
        ])->layout('components.layouts.user', [
            'title' => 'Report Lost Item',
        ]);
    }
}
