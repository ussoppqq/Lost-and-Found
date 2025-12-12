<div>
    {{-- Create & Match Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-opacity-50 backdrop-blur-sm" wire:click="closeModal"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl my-8 max-h-[90vh] overflow-hidden" @click.stop>
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b bg-gradient-to-r from-purple-50 to-indigo-50 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Create New {{ ucfirst(strtolower($oppositeType)) }} Report</h3>
                            <p class="mt-1 text-sm text-gray-600">Fill in the details to create and match with the current report</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-white rounded-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</h4>
                                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Content --}}
                <div class="overflow-y-auto p-6" style="max-height: calc(90vh - 200px);">
                    
                    {{-- Reporter Mode (FOUND only) --}}
                    @if($oppositeType === 'FOUND')
                        <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-indigo-900 mb-3">Reporter Type *</label>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <label class="flex items-start cursor-pointer flex-1">
                                    <input type="radio" wire:model.live="reporterMode" value="user"
                                        class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm">
                                        <span class="flex items-center font-medium text-gray-700">
                                            <svg class="w-5 h-5 mr-1 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            User/Walk-in Reporter
                                        </span>
                                        <span class="block text-xs text-gray-500 mt-1">Person who found the item</span>
                                    </span>
                                </label>
                                <label class="flex items-start cursor-pointer flex-1">
                                    <input type="radio" wire:model.live="reporterMode" value="moderator"
                                        class="mt-1 w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                    <span class="ml-3 text-sm">
                                        <span class="flex items-center font-medium text-gray-700">
                                            <svg class="w-5 h-5 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            As Moderator
                                        </span>
                                        <span class="block text-xs text-gray-500 mt-1">Staff/Security found the item</span>
                                    </span>
                                </label>
                            </div>
                            @error('reporterMode') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- Reporter Information --}}
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3">Reporter Information</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name *</label>
                                <input type="text" wire:model="reporter_name" placeholder="Full name"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @if($reporterMode === 'moderator') readonly @endif>
                                @error('reporter_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone *</label>
                                <input type="text" wire:model="reporter_phone" placeholder="08xxxxxxxxxx"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @if($reporterMode === 'moderator') readonly @endif>
                                @error('reporter_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email (Optional)</label>
                                <input type="email" wire:model="reporter_email" placeholder="email@example.com"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @if($reporterMode === 'moderator') readonly @endif>
                                @error('reporter_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Basic Report Fields --}}
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3">Report Details</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Item Name *</label>
                                <input type="text" wire:model="item_name" placeholder="e.g., iPhone 13, Black Wallet"
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
                                <input type="text" wire:model="report_location" placeholder="e.g., Lobby, Cafeteria"
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
                                <textarea wire:model="report_description" rows="3" placeholder="Describe the item..."
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('report_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- FOUND Item Specific Fields --}}
                    @if($oppositeType === 'FOUND')
                        <div class="mb-6 border-t pt-6">
                            <h5 class="text-sm font-semibold text-gray-700 mb-3">Physical Item Details</h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Brand (Optional)</label>
                                    <input type="text" wire:model="brand" placeholder="e.g., Nike, Apple"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Color (Optional)</label>
                                    <input type="text" wire:model="color" placeholder="e.g., Black, Blue"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Storage Post *</label>
                                    <select wire:model="post_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Location</option>
                                        @foreach($posts as $post)
                                            <option value="{{ $post->post_id }}">{{ $post->post_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('post_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Storage Detail (Optional)</label>
                                    <input type="text" wire:model="storage" placeholder="e.g., Shelf A-3"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('storage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sensitivity Level *</label>
                                    <select wire:model="sensitivity_level"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="NORMAL">Normal (Public)</option>
                                        <option value="RESTRICTED">Restricted (Limited)</option>
                                    </select>
                                    @error('sensitivity_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                                    <textarea wire:model="item_description" rows="2" placeholder="Condition, features, etc."
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('item_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Photo Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Photos (Optional)
                            <span class="text-xs text-gray-500 font-normal ml-2">Max 2MB each, up to 5 photos</span>
                        </label>

                        <div wire:key="upload-{{ $uploadKey }}">
                            <input type="file" wire:model="newPhotos" multiple accept="image/*" class="sr-only" id="photo-upload">
                        </div>

                        <div class="space-y-3">
                            @if(!empty($photos) && count($photos) > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($photos as $index => $photo)
                                        <div class="relative group">
                                            <img src="{{ $photo->temporaryUrl() }}"
                                                class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 group-hover:border-indigo-400 transition">
                                            <button type="button" wire:click="removePhoto({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs py-1 px-2 rounded-b-lg">
                                                Photo {{ $index + 1 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($photos) < 5)
                                <button type="button" onclick="document.getElementById('photo-upload').click()"
                                    class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 p-8 transition">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">
                                        @if(empty($photos))
                                            Click to upload photos
                                        @else
                                            Add more photos ({{ count($photos) }}/5)
                                        @endif
                                    </p>
                                </button>
                            @endif

                            <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-indigo-700">Uploading photos...</span>
                                </div>
                            </div>
                        </div>

                        @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center sticky bottom-0">
                    <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    
                    <button wire:click="save" wire:loading.attr="disabled"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 flex items-center shadow-md hover:shadow-lg transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">
                            Create & Proceed to Match
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Claim Modal --}}
    @if($showClaimModal && $createdReport)
        @livewire('admin.lost-and-found.quick-claim', [
            'sourceReport' => $sourceReport,
            'targetReport' => $createdReport,
        ], key('quick-claim-created-' . $sourceReportId . '-' . $createdReport->report_id))
    @endif
</div>