@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 12px 35px rgba(23, 71, 158, 0.25);
    }

    .salary-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .salary-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .employee-info-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
        border: none;
    }

    .employee-info-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .employee-info-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-dropdown {
        position: relative;
    }

    .modern-dropdown-toggle {
        background: var(--ura-gradient);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .modern-dropdown-toggle:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-dropdown-menu {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: var(--ura-shadow);
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }

    .modern-dropdown-item {
        padding: 0.75rem 1.5rem;
        color: var(--ura-primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .modern-dropdown-item:hover {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
    }

    .modern-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: none;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .modern-table th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        padding: 1rem;
        border: none;
        text-align: center;
    }

    .modern-table td {
        padding: 0.75rem 1rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
        text-align: center;
        font-weight: 500;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
    }

    .year-row {
        background: var(--ura-primary) !important;
        color: white !important;
    }

    .year-row td {
        font-weight: 700;
        font-size: 1.1rem;
        padding: 1rem;
        text-align: center;
    }

    .total-row {
        background: var(--ura-gradient-light) !important;
        font-weight: 700;
    }

    .total-row th {
        color: var(--ura-primary);
        font-weight: 700;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .employee-badge {
        background: var(--ura-gradient);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="salary-header">
        <h1 class="header-title">
            <i class="bx bx-money"></i>
            Salary Deduction Details
        </h1>
        <p class="header-subtitle">
            Comprehensive deduction history and balance tracking
        </p>
    </div>

    <!-- Employee Information Card -->
    <div class="employee-info-card">
        <div class="employee-info-header">
            <h5 class="employee-info-title">
                <i class="bx bx-user"></i>
                Employee Information
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">
                        <span class="employee-badge">{{ $firstName }} {{ $middleName }} {{ $lastName }}</span>
                    </h4>
                    <p class="text-muted mb-0">
                        <strong>Check Number:</strong> {{ $checkNumber }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="modern-dropdown dropdown">
                        <a class="modern-dropdown-toggle dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-download"></i>
                            Export Options
                        </a>
                        <ul class="modern-dropdown-menu dropdown-menu">
                            <li>
                                <a class="modern-dropdown-item" href="{{ route('salary_detail.export.csv', ['checkNumber' => $checkNumber]) }}">
                                    <i class="bx bx-file-doc"></i>
                                    Export to CSV
                                </a>
                            </li>
                            <li>
                                <a class="modern-dropdown-item" href="{{ route('exportSalaryDetailPdf', ['checkNumber' => $checkNumber]) }}" target="_blank">
                                    <i class="bx bx-file-pdf"></i>
                                    Export to PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Details Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-table"></i>
                Deduction History & Balance Summary
                <span class="badge bg-primary ms-2">{{ count($deductionTypes) }} Types</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table table-bordered mb-0">

                    <thead>
                        <tr>
                            <th><i class="bx bx-calendar me-1"></i>DATE</th>
                            @foreach($deductionTypes as $type)
                                <th><i class="bx bx-minus-circle me-1"></i>{{ strtoupper($type) }} DEDUCTION</th>
                                <th><i class="bx bx-wallet me-1"></i>{{ strtoupper($type) }} BALANCE</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $columnTotals = array_fill_keys($deductionTypes, 0); // Initialize totals array
                            $balanceTotals = array_fill_keys($deductionTypes, 0); // Initialize balance totals array
                        @endphp

                        @foreach($formattedData as $year => $months)
                            <tr class="year-row">
                                <td colspan="{{ count($deductionTypes) * 2 + 1 }}">
                                    <i class="bx bx-calendar-alt me-2"></i>
                                    <strong>{{ $year }}</strong>
                                </td>
                            </tr>
                            @foreach($months as $month => $deductions)
                                <tr>
                                    <td>{{ $month }}</td>
                                    @foreach($deductionTypes as $type)
                                        @php 
                                            $amount = $deductions[$type] ?? 0;
                                            $balance = $deductions[$type . '_balance'] ?? 0;
                                            $columnTotals[$type] += $amount;
                                            $balanceTotals[$type] += $balance;
                                        @endphp
                                        <td>{{ number_format($amount, 2) }}</td>
                                        <td>{{ number_format($balance, 2) }}</td> <!-- Balance for each deduction -->
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <th><i class="bx bx-calculator me-1"></i>TOTAL</th>
                            @foreach($deductionTypes as $type)
                                <th>UGX {{ number_format($columnTotals[$type], 2) }}</th>
                                <th class="text-muted">N/A</th>
                            @endforeach
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables
    $('.modern-table').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { targets: 0, orderable: true },
            { targets: '_all', className: 'text-center' }
        ]
    });
});
</script>

@endsection
