@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Global variables
let selectedLoans = new Set();
let currentLoanId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    initializeFilters();
    updateStatistics();
});

// Initialize tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Initialize filters
function initializeFilters() {
    const filterBadges = document.querySelectorAll('.filter-badge');
    filterBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            filterBadges.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            applyFilter(this.dataset.filter);
        });
    });
}

// Apply filter to table
function applyFilter(filter) {
    const rows = document.querySelectorAll('.modern-table tbody tr');
    rows.forEach(row => {
        if (filter === 'all') {
            row.style.display = '';
        } else if (filter === 'urgent') {
            const daysWaiting = parseInt(row.dataset.daysWaiting || 0);
            row.style.display = daysWaiting > 3 ? '' : 'none';
        } else if (filter === 'today') {
            const approvedToday = row.dataset.approvedToday === 'true';
            row.style.display = approvedToday ? '' : 'none';
        }
    });
    updateStatistics();
}

// Update statistics based on visible rows
function updateStatistics() {
    const visibleRows = document.querySelectorAll('.modern-table tbody tr:not([style*="display: none"])');
    const totalVisible = visibleRows.length;
    const totalAmount = Array.from(visibleRows).reduce((sum, row) => {
        return sum + parseFloat(row.dataset.amount || 0);
    }, 0);
    
    // Update counters
    const countElement = document.querySelector('.visible-count');
    if (countElement) {
        countElement.textContent = totalVisible;
    }
    
    const amountElement = document.querySelector('.total-amount');
    if (amountElement) {
        amountElement.textContent = formatCurrency(totalAmount);
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-TZ', {
        style: 'currency',
        currency: 'TZS',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// View loan details
function viewLoanDetails(loanId) {
    showLoading();
    
    fetch(`/employee-loans/${loanId}/details`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            showLoanDetailsModal(data);
        })
        .catch(error => {
            hideLoading();
            showError('Failed to load loan details');
        });
}

// Show loan details modal
function showLoanDetailsModal(loan) {
    const modalContent = `
        <div class="loan-details-content">
            <div class="detail-section">
                <h6 class="section-title">Employee Information</h6>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">${loan.first_name} ${loan.last_name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Employee ID:</span>
                    <span class="detail-value">${loan.employee_id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department:</span>
                    <span class="detail-value">${loan.department || 'N/A'}</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h6 class="section-title">Loan Information</h6>
                <div class="detail-row">
                    <span class="detail-label">Application No:</span>
                    <span class="detail-value">${loan.application_no}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">${formatCurrency(loan.loan_amount)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Approval Date:</span>
                    <span class="detail-value">${formatDate(loan.approval_date)}</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h6 class="section-title">Bank Details</h6>
                <div class="detail-row">
                    <span class="detail-label">Bank:</span>
                    <span class="detail-value">${loan.bank_name || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account:</span>
                    <span class="detail-value">${loan.account_number || 'N/A'}</span>
                </div>
            </div>
        </div>
    `;
    
    Swal.fire({
        title: 'Loan Details',
        html: modalContent,
        width: '600px',
        showCloseButton: true,
        confirmButtonText: 'Close',
        confirmButtonColor: '#17479E'
    });
}

// Process disbursement
function processDisbursement(loanId) {
    currentLoanId = loanId;
    
    // Load loan details for disbursement modal
    fetch(`/employee-loans/${loanId}/details`)
        .then(response => response.json())
        .then(data => {
            populateDisbursementModal(data);
            const modal = new bootstrap.Modal(document.getElementById('disbursementModal'));
            modal.show();
        })
        .catch(error => {
            showError('Failed to load loan details');
        });
}

// Populate disbursement modal
function populateDisbursementModal(loan) {
    const content = `
        <div class="disbursement-details">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                You are about to process disbursement for this loan.
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Employee Details</h6>
                    <p><strong>Name:</strong> ${loan.first_name} ${loan.last_name}</p>
                    <p><strong>Employee ID:</strong> ${loan.employee_id}</p>
                </div>
                <div class="col-md-6">
                    <h6>Loan Details</h6>
                    <p><strong>Amount:</strong> ${formatCurrency(loan.loan_amount)}</p>
                    <p><strong>Application No:</strong> ${loan.application_no}</p>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6>Bank Information</h6>
                    <p><strong>Bank:</strong> ${loan.bank_name || 'N/A'}</p>
                    <p><strong>Account Number:</strong> ${loan.account_number || 'N/A'}</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('disbursementContent').innerHTML = content;
}

// Confirm disbursement
document.getElementById('confirmDisbursement')?.addEventListener('click', function() {
    if (!currentLoanId) return;
    
    Swal.fire({
        title: 'Confirm Disbursement',
        text: 'Are you sure you want to process this disbursement?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Process',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processDisbursementRequest(currentLoanId);
        }
    });
});

// Process disbursement request
function processDisbursementRequest(loanId) {
    showLoading();
    
    fetch(`/employee-loans/${loanId}/disburse`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            loan_id: loanId,
            action: 'disburse'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Loan disbursement processed successfully',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to process disbursement');
        }
    })
    .catch(error => {
        hideLoading();
        showError('An error occurred while processing disbursement');
    });
}

// Reject disbursement
function rejectDisbursement(loanId) {
    currentLoanId = loanId;
    
    // Load loan details for rejection modal
    fetch(`/employee-loans/${loanId}/details`)
        .then(response => response.json())
        .then(data => {
            populateRejectionModal(data);
            const modal = new bootstrap.Modal(document.getElementById('rejectDisbursementModal'));
            modal.show();
        })
        .catch(error => {
            showError('Failed to load loan details');
        });
}

// Populate rejection modal
function populateRejectionModal(loan) {
    const detailsHtml = `
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-user"></i> Employee</span>
            <span class="detail-value">${loan.first_name} ${loan.last_name}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-id-badge"></i> ID</span>
            <span class="detail-value">${loan.employee_id}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-money-bill"></i> Amount</span>
            <span class="detail-value">${formatCurrency(loan.loan_amount)}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label"><i class="fas fa-file"></i> Application</span>
            <span class="detail-value">${loan.application_no}</span>
        </div>
    `;
    
    document.getElementById('rejectLoanDetails').innerHTML = detailsHtml;
    document.getElementById('rejectLoanId').value = loan.id;
}

// Handle rejection reason change
function handleRejectionReasonChange(select) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const templates = {
        'Insufficient funds': 'Insufficient funds for disbursement. Please try again later.',
        'Incomplete documentation': 'Missing required documents. Submit via ESS portal.',
        'Account verification pending': 'Account verification in progress. Will notify once complete.',
        'Customer cancellation': 'Loan cancelled as per your request.',
        'Technical error': 'Technical error occurred. Resolution in progress.',
        'Compliance check failed': 'Compliance requirements not met. Contact support for details.',
        'Invalid bank details': 'Bank account details incorrect. Please update and resubmit.'
    };
    
    if (templates[select.value]) {
        messageTextarea.value = templates[select.value];
        updateCharacterCount();
    }
}

// Set template message
function setTemplate(type) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const templates = {
        'verification': 'Account verification pending. We will notify you once completed.',
        'documents': 'Required documents missing. Please submit via ESS portal.',
        'technical': 'Technical issue preventing disbursement. Our team is working on it.'
    };
    
    if (templates[type]) {
        messageTextarea.value = templates[type];
        updateCharacterCount();
    }
}

// Update character count
function updateCharacterCount() {
    const textarea = document.getElementById('rejectionMessage');
    const charCount = document.getElementById('charCount');
    if (textarea && charCount) {
        charCount.textContent = textarea.value.length;
    }
}

// Confirm rejection
function confirmRejectDisbursement() {
    const loanId = document.getElementById('rejectLoanId').value;
    const reason = document.getElementById('rejectionReason').value;
    const message = document.getElementById('rejectionMessage').value.trim();
    
    if (!reason) {
        showError('Please select a rejection category');
        return;
    }
    
    if (!message || message.length < 20) {
        showError('Please provide a detailed message (minimum 20 characters)');
        return;
    }
    
    Swal.fire({
        title: 'Confirm Rejection',
        text: 'Are you sure you want to reject this disbursement?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processRejection(loanId, reason, message);
        }
    });
}

// Process rejection
function processRejection(loanId, reason, message) {
    showLoading();
    
    fetch(`/employee-loans/${loanId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            loan_id: loanId,
            reason: reason,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Rejected',
                text: 'Loan disbursement has been rejected',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to reject disbursement');
        }
    })
    .catch(error => {
        hideLoading();
        showError('An error occurred while rejecting disbursement');
    });
}

// Show loading
function showLoading() {
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'loading-overlay';
    loadingDiv.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(loadingDiv);
}

// Hide loading
function hideLoading() {
    const loadingDiv = document.querySelector('.loading-overlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Show error message
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#dc3545'
    });
}

// Format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

// Add event listener for rejection message textarea
document.getElementById('rejectionMessage')?.addEventListener('input', updateCharacterCount);

// Batch operations
function selectAllLoans() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        if (selectAllCheckbox.checked) {
            selectedLoans.add(checkbox.value);
        } else {
            selectedLoans.clear();
        }
    });
    
    updateBatchInfo();
}

function toggleLoanSelection(checkbox) {
    if (checkbox.checked) {
        selectedLoans.add(checkbox.value);
    } else {
        selectedLoans.delete(checkbox.value);
    }
    
    updateBatchInfo();
}

function updateBatchInfo() {
    const batchBar = document.getElementById('batchActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectedAmount = document.getElementById('selectedAmount');
    
    if (selectedLoans.size > 0) {
        batchBar?.classList.add('active');
        
        let totalAmount = 0;
        selectedLoans.forEach(loanId => {
            const row = document.querySelector(`tr[data-loan-id="${loanId}"]`);
            if (row) {
                totalAmount += parseFloat(row.dataset.amount || 0);
            }
        });
        
        if (selectedCount) selectedCount.textContent = selectedLoans.size;
        if (selectedAmount) selectedAmount.textContent = formatCurrency(totalAmount);
    } else {
        batchBar?.classList.remove('active');
    }
}

function clearSelection() {
    selectedLoans.clear();
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBatchInfo();
}

function processBatchDisbursement() {
    if (selectedLoans.size === 0) {
        showError('Please select at least one loan');
        return;
    }
    
    Swal.fire({
        title: 'Batch Disbursement',
        text: `Process disbursement for ${selectedLoans.size} selected loans?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Process All',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processBatchRequest();
        }
    });
}

function processBatchRequest() {
    showLoading();
    
    fetch('/employee-loans/batch-disburse', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            loan_ids: Array.from(selectedLoans)
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: `${data.processed} loans processed successfully`,
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            showError(data.message || 'Failed to process batch disbursement');
        }
    })
    .catch(error => {
        hideLoading();
        showError('An error occurred while processing batch disbursement');
    });
}
</script>
@endpush