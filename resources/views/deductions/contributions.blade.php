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

    .contributions-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .contributions-header::before {
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

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
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
        font-size: 0.875rem;
    }

    .modern-dropdown-toggle:hover {
        transform: translateY(-1px);
        box-shadow: var(--ura-shadow);
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

    .modern-breadcrumb {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .breadcrumb-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
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

    .stats-card {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-weight: 500;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="contributions-header">
        <h1 class="header-title">
            <i class="bx bx-coin-stack"></i>
            Member Contributions
        </h1>
        <p class="header-subtitle">
            Track and manage member contribution records with advanced analytics
        </p>
    </div>

    <!-- Modern Breadcrumb -->
    <div class="modern-breadcrumb d-flex flex-wrap align-items-center justify-content-between">
        <h6 class="breadcrumb-title">
            <i class="bx bx-home me-2"></i>
            Member Contributions
        </h6>
        <ul class="breadcrumb-nav">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="bx bx-home-alt"></i>
                    Dashboard
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">Contributions</li>
        </ul>
    </div>


    <!-- Advanced Filter Form -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-filter"></i>
                Advanced Search & Filter
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('deductions.contributions.handle') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-calendar me-1"></i>
                            Date
                        </label>
                        <input type="date" name="date" class="form-control" value="{{ request('date', '2025-01-31') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-hash me-1"></i>
                            Check Number
                        </label>
                        <input type="text" name="checkNumber" class="form-control" placeholder="Enter check number" value="{{ request('checkNumber') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-user me-1"></i>
                            First Name
                        </label>
                        <input type="text" name="firstName" class="form-control" placeholder="Enter first name" value="{{ request('firstName') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-user me-1"></i>
                            Middle Name
                        </label>
                        <input type="text" name="middleName" class="form-control" placeholder="Enter middle name" value="{{ request('middleName') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-user me-1"></i>
                            Last Name
                        </label>
                        <input type="text" name="lastName" class="form-control" placeholder="Enter last name" value="{{ request('lastName') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bx bx-cog me-1"></i>
                            Action
                        </label>
                        <div class="input-group">
                            <select name="action" class="form-control">
                                <option value="view">View Data</option>
                                <option value="export">Export CSV</option>
                            </select>
                            <button type="submit" class="modern-btn modern-btn-primary">
                                <i class="bx bx-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Contributions Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ count($contributions) }}</div>
                <div class="stats-label">Total Contributors</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($contributions->sum('deductionAmount'), 0) }}</div>
                <div class="stats-label">Total Contributions</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($contributions->sum('monthlySalary'), 0) }}</div>
                <div class="stats-label">Total Monthly Salary</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $contributions->unique('deptName')->count() }}</div>
                <div class="stats-label">Departments</div>
            </div>
        </div>
    </div>

    <!-- Contributions List Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-table"></i>
                Member Contributions
                <span class="badge bg-primary ms-2">{{ count($contributions) }} records</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th scope="col"><i class="bx bx-hash me-1"></i>Check Number</th>
                            <th scope="col"><i class="bx bx-id-card me-1"></i>National ID</th>
                            <th scope="col"><i class="bx bx-user me-1"></i>Full Name</th>
                            <th scope="col"><i class="bx bx-money me-1"></i>Monthly Salary</th>
                            <th scope="col"><i class="bx bx-coin-stack me-1"></i>Contribution</th>
                            <th scope="col"><i class="bx bx-code me-1"></i>Vote Code</th>
                            <th scope="col"><i class="bx bx-building me-1"></i>Department</th>
                            <th scope="col"><i class="bx bx-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contributions as $index => $record)
                        <tr>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    {{ $record->checkNumber }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $record->nationalId }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="bx bx-user text-info"></i>
                                    </div>
                                    <strong>{{ $record->firstName }} {{ $record->middleName }} {{ $record->lastName }}</strong>
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
                                <div class="action-dropdown">
                                    <div class="dropdown">
                                        <button class="modern-btn modern-btn-primary btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton-{{ $record->checkNumber }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $record->checkNumber }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $record->checkNumber]) }}">
                                                    <i class="bx bx-file-find"></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $record->checkNumber]) }}">
                                                    <i class="bx bx-calculator"></i>
                                                    Loan Analysis
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $contributions->appends(request()->query())->links() }}
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
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Contributions:",
            lengthMenu: "Show _MENU_ contributions per page",
            info: "Showing _START_ to _END_ of _TOTAL_ contributions",
            infoEmpty: "No contributions available",
            infoFiltered: "(filtered from _MAX_ total contributions)",
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
            { targets: [0, 1, 2, 3, 4, 5, 6], className: 'text-center' },
            { targets: [3, 4], type: 'num-fmt' },
            { targets: 7, orderable: false }
        ]
    });
});
</script>

@endsection
