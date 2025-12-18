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
        body {
            font-family: 'Times New Roman', Times, serif;
            font-weight: 400;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-dmserif-title,
        label {
            font-family: 'Times New Roman', Times, serif;
            font-weight: 700;
        }

        .font-openSans {
            font-family: 'Times New Roman', Times, serif;
        }

        /* Biar anchor target gak ketutup navbar fixed */
        section[id],
        div[id] {
            scroll-margin-top: 6rem;
        }

        @media (min-width: 1024px) {

            section[id],
            div[id] {
                scroll-margin-top: 7rem;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-white">

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
                <img
                    src="{{ asset('storage/images/logo/logowhite.png') }}"
                    alt="Logo White"
                    x-show="!scrolled"
                    class="h-10 md:h-12 lg:h-14 w-auto transition-opacity duration-300"
                >
                <img
                    src="{{ asset('storage/images/logo/logodark.png') }}"
                    alt="Logo Dark"
                    x-show="scrolled"
                    class="h-10 md:h-12 lg:h-14 w-auto transition-opacity duration-300"
                >
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ url('/#lostandfound') }}"
                   :class="scrolled ? 'text-gray-700 hover:text-gray-900' : 'text-white hover:text-gray-200 drop-shadow'"
                   class="font-medium text-sm lg:text-base transition-colors duration-300">
                    Lost &amp; Found
                </a>

                <a href="{{ route('lost-items') }}"
                   :class="scrolled ? 'text-gray-700 hover:text-gray-900' : 'text-white hover:text-gray-200 drop-shadow'"
                   class="font-medium text-sm lg:text-base transition-colors duration-300">
                    Lost Items
                </a>

                <a href="{{ url('/tracking') }}"
                   :class="scrolled ? 'text-gray-700 hover:text-gray-900' : 'text-white hover:text-gray-200 drop-shadow'"
                   class="font-medium text-sm lg:text-base transition-colors duration-300">
                    Tracking
                </a>

                @auth
                    {{-- Avatar Dropdown --}}
                    <div class="relative" x-data="{ dropdown: false }">
                        <button @click="dropdown = !dropdown" class="flex items-center focus:outline-none group relative">
                            @php
                                $user = auth()->user();
                                $initials = collect(explode(' ', $user->full_name ?? 'User'))
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->join('');
                            @endphp
                            @if($user->avatar)
                                <img
                                    src="{{ Storage::url($user->avatar) }}"
                                    alt="Avatar"
                                    class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-white shadow-lg object-cover"
                                >
                            @else
                                <div class="h-9 w-9 lg:h-10 lg:w-10 rounded-full border-2 border-white shadow-lg bg-gray-200 flex items-center justify-center text-gray-800 font-bold text-xs lg:text-sm">
                                    {{ $initials }}
                                </div>
                            @endif
                        </button>
                        <div
                            x-show="dropdown"
                            @click.away="dropdown = false"
                            x-transition
                            class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl py-2 border"
                        >
                            @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Dashboard
                                    </div>
                                </a>
                            @endif
                            <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </div>
                            </a>
                            <hr class="my-1 mx-2 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </div>
                                </button>
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

            {{-- Avatar Button Mobile --}}
            <div class="md:hidden">
                @auth
                    <button @click="open = true" class="flex items-center focus:outline-none">
                        @php
                            $user = auth()->user();
                            $initials = collect(explode(' ', $user->full_name ?? 'User'))
                                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                ->join('');
                        @endphp
                        @if($user->avatar)
                            <img
                                src="{{ Storage::url($user->avatar) }}"
                                alt="Avatar"
                                class="h-10 w-10 rounded-full border-2 border-white shadow-lg object-cover hover:border-gray-300 transition"
                            >
                        @else
                            <div class="h-10 w-10 rounded-full border-2 border-white shadow-lg bg-gray-200 flex items-center justify-center text-gray-800 font-bold text-sm hover:border-gray-300 transition">
                                {{ $initials }}
                            </div>
                        @endif
                    </button>
                @else
                    <button
                        @click="open = true"
                        :class="scrolled ? 'text-gray-700' : 'text-white drop-shadow'"
                        class="focus:outline-none transition-colors duration-300"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                @endauth
            </div>
        </div>

        {{-- Sidebar Mobile --}}
        <div x-show="open" class="fixed inset-0 z-40 flex md:hidden" x-transition>
            <div class="ml-auto w-72 bg-white h-full shadow-2xl overflow-y-auto border-l border-gray-100 flex flex-col">
                @auth
                    {{-- Profile Header (replaces logo) --}}
                    <div class="p-6 border-b border-gray-100" x-data="{ profileOpen: false }">
                        <div class="flex items-center justify-end mb-4">
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <button @click="profileOpen = !profileOpen" class="w-full flex items-center space-x-3 hover:bg-gray-50 p-3 rounded-lg transition">
                            @php
                                $user = auth()->user();
                                $initials = collect(explode(' ', $user->full_name ?? 'User'))
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->join('');
                            @endphp
                            @if($user->avatar)
                                <img
                                    src="{{ Storage::url($user->avatar) }}"
                                    alt="Avatar"
                                    class="h-14 w-14 rounded-full border-2 border-gray-300 object-cover flex-shrink-0"
                                >
                            @else
                                <div class="h-14 w-14 rounded-full border-2 border-gray-300 bg-gray-200 flex items-center justify-center text-gray-800 font-bold text-base flex-shrink-0">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="flex-1 text-left">
                                <p class="font-semibold text-gray-900 text-base">{{ $user->full_name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <svg class="h-5 w-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': profileOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Profile Dropdown Menu --}}
                        <div x-show="profileOpen" x-collapse class="mt-2 space-y-1">
                            @if($user->isAdmin() || $user->isModerator())
                                <a href="{{ route('admin.dashboard') }}" @click="open = false" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Dashboard
                                    </div>
                                </a>
                            @endif
                            <a href="{{ url('/profile') }}" @click="open = false" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </div>
                            </a>
                            <hr class="my-2 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Header with Logo for Guest --}}
                    <div class="flex items-center justify-between border-b p-6 pb-4">
                        <img src="{{ asset('storage/images/logo/logodark.png') }}" alt="Logo" class="h-10 w-auto">
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endauth

                {{-- Navigation Menu --}}
                <div class="p-6 space-y-2 flex-1">
                    <a href="#lostandfound" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">
                        Lost &amp; Found
                    </a>
                    <a href="{{ route('lost-items') }}" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">
                        Lost Items
                    </a>
                    <a href="{{ url('/tracking') }}" @click="open = false" class="block py-3 text-gray-700 hover:text-gray-900 font-medium border-b border-gray-100">
                        Tracking
                    </a>

                    @guest
                        <a href="{{ route('login') }}"
                           class="block mt-6 px-6 py-3 bg-gray-900 text-white rounded-xl text-center hover:bg-gray-800 transition font-medium">
                            Login
                        </a>
                    @endguest
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
                <img
                    src="{{ asset('storage/images/krb3.png') }}"
                    alt="Mascots Left"
                    class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none"
                >
                {{-- Right mascots --}}
                <img
                    src="{{ asset('storage/images/krb2.png') }}"
                    alt="Mascots Right"
                    class="h-24 md:h-32 lg:h-36 object-contain select-none pointer-events-none"
                >
            </div>
        </div>
    </div>
    {{-- ===== END MASCOTS BAR ===== --}}

    {{-- NEW FOOTER DESIGN --}}
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

            <!-- Description 
            <p class="text-gray-700 leading-relaxed text-sm lg:text-base max-w-2xl">
                Kebun Raya Bogor didirikan pada tahun 1817 oleh pemerintah Hindia Belanda dan menjadi pusat penelitian utama pertanian serta hortikultura di Asia Tenggara.
                Awalnya sebagai kebun percobaan, tempat ini kemudian berkembang menjadi pusat ilmu pengetahuan dan melahirkan berbagai institusi botani penting di Indonesia.
            </p> -->

    </footer>

    <footer class="bg-white-100 border-t border-black-200">
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
