<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FoundForm extends Component
{
    use WithFileUploads;

    // Form fields
    public string $item_name = '';
    public string $category = '';
    public string $description = '';
    public ?string $location = null;
    public ?string $date_found = null;

    // Contact fields
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool $show_name_input = false;

    // File upload
    public $photo;

    // Company ID
    public $company_id;

    // Validation rules
    protected function rules()
    {
        return [
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|uuid',
            'description' => 'required|string|min:10',
            'location'    => 'nullable|string|max:255',
            'date_found'  => 'nullable|date|before_or_equal:today',
            'phone'       => 'required|string|max:30',
            'user_name'   => $this->show_name_input ? 'required|string|max:255' : 'nullable',
            'photo'       => 'nullable|image|max:3072', // 3MB
        ];
    }

    protected $messages = [
        'item_name.required'   => 'Item name is required.',
        'category.required'    => 'Please select a category.',
        'description.required' => 'Please describe the item.',
        'description.min'      => 'Description must be at least 10 characters.',
        'phone.required'       => 'Phone number is required.',
        'user_name.required'   => 'Please enter your name.',
        'photo.image'          => 'Photo must be an image file.',
        'photo.max'            => 'Max photo size is 3MB.',
        'date_found.before_or_equal' => 'Date found cannot be in the future.',
    ];

    public function mount()
    {
        $this->company_id = session('company_id') ?? Company::first()?->company_id;
    }

    // Computed property for categories (Livewire 3 style)
    public function getCategoriesProperty()
    {
        return Category::where('company_id', $this->company_id)
            ->orderBy('category_name')
            ->get();
    }

    public function updatedPhone($value)
    {
        // Clean phone number
        $cleanPhone = trim($value);
        
        if (!empty($cleanPhone)) {
            // Check if user exists in database
            $user = User::where('phone_number', $cleanPhone)->first();
            
            if ($user) {
                // User found - auto-fill name and hide input
                $this->user_name = $user->full_name;
                $this->show_name_input = false;
            } else {
                // User not found - show name input field
                $this->user_name = null;
                $this->show_name_input = true;
            }
        } else {
            $this->user_name = null;
            $this->show_name_input = false;
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();
        
        try {
            // 1. Check or create user
            $user = User::where('phone_number', $this->phone)->first();
            
            if (!$user) {
                // Validate that user_name is provided for new users
                if (empty($this->user_name)) {
                    throw new \Exception('Please enter your name.');
                }
                
                $userRole = Role::where('role_code', 'USER')->first();
                
                if (!$userRole) {
                    throw new \Exception('User role not found. Please contact administrator.');
                }
                
                // Create new user
                $user = User::create([
                    'user_id' => Str::uuid(),
                    'company_id' => null, // Regular users don't have company
                    'role_id' => $userRole->role_id,
                    'full_name' => $this->user_name,
                    'email' => null,
                    'phone_number' => $this->phone,
                    'password' => null,
                    'is_verified' => false,
                ]);
            }

            // 2. Upload photo if provided
            $photoUrl = null;
            if ($this->photo) {
                try {
                    $filename = Str::uuid() . '.' . $this->photo->getClientOriginalExtension();
                    
                    // Store in public disk
                    $path = $this->photo->storeAs('reports/found', $filename, 'public');
                    
                    // Get full URL
                    $photoUrl = asset('storage/' . $path);
                    
                    \Log::info('Photo uploaded successfully', [
                        'filename' => $filename,
                        'path' => $path,
                        'url' => $photoUrl
                    ]);
                    
                } catch (\Exception $uploadError) {
                    \Log::error('Photo upload error: ' . $uploadError->getMessage());
                    // Continue without photo if upload fails
                }
            }

            // 3. Create FOUND report
            Report::create([
                'report_id' => Str::uuid(),
                'company_id' => $this->company_id,
                'user_id' => $user->user_id,
                'item_id' => null, // No item yet
                'category_id' => $this->category,
                'report_type' => 'FOUND',
                'item_name' => $this->item_name,
                'report_description' => $this->description,
                'report_datetime' => $this->date_found ? $this->date_found . ' 00:00:00' : now(),
                'report_location' => $this->location ?? 'Not specified',
                'report_status' => 'OPEN',
                'photo_url' => $photoUrl,
                'reporter_name' => $user->full_name,
                'reporter_phone' => $user->phone_number,
                'reporter_email' => $user->email,
            ]);

            DB::commit();

            session()->flash('status', '✓ Found item reported successfully! Thank you for helping reunite items with their owners.');

            // Reset form
            $this->reset([
                'item_name', 'category', 'description',
                'location', 'date_found', 'phone', 'user_name', 'photo', 'show_name_input'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Found Item Report Error: ' . $e->getMessage());
            
            session()->flash('error', 'Failed to submit report: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.found-form', )
            ->layout('components.layouts.app', [
                'title' => 'Report Found Item',
            ]);
    }
}