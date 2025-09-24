@extends('layouts.app')
@section('title', 'Edit Enquiry')
@section('content')

<style>
    /* URA SACCOS Color Variables */
    :root {
        --ura-primary: #17479e;
        --ura-primary-dark: #0d2c5f;
        --ura-primary-light: #1f5bb8;
        --ura-accent: #87CEEB;
        --ura-accent-light: #a4d9ee;
        --ura-purple: #764ba2;
        --ura-gradient-1: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);
        --ura-gradient-2: linear-gradient(135deg, #17479e 0%, #87CEEB 100%);
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-white: #ffffff;
        --ura-bg-light: #f8f9fa;
        --ura-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);
    }

    /* Breadcrumb Styles */
    .modern-breadcrumb {
        background: var(--ura-white);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--ura-shadow);
        border: 1px solid rgba(13, 42, 90, 0.1);
    }

    .breadcrumb {
        margin-bottom: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-item {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--ura-accent);
        font-weight: 600;
    }

    .breadcrumb-item.active {
        color: var(--ura-primary);
        font-weight: 600;
    }

    .breadcrumb-item a {
        color: var(--ura-accent);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--ura-primary);
    }

    /* Modern Form Container */
    .modern-form-container {
        background: var(--ura-white);
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        border: 1px solid rgba(13, 42, 90, 0.08);
    }

    /* Compact Header */
    .form-header {
        background: var(--ura-gradient-2);
        padding: 1.5rem 2rem;
        color: var(--ura-white);
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.15)"/><circle cx="30" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }

    .form-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .form-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    /* Progress Steps - Compact */
    .step-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1.5rem 2rem;
        background: rgba(13, 42, 90, 0.02);
        border-bottom: 1px solid rgba(13, 42, 90, 0.1);
        gap: 1rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        background: rgba(13, 42, 90, 0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .step-item.active {
        background: var(--ura-gradient-2);
        color: var(--ura-white);
        box-shadow: 0 4px 12px rgba(13, 42, 90, 0.2);
    }

    .step-item.completed {
        background: var(--ura-success);
        color: var(--ura-white);
        box-shadow: 0 4px 12px rgba(16, 220, 96, 0.3);
    }

    .step-item.completed .step-number {
        background: rgba(255, 255, 255, 0.2);
    }

    .step-item.completed .step-number::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 14px;
    }

    .step-item.completed .step-number-text {
        display: none;
    }

    .step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
        transition: all 0.3s ease;
        position: relative;
    }

    .step-item.active .step-number {
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .step-title {
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Form Sections */
    .form-section {
        display: none;
        padding: 2rem;
        animation: fadeInUp 0.4s ease;
    }

    .form-section.active {
        display: block;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ura-primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--ura-accent-light);
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: var(--ura-accent);
    }

    /* Modern Form Controls */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--ura-white);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--ura-accent);
        box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
        outline: none;
        background: var(--ura-white);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: var(--ura-danger) !important;
        box-shadow: 0 0 0 3px rgba(240, 65, 65, 0.1) !important;
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
    }

    .form-control[readonly] {
        background: #f8f9fa;
        border-color: #e2e8f0;
        color: #6b7280;
    }

    /* Monetary Input Styling */
    .monetary-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .currency-prefix {
        position: absolute;
        left: 1rem;
        color: var(--ura-accent);
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 1;
    }

    .monetary-wrapper .form-control {
        padding-left: 3rem;
    }

    /* Error Messages */
    .error-message {
        color: var(--ura-danger);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: none;
        font-weight: 500;
    }

    .error-message.show {
        display: block;
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Navigation Buttons */
    .form-navigation {
        padding: 1.5rem 2rem;
        background: rgba(13, 42, 90, 0.02);
        border-top: 1px solid rgba(13, 42, 90, 0.1);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-modern {
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        min-width: 120px;
        justify-content: center;
    }

    .btn-primary {
        background: var(--ura-gradient-2);
        color: var(--ura-white);
        box-shadow: 0 4px 12px rgba(13, 42, 90, 0.2);
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 42, 90, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--ura-primary);
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover:not(:disabled) {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Button Loading State */
    .btn-spinner {
        display: none;
    }

    .btn-modern.loading .btn-text {
        display: none;
    }

    .btn-modern.loading .btn-spinner {
        display: inline-block;
    }

    .btn-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced Review Section */
    .review-card {
        background: linear-gradient(145deg, var(--ura-white), #f8f9ff);
        border: 1px solid rgba(23, 71, 158, 0.1);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(13, 42, 90, 0.08);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .review-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--ura-gradient-2);
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(13, 42, 90, 0.15);
        border-color: var(--ura-accent);
    }

    .review-section-title {
        color: var(--ura-primary);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 2px solid rgba(23, 71, 158, 0.1);
        padding-bottom: 0.75rem;
    }

    .review-section-title::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--ura-accent);
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.3);
    }

    .review-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(23, 71, 158, 0.08);
        transition: all 0.3s ease;
    }

    .review-item:hover {
        background: rgba(23, 71, 158, 0.02);
        border-radius: 8px;
        margin: 0 -0.5rem;
        padding: 0.75rem 0.5rem;
        border-color: var(--ura-accent);
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-label {
        font-weight: 600;
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .review-label::before {
        content: '•';
        color: var(--ura-accent);
        font-weight: bold;
    }

    .review-value {
        color: #475569;
        text-align: right;
        font-weight: 500;
        max-width: 60%;
        word-wrap: break-word;
        background: rgba(135, 206, 235, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .review-highlight {
        background: var(--ura-gradient-1);
        color: var(--ura-white);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        text-align: center;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .review-summary {
        background: var(--ura-gradient-2);
        color: var(--ura-white);
        padding: 1.5rem;
        border-radius: 16px;
        margin: 2rem 0;
        text-align: center;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.25);
    }

    .review-summary h5 {
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .review-summary p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    /* Alert Styles */
    .alert {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
    }

    .alert-danger {
        background: linear-gradient(135deg, var(--ura-danger) 0%, #dc2626 100%);
        color: var(--ura-white);
        box-shadow: 0 4px 12px rgba(240, 65, 65, 0.2);
    }

    .alert-info {
        background: linear-gradient(135deg, var(--ura-accent) 0%, #0891b2 100%);
        color: var(--ura-white);
        box-shadow: 0 4px 12px rgba(0, 188, 212, 0.2);
    }

    /* Phone validation styling */
    .phone-status {
        font-size: 0.8rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .phone-status.valid {
        color: var(--ura-success);
    }

    .phone-status.invalid {
        color: var(--ura-danger);
    }

    /* Type Specific Fields */
    .type-fields {
        display: none;
        background: rgba(135, 206, 235, 0.08);
        border: 1px solid rgba(135, 206, 235, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .type-fields.show {
        display: block;
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            padding: 0 1.5rem;
        }
        to {
            opacity: 1;
            max-height: 500px;
            padding: 1.5rem;
        }
    }

    .type-fields-title {
        color: var(--ura-accent);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    /* Monetary Input Wrapper */
    .monetary-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .currency-prefix {
        position: absolute;
        left: 12px;
        z-index: 2;
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .monetary-input {
        padding-left: 45px !important;
    }

    /* File Upload Styles */
    .file-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: rgba(248, 250, 252, 0.8);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .file-upload-area:hover {
        border-color: var(--ura-primary);
        background: rgba(23, 71, 158, 0.02);
    }

    .file-upload-area.dragover {
        border-color: var(--ura-primary);
        background: rgba(23, 71, 158, 0.05);
        transform: scale(1.02);
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--ura-accent);
        margin-bottom: 1rem;
    }

    .upload-text {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #64748b;
    }

    .upload-browse {
        color: var(--ura-primary);
        font-weight: 600;
    }

    .upload-hint {
        font-size: 0.875rem;
        color: #94a3b8;
        margin: 0;
    }

    .selected-file {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(16, 220, 96, 0.1);
        border: 1px solid var(--ura-success);
        border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-header {
            padding: 1rem 1.5rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .step-progress {
            padding: 1rem;
            gap: 0.5rem;
        }

        .step-title {
            display: none;
        }

        .form-navigation {
            padding: 1rem 1.5rem;
            flex-direction: column;
        }

        .btn-modern {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="modern-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('enquiries.index') }}">Enquiries</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Enquiry #{{ $enquiry->id }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form Container -->
    <div class="modern-form-container">
        <!-- Compact Header -->
        <div class="form-header">
            <h3><i class="fas fa-edit me-2"></i>Edit Enquiry #{{ $enquiry->id }}</h3>
            <p>Update your enquiry information as needed</p>
        </div>

        <!-- Progress Steps -->
        <div class="step-progress">
            <div class="step-item active" data-step="1">
                <div class="step-number">
                    <span class="step-number-text">1</span>
                </div>
                <div class="step-title"><i class="fas fa-user me-2"></i>Member Details</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-number">
                    <span class="step-number-text">2</span>
                </div>
                <div class="step-title"><i class="fas fa-question-circle me-2"></i>Enquiry Type</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-number">
                    <span class="step-number-text">3</span>
                </div>
                <div class="step-title"><i class="fas fa-paperclip me-2"></i>Documents</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-number">
                    <span class="step-number-text">4</span>
                </div>
                <div class="step-title"><i class="fas fa-eye me-2"></i>Review</div>
            </div>
        </div>

        <form method="POST" action="{{ route('enquiries.update', $enquiry->id) }}" id="editEnquiryForm">
            @csrf
            @method('PUT')

            <!-- Step 1: Member Details -->
            <div class="form-section active" id="step-1">
                <h4 class="section-title">Member Personal Information</h4>

                <div class="row g-4">
                    <!-- Date Received (Read-only) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Date Received <span class="text-muted">(Cannot be modified)</span>
                            </label>
                            <input type="text" class="form-control" id="date_received"
                                   value="{{ $enquiry->date_received }}" readonly>
                            <div class="error-message" id="date_received_error"></div>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                   value="{{ $enquiry->full_name }}" required>
                            <div class="error-message" id="full_name_error"></div>
                        </div>
                    </div>

                    <!-- Membership Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Membership Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="force_no" name="force_no"
                                   value="{{ $enquiry->force_no }}" required>
                            <div class="error-message" id="force_no_error"></div>
                        </div>
                    </div>

                    <!-- Check Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Check Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="check_number" name="check_number"
                                   value="{{ $enquiry->check_number }}" required>
                            <div class="error-message" id="check_number_error"></div>
                        </div>
                    </div>

                    <!-- Bank Account Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Bank Account Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                   value="{{ $enquiry->account_number }}" required>
                            <div class="error-message" id="account_number_error"></div>
                        </div>
                    </div>

                    <!-- Bank Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Bank Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name"
                                   value="{{ $enquiry->bank_name }}" required>
                            <div class="error-message" id="bank_name_error"></div>
                        </div>
                    </div>

                    <!-- Basic Salary -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Basic Salary <span class="text-danger">*</span>
                            </label>
                            <div class="monetary-wrapper">
                                <span class="currency-prefix">TSH</span>
                                <input type="text" class="form-control monetary-input" id="basic_salary" name="basic_salary"
                                       value="{{ number_format($enquiry->basic_salary ?? 0, 2) }}" required>
                            </div>
                            <div class="error-message" id="basic_salary_error"></div>
                        </div>
                    </div>

                    <!-- Allowances -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Allowances <span class="text-danger">*</span>
                            </label>
                            <div class="monetary-wrapper">
                                <span class="currency-prefix">TSH</span>
                                <input type="text" class="form-control monetary-input" id="allowances" name="allowances"
                                       value="{{ number_format($enquiry->allowances ?? 0, 2) }}" required>
                            </div>
                            <div class="error-message" id="allowances_error"></div>
                        </div>
                    </div>

                    <!-- Take Home Pay -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Take Home Pay <span class="text-danger">*</span>
                            </label>
                            <div class="monetary-wrapper">
                                <span class="currency-prefix">TSH</span>
                                <input type="text" class="form-control monetary-input" id="take_home" name="take_home"
                                       value="{{ number_format($enquiry->take_home ?? 0, 2) }}" required>
                            </div>
                            <div class="error-message" id="take_home_error"></div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="{{ $enquiry->phone }}" required>
                            <div class="phone-status" id="phone_status"></div>
                            <div class="error-message" id="phone_error"></div>
                        </div>
                    </div>

                    <!-- Region (Read-only) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Region</label>
                            <input type="text" class="form-control"
                                   value="{{ $enquiry->region->name ?? 'Not specified' }}" readonly>
                            <input type="hidden" name="region_id" value="{{ $enquiry->region_id }}">
                        </div>
                    </div>

                    <!-- District (Read-only) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control"
                                   value="{{ $enquiry->district->name ?? 'Not specified' }}" readonly>
                            <input type="hidden" name="district_id" value="{{ $enquiry->district_id }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Enquiry Type -->
            <div class="form-section" id="step-2">
                <h4 class="section-title">Enquiry Type & Information</h4>

                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                Enquiry Type <span class="text-danger">*</span>
                            </label>
                            <select name="type" id="enquiry_type" class="form-select" required>
                                <option value="">Choose an enquiry type</option>
                                <option value="loan_application" {{ $enquiry->type === 'loan_application' ? 'selected' : '' }}>Loan Application</option>
                                <option value="refund" {{ $enquiry->type === 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="share_enquiry" {{ $enquiry->type === 'share_enquiry' ? 'selected' : '' }}>Share Enquiry</option>
                                <option value="retirement" {{ $enquiry->type === 'retirement' ? 'selected' : '' }}>Retirement</option>
                                <option value="deduction_add" {{ $enquiry->type === 'deduction_add' ? 'selected' : '' }}>Add Deduction of Savings</option>
                                <option value="withdraw_savings" {{ $enquiry->type === 'withdraw_savings' ? 'selected' : '' }}>Withdraw Savings</option>
                                <option value="withdraw_deposit" {{ $enquiry->type === 'withdraw_deposit' ? 'selected' : '' }}>Withdraw Deposit</option>
                                <option value="unjoin_membership" {{ $enquiry->type === 'unjoin_membership' ? 'selected' : '' }}>Unjoin Membership</option>
                                <option value="ura_mobile" {{ $enquiry->type === 'ura_mobile' ? 'selected' : '' }}>URA Mobile</option>
                                <option value="sick_for_30_days" {{ $enquiry->type === 'sick_for_30_days' ? 'selected' : '' }}>Sick Leave (30+ Days)</option>
                                <option value="condolences" {{ $enquiry->type === 'condolences' ? 'selected' : '' }}>Condolences</option>
                                <option value="injured_at_work" {{ $enquiry->type === 'injured_at_work' ? 'selected' : '' }}>Work Injury</option>
                                <option value="residential_disaster" {{ $enquiry->type === 'residential_disaster' ? 'selected' : '' }}>Residential Disaster</option>
                                <option value="join_membership" {{ $enquiry->type === 'join_membership' ? 'selected' : '' }}>Join Membership</option>
                            </select>
                            <div class="error-message" id="enquiry_type_error"></div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Type-Specific Fields -->
                <div class="type-fields" id="loanFields">
                    <h6 class="type-fields-title"><i class="fas fa-money-bill-wave me-2"></i>Loan Application Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Loan Category <span class="text-danger">*</span></label>
                                <select name="loan_category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="salary_loan" {{ (isset($enquiry->loanApplication) && $enquiry->loanApplication->loan_category === 'salary_loan') ? 'selected' : '' }}>Salary Loan</option>
                                    <option value="cash_loan" {{ (isset($enquiry->loanApplication) && $enquiry->loanApplication->loan_category === 'cash_loan') ? 'selected' : '' }}>Cash Loan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Loan Purpose <span class="text-danger">*</span></label>
                                <select name="loan_type" class="form-select" required>
                                    <option value="">Select Purpose</option>
                                    @php $loanType = $enquiry->loanApplication->loan_type ?? '' @endphp
                                    <option value="business" {{ $loanType === 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="education" {{ $loanType === 'education' ? 'selected' : '' }}>Education</option>
                                    <option value="medical" {{ $loanType === 'medical' ? 'selected' : '' }}>Medical</option>
                                    <option value="vehicle" {{ $loanType === 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                                    <option value="agriculture" {{ $loanType === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                    <option value="emergency" {{ $loanType === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    <option value="wedding" {{ $loanType === 'wedding' ? 'selected' : '' }}>Wedding</option>
                                    <option value="vacation" {{ $loanType === 'vacation' ? 'selected' : '' }}>Vacation</option>
                                    <option value="funeral" {{ $loanType === 'funeral' ? 'selected' : '' }}>Funeral</option>
                                    <option value="furniture" {{ $loanType === 'furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="construction" {{ $loanType === 'construction' ? 'selected' : '' }}>Construction</option>
                                    <option value="other" {{ $loanType === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Requested Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="loan_amount" class="form-control monetary-input" placeholder="0.00" value="{{ isset($enquiry->loanApplication) ? number_format($enquiry->loanApplication->loan_amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                <input type="number" name="loan_duration" class="form-control" placeholder="Enter months" value="{{ $enquiry->loanApplication->loan_duration ?? '' }}" min="1" max="60" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="shareFields">
                    <h6 class="type-fields-title"><i class="fas fa-chart-line me-2"></i>Share Enquiry Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Share Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="share_amount" class="form-control monetary-input" placeholder="0.00" value="{{ isset($enquiry->share) ? number_format($enquiry->share->share_amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="retirementFields">
                    <h6 class="type-fields-title"><i class="fas fa-user-clock me-2"></i>Retirement Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Expected Retirement Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_retirement" class="form-control" value="{{ $enquiry->retirement->date_of_retirement ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="deductionFields">
                    <h6 class="type-fields-title"><i class="fas fa-calculator me-2"></i>Deduction Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">From Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="from_amount" class="form-control monetary-input" placeholder="0.00" value="{{ isset($enquiry->deduction) ? number_format($enquiry->deduction->from_amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">To Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="to_amount" class="form-control monetary-input" placeholder="0.00" value="{{ isset($enquiry->deduction) ? number_format($enquiry->deduction->to_amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="refundFields">
                    <h6 class="type-fields-title"><i class="fas fa-undo me-2"></i>Refund Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Refund Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="refund_amount" class="form-control monetary-input" placeholder="0.00" value="{{ isset($enquiry->refund) ? number_format($enquiry->refund->refund_amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Refund Duration <span class="text-danger">*</span></label>
                                <input type="number" name="refund_duration" class="form-control" placeholder="Enter duration" value="{{ $enquiry->refund->refund_duration ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="withdrawSavingsFields">
                    <h6 class="type-fields-title"><i class="fas fa-piggy-bank me-2"></i>Withdraw Savings Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Withdraw Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    @php $savingsWithdrawal = $enquiry->withdrawals->where('type', 'savings')->first() @endphp
                                    <input type="text" name="withdraw_saving_amount" class="form-control monetary-input" placeholder="0.00" value="{{ $savingsWithdrawal ? number_format($savingsWithdrawal->amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <input type="text" name="withdraw_saving_reason" class="form-control" placeholder="Reason for withdrawal" value="{{ $savingsWithdrawal->reason ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="withdrawDepositFields">
                    <h6 class="type-fields-title"><i class="fas fa-university me-2"></i>Withdraw Deposit Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Withdraw Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    @php $depositWithdrawal = $enquiry->withdrawals->where('type', 'deposit')->first() @endphp
                                    <input type="text" name="withdraw_deposit_amount" class="form-control monetary-input" placeholder="0.00" value="{{ $depositWithdrawal ? number_format($depositWithdrawal->amount ?? 0, 2) : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <input type="text" name="withdraw_deposit_reason" class="form-control" placeholder="Reason for withdrawal" value="{{ $depositWithdrawal->reason ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="unjoinMembershipFields">
                    <h6 class="type-fields-title"><i class="fas fa-user-times me-2"></i>Membership Termination Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                @php $membershipChange = $enquiry->membershipChanges->where('action', 'unjoin')->first() @endphp
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="normal" {{ ($membershipChange && $membershipChange->category === 'normal') ? 'selected' : '' }}>Normal</option>
                                    <option value="job_termination" {{ ($membershipChange && $membershipChange->category === 'job_termination') ? 'selected' : '' }}>Job Termination</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="uraMobileFields">
                    <h6 class="type-fields-title"><i class="fas fa-mobile-alt me-2"></i>URA Mobile Details</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                URA Mobile service registration - no additional details required.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="sickLeaveFields">
                    <h6 class="type-fields-title"><i class="fas fa-bed me-2"></i>Sick Leave Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="startdate" class="form-control" value="{{ $enquiry->sickLeave->startdate ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="enddate" class="form-control" value="{{ $enquiry->sickLeave->enddate ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="condolenceFields">
                    <h6 class="type-fields-title"><i class="fas fa-heart me-2"></i>Condolence Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Beneficiary Type <span class="text-danger">*</span></label>
                                <select name="dependent_member_type" class="form-select" required>
                                    <option value="">Select Beneficiary</option>
                                    <option value="dependent_child" {{ ($enquiry->condolence && $enquiry->condolence->dependent_member_type === 'dependent_child') ? 'selected' : '' }}>Dependent Child</option>
                                    <option value="dependent_spouse" {{ ($enquiry->condolence && $enquiry->condolence->dependent_member_type === 'dependent_spouse') ? 'selected' : '' }}>Dependent Spouse</option>
                                    <option value="member" {{ ($enquiry->condolence && $enquiry->condolence->dependent_member_type === 'member') ? 'selected' : '' }}>Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="male" id="gender_male" {{ ($enquiry->condolence && $enquiry->condolence->gender === 'male') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="female" id="gender_female" {{ ($enquiry->condolence && $enquiry->condolence->gender === 'female') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender_female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="injuryFields">
                    <h6 class="type-fields-title"><i class="fas fa-band-aid me-2"></i>Work Injury Details</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Description (max 100 words) <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Please describe the injury details..." required>{{ $enquiry->injury->description ?? '' }}</textarea>
                                <small class="form-text text-muted">Please enter up to 100 words.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="disasterFields">
                    <h6 class="type-fields-title"><i class="fas fa-house-damage me-2"></i>Residential Disaster Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Disaster Cause <span class="text-danger">*</span></label>
                                <select name="disaster_type" class="form-select" required>
                                    <option value="">Select Disaster Cause</option>
                                    <option value="fire" {{ ($enquiry->residentialDisaster && $enquiry->residentialDisaster->disaster_type === 'fire') ? 'selected' : '' }}>Fire</option>
                                    <option value="hurricane" {{ ($enquiry->residentialDisaster && $enquiry->residentialDisaster->disaster_type === 'hurricane') ? 'selected' : '' }}>Hurricane</option>
                                    <option value="flood" {{ ($enquiry->residentialDisaster && $enquiry->residentialDisaster->disaster_type === 'flood') ? 'selected' : '' }}>Flood</option>
                                    <option value="earthquake" {{ ($enquiry->residentialDisaster && $enquiry->residentialDisaster->disaster_type === 'earthquake') ? 'selected' : '' }}>Earthquake</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="type-fields" id="joinMembershipFields">
                    <h6 class="type-fields-title"><i class="fas fa-user-plus me-2"></i>Join Membership Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Membership Status <span class="text-danger">*</span></label>
                                @php $joinMembership = $enquiry->membershipChanges->where('action', 'join')->first() @endphp
                                <select name="membership_status" class="form-select" required>
                                    <option value="">Select Membership Status</option>
                                    <option value="police_officer" {{ ($joinMembership && $joinMembership->membership_status === 'police_officer') ? 'selected' : '' }}>Police Officer</option>
                                    <option value="civilian" {{ ($joinMembership && $joinMembership->membership_status === 'civilian') ? 'selected' : '' }}>Civilian</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Documents -->
            <div class="form-section" id="step-3">
                <h4 class="section-title">Document Management</h4>

                <div class="row g-4">
                    @if($enquiry->file_path)
                    <!-- Current Document Section - Show First -->
                    <div class="col-12">
                        <div class="current-document-section" style="background: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(135, 206, 235, 0.05) 100%); border-radius: 12px; padding: 1.5rem; border: 2px solid rgba(23, 71, 158, 0.1); margin-bottom: 2rem;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="current-file-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #17479e 0%, #87CEEB 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                    <i class="fas fa-file-pdf" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; color: #17479e; font-weight: 600;">Current Document</h5>
                                    <p style="margin: 0; color: #64748b; font-size: 0.9rem;">This is the document currently attached to this enquiry</p>
                                </div>
                            </div>

                            <div class="current-file-info" style="background: white; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border: 1px solid rgba(23, 71, 158, 0.1);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger fa-lg me-3"></i>
                                        <div>
                                            <div class="fw-bold" style="color: #1e293b;">{{ basename($enquiry->file_path) }}</div>
                                            <small class="text-muted">Uploaded: {{ $enquiry->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ asset('storage/' . $enquiry->file_path) }}" target="_blank" class="btn btn-sm" style="background: #17479e; color: white; margin-right: 0.5rem;">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <a href="{{ asset('storage/' . $enquiry->file_path) }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Preview -->
                            <div class="document-preview-section" style="background: white; border-radius: 8px; padding: 1rem; border: 1px solid rgba(23, 71, 158, 0.1);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-eye me-2" style="color: #17479e;"></i>
                                    <span style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">Document Preview</span>
                                </div>
                                <div class="pdf-preview-frame">
                                    <iframe src="{{ asset('storage/' . $enquiry->file_path) }}" width="100%" height="400" style="border: 1px solid #dee2e6; border-radius: 8px;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="col-12">
                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1" style="border-color: #e2e8f0;">
                            <span style="padding: 0 1rem; color: #64748b; font-weight: 500; background: white;">OR</span>
                            <hr class="flex-grow-1" style="border-color: #e2e8f0;">
                        </div>
                    </div>
                    @endif

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                @if($enquiry->file_path)
                                    Replace Document (Optional)
                                @else
                                    Upload Document
                                @endif
                            </label>
                            <div class="file-upload-area">
                                <input type="file" name="file_path" id="file_path" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="file-upload-content">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <p class="upload-text">
                                        @if($enquiry->file_path)
                                            Drop new file here or <span class="upload-browse">browse</span> to replace
                                        @else
                                            Drop file here or <span class="upload-browse">browse</span>
                                        @endif
                                    </p>
                                    <p class="upload-hint">PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</p>
                                </div>
                            </div>
                            <div class="selected-file" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file me-2"></i>
                                    <span class="file-name"></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto remove-file">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Reference Section -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                File Reference <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="file_id" name="file_id" required>
                                <option value="">Select File Reference</option>
                                @if(isset($files))
                                    @foreach ($files as $file)
                                        <option value="{{ $file->id }}" {{ $enquiry->file_id == $file->id ? 'selected' : '' }}>
                                            {{ $file->reference_number }} - {{ $file->file_subject }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="error-message" id="file_id_error"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div class="form-section" id="step-4">
                <h4 class="section-title">Review Your Changes</h4>

                <div id="review_content">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-eye fa-3x mb-3"></i>
                        <p>Complete previous steps to review your changes</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirm_update" required>
                        <label class="form-check-label" for="confirm_update">
                            <strong>I confirm that all the updated information is accurate</strong>
                        </label>
                    </div>
                </div>
            </div>

        </form>

        <!-- Navigation -->
        <div class="form-navigation">
            <button type="button" class="btn-modern btn-secondary" id="prev_btn" onclick="previousStep()" style="display: none;">
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            <div class="ms-auto d-flex gap-2">
                <button type="button" class="btn-modern btn-primary" id="next_btn" onclick="nextStep()">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="btn-modern btn-primary" id="update_btn" onclick="submitForm()" style="display: none;">
                    <span class="btn-text">
                        <i class="fas fa-save"></i> Update Enquiry
                    </span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Updating...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    let currentStep = 1;
    const totalSteps = 4;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupEventListeners();
    });

    function initializeForm() {
        updateStepDisplay();
        initializeMonetaryInputs();
        initializePhoneValidation();
        initializeFileUpload();

        // Initialize enquiry type fields display
        const enquiryTypeSelect = document.getElementById('enquiry_type');
        if (enquiryTypeSelect && enquiryTypeSelect.value) {
            toggleTypeFields(enquiryTypeSelect.value);
        }

        // Set initial phone status
        const phoneInput = document.getElementById('phone');
        if (phoneInput.value) {
            updatePhoneStatus(phoneInput.value);
        }

        // Initialize type fields display
        const enquiryType = document.getElementById('enquiry_type');
        if (enquiryType && enquiryType.value) {
            toggleTypeFields(enquiryType.value);
        }
    }

    function setupEventListeners() {
        // Enquiry type change
        document.getElementById('enquiry_type').addEventListener('change', function() {
            toggleTypeFields(this.value);
        });

        // Only validate on form submission, not on blur/input
        // Remove real-time validation to prevent premature error messages
    }

    function nextStep() {
        // Simple validation for required fields only
        const currentSection = document.getElementById(`step-${currentStep}`);
        if (!currentSection) return;

        // Get only the visible required fields in the current step
        let requiredFields = [];
        if (currentStep === 1) {
            // Step 1: Basic required fields
            requiredFields = currentSection.querySelectorAll('input[required]:not([readonly]), select[required]:not([readonly])');
        } else if (currentStep === 2) {
            // Step 2: Enquiry type is required
            const enquiryTypeField = document.getElementById('enquiry_type');
            if (!enquiryTypeField.value) {
                showModernValidationDialog('Please select an enquiry type', 'warning');
                return;
            }
        }

        // Simple validation check
        let isValid = true;
        for (let field of requiredFields) {
            if (!field.value || field.value.trim() === '') {
                isValid = false;
                field.focus();
                showModernValidationDialog(`Please fill in the ${field.placeholder || field.name || 'required field'}`, 'warning');
                break;
            }
        }

        if (isValid && currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
            if (currentStep === 4) {
                generateReview();
            }
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    function updateStepDisplay() {
        // Hide all sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show current section
        document.getElementById(`step-${currentStep}`).classList.add('active');

        // Update step indicators
        document.querySelectorAll('.step-item').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 < currentStep) {
                step.classList.add('completed');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });

        // Update navigation buttons
        const prevBtn = document.getElementById('prev_btn');
        const nextBtn = document.getElementById('next_btn');
        const updateBtn = document.getElementById('update_btn');

        prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
        nextBtn.style.display = currentStep < totalSteps ? 'block' : 'none';
        updateBtn.style.display = currentStep === totalSteps ? 'block' : 'none';

        // Scroll to top
        document.querySelector('.modern-form-container').scrollIntoView({ behavior: 'smooth' });
    }


    function validateField(field) {
        const value = field.value ? field.value.trim() : '';
        let isValid = true;
        let errorMessage = '';

        // Clear previous validation
        clearFieldError(field);

        // Skip validation for hidden or readonly fields
        if (field.style.display === 'none' || field.hasAttribute('readonly')) {
            console.log('Skipping hidden/readonly field:', field.name || field.id);
            return true;
        }

        // Skip validation for fields inside hidden type-fields
        const parentTypeField = field.closest('.type-fields');
        if (parentTypeField && !parentTypeField.classList.contains('show')) {
            console.log('Skipping field in hidden type section:', field.name || field.id);
            return true;
        }

        if (field.hasAttribute('required')) {
            if (field.type === 'radio') {
                // For radio buttons, check if any in the group is checked
                const radioGroup = document.querySelectorAll(`[name="${field.name}"]`);
                const checked = Array.from(radioGroup).some(radio => radio.checked);
                if (!checked) {
                    errorMessage = 'Please select an option';
                    isValid = false;
                }
            } else if (!value) {
                errorMessage = 'This field is required';
                isValid = false;
            } else if (field.id === 'phone' && value && !isValidPhone(value)) {
                errorMessage = 'Please enter a valid phone number (255XXXXXXXXX)';
                isValid = false;
            }
        }

        if (!isValid) {
            console.log('Field validation failed:', field.name || field.id, errorMessage);
            showFieldError(field, errorMessage);
        } else {
            showFieldSuccess(field);
        }

        return isValid;
    }

    function showFieldError(field, message) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');

        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.add('show');
        }
    }

    function showFieldSuccess(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid', 'is-valid');

        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.classList.remove('show');
        }
    }

    function initializeMonetaryInputs() {
        document.querySelectorAll('.monetary-input').forEach(input => {
            input.addEventListener('input', function() {
                formatMonetaryInput(this);
            });

            input.addEventListener('blur', function() {
                if (this.value) {
                    const numericValue = parseFloat(this.value.replace(/,/g, ''));
                    if (!isNaN(numericValue)) {
                        this.value = numericValue.toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                }
            });
        });
    }

    function formatMonetaryInput(input) {
        let value = input.value.replace(/[^\d.]/g, '');

        // Prevent multiple decimal points
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        // Add thousand separators
        if (value && !isNaN(value)) {
            const [integerPart, decimalPart] = value.split('.');
            if (integerPart) {
                const formattedInteger = parseInt(integerPart).toLocaleString('en-US');
                value = decimalPart !== undefined ? formattedInteger + '.' + decimalPart : formattedInteger;
            }
        }

        input.value = value;
    }

    function initializePhoneValidation() {
        const phoneInput = document.getElementById('phone');
        const phoneStatus = document.getElementById('phone_status');

        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');

            // Auto-add 255 prefix if not present
            if (value && !value.startsWith('255')) {
                value = '255' + value.replace(/^255/, '');
            }

            // Limit to 12 digits
            if (value.length > 12) {
                value = value.slice(0, 12);
            }

            this.value = value;
            updatePhoneStatus(value);
        });
    }

    function updatePhoneStatus(value) {
        const phoneStatus = document.getElementById('phone_status');

        if (value.length === 12) {
            phoneStatus.textContent = '✓ Valid phone number';
            phoneStatus.className = 'phone-status valid';
        } else if (value.length > 0) {
            phoneStatus.textContent = `${12 - value.length} more digits needed`;
            phoneStatus.className = 'phone-status invalid';
        } else {
            phoneStatus.textContent = '';
        }
    }

    function generateReview() {
        const reviewContent = document.getElementById('review_content');

        // Create comprehensive review summary for editing
        let html = `
            <div class="review-summary">
                <h5><i class="fas fa-edit me-2"></i>Updated Enquiry Summary</h5>
                <p>Please review all changes below before saving your enquiry updates</p>
            </div>
            <div class="row g-3">
        `;

        // Member Information - Enhanced
        const fullName = document.getElementById('full_name').value;
        const checkNumber = document.getElementById('check_number').value;
        const phone = document.getElementById('phone').value;
        const bankName = document.getElementById('bank_name').value;
        const basicSalary = document.getElementById('basic_salary').value;
        const allowances = document.getElementById('allowances')?.value || '';
        const takeHome = document.getElementById('take_home').value;

        html += `
            <div class="col-12">
                <div class="review-card">
                    <h6 class="review-section-title"><i class="fas fa-user me-2"></i>Updated Member Information</h6>
                    <div class="review-item">
                        <span class="review-label">Full Name</span>
                        <span class="review-value">${fullName}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Check Number</span>
                        <span class="review-value">${checkNumber}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Phone Number</span>
                        <span class="review-value">${phone}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Bank Name</span>
                        <span class="review-value">${bankName}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Basic Salary</span>
                        <span class="review-value">TSh ${parseFloat(basicSalary || 0).toLocaleString()}</span>
                    </div>
                    ${allowances ? `
                    <div class="review-item">
                        <span class="review-label">Allowances</span>
                        <span class="review-value">TSh ${parseFloat(allowances).toLocaleString()}</span>
                    </div>
                    ` : ''}
                    <div class="review-item">
                        <span class="review-label">Take Home</span>
                        <span class="review-value">TSh ${parseFloat(takeHome || 0).toLocaleString()}</span>
                    </div>
                </div>
            </div>
        `;

        // Enquiry Information - Enhanced
        const selectedType = document.getElementById('enquiry_type').value;
        const selectedTypeText = document.getElementById('enquiry_type').selectedOptions[0]?.text;
        if (selectedType) {
            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-question-circle me-2"></i>Enquiry Information</h6>
                        <div class="review-item">
                            <span class="review-label">Enquiry Type</span>
                            <span class="review-value">${selectedTypeText}</span>
                        </div>
            `;

            // Add type-specific details
            const typeSpecificHtml = getTypeSpecificReviewHTML(selectedType);
            if (typeSpecificHtml) {
                html += typeSpecificHtml;
            }

            html += `
                    </div>
                </div>
            `;
        }

        // Document Information - Enhanced
        const fileReference = document.getElementById('file_id');
        const fileUpload = document.getElementById('file_path');

        if ((fileReference && fileReference.value) || (fileUpload && fileUpload.files.length > 0)) {
            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-paperclip me-2"></i>Document Information</h6>
            `;

            if (fileReference && fileReference.value) {
                html += `
                        <div class="review-item">
                            <span class="review-label">Existing File Reference</span>
                            <span class="review-value">${fileReference.selectedOptions[0]?.text || 'Selected File'}</span>
                        </div>
                `;
            }

            if (fileUpload && fileUpload.files.length > 0) {
                const file = fileUpload.files[0];
                html += `
                        <div class="review-item">
                            <span class="review-label">New Document</span>
                            <span class="review-value">${file.name}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label">File Size</span>
                            <span class="review-value">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label">File Type</span>
                            <span class="review-value">PDF Document</span>
                        </div>
                `;
            }

            html += `
                    </div>
                </div>
            `;
        }

        // Final Review Highlight
        html += `
            <div class="col-12">
                <div class="review-highlight">
                    <i class="fas fa-check-circle me-2"></i>
                    All changes have been reviewed and are ready to be saved
                </div>
            </div>
        `;

        html += '</div>';
        reviewContent.innerHTML = html;
    }

    function showModernValidationDialog(message, type = 'warning') {
        // Remove any existing validation dialog
        const existingDialog = document.querySelector('.modern-validation-overlay');
        if (existingDialog) {
            existingDialog.remove();
        }

        // Create modal overlay
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'modern-validation-overlay';
        modalOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(13, 42, 90, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        `;

        // Create modal container
        const modalContainer = document.createElement('div');
        modalContainer.style.cssText = `
            background: linear-gradient(145deg, #ffffff, #f8f9ff);
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(13, 42, 90, 0.3);
            border: 1px solid rgba(135, 206, 235, 0.3);
            text-align: center;
            position: relative;
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        `;

        // Determine icon and color based on type
        let icon, iconColor, borderColor;
        switch(type) {
            case 'error':
                icon = 'fas fa-exclamation-circle';
                iconColor = '#f04141';
                borderColor = '#f04141';
                break;
            case 'success':
                icon = 'fas fa-check-circle';
                iconColor = '#10dc60';
                borderColor = '#10dc60';
                break;
            default: // warning
                icon = 'fas fa-exclamation-triangle';
                iconColor = '#ffce00';
                borderColor = '#ffce00';
        }

        modalContainer.innerHTML = `
            <div style="
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, ${iconColor}20, ${iconColor}10);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                border: 3px solid ${borderColor}40;
            ">
                <i class="${icon}" style="
                    font-size: 2.5rem;
                    color: ${iconColor};
                    animation: pulse 1.5s infinite;
                "></i>
            </div>

            <h4 style="
                color: #17479e;
                font-weight: 700;
                margin-bottom: 1rem;
                font-size: 1.3rem;
            ">Validation Required</h4>

            <p style="
                color: #475569;
                font-size: 1.1rem;
                line-height: 1.6;
                margin-bottom: 2rem;
            ">${message}</p>

            <button onclick="closeValidationDialog()" style="
                background: linear-gradient(135deg, #17479e, #87CEEB);
                color: white;
                border: none;
                padding: 0.75rem 2rem;
                border-radius: 25px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(23, 71, 158, 0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(23, 71, 158, 0.3)'">
                <i class="fas fa-check me-2"></i>
                Got It
            </button>
        `;

        modalOverlay.appendChild(modalContainer);
        document.body.appendChild(modalOverlay);

        // Add CSS animations if not already present
        if (!document.getElementById('validationDialogStyles')) {
            const style = document.createElement('style');
            style.id = 'validationDialogStyles';
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px) scale(0.9);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                }
            `;
            document.head.appendChild(style);
        }
    }

    function closeValidationDialog() {
        const dialog = document.querySelector('.modern-validation-overlay');
        if (dialog) {
            dialog.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                dialog.remove();
            }, 300);
        }
    }

    function getTypeSpecificReviewHTML(selectedType) {
        let html = '';

        switch(selectedType) {
            case 'loan_application':
                const loanCategory = document.querySelector('input[name="loan_category"]:checked')?.value || '';
                const loanAmount = document.querySelector('input[name="loan_amount"]')?.value || '';
                const loanDuration = document.querySelector('input[name="loan_duration"]')?.value || '';
                if (loanCategory) {
                    html += `<div class="review-item"><span class="review-label">Loan Category:</span><span class="review-value">${loanCategory.replace('_', ' ').toUpperCase()}</span></div>`;
                }
                if (loanAmount) {
                    html += `<div class="review-item"><span class="review-label">Loan Amount:</span><span class="review-value">TSH ${loanAmount}</span></div>`;
                }
                if (loanDuration) {
                    html += `<div class="review-item"><span class="review-label">Duration:</span><span class="review-value">${loanDuration} months</span></div>`;
                }
                break;

            case 'withdraw_savings':
                const savingsAmount = document.querySelector('input[name="withdraw_saving_amount"]')?.value || '';
                const savingsReason = document.querySelector('input[name="withdraw_saving_reason"]')?.value || '';
                if (savingsAmount) {
                    html += `<div class="review-item"><span class="review-label">Amount:</span><span class="review-value">TSH ${savingsAmount}</span></div>`;
                }
                if (savingsReason) {
                    html += `<div class="review-item"><span class="review-label">Reason:</span><span class="review-value">${savingsReason}</span></div>`;
                }
                break;

            case 'withdraw_deposit':
                const depositAmount = document.querySelector('input[name="withdraw_deposit_amount"]')?.value || '';
                const depositReason = document.querySelector('input[name="withdraw_deposit_reason"]')?.value || '';
                if (depositAmount) {
                    html += `<div class="review-item"><span class="review-label">Amount:</span><span class="review-value">TSH ${depositAmount}</span></div>`;
                }
                if (depositReason) {
                    html += `<div class="review-item"><span class="review-label">Reason:</span><span class="review-value">${depositReason}</span></div>`;
                }
                break;

            case 'retirement':
                const retirementDate = document.querySelector('input[name="date_of_retirement"]')?.value || '';
                if (retirementDate) {
                    html += `<div class="review-item"><span class="review-label">Retirement Date:</span><span class="review-value">${retirementDate}</span></div>`;
                }
                break;

            case 'refund':
                const refundAmount = document.querySelector('input[name="refund_amount"]')?.value || '';
                const refundDuration = document.querySelector('input[name="refund_duration"]')?.value || '';
                if (refundAmount) {
                    html += `<div class="review-item"><span class="review-label">Refund Amount:</span><span class="review-value">TSH ${refundAmount}</span></div>`;
                }
                if (refundDuration) {
                    html += `<div class="review-item"><span class="review-label">Duration:</span><span class="review-value">${refundDuration} months</span></div>`;
                }
                break;

            case 'share_enquiry':
                const shareAmount = document.querySelector('input[name="share_amount"]')?.value || '';
                if (shareAmount) {
                    html += `<div class="review-item"><span class="review-label">Share Amount:</span><span class="review-value">TSH ${shareAmount}</span></div>`;
                }
                break;

            case 'deduction_add':
                const fromAmount = document.querySelector('input[name="from_amount"]')?.value || '';
                const toAmount = document.querySelector('input[name="to_amount"]')?.value || '';
                if (fromAmount && toAmount) {
                    html += `<div class="review-item"><span class="review-label">Deduction Change:</span><span class="review-value">From TSH ${fromAmount} to TSH ${toAmount}</span></div>`;
                }
                break;

            case 'condolences':
                const dependentType = document.querySelector('select[name="dependent_member_type"]')?.value || '';
                const gender = document.querySelector('input[name="gender"]:checked')?.value || '';
                if (dependentType) {
                    html += `<div class="review-item"><span class="review-label">Dependent Type:</span><span class="review-value">${dependentType.replace('_', ' ').toUpperCase()}</span></div>`;
                }
                if (gender) {
                    html += `<div class="review-item"><span class="review-label">Gender:</span><span class="review-value">${gender.toUpperCase()}</span></div>`;
                }
                break;

            case 'injured_at_work':
                const injuryDescription = document.querySelector('textarea[name="description"]')?.value || '';
                if (injuryDescription) {
                    html += `<div class="review-item"><span class="review-label">Injury Description:</span><span class="review-value">${injuryDescription}</span></div>`;
                }
                break;

            case 'sick_for_30_days':
                const startDate = document.querySelector('input[name="startdate"]')?.value || '';
                const endDate = document.querySelector('input[name="enddate"]')?.value || '';
                if (startDate && endDate) {
                    html += `<div class="review-item"><span class="review-label">Sick Leave Period:</span><span class="review-value">From ${startDate} to ${endDate}</span></div>`;
                }
                break;
        }

        return html;
    }

    function submitForm() {
        if (!document.getElementById('confirm_update').checked) {
            showModernValidationDialog('Please confirm that all information is accurate before updating.', 'warning');
            return;
        }

        // Show loading state on button
        const updateBtn = document.getElementById('update_btn');
        updateBtn.classList.add('loading');
        updateBtn.disabled = true;

        // Show modern confirmation dialog
        showModernConfirmationDialog();
    }

    function showModernConfirmationDialog() {
        // Create modal overlay
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'modern-modal-overlay';
        modalOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease-out;
        `;

        // Create modal container
        const modalContainer = document.createElement('div');
        modalContainer.className = 'modern-modal-container';
        modalContainer.style.cssText = `
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: slideInUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        `;

        modalContainer.innerHTML = `
            <div class="modal-icon" style="margin-bottom: 1.5rem;">
                <div style="
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #17479e 0%, #87CEEB 100%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto;
                    box-shadow: 0 10px 25px rgba(23, 71, 158, 0.3);
                ">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: white;"></i>
                </div>
            </div>

            <h3 style="
                color: #17479e;
                margin-bottom: 1rem;
                font-weight: 700;
                font-size: 1.5rem;
            ">Confirm Update</h3>

            <p style="
                color: #64748b;
                margin-bottom: 2rem;
                font-size: 1.1rem;
                line-height: 1.6;
            ">Please confirm that all information is accurate before submitting your changes.</p>

            <div class="modal-actions" style="
                display: flex;
                gap: 1rem;
                justify-content: center;
            ">
                <button type="button" class="btn-cancel" style="
                    padding: 0.875rem 2rem;
                    background: #f1f5f9;
                    color: #64748b;
                    border: 2px solid #e2e8f0;
                    border-radius: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-size: 1rem;
                " onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>

                <button type="button" class="btn-confirm" style="
                    padding: 0.875rem 2rem;
                    background: linear-gradient(135deg, #17479e 0%, #1f5bb8 100%);
                    color: white;
                    border: none;
                    border-radius: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-size: 1rem;
                    box-shadow: 0 4px 15px rgba(23, 71, 158, 0.4);
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(23, 71, 158, 0.6)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(23, 71, 158, 0.4)'">
                    <i class="fas fa-check me-2"></i>Confirm & Update
                </button>
            </div>
        `;

        // Add CSS animations
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px) scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
        `;
        document.head.appendChild(styleElement);

        // Event listeners
        const cancelBtn = modalContainer.querySelector('.btn-cancel');
        const confirmBtn = modalContainer.querySelector('.btn-confirm');

        cancelBtn.addEventListener('click', () => {
            document.body.removeChild(modalOverlay);
            document.head.removeChild(styleElement);
        });

        confirmBtn.addEventListener('click', () => {
            document.body.removeChild(modalOverlay);
            document.head.removeChild(styleElement);
            proceedWithUpdate();
        });

        // Close on overlay click
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                document.body.removeChild(modalOverlay);
                document.head.removeChild(styleElement);
            }
        });

        modalOverlay.appendChild(modalContainer);
        document.body.appendChild(modalOverlay);
    }

    function proceedWithUpdate() {
        // Keep button in loading state
        const updateBtn = document.getElementById('update_btn');
        updateBtn.classList.add('loading');
        updateBtn.disabled = true;

        // Convert monetary fields back to numeric values
        document.querySelectorAll('.monetary-input').forEach(input => {
            if (input.value) {
                input.value = input.value.replace(/,/g, '');
            }
        });

        // Remove readonly date_received field from submission to avoid validation issues
        const dateReceivedField = document.getElementById('date_received');
        if (dateReceivedField) {
            dateReceivedField.removeAttribute('name');
        }

        // Submit the form
        document.getElementById('editEnquiryForm').submit();
    }

    function toggleTypeFields(selectedType) {
        // Hide all type fields
        document.querySelectorAll('.type-fields').forEach(field => {
            field.classList.remove('show');
        });

        // Show selected type fields
        const typeFieldMap = {
            'loan_application': 'loanFields',
            'share_enquiry': 'shareFields',
            'retirement': 'retirementFields',
            'condolences': 'condolenceFields',
            'deduction_add': 'deductionFields',
            'refund': 'refundFields',
            'withdraw_savings': 'withdrawSavingsFields',
            'withdraw_deposit': 'withdrawDepositFields',
            'unjoin_membership': 'unjoinMembershipFields',
            'ura_mobile': 'uraMobileFields',
            'sick_for_30_days': 'sickLeaveFields',
            'injured_at_work': 'injuryFields',
            'residential_disaster': 'disasterFields',
            'join_membership': 'joinMembershipFields'
        };

        const fieldId = typeFieldMap[selectedType];
        if (fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.classList.add('show');
            }
        }
    }

    function initializeFileUpload() {
        const fileInput = document.getElementById('file_path');
        const uploadArea = document.querySelector('.file-upload-area');
        const selectedFileDiv = document.querySelector('.selected-file');
        const fileNameSpan = document.querySelector('.file-name');
        const removeFileBtn = document.querySelector('.remove-file');

        if (!fileInput || !uploadArea) return;

        // Click to browse
        uploadArea.addEventListener('click', () => fileInput.click());

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelection(files[0]);
            }
        });

        // File selection
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelection(e.target.files[0]);
            }
        });

        // Remove file
        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', () => {
                fileInput.value = '';
                selectedFileDiv.style.display = 'none';
            });
        }

        function handleFileSelection(file) {
            if (file.type === 'application/pdf' || file.type.startsWith('image/')) {
                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    showModernValidationDialog('File size must be less than 10MB', 'error');
                    return;
                }

                const fileURL = URL.createObjectURL(file);
                fileNameSpan.textContent = file.name;
                selectedFileDiv.style.display = 'block';

                // Add preview for new file
                const previewContainer = document.createElement('div');
                previewContainer.className = 'mt-3';
                previewContainer.innerHTML = `
                    <div class="new-file-preview" style="background: rgba(16, 220, 96, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid #10dc60;">
                        <h6 style="color: #10dc60; margin-bottom: 1rem;"><i class="fas fa-upload me-2"></i>New Document Preview</h6>
                        <div class="pdf-preview-frame">
                            <iframe src="${fileURL}" width="100%" height="300" style="border: 1px solid #dee2e6; border-radius: 8px;"></iframe>
                        </div>
                    </div>
                `;

                // Remove any existing new file preview
                const existingPreview = document.querySelector('.new-file-preview');
                if (existingPreview) {
                    existingPreview.parentNode.removeChild(existingPreview.parentNode);
                }

                // Add new preview after the selected file div
                selectedFileDiv.parentNode.insertBefore(previewContainer, selectedFileDiv.nextSibling);
            } else {
                fileNameSpan.textContent = file.name;
                selectedFileDiv.style.display = 'block';
                showModernValidationDialog('Please select a PDF or image file only', 'warning');
            }
        }
    }

    function isValidPhone(phone) {
        return /^255\d{9}$/.test(phone);
    }
</script>

@endsection