<div>
<!-- Backdrop Blur -->
<div class="fixed inset-0 bg-transparent backdrop-blur-sm transition-opacity z-40" wire:click="$parent.closeDetailModal"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Match Details</h3>
                    <p class="mt-1 text-sm text-gray-600">ID: {{ substr($match->match_id, 0, 13) }}...</p>
                </div>
                <button 
                    type="button"
                    wire:click="$parent.closeDetailModal" 
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-6">
            <!-- Flash Message -->
            @if (session()->has('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Match Status & Actions -->
            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Status Badge -->
                        <span class="px-4 py-2 text-sm font-bold rounded-full {{ $match->getStatusBadgeClass() }}">
                            {{ $match->match_status }}
                        </span>

                        <!-- Matched Date -->
                        <div class="text-xs text-gray-600 bg-white px-3 py-2 rounded-lg shadow-sm">
                            <span class="font-medium">📅 Matched:</span> {{ $match->matched_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($match->isPending())
                        <div class="flex space-x-2">
                            <button 
                                wire:click="confirmMatch"
                                wire:confirm="Are you sure you want to confirm this match?"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm
                            </button>
                            <button 
                                wire:click="rejectMatch"
                                wire:confirm="Are you sure you want to reject this match?"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comparison Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- LOST Report Card -->
                <div class="border-2 border-red-300 rounded-xl bg-gradient-to-br from-red-50 to-red-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-red-600 text-white px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h4 class="font-bold text-lg">Lost Item</h4>
                                    <p class="text-xs opacity-90">LOST Report</p>
                                </div>
                            </div>
                            <span class="text-2xl font-bold opacity-75">#{{ substr($match->lostReport->report_id, 0, 8) }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Photo -->
                        @if($match->lostReport->photo_url)
                            <img src="{{ Storage::url($match->lostReport->photo_url) }}" 
                                 alt="Lost item" 
                                 wire:click="openImageModal('{{ Storage::url($match->lostReport->photo_url) }}', '{{ $match->lostReport->item_name }} (LOST)')"
                                 class="w-full h-56 object-cover rounded-lg shadow-md mb-4 border-2 border-red-200 cursor-pointer hover:opacity-90 transition">
                        @else
                            <div class="w-full h-56 bg-red-200 rounded-lg flex items-center justify-center mb-4 border-2 border-red-300">
                                <svg class="w-20 h-20 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Item Name -->
                        <h5 class="font-bold text-gray-900 text-xl mb-3 flex items-center">
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs mr-2">LOST</span>
                            {{ $match->lostReport->item_name }}
                        </h5>
                        
                        <!-- Details Grid -->
                        <div class="space-y-3 text-sm bg-white rounded-lg p-4 shadow-sm">
                            <!-- Description -->
                            <div class="pb-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700 block mb-1">📝 Description:</span>
                                <p class="text-gray-600">{{ $match->lostReport->report_description ?? '-' }}</p>
                            </div>

                            <!-- Date & Time -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">📅 Date:</span>
                                <span class="text-gray-900">{{ $match->lostReport->report_datetime->format('d M Y, H:i') }}</span>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">📍 Location:</span>
                                <span class="text-gray-900">{{ $match->lostReport->report_location }}</span>
                            </div>

                            <!-- Category -->
                            @if($match->lostReport->category)
                                <div class="flex items-start">
                                    <span class="font-semibold text-gray-700 w-28 flex-shrink-0">🏷️ Category:</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                                        {{ $match->lostReport->category->category_name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">⚡ Status:</span>
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $match->lostReport->report_status === 'MATCHED' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $match->lostReport->report_status }}
                                </span>
                            </div>

                            <!-- Reporter -->
                            @if($match->lostReport->reporter_name)
                                <div class="pt-3 border-t border-gray-200">
                                    <span class="font-semibold text-gray-700 block mb-2">👤 Reporter:</span>
                                    <div class="bg-red-50 p-2 rounded">
                                        <div class="font-medium text-gray-900">{{ $match->lostReport->reporter_name }}</div>
                                        @if($match->lostReport->reporter_phone)
                                            <div class="text-xs text-gray-600">📱 {{ $match->lostReport->reporter_phone }}</div>
                                        @endif
                                        @if($match->lostReport->reporter_email)
                                            <div class="text-xs text-gray-600">✉️ {{ $match->lostReport->reporter_email }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- FOUND Report Card -->
                <div class="border-2 border-green-300 rounded-xl bg-gradient-to-br from-green-50 to-green-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-green-600 text-white px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h4 class="font-bold text-lg">Found Item</h4>
                                    <p class="text-xs opacity-90">FOUND Report</p>
                                </div>
                            </div>
                            <span class="text-2xl font-bold opacity-75">#{{ substr($match->foundReport->report_id, 0, 8) }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Photo -->
                        @if($match->foundReport->photo_url)
                            <img src="{{ Storage::url($match->foundReport->photo_url) }}" 
                                 alt="Found item" 
                                 wire:click="openImageModal('{{ Storage::url($match->foundReport->photo_url) }}', '{{ $match->foundReport->item_name }} (FOUND)')"
                                 class="w-full h-56 object-cover rounded-lg shadow-md mb-4 border-2 border-green-200 cursor-pointer hover:opacity-90 transition">
                        @else
                            <div class="w-full h-56 bg-green-200 rounded-lg flex items-center justify-center mb-4 border-2 border-green-300">
                                <svg class="w-20 h-20 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Item Name -->
                        <h5 class="font-bold text-gray-900 text-xl mb-3 flex items-center">
                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs mr-2">FOUND</span>
                            {{ $match->foundReport->item_name }}
                        </h5>
                        
                        <!-- Details Grid -->
                        <div class="space-y-3 text-sm bg-white rounded-lg p-4 shadow-sm">
                            <!-- Description -->
                            <div class="pb-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700 block mb-1">📝 Description:</span>
                                <p class="text-gray-600">{{ $match->foundReport->report_description ?? '-' }}</p>
                            </div>

                            <!-- Date & Time -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">📅 Date:</span>
                                <span class="text-gray-900">{{ $match->foundReport->report_datetime->format('d M Y, H:i') }}</span>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">📍 Location:</span>
                                <span class="text-gray-900">{{ $match->foundReport->report_location }}</span>
                            </div>

                            <!-- Category -->
                            @if($match->foundReport->category)
                                <div class="flex items-start">
                                    <span class="font-semibold text-gray-700 w-28 flex-shrink-0">🏷️ Category:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                        {{ $match->foundReport->category->category_name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="flex items-start">
                                <span class="font-semibold text-gray-700 w-28 flex-shrink-0">⚡ Status:</span>
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $match->foundReport->report_status === 'MATCHED' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $match->foundReport->report_status }}
                                </span>
                            </div>

                            <!-- Reporter -->
                            @if($match->foundReport->reporter_name)
                                <div class="pt-3 border-t border-gray-200">
                                    <span class="font-semibold text-gray-700 block mb-2">👤 Reporter:</span>
                                    <div class="bg-green-50 p-2 rounded">
                                        <div class="font-medium text-gray-900">{{ $match->foundReport->reporter_name }}</div>
                                        @if($match->foundReport->reporter_phone)
                                            <div class="text-xs text-gray-600">📱 {{ $match->foundReport->reporter_phone }}</div>
                                        @endif
                                        @if($match->foundReport->reporter_email)
                                            <div class="text-xs text-gray-600">✉️ {{ $match->foundReport->reporter_email }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Notes -->
            @if($match->match_notes)
                <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-amber-900 mb-1">Match Notes</h4>
                            <p class="text-sm text-amber-800">{{ $match->match_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Match Timeline -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Timeline
                </h4>
                <div class="space-y-4">
                    <!-- Created -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-900">Match Created</p>
                            <p class="text-xs text-gray-600 mt-0.5">
                                {{ $match->matched_at->format('d M Y, H:i') }}
                                by <span class="font-medium text-blue-600">{{ $match->matcher->full_name ?? 'System' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Confirmed -->
                    @if($match->isConfirmed())
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-gray-900">Match Confirmed</p>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    {{ $match->confirmed_at->format('d M Y, H:i') }}
                                    by <span class="font-medium text-green-600">{{ $match->confirmer->full_name ?? 'System' }}</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Rejected -->
                    @if($match->isRejected())
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-gray-900">Match Rejected</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $match->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 sticky bottom-0">
            <button 
                type="button"
                wire:click="$parent.closeDetailModal"
                class="px-6 py-2.5 text-sm font-semibold bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
@if($showImageModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black bg-opacity-75" 
         wire:click="closeImageModal">
        <div class="relative max-w-5xl max-h-[90vh]" wire:click.stop>
            <!-- Close Button -->
            <button 
                wire:click="closeImageModal"
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Title -->
            <div class="absolute -top-10 left-0 text-white font-semibold text-lg">
                {{ $currentImageTitle }}
            </div>

            <!-- Image -->
            <img src="{{ $currentImage }}" 
                 alt="Full size preview" 
                 class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
        </div>
    </div>
@endif
</div>