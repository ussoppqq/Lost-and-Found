<div class="min-h-screen bg-white py-4">
    <div class="mx-auto w-full px-4 max-w-lg lg:max-w-7xl">
        {{-- WRAPPER CARD --}}
        <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-8">

            <!-- Hero Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-800 shadow mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-2">Lost Items Directory</h1>
                <p class="text-sm text-gray-600">
                    Explore our database of lost items waiting to be reunited with their owners.
                </p>
            </div>

            <!-- Search & Filter -->
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-6">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by item name, location, or description..."
                    class="w-full px-5 py-3.5 rounded-xl border-2 border-gray-300"
                />

                <select
                    wire:model.live="categoryFilter"
                    class="w-full px-5 py-3.5 rounded-xl border-2 border-gray-300"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Items Grid -->
        @if($lostItems->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            @foreach($lostItems as $report)
            <div x-data="{ open: false }" class="relative">

                <!-- CARD LIST -->
                <div
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden cursor-pointer"
                    @click="open = true"
                >
                    <div class="p-6">
                        <!-- Badge -->
                        <span class="inline-flex mb-3 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white">
                            Lost Item
                        </span>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 min-h-[3.5rem]">
                            {{ optional($report->item)->item_name ?? $report->item_name ?? 'Lost Item' }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-3 min-h-[4rem]">
                            {{ $report->report_description }}
                        </p>

                        <!-- Mobile hint -->
                        <p class="text-xs text-gray-400 mt-2 md:hidden">
                            Tap card to view full details
                        </p>

                        <!-- DESKTOP INFO GRID -->
                        <div class="hidden md:block mt-4">
                            <!-- Info Grid -->
                            <div class="bg-gray-50 rounded-xl p-4 space-y-3">

                                <!-- Location -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-gray-500 mb-0.5">Location</div>
                                        <div class="text-sm font-semibold text-gray-900 line-clamp-2">
                                            {{ $report->report_location }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs text-gray-500 mb-0.5">Date Reported</div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                </div>

                <!-- MOBILE MODAL -->
                <div
                    x-show="open"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center md:hidden"
                >
                    <div
                        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                        @click="open = false"
                    ></div>

                    <div
                        x-transition.scale
                        class="relative bg-white rounded-2xl shadow-2xl w-[90%] max-h-[85vh] overflow-y-auto p-6"
                    >
                        <button
                            class="absolute top-4 right-4 text-sm text-gray-500 hover:text-gray-800"
                            @click="open = false"
                        >
                            Close
                        </button>

                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ optional($report->item)->item_name ?? $report->item_name ?? 'Lost Item' }}
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ $report->report_description }}
                        </p>

                        <!-- SAME INFO GRID -->
                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <!-- (copy SAME info grid block above – sengaja disamakan) -->
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-gray-500 mb-0.5">Location</div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $report->report_location }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 mb-0.5">Date Reported</div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 mb-0.5">Status</div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $report->item->item_status ?? 'Open' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $lostItems->links() }}
        </div>
        @endif

        </div>
        {{-- END WRAPPER CARD --}}
    </div>
</div>