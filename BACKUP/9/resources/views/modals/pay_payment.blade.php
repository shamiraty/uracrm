<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function triggerOtpSend(paymentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to proceed with the payment?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                sendOtp(paymentId);
            }
        });
    }

    function sendOtp(paymentId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/send-otp-pay/' + paymentId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ paymentId: paymentId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                promptForOTP(paymentId);
            } else {
                Swal.fire(
                    'Failed',
                    'Failed to send OTP. Please try again.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Error sending OTP:', error);
            Swal.fire(
                'Error',
                'An error occurred while sending OTP.',
                'error'
            );
        });
    }

    function promptForOTP(paymentId) {
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
                return verifyOTP(paymentId, otp);
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire(
                    'Verified!',
                    'Your payment has been successfully verified.',
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

    function verifyOTP(paymentId, otp) {
        return fetch('/verify-otp-pay/' + paymentId, {
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
