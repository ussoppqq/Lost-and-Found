<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundForm extends Component
{
    use WithFileUploads;

    // --- FORM STATE ---
    public string $item_name = '';
    public string $description = '';
    public ?string $location = null;
    public ?string $date_found = null;
    public ?string $category = null;
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool $is_existing_user = false;
    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public $photos = [];
    public $company_id;

    // --- WIZARD STATE ---
    public int $step = 1; // 1 = Finder Info, 2 = Item Details

    // ---------- VALIDATION ----------
    protected function step1Rules(): array
    {
        return [
            'phone'      => 'required|string|max:30',
            'user_name'  => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'location'   => 'nullable|string|max:255',
            'date_found' => 'nullable|date|before_or_equal:today',
        ];
    }

    protected function step2Rules(): array
    {
        return [
            'item_name'   => 'required|string|max:255',
            'category'    => 'nullable|uuid',
            'description' => 'required|string|min:10|max:200',
            'photos'      => 'nullable|array|max:5',
            'photos.*'    => 'nullable|image|max:25600', // 25MB each
        ];
    }

    protected function rules()
    {
        // Full rules used on final submit
        return array_merge($this->step1Rules(), $this->step2Rules());
    }

    protected array $messages = [
        'item_name.required' => 'Item name is required.',
        'description.required' => 'Please describe the found item.',
        'description.min' => 'Description must be at least 10 characters.',
        'description.max' => 'Description cannot exceed 200 characters.',
        'phone.required' => 'Phone number is required.',
        'user_name.required' => 'Name is required for new users.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image must not exceed 25MB.',
        'photos.max' => 'You can upload a maximum of 5 photos.',
        'date_found.before_or_equal' => 'Date found cannot be in the future.',
    ];

    // ---------- LIFECYCLE ----------
    public function mount()
    {
        $this->company_id = session('company_id') ?? Company::first()?->company_id;

        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }
    }

    // ---------- HELPERS ----------
    public function fillFromAuthenticatedUser(): void
    {
        $this->phone = Auth::user()->phone_number;
        $this->user_name = Auth::user()->full_name;
        $this->is_existing_user = true;
    }

    public function fillFromExistingPhone($phone): void
    {
        $user = User::where('phone_number', trim((string)$phone))->first();

        if ($user) {
            $this->user_name = $user->full_name;
            $this->is_existing_user = true;
        } else {
            $this->reset(['user_name']);
            $this->is_existing_user = false;
        }
    }

    public function updatedPhone($value)
    {
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        } else {
            $this->fillFromExistingPhone($value);
        }
    }

    // ---------- WIZARD ACTIONS ----------
    public function nextStep(): void
    {
        // Ensure step-1 is valid before moving on
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }
        $this->validate($this->step1Rules(), $this->messages);
        $this->step = 2;
    }

    public function previousStep(): void
    {
        $this->step = 1;
    }

    // ---------- SUBMIT ----------
    public function submit(): void
    {
        // Final submit always validates everything
        $this->step = 2;

        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }

        $this->validate($this->rules(), $this->messages);

        DB::beginTransaction();

        try {
            // Upsert user by phone
            $user = User::where('phone_number', $this->phone)->first();

            if ($user) {
                if ($this->user_name && $user->full_name !== $this->user_name) {
                    $user->update(['full_name' => $this->user_name]);
                }
            } else {
                $userRole = Role::where('role_code', 'USER')->firstOrFail();

                $user = User::create([
                    'user_id'      => Str::uuid(),
                    'company_id'   => null,
                    'role_id'      => $userRole->role_id,
                    'full_name'    => $this->user_name,
                    'email'        => null,
                    'phone_number' => $this->phone,
                    'password'     => null,
                    'is_verified'  => false,
                ]);
            }

            // Save first photo only (column is single URL)
            $photoUrl = null;
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $filename = Str::uuid().'.'.$photo->getClientOriginalExtension();
                    $photoUrl = $photo->storeAs('reports/found', $filename, 'public');
                    break;
                }
            }

            // Create FOUND report
            Report::create([
                'report_id'          => Str::uuid(),
                'company_id'         => $this->company_id,
                'user_id'            => $user->user_id,
                'item_id'            => null,
                'category_id'        => $this->category,
                'report_type'        => 'FOUND',
                'item_name'          => $this->item_name,
                'report_description' => $this->description,
                'report_datetime'    => $this->date_found ?? now(),
                'report_location'    => $this->location ?? 'Not specified',
                'report_status'      => 'OPEN',
                'photo_url'          => $photoUrl,
                'reporter_name'      => $user->full_name,
                'reporter_phone'     => $user->phone_number,
                'reporter_email'     => $user->email,
            ]);

            DB::commit();

            session()->flash('status', '✓ Found item reported successfully!');

            // Reset item fields only; keep contact for convenience
            $this->reset(['item_name', 'category', 'description', 'location', 'date_found', 'photos']);
            $this->step = 1;
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    // ---------- PHOTO REMOVE ----------
    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    public function render()
    {
        return view('livewire.found-form', [
            'categories' => Category::where('company_id', $this->company_id)
                ->orderBy('category_name')->get(),
        ])->layout('components.layouts.user', [
            'title' => 'Report Found Item',
        ]);
    }
}
