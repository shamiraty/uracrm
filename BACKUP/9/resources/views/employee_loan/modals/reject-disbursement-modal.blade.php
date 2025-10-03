<!-- Reject Disbursement Modal -->
<div class="modal fade" id="rejectDisbursementModal" tabindex="-1" aria-labelledby="rejectDisbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-ura-reject">
                <h5 class="modal-title" id="rejectDisbursementModalLabel">
                    <i class="fas fa-ban"></i>
                    REJECT LOAN DISBURSEMENT
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-ura">
                <input type="hidden" id="rejectLoanId">
                
                <div class="container-fluid">
                    <div class="row g-4">
                        <!-- Left Column - Loan Information (4 columns) -->
                        <div class="col-lg-4">
                            <!-- Loan Details Card -->
                            <div class="mb-3">
                                <label class="form-label-ura mb-3">
                                    <i class="fas fa-file-invoice"></i>
                                    LOAN DETAILS
                                </label>
                                <div id="rejectLoanDetails" class="loan-details-card">
                                    <!-- Loan details will be populated here -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Middle Column - Rejection Category (4 columns) -->
                        <div class="col-lg-4">
                            <!-- Rejection Reason Selection -->
                            <div class="mb-3">
                                <label for="rejectionReason" class="form-label-ura mb-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    REJECTION CATEGORY
                                </label>
                                <select class="form-select form-select-ura form-select-lg" id="rejectionReason" required onchange="handleRejectionReasonChange(this)">
                                    <option value="">Choose rejection category...</option>
                                    <option value="Insufficient funds">💰 Insufficient Funds</option>
                                    <option value="Incomplete documentation">📋 Incomplete Documentation</option>
                                    <option value="Account verification pending">🔍 Verification Pending</option>
                                    <option value="Customer cancellation">👤 Customer Request</option>
                                    <option value="Technical error">⚙️ Technical Error</option>
                                    <option value="Compliance check failed">⚖️ Compliance Issue</option>
                                    <option value="Invalid bank details">🏦 Invalid Bank Details</option>
                                    <option value="other">✏️ Other (Specify Below)</option>
                                </select>
                            </div>
                            
                            <!-- Quick Action Templates -->
                            <div class="mt-4">
                                <label class="form-label-ura mb-2">
                                    <i class="fas fa-magic"></i>
                                    QUICK TEMPLATES
                                </label>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setTemplate('verification')">
                                        <i class="fas fa-check-circle me-2"></i>Pending Verification
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setTemplate('documents')">
                                        <i class="fas fa-file-alt me-2"></i>Missing Documents
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setTemplate('technical')">
                                        <i class="fas fa-cog me-2"></i>Technical Issue
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Message Area (4 columns) -->
                        <div class="col-lg-4">
                            <!-- Detailed Message -->
                            <div class="mb-3">
                                <label for="rejectionMessage" class="form-label-ura mb-3">
                                    <i class="fas fa-comment-alt"></i>
                                    CUSTOMER MESSAGE
                                </label>
                                <textarea class="form-control form-control-ura" id="rejectionMessage" rows="8" 
                                          placeholder="Enter a clear explanation (max 150 chars for API compliance)..." 
                                          maxlength="150" required></textarea>
                                <div class="character-counter">
                                    <span id="charCount">0</span> / 150 characters
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    <strong>Tip:</strong> Be clear, professional, and helpful. The customer will see this message.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bottom Row - Important Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <!-- Info Alert -->
                            <div class="alert alert-ura-info" role="alert">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div class="col">
                                        <strong class="d-block mb-2">Important Information:</strong>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <i class="fas fa-broadcast-tower me-2"></i>ESS will be notified immediately
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-bell me-2"></i>Customer receives your message
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-lock me-2"></i>Action cannot be undone
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-ura">
                <button type="button" class="btn btn-ura-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-ura-reject" id="confirmRejectDisbursement" onclick="confirmRejectDisbursement()">
                    <i class="fas fa-times-circle me-2"></i>Confirm Rejection
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Modern Reject Disbursement Modal Styles */
#rejectDisbursementModal .modal-dialog {
    max-width: 1200px;
}

#rejectDisbursementModal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header-ura-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 1.5rem;
    border-bottom: 3px solid #FF8C00;
}

.modal-header-ura-reject .modal-title {
    font-weight: 700;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header-ura-reject .modal-title i {
    font-size: 1.5rem;
}

.modal-body-ura {
    padding: 2rem;
    background: #f8f9fa;
}

.form-label-ura {
    font-weight: 700;
    color: #003366;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-select-ura {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-select-ura:focus {
    border-color: #17479E;
    box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.1);
}

.form-control-ura {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-control-ura:focus {
    border-color: #17479E;
    box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.1);
}

.loan-details-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.alert-ura-info {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(33, 150, 243, 0.1) 100%);
    border: 1px solid rgba(23, 71, 158, 0.2);
    border-radius: 10px;
    padding: 1rem;
}

.character-counter {
    text-align: right;
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.modal-footer-ura {
    background: white;
    border-top: 1px solid #e0e0e0;
    padding: 1.25rem;
}

.btn-ura-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-ura-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-ura-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-ura-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Responsive adjustments */
@media (max-width: 991px) {
    #rejectDisbursementModal .col-lg-4 {
        margin-bottom: 1.5rem;
    }
    
    #rejectDisbursementModal .col-lg-4:not(:last-child)::after {
        content: '';
        display: block;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        margin-top: 1.5rem;
    }
}
</style>
@endpush