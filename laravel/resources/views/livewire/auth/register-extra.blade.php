<div>
    <div class="flex h-screen">
        <!-- Left: Gambar -->
        <div class="w-1/2 h-screen hidden md:block">
            <img src="/kebunraya.jpg" class="w-full h-full object-cover">
        </div>

        <!-- Right: Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
            <div class="w-3/4 max-w-md">
                <div class="text-center mb-6">
                    <a href="/">
                        <img src="/footer-logo.png" class="mx-auto h-12 mb-4 hover:opacity-80 transition">
                    </a>
                    <h2 class="text-xl tracking-wide">COMPLETE YOUR ACCOUNT</h2>
                </div>

                <div class="border-gray-300 rounded-lg p-6 bg-100">
                    {{-- Alert untuk success message --}}
                    @if (session()->has('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="register">
                        {{-- Phone Number --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Phone Number</label>
                            <input type="text" wire:model="phone"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Contoh: 6281234567890">
                            @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- OTP --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">OTP Verification</label>
                            <div class="flex space-x-2">
                                <input type="text" wire:model="otp"
                                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="Masukkan 6 digit OTP" maxlength="6">

                                <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded transition">
                                    <span wire:loading.remove wire:target="sendOtp">
                                        Kirim OTP
                                    </span>
                                    <span wire:loading wire:target="sendOtp">
                                        Mengirim...
                                    </span>
                                </button>
                            </div>

                            @error('otp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                            {{-- Status OTP --}}
                            @if (session()->has('success'))
                                <div class="mt-2 p-2 bg-green-100 border border-green-300 rounded text-sm text-green-700">
                                    ✅ {{ session('success') }}
                                    <br>Kode berlaku selama 5 menit.
                                </div>
                            @endif
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                            <input type="email" wire:model="email"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your email">
                            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Password</label>
                            <input type="password" wire:model="password"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Enter your password">
                            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Confirm your password">
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-2 rounded transition">
                            <span wire:loading.remove wire:target="register">Register</span>
                            <span wire:loading wire:target="register">Mendaftarkan...</span>
                        </button>
                    </form>

                    <div class="flex justify-between mt-3 text-sm">
                        <a href="/login" class="text-blue-600 hover:underline">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>