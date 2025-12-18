<!-- Admin Input Section -->
<div class="mb-6">
    <div class="flex items-center mb-4">
        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h4 class="text-lg font-semibold text-gray-900">
            @if($mode === 'from-report' && $report_type === 'LOST')
                Report Details
            @elseif($report_type === 'FOUND')
                Item Details & Storage
            @else
                Report Details
            @endif
        </h4>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

        @if($mode === 'standalone')

            <!-- Report Type -->
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Report Type *</label>
                <select wire:model.live="report_type"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="FOUND">Found Item (Physical item available)</option>
                    <option value="LOST">Lost Item (Report only)</option>
                </select>
                @error('report_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Reporter Mode (FOUND only) -->
            @if($report_type === 'FOUND')
                <div class="sm:col-span-2 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-indigo-900 mb-3">Reporter Type *</label>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="flex items-start cursor-pointer flex-1">
                            <input type="radio" wire:model.live="reporterMode" value="user"
                                class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-sm">
                                <span class="flex items-center font-medium text-gray-700">
                                    <svg class="w-5 h-5 mr-1 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    User/Walk-in Reporter
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">Person who found the item provides their
                                    details</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer flex-1">
                            <input type="radio" wire:model.live="reporterMode" value="moderator"
                                class="mt-1 w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                            <span class="ml-3 text-sm">
                                <span class="flex items-center font-medium text-gray-700">
                                    <svg class="w-5 h-5 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    As Moderator
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">Staff/Security found the item (use your
                                    account)</span>
                            </span>
                        </label>
                    </div>
                    @error('reporterMode') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Reporter Information (Show only if user mode OR LOST report) -->
            @if($reporterMode === 'user' || $report_type === 'LOST')
                <div class="sm:col-span-2 bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-sm font-semibold text-gray-700">Reporter Information</h5>
                        @if($report_type === 'FOUND')
                            <span class="text-xs text-gray-500 italic">Walk-in user details</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" wire:model="reporter_name" placeholder="Full name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('reporter_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone *</label>
                            <input type="text" wire:model="reporter_phone" placeholder="08xxxxxxxxxx"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('reporter_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Email
                                <span class="text-xs text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <input type="email" wire:model="reporter_email" placeholder="email@example.com"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('reporter_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Will update user profile if provided</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Moderator Info Display -->
            @if($reporterMode === 'moderator' && $report_type === 'FOUND')
                <div class="sm:col-span-2 bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-purple-900">Report will be registered under your account:</p>
                            <p class="text-sm text-purple-700 mt-1">
                                <strong>{{ auth()->user()->full_name }}</strong> ({{ auth()->user()->email }})
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Basic Fields -->
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Item Name *</label>
                <input type="text" wire:model="item_name" placeholder="e.g., iPhone 13, Black Wallet, Blue Backpack"
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
                <select wire:model="report_location"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Select Location --</option>
                    @php
                        $groupedLocations = $locations->groupBy('area');
                    @endphp
                    @foreach($groupedLocations as $area => $locs)
                        <optgroup label="{{ $area }}">
                            @foreach($locs as $loc)
                                <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('report_location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Select from predefined locations for your company</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date & Time *</label>
                <input type="datetime-local" id="report-datetime" wire:model="report_datetime"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('report_datetime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Timezone: GMT+7</p>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea wire:model="report_description" rows="3" placeholder="Describe the item in detail..."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('report_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Photo Upload for LOST Items (Optional reference photo) -->
            @if($report_type === 'LOST')
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Reference Photos (Optional)
                        <span class="text-xs text-gray-500 font-normal ml-2">Max 2MB each, up to 5 photos</span>
                    </label>

                    {{-- Hidden input with wire:key for reset --}}
                    <div wire:key="upload-{{ $uploadKey }}">
                        <input type="file" 
                               wire:model="newPhotos" 
                               multiple 
                               accept="image/*" 
                               class="sr-only" 
                               id="photo-upload-lost">
                    </div>

                    <div class="space-y-3">
                        {{-- Preview Grid --}}
                        @if(!empty($photos) && count($photos) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($photos as $index => $photo)
                                    <div class="relative group">
                                        <img src="{{ $photo->temporaryUrl() }}"
                                            class="w-full h-32 object-cover rounded-lg border-2 border-orange-200 group-hover:border-orange-400 transition">
                                        <button type="button" 
                                                wire:click="removePhoto({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-all transform hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div class="absolute bottom-0 left-0 right-0 bg-orange-600 bg-opacity-75 text-white text-xs py-1 px-2 rounded-b-lg">
                                            Photo {{ $index + 1 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload Button --}}
                        @if(count($photos) < 5)
                            <button type="button" 
                                    onclick="document.getElementById('photo-upload-lost').click()"
                                    class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-orange-300 bg-orange-50 hover:border-orange-400 p-8 transition">
                                <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-base font-semibold text-gray-700">
                                        @if(empty($photos))
                                            Click to upload photos
                                        @else
                                            Add more photos ({{ count($photos) }}/5)
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB each</p>
                                    <p class="text-xs text-orange-600 font-medium mt-1">Optional: Upload photos from your archive/memory</p>
                                </div>
                            </button>
                        @else
                            <div class="text-center p-4 bg-orange-50 rounded-xl border border-orange-200">
                                <p class="text-sm font-medium text-orange-600">Maximum photos reached (5/5)</p>
                            </div>
                        @endif

                        {{-- Loading Indicator --}}
                        <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-orange-50 rounded-xl border border-orange-200">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 animate-spin text-orange-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium text-orange-700">Uploading photos...</span>
                            </div>
                        </div>
                    </div>

                    @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('newPhotos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('newPhotos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif
        @endif

        <!-- FOUND Item Specific Fields -->
        @if($report_type === 'FOUND')
            <div class="sm:col-span-2 border-t-2 border-dashed border-indigo-200 pt-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h5 class="text-base font-semibold text-indigo-900">Physical Item Details (Found Item Only)</h5>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Brand (Optional)</label>
                <input type="text" wire:model="brand" placeholder="e.g., Nike, Apple, Samsung"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Color (Optional)</label>
                <input type="text" wire:model="color" placeholder="e.g., Black, Blue, Red"
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
                <input type="text" wire:model="storage" placeholder="e.g., Shelf A-3, Drawer 2"
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
                    <option value="NORMAL">Normal (Public access)</option>
                    <option value="RESTRICTED">Restricted (Limited access)</option>
                </select>
                @error('sensitivity_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                <textarea wire:model="item_description" rows="2"
                    placeholder="Additional details about condition, features, etc."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('item_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Photo Upload for FOUND Items -->
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Item Photos (Multiple)
                    <span class="text-xs text-gray-500 font-normal ml-2">Max 2MB each, up to 5 photos</span>
                </label>

                {{-- Hidden input with wire:key for reset --}}
                <div wire:key="upload-{{ $uploadKey }}">
                    <input type="file" 
                           wire:model="newPhotos" 
                           multiple 
                           accept="image/*" 
                           class="sr-only" 
                           id="photo-upload-found">
                </div>

                <div class="space-y-3">
                    {{-- Preview Grid --}}
                    @if(!empty($photos) && count($photos) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($photos as $index => $photo)
                                <div class="relative group">
                                    <img src="{{ $photo->temporaryUrl() }}"
                                        class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 group-hover:border-indigo-400 transition">
                                    <button type="button" 
                                            wire:click="removePhoto({{ $index }})"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-all transform hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs py-1 px-2 rounded-b-lg">
                                        Photo {{ $index + 1 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Upload Button (only shown if limit not reached) --}}
                    @if(count($photos) < 5)
                        <button type="button" 
                                onclick="document.getElementById('photo-upload-found').click()"
                                class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 p-8 transition">
                            <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-base font-semibold text-gray-700">
                                    @if(empty($photos))
                                        Click to upload photos
                                    @else
                                        Add more photos ({{ count($photos) }}/5)
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB each</p>
                            </div>
                        </button>
                    @else
                        <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                        </div>
                    @endif

                    {{-- Loading Indicator --}}
                    <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium text-indigo-700">Uploading photos...</span>
                        </div>
                    </div>
                </div>

                @error('photos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('photos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('newPhotos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('newPhotos.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif
    </div>
</div>