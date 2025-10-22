<div>
<!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Matches</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confirmed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['confirmed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters and Actions -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                <div class="flex-1 flex items-center space-x-3">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by item name..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="PENDING">Pending</option>
                        <option value="CONFIRMED">Confirmed</option>
                        <option value="REJECTED">Rejected</option>
                    </select>
                </div>

                <!-- Create Button -->
                <button 
                    wire:click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Match
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lost Report</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Found Report</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matched By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($matches as $match)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($match->lostReport->photo_url)
                                        <img src="{{ Storage::url($match->lostReport->photo_url) }}" 
                                             alt="Lost item" 
                                             class="w-10 h-10 rounded object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 bg-gray-800 text-white text-xs font-bold rounded">
                                                {{ $match->lostReport->formatted_report_number }}
                                            </span>
                                            <div class="text-sm font-medium text-gray-900">{{ $match->lostReport->item_name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($match->lostReport->report_description, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($match->foundReport->photo_url)
                                        <img src="{{ Storage::url($match->foundReport->photo_url) }}" 
                                             alt="Found item" 
                                             class="w-10 h-10 rounded object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 bg-gray-800 text-white text-xs font-bold rounded">
                                                {{ $match->foundReport->formatted_report_number }}
                                            </span>
                                            <div class="text-sm font-medium text-gray-900">{{ $match->foundReport->item_name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($match->foundReport->report_description, 30) }}</div>
                                        <!-- Item Status Indicator -->
                                        @if($match->foundReport->item_id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Item Registered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                                No Item Yet
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $match->getStatusBadgeClass() }}">
                                    {{ $match->match_status }}
                                </span>
                                @if($match->hasClaim())
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Claimed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $match->matcher->full_name ?? 'System' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $match->matched_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button 
                                        wire:click="viewMatch('{{ $match->match_id }}')"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="View Details">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    @if($match->isPending())
                                        <!-- Confirm Button - Disable jika found report belum punya item -->
                                        @if($match->foundReport->item_id)
                                            <button 
                                                wire:click="confirmMatch('{{ $match->match_id }}')"
                                                wire:confirm="Confirm this match?"
                                                class="text-green-600 hover:text-green-900"
                                                title="Confirm Match">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button 
                                                disabled
                                                class="text-gray-400 cursor-not-allowed"
                                                title="Found report must have item first">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @endif

                                        <button 
                                            wire:click="rejectMatch('{{ $match->match_id }}')"
                                            wire:confirm="Reject this match?"
                                            class="text-red-600 hover:text-red-900"
                                            title="Reject Match">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($match->isConfirmed() && !$match->hasClaim())
                                        <!-- Process Claim Button -->
                                        <button 
                                            wire:click="processClaim('{{ $match->match_id }}')"
                                            class="text-purple-600 hover:text-purple-900"
                                            title="Process Claim">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($match->hasClaim())
                                        <!-- View Claim Button -->
                                        <button 
                                            wire:click="viewClaim('{{ $match->match_id }}')"
                                            class="text-indigo-600 hover:text-indigo-900"
                                            title="View Claim">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button 
                                        wire:click="deleteMatch('{{ $match->match_id }}')"
                                        wire:confirm="Delete this match?"
                                        class="text-gray-600 hover:text-gray-900"
                                        title="Delete Match">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No matches found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $matches->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop transparan dengan blur -->
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.match-create :key="'create-'.now()" />
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if($selectedMatchId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop transparan dengan blur -->
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
            
            <!-- Modal Container -->
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.match-detail :matchId="$selectedMatchId" :key="'detail-'.$selectedMatchId" />
            </div>
        </div>
    @endif

    <!-- Claim Processing Modal -->
@if($showClaimModal && $selectedMatchForClaim)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop transparan dengan blur -->
        <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
        
        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <livewire:admin.matches.process-claim :matchId="$selectedMatchForClaim" :key="'claim-'.$selectedMatchForClaim" />
        </div>
    </div>
@endif

<!-- Claim Detail Modal -->
@if($showClaimDetailModal && $selectedClaimId)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop transparan dengan blur -->
        <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
        
        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <livewire:admin.matches.claim-detail :claimId="$selectedClaimId" :key="'claim-detail-'.$selectedClaimId" />
        </div>
    </div>
@endif
</div>