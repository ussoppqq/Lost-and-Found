<div class="min-h-screen bg-white py-4">
  <div class="mx-auto w-full px-4 max-w-lg lg:max-w-5xl">
    {{-- WRAPPER CARD --}}
    <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-8">

      {{-- Header --}}
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-800 shadow mb-3">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-2">Report Found Item</h1>
        <p class="text-sm text-gray-600">Fill out the form below with details about the item you found.</p>
      </div>

      {{-- Stepper (mobile) --}}
      <div class="flex justify-center mb-6 lg:hidden">
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2">
            <div
              class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm {{ $step === 1 ? 'bg-gray-800' : 'bg-white border border-gray-300 text-gray-500' }}">
              1
            </div>
            <span class="text-sm font-medium {{ $step === 1 ? 'text-gray-900' : 'text-gray-500' }}">Your Info</span>
          </div>
          <div class="w-8 h-0.5 bg-white border border-gray-200 rounded-full"></div>
          <div class="flex items-center gap-2">
            <div
              class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm {{ $step === 2 ? 'bg-gray-800' : 'bg-white border border-gray-300 text-gray-500' }}">
              2
            </div>
            <span class="text-sm font-medium {{ $step === 2 ? 'text-gray-900' : 'text-gray-500' }}">Item Details</span>
          </div>
        </div>
      </div>

      {{-- Flash top-level --}}
      @if(session('status'))
      <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">{{ session('status') }}</div>
      @endif
      @if(session('error'))
      <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">{{ session('error') }}</div>
      @endif
      @if(session('message'))
      <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-sm">{{ session('message') }}</div>
      @endif

      {{-- FORM --}}
      <form wire:submit.prevent="submit" class="space-y-6">

        {{-- ===== DESKTOP: 2 kolom ===== --}}
        <div class="hidden lg:grid lg:grid-cols-2 lg:gap-6">
          {{-- LEFT: Your Information --}}
          <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>

            {{-- Phone --}}
            <div class="mb-4">
              <label for="phone_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Phone Number <span class="text-red-500">*</span>
              </label>
              @if(auth()->check())
              <div class="flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200">
                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded-lg whitespace-nowrap">Logged-in</span>
              </div>
              @else
              {{-- <div class="flex gap-2"> --}}
                <input id="phone_d" type="tel" inputmode="tel" autocomplete="tel" wire:model.live="phone" required
                  class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                  placeholder="Enter your phone number">
                {{-- @if($needs_otp_verification && !$otp_verified)
                <button type="button" id="btnSendOtp" wire:click="sendOtpAutomatically"
                  class="px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 disabled:opacity-60 whitespace-nowrap">
                  Send OTP
                </button>
                @endif
              </div> --}}
              @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- OTP (desktop) --}}
            {{-- @if($needs_otp_verification && !$otp_verified)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-2xl p-5 mb-4 shadow-sm">
              <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-base font-bold text-blue-900 mb-1">Phone Verification Required</h3>
                  <p class="text-sm text-blue-700">Enter the 6-digit code sent to your WhatsApp</p>
                </div>
              </div>

              @if(session('otp_success'))
              <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-green-800 font-medium">{{ session('otp_success') }}</span>
              </div>
              @endif

              @if(session('otp_error'))
              <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-red-800 font-medium">{{ session('otp_error') }}</span>
              </div>
              @endif

              <div class="mb-4">
                <label for="otp_code_d" class="block text-sm font-semibold text-gray-900 mb-2">
                  Verification Code <span class="text-red-500">*</span>
                </label>
                <input id="otp_code_d" type="text" inputmode="numeric" maxlength="6" wire:model.defer="otp_code"
                  class="w-full px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] rounded-xl border-2 border-gray-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 placeholder:text-gray-400 placeholder:tracking-normal placeholder:text-base placeholder:font-normal"
                  placeholder="Enter 6-digit code" aria-describedby="otpHelpD">
                @error('otp_code')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                      clip-rule="evenodd" />
                  </svg>
                  {{ $message }}
                </p>
                @enderror
                <p id="otpHelpD" class="sr-only">6 digit verification code</p>
              </div>

              <div class="flex gap-2">
                <button type="button" wire:click="verifyOtpAndProceed"
                  class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Verify Code
                </button>
                <button type="button" id="btnResendOtp" wire:click="resendOtp"
                  class="px-4 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 active:scale-[0.98] transition-all">
                  <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span id="resendTimer"></span>
                </button>
              </div>

              <div class="mt-4 p-3 bg-white/60 rounded-lg border border-blue-200">
                <p class="text-xs text-gray-700 flex items-start gap-2">
                  <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>
                    <strong>Didn't receive the code?</strong> Check your WhatsApp messages.
                    The code is valid for 5 minutes. Click "Resend" if needed.
                  </span>
                </p>
              </div>
            </div>
            @endif --}}

            {{-- Name --}}
            <div class="mb-4">
              <label for="user_name_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Your Name <span class="text-red-500">*</span>
              </label>
              @if(auth()->check() || $is_existing_user)
              <div class="flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200">
                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1 break-words">{{ $user_name }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded-lg whitespace-nowrap">
                  {{ auth()->check() ? 'Logged-in' : 'Verified' }}
                </span>
              </div>
              @else
              <input id="user_name_d" type="text" autocomplete="name" wire:model.defer="user_name" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="Enter your full name">
              @error('user_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Location --}}
            <div class="mb-4">
              <label for="location_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Where You Found It <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="location_d" type="text" wire:model.defer="location"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="e.g. Library, Cafeteria, Hallway">
              @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Map Location Picker --}}
            <div class="mb-4">
              @livewire('location-picker', ['latitude' => $latitude, 'longitude' => $longitude])
            </div>

            {{-- Date Found --}}
            <div>
              <label for="date_found_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Date & Time Found <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="date_found_d" type="datetime-local" wire:model.defer="date_found"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
              @error('date_found') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Desktop helper: scroll ke Item Details --}}
            <div class="mt-4 hidden lg:block">
              <a href="#item-details"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-800 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Go to Item Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>

          {{-- RIGHT: Item Details --}}
          <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 id="item-details" class="text-lg font-semibold text-gray-900 mb-4">Item Details</h2>

            {{-- Item Name --}}
            <div class="mb-4">
              <label for="item_name_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Item Name <span class="text-red-500">*</span>
              </label>
              <input id="item_name_d" type="text" wire:model.defer="item_name" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="e.g. Black Wallet, Laptop, ID Card">
              @error('item_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div class="mb-4">
              <label for="category_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select id="category_d" wire:model="category" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 appearance-none bg-white"
                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; padding-right: 3rem;">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
              <label for="description_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Description <span class="text-red-500">*</span>
              </label>
              <textarea id="description_d" rows="5" wire:model.defer="description" maxlength="200"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 resize-none placeholder:text-gray-400"
                placeholder="Describe the found item: color, brand, unique marks, contents, etc."></textarea>
              @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photos (desktop) --}}
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">
                Photos <span class="text-gray-500 text-xs font-normal">(optional, max 5)</span>
              </label>

              {{-- wire:key untuk reset input --}}
              <div wire:key="upload-{{ $uploadKey }}-d">
                <input type="file" accept="image/*" multiple class="hidden" wire:model="newPhotos" id="foundPhotos_d">
              </div>

              <div class="space-y-3">
                @if(!empty($photos))
                <div class="grid grid-cols-3 gap-3">
                  @foreach($photos as $index => $photo)
                  <div class="relative aspect-square group">
                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                      class="w-full h-full rounded-xl object-cover border-2 border-gray-200">
                    <button type="button" wire:click="removePhoto({{ $index }})"
                      class="absolute -top-2 -right-2 p-2 bg-gray-800 text-white rounded-full shadow-lg opacity-90 group-hover:opacity-100 transition hover:bg-gray-700 active:scale-90">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  @endforeach
                </div>
                @endif

                @if(count($photos) < 5)
                <button type="button" onclick="document.getElementById('foundPhotos_d').click()"
                  class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-white hover:bg-gray-50 active:bg-gray-100 p-8 transition">
                  <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-base font-semibold text-gray-700">
                      @if(empty($photos)) Click to upload photos @else Add more photos ({{ count($photos) }}/5) @endif
                    </p>
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                  </div>
                </button>
                @else
                <div class="text-center p-4 bg-white rounded-xl border border-gray-200">
                  <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                </div>
                @endif

                <div wire:loading wire:target="newPhotos"
                  class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                  <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <p class="text-sm font-medium text-blue-700">Uploading photos...</p>
                  </div>
                </div>
              </div>

              @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('newPhotos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('newPhotos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        {{-- Submit (desktop only) --}}
        <div class="hidden lg:block pt-2">
          <div class="relative">
            <button type="submit" wire:loading.attr="disabled" wire:target="submit" id="btnSubmitReport"
              class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
              <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              <span wire:loading.remove wire:target="submit">Submit Report</span>
              <span wire:loading wire:target="submit">Submitting...</span>
            </button>
            <div id="submitSuccessDesktop" class="hidden mt-3 p-3 bg-green-50 border-2 border-green-500 rounded-xl">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p id="submitSuccessMessageDesktop" class="text-sm font-semibold text-green-800"></p>
              </div>
            </div>
          </div>
        </div>

        {{-- ===== MOBILE/TABLET: Step 1 ===== --}}
        @if ($step === 1)
        <div class="lg:hidden">
          <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>

            {{-- Phone --}}
            <div class="mb-4">
              <label for="phone_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Phone Number <span class="text-red-500">*</span>
              </label>
              @if(auth()->check())
              <div class="flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200">
                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded-lg whitespace-nowrap">Logged-in</span>
              </div>
              @else
              {{-- <div class="flex gap-2"> --}}
                <input id="phone_m" type="tel" inputmode="tel" autocomplete="tel" wire:model.live="phone" required
                  class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                  placeholder="Enter your phone number">
                {{-- @if($needs_otp_verification && !$otp_verified)
                <button type="button" id="btnSendOtpMobile" wire:click="sendOtpAutomatically"
                  class="px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 disabled:opacity-60 whitespace-nowrap text-sm">
                  Send
                </button>
                @endif
              </div> --}}
              @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- OTP (mobile) --}}
            {{-- @if($needs_otp_verification && !$otp_verified)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-2xl p-4 mb-4 shadow-sm">
              <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-base font-bold text-blue-900 mb-1">Phone Verification</h3>
                  <p class="text-sm text-blue-700">Enter 6-digit code from WhatsApp</p>
                </div>
              </div>

              @if(session('otp_success'))
              <div class="mb-3 p-2.5 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
                </svg>
                <span class="text-xs text-green-800 font-medium">{{ session('otp_success') }}</span>
              </div>
              @endif

              @if(session('otp_error'))
              <div class="mb-3 p-2.5 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
                </svg>
                <span class="text-xs text-red-800 font-medium">{{ session('otp_error') }}</span>
              </div>
              @endif

              <div class="mb-3">
                <label for="otp_code_m" class="block text-sm font-semibold text-gray-900 mb-2">
                  Verification Code <span class="text-red-500">*</span>
                </label>
                <input id="otp_code_m" type="text" inputmode="numeric" maxlength="6" wire:model.defer="otp_code"
                  class="w-full px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] rounded-xl border-2 border-gray-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 placeholder:text-gray-400 placeholder:tracking-normal placeholder:text-base placeholder:font-normal"
                  placeholder="000000" aria-describedby="otpHelpM">
                @error('otp_code')
                <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                      clip-rule="evenodd" />
                  </svg>
                  {{ $message }}
                </p>
                @enderror
                <p id="otpHelpM" class="sr-only">6 digit verification code</p>
              </div>

              <div class="space-y-2 mb-3">
                <button type="button" wire:click="verifyOtpAndProceed"
                  class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Verify Code
                </button>
                <button type="button" id="btnResendOtpMobile" wire:click="resendOtp"
                  class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 active:scale-[0.98] transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>Resend Code</span>
                  <span id="resendTimerMobile" class="text-gray-500"></span>
                </button>
              </div>

              <div class="p-2.5 bg-white/60 rounded-lg border border-blue-200">
                <p class="text-xs text-gray-700 flex items-start gap-2">
                  <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>
                    Check WhatsApp for your code. Valid for 5 minutes.
                  </span>
                </p>
              </div>
            </div>
            @endif --}}

            {{-- Name --}}
            <div class="mb-4">
              <label for="user_name_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Your Name <span class="text-red-500">*</span>
              </label>
              @if(auth()->check() || $is_existing_user)
              <div class="flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200">
                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1 break-words">{{ $user_name }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded-lg whitespace-nowrap">
                  {{ auth()->check() ? 'Logged-in' : 'Verified' }}
                </span>
              </div>
              @else
              <input id="user_name_m" type="text" autocomplete="name" wire:model.defer="user_name" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="Enter your full name">
              @error('user_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Location --}}
            <div class="mb-4">
              <label for="location_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Where You Found It <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="location_m" type="text" wire:model.defer="location"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="e.g. Library, Cafeteria, Hallway">
              @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Map Location Picker --}}
            <div class="mb-4">
              @livewire('location-picker', ['latitude' => $latitude, 'longitude' => $longitude])
            </div>

            {{-- Date Found --}}
            <div>
              <label for="date_found_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Date & Time Found <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="date_found_m" type="datetime-local" wire:model.defer="date_found"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
              @error('date_found') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Next (selalu tampil; disable jika OTP belum verified) --}}
            <div class="pt-4 space-y-2">
              <button type="button" wire:click="nextStep"
                {{-- @if($needs_otp_verification && !$otp_verified) disabled aria-disabled="true" @else aria-disabled="false" @endif --}}
                class="w-full flex items-center justify-center gap-2 rounded-xl px-6 py-4 text-base font-semibold transition-all shadow-lg text-white
                       {{-- {{ ($needs_otp_verification && !$otp_verified) ? 'bg-gray-400 cursor-not-allowed' : 'bg-gray-800 hover:bg-gray-900 active:scale-[0.98]' }} --}}
                       bg-gray-800 hover:bg-gray-900 active:scale-[0.98]">
                Next Step
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>

              {{-- @if($needs_otp_verification && !$otp_verified)
              <p class="text-xs text-red-600 text-center">
                Verify the OTP first before continuing.
              </p>
              @endif --}}
            </div>
          </div>
        </div>
        @endif

        {{-- ===== MOBILE/TABLET: Step 2 ===== --}}
        @if ($step === 2)
        <div class="lg:hidden">
          <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Item Details</h2>

            {{-- Item Name --}}
            <div class="mb-4">
              <label for="item_name_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Item Name <span class="text-red-500">*</span>
              </label>
              <input id="item_name_m" type="text" wire:model.defer="item_name" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                placeholder="e.g. Black Wallet, Laptop, ID Card">
              @error('item_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div class="mb-4">
              <label for="category_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select id="category_m" wire:model="category" required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 appearance-none bg-white"
                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; padding-right: 3rem;">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
              <label for="description_m" class="block text-sm font-semibold text-gray-900 mb-2">
                Description <span class="text-red-500">*</span>
              </label>
              <textarea id="description_m" rows="5" wire:model.defer="description" maxlength="200"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 resize-none placeholder:text-gray-400"
                placeholder="Describe the found item: color, brand, unique marks, contents, etc."></textarea>
              @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photos (mobile) --}}
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-900 mb-2">
                Photos <span class="text-gray-500 text-xs font-normal">(optional, max 5)</span>
              </label>

              <div wire:key="upload-{{ $uploadKey }}-m">
                <input type="file" accept="image/*" multiple class="hidden" wire:model="newPhotos" id="foundPhotos_m">
              </div>

              <div class="space-y-3">
                @if(!empty($photos))
                <div class="grid grid-cols-3 gap-3">
                  @foreach($photos as $index => $photo)
                  <div class="relative aspect-square group">
                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                      class="w-full h-full rounded-xl object-cover border-2 border-gray-200">
                    <button type="button" wire:click="removePhoto({{ $index }})"
                      class="absolute -top-2 -right-2 p-2 bg-gray-800 text-white rounded-full shadow-lg opacity-90 group-hover:opacity-100 transition hover:bg-gray-700 active:scale-90">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  @endforeach
                </div>
                @endif

                @if(count($photos) < 5)
                <button type="button" onclick="document.getElementById('foundPhotos_m').click()"
                  class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-white hover:bg-gray-50 active:bg-gray-100 p-8 transition">
                  <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-base font-semibold text-gray-700">
                      @if(empty($photos)) Tap to upload photos @else Add more photos ({{ count($photos) }}/5) @endif
                    </p>
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                  </div>
                </button>
                @else
                <div class="text-center p-4 bg-white rounded-xl border border-gray-200">
                  <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                </div>
                @endif

                <div wire:loading wire:target="newPhotos"
                  class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                  <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <p class="text-sm font-medium text-blue-700">Uploading photos...</p>
                  </div>
                </div>
              </div>

              @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('newPhotos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('newPhotos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="pt-2 space-y-3">
              <div>
                <button type="submit" wire:loading.attr="disabled" wire:target="submit" id="btnSubmitReportMobile"
                  class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                      stroke-width="4" />
                    <path class="opacity-75" fill="currentColor"
                      d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z" />
                  </svg>
                  <span wire:loading.remove wire:target="submit">Submit Report</span>
                  <span wire:loading wire:target="submit">Submitting...</span>
                </button>
                <div id="submitSuccessMobile" class="hidden mt-3 p-3 bg-green-50 border-2 border-green-500 rounded-xl">
                  <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p id="submitSuccessMessageMobile" class="text-sm font-semibold text-green-800"></p>
                  </div>
                </div>
              </div>

              <button type="button" wire:click="previousStep"
                class="w-full flex items-center justify-center gap-2 bg-white text-gray-700 rounded-xl px-6 py-3.5 text-base font-semibold border border-gray-200 hover:bg-gray-50 active:scale-[0.98] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
                </svg>
                Back to Your Info
              </button>
            </div>
          </div>
        </div>
        @endif
      </form>

      {{-- Toast sukses --}}
      @if (session('message'))
      <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
        {{ session('message') }}
      </div>
      @endif

      <p class="mt-6 text-center text-sm text-gray-500 leading-relaxed">
        Your information helps us contact the rightful owner.<br class="hidden sm:inline"> Thank you for reporting.
      </p>
    </div>
  </div>
</div>

@once
<script>
  // --- Download PDF after submit (dari Livewire) ---
  window.addEventListener('download-pdf', function (event) {
    try {
      const url = event?.detail?.url || event?.detail || null;
      if (!url) return;
      const a = document.createElement('a');
      a.href = url;
      a.setAttribute('download', '');
      a.setAttribute('rel', 'noopener');
      a.target = '_blank';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    } catch (err) {
      console.error('download-pdf handler error:', err);
      const url = event?.detail?.url || event?.detail;
      if (url) window.open(url, '_blank');
    }
  });

  // --- Cooldown helper untuk tombol OTP (Kirim/Resend) ---
  function startResendCooldown(seconds) {
    const btnSend = document.getElementById('btnSendOtp');
    const btnSendMobile = document.getElementById('btnSendOtpMobile');
    const btnResend = document.getElementById('btnResendOtp');
    const btnResendMobile = document.getElementById('btnResendOtpMobile');
    const timerSpan = document.getElementById('resendTimer');
    const timerSpanMobile = document.getElementById('resendTimerMobile');

    const buttons = [btnSend, btnSendMobile, btnResend, btnResendMobile].filter(Boolean);
    const timers = [timerSpan, timerSpanMobile].filter(Boolean);

    let remain = parseInt(seconds, 10) || 60;

    // Store original text
    buttons.forEach(btn => {
      if (!btn.dataset.originalText) {
        btn.dataset.originalText = btn.innerHTML;
      }
      btn.disabled = true;
      btn.classList.add('opacity-60', 'cursor-not-allowed');
    });

    const tick = () => {
      // Update timer displays
      timers.forEach(timer => {
        timer.textContent = `(${remain}s)`;
      });

      // Update button texts
      buttons.forEach(btn => {
        if (btn.id?.includes('SendOtp')) {
          btn.textContent = `Send (${remain}s)`;
        } else {
          const icon = btn.querySelector('svg');
          if (icon) {
            btn.innerHTML = icon.outerHTML + ` <span>Resend (${remain}s)</span>`;
            if (btn.id?.includes('Mobile')) {
              btn.innerHTML += ' <span id="resendTimerMobile" class="text-gray-500"></span>';
            }
          }
        }
      });

      remain--;

      if (remain < 0) {
        clearInterval(interval);

        // Reset timers
        timers.forEach(timer => {
          timer.textContent = '';
        });

        // Reset buttons
        buttons.forEach(btn => {
          btn.disabled = false;
          btn.classList.remove('opacity-60', 'cursor-not-allowed');
          btn.innerHTML = btn.dataset.originalText || btn.textContent;
        });
      }
    };

    tick();
    const interval = setInterval(tick, 1000);
  }

  // --- Lock tombol Submit biar tak dobel klik ---
  function lockSubmit(seconds) {
    const btns = [
      document.getElementById('btnSubmitReport'),
      document.getElementById('btnSubmitReportMobile')
    ].filter(Boolean);

    if (btns.length === 0) return;

    let remain = parseInt(seconds, 10) || 3;

    btns.forEach(btn => {
      const original = btn.innerHTML;
      btn.disabled = true;

      const tick = () => {
        btn.innerHTML = `
          <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          <span>Submitting… (${remain}s)</span>
        `;
        remain--;

        if (remain < 0) {
          clearInterval(interval);
          btn.disabled = false;
          btn.innerHTML = original;
        }
      };

      tick();
      const interval = setInterval(tick, 1000);
    });
  }

  // --- Listener event dari Livewire (dipanggil dari PHP) ---
  window.addEventListener('start-resend-cooldown', (e) => {
    const sec = e?.detail?.seconds ?? 60;
    startResendCooldown(sec);
  });

  window.addEventListener('lock-submit', (e) => {
    const sec = e?.detail?.seconds ?? 3;
    lockSubmit(sec);
  });

  // --- Show success alert ---
  window.addEventListener('show-success-alert', (e) => {
    const message = e?.detail?.message ?? 'Report submitted successfully!';

    // Desktop success
    const desktopBox = document.getElementById('submitSuccessDesktop');
    const desktopMsg = document.getElementById('submitSuccessMessageDesktop');

    // Mobile success
    const mobileBox = document.getElementById('submitSuccessMobile');
    const mobileMsg = document.getElementById('submitSuccessMessageMobile');

    if (desktopBox && desktopMsg) {
      desktopMsg.textContent = message;
      desktopBox.classList.remove('hidden');

      setTimeout(() => {
        desktopBox.classList.add('hidden');
      }, 5000);
    }

    if (mobileBox && mobileMsg) {
      mobileMsg.textContent = message;
      mobileBox.classList.remove('hidden');

      setTimeout(() => {
        mobileBox.classList.add('hidden');
      }, 5000);
    }
  });

  // --- Cegah Enter memicu submit tak sengaja di input text (kecuali textarea) ---
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target && ['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
      if (e.target.tagName !== 'TEXTAREA') {
        // Allow Enter on OTP input to trigger verification
        if (e.target.id && e.target.id.includes('otp_code')) {
          e.preventDefault();
          // Trigger verify button click
          const verifyBtn = e.target.closest('.bg-gradient-to-br')?.querySelector('button[wire\\:click*="verifyOtp"]');
          if (verifyBtn) verifyBtn.click();
        } else {
          e.preventDefault();
        }
      }
    }
  }, true);

  // --- Auto-focus OTP input saat muncul ---
  document.addEventListener('DOMContentLoaded', function () {
    const observer = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        if (mutation.addedNodes.length) {
          const otpInput = document.querySelector('input[id^="otp_code"]:not([data-focused])');
          if (otpInput) {
            setTimeout(() => {
              otpInput.focus();
              otpInput.dataset.focused = 'true';
            }, 100);
          }
        }
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });

  // --- Format OTP input (auto-spacing) ---
  document.addEventListener('input', function (e) {
    if (e.target.id && e.target.id.includes('otp_code')) {
      // Remove non-numeric characters
      e.target.value = e.target.value.replace(/\D/g, '');

      // Auto-submit when 6 digits entered
      if (e.target.value.length === 6) {
        setTimeout(() => {
          const verifyBtn = e.target.closest('.bg-gradient-to-br')?.querySelector('button[wire\\:click*="verifyOtp"]');
          if (verifyBtn) {
            // Visual feedback
            e.target.classList.add('ring-4', 'ring-green-200', 'border-green-500');
            setTimeout(() => verifyBtn.click(), 300);
          }
        }, 100);
      }
    }
  });
</script>
@endonce
