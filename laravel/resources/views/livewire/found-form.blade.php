<div x-data="{ preview: null }" class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-green-700 text-center">Submit Found Item Report</h1>

    {{-- Alert sukses --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-5">
        {{-- Name --}}
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Name <span class="text-red-500">*</span></label>
            <input type="text" wire:model.defer="name"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Phone / Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1 text-gray-700">Phone</label>
                <input type="text" wire:model.defer="phone"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-700">Email</label>
                <input type="email" wire:model.defer="email"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>
        </div>

        {{-- Location --}}
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Location</label>
            <input type="text" wire:model.defer="location"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Description <span class="text-red-500">*</span></label>
            <textarea wire:model.defer="description" rows="4"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        {{-- Photo + Preview pakai Alpine --}}
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Add Picture (optional)</label>
            <input type="file" wire:model="photo" @change="preview = URL.createObjectURL($event.target.files[0])"
                class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">

            @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <template x-if="preview">
                <div class="mt-2">
                    <span class="text-sm text-gray-600">Preview:</span>
                    <img :src="preview" class="h-32 mt-1 rounded border">
                </div>
            </template>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-200">
            Submit Found Item Report
        </button>
    </form>
</div>
