<div x-data="{ loading: false }" class="min-h-[100svh] bg-gray-100 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl bg-white shadow-2xl rounded-3xl border border-gray-200 p-8 lg:p-10">
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-800 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                Report Lost Item
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Please fill out the form below with details about your lost item.
            </p>
        </div>

        <!-- Flash Messages -->
        @if (session('status'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 mb-6">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Form -->
        <form wire:submit.prevent="submit" @submit="loading = true" class="mt-8 space-y-8" enctype="multipart/form-data">

            {{-- Item Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Item Name *</label>
                <input type="text" wire:model.defer="item_name" required
                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500"
                       placeholder="e.g. Black Wallet">
                @error('item_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Category *</label>
                <select wire:model="category" required
                        class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30">
                    <option value="">-- Choose Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Description / Characteristics *</label>
                <textarea rows="4" wire:model.defer="description" required
                          class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500"
                          placeholder="At least 100 characters: brand, special marks, accessories, etc."></textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Date Lost --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Date Lost *</label>
                <input type="date" wire:model.defer="date_lost" required
                       max="{{ now()->toDateString() }}"
                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30">
                @error('date_lost')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message === 'The date lost must be a date before or equal to today.' ? 'Mohon isi tanggal yang tepat' : $message }}
                    </p>
                @enderror
            </div>

            {{-- Contact --}}
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Your Contact Details</h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900">Phone *</label>
                        <input type="tel" wire:model.debounce.600ms="phone" required
                               class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                               placeholder="+62 812-xxxx-xxxx">
                        <p class="text-xs text-gray-600 mt-1">Name will auto-fill if this number exists.</p>
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900">Email</label>
                        <input type="email" wire:model.defer="email"
                               class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                               placeholder="you@example.com">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-900">Full Name *</label>
                    <input type="text" wire:model="user_name" required
                           @disabled($is_existing_user)
                           class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30 disabled:bg-gray-100 disabled:text-gray-600"
                           placeholder="Your name">
                    @if($is_existing_user)
                        <p class="text-xs text-gray-600 mt-1">Filled automatically from registered user data.</p>
                    @endif
                    @error('user_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-900">Your Location *</label>
                    <input type="text" wire:model.defer="location" required
                           class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                           placeholder="City / Area (for follow-up)">
                    @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Upload (multiple) --}}
            <div
                x-data="{ isDropping:false, openFile(){ $refs.file.click() } }"
                @dragover.prevent="isDropping = true"
                @dragleave.prevent="isDropping = false"
                @drop.prevent="isDropping = false"
            >
                <label class="block text-sm font-semibold text-gray-900">
                    Add Pictures <span class="text-gray-500">(optional, up to 5)</span>
                </label>

                <div :class="isDropping ? 'ring-2 ring-gray-400 bg-gray-50' : 'ring-1 ring-gray-300'"
                     class="mt-2 rounded-2xl border border-dashed bg-white p-6 transition">
                    <input type="file" accept="image/*" class="hidden" x-ref="file" wire:model="photos" multiple>

                    {{-- Empty state --}}
                    @if (empty($photos))
                        <div class="text-center">
                            <svg class="h-10 w-10 text-gray-500 mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 5l4 4h-3v4h-2V9H8l4-4z"></path>
                                <path d="M4 15h16v2H4z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-700">
                                Drag & drop images here, or
                                <button type="button" @click="openFile()" class="underline hover:text-gray-900">
                                    browse
                                </button>
                                (JPG/PNG, max 3MB each)
                            </p>
                        </div>
                    @endif

                    {{-- Previews --}}
                    @if (!empty($photos))
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($photos as $idx => $tmp)
                                <div class="relative border rounded-lg p-2">
                                    <img src="{{ $tmp->temporaryUrl() }}" class="h-32 w-full object-cover rounded-md" alt="Preview {{ $idx+1 }}">
                                    <button type="button"
                                            wire:click="removePhoto({{ $idx }})"
                                            class="absolute top-2 right-2 bg-white/90 border border-gray-300 rounded-full px-2 py-0.5 text-xs hover:bg-white">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-xs text-gray-600">
                            You can select more images to add (max 5).
                        </div>
                    @endif

                    <div wire:loading wire:target="photos" class="text-xs text-gray-600 mt-3">Uploading…</div>
                </div>
                @error('photos') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('photos.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit" :disabled="loading"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-black px-6 py-3 font-semibold text-white shadow-md hover:bg-gray-900 active:scale-95 disabled:opacity-60 transition">
                    <svg x-show="loading" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4"/>
                    </svg>
                    <span x-text="loading ? 'Submitting…' : 'Submit Lost Item Report'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
