<div class="flex h-screen">
    <div class="w-1/2 h-screen hidden md:block">
        <img src="{{ asset('storage/images/kebunraya.jpg') }}" class="w-full h-full object-cover">
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
        <div class="w-3/4 max-w-md">
            <div class="text-center mb-6">
                <a href="/">
                    <img src="{{ asset('storage/images/footer-logo.png') }}" class="mx-auto h-12 mb-4 hover:opacity-80 transition">
                </a>
                <h2 class="text-xl tracking-wide">WELCOME TO LOGIN</h2>
            </div>

            <div>
                <form wire:submit.prevent="login">
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

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
                        Sign In
                    </button>
                </form>

                <div class="flex justify-between mt-4 text-sm">
                    <a class="text-blue-600 hover:underline">Forgot password?</a>
                    <a class="text-blue-600 hover:underline">Haven’t login? Register</a>
                </div>
            </div>
        </div>
    </div>
</div>