<div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Reports</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $totalReports }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Lost Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Lost Reports</p>
                    <p class="text-3xl font-semibold text-red-600">{{ $lostReports }}</p>
                </div>
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Found Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Found Reports</p>
                    <p class="text-3xl font-semibold text-green-600">{{ $foundReports }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Matched -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Matched</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $matchedReports }}</p>
                </div>
                <div class="p-3 rounded-full bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Search reports...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Report Type Filter -->
                    <select wire:model.live="reportTypeFilter" 
                            class="block w-auto px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Types</option>
                        <option value="LOST">Lost</option>
                        <option value="FOUND">Found</option>
                    </select>

                    <!-- Report Status Filter -->
                    <select wire:model.live="reportStatusFilter" 
                            class="block w-auto px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Status</option>
                        <option value="OPEN">Open</option>
                        <option value="STORED">Stored</option>
                        <option value="MATCHED">Matched</option>
                        <option value="CLOSED">Closed</option>
                    </select>

                    <!-- Date To Filter -->
                    <input type="date" 
                           wire:model.live="dateTo"
                           placeholder="Date To"
                           class="block w-auto px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

                    <!-- Clear Filters -->
                    @if($search || $reportTypeFilter !== 'all' || $reportStatusFilter !== 'all' || $dateFrom || $dateTo)
                    <button wire:click="clearFilters" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Clear
                    </button>
                    @endif

                    <!-- Add Walk-in Item Button -->
                    <button wire:click="openCreateItemModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Walk-in Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- KOLOM ID BARU -->
                        <th wire:click="sortBy('report_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center space-x-1">
                                <span>ID</span>
                                @if($sortBy === 'report_number')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('item_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center space-x-1">
                                <span>Report</span>
                                @if($sortBy === 'item_name')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>
                        <th wire:click="sortBy('report_datetime')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                @if($sortBy === 'report_datetime')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reporter
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <!-- CELL ID BARU -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                {{ $report->formatted_report_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($report->report_type === 'LOST')
                                    <!-- <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div> -->
                                @else
                                    <!-- <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div> -->
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $report->item_name ?? $report->item->item_name ?? 'Unnamed Item' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($report->report_description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->report_type === 'LOST' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $report->report_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select wire:change="updateReportStatus('{{ $report->report_id }}', $event.target.value)" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1
                                    @switch($report->report_status)
                                        @case('OPEN') bg-yellow-100 text-yellow-800 focus:ring-yellow-500 @break
                                        @case('STORED') bg-blue-100 text-blue-800 focus:ring-blue-500 @break
                                        @case('MATCHED') bg-green-100 text-green-800 focus:ring-green-500 @break
                                        @case('CLOSED') bg-gray-100 text-gray-800 focus:ring-gray-500 @break
                                        @default bg-gray-100 text-gray-800 focus:ring-gray-500
                                    @endswitch">
                                <option value="OPEN" {{ $report->report_status === 'OPEN' ? 'selected' : '' }}>Open</option>
                                <option value="STORED" {{ $report->report_status === 'STORED' ? 'selected' : '' }}>Stored</option>
                                <option value="MATCHED" {{ $report->report_status === 'MATCHED' ? 'selected' : '' }}>Matched</option>
                                <option value="CLOSED" {{ $report->report_status === 'CLOSED' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($report->item)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($report->item->item_status)
                                        @case('REGISTERED') bg-indigo-100 text-indigo-800 @break
                                        @case('STORED') bg-blue-100 text-blue-800 @break
                                        @case('CLAIMED') bg-purple-100 text-purple-800 @break
                                        @case('DISPOSED') bg-red-100 text-red-800 @break
                                        @case('RETURNED') bg-green-100 text-green-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $report->item->item_status }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">No Item</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $report->report_location }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($report->report_datetime)->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($report->user)
                                    <img class="w-8 h-8 rounded-full mr-3" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($report->user->full_name) }}&background=1f2937&color=fff" 
                                         alt="">
                                    <div class="text-sm font-medium text-gray-900">{{ $report->user->full_name }}</div>
                                @else
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $report->reporter_name ?? 'Walk-in' }}</div>
                                            <div class="text-xs text-gray-500">{{ $report->reporter_phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
    @if($report->report_status === 'CLOSED' || $report->report_status === 'MATCHED')
        <!-- Ceklis jika sudah claimed/matched -->
        <button class="text-green-600" title="Completed" disabled>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>
    @elseif(!$report->item_id)
        <!-- Tombol View Detail jika belum ada item -->
        <button wire:click="viewReportDetail('{{ $report->report_id }}')" 
                class="text-blue-600 hover:text-blue-900"
                title="View Detail">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>
    @else
        <!-- Tombol View Detail -->
        <button wire:click="viewReportDetail('{{ $report->report_id }}')" 
                class="text-blue-600 hover:text-blue-900"
                title="View Detail">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>

        <!-- Tombol Edit Item (jika sudah ada item) -->
        <button wire:click="openEditItemModal('{{ $report->item_id }}')" 
                class="text-indigo-600 hover:text-indigo-900"
                title="Edit Item">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </button>
    @endif
    
    <!-- Delete button -->
    @if($report->report_status !== 'CLOSED' && $report->report_status !== 'MATCHED')
    <button wire:click="deleteReport('{{ $report->report_id }}')" 
            class="text-red-600 hover:text-red-900"
            title="Delete Report"
            onclick="return confirm('Are you sure you want to delete this report?')">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
    </button>
    @endif
</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-900 mb-2">No reports found</p>
                            <p class="text-sm text-gray-500">Get started by adding your first lost or found report.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $reports->links() }}
        </div>
        @endif
    </div>

    <!-- Include Modals -->
    @livewire('admin.lost-and-found.create-item')
    @livewire('admin.lost-and-found.edit-item')

    <!-- Report Detail Modal -->
    @if($showDetailModal && $selectedReportForDetail)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <livewire:admin.lost-and-found.report-detail :reportId="$selectedReportForDetail" :key="'detail-'.$selectedReportForDetail" />
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <!-- Backdrop dengan blur effect -->
    <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md my-8 transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium leading-6 text-gray-900">Delete Report</h3>
                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete this report? This action cannot be undone and will also delete the associated item if exists.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-400 transition">
                        <span wire:loading.remove wire:target="confirmDelete">Delete</span>
                        <span wire:loading wire:target="confirmDelete">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
</div>