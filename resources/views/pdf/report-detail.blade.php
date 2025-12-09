<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Detail - {{ $report->report_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #111827;
            padding: 20px;
            background: #ffffff;
        }
        .card {
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card-header {
            background: #ffffff;
            border-bottom: 2px solid #e5e7eb;
            padding: 15px 20px;
        }
        .card-header h2 {
            font-size: 13pt;
            font-weight: bold;
            color: #111827;
        }
        .card-body {
            padding: 20px;
        }
        .detail-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #f3f4f6;
            padding: 12px 0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            display: table-cell;
            width: 30%;
            font-weight: 600;
            color: #6b7280;
            font-size: 9pt;
            vertical-align: top;
            padding-right: 15px;
        }
        .detail-value {
            display: table-cell;
            width: 70%;
            color: #111827;
            font-size: 10pt;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
        }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-purple { background: #e9d5ff; color: #6b21a8; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .mono {
            font-family: 'Courier New', monospace;
            font-size: 9pt;
        }
        .section-divider {
            margin: 15px 0;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    {{-- Photos Section --}}
    @if($report->item && $report->item->photos->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h2>Foto Barang</h2>
        </div>
        <div class="card-body">
            @foreach($report->item->photos->take(4) as $photo)
                <p style="margin-bottom: 10px; color: #6b7280;">Photo {{ $loop->iteration }}: {{ asset('storage/' . $photo->photo_url) }}</p>
            @endforeach
        </div>
    </div>
    @elseif($report->photo_url)
    <div class="card">
        <div class="card-header">
            <h2>Foto Laporan</h2>
        </div>
        <div class="card-body">
            <p style="color: #6b7280;">{{ asset('storage/' . $report->photo_url) }}</p>
        </div>
    </div>
    @endif

    {{-- Report Details --}}
    <div class="card">
        <div class="card-header">
            <h2>Detail Laporan</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <div class="detail-label">ID Laporan</div>
                <div class="detail-value mono"># {{ strtoupper(substr($report->report_id, 0, 12)) }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tipe Laporan</div>
                <div class="detail-value">
                    <span class="badge {{ $report->report_type === 'LOST' ? 'badge-red' : 'badge-green' }}">
                        {{ $report->report_type === 'LOST' ? 'Barang Hilang' : 'Barang Ditemukan' }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status Laporan</div>
                <div class="detail-value">
                    @php
                        $statusConfig = [
                            'OPEN' => ['class' => 'badge-yellow', 'text' => 'Terbuka'],
                            'STORED' => ['class' => 'badge-blue', 'text' => 'Tersimpan'],
                            'MATCHED' => ['class' => 'badge-purple', 'text' => 'Tercocokkan'],
                            'CLOSED' => ['class' => 'badge-gray', 'text' => 'Ditutup']
                        ];
                        $status = $statusConfig[$report->report_status] ?? ['class' => 'badge-gray', 'text' => $report->report_status];
                    @endphp
                    <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Nama Barang</div>
                <div class="detail-value"><strong>{{ $report->item->item_name ?? $report->item_name ?? '-' }}</strong></div>
            </div>

            @if($report->item)
            <div class="detail-row">
                <div class="detail-label">Brand</div>
                <div class="detail-value">{{ $report->item->brand ?? '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Warna</div>
                <div class="detail-value">{{ $report->item->color ?? '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kategori</div>
                <div class="detail-value">
                    {{ $report->item->category->category_name ?? '-' }}
                    @if($report->item->category && $report->item->category->subcategory_name)
                        <span style="color: #6b7280;">/ {{ $report->item->category->subcategory_name }}</span>
                    @endif
                </div>
            </div>
            @endif

            <div class="detail-row">
                <div class="detail-label">Tanggal Kejadian</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($report->report_datetime)->format('d F Y, H:i') }} WIB</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Lokasi</div>
                <div class="detail-value">{{ $report->report_location }}</div>
            </div>

            @if($report->report_description)
            <div class="detail-row">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value" style="color: #374151; line-height: 1.6;">{{ $report->report_description }}</div>
            </div>
            @endif

            @if($report->item && $report->item->item_description)
            <div class="detail-row">
                <div class="detail-label">Detail Item</div>
                <div class="detail-value" style="color: #374151; line-height: 1.6;">{{ $report->item->item_description }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Item Status --}}
    @if($report->item)
    <div class="card">
        <div class="card-header">
            <h2>Status Barang</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @php
                        $itemStatusConfig = [
                            'REGISTERED' => ['class' => 'badge-gray', 'text' => 'Terdaftar'],
                            'STORED' => ['class' => 'badge-blue', 'text' => 'Tersimpan'],
                            'CLAIMED' => ['class' => 'badge-yellow', 'text' => 'Diklaim'],
                            'DISPOSED' => ['class' => 'badge-red', 'text' => 'Dibuang'],
                            'RETURNED' => ['class' => 'badge-green', 'text' => 'Dikembalikan']
                        ];
                        $itemStatus = $itemStatusConfig[$report->item->item_status] ?? ['class' => 'badge-gray', 'text' => $report->item->item_status];
                    @endphp
                    <span class="badge {{ $itemStatus['class'] }}">{{ $itemStatus['text'] }}</span>
                </div>
            </div>

            @if($report->item->post)
            <div class="detail-row">
                <div class="detail-label">Lokasi Penyimpanan</div>
                <div class="detail-value">
                    <strong>{{ $report->item->post->post_name }}</strong><br>
                    <span style="font-size: 9pt; color: #6b7280;">{{ $report->item->post->post_address }}</span>
                </div>
            </div>

            @if($report->item->storage)
            <div class="detail-row">
                <div class="detail-label">Nomor Rak/Storage</div>
                <div class="detail-value mono">{{ $report->item->storage }}</div>
            </div>
            @endif
            @endif

            @if($report->item->retention_until)
            <div class="detail-row">
                <div class="detail-label">Retensi Hingga</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($report->item->retention_until)->format('d F Y') }}
                    @php
                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($report->item->retention_until), false);
                    @endphp
                    @if($daysLeft > 0)
                        <span style="font-size: 9pt; color: #ea580c;">({{ $daysLeft }} hari tersisa)</span>
                    @elseif($daysLeft < 0)
                        <span style="font-size: 9pt; color: #dc2626;">(Melewati {{ abs($daysLeft) }} hari)</span>
                    @else
                        <span style="font-size: 9pt; color: #ca8a04;">(Hari ini)</span>
                    @endif
                </div>
            </div>
            @endif

            @if($report->item->sensitivity_level === 'RESTRICTED')
            <div class="detail-row">
                <div class="detail-label">Level Sensitifitas</div>
                <div class="detail-value">
                    <span class="badge badge-red">🔒 Terbatas</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Claims History --}}
    @if($report->item && $report->item->claims->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h2>Riwayat Klaim</h2>
        </div>
        <div class="card-body">
            @foreach($report->item->claims as $claim)
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 10px;">
                <div style="margin-bottom: 10px;">
                    @php
                        $claimStatusConfig = [
                            'PENDING' => ['class' => 'badge-yellow', 'text' => 'Menunggu'],
                            'APPROVED' => ['class' => 'badge-green', 'text' => 'Disetujui'],
                            'REJECTED' => ['class' => 'badge-red', 'text' => 'Ditolak'],
                            'RELEASED' => ['class' => 'badge-blue', 'text' => 'Diserahkan']
                        ];
                        $claimStatus = $claimStatusConfig[$claim->claim_status] ?? ['class' => 'badge-gray', 'text' => $claim->claim_status];
                    @endphp
                    <span class="badge {{ $claimStatus['class'] }}">{{ $claimStatus['text'] }}</span>
                    <span style="float: right; font-size: 9pt; color: #6b7280;">{{ \Carbon\Carbon::parse($claim->created_at)->format('d M Y') }}</span>
                </div>

                <div class="detail-row" style="border: none; padding: 5px 0;">
                    <div class="detail-label" style="font-size: 8pt;">Pengklaim</div>
                    <div class="detail-value" style="font-size: 9pt; font-weight: 600;">{{ $claim->user->full_name ?? '-' }}</div>
                </div>

                @if($claim->pickup_schedule)
                <div class="detail-row" style="border: none; padding: 5px 0;">
                    <div class="detail-label" style="font-size: 8pt;">Jadwal Pickup</div>
                    <div class="detail-value" style="font-size: 9pt;">{{ \Carbon\Carbon::parse($claim->pickup_schedule)->format('d F Y, H:i') }} WIB</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    {{-- Reporter Info --}}
    <div class="card">
        <div class="card-header">
            <h2>Informasi Pelapor</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <div class="detail-label">Nama</div>
                <div class="detail-value"><strong>{{ $report->user->full_name ?? $report->reporter_name ?? 'Guest User' }}</strong></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Nomor HP</div>
                <div class="detail-value mono">{{ $report->user->phone_number ?? $report->reporter_phone ?? '-' }}</div>
            </div>

            @if($report->reporter_email || ($report->user && $report->user->email))
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $report->user->email ?? $report->reporter_email }}</div>
            </div>
            @endif

            <div class="detail-row">
                <div class="detail-label">Tanggal Laporan</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 8px; text-align: center;">
        <p style="font-size: 9pt; color: #6b7280;">
            <strong>Generated:</strong> {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
        </p>
    </div>
</body>
</html>
