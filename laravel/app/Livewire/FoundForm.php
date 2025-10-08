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

class FoundForm extends Component
{
    use WithFileUploads;

    // Form fields
    public string $item_name = '';
    public string $description = '';
    public ?string $location = null;
    public ?string $date_found = null;
    public ?string $category = null;

    // Contact
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool $is_existing_user = false;

    // Photos
    public $photos = [];

    public $company_id;

    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|uuid',
            'description' => 'required|string|min:10|max:200',
            'location' => 'nullable|string|max:255',
            'date_found' => 'nullable|date|before_or_equal:today',
            'phone' => 'required|string|max:30',
            'user_name' => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'photos.*' => 'nullable|image|max:25600', // 25MB per image
            'photos' => 'nullable|array|max:5',
        ];
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
        if (!empty($value)) {
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

if ($user) {
    
    if ($this->user_name && $user->full_name !== $this->user_name) {
        $user->update(['full_name' => $this->user_name]);
    }
} else {
  
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
            if (!empty($this->photos)) {
                $uploadedPhotos = [];
                foreach ($this->photos as $photo) {
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('reports/found', $filename, 'public');
                    $uploadedPhotos[] = Storage::url($path);
                }
                $photoUrl = $uploadedPhotos[0];
            }

           
            Report::create([
                'report_id' => Str::uuid(),
                'company_id' => $this->company_id,
                'user_id' => $user->user_id,
                'item_id' => null,
                'category_id' => $this->category,
                'report_type' => 'FOUND',
                'item_name' => $this->item_name,
                'report_description' => $this->description,
                'report_datetime' => $this->date_found ?? now(),
                'report_location' => $this->location ?? 'Not specified',
                'report_status' => 'OPEN',
                'photo_url' => $photoUrl,
                'reporter_name' => $user->full_name,
                'reporter_phone' => $user->phone_number,
                'reporter_email' => $user->email,
            ]);

            DB::commit();

            session()->flash('status', '✓ Found item reported successfully! Thank you for your honesty.');

            $this->reset([
                'item_name', 'description', 'location', 'date_found', 'category',
                'phone', 'user_name', 'photos', 'is_existing_user',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Found Item Report Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.found-form', [
            'categories' => $this->getCategories(),
        ])->layout('components.layouts.user', [
            'title' => 'Report Found Item',
        ]);
    }
}
