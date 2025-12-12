<div>
    @if($showModal)
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-opacity-75 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Confirm Found Item</h3>
                                <p class="text-sm text-gray-600 mt-1">Register physical item into inventory</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 max-h-[calc(100vh-12rem)] overflow-y-auto">
                    
                    <!-- Error Alert -->
                    @if($errors->has('general'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-700">{{ $errors->first('general') }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Report Information Section -->
                    @if($report)
                    <div class="mb-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-900">Report Information</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Item Name -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Item Name</label>
                                <p class="text-sm font-semibold text-gray-900">{{ $report->item_name }}</p>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Category</label>
                                <p class="text-sm font-semibold text-gray-900">{{ $report->category->category_name ?? 'N/A' }}</p>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Found Location</label>
                                <p class="text-sm text-gray-700">{{ $report->report_location }}</p>
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Found Date</label>
                                <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }} WIB</p>
                            </div>

                            <!-- Reporter -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Reporter</label>
                                <div class="flex items-center space-x-2">
                                    @if($report->user)
                                        <img class="w-8 h-8 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($report->user->full_name) }}&background=8b5cf6&color=fff" 
                                             alt="">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $report->user->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $report->user->phone_number ?? 'No phone' }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-700">{{ $report->reporter_name ?? 'Anonymous' }} - {{ $report->reporter_phone ?? 'No phone' }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Description</label>
                                <p class="text-sm text-gray-700">{{ $report->report_description }}</p>
                            </div>
                        </div>

                        <!-- Report Photos -->
                        @if(!empty($reportPhotos))
                        <div class="mt-4 pt-4 border-t border-purple-200">
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                Report Photos ({{ count($reportPhotos) }})
                            </label>
                            
                            @if(count($reportPhotos) === 1)
                                <!-- Single photo - large display with click -->
                                @if(Storage::disk('public')->exists($reportPhotos[0]))
                                <img src="{{ Storage::url($reportPhotos[0]) }}" 
                                    wire:click="openLightbox('{{ $reportPhotos[0] }}', 0)"
                                    class="w-full max-h-64 object-contain rounded-lg border-2 border-purple-200 cursor-pointer hover:border-purple-400 transition"
                                    alt="Report photo">
                                @endif
                            @else
                                <!-- Multiple photos - grid display with click -->
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                    @foreach($reportPhotos as $index => $photoUrl)
                                        @if(Storage::disk('public')->exists($photoUrl))
                                        <div class="relative group">
                                            <img src="{{ Storage::url($photoUrl) }}" 
                                                wire:click="openLightbox('{{ $photoUrl }}', {{ $index }})"
                                                class="w-full h-24 object-cover rounded-lg border-2 border-purple-200 group-hover:border-purple-400 transition cursor-pointer"
                                                alt="Report photo {{ $index + 1 }}">
                                            
                                            <!-- Hover overlay -->
                                            <div class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-20 transition rounded-lg flex items-center justify-center pointer-events-none">
                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                            </div>
                                            
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs py-1 px-2 rounded-b-lg">
                                                Photo {{ $index + 1 }}
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <p class="text-xs text-gray-500 mt-2">
                                <svg class="w-4 h-4 inline mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Click on any photo to view full size • These photos will be copied to the item record
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Item Confirmation Form -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-900">Physical Item Details</h4>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            <!-- Brand -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Brand (Optional)</label>
                                <input type="text" 
                                       wire:model="brand" 
                                       placeholder="e.g., Nike, Apple, Samsung"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color (Optional)</label>
                                <input type="text" 
                                       wire:model="color" 
                                       placeholder="e.g., Black, Blue, Red"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Storage Post -->
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

                            <!-- Storage Detail -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Storage Detail (Optional)</label>
                                <input type="text" 
                                       wire:model="storage" 
                                       placeholder="e.g., Shelf A-3, Drawer 2"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('storage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Item Status -->
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

                            <!-- Sensitivity Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sensitivity Level *</label>
                                <select wire:model="sensitivity_level"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="NORMAL">Normal (Public access)</option>
                                    <option value="RESTRICTED">Restricted (Limited access)</option>
                                </select>
                                @error('sensitivity_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Additional Notes -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                                <textarea wire:model="item_description" 
                                          rows="3"
                                          placeholder="Additional details about condition, features, etc."
                                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('item_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Photo Upload -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Additional Photos (Optional)
                                    <span class="text-xs text-gray-500 font-normal ml-2">Max 5 photos, 25MB each</span>
                                </label>
                                
                                <!-- Wire:key untuk reset input -->
                                <div wire:key="upload-{{ $uploadKey }}">
                                    <input type="file" wire:model="newPhotos" multiple accept="image/*" class="hidden" id="confirmItemPhotos">
                                </div>
                                
                                <div class="space-y-3">
                                    @if(count($photos) < 5)
                                    <button type="button" onclick="document.getElementById('confirmItemPhotos').click()"
                                            class="w-full flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 p-8 transition">
                                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md">
                                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-base font-semibold text-gray-700">
                                                @if(empty($photos)) Click to upload photos @else Add more photos ({{ count($photos) }}/5) @endif
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">JPG, PNG up to 25MB each</p>
                                        </div>
                                    </button>
                                    @else
                                    <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                                        <p class="text-sm font-medium text-gray-600">Maximum photos reached (5/5)</p>
                                    </div>
                                    @endif

                                    <!-- Loading Indicator -->
                                    <div wire:loading wire:target="newPhotos" class="text-center p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0112-12V0C5.373 0 0 5.373 0 12h4z" />
                                            </svg>
                                            <p class="text-sm font-medium text-indigo-700">Uploading photos...</p>
                                        </div>
                                    </div>

                                    <!-- Photo Preview Grid -->
                                    @if(!empty($photos))
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ count($photos) }} {{ count($photos) > 1 ? 'photos' : 'photo' }} uploaded
                                        </p>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            @foreach($photos as $index => $photo)
                                            <div class="relative group">
                                                <img src="{{ $photo->temporaryUrl() }}"
                                                    class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 group-hover:border-indigo-400 transition">
                                                <button type="button" 
                                                        wire:click="removePhoto({{ $index }})"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-lg hover:bg-red-600 transition-all transform hover:scale-110">
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
                                    </div>
                                    @endif
                                </div>
                                
                                @error('photos') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                                @error('photos.*') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                                @error('newPhotos') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                                @error('newPhotos.*') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row gap-3 sm:justify-end border-t border-gray-200">
                    <button type="button" 
                            wire:click="closeModal"
                            class="inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="confirmItem"
                            wire:loading.attr="disabled"
                            class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <span wire:loading.remove wire:target="confirmItem">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirm & Register Item
                        </span>
                        <span wire:loading wire:target="confirmItem" class="inline-flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    @if($showLightbox)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black bg-opacity-95" 
         wire:click="closeLightbox">
        
        <!-- Close Button -->
        <button 
            wire:click="closeLightbox"
            class="absolute top-4 right-4 z-10 p-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Previous Button -->
        @if(count($lightboxPhotos) > 1 && $currentPhotoIndex > 0)
        <button 
            wire:click.stop="previousPhoto"
            class="absolute left-4 z-10 p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        @endif

        <!-- Image Container -->
        <div class="relative max-w-6xl max-h-[90vh] flex items-center justify-center" 
             wire:click.stop>
            @if(Storage::disk('public')->exists($currentPhotoUrl))
            <img src="{{ Storage::url($currentPhotoUrl) }}" 
                 alt="Full size photo" 
                 class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
            @endif
            
            <!-- Photo Counter -->
            @if(count($lightboxPhotos) > 1)
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-70 text-white px-4 py-2 rounded-full text-sm font-medium">
                {{ $currentPhotoIndex + 1 }} / {{ count($lightboxPhotos) }}
            </div>
            @endif
        </div>

        <!-- Next Button -->
        @if(count($lightboxPhotos) > 1 && $currentPhotoIndex < count($lightboxPhotos) - 1)
        <button 
            wire:click.stop="nextPhoto"
            class="absolute right-4 z-10 p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        @endif
    </div>
    @endif
    @endif
</div>
