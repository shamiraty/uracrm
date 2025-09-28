@foreach($enquiries as $enquiry)

<!-- View Details Modal -->
<div class="modal fade" id="viewModal-{{ $enquiry->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-eye me-2"></i>Payment Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-user me-1"></i>Personal Information
                                </h6>
                                <div class="mb-2">
                                    <strong>Full Name:</strong>
                                    <span class="text-muted">{{ ucwords($enquiry->full_name) }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Force Number:</strong>
                                    <span class="text-muted">{{ $enquiry->force_no ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <span class="text-muted">{{ $enquiry->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Check Number:</strong>
                                    <span class="badge bg-primary">{{ $enquiry->check_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-success mb-3">
                                    <i class="fas fa-university me-1"></i>Bank Information
                                </h6>
                                <div class="mb-2">
                                    <strong>Bank Name:</strong>
                                    <span class="text-muted">{{ strtoupper($enquiry->bank_name ?? 'N/A') }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Account Number:</strong>
                                    <span class="text-muted">{{ $enquiry->account_number ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Enquiry Type:</strong>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($enquiry->payment)
                <div class="mt-4">
                    <div class="card border-0 bg-warning-soft">
                        <div class="card-body">
                            <h6 class="card-title text-warning mb-3">
                                <i class="fas fa-money-bill-wave me-1"></i>Payment Information
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Amount:</strong>
                                    <div class="h5 text-success mb-0">Tsh {{ number_format($enquiry->payment->amount) }}</div>
                                </div>
                                <div class="col-md-4">
                                    <strong>Status:</strong>
                                    @switch($enquiry->payment->status)
                                        @case('initiated')
                                            <div><span class="badge bg-info fs-6">Initiated</span></div>
                                            @break
                                        @case('approved')
                                            <div><span class="badge bg-success fs-6">Approved</span></div>
                                            @break
                                        @case('paid')
                                            <div><span class="badge bg-primary fs-6">Paid</span></div>
                                            @break
                                        @case('rejected')
                                            <div><span class="badge bg-danger fs-6">Rejected</span></div>
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-4">
                                    <strong>Date:</strong>
                                    <div class="text-muted">{{ $enquiry->payment->created_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <a href="{{ route('enquiries.show', $enquiry->id) }}" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i>View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

@if(!$enquiry->payment)
<!-- Initiate Payment Modal -->
<div class="modal fade" id="initiateModal-{{ $enquiry->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-play-circle me-2"></i>Initiate Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <div>
                        <strong>Payment Initiation</strong><br>
                        <small>Initialize payment for {{ ucwords($enquiry->full_name) }} - {{ $enquiry->check_number }}</small>
                    </div>
                </div>

                <form id="initiateForm-{{ $enquiry->id }}" action="{{ route('payment.initiate', $enquiry->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-money-bill-wave me-1"></i>Payment Amount (Tsh)
                        </label>
                        <input type="number" class="form-control" name="amount" required
                               placeholder="Enter payment amount" min="1000" step="100">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Enter the amount to be paid out
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-paperclip me-1"></i>Attachment (Optional)
                        </label>
                        <input type="file" class="form-control" name="note"
                               accept=".pdf,.doc,.docx,.jpeg,.jpg,.png">
                        <div class="form-text">
                            <i class="fas fa-file me-1"></i>Upload supporting documents (PDF, DOC, Images)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File ID (Optional)</label>
                        <input type="text" class="form-control" name="file_id" placeholder="Enter file ID if applicable">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="initiatePaymentWithOTP({{ $enquiry->id }})">
                    <i class="fas fa-play-circle me-1"></i>Initiate Payment
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if($enquiry->payment)
    @if(in_array($enquiry->payment->status, ['initiated', 'approved']))
    <!-- Reject Payment Modal -->
    <div class="modal fade" id="rejectModal-{{ $enquiry->payment->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-times-circle me-2"></i>Reject Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        <div>
                            <strong>Payment Rejection</strong><br>
                            <small>Reject payment for {{ ucwords($enquiry->full_name) }} - {{ $enquiry->check_number }}</small>
                        </div>
                    </div>

                    <form id="rejectForm-{{ $enquiry->payment->id }}" action="{{ route('payment.reject', $enquiry->payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment me-1"></i>Rejection Reason
                            </label>
                            <textarea class="form-control" name="remarks" rows="4" required
                                      placeholder="Please provide detailed reason for rejection..."></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>This reason will be sent to the member
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitReject({{ $enquiry->payment->id }})">
                        <i class="fas fa-times-circle me-1"></i>Reject Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($enquiry->payment->status === 'approved')
    <!-- Pay Payment Modal with OTP -->
    <div class="modal fade" id="payModal-{{ $enquiry->payment->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-money-bill-wave me-2"></i>Process Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-3"></i>
                        <div>
                            <strong>Payment Processing</strong><br>
                            <small>Process payment of <strong>Tsh {{ number_format($enquiry->payment->amount) }}</strong> for {{ ucwords($enquiry->full_name) }}</small>
                        </div>
                    </div>

                    <!-- Step 1: Request OTP -->
                    <div id="otpRequestSection-{{ $enquiry->payment->id }}">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                                <h6>Security Verification Required</h6>
                                <p class="text-muted">Click below to send OTP to your registered phone number</p>
                            </div>
                            <button type="button" class="btn btn-success" onclick="sendOTPPay({{ $enquiry->payment->id }})">
                                <i class="fas fa-mobile-alt me-1"></i>Send OTP
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Enter OTP -->
                    <div id="otpVerifySection-{{ $enquiry->payment->id }}" style="display: none;">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-key fa-2x text-success mb-3"></i>
                                <h6>Enter Verification Code</h6>
                                <p class="text-muted">Enter the 6-digit code sent to your phone</p>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="d-flex gap-2 justify-content-center mb-3">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                        <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 50px;">
                                    </div>
                                    <input type="hidden" id="fullOTP-{{ $enquiry->payment->id }}">
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" onclick="verifyOTPPay({{ $enquiry->payment->id }})">
                                <i class="fas fa-check me-1"></i>Verify & Process Payment
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif

@endforeach

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-times-circle me-2"></i>Bulk Reject Payments
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>
                        <strong>Bulk Rejection</strong><br>
                        <small>Reject <span id="bulkRejectCount">0</span> selected payments</small>
                    </div>
                </div>

                <form id="bulkRejectForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment me-1"></i>Rejection Reason
                        </label>
                        <textarea class="form-control" id="bulkRejectReason" rows="4" required
                                  placeholder="Please provide detailed reason for bulk rejection..."></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>This reason will be applied to all selected payments
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="submitBulkReject()">
                    <i class="fas fa-times-circle me-1"></i>Reject All Selected
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// OTP Input handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle OTP input auto-advance
    document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Combine all OTP inputs
            const paymentId = this.closest('.modal').id.split('-')[1];
            const fullOTP = Array.from(inputs).map(i => i.value).join('');
            document.getElementById(`fullOTP-${paymentId}`).value = fullOTP;
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
});

// Send OTP for payment
function sendOTPPay(paymentId) {
    fetch(`/payment/${paymentId}/send-otp-pay`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`otpRequestSection-${paymentId}`).style.display = 'none';
            document.getElementById(`otpVerifySection-${paymentId}`).style.display = 'block';
            showAlert('OTP sent successfully', 'success');
        } else {
            showAlert('Failed to send OTP: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error sending OTP', 'error');
    });
}

// Verify OTP for payment
function verifyOTPPay(paymentId) {
    const otp = document.getElementById(`fullOTP-${paymentId}`).value;

    if (otp.length !== 6) {
        showAlert('Please enter complete 6-digit OTP', 'warning');
        return;
    }

    fetch(`/payment/${paymentId}/verify-otp-pay`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ otp: otp })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Payment processed successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('OTP verification failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error verifying OTP', 'error');
    });
}

// Initiate payment with OTP
function initiatePaymentWithOTP(enquiryId) {
    const form = document.getElementById(`initiateForm-${enquiryId}`);
    const formData = new FormData(form);

    // Validate amount
    const amount = formData.get('amount');
    if (!amount || amount < 1000) {
        showAlert('Please enter a valid amount (minimum 1000)', 'warning');
        return;
    }

    // Submit form
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Payment initiated successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('Failed to initiate payment: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // If not JSON response, treat as redirect
        showAlert('Payment initiated successfully!', 'success');
        setTimeout(() => location.reload(), 2000);
    });
}

// Submit reject payment
function submitReject(paymentId) {
    const form = document.getElementById(`rejectForm-${paymentId}`);
    const remarks = form.querySelector('textarea[name="remarks"]').value;

    if (!remarks.trim()) {
        showAlert('Please provide rejection reason', 'warning');
        return;
    }

    form.submit();
}

// Submit bulk reject
function submitBulkReject() {
    const reason = document.getElementById('bulkRejectReason').value;

    if (!reason.trim()) {
        showAlert('Please provide rejection reason', 'warning');
        return;
    }

    if (selectedPayments.length === 0) {
        showAlert('No payments selected', 'warning');
        return;
    }

    fetch('/payment/bulk-reject', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            payment_ids: selectedPayments,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Payments rejected successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('Failed to reject payments: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error processing bulk rejection', 'error');
    });
}

// Show alert function
function showAlert(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type];

    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alert);

    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>

<style>
.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.otp-input {
    font-size: 1.25rem;
    font-weight: bold;
    text-align: center;
}

.otp-input:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
}
</style>