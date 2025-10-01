<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Profile Report - {{ $user->name }}</title>
    <style>
        @page {
            margin: 80px 40px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #17479e;
        }
        .header h1 {
            color: #17479e;
            margin: 0;
            font-size: 22pt;
            text-transform: uppercase;
        }
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 14pt;
            font-weight: normal;
        }
        .header p {
            margin: 8px 0 0 0;
            color: #666;
            font-size: 9pt;
        }
        .info-box {
            background: #f8f9fa;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #17479e;
        }
        .info-box p {
            margin: 4px 0;
            font-size: 9pt;
        }
        .info-box strong {
            color: #17479e;
        }
        .section-title {
            color: #17479e;
            font-size: 13pt;
            margin-top: 25px;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #17479e;
            text-transform: uppercase;
            font-weight: bold;
        }
        .profile-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border: 2px solid #17479e;
        }
        .profile-item {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .profile-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #17479e;
            font-size: 9pt;
        }
        .profile-value {
            display: table-cell;
            width: 65%;
            color: #333;
            font-size: 9pt;
        }
        .info-grid {
            display: table;
            width: 100%;
            border: 2px solid #17479e;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #dee2e6;
            background: #fff;
        }
        .info-cell:nth-child(even) {
            background: #f8f9fa;
        }
        .cell-label {
            font-weight: bold;
            color: #17479e;
            font-size: 9pt;
            display: block;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .cell-value {
            color: #333;
            font-size: 10pt;
        }
        .analytics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .analytics-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border: 2px solid #17479e;
            border-right: none;
        }
        .analytics-item:last-child {
            border-right: 2px solid #17479e;
        }
        .analytics-item h3 {
            margin: 0;
            color: #17479e;
            font-size: 22pt;
            font-weight: bold;
        }
        .analytics-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 8pt;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 2px solid #17479e;
        }
        table thead {
            background: #17479e;
            color: white;
        }
        table th {
            padding: 8px;
            text-align: left;
            border: 1px solid #fff;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #dee2e6;
            font-size: 8pt;
        }
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        table tbody tr:nth-child(odd) {
            background: #fff;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-pending {
            background: #ffce00;
            color: #333;
        }
        .status-assigned {
            background: #00BCD4;
            color: white;
        }
        .status-approved {
            background: #10dc60;
            color: white;
        }
        .status-rejected {
            background: #f04141;
            color: white;
        }
        .status-active {
            background: #10dc60;
            color: white;
        }
        .status-inactive {
            background: #f04141;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            font-size: 8pt;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
        .summary-table {
            width: 100%;
            margin-top: 15px;
        }
        .summary-table td {
            padding: 6px 10px;
            border-left: 3px solid #00BCD4;
            background: #f8f9fa;
            margin-bottom: 5px;
        }
        .type-name {
            font-weight: bold;
            color: #333;
        }
        .type-count {
            float: right;
            color: #17479e;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>User Profile Report</h1>
        <h2>USALAMA WA RAIA SACCOS (URA SACCOS)</h2>
        <p style="margin-top: 5px;">URA - CRM SYSTEM</p>
        <p style="font-size: 8pt;">Makao Makuu: Dar es Salaam | Generated on {{ now()->format('d F Y, H:i:s') }}</p>
    </div>

    <!-- Report Information -->
    <div class="info-box">
        <p><strong>Report Generated By:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
        <p><strong>Generated On:</strong> {{ now()->format('d F Y, H:i:s') }}</p>
        <p><strong>Report ID:</strong> USR-{{ $user->id }}-{{ now()->format('YmdHis') }}</p>
    </div>

    <!-- User Profile Overview -->
    <h3 class="section-title">User Profile Overview</h3>
    <div class="profile-section">
        <div class="profile-item">
            <span class="profile-label">Full Name:</span>
            <span class="profile-value">{{ strtoupper($user->name) }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Email Address:</span>
            <span class="profile-value">{{ $user->email }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Phone Number:</span>
            <span class="profile-value">{{ $user->phone_number }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">User ID:</span>
            <span class="profile-value">#{{ $user->id }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Account Status:</span>
            <span class="profile-value">{{ strtoupper($user->status) }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Member Since:</span>
            <span class="profile-value">{{ $user->created_at->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Role & Organization Information -->
    <h3 class="section-title">Role & Organization Information</h3>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell">
                <span class="cell-label">Role</span>
                <span class="cell-value">
                    @if($user->getRoleNames()->isNotEmpty())
                        {{ strtoupper($user->getRoleNames()->implode(', ')) }}
                    @else
                        NO ROLE ASSIGNED
                    @endif
                </span>
            </div>
            <div class="info-cell">
                <span class="cell-label">Department</span>
                <span class="cell-value">{{ $user->department ? strtoupper($user->department->name) : 'N/A' }}</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell">
                <span class="cell-label">Branch</span>
                <span class="cell-value">{{ $user->branch ? strtoupper($user->branch->name) : 'N/A' }}</span>
            </div>
            <div class="info-cell">
                <span class="cell-label">Rank</span>
                <span class="cell-value">
                    @php
                        if (is_object($user->rank)) {
                            echo strtoupper($user->rank->name ?? 'N/A');
                        } elseif ($user->rank) {
                            $rankObj = \App\Models\Rank::find($user->rank);
                            echo $rankObj ? strtoupper($rankObj->name) : 'N/A';
                        } else {
                            echo 'N/A';
                        }
                    @endphp
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell">
                <span class="cell-label">Region</span>
                <span class="cell-value">{{ $user->region ? strtoupper($user->region->name) : 'N/A' }}</span>
            </div>
            <div class="info-cell">
                <span class="cell-label">District</span>
                <span class="cell-value">{{ $user->district ? strtoupper($user->district->name) : 'N/A' }}</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell">
                <span class="cell-label">Command</span>
                <span class="cell-value">{{ $user->command ? strtoupper($user->command->name) : 'N/A' }}</span>
            </div>
            <div class="info-cell">
                <span class="cell-label">Force Number</span>
                <span class="cell-value">{{ $user->force_number ? strtoupper($user->force_number) : 'N/A' }}</span>
            </div>
        </div>
    </div>

    @if($totalEnquiries > 0)
    <!-- Enquiry Statistics -->
    <div class="page-break"></div>
    <h3 class="section-title">Enquiry Statistics Overview</h3>

    <div class="analytics-grid">
        <div class="analytics-item">
            <h3>{{ number_format($totalEnquiries) }}</h3>
            <p>Total Enquiries</p>
        </div>
        <div class="analytics-item">
            <h3>{{ number_format($enquiriesByType->count()) }}</h3>
            <p>Enquiry Types</p>
        </div>
        <div class="analytics-item">
            <h3>{{ $enquiriesByStatus->where('status', 'Pending')->first()['count'] ?? 0 }}</h3>
            <p>Pending</p>
        </div>
        <div class="analytics-item">
            <h3>{{ $enquiriesByStatus->where('status', 'Approved')->first()['count'] ?? 0 }}</h3>
            <p>Approved</p>
        </div>
    </div>

    <!-- Enquiries by Type -->
    <h3 class="section-title">Enquiries by Type</h3>
    <table class="summary-table">
        @foreach($enquiriesByType as $type)
        <tr>
            <td>
                <span class="type-name">{{ strtoupper($type['type']) }}</span>
                <span class="type-count">{{ $type['count'] }}</span>
            </td>
        </tr>
        @endforeach
    </table>

    <!-- Enquiries by Status -->
    @if($enquiriesByStatus->count() > 0)
    <h3 class="section-title" style="margin-top: 20px;">Enquiries by Status</h3>
    <table class="summary-table">
        @foreach($enquiriesByStatus as $status)
        <tr>
            <td>
                <span class="type-name">{{ strtoupper($status['status']) }}</span>
                <span class="type-count">{{ $status['count'] }}</span>
            </td>
        </tr>
        @endforeach
    </table>
    @endif

    <!-- All Enquiries Details -->
    <div class="page-break"></div>
    <h3 class="section-title">Complete Enquiry Details</h3>
    <p style="margin-bottom: 10px; font-size: 9pt; color: #666;">
        Total Records: <strong>{{ number_format($allEnquiries->count()) }}</strong> |
        Last Updated: <strong>{{ $lastEnquiry ? $lastEnquiry->created_at->format('d F Y') : 'N/A' }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 9%;">Date</th>
                <th style="width: 12%;">Check No</th>
                <th style="width: 18%;">Full Name</th>
                <th style="width: 17%;">Type</th>
                <th style="width: 13%;">Region</th>
                <th style="width: 13%;">District</th>
                <th style="width: 9%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allEnquiries as $index => $enquiry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $enquiry->created_at->format('d/m/Y') }}</td>
                <td>{{ strtoupper($enquiry->check_number) }}</td>
                <td>{{ strtoupper($enquiry->full_name ?? 'N/A') }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $enquiry->type)) }}</td>
                <td>{{ strtoupper($enquiry->region->name ?? 'N/A') }}</td>
                <td>{{ strtoupper($enquiry->district->name ?? 'N/A') }}</td>
                <td>{{ strtoupper($enquiry->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                    No enquiries found for this user
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>USALAMA WA RAIA SACCOS (URA SACCOS) - CRM SYSTEM</strong></p>
        <p>This is a computer-generated report. Generated on {{ now()->format('d F Y, H:i:s') }}</p>
        <p>&copy; {{ date('Y') }} URA SACCOS. All rights reserved. | Makao Makuu: Dar es Salaam</p>
    </div>
</body>
</html>
