@php
    use App\Data\MapLegendData;

    $facilities = MapLegendData::getFacilities();
    $avenues = MapLegendData::getAvenues();
    $sites = MapLegendData::getInterestingSites();
    $collections = MapLegendData::getPlantCollections();
    $pins = MapLegendData::getMapPins();
@endphp

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

            {{-- Tip kecil --}}
            <p class="text-center text-white/80 text-xs mt-3 drop-shadow">
                Tip: kamu bisa paste <span class="font-mono">Tracking ID</span> dari PDF receipt langsung di sini.
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
                        showError('Masukkan Report ID yang valid dari PDF receipt Anda.');
                        return;
                    }

                    if (isUUID(v)) {
                        window.location.href = "{{ route('tracking.detail', ['reportId' => '_ID']) }}".replace('ID_', v);
                        return;
                    }

                    if (isPrefix(v)) {
                        window.location.href = "/tracking?reportId=" + encodeURIComponent(v);
                        return;
                    }

                    showError("${raw.substring(0, 20)}${raw.length > 20 ? '...' : ''}" bukan Report ID yang valid.Cek PDF receipt untuk mendapatkan Tracking ID yang benar.);
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

            {{-- Lost & Found Cards --}}
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
                            <div class="w-12 h-1 bg-red-500 mx-auto rounded-full mb-3"></div>
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base min-h-[3rem]">
                                Lost something valuable? Let us help you find it back.
                            </p>
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center text-red-600 font-semibold group-hover:text-red-700 transition-colors text-sm md:text-base">
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
                            <div class="w-12 h-1 bg-gray-500 mx-auto rounded-full mb-3"></div>
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base min-h-[3rem]">
                                Found something that doesn't belong to you? Help us return it to the rightful owner.
                            </p>
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center text-gray-600 font-semibold group-hover:text-gray-700 transition-colors text-sm md:text-base">
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
                <div class="text-center mb-8">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-3">Garden Map & Facilities</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Explore Bogor Botanical Garden facilities and plant
                        collections</p>
                </div>

                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 max-w-5xl mx-auto">
                    {{-- Map Preview --}}
                    <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 p-3 md:p-6">
                        <button type="button" onclick="openMapModal()"
                            class="block w-full text-left rounded-xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="Open Interactive Garden Map">
                            <img src="{{ asset('images/peta-kebun-raya-bogor.png') }}" alt="Peta Kebun Raya Bogor"
                                class="w-full h-auto rounded-xl shadow-md cursor-zoom-in select-none">
                        </button>

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
                            @include('components.map-legend-mobile', [
                                'facilities' => $facilities,
                                'avenues' => $avenues,
                                'sites' => $sites,
                                'collections' => $collections
                            ])
                        </div>

                        {{-- Desktop Grid View --}}
                        <div class="hidden md:grid md:grid-cols-3 gap-8">
                            @include('components.map-legend-desktop', [
                                'facilities' => $facilities,
                                'avenues' => $avenues,
                                'sites' => $sites,
                                'collections' => $collections
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Modal --}}
    @include('components.map-modal', ['pins' => $pins])
</x-layouts.app>