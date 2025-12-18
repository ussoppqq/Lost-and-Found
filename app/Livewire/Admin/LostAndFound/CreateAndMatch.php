<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Item;
use App\Models\Report;
use App\Models\ReportPhoto;
use App\Models\Category;
use App\Models\Post;
use App\Models\ItemPhoto;
use App\Models\User;
use App\Models\MatchedItem;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateAndMatch extends Component
{
    use WithFileUploads;

    public $sourceReportId;
    public $sourceReport;
    public $oppositeType; // 'LOST' or 'FOUND'

    // Report fields
    public $item_name;
    public $report_description;
    public $report_location;
    public $report_datetime;
    public $category_id;

    // Reporter info
    public $reporter_name;
    public $reporter_phone;
    public $reporter_email;
    public $reporterMode = 'user';

    // FOUND Item specific fields
    public $brand;
    public $color;
    public $item_description;
    public $storage;
    public $post_id;
    public $item_status = 'STORED';
    public $sensitivity_level = 'NORMAL';

    // Photos
    public $photos = [];
    public $newPhotos = [];
    public $uploadKey = 0;

    // Modal state
    public $showModal = false;
    public $showClaimModal = false;
    public $createdReport = null;

    protected $listeners = [
        'open-create-and-match-modal' => 'openModal',
        'claim-modal-closed' => 'handleClaimModalClosed',
    ];

    protected function rules()
    {
        $rules = [
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'report_description' => 'required|string',
            'report_location' => 'required|string|max:255',
            'report_datetime' => 'required|date',
            'reporter_name' => 'required|string|max:255',
            'reporter_phone' => 'required|string|max:20',
            'reporter_email' => 'nullable|email|max:255',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:5120',
        ];

        // FOUND specific validations
        if ($this->oppositeType === 'FOUND') {
            $rules['reporterMode'] = 'required|in:user,moderator';
            $rules['brand'] = 'nullable|string|max:255';
            $rules['color'] = 'nullable|string|max:100';
            $rules['storage'] = 'nullable|string|max:255';
            $rules['post_id'] = 'required|exists:posts,post_id';
            $rules['item_status'] = 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED';
            $rules['sensitivity_level'] = 'required|in:NORMAL,RESTRICTED';
        }

        return $rules;
    }

    public function mount()
    {
        $this->report_datetime = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
    }

    public function openModal($data)
    {
        $this->sourceReportId = $data['sourceReportId'];
        $this->sourceReport = Report::with(['user', 'category', 'photos'])->findOrFail($data['sourceReportId']);
        $this->oppositeType = $data['oppositeType'];

        // Pre-fill dari source report
        $this->item_name = $this->sourceReport->item_name ?? '';
        $this->category_id = $this->sourceReport->category_id;

        $this->showModal = true;

        Log::info('CreateAndMatch modal opened', [
            'sourceReportId' => $this->sourceReportId,
            'oppositeType' => $this->oppositeType,
        ]);
    }

    public function updatedNewPhotos()
    {
        $this->validateOnly('newPhotos.*');

        $allowed = max(0, 5 - count($this->photos));

        foreach (array_slice($this->newPhotos, 0, $allowed) as $file) {
            $this->photos[] = $file;
        }

        $this->newPhotos = [];
        $this->uploadKey++;
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
            $this->uploadKey++;
        }
    }

    public function updatedReporterMode()
    {
        if ($this->reporterMode === 'moderator') {
            $this->reporter_name = auth()->user()->full_name;
            $this->reporter_phone = auth()->user()->phone_number ?? '';
            $this->reporter_email = auth()->user()->email ?? '';
        } else {
            $this->reporter_name = '';
            $this->reporter_phone = '';
            $this->reporter_email = '';
        }
    }

    private function getOrCreateWalkInUser($companyId)
    {
        if ($this->reporterMode === 'moderator') {
            return auth()->user();
        }

        $user = User::where('phone_number', $this->reporter_phone)
            ->where('company_id', $companyId)
            ->first();

        if ($user) {
            $updateData = ['full_name' => $this->reporter_name];

            if (!empty($this->reporter_email)) {
                $emailExists = User::where('email', $this->reporter_email)
                    ->where('user_id', '!=', $user->user_id)
                    ->where('company_id', $companyId)
                    ->exists();

                if (!$emailExists) {
                    $updateData['email'] = $this->reporter_email;
                }
            }

            $user->update($updateData);
            return $user;
        }

        if (!empty($this->reporter_email)) {
            $user = User::where('email', $this->reporter_email)
                ->where('company_id', $companyId)
                ->first();

            if ($user) {
                if (empty($user->phone_number)) {
                    $user->update([
                        'full_name' => $this->reporter_name,
                        'phone_number' => $this->reporter_phone,
                    ]);
                }
                return $user;
            }
        }

        $roleId = \App\Models\Role::where('role_code', 'USER')->first()?->role_id
            ?? \App\Models\Role::where('role_code', 'GUEST')->first()?->role_id;

        $email = !empty($this->reporter_email)
            ? $this->reporter_email
            : 'walkin_' . Str::uuid() . '@temp.local';

        return User::create([
            'user_id' => (string) Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $roleId,
            'full_name' => $this->reporter_name,
            'email' => $email,
            'phone_number' => $this->reporter_phone,
            'password' => Hash::make(Str::random(16)),
            'is_verified' => !empty($this->reporter_email),
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $companyId = auth()->user()->company_id;
            $walkInUser = $this->getOrCreateWalkInUser($companyId);

            $reportDateTime = Carbon::parse($this->report_datetime, 'Asia/Jakarta');

            $itemId = null;

            // Create FOUND item
            if ($this->oppositeType === 'FOUND') {
                $category = Category::findOrFail($this->category_id);
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

                // Upload item photos
                $photoOrder = 0;
                if (!empty($this->photos)) {
                    foreach ($this->photos as $photo) {
                        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('items/' . $itemId, $filename, 'public');

                        ItemPhoto::create([
                            'photo_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'item_id' => $itemId,
                            'photo_url' => $path,
                            'alt_text' => $this->item_name . ' - Photo ' . ($photoOrder + 1),
                            'display_order' => $photoOrder++,
                        ]);
                    }
                }
            }

            // Create Report
            $report = Report::create([
                'report_id' => (string) Str::uuid(),
                'company_id' => $companyId,
                'user_id' => $walkInUser->user_id,
                'item_id' => $itemId,
                'category_id' => $this->category_id,
                'report_type' => $this->oppositeType,
                'item_name' => $this->item_name,
                'report_description' => $this->report_description,
                'report_datetime' => $reportDateTime,
                'report_location' => $this->report_location,
                'report_status' => $this->oppositeType === 'FOUND' ? 'STORED' : 'OPEN',
                'photo_url' => null,
                'reporter_name' => $this->reporter_name,
                'reporter_phone' => $this->reporter_phone,
                'reporter_email' => $this->reporter_email,
            ]);

            // Upload report photos
            if (!empty($this->photos)) {
                foreach ($this->photos as $index => $photo) {
                    $folder = $this->oppositeType === 'FOUND' ? 'reports/found' : 'reports/lost';
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $photoPath = $photo->storeAs($folder . '/' . $walkInUser->user_id, $filename, 'public');

                    ReportPhoto::create([
                        'photo_id' => Str::uuid(),
                        'report_id' => $report->report_id,
                        'photo_url' => $photoPath,
                        'is_primary' => $index === 0,
                        'photo_order' => $index,
                    ]);

                    if ($index === 0) {
                        $report->update(['photo_url' => $photoPath]);
                    }
                }
            }

            $this->createdReport = $report;

            DB::commit();

            session()->flash('success', ucfirst(strtolower($this->oppositeType)) . ' report created successfully!');

            // Close create modal and open claim modal
            $this->showModal = false;
            $this->showClaimModal = true;

            Log::info('Report created successfully', [
                'report_id' => $report->report_id,
                'report_type' => $this->oppositeType,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Error: ' . $e->getMessage());
            Log::error('Error creating report', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeClaimModal()
    {
        $this->showClaimModal = false;
        $this->dispatch('refresh-quick-match');
    }

    public function handleClaimModalClosed()
    {
        $this->showClaimModal = false;
        $this->createdReport = null;
        $this->dispatch('refresh-quick-match');

        Log::info('Claim modal closed from QuickClaim');
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
            'newPhotos',
            'report_description',
            'report_location',
            'reporter_name',
            'reporter_phone',
            'reporter_email',
        ]);

        $this->item_status = 'STORED';
        $this->sensitivity_level = 'NORMAL';
        $this->reporterMode = 'user';
        $this->report_datetime = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->uploadKey++;
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)->get();
        $posts = Post::where('company_id', auth()->user()->company_id)->get();

        $statusOptions = [
            ['value' => 'STORED', 'label' => 'Stored (In Storage) - Default'],
            ['value' => 'CLAIMED', 'label' => 'Claimed (Owner Found)'],
            ['value' => 'RETURNED', 'label' => 'Returned (Given Back)'],
            ['value' => 'DISPOSED', 'label' => 'Disposed (After Retention)'],
        ];

        return view('livewire.admin.lost-and-found.create-and-match', [
            'categories' => $categories,
            'posts' => $posts,
            'statusOptions' => $statusOptions,
        ]);
    }
}