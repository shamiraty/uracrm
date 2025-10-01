
 <script>
    function triggerLoanApprovalSend(loanApplicationId) {
        Swal.fire({
            title: 'Confirm Loan Approval',
            text: "Are you sure you want to approve this loan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                sendLoanApprovalOtp(loanApplicationId);
            }
        });
    }


    function sendLoanApprovalOtp(loanApplicationId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/loans/${loanApplicationId}/send-otp-approve-loan`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(handleErrors)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            promptForLoanApprovalOTP(loanApplicationId);
        } else {
            Swal.fire('Failed', data.message || 'Failed to send OTP. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        Swal.fire('Error', 'An error occurred while sending OTP.', 'error');
    });
}

function handleErrors(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}

    function promptForLoanApprovalOTP(loanApplicationId) {
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
                return verifyLoanApprovalOTP(loanApplicationId, otp);
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire(
                    'Verified!',
                    'The loan has been successfully approved.',
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

    function verifyLoanApprovalOTP(loanApplicationId, otp) {
        return fetch(`/loans/${loanApplicationId}/verify-otp-approve-loan`, {
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
