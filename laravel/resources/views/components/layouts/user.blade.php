<!DOCTYPE html>
<html lang="en" x-data="{ open: false }" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lost and Found' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Crimson+Text:wght@400;600;700&family=DM+Serif+Display:ital@0;1&display=swap"
        rel="stylesheet">

    <style>
        body { font-family: 'Crimson Text', serif; font-weight: 600; }
        h1, h2, h3, h4, h5, h6, .font-dmserif-title, label {
            font-family: 'DM Serif Display', serif; font-weight: 700;
        }
        .font-openSans { font-family: 'Open Sans', sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900">

    {{-- STATIC NAVBAR (No Scroll Effect) --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white shadow-md border-b border-gray-100 transition-all duration-300">
        <div class="container mx-auto flex items-center justify-between px-4 md:px-8 h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3 relative">
                <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Logo"
                     class="h-10 md:h-12 lg:h-14 w-auto">
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ route('lost-form') }}"
                   class="font-medium text-sm lg:text-base text-gray-700 hover:text-gray-900 transition-colors duration-300">
                    Lost & Found
                </a>
                <a href="{{ url('/tracking') }}"
                   class="font-medium text-sm lg:text-base text-gray-700 hover:text-gray-900 transition-colors duration-300">
                    Tracking
                </a>

                @auth
                    {{-- Avatar Dropdown --}}
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="flex items-center focus:outline-none">
                            <img src="{{ asset('images/avatar.png') }}" alt="Avatar"
                                 class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-gray-200 shadow-sm">
                        </button>
                        <div x-show="dropdown" @click.away="dropdown = false" x-transition
                             class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl py-2 border border-gray-100">
                            <a href="{{ url('/profile') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="px-5 py-2 lg:px-6 lg:py-3 rounded-full transition-all duration-300 bg-gray-900 text-white hover:bg-gray-800 shadow-md font-medium text-sm lg:text-base border border-transparent">
                        Login
                    </a>
                @endauth
            </div>

            {{-- Hamburger Mobile --}}
            <div class="md:hidden">
                <button @click="open = true"
                        class="text-gray-700 focus:outline-none transition-colors duration-300">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Sidebar Mobile --}}
        <div x-show="open" class="fixed inset-0 z-40 flex md:hidden" x-transition>
            <div class="ml-auto w-72 bg-white h-full p-6 space-y-6 shadow-2xl overflow-y-auto border-l border-gray-100">
                <div class="flex items-center justify-between border-b pb-4">
                    <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Logo" class="h-10 w-auto">
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <a href="{{ route('lost-form') }}" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Lost & Found</a>
                    <a href="{{ url('/map') }}" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Map</a>

                    @auth
                        <a href="{{ url('/profile') }}" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left py-3 text-gray-700 hover:text-gray-900 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="block mt-6 px-6 py-3 bg-gray-900 text-white rounded-xl text-center hover:bg-gray-800 transition font-medium">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="pt-24">
        {{ $slot }}
    </main>

    {{-- MASCOTS BAR --}}
    <div class="bg-white border-t border-gray-100">
        <div class="container mx-auto">
            <div class="flex justify-between items-end px-6 md:px-12 py-4">
                <img src="{{ asset('storage/images/krb3.png') }}" alt="Mascots Left"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
                <img src="{{ asset('storage/images/krb2.png') }}" alt="Mascots Right"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-50 border-t border-gray-200">
        <div class="container mx-auto px-4 md:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Logo" class="h-12 lg:h-16 w-auto">
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-8 text-sm lg:text-base max-w-md">
                        Building amazing digital experiences that connect people and transform businesses
                        through innovative technology solutions.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-gray-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-gray-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743A11.65 11.65 0 011.8 6.071a4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-gray-800 font-semibold mb-6 text-lg">Quick Links</h4>
                    <nav class="space-y-3">
                        <a href="#" class="block text-gray-600 hover:text-gray-900 text-sm lg:text-base">About Us</a>
                        <a href="#" class="block text-gray-600 hover:text-gray-900 text-sm lg:text-base">Services</a>
                        <a href="#" class="block text-gray-600 hover:text-gray-900 text-sm lg:text-base">Blog</a>
                        <a href="#" class="block text-gray-600 hover:text-gray-900 text-sm lg:text-base">Contact</a>
                    </nav>
                </div>

                <div>
                    <h4 class="text-gray-800 font-semibold mb-6 text-lg">Contact</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-700 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:hello@yourbrand.com"
                               class="text-gray-600 hover:text-gray-900 text-sm lg:text-base">hello@yourbrand.com</a>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-700 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-600 text-sm lg:text-base">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-700 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-600 text-sm lg:text-base leading-relaxed">123 Business St, City, State 12345</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <footer class="bg-gray-50 border-t border-gray-200 mt-16">
        <div class="container mx-auto px-4 md:px-8 py-12 lg:py-16 text-center">
            <p class="text-gray-500 text-sm">
                © 2025 Lost & Found. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Scripts --}}
    @livewireScripts

</body>
</html>
