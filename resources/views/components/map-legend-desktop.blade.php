{{-- Facilities Column --}}
<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Fasilitas / Facilities</h3>
    </div>
    <x-map-legend :items="$facilities" color="blue" />
    <div class="mt-6">
        <x-map-legend :items="$avenues" color="blue" />
    </div>
</div>

{{-- Interesting Sites Column --}}
<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Situs Menarik / Sites</h3>
    </div>
    <x-map-legend :items="$sites" color="purple" />
</div>

{{-- Plant Collections Column --}}
<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Koleksi Tanaman</h3>
    </div>
    <x-map-legend :items="$collections" color="green" />
</div>