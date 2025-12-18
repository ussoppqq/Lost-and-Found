<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Profile Dashboard' }} - Lost & Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden lg:block">
            <div class="h-full flex flex-col">
                {{-- Logo/Brand --}}
                <div class="p-6 border-b border-gray-200">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900">Lost & Found</span>
                    </a>
                </div>

                {{-- User Avatar & Info --}}
                <div class="p-6 border-b border-gray-200">
                    @php
                        $user = auth()->user();
                        $initials = collect(explode(' ', $user->full_name ?? 'User'))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->join('');
                    @endphp
                    <div class="flex flex-col items-center text-center">
                        <div class="relative group">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm mb-3">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-800 font-bold text-xl border-2 border-gray-200 shadow-sm mb-3">
                                    {{ $initials }}
                                </div>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $user->full_name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Navigation Menu --}}
                <nav class="flex-1 p-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('home') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-medium bg-gray-800 text-white rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tracking.index') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Tracking
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lost-items') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Lost Items
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- Logout Button --}}
                <div class="p-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Mobile Header --}}
            <div class="lg:hidden bg-white border-b border-gray-200 sticky top-0 z-10" x-data="{ mobileMenuOpen: false }">
                <div class="flex items-center justify-between p-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900">Lost & Found</span>
                    </a>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition class="border-t border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        @php
                            $user = auth()->user();
                            $initials = collect(explode(' ', $user->full_name ?? 'User'))
                                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                ->join('');
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-800 font-bold border-2 border-gray-200">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $user->full_name }}</h3>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <nav class="p-4">
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('home') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium bg-gray-800 text-white rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tracking.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Tracking
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('lost-items') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Lost Items
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="p-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
