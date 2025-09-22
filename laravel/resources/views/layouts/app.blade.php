<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lost & Found Kebun Raya')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>


<body class="bg-gray-50 text-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
                <span class="font-bold text-lg">KEBUN RAYA</span>
            </div>
            <ul class="flex space-x-6">
                <li><a href="#" class="hover:text-green-600">MAPS</a></li>
                <li><a href="/found-form" class="hover:text-green-600">FOUND/LOST</a></li>
                <li><a href="#" class="hover:text-green-600">LOGIN</a></li>
            </ul>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container mx-auto py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-200 mt-12">
        <div class="container mx-auto px-4 py-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h2 class="font-bold mb-2">About Us</h2>
                <p>Building amazing digital experiences that connect people and transform businesses.</p>
            </div>
            <div>
                <h2 class="font-bold mb-2">Contact</h2>
                <p>Email: hello@yourbrand.com</p>
                <p>Phone: (555) 123-4567</p>
                <p>Address: 123 Business St, City, State</p>
            </div>
            <div>
                <h2 class="font-bold mb-2">Follow Us</h2>
                <div class="flex space-x-3">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center py-3 border-t border-gray-700 text-sm">
            © {{ date('Y') }} Kebun Raya Bogor. All rights reserved.
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>

</html>