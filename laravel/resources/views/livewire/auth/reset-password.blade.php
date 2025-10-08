<div class="min-h-screen bg-cover bg-center flex items-center justify-center" 
     style="background-image: url('{{ asset('storage/images/location-bogor.jpg') }}');">

    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <a href="/">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Kebun Raya" 
                     class="mx-auto h-12 mb-3 hover:opacity-80 transition">
            </a>
            <h2 class="text-lg font-bold text-gray-700">RESET PASSWORD</h2>
            <p class="text-sm text-gray-500 mt-2">Enter your new password</p>
        </div>

        {{-- Error Messages --}}
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Reset Password Form --}}
        <form wire:submit.prevent="submit" class="space-y-5">
            {{-- Email (readonly) --}}
            <div>
                <label for="email" class="block font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" wire:model="email" readonly
                       class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-2 cursor-not-allowed">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block font-medium text-gray-700 mb-1">New Password *</label>
                <input id="password" type="password" wire:model="password" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Enter new password (min. 8 characters)">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">Confirm Password *</label>
                <input id="password_confirmation" type="password" wire:model="password_confirmation" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Confirm your new password">
                @error('password_confirmation')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Requirements --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm font-medium text-blue-900 mb-2">Password Requirements:</p>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Minimum 8 characters
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Both passwords must match
                    </li>
                </ul>
            </div>

            {{-- Submit Button --}}
            <button type="submit" wire:loading.attr="disabled"
                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400 transition">
                <span wire:loading.remove wire:target="submit">Reset Password</span>
                <span wire:loading wire:target="submit">Resetting...</span>
            </button>
        </form>

        {{-- Additional Info --}}
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Remember your password? 
                <a href="/login" wire:navigate class="text-green-600 hover:text-green-700 font-medium">Login here</a>
            </p>
        </div>
    </div>
</div>