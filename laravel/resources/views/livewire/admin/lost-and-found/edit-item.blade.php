<div>
    @if($showModal)
            <!-- Backdrop dengan blur effect -->
            <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
                <div
                    class="bg-white rounded-xl shadow-2xl w-full max-w-2xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Edit Item</h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500 transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="update">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 max-h-96 overflow-y-auto">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Item Name -->
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Item Name *</label>
                                    <input type="text" wire:model="item_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Brand -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Brand</label>
                                    <input type="text" wire:model="brand"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Color -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Color</label>
                                    <input type="text" wire:model="color"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Category *</label>
                                    <select wire:model="category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Post/Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Storage Post *</label>
                                    <select wire:model="post_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Post</option>
                                        @foreach($posts as $post)
                                            <option value="{{ $post->post_id }}">{{ $post->post_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('post_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Storage -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Storage Detail</label>
                                    <input type="text" wire:model="storage" placeholder="e.g., Shelf A-3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('storage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                                    <select wire:model="item_status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('item_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Sensitivity Level -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sensitivity Level *</label>
                                    <select wire:model="sensitivity_level"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="NORMAL">Normal</option>
                                        <option value="RESTRICTED">Restricted</option>
                                    </select>
                                    @error('sensitivity_level') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea wire:model="item_description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    @error('item_description') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Existing Photos -->
                                @if(count($existingPhotos) > 0)
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Photos</label>
                                        <div class="grid grid-cols-3 gap-4">
                                            @foreach($existingPhotos as $photo)
                                                <div class="relative">
                                                    <img src="{{ Storage::url($photo['photo_url']) }}"
                                                        class="w-full h-24 object-cover rounded border-2 border-gray-200">
                                                    <button type="button"
                                                        wire:click.prevent="removeExistingPhoto('{{ $photo['photo_id'] }}')"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- New Photos -->
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Add New Photos</label>
                                    <input type="file" wire:model="photos" multiple accept="image/*" class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100 cursor-pointer">
                                    @error('photos.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                    <!-- New Photo Preview -->
                                    @if($photos)
                                        <div class="mt-4 grid grid-cols-3 gap-4">
                                            @foreach($photos as $index => $photo)
                                                <div class="relative">
                                                    <img src="{{ $photo->temporaryUrl() }}"
                                                        class="w-full h-24 object-cover rounded border-2 border-gray-200">
                                                    <button type="button" wire:click.prevent="removeNewPhoto({{ $index }})"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update Item
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>