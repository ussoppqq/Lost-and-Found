<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click.self="closeModal"></div>
            
            <!-- Modal Container -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal Content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10">
                <!-- Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Category</h3>
                        <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="update">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 max-h-96 overflow-y-auto">
                        <div class="space-y-6">
                            <!-- Category Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="category_name" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g., Wallet, Bag, Phone">
                                @error('category_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Retention Days -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Retention Days <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="retention_days" required min="1" max="365"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">Number of days items will be retained</p>
                                @error('retention_days') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Icon Selector -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Icon <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($availableIcons as $availIcon)
                                        <button type="button" 
                                            wire:click.prevent="$set('category_icon', '{{ $availIcon }}')"
                                            class="p-3 text-2xl border-2 rounded-lg hover:border-indigo-500 transition-colors {{ $category_icon === $availIcon ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                            {{ $availIcon }}
                                        </button>
                                    @endforeach
                                </div>
                                <div class="mt-3 flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">Selected:</span>
                                    <span class="text-3xl">{{ $category_icon }}</span>
                                </div>
                                @error('category_icon') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Update Category
                        </button>
                        <button type="button" wire:click="closeModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>