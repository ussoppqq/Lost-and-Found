<div id="mapModal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/60 transition-opacity backdrop-blur-sm" onclick="closeMapModal()"></div>

    <div class="relative w-full h-full flex items-center justify-center p-3 md:p-4">
        <div class="relative bg-white rounded-xl md:rounded-2xl shadow-2xl max-w-7xl w-full max-h-[85vh] md:max-h-[92vh] overflow-hidden">

            {{-- Header --}}
            <div class="flex items-start md:items-center justify-between gap-2 md:gap-4 p-3 md:p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm md:text-2xl font-bold text-gray-800 truncate">Garden Map</h3>
                    <p class="text-xs md:text-sm text-gray-600 mt-0.5 md:mt-1">
                        <span class="hidden md:inline">Scroll to pan • Ctrl+Scroll to zoom • Hover pin untuk lihat nama • Klik pin untuk "mengunci" tooltip</span>
                        <span class="md:hidden">Pinch to zoom • Scroll to pan</span>
                    </p>
                </div>

                <button type="button" onclick="closeMapModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors p-1 md:p-2 hover:bg-white/60 rounded-lg flex-shrink-0"
                    aria-label="Close map modal">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Map Area --}}
            <div class="relative overflow-hidden bg-gray-100 map-area-height">

                {{-- Zoom Controls --}}
                <div class="absolute top-2 right-2 md:top-4 md:right-4 z-30 flex flex-col gap-1.5 md:gap-2">
                    <button type="button" onclick="zoomIn()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-2 md:p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Zoom in">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>

                    <button type="button" onclick="zoomOut()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-2 md:p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Zoom out">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <button type="button" onclick="resetZoom()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-2 md:p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Reset zoom">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>

                {{-- Viewport --}}
                <div id="mapScrollContainer" class="w-full h-full overflow-auto relative">
                    <div id="mapInner" class="relative inline-block" style="min-width: 100%; min-height: 100%;">
                        <img id="mapImage" src="{{ asset('images/peta-kebun-raya-bogor.png') }}"
                            alt="Peta Kebun Raya Bogor"
                            class="block w-full h-auto select-none"
                            style="pointer-events: none;">

                        {{-- Render Pins (Desktop Only) --}}
                        <div class="hidden md:block">
                            @foreach($pins as $pin)
                                <div class="absolute pin-marker"
                                    style="top: {{ $pin['top'] }}; left: {{ $pin['left'] }}; --pin-color: {{ $pin['color'] }};">
                                    <div class="pin-dot"></div>
                                    <div class="pin-tooltip">
                                        <div class="font-semibold {{ $pin['category'] === 'facility' ? 'text-blue-600' : ($pin['category'] === 'site' ? 'text-purple-600' : 'text-green-600') }}">
                                            {{ $pin['id'] }}. {{ $pin['name'] }}
                                        </div>
                                        <div class="text-xs text-gray-600">{{ $pin['subtitle'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer Legend (Desktop Only) --}}
            <div class="hidden md:block p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-wrap gap-4 justify-center text-xs md:text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: rgb(37, 99, 235)"></div>
                        <span class="text-gray-700">Facilities</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: rgb(147, 51, 234)"></div>
                        <span class="text-gray-700">Sites</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: rgb(22, 163, 74)"></div>
                        <span class="text-gray-700">Collections</span>
                    </div>
                </div>
            </div>

            {{-- Mobile Tip --}}
            <div class="md:hidden p-2.5 border-t border-gray-200 bg-gray-50">
                <p class="text-center text-xs text-gray-600">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pinch to zoom • Lihat legend di bawah untuk detail
                </p>
            </div>

        </div>
    </div>
</div>

<style>
    /* Map area responsive height (lebih pendek di mobile) */
    .map-area-height {
        height: 42vh;
        max-height: 380px;
    }

    @media (min-width: 768px) {
        .map-area-height {
            height: calc(92vh - 140px);
            max-height: none;
        }
    }

    .pin-marker {
        cursor: pointer;
        z-index: 10;
        transform: translate(-50%, -100%);
    }

    .pin-dot {
        width: 16px;
        height: 16px;
        border-radius: 9999px;
        background: rgb(var(--pin-color));
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
        0%, 100% {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 0 0 rgba(var(--pin-color), 0.35);
        }
        50% {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 0 12px rgba(var(--pin-color), 0);
        }
    }

    #mapScrollContainer::-webkit-scrollbar {
        width: 10px;
        height: 10px;
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

    /* Mobile optimizations */
    @media (max-width: 768px) {
        #mapScrollContainer {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script>
(function () {
    let currentZoom = 1;
    const minZoom = 1;
    const zoomStep = 0.25;

    function isMobile() {
        return window.matchMedia('(max-width: 768px)').matches;
    }

    function getMaxZoom() {
        return isMobile() ? 2.5 : 3;
    }

    let touchStartDistance = 0;
    let initialZoom = 1;

    function getElements() {
        return {
            modal: document.getElementById('mapModal'),
            container: document.getElementById('mapScrollContainer'),
            inner: document.getElementById('mapInner'),
            image: document.getElementById('mapImage'),
        };
    }

    function applyZoom() {
        const { inner } = getElements();
        if (!inner) return;
        inner.style.transform = `scale(${currentZoom})`;
        inner.style.transformOrigin = 'top left';
    }

    // Auto center map in container (mostly for mobile)
    function centerMap() {
        const { container, inner } = getElements();
        if (!container || !inner) return;

        const scaledW = inner.offsetWidth * currentZoom;
        const scaledH = inner.offsetHeight * currentZoom;

        const left = Math.max(0, (scaledW - container.clientWidth) / 2);
        const top  = Math.max(0, (scaledH - container.clientHeight) / 2);

        container.scrollLeft = left;
        container.scrollTop  = top;
    }

    function getTouchDistance(e) {
        const t1 = e.touches[0];
        const t2 = e.touches[1];
        return Math.sqrt(
            Math.pow(t2.pageX - t1.pageX, 2) +
            Math.pow(t2.pageY - t1.pageY, 2)
        );
    }

    window.openMapModal = function () {
        const { modal, container, image } = getElements();
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // mobile: zoom awal lebih besar dikit
        currentZoom = isMobile() ? 1.25 : 1;
        applyZoom();

        // clear active pins
        document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));

        // Center map on mobile (wait render + image load)
        const doCenter = () => {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => centerMap());
            });
        };

        if (isMobile()) {
            // kalau image belum load, tunggu dulu
            if (image && !image.complete) {
                image.onload = () => doCenter();
            } else {
                doCenter();
            }
        } else {
            // desktop tetap start dari atas (kalau mau center desktop juga, bilang ya)
            if (container) {
                container.scrollTop = 0;
                container.scrollLeft = 0;
            }
        }
    };

    window.closeMapModal = function () {
        const { modal } = getElements();
        if (!modal) return;

        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
        document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));
    };

    window.zoomIn = function () {
        const { container } = getElements();
        if (!container) return;

        const maxZoom = getMaxZoom();
        if (currentZoom >= maxZoom) return;

        const centerX = container.scrollLeft + container.clientWidth / 2;
        const centerY = container.scrollTop + container.clientHeight / 2;

        const oldZoom = currentZoom;
        currentZoom = Math.min(maxZoom, currentZoom + zoomStep);
        applyZoom();

        container.scrollLeft = (centerX * (currentZoom / oldZoom)) - container.clientWidth / 2;
        container.scrollTop  = (centerY * (currentZoom / oldZoom)) - container.clientHeight / 2;
    };

    window.zoomOut = function () {
        const { container } = getElements();
        if (!container) return;

        if (currentZoom <= minZoom) return;

        const centerX = container.scrollLeft + container.clientWidth / 2;
        const centerY = container.scrollTop + container.clientHeight / 2;

        const oldZoom = currentZoom;
        currentZoom = Math.max(minZoom, currentZoom - zoomStep);
        applyZoom();

        if (currentZoom > minZoom) {
            container.scrollLeft = (centerX * (currentZoom / oldZoom)) - container.clientWidth / 2;
            container.scrollTop  = (centerY * (currentZoom / oldZoom)) - container.clientHeight / 2;
        } else {
            if (isMobile()) {
                requestAnimationFrame(() => requestAnimationFrame(() => centerMap()));
            } else {
                container.scrollLeft = 0;
                container.scrollTop = 0;
            }
        }
    };

    window.resetZoom = function () {
        const { container } = getElements();
        currentZoom = 1;
        applyZoom();

        if (isMobile()) {
            requestAnimationFrame(() => requestAnimationFrame(() => centerMap()));
        } else if (container) {
            container.scrollTop = 0;
            container.scrollLeft = 0;
        }
    };

    // Close modal on Escape
    document.addEventListener('keydown', function (e) {
        const { modal } = getElements();
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            window.closeMapModal();
        }
    });

    // Init interactions
    document.addEventListener('DOMContentLoaded', function () {
        const pins = document.querySelectorAll('.pin-marker');
        const container = document.getElementById('mapScrollContainer');

        // Desktop pin clicks
        pins.forEach(pin => {
            pin.addEventListener('click', function (e) {
                e.stopPropagation();
                pins.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });

        if (!container) return;

        // Clear active pins when click background
        container.addEventListener('click', function () {
            pins.forEach(p => p.classList.remove('active'));
        });

        // Desktop: Ctrl + wheel zoom
        container.addEventListener('wheel', function (e) {
            if (e.ctrlKey) {
                e.preventDefault();
                (e.deltaY < 0) ? window.zoomIn() : window.zoomOut();
            }
        }, { passive: false });

        // Mobile: pinch zoom
        container.addEventListener('touchstart', function (e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                touchStartDistance = getTouchDistance(e);
                initialZoom = currentZoom;
            }
        }, { passive: false });

        container.addEventListener('touchmove', function (e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                const maxZoom = getMaxZoom();
                const scale = getTouchDistance(e) / touchStartDistance;
                const newZoom = Math.max(minZoom, Math.min(maxZoom, initialZoom * scale));
                if (newZoom !== currentZoom) {
                    currentZoom = newZoom;
                    applyZoom();
                }
            }
        }, { passive: false });
    });
})();
</script>
