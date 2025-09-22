<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title','Kebun Raya Bogor')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- If you use Livewire, keep these --}}
    @stack('styles') @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-white">

    {{-- Navbar lives in the same folder: layouts/ --}}
    @include('layouts.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer lives in the same folder: layouts/ --}}
    @include('layouts.footer')

    @livewireScripts @stack('scripts')
</body>
</html>
