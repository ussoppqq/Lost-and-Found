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

class LostForm extends Component
{
    use WithFileUploads;

    // ===== Form fields =====
    public string $item_name = '';
    public string $category = '';
    
    public string $description = '';
    public ?string $location = null;
    public ?string $date_lost = null;

    // Contact fields
    public ?string $phone = null;
    public ?string $user_name = null; // Auto-filled dari database

    // File upload (optional)
    public $photo;

    // Company ID
    public $company_id;

    // ===== Validation rules =====
    protected array $rules = [
        'item_name'   => 'required|string|max:255',
        'category'    => 'required|uuid',
        'description' => 'required|string|min:10',
        'location'    => 'nullable|string|max:255',
        'date_lost'   => 'nullable|date|before_or_equal:today',
        'phone'       => 'required|string|max:30',
        'photo'       => 'nullable|image|max:3072', // 3MB
    ];

    // Optional: nicer messages
    protected array $messages = [
        'item_name.required'   => 'Item name is required.',
        'category.required'    => 'Please select a category.',
        'description.required' => 'Please describe the item.',
        'description.min'      => 'Description must be at least 10 characters.',
        'phone.required'       => 'Phone number is required.',
        'photo.image'          => 'Photo must be an image file.',
        'photo.max'            => 'Max photo size is 3MB.',
        'date_lost.before_or_equal' => 'Date lost cannot be in the future.',
    ];

    public function mount()
    {
        // Get company_id from session or use first company
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
        // Auto-fill nama dari database jika nomor sudah terdaftar
        if (!empty($value)) {
            $user = User::where('phone_number', $value)->first();
            $this->user_name = $user ? $user->full_name : null;
        } else {
            $this->user_name = null;
        }
    }

    public function submit(): void
    {
        $this->validate();

        DB::beginTransaction();
        
        try {
            // 1. Cek atau buat user (TIDAK DUPLIKAT)
            $user = User::where('phone_number', $this->phone)->first();
            
            if (!$user) {
                $userRole = Role::where('role_code', 'USER')->first();
                
                if (!$userRole) {
                    throw new \Exception('User role not found. Please run database seeder.');
                }
                
                $user = User::create([
                    'user_id' => Str::uuid(),
                    'company_id' => null, // User biasa tidak punya company
                    'role_id' => $userRole->role_id,
                    'full_name' => $this->user_name ?? 'Guest User',
                    'email' => null,
                    'phone_number' => $this->phone,
                    'password' => null,
                    'is_verified' => false,
                ]);
            }

            // 2. Upload photo jika ada
            $photoUrl = null;
            if ($this->photo) {
                $filename = Str::uuid() . '.' . $this->photo->getClientOriginalExtension();
                $path = $this->photo->storeAs('reports/lost', $filename, 'public');
                $photoUrl = Storage::url($path);
            }

            // 3. Buat report LOST
            Report::create([
                'report_id' => Str::uuid(),
                'company_id' => $this->company_id,
                'user_id' => $user->user_id,
                'item_id' => null, // Belum ada item
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
                'location', 'date_lost', 'phone', 'user_name', 'photo',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Lost Item Report Error: ' . $e->getMessage());
            
            session()->flash('error', 'Failed to submit report. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => $this->getCategories()
        ])
        ->layout('components.layouts.app', [
            'title' => 'Report Lost Item',
        ]);
    }
}