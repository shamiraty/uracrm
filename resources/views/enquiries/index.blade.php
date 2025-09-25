@extends('layouts.app')

@section('content')
<!-- Enhanced Modern Styling -->
<style>
    /* Button hover effects */
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .btn.disabled, .btn:disabled {
        background-color: #6c757d;
        color: #fff;
    }

    /* Analytics Cards */
    .analytics-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .analytics-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .analytics-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #0056b3);
    }

    /* Progress bars */
    .progress {
        border-radius: 10px;
        background-color: rgba(0,0,0,0.05);
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* Icon styling */
    .analytics-card i {
        transition: transform 0.3s ease;
    }

    .analytics-card:hover i {
        transform: scale(1.1);
    }

    /* Table enhancements */
    .table thead th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.002);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }

    /* Dropdown enhancements */
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border-radius: 8px;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        transition: all 0.2s ease;
    }

    /* Card enhancements */
    .card {
        border-radius: 12px;
    }

    /* Breadcrumb styling */
    .hover-text-primary:hover {
        color: #007bff !important;
        transition: color 0.3s ease;
    }

    /* Enhanced table styling */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #007bff;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #0056b3;
    }

    /* Fixed table width for horizontal scroll */
    .fixed-table {
        min-width: 1400px;
        width: 100%;
    }

    .table th {
        background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 12px;
        border: none;
        font-size: 0.85rem;
    }

    .table td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f1f3f4;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Badge enhancements */
    .badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Pagination styling */
    .pagination {
        margin: 0;
    }

    .page-link {
        border: 1px solid #dee2e6;
        color: #6c757d;
        padding: 8px 12px;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
    }

    /* Card enhancements */
    .card {
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    /* Status indicators */
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    /* Bulk actions styling */
    .alert {
        border-radius: 10px;
        border: none;
    }

    /* Form enhancements */
    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Filter card styling */
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    /* Loading states */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .loading {
        animation: pulse 1.5s infinite;
    }
</style>
<div class="container-fluid py-4">

    <!-- Enhanced Breadcrumb Navigation -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0 text-dark">Enquiries Dashboard</h5>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('enquiries.index') }}" class="text-decoration-none">All Enquiries</a>
                        </li>
                        @if($type)
                            <li class="breadcrumb-item active" aria-current="page">
                                <span class="badge" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%); color: white;">
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </span>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">All Types</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <h1 class="h3 mb-1 fw-bold text-white">
                                <i class="fas fa-clipboard-list me-3"></i>Enquiries Management
                            </h1>
                            <p class="mb-0 text-white opacity-75">Manage and track all enquiry submissions efficiently</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('enquiries.create', ['type' => $type ?? null]) }}" class="btn btn-light btn-lg fw-semibold shadow">
                                <i class="fas fa-plus me-2"></i>
                                @if($type)
                                    Create {{ ucfirst(str_replace('_', ' ', $type)) }}
                                @else
                                    New Enquiry
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ultra-Modern Analytics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #87CEEB, #17479e);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 position-relative" style="width: 70px; height: 70px; background: linear-gradient(135deg, #87CEEB, #17479e); box-shadow: 0 8px 20px rgba(23, 71, 158, 0.3);">
                        <i class="fas fa-clipboard-list text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark display-6">{{ number_format($analytics['total'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Total Enquiries</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #87CEEB, #17479e); width: 100%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? '100%' : '0%' }} of capacity</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #ffc107, #ff8c00);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #ffc107, #ff8c00); box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);">
                        <i class="fas fa-clock text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark display-6">{{ number_format($analytics['pending'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Pending Review</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #ffc107, #ff8c00); width: {{ $analytics['total'] > 0 ? (($analytics['pending'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? round((($analytics['pending'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% pending</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #17a2b8, #007bff);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #17a2b8, #007bff); box-shadow: 0 8px 20px rgba(23, 162, 184, 0.3);">
                        <i class="fas fa-user-check text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark display-6">{{ number_format($analytics['assigned'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Assigned</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #17a2b8, #007bff); width: {{ $analytics['total'] > 0 ? (($analytics['assigned'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? round((($analytics['assigned'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% assigned</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #28a745, #20c997);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #28a745, #20c997); box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);">
                        <i class="fas fa-check-circle text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark display-6">{{ number_format($analytics['approved'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Approved</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #28a745, #20c997); width: {{ $analytics['total'] > 0 ? (($analytics['approved'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? round((($analytics['approved'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% approved</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="500">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #dc3545, #e74c3c);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #dc3545, #e74c3c); box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-times-circle text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark display-6">{{ number_format($analytics['rejected'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Rejected</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #dc3545, #e74c3c); width: {{ $analytics['total'] > 0 ? (($analytics['rejected'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? round((($analytics['rejected'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% rejected</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-lg h-100 analytics-card position-relative overflow-hidden" data-aos="fade-up" data-aos-delay="600">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: linear-gradient(135deg, #6c757d, #495057);"></div>
                <div class="card-body text-center p-4 position-relative">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #dc3545, #6c757d); box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);">
                        <i class="fas fa-exclamation-triangle text-white fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-danger display-6">{{ number_format($analytics['pending_overdue'] ?? 0) }}</h3>
                    <p class="mb-2 text-muted fw-semibold">Overdue</p>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" style="background: linear-gradient(90deg, #dc3545, #6c757d); width: {{ $analytics['total'] > 0 ? (($analytics['pending_overdue'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">{{ $analytics['total'] > 0 ? round((($analytics['pending_overdue'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% overdue</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-filter me-2"></i>Filter Enquiries
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search enquiries...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Type</label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="loan_application" {{ request('type') == 'loan_application' ? 'selected' : '' }}>Loan Application</option>
                                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="share_enquiry" {{ request('type') == 'share_enquiry' ? 'selected' : '' }}>Share Enquiry</option>
                                <option value="retirement" {{ request('type') == 'retirement' ? 'selected' : '' }}>Retirement</option>
                                <option value="deduction_add" {{ request('type') == 'deduction_add' ? 'selected' : '' }}>Add Deduction</option>
                                <option value="withdraw_savings" {{ request('type') == 'withdraw_savings' ? 'selected' : '' }}>Withdraw Savings</option>
                                <option value="withdraw_deposit" {{ request('type') == 'withdraw_deposit' ? 'selected' : '' }}>Withdraw Deposit</option>
                                <option value="unjoin_membership" {{ request('type') == 'unjoin_membership' ? 'selected' : '' }}>Unjoin Membership</option>
                                <option value="condolences" {{ request('type') == 'condolences' ? 'selected' : '' }}>Condolences</option>
                                <option value="injured_at_work" {{ request('type') == 'injured_at_work' ? 'selected' : '' }}>Injured at Work</option>
                                <option value="sick_for_30_days" {{ request('type') == 'sick_for_30_days' ? 'selected' : '' }}>Sick for 30 Days</option>
                                <option value="benefit_from_disasters" {{ request('type') == 'benefit_from_disasters' ? 'selected' : '' }}>Benefit from Disasters</option>
                                <option value="join_membership" {{ request('type') == 'join_membership' ? 'selected' : '' }}>Join Membership</option>
                                <option value="ura_mobile" {{ request('type') == 'ura_mobile' ? 'selected' : '' }}>URA Mobile</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="pending_overdue" {{ request('status') == 'pending_overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Per Page</label>
                            <select class="form-select" name="per_page">
                                <option value="15" {{ request('per_page', 15) == '15' ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-12 d-flex align-items-end justify-content-center gap-2 mt-3">
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%); color: white; border: none;">
                                <i class="fas fa-search me-1"></i>Apply Filters
                            </button>
                            <a href="{{ route('enquiries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear All
                            </a>
                            <button type="button" class="btn btn-outline-success" onclick="setDateRange('today')">
                                <i class="fas fa-calendar-day me-1"></i>Today
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="setDateRange('week')">
                                <i class="fas fa-calendar-week me-1"></i>This Week
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="setDateRange('month')">
                                <i class="fas fa-calendar-alt me-1"></i>This Month
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions (Hidden by default) -->
    <div id="bulkActions" class="alert alert-primary d-none mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><span id="selectedCount">0</span> enquiries selected</strong>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->user()->hasRole('registrar_hq'))
                    <button class="btn btn-success btn-sm" id="bulkAssignBtn" onclick="showBulkAssignModal()" style="display:none;">
                        <i class="fas fa-user-plus me-1"></i>Bulk Assign
                    </button>
                    <button class="btn btn-warning btn-sm" id="bulkReassignBtn" onclick="showBulkReassignModal()" style="display:none;">
                        <i class="fas fa-exchange-alt me-1"></i>Bulk Reassign
                    </button>
                @endif
                @if(auth()->user()->hasRole(['registrar_hq', 'superadmin']))
                    <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" onclick="confirmBulkDelete()" style="display:none;">
                        <i class="fas fa-trash me-1"></i>Bulk Delete
                    </button>
                @endif
                <button class="btn btn-secondary btn-sm" onclick="clearSelection()">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            <i class="fas fa-table me-2 text-primary"></i>Enquiries Management
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ number_format($enquiries->total()) }} total enquiries
                            @if($type)
                                • {{ ucfirst(str_replace('_', ' ', $type)) }} type
                            @endif
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-soft text-primary px-3 py-2">
                            <i class="fas fa-chart-bar me-1"></i>
                            Total: {{ number_format($enquiries->total()) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 80vh; overflow-x: auto; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0 fixed-table">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>S/N</th>
                                    <th>Date Received</th>
                                    <th>Check Number</th>
                                    <th>Full Name</th>
                                    <th>Account Number</th>
                                    <th>Bank Name</th>
                                    <th>Region</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Registered By</th>
                                    <th>Assigned To</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enquiries as $enquiry)
                                @php
                                    $isOverdue = $enquiry->status == 'pending' &&
                                                $enquiry->created_at->diffInWeekdays(now()) >= 3;
                                    $daysDiff = $enquiry->created_at->diffInWeekdays(now());
                                @endphp
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input enquiry-checkbox" type="checkbox"
                                                   value="{{ $enquiry->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $loop->iteration + (($enquiries->currentPage() - 1) * $enquiries->perPage()) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $enquiry->date_received ?? $enquiry->created_at->format('Y-m-d') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-primary fw-bold">
                                            {{ $enquiry->check_number }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ ucwords($enquiry->full_name) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $enquiry->account_number }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center text-uppercase">
                                            {{ $enquiry->bank_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ ucwords($enquiry->region->name ?? $enquiry->registeredBy->district->region->name ?? 'No Region') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $enquiry->phone }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($isOverdue)
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Overdue ({{ $daysDiff }} days)
                                            </span>
                                        @else
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-warning text-dark',
                                                    'assigned' => 'bg-info',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                ];
                                                $statusIcons = [
                                                    'pending' => 'fas fa-clock',
                                                    'assigned' => 'fas fa-user-check',
                                                    'approved' => 'fas fa-check-circle',
                                                    'rejected' => 'fas fa-times-circle',
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusClasses[$enquiry->status] ?? 'bg-secondary' }} fs-6">
                                                <i class="{{ $statusIcons[$enquiry->status] ?? 'fas fa-question' }} me-1"></i>
                                                {{ ucwords($enquiry->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $enquiry->registeredBy->name ?? 'N/A' }}</h6>
                                            @if($enquiry->registeredBy && $enquiry->registeredBy->district)
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $enquiry->registeredBy->district->name }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($enquiry->users->count() > 0)
                                            <div>
                                                <h6 class="mb-1">{{ $enquiry->users->first()->name }}</h6>
                                                <small class="badge bg-light text-dark">
                                                    {{ $enquiry->users->first()->getRoleNames()->implode(', ') }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">
                                                <i class="fas fa-user-slash me-1"></i>Not assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $enquiry->created_at->format('M d, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $enquiry->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown ms-auto">
                                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <!-- View Action -->
                                                <li><a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                                                <li><hr class="dropdown-divider"></li>

                                                @if(auth()->user()->hasRole('registrar_hq'))
                                                    <!-- Assign Action -->
                                                    @if(in_array($enquiry->status, ['pending', 'pending_overdue']))
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @elseif(in_array($enquiry->status, ['assigned', 'pending_overdue']))
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reassignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-switch me-2"></i>Reassign</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                @endif

                                                @if($enquiry->registered_by == auth()->user()->id)
                                                    <!-- Edit Action - Only for owner and if pending -->
                                                    @if($enquiry->status == 'pending' && !$isOverdue)
                                                    <li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif

                                                    <!-- Delete Action - Only for owner -->
                                                    @if(in_array($enquiry->status, ['pending', 'rejected']) && $enquiry->status != 'assigned')
                                                    <li>
                                                        <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this enquiry?')"><i class="mdi mdi-delete me-2"></i>Delete</button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                @endif

                                                <!-- Loan Details -->
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $enquiry->check_number]) }}">
                                                        <i class="bx bx-show me-2"></i> Loan Details
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>

                                                <!-- Contribution Details -->
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $enquiry->check_number]) }}">
                                                        <i class="bx bx-show me-2"></i> Contribution Details
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                            <h5>No enquiries found</h5>
                                            <p>Try adjusting your search criteria or create a new enquiry.</p>
                                            <a href="{{ route('enquiries.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Create New Enquiry
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                @if($enquiries->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="text-muted fw-medium">
                                <i class="fas fa-info-circle me-2"></i>
                                Showing <span class="fw-bold text-primary">{{ $enquiries->firstItem() }}</span> to
                                <span class="fw-bold text-primary">{{ $enquiries->lastItem() }}</span> of
                                <span class="fw-bold text-primary">{{ number_format($enquiries->total()) }}</span> results
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-end">
                                {{ $enquiries->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modals -->
@foreach($enquiries as $enquiry)
    <!-- Assign Modal -->
    @include('modals.assign_enquries')

    <!-- Reassign Modal -->
    @include('modals.reassign_enquiry')
@endforeach

<!-- Bulk Operation Modals -->
@include('modals.bulk_assign')
@include('modals.bulk_reassign')

<!-- JavaScript for Modern Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCheckboxes();
});

// Checkbox Management
function initializeCheckboxes() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const enquiryCheckboxes = document.querySelectorAll('.enquiry-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectAllCheckbox.addEventListener('change', function() {
        enquiryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    enquiryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
        const count = checkedBoxes.length;

        selectedCount.textContent = count;

        if (count > 0) {
            bulkActions.classList.remove('d-none');

            // Get statuses of selected enquiries
            const selectedStatuses = Array.from(checkedBoxes).map(cb => {
                const row = cb.closest('tr');
                const statusBadge = row.querySelector('.badge');
                return statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
            });

            // Show/hide bulk buttons based on selected enquiry statuses
            const bulkAssignBtn = document.getElementById('bulkAssignBtn');
            const bulkReassignBtn = document.getElementById('bulkReassignBtn');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            // Show assign button if any pending enquiries are selected
            const hasPending = selectedStatuses.some(s => s === 'pending');
            if (bulkAssignBtn) bulkAssignBtn.style.display = hasPending ? 'inline-block' : 'none';

            // Show reassign button if any assigned enquiries are selected
            const hasAssigned = selectedStatuses.some(s => s === 'assigned');
            if (bulkReassignBtn) bulkReassignBtn.style.display = hasAssigned ? 'inline-block' : 'none';

            // Show delete button only if all selected are pending or rejected and user owns them
            const canDelete = selectedStatuses.every(s => s === 'pending' || s === 'rejected');
            if (bulkDeleteBtn) bulkDeleteBtn.style.display = canDelete ? 'inline-block' : 'none';

        } else {
            bulkActions.classList.add('d-none');
            // Hide all bulk buttons
            const bulkBtns = ['bulkAssignBtn', 'bulkReassignBtn', 'bulkDeleteBtn'];
            bulkBtns.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.style.display = 'none';
            });
        }

        selectAllCheckbox.checked = count === enquiryCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < enquiryCheckboxes.length;
    }
}

// Modal Functions for bulk operations

function showBulkAssignModal() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }
    document.getElementById('bulkAssignCount').textContent = checkedBoxes.length;
    new bootstrap.Modal(document.getElementById('bulkAssignModal')).show();
}

function showBulkReassignModal() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }
    document.getElementById('bulkReassignCount').textContent = checkedBoxes.length;
    new bootstrap.Modal(document.getElementById('bulkReassignModal')).show();
}

// Bulk Assignment Functions

function bulkAssignEnquiries() {
    const userId = document.getElementById('bulkAssignUserId').value;
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (!userId) {
        alert('Please select a user');
        return;
    }

    // Get enquiry types from selected rows
    const enquiryTypes = Array.from(checkedBoxes).map(cb => {
        const row = cb.closest('tr');
        const typeCell = row.querySelector('td:nth-child(4)'); // Assuming type is in 4th column
        return typeCell ? typeCell.textContent.trim() : '';
    });

    fetch('/enquiries/bulk-assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            enquiry_ids: enquiryIds,
            user_id: userId,
            enquiry_types: enquiryTypes
        })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('bulkAssignModal')).hide();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Bulk assignment failed: Some enquiries require different user roles');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during bulk assignment');
    });
}

function bulkReassignEnquiries() {
    const userId = document.getElementById('bulkReassignUserId').value;
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (!userId) {
        alert('Please select a user');
        return;
    }

    // Get enquiry types from selected rows
    const enquiryTypes = Array.from(checkedBoxes).map(cb => {
        const row = cb.closest('tr');
        const typeCell = row.querySelector('td:nth-child(4)'); // Assuming type is in 4th column
        return typeCell ? typeCell.textContent.trim() : '';
    });

    fetch('/enquiries/bulk-reassign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            enquiry_ids: enquiryIds,
            user_id: userId,
            enquiry_types: enquiryTypes
        })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('bulkReassignModal')).hide();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Bulk reassignment failed: Some enquiries require different user roles');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during bulk reassignment');
    });
}

// Delete Functions
function confirmDelete(enquiryId) {
    if (confirm('Are you sure you want to delete this enquiry?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/enquiries/${enquiryId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmBulkDelete() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }

    if (confirm(`Delete ${checkedBoxes.length} selected enquiries?`)) {
        const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

        fetch('/enquiries/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ enquiry_ids: enquiryIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Bulk delete failed');
            }
        });
    }
}

function clearSelection() {
    document.querySelectorAll('.enquiry-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActions').classList.add('d-none');
}
</script>

@endsection