<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-800 to-gray-600 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-3">Lost Items Directory</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore our database of lost items waiting to be reunited with their owners. If you recognize any item, click to view details and contact information.</p>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 mb-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Find a Lost Item</h2>
                <p class="text-sm text-gray-600">Use the search and filter options below to narrow down your results</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search Items
                        </div>
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by item name, location, or description..."
                        class="w-full px-5 py-3.5 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400 transition-all"
                    />
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Category
                        </div>
                    </label>
                    <select
                        wire:model.live="categoryFilter"
                        class="w-full px-5 py-3.5 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 transition-all"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Info Bar -->
        <div class="flex items-center justify-between mb-6 px-2">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-6 bg-gray-800 rounded-full"></div>
                <p class="text-base font-semibold text-gray-900">
                    {{ $lostItems->total() }} Lost {{ Str::plural('Item', $lostItems->total()) }} Found
                </p>
            </div>
            @if($search || $categoryFilter)
            <button
                wire:click="$set('search', ''); $set('categoryFilter', '')"
                class="text-sm text-gray-600 hover:text-gray-900 font-medium flex items-center gap-1 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear Filters
            </button>
            @endif
        </div>

        <!-- Items Grid -->
        @if($lostItems->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($lostItems as $report)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Content -->
                <div class="p-6">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 mb-4 flex-wrap">
                        <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-sm">
                            Lost Item
                        </span>
                        @if($report->item && $report->item->category)
                        <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $report->item->category->category_name }}
                        </span>
                        @elseif($report->category)
                        <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $report->category->category_name }}
                        </span>
                        @endif
                    </div>

                    <!-- Item Name -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 min-h-[3.5rem]">
                        {{ optional($report->item)->item_name ?? $report->item_name ?? 'Lost Item' }}
                    </h3>

                    <!-- Description -->
                    @if($report->report_description)
                    <p class="text-sm text-gray-600 line-clamp-3 mb-5 leading-relaxed min-h-[4rem]">
                        {{ $report->report_description }}
                    </p>
                    @else
                    <div class="mb-5 min-h-[4rem]"></div>
                    @endif

                    <!-- Info Grid -->
                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 mb-0.5">Location</div>
                                <div class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $report->report_location }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 mb-0.5">Date Reported</div>
                                <div class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 mb-0.5">Status</div>
                                <div class="text-sm font-semibold text-gray-900">
                                    @if($report->item)
                                        @if($report->item->item_status === 'PENDING')
                                            <span class="text-yellow-700">Pending Verification</span>
                                        @elseif($report->item->item_status === 'STORED')
                                            <span class="text-blue-700">In Storage</span>
                                        @elseif($report->item->item_status === 'CLAIMED')
                                            <span class="text-purple-700">Claimed</span>
                                        @elseif($report->item->item_status === 'RETURNED')
                                            <span class="text-green-700">Returned to Owner</span>
                                        @else
                                            <span class="text-gray-700">{{ $report->item->item_status }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-700">Open</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $lostItems->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-200 p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 mb-6 shadow-inner">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No Lost Items Found</h3>
            <p class="text-base text-gray-600 mb-6 max-w-md mx-auto">
                @if($search || $categoryFilter)
                    We couldn't find any items matching your search criteria. Try adjusting your filters or search terms.
                @else
                    There are currently no open lost item reports in our database.
                @endif
            </p>
            @if($search || $categoryFilter)
            <button
                wire:click="$set('search', ''); $set('categoryFilter', '')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold text-sm shadow-lg hover:shadow-xl transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear All Filters
            </button>
            @endif
        </div>
        @endif
    </div>
</div>
