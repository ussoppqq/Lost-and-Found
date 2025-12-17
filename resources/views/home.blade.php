<x-layouts.app>
    {{-- ========== HERO SECTION ========== --}}
    <section class="relative w-full h-screen overflow-hidden">
        <div class="absolute inset-0">
            <video src="{{ asset('storage/images/video.mp4') }}" autoplay muted loop playsinline
                class="absolute top-0 left-0 w-full h-full object-cover"></video>
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        {{-- ===== Search Overlay ===== --}}
        <div
            class="absolute bottom-8 md:bottom-12 lg:bottom-16 left-1/2 -translate-x-1/2 w-full max-w-xs sm:max-w-md md:max-w-lg lg:max-w-2xl px-4 z-20">
            <form id="heroForm" action="{{ url('/search') }}" method="GET"
                class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full shadow-2xl overflow-hidden h-12 sm:h-14 md:h-16 lg:h-[65px] border border-white/20">

                {{-- Search Icon --}}
                <div class="absolute left-4 lg:left-6 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </div>

                {{-- Search Input --}}
                <input id="heroSearch" type="text" name="q" placeholder="Search tracking ID" class="font-openSans flex-1 pl-12 lg:pl-16 pr-24 lg:pr-32 h-full bg-transparent text-gray-700 placeholder:text-gray-400
                           placeholder:font-openSans text-sm lg:text-base outline-none rounded-full transition-all duration-300
                           focus:placeholder:text-gray-300" />

                {{-- Search Button --}}
                <button type="submit" class="absolute right-2 lg:right-3 bg-gray-800 text-white px-4 lg:px-6 py-2 lg:py-3 rounded-full
                           text-sm lg:text-base font-medium hover:bg-gray-900 transition-all duration-300
                           shadow-lg hover:shadow-xl active:scale-95">
                    <span class="hidden sm:inline">Tracking</span>
                    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </button>
            </form>

            {{-- Tip --}}
            <p class="text-center text-white/80 text-xs mt-3 drop-shadow">
                Tip: You can paste the <span class="font-mono">Tracking ID</span> from your PDF receipt directly here.
            </p>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>

        {{-- ==== JS Smart Tracking Logic ==== --}}
        <script>
            (function () {
                const form = document.getElementById('heroForm');
                const input = document.getElementById('heroSearch');

                const clean = (v) => (v || '').replace(/#/g, '').replace(/\s+/g, '').trim();
                const isUUID = (v) =>
                    /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(v);
                const isPrefix = (v) => /^[0-9a-f-]{8,36}$/i.test(v);

                const showError = (message) => {
                    let alertDiv = document.getElementById('search-error-alert');
                    if (!alertDiv) {
                        alertDiv = document.createElement('div');
                        alertDiv.id = 'search-error-alert';
                        alertDiv.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 max-w-md w-full mx-4 bg-red-50 border-2 border-red-200 rounded-xl p-4 shadow-2xl animate-bounce';
                        alertDiv.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-red-800 mb-1">Invalid Tracking ID</p>
                                    <p class="text-sm text-red-700" id="error-message"></p>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        `;
                        document.body.appendChild(alertDiv);
                    }

                    document.getElementById('error-message').textContent = message;
                    setTimeout(() => { alertDiv.remove(); }, 5000);
                };

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const raw = input.value;
                    const v = clean(raw);

                    if (!v) {
                        showError('Please enter a valid Report ID from your PDF receipt.');
                        return;
                    }

                    if (isUUID(v)) {
                        // redirect langsung ke tracking detail
                        window.location.href = "{{ route('tracking.detail', ['reportId' => '__ID__']) }}".replace('__ID__', v);
                        return;
                    }

                    if (isPrefix(v)) {
                        // redirect ke halaman tracking dengan prefilled ID
                        window.location.href = "/tracking?reportId=" + encodeURIComponent(v);
                        return;
                    }

                    // kalau bukan ID yang valid, tampilkan error
                    showError(`"${raw.substring(0, 20)}${raw.length > 20 ? '...' : ''}" bukan Report ID yang valid. Cek PDF receipt untuk mendapatkan Tracking ID yang benar.`);
                });
            })();
        </script>
    </section>

    {{-- ========== MAIN CONTENT SECTION ========== --}}
    <section id="lostandfound" class="py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4 md:px-8">

            {{-- Section Title --}}
            <div class="text-center mb-12 lg:mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 tracking-wide mb-4">
                    KEBUN RAYA BOGOR
                </h2>
                <div class="w-24 h-1 bg-gray-600 mx-auto rounded-full"></div>
            </div>

            {{-- Container untuk cards --}}
            <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 max-w-6xl mx-auto px-2 sm:px-4">

                {{-- LOST Card --}}
                <div class="group">
                    <a href="{{ url('/lost-form') }}"
                        class="block rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 h-full"
                        style="background-color: #FEFBF8;">

                        <div class="relative h-40 md:h-48 overflow-hidden flex items-center justify-center p-4 md:p-6"
                            style="background-color: #FEFBF8;">
                            <img src="{{ asset('storage/images/logo/foundbgputih.png') }}"
                                class="max-h-full max-w-full object-contain transition-transform duration-700 group-hover:scale-110"
                                alt="Lost Item" />
                        </div>

                        <div class="p-4 md:p-6 text-center">
                            <div class="w-12 h-1 bg-red-500 mx-auto rounded-full mb-4 md:mb-3"></div>
                            <p class="hidden md:block text-gray-600 leading-relaxed text-sm md:text-base mb-4">
                                Lost something valuable? Let us help you find it back.
                            </p>
                            <div class="mt-2 md:mt-4">
                                <span
                                    class="inline-flex items-center justify-center text-red-600 font-semibold group-hover:text-red-700 transition-colors text-sm md:text-base">
                                    Report Lost Item
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- FOUND Card --}}
                <div class="group">
                    <a href="{{ url('/found-form') }}"
                        class="block rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 h-full"
                        style="background-color: #FEFBF8;">

                        <div class="relative h-40 md:h-48 overflow-hidden flex items-center justify-center p-4 md:p-6"
                            style="background-color: #FEFBF8;">
                            <img src="{{ asset('storage/images/logo/lostbgputih.png') }}"
                                class="max-h-full max-w-full object-contain transition-transform duration-700 group-hover:scale-110"
                                alt="Found Item" />
                        </div>

                        <div class="p-4 md:p-6 text-center">
                            <div class="w-12 h-1 bg-red-500 mx-auto rounded-full mb-4 md:mb-3"></div>
                            <p class="hidden md:block text-gray-600 leading-relaxed text-sm md:text-base mb-4">
                                Found something that doesn't belong to you? Help us return it to the rightful owner.
                            </p>
                            <div class="mt-2 md:mt-4">
                                <span
                                    class="inline-flex items-center justify-center text-red-600 font-semibold group-hover:text-red-700 transition-colors text-sm md:text-base">
                                    Report Found Item
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            {{-- Garden Map & Legend Section --}}
            <div class="mt-16 lg:mt-20">

                {{-- Section Header --}}
                <div class="text-center mb-8">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-3">Garden Map & Facilities</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Explore Bogor Botanical Garden facilities and plant
                        collections</p>
                </div>

                {{-- Map Container --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 max-w-5xl mx-auto">

                    {{-- Map Image (Preview) + Button --}}
                    <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 p-3 md:p-6">

                        {{-- Klik gambar preview untuk buka modal --}}
                        <button type="button" onclick="openMapModal()"
                            class="block w-full text-left rounded-xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="Open Interactive Garden Map">
                            <img src="{{ asset('images/peta-kebun-raya-bogor.png') }}" alt="Peta Kebun Raya Bogor"
                                class="w-full h-auto rounded-xl shadow-md cursor-zoom-in select-none">
                        </button>

                        {{-- Button open modal --}}
                        <div class="absolute top-6 right-6 md:top-8 md:right-8">
                            <button type="button" onclick="openMapModal()"
                                class="bg-white/95 backdrop-blur-sm text-gray-800 px-3 md:px-5 py-2 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 inline-flex items-center gap-2 hover:bg-white hover:-translate-y-1 text-xs md:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                                <span class="hidden sm:inline">Interactive Map</span>
                                <span class="sm:hidden">Map</span>
                            </button>
                        </div>
                    </div>

                    {{-- Legend Section --}}
                    <div class="p-4 md:p-6 bg-gradient-to-br from-gray-50 to-white border-t border-gray-100">

                        {{-- Mobile Dropdown View --}}
                        <div class="md:hidden space-y-4">
                            {{-- Facilities Dropdown --}}
                            <details class="group bg-white rounded-xl shadow-md overflow-hidden">
                                <summary
                                    class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-gray-800">Fasilitas / Facilities</h3>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="p-4 pt-0 space-y-2 text-sm border-t border-gray-100">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">1.</span><span
                                            class="text-gray-700">Gerbang Utama / Main Gate</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">2.</span><span
                                            class="text-gray-700">Pusat Informasi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">3.</span><span
                                            class="text-gray-700">Museum Zoology</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">4.</span><span
                                            class="text-gray-700">Gedung Konservasi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">5.</span><span
                                            class="text-gray-700">Hotel</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">6.</span><span
                                            class="text-gray-700">Lab Treub</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">7.</span><span
                                            class="text-gray-700">Toko Merchandise</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">8.</span><span
                                            class="text-gray-700">Pembibitan / Nursery</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">9.</span><span
                                            class="text-gray-700">Pintu II / Gate II</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">10.</span><span
                                            class="text-gray-700">Kantor Pengelola</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">11.</span><span
                                            class="text-gray-700">Masjid / Mosque</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">12.</span><span
                                            class="text-gray-700">Herbarium & Museum Biji</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">13.</span><span
                                            class="text-gray-700">Pintu III / Gate III</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">14.</span><span
                                            class="text-gray-700">Restoran / Restaurant</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">15.</span><span
                                            class="text-gray-700">Pembibitan Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">16.</span><span
                                            class="text-gray-700">Lab Kultur Jaringan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">17.</span><span
                                            class="text-gray-700">Pintu IV / Gate IV</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">18.</span><span
                                            class="text-gray-700">Musholla</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">19.</span><span
                                            class="text-gray-700">Pembibitan Reintroduksi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">20.</span><span
                                            class="text-gray-700">Auditorium Rafflesia</span></div>
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">21.</span><span
                                                class="text-gray-700">Melchior Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">22.</span><span
                                                class="text-gray-700">Little Melchior Ave</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">23.</span><span
                                                class="text-gray-700">Astrid Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">24.</span><span
                                                class="text-gray-700">Cappelen Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">25.</span><span
                                                class="text-gray-700">Reindwart Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">26.</span><span
                                                class="text-gray-700">Otto Avenue</span></div>
                                    </div>
                                </div>
                            </details>

                            {{-- Interesting Sites Dropdown --}}
                            <details class="group bg-white rounded-xl shadow-md overflow-hidden">
                                <summary
                                    class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-gray-800">Situs Menarik / Sites</h3>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="p-4 pt-0 space-y-2 text-sm border-t border-gray-100">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">27.</span><span
                                            class="text-gray-700">Monumen Lady Raffles</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">28.</span><span
                                            class="text-gray-700">Monumen J.J. Smith</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">29.</span><span
                                            class="text-gray-700">Kolam Gunting</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">30.</span><span
                                            class="text-gray-700">Taman Teisjmann</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">31.</span><span
                                            class="text-gray-700">Makam Belanda</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">32.</span><span
                                            class="text-gray-700">Istana Bogor</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">33.</span><span
                                            class="text-gray-700">Jalan Kenari I</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">34.</span><span
                                            class="text-gray-700">Jalan Kenari II</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">35.</span><span
                                            class="text-gray-700">Jembatan Gantung</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">36.</span><span
                                            class="text-gray-700">Jembatan Surya Lembayung</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">37.</span><span
                                            class="text-gray-700">Jalan Astrid</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">38.</span><span
                                            class="text-gray-700">Griya Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">39.</span><span
                                            class="text-gray-700">Taman Sudjana Kassan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">40.</span><span
                                            class="text-gray-700">Area Pinus</span></div>
                                </div>
                            </details>

                            {{-- Plant Collections Dropdown --}}
                            <details class="group bg-white rounded-xl shadow-md overflow-hidden">
                                <summary
                                    class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-gray-800">Koleksi Tanaman</h3>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="p-4 pt-0 space-y-2 text-sm border-t border-gray-100">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">41.</span><span
                                            class="text-gray-700">Tumbuhan Obat</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">42.</span><span
                                            class="text-gray-700">Kayu Raja</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">43.</span><span
                                            class="text-gray-700">Tumbuhan Aracea</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">44.</span><span
                                            class="text-gray-700">Bunga Bangkai</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">45.</span><span
                                            class="text-gray-700">Rotan / Rattan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">46.</span><span
                                            class="text-gray-700">Koleksi Pandan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">47.</span><span
                                            class="text-gray-700">Kaktus (Taman Meksiko)</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">48.</span><span
                                            class="text-gray-700">Koleksi Palem</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">49.</span><span
                                            class="text-gray-700">Tanaman Air</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">50.</span><span
                                            class="text-gray-700">Koleksi Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">51.</span><span
                                            class="text-gray-700">Tanaman Pemanjat</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">52.</span><span
                                            class="text-gray-700">Koleksi Paku-pakuan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">53.</span><span
                                            class="text-gray-700">Hutan / Wild Corner</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">54.</span><span
                                            class="text-gray-700">Koleksi Kayu Manis</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">55.</span><span
                                            class="text-gray-700">Teratai Raksasa</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">56.</span><span
                                            class="text-gray-700">Tanaman Kayu</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">57.</span><span
                                            class="text-gray-700">Koleksi Bambu</span></div>
                                </div>
                            </details>
                        </div>

                        {{-- Desktop Grid View --}}
                        <div class="hidden md:grid md:grid-cols-3 gap-8">
                            {{-- Facilities Column --}}
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Fasilitas / Facilities</h3>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">1.</span><span
                                            class="text-gray-700">Gerbang Utama / Main Gate</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">2.</span><span
                                            class="text-gray-700">Pusat Informasi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">3.</span><span
                                            class="text-gray-700">Museum Zoology</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">4.</span><span
                                            class="text-gray-700">Gedung Konservasi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">5.</span><span
                                            class="text-gray-700">Hotel</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">6.</span><span
                                            class="text-gray-700">Lab Treub</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">7.</span><span
                                            class="text-gray-700">Toko Merchandise</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">8.</span><span
                                            class="text-gray-700">Pembibitan / Nursery</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">9.</span><span
                                            class="text-gray-700">Pintu II / Gate II</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">10.</span><span
                                            class="text-gray-700">Kantor Pengelola</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">11.</span><span
                                            class="text-gray-700">Masjid / Mosque</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">12.</span><span
                                            class="text-gray-700">Herbarium & Museum Biji</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">13.</span><span
                                            class="text-gray-700">Pintu III / Gate III</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">14.</span><span
                                            class="text-gray-700">Restoran / Restaurant</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">15.</span><span
                                            class="text-gray-700">Pembibitan Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">16.</span><span
                                            class="text-gray-700">Lab Kultur Jaringan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">17.</span><span
                                            class="text-gray-700">Pintu IV / Gate IV</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">18.</span><span
                                            class="text-gray-700">Musholla</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">19.</span><span
                                            class="text-gray-700">Pembibitan Reintroduksi</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-blue-600 min-w-[28px]">20.</span><span
                                            class="text-gray-700">Auditorium Rafflesia</span></div>
                                </div>

                                <div class="mt-6">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">21.</span><span
                                                class="text-gray-700">Melchior Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">22.</span><span
                                                class="text-gray-700">Little Melchior Ave</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">23.</span><span
                                                class="text-gray-700">Astrid Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">24.</span><span
                                                class="text-gray-700">Cappelen Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">25.</span><span
                                                class="text-gray-700">Reindwart Avenue</span></div>
                                        <div class="flex gap-2"><span
                                                class="font-semibold text-blue-600 min-w-[28px]">26.</span><span
                                                class="text-gray-700">Otto Avenue</span></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Interesting Sites Column --}}
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Situs Menarik / Sites</h3>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">27.</span><span
                                            class="text-gray-700">Monumen Lady Raffles</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">28.</span><span
                                            class="text-gray-700">Monumen J.J. Smith</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">29.</span><span
                                            class="text-gray-700">Kolam Gunting</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">30.</span><span
                                            class="text-gray-700">Taman Teisjmann</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">31.</span><span
                                            class="text-gray-700">Makam Belanda</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">32.</span><span
                                            class="text-gray-700">Istana Bogor</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">33.</span><span
                                            class="text-gray-700">Jalan Kenari I</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">34.</span><span
                                            class="text-gray-700">Jalan Kenari II</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">35.</span><span
                                            class="text-gray-700">Jembatan Gantung</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">36.</span><span
                                            class="text-gray-700">Jembatan Surya Lembayung</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">37.</span><span
                                            class="text-gray-700">Jalan Astrid</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">38.</span><span
                                            class="text-gray-700">Griya Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">39.</span><span
                                            class="text-gray-700">Taman Sudjana Kassan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-purple-600 min-w-[28px]">40.</span><span
                                            class="text-gray-700">Area Pinus</span></div>
                                </div>
                            </div>

                            {{-- Plant Collections Column --}}
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Koleksi Tanaman</h3>
                                </div>

                                <div class="space-y-2 text-sm">
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">41.</span><span
                                            class="text-gray-700">Tumbuhan Obat</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">42.</span><span
                                            class="text-gray-700">Kayu Raja</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">43.</span><span
                                            class="text-gray-700">Tumbuhan Aracea</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">44.</span><span
                                            class="text-gray-700">Bunga Bangkai</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">45.</span><span
                                            class="text-gray-700">Rotan / Rattan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">46.</span><span
                                            class="text-gray-700">Koleksi Pandan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">47.</span><span
                                            class="text-gray-700">Kaktus (Taman Meksiko)</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">48.</span><span
                                            class="text-gray-700">Koleksi Palem</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">49.</span><span
                                            class="text-gray-700">Tanaman Air</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">50.</span><span
                                            class="text-gray-700">Koleksi Anggrek</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">51.</span><span
                                            class="text-gray-700">Tanaman Pemanjat</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">52.</span><span
                                            class="text-gray-700">Koleksi Paku-pakuan</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">53.</span><span
                                            class="text-gray-700">Hutan / Wild Corner</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">54.</span><span
                                            class="text-gray-700">Koleksi Kayu Manis</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">55.</span><span
                                            class="text-gray-700">Teratai Raksasa</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">56.</span><span
                                            class="text-gray-700">Tanaman Kayu</span></div>
                                    <div class="flex gap-2"><span
                                            class="font-semibold text-green-600 min-w-[28px]">57.</span><span
                                            class="text-gray-700">Koleksi Bambu</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- end map section --}}
        </div>
    </section>

    {{-- =========================
    MAP MODAL (FULL MAP + ZOOM + PINS TOOLTIP)
    Tempel 1x saja (sudah aku taruh di bawah)
    ========================= --}}
    <div id="mapModal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 transition-opacity backdrop-blur-sm" onclick="closeMapModal()"></div>

        <!-- Modal Wrapper -->
        <div class="relative w-full h-full flex items-center justify-center p-3 sm:p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-7xl w-full max-h-[92vh] overflow-hidden">

                <!-- Header -->
                <div
                    class="flex items-start md:items-center justify-between gap-4 p-4 md:p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div>
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800">Interactive Garden Map</h3>
                        <p class="text-xs md:text-sm text-gray-600 mt-1">
                            Zoom in/out • Hover pin untuk lihat nama • Klik pin untuk “mengunci” tooltip
                        </p>
                    </div>

                    <button type="button" onclick="closeMapModal()"
                        class="text-gray-500 hover:text-gray-700 transition-colors p-2 hover:bg-white/60 rounded-lg"
                        aria-label="Close map modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Map Content would go here -->
                <div class="p-4 text-center text-gray-500">
                    <p>Interactive map feature coming soon...</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Pins */
        .pin-marker {
            cursor: pointer;
            z-index: 10;
            transform: translate(-50%, -100%);
        }

        .pin-dot {
            width: 16px;
            height: 16px;
            border-radius: 9999px;
            background: rgb(var(--pin));
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
            transition: transform .2s ease, box-shadow .2s ease;
            animation: pinPulse 2s infinite;
        }

        .pin-marker:hover .pin-dot {
            transform: scale(1.25);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
            animation: none;
        }

        /* Tooltip */
        .pin-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background: white;
            padding: 8px 12px;
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all .2s ease;
            margin-bottom: 10px;
            border: 1px solid #e5e7eb;
        }

        .pin-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 7px solid transparent;
            border-top-color: #fff;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.12));
        }

        .pin-marker:hover .pin-tooltip,
        .pin-marker.active .pin-tooltip {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        @keyframes pinPulse {

            0%,
            100% {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 0 0 rgba(var(--pin), 0.35);
            }

            50% {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 0 12px rgba(var(--pin), 0);
            }
        }

        .map-viewport {
            cursor: grab;
            touch-action: none;
            /* penting: biar drag di hp gak jadi scroll halaman */
        }

        .map-viewport.dragging {
            cursor: grabbing;
        }

        /* Scrollbar */
        #mapScrollContainer::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        #mapScrollContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #mapScrollContainer::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 9999px;
        }

        #mapScrollContainer::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>

    <script>
        (function () {
            let currentZoom = 1;
            const minZoom = 1;
            const maxZoom = 3;
            const zoomStep = 0.25;

            // Samakan dengan width awal #mapInner
            let baseWidth = 1400;

            const modal = () => document.getElementById('mapModal');
            const scroll = () => document.getElementById('mapScrollContainer');
            const inner = () => document.getElementById('mapInner');

            function applyZoom() {
                inner().style.width = (baseWidth * currentZoom) + 'px';
            }

            window.openMapModal = function () {
                modal().classList.remove('hidden');
                modal().setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';

                document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));
                applyZoom();
            }

            window.closeMapModal = function () {
                modal().classList.add('hidden');
                modal().setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';

                document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));
            }

            window.zoomIn = function () {
                if (currentZoom < maxZoom) {
                    currentZoom = Math.min(maxZoom, currentZoom + zoomStep);
                    applyZoom();
                }
            }

            window.zoomOut = function () {
                if (currentZoom > minZoom) {
                    currentZoom = Math.max(minZoom, currentZoom - zoomStep);
                    applyZoom();
                }
            }

            window.resetZoom = function () {
                currentZoom = 1;
                applyZoom();
                scroll().scrollTop = 0;
                scroll().scrollLeft = 0;
            }

            // ESC to close
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal().classList.contains('hidden')) {
                    window.closeMapModal();
                }
            });

            // Pin click: tooltip “nempel”
            document.addEventListener('DOMContentLoaded', function () {
                const pins = document.querySelectorAll('.pin-marker');
                pins.forEach(pin => {
                    pin.addEventListener('click', function (e) {
                        e.stopPropagation();
                        pins.forEach(p => p.classList.remove('active'));
                        this.classList.add('active');
                    });
                });

                // Klik area map: hapus active
                const sc = document.getElementById('mapScrollContainer');
                sc.addEventListener('click', function () {
                    pins.forEach(p => p.classList.remove('active'));
                });

                // OPTIONAL: Ctrl + wheel untuk zoom
                sc.addEventListener('wheel', function (e) {
                    if (!e.ctrlKey) return;
                    e.preventDefault();
                    if (e.deltaY < 0) window.zoomIn();
                    else window.zoomOut();
                }, { passive: false });
            });
        })();
    </script>
</x-layouts.app>