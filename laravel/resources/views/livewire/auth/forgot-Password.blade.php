<div class="min-h-screen bg-cover bg-center flex items-center justify-center" 
     style="background-image: url('{{ asset('storage/images/location-bogor.jpg') }}');">

    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <a href="/">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Kebun Raya" 
                     class="mx-auto h-12 mb-3 hover:opacity-80 transition">
            </a>
            <h2 class="text-lg font-bold text-gray-700">FORGOT PASSWORD</h2>
            <p class="text-sm text-gray-500 mt-2">Enter your email to receive a password reset link</p>
        </div>

        {{-- Success Message --}}
        @if ($emailSent)
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium">Reset link sent!</p>
                        <p class="text-sm mt-1">Please check your email inbox (and spam folder) for the password reset link.</p>
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

        {{-- Forgot Password Form --}}
        <form wire:submit.prevent="submit" class="space-y-5">
            {{-- Email --}}
            <div>
                <label for="email" class="block font-medium text-gray-700 mb-1">Email Address *</label>
                <input id="email" type="email" wire:model="email" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Enter your email address"
                       {{ $emailSent ? 'disabled' : '' }}>
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
                
                @if (!$emailSent)
                    <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition flex-1">
                        <span wire:loading.remove wire:target="submit">Send Reset Link</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                    </button>
                @else
                    <button type="button" wire:click="resetForm"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex-1">
                        Send Again
                    </button>
                @endif
            </div>
        </form>

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