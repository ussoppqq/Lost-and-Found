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
                <form wire:submit.prevent="register">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter your email">
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 text-gray-700">Password</label>
                        <input type="password" wire:model="password"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter your password">
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 text-gray-700">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Confirm your password">
                    </div>

                    <button type="submit" class="w-full bg-black text-white py-2 rounded">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
