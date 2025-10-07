<div>
@if ($showModal)
    <!-- Backdrop dengan blur effect -->
    <div class="fixed inset-0 bg-opacity-60 transition-opacity z-40 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md my-8 transform transition-all">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Verify User</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">User: <strong>{{ $selectedUser->full_name ?? '' }}</strong>
                    </p>
                    <p class="text-sm text-gray-600 mb-4">Phone: <strong>{{ $phone_number }}</strong></p>
                </div>

                {{-- OTP Success Message --}}
                @if (session()->has('otp_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        {{ session('otp_success') }}
                    </div>
                @endif

                {{-- OTP Verification --}}
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-3">Send OTP to User</h4>

                    @if (!$otpSent)
                        <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white py-2 rounded-lg transition">
                            <span wire:loading.remove wire:target="sendOtp">Send OTP to WhatsApp</span>
                            <span wire:loading wire:target="sendOtp">Sending...</span>
                        </button>
                    @else
                        <div class="space-y-3">
                            <div class="flex gap-2">
                                <input type="text" wire:model="otp"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter 6-digit OTP" maxlength="6">

                                <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                                    class="bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition">
                                    <span wire:loading.remove wire:target="sendOtp">Resend</span>
                                    <span wire:loading wire:target="sendOtp">Sending...</span>
                                </button>
                            </div>

                            @error('otp')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                            <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled"
                                class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-2 rounded-lg transition">
                                <span wire:loading.remove wire:target="verifyOtp">Verify OTP</span>
                                <span wire:loading wire:target="verifyOtp">Verifying...</span>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Manual Verification Option --}}
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">OR</span>
                        </div>
                    </div>
                </div>

                <button type="button" wire:click="manualVerify" wire:loading.attr="disabled"
                    class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-2 rounded-lg transition mb-4">
                    <span wire:loading.remove wire:target="manualVerify">Manual Verify (Skip OTP)</span>
                    <span wire:loading wire:target="manualVerify">Verifying...</span>
                </button>

                {{-- Close Button --}}
                <button type="button" wire:click="closeModal"
                    class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif
</div>