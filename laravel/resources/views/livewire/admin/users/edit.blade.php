<div>
@if ($showModal)
    <!-- Backdrop dengan blur effect -->
    <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg my-8 transform transition-all">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit User</h3>

                <form wire:submit.prevent="update">
                    {{-- Full Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" wire:model="full_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('full_name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone Number --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                        <input type="text" wire:model="phone_number"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('phone_number')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-yellow-600 mt-1">⚠️ Changing phone number will require
                            re-verification</p>
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select wire:model="role_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (Leave blank to
                            keep current)</label>
                        <input type="password" wire:model="password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter new password">
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    @if ($password)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New
                                Password</label>
                            <input type="password" wire:model="password_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Confirm new password">
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 disabled:bg-gray-400 transition">
                            <span wire:loading.remove wire:target="update">Update User</span>
                            <span wire:loading wire:target="update">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
</div>  