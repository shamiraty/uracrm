@extends('layouts.app')

@section('content')
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
            <li class="breadcrumb-item">Disbursement Management</li>
            <li class="breadcrumb-item active fw-bold">{{ $title }}</li>
        </ol>
    </nav>

    <!-- Compact Page Header with Animation -->
    <div class="page-header-compact mb-3 animate-fade-in">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact">
                            @if($status == 'rejected')
                                <i class="fas fa-times-circle"></i>
                            @elseif($status == 'failed')
                                <i class="fas fa-exclamation-triangle"></i>
                            @elseif($status == 'disbursed')
                                <i class="fas fa-check-double"></i>
                            @else
                                <i class="fas fa-money-bill-wave"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0">
                            {{ $title }}
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-chart-line me-1"></i>
                            Track and manage loan disbursements
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    <button class="btn btn-ura-primary-sm animate-hover" onclick="refreshDisbursements()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
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

    <!-- Modern KPI Dashboard with Glassmorphism -->
    <div class="kpi-dashboard-modern mb-3">
        <div class="kpi-backdrop"></div>
        <div class="row g-3">
            <!-- Rejected Card -->
            <div class="col-md-4">
                <div class="kpi-card-modern glass-card animate-float-1" onclick="window.location.href='{{ route('disbursements.index', ['status' => 'rejected']) }}'" data-status="rejected" style="cursor: pointer;">
                    <div class="kpi-glow rejected-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern rejected-icon">
                                <i class="fas fa-times-circle"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern danger-badge">REJECTED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $rejectedCount }}">
                                <span class="number-animate">{{ $rejectedCount }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon rejected-trend">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span class="trend-text text-danger">Rejected Disbursements</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="rejected-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill rejected-progress" style="width: {{ $rejectedCount > 0 ? min(($rejectedCount / ($rejectedCount + $failedCount + $disbursedCount)) * 100, 100) : 0 }}%"></div>
                            </div>
                            <span class="footer-text">Declined disbursements</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Failed Card -->
            <div class="col-md-4">
                <div class="kpi-card-modern glass-card animate-float-2" onclick="window.location.href='{{ route('disbursements.index', ['status' => 'failed']) }}'" data-status="failed" style="cursor: pointer;">
                    <div class="kpi-glow warning-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern warning-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern warning-badge">FAILED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $failedCount }}">
                                <span class="number-animate">{{ $failedCount }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon warning-trend">
                                    <i class="fas fa-exclamation"></i>
                                </span>
                                <span class="trend-text text-warning">NMB Failed</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="failed-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill warning-progress" style="width: {{ $failedCount > 0 ? min(($failedCount / ($rejectedCount + $failedCount + $disbursedCount)) * 100, 100) : 0 }}%"></div>
                            </div>
                            <span class="footer-text">Bank processing failed</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Disbursed Card -->
            <div class="col-md-4">
                <div class="kpi-card-modern glass-card animate-float-3" onclick="window.location.href='{{ route('disbursements.index', ['status' => 'disbursed']) }}'" data-status="disbursed" style="cursor: pointer;">
                    <div class="kpi-glow disbursed-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern disbursed-icon">
                                <i class="fas fa-check-double"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern success-badge">DISBURSED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $disbursedCount }}">
                                <span class="number-animate">{{ $disbursedCount }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon disbursed-trend">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="trend-text text-success">Successfully Disbursed</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="disbursed-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill disbursed-progress" style="width: {{ $disbursedCount > 0 ? min(($disbursedCount / ($rejectedCount + $failedCount + $disbursedCount)) * 100, 100) : 0 }}%"></div>
                            </div>
                            <span class="footer-text">Completed payments</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced URASACCOS Filter Section with Animation -->
    <div class="filter-card-ura mb-3 animate-fade-in-delayed">
        <div class="filter-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="filter-title mb-0">
                        <i class="fas fa-filter filter-icon"></i>
                        Filter Disbursements
                    </h5>
                    <p class="filter-subtitle mb-0">Quick access to disbursement status</p>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-sm btn-ura-light" onclick="resetFilters()">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="filter-body">
            <!-- Quick Filter Pills -->
            <div class="filter-pills mb-3">
                <span class="filter-pills-label">Status Filter:</span>
                <div class="filter-pills-group">
                    <a href="{{ route('disbursements.index', ['status' => 'all']) }}" 
                       class="filter-pill {{ $status == 'all' ? 'active' : '' }}">
                        <i class="fas fa-list me-1"></i>All
                        <span class="filter-pill-count">{{ $rejectedCount + $failedCount + $disbursedCount }}</span>
                    </a>
                    <a href="{{ route('disbursements.index', ['status' => 'rejected']) }}" 
                       class="filter-pill {{ $status == 'rejected' ? 'active rejected-pill' : '' }}">
                        <i class="fas fa-times-circle me-1"></i>Rejected
                        <span class="filter-pill-count">{{ $rejectedCount }}</span>
                    </a>
                    <a href="{{ route('disbursements.index', ['status' => 'failed']) }}" 
                       class="filter-pill {{ $status == 'failed' ? 'active warning-pill' : '' }}">
                        <i class="fas fa-exclamation-triangle me-1"></i>Failed
                        <span class="filter-pill-count">{{ $failedCount }}</span>
                    </a>
                    <a href="{{ route('disbursements.index', ['status' => 'disbursed']) }}" 
                       class="filter-pill {{ $status == 'disbursed' ? 'active success-pill' : '' }}">
                        <i class="fas fa-check-double me-1"></i>Disbursed
                        <span class="filter-pill-count">{{ $disbursedCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Disbursements Table -->
    <div class="modern-table-wrapper">
        <div class="modern-table-header-section">
            <div class="table-header-content">
                <div class="table-title-group">
                    <div class="table-icon-wrapper">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="table-title-text">
                        <h4 class="table-main-title">Disbursement Records</h4>
                        <div class="table-subtitle">
                            <span class="record-count">
                                <span class="count-number">{{ $disbursements->count() }}</span>
                                <span class="count-text">of {{ $disbursements->total() ?? 0 }}</span>
                            </span>
                            <span class="separator">•</span>
                            <span class="filter-status">
                                @if($status != 'all')
                                    <i class="fas fa-filter filter-active"></i> {{ ucfirst($status) }}
                                @else
                                    <i class="fas fa-list"></i> All Records
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="table-actions-group">
                    <div class="quick-stats">
                        <div class="stat-item stat-rejected">
                            <span class="stat-value">{{ $rejectedCount }}</span>
                            <span class="stat-label">Rejected</span>
                        </div>
                        <div class="stat-item stat-failed">
                            <span class="stat-value">{{ $failedCount }}</span>
                            <span class="stat-label">Failed</span>
                        </div>
                        <div class="stat-item stat-disbursed">
                            <span class="stat-value">{{ $disbursedCount }}</span>
                            <span class="stat-label">Disbursed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modern-table-body-wrapper">
            <div class="modern-table-container">
                <table class="modern-data-table" id="disbursementsTable">
                    <thead class="modern-table-header">
                        <tr>
                            <th class="checkbox-column">
                                <div class="modern-checkbox">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>
                            </th>
                            <th class="sortable-column" onclick="sortTable('reference')">
                                <div class="th-content">
                                    <span class="th-text">Reference</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column" onclick="sortTable('employee')">
                                <div class="th-content">
                                    <span class="th-text">Employee</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column text-end" onclick="sortTable('amount')">
                                <div class="th-content justify-content-end">
                                    <span class="th-text">Amount</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="text-center">Bank</th>
                            <th class="text-center">Account</th>
                            <th class="text-center">Status</th>
                            <th class="sortable-column text-center" onclick="sortTable('date')">
                                <div class="th-content justify-content-center">
                                    <span class="th-text">Date</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="text-center action-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="modern-table-body">
                        @forelse ($disbursements as $disbursement)
                            <tr class="modern-table-row clickable-row" data-id="{{ $disbursement->id }}">
                                <td class="checkbox-column">
                                    <div class="modern-checkbox">
                                        <input type="checkbox" class="disbursement-checkbox" value="{{ $disbursement->id }}" id="check-{{ $disbursement->id }}">
                                        <label for="check-{{ $disbursement->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="reference-info">
                                        @if($disbursement->loanOffer)
                                            <div class="reference-primary">{{ $disbursement->loanOffer->fsp_reference_number ?? $disbursement->loanOffer->application_number ?? 'N/A' }}</div>
                                            <div class="reference-secondary">{{ $disbursement->loanOffer->check_number ?? '-' }}</div>
                                        @else
                                            <div class="reference-primary">N/A</div>
                                            <div class="reference-secondary">-</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="employee-column">
                                    <div class="employee-info">
                                        <div class="employee-details">
                                            @if($disbursement->loanOffer)
                                                <div class="employee-name">
                                                    {{ $disbursement->loanOffer->first_name ?? '' }} {{ $disbursement->loanOffer->last_name ?? '' }}
                                                    @if($disbursement->loanOffer->loan_type === 'topup' || $disbursement->loanOffer->offer_type === 'TOP_UP')
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem;">
                                                            <i class="fas fa-sync-alt"></i> TOPUP
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success ms-2" style="font-size: 0.65rem;">
                                                            <i class="fas fa-plus-circle"></i> NEW
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="employee-id">
                                                    {{ $disbursement->loanOffer->check_number ?? '' }}
                                                    @if($disbursement->loanOffer->loan_type === 'topup' && $disbursement->loanOffer->topupAsNew && $disbursement->loanOffer->topupAsNew->original_loan_number)
                                                        <span class="badge bg-secondary ms-2" style="font-size: 0.6rem;">
                                                            <i class="fas fa-link"></i> {{ $disbursement->loanOffer->topupAsNew->original_loan_number }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="employee-name">Unknown</div>
                                                <div class="employee-id">-</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="amount-cell">
                                        <div class="amount-primary">{{ number_format($disbursement->amount ?? 0, 0) }}</div>
                                        <div class="amount-secondary">TZS</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($disbursement->loanOffer)
                                        @if($disbursement->loanOffer->bank)
                                            <span class="modern-badge badge-info" title="{{ $disbursement->loanOffer->bank->name }}">
                                                <i class="fas fa-university me-1"></i>
                                                {{ $disbursement->loanOffer->bank->short_name ?: $disbursement->loanOffer->bank->name }}
                                            </span>
                                        @elseif($disbursement->loanOffer->swift_code)
                                            <span class="text-muted small">
                                                <i class="fas fa-barcode me-1"></i>{{ $disbursement->loanOffer->swift_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="account-number">{{ $disbursement->loanOffer->bank_account_number ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($disbursement->status == 'rejected')
                                        <span class="modern-badge badge-danger">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                    @elseif($disbursement->status == 'failed')
                                        <span class="modern-badge badge-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Failed
                                        </span>
                                        @if($disbursement->error_message)
                                            <div class="text-muted small mt-1">{{ Str::limit($disbursement->error_message, 50) }}</div>
                                        @endif
                                    @elseif($disbursement->status == 'disbursed')
                                        <span class="modern-badge badge-success">
                                            <i class="fas fa-check-double me-1"></i>Disbursed
                                        </span>
                                    @else
                                        <span class="modern-badge badge-secondary">{{ ucfirst($disbursement->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="date-info">
                                        <div class="date-primary">{{ $disbursement->created_at->format('d/m/Y') }}</div>
                                        <div class="date-secondary">{{ $disbursement->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="action-column">
                                    <div class="action-buttons">
                                        @php
                                            // Match the controller's logic for status determination
                                            $actualStatus = $disbursement->status;
                                            $isFailedStatus = false;
                                            $isSuccessStatus = false;
                                            $isRejectedStatus = false;
                                            
                                            // Check if it's a failed status (matching controller logic)
                                            if ($actualStatus === 'failed' || $actualStatus === 'error') {
                                                $isFailedStatus = true;
                                            } elseif ($disbursement->loanOffer && $disbursement->loanOffer->callbacks) {
                                                $failedCallbacks = $disbursement->loanOffer->callbacks->whereIn('status', ['FAILED', 'ERROR', 'REJECTED'])->count();
                                                if ($failedCallbacks > 0) {
                                                    $isFailedStatus = true;
                                                }
                                            }
                                            
                                            // Check if it's a success/disbursed status (matching controller logic)
                                            if (!$isFailedStatus) {
                                                if ($actualStatus === 'success' || $actualStatus === 'disbursed') {
                                                    $isSuccessStatus = true;
                                                } elseif ($disbursement->loanOffer) {
                                                    if ($disbursement->loanOffer->status === 'disbursed' || $disbursement->loanOffer->approval === 'DISBURSED') {
                                                        $isSuccessStatus = true;
                                                    }
                                                }
                                            }
                                            
                                            // Check if it's a rejected status (matching controller logic)
                                            if (!$isFailedStatus && !$isSuccessStatus) {
                                                if ($actualStatus === 'rejected') {
                                                    $isRejectedStatus = true;
                                                } elseif ($disbursement->loanOffer && $disbursement->loanOffer->approval === 'REJECTED') {
                                                    $isRejectedStatus = true;
                                                }
                                            }
                                        @endphp
                                        
                                        <!-- View Details Button - Available for all statuses -->
                                        <button class="action-btn view-btn" 
                                                onclick="viewLoanDetails({{ $disbursement->loan_offer_id ?? 'null' }}, '{{ $actualStatus }}', {{ $isFailedStatus ? $disbursement->id : 'null' }}, {{ $isFailedStatus ? 'true' : 'false' }}, {{ $isSuccessStatus ? 'true' : 'false' }}, {{ $isRejectedStatus ? 'true' : 'false' }})" 
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($disbursement->loanOffer)
                                        <!-- View Loan Document - Available for all statuses -->
                                        <button class="action-btn edit-btn" 
                                                onclick="window.location.href='{{ route('loan-offers.show', $disbursement->loan_offer_id) }}'" 
                                                title="View Loan Document">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                        @endif
                                        
                                        <!-- Debug: Status={{ $actualStatus }}, Failed={{ $isFailedStatus ? 'true' : 'false' }} -->
                                        @if($isFailedStatus === true)
                                            @if($disbursement->loanOffer && $disbursement->loanOffer->callbacks->count() > 0)
                                            <!-- View Callbacks - Only for failed with callbacks -->
                                            <button class="action-btn info-btn" 
                                                    onclick="window.location.href='{{ route('loan-offers.callbacks', $disbursement->loan_offer_id) }}'" 
                                                    title="View Callbacks">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            @endif
                                            
                                            <!-- Retry Button - ONLY for failed/error status -->
                                            <button class="action-btn retry-btn" 
                                                    onclick="retryDisbursement({{ $disbursement->id }}, {{ $disbursement->loan_offer_id ?? 'null' }})" 
                                                    title="Retry Disbursement (Status: {{ $actualStatus }})">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- No action buttons for disbursed or rejected status -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                        </div>
                                        <h5 class="text-muted">No Disbursements Found</h5>
                                        <p class="text-muted">No disbursement records match your current filter.</p>
                                        <a href="{{ route('disbursements.index') }}" class="btn btn-sm btn-ura-primary mt-2">
                                            <i class="fas fa-list me-1"></i>View All Disbursements
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($disbursements->hasPages())
        <div class="modern-table-footer">
            <div class="pagination-info">
                Showing {{ $disbursements->firstItem() ?? 0 }} to {{ $disbursements->lastItem() ?? 0 }} of {{ $disbursements->total() }} entries
            </div>
            <div class="pagination-wrapper">
                {{ $disbursements->appends(['status' => $status])->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* URASACCOS Brand Colors - Enhanced */
:root {
    --ura-primary: #003366;
    --ura-secondary: #17479E;
    --ura-tertiary: #2E5090;
    --ura-accent: #4A6FA5;
    --ura-light: #E8F0FE;
    --ura-success: #28a745;
    --ura-warning: #FF8C00;
    --ura-danger: #dc3545;
    --ura-grey: #6c757d;
    --ura-gold: #FFD700;
    --ura-orange: #FF8C00;
    --primary-gradient: linear-gradient(135deg, #003366 0%, #17479E 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --grey-gradient: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    --shadow-sm: 0 2px 8px rgba(0, 51, 102, 0.08);
    --shadow-md: 0 4px 16px rgba(0, 51, 102, 0.12);
    --shadow-lg: 0 8px 32px rgba(0, 51, 102, 0.16);
}

/* Modern Table Wrapper */
.modern-table-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.08);
    margin-bottom: 30px;
}

/* Modern Table Header Section */
.modern-table-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    padding: 20px 25px;
    position: relative;
    overflow: hidden;
}

.modern-table-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #17479E 0%, #2E5090 50%, #17479E 100%);
}

.table-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

/* Table Title Group */
.table-title-group {
    display: flex;
    align-items: center;
    gap: 15px;
}

.table-icon-wrapper {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(23, 71, 158, 0.2);
    position: relative;
    transition: all 0.3s ease;
}

.table-icon-wrapper:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 20px rgba(23, 71, 158, 0.3);
}

@keyframes iconFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}

.table-icon-wrapper i {
    color: white;
    font-size: 20px;
}

.table-title-text {
    flex: 1;
}

.table-main-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #003366;
    letter-spacing: -0.5px;
}

.table-subtitle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
    font-size: 14px;
}

.record-count {
    display: flex;
    align-items: center;
    gap: 5px;
}

.count-number {
    font-weight: 700;
    color: #17479E;
    font-size: 16px;
}

.count-text {
    color: #6c757d;
}

.separator {
    color: #dee2e6;
}

.filter-status {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6c757d;
}

.filter-active {
    color: #FF8C00;
    font-weight: 600;
}

/* Quick Stats */
.table-actions-group {
    display: flex;
    align-items: center;
}

.quick-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
}

.stat-rejected .stat-value { color: #dc3545; }
.stat-rejected {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
    border-color: rgba(220, 53, 69, 0.2);
}

.stat-failed .stat-value { color: #FF8C00; }
.stat-failed {
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
    border-color: rgba(255, 140, 0, 0.2);
}

.stat-disbursed .stat-value { color: #28a745; }
.stat-disbursed {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    border-color: rgba(40, 167, 69, 0.2);
}

/* Modern Table Body Wrapper */
.modern-table-body-wrapper {
    position: relative;
    background: white;
}

/* Modern Table Footer */
.modern-table-footer {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    border-top: 2px solid rgba(255, 255, 255, 0.1);
    padding: 20px 25px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    color: rgba(255, 255, 255, 0.9);
}

.pagination-wrapper .page-link {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    margin: 0 2px;
    border-radius: 8px;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.pagination-wrapper .page-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.pagination-wrapper .page-item.active .page-link {
    background-color: white;
    border-color: white;
    color: #17479E;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

/* Modern Data Table Styles */
.modern-table-container {
    position: relative;
    overflow-x: auto;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 51, 102, 0.08);
}

.modern-data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

/* Modern Table Header */
.modern-table-header {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.15);
}

.modern-table-header tr th {
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border: none;
    position: relative;
}

.modern-table-header tr th:first-child {
    border-top-left-radius: 15px;
}

.modern-table-header tr th:last-child {
    border-top-right-radius: 15px;
}

.checkbox-column {
    width: 50px;
    padding: 12px !important;
}

.action-column {
    width: 180px;
}

/* Modern Checkbox */
.modern-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modern-checkbox input[type="checkbox"] {
    display: none;
}

.modern-checkbox label {
    position: relative;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #17479E;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
}

.modern-checkbox label:hover {
    border-color: #003366;
    box-shadow: 0 0 5px rgba(23, 71, 158, 0.2);
}

.modern-checkbox input[type="checkbox"]:checked + label {
    background: #17479E;
    border-color: #17479E;
}

.modern-checkbox input[type="checkbox"]:checked + label::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 14px;
}

/* Header checkbox style */
.modern-table-header .modern-checkbox label {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid white;
}

.modern-table-header .modern-checkbox label:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label {
    background: white;
    border-color: white;
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label::after {
    color: #17479E;
}

/* Sortable Columns */
.sortable-column {
    cursor: pointer;
    transition: background 0.3s ease;
}

.sortable-column:hover {
    background: rgba(255, 255, 255, 0.1);
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-icon {
    opacity: 0.5;
    font-size: 12px;
    transition: opacity 0.3s ease;
}

.sortable-column:hover .sort-icon {
    opacity: 1;
}

/* Table Body Styles */
.modern-table-body tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f3f4f6;
}

.modern-table-body tr:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(23, 71, 158, 0.01) 100%);
    transform: scale(1.001);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.modern-table-row td {
    padding: 16px 12px;
    vertical-align: middle;
    color: #495057;
}

/* Employee Info Styles */
.employee-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.employee-details {
    flex: 1;
}

.employee-name {
    font-weight: 600;
    color: #003366;
    font-size: 14px;
    margin-bottom: 2px;
}

.employee-id {
    font-size: 12px;
    color: #6c757d;
}

/* Reference Info */
.reference-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.reference-primary {
    font-weight: 600;
    color: #17479E;
    font-size: 13px;
}

.reference-secondary {
    font-size: 11px;
    color: #6c757d;
}

/* Amount Styles */
.amount-cell {
    text-align: right;
}

.amount-primary {
    font-weight: 700;
    color: #003366;
    font-size: 15px;
}

.amount-secondary {
    font-size: 11px;
    color: #6c757d;
    margin-top: 2px;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.date-primary {
    font-weight: 600;
    color: #495057;
    font-size: 13px;
}

.date-secondary {
    font-size: 11px;
    color: #6c757d;
}

/* Account Number */
.account-number {
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #495057;
    letter-spacing: 0.5px;
}

/* Modern Badge Styles */
.modern-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.badge-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.badge-warning {
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
    color: #FF8C00;
    border: 1px solid rgba(255, 140, 0, 0.2);
}

.badge-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.badge-info {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
    border: 1px solid rgba(23, 71, 158, 0.2);
}

.badge-secondary {
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(108, 117, 125, 0.05) 100%);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
    align-items: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.view-btn {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
}

.view-btn:hover {
    background: #17479E;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
}

.edit-btn {
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
    color: #FF8C00;
}

.edit-btn:hover {
    background: #FF8C00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
}

.info-btn {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(23, 162, 184, 0.05) 100%);
    color: #17a2b8;
}

.info-btn:hover {
    background: #17a2b8;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.retry-btn {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    color: #28a745;
}

.retry-btn:hover {
    background: #28a745;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    margin-bottom: 20px;
}

.empty-icon i {
    color: #dee2e6;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 20px;
}

/* Page Header Styles */
.page-header-compact {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(23, 71, 158, 0.08);
    position: relative;
    overflow: hidden;
}

.page-header-compact::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--ura-primary) 0%, var(--ura-secondary) 50%, var(--ura-gold) 100%);
}

.page-title-compact {
    font-size: 24px;
    font-weight: 700;
    color: #003366;
    margin: 0;
}

.icon-pulse {
    position: relative;
}

.icon-box-compact {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(23, 71, 158, 0.2);
    transition: all 0.3s ease;
}

.icon-box-compact:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 71, 158, 0.3);
}

.icon-box-compact i {
    color: white;
    font-size: 24px;
}

/* Button Styles */
.btn-ura-primary-sm {
    background: linear-gradient(135deg, var(--ura-secondary) 0%, var(--ura-tertiary) 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-ura-primary-sm::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.btn-ura-primary-sm:hover::before {
    width: 300px;
    height: 300px;
}

.btn-ura-primary-sm:hover {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-light-sm {
    background: white;
    color: #17479E;
    border: 1px solid #dee2e6;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-light-sm:hover {
    background: #f8f9fa;
    border-color: #17479E;
    color: #17479E;
}

.btn-ura-primary {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    background: linear-gradient(135deg, #003366 0%, #17479E 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
    color: white;
}

/* Filter Section Styles */
.filter-card-ura {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
}

.filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
}

.filter-title {
    font-size: 18px;
    font-weight: 600;
    color: #003366;
    margin: 0;
}

.filter-subtitle {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}

.filter-body {
    padding: 20px;
}

.filter-pills {
    display: flex;
    align-items: center;
    gap: 15px;
}

.filter-pills-label {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
}

.filter-pills-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 10px 18px;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.filter-pill::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    border-radius: 25px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.filter-pill:hover {
    background: #f8f9fa;
    border-color: var(--ura-secondary);
    color: var(--ura-secondary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.15);
}

.filter-pill.active {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-color: #17479E;
    color: white;
}

.filter-pill.active.rejected-pill {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-color: #dc3545;
}

.filter-pill.active.warning-pill {
    background: linear-gradient(135deg, #FF8C00 0%, #FFA500 100%);
    border-color: #FF8C00;
}

.filter-pill.active.success-pill {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-color: #28a745;
}

.filter-pill-count {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.filter-pill.active .filter-pill-count {
    background: rgba(255, 255, 255, 0.2);
}

/* KPI Dashboard Styles */
.kpi-dashboard-modern {
    position: relative;
    padding: 1rem 0;
    margin-bottom: 2rem;
}

.kpi-backdrop {
    position: absolute;
    top: 0;
    left: -20px;
    right: -20px;
    bottom: 0;
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(46, 80, 144, 0.03) 100%);
    border-radius: 20px;
    z-index: -1;
}

.glass-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(23, 71, 158, 0.1);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.glass-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 215, 0, 0.05) 0%, transparent 70%);
    transform: rotate(45deg);
    transition: all 0.5s ease;
    opacity: 0;
}

.glass-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 50px rgba(0, 51, 102, 0.15);
    border-color: rgba(255, 215, 0, 0.3);
}

.glass-card:hover::after {
    opacity: 1;
}

.kpi-card-modern {
    position: relative;
    padding: 1.5rem;
    height: 100%;
    overflow: hidden;
}

.kpi-glow {
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    opacity: 0.1;
    border-radius: 50%;
    filter: blur(60px);
}

.rejected-glow { background: #dc3545; }
.warning-glow { background: #FF8C00; }
.disbursed-glow { background: #28a745; }

.kpi-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.kpi-icon-modern {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    position: relative;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.rejected-icon {
    background: linear-gradient(135deg, var(--ura-danger) 0%, #c82333 100%);
    color: white;
}

.warning-icon {
    background: linear-gradient(135deg, var(--ura-orange) 0%, var(--ura-gold) 100%);
    color: white;
}

.disbursed-icon {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
}

.icon-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 12px;
    border: 2px solid currentColor;
    opacity: 0.2;
    transition: all 0.3s ease;
}

.kpi-icon-modern:hover .icon-pulse-ring {
    transform: translate(-50%, -50%) scale(1.1);
    opacity: 0.4;
}

.kpi-badge-modern {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.danger-badge {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.warning-badge {
    background: rgba(255, 140, 0, 0.1);
    color: #FF8C00;
}

.success-badge {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.kpi-value-modern {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 4px rgba(0, 51, 102, 0.1);
}

.kpi-trend-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.trend-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.kpi-chart-modern {
    margin: 1rem 0;
    height: 30px;
}

.kpi-footer-modern {
    margin-top: 1rem;
}

.progress-bar-modern {
    height: 4px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    transition: width 0.5s ease;
}

.rejected-progress {
    background: linear-gradient(90deg, #dc3545 0%, #ff6b6b 100%);
}

.warning-progress {
    background: linear-gradient(90deg, #FF8C00 0%, #ffd93d 100%);
}

.disbursed-progress {
    background: linear-gradient(90deg, #28a745 0%, #4ade80 100%);
}

.footer-text {
    font-size: 0.75rem;
    color: #6c757d;
}

.card-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
    pointer-events: none;
}

.kpi-card-modern:hover .card-shine {
    left: 100%;
}

/* Animation Classes - Removed infinite animations, only on hover now */
.animate-float-1,
.animate-float-2,
.animate-float-3 {
    transition: transform 0.3s ease;
}

.animate-float-1:hover,
.animate-float-2:hover,
.animate-float-3:hover {
    transform: translateY(-5px);
}

.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in-delayed {
    animation: fadeIn 0.5s ease-out 0.3s both;
}

/* Particle and Blob Backgrounds - Disabled for less distraction */
.particle-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
    overflow: hidden;
    display: none; /* Disabled */
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(23, 71, 158, 0.1);
    border-radius: 50%;
    /* animation: particleFloat 20s infinite linear; */ /* Disabled */
}

.particle-1 { left: 10%; animation-delay: 0s; }
.particle-2 { left: 30%; animation-delay: 2s; }
.particle-3 { left: 50%; animation-delay: 4s; }
.particle-4 { left: 70%; animation-delay: 6s; }
.particle-5 { left: 90%; animation-delay: 8s; }

@keyframes particleFloat {
    from {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
    }
    10% { opacity: 1; }
    90% { opacity: 1; }
    to {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
    }
}

.blob-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -2;
    overflow: hidden;
    display: none; /* Disabled */
}

.blob {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.05;
}

.blob-1 {
    background: #17479E;
    top: -150px;
    left: -150px;
    /* animation: blobMove1 20s infinite; */ /* Disabled */
}

.blob-2 {
    background: #2E5090;
    bottom: -150px;
    right: -150px;
    /* animation: blobMove2 25s infinite; */ /* Disabled */
}

.blob-3 {
    background: #4A6FA5;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* animation: blobMove3 30s infinite; */ /* Disabled */
}

@keyframes blobMove1 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(100px, 100px) scale(1.1); }
    66% { transform: translate(-100px, 100px) scale(0.9); }
}

@keyframes blobMove2 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(-100px, -100px) scale(1.2); }
    66% { transform: translate(100px, -100px) scale(0.8); }
}

@keyframes blobMove3 {
    0%, 100% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.3); }
}

/* Loan Details Modal Styles */
.loan-details-wrapper {
    padding: 20px;
}

.detail-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid rgba(23, 71, 158, 0.1);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.1);
}

.detail-card-title {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.detail-value {
    color: var(--ura-primary);
    font-weight: 500;
    font-size: 14px;
}

.detail-value.fw-bold {
    font-weight: 700 !important;
    color: var(--ura-secondary);
    font-size: 16px;
}

/* Modal Enhancement */
#loanDetailsModal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

#loanDetailsModal .modal-header {
    border-bottom: 3px solid var(--ura-gold);
    padding: 20px 25px;
}

#loanDetailsModal .modal-body {
    padding: 0;
    max-height: 70vh;
    overflow-y: auto;
}

#loanDetailsModal .modal-footer {
    border-top: 2px solid rgba(23, 71, 158, 0.1);
    padding: 15px 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

#loanDetailsModal .btn-warning {
    background: linear-gradient(135deg, var(--ura-orange) 0%, var(--ura-gold) 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

#loanDetailsModal .btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .table-icon-wrapper {
        width: 40px;
        height: 40px;
    }

    .table-icon-wrapper i {
        font-size: 16px;
    }

    .table-main-title {
        font-size: 20px;
    }

    .quick-stats {
        width: 100%;
        justify-content: space-between;
    }

    .stat-item {
        padding: 8px 12px;
    }

    .stat-value {
        font-size: 16px;
    }

    .modern-table-footer {
        flex-direction: column;
        gap: 15px;
    }

    .pagination-info {
        text-align: center;
    }

    .filter-pills {
        flex-direction: column;
        align-items: flex-start;
    }

    .kpi-card-modern {
        padding: 1rem;
    }

    .kpi-value-modern {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- Include SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize animations and interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Animate numbers on load
        animateNumbers();
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Initialize sparkline charts
        initializeSparklines();
    });

    function animateNumbers() {
        document.querySelectorAll('.number-animate').forEach(element => {
            const finalValue = parseInt(element.textContent);
            const duration = 1000;
            const steps = 30;
            const stepValue = finalValue / steps;
            let currentValue = 0;
            let step = 0;
            
            const timer = setInterval(() => {
                currentValue += stepValue;
                step++;
                
                if (step >= steps) {
                    element.textContent = finalValue.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(currentValue).toLocaleString();
                }
            }, duration / steps);
        });
    }

    function initializeSparklines() {
        // Initialize mini charts for KPI cards
        ['rejected', 'failed', 'disbursed'].forEach(status => {
            const canvas = document.getElementById(status + '-sparkline');
            if (canvas && canvas.getContext) {
                // Simple sparkline implementation
                const ctx = canvas.getContext('2d');
                ctx.strokeStyle = status === 'rejected' ? '#dc3545' : 
                                  status === 'failed' ? '#ffc107' : '#28a745';
                ctx.lineWidth = 2;
                ctx.beginPath();
                // Draw a simple trend line
                ctx.moveTo(0, 20);
                ctx.lineTo(20, 15);
                ctx.lineTo(40, 18);
                ctx.lineTo(60, 10);
                ctx.lineTo(80, 12);
                ctx.lineTo(100, 8);
                ctx.stroke();
            }
        });
    }

    function refreshDisbursements() {
        window.location.reload();
    }

    function resetFilters() {
        window.location.href = '{{ route('disbursements.index') }}';
    }

    function viewDisbursementDetails(id) {
        // Implement view details modal or redirect
        console.log('View disbursement:', id);
    }

    // View Loan Details Modal (for disbursed/rejected/failed loans)
    function viewLoanDetails(loanOfferId, status) {
        if (!loanOfferId) {
            alert('Loan details not available');
            return;
        }
        
        // Show loading state
        const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
        const modalContent = document.getElementById('loanDetailsContent');
        modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Hide action buttons for disbursed/rejected loans
        const modalFooter = document.querySelector('#loanDetailsModal .modal-footer');
        if (status === 'disbursed' || status === 'rejected') {
            modalFooter.style.display = 'none';
        } else if (status === 'failed') {
            modalFooter.style.display = 'flex';
            // Show only retry button for failed
            document.getElementById('rejectLoanBtn').style.display = 'none';
            document.getElementById('confirmLoanBtn').style.display = 'none';
            document.getElementById('retryLoanBtn').style.display = 'block';
        }
        
        modal.show();
        
        // Fetch loan details via AJAX
        fetch(`/loan-offers/${loanOfferId}/details`)
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                modalContent.innerHTML = '<div class="alert alert-danger">Failed to load loan details. Please try again.</div>';
                console.error('Error:', error);
            });
    }

    // Retry Disbursement Function
    function retryDisbursement(disbursementId, loanOfferId) {
        Swal.fire({
            title: 'Retry Disbursement?',
            text: 'Are you sure you want to retry this failed disbursement?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17479E',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Retry',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Retrying disbursement...',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Make AJAX request to retry disbursement
                fetch(`/disbursements/${disbursementId}/retry`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        loan_offer_id: loanOfferId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Disbursement retry initiated successfully.',
                            icon: 'success',
                            confirmButtonColor: '#17479E'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to retry disbursement.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while retrying the disbursement.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                });
            }
        });
    }

    function exportReport(format) {
        // Implement export functionality
        console.log('Export as:', format);
    }

    function showBulkActions() {
        // Implement bulk actions
        console.log('Show bulk actions');
    }

    function sortTable(column) {
        // Implement table sorting
        console.log('Sort by:', column);
    }

    // Select all checkboxes
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.disbursement-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Retry from modal
    let currentDisbursementId = null;
    let currentLoanOfferId = null;
    
    function retryFromModal() {
        if (currentDisbursementId && currentLoanOfferId) {
            // Close the modal first
            const modal = bootstrap.Modal.getInstance(document.getElementById('loanDetailsModal'));
            modal.hide();
            
            // Then retry
            retryDisbursement(currentDisbursementId, currentLoanOfferId);
        }
    }
    
    // Store current IDs when opening modal
    window.viewLoanDetails = function(loanOfferId, status, disbursementId = null, isFailedParam = false, isSuccessParam = false, isRejectedParam = false) {
        if (!loanOfferId) {
            Swal.fire({
                title: 'Info',
                text: 'Loan details not available',
                icon: 'info',
                confirmButtonColor: '#17479E'
            });
            return;
        }
        
        currentLoanOfferId = loanOfferId;
        currentDisbursementId = disbursementId;
        
        // Show loading state
        const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
        const modalContent = document.getElementById('loanDetailsContent');
        const modalTitle = document.getElementById('loanDetailsModalLabel');
        
        // Use the boolean parameters passed from PHP
        let isFailedStatus = isFailedParam === true || isFailedParam === 'true';
        let isSuccessStatus = isSuccessParam === true || isSuccessParam === 'true';
        let isRejectedStatus = isRejectedParam === true || isRejectedParam === 'true';
        
        // Update modal title based on status
        const statusLower = status ? status.toLowerCase() : '';
        let statusBadge = '';
        
        if (isSuccessStatus) {
            statusBadge = '<span class="badge bg-success ms-2">DISBURSED</span>';
        } else if (isRejectedStatus) {
            statusBadge = '<span class="badge bg-danger ms-2">REJECTED</span>';
        } else if (isFailedStatus) {
            statusBadge = '<span class="badge bg-warning text-dark ms-2">FAILED</span>';
        } else {
            statusBadge = `<span class="badge bg-secondary ms-2">${status.toUpperCase()}</span>`;
        }
        modalTitle.innerHTML = '<i class="fas fa-file-invoice me-2"></i>Loan Details ' + statusBadge;
        
        modalContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading loan details...</p>
            </div>`;
        
        // Configure footer buttons based on status
        const modalFooter = document.querySelector('#loanDetailsModal .modal-footer');
        const retryBtn = document.getElementById('retryLoanBtn');
        const rejectBtn = document.getElementById('rejectLoanBtn');
        const confirmBtn = document.getElementById('confirmLoanBtn');
        
        // Store disbursement ID if this is a failed status
        if (isFailedStatus && disbursementId) {
            currentDisbursementId = disbursementId;
        }
        
        // Configure buttons based on status
        if (isSuccessStatus || isRejectedStatus) {
            // For disbursed/rejected - no action buttons
            modalFooter.style.display = 'flex';
            retryBtn.style.display = 'none';
            rejectBtn.style.display = 'none';
            confirmBtn.style.display = 'none';
        } else if (isFailedStatus) {
            // For failed - show retry button only
            modalFooter.style.display = 'flex';
            retryBtn.style.display = 'inline-block';
            rejectBtn.style.display = 'none';
            confirmBtn.style.display = 'none';
        } else {
            // For other statuses - show approve/reject buttons
            modalFooter.style.display = 'flex';
            retryBtn.style.display = 'none';
            rejectBtn.style.display = 'inline-block';
            confirmBtn.style.display = 'inline-block';
        }
        
        modal.show();
        
        // Simulate loading loan details (replace with actual AJAX call)
        setTimeout(() => {
            modalContent.innerHTML = `
                <div class="loan-details-wrapper">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-card-title"><i class="fas fa-user me-2"></i>Employee Information</h6>
                                <div class="detail-item">
                                    <span class="detail-label">Name:</span>
                                    <span class="detail-value">John Doe</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check Number:</span>
                                    <span class="detail-value">CHK-001234</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Department:</span>
                                    <span class="detail-value">Finance</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-card-title"><i class="fas fa-money-check me-2"></i>Loan Information</h6>
                                <div class="detail-item">
                                    <span class="detail-label">Amount:</span>
                                    <span class="detail-value fw-bold">TZS 5,000,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Type:</span>
                                    <span class="detail-value">Personal Loan</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration:</span>
                                    <span class="detail-value">12 Months</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-card-title"><i class="fas fa-university me-2"></i>Bank Details</h6>
                                <div class="detail-item">
                                    <span class="detail-label">Bank:</span>
                                    <span class="detail-value">NMB Bank</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account:</span>
                                    <span class="detail-value">20001234567</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Branch:</span>
                                    <span class="detail-value">Dar es Salaam</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="detail-card-title"><i class="fas fa-info-circle me-2"></i>Status Information</h6>
                                <div class="detail-item">
                                    <span class="detail-label">Status:</span>
                                    <span class="detail-value">${statusLower === 'success' || statusLower === 'disbursed' ? 'DISBURSED' : status.toUpperCase()}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date:</span>
                                    <span class="detail-value">${new Date().toLocaleDateString()}</span>
                                </div>
                                ${isFailedStatus ? `
                                <div class="detail-item">
                                    <span class="detail-label">Reason:</span>
                                    <span class="detail-value text-danger">Bank connection timeout</span>
                                </div>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }, 1500);
    };
</script>
@endpush

<!-- Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-labelledby="loanDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #2E5090 100%); color: white;">
                <h5 class="modal-title" id="loanDetailsModalLabel">
                    <i class="fas fa-file-invoice me-2"></i>Loan Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <div id="loanDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa;">
                <!-- Retry button for failed disbursements only -->
                <button type="button" class="btn btn-warning" id="retryLoanBtn" style="display: none;" onclick="retryFromModal()">
                    <i class="fas fa-redo me-2"></i>Retry Disbursement
                </button>
                <!-- Standard buttons hidden for disbursed/rejected -->
                <button type="button" class="btn btn-danger" id="rejectLoanBtn" style="display: none;">
                    <i class="fas fa-times-circle me-2"></i>Reject
                </button>
                <button type="button" class="btn btn-primary" id="confirmLoanBtn" style="display: none;">
                    <i class="fas fa-check-circle me-2"></i>Confirm
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Reusable Modals -->
@include('employee_loan.modals.disbursement-modal')
@include('employee_loan.modals.reject-disbursement-modal')

@endsection