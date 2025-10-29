<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundForm extends Component
{
    use WithFileUploads;

    // --- FORM STATE ---
    public string  $item_name = '';
    public string  $description = '';
    public ?string $location = null;
    public ?string $date_found = null;
    public ?string $category = null;
    public ?string $phone = null;
    public ?string $user_name = null;
    public bool    $is_existing_user = false;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $newPhotos = [];

    public ?string $company_id = null;

    // --- OTP STATE ---
    public bool $needs_otp_verification = false;
    public string $otp_code = '';
    public bool $otp_sent = false;
    public ?string $otp_sent_at = null;
    public bool $otp_verified = false;

    // --- UI STATE ---
    public int $step = 1; // 1=Your Info, 2=Item Details
    public ?string $submitted_report_id = null;
    public bool $show_success = false;

    // kunci untuk reset input file (dibinding ke wire:key)
    public int $uploadKey = 0;

    // ---------- VALIDATION ----------
    protected function step1Rules(): array
    {
        return [
            'phone'      => 'required|string|max:30',
            'user_name'  => $this->is_existing_user ? 'nullable' : 'required|string|max:255',
            'location'   => 'nullable|string|max:255',
            'date_found' => 'nullable|date|before_or_equal:now',
        ];
    }

    protected function otpRules(): array
    {
        return ['otp_code' => 'required|string|size:6'];
    }

    protected function step2Rules(): array
    {
        return [
            'item_name'   => 'required|string|max:255',
            'category'    => 'nullable|uuid',
            'description' => 'required|string|min:10|max:200',
            'photos'      => 'nullable|array|max:5',
            'photos.*'    => 'nullable|image|max:25600',
            'newPhotos'   => 'nullable|array',
            'newPhotos.*' => 'nullable|image|max:25600',
        ];
    }

    protected function rules()
    {
        return array_merge($this->step1Rules(), $this->step2Rules());
    }

    protected array $messages = [
        'item_name.required'         => 'Item name is required.',
        'description.required'       => 'Please describe the found item.',
        'description.min'            => 'Description must be at least 10 characters.',
        'description.max'            => 'Description cannot exceed 200 characters.',
        'phone.required'             => 'Phone number is required.',
        'user_name.required'         => 'Name is required for new users.',
        'photos.*.image'             => 'Each file must be an image.',
        'photos.*.max'               => 'Each image must not exceed 25MB.',
        'photos.max'                 => 'You can upload a maximum of 5 photos.',
        'newPhotos.*.image'          => 'Each file must be an image.',
        'newPhotos.*.max'            => 'Each image must not exceed 25MB.',
        'date_found.before_or_equal' => 'Date and time cannot be in the future.',
        'otp_code.required'          => 'OTP code is required.',
        'otp_code.size'              => 'OTP code must be 6 digits.',
    ];

    // ---------- LIFECYCLE ----------
    public function mount(): void
    {
        $this->company_id = session('company_id') ?? Company::first()?->company_id;
        $this->date_found = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');

        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
            $this->needs_otp_verification = false;
            $this->otp_verified = true;
        }
    }

    // ---------- HELPERS ----------
    private function fillFromAuthenticatedUser(): void
    {
        $this->phone            = Auth::user()->phone_number ?? null;
        $this->user_name        = Auth::user()->full_name ?? null;
        $this->is_existing_user = true;
    }

    private function fillFromExistingPhone($phone): void
    {
        $user = User::where('phone_number', trim((string)$phone))->first();

        if ($user) {
            $this->user_name = $user->full_name;
            $this->is_existing_user = true;
            $this->needs_otp_verification = false;
            $this->otp_sent = false;
            $this->otp_code = '';
            $this->otp_verified = true;
            session()->forget(['otp_success', 'otp_error']);
        } else {
            $this->reset(['user_name']);
            $this->is_existing_user = false;
            $this->needs_otp_verification = true;
            $this->otp_verified = false;

            if (!empty($phone)) {
                $this->sendOtpAutomatically();
            }
        }
    }

    public function updatedPhone($value): void
    {
        if (Auth::check()) {
            $this->fillFromAuthenticatedUser();
            $this->needs_otp_verification = false;
            $this->otp_verified = true;
        } else {
            $this->fillFromExistingPhone($value);
        }
    }

    // ---------- OTP ----------
    public function sendOtpAutomatically(): void
    {
        if (empty($this->phone)) return;

        $otp = random_int(100000, 999999);
        Cache::put('otp_' . $this->phone, $otp, now()->addMinutes(5));
        Cache::put('otp_time_' . $this->phone, time(), now()->addMinutes(5));

        try {
            $message = "Kode OTP kamu adalah: {$otp}\n\nJangan bagikan kode ini ke siapapun.\n\nKode akan kadaluarsa dalam 5 menit.";
            $response = FonnteService::sendMessage($this->phone, $message);
            Log::info('Fonnte OTP Response', ['response' => $response]);

            $isSuccess = false;

            if (is_array($response)) {
                if (isset($response['status']) && $response['status'] === true) $isSuccess = true;
                elseif (isset($response['status']) && strtolower($response['status']) === 'success') $isSuccess = true;
                elseif (!isset($response['error']) && !isset($response['reason'])) $isSuccess = true;
                elseif (isset($response['detail']) && stripos($response['detail'], 'success') !== false) $isSuccess = true;
            } elseif (is_string($response) && (strtolower($response) === 'ok' || stripos($response, 'success') !== false)) {
                $isSuccess = true;
            }

            if ($isSuccess) {
                $this->otp_sent = true;
                $this->otp_sent_at = now()->toDateTimeString();
                session()->flash('otp_success', 'OTP has been sent to ' . $this->phone);
            } else {
                $errorMsg = $response['reason'] ?? $response['error'] ?? $response['message'] ?? 'Unknown error';
                session()->flash('otp_error', 'Failed to send OTP: ' . $errorMsg);
                $this->otp_sent = false;
            }
        } catch (\Exception $e) {
            Log::error("Error sending OTP: " . $e->getMessage(), [
                'exception' => $e,
                'phone_number' => $this->phone,
            ]);
            session()->flash('otp_error', 'Error sending OTP. Please try again.');
            $this->otp_sent = false;
        }
    }

    public function resendOtp(): void
    {
        $this->sendOtpAutomatically();
    }

    public function verifyOtpAndProceed(): void
    {
        $this->validate($this->otpRules(), $this->messages);

        $storedOtp = Cache::get('otp_' . $this->phone);
        if (!$storedOtp) {
            $this->addError('otp_code', 'OTP has expired. Please request a new one.');
            return;
        }

        if ($this->otp_code != $storedOtp) {
            $this->addError('otp_code', 'Invalid OTP code. Please try again.');
            return;
        }

        Cache::forget('otp_' . $this->phone);
        Cache::forget('otp_time_' . $this->phone);
        $this->needs_otp_verification = false;
        $this->otp_verified = true;

        session()->flash('status', '✓ Phone number verified successfully!');
    }

    // ---------- PHOTO FLOW ----------
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

    // ---------- NAVIGATION ----------
    public function nextStep(): void
    {
        if (Auth::check()) $this->fillFromAuthenticatedUser();

        // kalau masih butuh OTP dan belum verified → tahan
        if ($this->needs_otp_verification && !$this->otp_verified) {
            $this->addError('otp_code', 'Please verify your phone number first.');
            return;
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
        if (Auth::check()) $this->fillFromAuthenticatedUser();

        if ($this->needs_otp_verification && !$this->otp_verified) {
            $this->addError('general', 'Please verify your phone number first.');
            $this->step = 1;
            return;
        }

        $this->validate($this->rules(), $this->messages);

        DB::beginTransaction();

        try {
            // Upsert user
            $user = User::where('phone_number', $this->phone)->first();
            if ($user) {
                if ($this->user_name && $user->full_name !== $this->user_name) {
                    $user->update(['full_name' => $this->user_name]);
                }
            } else {
                $userRole = Role::where('role_code', 'USER')->firstOrFail();
                $user = User::create([
                    'user_id'           => Str::uuid(),
                    'company_id'        => null,
                    'role_id'           => $userRole->role_id,
                    'full_name'         => $this->user_name,
                    'email'             => null,
                    'phone_number'      => $this->phone,
                    'password'          => null,
                    'is_verified'       => true,
                    'phone_verified_at' => now(),
                ]);
            }

            // Simpan foto (ambil pertama sebagai primary)
            $photoPaths = [];
            foreach ($this->photos as $file) {
                $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
                $photoPaths[] = $file->storeAs('reports/found/'.$user->user_id, $filename, 'public');
            }
            $primaryPhoto = $photoPaths[0] ?? null;

            // Parse datetime WIB & simpan apa adanya (tanpa convert)
            $reportDateTime = $this->date_found
                ? Carbon::parse($this->date_found, 'Asia/Jakarta')
                : Carbon::now('Asia/Jakarta');

            // Create report
            $report = Report::create([
                'report_id'          => Str::uuid(),
                'company_id'         => $this->company_id,
                'user_id'            => $user->user_id,
                'item_id'            => null,
                'category_id'        => $this->category,
                'report_type'        => 'FOUND',
                'item_name'          => $this->item_name,
                'report_description' => $this->description,
                'report_datetime'    => $reportDateTime,
                'report_location'    => $this->location ?? 'Not specified',
                'report_status'      => 'OPEN',
                'photo_url'          => $primaryPhoto,
                'reporter_name'      => $user->full_name,
                'reporter_phone'     => $user->phone_number,
                'reporter_email'     => $user->email,
            ]);

            DB::commit();

            $this->submitted_report_id = $report->report_id;
            $this->show_success = true;

            // AUTO DOWNLOAD RECEIPT (signed URL)
            $signedUrl = URL::temporarySignedRoute(
                'reports.receipt.pdf',
                now()->addMinutes(10),
                ['report' => $report->report_id]
            );
            $this->dispatch('download-pdf', url: $signedUrl);

            // Flash + reset form
            session()->flash('message', 'Report submitted successfully.');
            $this->resetForm();

            // reload ringan agar event sempat jalan
            $this->js('setTimeout(() => window.location.reload(), 800)');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Found form submission error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to submit report. Please try again.');
        }
    }

    private function resetForm(): void
    {
        // kosongkan seluruh field input & state upload
        $this->item_name = '';
        $this->description = '';
        $this->location = null;
        $this->date_found = now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i');
        $this->category = null;
        $this->phone = null;
        $this->user_name = null;
        $this->is_existing_user = false;

        $this->photos = [];
        $this->newPhotos = [];

        $this->needs_otp_verification = false;
        $this->otp_code = '';
        $this->otp_sent = false;
        $this->otp_sent_at = null;
        $this->otp_verified = false;

        $this->step = 1;
        $this->uploadKey++;
    }

    public function render()
    {
        return view('livewire.found-form', [
            'categories' => Category::when($this->company_id, fn($q) => $q->where('company_id', $this->company_id))
                                    ->orderBy('category_name')
                                    ->get(),
        ])->layout('components.layouts.user', [
            'title' => 'Report Found Item',
        ]);
    }
}
