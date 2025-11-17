{{-- resources/views/livewire/tracking-detail.blade.php --}}
<div class="min-h-screen bg-white py-12 px-4 print:bg-white">
    {{-- ==== PRINT CSS ==== --}}
    @once
    @push('styles')
    <style>
      @page { size: A4; margin: 16mm; }
      * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
      .no-print, .no-print * { display: none !important; }
      body { background: #fff !important; }
      .bg-gradient-to-br { background: #fff !important; }
      .shadow, .shadow-lg, .shadow-xl, .shadow-2xl { box-shadow: none !important; }
      .print\:max-w-none { max-width: none !important; }
      .page-break { page-break-before: always; }
      .avoid-break { page-break-inside: avoid; }
      @media print {
        html, body { height: auto; }
        #print-area { width: 100% !important; max-width: 100% !important; }
      }
    </style>
    @endpush
    @endonce

    <div class="max-w-6xl mx-auto print:max-w-none" id="print-area">
        <!-- Back Button (no-print) -->
        <button
            onclick="history.back()"
            class="no-print inline-flex items-center text-gray-800 hover:text-gray-900 mb-6 transition-colors font-semibold"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </button>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Item Photos -->
                @if($this->report && $this->report->item && $this->report->item->photos->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Foto Barang</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($this->report->item->photos as $photo)
                        <img 
                            src="{{ asset('storage/' . $photo->photo_url) }}" 
                            alt="{{ $photo->alt_text ?? 'Item photo' }}"
                            class="w-full h-64 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                            wire:click="openImageModal('{{ asset('storage/' . $photo->photo_url) }}')"
                        >
                        @endforeach
                    </div>
                </div>
                @elseif($this->report && $this->report->photo_url)
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Foto Laporan</h2>
                    <img 
                        src="{{ asset('storage/' . $this->report->photo_url) }}" 
                        alt="Report photo"
                        class="w-full h-96 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                        wire:click="openImageModal('{{ asset('storage/' . $this->report->photo_url) }}')"
                    >
                </div>
                @endif

                <!-- Report Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Laporan</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">ID Laporan</div>
                            <div class="w-2/3 text-sm text-gray-900 font-mono">
                                #{{ Str::upper(Str::substr($this->report->report_id, 0, 12)) }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Tipe Laporan</div>
                            <div class="w-2/3">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $this->report->report_type === 'LOST' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $this->report->report_type === 'LOST' ? 'Barang Hilang' : 'Barang Ditemukan' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Status Laporan</div>
                            <div class="w-2/3">
                                @php
                                    $statusConfig = [
                                        'OPEN' => ['color' => 'yellow', 'text' => 'Terbuka'],
                                        'STORED' => ['color' => 'blue', 'text' => 'Tersimpan'],
                                        'MATCHED' => ['color' => 'purple', 'text' => 'Tercocokkan'],
                                        'CLOSED' => ['color' => 'gray', 'text' => 'Ditutup']
                                    ];
                                    $status = $statusConfig[$this->report->report_status] ?? ['color' => 'gray', 'text' => $this->report->report_status];
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-700">
                                    {{ $status['text'] }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Nama Barang</div>
                            <div class="w-2/3 text-sm font-semibold text-gray-900">
                                {{ $this->report->item->item_name ?? $this->report->item_name ?? '-' }}
                            </div>
                        </div>

                        @if($this->report->item)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Brand</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ $this->report->item->brand ?? '-' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Warna</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ $this->report->item->color ?? '-' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Kategori</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ $this->report->item->category->category_name ?? '-' }}
                                @if($this->report->item->category && $this->report->item->category->subcategory_name)
                                    <span class="text-gray-500">/ {{ $this->report->item->category->subcategory_name }}</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Tanggal Kejadian</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($this->report->report_datetime)->format('d F Y, H:i') }} WIB
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Lokasi</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ $this->report->report_location }}
                            </div>
                        </div>

                        @if($this->report->report_description)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Deskripsi</div>
                            <div class="w-2/3 text-sm text-gray-700">
                                {{ $this->report->report_description }}
                            </div>
                        </div>
                        @endif

                        @if($this->report->item && $this->report->item->item_description)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Detail Item</div>
                            <div class="w-2/3 text-sm text-gray-700">
                                {{ $this->report->item->item_description }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Item Status (if exists) -->
                @if($this->report->item)
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Status Barang</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Status</div>
                            <div class="w-2/3">
                                @php
                                    $itemStatusConfig = [
                                        'REGISTERED' => ['color' => 'gray', 'text' => 'Terdaftar'],
                                        'STORED' => ['color' => 'blue', 'text' => 'Tersimpan'],
                                        'CLAIMED' => ['color' => 'yellow', 'text' => 'Diklaim'],
                                        'DISPOSED' => ['color' => 'red', 'text' => 'Dibuang'],
                                        'RETURNED' => ['color' => 'green', 'text' => 'Dikembalikan']
                                    ];
                                    $itemStatus = $itemStatusConfig[$this->report->item->item_status] ?? ['color' => 'gray', 'text' => $this->report->item->item_status];
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-{{ $itemStatus['color'] }}-100 text-{{ $itemStatus['color'] }}-700">
                                    {{ $itemStatus['text'] }}
                                </span>
                            </div>
                        </div>

                        @if($this->report->item->post)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Lokasi Penyimpanan</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                <div class="font-semibold">{{ $this->report->item->post->post_name }}</div>
                                <div class="text-gray-600 text-xs mt-1">{{ $this->report->item->post->post_address }}</div>
                            </div>
                        </div>

                        @if($this->report->item->storage)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Nomor Rak/Storage</div>
                            <div class="w-2/3 text-sm text-gray-900 font-mono">
                                {{ $this->report->item->storage }}
                            </div>
                        </div>
                        @endif
                        @endif

                        @if($this->report->item->retention_until)
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Retensi Hingga</div>
                            <div class="w-2/3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($this->report->item->retention_until)->format('d F Y') }}
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($this->report->item->retention_until), false);
                                @endphp
                                @if($daysLeft > 0)
                                    <span class="text-orange-600 text-xs">({{ $daysLeft }} hari tersisa)</span>
                                @elseif($daysLeft < 0)
                                    <span class="text-red-600 text-xs">(Melewati {{ abs($daysLeft) }} hari)</span>
                                @else
                                    <span class="text-yellow-600 text-xs">(Hari ini)</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($this->report->item->sensitivity_level === 'RESTRICTED')
                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Level Sensitifitas</div>
                            <div class="w-2/3">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Terbatas
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Claims (if any) -->
                @if($this->report->item && $this->report->item->claims->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Riwayat Klaim</h2>
                    
                    <div class="space-y-4">
                        @foreach($this->report->item->claims as $claim)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    @php
                                        $claimStatusConfig = [
                                            'PENDING' => ['color' => 'yellow', 'text' => 'Menunggu'],
                                            'APPROVED' => ['color' => 'green', 'text' => 'Disetujui'],
                                            'REJECTED' => ['color' => 'red', 'text' => 'Ditolak'],
                                            'RELEASED' => ['color' => 'blue', 'text' => 'Diserahkan']
                                        ];
                                        $claimStatus = $claimStatusConfig[$claim->claim_status] ?? ['color' => 'gray', 'text' => $claim->claim_status];
                                    @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-{{ $claimStatus['color'] }}-100 text-{{ $claimStatus['color'] }}-700">
                                        {{ $claimStatus['text'] }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($claim->created_at)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <div class="w-1/3 text-xs font-medium text-gray-500">Pengklaim</div>
                                    <div class="w-2/3 text-sm text-gray-900">
                                        {{ $claim->user->full_name ?? '-' }}
                                    </div>
                                </div>

                                @if($claim->pickup_schedule)
                                <div class="flex items-start">
                                    <div class="w-1/3 text-xs font-medium text-gray-500">Jadwal Pickup</div>
                                    <div class="w-2/3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($claim->pickup_schedule)->format('d F Y, H:i') }} WIB
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Reporter Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pelapor</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Nama</div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $this->report->user->full_name ?? $this->report->reporter_name ?? 'Guest User' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500 mb-1">Nomor HP</div>
                            <div class="text-sm text-gray-900">
                                {{ $this->report->user->phone_number ?? $this->report->reporter_phone ?? '-' }}
                            </div>
                        </div>

                        @if($this->report->reporter_email || ($this->report->user && $this->report->user->email))
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Email</div>
                            <div class="text-sm text-gray-900 break-all">
                                {{ $this->report->user->email ?? $this->report->reporter_email }}
                            </div>
                        </div>
                        @endif

                        <div>
                            <div class="text-xs text-gray-500 mb-1">Tanggal Laporan</div>
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($this->report->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (no-print) -->
                <div class="bg-white rounded-2xl shadow-lg p-6 no-print">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Bantuan</h3>
                    
                    <div class="space-y-3">
                        <a href="tel:{{ $this->report->user->phone_number ?? $this->report->reporter_phone }}" 
                           class="flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Hubungi via Telepon
                        </a>

                        @if($this->report->reporter_email || ($this->report->user && $this->report->user->email))
                        <a href="mailto:{{ $this->report->user->email ?? $this->report->reporter_email }}"
                           class="flex items-center justify-center w-full px-4 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 active:scale-[0.98] transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Kirim Email
                        </a>
                        @endif

                        {{-- Tombol UNDUH PDF (server-side Dompdf) --}}
                        <a href="{{ route('reports.pdf', $this->report) }}" target="_blank"
                           class="flex items-center justify-center w-full px-4 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 active:scale-[0.98] transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12v9m0 0l-3-3m3 3l3-3M12 3v9" />
                            </svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>

                <!-- Timeline Status -->
                <div class="bg-white rounded-2xl shadow-lg p-6 avoid-break">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline Status</h3>
                    
                    <div class="relative">
                        <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <div class="relative space-y-6">
                            <!-- Created -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-4 h-4 rounded-full bg-green-500 ring-4 ring-white z-10"></div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">Laporan Dibuat</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($this->report->created_at)->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            @if($this->report->item)
                            <!-- Stored -->
                            @if($this->report->item->item_status === 'STORED' || in_array($this->report->item->item_status, ['CLAIMED', 'DISPOSED', 'RETURNED']))
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-4 h-4 rounded-full bg-blue-500 ring-4 ring-white z-10"></div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">Barang Tersimpan</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $this->report->item->post->post_name ?? 'Lokasi penyimpanan' }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Claimed -->
                            @if($this->report->item->claims->isNotEmpty())
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-4 h-4 rounded-full bg-yellow-500 ring-4 ring-white z-10"></div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">Klaim Dibuat</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($this->report->item->claims->first()->created_at)->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Final Status -->
                            @if(in_array($this->report->item->item_status, ['RETURNED', 'DISPOSED']))
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-4 h-4 rounded-full {{ $this->report->item->item_status === 'RETURNED' ? 'bg-green-600' : 'bg-red-500' }} ring-4 ring-white z-10"></div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $this->report->item->item_status === 'RETURNED' ? 'Barang Dikembalikan' : 'Barang Dibuang' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($this->report->item->updated_at)->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal (no-print) -->
    @if($this->showImageModal)
    <div class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-90" wire:click="closeImageModal">
        <div class="flex items-center justify-center min-h-screen p-4">
            <img src="{{ $this->modalImageSrc }}" alt="Full size" class="max-w-full max-h-screen rounded-lg">
        </div>
        <button wire:click="closeImageModal" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif
</div>
