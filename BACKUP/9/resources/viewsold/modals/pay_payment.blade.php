{{--
<!-- Modal for Paying Payment -->
<div class="modal fade" id="payPaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="payPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white text-uppercase" id="payPaymentModalLabel">Pay Payment for {{ $enquiry->full_name }}</h6>
            </div>
            <form action="{{ route('payment.pay', ['paymentId' => $enquiry->payment->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                <div class="card">
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
                <strong>Full Name:</strong> <span>{{ $enquiry->full_name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Check Number:</strong> <span>{{ $enquiry->check_number }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Bank Account:</strong> <span>{{ $enquiry->account_number }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Bank Name:</strong> <span>{{ $enquiry->bank_name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Phone Number:</strong> <span>{{ $enquiry->phone }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Amount to Pay:</strong> <span>${{ number_format($enquiry->payment->amount, 2) }}</span>
            </li>
        </ul>
    </div>
</div>

<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
									<div class="d-flex align-items-center">
										<div class="font-35 text-dark"><i class='bx bx-info-circle'></i>
										</div>
										<div class="ms-3">
											<h6 class="mb-0 text-dark">Warning Alerts</h6>
											<div class="text-dark">Are you sure you want to complete this payment?</div>
										</div>
									</div>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
                </div>
                <div class=" modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="dispatchOtp({{ $enquiry->payment->id }})">Send OTP and Pay</button>
                </div>
            </form>
        </div>
    </div>
  </div>

<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpVerificationModalLabel">OTP Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter the OTP sent to your mobile number.</p>
                <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="verifyOtp({{ $enquiry->payment->id }})">Verify OTP</button>
            </div>
        </div>
    </div>
</div>



    <script>
        function dispatchOtp(paymentId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/payments/${paymentId}/dispatch-otp`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('OTP sent to your phone.');
                    // Close the payment modal
                    const payPaymentModal = bootstrap.Modal.getInstance(document.getElementById('payPaymentModal-' + paymentId));
                    if (payPaymentModal) {
                        payPaymentModal.hide();
                    }
                    // Show the OTP verification modal
                    const otpVerificationModal = new bootstrap.Modal(document.getElementById('otpVerificationModal'));
                    otpVerificationModal.show();
                } else {
                    alert('Failed to send OTP. Please try again: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error during OTP dispatch:', error);
                alert('Error during OTP dispatch: ' + error.message);
            });
        }

        function verifyOtp(paymentId) {
            const otpInput = document.getElementById('otpInput').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/payments/${paymentId}/verify-otp-and-pay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ otp: otpInput })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const otpVerificationModal = new bootstrap.Modal(document.getElementById('otpVerificationModal'));
                    otpVerificationModal.hide();
                    alert('Payment has been successfully completed.');
                    window.location.href = '/my-enquiries'; // Redirect or update UI
                } else {
                    alert('Invalid or expired OTP. Please try again: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error verifying OTP:', error);
                alert('Error verifying OTP: ' + error.message);
            });
        }
    </script>

 --}}


 <!-- Modal for Paying Payment -->
<div class="modal fade" id="payPaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="payPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white text-uppercase" id="payPaymentModalLabel">Pay Payment for {{ $enquiry->full_name }}</h6>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Full Name:</strong> <span>{{ $enquiry->full_name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Check Number:</strong> <span>{{ $enquiry->check_number }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Bank Account:</strong> <span>{{ $enquiry->account_number }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Bank Name:</strong> <span>{{ $enquiry->bank_name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Phone Number:</strong> <span>{{ $enquiry->phone }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Amount to Pay:</strong> <span>${{ number_format($enquiry->payment->amount, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-dark"><i class='bx bx-info-circle'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-dark">Warning Alerts</h6>
                                <div class="text-dark">Are you sure you want to complete this payment?</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="dispatchOtp({{ $enquiry->payment->id }})">Send OTP and Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpVerificationModalLabel">OTP Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter the OTP sent to your mobile number.</p>
                <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="verifyOtp({{ $enquiry->payment->id }})">Verify OTP</button>
            </div>
        </div>
    </div>
</div>

<script>
    function dispatchOtp(paymentId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/payments/${paymentId}/dispatch-otp`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('OTP sent to your phone.');
                const payPaymentModal = bootstrap.Modal.getInstance(document.getElementById('payPaymentModal-{{ $enquiry->payment->id }}'));
                if (payPaymentModal) {
                    payPaymentModal.hide();  // Correctly close the payment modal
                }
                const otpVerificationModal = new bootstrap.Modal(document.getElementById('otpVerificationModal'));
                otpVerificationModal.show();
            } else {
                alert('Failed to send OTP. Please try again: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error during OTP dispatch:', error);
            alert('Error during OTP dispatch: ' + error.message);
        });
    }

    function verifyOtp(paymentId) {
        const otpInput = document.getElementById('otpInput').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/payments/${paymentId}/verify-otp-and-pay`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ otp: otpInput })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const otpVerificationModal = bootstrap.Modal.getInstance(document.getElementById('otpVerificationModal'));
                otpVerificationModal.hide();  // Correctly close the OTP modal
                alert('Payment has been successfully completed.');
                window.location.href = '/my-enquiries';  // Redirect or update UI
            } else {
                alert('Invalid or expired OTP. Please try again: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error verifying OTP:', error);
            alert('Error verifying OTP: ' + error.message);
        });
    }
</script>
