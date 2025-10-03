{{-- >
<div class="modal fade" id="approvePaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title bg- text-uppercase text-white" id="approvePaymentModalLabel">Approve Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.approve', ['paymentId' => $enquiry->payment->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                <ul class="list-group">
    <li class="list-group-item d-flex justify-content-between">
        <strong>Full Name:</strong>
        <span>{{ $enquiry->full_name }}</span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <strong>Check Number:</strong>
        <span>{{ $enquiry->check_number }}</span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <strong>Bank Account:</strong>
        <span>{{ $enquiry->account_number }}</span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <strong>Bank Name:</strong>
        <span>{{ $enquiry->bank_name }}</span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <strong>Phone Number:</strong>
        <span>{{ $enquiry->phone }}</span>
    </li>
    <li class="list-group-item d-flex justify-content-between">
        <strong>Amount to Approve:</strong>
        <span>${{ number_format($enquiry->payment->amount, 2) }}</span>
    </li>
</ul>

                    <div class="alert alert-danger mt-2">Are you sure you want to approve this payment?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<!-- Modal for Approving Payment -->
{{-- <div class="modal fade" id="approvePaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-uppercase text-white" id="approvePaymentModalLabel">Approve Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.approve', ['paymentId' => $enquiry->payment->id]) }}" method="POST"onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body row">
                    <!-- Enquiry Details Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <h5 class="card-header">Enquiry Details</h5>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Full Name:</strong>
                                        <span>{{ $enquiry->full_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Check Number:</strong>
                                        <span>{{ $enquiry->check_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Account:</strong>
                                        <span>{{ $enquiry->account_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Name:</strong>
                                        <span>{{ $enquiry->bank_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Phone Number:</strong>
                                        <span>{{ $enquiry->phone }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Amount to Approve:</strong>
                                        <span>TZS {{ number_format($enquiry->payment->amount, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

<div class="col-md-6">
    <div class="card">
    <h5 class="card-header">Note File</h5>
        <div class="card-body">
            @if($enquiry->payment->note_path)
                <div class="note-file-container">
                    <!-- Display an embedded PDF or a link to download other file types -->
                    @if(pathinfo($enquiry->payment->note_path, PATHINFO_EXTENSION) === 'pdf')
                        <embed src="{{ asset('/' . $enquiry->payment->note_path) }}" type="application/pdf" style="width:100%; height:400px;">
                    @else
                        <a href="{{ asset('/' . $enquiry->payment->note_path) }}" target="_blank">Download Note</a>
                    @endif
                </div>
            @else
                <p>No file attached.</p>
            @endif
        </div>
    </div>
</div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"id="submit-button-{{ $enquiry->id }}">Approve</button>
                    <!-- Modal Approve Button -->
<button type="button" class="btn btn-success btn-sm" onclick="triggerOtpSend({{ $enquiry->payment->id }})">Approve</button>

                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for OTP Verification -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpModalLabel">OTP Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter the OTP sent to your phone.</p>
                <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="verifyOTP()">Verify OTP</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelector('#approvePaymentModal-{{ $enquiry->payment->id }} form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent direct form submission
        showOTPPrompt(); // Show OTP modal to enter OTP
    });

    function showOTPPrompt() {
        new bootstrap.Modal(document.getElementById('otpModal')).show();
    }

    function verifyOTP() {
        const otpInput = document.getElementById('otpInput').value;
        const paymentId = '{{ $enquiry->payment->id }}'; // Ensure this ID is accurate

        fetch('/verify-otp/' + paymentId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ otp: otpInput })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otpModal').modal('hide');
                alert('OTP verified successfully. Approving payment.');
                // Optionally, submit the form or redirect as necessary
            } else {
                alert('Invalid OTP. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error verifying OTP:', error);
            alert('Error verifying OTP.');
        });
    }
    </script>

<script>
    function triggerOtpSend(paymentId) {
        // AJAX call to backend to initiate OTP sending
        fetch('/send-otp/' + paymentId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  // CSRF token for security
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('OTP sent to your phone.');
                // Open OTP modal for user input
                new bootstrap.Modal(document.getElementById('otpModal')).show();
            } else {
                alert('Failed to send OTP. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            alert('Error during OTP dispatch.');
        });
    }
    </script> --}}

    <!-- Modal for Approving Payment -->
<div class="modal fade" id="approvePaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-uppercase text-white" id="approvePaymentModalLabel">Approve Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approvePaymentForm-{{ $enquiry->payment->id }}" action="{{ route('payment.approve', ['paymentId' => $enquiry->payment->id]) }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <!-- Enquiry Details Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <h5 class="card-header">Enquiry Details</h5>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Full Name:</strong>
                                        <span>{{ $enquiry->full_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Check Number:</strong>
                                        <span>{{ $enquiry->check_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Account:</strong>
                                        <span>{{ $enquiry->account_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Name:</strong>
                                        <span>{{ $enquiry->bank_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Phone Number:</strong>
                                        <span>{{ $enquiry->phone }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Amount to Approve:</strong>
                                        <span>TZS {{ number_format($enquiry->payment->amount, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <h5 class="card-header">Note File</h5>
                            <div class="card-body">
                                @if($enquiry->payment->note_path)
                                    <div class="note-file-container">
                                        @if(pathinfo($enquiry->payment->note_path, PATHINFO_EXTENSION) === 'pdf')
                                            <embed src="{{ asset($enquiry->payment->note_path) }}" type="application/pdf" style="width:100%; height:400px;">
                                        @else
                                            <a href="{{ asset($enquiry->payment->note_path) }}" target="_blank">Download Note</a>
                                        @endif
                                    </div>
                                @else
                                    <p>No file attached.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="triggerOtpSend({{ $enquiry->payment->id }})">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal for OTP Verification -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpModalLabel">OTP Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter the OTP sent to your phone.</p>
                <input type="text" id="otpInput" class="form-control" placeholder="Enter OTP">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="verifyOTP({{ $enquiry->payment->id }})">Verify OTP</button>
            </div>
        </div>
    </div>
</div>

<script>
//     function triggerOtpSend(paymentId) {
//     const csrfToken = document.querySelector('meta[name="csrf-token"]');

//     if (!csrfToken) {
//         console.error('CSRF token not found');
//         return;
//     }

//     fetch('/send-otp/' + paymentId, {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': csrfToken.getAttribute('content'), // This line should now be safe
//             'Content-Type': 'application/json'
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             console.log('OTP sent to your phone.');
//             new bootstrap.Modal(document.getElementById('otpModal')).show();
//         } else {
//             alert('Failed to send OTP. Please try again.');
//         }
//     })
//     .catch(error => {
//         console.error('Error sending OTP:', error);
//         alert('Error during OTP dispatch.');
//     });
// }



// function verifyOTP(paymentId) {
//     const otpInput = document.getElementById('otpInput').value;
//     const otpModalElement = document.getElementById('otpModal');
//     const otpModal = new bootstrap.Modal(otpModalElement);

//     fetch('/verify-otp/' + paymentId, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify({ otp: otpInput })
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Server responded with a status: ' + response.status);
//         }
//         return response.json();
//     })
//     .then(data => {
//         if (data.success) {
//             otpModal.hide();
//             alert('OTP verified successfully. Approving payment.');
//             window.location.href = '/my-enquiries'; // Redirect as needed
//         } else {
//             throw new Error(data.message || 'Invalid OTP.');
//         }
//     })
//     .catch(error => {
//         console.error('Error verifying OTP:', error);
//         alert('Error verifying OTP: ' + error.message);
//     });
// }

function triggerOtpSend(paymentId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    fetch('/send-otp/' + paymentId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('OTP sent to your phone.');
            new bootstrap.Modal(document.getElementById('otpModal')).show();
        } else {
            alert('Failed to send OTP. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        alert('Error during OTP dispatch.');
    });
}

function verifyOTP(paymentId) {
    const otpInput = document.getElementById('otpInput').value.trim(); // Trim any leading or trailing spaces
    console.log("Captured OTP: ", otpInput); // Log the captured OTP to console for debugging

    // Check the length and content of the OTP input
    if (!otpInput || otpInput.length !== 6 || !/^\d{6}$/.test(otpInput)) {
        alert('Please enter a valid 6-digit OTP.');
        return; // Stop the function if the OTP is not exactly 6 digits
    }

    const otpModalElement = document.getElementById('otpModal');
    const otpModal = new bootstrap.Modal(otpModalElement);

    fetch('/verify-otp/' + paymentId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ otp: otpInput })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            otpModal.hide();
            alert('OTP verified successfully. Approving payment.');
            window.location.href = '/my-enquiries'; // Redirect as needed
        } else {
            throw new Error(data.message || 'Invalid OTP.');
        }
    })
    .catch(error => {
        console.error('Error verifying OTP:', error);
        alert('Error verifying OTP: ' + error.message);
    });
}


</script>
