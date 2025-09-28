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

    .loans-dashboard {
        background: var(--ura-gradient);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .loans-dashboard::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .dashboard-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
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

    .filter-body {
        padding: 2rem;
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
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

    .loans-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
    }

    .table-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    <!-- Modern Dashboard Header -->
    <div class="loans-dashboard">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">
                    <i class="bx bx-wallet"></i>
                    Salary Loans Management
                </h1>
                <p class="dashboard-subtitle">
                    Track and manage salary loan deductions with comprehensive analytics
                </p>
            </div>
        </div>

        <!-- Quick Statistics -->
        <div class="quick-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $salaryLoans->total() ?? count($salaryLoans) }}</div>
                <div class="stat-label">Total Loans</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($salaryLoans->sum('deductionAmount'), 2) }}</div>
                <div class="stat-label">Total Deductions (TZS)</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($salaryLoans->avg('deductionAmount'), 2) }}</div>
                <div class="stat-label">Average Deduction</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($salaryLoans->sum('monthlySalary'), 2) }}</div>
                <div class="stat-label">Total Salaries (TZS)</div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Section -->
    <div class="modern-filter-section">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="bx bx-filter-alt"></i>
                Advanced Search & Filters
            </h5>
        </div>
        <div class="filter-body">
            <form action="{{ route('deductions.salary.loans') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="date" class="form-label">
                            <i class="bx bx-calendar me-2"></i>Date
                        </label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date', $date) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="checkNumber" class="form-label">
                            <i class="bx bx-id-card me-2"></i>Check Number
                        </label>
                        <input type="text" name="checkNumber" id="checkNumber" class="form-control"
                               placeholder="Enter check number" value="{{ request('checkNumber', $checkNumber) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="firstName" class="form-label">
                            <i class="bx bx-user me-2"></i>First Name
                        </label>
                        <input type="text" name="firstName" id="firstName" class="form-control"
                               placeholder="Enter first name" value="{{ request('firstName', $firstName) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="middleName" class="form-label">
                            <i class="bx bx-user me-2"></i>Middle Name
                        </label>
                        <input type="text" name="middleName" id="middleName" class="form-control"
                               placeholder="Enter middle name" value="{{ request('middleName', $middleName) }}">
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="lastName" class="form-label">
                            <i class="bx bx-user me-2"></i>Last Name
                        </label>
                        <input type="text" name="lastName" id="lastName" class="form-control"
                               placeholder="Enter last name" value="{{ request('lastName', $lastName) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="action" class="form-label">
                            <i class="bx bx-cog me-2"></i>Action
                        </label>
                        <select name="action" id="action" class="form-control">
                            <option value="view" {{ request('action', 'view') == 'view' ? 'selected' : '' }}>View Data</option>
                            <option value="export" {{ request('action', 'view') == 'export' ? 'selected' : '' }}>Export CSV</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="modern-btn modern-btn-primary w-100">
                            <i class="bx bx-search"></i> Search & Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Salary Loans Table -->
    <div class="loans-table-card">
        <div class="table-header">
            <h5 class="table-title">
                <i class="bx bx-table"></i>
                Salary Loans Directory
            </h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                    {{ $salaryLoans->total() ?? count($salaryLoans) }} Records
                </span>
            </div>
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
                        <th><i class="bx bx-calendar me-2"></i>Month</th>
                        <th><i class="bx bx-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaryLoans as $index => $record)
                    <tr>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                {{ $record->checkNumber }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="bx bx-user text-info"></i>
                                </div>
                                <div>
                                    <strong>{{ $record->firstName }} {{ $record->middleName }} {{ $record->lastName }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success">
                                TZS {{ number_format($record->monthlySalary, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold" style="color: var(--ura-primary);">
                                TZS {{ number_format($record->deductionAmount, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-warning">
                                TZS {{ number_format($record->balanceAmount, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $record->deductionDesc }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded">
                                {{ $record->voteCode }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                {{ $record->deptName }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ number_format($record->month, 2) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-dropdown">
                                <div class="dropdown">
                                    <button class="modern-btn modern-btn-primary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton-{{ $record->checkNumber }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $record->checkNumber }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $record->checkNumber]) }}">
                                                <i class="bx bx-file-find"></i>
                                                Loan Details
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $record->checkNumber]) }}">
                                                <i class="bx bx-pie-chart-alt-2"></i>
                                                Contribution Details
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
                                <h5 class="text-muted mt-2">No Salary Loans Found</h5>
                                <p class="text-muted">Try adjusting your search criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-wrapper">
            {{ $salaryLoans->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<style>
    .pagination-wrapper .pagination {
        --bs-pagination-color: var(--ura-primary);
        --bs-pagination-border-color: rgba(23, 71, 158, 0.2);
        --bs-pagination-hover-color: white;
        --bs-pagination-hover-bg: var(--ura-primary);
        --bs-pagination-active-bg: var(--ura-gradient);
        --bs-pagination-active-border-color: var(--ura-primary);
    }

    .pagination-wrapper .page-link {
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 71, 158, 0.2);
    }
</style>

@endsection
