<div
    x-data="{ loading: false }"
    class="relative min-h-[100svh] bg-gradient-to-b from-[#f5fff6] via-[#f7faf7] to-[#eef7ef] overflow-hidden">

    {{-- Decorative floating leaves (keep or remove) --}}
    <img src="{{ asset('images/florals/leaf-1.png') }}"
         onerror="this.replaceWith(document.getElementById('leaf-svg-1').content.cloneNode(true))"
         class="pointer-events-none select-none absolute -left-10 -top-10 w-40 opacity-40 animate-float-slow" alt="">
    <template id="leaf-svg-1">
        <svg viewBox="0 0 120 120" class="absolute -left-10 -top-10 w-40 opacity-30 animate-float-slow">
            <path d="M20,70 C20,30 70,10 110,20 C85,40 70,60 60,95 C40,92 25,85 20,70Z" fill="#a7d7a2"/>
            <path d="M24,68 C45,65 70,55 96,34" stroke="#6aa56a" stroke-width="3" fill="none" />
        </svg>
    </template>

    <img src="{{ asset('images/florals/leaf-2.png') }}"
         onerror="this.replaceWith(document.getElementById('leaf-svg-2').content.cloneNode(true))"
         class="pointer-events-none select-none absolute -right-12 -bottom-12 w-56 opacity-40 animate-float-slower" alt="">
    <template id="leaf-svg-2">
        <svg viewBox="0 0 160 160" class="absolute -right-12 -bottom-12 w-56 opacity-30 animate-float-slower">
            <path d="M30,120 C15,80 50,40 95,30 C135,22 150,55 130,90 C110,125 70,140 30,120Z" fill="#b9e0b4"/>
            <path d="M48,112 C80,98 108,73 126,46" stroke="#7dbb7a" stroke-width="3" fill="none"/>
        </svg>
    </template>

    {{-- Page container --}}
    <div class="relative z-10 max-w-3xl mx-auto px-5 md:px-8 py-10 md:py-16">
        <h1 class="text-center text-2xl md:text-3xl font-semibold tracking-wide text-emerald-900/90">
            Report Lost Item
        </h1>
        <p class="mt-2 text-center text-sm text-emerald-900/70">
            Tell us what you lost—details help our team identify items faster.
        </p>

        {{-- Glass card --}}
        <div class="mt-8 rounded-2xl bg-white/80 backdrop-blur-xl shadow-[0_10px_40px_-10px_rgba(16,185,129,.15)] border border-emerald-900/10">
            <form wire:submit.prevent="submit" @submit="loading = true" class="p-6 md:p-8 space-y-8">

                {{-- Item Name --}}
                <div>
                    <label class="block text-sm font-medium text-emerald-900/80">Item Name</label>
                    <input type="text" wire:model.defer="item_name" required
                           class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30 placeholder:text-emerald-900/40"
                           placeholder="e.g. Black Wallet">
                    @error('item_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Category + Custom --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-emerald-900/80">Category</label>
                        <select wire:model="category"
                                class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30">
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
                        <label class="block text-sm font-medium text-emerald-900/80">Custom Category</label>
                        <input type="text" wire:model.defer="category_other"
                               class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30"
                               placeholder="Type your category">
                        @error('category_other') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-emerald-900/80">Description / Characteristics</label>
                    <textarea rows="4" wire:model.defer="description" required
                              class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30 placeholder:text-emerald-900/40"
                              placeholder="Explain special marks, brand, etc."></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Date Lost ONLY (removed: Where It Was Lost) --}}
                <div>
                    <label class="block text-sm font-medium text-emerald-900/80">Date Lost</label>
                    <input type="date" wire:model.defer="date_lost"
                           class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30">
                    @error('date_lost') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Contact Details --}}
                <div class="border-t border-emerald-900/10 pt-6">
                    <h3 class="text-base font-medium text-emerald-900/90 mb-4">Your Contact Details</h3>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-emerald-900/80">Email</label>
                            <input type="email" wire:model.defer="email"
                                   class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30"
                                   placeholder="you@example.com">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-emerald-900/80">Phone</label>
                            <input type="tel" wire:model.defer="phone"
                                   class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30"
                                   placeholder="+62 812-xxxx-xxxx">
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-emerald-900/80">Your Location (optional)</label>
                        <input type="text" wire:model.defer="location"
                               class="mt-2 w-full rounded-xl border-emerald-900/15 focus:border-emerald-400 focus:ring-emerald-400/30"
                               placeholder="City / Area (for follow-up)">
                        @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Upload (NEW drag & drop with preview) --}}
                <div
                    x-data="{
                        isDropping:false,
                        openFile(){ $refs.file.click() }
                    }"
                    @dragover.prevent="isDropping = true"
                    @dragleave.prevent="isDropping = false"
                    @drop.prevent="isDropping = false"
                >
                    <label class="block text-sm font-medium text-emerald-900/80">Add Picture (optional)</label>

                    <div
                        :class="isDropping ? 'ring-2 ring-emerald-400 bg-emerald-50/60' : 'ring-1 ring-emerald-900/10'"
                        class="mt-2 rounded-2xl border border-dashed bg-white/70 backdrop-blur p-6 text-center transition">

                        {{-- Hidden file input --}}
                        <input type="file" accept="image/*" class="hidden" x-ref="file" wire:model="photo">

                        {{-- Empty state --}}
                        @unless ($photo)
                            <div class="flex flex-col items-center gap-3">
                                <svg class="h-10 w-10 text-emerald-500/70" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 5l4 4h-3v4h-2V9H8l4-4z"></path>
                                    <path d="M4 15h16v2H4z"></path>
                                </svg>
                                <p class="text-sm text-emerald-900/70">
                                    Drag & drop an image here, or
                                    <button type="button" @click="openFile()" class="underline hover:text-emerald-700">
                                        browse
                                    </button>
                                    (JPG/PNG, max 3MB)
                                </p>
                                <div wire:loading wire:target="photo" class="text-xs text-emerald-600">Uploading…</div>
                            </div>
                        @endunless

                        {{-- Preview --}}
                        @if ($photo)
                            <div class="flex flex-col items-center gap-4">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                     class="max-h-56 rounded-xl shadow-md object-contain">
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="openFile()"
                                            class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                                        Change
                                    </button>
                                    <button type="button" wire:click="$set('photo', null)"
                                            class="px-3 py-1.5 text-sm rounded-lg bg-white border hover:bg-gray-50">
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
                    <label class="block text-sm font-medium text-emerald-900/80">Sensitivity Level</label>
                    <div class="mt-2 flex gap-3 flex-wrap">
                        @foreach (['NORMAL','RESTRICTED'] as $level)
                            <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-900/15 bg-white hover:border-emerald-400 cursor-pointer">
                                <input type="radio" class="text-emerald-600" wire:model.defer="sensitivity_level" value="{{ $level }}">
                                <span class="text-sm">{{ $level }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('sensitivity_level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" :disabled="loading"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-medium text-white shadow-md hover:bg-emerald-700 active:scale-[.99] disabled:opacity-60 disabled:cursor-not-allowed transition">
                        <svg x-show="loading" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="4"/>
                        </svg>
                        <span x-text="loading ? 'Submitting…' : 'Submit Lost Item Report'"></span>
                    </button>
                </div>

                {{-- Success toast --}}
                @if (session('status'))
                    <div x-init="$nextTick(() => { setTimeout(() => $el.classList.add('opacity-0','translate-y-2'), 2500) })"
                         class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 transition">
                        {{ session('status') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <style>
        @keyframes float-slow{0%{transform:translateY(0) rotate(0)}50%{transform:translateY(-10px) rotate(2deg)}100%{transform:translateY(0) rotate(0)}}
        @keyframes float-slower{0%{transform:translateY(0) rotate(0)}50%{transform:translateY(8px) rotate(-2deg)}100%{transform:translateY(0) rotate(0)}}
        .animate-float-slow{animation:float-slow 8s ease-in-out infinite}
        .animate-float-slower{animation:float-slower 12s ease-in-out infinite}
        [x-cloak]{display:none!important}
    </style>
</div>
