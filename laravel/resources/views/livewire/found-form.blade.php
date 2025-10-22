<div class="min-h-[100svh] bg-gray-100 py-4 sm:py-6 overflow-x-hidden">
  <div class="mx-auto w-full max-w-screen-sm sm:max-w-2xl lg:max-w-4xl px-3 sm:px-6">
    <div class="w-full bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0">

      {{-- Header --}}
      <div class="text-center mb-5 sm:mb-8 px-1">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-800 shadow mb-3">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">Report Found Item</h1>
        <p class="mt-2 text-[13px] sm:text-sm text-gray-600 max-w-prose mx-auto">
          Please fill out the form below with details about the item you found.
        </p>
      </div>

      {{-- Stepper --}}
      <div class="flex justify-center mb-5 px-1">
        <div class="flex items-center justify-center gap-2 sm:gap-3 flex-wrap max-w-full">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold {{ $step === 1 ? 'bg-gray-800' : 'bg-gray-400' }}">1</div>
            <span class="hidden sm:inline {{ $step === 1 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">Your Info</span>
          </div>
          <div class="w-8 h-[2px] bg-gray-300 sm:mx-1"></div>
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold {{ $step === 2 ? 'bg-gray-800' : 'bg-gray-400' }}">2</div>
            <span class="hidden sm:inline {{ $step === 2 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">Item Details</span>
          </div>
        </div>
      </div>

      {{-- Form --}}
      <form wire:submit.prevent="submit" class="space-y-5 sm:space-y-6 min-w-0">

        <!-- Auto Submit Time (Display Only) -->
        <div class="rounded-xl border border-gray-200 p-3 sm:p-4 bg-white/60">
          <p class="text-xs sm:text-sm text-gray-500 mb-1">Waktu submit (otomatis, WIB)</p>
          <div x-data="{ now: new Date() }" x-init="setInterval(() => now = new Date(), 1000)" class="text-base sm:text-lg font-semibold text-gray-900">
            <span x-text="now.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' }) + ' WIB'"></span>
          </div>
          <p class="text-[11px] sm:text-xs text-gray-400 mt-1">
            Waktu final akan direkam dari server saat kamu menekan <strong>Submit</strong>.
          </p>
        </div>

        {{-- STEP 1: Finder Information --}}
        @if ($step === 1)
          <div class="space-y-4 sm:space-y-5 min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Finder Information</h2>

            {{-- Phone --}}
            <div class="min-w-0">
              <label for="phone" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Phone Number <span class="text-red-500">*</span>
              </label>

              @if(auth()->check())
                <div class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-200 shadow-sm min-w-0">
                  <svg class="w-5 h-5 text-gray-700 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-medium text-gray-900 truncate">{{ $phone }}</span>
                  <span class="ml-auto text-[10px] sm:text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">Logged-in User</span>
                </div>
              @else
                <input id="phone" type="tel" inputmode="tel" autocomplete="tel" wire:model.live="phone" required
                       class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800 placeholder:text-gray-400"
                       placeholder="Enter your phone number">
                @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Name --}}
            <div class="min-w-0">
              <label for="user_name" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Your Name <span class="text-red-500">*</span>
              </label>

              @if(auth()->check() || $is_existing_user)
                <div class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-200 shadow-sm min-w-0">
                  <svg class="w-5 h-5 text-gray-700 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-medium text-gray-900 truncate">{{ $user_name }}</span>
                  <span class="ml-auto text-[10px] sm:text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                    {{ auth()->check() ? 'Logged-in User' : 'Verified' }}
                  </span>
                </div>
              @else
                <input id="user_name" type="text" autocomplete="name" wire:model.defer="user_name" required
                       class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800 placeholder:text-gray-400"
                       placeholder="Enter your full name">
                @error('user_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Location Found --}}
            <div class="min-w-0">
              <label for="location" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Location Found <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="location" type="text" wire:model.defer="location"
                     class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800 placeholder:text-gray-400"
                     placeholder="e.g. Near Cafeteria, Parking Area">
              @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Date Found --}}
            <div class="min-w-0">
              <label for="date_found" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Date Found <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="date_found" type="date" wire:model.defer="date_found"
                     class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800">
              @error('date_found') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Next --}}
            <div class="pt-2 text-right">
              <button type="button" wire:click="nextStep"
                      class="inline-flex items-center justify-center gap-2 bg-gray-800 text-white rounded-lg px-6 py-2.5 hover:bg-gray-900 transition">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
        @endif

        {{-- STEP 2: Item Information --}}
        @if ($step === 2)
          <div class="space-y-4 sm:space-y-5 min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Item Information</h2>

            {{-- Item Name --}}
            <div class="min-w-0">
              <label for="item_name" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Item Name <span class="text-red-500">*</span>
              </label>
              <input id="item_name" type="text" wire:model.defer="item_name" required
                     class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800 placeholder:text-gray-400"
                     placeholder="e.g. Blue Backpack with Laptop">
              @error('item_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div class="min-w-0">
              <label for="category" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Category <span class="text-red-500">*</span>
              </label>
              <select id="category" wire:model="category" required
                      class="w-full max-w-full h-11 rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="min-w-0">
              <label for="description" class="block text-sm font-semibold text-gray-900 mb-1.5">
                Description / Characteristics <span class="text-red-500">*</span>
              </label>
              <textarea id="description" rows="4" wire:model.defer="description" maxlength="200"
                        class="w-full max-w-full min-h-[112px] rounded-lg border-gray-300 focus:border-gray-800 focus:ring-gray-800 resize-none placeholder:text-gray-400"
                        placeholder="Brand, color, condition, unique marks, etc."></textarea>
              @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photos --}}
            <div class="min-w-0">
              <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                Photos <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input type="file" multiple accept="image/*" class="hidden" wire:model="photos" id="foundPhotos">
              <div class="space-y-3">
                @if(empty($photos))
                  <button type="button" onclick="document.getElementById('foundPhotos').click()"
                          class="w-full flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 p-5 transition">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow">
                      <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                      </svg>
                    </div>
                    <div class="text-center">
                      <p class="text-sm font-semibold text-gray-700">Tap to upload photos</p>
                      <p class="text-[11px] text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                    </div>
                  </button>
                @else
                  <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 sm:gap-3">
                    @foreach($photos as $index => $photo)
                      <div class="relative group min-w-0">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                             class="w-full h-24 sm:h-32 rounded-lg object-cover border border-gray-300 shadow-sm">
                        <button type="button" wire:click="removePhoto({{ $index }})"
                                class="absolute top-1.5 right-1.5 p-1.5 bg-gray-800 text-white rounded-full opacity-90 group-hover:opacity-100 transition hover:bg-gray-700">
                          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>
              @error('photos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
              @error('photos.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Back + Submit --}}
            <div class="pt-2 flex justify-between">
              <button type="button" wire:click="previousStep"
                      class="inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-700 rounded-lg px-5 py-2.5 hover:bg-gray-300 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
              </button>

              <button type="submit" wire:loading.attr="disabled"
                      class="inline-flex items-center justify-center gap-2 bg-gray-800 text-white rounded-lg px-6 py-2.5 hover:bg-gray-900 transition">
                <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span wire:loading.remove wire:target="submit">Submit Report</span>
              </button>
            </div>
          </div>
        @endif
      </form>

      <p class="mt-4 sm:mt-6 text-center text-xs sm:text-sm text-gray-500">
        Your report helps the rightful owner reclaim their item. Thank you for your honesty.
      </p>
    </div>
  </div>
</div>
