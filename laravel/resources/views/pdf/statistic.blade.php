<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistics Report</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 12px; color: #111827; } /* gray-900 */
        .h1 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .muted { color: #6B7280; } /* gray-500 */
        .card { border:1px solid #E5E7EB; border-radius:8px; padding:12px; margin-bottom:10px; }
        .grid { display:flex; flex-wrap:wrap; gap:10px; }
        .col { flex:1 1 160px; border:1px solid #E5E7EB; border-radius:8px; padding:12px; }
        .title { font-size:12px; color:#6B7280; }
        .value { font-size:22px; font-weight:700; margin-top:4px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #E5E7EB; padding:8px; text-align:left; }
        th { background:#F9FAFB; font-weight:600; }
        .badge { display:inline-block; padding:2px 8px; border-radius:9999px; border:1px solid #E5E7EB; font-size:10px; }
    </style>
</head>
<body>
    <div class="h1">Statistics Report</div>
    <div class="muted">Range: {{ $range[0] }} – {{ $range[1] }}</div>

    <div class="grid" style="margin-top:12px;">
        <div class="col">
            <div class="title">Lost Reports</div>
            <div class="value">{{ $totalLost }}</div>
            <div class="muted" style="font-size:10px;">{{ $openLost }} still open</div>
        </div>
        <div class="col">
            <div class="title">Found Reports</div>
            <div class="value">{{ $totalFound }}</div>
            <div class="muted" style="font-size:10px;">{{ $matched }} matched</div>
        </div>
        <div class="col">
            <div class="title">Total Items</div>
            <div class="value">{{ $totalStored + $totalRegistered }}</div>
            <div class="muted" style="font-size:10px;">{{ $totalStored }} in storage</div>
        </div>
        <div class="col">
            <div class="title">Items Claimed</div>
            <div class="value">{{ $totalClaimed }}</div>
            <div class="muted" style="font-size:10px;">Success {{ $successRate }}%</div>
        </div>
    </div>

    <div class="card">
        <div class="title" style="margin-bottom:6px;">Category Distribution (Top)</div>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th width="80">Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoryDistribution as $c)
                <tr>
                    <td>{{ $c['name'] }}</td>
                    <td>{{ $c['count'] }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="muted">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="title" style="margin-bottom:6px;">Recent Activities</div>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Item</th>
                    <th>Reporter</th>
                    <th>Status</th>
                    <th width="120">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $r)
                <tr>
                    <td><span class="badge">{{ $r->report_type }}</span></td>
                    <td>{{ $r->item_name ?? '—' }}</td>
                    <td>{{ optional($r->user)->full_name ?? 'Anonymous' }}</td>
                    <td>{{ $r->report_status }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->report_datetime)->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="muted">No recent activities</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
