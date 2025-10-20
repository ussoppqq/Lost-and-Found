<div>
<!-- Backdrop Blur -->
<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity z-40"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-800 text-white">
                        {{ $report->formatted_report_number }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $report->report_type === 'LOST' ? 'bg-red-600 text-white' : 'bg-green-600 text-white' }}">
                        {{ $report->report_type }}
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900">Report Details</h3>
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
            
            <!-- Report Photo -->
            @if($report->photo_url)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Report Photo</h4>
                    <img src="{{ Storage::url($report->photo_url) }}" 
                         alt="Report photo" 
                         class="w-full max-h-96 object-contain rounded-lg border-2 border-gray-200">
                </div>
            @endif

            <!-- Item Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Item Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Item Name</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $report->item_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                        <p class="text-sm text-gray-900">{{ $report->category->category_name ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-sm text-gray-900">{{ $report->report_description }}</p>
                    </div>
                </div>
            </div>

            <!-- Report Details -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Report Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Report Type</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $report->report_type === 'LOST' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $report->report_type }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Report Status</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @switch($report->report_status)
                                @case('OPEN') bg-yellow-100 text-yellow-800 @break
                                @case('STORED') bg-blue-100 text-blue-800 @break
                                @case('MATCHED') bg-green-100 text-green-800 @break
                                @case('CLOSED') bg-gray-100 text-gray-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ $report->report_status }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Location</label>
                        <p class="text-sm text-gray-900">{{ $report->report_location }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Date & Time</label>
                        <p class="text-sm text-gray-900">{{ $report->report_datetime->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Reporter Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Reporter Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($report->user)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Reporter Name</label>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($report->user->full_name) }}&background=1f2937&color=fff" 
                                     alt="">
                                <p class="text-sm font-semibold text-gray-900">{{ $report->user->full_name }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $report->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-sm text-gray-900">{{ $report->user->phone ?? 'N/A' }}</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Reporter Name</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $report->reporter_name ?? 'Walk-in' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-sm text-gray-900">{{ $report->reporter_phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $report->reporter_email ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Item Details (if exists) -->
            @if($report->item)
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-4 border-2 border-indigo-200">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-gray-900">Registered Item Details</h4>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @switch($report->item->item_status)
                                @case('REGISTERED') bg-indigo-600 text-white @break
                                @case('STORED') bg-blue-600 text-white @break
                                @case('CLAIMED') bg-purple-600 text-white @break
                                @case('DISPOSED') bg-red-600 text-white @break
                                @case('RETURNED') bg-green-600 text-white @break
                                @default bg-gray-600 text-white
                            @endswitch">
                            {{ $report->item->item_status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Item Name</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $report->item->item_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                            <p class="text-sm text-gray-900">{{ $report->item->category->category_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Storage Location</label>
                            <p class="text-sm text-gray-900">{{ $report->item->storage_location ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Registered Date</label>
                            <p class="text-sm text-gray-900">{{ $report->item->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if($report->item->description)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Item Description</label>
                                <p class="text-sm text-gray-900">{{ $report->item->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Item Photos -->
                    @if($report->item->photos && $report->item->photos->count() > 0)
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Item Photos</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($report->item->photos as $photo)
                                    <img src="{{ Storage::url($photo->photo_url) }}" 
                                         alt="Item photo" 
                                         class="w-full h-32 object-cover rounded-lg border-2 border-indigo-200 hover:border-indigo-400 transition cursor-pointer"
                                         onclick="window.open('{{ Storage::url($photo->photo_url) }}', '_blank')">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h5 class="text-sm font-semibold text-yellow-800 mb-1">No Item Registered</h5>
                            <p class="text-sm text-yellow-700">
                                This report has not been registered as an item in the system yet.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">System Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600">
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Created At</label>
                        <p>{{ $report->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Last Updated</label>
                        <p>{{ $report->updated_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">Report ID</label>
                        <p class="font-mono text-xs">{{ $report->report_id }}</p>
                    </div>
                    @if($report->item_id)
                        <div>
                            <label class="block font-medium text-gray-500 mb-1">Item ID</label>
                            <p class="font-mono text-xs">{{ $report->item_id }}</p>
                        </div>
                    @endif
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