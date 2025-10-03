<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESS Loan Applications Export - {{ date('Y-m-d') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #003366;
        }
        
        .header h1 {
            color: #003366;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .header .date {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .summary {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary h3 {
            color: #003366;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .summary-item {
            flex: 1;
            min-width: 150px;
            margin: 5px;
        }
        
        .summary-item label {
            font-weight: bold;
            color: #666;
        }
        
        .summary-item .value {
            font-size: 18px;
            color: #003366;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        thead {
            background: #003366;
            color: white;
        }
        
        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #003366;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        tbody tr:hover {
            background: #f0f0f0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-cancelled {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .status-disbursed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: white;
            }
            
            table {
                margin-top: 100px;
            }
            
            thead {
                display: table-header-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
            
            tbody {
                display: table-row-group;
            }
            
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ESS LOAN APPLICATIONS REPORT</h1>
        <div class="subtitle">Employee Self Service Loan Requests</div>
        <div class="date">Generated on: {{ date('F d, Y h:i A') }}</div>
    </div>
    
    <div class="summary">
        <h3>Summary Statistics</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <label>Total Applications</label>
                <div class="value">{{ $loanOffers->count() }}</div>
            </div>
            <div class="summary-item">
                <label>Pending</label>
                <div class="value">{{ $loanOffers->whereIn('approval', ['PENDING', null])->count() }}</div>
            </div>
            <div class="summary-item">
                <label>Approved</label>
                <div class="value">{{ $loanOffers->where('approval', 'APPROVED')->count() }}</div>
            </div>
            <div class="summary-item">
                <label>Rejected</label>
                <div class="value">{{ $loanOffers->where('approval', 'REJECTED')->count() }}</div>
            </div>
            <div class="summary-item">
                <label>Cancelled</label>
                <div class="value">{{ $loanOffers->where('approval', 'CANCELLED')->count() }}</div>
            </div>
            <div class="summary-item">
                <label>Total Amount</label>
                <div class="value">TZS {{ number_format($loanOffers->sum('requested_amount'), 2) }}</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 12%;">Application #</th>
                <th style="width: 10%;">Check #</th>
                <th style="width: 15%;">Name</th>
                <th style="width: 10%;">Employment</th>
                <th style="width: 12%;">Requested</th>
                <th style="width: 12%;">Take Home</th>
                <th style="width: 5%;">Tenure</th>
                <th style="width: 5%;">Rate</th>
                <th style="width: 8%;">Approval</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 10%;">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loanOffers as $index => $offer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $offer->application_number }}</td>
                <td>{{ $offer->check_number }}</td>
                <td>{{ $offer->first_name }} {{ $offer->last_name }}</td>
                <td>{{ $offer->employment_date ? \Carbon\Carbon::parse($offer->employment_date)->format('Y-m-d') : '-' }}</td>
                <td>TZS {{ number_format($offer->requested_amount, 2) }}</td>
                <td>TZS {{ number_format($offer->take_home_amount ?? $offer->net_loan_amount ?? 0, 2) }}</td>
                <td>{{ $offer->tenure ?? '-' }}</td>
                <td>{{ $offer->interest_rate ? $offer->interest_rate . '%' : '-' }}</td>
                <td>
                    @php
                        $approval = $offer->approval ?: 'PENDING';
                        $approvalClass = 'status-' . strtolower($approval);
                    @endphp
                    <span class="status-badge {{ $approvalClass }}">
                        {{ $approval }}
                    </span>
                </td>
                <td>
                    @php
                        $status = $offer->status ?: 'NEW';
                        $statusClass = 'status-' . str_replace('_', '-', strtolower($status));
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ str_replace('_', ' ', ucfirst($status)) }}
                    </span>
                </td>
                <td>{{ $offer->created_at ? $offer->created_at->format('Y-m-d') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 20px;">
                    No loan applications found
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f0f0f0;">
                <td colspan="5" style="text-align: right;">TOTALS:</td>
                <td>TZS {{ number_format($loanOffers->sum('requested_amount'), 2) }}</td>
                <td>TZS {{ number_format($loanOffers->sum('take_home_amount') ?: $loanOffers->sum('net_loan_amount'), 2) }}</td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>This is a system-generated report from URAERP - ESS Loan Management System</p>
        <p>© {{ date('Y') }} URASACCOS. All rights reserved.</p>
        <p>Page 1 of 1</p>
    </div>
    
    <script>
        // Auto-print when opened
        window.onload = function() {
            // Uncomment to auto-print
            // window.print();
        }
    </script>
</body>
</html>