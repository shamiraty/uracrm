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
        background-color: rgba(255,255,255,0.2);
        height: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border-radius: 12px;
        padding: 8px 0;
        min-width: 200px;
        z-index: 1060;
        position: absolute;
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

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown-toggle {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 18px;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dropdown-toggle:hover {
        background-color: #87CEEB;
        color: white;
        transform: scale(1.1);
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
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        background: white;
        border: 1px solid #e9ecef;
    }

    .table-responsive::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: linear-gradient(90deg, #f8f9fa, #e9ecef);
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #87CEEB, #17479e);
        border-radius: 10px;
        border: 2px solid #f8f9fa;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, #17479e, #87CEEB);
    }

    .table-responsive::-webkit-scrollbar-corner {
        background: #f8f9fa;
    }


    /* Modern table design */
    .table {
        margin-bottom: 0;
        background: white;
    }

    .table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        position: relative;
    }

    .table tbody tr::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: transparent;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover::before {
        background: linear-gradient(180deg, #87CEEB, #17479e);
    }

    /* Cell styling improvements */
    .table td {
        border: none;
        position: relative;
        background: white;
        transition: all 0.3s ease;
    }

    .table tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%) !important;
        box-shadow: 0 6px 20px rgba(135, 206, 235, 0.1);
        transform: translateY(-1px);
    }

    /* Badge improvements */
    .badge {
        padding: 8px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Checkbox styling */
    .form-check-input {
        border-radius: 6px;
        border: 2px solid #dee2e6;
        width: 18px;
        height: 18px;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #87CEEB;
        border-color: #87CEEB;
        box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.25);
    }

    .form-check-input:focus {
        border-color: #87CEEB;
        box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.25);
    }

    /* Sticky header improvements */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    /* Fix dropdown visibility and positioning */
    .table td .dropdown {
        position: static;
    }

    .table .dropdown-menu {
        position: absolute !important;
        transform: translate3d(0px, 38px, 0px) !important;
        top: 0px !important;
        left: 0px !important;
        will-change: transform;
        z-index: 1060;
        border: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border-radius: 12px;
        padding: 8px 0;
        min-width: 200px;
        max-width: 250px;
        overflow: visible;
    }

    /* Ensure dropdown doesn't get cut off by table overflow */
    .table-responsive {
        overflow: visible;
    }

    .table-responsive .table {
        position: relative;
        z-index: 1;
    }

    .table tbody tr {
        position: relative;
    }

    .table tbody tr:last-child .dropdown-menu {
        bottom: 100%;
        top: auto;
        transform: translate3d(0px, -8px, 0px) !important;
    }

    .sticky-top th {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

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

    /* Mobile responsiveness improvements */
    @media (max-width: 768px) {
        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem 0.5rem !important;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
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
                                <i class="fas fa-chart-area me-3"></i>Business Analytics Dashboard
                            </h1>
                            <p class="mb-0 text-white opacity-75">Comprehensive enquiry analytics and performance insights</p>
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

    <!-- Manager Business Intelligence Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <div class="card-body text-white p-3 position-relative" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clipboard-list fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['total'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Total Enquiries</p>
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
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['pending'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Pending Review</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark px-2 py-1">ACTION NEEDED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-warning" style="width: {{ $analytics['total'] > 0 ? (($analytics['pending'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round((($analytics['pending'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% of total portfolio
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
                                <i class="fas fa-user-check fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['assigned'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Assigned</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-info px-2 py-1">ACTIVE</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-info" style="width: {{ $analytics['total'] > 0 ? (($analytics['assigned'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round((($analytics['assigned'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% assigned
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
                                <i class="fas fa-check-circle fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['approved'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Approved</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-success px-2 py-1">PROCESSED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-success" style="width: {{ $analytics['total'] > 0 ? (($analytics['approved'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round((($analytics['approved'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% approved
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
                                <i class="fas fa-times-circle fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['rejected'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Rejected</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-danger px-2 py-1">REJECTED</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-danger" style="width: {{ $analytics['total'] > 0 ? (($analytics['rejected'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round((($analytics['rejected'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% of total portfolio
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
                                <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ number_format($analytics['pending_overdue'] ?? 0) }}</h4>
                                    <p class="mb-0 opacity-75 small">Overdue</p>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark px-2 py-1">URGENT</span>
                    </div>
                    <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-warning" style="width: {{ $analytics['total'] > 0 ? (($analytics['pending_overdue'] ?? 0) / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $analytics['total'] > 0 ? round((($analytics['pending_overdue'] ?? 0) / $analytics['total']) * 100, 1) : 0 }}% overdue
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Button -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i>Filter Enquiries
                    @if(request()->hasAny(['search', 'type', 'status', 'date_from', 'date_to']))
                        <span class="badge bg-danger ms-2">{{ collect(['search', 'type', 'status', 'date_from', 'date_to'])->filter(fn($key) => request($key))->count() }}</span>
                    @endif
                </button>
                @if(request()->hasAny(['search', 'type', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('enquiries.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                @endif
            </div>
            <div class="text-muted">
                <small><i class="fas fa-info-circle me-1"></i>Total: {{ number_format($enquiries->total()) }} enquiries</small>
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
                                <h5 class="fw-bold mb-1" style="color: #17479E;">Analytics Data Overview</h5>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-database me-1"></i>{{ number_format($enquiries->total()) }} records
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-sync-alt me-1"></i>Real-time updates
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i>Table Settings
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-columns me-2"></i>Manage Columns</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-sort me-2"></i>Sort Options</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export All Data</a></li>
                                </ul>
                            </div>
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
                                            <i class="fas fa-calendar me-2"></i>
                                            <span class="fw-bold">Date & Time</span>
                                            <i class="fas fa-sort ms-1 opacity-75"></i>
                                        </div>
                                    </th>
                                    <th class="border-0 sortable" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-receipt me-2"></i>
                                            <span class="fw-bold">Check Reference</span>
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
                                            <i class="fas fa-university me-2"></i>
                                            <span class="fw-bold">Bank Information</span>
                                        </div>
                                    </th>
                                    <th class="border-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tags me-2"></i>
                                            <span class="fw-bold">Category</span>
                                        </div>
                                    </th>
                                    <th class="border-0 sortable" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-chart-pie me-2"></i>
                                            <span class="fw-bold">Status & Progress</span>
                                            <i class="fas fa-sort ms-1 opacity-75"></i>
                                        </div>
                                    </th>
                                    <th width="150" class="text-center border-0">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-tools me-2"></i>
                                            <span class="fw-bold">Actions</span>
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
                                @php
                                    $isOverdue = $enquiry->status == 'pending' &&
                                                $enquiry->created_at->diffInWeekdays(now()) >= 3;
                                    $daysDiff = $enquiry->created_at->diffInWeekdays(now());
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="form-check">
                                            <input class="form-check-input enquiry-checkbox" type="checkbox"
                                                   value="{{ $enquiry->id }}"
                                                   style="transform: scale(1.1);">
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="badge badge-counter" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white; font-weight: bold;">
                                            {{ $loop->iteration + (($enquiries->currentPage() - 1) * $enquiries->perPage()) }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            <strong class="text-dark">{{ $enquiry->date_received ?? $enquiry->created_at->format('M d, Y') }}</strong>
                                            <small class="text-muted">{{ $enquiry->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary-soft text-primary px-2 py-1">
                                                {{ $enquiry->check_number }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            <strong class="text-dark">{{ ucwords($enquiry->full_name) }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>{{ $enquiry->force_no ?? 'N/A' }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $enquiry->phone ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            <strong class="text-dark">{{ strtoupper($enquiry->bank_name ?? 'N/A') }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-credit-card me-1"></i>{{ $enquiry->account_number ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-primary text-white px-2 py-1">
                                                {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}
                                            </span>
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ ucwords($enquiry->district->name ?? 'N/A') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            @if($isOverdue)
                                                <span class="badge bg-danger mb-1">
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
                                                <span class="badge {{ $statusClasses[$enquiry->status] ?? 'bg-secondary' }} mb-1">
                                                    <i class="{{ $statusIcons[$enquiry->status] ?? 'fas fa-question' }} me-1"></i>
                                                    {{ ucwords($enquiry->status) }}
                                                </span>
                                            @endif
                                            @if($enquiry->users->count() > 0)
                                                <small class="text-muted">
                                                    <i class="fas fa-user-check me-1"></i>{{ $enquiry->users->first()->name }}
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    <i class="fas fa-user-slash me-1"></i>Not assigned
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-cog me-1"></i>Actions
                                            </button>
                                            <ul class="dropdown-menu shadow border-0" style="min-width: 200px;">
                                                <!-- View Action -->
                                                <li><a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}">
                                                    <i class="fas fa-eye text-info me-2"></i>View Details
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>

                                                @if(auth()->user()->hasRole('registrar_hq'))
                                                    <!-- Assign Action -->
                                                    @if(in_array($enquiry->status, ['pending', 'pending_overdue']))
                                                    <li><a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}">
                                                        <i class="fas fa-user-plus me-2"></i>Assign User
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @elseif(in_array($enquiry->status, ['assigned', 'pending_overdue']))
                                                    <li><a class="dropdown-item text-warning" href="#" data-bs-toggle="modal" data-bs-target="#reassignUserModal-{{ $enquiry->id }}">
                                                        <i class="fas fa-exchange-alt me-2"></i>Reassign User
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                @endif

                                                @if($enquiry->registered_by == auth()->user()->id)
                                                    <!-- Edit Action - Only for owner and if pending -->
                                                    @if($enquiry->status == 'pending' && !$isOverdue)
                                                    <li><a class="dropdown-item text-primary" href="{{ route('enquiries.edit', $enquiry->id) }}">
                                                        <i class="fas fa-edit"></i>Edit Enquiry
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif

                                                    <!-- Delete Action - Only for owner -->
                                                    @if(in_array($enquiry->status, ['pending', 'rejected']) && $enquiry->status != 'assigned')
                                                    <li>
                                                        <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" class="w-100">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent text-start" onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                                                <i class="fas fa-trash-alt"></i>Delete Enquiry
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                @endif

                                                <!-- Loan Details -->
                                                <li>
                                                    <a class="dropdown-item text-info" href="{{ route('deductions.details', ['checkNumber' => $enquiry->check_number]) }}">
                                                        <i class="fas fa-money-bill-wave"></i>Loan Details
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>

                                                <!-- Contribution Details -->
                                                <li>
                                                    <a class="dropdown-item text-secondary" href="{{ route('deductions.contributiondetails', ['checkNumber' => $enquiry->check_number]) }}">
                                                        <i class="fas fa-chart-line"></i>Contribution Details
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);">
                <h5 class="modal-title text-white fw-bold" id="filterModalLabel">
                    <i class="fas fa-filter me-2"></i>Filter Enquiries
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-search me-1"></i>Search
                            </label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search enquiries...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-list me-1"></i>Enquiry Type
                            </label>
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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar me-1"></i>From Date
                            </label>
                            <input type="date" class="form-control" name="date_from" id="modalDateFrom" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar me-1"></i>To Date
                            </label>
                            <input type="date" class="form-control" name="date_to" id="modalDateTo" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-flag me-1"></i>Status
                            </label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="pending_overdue" {{ request('status') == 'pending_overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-list-ol me-1"></i>Items Per Page
                            </label>
                            <select class="form-select" name="per_page">
                                <option value="15" {{ request('per_page', 15) == '15' ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="mb-2 fw-bold text-dark">
                                        <i class="fas fa-clock me-1"></i>Quick Date Ranges
                                    </h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setModalDateRange('today')">
                                            <i class="fas fa-calendar-day me-1"></i>Today
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="setModalDateRange('week')">
                                            <i class="fas fa-calendar-week me-1"></i>This Week
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="setModalDateRange('month')">
                                            <i class="fas fa-calendar-alt me-1"></i>This Month
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="clearModalFilters()">
                    <i class="fas fa-eraser me-1"></i>Clear All
                </button>
                <button type="button" class="btn" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%); color: white; border: none;" onclick="applyFilters()">
                    <i class="fas fa-search me-1"></i>Apply Filters
                </button>
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
                // Find the status column (8th column, index 7)
                const statusCell = row.children[7];
                const statusBadge = statusCell ? statusCell.querySelector('.badge') : null;
                let statusText = '';
                if (statusBadge) {
                    statusText = statusBadge.textContent.toLowerCase().trim();
                    // Handle overdue case
                    if (statusText.includes('overdue')) {
                        statusText = 'pending';
                    }
                }
                return statusText;
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

// Date Range Functions for Modal
function setModalDateRange(range) {
    const dateFrom = document.getElementById('modalDateFrom');
    const dateTo = document.getElementById('modalDateTo');
    const today = new Date();

    switch(range) {
        case 'today':
            const todayStr = today.toISOString().split('T')[0];
            dateFrom.value = todayStr;
            dateTo.value = todayStr;
            break;

        case 'week':
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            const endOfWeek = new Date(today);
            endOfWeek.setDate(today.getDate() - today.getDay() + 6);

            dateFrom.value = startOfWeek.toISOString().split('T')[0];
            dateTo.value = endOfWeek.toISOString().split('T')[0];
            break;

        case 'month':
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            dateFrom.value = startOfMonth.toISOString().split('T')[0];
            dateTo.value = endOfMonth.toISOString().split('T')[0];
            break;
    }
}

// Modal Filter Functions
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const urlParams = new URLSearchParams();

    for (const [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            urlParams.append(key, value);
        }
    }

    const url = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    window.location.href = url;
}

function clearModalFilters() {
    const form = document.getElementById('filterForm');
    form.reset();
    window.location.href = window.location.pathname;
}
</script>

@endsection