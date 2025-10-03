<div>
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.categories') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Categories
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" wire:model="name" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g., Wallet, Bag, Phone">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" wire:model="description" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Optional description..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Icon Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Icon <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($availableIcons as $availIcon)
                                <button type="button" 
                                        wire:click="$set('icon', '{{ $availIcon }}')"
                                        class="p-3 text-2xl border-2 rounded-lg hover:border-indigo-500 transition-colors {{ $icon === $availIcon ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                    {{ $availIcon }}
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-3 flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Selected:</span>
                            <span class="text-3xl">{{ $icon }}</span>
                        </div>
                        @error('icon') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.categories') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>