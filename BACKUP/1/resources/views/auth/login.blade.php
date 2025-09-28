<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <title>URA SACCOS CRM | Secure Login</title>
    
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

        /* Form popup container */
        .form-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 10;
            width: 100%;
            max-width: 850px;
            background: #fff;
            border-radius: 20px;
            transform: translate(-50%, -50%);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3),
                        0 0 100px rgba(23, 71, 158, 0.2);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Form box container */
        .form-box {
            display: flex;
            height: 100%;
        }

        /* Left side - Branding */
        .form-details {
            width: 45%;
            padding: 60px 40px;
            background: #17479E;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            overflow: hidden;
        }

        .form-details::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 70% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .form-details h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-details p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.95;
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .security-features {
            position: relative;
            z-index: 2;
            margin-top: 30px;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .security-item .material-symbols-rounded {
            font-size: 20px;
            color: #FFD700;
        }

        /* Right side - Login form */
        .form-content {
            width: 55%;
            padding: 50px 45px;
            background: white;
            position: relative;
        }

        /* Logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-container {
            display: inline-block;
            position: relative;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 100px;
            height: 85px;
            border: 3px solid var(--primary-blue);
            border-radius: 15px;
            padding: 10px;
            background: white;
            box-shadow: 0 5px 15px rgba(23, 71, 158, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
        }

        .form-content h3 {
            color: #17479E;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-content .subtitle {
            color: #17479E;
            font-size: 0.95rem;
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* Input fields */
        .input-field {
            position: relative;
            margin-bottom: 25px;
        }

        .input-field input {
            width: 100%;
            height: 52px;
            padding: 0 45px 0 15px;
            font-size: 0.95rem;
            border: 2px solid #17479E;
            border-radius: 10px;
            background: #F0F4FF;
            transition: all 0.3s ease;
            color: #17479E;
            font-weight: 500;
        }
        
        .input-field input::placeholder {
            color: #17479E;
            opacity: 0.5;
            font-weight: 400;
        }

        .input-field input:focus {
            outline: none;
            border-color: #17479E;
            border-width: 3px;
            background: white;
            box-shadow: 0 0 0 4px rgba(23, 71, 158, 0.25);
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #17479E;
            font-size: 0.95rem;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 5px;
            font-weight: 500;
        }

        .input-field input:focus ~ label,
        .input-field input:valid ~ label {
            top: -8px;
            left: 12px;
            font-size: 0.8rem;
            color: #17479E;
            font-weight: 600;
        }

        /* Icons */
        .input-field .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #17479E;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .input-field input:focus ~ .input-icon {
            color: #17479E;
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password {
            cursor: pointer;
            user-select: none;
        }

        .toggle-password:hover {
            color: #0F3470;
            transform: scale(1.1);
        }

        /* Remember me and forgot password */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #17479E;
            border: 2px solid #17479E;
            outline: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .remember-me input[type="checkbox"]:checked {
            background-color: #17479E;
            border-color: #17479E;
            outline: 2px solid rgba(23, 71, 158, 0.2);
        }
        
        .remember-me input[type="checkbox"]:hover {
            transform: scale(1.1);
            outline: 2px solid rgba(23, 71, 158, 0.3);
        }

        .remember-me label {
            font-size: 0.9rem;
            color: #17479E;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .forgot-pass-link {
            color: #17479E;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-pass-link:hover {
            color: #0F3470;
            text-decoration: underline;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            height: 52px;
            background: #17479E;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 71, 158, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Error messages */
        .error-message {
            color: var(--error-red);
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-message .material-symbols-rounded {
            font-size: 16px;
        }

        .input-field.has-error input {
            border-color: var(--error-red);
            background: #FFF5F5;
        }

        /* Success alert */
        .alert-success {
            background: linear-gradient(135deg, #D4EDDA 0%, #E8F5E9 100%);
            border: 1px solid #C3E6CB;
            color: #155724;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success .material-symbols-rounded {
            color: var(--success-green);
            font-size: 20px;
        }

        /* Footer */
        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-gray);
        }

        .form-footer p {
            color: #17479E;
            font-size: 0.85rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .form-footer .links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .form-footer a {
            color: #17479E;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
            font-weight: 600;
        }

        .form-footer a:hover {
            color: #0F3470;
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-popup {
                width: 95%;
                max-width: 400px;
            }

            .form-details {
                display: none;
            }

            .form-content {
                width: 100%;
                padding: 40px 30px;
            }

            .form-content h3 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .form-content {
                padding: 30px 20px;
            }

            .logo-container img {
                width: 80px;
                height: 68px;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
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

    <!-- Login form popup -->
    <div class="form-popup">
        <div class="form-box login">
            <!-- Left side - Branding -->
            <div class="form-details">
                <h2>URA SACCOS CRM</h2>
                <p>Customer Relationship Management System</p>
                <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 10px;">
                    Empowering financial services with modern technology
                </p>
                
                <div class="security-features">
                    <div class="security-item">
                        <span class="material-symbols-rounded">verified_user</span>
                        <span>Bank-level Security</span>
                    </div>
                    <div class="security-item">
                        <span class="material-symbols-rounded">lock</span>
                        <span>256-bit SSL Encryption</span>
                    </div>
                    <div class="security-item">
                        <span class="material-symbols-rounded">security</span>
                        <span>Two-Factor Authentication</span>
                    </div>
                    <div class="security-item">
                        <span class="material-symbols-rounded">shield</span>
                        <span>GDPR Compliant</span>
                    </div>
                </div>
            </div>

            <!-- Right side - Login form -->
            <div class="form-content">
                <div class="logo-section">
                    <div class="logo-container">
                        <img src="{{ asset('assets/images/uralogo.png') }}" alt="URA SACCOS Logo">
                    </div>
                    <h3>Welcome Back</h3>
                    <p class="subtitle">Sign in to continue to your dashboard</p>
                </div>

                @if(session('status'))
                    <div class="alert-success">
                        <span class="material-symbols-rounded">check_circle</span>
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <!-- Email field -->
                    <div class="input-field @error('email') has-error @enderror">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email">
                        <label for="email">Email Address</label>
                        <span class="material-symbols-rounded input-icon">mail</span>
                        @error('email')
                            <div class="error-message">
                                <span class="material-symbols-rounded">error</span>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password field -->
                    <div class="input-field @error('password') has-error @enderror">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password">
                        <label for="password">Password</label>
                        <span class="material-symbols-rounded input-icon toggle-password" id="togglePassword">
                            visibility_off
                        </span>
                        @error('password')
                            <div class="error-message">
                                <span class="material-symbols-rounded">error</span>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember me and forgot password -->
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-pass-link">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    
                    <!-- Submit button -->
                    <button type="submit" class="submit-btn" id="loginBtn">
                        <span id="loginText">Sign In</span>
                        <span id="loginSpinner" class="spinner" style="display: none;"></span>
                    </button>
                </form>

                <!-- Footer -->
                <div class="form-footer">
                    <p>&copy; {{ date('Y') }} URA SACCOS LTD. All rights reserved.</p>
                    <div class="links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.textContent = type === 'password' ? 'visibility_off' : 'visibility';
        });

        // Form submission with loading state
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loginSpinner = document.getElementById('loginSpinner');

        loginForm.addEventListener('submit', function(e) {
            // Show loading state
            loginText.textContent = 'Signing in...';
            loginSpinner.style.display = 'inline-block';
            loginBtn.disabled = true;
        });

        // Auto-focus email field on load
        window.addEventListener('load', function() {
            document.getElementById('email').focus();
        });

        // Add ripple effect to button
        const submitBtn = document.querySelector('.submit-btn');
        submitBtn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>

</html>