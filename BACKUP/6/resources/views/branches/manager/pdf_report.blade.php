<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Branch Manager Report - {{ $branch->name }}</title>
    <style>
        @page {
            margin: 100px 50px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #17479e;
        }
        .header h1 {
            color: #17479e;
            margin: 0;
            font-size: 24pt;
        }
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 16pt;
            font-weight: normal;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #17479e;
        }
        .info-box p {
            margin: 5px 0;
        }
        .analytics-section {
            margin-bottom: 30px;
        }
        .analytics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .analytics-item {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
        }
        .analytics-item h3 {
            margin: 0;
            color: #17479e;
            font-size: 24pt;
        }
        .analytics-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 9pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 2px solid #17479e;
        }
        table thead {
            background: #17479e;
            color: white;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #333;
            font-size: 9pt;
            text-transform: uppercase;
        }
        table th {
            font-weight: bold;
            border: 1px solid #fff;
        }
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        table tbody tr {
            border-bottom: 1px solid #333;
        }
        .status-text {
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            color: #856404;
        }
        .status-assigned {
            color: #0c5460;
        }
        .status-approved {
            color: #155724;
        }
        .status-rejected {
            color: #721c24;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            font-size: 8pt;
            color: #666;
        }
        .section-title {
            color: #17479e;
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #17479e;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Branch Manager Report</h1>
        <h2>{{ $branch->name }}</h2>
        <p style="margin: 10px 0 0 0; color: #666;">Generated on {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- Branch Information -->
    <div class="info-box">
        <p><strong>Branch:</strong> {{ $branch->name }}</p>
        <p><strong>District:</strong> {{ $branch->district->name ?? 'N/A' }}</p>
        <p><strong>Region:</strong> {{ $branch->region->name ?? 'N/A' }}</p>
        <p><strong>Report Date:</strong> {{ date('d/m/Y') }}</p>
    </div>

    <!-- Analytics Overview -->
    <h3 class="section-title">ENQUIRY STATISTICS</h3>
    <div class="analytics-grid">
        <div class="analytics-item" style="border-right: none;">
            <h3>{{ number_format($analytics['total']) }}</h3>
            <p>Total Enquiries</p>
        </div>
        <div class="analytics-item" style="border-right: none;">
            <h3>{{ number_format($analytics['pending']) }}</h3>
            <p>Pending</p>
        </div>
        <div class="analytics-item" style="border-right: none;">
            <h3>{{ number_format($analytics['assigned']) }}</h3>
            <p>Assigned</p>
        </div>
        <div class="analytics-item" style="border-right: none;">
            <h3>{{ number_format($analytics['approved']) }}</h3>
            <p>Approved</p>
        </div>
    </div>

    <!-- Enquiries Table -->
    <h3 class="section-title">ENQUIRY DETAILS</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 12%;">Check No</th>
                <th style="width: 20%;">Member</th>
                <th style="width: 15%;">Type</th>
                <th style="width: 15%;">District</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enquiries as $index => $enquiry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $enquiry->created_at->format('d/m/Y') }}</td>
                <td>{{ strtoupper($enquiry->check_number) }}</td>
                <td>
                    <strong>{{ strtoupper($enquiry->full_name) }}</strong><br>
                    <small style="color: #666;">{{ strtoupper($enquiry->force_no) }}</small>
                </td>
                <td>{{ strtoupper(str_replace('_', ' ', $enquiry->type)) }}</td>
                <td>{{ strtoupper($enquiry->district->name ?? 'N/A') }}</td>
                <td>
                    @php
                        $statusClass = 'status-' . strtolower($enquiry->status);
                    @endphp
                    <span class="status-text {{ $statusClass }}">
                        {{ strtoupper($enquiry->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                    No enquiries found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary by Type -->
    @php
        $typeStats = $enquiries->groupBy('type')->map(function($items, $type) {
            return [
                'type' => $type,
                'count' => $items->count(),
                'pending' => $items->where('status', 'pending')->count(),
                'assigned' => $items->where('status', 'assigned')->count(),
                'approved' => $items->where('status', 'approved')->count(),
            ];
        });
    @endphp

    @if($typeStats->count() > 0)
    <h3 class="section-title" style="page-break-before: always;">SUMMARY BY ENQUIRY TYPE</h3>
    <table>
        <thead>
            <tr>
                <th>Enquiry Type</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Pending</th>
                <th style="text-align: center;">Assigned</th>
                <th style="text-align: center;">Approved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($typeStats as $stat)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $stat['type'])) }}</td>
                <td style="text-align: center;"><strong>{{ $stat['count'] }}</strong></td>
                <td style="text-align: center;">{{ $stat['pending'] }}</td>
                <td style="text-align: center;">{{ $stat['assigned'] }}</td>
                <td style="text-align: center;">{{ $stat['approved'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Summary by District -->
    @php
        $districtStats = $enquiries->groupBy('district_id')->map(function($items, $districtId) {
            $district = $items->first()->district;
            return [
                'district' => $district ? $district->name : 'N/A',
                'count' => $items->count(),
                'pending' => $items->where('status', 'pending')->count(),
                'assigned' => $items->where('status', 'assigned')->count(),
                'approved' => $items->where('status', 'approved')->count(),
            ];
        });
    @endphp

    @if($districtStats->count() > 0)
    <h3 class="section-title">SUMMARY BY DISTRICT</h3>
    <table>
        <thead>
            <tr>
                <th>District</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Pending</th>
                <th style="text-align: center;">Assigned</th>
                <th style="text-align: center;">Approved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($districtStats as $stat)
            <tr>
                <td>{{ $stat['district'] }}</td>
                <td style="text-align: center;"><strong>{{ $stat['count'] }}</strong></td>
                <td style="text-align: center;">{{ $stat['pending'] }}</td>
                <td style="text-align: center;">{{ $stat['assigned'] }}</td>
                <td style="text-align: center;">{{ $stat['approved'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This is an official computer-generated report from {{ config('app.name') }}</p>
        <p>Branch Manager Report - {{ $branch->name }} | Generated: {{ date('d/m/Y H:i:s') }}</p>
        <p>Page {PAGE_NUM} of {PAGE_COUNT}</p>
    </div>
</body>
</html>