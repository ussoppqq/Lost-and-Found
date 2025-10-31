
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Track Your Item</h1>
            <p class="text-gray-600">Masukkan Report ID dari PDF receipt untuk mengecek status</p>
        </div>

        <!-- Search Form (ID only) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Report ID (dari PDF Receipt)
                        </div>
                    </label>
                    <div class="relative">
                        <input 
                            type="text"
                            wire:model.defer="reportId"
                            placeholder="e.g., 9d4e7a2b-1c8f-4e5d-9a3b-7c2e8f1d6a5b"
                            class="w-full px-4 py-3 pr-12 text-sm font-mono rounded-xl border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400"
                            maxlength="36"
                        />
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                    </div>
                    @error('reportId') 
                        <div class="mt-2 flex items-start gap-2 text-sm text-red-600">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs text-blue-800">
                                <p class="font-semibold mb-1">Di mana menemukan Report ID?</p>
                                <p>Cek PDF receipt yang diunduh setelah submit laporan. Report ID ada di kotak bertuliskan "Tracking ID".</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button 
                        wire:click="trackByReportId"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Track Report
                    </button>
                    <button 
                        wire:click="resetSearch"
                        class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all active:scale-[0.98]">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Error Message -->
            @if($errorMessage)
                <div class="mt-4 rounded-xl bg-red-50 border-2 border-red-200 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800">{{ $errorMessage }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Results -->
        @if($showResults && $reports->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">
                    Found {{ $reports->count() }} {{ $reports->count() === 1 ? 'Report' : 'Reports' }}
                </h2>
            </div>

            @foreach($reports as $report)
            <a href="{{ route('tracking.detail', ['reportId' => $report->report_id]) }}" 
               class="block bg-white rounded-2xl shadow hover:shadow-lg transition-all p-5 group">
                <div class="flex gap-4">
                    <!-- Image -->
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100 group-hover:scale-105 transition-transform">
                        @if(optional($report->item)->photos && $report->item->photos->isNotEmpty())
                            <img src="{{ asset('storage/' . $report->item->photos->first()->photo_url) }}" 
                                 class="w-full h-full object-cover" 
                                 alt="Item photo" />
                        @elseif($report->photo_url)
                            <img src="{{ asset('storage/' . $report->photo_url) }}" 
                                 class="w-full h-full object-cover" 
                                 alt="Report photo" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <!-- Badges -->
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <div class="text-xs text-gray-500 font-mono">
                                #{{ Str::upper(Str::substr($report->report_id, 0, 12)) }}
                            </div>
                            @php
                                $statusConfig = [
                                    'OPEN' => ['color' => 'yellow', 'text' => 'Terbuka'],
                                    'STORED' => ['color' => 'blue', 'text' => 'Tersimpan'],
                                    'MATCHED' => ['color' => 'purple', 'text' => 'Tercocokkan'],
                                    'CLOSED' => ['color' => 'gray', 'text' => 'Ditutup']
                                ];
                                $status = $statusConfig[$report->report_status] ?? ['color' => 'gray', 'text' => $report->report_status];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-700">
                                {{ $status['text'] }}
                            </span>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $report->report_type === 'LOST' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $report->report_type === 'LOST' ? 'Hilang' : 'Ditemukan' }}
                            </span>
                        </div>

                        <!-- Item Name -->
                        <div class="text-base font-bold text-gray-900 truncate mb-1 group-hover:text-blue-600 transition-colors">
                            {{ optional($report->item)->item_name ?? $report->item_name ?? 'Barang' }}
                        </div>

                        <!-- Location & Date -->
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $report->report_location }}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- View Details Arrow -->
                        <div class="mt-2 flex items-center text-blue-600 text-xs font-semibold group-hover:gap-2 transition-all">
                            <span>View Details</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>