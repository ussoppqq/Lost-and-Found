<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Receipt</title>
    <style>
        /* --- Page setup (A4) --- */
        @page {
            margin: 24mm 18mm 22mm 18mm;
        }

        * { box-sizing: border-box; }
        html, body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        body { padding: 0; }

        .header {
            text-align: center;
            margin-bottom: 22px;
            padding-bottom: 12px;
            border-bottom: 3px solid #1f2937;
        }
        .header h1 {
            font-size: 20px;
            color: #1f2937;
            margin: 0 0 4px;
            font-weight: 700;
        }
        .header p {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        .report-id-box {
            background: #f3f4f6;
            border: 2px solid #1f2937;
            border-radius: 8px;
            padding: 12px;
            margin: 16px 0 18px;
            text-align: center;
        }
        .report-id-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .report-id {
            font-size: 15px;
            font-weight: bold;
            color: #1f2937;
            font-family: "DejaVu Sans Mono", "Courier New", monospace;
            word-break: break-word;
        }

        .section {
            margin-bottom: 18px;
            page-break-inside: avoid; /* keep blocks together */
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: 600;
            color: #4b5563;
            padding-right: 10px;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            color: #1f2937;
            word-break: break-word;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #d1d5db;
        }
        .status-open     { background: #fef3c7; color: #92400e; }
        .status-claimed  { background: #d1fae5; color: #065f46; }
        .status-closed   { background: #e5e7eb; color: #374151; }
        .type-lost       { background: #fee2e2; color: #991b1b; }
        .type-found      { background: #dbeafe; color: #1e40af; }

        .description-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin-top: 5px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .instructions {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            margin: 12px 0 18px;
            border-radius: 4px;
        }
        .instructions h3 {
            font-size: 12px;
            color: #1e40af;
            margin: 0 0 6px;
        }
        .instructions p {
            font-size: 11px;
            color: #1e3a8a;
            margin: 0;
        }

        .photo-info {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 6px;
            padding: 8px;
            margin-top: 6px;
            font-size: 11px;
            color: #92400e;
        }

        .footer {
            margin-top: 26px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Report Receipt</h1>
        <p>{{ $report->report_type === 'LOST' ? 'Lost Item Report' : 'Found Item Report' }}</p>
    </div>

    <div class="report-id-box">
        <div class="report-id-label">Report ID</div>
        <div class="report-id">{{ $report->report_id }}</div>
    </div>

    <div class="instructions">
        <h3>How to Track Your Report</h3>
        <p>
            Use the Report ID above to track your report status on our website. 
            <strong>Please save this document for your records.</strong>
        </p>
    </div>

    <div class="section">
        <div class="section-title">Report Information</div>

        <div class="info-row">
            <div class="info-label">Report Type:</div>
            <div class="info-value">
                <span class="badge {{ $report->report_type === 'LOST' ? 'type-lost' : 'type-found' }}">
                    {{ $report->report_type }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                @php
                    $statusClass = match($report->report_status) {
                        'OPEN'     => 'status-open',
                        'CLAIMED'  => 'status-claimed',
                        default    => 'status-closed',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $report->report_status }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Date {{ $report->report_type === 'LOST' ? 'Lost' : 'Found' }}:</div>
            <div class="info-value">
                {{ optional($report->report_datetime)->format('d M Y, H:i') ?? '-' }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Location:</div>
            <div class="info-value">{{ $report->report_location ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Submitted On:</div>
            <div class="info-value">{{ optional($report->created_at)->format('d M Y, H:i') ?? '-' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Item Details</div>

        <div class="info-row">
            <div class="info-label">Item Name:</div>
            <div class="info-value"><strong>{{ $report->item_name }}</strong></div>
        </div>

        @if($report->relationLoaded('category') ? $report->category : optional($report->category)->exists)
            <div class="info-row">
                <div class="info-label">Category:</div>
                <div class="info-value">{{ optional($report->category)->category_name ?? '-' }}</div>
            </div>
        @endif

        <div class="info-row">
            <div class="info-label">Description:</div>
            <div class="info-value"></div>
        </div>
        <div class="description-box">{{ $report->report_description }}</div>

        @if(!empty($report->photo_url))
            <div class="photo-info">
                ℹ️ A photo is attached to this report. View it online using your Report ID.
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Reporter Information</div>

        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ $report->reporter_name ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $report->reporter_phone ?: '-' }}</div>
        </div>

        @if(!empty($report->reporter_email))
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $report->reporter_email }}</div>
            </div>
        @endif
    </div>

    <div class="footer">
        <div>This is an automatically generated receipt. Keep it for tracking purposes.</div>
        <div style="margin-top: 4px;">
            Generated on {{ now()->format('d M Y, H:i:s') }}
        </div>
    </div>
</body>
</html>
