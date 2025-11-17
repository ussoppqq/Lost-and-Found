<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistics Report</title>
    <style>
        * { 
            font-family: 'Segoe UI', DejaVu Sans, sans-serif; 
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            font-size: 11px; 
            color: #111827;
            background: #F9FAFB;
            padding: 15px;
        }
        
        /* Header Section */
        .header {
            background: white;
            border-bottom: 3px solid #E5E7EB;
            padding: 20px;
            margin-bottom: 20px;
        }
        .main-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .date-info {
            display: flex;
            align-items: center;
            color: #6B7280;
            font-size: 11px;
            margin-top: 8px;
        }
        .calendar-icon {
            width: 16px;
            height: 16px;
            margin-right: 6px;
        }

        /* Stats Cards - 4 Columns */
        .stats-row {
            display: flex;
            gap: 12px;
            margin-bottom: 15px;
        }
        .stat-card {
            flex: 1;
            border-radius: 10px;
            padding: 18px;
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-card.blue {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        }
        .stat-card.green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        .stat-card.purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }
        .stat-card.yellow {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }
        
        .stat-header {
            font-size: 9px;
            opacity: 0.9;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .stat-details {
            font-size: 8px;
            opacity: 0.95;
        }
        .detail-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 12px;
            margin-right: 6px;
            margin-top: 4px;
        }

        /* Chart Cards */
        .chart-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .chart-card {
            flex: 1;
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chart-card.full {
            flex: 1 1 100%;
        }
        .chart-header {
            padding: 15px 18px;
            border-bottom: 1px solid #E5E7EB;
        }
        .chart-title {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }
        .chart-body {
            padding: 20px;
            text-align: center;
        }
        .chart-img {
            max-width: 100%;
            height: auto;
        }

        /* Table Styling */
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 15px;
        }
        .table-header {
            padding: 15px 18px;
            border-bottom: 1px solid #E5E7EB;
        }
        .table-title {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }
        .table-subtitle {
            font-size: 10px;
            color: #6B7280;
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #F9FAFB;
        }
        th {
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #E5E7EB;
        }
        td {
            padding: 12px;
            font-size: 10px;
            color: #374151;
            border-bottom: 1px solid #F3F4F6;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-lost { 
            background: #FEE2E2; 
            color: #991B1B; 
        }
        .badge-found { 
            background: #D1FAE5; 
            color: #065F46; 
        }
        .badge-open { 
            background: #DBEAFE; 
            color: #1E40AF; 
        }
        .badge-closed { 
            background: #E5E7EB; 
            color: #374151; 
        }
        .badge-matched { 
            background: #FEF3C7; 
            color: #92400E; 
        }
        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
        }
        .badge-approved {
            background: #D1FAE5;
            color: #065F46;
        }
        .badge-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }
        .badge-released {
            background: #E0E7FF;
            color: #3730A3;
        }

        /* Icon Simulation */
        .icon-box {
            float: right;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* No Data State */
        .no-data {
            text-align: center;
            color: #9CA3AF;
            padding: 40px 20px;
            font-style: italic;
            font-size: 11px;
        }

        /* Footer */
        .footer {
            margin-top: 25px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .footer-line {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="main-title">Statistics Dashboard</div>
        <div class="date-info">
            <svg class="calendar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ $range[0] }} - {{ $range[1] }}
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="stats-row">
        <!-- Total Reports Card -->
        <div class="stat-card blue">
            <div class="icon-box">📋</div>
            <div class="stat-header">Total Reports</div>
            <div class="stat-number">{{ $totalReports }}</div>
            <div class="stat-details">
                <span class="detail-badge">Lost: {{ $lostReports }}</span>
                <span class="detail-badge">Found: {{ $foundReports }}</span>
            </div>
        </div>

        <!-- Total Items Card -->
        <div class="stat-card green">
            <div class="icon-box">📦</div>
            <div class="stat-header">Total Items</div>
            <div class="stat-number">{{ $totalItems }}</div>
            <div class="stat-details">
                <span class="detail-badge">Stored: {{ $storedItems }}</span>
                <span class="detail-badge">Claimed: {{ $claimedItems }}</span>
            </div>
        </div>

        <!-- Total Claims Card -->
        <div class="stat-card purple">
            <div class="icon-box">📝</div>
            <div class="stat-header">Total Claims</div>
            <div class="stat-number">{{ $totalClaims }}</div>
            <div class="stat-details">
                <span class="detail-badge">Pending: {{ $pendingClaims }}</span>
                <span class="detail-badge">Approved: {{ $approvedClaims }}</span>
            </div>
        </div>

        <!-- Matched Reports Card -->
        <div class="stat-card yellow">
            <div class="icon-box">✓</div>
            <div class="stat-header">Matched Reports</div>
            <div class="stat-number">{{ $matchedReports }}</div>
            <div class="stat-details">
                <span class="detail-badge">Open: {{ $openReports }}</span>
                <span class="detail-badge">Closed: {{ $closedReports }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Report Types & Item Status -->
    <div class="chart-row">
        <!-- Report Types Distribution -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Report Types Distribution</div>
            </div>
            <div class="chart-body">
                @if($lostReports > 0 || $foundReports > 0)
                    <img src="{{ $reportTypeChartUrl }}" class="chart-img" alt="Report Types">
                @else
                    <div class="no-data">No data available</div>
                @endif
            </div>
        </div>

        <!-- Item Status Overview -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Item Status Overview</div>
            </div>
            <div class="chart-body">
                @if($totalItems > 0)
                    <img src="{{ $itemStatusChartUrl }}" class="chart-img" alt="Item Status">
                @else
                    <div class="no-data">No data available</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="chart-row">
        <div class="chart-card full">
            <div class="chart-header">
                <div class="chart-title">Daily Report Trends</div>
                <div class="table-subtitle">Reports over time by type</div>
            </div>
            <div class="chart-body">
                @if(!empty($trendChartData))
                    <img src="{{ $trendChartUrl }}" class="chart-img" alt="Trends">
                @else
                    <div class="no-data">No trend data available for this period</div>
                @endif
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Charts Row 2: Claims & Categories -->
    <div class="chart-row">
        <!-- Claim Status Breakdown -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Claim Status Breakdown</div>
            </div>
            <div class="chart-body">
                @if($totalClaims > 0)
                    <img src="{{ $claimStatusChartUrl }}" class="chart-img" alt="Claim Status">
                @else
                    <div class="no-data">No claims data</div>
                @endif
            </div>
        </div>

        <!-- Top 10 Categories -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">Top 10 Categories</div>
            </div>
            <div class="chart-body">
                @if(count($categoryDistribution) > 0)
                    <img src="{{ $categoryChartUrl }}" class="chart-img" alt="Categories">
                @else
                    <div class="no-data">No category data</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Distribution Table -->
    @if(count($categoryDistribution) > 0)
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">Category Distribution Details</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">#</th>
                    <th>Category Name</th>
                    <th style="width: 100px; text-align: center;">Total Items</th>
                    <th style="width: 100px; text-align: center;">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php $totalCategoryItems = array_sum(array_column($categoryDistribution, 'count')); @endphp
                @foreach($categoryDistribution as $index => $cat)
                <tr>
                    <td style="text-align: center; font-weight: 600;">{{ $index + 1 }}</td>
                    <td style="font-weight: 600;">{{ $cat['name'] }}</td>
                    <td style="text-align: center;">{{ $cat['count'] }}</td>
                    <td style="text-align: center;">
                        {{ $totalCategoryItems > 0 ? round(($cat['count'] / $totalCategoryItems) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Activities Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">Recent Activities</div>
            <div class="table-subtitle">Latest reports and updates</div>
        </div>
        @if(count($recent) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Type</th>
                    <th>Item Name</th>
                    <th style="width: 120px;">Reporter</th>
                    <th style="width: 70px;">Status</th>
                    <th style="width: 110px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent as $r)
                <tr>
                    <td>
                        <span class="badge badge-{{ strtolower($r->report_type) }}">
                            {{ $r->report_type }}
                        </span>
                    </td>
                    <td style="font-weight: 500;">{{ $r->item_name ?? '—' }}</td>
                    <td>{{ optional($r->user)->full_name ?? 'Anonymous' }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($r->report_status) }}">
                            {{ $r->report_status }}
                        </span>
                    </td>
                    <td style="font-size: 9px;">
                        {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">No recent activities found</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-line" style="font-weight: 600;">Lost & Found Management System</div>
        <div class="footer-line">Generated on {{ now()->format('d M Y H:i:s') }}</div>
        <div class="footer-line">This is a computer-generated report</div>
    </div>
</body>
</html>