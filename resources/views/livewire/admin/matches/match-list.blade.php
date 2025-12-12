<div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Active -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Active</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-semibold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Confirmed -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Confirmed</p>
                    <p class="text-3xl font-semibold text-green-600">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
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

    <!-- Info Banner untuk Rejected Matches -->
    @if($statusFilter === 'REJECTED')
        <div class="mb-4 bg-blue-50 border border-blue-200 px-4 py-3 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Viewing rejected matches.</span> 
                        These reports have been returned to STORED status and can be matched again through "Create New Match".
                    </p>
                </div>
            </div>
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
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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
                <!-- Full Table Body - Replace entire <tbody> section -->
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($matches as $match)
                        <tr class="hover:bg-gray-50 {{ $match->trashed() ? 'opacity-60' : '' }}">
                            <!-- Lost Report Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($match->lostReport->photo_url)
                                        <img src="{{ Storage::url($match->lostReport->photo_url) }}" 
                                            alt="Lost item" 
                                            class="w-10 h-10 rounded object-cover mr-3 {{ $match->trashed() ? 'grayscale' : '' }}">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
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

                            <!-- Found Report Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($match->foundReport->photo_url)
                                        <img src="{{ Storage::url($match->foundReport->photo_url) }}" 
                                            alt="Found item" 
                                            class="w-10 h-10 rounded object-cover mr-3 {{ $match->trashed() ? 'grayscale' : '' }}">
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
                                        @if(!$match->trashed())
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
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <!-- Match Status -->
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $match->getStatusBadgeClass() }}">
                                        {{ $match->match_status }}
                                    </span>
                                    
                                    <!-- Claim Status (jika ada) -->
                                    @if($match->hasClaim())
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $match->claim->getStatusBadgeClass() }}">
                                            Claim: {{ $match->claim->claim_status }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Matched By Column -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $match->matcher->full_name ?? 'System' }}</div>
                            </td>

                            <!-- Date Column -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ $match->matched_at->format('d M Y H:i') }}
                                </div>
                                @if($match->trashed())
                                    <div class="text-xs text-red-600 mt-1">
                                        Rejected: {{ $match->deleted_at->format('d M Y H:i') }}
                                    </div>
                                @endif
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View Button - Selalu Ada -->
                                    <button 
                                        wire:click="viewMatch('{{ $match->match_id }}')"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="View Details">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    @if($match->trashed())
                                        <!-- Rejected Match - Hanya Delete Permanent -->
                                        <button 
                                            wire:click="deleteMatch('{{ $match->match_id }}')"
                                            wire:confirm="Permanently delete this rejected match? This action cannot be undone!"
                                            class="text-red-600 hover:text-red-900"
                                            title="Delete Permanently">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <!-- Active Match Actions -->
                                        @if($match->isPending())
                                            <!-- Match PENDING - Show Confirm/Reject -->
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
                                                wire:confirm="Reject this match? Reports will be available for new matching."
                                                class="text-red-600 hover:text-red-900"
                                                title="Reject Match">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif

                                        @if($match->isConfirmed())
                                            <!-- Match CONFIRMED - Show Process Claim Button -->
                                            @if($match->hasClaim())
                                                @if($match->claim->isPending())
                                                    <!-- 🎯 INI YANG PENTING - Button Process Claim untuk PENDING -->
                                                    <button 
                                                        wire:click="processClaim('{{ $match->match_id }}')"
                                                        class="text-purple-600 hover:text-purple-900"
                                                        title="Process Claim (Pending)">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </button>
                                                @elseif($match->claim->isReleased() || $match->claim->isRejected())
                                                    <!-- Claim sudah diproses - Show View Claim -->
                                                    <button 
                                                        wire:click="viewClaim('{{ $match->match_id }}')"
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                        title="View Claim Details">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            @endif
                                        @endif

                                        <!-- Delete Button - Hanya jika tidak ada claim yang RELEASED -->
                                        @if(!$match->hasClaim() || !$match->claim->isReleased())
                                            <button 
                                                wire:click="deleteMatch('{{ $match->match_id }}')"
                                                wire:confirm="Permanently delete this match? This action cannot be undone!"
                                                class="text-gray-600 hover:text-gray-900"
                                                title="Delete Match">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
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

    <!-- Modals -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.match-create :key="'create-'.now()" />
            </div>
        </div>
    @endif

    @if($selectedMatchId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.match-detail :matchId="$selectedMatchId" :key="'detail-'.$selectedMatchId" />
            </div>
        </div>
    @endif

    @if($showClaimModal && $selectedMatchForClaim)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.process-claim :matchId="$selectedMatchForClaim" :key="'claim-'.$selectedMatchForClaim" />
            </div>
        </div>
    @endif

    @if($showClaimDetailModal && $selectedClaimId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 backdrop-blur-sm z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <livewire:admin.matches.claim-detail :claimId="$selectedClaimId" :key="'claim-detail-'.$selectedClaimId" />
            </div>
        </div>
    @endif
</div>