<div id="mapModal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/60 transition-opacity backdrop-blur-sm" onclick="closeMapModal()"></div>

    <div class="relative w-full h-full flex items-center justify-center p-3 sm:p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-7xl w-full max-h-[92vh] overflow-hidden">

            {{-- Header --}}
            <div class="flex items-start md:items-center justify-between gap-4 p-4 md:p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div>
                    <h3 class="text-lg md:text-2xl font-bold text-gray-800">Interactive Garden Map</h3>
                    <p class="text-xs md:text-sm text-gray-600 mt-1">
                        Scroll to pan • Ctrl+Scroll to zoom • Hover pin untuk lihat nama • Klik pin untuk "mengunci" tooltip
                    </p>
                </div>
                <button type="button" onclick="closeMapModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors p-2 hover:bg-white/60 rounded-lg"
                    aria-label="Close map modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Map Area --}}
            <div class="relative overflow-hidden bg-gray-100" style="height: calc(92vh - 150px);">
                {{-- Zoom Controls --}}
                <div class="absolute top-4 right-4 z-30 flex flex-col gap-2">
                    <button type="button" onclick="zoomIn()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Zoom in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                    <button type="button" onclick="zoomOut()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Zoom out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>
                    <button type="button" onclick="resetZoom()"
                        class="bg-white hover:bg-gray-50 text-gray-800 p-3 rounded-lg shadow-lg transition-all hover:scale-110 active:scale-95"
                        aria-label="Reset zoom">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                        {{-- Render Pins --}}
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

            {{-- Footer Legend --}}
            <div class="p-4 border-t border-gray-200 bg-gray-50">
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
        </div>
    </div>
</div>

<style>
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

        function getElements() {
            return {
                modal: document.getElementById('mapModal'),
                container: document.getElementById('mapScrollContainer'),
                inner: document.getElementById('mapInner'),
                image: document.getElementById('mapImage')
            };
        }

        function applyZoom() {
            const { inner } = getElements();
            if (inner) {
                inner.style.transform = `scale(${currentZoom})`;
                inner.style.transformOrigin = 'top left';
            }
        }

        window.openMapModal = function () {
            const { modal } = getElements();
            if (modal) {
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                
                // Reset zoom and clear active pins
                currentZoom = 1;
                applyZoom();
                document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));
            }
        };

        window.closeMapModal = function () {
            const { modal } = getElements();
            if (modal) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';
                document.querySelectorAll('.pin-marker.active').forEach(p => p.classList.remove('active'));
            }
        };

        window.zoomIn = function () {
            if (currentZoom < maxZoom) {
                const { container } = getElements();
                const centerX = container.scrollLeft + container.clientWidth / 2;
                const centerY = container.scrollTop + container.clientHeight / 2;
                
                currentZoom = Math.min(maxZoom, currentZoom + zoomStep);
                applyZoom();
                
                // Adjust scroll to keep center point
                container.scrollLeft = centerX * (currentZoom / (currentZoom - zoomStep)) - container.clientWidth / 2;
                container.scrollTop = centerY * (currentZoom / (currentZoom - zoomStep)) - container.clientHeight / 2;
            }
        };

        window.zoomOut = function () {
            if (currentZoom > minZoom) {
                const { container } = getElements();
                const centerX = container.scrollLeft + container.clientWidth / 2;
                const centerY = container.scrollTop + container.clientHeight / 2;
                
                const oldZoom = currentZoom;
                currentZoom = Math.max(minZoom, currentZoom - zoomStep);
                applyZoom();
                
                // Adjust scroll to keep center point
                if (currentZoom > minZoom) {
                    container.scrollLeft = centerX * (currentZoom / oldZoom) - container.clientWidth / 2;
                    container.scrollTop = centerY * (currentZoom / oldZoom) - container.clientHeight / 2;
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
            if (container) {
                container.scrollTop = 0;
                container.scrollLeft = 0;
            }
        };

        // Close modal on Escape key
        document.addEventListener('keydown', function (e) {
            const { modal } = getElements();
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                window.closeMapModal();
            }
        });

        // Initialize pin interactions
        document.addEventListener('DOMContentLoaded', function () {
            const pins = document.querySelectorAll('.pin-marker');
            const container = document.getElementById('mapScrollContainer');
            
            pins.forEach(pin => {
                pin.addEventListener('click', function (e) {
                    e.stopPropagation();
                    pins.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Clear active pins when clicking on map background
            if (container) {
                container.addEventListener('click', function () {
                    pins.forEach(p => p.classList.remove('active'));
                });
            }

            // Zoom with Ctrl+Scroll
            if (container) {
                container.addEventListener('wheel', function (e) {
                    if (e.ctrlKey) {
                        e.preventDefault();
                        if (e.deltaY < 0) {
                            window.zoomIn();
                        } else {
                            window.zoomOut();
                        }
                    }
                }, { passive: false });
            }
        });
    })();
</script>