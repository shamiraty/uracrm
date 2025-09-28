@extends('layouts.app')

@section('content')
<style>
/* Minimal CSS for step progress and validation */
.step-progress {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.step-item {
    display: flex;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
}

.step-line {
    width: 100px;
    height: 2px;
    background: #dee2e6;
    transition: all 0.3s ease;
}

.step-item.active .step-circle {
    color: white;
}

.step-item.completed .step-circle {
    background: #54c590ff;
    color: white;
}

.step-item.completed .step-line {
    background: #7ac8a4ff;
}

.form-floating-custom {
    position: relative;
}

.form-floating-custom label {
    position: absolute;
    top: 0;
    left: 12px;
    font-size: 0.85rem;
    color: #6c757d;
    padding: 0 5px;
    z-index: 1;
}

.shake {
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 p-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0"><i class="bi bi-person-gear me-2"></i>Update User: {{ $user->name }}</h6>
                            <small class="opacity-75">Edit user information and settings</small>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Step Progress Indicator -->
                    <div class="step-progress mb-4">
                        <div class="step-item active" id="step-indicator-0">
                            <div class="step-circle bg-secondary text-white">1</div>
                            <div class="step-line"></div>
                        </div>
                        <div class="step-item" id="step-indicator-1">
                            <div class="step-circle bg-secondary text-white">2</div>
                            <div class="step-line"></div>
                        </div>
                        <div class="step-item" id="step-indicator-2">
                            <div class="step-circle bg-secondary text-white">3</div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Success Message -->
                    <div id="validation-success" class="alert alert-success d-none">
                        <i class="bi bi-check-circle me-2"></i><span id="success-message"></span>
                    </div>

                    <!-- Form -->
                    <form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <!-- Step 1: Personal Information -->
                        <div class="step-content" id="step-0">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded mb-4">
                                        <h5 class="text-primary mb-1">
                                            <i class="bi bi-person-circle me-2"></i>Personal Information
                                        </h5>
                                        <p class="text-muted mb-0">Enter the user's basic personal details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="name">Full Name *</label>
                                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                               value="{{ old('name', $user->name) }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="email">Email Address *</label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                               value="{{ old('email', $user->email) }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="phone_number">Phone Number *</label>
                                        <input type="text" class="form-control form-control-lg" id="phone_number" name="phone_number" 
                                               value="{{ old('phone_number', $user->phone_number) }}" required 
                                               placeholder="255XXXXXXXXX">
                                        <div class="invalid-feedback"></div>
                                        <small class="text-muted">Format: 255XXXXXXXXX (12 digits starting with 255)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="rank">Rank *</label>
                                        <select class="form-select form-select-lg" id="rank" name="rank" required>
                                            <option value="">Choose rank...</option>
                                            @foreach($ranks as $rank)
                                            <option value="{{ $rank->id }}" {{ old('rank', $user->rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="generate_password" name="generate_password" value="1">
                                                <label class="form-check-label fw-bold" for="generate_password">
                                                    <i class="bi bi-key me-2"></i>Generate New Password
                                                </label>
                                                <small class="d-block text-muted mt-1">Check this to generate a new password for the user (will be sent via SMS)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Administrative Information -->
                        <div class="step-content d-none" id="step-1">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded mb-4">
                                        <h5 class="text-primary mb-1">
                                            <i class="bi bi-shield-check me-2"></i>Administrative Information
                                        </h5>
                                        <p class="text-muted mb-0">Set user roles, permissions and status</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-floating-custom">
                                        <label for="status">Account Status *</label>
                                        <select class="form-select form-select-lg" id="status" name="status" required>
                                            <option value="active" {{ old('status', $user->status) == "active" ? 'selected' : '' }}>
                                                <i class="bi bi-check-circle"></i> Active
                                            </option>
                                            <option value="inactive" {{ old('status', $user->status) == "inactive" ? 'selected' : '' }}>
                                                <i class="bi bi-x-circle"></i> Inactive
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating-custom">
                                        <label for="role">User Role *</label>
                                        <select class="form-select form-select-lg" id="role" name="role" required>
                                            <option value="">Choose role...</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->roles->first() && $user->roles->first()->name == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating-custom">
                                        <label for="designation">Job Designation *</label>
                                        <input type="text" class="form-control form-control-lg" id="designation" name="designation" 
                                               value="{{ old('designation', $user->designation) }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Information Card -->
                            <div class="mt-4">
                                <div class="card border-info">
                                    <div class="card-header ">
                                        <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-2 "></i>Current Role Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Current Role:</strong> 
                                                <span class="badge bg-primary">{{ $user->roles->first()->name ?? 'No role assigned' }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Current Status:</strong> 
                                                @if($user->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Location Information -->
                        <div class="step-content d-none" id="step-2">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded mb-4">
                                        <h5 class="text-primary mb-1">
                                            <i class="bi bi-geo-alt me-2"></i>Location & Organization
                                        </h5>
                                        <p class="text-muted mb-0">Assign user to organizational units and locations</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="region_id">Region *</label>
                                        <select class="form-select form-select-lg" id="region_id" name="region_id" required>
                                            <option value="">Choose region...</option>
                                            @foreach($regions as $region)
                                            <option value="{{ $region->id }}" {{ old('region_id', $user->region_id) == $region->id ? 'selected' : '' }}>
                                                {{ $region->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="district_id">District *</label>
                                        <select class="form-select form-select-lg" id="district_id" name="district_id" required>
                                            <option value="">Choose district...</option>
                                            @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="branch_id">Branch *</label>
                                        <select class="form-select form-select-lg" id="branch_id" name="branch_id" required>
                                            <option value="">Choose branch...</option>
                                            @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="command_id">Command *</label>
                                        <select class="form-select form-select-lg" id="command_id" name="command_id" required>
                                            <option value="">Choose command...</option>
                                            @foreach($commands as $command)
                                            <option value="{{ $command->id }}" {{ old('command_id', $user->command_id) == $command->id ? 'selected' : '' }}>
                                                {{ $command->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating-custom">
                                        <label for="department_id">Department *</label>
                                        <select class="form-select form-select-lg" id="department_id" name="department_id" required>
                                            <option value="">Choose department...</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Location Summary -->
                            <div class="mt-4">
                                <div class="card border-success">
                                    <div class="card-header">
                                        <h6 class="mb-0 text-primary"><i class="bi bi-building me-2"></i>Current Location Assignment</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6 col-lg-3">
                                                <small class="text-muted">Region</small>
                                                <div class="fw-bold">{{ $user->region->name ?? 'Not assigned' }}</div>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <small class="text-muted">District</small>
                                                <div class="fw-bold">{{ $user->district->name ?? 'Not assigned' }}</div>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <small class="text-muted">Branch</small>
                                                <div class="fw-bold">{{ $user->branch->name ?? 'Not assigned' }}</div>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <small class="text-muted">Department</small>
                                                <div class="fw-bold">{{ $user->department->name ?? 'Not assigned' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="mt-5 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg" id="prevBtn" onclick="previousStep()" style="display: none;">
                                <i class="bi bi-arrow-left me-2"></i>Previous
                            </button>
                            <div class="flex-grow-1"></div>
                            <button type="button" class="btn btn-primary btn-lg" id="nextBtn" onclick="nextStep()">
                                Next<i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-lg d-none" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 3;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    setupValidation();
    setupRegionDistrictFilter();
});

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
    
    // Show current step
    document.getElementById(`step-${step}`).classList.remove('d-none');
    
    // Update step indicators
    updateStepIndicators(step);
    
    // Update navigation buttons
    updateNavigationButtons(step);
}

function updateStepIndicators(activeStep) {
    for (let i = 0; i < totalSteps; i++) {
        const indicator = document.getElementById(`step-indicator-${i}`);
        const circle = indicator.querySelector('.step-circle');
        
        indicator.classList.remove('active', 'completed');
        circle.classList.remove('bg-primary', 'bg-success', 'bg-secondary');
        
        if (i < activeStep) {
            indicator.classList.add('completed');
            circle.classList.add('bg-success');
            circle.innerHTML = '<i class="bi bi-check"></i>';
        } else if (i === activeStep) {
            indicator.classList.add('active');
            circle.classList.add('bg-primary');
            circle.textContent = i + 1;
        } else {
            circle.classList.add('bg-secondary');
            circle.textContent = i + 1;
        }
    }
}

function updateNavigationButtons(step) {
    document.getElementById('prevBtn').style.display = step === 0 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps - 1 ? 'none' : 'block';
    document.getElementById('submitBtn').classList.toggle('d-none', step !== totalSteps - 1);
}

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
            showSuccessMessage(`Step ${currentStep} completed successfully!`);
        }
    }
}

function previousStep() {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
}

function validateCurrentStep() {
    const currentStepElement = document.getElementById(`step-${currentStep}`);
    const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    // Special validation for phone number
    if (currentStep === 0) {
        const phoneInput = document.getElementById('phone_number');
        if (!validatePhoneNumber(phoneInput.value)) {
            setFieldError(phoneInput, 'Phone number must start with 255 and be exactly 12 digits');
            isValid = false;
        }
    }

    if (!isValid) {
        currentStepElement.classList.add('shake');
        setTimeout(() => currentStepElement.classList.remove('shake'), 500);
    }

    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    // Check if required field is empty
    if (field.hasAttribute('required') && !value) {
        errorMessage = 'This field is required';
        isValid = false;
    }
    
    // Email validation
    if (field.type === 'email' && value && !isValidEmail(value)) {
        errorMessage = 'Please enter a valid email address';
        isValid = false;
    }

    if (isValid) {
        clearFieldError(field);
    } else {
        setFieldError(field, errorMessage);
    }

    return isValid;
}

function validatePhoneNumber(phone) {
    return /^255\d{9}$/.test(phone);
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function setFieldError(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    
    const feedback = field.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    }
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
}

function setupValidation() {
    // Real-time validation
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('blur', () => validateField(field));
        field.addEventListener('input', () => {
            if (field.classList.contains('is-invalid')) {
                validateField(field);
            }
        });
    });
}

function setupRegionDistrictFilter() {
    document.getElementById('region_id').addEventListener('change', function() {
        const regionId = this.value;
        const districtSelect = document.getElementById('district_id');
        
        if (regionId) {
            fetch(`/districts/${regionId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Choose district...</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading districts:', error);
                    showErrorMessage('Error loading districts. Please try again.');
                });
        } else {
            districtSelect.innerHTML = '<option value="">Choose district...</option>';
        }
    });
}

function showSuccessMessage(message) {
    const alert = document.getElementById('validation-success');
    document.getElementById('success-message').textContent = message;
    alert.classList.remove('d-none');
    setTimeout(() => alert.classList.add('d-none'), 3000);
}

function showErrorMessage(message) {
    // You can implement error message display here
    alert(message);
}

// Form submission
document.getElementById('userEditForm').addEventListener('submit', function(e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
        return false;
    }
});
</script>

@endsection