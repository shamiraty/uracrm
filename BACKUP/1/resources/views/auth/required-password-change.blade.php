<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/uralogo.png') }}" type="image/png" />
    <title>URASACCOS CRM - Change Required Password</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="{{ asset('asset/assets/style.css') }}">
    <style>
        /* This is for the password toggle icon */
        .input-field {
            position: relative;
        }

        .input-field .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            user-select: none;
        }

        .input-field .toggle-password:hover {
            color: #555;
        }
        
        /* Improved Error Styling */
        .error-message {
            color: #d9534f; /* A standard red color */
            font-size: 0.85rem;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        .input-field.has-error input {
            border-color: #d9534f;
        }

        /* Spinner and Button Styling */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <div class="form-box login">
            <div class="form-details">
                <h2>URASACCOS CRM</h2>
                <p>Change Required Password</p>
            </div>
            <div class="form-content">
                <div style="display: flex; justify-content: center;">
                    <a href="#">
                        <img src="{{ asset('asset/assets/images/uralogo.png') }}" alt="URASACCOS CRM" style="width:120px; height:100px; border: 2px solid #18479e;" class="img-responsive">
                    </a>
                </div>
                <form action="{{ route('password.change.required.store') }}" method="POST">
                    @csrf
                    <div class="input-field @error('old_password') has-error @enderror">
                        <input type="password" id="old_password" name="old_password" required>
                        <label>Old Password</label>
                        <span class="material-symbols-rounded toggle-password" data-target="old_password">
                            visibility_off
                        </span>
                    </div>
                    @error('old_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <div class="input-field @error('password') has-error @enderror">
                        <input type="password" id="password" name="password" required>
                        <label>New Password</label>
                        <span class="material-symbols-rounded toggle-password" data-target="password">
                            visibility_off
                        </span>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <div class="input-field">
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <label>Confirm New Password</label>
                        <span class="material-symbols-rounded toggle-password" data-target="password_confirmation">
                            visibility_off
                        </span>
                    </div>

                    <button type="submit" id="changePasswordBtn">
                        <span id="changePasswordText">Change Password</span>
                        <span id="changePasswordSpinner" class="spinner" style="display: none;"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add("show-popup");

            // Password Toggle Logic
            document.querySelectorAll('.toggle-password').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if (this.textContent === 'visibility') {
                        this.textContent = 'visibility_off';
                    } else {
                        this.textContent = 'visibility';
                    }
                });
            });

            // Form Submission Logic
            const changePasswordForm = document.querySelector("form");
            const changePasswordBtn = document.getElementById("changePasswordBtn");
            const changePasswordText = document.getElementById("changePasswordText");
            const changePasswordSpinner = document.getElementById("changePasswordSpinner");
            
            changePasswordForm.addEventListener("submit", function () {
                changePasswordBtn.disabled = true;
                changePasswordText.textContent = "Changing Password...";
                changePasswordSpinner.style.display = "inline-block";
            });
        });
    </script>
</body>
</html>