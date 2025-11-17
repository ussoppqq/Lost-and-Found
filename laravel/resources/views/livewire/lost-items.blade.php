<div class="min-h-screen bg-white py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-800 shadow mb-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-2">Lost Items</h1>
            <p class="text-sm text-gray-600">Browse open lost item reports and help reunite them with their owners</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
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
                        class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 placeholder:text-gray-400"
                    />
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Category
                        </div>
                    </label>
                    <select
                        wire:model.live="categoryFilter"
                        class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-900">{{ $lostItems->total() }}</span> lost items found
            </p>
        </div>

        <!-- Items Grid -->
        @if($lostItems->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($lostItems as $report)
            <a href="{{ route('public.tracking.detail', ['reportId' => $report->report_id]) }}"
               class="block bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-all overflow-hidden group">
                <!-- Image -->
                <div class="w-full h-48 bg-gray-100 overflow-hidden">
                    @if(optional($report->item)->photos && $report->item->photos->isNotEmpty())
                        <img src="{{ asset('storage/' . $report->item->photos->first()->photo_url) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             alt="Item photo" />
                    @elseif($report->photo_url)
                        <img src="{{ asset('storage/' . $report->photo_url) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             alt="Report photo" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-5">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">
                            Lost Item
                        </span>
                        @if($report->item && $report->item->category)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                            {{ $report->item->category->category_name }}
                        </span>
                        @elseif($report->category)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                            {{ $report->category->category_name }}
                        </span>
                        @endif
                    </div>

                    <!-- Item Name -->
                    <div class="text-base font-bold text-gray-900 truncate mb-2 group-hover:text-gray-800 transition-colors">
                        {{ optional($report->item)->item_name ?? $report->item_name ?? 'Lost Item' }}
                    </div>

                    <!-- Description -->
                    @if($report->report_description)
                    <p class="text-xs text-gray-600 line-clamp-2 mb-3">
                        {{ $report->report_description }}
                    </p>
                    @endif

                    <!-- Location & Date -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">{{ $report->report_location }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- View Details -->
                    <div class="mt-4 flex items-center text-gray-700 text-xs font-semibold group-hover:gap-2 transition-all">
                        <span>View Details</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $lostItems->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Lost Items Found</h3>
            <p class="text-sm text-gray-600 mb-4">
                @if($search || $categoryFilter)
                    Try adjusting your search or filters to find what you're looking for.
                @else
                    There are currently no open lost item reports.
                @endif
            </p>
            @if($search || $categoryFilter)
            <button
                wire:click="$set('search', ''); $set('categoryFilter', '')"
                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold text-sm transition-all"
            >
                Clear Filters
            </button>
            @endif
        </div>
        @endif
    </div>
</div>
