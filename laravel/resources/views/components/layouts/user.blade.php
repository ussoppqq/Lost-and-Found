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

{{-- bg dibuat putih biar sama kayak user layout --}}
<body class="bg-white text-gray-900">

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
                <a href="{{ url('/') }}"
                   class="font-medium text-sm lg:text-base text-gray-700 hover:text-gray-900 transition-colors duration-300">
                    Lost &amp; Found
                </a>
                <a href="{{ route('lost-items') }}"
                   class="font-medium text-sm lg:text-base text-gray-700 hover:text-gray-900 transition-colors duration-300">
                    Lost Items
                </a>
                <a href="{{ url('/tracking') }}"
                   class="font-medium text-sm lg:text-base text-gray-700 hover:text-gray-900 transition-colors duration-300">
                    Tracking
                </a>

                @auth
                    {{-- Avatar Dropdown --}}
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="flex items-center focus:outline-none">
                            @php
                                $user = auth()->user();
                                $initials = collect(explode(' ', $user->full_name ?? 'User'))
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->join('');
                            @endphp
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                                     class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-gray-200 shadow-sm object-cover">
                            @else
                                <div class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-gray-200 shadow-sm bg-gray-200 flex items-center justify-center text-gray-800 font-bold text-xs lg:text-sm">
                                    {{ $initials }}
                                </div>
                            @endif
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
                    <a href="{{ url('/') }}" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Lost &amp; Found</a>
                    <a href="{{ route('lost-items') }}" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Lost Items</a>
                    <a href="{{ url('/tracking') }}" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">Tracking</a>

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
    <main class="pt-24 bg-white min-h-screen">
        {{ $slot }}
    </main>

    {{-- MASCOTS BAR --}}
    
        <div class="container mx-auto">
            <div class="flex justify-between items-end px-6 md:px-12 py-4">
                <img src="{{ asset('storage/images/krb3.png') }}" alt="Mascots Left"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
                <img src="{{ asset('storage/images/krb2.png') }}" alt="Mascots Right"
                     class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none">
            </div>
        </div>
    {{-- ===== END MASCOTS BAR ===== --}}

    

    {{-- FOOTER (SAMA SEPERTI user.blade.php) --}}
    
    
    <footer class="bg-white border-t border-black-200">
        <div class="container mx-auto px-4 md:px-8 py-12 lg:py-16 flex flex-col items-center text-center gap-8">

            <!-- Main Logo -->
            <div class="flex justify-center">
                <img
                    src="{{ asset('storage/images/logo/logodark.png') }}"
                    alt="Kebun Raya"
                    class="h-12 sm:h-16 lg:h-20 w-auto object-contain"
                >
            </div>

            <!-- Sub-brands row (4 logo cabang) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-10 gap-y-8 items-center justify-items-center">
                <a href="#" aria-label="Kebun Raya Bogor" class="opacity-90 hover:opacity-100 transition">
                    <img
                        src="{{ asset('storage/images/logo/kebunraya-bogor.png') }}"
                        alt="Kebun Raya Bogor"
                        class="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                    />
                </a>

                <a href="#" aria-label="Kebun Raya Cibodas" class="opacity-90 hover:opacity-100 transition">
                    <img
                        src="{{ asset('storage/images/logo/kebunraya-cibodas.png') }}"
                        alt="Kebun Raya Cibodas"
                        class="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                    />
                </a>

                <a href="#" aria-label="Kebun Raya Purwodadi" class="opacity-90 hover:opacity-100 transition">
                    <img
                        src="{{ asset('storage/images/logo/kebunraya-purwodadi.png') }}"
                        alt="Kebun Raya Purwodadi"
                        class="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                    />
                </a>

                <a href="#" aria-label="Kebun Raya Bali" class="opacity-90 hover:opacity-100 transition">
                    <img
                        src="{{ asset('storage/images/logo/kebunraya-bali.png') }}"
                        alt="Kebun Raya Bali"
                        class="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                    />
                </a>
            </div>



            <!-- Social Media Icons -->
            <div class="flex space-x-3 justify-center">
                <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-green-600 hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-green-600 hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743A11.65 11.65 0 011.8 6.071a4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-green-600 hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z"/>
                    </svg>
                </a>
                <a href="#" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-700 hover:bg-green-600 hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <footer class="bg-white border-t border-black-200">
        <div class="container mx-auto text-center py-4">
            <p class="text-gray-500 text-xs">
                © {{ date('Y') }} Lost &amp; Found Kebun Raya Bogor. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Scripts --}}
    @livewireScripts

</body>
</html>
