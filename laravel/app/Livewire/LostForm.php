<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class LostForm extends Component
{
    use WithFileUploads;

    // --- FORM STATE ---
    public string  $item_name = '';
    public string  $description = '';
    public ?string $location = null;
    public ?string $date_lost = null;
    public ?string $category = null;
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool    $is_existing_user = false;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $newPhotos = [];

    public ?string $company_id = null;

    // --- UI STATE ---
    public int $step = 1;
    public ?string $submitted_report_id = null;
    public bool $show_success = false;

    // kunci untuk reset input file (dibinding ke wire:key di Blade)
    public int $uploadKey = 0;

    protected function rules(): array
    {
        return [
            // step 1
            'phone'       => 'required|string|max:30',
            'user_name'   => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'date_lost'   => 'nullable|date|before_or_equal:today',

            // step 2
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|uuid',
            'description' => 'required|string|min:10|max:200',

            // photos
            'photos'      => 'nullable|array|max:5',
            'photos.*'    => 'nullable|image|max:25600',
            'newPhotos'   => 'nullable|array',
            'newPhotos.*' => 'nullable|image|max:25600',
        ];
    }

    protected array $messages = [
        'item_name.required'     => 'Item name is required.',
        'description.required'   => 'Please describe the lost item.',
        'description.min'        => 'Description must be at least 10 characters.',
        'description.max'        => 'Description cannot exceed 200 characters.',
        'phone.required'         => 'Phone number is required.',
        'user_name.required'     => 'Name is required for new users.',
        'category.required'      => 'Category is required.',
        'category.uuid'          => 'Invalid category id.',
        'photos.max'             => 'You can upload a maximum of 5 photos.',
        'photos.*.image'         => 'Each file must be an image.',
        'photos.*.max'           => 'Each image must not exceed 25MB.',
        'newPhotos.*.image'      => 'Each file must be an image.',
        'newPhotos.*.max'        => 'Each image must not exceed 25MB.',
        'date_lost.before_or_equal' => 'Date lost cannot be in the future.',
    ];

    public function mount(): void
    {
        $this->company_id = session('company_id') ?? Company::first()?->company_id;
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        }
    }

    private function fillFromAuthenticatedUser(): void
    {
        $this->phone     = Auth::user()->phone_number ?? null;
        $this->user_name = Auth::user()->full_name ?? null;
        $this->is_existing_user = true;
    }

    private function fillFromExistingPhone($phone): void
    {
        $user = User::where('phone_number', trim((string) $phone))->first();
        if ($user) {
            $this->user_name        = $user->full_name;
            $this->is_existing_user = true;
        } else {
            $this->user_name        = null;
            $this->is_existing_user = false;
        }
    }

    public function updatedPhone($value): void
    {
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
        } else {
            $this->fillFromExistingPhone($value);
        }
    }

    public function updatedNewPhotos(): void
    {
        $this->validateOnly('newPhotos.*');

        $allowed = max(0, 5 - count($this->photos));
        foreach (array_slice($this->newPhotos, 0, $allowed) as $file) {
            $this->photos[] = $file;
        }

        // kosongkan newPhotos + paksa re-render input file
        $this->newPhotos = [];
        $this->uploadKey++;
    }

    public function removePhoto(int $index): void
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
            $this->uploadKey++; // sync id input file
        }
    }

    public function nextStep(): void
    {
        // Validasi step 1 sebelum lanjut
        $this->validate([
            'phone'      => 'required|string|max:30',
            'user_name'  => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'location'   => 'nullable|string|max:255',
            'date_lost'  => 'nullable|date|before_or_equal:today',
        ]);
        $this->step = 2;
    }

    public function previousStep(): void
    {
        $this->step = 1;
    }

    public function submit(): void
    {
        // Pastikan step 1 & 2 tervalidasi
        $this->validate();

        DB::beginTransaction();

        try {
            // Upsert user by phone
            $user = User::where('phone_number', $this->phone)->first();
            if ($user) {
                if ($this->user_name && $user->full_name !== $this->user_name) {
                    $user->update(['full_name' => $this->user_name]);
                }
            } else {
                $role = Role::where('role_code', 'USER')->firstOrFail();
                $user = User::create([
                    'user_id'      => Str::uuid(),
                    'company_id'   => null,
                    'role_id'      => $role->role_id,
                    'full_name'    => $this->user_name,
                    'email'        => null,
                    'phone_number' => $this->phone,
                    'password'     => null,
                    'is_verified'  => false,
                ]);
            }

            // Simpan foto
            $photoPaths = [];
            foreach ($this->photos as $file) {
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $photoPaths[] = $file->storeAs('reports/lost/'.$user->user_id, $filename, 'public');
            }
            $primaryPhoto = $photoPaths[0] ?? null;
            // $photosJson = $photoPaths ? json_encode($photoPaths) : null;

            // Simpan report
            $report = Report::create([
                'report_id'          => Str::uuid(),
                'company_id'         => $this->company_id,
                'user_id'            => $user->user_id,
                'item_id'            => null,
                'category_id'        => $this->category,
                'report_type'        => 'LOST',
                'item_name'          => $this->item_name,
                'report_description' => $this->description,
                'report_datetime'    => $this->date_lost ?? now(),
                'report_location'    => $this->location ?? 'Not specified',
                'report_status'      => 'OPEN',
                'photo_url'          => $primaryPhoto,
                // 'photos'          => $photosJson, // jika ada kolom JSON
                'reporter_name'      => $user->full_name,
                'reporter_phone'     => $user->phone_number,
                'reporter_email'     => $user->email,
            ]);

            DB::commit();

            $this->submitted_report_id = $report->report_id;
            $this->show_success = true;

            // (opsional) generate signed URL untuk auto-download PDF
            $signedUrl = URL::temporarySignedRoute(
                'reports.receipt.pdf',
                now()->addMinutes(10),
                ['report' => $report->report_id]
            );
            $this->dispatch('download-pdf', url: $signedUrl);

            // Flash + reset form (tanpa reload)
            session()->flash('message', 'Report submitted successfully.');
            $this->resetForm();

            // --- HARD RELOAD setelah 800ms (agar event di atas sempat jalan) ---
            $this->js('setTimeout(() => window.location.reload(), 800)');

        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to submit report. Please try again.');
            report($e);
        }
    }

    private function resetForm(): void
    {
        // kosongkan seluruh field input & state upload
        $this->item_name = '';
        $this->description = '';
        $this->location = null;
        $this->date_lost = null;
        $this->category = null;
        $this->phone = null;
        $this->user_name = null;
        $this->is_existing_user = false;

        $this->photos = [];
        $this->newPhotos = [];

        $this->step = 1;

        // paksa file input remount
        $this->uploadKey++;
    }

    public function render()
    {
        return view('livewire.lost-form', [
            'categories' => Category::when($this->company_id, fn($q) => $q->where('company_id', $this->company_id))
                                    ->orderBy('category_name')
                                    ->get(),
        ])->layout('components.layouts.user', [
            'title' => 'Report Lost Item',
        ]);
    }
}
