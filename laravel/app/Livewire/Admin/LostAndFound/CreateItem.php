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

    public $mode = 'standalone';
    public $reportId = null;
    public $report = null;

    // Report fields
    public $report_type = 'FOUND';
    public $report_description;
    public $report_location;
    public $report_datetime;

    // Reporter info
    public $reporter_name;
    public $reporter_phone;
    public $reporter_email;

    // Item fields (only for FOUND items)
    public $item_name;
    public $brand;
    public $color;
    public $item_description;
    public $storage;
    public $item_status = 'STORED'; // Default STORED for FOUND items
    public $sensitivity_level = 'NORMAL';
    public $category_id;
    public $post_id;

    // Photos
    public $photos = []; // For admin upload (FOUND items)
    public $reportPhotos = []; // Photos from user report

    public $showModal = false;

    protected $listeners = [
        'open-create-item-modal' => 'openModalFromReport',
        'open-create-item-modal-standalone' => 'openModalStandalone',
    ];

    protected function rules()
    {
        $rules = [];

        // Common rules for all modes
        if ($this->mode === 'standalone') {
            $rules['report_type'] = 'required|in:LOST,FOUND';
            $rules['report_description'] = 'required|string';
            $rules['report_location'] = 'required|string|max:255';
            $rules['report_datetime'] = 'required|date';
            $rules['reporter_name'] = 'required|string|max:255';
            $rules['reporter_phone'] = 'required|string|max:20';
            $rules['reporter_email'] = 'required|email|max:255';
            $rules['item_name'] = 'required|string|max:255';
            $rules['category_id'] = 'required|exists:categories,category_id';
        }

        // Rules khusus untuk FOUND items
        if ($this->report_type === 'FOUND') {
            $rules['brand'] = 'nullable|string|max:255';
            $rules['color'] = 'nullable|string|max:100';
            $rules['storage'] = 'nullable|string|max:255';
            $rules['item_status'] = 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED';
            $rules['sensitivity_level'] = 'required|in:NORMAL,RESTRICTED';
            $rules['post_id'] = 'required|exists:posts,post_id';
        }
        
        // Photos for both LOST and FOUND
        $rules['photos.*'] = 'nullable|image|max:2048';

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
        $this->report = Report::with(['user'])->findOrFail($reportId);

        // Pre-fill data dari report
        $this->item_name = $this->report->item_name ?? '';
        $this->report_description = $this->report->report_description;
        $this->report_location = $this->report->report_location;
        $this->report_datetime = $this->report->report_datetime;
        $this->report_type = $this->report->report_type;
        $this->category_id = $this->report->category_id;

        // Reporter info
        if ($this->report->user) {
            $this->reporter_name = $this->report->user->full_name;
            $this->reporter_phone = $this->report->user->phone_number ?? '-';
            $this->reporter_email = $this->report->user->email;
        } else {
            $this->reporter_name = $this->report->reporter_name ?? 'Anonymous';
            $this->reporter_phone = $this->report->reporter_phone ?? '-';
            $this->reporter_email = $this->report->reporter_email ?? '-';
        }

        // Load photos from report (user uploaded)
        if ($this->report->photo_url) {
            $this->reportPhotos = [$this->report->photo_url];
        }

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
            'reportPhotos',
            'report_type',
            'report_description',
            'report_location',
            'reporter_name',
            'reporter_phone',
            'reporter_email',
        ]);

        $this->item_status = 'REGISTERED';
        $this->sensitivity_level = 'NORMAL';
        $this->report_type = 'FOUND';
        $this->report_datetime = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function removePhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    private function getOrCreateWalkInUser($companyId)
    {
        $user = User::where('email', $this->reporter_email)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            $user->update([
                'full_name' => $this->reporter_name,
                'phone_number' => $this->reporter_phone,
            ]);
            return $user;
        }

        $roleId = \App\Models\Role::where('role_code', 'USER')->first()?->role_id
            ?? \App\Models\Role::where('role_code', 'GUEST')->first()?->role_id;

        if (!$roleId) {
            throw new \Exception('Default role not found.');
        }

        $user = User::create([
            'user_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $roleId,
            'full_name' => $this->reporter_name,
            'email' => $this->reporter_email,
            'phone_number' => $this->reporter_phone,
            'password' => Hash::make(Str::random(16)),
            'is_verified' => false,
        ]);

        return $user;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $userId = null;

            // Get or create user
            if ($this->mode === 'standalone') {
                $walkInUser = $this->getOrCreateWalkInUser($companyId);
                $userId = $walkInUser->user_id;
            } else {
                $userId = $this->report->user_id;
            }

            $itemId = null;

            // ============================================
            // FOUND ITEMS: Create physical item record
            // ============================================
            if ($this->report_type === 'FOUND') {
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

                $itemId = $item->item_id;

                // Save item photos (admin upload)
                if (!empty($this->photos)) {
                    foreach ($this->photos as $index => $photo) {
                        $path = $photo->store('item-photos', 'public');
                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $itemId,
                            'photo_url' => $path,
                            'alt_text' => $this->item_name . ' - Photo ' . ($index + 1),
                            'display_order' => $index,
                        ]);
                    }
                }
            }

            // ============================================
            // Create or Update Report
            // ============================================
            if ($this->mode === 'standalone') {
                // Save reference photos to storage first (for LOST items)
                $photoUrl = null;
                if (!empty($this->photos) && $this->report_type === 'LOST') {
                    // For LOST items, save first photo as report photo
                    $photoUrl = $this->photos[0]->store('report-photos', 'public');
                }

                Report::create([
                    'report_id' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'item_id' => $itemId, // NULL for LOST, has value for FOUND
                    'category_id' => $this->category_id,
                    'report_type' => $this->report_type,
                    'item_name' => $this->item_name,
                    'report_description' => $this->report_description,
                    'report_datetime' => $this->report_datetime,
                    'report_location' => $this->report_location,
                    'report_status' => $this->report_type === 'FOUND' ? 'STORED' : 'OPEN',
                    'photo_url' => $photoUrl, // Only for LOST items
                    'reporter_name' => $this->reporter_name,
                    'reporter_phone' => $this->reporter_phone,
                    'reporter_email' => $this->reporter_email,
                ]);
            } else {
                // Update existing report
                Report::where('report_id', $this->reportId)->update([
                    'item_id' => $itemId,
                    'report_status' => $this->report_type === 'FOUND' ? 'STORED' : 'OPEN',
                ]);
            }

            DB::commit();

            $message = $this->report_type === 'FOUND' 
                ? 'Found item registered successfully!' 
                : 'Lost item report confirmed!';
                
            session()->flash('success', $message);
            $this->closeModal();
            $this->dispatch('item-created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('item_name', 'Error: ' . $e->getMessage());
            \Log::error('Error creating item: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)->get();
        $posts = Post::where('company_id', auth()->user()->company_id)->get();

        // Status options based on report type
        $statusOptions = [];
        if ($this->report_type === 'FOUND') {
            $statusOptions = [
                ['value' => 'REGISTERED', 'label' => 'Registered (Initial)'],
                ['value' => 'STORED', 'label' => 'Stored (In Storage)'],
                ['value' => 'CLAIMED', 'label' => 'Claimed (Owner Found)'],
                ['value' => 'RETURNED', 'label' => 'Returned (Given Back)'],
                ['value' => 'DISPOSED', 'label' => 'Disposed (After Retention)'],
            ];
        }

        return view('livewire.admin.lost-and-found.create-item', [
            'categories' => $categories,
            'posts' => $posts,
            'statusOptions' => $statusOptions,
        ]);
    }
}