<div class="p-6">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    {{-- Header with Create Button --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Users Management</h1>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500"
                            placeholder="Search users...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Role Filter -->
                    <select wire:model.live="roleFilter"
                        class="block w-auto px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                        <option value="all">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_code }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>

                    <!-- Verified Filter -->
                    <select wire:model.live="verifiedFilter"
                        class="block w-auto px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                        <option value="all">All Status</option>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>

                    <!-- Clear Filters -->
                    @if($search || $roleFilter !== 'all' || $verifiedFilter !== 'all')
                        <button wire:click="clearFilters"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </button>
                    @endif

                    <!-- Add New User Button -->
                    <button wire:click="$dispatch('openCreateModal')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm 
           text-sm font-medium text-white bg-black hover:bg-gray-800 
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('full_name')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Name
                        @if ($sortBy === 'full_name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Phone
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th wire:click="sortBy('is_verified')"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Status
                        @if ($sortBy === 'is_verified')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $user->role->role_name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->is_verified)
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Unverified
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                {{-- Verify Button --}}
                                @if (!$user->is_verified)
                                    <button wire:click="$dispatch('openVerifyModal', { userId: '{{ $user->user_id }}' })"
                                        class="text-green-600 hover:text-green-900" title="Verify User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                @endif

                                {{-- View Button --}}
                                <button wire:click="openDetailModal('{{ $user->user_id }}')"
                                    class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                {{-- Edit Button --}}
                                <button wire:click="$dispatch('openEditModal', { userId: '{{ $user->user_id }}' })"
                                    class="text-yellow-600 hover:text-yellow-900" title="Edit User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete Button --}}
                                <button wire:click="openDeleteModal('{{ $user->user_id }}')"
                                    class="text-red-600 hover:text-red-900" title="Delete User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                {{-- Toggle Verification --}}
                                <button wire:click="toggleVerification('{{ $user->user_id }}')"
                                    class="text-gray-600 hover:text-gray-900" title="Toggle Verification">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t">
            {{ $users->links() }}
        </div>
    </div>

    {{-- DETAIL MODAL --}}
    @if ($showDetailModal && $selectedUser)
        <!-- Backdrop dengan blur effect -->
        <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md my-8 transform transition-all">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">User Details</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-gray-900">{{ $selectedUser->full_name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $selectedUser->email ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone Number</label>
                            <p class="text-gray-900">{{ $selectedUser->phone_number }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <p class="text-gray-900">{{ $selectedUser->role->role_name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Company</label>
                            <p class="text-gray-900">{{ $selectedUser->company->company_name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Verification Status</label>
                            <div class="mt-1">
                                @if ($selectedUser->is_verified)
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                    @if ($selectedUser->phone_verified_at)
                                        <p class="text-xs text-gray-500 mt-1">Verified at:
                                            {{ $selectedUser->phone_verified_at->format('d M Y H:i') }}
                                        </p>
                                    @endif
                                @else
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Created At</label>
                            <p class="text-gray-900">{{ $selectedUser->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="text-gray-900">{{ $selectedUser->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="button" wire:click="closeModal"
                            class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- DELETE MODAL --}}
    @if ($showDeleteModal && $selectedUser)
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
                        <h3 class="ml-3 text-lg font-medium leading-6 text-gray-900">Delete User</h3>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">
                        Are you sure you want to delete <strong>{{ $selectedUser->full_name }}</strong>? This action
                        cannot be undone.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="button" wire:click="delete" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-400 transition">
                            <span wire:loading.remove wire:target="delete">Delete</span>
                            <span wire:loading wire:target="delete">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Include Child Components --}}
    <livewire:admin.users.create />
    <livewire:admin.users.edit />
    <livewire:admin.users.verify />
</div>