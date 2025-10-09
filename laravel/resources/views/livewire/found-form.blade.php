<div class="min-h-[100svh] bg-gray-100 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl border border-gray-200 p-8 lg:p-10">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-800 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                Report Found Item
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Please fill out the form below with details about the item you found.
            </p>
        </div>

        <!-- Card -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-inner p-8">
            <form wire:submit.prevent="submit">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- LEFT: Contact Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Finder Information</h2>
                                <p class="text-sm text-gray-500">Who found this item?</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>

                                @if(auth()->check())
                                    <!-- Logged in -->
                                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                        <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $phone }}</span>
                                        <span class="ml-auto text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">Logged-in User</span>
                                    </div>
                                @else
                                    <!-- Manual input -->
                                    <input type="text"
                                           wire:model.live="phone"
                                           required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                           placeholder="Enter your phone number">
                                    @error('phone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Your Name <span class="text-red-500">*</span>
                                </label>

                                @if(auth()->check())
                                    <!-- Logged in user -->
                                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                        <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $user_name }}</span>
                                        <span class="ml-auto text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">Logged-in User</span>
                                    </div>

                                @elseif($is_existing_user)
                                    <!-- Existing user (by phone) -->
                                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                        <svg class="w-5 h-5 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $user_name }}</span>
                                        <span class="ml-auto text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">Verified</span>
                                    </div>
                                @else
                                    <!-- New user -->
                                    <input type="text" wire:model.defer="user_name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                           placeholder="Enter your full name">
                                    @error('user_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Location Found <span class="text-gray-500 font-normal text-xs">(optional)</span>
                                </label>
                                <input type="text" wire:model.defer="location"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                       placeholder="e.g. Near Cafeteria, Parking Area">
                                @error('location') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Date Found <span class="text-gray-500 font-normal text-xs">(optional)</span>
                                </label>
                                <input type="date" wire:model.defer="date_found"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 transition">
                                @error('date_found') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Item Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 7l-8-4-8 4v10l8 4 8-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Item Information</h2>
                                <p class="text-sm text-gray-500">Tell us about the found item</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- Item Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Item Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model.defer="item_name" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition"
                                       placeholder="e.g. Blue Backpack with Laptop">
                                @error('item_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
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
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Description / Characteristics <span class="text-red-500">*</span>
                                </label>
                                <textarea rows="4" wire:model.defer="description" required maxlength="200"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-700 focus:ring-gray-700 placeholder:text-gray-400 transition resize-none"
                                          placeholder="Brand, color, condition, unique marks, etc."></textarea>
                                @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Photos -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Photos <span class="text-gray-500 font-normal text-xs">(optional)</span>
                                </label>

                                <input type="file" multiple accept="image/*" class="hidden" wire:model="photos" id="photoUpload">
                                <div class="space-y-3">
                                    @if(empty($photos))
                                        <button type="button" onclick="document.getElementById('photoUpload').click()"
                                                class="w-full flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 p-8 transition">
                                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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
                                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                                         class="w-full h-32 rounded-lg object-cover border border-gray-300 shadow-sm">
                                                    <button type="button" wire:click="removePhoto({{ $index }})"
                                                            class="absolute top-2 right-2 p-1.5 bg-gray-800 text-white rounded-full opacity-0 group-hover:opacity-100 transition hover:bg-gray-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M6 18L18 6M6 6l12 12"/>
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
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full inline-flex items-center justify-center gap-3 rounded-xl bg-gray-800 hover:bg-gray-900 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200">
                        <svg wire:loading wire:target="submit" class="h-6 w-6 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 
                                  3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span wire:loading.remove wire:target="submit">Submit Report</span>
                        <span wire:loading wire:target="submit">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            Your report helps the rightful owner reclaim their item. Thank you for your honesty.
        </p>
    </div>
</div>
