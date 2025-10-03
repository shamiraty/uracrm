<script>
    function triggerApprovalSend(paymentId) {
        Swal.fire({
            title: 'Confirm Approval',
            text: "Are you sure you want to approve this payment?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
        }).then((result) => {
            if (result.isConfirmed) {
                sendApprovalOtp(paymentId);
            }
        });
    }

    function sendApprovalOtp(paymentId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/send-otp-approve/${paymentId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                promptForApprovalOTP(paymentId);
            } else {
                Swal.fire('Failed', 'Failed to send OTP. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            Swal.fire('Error', 'An error occurred while sending OTP.', 'error');
        });
    }

    function promptForApprovalOTP(paymentId) {
        Swal.fire({
            title: 'Enter your OTP',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Verify OTP',
            showLoaderOnConfirm: true,
            preConfirm: (otp) => {
                return verifyApprovalOTP(paymentId, otp);
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire(
                    'Verified!',
                    'The payment has been successfully approved.',
                    'success'
                );
            } else if (result.isDismissed || !result.value.success) {
                Swal.fire(
                    'Failed',
                    'OTP verification failed. Please try again.',
                    'error'
                );
            }
        });
    }

    function verifyApprovalOTP(paymentId, otp) {
        return fetch(`/verify-otp-approve/${paymentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ otp: otp })
        }).then(response => {
            if (!response.ok) {
                throw new Error('Server responded with a status: ' + response.status);
            }
            return response.json();
        }).catch(error => {
            console.error('Error verifying OTP:', error);
            throw error;
        });
    }
    </script>

