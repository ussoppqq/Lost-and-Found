<div class="min-h-[100svh] bg-gray-100 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl border border-gray-200 p-8 lg:p-10">
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-800 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                Report Lost Item
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Please fill out the form below with details about your lost item.
            </p>
        </div>

        <!-- Outer Card (Wrapper) -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-inner p-8">
            <form wire:submit.prevent="submit" @submit="loading = true">
                <div class="grid lg:grid-cols-2 gap-8">

                    <!-- LEFT CARD: Contact Information -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Contact Information</h2>
                                <p class="text-sm text-gray-500">Who should we contact?</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- PHONE NUMBER -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>

                                @if(auth()->check())
                                <!-- Kalau user login -->
                                <div
                                    class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                    <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $phone }}</span>
                                    <span
                                        class="ml-auto text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                                        Logged-in User
                                    </span>
                                </div>
                                @else
                                <!-- Kalau belum login -->
                                <input type="text" wire:model.debounce.500ms="phone" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                    placeholder="Enter your phone number">
                                @error('phone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>


                            <!-- NAME -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Your Name <span class="text-red-500">*</span>
                                </label>

                                @if($is_existing_user || auth()->check())
                                <!-- Jika nama sudah diambil dari database / user login -->
                                <div
                                    class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                    <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $user_name }}</span>
                                    <span
                                        class="ml-auto text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                                        {{ auth()->check() ? 'Logged-in User' : 'Verified' }}
                                    </span>
                                </div>
                                @else
                                <!-- Jika user baru (belum ada di database) -->
                                <input type="text" wire:model.defer="user_name" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                    placeholder="Enter your full name">
                                @error('user_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>


                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Your Location <span
                                        class="text-gray-500 font-normal text-xs">(optional)</span></label>
                                <input type="text" wire:model.defer="location"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                    placeholder="City / Area">
                                @error('location') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Date Lost -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Date Lost <span
                                        class="text-gray-500 font-normal text-xs">(optional)</span></label>
                                <input type="date" wire:model.defer="date_lost"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 transition">
                                @error('date_lost') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT CARD: Item Information -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Item Details</h2>
                                <p class="text-sm text-gray-500">Tell us about the lost item</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Item Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Item Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="item_name" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                    placeholder="e.g. Black Leather Wallet">
                                @error('item_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Category <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="category" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 transition">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Description <span
                                        class="text-red-500">*</span></label>
                                <textarea rows="4" wire:model.defer="description" required maxlength="200"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition resize-none"
                                    placeholder="Describe the item: brand, color, special marks, etc."></textarea>
                                <div class="flex justify-between items-center mt-1.5">
                                    @error('description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                    <p class="text-xs text-gray-500">
                                        <span x-text="$wire.description ? $wire.description.length : 0"></span>/200
                                    </p>
                                </div>
                            </div>

                            <!-- Upload Photos -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Photos <span
                                        class="text-gray-500 font-normal text-xs">(optional)</span></label>
                                <input type="file" accept="image/*" multiple class="hidden" x-ref="fileInput"
                                    wire:model="photos">

                                <div class="space-y-3">
                                    @if(empty($photos))
                                    <button type="button" @click="$refs.fileInput.click()"
                                        class="w-full flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 p-8 transition">
                                        <div
                                            class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-semibold text-gray-700">Click to upload photos</p>
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                                        </div>
                                    </button>
                                    @else
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($photos as $index => $photo)
                                        <div class="relative group">
                                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview {{ $index + 1 }}"
                                                class="w-full h-32 rounded-lg object-cover border border-gray-300 shadow-sm">
                                            <button type="button" wire:click="removePhoto({{ $index }})"
                                                class="absolute top-2 right-2 p-1.5 bg-gray-800 text-white rounded-full opacity-0 group-hover:opacity-100 transition hover:bg-gray-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" wire:target="submit" wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center gap-3 rounded-xl bg-gray-800 hover:bg-gray-900 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200">

                        <!-- Spinner -->
                        <svg wire:loading wire:target="submit" class="h-6 w-6 animate-spin" viewBox="0 0 24 24"
                            fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>

                        <!-- Text berubah otomatis -->
                        <span wire:loading.remove wire:target="submit">Submit Report</span>
                        <span wire:loading wire:target="submit">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="mt-6 text-center text-sm text-gray-500">
            Your information will be used solely to assist in recovering your lost item.
        </p>
    </div>
</div>