<div>
@if ($showModal)
    <!-- Backdrop dengan blur effect -->
    <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg my-8 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                    @if($pendingUserId && !$skip_otp_verification)
                        OTP Verification
                    @else
                        Create New User
                    @endif
                </h3>

                {{-- OTP Success Message --}}
                @if (session()->has('otp_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        {{ session('otp_success') }}
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    {{-- Full Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" wire:model="full_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter full name">
                        @error('full_name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                        <input type="email" wire:model="email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter email">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone Number --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                        <input type="text" wire:model="phone_number"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Example: 6281234567890">
                        @error('phone_number')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Format: 62 followed by 9-13 digits</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" wire:model="password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter password">
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                        <input type="password" wire:model="password_confirmation"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Confirm password">
                    </div>

                    {{-- Skip OTP Verification Checkbox --}}
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="skip_otp_verification"
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Skip OTP verification (Manual verification by
                                admin)</span>
                        </label>
                    </div>

                    {{-- OTP Section (jika sudah dibuat user) --}}
                    @if ($pendingUserId && !$skip_otp_verification)
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-900 mb-3">OTP Verification Required</h4>

                            <div class="flex gap-2 mb-3">
                                <input type="text" wire:model="otp"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter 6-digit OTP" maxlength="6">

                                <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition">
                                    <span wire:loading.remove wire:target="sendOtp">Resend</span>
                                    <span wire:loading wire:target="sendOtp">Sending...</span>
                                </button>
                            </div>

                            @error('otp')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                            <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled"
                                class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-2 rounded-lg transition mt-2">
                                <span wire:loading.remove wire:target="verifyOtp">Verify & Complete</span>
                                <span wire:loading wire:target="verifyOtp">Verifying...</span>
                            </button>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </button>

                        @if (!$pendingUserId)
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition">
                                <span wire:loading.remove wire:target="save">Create User</span>
                                <span wire:loading wire:target="save">Creating...</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
</div>