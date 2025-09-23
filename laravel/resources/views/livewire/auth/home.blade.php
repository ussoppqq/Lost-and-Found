@extends('layouts.app')



@section('content')

    {{-- ========== HERO (edge-to-edge inside container) ========== --}}
    <section class="-mx-4 md:-mx-8">
        <div class="relative">
            <img
                src="{{ asset('images/hero-bogor.jpg') }}"
                alt="Kebun Raya Bogor"
                class="w-full h-[50vh] md:h-[70vh] object-cover">
            <div class="absolute inset-0 bg-black/20"></div>

            {{-- Search overlay --}}
            <div class="absolute inset-0 flex items-center justify-center px-4">
                <form action="{{ url('/search') }}" method="GET"
                      class="w-full max-w-2xl flex bg-white/90 backdrop-blur rounded-full overflow-hidden shadow">
                    <input type="text" name="q" placeholder="Search lost/found items…"
                           class="flex-1 px-5 py-3 bg-transparent outline-none text-sm" />
                    <button class="px-5 py-3 text-sm font-medium hover:bg-gray-100">Search</button>
                </form>
            </div>
        </div>
    </section>

    {{-- quick links strip (optional) --}}
    <div class="mt-6 flex items-center justify-between border-b pb-3">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" class="h-7" alt="Logo">
            <span class="font-semibold tracking-wide">KEBUN RAYA BOGOR</span>
        </div>
        <div class="hidden md:flex items-center gap-8 text-xs tracking-widest">
            <a href="{{ url('/maps') }}" class="hover:text-green-700">MAPS</a>
            <a href="{{ url('/found') }}" class="hover:text-green-700">FOUND</a>
            <a href="{{ url('/report') }}" class="hover:text-green-700">FIND</a>
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="hover:text-green-700">LOGIN</a>
            @else
                <a href="{{ url('/login') }}" class="hover:text-green-700">LOGIN</a>
            @endif
        </div>
    </div>

    {{-- ========== LOST / FOUND CARDS ========== --}}
    <section class="py-12">
        <h2 class="text-center text-xl md:text-2xl tracking-[0.25em] mb-8">
            KEBUN RAYA BOGOR
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 place-items-center">
            {{-- LOST --}}
            <a href="{{ url('/report') }}"
               class="group block w-full max-w-sm rounded-2xl overflow-hidden border shadow-sm hover:shadow-md">
                <div class="h-40 md:h-48 overflow-hidden">
                    <img src="{{ asset('images/lost-thumb.jpg') }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
                </div>
                <div class="p-5 text-center">
                    <span class="inline-block text-lg tracking-widest">LOST</span>
                </div>
            </a>

            {{-- FOUND --}}
            <a href="{{ url('/found') }}"
               class="group block w-full max-w-sm rounded-2xl overflow-hidden border shadow-sm hover:shadow-md">
                <div class="h-40 md:h-48 overflow-hidden">
                    <img src="{{ asset('images/found-thumb.jpg') }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
                </div>
                <div class="p-5 text-center">
                    <span class="inline-block text-lg tracking-widest">FOUND</span>
                </div>
            </a>
        </div>
    </section>

    {{-- ========== ANNOUNCEMENT / CONTENT BLOCK ========== --}}
    <section class="relative -mx-4 md:-mx-8">
        <div class="absolute inset-0 -z-10">
            <img src="{{ asset('images/bg-section.jpg') }}" class="w-full h-full object-cover" alt="">
        </div>

        <div class="px-4 md:px-8 py-12">
            <div class="bg-white/90 backdrop-blur border rounded-2xl shadow p-6 md:p-8 max-w-5xl mx-auto">
                <h3 class="text-lg font-semibold mb-3">Pengumuman</h3>
                <p class="text-gray-700 leading-relaxed">
                    Temukan informasi terbaru terkait barang hilang/temuan di area Kebun Raya Bogor.
                    Silakan gunakan form pencarian atau pilih menu <em>Lost</em>/<em>Found</em>.
                </p>

                <div class="mt-6 border rounded-xl p-4 bg-white">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span> helpdesk online
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="p-3 rounded bg-gray-50">
                            Halo! Butuh bantuan melaporkan barang hilang? Klik tombol “Report Lost Item”.
                        </div>
                        <div class="p-3 rounded bg-gray-50">
                            Barang temuan terbaru dipublikasikan di halaman “Found Items”.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
