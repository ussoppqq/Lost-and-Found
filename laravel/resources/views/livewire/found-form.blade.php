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
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm {{ $step === 1 ? 'bg-gray-800' : 'bg-gray-400' }}">1</div>
            <span class="text-sm font-medium {{ $step === 1 ? 'text-gray-900' : 'text-gray-500' }}">Your Info</span>
          </div>
          <div class="w-8 h-0.5 bg-gray-300"></div>
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm {{ $step === 2 ? 'bg-gray-800' : 'bg-gray-400' }}">2</div>
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

        {{-- DESKTOP: 2 kolom --}}
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">Logged-in</span>
              </div>
              @else
              <div class="flex gap-2">
                <input id="phone_d" type="tel" inputmode="tel" autocomplete="tel" wire:model.live="phone" required
                  class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                  placeholder="Enter your phone number">
                @if($needs_otp_verification && !$otp_verified)
                  <button
                    type="button"
                    id="btnSendOtp"
                    wire:click="sendOtpAutomatically"
                    class="px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 disabled:opacity-60 whitespace-nowrap"
                  >
                    Send OTP
                  </button>
                @endif
              </div>
              @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- OTP Section --}}
            @if($needs_otp_verification && !$otp_verified)
            <div class="bg-white border-2 border-blue-300 rounded-2xl p-5 mb-4 shadow-sm">
              <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-base font-bold text-blue-900 mb-1">Phone Verification Required</h3>
                  <p class="text-sm text-blue-700">Enter the 6-digit code sent to your WhatsApp</p>
                </div>
              </div>

              {{-- OTP Input --}}
              <div class="mb-4">
                <label for="otp_code_d" class="block text-sm font-semibold text-gray-900 mb-2">
                  Verification Code <span class="text-red-500">*</span>
                </label>
                <input 
                  id="otp_code_d" 
                  type="text" 
                  inputmode="numeric" 
                  maxlength="6" 
                  wire:model.defer="otp_code"
                  class="w-full px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] rounded-xl border-2 border-gray-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 placeholder:text-gray-400 placeholder:tracking-normal placeholder:text-base placeholder:font-normal"
                  placeholder="Enter 6-digit code"
                >
                @error('otp_code') 
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                  {{ $message }}
                </p>
                @enderror
              </div>
            </div>
            @endif

            {{-- Name --}}
            <div class="mb-4">
              <label for="user_name_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Your Name <span class="text-red-500">*</span>
              </label>
              @if(auth()->check() || $is_existing_user)
              <div class="flex items-center gap-2 px-4 py-3 bg-white rounded-xl border border-gray-200">
                <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 flex-1 break-words">{{ $user_name }}</span>
                <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">
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
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 bg-white appearance-none">
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
                placeholder="Describe the item: color, brand, unique marks, contents, etc."></textarea>
              @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        {{-- Submit --}}
        <div class="pt-4">
          <button type="submit"
            class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 transition-all shadow-lg">
            Submit Report
          </button>
        </div>
      </form>

      <p class="mt-6 text-center text-sm text-gray-500 leading-relaxed">
        Your information helps us contact the rightful owner.<br class="hidden sm:inline"> Thank you for reporting.
      </p>
    </div>
  </div>
</div>
