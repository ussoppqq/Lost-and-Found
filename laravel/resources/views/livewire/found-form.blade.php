<div class="container mx-auto py-10">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
        <h1 class="text-xl font-bold mb-6">Submit Found Item Report</h1>

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-5" enctype="multipart/form-data">
            {{-- Name --}}
            <div>
                <label class="block font-semibold mb-1">Name *</label>
                <input type="text" wire:model="name" class="w-full border rounded px-3 py-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Phone / Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Phone</label>
                    <input type="text" wire:model="phone" class="w-full border rounded px-3 py-2">
                    @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full border rounded px-3 py-2">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Location --}}
            <div>
                <label class="block font-semibold mb-1">Location</label>
                <input type="text" wire:model="location" class="w-full border rounded px-3 py-2">
                @error('location') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block font-semibold mb-1">Description *</label>
                <textarea wire:model="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Photo --}}
            <div>
    <label class="block font-semibold mb-1">Add Picture <span class="text-gray-500 text-sm">(Optional)</span></label>

    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-500 transition"
         x-data="{ isDropping: false }"
         x-bind:class="{ 'bg-green-50': isDropping }"
         @dragover.prevent="isDropping = true"
         @dragleave.prevent="isDropping = false"
         @drop.prevent="isDropping = false">

        {{-- Ikon upload --}}
        <div class="flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M4 12l1.293-1.293a1 1 0 011.414 0L12 16l5.293-5.293a1 1 0 011.414 0L20 12m-8-8v12" />
            </svg>
            <p class="text-gray-600">Click to upload or drag and drop</p>
            <p class="text-sm text-gray-400">PNG, JPG, GIF up to 10MB</p>

            {{-- Input file --}}
            <input type="file" wire:model="photo" accept="image/*" class="hidden" id="upload-photo">
            <label for="upload-photo"
                   class="mt-2 px-4 py-2 bg-blue-500 text-white rounded cursor-pointer hover:bg-blue-600 transition">
                Choose File
            </label>
        </div>
    </div>

    {{-- Preview --}}
    @if ($photo)
        <div class="mt-4">
            <img src="{{ $photo->temporaryUrl() }}" class="h-40 object-cover rounded-lg mx-auto shadow">
        </div>
    @endif

    {{-- Error --}}
    @error('photo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

    <p class="mt-2 text-sm text-gray-500">Adding a photo helps identify the item</p>
</div>


            {{-- Submit --}}
            <button type="submit" 
                class="w-full bg-black text-white py-2 rounded hover:bg-gray-900">
                Submit Found Item Report
            </button>
        </form>
    </div>
</div>
