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

class LostForm extends Component
{
    use WithFileUploads;

    // Form fields
    public string $item_name = '';

    public string $category = '';

    public string $description = '';

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
    ];

    public function mount()
    {
        $this->company_id = session('company_id') ?? Company::first()?->company_id;
    }

    public function getCategories()
    {
        return Category::where('company_id', $this->company_id)
            ->orderBy('category_name')
            ->get();
    }

    public function updatedPhone($value)
    {
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
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
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
                    'phone_number' => $this->phone,
                    'password' => null,
                    'is_verified' => false,
                ]);
            }


            $photoUrl = null;
            if (! empty($this->photos)) {
                $uploadedPhotos = [];
                foreach ($this->photos as $index => $photo) {
                    $filename = Str::uuid().'.'.$photo->getClientOriginalExtension();
                    $path = $photo->storeAs('reports/lost', $filename, 'public');
                    $uploadedPhotos[] = Storage::url($path);
                }
                
                $photoUrl = $uploadedPhotos[0]; 
            }

            // 3. Create LOST report
            Report::create([
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
            ]);

            DB::commit();

            session()->flash('status', '✓ Lost item reported successfully! We will notify you if we find a match.');

            // Reset form
            $this->reset([
                'item_name', 'category', 'description',
                'location', 'date_lost', 'phone', 'user_name', 'photos',
                'is_existing_user',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Lost Item Report Error: '.$e->getMessage());

            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => $this->getCategories(),
        ])
            ->layout('components.layouts.user', [
                'title' => 'Report Lost Item',
            ]);
    }
}
