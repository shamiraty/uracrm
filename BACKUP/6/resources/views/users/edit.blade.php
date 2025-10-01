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
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
        --ura-shadow: 0 4px 20px rgba(23, 71, 158, 0.12);
        --ura-shadow-hover: 0 8px 30px rgba(23, 71, 158, 0.18);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: #f8fafc;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .user-form-page {
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-card {
        border: none;
        background: white;
        overflow: visible;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-radius: 0;
    }

    .card-header-modern {
        background: var(--ura-gradient);
        padding: 2rem 3rem;
        color: white;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--ura-accent);
    }

    .card-header-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 65%);
        animation: float 25s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(25px, -25px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .card-header-modern h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: 0.5px;
    }

    .card-header-modern p {
        margin: 0.5rem 0 0 0;
        opacity: 0.92;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .step-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 2.5rem auto;
        position: relative;
        padding: 0 1.5rem;
        max-width: 900px;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }

    .step-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.5rem;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        z-index: 2;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .step-label {
        margin-top: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-align: center;
        letter-spacing: 0.8px;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .step-item.active .step-circle {
        background: var(--ura-gradient);
        color: white;
        transform: scale(1.15);
        box-shadow: 0 4px 20px rgba(23, 71, 158, 0.35);
    }

    .step-item.active .step-label {
        color: var(--ura-primary);
        font-size: 0.8rem;
    }

    .step-item.completed .step-circle {
        background: linear-gradient(135deg, #10dc60 0%, #00e676 100%);
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(16, 220, 96, 0.3);
    }

    .step-item.completed .step-label {
        color: var(--ura-success);
    }

    .step-line {
        position: absolute;
        top: 30px;
        left: 50%;
        right: -50%;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
        transition: all 0.5s ease;
    }

    .step-item.completed .step-line {
        background: linear-gradient(90deg, #10dc60 0%, #00e676 100%);
    }

    .step-item:last-child .step-line {
        display: none;
    }

    .form-section {
        padding: 2.5rem;
        animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .section-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border-left: 5px solid var(--ura-primary);
    }

    .section-title {
        color: var(--ura-primary);
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: 0.5px;
    }

    .section-subtitle {
        color: #64748b;
        margin: 0;
        font-size: 0.9rem;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 1.75rem;
    }

    .form-floating-custom label {
        position: absolute;
        top: -10px;
        left: 18px;
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--ura-primary);
        background: white;
        padding: 0 8px;
        z-index: 10;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.1rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.95rem;
        background: white;
        font-weight: 500;
        width: 100%;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #cbd5e1;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--ura-primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.08);
        outline: none;
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: var(--ura-success);
        background: rgba(16, 220, 96, 0.02);
        padding-right: 3rem;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: var(--ura-danger);
        background: rgba(240, 65, 65, 0.02);
        animation: shake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
        20%, 40%, 60%, 80% { transform: translateX(8px); }
    }

    .valid-feedback,
    .invalid-feedback {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: none;
        padding-left: 0.25rem;
    }

    .form-control.is-valid ~ .valid-feedback,
    .form-select.is-valid ~ .valid-feedback {
        display: block;
    }

    .form-control.is-invalid ~ .invalid-feedback,
    .form-select.is-invalid ~ .invalid-feedback {
        display: block;
    }

    .modern-btn {
        border: none;
        border-radius: 10px;
        padding: 0.875rem 2rem;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
    }

    .modern-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(23, 71, 158, 0.3);
        color: white;
    }

    .modern-btn-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
    }

    .modern-btn-secondary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(100, 116, 139, 0.3);
        color: white;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, #10dc60 0%, #00e676 100%);
        color: white;
    }

    .modern-btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 220, 96, 0.3);
        color: white;
    }

    .modern-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    .navigation-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #e2e8f0;
    }

    .modern-alert {
        border: none;
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .modern-alert-danger {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.1) 0%, rgba(240, 65, 65, 0.05) 100%);
        border-left: 4px solid var(--ura-danger);
        color: #991b1b;
    }

    .modern-alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.1) 0%, rgba(16, 220, 96, 0.05) 100%);
        border-left: 4px solid var(--ura-success);
        color: #065f46;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--ura-primary);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        background: var(--ura-gradient-light);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .back-link:hover {
        background: var(--ura-primary);
        color: white;
        transform: translateX(-3px);
        box-shadow: 0 2px 8px rgba(23, 71, 158, 0.2);
    }

    @media (max-width: 768px) {
        .form-section {
            padding: 1.5rem;
        }
        .step-circle {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        .step-label {
            font-size: 0.65rem;
        }
    }
</style>

<div class="container-fluid user-form-page">
    <div class="modern-card">
        <!-- Card Header -->
        <div class="card-header-modern">
            <h3 class="text-white text-uppercase">
                <i class="bx bx-user-circle"></i>
                EDIT {{ old('name', $user->name) }} [ {{ old('status', $user->status)}}  ] 
            </h3>
           
        </div>

        <!-- Card Body -->
        <div class="form-section">
            <!-- Step Progress -->
            <div class="step-progress">
                <div class="step-item active" id="step-indicator-0">
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal Information</div>
                    <div class="step-line"></div>
                </div>
                <div class="step-item" id="step-indicator-1">
                    <div class="step-circle">2</div>
                    <div class="step-label">Administrative</div>
                    <div class="step-line"></div>
                </div>
                <div class="step-item" id="step-indicator-2">
                    <div class="step-circle">3</div>
                    <div class="step-label">Location & Organization</div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="modern-alert modern-alert-danger">
                <i class="bx bx-error-circle" style="font-size: 1.75rem;"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2" style="list-style-position: inside;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
            <div class="modern-alert modern-alert-success">
                <i class="bx bx-check-circle" style="font-size: 1.75rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            <!-- Form -->
            <form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Step 1: Personal Information -->
                <div class="step-content" id="step-0">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="bx bx-user"></i>
                            PERSONAL INFORMATION
                        </h5>
                        <p class="section-subtitle">Update the user's basic personal details</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="name">Full Name *</label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       minlength="2">
                                <div class="invalid-feedback">Please enter a valid name</div>
                                <div class="valid-feedback">✓ Valid name entered</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="email">Email Address *</label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                <div class="invalid-feedback">Please enter a valid email</div>
                                <div class="valid-feedback">✓ Valid email address</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="phone_number">Phone Number *</label>
                                <input type="text"
                                       class="form-control"
                                       id="phone_number"
                                       name="phone_number"
                                       value="{{ old('phone_number', $user->phone_number) }}"
                                       required
                                       placeholder="255XXXXXXXXX">
                                <div class="invalid-feedback">Phone must start with 255 and be 12 digits</div>
                                <div class="valid-feedback">✓ Valid phone number</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="rank">Rank *</label>
                                <select class="form-select" id="rank" name="rank" required>
                                    <option value="">CHOOSE RANK...</option>
                                    @foreach($ranks as $rank)
                                    <option value="{{ $rank->id }}" {{ old('rank', $user->rank_id) == $rank->id ? 'selected' : '' }}>
                                        {{ strtoupper($rank->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a rank</div>
                                <div class="valid-feedback">✓ Rank selected</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Administrative Information -->
                <div class="step-content d-none" id="step-1">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="bx bx-shield-check"></i>
                            ADMINISTRATIVE INFORMATION
                        </h5>
                        <p class="section-subtitle">Update user roles, permissions and status</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-floating-custom">
                                <label for="status">Account Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">CHOOSE STATUS...</option>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>ACTIVE</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>INACTIVE</option>
                                </select>
                                <div class="invalid-feedback">Please select status</div>
                                <div class="valid-feedback">✓ Status selected</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating-custom">
                                <label for="role">User Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">CHOOSE ROLE...</option>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}"
                                        {{ ($user->roles->first() && $user->roles->first()->name == $role->name) ? 'selected' : '' }}>
                                        {{ strtoupper(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a role</div>
                                <div class="valid-feedback">✓ Role assigned</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating-custom">
                                <label for="designation">Job Designation *</label>
                                <input type="text"
                                       class="form-control"
                                       id="designation"
                                       name="designation"
                                       value="{{ old('designation', $user->designation) }}"
                                       required
                                       minlength="2">
                                <div class="invalid-feedback">Please enter designation</div>
                                <div class="valid-feedback">✓ Valid designation</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Location & Organization -->
                <div class="step-content d-none" id="step-2">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="bx bx-buildings"></i>
                            LOCATION & ORGANIZATION
                        </h5>
                        <p class="section-subtitle">Assign user to organizational units and locations</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="region_id">Region *</label>
                                <select class="form-select" id="region_id" name="region_id" required>
                                    <option value="">CHOOSE REGION...</option>
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id', $user->region_id) == $region->id ? 'selected' : '' }}>
                                        {{ strtoupper($region->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a region</div>
                                <div class="valid-feedback">✓ Region selected</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="district_id">District *</label>
                                <select class="form-select" id="district_id" name="district_id" required>
                                    <option value="">CHOOSE DISTRICT...</option>
                                    @if($user->district_id)
                                    <option value="{{ $user->district_id }}" selected>
                                        {{ strtoupper($user->district->name ?? '') }}
                                    </option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">Please select a district</div>
                                <div class="valid-feedback">✓ District selected</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="branch_id">Branch *</label>
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">CHOOSE BRANCH...</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ strtoupper($branch->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a branch</div>
                                <div class="valid-feedback">✓ Branch selected</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label for="command_id">Command *</label>
                                <select class="form-select" id="command_id" name="command_id" required>
                                    <option value="">CHOOSE COMMAND...</option>
                                    @foreach($commands as $command)
                                    <option value="{{ $command->id }}" {{ old('command_id', $user->command_id) == $command->id ? 'selected' : '' }}>
                                        {{ strtoupper($command->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a command</div>
                                <div class="valid-feedback">✓ Command selected</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label for="department_id">Department *</label>
                                <select class="form-select" id="department_id" name="department_id" required>
                                    <option value="">CHOOSE DEPARTMENT...</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ strtoupper($department->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a department</div>
                                <div class="valid-feedback">✓ Department selected</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="navigation-section">
                    <button type="button"
                            class="modern-btn modern-btn-secondary"
                            id="prevBtn"
                            onclick="previousStep()"
                            style="display: none;">
                        <i class="bx bx-chevron-left"></i>
                        Back
                    </button>

                    <div class="flex-grow-1"></div>

                    <button type="button"
                            class="modern-btn modern-btn-primary"
                            id="nextBtn"
                            onclick="nextStep()">
                        Next
                        <i class="bx bx-chevron-right"></i>
                    </button>

                    <button type="submit"
                            class="modern-btn modern-btn-success d-none"
                            id="submitBtn">
                        <i class="bx bx-check-circle"></i>
                        Update User
                    </button>
                </div>
            </form>

            <!-- Back Link -->
            <div class="text-center mt-4 pb-3">
                <a href="{{ route('users.index') }}" class="back-link">
                    <i class="bx bx-arrow-back"></i>
                    Back to Users List
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 3;
const regionsData = @json($regions);

document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    setupRegionDistrictFilter();
    setupValidation();
});

function showStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
    document.getElementById(`step-${step}`).classList.remove('d-none');
    updateStepIndicators(step);
    updateNavigationButtons(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepIndicators(activeStep) {
    for (let i = 0; i < totalSteps; i++) {
        const indicator = document.getElementById(`step-indicator-${i}`);
        const circle = indicator.querySelector('.step-circle');

        indicator.classList.remove('active', 'completed');

        if (i < activeStep) {
            indicator.classList.add('completed');
            circle.innerHTML = '<i class="bx bx-check"></i>';
        } else if (i === activeStep) {
            indicator.classList.add('active');
            circle.textContent = i + 1;
        } else {
            circle.textContent = i + 1;
        }
    }
}

function updateNavigationButtons(step) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    prevBtn.style.display = step === 0 ? 'none' : 'flex';
    nextBtn.style.display = step === totalSteps - 1 ? 'none' : 'flex';
    submitBtn.classList.toggle('d-none', step !== totalSteps - 1);
}

function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
        }
    } else {
        const stepElement = document.getElementById(`step-${currentStep}`);
        stepElement.style.animation = 'none';
        setTimeout(() => {
            stepElement.style.animation = 'slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        }, 10);
    }
}

function previousStep() {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
}

function validateStep(step) {
    const stepElement = document.getElementById(`step-${step}`);
    const inputs = stepElement.querySelectorAll('input[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    if (field.hasAttribute('required') && !value) {
        errorMessage = 'This field is required';
        isValid = false;
    }

    if (isValid && value) {
        switch (field.type) {
            case 'email':
                if (!isValidEmail(value)) {
                    errorMessage = 'Please enter a valid email';
                    isValid = false;
                }
                break;
            case 'text':
                if (field.id === 'phone_number') {
                    if (!validatePhoneNumber(value)) {
                        errorMessage = 'Phone must start with 255 and be 12 digits';
                        isValid = false;
                    }
                }
                break;
        }
    }

    if (isValid) {
        setFieldSuccess(field);
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

    const feedback = field.parentElement.querySelector('.invalid-feedback');
    if (feedback && message) {
        feedback.textContent = message;
    }
}

function setFieldSuccess(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
}

function setupValidation() {
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        let hasInteracted = false;

        field.addEventListener('input', () => {
            hasInteracted = true;
            if (hasInteracted) {
                validateField(field);
            }
        });

        field.addEventListener('change', () => {
            hasInteracted = true;
            if (hasInteracted) {
                validateField(field);
            }
        });
    });

    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 0 && !value.startsWith('255')) {
                value = '255' + value.replace(/^255/, '');
            }

            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            e.target.value = value;
        });
    }
}

function setupRegionDistrictFilter() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');

    regionSelect.addEventListener('change', function() {
        const regionId = this.value;

        districtSelect.innerHTML = '<option value="">CHOOSE DISTRICT...</option>';

        if (regionId) {
            const selectedRegion = regionsData.find(r => r.id == regionId);

            if (selectedRegion && selectedRegion.districts && selectedRegion.districts.length > 0) {
                selectedRegion.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name.toUpperCase();
                    districtSelect.appendChild(option);
                });
            }
        }

        districtSelect.classList.remove('is-valid', 'is-invalid');
    });
}

document.getElementById('userEditForm').addEventListener('submit', function(e) {
    if (!validateStep(currentStep)) {
        e.preventDefault();
        return false;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> UPDATING...';
    submitBtn.disabled = true;
});
</script>

@endsection
