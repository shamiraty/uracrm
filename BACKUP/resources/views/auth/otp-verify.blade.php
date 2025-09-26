<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">  <!-- Ensure this line is here -->
    <title>Verify OTP</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Enter OTP',
                text: 'Please enter the OTP sent to your phone.',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Verify',
                showLoaderOnConfirm: true,
                preConfirm: (otp) => {
                    return fetch('/otp-confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ otp: otp })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/dashboard'; // Redirect to dashboard or home page
                        } else {
                            throw new Error(data.message || 'OTP verification failed.');
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Enter OTP',
                text: 'Please enter the OTP sent to your phone.',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Verify',
                cancelButtonText: 'Cancel', // Ensure there's a cancel button text
                showLoaderOnConfirm: true,
                preConfirm: (otp) => {
                    return fetch('/otp-confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ otp: otp })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/dashboard'; // Redirect to dashboard or home page
                        } else {
                            throw new Error(data.message || 'OTP verification failed.');
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '/login'; // Redirect to login page on cancel
                }
            });
        });
    </script>

</body>
</html>
