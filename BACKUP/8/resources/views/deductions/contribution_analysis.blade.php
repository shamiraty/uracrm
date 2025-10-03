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

    .analytics-dashboard {
        background: var(--ura-gradient-light);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .analytics-dashboard::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .dashboard-title {
        color: var(--ura-primary);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dashboard-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    .analytics-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .analytics-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .analytics-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ura-shadow-hover);
    }

    .analytics-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .card-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .card-label {
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .card-trend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .trend-up {
        color: var(--ura-success);
    }

    .trend-down {
        color: var(--ura-danger);
    }

    .modern-filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .filter-header {
        background: var(--ura-gradient);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .filter-body {
        padding: 2rem;
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
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
        background: linear-gradient(135deg, var(--ura-success) 0%, #00e676 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 220, 96, 0.3);
    }

    .modern-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 220, 96, 0.4);
        color: white;
    }

    .data-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
    }

    .table-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .table-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.05);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .record-count {
        background: var(--ura-gradient);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: auto;
    }

    .action-dropdown {
        position: relative;
    }

    .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: var(--ura-shadow);
        padding: 0.5rem 0;
    }

    .dropdown-item {
        padding: 0.75rem 1.5rem;
        color: #495057;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dropdown-item:hover {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
</style>

<div class="container-fluid">
    <!-- Analytics Dashboard Header -->
    <div class="analytics-dashboard">
        <div class="dashboard-title">
            <i class="bx bx-bar-chart-alt-2"></i>
            Contribution Analysis Dashboard
        </div>
        <p class="dashboard-subtitle">
            Comprehensive deduction analysis with advanced filtering and insights (Code: 667)
        </p>

        <!-- Quick Stats Cards -->
        <div class="analytics-cards">
            <div class="analytics-card">
                <div class="card-icon">
                    <i class="bx bx-group"></i>
                </div>
                <div class="card-value">{{ number_format($count) }}</div>
                <div class="card-label">Total Records</div>
                <div class="card-trend trend-up">
                    <i class="bx bx-trending-up"></i>
                    <span>Active Filter</span>
                </div>
            </div>

            <div class="analytics-card">
                <div class="card-icon">
                    <i class="bx bx-money"></i>
                </div>
                <div class="card-value">{{
                    is_object($deductions) && method_exists($deductions, 'count') && $deductions->count() > 0
                        ? number_format($deductions->sum('deductionAmount'), 2)
                        : (is_array($deductions) && count($deductions) > 0
                            ? number_format(array_sum(array_column($deductions, 'deductionAmount')), 2)
                            : '0.00')
                }}</div>
                <div class="card-label">Total Deductions</div>
                <div class="card-trend trend-up">
                    <i class="bx bx-dollar-circle"></i>
                    <span>TZS</span>
                </div>
            </div>

            <div class="analytics-card">
                <div class="card-icon">
                    <i class="bx bx-calculator"></i>
                </div>
                <div class="card-value">{{
                    is_object($deductions) && method_exists($deductions, 'count') && $deductions->count() > 0
                        ? number_format($deductions->avg('deductionAmount'), 2)
                        : (is_array($deductions) && count($deductions) > 0
                            ? number_format(array_sum(array_column($deductions, 'deductionAmount')) / count($deductions), 2)
                            : '0.00')
                }}</div>
                <div class="card-label">Average Deduction</div>
                <div class="card-trend trend-up">
                    <i class="bx bx-trending-up"></i>
                    <span>Per Member</span>
                </div>
            </div>

            <div class="analytics-card">
                <div class="card-icon">
                    <i class="bx bx-buildings"></i>
                </div>
                <div class="card-value">{{ is_array($departments) ? count($departments) : $departments->count() }}</div>
                <div class="card-label">Departments</div>
                <div class="card-trend trend-up">
                    <i class="bx bx-badge-check"></i>
                    <span>Active</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Advanced Filter Section -->
    <div class="modern-filter-card">
        <div class="filter-header">
            <i class="bx bx-filter-alt"></i>
            <h5>Advanced Analytics Filters</h5>
            <div class="record-count">{{ number_format($count) }} Records</div>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('deductions.contribution_analysis') }}">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="checkDate" class="form-label">
                            <i class="bx bx-calendar me-2"></i>Check Date
                        </label>
                        <input type="text" name="checkDate" id="checkDate" class="form-control"
                               value="{{ request('checkDate') }}"
                               placeholder="Select check date" required>
                    </div>

                    <div class="col-md-4">
                        <label for="deptName" class="form-label">
                            <i class="bx bx-buildings me-2"></i>Department
                        </label>
                        <select name="deptName" id="deptName" class="form-select">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}" {{ request('deptName') == $department ? 'selected' : '' }}>
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="deduction_filter" class="form-label">
                            <i class="bx bx-calculator me-2"></i>Amount Filter
                        </label>
                        <select name="deduction_filter" id="deduction_filter" class="form-select">
                            <option value="">All Amounts</option>
                            <option value="greater" {{ request('deduction_filter') == 'greater' ? 'selected' : '' }}>Greater Than</option>
                            <option value="greater_or_equal" {{ request('deduction_filter') == 'greater_or_equal' ? 'selected' : '' }}>Greater Than or Equal</option>
                            <option value="less" {{ request('deduction_filter') == 'less' ? 'selected' : '' }}>Less Than</option>
                            <option value="less_or_equal" {{ request('deduction_filter') == 'less_or_equal' ? 'selected' : '' }}>Less Than or Equal</option>
                            <option value="between" {{ request('deduction_filter') == 'between' ? 'selected' : '' }}>In Between</option>
                            <option value="exact" {{ request('deduction_filter') == 'exact' ? 'selected' : '' }}>Exactly</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <label for="deduction_min" class="form-label">
                            <i class="bx bx-money me-2"></i>Minimum Amount
                        </label>
                        <input type="number" name="deduction_min" id="deduction_min" class="form-control"
                               placeholder="Enter minimum amount" value="{{ request('deduction_min') }}" step="0.01">
                    </div>

                    <div class="col-md-6" id="deduction_max_div" style="display: none;">
                        <label for="deduction_max" class="form-label">
                            <i class="bx bx-money me-2"></i>Maximum Amount
                        </label>
                        <input type="number" name="deduction_max" id="deduction_max" class="form-control"
                               placeholder="Enter maximum amount" value="{{ request('deduction_max') }}" step="0.01">
                    </div>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-auto">
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <i class="bx bx-filter"></i> Apply Filters
                        </button>
                    </div>
                    <div class="col-md-auto">
                        <a href="{{ route('deductions.export_analysis', request()->query()) }}" class="modern-btn modern-btn-success">
                            <i class="bx bx-download"></i> Export CSV
                        </a>
                    </div>
                    <div class="col-md-auto">
                        <a href="{{ route('deductions.contribution_analysis') }}" class="modern-btn" style="background: #6c757d; color: white;">
                            <i class="bx bx-refresh"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Analytics Data Table -->
    <div class="data-table-card">
        <div class="table-header">
            <h5 class="table-title">
                <i class="bx bx-table"></i>
                Deduction Analysis Results
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table modern-table" id="dataTable">
                <thead>
                    <tr>
                        <th><i class="bx bx-id-card me-2"></i>Check Number</th>
                        <th><i class="bx bx-user me-2"></i>Employee Name</th>
                        <th><i class="bx bx-wallet me-2"></i>Monthly Salary</th>
                        <th><i class="bx bx-money me-2"></i>Deduction Amount</th>
                        <th><i class="bx bx-calculator me-2"></i>Balance Amount</th>
                        <th><i class="bx bx-file-find me-2"></i>Description</th>
                        <th><i class="bx bx-code-alt me-2"></i>Vote Code</th>
                        <th><i class="bx bx-buildings me-2"></i>Department</th>
                        <th><i class="bx bx-calendar me-2"></i>Check Date</th>
                        <th><i class="bx bx-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deductions as $index => $deduction)
                        <tr>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    {{ $deduction->checkNumber }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="bx bx-user text-info"></i>
                                    </div>
                                    <div>
                                        <strong>{{ trim("{$deduction->firstName} {$deduction->middleName} {$deduction->lastName}") }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    TZS {{ number_format($deduction->monthlySalary, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="color: var(--ura-primary);">
                                    TZS {{ number_format($deduction->deductionAmount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-warning">
                                    TZS {{ number_format(($deduction->balanceAmount ?? ($deduction->monthlySalary - $deduction->deductionAmount)), 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $deduction->deductionDesc ?? 'Contribution Deduction' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded">
                                    {{ $deduction->voteCode ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                    {{ $deduction->deptName }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <i class="bx bx-time me-1"></i>
                                    {{ \Carbon\Carbon::parse($deduction->checkDate)->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-dropdown">
                                    <div class="dropdown">
                                        <button class="modern-btn modern-btn-primary btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton-{{ $deduction->checkNumber }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $deduction->checkNumber }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $deduction->checkNumber]) }}">
                                                    <i class="bx bx-file-find"></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $deduction->checkNumber]) }}">
                                                    <i class="bx bx-calculator"></i>
                                                    Loan Analysis
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="exportRecord('{{ $deduction->checkNumber }}')">
                                                    <i class="bx bx-download"></i>
                                                    Export Record
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bx bx-search-alt-2 text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-2">No Records Found</h5>
                                    <p class="text-muted">Try adjusting your filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Enhanced date picker with modern styling
        flatpickr("#checkDate", {
            dateFormat: "Y-m-d",
            maxDate: new Date(),
            minDate: (() => {
                const today = new Date();
                const twoMonthsAgo = new Date(today);
                twoMonthsAgo.setMonth(today.getMonth() - 2);
                return twoMonthsAgo;
            })(),
            theme: "material_blue",
            allowInput: true,
            clickOpens: true,
        });

        // Enhanced filter functionality
        const deductionFilter = document.getElementById('deduction_filter');
        const deductionMaxDiv = document.getElementById('deduction_max_div');
        const deductionMinInput = document.getElementById('deduction_min');

        function updateFilterInputs() {
            const filterValue = deductionFilter.value;

            if (filterValue === 'between') {
                deductionMaxDiv.style.display = 'block';
                deductionMinInput.parentElement.className = 'col-md-6';
            } else {
                deductionMaxDiv.style.display = 'none';
                deductionMinInput.parentElement.className = 'col-md-6';
            }

            // Update placeholder text based on filter type
            switch(filterValue) {
                case 'greater':
                    deductionMinInput.placeholder = 'Amount must be greater than...';
                    break;
                case 'greater_or_equal':
                    deductionMinInput.placeholder = 'Amount must be greater than or equal to...';
                    break;
                case 'less':
                    deductionMinInput.placeholder = 'Amount must be less than...';
                    break;
                case 'less_or_equal':
                    deductionMinInput.placeholder = 'Amount must be less than or equal to...';
                    break;
                case 'exact':
                    deductionMinInput.placeholder = 'Exact amount...';
                    break;
                case 'between':
                    deductionMinInput.placeholder = 'Minimum amount...';
                    break;
                default:
                    deductionMinInput.placeholder = 'Enter amount...';
            }
        }

        deductionFilter.addEventListener('change', updateFilterInputs);

        // Initialize on page load
        updateFilterInputs();

        // Add loading animation to filter button
        const filterForm = document.querySelector('form[action*="contribution_analysis"]');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Filtering...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }

        // Initialize Select2 if available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#deptName').select2({
                placeholder: "Select Department",
                allowClear: true,
                theme: "bootstrap-5"
            });
        }

        // Add tooltips to analytics cards
        const cards = document.querySelectorAll('.analytics-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            pageLength: 10,
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
            order: [[0, 'asc']]
        });
    });

    // Export individual record function
    function exportRecord(checkNumber) {
        const exportUrl = `{{ route('deductions.export_analysis') }}?checkNumber=${checkNumber}`;
        window.open(exportUrl, '_blank');
    }
</script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

@endsection