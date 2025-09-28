<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <title>URA SACCOS CRM | OTP Verification</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans", sans-serif;
        }

        :root {
            --primary-blue: #17479E;
            --primary-dark: #0F3470;
            --primary-light: #2558B3;
            --accent-cyan: #00BCD4;
            --text-dark: #333333;
            --text-gray: #717171;
            --text-light: #4a4646;
            --border-gray: #E0E0E0;
            --error-red: #d9534f;
            --success-green: #5cb85c;
            --warning-yellow: #f0ad4e;
            --bg-overlay: rgba(0, 0, 0, 0.4);
        }

        body {
            height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-opacity='0.03'%3E%3Cpolygon fill='%23000' points='50 0 60 40 100 50 60 60 50 100 40 60 0 50 40 40'/%3E%3C/g%3E%3C/svg%3E");
            background-color: #17479E;
            background-size: 100px 100px, cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float-up 15s infinite linear;
        }

        @keyframes float-up {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        /* Generate multiple particles */
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 12s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 14s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 16s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; animation-duration: 18s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 8s; animation-duration: 20s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 10s; animation-duration: 12s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 12s; animation-duration: 14s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 14s; animation-duration: 16s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 16s; animation-duration: 18s; }
        .particle:nth-child(10) { left: 95%; animation-delay: 18s; animation-duration: 20s; }

        /* Blur overlay */
        .blur-bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2;
            height: 100%;
            width: 100%;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            background: rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Animated particles background -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Blur overlay -->
    <div class="blur-bg-overlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get cooldown info from backend session
            const backendCooldownEnd = {{ session('otp_cooldown_end') ?? 'null' }};
            const sessionPhone = '{{ session('otp_phone') ?? '' }}';
            const sessionUserId = '{{ session('otp_user_id') ?? '' }}';

            let canRequestOtp = true;
            let remainingTime = 0;

            // Function to sync cooldown with backend
            function syncCooldownWithBackend() {
                if (sessionPhone && sessionUserId) {
                    fetch('/check-cooldown-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            phone: sessionPhone,
                            user_id: sessionUserId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.under_cooldown) {
                            canRequestOtp = false;
                            remainingTime = data.remaining_seconds;
                            console.log('Backend cooldown sync: ' + data.remaining_time + ' remaining');

                            // Update the UI dynamically instead of reloading
                            console.log('Backend cooldown detected, updating UI...');
                        } else {
                            canRequestOtp = true;
                            remainingTime = 0;
                            console.log('Backend sync: No cooldown active');
                        }
                    })
                    .catch(error => {
                        console.log('Cooldown sync failed:', error);
                    });
                }
            }

            // Check backend cooldown status first (for cross-session persistence)
            syncCooldownWithBackend();

            // Only apply cooldown if there's actually a cooldown set
            if (backendCooldownEnd && backendCooldownEnd !== null && backendCooldownEnd !== 'null') {
                const now = Math.floor(new Date().getTime() / 1000);
                const timeRemaining = backendCooldownEnd - now;

                console.log('Backend cooldown check:', {
                    cooldownEnd: backendCooldownEnd,
                    currentTime: now,
                    remaining: timeRemaining
                });

                if (timeRemaining > 0) {
                    canRequestOtp = false;
                    remainingTime = timeRemaining;
                    console.log('Session cooldown active: ' + remainingTime + ' seconds remaining');
                } else {
                    console.log('Session cooldown expired, will show OTP inputs');
                    canRequestOtp = true;
                    remainingTime = 0;
                }
            }

            function formatTime(seconds) {
                // Ensure seconds is a valid positive number
                if (isNaN(seconds) || seconds < 0) {
                    return '0:00';
                }
                const minutes = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${minutes}:${secs.toString().padStart(2, '0')}`;
            }

            function showOtpDialog() {
                let otpInputs = '';
                let dialogTitle = 'Enter OTP Verification Code';
                let showVerifyButton = true;

                if (!canRequestOtp) {
                    // User is under cooldown - hide input boxes and show only countdown
                    dialogTitle = 'OTP Request Cooldown';
                    showVerifyButton = false;
                    otpInputs = `
                        <div style="text-align: center; margin: 30px 0;">
                            <div style="font-size: 18px; color: #17479E; margin-bottom: 10px;">
                                <i class="material-symbols-rounded" style="font-size: 48px; color: #f0ad4e;">timer</i>
                            </div>
                            <p style="color: #17479E; font-size: 16px; margin-bottom: 15px;">
                                You must wait before requesting a new OTP
                            </p>
                            <p style="color: #17479E; font-size: 18px; font-weight: bold;">
                                Time remaining: <span id="countdown-timer">${formatTime(remainingTime)}</span>
                            </p>
                            <p style="color: #666; font-size: 14px; margin-top: 10px;">
                                Please wait before requesting a new verification code
                            </p>
                            <p style="color: #888; font-size: 12px; margin-top: 5px;">
                                This security measure protects your account
                            </p>
                        </div>
                    `;
                } else {
                    // Enhanced OTP input boxes with 6 separate boxes
                    otpInputs = `
                        <div style="display: flex; justify-content: center; gap: 12px; margin: 30px 0;">
                            <input type="text" id="otp-input-1" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                            <input type="text" id="otp-input-2" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                            <input type="text" id="otp-input-3" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                            <input type="text" id="otp-input-4" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                            <input type="text" id="otp-input-5" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                            <input type="text" id="otp-input-6" maxlength="1" style="width: 55px; height: 55px; text-align: center; font-size: 24px; font-weight: 600; border: 2px solid #17479E; border-radius: 12px; outline: none; background: rgba(255, 255, 255, 0.95); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);" autocomplete="off">
                        </div>
                        <style>
                            input[id^="otp-input-"]:focus {
                                border-color: #00BCD4 !important;
                                box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.2), 0 4px 20px rgba(23, 71, 158, 0.15) !important;
                                transform: scale(1.05) !important;
                            }
                            input[id^="otp-input-"]:not(:placeholder-shown) {
                                border-color: #00BCD4 !important;
                                background: rgba(0, 188, 212, 0.08) !important;
                                color: #17479E !important;
                                font-weight: 700 !important;
                            }
                            input[id^="otp-input-"]:hover {
                                border-color: #00BCD4 !important;
                                box-shadow: 0 4px 20px rgba(23, 71, 158, 0.12) !important;
                            }
                        </style>
                    `;
                }

                const html = `
                    ${otpInputs}
                    <div id="resend-section" style="margin-top: 20px; text-align: center;">
                        ${!canRequestOtp ?
                            '' : // No resend button during cooldown
                            `<div style="text-align: center;">
                                <p style="color: #666; font-size: 13px; margin-bottom: 8px;">Didn't receive your code?</p>
                                <button id="resend-otp-btn" style="background: #17479E; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(23, 71, 158, 0.3);">
                                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 5px;">refresh</i>
                                    Resend Verification Code
                                </button>
                            </div>`
                        }
                    </div>
                `;

                Swal.fire({
                    title: dialogTitle,
                    html: html,
                    showCancelButton: true,
                    confirmButtonText: showVerifyButton ? 'Verify OTP' : 'Cancel',
                    cancelButtonText: 'Back to Login',
                    showConfirmButton: showVerifyButton,
                    showLoaderOnConfirm: showVerifyButton,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        popup: 'swal-wide-popup'
                    },
                    didOpen: () => {
                        // Add custom styles for wider popup
                        const style = document.createElement('style');
                        style.textContent = `
                            .swal-wide-popup {
                                width: 500px !important;
                                max-width: 90vw !important;
                            }
                            .swal2-html-container {
                                padding: 20px 30px !important;
                            }
                        `;
                        document.head.appendChild(style);

                        if (showVerifyButton) {
                            // Focus first input only if showing input boxes
                            const firstInput = document.getElementById('otp-input-1');
                            if (firstInput) {
                                firstInput.focus();
                            }

                            // Handle input navigation between 6 boxes
                            for (let i = 1; i <= 6; i++) {
                                const input = document.getElementById(`otp-input-${i}`);
                                if (input) {
                                    input.addEventListener('input', function(e) {
                                        // Only allow digits
                                        this.value = this.value.replace(/[^0-9]/g, '');

                                        if (this.value.length === 1 && i < 6) {
                                            document.getElementById(`otp-input-${i + 1}`).focus();
                                        }
                                    });

                                    input.addEventListener('keydown', function(e) {
                                        if (e.key === 'Backspace' && this.value === '' && i > 1) {
                                            document.getElementById(`otp-input-${i - 1}`).focus();
                                        }

                                        // Allow pasting
                                        if (e.key === 'v' && e.ctrlKey) {
                                            e.preventDefault();
                                            navigator.clipboard.readText().then(text => {
                                                const digits = text.replace(/[^0-9]/g, '').slice(0, 6);
                                                for (let j = 0; j < digits.length && j < 6; j++) {
                                                    const targetInput = document.getElementById(`otp-input-${j + 1}`);
                                                    if (targetInput) {
                                                        targetInput.value = digits[j];
                                                    }
                                                }
                                                if (digits.length > 0) {
                                                    const lastInput = document.getElementById(`otp-input-${Math.min(digits.length, 6)}`);
                                                    if (lastInput) lastInput.focus();
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        }

                        // Start countdown if needed
                        if (!canRequestOtp && remainingTime > 0) {
                            const timer = setInterval(() => {
                                remainingTime--;
                                const timerElement = document.getElementById('countdown-timer');

                                if (remainingTime <= 0) {
                                    clearInterval(timer);
                                    canRequestOtp = true;
                                    // Update UI to show OTP inputs without reload
                                    Swal.close();
                                    setTimeout(() => showOtpDialog(), 500);
                                    return;
                                }

                                if (timerElement) {
                                    timerElement.textContent = formatTime(remainingTime);
                                }
                            }, 1000);
                        } else if (remainingTime <= 0) {
                            // Cooldown already expired, show OTP inputs directly
                            console.log('Cooldown expired, enabling OTP inputs');
                            canRequestOtp = true;
                        }

                        function setupResendButton() {
                            const resendBtn = document.getElementById('resend-otp-btn');
                            if (resendBtn) {
                                resendBtn.addEventListener('click', function() {
                                    if (canRequestOtp) {
                                        // Disable button and show loading
                                        this.disabled = true;
                                        this.style.background = '#6c757d';
                                        this.innerHTML = '<i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 5px;">hourglass_empty</i>Sending code...';

                                        // Send request for new OTP
                                        fetch('/resend-otp', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                            }
                                        })
                                        .then(response => {
                                            // Handle both success and error responses
                                            return response.json().then(data => ({
                                                ok: response.ok,
                                                status: response.status,
                                                data: data
                                            }));
                                        })
                                        .then(response => {
                                            const data = response.data;

                                            if (response.ok && data.success) {
                                                Swal.showValidationMessage('New OTP sent successfully!');
                                                setTimeout(() => {
                                                    Swal.resetValidationMessage();
                                                }, 2000);

                                                // Check if immediate cooldown should be applied
                                                if (data.no_immediate_cooldown) {
                                                    // User can enter OTP immediately - hide resend button but keep input boxes
                                                    const resendSection = document.getElementById('resend-section');
                                                    if (resendSection) {
                                                        resendSection.innerHTML = '<p style="color: #17479E; font-size: 14px; font-weight: 600;">✓ New OTP sent! Please enter the code above.</p>';
                                                    }
                                                } else if (data.cooldown_end) {
                                                    // Normal cooldown flow
                                                    const now = Math.floor(new Date().getTime() / 1000);
                                                    remainingTime = data.cooldown_end - now;
                                                    canRequestOtp = false;

                                                    // Reload page to show cooldown state
                                                    setTimeout(() => {
                                                        window.location.reload();
                                                    }, 1000);
                                                }
                                            } else {
                                                // Handle error responses (including 429 cooldown)
                                                if (response.status === 429 || data.remaining_seconds) {
                                                    // Backend has set cooldown - reload page to show countdown UI
                                                    Swal.showValidationMessage('Cooldown activated. Redirecting to countdown...');
                                                    setTimeout(() => {
                                                        window.location.reload();
                                                    }, 1000);
                                                } else {
                                                    Swal.showValidationMessage('Failed to send OTP: ' + (data.message || 'Unknown error'));
                                                    // Re-enable button
                                                    this.disabled = false;
                                                    this.style.background = '#17479E';
                                                    this.innerHTML = '<i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 5px;">refresh</i>Resend Verification Code';
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            Swal.showValidationMessage('Error sending OTP: ' + error.message);
                                            // Re-enable button
                                            this.disabled = false;
                                            this.style.background = '#17479E';
                                            this.innerHTML = '<i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 5px;">refresh</i>Resend Verification Code';
                                        });
                                    }
                                });
                            }
                        }

                        setupResendButton();
                    },
                    preConfirm: () => {
                        if (!showVerifyButton) {
                            // If not showing verify button, don't perform verification
                            return false;
                        }

                        let otp = '';
                        for (let i = 1; i <= 6; i++) {
                            const input = document.getElementById(`otp-input-${i}`);
                            if (!input) {
                                Swal.showValidationMessage('OTP input not available during cooldown');
                                return false;
                            }
                            const value = input.value;
                            if (!value) {
                                Swal.showValidationMessage('Please enter all 6 digits');
                                return false;
                            }
                            otp += value;
                        }

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
                                window.location.href = '/dashboard';
                            } else {
                                throw new Error(data.message || 'OTP verification failed.');
                            }
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Verification failed: ${error.message}`);
                        });
                    }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = '/login';
                    }
                });
            }

            showOtpDialog();
        });
    </script>

</body>
</html>