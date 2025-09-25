<x-layouts.app>
    {{-- ========== HERO ========== --}}
    <section class="relative w-full h-screen">
        <div class="absolute inset-0">
            <video src="{{ asset('storage/images/Video-Landing-Page-Unit-Bogor.mp4') }}" autoplay muted loop playsinline
                class="absolute top-0 left-0 w-full h-full object-cover">
            </video>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        {{-- Search overlay --}}
        <div class="relative z-10 flex items-center justify-center h-full px-4">
            <form action="{{ url('/search') }}" method="GET"
                class="w-full max-w-2xl flex bg-white/90 backdrop-blur rounded-full overflow-hidden shadow">
                <input type="text" name="q" placeholder="Search lost/found items…"
                    class="flex-1 px-5 py-3 bg-transparent outline-none text-sm" />
                <button class="px-5 py-3 text-sm font-medium hover:bg-gray-100">Search</button>
            </form>
        </div>
    </section>


    {{-- ========== LOST / FOUND CARDS ========== --}}
    <section class="py-12 container mx-auto">
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
    <section class="relative -mx-4 md:-mx-8 container mx-auto">
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
</x-layouts.app>