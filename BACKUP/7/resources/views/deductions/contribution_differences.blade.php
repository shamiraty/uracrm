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

    .differences-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .differences-header::before {
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

    .modern-btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
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
        font-size: 0.9rem;
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
        font-size: 0.9rem;
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

    .change-increase {
        color: var(--ura-success) !important;
        font-weight: 700;
    }

    .change-decrease {
        color: var(--ura-danger) !important;
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

    .modern-alert {
        background: var(--ura-gradient-light);
        border: 1px solid var(--ura-primary);
        border-radius: 12px;
        padding: 1.5rem;
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modern-alert i {
        font-size: 1.5rem;
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
    <div class="differences-header">
        <h1 class="header-title">
            <i class="bx bx-compare"></i>
            Contribution Differences Analysis
        </h1>
        <p class="header-subtitle">
            Comprehensive analysis of deduction code 667 differences across multiple periods
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
            <li class="text-muted">Contribution Differences</li>
        </ul>
    </div>

    @if(count($differences) > 0)
        <!-- Statistics Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-group"></i>
                </div>
                <div class="stat-value">{{ count($differences) }}</div>
                <p class="stat-label">Total Records</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-trending-up"></i>
                </div>
                <div class="stat-value">{{ collect($differences)->where('change_comment', 'Increase')->count() }}</div>
                <p class="stat-label">Increased Contributions</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-trending-down"></i>
                </div>
                <div class="stat-value">{{ collect($differences)->where('change_comment', 'Decrease')->count() }}</div>
                <p class="stat-label">Decreased Contributions</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-buildings"></i>
                </div>
                <div class="stat-value">{{ collect($differences)->unique('deptName')->count() }}</div>
                <p class="stat-label">Departments Affected</p>
            </div>
        </div>
    @endif

    <!-- Enhanced Filter Section -->
    <div class="modern-filter-section">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="bx bx-filter-alt"></i>
                Advanced Analysis Filters
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('deduction667.differences.index') }}">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="month_range" class="form-label">
                            <i class="bx bx-calendar me-2"></i>Month Range Comparison
                        </label>
                        <input type="text" name="month_range" id="month_range" class="form-control"
                               value="{{ request('month_range') }}"
                               placeholder="Select consecutive months">
                        <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        <small class="text-muted">Must select two consecutive months</small>
                    </div>

                    <div class="col-md-4">
                        <label for="deptName" class="form-label">
                            <i class="bx bx-buildings me-2"></i>Department Filter
                        </label>
                        <select name="deptName" id="deptName" class="form-select">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}" {{ request('deptName') === $department ? 'selected' : '' }}>
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <i class="bx bx-search"></i> Analyze Differences
                        </button>
                        @if(count($differences) > 0)
                            <a href="{{ route('deduction667.differences.export', request()->query()) }}"
                               class="modern-btn modern-btn-success">
                                <i class="bx bx-download"></i> Export CSV
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(count($differences) > 0)
        <!-- Enhanced Differences Table -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5 class="modern-card-title">
                        <i class="bx bx-table"></i>
                        Contribution Differences Analysis Results
                    </h5>
                    <span class="badge-custom badge-primary">
                        {{ count($differences) }} Records Found
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table" id="differencesTable">
                        <thead>
                            <tr>
                                <th><i class="bx bx-hash me-2"></i>SN</th>
                                <th><i class="bx bx-user me-2"></i>Employee Name</th>
                                <th><i class="bx bx-wallet me-2"></i>Monthly Salary</th>
                                <th><i class="bx bx-code me-2"></i>Vote Code</th>
                                <th><i class="bx bx-buildings me-2"></i>Department</th>
                                <th><i class="bx bx-transfer-alt me-2"></i>Change Type</th>
                                @php
                                    $monthYears = collect($differences)->flatMap(fn($diff) => array_keys($diff['details']))->unique()->sort()->values()->toArray();
                                @endphp
                                @foreach($monthYears as $my)
                                    <th><i class="bx bx-calendar me-2"></i>{{ \Carbon\Carbon::parse($my . '-01')->format('M Y') }}</th>
                                @endforeach
                                <th><i class="bx bx-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($differences as $key => $difference)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded">
                                            {{ $key + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="bx bx-user text-info"></i>
                                            </div>
                                            <strong>{{ trim("{$difference['firstName']} {$difference['middleName']} {$difference['lastName']}") }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            TZS {{ number_format($difference['monthlySalary'], 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                            {{ $difference['voteCode'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                            {{ $difference['deptName'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($difference['change_comment'] === 'Increase')
                                            <span class="change-increase">
                                                <i class="bx bx-trending-up me-1"></i> Increase
                                            </span>
                                        @elseif ($difference['change_comment'] === 'Decrease')
                                            <span class="change-decrease">
                                                <i class="bx bx-trending-down me-1"></i> Decrease
                                            </span>
                                        @endif
                                    </td>
                                    @foreach($monthYears as $my)
                                        <td>
                                            <span class="fw-bold" style="color: var(--ura-primary);">
                                                TZS {{ number_format($difference['details'][$my] ?? 0, 2) }}
                                            </span>
                                        </td>
                                    @endforeach
                                    <td>
                                        <a href="#" class="modern-btn modern-btn-primary modern-btn-sm">
                                            <i class="bx bx-show"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->filled('start_date') && request()->filled('end_date'))
        <div class="modern-alert">
            <i class="bx bx-info-circle"></i>
            <div>
                <strong>No Differences Found</strong><br>
                No differences in deduction amounts found for Deduction Code 667 within the selected date range and department (if specified).
            </div>
        </div>
    @endif
</div>

<!-- Enhanced CSS and JS Libraries -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced date range picker for consecutive months
    flatpickr("#month_range", {
        mode: "range",
        maxDate: new Date(),
        dateFormat: "Y-m-d",
        allowInput: true,
        clickOpens: true,
        locale: {
            rangeSeparator: " to "
        },
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0];
                const endDate = selectedDates[1];

                const startMonth = startDate.getMonth();
                const startYear = startDate.getFullYear();
                const endMonth = endDate.getMonth();
                const endYear = endDate.getFullYear();

                // Check if dates are in consecutive months
                const isConsecutive =
                    (startYear === endYear && endMonth === startMonth + 1) ||
                    (endYear === startYear + 1 && startMonth === 11 && endMonth === 0);

                if (!isConsecutive) {
                    // Show modern alert instead of browser alert
                    showModernAlert("Please select a date range within two consecutive months.", "warning");
                    instance.clear();
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("input[name='month_range']").val('');
                } else {
                    const formattedStartDate = instance.formatDate(startDate, 'Y-m-d');
                    const formattedEndDate = instance.formatDate(endDate, 'Y-m-d');
                    $("#start_date").val(formattedStartDate);
                    $("#end_date").val(formattedEndDate);
                    $("input[name='month_range']").val(dateStr);
                }
            } else if (selectedDates.length > 2) {
                showModernAlert("Please select only two dates for the range.", "warning");
                instance.clear();
                $("#start_date").val('');
                $("#end_date").val('');
                $("input[name='month_range']").val('');
            } else {
                $("#start_date").val('');
                $("#end_date").val('');
                $("input[name='month_range']").val('');
            }
        }
    });

    // Initialize DataTable for differences analysis
    if (document.getElementById('differencesTable')) {
        $('#differencesTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']], // Sort by serial number
            language: {
                search: "Search differences:",
                lengthMenu: "Show _MENU_ records per page",
                info: "Showing _START_ to _END_ of _TOTAL_ difference records",
                infoEmpty: "No difference records available",
                infoFiltered: "(filtered from _MAX_ total records)",
                zeroRecords: "No matching difference records found"
            },
            columnDefs: [
                {
                    targets: [2], // Salary column
                    className: 'text-end'
                },
                {
                    targets: -1, // Last column (actions)
                    orderable: false
                }
            ]
        });
    }

    // Enhanced filter form interaction
    const filterForm = document.querySelector('form[action*="differences"]');
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

    // Function to show modern alerts
    function showModernAlert(message, type = 'info') {
        const alertContainer = document.createElement('div');
        alertContainer.className = `modern-alert alert-${type}`;
        alertContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        `;

        alertContainer.innerHTML = `
            <i class="bx bx-${type === 'warning' ? 'error' : 'info'}-circle"></i>
            <div>${message}</div>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; margin-left: auto; font-size: 1.2rem;">&times;</button>
        `;

        document.body.appendChild(alertContainer);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertContainer.parentElement) {
                alertContainer.remove();
            }
        }, 5000);
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