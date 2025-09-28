<!-- Modern Reject Loan Modal -->
<div class="modal fade" id="rejectLoanModal" tabindex="-1" aria-labelledby="rejectLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-ura-reject">
                <h5 class="modal-title" id="rejectLoanModalLabel">
                    <i class="fas fa-ban"></i>
                    REJECT LOAN APPLICATION
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-ura">
                <input type="hidden" id="rejectLoanId">
                <input type="hidden" id="rejectLoanType" value="single"> <!-- single or bulk -->
                <input type="hidden" id="rejectLoanIds"> <!-- For bulk rejection -->
                
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
                            
                            <!-- Bulk Info (shown only for bulk rejections) -->
                            <div id="bulkRejectInfo" class="alert alert-warning d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        <strong>Bulk Rejection</strong><br>
                                        <span id="bulkCount">0</span> loans will be rejected
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Middle Column - Rejection Category (4 columns) -->
                        <div class="col-lg-4">
                            <!-- Rejection Reason Selection -->
                            <div class="mb-3">
                                <label for="loanRejectionReason" class="form-label-ura mb-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    REJECTION CATEGORY
                                </label>
                                <select class="form-select form-select-ura form-select-lg" id="loanRejectionReason" required onchange="handleRejectionCategoryChange(this)">
                                    <option value="">Choose rejection category...</option>
                                    <option value="Loan does not meet approval criteria">❌ Does Not Meet Criteria</option>
                                    <option value="Insufficient income to support loan">💰 Insufficient Income</option>
                                    <option value="Poor credit history">📊 Credit History Issues</option>
                                    <option value="Documentation is incomplete">📋 Incomplete Documentation</option>
                                    <option value="Verification failed">🔍 Verification Failed</option>
                                    <option value="Customer requested cancellation">👤 Customer Request</option>
                                    <option value="Duplicate application">📑 Duplicate Application</option>
                                    <option value="Compliance requirements not met">⚖️ Compliance Issue</option>
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
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setLoanTemplate('income')">
                                        <i class="fas fa-dollar-sign me-2"></i>Insufficient Income
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setLoanTemplate('documents')">
                                        <i class="fas fa-file-alt me-2"></i>Missing Documents
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setLoanTemplate('criteria')">
                                        <i class="fas fa-clipboard-check me-2"></i>Criteria Not Met
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setLoanTemplate('credit')">
                                        <i class="fas fa-chart-line me-2"></i>Credit Issues
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Message Area (4 columns) -->
                        <div class="col-lg-4">
                            <!-- Detailed Message -->
                            <div class="mb-3">
                                <label for="loanRejectionMessage" class="form-label-ura mb-3">
                                    <i class="fas fa-comment-alt"></i>
                                    CUSTOMER MESSAGE
                                </label>
                                <textarea class="form-control form-control-ura" id="loanRejectionMessage" rows="8" 
                                          placeholder="Enter a clear explanation for the rejection (max 150 chars for API compliance)..." 
                                          maxlength="150" required
                                          oninput="validateLoanMessage(this)"></textarea>
                                <div class="character-counter">
                                    <span id="loanCharCount">0</span> / 150 characters
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    <strong>API Limit:</strong> Maximum 150 characters to comply with ESS API requirements.
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
                                                <i class="fas fa-envelope me-2"></i>Applicant will be notified
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-history me-2"></i>Decision is recorded
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-lock me-2"></i>Action is final
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
                <button type="button" class="btn btn-ura-reject" id="confirmRejectLoan" onclick="confirmLoanRejection()">
                    <i class="fas fa-times-circle me-2"></i>Confirm Rejection
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Reuse styles from approved blade - these should be in a shared CSS file */
.modal-header-ura-reject {
    background: linear-gradient(135deg, var(--ura-primary, #003366) 0%, var(--ura-secondary, #17479E) 100%);
    color: white;
    padding: 1.5rem 2rem;
    border: none;
    position: relative;
    overflow: hidden;
}

.modal-header-ura-reject::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    animation: rotate 15s linear infinite;
}

.modal-header-ura-reject .modal-title {
    font-weight: 700;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    z-index: 1;
    position: relative;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.5px;
}

.modal-header-ura-reject .modal-title i {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.modal-body-ura {
    padding: 2.5rem;
}

.loan-details-card {
    background: linear-gradient(135deg, var(--ura-primary, #003366) 0%, var(--ura-secondary, #17479E) 100%);
    color: white !important;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.3);
    position: relative;
    overflow: hidden;
}

.loan-details-card * {
    color: white !important;
}

.loan-details-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
    animation: rotate 20s linear infinite;
}

.loan-details-card .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    position: relative;
    z-index: 1;
}

.loan-details-card .detail-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.loan-details-card .detail-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9) !important;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    font-weight: 500;
}

.loan-details-card .detail-value {
    font-weight: 700;
    font-size: 1.05rem;
    text-align: right;
    color: white !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.form-control-ura,
.form-select-ura {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control-ura:focus,
.form-select-ura:focus {
    border-color: var(--ura-secondary, #17479E);
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.15);
    outline: none;
}

.form-label-ura {
    font-weight: 700;
    color: var(--ura-primary, #003366);
    margin-bottom: 0.75rem;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    display: flex;
    align-items: center;
}

.form-label-ura i {
    color: var(--ura-secondary, #17479E);
    margin-right: 0.5rem;
}

.alert-ura-info {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(21, 101, 192, 0.1) 100%);
    border: 1px solid rgba(33, 150, 243, 0.3);
    color: #1565C0;
    border-radius: 10px;
    padding: 1rem;
}

.modal-footer-ura {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    padding: 1.25rem;
}

.btn-ura-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-ura-cancel:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-ura-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-ura-reject:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.character-counter {
    font-size: 0.75rem;
    color: #6c757d;
    text-align: right;
    margin-top: 0.25rem;
}

.btn-outline-primary {
    border-color: var(--ura-secondary, #17479E);
    color: var(--ura-primary, #003366);
}

.btn-outline-primary:hover {
    background: var(--ura-secondary, #17479E);
    border-color: var(--ura-secondary, #17479E);
    color: white;
}

/* Modal XL Specific Styles */
@media (min-width: 1200px) {
    #rejectLoanModal .modal-dialog {
        max-width: 1200px;
    }
    
    .modal-body-ura .container-fluid {
        padding: 0 1rem;
    }
}

/* Three Column Layout Styling */
#rejectLoanModal .col-lg-4 {
    position: relative;
}

@media (min-width: 992px) {
    #rejectLoanModal .col-lg-4:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -1rem;
        top: 0;
        bottom: 0;
        width: 1px;
        background: linear-gradient(180deg, transparent, #e9ecef 20%, #e9ecef 80%, transparent);
    }
}
</style>

<script>
// Validate loan rejection message in real-time
function validateLoanMessage(textarea) {
    const messageLength = textarea.value.trim().length;
    const charCount = document.getElementById('loanCharCount');
    
    if (charCount) {
        charCount.textContent = messageLength;
        
        // Update color based on length (adjusted for 150 char limit)
        if (messageLength > 140) {
            charCount.parentElement.style.color = '#dc3545';
        } else if (messageLength > 120) {
            charCount.parentElement.style.color = '#ffc107';
        } else {
            charCount.parentElement.style.color = '#6c757d';
        }
    }
    
    // Add validation state
    if (messageLength === 0 && textarea.value.length > 0) {
        textarea.classList.add('is-invalid');
        textarea.classList.remove('is-valid');
    } else if (messageLength > 0 && messageLength < 20) {
        textarea.classList.add('is-invalid');
        textarea.classList.remove('is-valid');
    } else if (messageLength >= 20) {
        textarea.classList.remove('is-invalid');
        textarea.classList.add('is-valid');
    } else {
        textarea.classList.remove('is-invalid');
        textarea.classList.remove('is-valid');
    }
}

// Quick template function for loan rejection messages
function setLoanTemplate(type) {
    const messageTextarea = document.getElementById('loanRejectionMessage');
    const reasonSelect = document.getElementById('loanRejectionReason');
    
    const templates = {
        'income': {
            reason: 'Insufficient income to support loan',
            message: 'Income insufficient for requested amount. Consider smaller loan or reapply when income improves.'
        },
        'documents': {
            reason: 'Documentation is incomplete',
            message: 'Missing required documents. Submit complete documentation including income proof, ID, and employment verification.'
        },
        'criteria': {
            reason: 'Loan does not meet approval criteria',
            message: 'Loan application does not meet approval criteria. Please review requirements and reapply after addressing issues.'
        },
        'credit': {
            reason: 'Poor credit history',
            message: 'Unable to approve due to credit history. Improve credit score and reapply after 6 months.'
        }
    };
    
    if (templates[type]) {
        reasonSelect.value = templates[type].reason;
        messageTextarea.value = templates[type].message;
        
        // Update character counter
        const charCount = document.getElementById('loanCharCount');
        if (charCount) {
            charCount.textContent = messageTextarea.value.length;
            
            // Update color
            const count = messageTextarea.value.length;
            if (count > 450) {
                charCount.parentElement.style.color = '#dc3545';
            } else if (count > 400) {
                charCount.parentElement.style.color = '#ffc107';
            } else {
                charCount.parentElement.style.color = '#6c757d';
            }
        }
        
        // Trigger validation
        validateLoanMessage(messageTextarea);
    }
}

// Show reject loan modal
function showRejectLoanModal(loanId, loanData) {
    // Reset form
    document.getElementById('loanRejectionReason').value = '';
    document.getElementById('loanRejectionMessage').value = '';
    document.getElementById('loanCharCount').textContent = '0';
    document.getElementById('rejectLoanId').value = loanId;
    document.getElementById('rejectLoanType').value = 'single';
    
    // Hide bulk info
    document.getElementById('bulkRejectInfo').classList.add('d-none');
    
    // Populate loan details if provided
    if (loanData) {
        console.log('Loan data received:', loanData); // Debug log
        const detailsHtml = `
            <div class="detail-row">
                <span class="detail-label">Application No.</span>
                <span class="detail-value">${loanData.application_number || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Applicant Name</span>
                <span class="detail-value">${loanData.name || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Loan Amount</span>
                <span class="detail-value">${loanData.amount || 'N/A'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Applied Date</span>
                <span class="detail-value">${loanData.date || 'N/A'}</span>
            </div>
        `;
        document.getElementById('rejectLoanDetails').innerHTML = detailsHtml;
    } else {
        console.log('No loan data provided'); // Debug log
        // Provide default content
        const detailsHtml = `
            <div class="detail-row">
                <span class="detail-label">Application No.</span>
                <span class="detail-value">Loading...</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Applicant Name</span>
                <span class="detail-value">Loading...</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Loan Amount</span>
                <span class="detail-value">Loading...</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Applied Date</span>
                <span class="detail-value">Loading...</span>
            </div>
        `;
        document.getElementById('rejectLoanDetails').innerHTML = detailsHtml;
    }
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('rejectLoanModal'));
    modal.show();
}

// Show bulk reject modal
function showBulkRejectModal(loanIds) {
    // Reset form
    document.getElementById('loanRejectionReason').value = '';
    document.getElementById('loanRejectionMessage').value = '';
    document.getElementById('loanCharCount').textContent = '0';
    document.getElementById('rejectLoanType').value = 'bulk';
    document.getElementById('rejectLoanIds').value = loanIds.join(',');
    
    // Show bulk info
    const bulkInfo = document.getElementById('bulkRejectInfo');
    bulkInfo.classList.remove('d-none');
    document.getElementById('bulkCount').textContent = loanIds.length;
    
    // Update loan details for bulk
    const detailsHtml = `
        <div class="detail-row">
            <span class="detail-label">Total Loans</span>
            <span class="detail-value">${loanIds.length}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Action Type</span>
            <span class="detail-value">Bulk Rejection</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">Pending Review</span>
        </div>
    `;
    document.getElementById('rejectLoanDetails').innerHTML = detailsHtml;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('rejectLoanModal'));
    modal.show();
}

// Handle rejection category change
function handleRejectionCategoryChange(selectElement) {
    const selectedValue = selectElement.value;
    const messageTextarea = document.getElementById('loanRejectionMessage');
    
    // Define template messages for each category (max 150 chars for API compliance)
    const templateMessages = {
        'Loan does not meet approval criteria': 'Loan application does not meet approval criteria. Please review requirements and reapply after addressing issues.',
        'Insufficient income to support loan': 'Income insufficient for requested amount. Consider smaller loan or reapply when income improves.',
        'Poor credit history': 'Unable to approve due to credit history. Improve credit score and reapply after 6 months.',
        'Documentation is incomplete': 'Missing required documents. Submit complete documentation including income proof, ID, and employment verification.',
        'Verification failed': 'Unable to verify application information. Ensure all details are accurate and resubmit.',
        'Customer requested cancellation': 'Loan cancelled as per your request. You may reapply at any time.',
        'Duplicate application': 'Duplicate application detected. Check status of existing application or contact support.',
        'Compliance requirements not met': 'Application does not meet regulatory requirements. Contact compliance for details.'
    };
    
    // If a template message exists for the selected category, populate it
    if (templateMessages[selectedValue]) {
        messageTextarea.value = templateMessages[selectedValue];
        
        // Update character counter
        const charCount = document.getElementById('loanCharCount');
        if (charCount) {
            charCount.textContent = messageTextarea.value.length;
            
            // Update color based on length (adjusted for 150 char limit)
            const count = messageTextarea.value.length;
            if (count > 140) {
                charCount.parentElement.style.color = '#dc3545';
            } else if (count > 120) {
                charCount.parentElement.style.color = '#ffc107';
            } else {
                charCount.parentElement.style.color = '#6c757d';
            }
        }
        
        // Trigger validation
        validateLoanMessage(messageTextarea);
    } else if (selectedValue === 'other' || selectedValue === '') {
        // Clear the message for 'other' or empty selection
        messageTextarea.value = '';
        const charCount = document.getElementById('loanCharCount');
        if (charCount) {
            charCount.textContent = '0';
            charCount.parentElement.style.color = '#6c757d';
        }
        messageTextarea.classList.remove('is-valid', 'is-invalid');
    }
}

// Make functions globally available
window.showRejectLoanModal = showRejectLoanModal;
window.showBulkRejectModal = showBulkRejectModal;
window.confirmLoanRejection = confirmLoanRejection;
window.setLoanTemplate = setLoanTemplate;
window.validateLoanMessage = validateLoanMessage;
window.handleRejectionCategoryChange = handleRejectionCategoryChange;

// Confirm loan rejection
function confirmLoanRejection() {
    const type = document.getElementById('rejectLoanType').value;
    const reason = document.getElementById('loanRejectionReason').value;
    const message = document.getElementById('loanRejectionMessage').value.trim();
    
    // Validate inputs
    if (!reason || reason === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Rejection Category Required',
            text: 'Please select a rejection category from the dropdown.',
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    if (!message || message === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Customer Message Required',
            html: `
                <div class="text-center">
                    <i class="fas fa-comment-alt mb-3" style="font-size: 48px; color: #ffc107;"></i>
                    <p>Please enter an explanation for the rejection.</p>
                    <small class="text-muted">This message will be sent to the applicant.</small>
                </div>
            `,
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK',
            didClose: () => {
                document.getElementById('loanRejectionMessage').focus();
            }
        });
        return;
    }
    
    // Check minimum message length
    if (message.length < 20) {
        Swal.fire({
            icon: 'warning',
            title: 'Message Too Short',
            html: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #ffc107;"></i>
                    <p>Please provide a more detailed explanation.</p>
                    <small class="text-muted">Minimum 20 characters required. Current: ${message.length} characters.</small>
                </div>
            `,
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK',
            didClose: () => {
                document.getElementById('loanRejectionMessage').focus();
            }
        });
        return;
    }
    
    // Get the appropriate data based on type
    let loanId, loanIds;
    if (type === 'single') {
        loanId = document.getElementById('rejectLoanId').value;
    } else {
        loanIds = document.getElementById('rejectLoanIds').value.split(',');
    }
    
    // Use the message as the reason if 'other' is selected
    const finalReason = (reason === 'other') ? message : reason;
    
    // Show confirmation
    const confirmMessage = type === 'single' 
        ? 'You are about to reject this loan application.' 
        : `You are about to reject ${loanIds.length} loan applications.`;
        
    Swal.fire({
        title: 'Confirm Rejection',
        html: `
            <div class="text-start">
                <p><strong>${confirmMessage}</strong></p>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">Category:</small><br>
                    <strong>${reason === 'other' ? 'Other (Custom Reason)' : reason}</strong><br><br>
                    <small class="text-muted">Message to Applicant:</small><br>
                    <div class="mt-2 p-2 border rounded bg-white" style="max-height: 150px; overflow-y: auto;">
                        ${message}
                    </div>
                </div>
                <p class="mt-3 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times-circle me-2"></i>Yes, Reject',
        cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('rejectLoanModal')).hide();
            
            // Call the appropriate rejection function
            if (type === 'single') {
                // This function should be defined in the parent page
                if (typeof processLoanRejection === 'function') {
                    processLoanRejection(loanId, finalReason, message);
                } else {
                    console.error('processLoanRejection function not defined');
                }
            } else {
                // This function should be defined in the parent page
                if (typeof processBulkLoanRejection === 'function') {
                    processBulkLoanRejection(loanIds, finalReason, message);
                } else {
                    console.error('processBulkLoanRejection function not defined');
                }
            }
        }
    });
}
</script>