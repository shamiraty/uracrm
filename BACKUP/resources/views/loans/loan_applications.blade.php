@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb breadcrumb-style" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 12px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: #17479E;">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="" class="text-decoration-none" style="color: #17479E;">
                    <i class="fas fa-file-alt me-1"></i>Enquiries
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                <i class="fas fa-money-bill-wave me-1"></i>Loan Applications (Loan Officer)
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-2">
                <i class="fas fa-credit-card me-2" style="color: #17479E;"></i>Loan Applications Management
            </h2>
            <p class="text-muted mb-0">Process and manage assigned loan applications</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <i class="fas fa-user-tie me-1"></i>Loan Officer Dashboard
            </span>
        </div>
    </div>

    <!-- Business Intelligence Analytics Dashboard -->
    <div class="row g-4 mb-4">
        <!-- Primary KPI Cards -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <div class="card-body text-white p-3 position-relative" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clipboard-list fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ $analytics['total'] }}</h4>
                                    <p class="mb-0 opacity-75 small">Total Assigned</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark px-2 py-1">LIVE</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-light" style="width: 100%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        <i class="fas fa-arrow-up text-success me-1"></i>Updated just now
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #87ceeb 0%, #4facfe 100%);">
                <div class="card-body text-white p-3 position-relative" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ $analytics['pending'] }}</h4>
                                    <p class="mb-0 opacity-75 small">Pending Review</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark px-2 py-1">ACTION NEEDED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-warning" style="width: {{ $analytics['total'] > 0 ? ($analytics['pending'] / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round(($analytics['pending'] / $analytics['total']) * 100, 1) : 0 }}% of total
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <div class="card-body text-white p-3 position-relative" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-cog fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ $analytics['processed'] }}</h4>
                                    <p class="mb-0 opacity-75 small">Processed</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-info px-2 py-1">FORWARDED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-info" style="width: {{ $analytics['total'] > 0 ? ($analytics['processed'] / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round(($analytics['processed'] / $analytics['total']) * 100, 1) : 0 }}% forwarded
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #87ceeb 0%, #4facfe 100%);">
                <div class="card-body text-white p-3 position-relative" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-times-circle fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ $analytics['rejected'] }}</h4>
                                    <p class="mb-0 opacity-75 small">Rejected</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-danger px-2 py-1">REJECTED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-danger" style="width: {{ $analytics['total'] > 0 ? ($analytics['rejected'] / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round(($analytics['rejected'] / $analytics['total']) * 100, 1) : 0 }}% rejected
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
        <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter fa-lg me-2"></i>
                    <span class="fw-bold">Quick Actions</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-sliders-h me-1"></i>Advanced Filters
                    </button>
                    <a href="{{ route('export.loan.applications', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulkActions" class="card border-0 shadow-sm mb-4" style="display: none;">
        <div class="card-body bg-primary-soft">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium text-primary">
                    <span id="selectedCount">0</span> loans selected
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkReject()">
                        <i class="fas fa-times me-1"></i>Bulk Reject
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                        <i class="fas fa-times me-1"></i>Clear Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern BI Data Table -->
    <div class="card border-0 shadow-lg">
        <div class="card-header border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2">
                            <i class="fas fa-table text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="color: #17479E;">Loan Applications Portfolio</h5>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-database me-1"></i>{{ number_format(method_exists($enquiries, 'total') ? $enquiries->total() : $enquiries->count()) }} records
                            <span class="mx-2">•</span>
                            <i class="fas fa-sync-alt me-1"></i>Real-time updates
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success px-3 py-2">
                        <i class="fas fa-circle fa-xs me-1"></i>Live
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-striped table-hover mb-0 modern-table" style="min-width: 1200px;">
                    <thead style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                        <tr class="text-white">
                            <th width="50" class="text-center border-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" style="border-color: white;">
                                </div>
                            </th>
                            <th width="60" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-hashtag me-1"></i>
                                    <span class="fw-bold">#</span>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt me-2"></i>
                                    <span class="fw-bold">Check Number</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span class="fw-bold">Member Details</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    <span class="fw-bold">Loan Amount</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <span class="fw-bold">Duration</span>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    <span class="fw-bold">Status</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th width="150" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tools me-2"></i>
                                    <span class="fw-bold">Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                        @if($enquiry->type === 'loan_application' && ($enquiry->enquirable || \App\Models\LoanApplication::where('enquiry_id', $enquiry->id)->exists()))
                        @php
                            // Get loan application data (fallback for missing polymorphic relationship)
                            $loanApp = $enquiry->enquirable ?: \App\Models\LoanApplication::where('enquiry_id', $enquiry->id)->first();
                        @endphp
                        <tr>
                            <td class="text-center align-middle">
                                <div class="form-check">
                                    <input class="form-check-input loan-checkbox" type="checkbox"
                                           value="{{ $enquiry->id }}"
                                           style="transform: scale(1.1);">
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="badge badge-counter" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white; font-weight: bold;">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="check-number-container">
                                    <div class="badge bg-gradient-primary px-3 py-2 text-white fw-bold fs-6">
                                        <i class="fas fa-receipt me-1"></i>{{ $enquiry->check_number }}
                                    </div>
                                    <small class="text-muted d-block mt-1">Reference ID</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="member-info-card p-2 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-placeholder bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($enquiry->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block">{{ ucwords($enquiry->full_name) }}</strong>
                                        </div>
                                    </div>
                                    <div class="member-details">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-id-badge text-info me-1"></i>{{ $enquiry->force_no ?? 'No Force Number' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-phone text-success me-1"></i>{{ $enquiry->phone ?? 'No Phone' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="loan-amount-card">
                                    <div class="d-flex align-items-center mb-1">
                                        <strong class="text-success fs-5">Tsh {{ number_format($loanApp->loan_amount, 2) }}</strong>
                                    </div>
                                    <small class="text-muted">Requested Amount</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="duration-card">
                                    <span class="badge bg-info-subtle text-info px-3 py-2 fw-bold">
                                        <i class="fas fa-calendar me-1"></i>{{ $loanApp->loan_duration }} months
                                    </span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="status-container">
                                    @switch($loanApp->status)
                                        @case('rejected')
                                            <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-times-circle me-1"></i>Rejected
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-check-circle me-1"></i>Approved
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-hourglass-half me-1"></i>Pending
                                            </span>
                                            @break
                                        @case('processed')
                                            <span class="badge bg-info fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-cog me-1"></i>Processed
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-question-circle me-1"></i>Unknown
                                            </span>
                                    @endswitch
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog me-1"></i>Actions
                                        </button>
                                        <ul class="dropdown-menu shadow border-0" style="min-width: 200px;">
                                            <!-- View Action -->
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewLoanDetailsModal-{{ $loanApp->id }}">
                                                    <i class="fas fa-eye text-info me-2"></i>View Details
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>

                                            @if($loanApp->status === 'pending')
                                                <!-- Process Action -->
                                                <li>
                                                    <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#processLoanModal-{{ $loanApp->id }}">
                                                        <i class="fas fa-cogs me-2"></i>Process Loan
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <!-- Reject Action -->
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectLoanModal-{{ $loanApp->id }}">
                                                        <i class="fas fa-times-circle me-2"></i>Reject Loan
                                                    </button>
                                                </li>
                                            @elseif($loanApp->status === 'processed')
                                                <!-- Process ke Approved - Loan Officer can approve processed loans -->
                                                <li>
                                                    <button type="button" class="dropdown-item text-success" onclick="sendOtpForApproval({{ $loanApp->id }})">
                                                        <i class="fas fa-check-circle me-2"></i>Approve Loan
                                                    </button>
                                                </li>
                                            @elseif($loanApp->status === 'approved')
                                                <li>
                                                    <span class="dropdown-item-text">
                                                        <span class="badge bg-success px-2 py-1">
                                                            <i class="fas fa-check me-1"></i>Approved - No Actions
                                                        </span>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                    <h5>No loan applications found</h5>
                                    <p>No loan applications assigned to you at this time.</p>
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

<!-- Modals for each loan application -->
@foreach ($enquiries as $enquiry)
@if($enquiry->type === 'loan_application')
    @php
        $loanApp = $enquiry->enquirable ?: \App\Models\LoanApplication::where('enquiry_id', $enquiry->id)->first();
    @endphp
    @if($loanApp)
        @include('modals.view_loan', ['loanApplication' => $loanApp])
        @include('modals.process_loan', ['enquiry' => $enquiry, 'loanApplication' => $loanApp])
        @include('modals.reject_loan', ['loanApplication' => $loanApp])
    @endif
@endif
@endforeach

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-sliders-h me-2"></i>Advanced Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="GET" action="{{ route('enquiries.my') }}" id="filterForm">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-search me-1 text-primary"></i>Search Terms
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control form-control-lg" placeholder="Enter check number, member name, force number, or phone...">
                            <small class="form-text text-muted">Search across check numbers, member names, force numbers, and phone numbers</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-info-circle me-1 text-info"></i>Loan Status
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">🔍 All Loan Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    ⏳ Pending Review
                                </option>
                                <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>
                                    🔧 Processed (Ready for Approval)
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                    ✅ Approved
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                    ❌ Rejected
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-clock me-1 text-warning"></i>Quick Date Ranges
                            </label>
                            <select class="form-select form-select-lg" id="quickDateRange">
                                <option value="">📅 Custom Date Range</option>
                                <option value="today">📆 Today</option>
                                <option value="yesterday">📆 Yesterday</option>
                                <option value="this_week">📅 This Week</option>
                                <option value="last_week">📅 Last Week</option>
                                <option value="this_month">📅 This Month</option>
                                <option value="last_month">📅 Last Month</option>
                                <option value="this_quarter">📅 This Quarter</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-alt me-1 text-success"></i>From Date
                            </label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="form-control form-control-lg" id="dateFrom">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-alt me-1 text-danger"></i>To Date
                            </label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="form-control form-control-lg" id="dateTo">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-money-bill-wave me-1 text-success"></i>Minimum Loan Amount
                            </label>
                            <input type="number" name="min_amount" value="{{ request('min_amount') }}"
                                   class="form-control form-control-lg" placeholder="0.00" min="0" step="0.01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-money-bill-wave me-1 text-danger"></i>Maximum Loan Amount
                            </label>
                            <input type="number" name="max_amount" value="{{ request('max_amount') }}"
                                   class="form-control form-control-lg" placeholder="No limit" min="0" step="0.01">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="clearFilters()">
                    <i class="fas fa-refresh me-1"></i>Clear Filters
                </button>
                <button type="button" class="btn btn-primary" style="background: #17479E;" onclick="applyFilters()">
                    <i class="fas fa-search me-1"></i>Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-shield-alt me-2"></i>OTP Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                    <h6 class="fw-bold">Enter OTP Code</h6>
                    <p class="text-muted">Please enter the 6-digit OTP sent to your phone</p>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control form-control-lg text-center"
                           id="otpInput" placeholder="000000" maxlength="6"
                           style="letter-spacing: 0.5rem; font-weight: bold;">
                </div>
                <div id="otpError" class="alert alert-danger d-none"></div>
                <div id="otpSuccess" class="alert alert-success d-none"></div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-outline-info" onclick="resendOtp()">
                    <i class="fas fa-redo me-1"></i>Resend OTP
                </button>
                <button type="button" class="btn btn-primary" style="background: #17479E;" onclick="verifyOtp()">
                    <i class="fas fa-check me-1"></i>Verify
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Table Styling */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    border: none !important;
    padding: 1rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.modern-table tbody td {
    border: none !important;
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    padding: 1.25rem 1rem !important;
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(135, 206, 235, 0.03) 100%);
    transform: scale(1.001);
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.08);
}

/* Enhanced Member Info Cards */
.member-info-card {
    transition: all 0.2s ease;
    border-radius: 10px !important;
    border: 1px solid rgba(23, 71, 158, 0.1);
}

.member-info-card:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    box-shadow: 0 4px 8px rgba(23, 71, 158, 0.1);
}

.avatar-placeholder {
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    font-weight: bold;
    font-size: 14px;
}

/* Enhanced Badges */
.badge-counter {
    border-radius: 50% !important;
    width: 35px;
    height: 35px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 12px !important;
    box-shadow: 0 2px 8px rgba(23, 71, 158, 0.3);
}

.check-number-container .badge {
    border-radius: 25px !important;
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    box-shadow: 0 3px 6px rgba(23, 71, 158, 0.3);
    font-size: 13px !important;
}

/* Soft Background Colors */
.bg-primary-soft {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.08) 0%, rgba(79, 172, 254, 0.05) 100%) !important;
}

.bg-success-subtle {
    background: linear-gradient(135deg, rgba(32, 201, 151, 0.08) 0%, rgba(25, 135, 84, 0.05) 100%) !important;
}

.bg-info-subtle {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(13, 202, 240, 0.05) 100%) !important;
}

/* Dropdown enhancements */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-radius: 12px;
    padding: 8px 0;
    min-width: 200px;
    z-index: 1060;
}

.dropdown-item {
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin: 2px 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);
    color: white;
    transform: translateX(8px);
}

.dropdown-item i {
    margin-right: 10px;
    width: 16px;
    text-align: center;
}

.dropdown-divider {
    margin: 8px 16px;
    border-color: #e9ecef;
}

/* Action Buttons Enhancement */
.btn-sm {
    border-radius: 8px !important;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Card Enhancements */
.card {
    border-radius: 16px !important;
    border: none !important;
}

.card-header {
    border-radius: 16px 16px 0 0 !important;
}

/* Modern Alert Styles */
.modern-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.modern-alert-content {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    color: white;
    font-weight: 500;
}

.modern-alert-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.modern-alert-error { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); }
.modern-alert-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #212529 !important; }
.modern-alert-info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }

.modern-alert-close {
    background: none;
    border: none;
    color: inherit;
    margin-left: auto;
    padding: 0;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.modern-alert-close:hover {
    opacity: 1;
}

/* Modern Confirm Styles */
.modern-confirm-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.modern-confirm-modal {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 400px;
    width: 90%;
    animation: scaleIn 0.3s ease;
}

.modern-confirm-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px 16px 0 0;
}

.modern-confirm-body {
    padding: 24px;
    text-align: center;
    font-size: 16px;
    line-height: 1.5;
    color: #495057;
}

.modern-confirm-footer {
    padding: 16px 24px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .modern-table thead th,
    .modern-table tbody td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }

    .member-info-card {
        padding: 0.75rem !important;
    }

    .modern-alert {
        right: 10px;
        left: 10px;
        min-width: auto;
    }

    .modern-confirm-modal {
        margin: 20px;
        width: auto;
    }
}
</style>

<script>
// Bulk selection functionality
let selectedLoans = [];

document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.loan-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.loan-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectedLoans = Array.from(checkedBoxes).map(cb => cb.value);

    if (selectedLoans.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedLoans.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.loan-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkReject() {
    if (selectedLoans.length === 0) {
        showModernAlert('Please select loans to reject.', 'warning');
        return;
    }

    showModernConfirm(
        'Bulk Reject Confirmation',
        'Are you sure you want to reject ' + selectedLoans.length + ' selected loan applications? This action cannot be undone.',
        function() {
            processBulkReject();
        }
    );
}

function processBulkReject() {

    // Send bulk reject request
    fetch('/loans/bulk-reject', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ loan_ids: selectedLoans })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModernAlert(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showModernAlert('Error: ' + data.message, 'error');
            if (data.errors && data.errors.length > 0) {
                console.error('Bulk reject errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModernAlert('An error occurred during bulk rejection.', 'error');
    });
}

// Modern Alert Functions
function showModernAlert(message, type = 'info') {
    const alertHtml = `
        <div class="modern-alert modern-alert-${type}" id="modernAlert">
            <div class="modern-alert-content">
                <i class="fas fa-${getAlertIcon(type)} me-2"></i>
                <span>${message}</span>
                <button type="button" class="modern-alert-close" onclick="closeModernAlert()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHtml);

    // Auto remove after 4 seconds
    setTimeout(() => {
        closeModernAlert();
    }, 4000);
}

function showModernConfirm(title, message, onConfirm) {
    const confirmHtml = `
        <div class="modern-confirm-overlay" id="modernConfirmOverlay">
            <div class="modern-confirm-modal">
                <div class="modern-confirm-header">
                    <h5 class="fw-bold mb-0">${title}</h5>
                </div>
                <div class="modern-confirm-body">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <span>${message}</span>
                </div>
                <div class="modern-confirm-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModernConfirm()">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmModernAction()">Confirm</button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', confirmHtml);
    window.modernConfirmCallback = onConfirm;
}

function closeModernAlert() {
    const alert = document.getElementById('modernAlert');
    if (alert) {
        alert.remove();
    }
}

function closeModernConfirm() {
    const overlay = document.getElementById('modernConfirmOverlay');
    if (overlay) {
        overlay.remove();
    }
}

function confirmModernAction() {
    if (window.modernConfirmCallback) {
        window.modernConfirmCallback();
    }
    closeModernConfirm();
}

function getAlertIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

function applyFilters() {
    document.getElementById('filterForm').submit();
}

function clearFilters() {
    document.getElementById('filterForm').reset();
}

// OTP Functionality
let currentLoanId = null;

function sendOtpForApproval(loanId) {
    currentLoanId = loanId;

    // Send AJAX request to generate and send OTP
    fetch('/loans/' + loanId + '/send-otp-approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show OTP modal
            new bootstrap.Modal(document.getElementById('otpModal')).show();
        } else {
            alert('Failed to send OTP. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending OTP.');
    });
}

function verifyOtp() {
    const otp = document.getElementById('otpInput').value;
    const errorDiv = document.getElementById('otpError');
    const successDiv = document.getElementById('otpSuccess');

    if (!otp || otp.length !== 6) {
        showError('Please enter a valid 6-digit OTP');
        return;
    }

    // Send OTP verification request
    fetch('/loans/' + currentLoanId + '/verify-otp-approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ otp: otp })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Loan approved successfully!');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showError(data.message || 'Invalid OTP. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred during verification.');
    });
}

function resendOtp() {
    if (currentLoanId) {
        sendOtpForApproval(currentLoanId);
    }
}

function showError(message) {
    const errorDiv = document.getElementById('otpError');
    const successDiv = document.getElementById('otpSuccess');

    successDiv.classList.add('d-none');
    errorDiv.classList.remove('d-none');
    errorDiv.textContent = message;
}

function showSuccess(message) {
    const errorDiv = document.getElementById('otpError');
    const successDiv = document.getElementById('otpSuccess');

    errorDiv.classList.add('d-none');
    successDiv.classList.remove('d-none');
    successDiv.textContent = message;
}

// Auto-format OTP input
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otpInput');
    if (otpInput) {
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Quick Date Range functionality
    const quickDateRange = document.getElementById('quickDateRange');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');

    if (quickDateRange && dateFrom && dateTo) {
        quickDateRange.addEventListener('change', function() {
            const range = this.value;
            const today = new Date();
            let fromDate, toDate;

            switch(range) {
                case 'today':
                    fromDate = toDate = today.toISOString().split('T')[0];
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    fromDate = toDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'this_week':
                    const startOfWeek = new Date(today);
                    startOfWeek.setDate(today.getDate() - today.getDay());
                    fromDate = startOfWeek.toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
                case 'last_week':
                    const lastWeekStart = new Date(today);
                    lastWeekStart.setDate(today.getDate() - today.getDay() - 7);
                    const lastWeekEnd = new Date(lastWeekStart);
                    lastWeekEnd.setDate(lastWeekStart.getDate() + 6);
                    fromDate = lastWeekStart.toISOString().split('T')[0];
                    toDate = lastWeekEnd.toISOString().split('T')[0];
                    break;
                case 'this_month':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
                case 'last_month':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                    fromDate = lastMonth.toISOString().split('T')[0];
                    toDate = lastMonthEnd.toISOString().split('T')[0];
                    break;
                case 'this_quarter':
                    const quarterStart = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
                    fromDate = quarterStart.toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
                default:
                    return;
            }

            dateFrom.value = fromDate;
            dateTo.value = toDate;
        });
    }
});
</script>

@endsection