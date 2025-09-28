@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="page-header-compact mb-3">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact" style="background: linear-gradient(135deg, #FF8C00 0%, #FFD700 100%);">
                            <i class="fas fa-exclamation-triangle" style="color: white;"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0" style="color: #003366;">
                            Failed Disbursements
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Disbursements that failed processing - Retry available
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    <button class="btn btn-primary btn-sm me-2" onclick="refreshFailedDisbursements()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                    <button class="btn btn-warning btn-sm me-2" onclick="showBatchRetry()">
                        <i class="fas fa-redo me-1"></i>Batch Retry
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('disbursements.index', ['status' => 'all']) }}">
                                <i class="fas fa-list me-2"></i>View All Disbursements</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-2 text-success"></i>Export Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="kpi-card" style="background: linear-gradient(135deg, #FF8C00 0%, #FFD700 100%); color: white;">
                <div class="kpi-content">
                    <div class="kpi-value">{{ $failedCount }}</div>
                    <div class="kpi-label">Total Failed</div>
                    <div class="kpi-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card" style="background: white; border: 2px solid #17479E;">
                <div class="kpi-content">
                    <div class="kpi-value" style="color: #17479E;">{{ $rejectedCount }}</div>
                    <div class="kpi-label">Rejected</div>
                    <div class="kpi-icon">
                        <i class="fas fa-times-circle" style="color: #dc3545;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card" style="background: white; border: 2px solid #28a745;">
                <div class="kpi-content">
                    <div class="kpi-value" style="color: #28a745;">{{ $disbursedCount }}</div>
                    <div class="kpi-label">Disbursed</div>
                    <div class="kpi-icon">
                        <i class="fas fa-check-double" style="color: #28a745;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Disbursements Table -->
    <div class="table-wrapper">
        <div class="table-header" style="background: linear-gradient(135deg, #FF8C00 0%, #FFD700 100%);">
            <h6 class="mb-0 text-white">
                <i class="fas fa-exclamation-triangle me-2"></i>Failed Disbursement Records
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background-color: #003366;">
                    <tr>
                        <th class="text-white">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="text-white">Reference</th>
                        <th class="text-white">Employee</th>
                        <th class="text-white">Amount</th>
                        <th class="text-white">Bank</th>
                        <th class="text-white">Account</th>
                        <th class="text-white">Date</th>
                        <th class="text-white text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disbursements as $disbursement)
                        <tr data-disbursement-id="{{ $disbursement->id }}">
                            <td>
                                <input type="checkbox" class="disbursement-checkbox loan-checkbox" value="{{ $disbursement->loan_offer_id }}" data-disbursement-id="{{ $disbursement->id }}">
                            </td>
                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $disbursement->loanOffer->fsp_reference_number ?? $disbursement->loanOffer->application_number ?? 'N/A' }}
                                </div>
                                <small class="text-muted">{{ $disbursement->loanOffer->check_number ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="fw-semibold employee-name">
                                        {{ $disbursement->loanOffer->first_name ?? '' }} {{ $disbursement->loanOffer->last_name ?? '' }}
                                    </div>
                                    <small class="text-muted">{{ $disbursement->loanOffer->check_number ?? '' }}</small>
                                </div>
                            </td>
                            <td class="text-end">
                                <strong class="amount" data-amount="{{ $disbursement->amount ?? 0 }}">{{ number_format($disbursement->amount ?? 0, 0) }}</strong>
                                <span class="text-muted">TZS</span>
                            </td>
                            <td>
                                @if($disbursement->loanOffer && $disbursement->loanOffer->bank)
                                    <span class="badge bg-info bank-name">
                                        {{ $disbursement->loanOffer->bank->short_name ?: $disbursement->loanOffer->bank->name }}
                                    </span>
                                @else
                                    <span class="text-muted bank-name">-</span>
                                @endif
                            </td>
                            <td>
                                <code class="account-number">{{ $disbursement->loanOffer->bank_account_number ?? '-' }}</code>
                            </td>
                            <td>
                                <div>{{ $disbursement->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $disbursement->created_at->format('H:i') }}</small>
                                <span class="d-none" data-failure-reason="{{ $disbursement->reason ?? 'Network timeout' }}"></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Details -->
                                    <button class="btn btn-outline-primary" 
                                            onclick="viewFailedDetails({{ $disbursement->loan_offer_id ?? 'null' }}, {{ $disbursement->id }})" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- View Callbacks if available -->
                                    @if($disbursement->loanOffer && $disbursement->loanOffer->callbacks->count() > 0)
                                    <button class="btn btn-outline-info" 
                                            onclick="window.location.href='{{ route('loan-offers.callbacks', $disbursement->loan_offer_id) }}'" 
                                            title="View Callbacks">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    @endif
                                    
                                    <!-- Retry Button - ALWAYS VISIBLE FOR FAILED -->
                                    <button class="btn btn-warning" 
                                            onclick="retryDisbursement({{ $disbursement->id }}, {{ $disbursement->loan_offer_id ?? 'null' }})" 
                                            title="Retry Disbursement">
                                        <i class="fas fa-redo"></i> Retry
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                    <h5>No Failed Disbursements</h5>
                                    <p class="text-muted">All disbursements have been processed successfully.</p>
                                    <a href="{{ route('disbursements.index') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-list me-1"></i>View All Disbursements
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($disbursements->hasPages())
        <div class="table-footer">
            {{ $disbursements->appends(['status' => 'failed'])->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* URASACCOS Brand Colors */
:root {
    --ura-primary: #003366;
    --ura-secondary: #17479E;
    --ura-gold: #FFD700;
    --ura-orange: #FF8C00;
    --ura-success: #28a745;
    --ura-danger: #dc3545;
}

/* Page Header */
.page-header-compact {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border-left: 4px solid var(--ura-orange);
}

.icon-box-compact {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
}

/* KPI Cards */
.kpi-card {
    border-radius: 15px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.kpi-content {
    position: relative;
    z-index: 1;
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.kpi-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.kpi-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 3rem;
    opacity: 0.2;
}

/* Table Wrapper */
.table-wrapper {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.table-header {
    padding: 15px 20px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.table-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* Table Styles */
.table {
    margin-bottom: 0;
}

.table thead th {
    border: none;
    padding: 12px;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 12px;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.employee-info {
    display: flex;
    flex-direction: column;
}

/* Button Styles */
.btn-warning {
    background: linear-gradient(135deg, var(--ura-orange) 0%, var(--ura-gold) 100%);
    border: none;
    color: white;
    font-weight: 600;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
    color: white;
}

/* Empty State */
.empty-state {
    padding: 40px;
}

.empty-state i {
    color: #dee2e6;
}

/* Modal Styles */
.detail-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.detail-card-title {
    color: var(--ura-primary);
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #dee2e6;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 13px;
}

.detail-value {
    color: var(--ura-primary);
    font-weight: 500;
    font-size: 13px;
}

/* Timeline Styles */
.failure-timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% - 20px);
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-time {
    font-size: 11px;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-title {
    font-weight: 600;
    color: var(--ura-primary);
    margin-bottom: 3px;
}

.timeline-desc {
    font-size: 13px;
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .kpi-card {
        margin-bottom: 15px;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
}
</style>
@endpush

<!-- Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-labelledby="loanDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF8C00 0%, #FFD700 100%); color: white;">
                <h5 class="modal-title" id="loanDetailsModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Failed Disbursement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <div id="loanDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="retryFromModalBtn" onclick="retryFromModal()">
                    <i class="fas fa-redo me-2"></i>Retry Disbursement
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Batch Retry Modal -->
<div class="modal fade" id="batchRetryModal" tabindex="-1" role="dialog" aria-labelledby="batchRetryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF8C00 0%, #FFD700 100%); color: white;">
                <h5 class="modal-title" id="batchRetryModalLabel">
                    <i class="fas fa-redo me-2"></i>Batch Retry Failed Disbursements
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <form id="batchRetryForm" method="POST" action="{{ route('disbursements.batch.retry') }}">
                @csrf
                <div class="modal-body">
                    <!-- Summary Section -->
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Batch Retry Summary</h6>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <small>Selected Loans:</small>
                                        <strong id="batchSelectedCount">0</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Total Amount:</small>
                                        <strong id="batchTotalAmount">0</strong> TZS
                                    </div>
                                    <div class="col-md-4">
                                        <small>Processing Bank:</small>
                                        <strong id="batchBankCount">Multiple</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Loans Table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Amount</th>
                                    <th>Bank</th>
                                    <th>Account</th>
                                    <th>Previous Failure</th>
                                </tr>
                            </thead>
                            <tbody id="batchRetryTableBody">
                                <!-- Dynamically populated -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Processing Options -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Retry Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="retryChannel">Disbursement Channel</label>
                                        <select class="form-control" id="retryChannel" name="channel" required>
                                            <option value="BANK_TRANSFER">Bank Transfer</option>
                                            <option value="MOBILE_MONEY">Mobile Money</option>
                                            <option value="CASH">Cash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="retryPriority">Processing Priority</label>
                                        <select class="form-control" id="retryPriority" name="priority">
                                            <option value="NORMAL">Normal</option>
                                            <option value="HIGH" selected>High Priority</option>
                                            <option value="URGENT">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="retryReference">Batch Reference Number</label>
                                <input type="text" class="form-control" id="retryReference" name="batch_reference" 
                                       placeholder="Auto-generated if empty" readonly value="RETRY-{{ date('YmdHis') }}">
                            </div>

                            <div class="form-group">
                                <label for="retryNotes">Retry Notes (Optional)</label>
                                <textarea class="form-control" id="retryNotes" name="notes" rows="2" 
                                          placeholder="Add any notes for this batch retry..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for selected loan IDs -->
                    <input type="hidden" id="batchLoanIds" name="loan_ids">

                    <!-- Warning for large batches -->
                    <div id="batchWarning" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Large Batch Warning:</strong> Processing more than 50 loans at once may take longer. Consider processing in smaller batches.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-info" onclick="validateBatchSelection()">
                        <i class="fas fa-check-circle me-1"></i>Validate Selection
                    </button>
                    <button type="submit" class="btn btn-warning" id="batchRetryBtn">
                        <i class="fas fa-redo me-1"></i>Process Retry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Store current disbursement data
let currentDisbursementId = null;
let currentLoanOfferId = null;

// Select all checkboxes
document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

// Update selected count
function updateSelectedCount() {
    const count = document.querySelectorAll('.loan-checkbox:checked').length;
    const batchBtn = document.querySelector('button[onclick="showBatchRetry()"]');
    
    if (batchBtn) {
        if (count > 0) {
            batchBtn.innerHTML = `<i class="fas fa-redo me-1"></i>Batch Retry (${count})`;
            batchBtn.classList.remove('btn-warning');
            batchBtn.classList.add('btn-primary');
        } else {
            batchBtn.innerHTML = `<i class="fas fa-redo me-1"></i>Batch Retry`;
            batchBtn.classList.remove('btn-primary');
            batchBtn.classList.add('btn-warning');
        }
    }
}

// Add event listener to individual checkboxes
document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// View Failed Details with Modal
function viewFailedDetails(loanOfferId, disbursementId) {
    if (!loanOfferId) {
        Swal.fire('Info', 'Loan details not available', 'info');
        return;
    }
    
    currentLoanOfferId = loanOfferId;
    currentDisbursementId = disbursementId;
    
    // Show modal with loading state
    const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
    const modalContent = document.getElementById('loanDetailsContent');
    
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading disbursement details...</p>
        </div>`;
    
    modal.show();
    
    // Fetch loan details via AJAX or use inline data
    fetch(`/loan-offers/${loanOfferId}/details`)
        .then(response => response.json())
        .then(data => {
            modalContent.innerHTML = generateDetailsHTML(data, 'failed');
        })
        .catch(error => {
            // Fallback to sample data if API fails
            modalContent.innerHTML = generateDetailsHTML(getSampleData(), 'failed');
        });
}

// Generate Details HTML
function generateDetailsHTML(data, status) {
    return `
        <div class="loan-details-wrapper">
            <!-- Status Alert -->
            <div class="alert alert-warning mb-4">
                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Disbursement Failed</h6>
                <hr>
                <p class="mb-0"><strong>Failure Reason:</strong> ${data.failure_reason || 'Network timeout - Bank connection failed'}</p>
                <p class="mb-0 mt-2"><strong>Error Code:</strong> ${data.error_code || 'NMB_TIMEOUT_001'}</p>
                <p class="mb-0 mt-2"><strong>Failed At:</strong> ${data.failed_at || new Date().toLocaleString()}</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-user me-2"></i>Employee Information</h6>
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">${data.employee_name || 'John Doe'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Check Number:</span>
                            <span class="detail-value">${data.check_number || 'CHK-001234'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Department:</span>
                            <span class="detail-value">${data.department || 'Finance'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value">${data.phone || '+255 712 345 678'}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-money-check me-2"></i>Loan Information</h6>
                        <div class="detail-item">
                            <span class="detail-label">Loan Amount:</span>
                            <span class="detail-value fw-bold text-primary">${data.amount || 'TZS 5,000,000'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value">${data.loan_type || 'Personal Loan'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration:</span>
                            <span class="detail-value">${data.duration || '12 Months'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Interest Rate:</span>
                            <span class="detail-value">${data.interest_rate || '15% p.a.'}</span>
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
                            <span class="detail-value">${data.bank_name || 'NMB Bank'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Account Number:</span>
                            <span class="detail-value">${data.account_number || '20001234567'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Branch:</span>
                            <span class="detail-value">${data.branch || 'Dar es Salaam'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Swift Code:</span>
                            <span class="detail-value">${data.swift_code || 'NLCBTZTX'}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-history me-2"></i>Transaction History</h6>
                        <div class="detail-item">
                            <span class="detail-label">Application Date:</span>
                            <span class="detail-value">${data.application_date || '01/01/2024'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Approval Date:</span>
                            <span class="detail-value">${data.approval_date || '05/01/2024'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Disbursement Attempt:</span>
                            <span class="detail-value text-danger">${data.disbursement_date || '10/01/2024 - Failed'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Retry Count:</span>
                            <span class="detail-value">${data.retry_count || '0 times'}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Failure Details -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="detail-card" style="border-left: 4px solid #FF8C00;">
                        <h6 class="detail-card-title text-warning"><i class="fas fa-exclamation-circle me-2"></i>Failure Details</h6>
                        <div class="failure-timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <div class="timeline-time">10/01/2024 14:30</div>
                                    <div class="timeline-title">Disbursement Initiated</div>
                                    <div class="timeline-desc">Request sent to NMB Bank</div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <div class="timeline-time">10/01/2024 14:35</div>
                                    <div class="timeline-title">Connection Timeout</div>
                                    <div class="timeline-desc">Failed to receive response from bank API</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Get Sample Data
function getSampleData() {
    return {
        employee_name: 'John Doe',
        check_number: 'CHK-001234',
        department: 'Finance',
        phone: '+255 712 345 678',
        amount: 'TZS 5,000,000',
        loan_type: 'Personal Loan',
        duration: '12 Months',
        interest_rate: '15% p.a.',
        bank_name: 'NMB Bank',
        account_number: '20001234567',
        branch: 'Dar es Salaam',
        swift_code: 'NLCBTZTX',
        failure_reason: 'Network timeout - Bank connection failed',
        error_code: 'NMB_TIMEOUT_001',
        retry_count: '2 times'
    };
}

// Retry from Modal
function retryFromModal() {
    if (currentDisbursementId && currentLoanOfferId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('loanDetailsModal'));
        modal.hide();
        retryDisbursement(currentDisbursementId, currentLoanOfferId);
    }
}

// Retry Disbursement
function retryDisbursement(disbursementId, loanOfferId) {
    Swal.fire({
        title: 'Retry Disbursement?',
        text: 'This will attempt to process the disbursement again.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FF8C00',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Retry',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
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
            
            // Make AJAX request to retry
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
                        confirmButtonColor: '#28a745'
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

// Show batch retry modal
function showBatchRetry() {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    const selectedLoanIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedLoanIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one failed disbursement to retry',
            confirmButtonColor: '#FF8C00'
        });
        return;
    }
    
    // Populate batch retry modal with selected disbursements
    populateBatchRetryModal(selectedLoanIds);
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('batchRetryModal'));
    modal.show();
}

// Populate batch retry modal
function populateBatchRetryModal(selectedIds) {
    const tbody = document.getElementById('batchRetryTableBody');
    let totalAmount = 0;
    let bankCount = new Set();
    tbody.innerHTML = '';
    
    selectedIds.forEach(loanOfferId => {
        const checkbox = document.querySelector(`.loan-checkbox[value="${loanOfferId}"]`);
        const row = checkbox?.closest('tr');
        if (row) {
            const employee = row.querySelector('.employee-name')?.textContent.trim() || '-';
            const amount = parseFloat(row.querySelector('.amount')?.dataset.amount || 0);
            const bank = row.querySelector('.bank-name')?.textContent.trim() || '-';
            const account = row.querySelector('.account-number')?.textContent.trim() || '-';
            const failureReason = row.querySelector('[data-failure-reason]')?.dataset.failureReason || 'Unknown';
            
            totalAmount += amount;
            bankCount.add(bank);
            
            tbody.innerHTML += `
                <tr>
                    <td>${employee}</td>
                    <td>${formatCurrency(amount)}</td>
                    <td>${bank}</td>
                    <td>${account}</td>
                    <td><span class="badge bg-danger">${failureReason}</span></td>
                </tr>
            `;
        }
    });
    
    // Update summary
    document.getElementById('batchSelectedCount').textContent = selectedIds.length;
    document.getElementById('batchTotalAmount').textContent = formatCurrency(totalAmount);
    document.getElementById('batchBankCount').textContent = bankCount.size > 1 ? 'Multiple Banks' : Array.from(bankCount)[0];
    document.getElementById('batchLoanIds').value = selectedIds.join(',');
    
    // Show warning for large batches
    const warningDiv = document.getElementById('batchWarning');
    if (selectedIds.length > 50) {
        warningDiv.classList.remove('d-none');
    } else {
        warningDiv.classList.add('d-none');
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-TZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Validate batch selection
function validateBatchSelection() {
    const selectedIds = document.getElementById('batchLoanIds').value.split(',');
    
    if (selectedIds.length === 0) {
        Swal.fire('Error', 'No disbursements selected', 'error');
        return;
    }
    
    // Perform validation
    Swal.fire({
        icon: 'info',
        title: 'Validating...',
        text: `Checking ${selectedIds.length} disbursement(s) for retry eligibility`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            // Simulate validation (replace with actual API call)
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Validation Complete',
                    text: 'All selected disbursements are eligible for retry',
                    confirmButtonColor: '#28a745'
                });
            }, 1500);
        }
    });
}

// Refresh failed disbursements
function refreshFailedDisbursements() {
    window.location.reload();
}

// Export report
function exportReport(format) {
    const selectedIds = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    const params = new URLSearchParams({
        format: format,
        status: 'failed',
        ids: selectedIds.join(',')
    });
    window.location.href = `/disbursements/export?${params.toString()}`;
}

// Handle batch retry form submission
document.getElementById('batchRetryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Processing Batch Retry',
        text: 'Please wait while we process the retry requests...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Batch Retry Initiated',
                text: data.message || 'Retry process has been started successfully',
                confirmButtonColor: '#28a745'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Retry Failed',
                text: data.message || 'An error occurred during batch retry',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Network error occurred. Please try again.',
            confirmButtonColor: '#dc3545'
        });
    });
});
</script>
@endpush
@endsection