<div class="min-h-screen bg-gray-100 py-4">
  <div class="mx-auto w-full px-4 max-w-lg lg:max-w-5xl">
    {{-- WRAPPER CARD BESAR --}}
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
        <p class="text-sm text-gray-600">Fill out the form below with details about your lost item.</p>
      </div>

      {{-- Stepper (hanya mobile/tablet) --}}
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

      {{-- FORM --}}
      <form wire:submit.prevent="submit" class="space-y-6">

        {{-- ===== DESKTOP (lg+): dua CARD di dalam WRAPPER ===== --}}
        <div class="hidden lg:grid lg:grid-cols-2 lg:gap-6">
          {{-- CARD: YOUR INFORMATION (DESKTOP) --}}
          <div class="rounded-2xl border border-gray-200 bg-white/70 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>

            {{-- Phone --}}
            <div class="mb-4">
              <label for="phone_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Phone Number <span class="text-red-500">*</span>
              </label>
              @if(auth()->check())
                <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                  <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                  <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">Logged-in</span>
                </div>
              @else
                <input id="phone_d" type="tel" inputmode="tel" autocomplete="tel"
                       wire:model.live="phone" required
                       class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                       placeholder="Enter your phone number">
                @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Name --}}
            <div class="mb-4">
              <label for="user_name_d" class="block text-sm font-semibold text-gray-900 mb-2">
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
                <input id="user_name_d" type="text" autocomplete="name"
                       wire:model.defer="user_name" required
                       class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                       placeholder="Enter your full name">
                @error('user_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              @endif
            </div>

            {{-- Location --}}
            <div class="mb-4">
              <label for="location_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Where You Lost It <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="location_d" type="text" wire:model.defer="location"
                     class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                     placeholder="e.g. Library, Cafeteria, Hallway">
              @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Date Lost --}}
            <div>
              <label for="date_lost_d" class="block text-sm font-semibold text-gray-900 mb-2">
                Date Lost <span class="text-gray-500 text-xs font-normal">(optional)</span>
              </label>
              <input id="date_lost_d" type="date" wire:model.defer="date_lost"
                     class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
              @error('date_lost') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- CARD: ITEM DETAILS (DESKTOP) --}}
          <div class="rounded-2xl border border-gray-200 bg-white/70 p-5">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Item Details</h2>

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
                        placeholder="Describe your item: color, brand, unique marks, contents, etc."></textarea>
              @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photos --}}
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">
                Photos <span class="text-gray-500 text-xs font-normal">(optional, max 5)</span>
              </label>

              {{-- wire:key untuk reset benar-benar clear --}}
              <div wire:key="upload-{{ $uploadKey }}-d">
                <input type="file" accept="image/*" multiple class="hidden" wire:model="newPhotos" id="lostPhotos_d">
              </div>

              <div class="space-y-3">
                @if(!empty($photos))
                  <div class="grid grid-cols-3 gap-3">
                    @foreach($photos as $index => $photo)
                      <div class="relative aspect-square group">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full rounded-xl object-cover border-2 border-gray-200">
                        <button type="button" wire:click="removePhoto({{ $index }})"
                                class="absolute -top-2 -right-2 p-2 bg-gray-800 text-white rounded-full shadow-lg opacity-90 group-hover:opacity-100 transition hover:bg-gray-700 active:scale-90">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                    @endforeach
                  </div>
                @endif

                @if(count($photos) < 5)
                  <button type="button"
                          onclick="document.getElementById('lostPhotos_d').click()"
                          class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 p-8 transition">
                    <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                      <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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
                  <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                  </div>
                @endif

                <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                  <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"/>
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

        {{-- Tombol Submit (hanya desktop) --}}
        <div class="hidden lg:block pt-2">
          <button type="submit"
                  wire:loading.attr="disabled"
                  wire:target="submit"
                  class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span wire:loading.remove wire:target="submit">Submit Report</span>
            <span wire:loading wire:target="submit">Submitting...</span>
          </button>
        </div>

        {{-- ===== MOBILE/TABLET (<lg): Satu CARD per step di dalam WRAPPER ===== --}}
        @if ($step === 1)
          <div class="lg:hidden">
            <div class="rounded-2xl border border-gray-200 bg-white/70 p-5">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Information</h2>

              {{-- Phone --}}
              <div class="mb-4">
                <label for="phone_m" class="block text-sm font-semibold text-gray-900 mb-2">
                  Phone Number <span class="text-red-500">*</span>
                </label>
                @if(auth()->check())
                  <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                    <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 flex-1">{{ $phone }}</span>
                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg whitespace-nowrap">Logged-in</span>
                  </div>
                @else
                  <input id="phone_m" type="tel" inputmode="tel" autocomplete="tel"
                         wire:model.live="phone" required
                         class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                         placeholder="Enter your phone number">
                  @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @endif
              </div>

              {{-- Name --}}
              <div class="mb-4">
                <label for="user_name_m" class="block text-sm font-semibold text-gray-900 mb-2">
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
                  <input id="user_name_m" type="text" autocomplete="name"
                         wire:model.defer="user_name" required
                         class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                         placeholder="Enter your full name">
                  @error('user_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @endif
              </div>

              {{-- Location --}}
              <div class="mb-4">
                <label for="location_m" class="block text-sm font-semibold text-gray-900 mb-2">
                  Where You Lost It <span class="text-gray-500 text-xs font-normal">(optional)</span>
                </label>
                <input id="location_m" type="text" wire:model.defer="location"
                       class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                       placeholder="e.g. Library, Cafeteria, Hallway">
                @error('location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>

              {{-- Date Lost --}}
              <div>
                <label for="date_lost_m" class="block text-sm font-semibold text-gray-900 mb-2">
                  Date Lost <span class="text-gray-500 text-xs font-normal">(optional)</span>
                </label>
                <input id="date_lost_m" type="date" wire:model.defer="date_lost"
                       class="w-full px-4 py-3 text-base rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
                @error('date_lost') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>

              {{-- Next (hanya mobile/tablet) --}}
              <div class="pt-4">
                <button type="button" wire:click="nextStep"
                        class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg">
                  Next Step
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        @endif

        @if ($step === 2)
          <div class="lg:hidden">
            <div class="rounded-2xl border border-gray-200 bg-white/70 p-5">
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
                          placeholder="Describe your item: color, brand, unique marks, contents, etc."></textarea>
                @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
              </div>

              {{-- Photos --}}
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Photos <span class="text-gray-500 text-xs font-normal">(optional, max 5)</span>
                </label>

                <div wire:key="upload-{{ $uploadKey }}-m">
                  <input type="file" accept="image/*" multiple class="hidden" wire:model="newPhotos" id="lostPhotos_m">
                </div>

                <div class="space-y-3">
                  @if(!empty($photos))
                    <div class="grid grid-cols-3 gap-3">
                      @foreach($photos as $index => $photo)
                        <div class="relative aspect-square group">
                          <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full rounded-xl object-cover border-2 border-gray-200">
                          <button type="button" wire:click="removePhoto({{ $index }})"
                                  class="absolute -top-2 -right-2 p-2 bg-gray-800 text-white rounded-full shadow-lg opacity-90 group-hover:opacity-100 transition hover:bg-gray-700 active:scale-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                          </button>
                        </div>
                      @endforeach
                    </div>
                  @endif

                  @if(count($photos) < 5)
                    <button type="button"
                            onclick="document.getElementById('lostPhotos_m').click()"
                            class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 p-8 transition">
                      <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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
                    <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                      <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                    </div>
                  @endif

                  <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-center gap-2">
                      <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"/>
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

              {{-- Actions (mobile/tablet) --}}
              <div class="pt-2 space-y-3">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="w-full flex items-center justify-center gap-2 bg-gray-800 text-white rounded-xl px-6 py-4 text-base font-semibold hover:bg-gray-900 active:scale-[0.98] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg wire:loading wire:target="submit" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"/>
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
          </div>
        @endif
      </form>

      {{-- Toast sukses (opsional) --}}
      @if (session('message'))
        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
          {{ session('message') }}
        </div>
      @endif

      <p class="mt-6 text-center text-sm text-gray-500 leading-relaxed">
        Your information will help others return your lost item.<br class="hidden sm:inline"> Thank you for reporting.
      </p>
    </div>
  </div>
</div>
