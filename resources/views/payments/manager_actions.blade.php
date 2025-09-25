@extends('layouts.app')

@section('content')
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
                <i class="fas fa-user-tie me-1"></i>Payment Approval Center (Manager)
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-2">
                <i class="fas fa-clipboard-check me-2" style="color: #17479E;"></i>Payment Management
            </h2>
         </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <i class="fas fa-user-tie me-1"></i>Manager Dashboard
            </span>
        </div>
    </div>

    <!-- Manager Business Intelligence Dashboard -->
    <div class="row g-4 mb-4">
        <!-- Primary KPI Cards -->
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-chart-area fa-3x"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($analytics['total']) }}</h2>
                                <p class="mb-0 opacity-75">Total Portfolio Under Review</p>
                                <small class="opacity-75 mt-1 d-block">
                                    <i class="fas fa-trending-up me-1"></i>Complete oversight dashboard
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-light text-dark px-2 py-1 mb-2">LIVE</div>
                            <div class="progress" style="height: 3px; background: rgba(255,255,255,0.2);">
                                <div class="progress-bar bg-light" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #87ceeb 0%, #4facfe 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-bell fa-3x"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($analytics['initiated']) }}</h2>
                                <p class="mb-0 opacity-75">Awaiting Your Approval</p>
                                <small class="opacity-75 mt-1 d-block">
                                    <i class="fas fa-money-bill me-1"></i>Tsh {{ number_format($analytics['total_amount_initiated'] ?? 0) }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-warning text-dark px-2 py-1 mb-2">ACTION NEEDED</div>
                            <div class="progress" style="height: 3px; background: rgba(255,255,255,0.2);">
                                <div class="progress-bar bg-warning" style="width: {{ $analytics['total'] > 0 ? ($analytics['initiated'] / $analytics['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #20c997 0%, #17479E 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-thumbs-up fa-3x"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">{{ number_format($analytics['approved']) }}</h2>
                                <p class="mb-0 opacity-75">Successfully Approved</p>
                                <small class="opacity-75 mt-1 d-block">
                                    <i class="fas fa-money-bill me-1"></i>Tsh {{ number_format($analytics['total_amount_approved'] ?? 0) }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-success px-2 py-1 mb-2">PROCESSED</div>
                            <div class="progress" style="height: 3px; background: rgba(255,255,255,0.2);">
                                <div class="progress-bar bg-success" style="width: {{ $analytics['total'] > 0 ? ($analytics['approved'] / $analytics['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Analytics Row -->
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark mb-0">Completed Payments</h6>
                        <div class="badge bg-primary-subtle text-primary px-3 py-2">
                            <i class="fas fa-check-double me-1"></i>{{ number_format($analytics['paid']) }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="p-3 bg-primary-subtle rounded mb-3">
                            <i class="fas fa-medal text-primary fa-3x mb-2"></i>
                            <h3 class="fw-bold text-primary mb-1">{{ $analytics['total'] > 0 ? round(($analytics['paid'] / $analytics['total']) * 100, 1) : 0 }}%</h3>
                            <small class="text-muted">Completion Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark mb-0">Rejected Cases</h6>
                        <div class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>{{ number_format($analytics['rejected']) }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="p-3 bg-danger-subtle rounded mb-3">
                            <i class="fas fa-shield-alt text-danger fa-3x mb-2"></i>
                            <h3 class="fw-bold text-danger mb-1">{{ $analytics['total'] > 0 ? round(($analytics['rejected'] / $analytics['total']) * 100, 1) : 0 }}%</h3>
                            <small class="text-muted">Quality Control</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-lg h-100 border-start border-warning border-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark mb-0">Overdue Items</h6>
                        <div class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="fas fa-clock me-1"></i>{{ number_format($analytics['overdue_initiated'] ?? 0) }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="p-3 bg-warning-subtle rounded mb-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-2"></i>
                            <h3 class="fw-bold text-warning mb-1">URGENT</h3>
                            <small class="text-muted">Requires Attention</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <div class="card-body p-4">
                    <div class="text-center">
                        <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold text-dark mb-2">Manager Efficiency</h6>
                        <div class="row g-2 mt-3">
                            <div class="col-6">
                                <div class="bg-white rounded p-2">
                                    <div class="text-success fw-bold">{{ $analytics['total'] > 0 ? round((($analytics['approved'] + $analytics['paid']) / $analytics['total']) * 100, 1) : 0 }}%</div>
                                    <small class="text-muted">Approval Rate</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white rounded p-2">
                                    <div class="text-info fw-bold">Live</div>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Manager Actions Bar -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
        <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-tie fa-lg me-2"></i>
                    <span class="fw-bold">Manager Dashboard</span>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-filter me-1"></i>Filters Active
                        </span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#managerFilterModal">
                        <i class="fas fa-sliders-h me-1"></i>Advanced Search
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i>Export Data
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('payment.manager.dashboard') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-refresh me-1"></i>Reset Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulkActions" class="card border-0 shadow-sm mb-4" style="display: none;">
        <div class="card-body bg-success-soft">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium text-success">
                    <span id="selectedCount">0</span> payments selected
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="bulkApprove()">
                        <i class="fas fa-check me-1"></i>Bulk Approve
                    </button>
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

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Payment Approval Queue</h5>
                <small class="text-muted">
                    {{ number_format($payments->total()) }} total entries
                </small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th width="80" class="text-center">S/N</th>
                            <th width="120">Date Initiated</th>
                            <th width="140">Check Number</th>
                            <th width="200">Full Name & Details</th>
                            <th width="150">Amount</th>
                            <th width="150">Initiated By</th>
                            <th width="130">Type</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        @php
                            $isOverdue = $payment->created_at->diffInDays(now()) >= 2;
                        @endphp
                        <tr class="{{ $isOverdue ? 'table-warning' : '' }}">
                            <td class="text-center">
                                <div class="form-check">
                                    @if($payment->status === 'initiated')
                                        <input class="form-check-input payment-checkbox" type="checkbox"
                                               value="{{ $payment->id }}">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-muted">
                                    {{ $loop->iteration + (($payments->currentPage() - 1) * $payments->perPage()) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="text-dark">{{ $payment->created_at->format('M d, Y') }}</strong>
                                    <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                    @if($isOverdue)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-soft text-primary px-2 py-1">
                                    {{ $payment->enquiry->check_number }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="text-dark">{{ ucwords($payment->enquiry->full_name) }}</strong>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>{{ $payment->enquiry->force_no ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $payment->enquiry->phone ?? 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="text-success fs-5">Tsh {{ number_format($payment->amount) }}</strong>
                                    <small class="text-muted">{{ strtoupper($payment->enquiry->bank_name ?? 'N/A') }}</small>
                                    <small class="text-muted">{{ $payment->enquiry->account_number ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong class="text-dark">{{ $payment->initiatedBy->name ?? 'N/A' }}</strong>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $payment->initiatedBy->getRoleNames()->first() ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info-soft text-info">
                                    {{ ucfirst(str_replace('_', ' ', $payment->enquiry->type)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-info me-1"
                                            data-bs-toggle="modal" data-bs-target="#viewModal-{{ $payment->id }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($payment->status === 'initiated')
                                        <button type="button" class="btn btn-sm btn-outline-success me-1"
                                                data-bs-toggle="modal" data-bs-target="#approveModal-{{ $payment->id }}"
                                                title="Approve Payment">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $payment->id }}"
                                                title="Reject Payment">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @else
                                        <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'paid' ? 'primary' : 'danger') }} px-2 py-1">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @endif

                                    <a href="{{ route('enquiries.show', $payment->enquiry->id) }}"
                                       class="btn btn-sm btn-outline-secondary ms-1" title="View Full Details">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                    <h5>No pending approvals</h5>
                                    <p>All payment requests have been processed.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="text-muted fw-medium">
                        <i class="fas fa-info-circle me-2"></i>
                        Showing <span class="fw-bold text-primary">{{ $payments->firstItem() }}</span> to
                        <span class="fw-bold text-primary">{{ $payments->lastItem() }}</span> of
                        <span class="fw-bold text-primary">{{ number_format($payments->total()) }}</span> results
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-end">
                        {{ $payments->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@include('payments.modals.manager_modals')

<style>
/* ===== MANAGER BUSINESS INTELLIGENCE DASHBOARD STYLES ===== */

/* Enhanced Analytics Cards */
.analytics-card:hover {
    transform: translateY(-3px) scale(1.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 20px 40px rgba(23, 71, 158, 0.15) !important;
}

.analytics-card {
    transition: all 0.3s ease;
    border-radius: 16px !important;
    overflow: hidden;
}

/* Modern Table Styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    border: none !important;
    padding: 1rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    color: white !important;
}

.table tbody td {
    border: none !important;
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    padding: 1.25rem 1rem !important;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(135, 206, 235, 0.03) 100%);
    transform: scale(1.001);
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.08);
}

/* Manager-specific enhancements */
.manager-table-row {
    position: relative;
}

.manager-table-row.overdue {
    border-left: 4px solid #ff4500 !important;
    background: linear-gradient(135deg, rgba(255, 69, 0, 0.05) 0%, rgba(255, 193, 7, 0.03) 100%);
    animation: pulse 2s infinite;
}

/* Enhanced Badges and Progress */
.status-badge-manager {
    border-radius: 20px !important;
    font-weight: 700 !important;
    font-size: 12px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Soft Background Colors */
.bg-success-soft {
    background: linear-gradient(135deg, rgba(32, 201, 151, 0.08) 0%, rgba(25, 135, 84, 0.05) 100%) !important;
}

.bg-primary-soft {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.08) 0%, rgba(79, 172, 254, 0.05) 100%) !important;
}

.bg-info-soft {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(13, 202, 240, 0.05) 100%) !important;
}

.bg-warning-subtle {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.05) 100%) !important;
}

.bg-danger-subtle {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.08) 0%, rgba(220, 53, 69, 0.05) 100%) !important;
}

.bg-primary-subtle {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.08) 0%, rgba(79, 172, 254, 0.05) 100%) !important;
}

.text-info-soft {
    color: #0dcaf0 !important;
}

.table-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0.02) 100%) !important;
}

/* Action Button Enhancements */
.btn-sm {
    border-radius: 8px !important;
    font-weight: 600;
    transition: all 0.2s ease;
    text-transform: uppercase;
    font-size: 11px !important;
    letter-spacing: 0.5px;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Card Enhancements */
.card {
    border-radius: 16px !important;
    border: none !important;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 16px 16px 0 0 !important;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(23, 71, 158, 0.1) !important;
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

/* Priority Indicators */
.priority-high {
    border-left: 4px solid #ffc107 !important;
}

.priority-critical {
    border-left: 4px solid #dc3545 !important;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(255, 193, 7, 0.03) 100%);
}

/* Responsive Design */
@media (max-width: 768px) {
    .table thead th,
    .table tbody td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }

    .analytics-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Bulk selection functionality
let selectedPayments = [];

document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkboxes
    document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectedPayments = Array.from(checkedBoxes).map(cb => cb.value);

    if (selectedPayments.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedPayments.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkApprove() {
    if (selectedPayments.length === 0) {
        alert('Please select payments to approve.');
        return;
    }

    // Show bulk approve modal with OTP
    $('#bulkApproveModal').modal('show');
    document.getElementById('bulkApproveCount').textContent = selectedPayments.length;
}

function bulkReject() {
    if (selectedPayments.length === 0) {
        alert('Please select payments to reject.');
        return;
    }

    // Show bulk reject modal
    $('#bulkRejectModal').modal('show');
    document.getElementById('bulkRejectCount').textContent = selectedPayments.length;
}

function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("payment.manager.dashboard") }}?' + params.toString();
}
</script>

<!-- Manager Filter Modal -->
<div class="modal fade" id="managerFilterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-tie me-2"></i>Manager Search & Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="GET" action="{{ route('payment.manager.dashboard') }}" id="managerFilterForm">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-search me-1 text-primary"></i>Search Payment Records
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control form-control-lg" placeholder="Search by check number, member name, or account number...">
                            <small class="form-text text-muted">Find specific payments by check number, member details, or bank account</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-tasks me-1 text-warning"></i>Payment Status
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">🔍 All Payment Status</option>
                                <option value="initiated" {{ request('status') === 'initiated' ? 'selected' : '' }}>
                                    ⏳ Pending Approval (Require Action)
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                    ✅ Approved (Ready for Payment)
                                </option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>
                                    💰 Paid (Completed)
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                    ❌ Rejected
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-alt me-1 text-info"></i>Quick Time Periods
                            </label>
                            <select class="form-select form-select-lg" id="managerQuickDateRange">
                                <option value="">📅 Custom Date Range</option>
                                <option value="today">📆 Today's Submissions</option>
                                <option value="yesterday">📆 Yesterday's Submissions</option>
                                <option value="this_week">📅 This Week</option>
                                <option value="last_week">📅 Last Week</option>
                                <option value="this_month">📅 This Month</option>
                                <option value="last_month">📅 Last Month</option>
                                <option value="pending_only">⏰ Only Pending Approvals</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-plus me-1 text-success"></i>From Date
                            </label>
                            <input type="date" name="date_from" id="managerDateFrom" value="{{ request('date_from') }}"
                                   class="form-control form-control-lg">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-check me-1 text-success"></i>To Date
                            </label>
                            <input type="date" name="date_to" id="managerDateTo" value="{{ request('date_to') }}"
                                   class="form-control form-control-lg">
                        </div>

                        <!-- Manager Quick Action Shortcuts -->
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-lightbulb me-3 fa-lg"></i>
                                <div>
                                    <strong>Quick Tip:</strong> Use "Pending Approvals" to focus on payments requiring your immediate attention.
                                    Use date filters to review historical approvals and track payment patterns.
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <a href="{{ route('payment.manager.dashboard') }}" class="btn btn-outline-warning">
                    <i class="fas fa-refresh me-1"></i>Clear All Filters
                </a>
                <button type="button" class="btn btn-primary" style="background: #17479E;" onclick="applyManagerFilters()">
                    <i class="fas fa-search me-1"></i>Apply Search & Filters
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Manager quick date range selection
document.getElementById('managerQuickDateRange').addEventListener('change', function() {
    const range = this.value;
    const fromDate = document.getElementById('managerDateFrom');
    const toDate = document.getElementById('managerDateTo');
    const statusSelect = document.querySelector('select[name="status"]');

    const today = new Date();
    const formatDate = (date) => date.toISOString().split('T')[0];

    switch(range) {
        case 'today':
            fromDate.value = formatDate(today);
            toDate.value = formatDate(today);
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            fromDate.value = formatDate(yesterday);
            toDate.value = formatDate(yesterday);
            break;
        case 'this_week':
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            fromDate.value = formatDate(startOfWeek);
            toDate.value = formatDate(today);
            break;
        case 'last_week':
            const lastWeekStart = new Date(today);
            lastWeekStart.setDate(today.getDate() - today.getDay() - 7);
            const lastWeekEnd = new Date(today);
            lastWeekEnd.setDate(today.getDate() - today.getDay() - 1);
            fromDate.value = formatDate(lastWeekStart);
            toDate.value = formatDate(lastWeekEnd);
            break;
        case 'this_month':
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            fromDate.value = formatDate(startOfMonth);
            toDate.value = formatDate(today);
            break;
        case 'last_month':
            const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
            fromDate.value = formatDate(lastMonthStart);
            toDate.value = formatDate(lastMonthEnd);
            break;
        case 'pending_only':
            statusSelect.value = 'initiated';
            fromDate.value = '';
            toDate.value = '';
            break;
    }
});

function applyManagerFilters() {
    document.getElementById('managerFilterForm').submit();
}
</script>

@endsection