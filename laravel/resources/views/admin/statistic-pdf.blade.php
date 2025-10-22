<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Statistics PDF</title>
  <style>
    @page { margin: 16mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    h1,h2,h3 { margin: 0 0 8px; }
    .muted { color:#666; }
    .section { margin-bottom: 14px; }
    .table { width:100%; border-collapse: collapse; }
    .table th, .table td { padding:6px 8px; border:1px solid #e5e7eb; vertical-align: top; }
    .grid { display: table; width:100%; }
    .col { display: table-cell; width: 33%; vertical-align: top; }
    .badge { display:inline-block; padding:3px 8px; border-radius: 999px; font-size:10px; border:1px solid #bbb; }
  </style>
</head>
<body>
  <h2 style="margin-bottom:2px;">{{ $companyName }}</h2>
  <div class="muted" style="margin-bottom:12px;">Lost &amp; Found Statistics</div>

  <div class="section">
    <div><strong>Range:</strong>
      {{ $startAt->timezone($tz)->format('d M Y') }} — {{ $endAt->timezone($tz)->format('d M Y') }}
    </div>
  </div>

  <div class="section">
    <div class="grid">
      <div class="col">
        <div class="badge">Total</div>
        <div style="font-size:22px; font-weight:bold; margin-top:4px;">{{ $total }}</div>
      </div>
      <div class="col">
        <div class="badge">Lost</div>
        <div style="font-size:22px; font-weight:bold; color:#dc2626; margin-top:4px;">{{ $lost }}</div>
      </div>
      <div class="col">
        <div class="badge">Found</div>
        <div style="font-size:22px; font-weight:bold; color:#16a34a; margin-top:4px;">{{ $found }}</div>
      </div>
    </div>
  </div>

  <div class="section">
    <h3>Daily Breakdown</h3>
    <table class="table" style="margin-top:6px;">
      <thead>
        <tr>
          <th style="width:40%;">Date</th>
          <th style="width:30%;">Lost</th>
          <th style="width:30%;">Found</th>
        </tr>
      </thead>
      <tbody>
        @foreach($daily as $row)
          <tr>
            <td>{{ $row['date'] }}</td>
            <td>{{ $row['lost'] }}</td>
            <td>{{ $row['found'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="muted" style="margin-top:10px;">
    Generated at {{ now($tz)->format('d M Y H:i') }} ({{ $tz }})
  </div>
  
</body>
</html>
