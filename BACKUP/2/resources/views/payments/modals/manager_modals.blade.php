@foreach($payments as $payment)

<!-- View Details Modal -->
<div class="modal fade" id="viewModal-{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-eye me-2"></i>Payment Review Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-user me-1"></i>Member Information
                                </h6>
                                <div class="mb-2">
                                    <strong>Full Name:</strong>
                                    <span class="text-muted">{{ ucwords($payment->enquiry->full_name) }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Force Number:</strong>
                                    <span class="text-muted">{{ $payment->enquiry->force_no ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <span class="text-muted">{{ $payment->enquiry->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Check Number:</strong>
                                    <span class="badge bg-primary">{{ $payment->enquiry->check_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-success mb-3">
                                    <i class="fas fa-university me-1"></i>Payment Information
                                </h6>
                                <div class="mb-2">
                                    <strong>Bank Name:</strong>
                                    <span class="text-muted">{{ strtoupper($payment->enquiry->bank_name ?? 'N/A') }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Account Number:</strong>
                                    <span class="text-muted">{{ $payment->enquiry->account_number ?? 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Enquiry Type:</strong>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->enquiry->type)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="card border-0 bg-warning-soft">
                        <div class="card-body">
                            <h6 class="card-title text-warning mb-3">
                                <i class="fas fa-money-bill-wave me-1"></i>Payment Details
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Amount:</strong>
                                    <div class="h4 text-success mb-0">Tsh {{ number_format($payment->amount) }}</div>
                                </div>
                                <div class="col-md-4">
                                    <strong>Initiated By:</strong>
                                    <div class="text-dark">{{ $payment->initiatedBy->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $payment->initiatedBy->getRoleNames()->first() ?? '' }}</small>
                                </div>
                                <div class="col-md-4">
                                    <strong>Date Initiated:</strong>
                                    <div class="text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</div>
                                    <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <a href="{{ route('enquiries.show', $payment->enquiry->id) }}" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i>View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Approve Payment Modal with OTP -->
<div class="modal fade" id="approveModal-{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Approve Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-3"></i>
                    <div>
                        <strong>Payment Approval</strong><br>
                        <small>Approve payment of <strong>Tsh {{ number_format($payment->amount) }}</strong> for {{ ucwords($payment->enquiry->full_name) }}</small>
                    </div>
                </div>

                <!-- Step 1: Request OTP -->
                <div id="otpRequestSection-{{ $payment->id }}">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                            <h6>Security Verification Required</h6>
                            <p class="text-muted">Click below to send OTP to your registered phone number</p>
                        </div>
                        <button type="button" class="btn btn-success" onclick="sendSingleOTP({{ $payment->id }})">
                            <i class="fas fa-mobile-alt me-1"></i>Send OTP
                        </button>
                    </div>
                </div>

                <!-- Step 2: Enter OTP -->
                <div id="otpVerifySection-{{ $payment->id }}" style="display: none;">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-key fa-2x text-success mb-3"></i>
                            <h6>Enter Verification Code</h6>
                            <p class="text-muted">Enter the 6-digit code sent to your phone</p>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex gap-2 justify-content-center mb-3">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center otp-input-single" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                </div>
                                <input type="hidden" id="singleOTP-{{ $payment->id }}">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="verifySingleOTP({{ $payment->id }})">
                            <i class="fas fa-check me-1"></i>Verify & Approve Payment
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

<!-- Reject Payment Modal -->
<div class="modal fade" id="rejectModal-{{ $payment->id }}" tabindex="-1">
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
                        <small>Reject payment of <strong>Tsh {{ number_format($payment->amount) }}</strong> for {{ ucwords($payment->enquiry->full_name) }}</small>
                    </div>
                </div>

                <form id="singleRejectForm-{{ $payment->id }}" action="{{ route('payment.reject', $payment->id) }}" method="POST">
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
                <button type="button" class="btn btn-danger" onclick="submitSingleReject({{ $payment->id }})">
                    <i class="fas fa-times-circle me-1"></i>Reject Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loan Application Approve Modal -->
@if($payment->enquiry->type === 'loan_application')
<div class="modal fade" id="approveLoanModal-{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Approve Loan Application
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-3"></i>
                    <div>
                        <strong>Loan Application Approval</strong><br>
                        <small>Approve loan application for {{ ucwords($payment->enquiry->full_name) }}</small>
                    </div>
                </div>

                <form id="approveLoanForm-{{ $payment->id }}">
                    @csrf
                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                    <input type="hidden" name="enquiry_id" value="{{ $payment->enquiry->id }}">
                </form>

                <!-- Step 1: Request OTP -->
                <div id="loanOtpRequestSection-{{ $payment->id }}">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                            <h6>Security Verification Required</h6>
                            <p class="text-muted">Click below to send OTP to your registered phone number</p>
                        </div>
                        <button type="button" class="btn btn-success" onclick="sendLoanOTP({{ $payment->id }})">
                            <i class="fas fa-mobile-alt me-1"></i>Send OTP
                        </button>
                    </div>
                </div>

                <!-- Step 2: Enter OTP -->
                <div id="loanOtpVerifySection-{{ $payment->id }}" style="display: none;">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-key fa-2x text-success mb-3"></i>
                            <h6>Enter Verification Code</h6>
                            <p class="text-muted">Enter the 6-digit code sent to your phone</p>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex gap-2 justify-content-center mb-3">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center loan-otp-input" data-payment="{{ $payment->id }}" maxlength="1" style="width: 50px;">
                                </div>
                                <input type="hidden" id="loanOTP-{{ $payment->id }}">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="verifyLoanOTP({{ $payment->id }})">
                            <i class="fas fa-check me-1"></i>Verify & Approve Loan
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

<!-- Loan Application Reject Modal -->
<div class="modal fade" id="rejectLoanModal-{{ $payment->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-times-circle me-2"></i>Reject Loan Application
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>
                        <strong>Loan Application Rejection</strong><br>
                        <small>Reject loan application for {{ ucwords($payment->enquiry->full_name) }}</small>
                    </div>
                </div>

                <form id="rejectLoanForm-{{ $payment->id }}">
                    @csrf
                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                    <input type="hidden" name="enquiry_id" value="{{ $payment->enquiry->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment me-1"></i>Rejection Reason
                        </label>
                        <textarea class="form-control" name="reason" rows="4" required
                                  placeholder="Please provide detailed reason for rejection..."></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>This reason will be sent to the applicant
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="rejectLoanApplication({{ $payment->id }})">
                    <i class="fas fa-times-circle me-1"></i>Reject Loan Application
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endforeach

<!-- Bulk Approve Modal with OTP -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-check-double me-2"></i>Bulk Approve Payments
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-3"></i>
                    <div>
                        <strong>Bulk Approval</strong><br>
                        <small>Approve <span id="bulkApproveCount">0</span> selected payments</small>
                    </div>
                </div>

                <!-- Step 1: Request OTP -->
                <div id="bulkOtpRequestSection">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                            <h6>Security Verification Required</h6>
                            <p class="text-muted">Click below to send OTP to your registered phone number for bulk approval</p>
                        </div>
                        <button type="button" class="btn btn-success" onclick="sendBulkOTP()">
                            <i class="fas fa-mobile-alt me-1"></i>Send OTP for Bulk Approval
                        </button>
                    </div>
                </div>

                <!-- Step 2: Enter OTP -->
                <div id="bulkOtpVerifySection" style="display: none;">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-key fa-2x text-success mb-3"></i>
                            <h6>Enter Verification Code</h6>
                            <p class="text-muted">Enter the 6-digit code sent to your phone</p>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex gap-2 justify-content-center mb-3">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                    <input type="text" class="form-control text-center bulk-otp-input" maxlength="1" style="width: 50px;">
                                </div>
                                <input type="hidden" id="bulkOTP">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="verifyBulkOTP()">
                            <i class="fas fa-check me-1"></i>Verify & Approve All Selected
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
// OTP Input handling for single and bulk approvals
document.addEventListener('DOMContentLoaded', function() {
    // Handle single OTP input auto-advance
    document.querySelectorAll('.otp-input-single').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < inputs.length - 1) {
                const nextInput = inputs[index + 1];
                if (nextInput && nextInput.dataset.payment === this.dataset.payment) {
                    nextInput.focus();
                }
            }

            // Combine all OTP inputs for this payment
            const paymentId = this.dataset.payment;
            const paymentInputs = document.querySelectorAll(`.otp-input-single[data-payment="${paymentId}"]`);
            const fullOTP = Array.from(paymentInputs).map(i => i.value).join('');
            document.getElementById(`singleOTP-${paymentId}`).value = fullOTP;
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                const prevInput = inputs[index - 1];
                if (prevInput && prevInput.dataset.payment === this.dataset.payment) {
                    prevInput.focus();
                }
            }
        });
    });

    // Handle loan OTP input auto-advance
    document.querySelectorAll('.loan-otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < inputs.length - 1) {
                const nextInput = inputs[index + 1];
                if (nextInput && nextInput.dataset.payment === this.dataset.payment) {
                    nextInput.focus();
                }
            }

            // Combine all OTP inputs for this loan
            const paymentId = this.dataset.payment;
            const paymentInputs = document.querySelectorAll(`.loan-otp-input[data-payment="${paymentId}"]`);
            const fullOTP = Array.from(paymentInputs).map(i => i.value).join('');
            document.getElementById(`loanOTP-${paymentId}`).value = fullOTP;
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                const prevInput = inputs[index - 1];
                if (prevInput && prevInput.dataset.payment === this.dataset.payment) {
                    prevInput.focus();
                }
            }
        });
    });

    // Handle bulk OTP input auto-advance
    document.querySelectorAll('.bulk-otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Combine all bulk OTP inputs
            const fullOTP = Array.from(inputs).map(i => i.value).join('');
            document.getElementById('bulkOTP').value = fullOTP;
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
});

// Send OTP for single approval
function sendSingleOTP(paymentId) {
    fetch(`/payment/send-otp-approve/${paymentId}`, {
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

// Verify OTP for single approval
function verifySingleOTP(paymentId) {
    const otp = document.getElementById(`singleOTP-${paymentId}`).value;
    const button = document.querySelector(`button[onclick="verifySingleOTP(${paymentId})"]`);

    if (otp.length !== 6) {
        showAlert('Please enter complete 6-digit OTP', 'warning');
        return;
    }

    // Show loading state
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Verifying OTP...';
    button.disabled = true;

    fetch(`/payment/verify-otp-approve/${paymentId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ otp: otp })
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;

        if (data.success) {
            showAlert('Payment approved successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('OTP verification failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;
        console.error('Error:', error);
        showAlert('Error verifying OTP', 'error');
    });
}

// Send OTP for bulk approval
function sendBulkOTP() {
    fetch('/payment/send-bulk-otp', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('bulkOtpRequestSection').style.display = 'none';
            document.getElementById('bulkOtpVerifySection').style.display = 'block';
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

// Verify OTP for bulk approval
function verifyBulkOTP() {
    const otp = document.getElementById('bulkOTP').value;
    const button = document.querySelector('button[onclick="verifyBulkOTP()"]');

    if (otp.length !== 6) {
        showAlert('Please enter complete 6-digit OTP', 'warning');
        return;
    }

    // Show loading state
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing Approvals...';
    button.disabled = true;

    fetch('/payment/bulk-approve', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            payment_ids: selectedPayments,
            otp: otp
        })
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;

        if (data.success) {
            showAlert('Payments approved successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('Bulk approval failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;
        console.error('Error:', error);
        showAlert('Error processing bulk approval', 'error');
    });
}

// Submit single reject
function submitSingleReject(paymentId) {
    const form = document.getElementById(`singleRejectForm-${paymentId}`);
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

    fetch('/payment/manager-bulk-reject', {
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

// Send OTP for loan approval
function sendLoanOTP(paymentId) {
    fetch(`/payment/send-loan-otp/${paymentId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`loanOtpRequestSection-${paymentId}`).style.display = 'none';
            document.getElementById(`loanOtpVerifySection-${paymentId}`).style.display = 'block';
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

// Verify OTP for loan approval
function verifyLoanOTP(paymentId) {
    const otp = document.getElementById(`loanOTP-${paymentId}`).value;
    const button = document.querySelector(`button[onclick="verifyLoanOTP(${paymentId})"]`);

    if (otp.length !== 6) {
        showAlert('Please enter complete 6-digit OTP', 'warning');
        return;
    }

    // Show loading state
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Verifying OTP...';
    button.disabled = true;

    const enquiryId = document.querySelector(`#approveLoanForm-${paymentId} input[name="enquiry_id"]`).value;

    fetch(`/payment/verify-loan-otp/${paymentId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            otp: otp,
            enquiry_id: enquiryId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;

        if (data.success) {
            showAlert('Loan application approved successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('OTP verification failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;
        console.error('Error:', error);
        showAlert('Error verifying OTP', 'error');
    });
}

// Reject loan application
function rejectLoanApplication(paymentId) {
    const form = document.getElementById(`rejectLoanForm-${paymentId}`);
    const reason = form.querySelector('textarea[name="reason"]').value;
    const enquiryId = form.querySelector('input[name="enquiry_id"]').value;
    const button = document.querySelector(`button[onclick="rejectLoanApplication(${paymentId})"]`);

    if (!reason.trim()) {
        showAlert('Please provide rejection reason', 'warning');
        return;
    }

    // Show loading state
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
    button.disabled = true;

    fetch(`/payment/reject-loan-application`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            payment_id: paymentId,
            enquiry_id: enquiryId,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;

        if (data.success) {
            showAlert('Loan application rejected successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('Failed to reject loan application: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Reset button state
        button.innerHTML = originalHtml;
        button.disabled = false;
        console.error('Error:', error);
        showAlert('Error rejecting loan application', 'error');
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

.otp-input-single, .bulk-otp-input, .loan-otp-input {
    font-size: 1.25rem;
    font-weight: bold;
    text-align: center;
}

.otp-input-single:focus, .bulk-otp-input:focus, .loan-otp-input:focus {
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