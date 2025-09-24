<div class="min-h-screen bg-cover bg-center flex items-center justify-center"
     style="background-image: url('{{ asset('storage/images/location-bogor.jpg') }}');">

    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Kebun Raya" class="mx-auto h-12 mb-3">
            <h2 class="text-lg font-bold text-gray-700">WELCOME TO FORGOT PASSWORD</h2>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" wire:model="email" required autofocus
                       class="">
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
