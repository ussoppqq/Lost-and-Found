<div class="min-h-screen bg-gray-100 py-4">
  <!-- Mobile-optimized container -->
  <div class="mx-auto w-full px-4 max-w-lg lg:max-w-4xl">
    <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-8">

      {{-- Header --}}
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-800 shadow mb-3">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-2">
          Report Lost Item
        </h1>
        <p class="text-sm text-gray-600">
          Fill out the form below with details about your lost item.
        </p>
      </div>

      {{-- Stepper --}}
      <div class="flex justify-center mb-6">
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm
              {{ $step === 1 ? 'bg-gray-800' : 'bg-gray-400' }}">1</div>
            <span class="text-sm font-medium {{ $step === 1 ? 'text-gray-900' : 'text-gray-500' }}">
              Your Info
            </span>
          </div>

          <div class="w-8 h-0.5 bg-gray-300"></div>

          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm
              {{ $step === 2 ? 'bg-gray-800' : 'bg-gray-400' }}">2</div>
            <span class="text-sm font-medium {{ $step === 2 ? 'text-gray-900' : 'text-gray-500' }}">
              Item Details
            </span>
          </div>
        </div>
      </div>

      {{-- Form --}}
      <form wire:submit.prevent="submit" class="space-y-5">

        {{-- STEP 1: YOUR INFORMATION --}}
        @if ($step === 1)
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>

            {{-- Phone --}}
            <div>
              <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                Phone Number <span class="text-red-500">*</span>
              </label>

              @if(auth()->check())
                <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                  <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                  <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">
                    Logged-in
                  </span>
                </div>
              @else
                <input
                  id="phone"
                  type="tel"
                  inputmode="tel"
                  autocomplete="tel"
                  wire:model.live="phone"
                  required
                  class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 placeholder:text-gray-400"
                  placeholder="Enter your phone number">
                @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Name --}}
            <div>
              <label for="user_name" class="block text-sm font-semibold text-gray-900 mb-2">
                Your Name <span class="text-red-500">*</span>
              </label>

              @if(auth()->check() || $is_existing_user)
                <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                  <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-medium text-gray-900 flex-1 break-words">{{ $user_name }}</span>
                  <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">
                    {{ auth()->check() ? 'Logged-in' : 'Verified' }}
                  </span>
                </div>
              @else
                <input
                  id="user_name"
                  type="text"
                  autocomplete="name"
                  wire:model.defer="user_name"
                  required
                  class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 placeholder:text-gray-400"
                  placeholder="Enter your full name">
                @error('user_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Location --}}
            <div>
              <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">
                Where You Lost It <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input
                id="location"
                type="text"
                wire:model.defer="location"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 placeholder:text-gray-400"
                placeholder="e.g. Library, Cafeteria, Hallway">
              @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Date Lost --}}
            <div>
              <label for="date_lost" class="block text-sm font-semibold text-gray-900 mb-2">
                Date Lost <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input
                id="date_lost"
                type="date"
                wire:model.defer="date_lost"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0">
              @error('date_lost') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Next Button --}}
            <div class="pt-3">
              <button type="button" wire:click="nextStep"
                      class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg">
                Next Step
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
        @endif

        {{-- STEP 2: ITEM DETAILS --}}
        @if ($step === 2)
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Item Details</h2>

            {{-- Item Name --}}
            <div>
              <label for="item_name" class="block text-sm font-semibold text-gray-900 mb-2">
                Item Name <span class="text-red-500">*</span>
              </label>
              <input
                id="item_name"
                type="text"
                wire:model.defer="item_name"
                required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 placeholder:text-gray-400"
                placeholder="e.g. Black Wallet, Laptop, ID Card">
              @error('item_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
              <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select
                id="category"
                wire:model="category"
                required
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 appearance-none bg-white"
                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; padding-right: 3rem;">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
              <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                Description <span class="text-red-500">*</span>
              </label>
              <textarea
                id="description"
                rows="5"
                wire:model.defer="description"
                maxlength="200"
                class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 focus:ring-offset-0 resize-none placeholder:text-gray-400"
                placeholder="Describe your item: color, brand, unique marks, contents, etc."></textarea>
              @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photos --}}
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">
                Photos <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input type="file" accept="image/*" multiple class="hidden" wire:model="photos" id="lostPhotos">
              
              @if(empty($photos))
                <button type="button"
                        onclick="document.getElementById('lostPhotos').click()"
                        class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 active:bg-gray-100 p-8 transition">
                  <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                  </div>
                  <div class="text-center">
                    <p class="text-base font-semibold text-gray-700">Tap to upload photos</p>
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                  </div>
                </button>
              @else
                <div class="space-y-3">
                  <div class="grid grid-cols-3 gap-3">
                    @foreach($photos as $index => $photo)
                      <div class="relative aspect-square">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                             class="w-full h-full rounded-xl object-cover border-2 border-gray-200">
                        <button type="button" wire:click="removePhoto({{ $index }})"
                                class="absolute -top-2 -right-2 p-2 bg-gray-800 text-white rounded-full shadow-lg active:scale-90 transition">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                    @endforeach
                  </div>
                  <button type="button"
                          onclick="document.getElementById('lostPhotos').click()"
                          class="w-full py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 rounded-xl transition">
                    + Add More Photos
                  </button>
                </div>
              @endif
              
              @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="pt-3 space-y-3">
              <button type="submit" wire:loading.attr="disabled"
                      class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 
                        3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <span wire:loading.remove wire:target="submit">Submit Report</span>
                <span wire:loading wire:target="submit">Submitting...</span>
              </button>

              <button type="button" wire:click="previousStep"
                      class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-700 rounded-xl px-6 py-3.5 text-base font-semibold hover:bg-gray-200 active:scale-[0.98] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Your Info
              </button>
            </div>
          </div>
        @endif
      </form>

      <p class="mt-6 text-center text-sm text-gray-500 leading-relaxed">
        Your information will help others return your lost item.<br class="hidden sm:inline"> Thank you for reporting.
      </p>
    </div>
  </div>
</div>