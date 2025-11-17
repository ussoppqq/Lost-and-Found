<div>
    {{-- Quick Match Modal --}}
    @if($showMatchModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-opacity-50 backdrop-blur-sm" wire:click="closeMatchModal"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-7xl my-8 max-h-[90vh] overflow-hidden" @click.stop>
                <!-- Header -->
                <div class="px-6 py-5 border-b bg-gradient-to-r from-blue-50 to-purple-50 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Quick Match Report</h3>
                            <p class="mt-1 text-sm text-gray-600">Find or create {{ $oppositeType === 'LOST' ? 'lost' : 'found' }} items to match</p>
                        </div>
                        <button wire:click="closeMatchModal" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-white rounded-lg transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @if (session()->has('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                    <div class="px-6 py-6">
                        
                        <!-- Two Column Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            
                            <!-- Left: Current Report -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-900">Current Report</h4>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">Source</span>
                                </div>

                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border-2 border-gray-200">
                                    <div class="flex gap-4">
                                        @if($sourceReport->photos?->count() > 0)
                                            <img src="{{ Storage::url($sourceReport->photos->first()->photo_url) }}" 
                                                 class="w-28 h-28 object-cover rounded-lg border-2 border-gray-300 shadow-sm flex-shrink-0">
                                        @else
                                            <div class="w-28 h-28 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300 flex-shrink-0">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded">{{ $sourceReport->formatted_report_number }}</span>
                                                <span class="px-2 py-1 {{ $sourceReport->report_type === 'LOST' ? 'bg-red-600' : 'bg-green-600' }} text-white text-xs font-bold rounded">{{ $sourceReport->report_type }}</span>
                                            </div>
                                            <h5 class="font-bold text-gray-900 mb-1 truncate">{{ $sourceReport->item_name }}</h5>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $sourceReport->report_description }}</p>
                                            
                                            <div class="flex flex-col gap-1.5 text-xs text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $sourceReport->report_datetime->format('d M Y H:i') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $sourceReport->report_location }}
                                                </div>
                                                @if($sourceReport->category)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    {{ $sourceReport->category->category_name }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-semibold mb-1">Looking for {{ $oppositeType }} Items</p>
                                            <p class="text-xs">Search existing reports or create new {{ strtolower($oppositeType) }} report to match</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Search & Select or Create -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-900">Select Matching Report</h4>
                                    <span class="text-xs text-gray-500">{{ $availableReports->count() }} available</span>
                                </div>

                                <!-- Search Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Search by Report ID or Item Name
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            wire:model.live.debounce.300ms="searchTerm" 
                                            placeholder="Type report number, item name, or location..."
                                            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                                    <select wire:model.live="selectedCategoryId" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Selected Report Preview -->
                                @if($selectedReportId && $selectedReport)
                                    <div class="bg-gradient-to-br {{ $selectedReport->report_type === 'LOST' ? 'from-red-50 to-red-100 border-red-300' : 'from-green-50 to-green-100 border-green-300' }} rounded-xl p-5 border-2">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-semibold text-gray-600">SELECTED REPORT</span>
                                            <button wire:click="selectReport(null)" class="text-gray-500 hover:text-gray-700 transition">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="flex gap-4">
                                            @if($selectedReport->photos?->count() > 0)
                                                <img src="{{ Storage::url($selectedReport->photos->first()->photo_url) }}" 
                                                     class="w-24 h-24 object-cover rounded-lg border-2 {{ $selectedReport->report_type === 'LOST' ? 'border-red-300' : 'border-green-300' }} shadow-sm flex-shrink-0">
                                            @else
                                                <div class="w-24 h-24 {{ $selectedReport->report_type === 'LOST' ? 'bg-red-200' : 'bg-green-200' }} rounded-lg flex items-center justify-center border-2 {{ $selectedReport->report_type === 'LOST' ? 'border-red-300' : 'border-green-300' }} flex-shrink-0">
                                                    <svg class="w-10 h-10 {{ $selectedReport->report_type === 'LOST' ? 'text-red-400' : 'text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap gap-2 mb-2">
                                                    <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded">{{ $selectedReport->formatted_report_number }}</span>
                                                    <span class="px-2 py-1 {{ $selectedReport->report_type === 'LOST' ? 'bg-red-600' : 'bg-green-600' }} text-white text-xs font-bold rounded">{{ $selectedReport->report_type }}</span>
                                                    @if($selectedReport->report_type === 'FOUND')
                                                        <span class="px-2 py-1 {{ $selectedReport->item_id ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-300' }} text-xs font-medium rounded">
                                                            {{ $selectedReport->item_id ? '✓ Item Registered' : 'No Item Yet' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <h5 class="font-bold text-gray-900 mb-1 truncate">{{ $selectedReport->item_name }}</h5>
                                                <p class="text-sm text-gray-700 mb-2 line-clamp-2">{{ $selectedReport->report_description }}</p>
                                                <div class="flex items-center text-xs text-gray-600">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $selectedReport->report_datetime->format('d M Y') }}
                                                    <span class="mx-2">•</span>
                                                    {{ $selectedReport->report_location }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Available Reports List -->
                                    @if($availableReports->count() > 0)
                                        <div class="border border-gray-300 rounded-lg max-h-80 overflow-y-auto">
                                            @foreach($availableReports as $rep)
                                                <div 
                                                    wire:click="selectReport('{{ $rep->report_id }}')" 
                                                    class="flex gap-3 p-3 border-b last:border-b-0 hover:bg-blue-50 cursor-pointer transition group">
                                                    
                                                    @if($rep->photos?->count() > 0)
                                                        <img src="{{ Storage::url($rep->photos->first()->photo_url) }}" 
                                                             class="w-16 h-16 object-cover rounded-lg border border-gray-200 group-hover:border-blue-300 flex-shrink-0">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 group-hover:border-blue-300 flex-shrink-0">
                                                            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex flex-wrap gap-1.5 mb-1.5">
                                                            <span class="px-1.5 py-0.5 bg-gray-800 text-white text-xs font-bold rounded">{{ $rep->formatted_report_number }}</span>
                                                            <span class="px-1.5 py-0.5 {{ $rep->report_type === 'LOST' ? 'bg-red-600' : 'bg-green-600' }} text-white text-xs font-bold rounded">{{ $rep->report_type }}</span>
                                                            @if($rep->report_type === 'FOUND' && !$rep->item_id)
                                                                <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded border border-yellow-300">No Item</span>
                                                            @endif
                                                        </div>
                                                        <h5 class="font-semibold text-sm text-gray-900 mb-1 truncate group-hover:text-blue-600">{{ $rep->item_name }}</h5>
                                                        <p class="text-xs text-gray-600 line-clamp-1">{{ $rep->report_description }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $rep->report_datetime->format('d M Y') }} • {{ $rep->report_location }}</p>
                                                    </div>

                                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-gray-600 font-medium mb-1">No {{ strtolower($oppositeType) }} reports found</p>
                                            <p class="text-sm text-gray-500">Try adjusting your filters or create a new report</p>
                                        </div>
                                    @endif
                                @endif

                                <!-- OR Divider -->
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-xs">
                                        <span class="px-3 bg-white text-gray-500 font-medium">OR CREATE NEW</span>
                                    </div>
                                </div>

                                <!-- Create New Button -->
                                <button 
                                    wire:click="openCreateAndMatchModal"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Create New {{ $oppositeType }} Report & Match
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center sticky bottom-0">
                    <button wire:click="closeMatchModal" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    
                    @if($selectedReportId)
                        <div class="flex gap-3">
                            <button wire:click="createMatch" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center shadow-md hover:shadow-lg transition">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Create Match Only
                            </button>
                            
                            <button wire:click="openClaimModal" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 flex items-center shadow-md hover:shadow-lg transition">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Match & Process Claim
                            </button>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="italic">Select a report or create new to continue</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Claim Modal --}}
    @if($showClaimModal && $selectedReport)
        @livewire('admin.lost-and-found.quick-claim', [
            'sourceReport' => $sourceReport,
            'targetReport' => $selectedReport,
        ], key('quick-claim-' . $sourceReportId . '-' . $selectedReportId))
    @endif

    {{-- Create & Match Component --}}
    @livewire('admin.lost-and-found.create-and-match')
</div>