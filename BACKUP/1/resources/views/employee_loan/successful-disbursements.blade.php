@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="page-header-compact mb-3">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="fas fa-check-double" style="color: white;"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0" style="color: #003366;">
                            Successful Disbursements
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Successfully processed and disbursed loans
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    <a href="{{ route('disbursements.index', ['status' => 'all']) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to All
                    </a>
                    <button class="btn btn-success btn-sm" onclick="exportSuccess()">
                        <i class="fas fa-file-excel me-1"></i>Export Report
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="printReceipts()">
                        <i class="fas fa-print me-1"></i>Print Receipts
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="kpi-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <div class="kpi-content">
                    <div class="kpi-value">{{ $disbursedCount }}</div>
                    <div class="kpi-label">Total Disbursed</div>
                    <div class="kpi-icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card" style="background: white; border: 2px solid #003366;">
                <div class="kpi-content">
                    <div class="kpi-value" style="color: #003366; font-size: 1.8rem;">
                        {{ number_format($disbursements->sum('amount'), 0) }}
                    </div>
                    <div class="kpi-label">Total Amount (TZS)</div>
                    <div class="kpi-icon">
                        <i class="fas fa-money-bill-wave" style="color: #17479E;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card" style="background: white; border: 2px solid #FF8C00;">
                <div class="kpi-content">
                    <div class="kpi-value" style="color: #FF8C00;">{{ $failedCount }}</div>
                    <div class="kpi-label">Failed</div>
                    <div class="kpi-icon">
                        <i class="fas fa-exclamation-triangle" style="color: #FF8C00;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card" style="background: white; border: 2px solid #dc3545;">
                <div class="kpi-content">
                    <div class="kpi-value" style="color: #dc3545;">{{ $rejectedCount }}</div>
                    <div class="kpi-label">Rejected</div>
                    <div class="kpi-icon">
                        <i class="fas fa-times-circle" style="color: #dc3545;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if($disbursedCount > 0)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ $disbursedCount }} disbursements have been successfully processed.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Successful Disbursements Table -->
    <div class="table-wrapper">
        <div class="table-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <h6 class="mb-0 text-white">
                <i class="fas fa-check-double me-2"></i>Successfully Disbursed Loans
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background-color: #003366;">
                    <tr>
                        <th class="text-white">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="text-white">Transaction ID</th>
                        <th class="text-white">Employee</th>
                        <th class="text-white">Amount</th>
                        <th class="text-white">Bank</th>
                        <th class="text-white">Account</th>
                        <th class="text-white">Reference</th>
                        <th class="text-white">Disbursed Date</th>
                        <th class="text-white text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disbursements as $disbursement)
                        <tr>
                            <td>
                                <input type="checkbox" class="disbursement-checkbox" value="{{ $disbursement->id }}">
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $disbursement->transaction_id ?? $disbursement->id }}
                                </div>
                                <small class="text-muted">Batch: {{ $disbursement->batch_id ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="fw-semibold">
                                        {{ $disbursement->loanOffer->first_name ?? '' }} {{ $disbursement->loanOffer->last_name ?? '' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $disbursement->loanOffer->check_number ?? '' }}
                                        @if($disbursement->loanOffer && ($disbursement->loanOffer->loan_type === 'topup' || $disbursement->loanOffer->offer_type === 'TOP_UP'))
                                            <span class="badge bg-warning text-dark ms-1">TOPUP</span>
                                        @else
                                            <span class="badge bg-info ms-1">NEW</span>
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">{{ number_format($disbursement->amount ?? 0, 0) }}</strong>
                                <span class="text-muted">TZS</span>
                            </td>
                            <td>
                                @if($disbursement->loanOffer && $disbursement->loanOffer->bank)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-university me-1"></i>
                                        {{ $disbursement->loanOffer->bank->short_name ?: $disbursement->loanOffer->bank->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-success">{{ $disbursement->loanOffer->bank_account_number ?? '-' }}</code>
                            </td>
                            <td>
                                <div class="reference-info">
                                    <div class="fw-bold">{{ $disbursement->loanOffer->fsp_reference_number ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $disbursement->reference_number ?? '-' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-calendar-check text-success me-1"></i>
                                    {{ $disbursement->disbursed_at ? $disbursement->disbursed_at->format('d/m/Y') : $disbursement->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $disbursement->disbursed_at ? $disbursement->disbursed_at->format('H:i') : $disbursement->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Details -->
                                    <button class="btn btn-outline-success" 
                                            onclick="viewSuccessDetails({{ $disbursement->loan_offer_id ?? 'null' }})" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Print Receipt -->
                                    <button class="btn btn-outline-secondary" 
                                            onclick="printReceipt({{ $disbursement->id }})" 
                                            title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    
                                    <!-- Download Receipt -->
                                    <button class="btn btn-outline-info" 
                                            onclick="downloadReceipt({{ $disbursement->id }})" 
                                            title="Download Receipt">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    
                                    <!-- NO RETRY BUTTON FOR SUCCESSFUL DISBURSEMENTS -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h5>No Successful Disbursements Yet</h5>
                                    <p class="text-muted">No disbursements have been successfully processed.</p>
                                    <a href="{{ route('disbursements.index', ['status' => 'failed']) }}" class="btn btn-warning btn-sm mt-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>View Failed Disbursements
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
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $disbursements->firstItem() }} to {{ $disbursements->lastItem() }} of {{ $disbursements->total() }} entries
                </div>
                {{ $disbursements->appends(['status' => 'disbursed'])->links('pagination::bootstrap-5') }}
            </div>
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
    border-left: 4px solid var(--ura-success);
}

.icon-box-compact {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    50% {
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.5);
    }
    100% {
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
}

/* KPI Cards */
.kpi-card {
    border-radius: 15px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.kpi-card:hover {
    transform: translateY(-5px);
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

/* Alert Styles */
.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #155724;
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

.table tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.employee-info {
    display: flex;
    flex-direction: column;
}

.reference-info {
    display: flex;
    flex-direction: column;
}

/* Button Styles */
.btn-success {
    background: linear-gradient(135deg, var(--ura-success) 0%, #20c997 100%);
    border: none;
    color: white;
    font-weight: 600;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    color: white;
}

.btn-outline-success {
    border-color: var(--ura-success);
    color: var(--ura-success);
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, var(--ura-success) 0%, #20c997 100%);
    border-color: var(--ura-success);
    color: white;
}

/* Empty State */
.empty-state {
    padding: 40px;
}

.empty-state i {
    color: #dee2e6;
}

/* Success Badge Animation */
.badge.bg-info, .badge.bg-warning {
    animation: badgePulse 2s infinite;
}

@keyframes badgePulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .kpi-card {
        margin-bottom: 15px;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .btn-group-sm {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Select all checkboxes
document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.disbursement-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// View Success Details
function viewSuccessDetails(loanOfferId) {
    if (!loanOfferId) {
        Swal.fire('Info', 'Loan details not available', 'info');
        return;
    }
    
    Swal.fire({
        title: 'Disbursement Successful',
        html: `
            <div class="text-start">
                <p><i class="fas fa-check-circle text-success me-2"></i>This loan has been successfully disbursed.</p>
                <p><strong>What would you like to do?</strong></p>
            </div>
        `,
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'View Full Details',
        cancelButtonText: 'Close'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/loan-offers/${loanOfferId}`;
        }
    });
}

// Print Receipt
function printReceipt(disbursementId) {
    Swal.fire({
        title: 'Generating Receipt...',
        text: 'Please wait while we prepare the receipt for printing.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate receipt generation
    setTimeout(() => {
        Swal.close();
        window.print();
    }, 1500);
}

// Download Receipt
function downloadReceipt(disbursementId) {
    Swal.fire({
        title: 'Download Receipt',
        text: 'Select download format',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17479E',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'PDF',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Download as PDF
            window.location.href = `/disbursements/${disbursementId}/receipt`;
        }
    });
}

// Print Multiple Receipts
function printReceipts() {
    const checkedBoxes = document.querySelectorAll('.disbursement-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        Swal.fire('Info', 'Please select disbursements to print receipts', 'info');
        return;
    }
    
    Swal.fire({
        title: `Print ${checkedBoxes.length} Receipts?`,
        text: 'This will generate receipts for all selected disbursements.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17479E',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Print All',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implement batch print logic
            console.log('Printing receipts for selected disbursements...');
        }
    });
}

// Export Success Report
function exportSuccess() {
    Swal.fire({
        title: 'Export Successful Disbursements',
        text: 'Select export format',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonColor: '#28a745',
        denyButtonColor: '#17479E',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Excel',
        denyButtonText: 'PDF',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Export as Excel
            window.location.href = '{{ route("disbursements.export", ["status" => "disbursed", "format" => "excel"]) }}';
        } else if (result.isDenied) {
            // Export as PDF
            window.location.href = '{{ route("disbursements.export", ["status" => "disbursed", "format" => "pdf"]) }}';
        }
    });
}
</script>
@endpush
@endsection