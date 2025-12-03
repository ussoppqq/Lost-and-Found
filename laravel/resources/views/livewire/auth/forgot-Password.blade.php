<div class="min-h-screen bg-cover bg-center flex items-center justify-center" 
     style="background-image: url('{{ asset('storage/images/location-bogor.jpg') }}');">

    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <a href="/">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Kebun Raya"
                     class="mx-auto h-12 mb-3 hover:opacity-80 transition">
            </a>
            <h2 class="text-lg font-bold text-gray-700">FORGOT PASSWORD</h2>
            @if($step == 1)
                <p class="text-sm text-gray-500 mt-2">Enter your email to receive a recovery code</p>
            @elseif($step == 2)
                <p class="text-sm text-gray-500 mt-2">Enter the 6-digit recovery code sent to your email</p>
            @elseif($step == 3)
                <p class="text-sm text-gray-500 mt-2">Enter your new password</p>
            @endif
        </div>

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Messages --}}
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Step 1: Email Input --}}
        @if($step == 1)
            <form wire:submit.prevent="submit" class="space-y-5">
                {{-- Email --}}
                <div>
                    <label for="email" class="block font-medium text-gray-700 mb-1">Email Address *</label>
                    <input id="email" type="email" wire:model="email"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter your email address">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-between items-center gap-3">
                    <a href="/login" wire:navigate
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center flex-1">
                        Back to Login
                    </a>

                    <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition flex-1">
                        <span wire:loading.remove wire:target="submit">Send Recovery Code</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                    </button>
                </div>
            </form>
        @endif

        {{-- Step 2: Recovery Code Input --}}
        @if($step == 2)
            <form wire:submit.prevent="verifyRecoveryCode" class="space-y-5">
                {{-- Recovery Code --}}
                <div>
                    <label for="recovery_code" class="block font-medium text-gray-700 mb-1">Recovery Code *</label>
                    <input id="recovery_code" type="text" wire:model="recovery_code"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-center text-2xl tracking-widest"
                           placeholder="000000"
                           maxlength="6">
                    @error('recovery_code')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror

                    <div class="mt-2 p-2 bg-blue-100 border border-blue-300 rounded text-sm text-blue-700">
                        Recovery code sent to <strong>{{ $email }}</strong>
                        <br>Code expires in 10 minutes.
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-between items-center gap-3">
                    <button type="button" wire:click="resetForm"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center flex-1">
                        Start Over
                    </button>

                    <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition flex-1">
                        <span wire:loading.remove wire:target="verifyRecoveryCode">Verify Code</span>
                        <span wire:loading wire:target="verifyRecoveryCode">Verifying...</span>
                    </button>
                </div>
            </form>
        @endif

        {{-- Step 3: New Password Input --}}
        @if($step == 3)
            <form wire:submit.prevent="resetPassword" class="space-y-5">
                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block font-medium text-gray-700 mb-1">New Password *</label>
                    <input id="new_password" type="password" wire:model="new_password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter new password">
                    @error('new_password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="new_password_confirmation" class="block font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <input id="new_password_confirmation" type="password" wire:model="new_password_confirmation"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Confirm new password">
                </div>

                {{-- Buttons --}}
                <div class="flex justify-between items-center gap-3">
                    <button type="button" wire:click="resetForm"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center flex-1">
                        Cancel
                    </button>

                    <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition flex-1">
                        <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                        <span wire:loading wire:target="resetPassword">Resetting...</span>
                    </button>
                </div>
            </form>
        @endif

        {{-- Additional Info --}}
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Remember your password? 
                <a href="/login" wire:navigate class="text-green-600 hover:text-green-700 font-medium">Login here</a>
            </p>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('reset-form-delayed', () => {
        setTimeout(() => {
            $wire.call('resetForm');
        }, 5000); // Reset after 5 seconds
    });
</script>
@endscript