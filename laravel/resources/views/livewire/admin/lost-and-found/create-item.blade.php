<div>
    @if($showModal)
    <!-- Backdrop dengan blur effect -->
    <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            @if($mode === 'from-report')
                                Confirm Report - {{ ucfirst(strtolower($report_type)) }} Item
                            @else
                                Register {{ ucfirst(strtolower($report_type)) }} Item
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($report_type === 'FOUND')
                                Register found item with physical storage details
                            @else
                                Record lost item report (no physical item)
                            @endif
                        </p>
                    </div>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="save">
                <div class="px-6 py-6">
                    
                    @if($mode === 'from-report')
                    <!-- Report Information (Read-only) -->
                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-5">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="text-lg font-semibold text-blue-900">Report Information</h4>
                            <span class="ml-auto px-3 py-1 text-xs font-medium rounded-full {{ $report_type === 'FOUND' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $report_type }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-2 bg-white rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3">Reporter Details</h5>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500">Name</label>
                                        <p class="text-sm font-medium">{{ $reporter_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Phone</label>
                                        <p class="text-sm">{{ $reporter_phone }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Email</label>
                                        <p class="text-sm truncate">{{ $reporter_email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500">Item Name</label>
                                <p class="text-sm font-medium">{{ $item_name }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500">Category</label>
                                <p class="text-sm font-medium">
                                    {{ $categories->firstWhere('category_id', $category_id)?->category_name ?? '-' }}
                                </p>
                            </div>

                            <div class="bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500">Location</label>
                                <p class="text-sm">{{ $report_location }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500">Date & Time</label>
                                <p class="text-sm">{{ \Carbon\Carbon::parse($report_datetime)->format('d M Y, H:i') }}</p>
                            </div>

                            <div class="col-span-2 bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500">Description</label>
                                <p class="text-sm">{{ $report_description }}</p>
                            </div>

                            @if(!empty($reportPhotos))
                            <div class="col-span-2 bg-white rounded-lg p-4">
                                <label class="text-xs text-gray-500 mb-3 block">User Uploaded Photos</label>
                                <div class="grid grid-cols-4 gap-3">
                                    @foreach($reportPhotos as $photo)
                                        <img src="{{ Storage::url($photo) }}" 
                                             class="w-full h-24 object-cover rounded-lg border-2 border-gray-200">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Type Indicator for Standalone -->
                    @if($mode === 'standalone')
                    <div class="mb-6 p-4 rounded-lg {{ $report_type === 'FOUND' ? 'bg-green-50 border border-green-200' : 'bg-orange-50 border border-orange-200' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 {{ $report_type === 'FOUND' ? 'text-green-600' : 'text-orange-600' }} mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium {{ $report_type === 'FOUND' ? 'text-green-900' : 'text-orange-900' }}">
                                {{ $report_type === 'FOUND' ? '✓ Physical item available - Complete storage details required' : '⚠ No physical item - Report only mode' }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Input Section -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-900">
                                @if($report_type === 'FOUND')
                                    Item Details & Storage
                                @else
                                    Report Details
                                @endif
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            @if($mode === 'standalone')
                            <!-- Reporter Information -->
                            <div class="sm:col-span-2 bg-gray-50 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-4">Reporter Information</h5>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name *</label>
                                        <input type="text" wire:model="reporter_name"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('reporter_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone *</label>
                                        <input type="text" wire:model="reporter_phone"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('reporter_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email *</label>
                                        <input type="email" wire:model="reporter_email"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('reporter_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Fields -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Report Type *</label>
                                <select wire:model.live="report_type"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="FOUND">Found Item (Physical item available)</option>
                                    <option value="LOST">Lost Item (Report only)</option>
                                </select>
                                @error('report_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Item Name *</label>
                                <input type="text" wire:model="item_name"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category *</label>
                                <select wire:model="category_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location *</label>
                                <input type="text" wire:model="report_location"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('report_location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date & Time *</label>
                                <input type="datetime-local" wire:model="report_datetime"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('report_datetime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description *</label>
                                <textarea wire:model="report_description" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('report_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Photo Upload for LOST Items (Optional reference photo) -->
                            @if($report_type === 'LOST')
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Reference Photo (Optional)
                                </label>
                                <input type="file" wire:model="photos" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-orange-50 file:text-orange-700
                                        hover:file:bg-orange-100 cursor-pointer">
                                @error('photos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Upload reference photos from user's archive/memory</p>

                                <!-- Photo Preview -->
                                @if($photos)
                                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        @foreach($photos as $index => $photo)
                                            <div class="relative group">
                                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-28 object-cover rounded-lg border-2 border-gray-200">
                                                <button type="button" wire:click="removePhoto({{ $index }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @endif
                            @endif

                            <!-- FOUND Item Specific Fields -->
                            @if($report_type === 'FOUND')
                            <div class="sm:col-span-2 border-t-2 border-dashed border-indigo-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <h5 class="text-base font-semibold text-indigo-900">Physical Item Details (Found Item Only)</h5>
                                </div>
                            </div>

                            <!-- Brand (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Brand (Optional)</label>
                                <input type="text" wire:model="brand" placeholder="e.g., Nike, Apple"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Leave empty if unknown</p>
                            </div>

                            <!-- Color (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color (Optional)</label>
                                <input type="text" wire:model="color" placeholder="e.g., Black, Blue"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Leave empty if unknown</p>
                            </div>

                            <!-- Storage Location (Required for FOUND) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Storage Location *</label>
                                <select wire:model="post_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Location</option>
                                    @foreach($posts as $post)
                                        <option value="{{ $post->post_id }}">{{ $post->post_name }}</option>
                                    @endforeach
                                </select>
                                @error('post_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Storage Detail (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Storage Detail (Optional)</label>
                                <input type="text" wire:model="storage" placeholder="e.g., Shelf A-3, Drawer 2"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('storage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status (Required for FOUND) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item Status *</label>
                                <select wire:model="item_status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($statusOptions as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('item_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sensitivity Level (Required for FOUND) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sensitivity Level *</label>
                                <select wire:model="sensitivity_level"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="NORMAL">Normal (Public access)</option>
                                    <option value="RESTRICTED">Restricted (Limited access)</option>
                                </select>
                                @error('sensitivity_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Use RESTRICTED for valuable/sensitive items</p>
                            </div>

                            <!-- Item Description (Optional for FOUND) -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                                <textarea wire:model="item_description" rows="2" placeholder="Additional details about the item's condition, features, etc."
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('item_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Photo Upload (FOUND items only) -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Item Photos (Physical Item)
                                </label>
                                <input type="file" wire:model="photos" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100 cursor-pointer">
                                @error('photos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Upload photos of the physical found item</p>

                                <!-- Photo Preview -->
                                @if($photos)
                                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        @foreach($photos as $index => $photo)
                                            <div class="relative group">
                                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-28 object-cover rounded-lg border-2 border-gray-200">
                                                <button type="button" wire:click="removePhoto({{ $index }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 sticky bottom-0">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Note:</span> 
                            @if($report_type === 'FOUND')
                                Physical item details and storage location are required
                            @else
                                This is a report-only entry (no physical item)
                            @endif
                        </p>
                        <div class="flex gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 disabled:bg-gray-400 transition-colors shadow-sm">
                                <span wire:loading.remove wire:target="save" class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    @if($report_type === 'FOUND')
                                        Register Found Item
                                    @else
                                        Confirm Lost Report
                                    @endif
                                </span>
                                <span wire:loading wire:target="save">Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>