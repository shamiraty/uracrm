<!-- Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-labelledby="loanDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #003366 0%, #17479E 100%); color: white;">
                <h5 class="modal-title" id="loanDetailsModalLabel" style="color: white !important;">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Loan Application Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-fill mb-4" id="loanDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="applicant-tab" data-bs-toggle="tab" data-bs-target="#applicant-details" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Applicant Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-details" type="button" role="tab">
                            <i class="fas fa-hand-holding-usd me-2"></i>Loan Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank-details" type="button" role="tab">
                            <i class="fas fa-university me-2"></i>Banking Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-details" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Status & Timeline
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="disbursement-tab" data-bs-toggle="tab" data-bs-target="#disbursement-details" type="button" role="tab">
                            <i class="fas fa-money-check-alt me-2"></i>Disbursement
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="audit-tab" data-bs-toggle="tab" data-bs-target="#audit-details" type="button" role="tab">
                            <i class="fas fa-history me-2"></i>Approval History
                        </button>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="loanDetailsTabContent">
                    <!-- Applicant Information Tab -->
                    <div class="tab-pane fade show active" id="applicant-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-id-card text-primary me-2"></i>Personal Information
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Full Name</span>
                                            <span class="detail-value" id="detail-full-name">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Check Number</span>
                                            <span class="detail-value" id="detail-check-number">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Employee ID</span>
                                            <span class="detail-value" id="detail-employee-id">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Vote Code</span>
                                            <span class="detail-value" id="detail-vote-code">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Vote Name</span>
                                            <span class="detail-value" id="detail-vote-name">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Department</span>
                                            <span class="detail-value" id="detail-department">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-phone-alt text-primary me-2"></i>Contact Information
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Phone Number</span>
                                            <span class="detail-value" id="detail-phone">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Email Address</span>
                                            <span class="detail-value" id="detail-email">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Residential Address</span>
                                            <span class="detail-value" id="detail-address">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Region</span>
                                            <span class="detail-value" id="detail-region">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">District</span>
                                            <span class="detail-value" id="detail-district">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Details Tab -->
                    <div class="tab-pane fade" id="loan-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-calculator text-success me-2"></i>Salary Information
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Basic Salary</span>
                                            <span class="detail-value text-success fw-bold" id="detail-basic-salary">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Net Salary</span>
                                            <span class="detail-value text-info fw-bold" id="detail-net-salary">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">One Third Amount</span>
                                            <span class="detail-value" id="detail-one-third">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Deductible Amount</span>
                                            <span class="detail-value text-warning" id="detail-deductible">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>Loan Information
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Application Number</span>
                                            <span class="detail-value fw-bold text-primary" id="detail-application-number">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Requested Amount</span>
                                            <span class="detail-value text-primary fw-bold fs-5" id="detail-requested-amount">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Purpose</span>
                                            <span class="detail-value" id="detail-purpose">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Tenure</span>
                                            <span class="detail-value" id="detail-tenure">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Interest Rate</span>
                                            <span class="detail-value" id="detail-interest-rate">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Monthly Installment</span>
                                            <span class="detail-value text-danger fw-bold" id="detail-monthly-installment">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>Loan Breakdown
                                    </h6>
                                    <div class="loan-breakdown">
                                        <div class="breakdown-item">
                                            <span>Principal Amount</span>
                                            <span class="fw-bold" id="detail-principal">-</span>
                                        </div>
                                        <div class="breakdown-item">
                                            <span>Interest Amount</span>
                                            <span class="fw-bold" id="detail-interest">-</span>
                                        </div>
                                        <div class="breakdown-item">
                                            <span>Processing Fee</span>
                                            <span class="fw-bold" id="detail-processing-fee">-</span>
                                        </div>
                                        <div class="breakdown-item">
                                            <span>Insurance Fee</span>
                                            <span class="fw-bold" id="detail-insurance-fee">-</span>
                                        </div>
                                        <div class="breakdown-item total">
                                            <span>Total Amount to Pay</span>
                                            <span class="fw-bold text-primary fs-5" id="detail-total-amount">-</span>
                                        </div>
                                        <div class="breakdown-item">
                                            <span>Net Loan Amount (Take Home)</span>
                                            <span class="fw-bold text-success fs-5" id="detail-net-amount">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banking Information Tab -->
                    <div class="tab-pane fade" id="bank-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-university text-info me-2"></i>Bank Details
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Bank Name</span>
                                            <span class="detail-value fw-bold" id="detail-bank-name">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Bank Branch</span>
                                            <span class="detail-value" id="detail-bank-branch">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">SWIFT Code</span>
                                            <span class="detail-value" id="detail-swift-code">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Account Number</span>
                                            <span class="detail-value fw-bold text-primary" id="detail-account-number">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Account Name</span>
                                            <span class="detail-value" id="detail-account-name">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-exchange-alt text-warning me-2"></i>Disbursement Channel
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Channel Type</span>
                                            <span class="detail-value" id="detail-channel-type">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Destination Code</span>
                                            <span class="detail-value" id="detail-destination-code">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Payment Method</span>
                                            <span class="detail-value" id="detail-payment-method">Bank Transfer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Timeline Tab -->
                    <div class="tab-pane fade" id="status-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-chart-line text-success me-2"></i>Current Status
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Application Status</span>
                                            <span class="detail-value" id="detail-status">
                                                <span class="badge bg-success">-</span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Approval Status</span>
                                            <span class="detail-value" id="detail-approval-status">
                                                <span class="badge bg-info">-</span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Disbursement Status</span>
                                            <span class="detail-value" id="detail-disbursement-status">
                                                <span class="badge bg-warning">-</span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">State</span>
                                            <span class="detail-value" id="detail-state">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-clock text-primary me-2"></i>Timeline
                                    </h6>
                                    <div class="timeline-container" id="detail-timeline">
                                        <!-- Timeline items will be dynamically added here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-comment-alt text-info me-2"></i>Remarks & Notes
                                    </h6>
                                    <div id="detail-remarks" class="remarks-section">
                                        <p class="text-muted">No remarks available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Disbursement Tab -->
                    <div class="tab-pane fade" id="disbursement-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-money-check-alt text-success me-2"></i>Disbursement Information
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Batch ID</span>
                                            <span class="detail-value" id="detail-batch-id">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Transaction ID</span>
                                            <span class="detail-value" id="detail-transaction-id">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Reference Number</span>
                                            <span class="detail-value" id="detail-reference-number">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Disbursed Amount</span>
                                            <span class="detail-value text-success fw-bold" id="detail-disbursed-amount">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Disbursement Date</span>
                                            <span class="detail-value" id="detail-disbursement-date">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Disbursed By</span>
                                            <span class="detail-value" id="detail-disbursed-by">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="detail-card-title">
                                        <i class="fas fa-file-alt text-primary me-2"></i>ESS Integration
                                    </h6>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">FSP Reference</span>
                                            <span class="detail-value" id="detail-fsp-reference">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Loan Number</span>
                                            <span class="detail-value" id="detail-loan-number">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">ESS Status</span>
                                            <span class="detail-value" id="detail-ess-status">-</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Last Sync</span>
                                            <span class="detail-value" id="detail-last-sync">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Audit Trail Tab -->
                    <div class="tab-pane fade" id="audit-details" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="accordion" id="auditAccordion">
                                    <!-- Internal Approvals Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingInternal">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInternal" aria-expanded="true">
                                                <i class="fas fa-building me-2 text-primary"></i>
                                                <strong>Internal Approvals</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseInternal" class="accordion-collapse collapse show" data-bs-parent="#auditAccordion">
                                            <div class="accordion-body">
                                                <div id="internal-audit-trail">
                                                    <!-- Internal audit trail will be populated here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- External Approvals Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingExternal">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExternal" aria-expanded="false">
                                                <i class="fas fa-globe me-2 text-info"></i>
                                                <strong>External Approvals (ESS/FSP)</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseExternal" class="accordion-collapse collapse" data-bs-parent="#auditAccordion">
                                            <div class="accordion-body">
                                                <div id="external-audit-trail">
                                                    <!-- External audit trail will be populated here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Disbursement History Accordion -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingDisbursement">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDisbursement" aria-expanded="false">
                                                <i class="fas fa-money-check-alt me-2 text-success"></i>
                                                <strong>Disbursement Activities</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseDisbursement" class="accordion-collapse collapse" data-bs-parent="#auditAccordion">
                                            <div class="accordion-body">
                                                <div id="disbursement-audit-trail">
                                                    <!-- Disbursement audit trail will be populated here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printLoanDetails()">
                    <i class="fas fa-print me-2"></i>Print Details
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styling */
#loanDetailsModal .modal-dialog {
    max-width: 1200px;
}

#loanDetailsModal .nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

#loanDetailsModal .nav-tabs .nav-link:hover {
    color: #17479E;
    border-bottom-color: rgba(23, 71, 158, 0.3);
}

#loanDetailsModal .nav-tabs .nav-link.active {
    color: #003366;
    background: none;
    border: none;
    border-bottom: 3px solid #003366;
}

.detail-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.05);
    transition: all 0.3s ease;
}

.detail-card:hover {
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.1);
}

.detail-card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #003366;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f3f5;
}

.detail-grid {
    display: grid;
    gap: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.detail-value {
    font-size: 0.95rem;
    color: #2c3e50;
    font-weight: 600;
    text-align: right;
}

/* Loan Breakdown Styling */
.loan-breakdown {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #dee2e6;
}

.breakdown-item:last-child {
    border-bottom: none;
}

.breakdown-item.total {
    background: #e7f3ff;
    margin: 0.5rem -1rem;
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #17479E;
}

/* Timeline Styling */
.timeline-container {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #17479E;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.timeline-item::after {
    content: '';
    position: absolute;
    left: -1.06rem;
    top: 1.5rem;
    width: 2px;
    height: calc(100% - 1rem);
    background: #dee2e6;
}

.timeline-item:last-child::after {
    display: none;
}

.timeline-date {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.timeline-content {
    font-size: 0.875rem;
    color: #2c3e50;
    font-weight: 500;
}

/* Remarks Section */
.remarks-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    min-height: 100px;
}

.remarks-section p {
    margin-bottom: 0;
    line-height: 1.6;
}

/* Badge Styling */
.badge {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 20px;
}

/* Print Styles */
@media print {
    .modal-header,
    .modal-footer,
    .nav-tabs {
        display: none !important;
    }
    
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
    
    .detail-card {
        page-break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    #loanDetailsModal .modal-dialog {
        max-width: 100%;
        margin: 0;
    }
    
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-value {
        text-align: left;
        margin-top: 0.25rem;
    }
}
</style>

<script>
// Populate modal with loan data
function populateLoanDetailsModal(loan) {
    // Applicant Information
    document.getElementById('detail-full-name').textContent = `${loan.first_name || ''} ${loan.middle_name || ''} ${loan.last_name || ''}`.trim() || '-';
    document.getElementById('detail-check-number').textContent = loan.check_number || '-';
    document.getElementById('detail-employee-id').textContent = loan.employee_id || loan.employee_number || '-';
    document.getElementById('detail-vote-code').textContent = loan.vote_code || '-';
    document.getElementById('detail-vote-name').textContent = loan.vote_name || '-';
    document.getElementById('detail-department').textContent = loan.department || '-';
    
    // Contact Information
    document.getElementById('detail-phone').textContent = loan.phone_number || loan.mobile || '-';
    document.getElementById('detail-email').textContent = loan.email || '-';
    document.getElementById('detail-address').textContent = loan.residential_address || '-';
    document.getElementById('detail-region').textContent = loan.region || '-';
    document.getElementById('detail-district').textContent = loan.district || '-';
    
    // Salary Information
    document.getElementById('detail-basic-salary').textContent = formatCurrency(loan.basic_salary);
    document.getElementById('detail-net-salary').textContent = formatCurrency(loan.net_salary);
    document.getElementById('detail-one-third').textContent = formatCurrency(loan.one_third_amount);
    document.getElementById('detail-deductible').textContent = formatCurrency(loan.deductible_amount || loan.desired_deductible_amount);
    
    // Loan Information
    document.getElementById('detail-application-number').textContent = loan.application_number || '-';
    document.getElementById('detail-requested-amount').textContent = formatCurrency(loan.requested_amount);
    document.getElementById('detail-purpose').textContent = loan.purpose || loan.loan_purpose || '-';
    document.getElementById('detail-tenure').textContent = loan.tenure ? `${loan.tenure} months` : '-';
    document.getElementById('detail-interest-rate').textContent = loan.interest_rate ? `${loan.interest_rate}%` : '-';
    document.getElementById('detail-monthly-installment').textContent = formatCurrency(loan.monthly_installment || loan.desired_deductible_amount);
    
    // Loan Breakdown
    document.getElementById('detail-principal').textContent = formatCurrency(loan.requested_amount);
    document.getElementById('detail-interest').textContent = formatCurrency(loan.interest_amount || 0);
    document.getElementById('detail-processing-fee').textContent = formatCurrency(loan.processing_fee || 0);
    document.getElementById('detail-insurance-fee').textContent = formatCurrency(loan.insurance || 0);
    document.getElementById('detail-total-amount').textContent = formatCurrency(loan.total_amount_to_pay || loan.requested_amount);
    document.getElementById('detail-net-amount').textContent = formatCurrency(loan.take_home_amount || loan.net_loan_amount || loan.requested_amount);
    
    // Banking Information
    document.getElementById('detail-bank-name').textContent = loan.bank?.name || loan.bank_name || '-';
    document.getElementById('detail-bank-branch').textContent = loan.bank_branch || '-';
    document.getElementById('detail-swift-code').textContent = loan.swift_code || '-';
    document.getElementById('detail-account-number').textContent = loan.bank_account_number || '-';
    document.getElementById('detail-account-name').textContent = loan.bank_account_name || `${loan.first_name || ''} ${loan.last_name || ''}`.trim() || '-';
    
    // Disbursement Channel
    const channelType = loan.disbursements?.[0]?.channel_identifier || determineChannel(loan);
    document.getElementById('detail-channel-type').textContent = channelType || '-';
    document.getElementById('detail-destination-code').textContent = loan.disbursements?.[0]?.destination_code || loan.bank?.short_name || '-';
    
    // Status Information
    updateStatusBadge('detail-status', loan.status);
    updateStatusBadge('detail-approval-status', loan.approval);
    updateStatusBadge('detail-disbursement-status', loan.disbursement_status);
    document.getElementById('detail-state').textContent = loan.state || '-';
    
    // Timeline
    updateTimeline(loan);
    
    // Remarks
    const remarksSection = document.getElementById('detail-remarks');
    if (loan.remarks || loan.reason) {
        remarksSection.innerHTML = `<p>${loan.remarks || loan.reason}</p>`;
    } else {
        remarksSection.innerHTML = '<p class="text-muted">No remarks available</p>';
    }
    
    // Disbursement Information
    document.getElementById('detail-batch-id').textContent = loan.nmb_batch_id || loan.batch_id || '-';
    document.getElementById('detail-transaction-id').textContent = loan.transaction_id || '-';
    document.getElementById('detail-reference-number').textContent = loan.reference_number || '-';
    document.getElementById('detail-disbursed-amount').textContent = formatCurrency(loan.disbursed_amount || loan.take_home_amount || loan.net_loan_amount);
    document.getElementById('detail-disbursement-date').textContent = loan.disbursed_at ? formatDate(loan.disbursed_at) : '-';
    document.getElementById('detail-disbursed-by').textContent = loan.disbursed_by_name || '-';
    
    // ESS Integration
    document.getElementById('detail-fsp-reference').textContent = loan.fsp_reference_number || '-';
    document.getElementById('detail-loan-number').textContent = loan.loan_number || '-';
    document.getElementById('detail-ess-status').textContent = loan.ess_status || loan.state || '-';
    document.getElementById('detail-last-sync').textContent = loan.last_sync ? formatDate(loan.last_sync) : '-';
    
    // Populate Audit Trail sections
    populateAuditTrailSections(loan);
}

// Populate audit trail sections in accordion
function populateAuditTrailSections(loan) {
    // Internal Approvals
    const internalTrail = document.getElementById('internal-audit-trail');
    if (internalTrail) {
        internalTrail.innerHTML = generateInternalAuditTrail(loan);
    }
    
    // External Approvals
    const externalTrail = document.getElementById('external-audit-trail');
    if (externalTrail) {
        externalTrail.innerHTML = generateExternalAuditTrail(loan);
    }
    
    // Disbursement Activities
    const disbursementTrail = document.getElementById('disbursement-audit-trail');
    if (disbursementTrail) {
        disbursementTrail.innerHTML = generateDisbursementAuditTrail(loan);
    }
}

// Helper function to format currency
function formatCurrency(amount) {
    if (!amount && amount !== 0) return '-';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' TZS';
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Helper function to determine channel
function determineChannel(loan) {
    if (loan.swift_code === 'NMIBTZTZ') return 'INTERNAL';
    const amount = loan.take_home_amount || loan.net_loan_amount || loan.requested_amount;
    return amount >= 20000000 ? 'TISS' : 'DOMESTIC';
}

// Helper function to update status badge
function updateStatusBadge(elementId, status) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const badge = element.querySelector('.badge') || document.createElement('span');
    badge.className = 'badge';
    
    if (!status) {
        badge.textContent = 'Pending';
        badge.classList.add('bg-secondary');
    } else {
        const upperStatus = status.toUpperCase();
        badge.textContent = upperStatus;
        
        if (['APPROVED', 'DISBURSED', 'COMPLETED', 'SUCCESS'].includes(upperStatus)) {
            badge.classList.add('bg-success');
        } else if (['REJECTED', 'FAILED', 'CANCELLED', 'CANCELED'].includes(upperStatus)) {
            badge.classList.add('bg-danger');
        } else if (['PENDING', 'PROCESSING', 'IN_PROGRESS'].includes(upperStatus)) {
            badge.classList.add('bg-warning');
        } else {
            badge.classList.add('bg-info');
        }
    }
    
    if (!element.querySelector('.badge')) {
        element.appendChild(badge);
    }
}

// Helper function to update timeline
function updateTimeline(loan) {
    const timeline = document.getElementById('detail-timeline');
    timeline.innerHTML = '';
    
    const events = [];
    
    if (loan.created_at) {
        events.push({
            date: loan.created_at,
            text: 'Application Submitted'
        });
    }
    
    if (loan.approved_at) {
        events.push({
            date: loan.approved_at,
            text: 'Loan Approved'
        });
    }
    
    if (loan.disbursed_at) {
        events.push({
            date: loan.disbursed_at,
            text: 'Loan Disbursed'
        });
    }
    
    if (loan.liquidated_at) {
        events.push({
            date: loan.liquidated_at,
            text: 'Loan Liquidated'
        });
    }
    
    events.sort((a, b) => new Date(a.date) - new Date(b.date));
    
    events.forEach(event => {
        const item = document.createElement('div');
        item.className = 'timeline-item';
        item.innerHTML = `
            <div class="timeline-date">${formatDate(event.date)}</div>
            <div class="timeline-content">${event.text}</div>
        `;
        timeline.appendChild(item);
    });
    
    if (events.length === 0) {
        timeline.innerHTML = '<p class="text-muted">No timeline events available</p>';
    }
}

// Print function
function printLoanDetails() {
    window.print();
}

// Listen for view button clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-loan-btn')) {
        const button = e.target.closest('.view-loan-btn');
        const loanData = JSON.parse(button.dataset.loan || '{}');
        populateLoanDetailsModal(loanData);
    }
});
</script>