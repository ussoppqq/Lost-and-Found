<div>
<!-- Backdrop Blur -->
<div class="fixed inset-0 bg-opacity-50 backdrop-blur-sm transition-opacity z-40" wire:click="$parent.closeClaimDetailModal"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $claim->getStatusBadgeClass() }}">
                        {{ $claim->claim_status }}
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900">Claim Details</h3>
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

        <!-- Body -->
        <div class="px-6 py-6 space-y-6">
            
            <!-- Match Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Match Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Lost Report -->
                    <div class="bg-white rounded-lg p-4 border-2 border-red-200">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">
                                {{ $claim->match->lostReport->formatted_report_number }}
                            </span>
                            <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">LOST</span>
                        </div>
                        <h5 class="font-bold text-gray-900 mb-1">{{ $claim->match->lostReport->item_name }}</h5>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($claim->match->lostReport->report_description, 80) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $claim->match->lostReport->user->full_name ?? $claim->match->lostReport->reporter_name }}
                        </div>
                    </div>

                    <!-- Found Report -->
                    <div class="bg-white rounded-lg p-4 border-2 border-green-200">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">
                                {{ $claim->match->foundReport->formatted_report_number }}
                            </span>
                            <span class="px-2 py-1 bg-green-600 text-white text-xs font-bold rounded">FOUND</span>
                        </div>
                        <h5 class="font-bold text-gray-900 mb-1">{{ $claim->match->foundReport->item_name }}</h5>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($claim->match->foundReport->report_description, 80) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $claim->match->foundReport->user->full_name ?? $claim->match->foundReport->reporter_name }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Information -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-4 border-2 border-indigo-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Found Item Details</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Item Name</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $claim->item->item_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                        <p class="text-sm text-gray-900">{{ $claim->item->category->category_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Storage Location</label>
                        <p class="text-sm text-gray-900">{{ $claim->item->storage ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Item Status</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @switch($claim->item->item_status)
                                @case('REGISTERED') bg-indigo-600 text-white @break
                                @case('STORED') bg-blue-600 text-white @break
                                @case('CLAIMED') bg-purple-600 text-white @break
                                @case('DISPOSED') bg-red-600 text-white @break
                                @case('RETURNED') bg-green-600 text-white @break
                                @default bg-gray-600 text-white
                            @endswitch">
                            {{ $claim->item->item_status }}
                        </span>
                    </div>
                </div>

                <!-- Item Photos -->
                @if($claim->item->photos && $claim->item->photos->count() > 0)
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Item Photos</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($claim->item->photos as $photo)
                                <img src="{{ Storage::url($photo->photo_url) }}" 
                                     alt="Item photo" 
                                     class="w-full h-32 object-cover rounded-lg border-2 border-indigo-200 hover:border-indigo-400 transition cursor-pointer"
                                     onclick="window.open('{{ Storage::url($photo->photo_url) }}', '_blank')">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Claim Verification Details -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Verification Details</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($claim->brand)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Brand / Model</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $claim->brand }}</p>
                        </div>
                    @endif
                    
                    @if($claim->color)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Color</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $claim->color }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Claim Status</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $claim->getStatusBadgeClass() }}">
                            {{ $claim->claim_status }}
                        </span>
                    </div>
                    
                    @if($claim->pickup_schedule)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pickup Schedule</label>
                            <p class="text-sm text-gray-900">{{ $claim->pickup_schedule->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>

                @if($claim->claim_notes)
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Verification Notes</label>
                        <p class="text-sm text-gray-900 bg-white rounded-lg p-3 border border-gray-200">{{ $claim->claim_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Verification Photos -->
            @if($claim->claim_photos && count($claim->claim_photos) > 0)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Verification Photos</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($claim->claim_photos as $photo)
                            <img src="{{ Storage::url($photo) }}" 
                                 alt="Verification photo" 
                                 class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-purple-400 transition cursor-pointer"
                                 onclick="window.open('{{ Storage::url($photo) }}', '_blank')">
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Claimer Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Claimer Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($claim->user)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($claim->user->full_name) }}&background=1f2937&color=fff" 
                                     alt="">
                                <p class="text-sm font-semibold text-gray-900">{{ $claim->user->full_name }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $claim->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-sm text-gray-900">{{ $claim->user->phone_number ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">System Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600">
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Claim Created</label>
                        <p>{{ $claim->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Last Updated</label>
                        <p>{{ $claim->updated_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Claim ID</label>
                        <p class="font-mono text-xs">{{ $claim->claim_id }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Match ID</label>
                        <p class="font-mono text-xs">{{ $claim->match_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end sticky bottom-0">
            <button 
                type="button"
                wire:click="closeModal"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
</div>