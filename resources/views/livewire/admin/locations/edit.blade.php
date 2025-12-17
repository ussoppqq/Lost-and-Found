<x-layouts.admin>
    <x-slot name="pageTitle">Edit Location</x-slot>
    <x-slot name="pageDescription">Update location information for Kebun Raya Bogor</x-slot>

    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.locations.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Location</h1>
            </div>
            <p class="text-sm text-gray-600">Update location information for Kebun Raya Bogor</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('admin.locations.update', $location->location_id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Area Name -->
                <div class="mb-6">
                    <label for="area" class="block text-sm font-semibold text-gray-900 mb-2">
                        Location Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="area" name="area" value="{{ old('area', $location->area) }}" required
                        class="w-full px-4 py-3 text-base rounded-xl border-2 border-blue-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 placeholder:text-gray-400"
                        placeholder="e.g., Taman Anggrek, Taman Palem, etc.">
                    @error('area')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Location
                    </button>
                    <a href="{{ route('admin.locations.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Delete Section -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Danger Zone</h3>
                        <p class="text-sm text-gray-600">Permanently delete this location from the system</p>
                    </div>
                    <form action="{{ route('admin.locations.destroy', $location->location_id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                            Delete Location
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>