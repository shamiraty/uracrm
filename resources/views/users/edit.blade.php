@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 12px 35px rgba(23, 71, 158, 0.25);
    }

    .users-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .users-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

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
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s ease;
        background: #dee2e6;
        color: #6c757d;
    }

    .step-line {
        width: 100px;
        height: 3px;
        background: #dee2e6;
        transition: all 0.3s ease;
    }

    .step-item.active .step-circle {
        background: var(--ura-gradient);
        color: white;
        transform: scale(1.1);
        box-shadow: var(--ura-shadow);
    }

    .step-item.completed .step-circle {
        background: var(--ura-success);
        color: white;
    }

    .step-item.completed .step-line {
        background: var(--ura-success);
    }

    .modern-card {
        border: none;
        border-radius: 20px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating-custom label {
        position: absolute;
        top: -8px;
        left: 12px;
        font-size: 0.9rem;
        color: var(--ura-primary);
        background: white;
        padding: 0 8px;
        z-index: 1;
        font-weight: 600;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        transition: all 0.3s ease;
        font-size: 1rem;
        position: relative;
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25), 0 4px 15px rgba(23, 71, 158, 0.1);
        transform: translateY(-1px);
        background: white;
    }

    .form-control.is-valid, .form-select.is-valid {
        border-color: var(--ura-success);
        background: linear-gradient(145deg, #ffffff 0%, rgba(16, 220, 96, 0.05) 100%);
        box-shadow: 0 0 0 0.1rem rgba(16, 220, 96, 0.2);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--ura-danger);
        background: linear-gradient(145deg, #ffffff 0%, rgba(240, 65, 65, 0.05) 100%);
        box-shadow: 0 0 0 0.1rem rgba(240, 65, 65, 0.2);
        animation: shake 0.3s ease-in-out;
    }

    .valid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--ura-success);
        font-weight: 500;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--ura-danger);
        font-weight: 500;
    }

    .section-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border-left: 4px solid var(--ura-primary);
    }

    .section-title {
        color: var(--ura-primary);
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-subtitle {
        color: #6c757d;
        margin: 0;
    }

    .modern-btn {
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 1rem;
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modern-btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
        color: white;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, var(--ura-success) 0%, #00e676 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 220, 96, 0.3);
    }

    .modern-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 220, 96, 0.4);
        color: white;
    }

    .info-card {
        background: white;
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .info-card-header {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-active {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.2) 0%, rgba(16, 220, 96, 0.1) 100%);
        color: var(--ura-success);
        border: 1px solid var(--ura-success);
    }

    .status-inactive {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.2) 0%, rgba(240, 65, 65, 0.1) 100%);
        color: var(--ura-danger);
        border: 1px solid var(--ura-danger);
    }

    .status-role {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.2) 0%, rgba(23, 71, 158, 0.1) 100%);
        color: var(--ura-primary);
        border: 1px solid var(--ura-primary);
    }

    .modern-alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--ura-shadow);
    }

    .modern-alert.alert-danger {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.1) 0%, rgba(240, 65, 65, 0.05) 100%);
        border-left: 4px solid var(--ura-danger);
        color: #721c24;
    }

    .modern-alert.alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.1) 0%, rgba(16, 220, 96, 0.05) 100%);
        border-left: 4px solid var(--ura-success);
        color: #0d5e2d;
    }

    .shake {
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .field-loading {
        position: relative;
    }

    .field-loading::after {
        content: '';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid var(--ura-primary);
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10;
    }

    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }

    .field-focus {
        transform: scale(1.02);
        transition: all 0.3s ease;
    }

    .form-step-indicator {
        position: absolute;
        top: -8px;
        right: 15px;
        background: var(--ura-primary);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        z-index: 5;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .form-check-input:checked {
        background-color: var(--ura-primary);
        border-color: var(--ura-primary);
    }

    .form-check-input:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.25);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="users-header">
        <h1 class="header-title">
            <i class="bx bx-user-circle"></i>
            Edit User: {{ $user->name }}
        </h1>
        <p class="header-subtitle">
            Update user information, roles, and organizational assignments
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="modern-card-title">
                        <i class="bx bx-edit"></i>
                        User Management Form
                    </div>
                    <a href="{{ route('users.index') }}" class="modern-btn modern-btn-secondary">
                        <i class="bx bx-arrow-left"></i>
                        Back to Users
                    </a>
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
                    <div class="alert modern-alert alert-danger alert-dismissible fade show" role="alert">
                        <h6 class="alert-heading"><i class="bx bx-error-circle me-2"></i>Please fix the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Success Message -->
                    <div id="validation-success" class="alert modern-alert alert-success d-none">
                        <i class="bx bx-check-circle me-2"></i><span id="success-message"></span>
                    </div>

                    <!-- Form -->
                    <form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <!-- Step 1: Personal Information -->
                        <div class="step-content" id="step-0">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="bx bx-user"></i>Personal Information
                                        </h5>
                                        <p class="section-subtitle">Enter the user's basic personal details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="name">Full Name *</label>
                                        <input type="text" class="form-control form-control-lg" id="name" name="name"
                                               value="{{ old('name', $user->name) }}" required minlength="2">
                                        <div class="invalid-feedback"></div>
                                        <div class="valid-feedback">✓ Valid name entered</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="email">Email Address *</label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                                               value="{{ old('email', $user->email) }}" required>
                                        <div class="invalid-feedback"></div>
                                        <div class="valid-feedback">✓ Valid email address</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="phone_number">Phone Number *</label>
                                        <input type="text" class="form-control form-control-lg" id="phone_number" name="phone_number"
                                               value="{{ old('phone_number', $user->phone_number) }}" required
                                               placeholder="255XXXXXXXXX" maxlength="12">
                                        <div class="invalid-feedback"></div>
                                        <div class="valid-feedback">✓ Valid phone number format</div>
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
                                        <div class="valid-feedback">✓ Rank selected</div>
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
                                        <div class="valid-feedback">✓ Status set</div>
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
                                        <div class="valid-feedback">✓ Role assigned</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating-custom">
                                        <label for="designation">Job Designation *</label>
                                        <input type="text" class="form-control form-control-lg" id="designation" name="designation"
                                               value="{{ old('designation', $user->designation) }}" required minlength="2">
                                        <div class="invalid-feedback"></div>
                                        <div class="valid-feedback">✓ Valid designation</div>
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
                            <button type="button" class="modern-btn modern-btn-secondary" id="prevBtn" onclick="previousStep()" style="display: none;">
                                <i class="bx bx-chevron-left"></i>Previous
                            </button>
                            <div class="flex-grow-1"></div>
                            <button type="button" class="modern-btn modern-btn-primary" id="nextBtn" onclick="nextStep()">
                                Next<i class="bx bx-chevron-right"></i>
                            </button>
                            <button type="submit" class="modern-btn modern-btn-success d-none" id="submitBtn">
                                <i class="bx bx-check"></i>Update User
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
    let successMessage = '';

    // Check if required field is empty
    if (field.hasAttribute('required') && !value) {
        errorMessage = 'This field is required';
        isValid = false;
    }

    // Specific field validations
    if (isValid && value) {
        switch (field.type) {
            case 'email':
                if (!isValidEmail(value)) {
                    errorMessage = 'Please enter a valid email address';
                    isValid = false;
                } else {
                    successMessage = '✓ Valid email address';
                }
                break;
            case 'text':
                if (field.id === 'phone_number') {
                    if (!validatePhoneNumber(value)) {
                        errorMessage = 'Phone number must start with 255 and be exactly 12 digits';
                        isValid = false;
                    } else {
                        successMessage = '✓ Valid phone number format';
                    }
                } else if (field.id === 'name') {
                    if (value.length < 2) {
                        errorMessage = 'Name must be at least 2 characters long';
                        isValid = false;
                    } else if (!/^[a-zA-Z\s]+$/.test(value)) {
                        errorMessage = 'Name should only contain letters and spaces';
                        isValid = false;
                    } else {
                        successMessage = '✓ Valid name entered';
                    }
                } else if (field.id === 'designation') {
                    if (value.length < 2) {
                        errorMessage = 'Designation must be at least 2 characters long';
                        isValid = false;
                    } else {
                        successMessage = '✓ Valid designation';
                    }
                }
                break;
        }

        // Select validation
        if (field.tagName === 'SELECT' && value) {
            successMessage = getSelectSuccessMessage(field.id);
        }
    }

    if (isValid) {
        setFieldSuccess(field, successMessage);
    } else {
        setFieldError(field, errorMessage);
    }

    return isValid;
}

function getSelectSuccessMessage(fieldId) {
    const messages = {
        'rank': '✓ Rank selected',
        'status': '✓ Status set',
        'role': '✓ Role assigned',
        'region_id': '✓ Region selected',
        'district_id': '✓ District selected',
        'branch_id': '✓ Branch selected',
        'command_id': '✓ Command selected',
        'department_id': '✓ Department selected'
    };
    return messages[fieldId] || '✓ Selection made';
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

    const feedback = field.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.style.display = 'block';
    }

    // Hide valid feedback
    const validFeedback = field.parentElement.querySelector('.valid-feedback');
    if (validFeedback) {
        validFeedback.style.display = 'none';
    }
}

function setFieldSuccess(field, message) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');

    const validFeedback = field.parentElement.querySelector('.valid-feedback');
    if (validFeedback && message) {
        validFeedback.textContent = message;
        validFeedback.style.display = 'block';
    }

    // Hide invalid feedback
    const invalidFeedback = field.parentElement.querySelector('.invalid-feedback');
    if (invalidFeedback) {
        invalidFeedback.style.display = 'none';
    }
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
}

function setupValidation() {
    // Enhanced real-time validation with debouncing
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('blur', () => validateField(field));
        field.addEventListener('input', () => {
            clearTimeout(field.validationTimeout);
            field.validationTimeout = setTimeout(() => {
                validateField(field);
            }, 300);
        });

        // Focus effects
        field.addEventListener('focus', () => {
            field.parentElement.classList.add('field-focus');
        });

        field.addEventListener('blur', () => {
            field.parentElement.classList.remove('field-focus');
        });
    });

    // Special handlers
    setupPhoneNumberFormatting();
    setupEmailValidation();
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

function setupPhoneNumberFormatting() {
    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-digits
            let value = e.target.value.replace(/\D/g, '');

            // Ensure it starts with 255
            if (value.length > 0 && !value.startsWith('255')) {
                value = '255' + value.replace(/^255/, '');
            }

            // Limit to 12 digits
            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            e.target.value = value;
        });
    }
}

function setupEmailValidation() {
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && isValidEmail(email)) {
                // Add loading state
                this.parentElement.classList.add('field-loading');

                // Simulate email availability check
                setTimeout(() => {
                    this.parentElement.classList.remove('field-loading');
                    setFieldSuccess(this, '✓ Valid email address');
                }, 800);
            }
        });
    }
}

// Enhanced form submission with progress indication
document.getElementById('userEditForm').addEventListener('submit', function(e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
        return false;
    }

    // Show submission progress
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating User...';
        submitBtn.disabled = true;
    }
});
</script>

@endsection