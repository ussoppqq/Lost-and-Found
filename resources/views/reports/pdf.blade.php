<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1 { font-size: 20px; margin-bottom: 12px; }
        h2 { font-size: 14px; margin: 18px 0 8px; }
        .muted { color: #6B7280; }
        .section { margin-bottom: 14px; }
        .row { display: flex; margin-bottom: 6px; }
        .label { width: 160px; color: #374151; }
        .value { flex: 1; font-weight: 600; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .badge-red { background:#FEE2E2; color:#B91C1C; }
        .badge-green { background:#DCFCE7; color:#166534; }
        .badge-blue { background:#DBEAFE; color:#1D4ED8; }
        .badge-yellow { background:#FEF3C7; color:#B45309; }
        .divider { height:1px; background:#E5E7EB; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 8px; border-bottom: 1px solid #E5E7EB; }
    </style>
    
</head>
<body>
    <h1>Report Details</h1>

    <div class="section">
        <div class="row">
            <div class="label">Report ID</div>
            <div class="value">#{{ strtoupper(substr($report->report_id, 0, 12)) }}</div>
        </div>
        <div class="row">
            <div class="label">Report Type</div>
            <div class="value">
                @if($report->report_type === 'LOST')
                    <span class="badge badge-red">Lost Item</span>
                @else
                    <span class="badge badge-green">Found Item</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="label">Report Status</div>
            <div class="value">{{ $report->report_status }}</div>
        </div>
        <div class="row">
            <div class="label">Date</div>
            <div class="value">{{ \Carbon\Carbon::parse($report->report_datetime)->format('d F Y, H:i') }}</div>
        </div>
        <div class="row">
            <div class="label">Location</div>
            <div class="value">{{ $report->report_location }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <h2>Item Information</h2>
    <div class="section">
        <div class="row">
            <div class="label">Item Name</div>
            <div class="value">{{ optional($report->item)->item_name ?? $report->item_name ?? '-' }}</div>
        </div>
        @if(optional($report->item)->brand)
        <div class="row">
            <div class="label">Brand</div>
            <div class="value">{{ $report->item->brand }}</div>
        </div>
        @endif
        @if(optional($report->item)->color)
        <div class="row">
            <div class="label">Color</div>
            <div class="value">{{ $report->item->color }}</div>
        </div>
        @endif
        @php
            $categoryName = data_get($report, 'item.category.category_name');
            $subcategoryName = data_get($report, 'item.category.subcategory_name');
        @endphp
        @if($categoryName)
        <div class="row">
            <div class="label">Category</div>
            <div class="value">
                {{ $categoryName }}
                @if($subcategoryName)
                    / {{ $subcategoryName }}
                @endif
            </div>
        </div>
        @endif
        @if(optional($report->item)->item_description)
        <div class="row">
            <div class="label">Item Description</div>
            <div class="value">{{ $report->item->item_description }}</div>
        </div>
        @endif
    </div>

    <div class="divider"></div>

    <h2>Reporter Information</h2>
    <div class="section">
        <div class="row">
            <div class="label">Name</div>
            <div class="value">{{ optional($report->user)->full_name ?? $report->reporter_name ?? 'Guest User' }}</div>
        </div>
        <div class="row">
            <div class="label">Phone Number</div>
            <div class="value">{{ optional($report->user)->phone_number ?? $report->reporter_phone ?? '-' }}</div>
        </div>
        @if($report->reporter_email || optional($report->user)->email)
        <div class="row">
            <div class="label">Email</div>
            <div class="value">{{ optional($report->user)->email ?? $report->reporter_email }}</div>
        </div>
        @endif
        <div class="row">
            <div class="label">Report Date</div>
            <div class="value">{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y, H:i') }}</div>
        </div>
    </div>

    @php $hasItem = data_get($report, 'item') !== null; @endphp
    @if($hasItem)
    <div class="divider"></div>
    <h2>Item Status</h2>
    <div class="section">
        <div class="row">
            <div class="label">Status</div>
            <div class="value">{{ data_get($report, 'item.item_status', '-') }}</div>
        </div>
        @if(data_get($report, 'item.post'))
        <div class="row">
            <div class="label">Storage Location</div>
            <div class="value">{{ data_get($report, 'item.post.post_name') }}</div>
        </div>
        @endif
        @if(data_get($report, 'item.storage'))
        <div class="row">
            <div class="label">Storage Number</div>
            <div class="value">{{ data_get($report, 'item.storage') }}</div>
        </div>
        @endif
        @if(data_get($report, 'item.retention_until'))
        <div class="row">
            <div class="label">Retention Until</div>
            <div class="value">{{ \Carbon\Carbon::parse(data_get($report, 'item.retention_until'))->format('d F Y') }}</div>
        </div>
        @endif
    </div>
    @endif

    @php $claims = data_get($report, 'item.claims'); @endphp
    @if($claims && $claims->isNotEmpty())
        <div class="divider"></div>
        <h2>Claim History</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Claimant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($claims as $claim)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($claim->created_at)->format('d M Y, H:i') }}</td>
                    <td>{{ $claim->claim_status }}</td>
                    <td>{{ optional($claim->user)->full_name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>


