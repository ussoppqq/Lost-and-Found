<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Receipt - {{ $report->report_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1f2937;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24pt;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            font-size: 10pt;
        }
        .tracking-box {
            background: #f3f4f6;
            border: 3px solid #1f2937;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .tracking-box .label {
            font-size: 10pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .tracking-box .id {
            font-size: 14pt;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 1px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            line-height: 1.4;
        }
        .tracking-instruction {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .tracking-instruction .title {
            font-size: 11pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .tracking-instruction .steps {
            font-size: 9pt;
            color: #1e3a8a;
            line-height: 1.8;
        }
        .tracking-instruction .steps li {
            margin-bottom: 5px;
        }
        .tracking-url {
            background: #f0f9ff;
            border: 2px dashed #3b82f6;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            margin: 15px 0;
        }
        .tracking-url .url {
            font-size: 10pt;
            font-weight: bold;
            color: #1e40af;
            font-family: 'Courier New', monospace;
        }
        .section {
            margin: 25px 0;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #4b5563;
            padding: 5px 0;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #1f2937;
            padding: 5px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9pt;
            color: #6b7280;
        }
        .important-note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            font-size: 10pt;
        }
        .important-note strong {
            color: #92400e;
        }
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 REPORT ID</h1>
        <p>{{ $reportType }} Report Confirmation</p>
    </div>

    <div class="tracking-box">
        <div class="label">🔍 Your Report ID</div>
        <div class="id">{{ $report->report_id }}</div>
    </div>

    <div class="important-note">
        <strong>⚠️ IMPORTANT - SAVE THIS DOCUMENT:</strong> This Tracking ID is your unique reference number. Keep this PDF safe to track your item status and communicate with support.
    </div>

    <div class="tracking-instruction">
        <div class="title">📱 How to Track Your Item:</div>
        <ol class="steps">
            <li><strong>Visit our tracking page:</strong> Go to the URL below or scan the QR code</li>
            <li><strong>Enter your Tracking ID:</strong> Copy the ID from the box above</li>
            <li><strong>View real-time status:</strong> See updates on your item's location and status</li>
            <li><strong>Get notifications:</strong> We'll contact you if there are any updates</li>
        </ol>
    </div>

    <div class="tracking-url">
        <div style="font-size: 9pt; color: #6b7280; margin-bottom: 5px;">Track online at:</div>
        <div class="url">{{ url('/tracking') }}</div>
    </div>

    {{-- Optional: Add QR Code if you have QR generator package
    <div class="qr-section">
        <p style="font-size: 9pt; color: #6b7280; margin-bottom: 10px;">Scan to track instantly:</p>
        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(150)->generate(url('/tracking?id=' . $report->report_id))) }}" alt="QR Code">
    </div>
    --}}

    <div class="section">
        <div class="section-title">Reporter Information</div>
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ $report->reporter_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $report->reporter_phone }}</div>
        </div>
        @if($report->reporter_email)
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $report->reporter_email }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Item Details</div>
        <div class="info-row">
            <div class="info-label">Item Name:</div>
            <div class="info-value">{{ $report->item_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Category:</div>
            <div class="info-value">{{ $report->category->category_name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Description:</div>
            <div class="info-value">{{ $report->report_description }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Location:</div>
            <div class="info-value">{{ $report->report_location }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date & Time:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($report->report_datetime)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">{{ ucfirst(strtolower($report->report_status)) }}</div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Generated on:</strong> {{ $generatedAt }}</p>
        <p style="margin-top: 10px;">This is an automatically generated receipt.</p>
        <p style="margin-top: 5px; font-weight: bold;">Keep this document for tracking and verification purposes.</p>
    </div>
</body>
</html>