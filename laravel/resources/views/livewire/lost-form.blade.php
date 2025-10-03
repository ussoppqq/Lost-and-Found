<div x-data="{ loading: false }"
     class="min-h-[100svh] bg-white flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-300 p-8 md:p-12">

        {{-- Heading --}}
        <h1 class="text-center text-3xl md:text-4xl font-bold text-gray-900">
            Report Lost Item
        </h1>
        <p class="mt-2 text-center text-gray-600">
            Tell us what you lost—details help our team identify items faster.
        </p>

        {{-- Form card --}}
        <form wire:submit.prevent="submit" @submit="loading = true" class="mt-8 space-y-8">

            {{-- Item Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Item Name *</label>
                <input type="text" wire:model.defer="item_name" required
                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500"
                       placeholder="e.g. Black Wallet">
                @error('item_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Custom --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900">Category *</label>
                    <select wire:model="category"
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30">
                        <option value="">-- Choose Category --</option>
                        <option>Phone</option>
                        <option>Wallet</option>
                        <option>Bag</option>
                        <option>Clothing</option>
                        <option>Jewelry</option>
                        <option>Document</option>
                        <option>Other</option>
                    </select>
                    @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div x-show="$wire.category === 'Other'" x-cloak>
                    <label class="block text-sm font-semibold text-gray-900">Custom Category</label>
                    <input type="text" wire:model.defer="category_other"
                           class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                           placeholder="Type your category">
                    @error('category_other') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Description / Characteristics *</label>
                <textarea rows="4" wire:model.defer="description" required
                          class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500"
                          placeholder="Brand, special marks, accessories, etc."></textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Date Lost --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Date Lost</label>
                <input type="date" wire:model.defer="date_lost"
                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30">
                @error('date_lost') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Contact Details --}}
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Your Contact Details</h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900">Email</label>
                        <input type="email" wire:model.defer="email"
                               class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                               placeholder="you@example.com">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900">Phone</label>
                        <input type="tel" wire:model.defer="phone"
                               class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                               placeholder="+62 812-xxxx-xxxx">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-900">Your Location (optional)</label>
                    <input type="text" wire:model.defer="location"
                           class="mt-2 w-full rounded-xl border-gray-300 focus:border-gray-500 focus:ring-gray-400/30"
                           placeholder="City / Area (for follow-up)">
                    @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Upload --}}
            <div x-data="{ isDropping:false, openFile(){ $refs.file.click() } }"
                 @dragover.prevent="isDropping = true"
                 @dragleave.prevent="isDropping = false"
                 @drop.prevent="isDropping = false">
                <label class="block text-sm font-semibold text-gray-900">
                    Add Picture <span class="text-gray-500">(optional)</span>
                </label>

                <div :class="isDropping ? 'ring-2 ring-gray-400 bg-gray-50' : 'ring-1 ring-gray-300'"
                     class="mt-2 rounded-2xl border border-dashed bg-white p-6 text-center transition">
                    {{-- hidden input --}}
                    <input type="file" accept="image/*" class="hidden" x-ref="file" wire:model="photo">

                    {{-- Empty state --}}
                    @unless ($photo)
                        <div class="flex flex-col items-center gap-3">
                            <svg class="h-10 w-10 text-gray-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 5l4 4h-3v4h-2V9H8l4-4z"></path>
                                <path d="M4 15h16v2H4z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">
                                Drag & drop an image here, or
                                <button type="button" @click="openFile()" class="underline hover:text-gray-900">
                                    browse
                                </button>
                                (JPG/PNG, max 3MB)
                            </p>
                            <div wire:loading wire:target="photo" class="text-xs text-gray-600">Uploading…</div>
                        </div>
                    @endunless

                    {{-- Preview --}}
                    @if ($photo)
                        <div class="flex flex-col items-center gap-4">
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                 class="max-h-56 rounded-xl shadow-md object-contain">
                            <div class="flex gap-3">
                                <button type="button" @click="openFile()"
                                        class="px-3 py-1.5 text-sm rounded-lg bg-black text-white hover:bg-gray-900">
                                    Change
                                </button>
                                <button type="button" wire:click="$set('photo', null)"
                                        class="px-3 py-1.5 text-sm rounded-lg bg-white border border-gray-300 hover:bg-gray-50">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                @error('photo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Sensitivity --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900">Sensitivity Level</label>
                <div class="mt-2 flex gap-3 flex-wrap">
                    @foreach (['NORMAL','RESTRICTED'] as $level)
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 bg-white hover:bg-gray-50 cursor-pointer">
                            <input type="radio" class="text-black" wire:model.defer="sensitivity_level" value="{{ $level }}">
                            <span class="text-sm text-gray-800">{{ $level }}</span>
                        </label>
                    @endforeach
                </div>
                @error('sensitivity_level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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

            {{-- Success toast --}}
            @if (session('status'))
                <div class="rounded-lg bg-gray-100 border border-gray-300 text-gray-900 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </div>
</div>
