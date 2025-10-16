<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Tracking Barang</h1>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="grid sm:grid-cols-[1fr_auto] gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP Pelapor</label>
                    <input type="text"
                           wire:model.defer="phoneNumber"
                           placeholder="Masukkan nomor HP"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    @error('phoneNumber')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex gap-2">
                    <button wire:click="trackItem"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Cari</button>
                    <button wire:click="resetSearch"
                            class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Reset</button>
                </div>
            </div>
            @if($errorMessage)
                <div class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
            @endif
        </div>

        <!-- Results -->
        @if($showResults)
        <div class="space-y-4">
            @foreach($reports as $report)
            <a href="{{ route('tracking.detail', ['reportId' => $report->report_id]) }}" class="block bg-white rounded-2xl shadow hover:shadow-md transition-shadow p-5">
                <div class="flex gap-4">
                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                        @if(optional($report->item)->photos && $report->item->photos->isNotEmpty())
                            <img src="{{ asset('storage/' . $report->item->photos->first()->photo_url) }}" class="w-full h-full object-cover" alt="Foto barang" />
                        @elseif($report->photo_url)
                            <img src="{{ asset('storage/' . $report->photo_url) }}" class="w-full h-full object-cover" alt="Foto laporan" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Tidak ada foto</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="text-xs text-gray-500">#{{ Str::upper(Str::substr($report->report_id, 0, 12)) }}</div>
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
                        <div class="text-sm font-semibold text-gray-900 truncate">
                            {{ optional($report->item)->item_name ?? $report->item_name ?? 'Barang' }}
                        </div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ $report->report_location }} • {{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
