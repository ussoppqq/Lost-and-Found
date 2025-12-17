<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Statistics Report</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif; 
        }
        
        body { 
            font-size: 11px; 
            color: #1F2937; 
            padding: 15px; 
            line-height: 1.4;
        }
        
        .h1 { 
            font-size: 22px; 
            font-weight: bold; 
            margin-bottom: 5px; 
            color: #1F2937; 
        }
        
        .h2 { 
            font-size: 16px; 
            font-weight: bold; 
            margin: 15px 0 8px; 
            color: #374151; 
            border-bottom: 2px solid #E5E7EB;
            padding-bottom: 5px;
        }
        
        .muted { 
            color: #6B7280; 
            font-size: 11px;
        }
        
        /* Header Section */
        .header { 
            margin-bottom: 20px; 
            border-bottom: 3px solid #3B82F6; 
            padding-bottom: 10px; 
        }
        
        /* Stats Grid - FIXED: Better spacing and no overflow */
        .stats-grid { 
            width: 100%; 
            margin-bottom: 20px;
            display: table;
            table-layout: fixed;
        }
        
        .stat-cell { 
            display: table-cell;
            width: 25%; 
            padding: 10px 8px;
            border: 2px solid #E5E7EB; 
            background: #F9FAFB;
            vertical-align: top;
        }
        
        .stat-label { 
            font-size: 8px; 
            color: #6B7280; 
            text-transform: uppercase; 
            margin-bottom: 4px;
            font-weight: bold;
            line-height: 1.2;
        }
        
        .stat-value { 
            font-size: 22px; 
            font-weight: bold; 
            margin-bottom: 3px;
            line-height: 1;
        }
        
        .stat-detail { 
            font-size: 8px; 
            color: #9CA3AF;
            line-height: 1.3;
        }
        
        /* Chart Container */
        .chart-container { 
            margin: 12px 0; 
            padding: 10px; 
            border: 1px solid #E5E7EB; 
            background: #FFFFFF;
            page-break-inside: avoid;
        }
        
        .chart-title { 
            font-size: 12px; 
            font-weight: bold; 
            margin-bottom: 8px; 
            color: #374151;
        }
        
        .chart-img { 
            width: 100%; 
            height: auto; 
            display: block;
        }
        
        /* Two Column Charts - Using Float */
        .chart-grid { 
            width: 100%; 
            margin: 12px 0;
            overflow: hidden;
        }
        
        .chart-col { 
            float: left;
            width: 49%; 
            margin-right: 2%;
        }
        
        .chart-col:last-child { 
            margin-right: 0; 
        }
        
        /* Full Width Chart */
        .chart-full {
            width: 100%;
            clear: both;
        }
        
        /* Table Styles */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 12px 0;
            page-break-inside: avoid;
        }
        
        th, td { 
            border: 1px solid #E5E7EB; 
            padding: 8px 10px; 
            text-align: left;
            font-size: 10px;
        }
        
        th { 
            background: #F3F4F6; 
            font-weight: bold; 
            color: #374151;
        }
        
        tr:nth-child(even) { 
            background: #F9FAFB; 
        }
        
        .badge { 
            display: inline-block; 
            padding: 3px 8px; 
            border-radius: 10px; 
            font-size: 8px; 
            font-weight: bold;
        }
        
        .badge-lost { 
            background: #FEE2E2; 
            color: #991B1B; 
        }
        
        .badge-found { 
            background: #D1FAE5; 
            color: #065F46; 
        }
        
        .badge-status {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .footer { 
            margin-top: 20px; 
            padding-top: 10px; 
            border-top: 2px solid #E5E7EB; 
            text-align: center; 
            font-size: 9px; 
            color: #6B7280;
            clear: both;
        }
        
        /* Clear floats */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Page break control */
        .page-break { 
            page-break-after: always; 
        }
        
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="h1">Lost & Found Statistics Report</div>
        <div class="muted">
            <strong>Period:</strong> {{ $range[0] }} - {{ $range[1] }} | <strong>Type:</strong> {{ $periodType }}
            @if(!$hasData)
            <span style="color: #DC2626; font-weight: bold; margin-left: 10px;">
                (No Data Available)
            </span>
            @endif
        </div>
    </div>

    <!-- Key Metrics - FIXED LAYOUT -->
    <div class="stats-grid">
        <div class="stat-cell">
            <div class="stat-label">Lost Reports</div>
            <div class="stat-value" style="color: #DC2626;">{{ $totalLost }}</div>
            <div class="stat-detail">{{ $openLost }} still open</div>
        </div>
        <div class="stat-cell">
            <div class="stat-label">Found Reports</div>
            <div class="stat-value" style="color: #16A34A;">{{ $totalFound }}</div>
            <div class="stat-detail">{{ $matched }} matched</div>
        </div>
        <div class="stat-cell">
            <div class="stat-label">Total Items</div>
            <div class="stat-value" style="color: #2563EB;">{{ $totalStored + $totalRegistered }}</div>
            <div class="stat-detail">{{ $totalStored }} stored</div>
        </div>
        <div class="stat-cell">
            <div class="stat-label">Claims Released</div>
            <div class="stat-value" style="color: #7C3AED;">{{ $totalReleased }}</div>
            <div class="stat-detail">{{ $successRate }}% success</div>
        </div>
    </div>

    <!-- Visual Analysis Section -->
    <div class="h2">Visual Analysis</div>

    @if($hasData && $chartUrls)
        <!-- Report Types & Item Status Charts -->
        <div class="chart-grid clearfix">
            @if(isset($chartUrls['reportType']))
            <div class="chart-col">
                <div class="chart-container">
                    <div class="chart-title">Report Types Distribution</div>
                    <img src="{{ $chartUrls['reportType'] }}" alt="Report Types" class="chart-img">
                </div>
            </div>
            @endif
            
            @if(isset($chartUrls['itemStatus']))
            <div class="chart-col">
                <div class="chart-container">
                    <div class="chart-title">Item Status Overview</div>
                    <img src="{{ $chartUrls['itemStatus'] }}" alt="Item Status" class="chart-img">
                </div>
            </div>
            @endif
        </div>

        <!-- Trend Chart Full Width -->
        @if(isset($chartUrls['trend']))
        <div class="chart-full">
            <div class="chart-container">
                <div class="chart-title">Daily Report Trends</div>
                <img src="{{ $chartUrls['trend'] }}" alt="Trend" class="chart-img">
            </div>
        </div>
        @endif

        <!-- Category & Claim Charts -->
        <div class="h2">Detailed Breakdown</div>
        
        <div class="chart-grid clearfix">
            @if(isset($chartUrls['category']))
            <div class="chart-col">
                <div class="chart-container">
                    <div class="chart-title">Top Categories</div>
                    <img src="{{ $chartUrls['category'] }}" alt="Categories" class="chart-img">
                </div>
            </div>
            @endif
            
            @if(isset($chartUrls['claim']))
            <div class="chart-col">
                <div class="chart-container">
                    <div class="chart-title">Claim Status</div>
                    <img src="{{ $chartUrls['claim'] }}" alt="Claims" class="chart-img">
                </div>
            </div>
            @endif
        </div>
    @else
        <!-- No Data Message with Suggestions -->
        <div class="chart-container" style="text-align: center; padding: 30px; background: #FEF3C7; border: 2px solid #F59E0B;">
            <div style="font-size: 16px; font-weight: bold; color: #92400E; margin-bottom: 10px;">
                No Data Available for This Period
            </div>
            <div style="font-size: 11px; color: #78350F; line-height: 1.6;">
                There are no reports or activities recorded between {{ $range[0] }} and {{ $range[1] }}.<br><br>
                <strong>Suggestions:</strong><br>
                - Try selecting a <strong>wider date range</strong> (e.g., Monthly or Yearly)<br>
                - Check if there are any reports created before or after this period<br>
                - Verify that reports are assigned to your company
            </div>
        </div>
    @endif

    <!-- Category Distribution Table -->
    <div class="no-break">
        <div class="h2">Category Distribution</div>
        @if(count($categoryDistribution) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 60%;">Category Name</th>
                    <th style="width: 30%; text-align: center;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryDistribution as $index => $c)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $c['name'] }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $c['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="chart-container" style="text-align: center; padding: 20px; background: #F3F4F6;">
            <div style="font-size: 11px; color: #6B7280;">
                No category data available for this period
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Activities -->
    <div class="no-break" style="margin-top: 15px;">
        <div class="h2">Recent Activities</div>
        @if(count($recent) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 30%;">Item</th>
                    <th style="width: 20%;">Reporter</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 28%;">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent as $r)
                <tr>
                    <td>
                        <span class="badge {{ $r->report_type === 'LOST' ? 'badge-lost' : 'badge-found' }}">
                            {{ $r->report_type }}
                        </span>
                    </td>
                    <td>{{ Str::limit($r->item_name ?? 'N/A', 30) }}</td>
                    <td>{{ Str::limit(optional($r->user)->full_name ?? 'Anonymous', 20) }}</td>
                    <td><span class="badge badge-status">{{ $r->report_status }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="chart-container" style="text-align: center; padding: 20px; background: #F3F4F6;">
            <div style="font-size: 11px; color: #6B7280;">
                No activities recorded during this period
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer clearfix">
        <div><strong>Generated:</strong> {{ now('Asia/Jakarta')->format('d M Y, H:i') }}</div>
        <div style="margin-top: 3px;">Lost & Found Management System</div>
    </div>
</body>
</html>