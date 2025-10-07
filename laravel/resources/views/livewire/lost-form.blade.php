{{-- resources/views/livewire/report-lost-item.blade.php --}}

<div x-data="{ loading: false }"
     class="min-h-[100svh] bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">

    <div class="w-full max-w-5xl">
        
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-600 to-orange-600 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                Report Lost Item
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Tell us what you lost—details help our team identify items faster.
            </p>
        </div>

        <!-- Form Container -->
        <div class="relative">
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-gradient-to-br from-gray-400/20 to-gray-500/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-gradient-to-br from-gray-500/20 to-gray-600/10 rounded-full blur-2xl"></div>
            
            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-200/50 overflow-hidden">
                <form wire:submit.prevent="submit" @submit="loading = true" class="p-6 sm:p-8 lg:p-10">
                    
                    <div class="grid lg:grid-cols-2 gap-6 lg:gap-8">
                        
                        <!-- LEFT CARD: Item Details -->
                        <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl border border-red-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Item Details</h2>
                            </div>

                            <div class="space-y-5">
                                <!-- Item Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Item Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.defer="item_name" required
                                           class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring-red-600/20 placeholder:text-gray-400 transition"
                                           placeholder="e.g. Black Wallet">
                                    @error('item_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="category"
                                            class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring-red-600/20 transition">
                                        <option value="">-- Choose Category --</option>
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
                                    <textarea rows="4" wire:model.defer="description" required
                                              class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring-red-600/20 placeholder:text-gray-400 transition resize-none"
                                              placeholder="Brand, special marks, accessories, etc."></textarea>
                                    @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Date Lost -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Date Lost
                                    </label>
                                    <input type="date" wire:model.defer="date_lost"
                                           class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring-red-600/20 transition">
                                    @error('date_lost') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT CARD: Contact Info & Upload -->
                        <div class="space-y-6">
                            
                            <!-- Contact Information Card -->
                            <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl border border-orange-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900">Contact Information</h2>
                                </div>

                                <div class="space-y-5">
                                    <!-- Phone Number -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                                            Phone Number <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <input type="tel" wire:model.lazy="phone" required
                                                   class="w-full pl-12 rounded-xl border-gray-300 focus:border-orange-600 focus:ring-orange-600/20 placeholder:text-gray-400 transition"
                                                   placeholder="+62 812-xxxx-xxxx">
                                        </div>
                                        <p class="mt-1.5 text-xs text-gray-500">
                                            Your name will be auto-filled if this number is registered
                                        </p>
                                        @error('phone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Name - Auto-filled (if exists) -->
                                    @if($user_name && !$show_name_input)
                                        <div x-transition>
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                                Your Name
                                            </label>
                                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-900">{{ $user_name }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Name Input (if new user) -->
                                    @if($show_name_input)
                                        <div x-transition>
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                                Your Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model.defer="user_name" required
                                                   class="w-full rounded-xl border-gray-300 focus:border-orange-600 focus:ring-orange-600/20 placeholder:text-gray-400 transition"
                                                   placeholder="Enter your full name">
                                            @error('user_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    @endif

                                    <!-- Location -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                                            Your Location <span class="text-gray-500 font-normal">(optional)</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <input type="text" wire:model.defer="location"
                                                   class="w-full pl-12 rounded-xl border-gray-300 focus:border-orange-600 focus:ring-orange-600/20 placeholder:text-gray-400 transition"
                                                   placeholder="City / Area (for follow-up)">
                                        </div>
                                        @error('location') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Image Card -->
                            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl border border-yellow-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-300"
                                 x-data="{ isDropping:false, openFile(){ $refs.file.click() } }"
                                 @dragover.prevent="isDropping = true"
                                 @dragleave.prevent="isDropping = false"
                                 @drop.prevent="isDropping = false">
                                
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-yellow-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900">
                                        Add Picture <span class="text-sm font-normal text-gray-500">(optional)</span>
                                    </h2>
                                </div>

                                <input type="file" accept="image/*" class="hidden" x-ref="file" wire:model="photo">

                                <div :class="isDropping ? 'border-red-600 bg-red-50/50' : 'border-gray-300 bg-white'"
                                     class="rounded-xl border-2 border-dashed p-6 text-center transition-all duration-200">
                                    
                                    @unless ($photo)
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-700 mb-1">
                                                    Drag & drop an image here, or
                                                </p>
                                                <button type="button" @click="openFile()" 
                                                        class="text-sm font-semibold text-gray-900 hover:text-gray-700 underline underline-offset-2 transition">
                                                    browse files
                                                </button>
                                                <p class="text-xs text-gray-500 mt-2">JPG/PNG, max 3MB</p>
                                            </div>
                                            <div wire:loading wire:target="photo" class="text-xs text-gray-600 flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                                </svg>
                                                Uploading…
                                            </div>
                                        </div>
                                    @endunless

                                    @if ($photo)
                                        <div class="flex flex-col items-center gap-4">
                                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                                 class="max-h-48 rounded-xl shadow-lg object-contain border border-gray-200">
                                            <div class="flex gap-3">
                                                <button type="button" @click="openFile()"
                                                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition active:scale-95">
                                                    Change Image
                                                </button>
                                                <button type="button" wire:click="$set('photo', null)"
                                                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition active:scale-95">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @error('photo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit" :disabled="loading"
                                class="w-full inline-flex items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 font-bold text-white shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200">
                            <svg x-show="loading" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            <span x-text="loading ? 'Submitting Your Report...' : 'Submit Lost Item Report'" class="text-base"></span>
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Success Message -->
                    @if (session('status'))
                        <div class="mt-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-800 font-medium">{{ session('status') }}</p>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="mt-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                </form>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="mt-6 text-center text-sm text-gray-500">
            Your information is secure and will only be used to help recover your lost item.
        </p>
    </div>
</div>