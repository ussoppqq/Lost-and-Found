<!DOCTYPE html>
<html lang="en" x-data="{ open: false }" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lost and Found' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Crimson+Text:wght@400;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

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

    {{-- NAVBAR with scroll effect --}}
    <nav 
        x-data="{ scrolled: false }" 
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > window.innerHeight * 0.7 })"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-transparent'"
        class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
    >
        <div class="container mx-auto flex items-center justify-between px-4 md:px-8 h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3 relative">
                <img src="{{ asset('storage/images/logo/logowhite.png') }}" alt="Logo White"
                     x-show="!scrolled" class="h-10 md:h-12 lg:h-14 w-auto transition-opacity duration-300">
                <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Logo Dark"
                     x-show="scrolled" class="h-10 md:h-12 lg:h-14 w-auto transition-opacity duration-300">
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ route('lost-form') }}"
                   :class="scrolled ? 'text-gray-700 hover:text-green-700' : 'text-white hover:text-green-200 drop-shadow'"
                   class="font-medium text-sm lg:text-base transition-colors duration-300">
                    Lost & Found
                </a>
                <a href="{{ url('/map') }}"
                   :class="scrolled ? 'text-gray-700 hover:text-green-700' : 'text-white hover:text-green-200 drop-shadow'"
                   class="font-medium text-sm lg:text-base transition-colors duration-300">
                    Map
                </a>

                @auth
                    {{-- Avatar Dropdown --}}
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="flex items-center focus:outline-none">
                            <img src="{{ asset('images/avatar.png') }}" alt="Avatar"
                                 class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-white shadow-lg">
                        </button>
                        <div x-show="dropdown" @click.away="dropdown = false" x-transition
                             class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl py-2 border">
                            <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       :class="scrolled ? 'bg-gray-800 text-white hover:bg-gray-900' : 'bg-white text-gray-800 hover:bg-gray-100'"
                       class="px-5 py-2 lg:px-6 lg:py-3 rounded-full transition-all duration-300 shadow-lg font-medium text-sm lg:text-base border border-transparent">
                        Login
                    </a>
                @endauth
            </div>

            {{-- Hamburger Mobile --}}
            <div class="md:hidden">
                <button @click="open = true" 
                        :class="scrolled ? 'text-gray-700' : 'text-white drop-shadow'"
                        class="focus:outline-none transition-colors duration-300">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Sidebar Mobile --}}
        <div x-show="open" class="fixed inset-0 z-40 flex md:hidden" x-transition>
            <div class="ml-auto w-72 bg-white h-full p-6 space-y-6 shadow-2xl overflow-y-auto">
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
                    <a href="{{ route('lost-form') }}" class="block py-3 text-gray-700 hover:text-green-700 font-medium border-b border-gray-100">Lost & Found</a>
                    <a href="{{ url('/map') }}" class="block py-3 text-gray-700 hover:text-green-700 font-medium border-b border-gray-100">Map</a>

                    @auth
                        <a href="{{ url('/profile') }}" class="block py-3 text-gray-700 hover:text-green-700 font-medium border-b border-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-3 text-gray-700 hover:text-green-700 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="block mt-6 px-6 py-3 bg-green-600 text-white rounded-xl text-center hover:bg-green-700 transition font-medium">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ===== MASCOTS BAR just above footer ===== --}}
    <div class="bg-white">
        <div class="container mx-auto">
            <div class="flex justify-between items-end px-6 md:px-12 py-2">
                {{-- Left mascots --}}
                <img src="{{ asset('storage/images/krb3.png') }}" alt="Mascots Left"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
                {{-- Right mascots --}}
                <img src="{{ asset('storage/images/krb2.png') }}" alt="Mascots Right"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
            </div>
        </div>
    </div>
    {{-- ===== END MASCOTS BAR ===== --}}

    {{-- NEW FOOTER DESIGN --}}
    <footer class="bg-gray-50 border-t border-gray-200">
        <div class="container mx-auto px-4 md:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                {{-- Logo & Description --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Kebun Raya Bogor" class="h-12 lg:h-16 w-auto">
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-8 text-sm lg:text-base max-w-md">
                        Building amazing digital experiences that connect people and transform businesses
                        through innovative technology solutions.
                    </p>

                    {{-- Social Media Icons --}}
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-green-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-green-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-green-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-800 text-white hover:bg-green-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-gray-800 font-semibold mb-6 text-lg">Quick Links</h4>
                    <nav class="space-y-3">
                        <a href="#" class="block text-gray-600 hover:text-green-600 transition-colors duration-200 text-sm lg:text-base">About Us</a>
                        <a href="#" class="block text-gray-600 hover:text-green-600 transition-colors duration-200 text-sm lg:text-base">Services</a>
                        <a href="#" class="block text-gray-600 hover:text-green-600 transition-colors duration-200 text-sm lg:text-base">Portfolio</a>
                        <a href="#" class="block text-gray-600 hover:text-green-600 transition-colors duration-200 text-sm lg:text-base">Blog</a>
                        <a href="#" class="block text-gray-600 hover:text-green-600 transition-colors duration-200 text-sm lg:text-base">Contact</a>
                    </nav>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h4 class="text-gray-800 font-semibold mb-6 text-lg">Contact</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:hello@yourbrand.com" class="text-gray-600 hover:text-green-600 transition-colors text-sm lg:text-base">
                                hello@yourbrand.com
                            </a>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-600 text-sm lg:text-base">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-600 text-sm lg:text-base leading-relaxed">123 Business St, City, State 12345</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- FOOTER --}}
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
