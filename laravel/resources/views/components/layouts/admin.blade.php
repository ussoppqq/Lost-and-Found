<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200">
                <!-- Logo -->
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4 mb-8">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">🌿</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-semibold text-gray-900">Kebun Raya</p>
                                <p class="text-sm text-gray-500">
                                    {{ auth()->user()->isAdmin() ? 'Admin Panel' : 'Moderator Panel' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        @php
                            $user = auth()->user();
                            $isAdmin = $user->isAdmin();
                        @endphp

                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- Lost & Found -->
                        <a href="{{ route('admin.lost-found') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.lost-found*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Lost & Found
                        </a>

                        <!-- Items -->
                        <a href="{{ route('admin.items') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.items*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Items
                        </a>

                        <!-- Matches -->
                        <a href="{{ route('admin.matches') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.matches*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Matches
                        </a>

                        <!-- Categories -->
                        <a href="{{ route('admin.categories') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Categories
                        </a>

                        <!-- Users - HANYA ADMIN -->
                        @if($isAdmin)
                            <a href="{{ route('admin.users') }}"
                                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Users
                            </a>
                        @endif

                        <!-- Statistics -->
                        <a href="{{ route('admin.statistic') }}"
                            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.statistic*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Statistics
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <div class="sticky top-0 z-10 bg-white pl-1 pt-1 sm:pl-3 sm:pt-3">
                <div class="flex justify-between items-center px-4 sm:px-6 py-3 border-b border-gray-200">
                    <!-- Page title -->
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
                        <p class="text-sm text-gray-500">{{ $pageDescription ?? 'Welcome to admin panel' }}</p>
                    </div>

                    <!-- User menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-100">
                                <img class="w-8 h-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name ?? 'Admin') }}&background=1f2937&color=fff"
                                    alt="Profile">
                                <div class="text-left">
                                    <span class="text-sm font-medium text-gray-700 block">{{ auth()->user()->full_name ?? 'Admin' }}</span>
                                    <span class="text-xs text-gray-500">
                                        {{ auth()->user()->isAdmin() ? 'Admin' : 'Moderator' }}
                                    </span>
                                </div>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>