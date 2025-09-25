<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lost and Found' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900">

    {{-- === Transparent navbar that turns solid on scroll (no Login) === --}}
    <nav x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md' : 'bg-transparent'"
         class="fixed w-full top-0 left-0 z-50 transition-colors duration-300">
        <div class="container mx-auto flex items-center justify-between px-6 py-4">
            <a href="/" class="text-lg font-bold transition-colors"
               :class="scrolled ? 'text-gray-800' : 'text-white'">
                KEBUN RAYA BOGOR
            </a>
            <ul class="flex gap-8 font-medium">
                <li>
                    <a href="{{ url('/maps') }}" class="hover:text-green-500 transition-colors"
                       :class="scrolled ? 'text-gray-800' : 'text-white'">Map</a>
                </li>
                <li>
                    <a href="{{ url('/found-form') }}" class="hover:text-green-500 transition-colors"
                       :class="scrolled ? 'text-gray-800' : 'text-white'">Found / Lost</a>
                </li>
                {{-- no Login link --}}
            </ul>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container mx-auto pt-24">
        {{ $slot }}
    </main>

    {{-- === Footer (unchanged) === --}}
    <footer class="bg-white">
        <div class="mx-auto max-w-7xl px-6 sm:px-8">
            {{-- Top area --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-10 py-10">

                {{-- Logo + Socials --}}
                <div>
                    <img src="{{ asset('storage/images/logo/logo.png') }}" 
                         alt="Kebun Raya Bogor" 
                         class="h-8 w-auto mb-4">

                    <div class="flex items-center gap-4">
                        <a href="#" aria-label="Facebook"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22 12a10 10 0 1 0-11.6 9.87v-6.99H7.9V12h2.5V9.8c0-2.46 1.47-3.82 3.72-3.82 1.08 0 2.22.19 2.22.19v2.44h-1.25c-1.23 0-1.61.76-1.61 1.54V12h2.74l-.44 2.88h-2.3v6.99A10 10 0 0 0 22 12z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.53 3h3.02l-6.6 7.55L22 21h-6.52l-4.56-5.9L5.7 21H2.67l7.1-8.13L2 3h6.64l4.13 5.5L17.53 3zm-1.14 16h1.67L7.7 4.99H5.96L16.39 19z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 7.2A4.8 4.8 0 1 0 12 16.8 4.8 4.8 0 0 0 12 7.2zm0 7.9a3.1 3.1 0 1 1 0-6.2 3.1 3.1 0 0 1 0 6.2z" />
                                <path d="M17.3 2H6.7A4.7 4.7 0 0 0 2 6.7v10.6A4.7 4.7 0 0 0 6.7 22h10.6A4.7 4.7 0 0 0 22 17.3V6.7A4.7 4.7 0 0 0 17.3 2zM20.3 17.3c0 1.66-1.34 3-3 3H6.7c-1.66 0-3-1.34-3-3V6.7c0-1.66 1.34-3 3-3h10.6c1.66 0 3 1.34 3 3v10.6z" />
                                <circle cx="17.7" cy="6.3" r="1.2" />
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6.94 8.88H4.19V20h2.75V8.88zM5.56 7.55a1.6 1.6 0 1 0 0-3.2 1.6 1.6 0 0 0 0 3.2zM20 20h-2.74v-5.62c0-1.34-.03-3.07-1.87-3.07-1.87 0-2.16 1.46-2.16 2.97V20H10.5V8.88h2.63v1.51h.04c.37-.7 1.29-1.44 2.66-1.44 2.85 0 3.37 1.88 3.37 4.33V20z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Tagline + links + contact unchanged … --}}
                {{-- [Keep the rest of your footer exactly as it was] --}}
                {{-- ... --}}
            </div>

            <div class="border-t border-slate-200"></div>

            <div class="flex flex-col sm:flex-row items-center justify-between py-4 text-xs text-slate-400 gap-4">
                <p>© 2024 YourBrand. All rights reserved.</p>
                <ul class="flex items-center gap-8">
                    <li><a href="#" class="hover:text-slate-600">Cookie Policy</a></li>
                    <li><a href="#" class="hover:text-slate-600">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-slate-600">Terms of Service</a></li>
                </ul>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
    <script defer src="https://unpkg.com/alpinejs"></script>
</body>
</html>
