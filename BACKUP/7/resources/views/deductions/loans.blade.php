
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

    .loans-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .loans-header::before {
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

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .search-form {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--ura-shadow);
        margin-bottom: 2rem;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="loans-header">
        <h1 class="header-title">
            <i class="bx bx-search"></i>
            Loan Repayment Tracker
        </h1>
        <p class="header-subtitle">
            Search and track loan repayments by date with advanced filtering
        </p>
    </div>

    <!-- Search Form -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-calendar"></i>
                Loan Search Parameters
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('deductions.loans.show') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label for="date" class="form-label">
                            <i class="bx bx-calendar-alt me-2"></i>
                            Select Search Date
                        </label>
                        <input type="date" class="form-control form-control-lg" id="date" name="date"
                               required value="{{ $date ?? '' }}"
                               max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="modern-btn modern-btn-primary w-100">
                            <i class="bx bx-search"></i>
                            Search Loans
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($loans))
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="modern-card-title">
                    <i class="bx bx-list-ul"></i>
                    Loan Results for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                    <span class="badge bg-primary ms-2">{{ count($loans) }} records</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @include('deductions.table', ['details' => $loans])
            </div>
        </div>
    @endif
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
    // Initialize DataTables if table exists
    if ($('#dataTable').length || $('.table').length) {
        $('.table').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "Search Loans:",
                lengthMenu: "Show _MENU_ loans per page",
                info: "Showing _START_ to _END_ of _TOTAL_ loans",
                infoEmpty: "No loans available",
                infoFiltered: "(filtered from _MAX_ total loans)",
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
    }
});
</script>

@endsection