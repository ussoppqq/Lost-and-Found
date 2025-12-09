<div>
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-opacity-50 backdrop-blur-sm transition-opacity z-40" 
         wire:click="closeModal"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl my-8 transform transition-all max-h-[90vh] overflow-y-auto" 
             @click.stop>
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Process Claim</h3>
                        <p class="mt-1 text-sm text-gray-600">Verify and document the item return process</p>
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $claim->getStatusBadgeClass() }}">
                                {{ $claim->claim_status }}
                            </span>
                        </div>
                    </div>
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session()->has('success'))
                <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Body -->
            <div class="px-6 py-6 space-y-6">
                
                <!-- Match Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Match Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Lost Report -->
                        <div class="bg-white rounded-lg p-4 border-2 border-red-200">
                            <div class="flex items-center mb-2">
                                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">
                                    {{ $match->lostReport->formatted_report_number }}
                                </span>
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">LOST</span>
                            </div>
                            <h5 class="font-bold text-gray-900 mb-1">{{ $match->lostReport->item_name }}</h5>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($match->lostReport->report_description, 80) }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $match->lostReport->user->full_name ?? $match->lostReport->reporter_name }}
                            </div>
                        </div>

                        <!-- Found Report -->
                        <div class="bg-white rounded-lg p-4 border-2 border-green-200">
                            <div class="flex items-center mb-2">
                                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">
                                    {{ $match->foundReport->formatted_report_number }}
                                </span>
                                <span class="px-2 py-1 bg-green-600 text-white text-xs font-bold rounded">FOUND</span>
                            </div>
                            <h5 class="font-bold text-gray-900 mb-1">{{ $match->foundReport->item_name }}</h5>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($match->foundReport->report_description, 80) }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $match->foundReport->user->full_name ?? $match->foundReport->reporter_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Verification Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Brand / Model
                        </label>
                        <input 
                            type="text" 
                            wire:model="brand"
                            placeholder="e.g., Samsung, iPhone, etc."
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @error('brand') border-red-500 @enderror">
                        @error('brand')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Color
                        </label>
                        <input 
                            type="text" 
                            wire:model="color"
                            placeholder="e.g., Black, Blue, etc."
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @error('color') border-red-500 @enderror">
                        @error('color')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Claim Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Verification Notes <span class="text-gray-500 font-normal">(Optional)</span>
                    </label>
                    <textarea 
                        wire:model="claimNotes"
                        rows="4"
                        placeholder="Add notes about the verification process, item condition, or any important observations..."
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition resize-none @error('claimNotes') border-red-500 @enderror"></textarea>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Document any verification details or observations during the return process
                    </p>
                    @error('claimNotes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Claim Photos -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Verification Photos <span class="text-gray-500 font-normal">(Optional)</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Upload photos showing the item being returned to the owner</p>
                    
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition">
                        <input 
                            type="file" 
                            wire:model="tempPhotos"
                            multiple
                            accept="image/*"
                            class="hidden"
                            id="claim-photos">
                        <label for="claim-photos" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB each</p>
                        </label>
                    </div>

                    <!-- Loading Indicator -->
                    <div wire:loading wire:target="tempPhotos" class="mt-3 text-center">
                        <p class="text-sm text-purple-600">Uploading photos...</p>
                    </div>

                    <!-- Preview Photos -->
                    @if(count($tempPhotos) > 0)
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($tempPhotos as $index => $photo)
                                <div class="relative group">
                                    <img src="{{ $photo->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                                    <button 
                                        type="button"
                                        wire:click="removePhoto({{ $index }})"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @error('tempPhotos.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Important Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <h5 class="text-sm font-semibold text-yellow-800 mb-1">Important Notice</h5>
                            <p class="text-sm text-yellow-700">
                                <strong>Release:</strong> Item will be marked as returned to owner. Reports will be closed.<br>
                                <strong>Reject:</strong> Claim will be rejected, reports will be closed, and item will return to storage for potential matching with other users.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
                <!-- Reject Button (Left) -->
                <button 
                    type="button"
                    wire:click="openRejectModal"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Claim
                </button>

                <!-- Cancel & Release Buttons (Right) -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button 
                        type="button"
                        wire:click="releaseClaim"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span wire:loading.remove wire:target="releaseClaim">Release Item</span>
                        <span wire:loading wire:target="releaseClaim">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.stop>
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-900">Reject Claim</h3>
                    <p class="mt-1 text-sm text-gray-600">Please provide a reason for rejecting this claim. This action will close the reports and return the item to storage.</p>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea 
                    wire:model="rejectionReason"
                    rows="4"
                    placeholder="e.g., User declined to pickup, Cannot verify ownership, Item verification failed..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none @error('rejectionReason') border-red-500 @enderror"></textarea>
                
                @error('rejectionReason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <button 
                    type="button"
                    wire:click="closeRejectModal"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button 
                    type="button"
                    wire:click="rejectClaim"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors">
                    <span wire:loading.remove wire:target="rejectClaim">Confirm Rejection</span>
                    <span wire:loading wire:target="rejectClaim">Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>