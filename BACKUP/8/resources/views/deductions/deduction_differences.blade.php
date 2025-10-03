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

    .variance-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .variance-header::before {
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
        font-size: 2.5rem;
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

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
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

    .modern-filter-section {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .filter-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .filter-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(23, 71, 158, 0.05);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.15);
        outline: none;
    }

    .form-control:hover, .form-select:hover {
        border-color: var(--ura-accent);
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-btn-success {
        background: var(--ura-success);
        color: white;
    }

    .modern-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 220, 96, 0.3);
        color: white;
    }

    .modern-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        padding: 1rem;
        border: none;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modern-table th:first-child {
        border-top-left-radius: 12px;
    }

    .modern-table th:last-child {
        border-top-right-radius: 12px;
    }

    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        vertical-align: middle;
        color: #495057;
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .difference-positive {
        color: var(--ura-success) !important;
        font-weight: 700;
    }

    .difference-negative {
        color: var(--ura-danger) !important;
        font-weight: 700;
    }

    .difference-zero {
        color: var(--ura-primary) !important;
        font-weight: 700;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-primary {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.2) 0%, rgba(23, 71, 158, 0.1) 100%);
        color: var(--ura-primary);
        border: 1px solid var(--ura-primary);
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-breadcrumb {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .breadcrumb-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-nav a {
        color: var(--ura-primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .breadcrumb-nav a:hover {
        color: var(--ura-accent);
    }

    /* Enhanced Flatpickr styling */
    .flatpickr-calendar {
        box-shadow: var(--ura-shadow) !important;
        border-radius: 12px !important;
        border: none !important;
    }

    .flatpickr-month {
        background: var(--ura-gradient) !important;
        color: white !important;
    }

    .flatpickr-weekday {
        background: var(--ura-gradient-light) !important;
        color: var(--ura-primary) !important;
        font-weight: 600 !important;
    }

    .flatpickr-day.selected {
        background: var(--ura-primary) !important;
        border-color: var(--ura-primary) !important;
    }

    .flatpickr-day.inRange {
        background: var(--ura-gradient-light) !important;
        border-color: var(--ura-accent) !important;
        color: var(--ura-primary) !important;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="variance-header">
        <h1 class="header-title">
            <i class="bx bx-line-chart"></i>
            Deduction Variance Analysis
        </h1>
        <p class="header-subtitle">
            Track and analyze deduction differences across multiple periods with advanced filtering
        </p>
    </div>

    <!-- Breadcrumb -->
    <div class="modern-breadcrumb">
        <ul class="breadcrumb-nav">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="bx bx-home-alt"></i>
                    Dashboard
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">Deduction Variance</li>
        </ul>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-group"></i>
            </div>
            <div class="stat-value">{{ $filteredCount }}</div>
            <p class="stat-label">Total Records</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">{{ collect($filteredData)->where('difference', '>', 0)->count() }}</div>
            <p class="stat-label">Increased Deductions</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-down"></i>
            </div>
            <div class="stat-value">{{ collect($filteredData)->where('difference', '<', 0)->count() }}</div>
            <p class="stat-label">Decreased Deductions</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-minus"></i>
            </div>
            <div class="stat-value">{{ collect($filteredData)->where('difference', '=', 0)->count() }}</div>
            <p class="stat-label">No Change</p>
        </div>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="modern-filter-section">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="bx bx-filter-alt"></i>
                Advanced Analysis Filters
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('deductions.variance') }}">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="check_date" class="form-label">
                            <i class="bx bx-calendar me-2"></i>Date Range Comparison
                        </label>
                        <input type="text" name="check_date" id="check_date" class="form-control"
                               value="{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}"
                               placeholder="Select date range for comparison">
                        <small class="text-muted">Compare deductions between two periods</small>
                    </div>

                    <div class="col-md-4">
                        <label for="deptName" class="form-label">
                            <i class="bx bx-buildings me-2"></i>Department Filter
                        </label>
                        <select name="deptName" id="deptName" class="form-select">
                            <option value="All" {{ $departmentFilter === 'All' ? 'selected' : '' }}>All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}" {{ $departmentFilter === $department ? 'selected' : '' }}>
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <i class="bx bx-search"></i> Analyze Variance
                        </button>
                        <a href="{{ route('deductions.export_csv', ['check_date' => request('check_date'), 'deptName' => $departmentFilter]) }}"
                           class="modern-btn modern-btn-success">
                            <i class="bx bx-download"></i> Export CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Variance Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="modern-card-title">
                    <i class="bx bx-table"></i>
                    Deduction Variance Analysis Results
                </h5>
                <span class="badge-custom badge-primary">
                    {{ $filteredCount }} Records Found
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table" id="varianceTable">
                    <thead>
                        <tr>
                            <th><i class="bx bx-id-card me-2"></i>Check Number</th>
                            <th><i class="bx bx-user me-2"></i>Employee Name</th>
                            <th><i class="bx bx-money me-2"></i>{{ \Carbon\Carbon::parse($startDate)->format('M Y') }} Deduction</th>
                            <th><i class="bx bx-money me-2"></i>{{ \Carbon\Carbon::parse($endDate)->format('M Y') }} Deduction</th>
                            <th><i class="bx bx-calculator me-2"></i>Variance</th>
                            <th><i class="bx bx-wallet me-2"></i>Balance</th>
                            <th><i class="bx bx-file-text me-2"></i>Description</th>
                            <th><i class="bx bx-calendar me-2"></i>Period</th>
                            <th><i class="bx bx-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filteredData as $data)
                            <tr>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        {{ $data['check_number'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bx bx-user text-info"></i>
                                        </div>
                                        <strong>{{ $data['name'] }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        TZS {{ number_format((float)($data['deduction_month_1'] ?? 0), 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-info">
                                        TZS {{ number_format((float)($data['deduction_month_2'] ?? 0), 2) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $difference = (float)($data['difference'] ?? 0);
                                        $class = $difference > 0 ? 'difference-positive' : ($difference < 0 ? 'difference-negative' : 'difference-zero');
                                        $icon = $difference > 0 ? 'bx-trending-up' : ($difference < 0 ? 'bx-trending-down' : 'bx-minus');
                                    @endphp
                                    <span class="{{ $class }}">
                                        <i class="bx {{ $icon }} me-1"></i>
                                        TZS {{ number_format(abs($difference), 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-warning">
                                        TZS {{ number_format((float)($data['balance'] ?? 0), 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $data['deduction_description'] }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        {{ $data['month_computed'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('deductions.details', ['checkNumber' => $data['check_number']]) }}"
                                       class="modern-btn modern-btn-primary btn-sm">
                                        <i class="bx bx-show"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <i class="bx bx-search-alt-2 text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mt-2">No Variance Data Found</h5>
                                        <p class="text-muted">Try adjusting your date range or department filter</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced date range picker with better functionality
    flatpickr("#check_date", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: "{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}".split(' to '),
        // Allow selection of any date without restrictions
        minDate: null,
        maxDate: null,
        allowInput: true,
        clickOpens: true,
        // Enable month and year navigation
        yearSelectorRange: 100, // Show 100 years in dropdown
        monthSelectorType: "dropdown", // Show month dropdown
        showMonths: 1,
        locale: {
            rangeSeparator: " to "
        },
        // Remove problematic onChange that restricts dates
        onChange: function(selectedDates, dateStr, instance) {
            // Allow any date selection without restrictions
            console.log('Selected dates:', selectedDates);
        },
        onReady: function(selectedDates, dateStr, instance) {
            // Ensure all months and years are accessible
            instance.yearElements.forEach(function(yearElem) {
                yearElem.removeAttribute('readonly');
            });
        }
    });

    // Initialize DataTable for variance analysis
    $('#varianceTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[4, 'desc']], // Sort by variance (difference) descending
        language: {
            search: "Search variance records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ variance records",
            infoEmpty: "No variance records available",
            infoFiltered: "(filtered from _MAX_ total records)",
            zeroRecords: "No matching variance records found"
        },
        columnDefs: [
            {
                targets: [2, 3, 4, 5], // Amount columns
                className: 'text-end'
            },
            {
                targets: [4], // Variance column
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return parseFloat(data.replace(/[^\d.-]/g, ''));
                    }
                    return data;
                }
            }
        ]
    });

    // Add enhanced tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Enhanced filter form interaction
    const filterForm = document.querySelector('form[action*="variance"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Analyzing...';
            submitBtn.disabled = true;

            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
});
</script>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

@endsection