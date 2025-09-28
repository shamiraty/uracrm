@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="page-header-compact mb-3">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                            <i class="fas fa-times-circle" style="color: white;"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0" style="color: #003366;">
                            Rejected Disbursements
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Disbursements that were rejected - No further action available
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    <a href="{{ route('disbursements.index', ['status' => 'all']) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to All
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="exportRejected()">
                        <i class="fas fa-file-export me-1"></i>Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="kpi-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <div class="kpi-content">
                    <div class="kpi-value">{{ $rejectedCount }}</div>
                    <div class="kpi-label">Total Rejected</div>
                    <div class="kpi-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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

    <!-- Rejected Disbursements Table -->
    <div class="table-wrapper">
        <div class="table-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <h6 class="mb-0 text-white">
                <i class="fas fa-times-circle me-2"></i>Rejected Disbursement Records
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background-color: #003366;">
                    <tr>
                        <th class="text-white">Reference</th>
                        <th class="text-white">Employee</th>
                        <th class="text-white">Amount</th>
                        <th class="text-white">Bank</th>
                        <th class="text-white">Account</th>
                        <th class="text-white">Rejected By</th>
                        <th class="text-white">Date</th>
                        <th class="text-white text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disbursements as $disbursement)
                        <tr>
                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $disbursement->loanOffer->fsp_reference_number ?? $disbursement->loanOffer->application_number ?? 'N/A' }}
                                </div>
                                <small class="text-muted">{{ $disbursement->loanOffer->check_number ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="fw-semibold">
                                        {{ $disbursement->loanOffer->first_name ?? '' }} {{ $disbursement->loanOffer->last_name ?? '' }}
                                    </div>
                                    <small class="text-muted">{{ $disbursement->loanOffer->check_number ?? '' }}</small>
                                </div>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($disbursement->amount ?? 0, 0) }}</strong>
                                <span class="text-muted">TZS</span>
                            </td>
                            <td>
                                @if($disbursement->loanOffer && $disbursement->loanOffer->bank)
                                    <span class="badge bg-info">
                                        {{ $disbursement->loanOffer->bank->short_name ?: $disbursement->loanOffer->bank->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <code>{{ $disbursement->loanOffer->bank_account_number ?? '-' }}</code>
                            </td>
                            <td>
                                @if($disbursement->loanOffer && $disbursement->loanOffer->approvals && $disbursement->loanOffer->approvals->first())
                                    <div>
                                        {{ $disbursement->loanOffer->approvals->first()->rejectedBy->name ?? 'System' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $disbursement->loanOffer->approvals->first()->rejected_at ? $disbursement->loanOffer->approvals->first()->rejected_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $disbursement->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $disbursement->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Details Only -->
                                    <button class="btn btn-outline-primary" 
                                            onclick="viewRejectedDetails({{ $disbursement->loan_offer_id ?? 'null' }}, {{ $disbursement->id }}, '{{ $disbursement->loanOffer->reason ?? 'Administrative rejection' }}')" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                    
                                    <!-- NO RETRY BUTTON FOR REJECTED -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-smile fa-4x text-success mb-3"></i>
                                    <h5>No Rejected Disbursements</h5>
                                    <p class="text-muted">No disbursements have been rejected.</p>
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
            {{ $disbursements->appends(['status' => 'rejected'])->links('pagination::bootstrap-5') }}
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
    border-left: 4px solid var(--ura-danger);
}

.icon-box-compact {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
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
.btn-danger {
    background: linear-gradient(135deg, var(--ura-danger) 0%, #c82333 100%);
    border: none;
    color: white;
    font-weight: 600;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    color: white;
}

/* Empty State */
.empty-state {
    padding: 40px;
}

.empty-state i {
    color: #dee2e6;
}

/* Modal Detail Styles */
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

.rejection-details {
    padding: 10px;
}

.rejection-list {
    margin-top: 10px;
    padding-left: 20px;
}

.rejection-list li {
    margin-bottom: 8px;
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
    
    .modal-dialog {
        max-width: 95%;
    }
}
</style>
@endpush

<!-- Loan Details Modal -->
<div class="modal fade" id="rejectedDetailsModal" tabindex="-1" aria-labelledby="rejectedDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <h5 class="modal-title" id="rejectedDetailsModalLabel">
                    <i class="fas fa-times-circle me-2"></i>Rejected Disbursement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <div id="rejectedDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- NO RETRY BUTTON FOR REJECTED -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// View Rejected Details with Detailed Modal
function viewRejectedDetails(loanOfferId, disbursementId, rejectionReason) {
    if (!loanOfferId) {
        Swal.fire('Info', 'Loan details not available', 'info');
        return;
    }
    
    // Show modal with loading state
    const modal = new bootstrap.Modal(document.getElementById('rejectedDetailsModal'));
    const modalContent = document.getElementById('rejectedDetailsContent');
    
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading disbursement details...</p>
        </div>`;
    
    modal.show();
    
    // Generate content after a short delay (or fetch via AJAX)
    setTimeout(() => {
        modalContent.innerHTML = generateRejectedDetailsHTML({
            loan_offer_id: loanOfferId,
            disbursement_id: disbursementId,
            rejection_reason: rejectionReason
        });
    }, 1000);
}

// Generate Rejected Details HTML
function generateRejectedDetailsHTML(data) {
    return `
        <div class="loan-details-wrapper">
            <!-- Rejection Alert -->
            <div class="alert alert-danger mb-4">
                <h6 class="alert-heading"><i class="fas fa-ban me-2"></i>Disbursement Rejected</h6>
                <hr>
                <p class="mb-0"><strong>Rejection Reason:</strong> ${data.rejection_reason || 'Administrative decision'}</p>
                <p class="mb-0 mt-2"><strong>Status:</strong> Final - Cannot be retried</p>
                <p class="mb-0 mt-2"><strong>Rejected Date:</strong> ${new Date().toLocaleString()}</p>
            </div>
            
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
                        <div class="detail-item">
                            <span class="detail-label">Employment Status:</span>
                            <span class="detail-value">Active</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-money-check me-2"></i>Loan Information</h6>
                        <div class="detail-item">
                            <span class="detail-label">Requested Amount:</span>
                            <span class="detail-value fw-bold text-danger">TZS 5,000,000</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value">Personal Loan</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration:</span>
                            <span class="detail-value">12 Months</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Interest Rate:</span>
                            <span class="detail-value">15% p.a.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-gavel me-2"></i>Rejection Details</h6>
                        <div class="detail-item">
                            <span class="detail-label">Rejected By:</span>
                            <span class="detail-value">Admin User</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Rejection Date:</span>
                            <span class="detail-value text-danger">${new Date().toLocaleDateString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Rejection Type:</span>
                            <span class="detail-value">Administrative</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Can Reapply:</span>
                            <span class="detail-value">After 3 months</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-card">
                        <h6 class="detail-card-title"><i class="fas fa-clipboard-check me-2"></i>Verification Status</h6>
                        <div class="detail-item">
                            <span class="detail-label">Credit Score:</span>
                            <span class="detail-value text-danger">Below Threshold</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Existing Loans:</span>
                            <span class="detail-value">2 Active</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Debt Ratio:</span>
                            <span class="detail-value text-danger">85%</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Previous Defaults:</span>
                            <span class="detail-value">1</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rejection Reason Details -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="detail-card" style="border-left: 4px solid #dc3545;">
                        <h6 class="detail-card-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Detailed Rejection Reason</h6>
                        <div class="rejection-details">
                            <p class="mb-2">${data.rejection_reason || 'The loan application has been rejected due to the following reasons:'}</p>
                            <ul class="rejection-list">
                                <li>High debt-to-income ratio exceeding acceptable limits</li>
                                <li>Previous loan default history</li>
                                <li>Insufficient collateral or guarantors</li>
                            </ul>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Next Steps:</strong> The applicant may reapply after 3 months with improved financial standing or additional guarantors.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Export Rejected Report
function exportRejected() {
    Swal.fire({
        title: 'Export Report',
        text: 'Select export format',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#003366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF'
    }).then((result) => {
        if (result.isConfirmed) {
            // Export as Excel
            window.location.href = '{{ route("disbursements.export", ["status" => "rejected", "format" => "excel"]) }}';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Export as PDF
            window.location.href = '{{ route("disbursements.export", ["status" => "rejected", "format" => "pdf"]) }}';
        }
    });
}
</script>
@endpush
@endsection