@vite('resources/css/app.css')

<div class="min-h-screen bg-cover bg-center flex items-center justify-center" 
     style="background-image: url('{{ asset('storage/images/location-bogor.jpg') }}');">

    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Kebun Raya" class="mx-auto h-12 mb-3">
            <h2 class="text-lg font-bold text-gray-700">WELCOME TO FORGOT PASSWORD</h2>
        </div>

        {{-- Forgot Password Form --}}
        <form method="POST"  class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>

            {{-- Buttons --}}
            <div class="flex justify-between items-center">
                <a  class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
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
