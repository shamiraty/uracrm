@extends('layouts.app')

@section('content')
<!-- Include all the styles from index.blade.php -->
@include('employee_loan.partials.styles')

<!-- Animated Particle Background -->
<div class="particle-container">
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
</div>

<!-- Morphing Blob Background -->
<div class="blob-container">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="container-fluid py-2 bg-gradient position-relative">
    <!-- Breadcrumb at the top -->
    <nav aria-label="breadcrumb" class="mb-2 animate-slide-down">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item">Loan Management</li>
            <li class="breadcrumb-item active fw-bold">@yield('page_title', 'Loans')</li>
        </ol>
    </nav>

    <!-- Compact Page Header with Animation -->
    <div class="page-header-compact mb-3 animate-fade-in">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact">
                            @yield('page_icon', '<i class="fas fa-hand-holding-usd"></i>')
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0">
                            @yield('page_heading', 'Loan Applications')
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            @yield('page_description', 'Member employee loan requests')
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    @yield('header_actions')
                    <button class="btn btn-ura-primary-sm animate-hover" onclick="refreshFromESS()">
                        <i class="fas fa-sync-alt me-1"></i>Sync ESS
                    </button>
                    <div class="btn-group ms-2">
                        <button class="btn btn-ura-light-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                            <li><a class="dropdown-item" href="#" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-2 text-success"></i>Export Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="showBulkActions()">
                                <i class="fas fa-tasks me-2"></i>Bulk Actions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('kpi_section')

    <!-- Search and Filter Section -->
    @yield('search_section')

    <!-- Main Table Section -->
    <div class="card shadow-premium animate-scale-up">
        <div class="modern-table-container">
            <div class="table-responsive">
                <table class="table modern-table align-middle display" id="loansTable">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <div class="form-check modern-checkbox">
                                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th>
                                <span class="table-header-text">
                                    <i class="fas fa-user me-1"></i>Employee
                                </span>
                            </th>
                            <th class="text-end">
                                <span class="table-header-text">
                                    <i class="fas fa-money-bill-wave me-1"></i>Salary
                                </span>
                            </th>
                            <th class="text-end">
                                <span class="table-header-text">
                                    <i class="fas fa-percentage me-1"></i>Deductible
                                </span>
                            </th>
                            <th class="text-end">
                                <span class="table-header-text">
                                    <i class="fas fa-hand-holding-usd me-1"></i>Requested
                                </span>
                            </th>
                            <th class="text-end">
                                <span class="table-header-text">
                                    <i class="fas fa-wallet me-1"></i>Take Home
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-university me-1"></i>Employee Bank
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-calendar-alt me-1"></i>Tenure
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-clock me-1"></i>Submitted
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-flag me-1"></i>Approval
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-info-circle me-1"></i>Status
                                </span>
                            </th>
                            <th class="text-center">
                                <span class="table-header-text">
                                    <i class="fas fa-cogs me-1"></i>Actions
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="loanTableBody">
                        @yield('table_content')
                    </tbody>
                </table>
            </div>
        </div>

        @yield('pagination_section')
    </div>
</div>

<!-- Include all modals from index.blade.php -->
@include('employee_loan.partials.modals')

<!-- Include all scripts from index.blade.php -->
@include('employee_loan.partials.scripts')

@yield('additional_scripts')

@endsection