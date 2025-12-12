<div>
    <div class="mb-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Lokasi Kehilangan/Penemuan di Kebun Raya Bogor
        </label>
        <p class="text-xs text-gray-500 mb-2">Klik pada peta untuk menandai lokasi</p>
    </div>

    <!-- Custom Map Container -->
    <div class="relative w-full">
        <div id="map-container" class="relative inline-block w-full">
            <!-- Map Image -->
            <img
                id="kebun-raya-map"
                src="{{ asset('images/peta-kebun-raya-bogor.png') }}"
                alt="Peta Kebun Raya Bogor"
                class="max-w-full h-auto cursor-crosshair"
                style="max-height: 600px; display: block;"
                draggable="false"
            >

            <!-- Marker Overlay -->
            <div id="marker" class="absolute" style="width: 30px; height: 30px; margin-left: -15px; margin-top: -30px; pointer-events: none; z-index: 10; display: none;">
                <svg viewBox="0 0 24 24" fill="red" stroke="white" stroke-width="2">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
            </div>
        </div>

        <!-- Coordinates Display (Outside map, below) -->
        <div class="mt-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-gray-700">
                    <span class="inline-block mr-4">X: <span id="coord-x" class="font-mono text-blue-600">-</span></span>
                    <span class="inline-block">Y: <span id="coord-y" class="font-mono text-blue-600">-</span></span>
                </div>
                <!-- Reset Button -->
                <button
                    type="button"
                    id="reset-marker"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hidden"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Location Name-->
    <div class="mt-3">
        <input
            type="text"
            wire:model.defer="locationName"
            placeholder="Nama lokasi (opsional, contoh: Dekat Kolam Teratai)"
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >
    </div>

    <!-- Display Selected Coordinates -->
    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="text-xs font-medium text-blue-900">
                    <span id="status-text">Belum ada lokasi yang dipilih</span>
                </p>
                <p class="text-xs text-blue-700 mt-1 font-mono" id="coordinate-display">
                    Koordinat: -
                </p>
            </div>
        </div>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="latitude" id="latitude-input" wire:model="latitude">
    <input type="hidden" name="longitude" id="longitude-input" wire:model="longitude">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapImage = document.getElementById('kebun-raya-map');
    const marker = document.getElementById('marker');
    const resetBtn = document.getElementById('reset-marker');
    const coordX = document.getElementById('coord-x');
    const coordY = document.getElementById('coord-y');
    const statusText = document.getElementById('status-text');
    const coordinateDisplay = document.getElementById('coordinate-display');
    const latInput = document.getElementById('latitude-input');
    const lonInput = document.getElementById('longitude-input');

    // Koordinat geografis Kebun Raya Bogor (bounds)
    // Sesuaikan dengan koordinat sebenarnya dari peta Anda
    const mapBounds = {
        north: -6.5950,  // Koordinat paling utara
        south: -6.6020,  // Koordinat paling selatan
        west: 106.7960,  // Koordinat paling barat
        east: 106.8020   // Koordinat paling timur
    };

    let selectedPosition = null;

    // Create a canvas to check pixel transparency
    const canvas = document.createElement('canvas');
    const canvasCtx = canvas.getContext('2d', { willReadFrequently: true });
    let imageLoaded = false;

    mapImage.onload = function() {
        canvas.width = mapImage.naturalWidth;
        canvas.height = mapImage.naturalHeight;
        canvasCtx.drawImage(mapImage, 0, 0);
        imageLoaded = true;
    };

    // If image already loaded
    if (mapImage.complete) {
        mapImage.onload();
    }

    // Function to position marker based on percentage
    function positionMarker(xPercent, yPercent) {
        const rect = mapImage.getBoundingClientRect();
        const x = (xPercent / 100) * rect.width;
        const y = (yPercent / 100) * rect.height;

        // Marker sudah punya margin-left: -15px dan margin-top: -30px
        // Jadi kita set posisi langsung tanpa offset tambahan
        marker.style.left = x + 'px';
        marker.style.top = y + 'px';
        marker.style.display = 'block';
    }

    // Function to update marker display
    function updateMarkerDisplay(xPercent, yPercent, latitude, longitude) {
        marker.style.display = 'block';
        resetBtn.classList.remove('hidden');

        coordX.textContent = xPercent.toFixed(2) + '%';
        coordY.textContent = yPercent.toFixed(2) + '%';
        statusText.textContent = 'Lokasi telah dipilih pada peta';
        coordinateDisplay.textContent = `Koordinat: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;

        // Update form inputs
        latInput.value = latitude.toFixed(8);
        lonInput.value = longitude.toFixed(8);

        // Save position for maintaining marker on window resize
        selectedPosition = { x: xPercent, y: yPercent, lat: latitude, lon: longitude };
    }

    // Process click/tap and place marker
    function placeMarkerAt(clientX, clientY) {
        const rect = mapImage.getBoundingClientRect();

        // Get position relative to image
        const x = clientX - rect.left;
        const y = clientY - rect.top;

        // Validate position is within image bounds
        if (x < 0 || y < 0 || x > rect.width || y > rect.height) {
            return;
        }

        // Calculate position on actual image (considering scaling)
        const scaleX = mapImage.naturalWidth / rect.width;
        const scaleY = mapImage.naturalHeight / rect.height;
        const imgX = Math.floor(x * scaleX);
        const imgY = Math.floor(y * scaleY);

        // Check if click is on transparent area (only works with PNG)
        if (imageLoaded) {
            try {
                const pixelData = canvasCtx.getImageData(imgX, imgY, 1, 1).data;
                const alpha = pixelData[3];

                // If pixel is transparent or nearly transparent, ignore click
                if (alpha < 10) {
                    return; // Don't place marker on transparent area
                }
            } catch (err) {
                // If can't check transparency (CORS issue), just continue
                console.log('Cannot check transparency:', err);
            }
        }

        // Calculate percentage position
        const xPercent = (x / rect.width) * 100;
        const yPercent = (y / rect.height) * 100;

        // Convert to geographic coordinates
        const longitude = mapBounds.west + (xPercent / 100) * (mapBounds.east - mapBounds.west);
        const latitude = mapBounds.north - (yPercent / 100) * (mapBounds.north - mapBounds.south);

        // Update marker position
        positionMarker(xPercent, yPercent);
        updateMarkerDisplay(xPercent, yPercent, latitude, longitude);

        // Trigger Livewire update (no re-render)
        @this.set('latitude', latitude, false);
        @this.set('longitude', longitude, false);

        // Emit event to parent
        @this.dispatch('locationUpdated', { latitude: latitude, longitude: longitude });
    }

    // Handle mouse click (desktop)
    mapImage.addEventListener('click', function(e) {
        console.log('Click event triggered at:', e.clientX, e.clientY);
        placeMarkerAt(e.clientX, e.clientY);
    });

    // Handle touch events (mobile/tablet) - simpler approach
    mapImage.addEventListener('touchend', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const touch = e.changedTouches[0];
        console.log('Touch tap at:', touch.clientX, touch.clientY);
        placeMarkerAt(touch.clientX, touch.clientY);
    }, { passive: false });

    // Prevent context menu on long press
    mapImage.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Reset marker
    resetBtn.addEventListener('click', function() {
        marker.style.display = 'none';
        resetBtn.classList.add('hidden');
        coordX.textContent = '-';
        coordY.textContent = '-';
        statusText.textContent = 'Belum ada lokasi yang dipilih';
        coordinateDisplay.textContent = 'Koordinat: -';
        latInput.value = '';
        lonInput.value = '';
        @this.set('latitude', null);
        @this.set('longitude', null);
        selectedPosition = null;
    });

    // Handle window resize to maintain marker position
    window.addEventListener('resize', function() {
        if (selectedPosition) {
            positionMarker(selectedPosition.x, selectedPosition.y);
        }
    });

    // Function to restore marker after Livewire updates
    function restoreMarker() {
        if (selectedPosition) {
            positionMarker(selectedPosition.x, selectedPosition.y);
            marker.style.display = 'block';
            resetBtn.classList.remove('hidden');
        }
    }

    // Listen for Livewire updates to restore marker
    document.addEventListener('livewire:update', restoreMarker);
    window.addEventListener('livewire:load', restoreMarker);

    // Load existing coordinates if any
    @if($latitude && $longitude)
        // Convert geographic coordinates back to percentage position
        const xPercent = ((@js($longitude) - mapBounds.west) / (mapBounds.east - mapBounds.west)) * 100;
        const yPercent = ((mapBounds.north - @js($latitude)) / (mapBounds.north - mapBounds.south)) * 100;

        // Function to place existing marker
        function placeExistingMarker() {
            const latitude = @js($latitude);
            const longitude = @js($longitude);

            positionMarker(xPercent, yPercent);
            updateMarkerDisplay(xPercent, yPercent, latitude, longitude);
        }

        // Wait for image to load, then place marker
        if (mapImage.complete) {
            setTimeout(placeExistingMarker, 100);
        } else {
            mapImage.addEventListener('load', placeExistingMarker);
        }
    @endif
});
</script>
@endpush

@push('styles')
<style>
    #kebun-raya-map {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -webkit-touch-callout: none;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        display: block;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 8px;
    }

    #map-container {
        position: relative;
        touch-action: manipulation;
    }

    #marker {
        transition: all 0.2s ease;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }

    #marker:hover {
        transform: scale(1.1);
    }

    /* Visual feedback when hovering over map */
    #kebun-raya-map:hover {
        filter: brightness(1.05);
    }
</style>
@endpush
