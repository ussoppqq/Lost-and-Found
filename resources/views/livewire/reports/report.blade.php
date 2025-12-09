{{-- resources/views/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Report PDF</title>
  <style>
    @page { margin: 16mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    h1,h2,h3 { margin: 0 0 8px; }
    .muted { color: #666; }
    .section { margin-bottom: 14px; }
    .table { width:100%; border-collapse: collapse; }
    .table td { padding:6px 8px; vertical-align: top; }
    .head { width: 32%; color:#555; white-space: nowrap; }
    .badge { display:inline-block; padding:3px 8px; border-radius: 999px; font-size:10px; border:1px solid #bbb; }
    .page-break { page-break-before: always; }
    img { max-width: 100%; height: auto; }
    .title { font-size: 18px; font-weight: bold; margin-bottom: 8px; }
    .subtitle { font-size: 12px; color:#555; margin-bottom: 14px; }
  </style>
</head>
<body>
  <div class="title">Detail Laporan</div>
  <div class="subtitle">
    ID: #{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($report->report_id ?? $report->id, 0, 12)) }}
  </div>

  <table class="table">
    <tr>
      <td class="head">Tipe</td>
      <td>
        <span class="badge">{{ $report->report_type === 'LOST' ? 'Barang Hilang' : 'Barang Ditemukan' }}</span>
      </td>
    </tr>
    <tr>
      <td class="head">Status Laporan</td>
      <td>{{ $report->report_status }}</td>
    </tr>
    <tr>
      <td class="head">Nama Barang</td>
      <td>{{ $report->item->item_name ?? $report->item_name ?? '-' }}</td>
    </tr>
    <tr>
      <td class="head">Tanggal Kejadian</td>
      <td>{{ \Carbon\Carbon::parse($report->report_datetime)->format('d F Y, H:i') }} WIB</td>
    </tr>
    <tr>
      <td class="head">Lokasi</td>
      <td>{{ $report->report_location }}</td>
    </tr>
    @if($report->report_description)
    <tr>
      <td class="head">Deskripsi</td>
      <td>{{ $report->report_description }}</td>
    </tr>
    @endif
  </table>

  @if($report->item)
    <div class="section">
      <h3>Status Barang</h3>
      <table class="table">
        <tr><td class="head">Status</td><td>{{ $report->item->item_status }}</td></tr>
        @if($report->item->post)
        <tr><td class="head">Lokasi Penyimpanan</td><td>{{ $report->item->post->post_name }} — {{ $report->item->post->post_address }}</td></tr>
        @endif
        @if($report->item->storage)
        <tr><td class="head">Rak/Storage</td><td>{{ $report->item->storage }}</td></tr>
        @endif
        @if($report->item->retention_until)
        <tr><td class="head">Retensi Hingga</td><td>{{ \Carbon\Carbon::parse($report->item->retention_until)->format('d F Y') }}</td></tr>
        @endif
      </table>
    </div>

    @if($report->item->claims->isNotEmpty())
    <div class="section page-break">
      <h3>Riwayat Klaim</h3>
      <table class="table">
        @foreach($report->item->claims as $claim)
          <tr>
            <td class="head">{{ \Carbon\Carbon::parse($claim->created_at)->format('d M Y') }}</td>
            <td>{{ $claim->user->full_name ?? '-' }} — {{ $claim->claim_status }}</td>
          </tr>
        @endforeach
      </table>
    </div>
    @endif
  @endif

  <div class="section">
    <h3>Informasi Pelapor</h3>
    <table class="table">
      <tr><td class="head">Nama</td><td>{{ $report->user->full_name ?? $report->reporter_name ?? 'Guest User' }}</td></tr>
      <tr><td class="head">Nomor HP</td><td>{{ $report->user->phone_number ?? $report->reporter_phone ?? '-' }}</td></tr>
      @if($report->reporter_email || ($report->user && $report->user->email))
      <tr><td class="head">Email</td><td>{{ $report->user->email ?? $report->reporter_email }}</td></tr>
      @endif
      <tr><td class="head">Tanggal Laporan</td><td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y, H:i') }}</td></tr>
    </table>
  </div>

  @if($report->item && $report->item->photos->isNotEmpty())
    <div class="section page-break">
      <h3>Foto Barang</h3>
      @foreach($report->item->photos as $photo)
        <div style="margin-bottom:8px;">
          {{-- Dompdf butuh path file lokal, bukan URL --}}
          <img src="{{ public_path('storage/'.$photo->photo_url) }}" alt="{{ $photo->alt_text ?? 'Item photo' }}">
        </div>
      @endforeach
    </div>
  @endif
</body>
</html>
