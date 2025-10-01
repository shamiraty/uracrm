@extends('layouts.app')
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
        font-size: 12px;
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
        font-size: 0.75rem;
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
        font-size: 0.8rem;
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
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: var(--ura-success);
        box-shadow: 0 0 0 3px rgba(16, 220, 96, 0.1);
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

    .success-message {
        color: var(--ura-success);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: none;
        font-weight: 500;
    }

    .success-message.show {
        display: block;
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

    /* Modern File Upload */
    .file-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: rgba(0, 188, 212, 0.02);
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: var(--ura-accent);
        background: rgba(0, 188, 212, 0.05);
    }

    .file-upload-area.drag-over {
        border-color: var(--ura-accent);
        background: rgba(0, 188, 212, 0.1);
    }

    .file-upload-icon {
        font-size: 3rem;
        color: var(--ura-accent);
        margin-bottom: 1rem;
    }

    .file-upload-text {
        color: var(--ura-primary);
        font-weight: 500;
    }

    .file-upload-subtext {
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .file-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: var(--ura-white);
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: none;
    }

    .file-preview.show {
        display: block;
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

    /* Button Loading State - Enhanced */
    .btn-spinner {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .btn-text {
        display: inline-block;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .btn-modern.loading .btn-text {
        display: none !important;
        opacity: 0;
    }

    .btn-modern.loading .btn-spinner {
        display: inline-block !important;
        opacity: 1;
    }

    .btn-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        pointer-events: none;
        transform: none !important;
    }

    /* Ensure button maintains consistent appearance */
    .btn-modern {
        min-height: 44px;
        position: relative;
        overflow: hidden;
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

    /* Success Alert */
    .alert-success {
        background: linear-gradient(135deg, var(--ura-success) 0%, #00e676 100%);
        color: var(--ura-white);
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(16, 220, 96, 0.2);
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
                    Create New Enquiry
                </li>
            </ol>
        </nav>
    </div>

    <!-- Success Message -->
    @if(session('message'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Main Form Container -->
    <div class="modern-form-container">
        <!-- Compact Header -->
        <div class="form-header text-white">
            <h3 class="text-white"><i class="fas fa-plus-circle me-2"></i>Create New Enquiry</h3>
            <p>Fill in the required information to submit your enquiry request</p>
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
                <div class="step-title"><i class="fas fa-map-marker-alt me-2"></i>Location</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-number">
                    <span class="step-number-text">4</span>
                </div>
                <div class="step-title"><i class="fas fa-paperclip me-2"></i>Documents</div>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-number">
                    <span class="step-number-text">5</span>
                </div>
                <div class="step-title"><i class="fas fa-eye me-2"></i>Review</div>
            </div>
        </div>

        <form method="POST" action="{{ route('enquiries.store') }}" enctype="multipart/form-data" id="enquiryForm">
            @csrf

            <!-- Step 1: Member Details -->
            <div class="form-section active" id="step-1">
                <h4 class="section-title">Member Personal Information</h4>

                <div class="row g-4">
                    <!-- Check Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Check Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="check_number" name="check_number" required>
                            <div class="error-message" id="check_number_error"></div>
                            <small class="text-muted">Start typing to auto-fill details</small>
                        </div>
                    </div>

                    <!-- Date Received -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Date Received <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="date_received" name="date_received" value="{{ date('Y-m-d') }}" required>
                            <div class="error-message" id="date_received_error"></div>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                            <div class="error-message" id="full_name_error"></div>
                        </div>
                    </div>

                    <!-- Membership Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Membership Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="force_no" name="force_no" value="None" required>
                            <div class="error-message" id="force_no_error"></div>
                        </div>
                    </div>

                    <!-- Bank Account Number -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Bank Account Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="account_number" name="account_number" required>
                            <div class="error-message" id="account_number_error"></div>
                        </div>
                    </div>

                    <!-- Bank Name -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Bank Name <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="bank_name" name="bank_name" required>
                                <option value="">Select Bank</option>
                                <option value="NBC">NBC</option>
                                <option value="NMB">NMB</option>
                                <option value="CRDB">CRDB</option>
                                <option value="Posta Bank">Posta Bank</option>
                                <option value="Absa Bank">Absa Bank</option>
                                <option value="DCB Commercial Bank">DCB Commercial Bank</option>
                                <option value="Access Bank Tanzania">Access Bank Tanzania</option>
                                <option value="Akiba Commercial Bank">Akiba Commercial Bank</option>
                                <option value="Amana Bank">Amana Bank</option>
                                <option value="Azania Bank">Azania Bank</option>
                                <option value="Bank of Africa">Bank of Africa</option>
                                <option value="Bank of Baroda">Bank of Baroda</option>
                                <option value="Bank of India">Bank of India</option>
                                <option value="Canara Bank">Canara Bank</option>
                                <option value="Citibank Tanzania">Citibank Tanzania</option>
                                <option value="Diamond Trust Bank">Diamond Trust Bank</option>
                                <option value="Ecobank">Ecobank</option>
                                <option value="Equity Bank">Equity Bank</option>
                                <option value="Exim Bank">Exim Bank</option>
                                <option value="GTBank Tanzania">GTBank Tanzania</option>
                                <option value="Habib African Bank">Habib African Bank</option>
                                <option value="I&M Bank">I&M Bank</option>
                                <option value="ICBank">ICBank</option>
                                <option value="KCB Bank">KCB Bank</option>
                                <option value="Letshego Bank">Letshego Bank</option>
                                <option value="Mkombozi Commercial Bank">Mkombozi Commercial Bank</option>
                                <option value="Mwalimu Commercial Bank">Mwalimu Commercial Bank</option>
                                <option value="NCBA Bank">NCBA Bank</option>
                                <option value="People's Bank of Zanzibar">People's Bank of Zanzibar</option>
                                <option value="Stanbic Bank Tanzania Limited">Stanbic Bank Tanzania Limited</option>
                                <option value="Standard Chartered Bank">Standard Chartered Bank</option>
                                <option value="Tanzania Commercial Bank">Tanzania Commercial Bank</option>
                                <option value="UBA Bank">UBA Bank</option>
                                <option value="Mwanga Hakika Bank">Mwanga Hakika Bank</option>
                            </select>
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
                                <input type="text" class="form-control monetary-input" id="basic_salary" name="basic_salary" placeholder="0.00" required>
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
                                <input type="text" class="form-control monetary-input" id="allowances" name="allowances" placeholder="0.00" required>
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
                                <input type="text" class="form-control monetary-input" id="take_home" name="take_home" placeholder="0.00" required>
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
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="255XXXXXXXXX" required>
                            <div class="phone-status" id="phone_status"></div>
                            <div class="error-message" id="phone_error"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Enquiry Type -->
            <div class="form-section" id="step-2">
                <h4 class="section-title">Enquiry Type & Details</h4>

                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                Select Enquiry Type <span class="text-danger">*</span>
                            </label>
                           <select name="type" id="enquiry_type" class="form-select text-uppercase fw-bold" required>
  <option value="">Choose an enquiry type / Chagua aina ya maombi</option>
  <option value="loan_application">Loan Application (Mkopo wa akiba)</option>
  <option value="refund">Refund (Kurejeshewa fedha)</option>
  <option value="share_enquiry">Share Enquiry (Kununua Hisa)</option>
  <option value="retirement">Retirement (Kustaafu kazi)</option>
  <option value="deduction_add">Add Deduction of Savings (Kuongeza/kupunguza akiba)</option>
  <option value="withdraw_savings">Withdraw Savings (Kuomba sehemu ya akiba)</option>
  <option value="withdraw_deposit">Withdraw Deposit (Kutoa Amana)</option>
  <option value="unjoin_membership">Unjoin Membership (Kujitoa Uanachama)</option>
  <option value="ura_mobile">URA Mobile (Ura mobile)</option>
  <option value="sick_for_30_days">Sick Leave 30+ Days (Ugonjwa siku 30)</option>
  <option value="condolences">Condolences (Rambirambi)</option>
  <option value="injured_at_work">Work Injury (Kuumia kazini)</option>
  <option value="residential_disaster">Residential Disaster (Majanga ya asili)</option>
  <option value="join_membership">Join Membership (Kujiunga uanachama)</option>
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
                                <select name="loan_category" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="salary_loan">Salary Loan</option>
                                    <option value="cash_loan">Cash Loan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Loan Purpose <span class="text-danger">*</span></label>
                                <select name="loan_type" class="form-select text-uppercase">
                                    <option value="">Select Purpose</option>
                                    <option value="business">Business</option>
                                    <option value="education">Education</option>
                                    <option value="medical">Medical</option>
                                    <option value="vehicle">Vehicle</option>
                                    <option value="agriculture">Agriculture</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="wedding">Wedding</option>
                                    <option value="vacation">Vacation</option>
                                    <option value="funeral">Funeral</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="construction">Construction</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Requested Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="loan_amount" class="form-control monetary-input" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                <input type="number" name="loan_duration" class="form-control" placeholder="Enter months" min="1" max="60">
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
                                    <input type="text" name="share_amount" class="form-control monetary-input" placeholder="0.00">
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
                                <input type="date" name="date_of_retirement" class="form-control">
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
                                    <input type="text" name="from_amount" class="form-control monetary-input" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">To Amount <span class="text-danger">*</span></label>
                                <div class="monetary-wrapper">
                                    <span class="currency-prefix">TSH</span>
                                    <input type="text" name="to_amount" class="form-control monetary-input" placeholder="0.00">
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
                                    <input type="text" name="refund_amount" class="form-control monetary-input" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Refund Duration <span class="text-danger">*</span></label>
                                <input type="number" name="refund_duration" class="form-control" placeholder="Enter duration">
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
                                    <input type="text" name="withdraw_saving_amount" class="form-control monetary-input" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <input type="text" name="withdraw_saving_reason" class="form-control" placeholder="Reason for withdrawal">
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
                                    <input type="text" name="withdraw_deposit_amount" class="form-control monetary-input" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <input type="text" name="withdraw_deposit_reason" class="form-control" placeholder="Reason for withdrawal">
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
                                <select name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="normal">Normal</option>
                                    <option value="job_termination">Job Termination</option>
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
                                <input type="date" name="startdate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="enddate" class="form-control">
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
                                <select name="dependent_member_type" class="form-select">
                                    <option value="">Select Beneficiary</option>
                                    <option value="dependent_child">Dependent Child</option>
                                    <option value="dependent_spouse">Dependent Spouse</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="male" id="gender_male">
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="female" id="gender_female">
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
                                <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Please describe the injury details..."></textarea>
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
                                <select name="disaster_type" class="form-select text-uppercase">
                                    <option value="">Select Disaster Cause</option>
                                    <option value="fire">Fire (Ajali ya moto)</option>
                                    <option value="hurricane">Hurricane (Kimbunga)</option>
                                    <option value="flood">Flood (Mafuriko)</option>
                                    <option value="earthquake">Earthquake (Tetemeko la ardhi)</option>
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
                                <select name="membership_status" class="form-select">
                                    <option value="">Select Membership Status</option>
                                    <option value="police_officer">Police Officer</option>
                                    <option value="civilian">Civilian</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Step 3: Location -->
            <div class="form-section" id="step-3">
                <h4 class="section-title">Location Information</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Region <span class="text-danger">*</span>
                            </label>
                            <select class="form-select text-uppercase" id="region" name="region_id" required>
                                <option value="">Select Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                            <div class="error-message" id="region_error"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                District <span class="text-danger">*</span>
                            </label>
                            <select class="form-select text-uppercase" id="district" name="district_id" required>
                                <option value="">Select District</option>
                            </select>
                            <div class="error-message" id="district_error"></div>
                        </div>
                    </div>

                    @if(auth()->user()->hasRole(['registrar_hq']))
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Command</label>
                            <select class="form-select text-uppercase" id="command_id" name="command_id">
                                <option value="">Select Command</option>
                                @foreach ($commands as $command)
                                    <option value="{{ $command->id }}">{{ $command->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Step 4: Documents -->
            <div class="form-section" id="step-4">
                <h4 class="section-title">Document Attachment</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Upload Supporting Document <span class="text-danger">*</span>
                            </label>
                            <div class="file-upload-area" onclick="document.getElementById('file_upload').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    Click to upload or drag and drop
                                </div>
                                <div class="file-upload-subtext">
                                    PDF files only (Max 10MB)
                                </div>
                            </div>
                            <input type="file" id="file_upload" name="file_path" accept="application/pdf" style="display: none;" required>
                            <div class="file-preview" id="file_preview"></div>
                            <div class="error-message" id="file_error"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                File Reference <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="file_id" name="file_id" required>
                                <option value="">Select File Reference</option>
                                @foreach ($files as $file)
                                    <option value="{{ $file->id }}">{{ $file->reference_number }} - {{ $file->file_subject }}</option>
                                @endforeach
                            </select>
                            <div class="error-message" id="file_id_error"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Review -->
            <div class="form-section" id="step-5">
                <h4 class="section-title">Review Your Information</h4>

                <div id="review_content">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-eye fa-3x mb-3"></i>
                        <p>Please complete all previous steps to review your information</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirm_submission" required>
                        <label class="form-check-label" for="confirm_submission">
                            <strong>I confirm that all the information provided is accurate and complete</strong>
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
                <button type="button" class="btn-modern btn-primary" id="submit_btn" onclick="submitForm()" style="display: none;">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane"></i> Submit Enquiry
                    </span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Submitting...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    let currentStep = 1;
    const totalSteps = 5;
    let formData = {};

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupEventListeners();
    });

    function initializeForm() {
        updateStepDisplay();
        initializeMonetaryInputs();
        initializePhoneValidation();
        initializeCheckNumberLookup();
        initializeFileUpload();
    }

    function setupEventListeners() {
        // Enquiry type change
        document.getElementById('enquiry_type').addEventListener('change', function() {
            toggleTypeFields(this.value);
        });

        // Region change
        document.getElementById('region').addEventListener('change', function() {
            updateDistricts(this.value);
        });

        // Only validate on form submission, not on blur/input
        // Remove real-time validation to prevent premature error messages
    }

    function nextStep() {
        if (validateCurrentStep()) {
            // Additional validation for step 2 (enquiry type)
            if (currentStep === 2) {
                const enquiryType = document.getElementById('enquiry_type').value;

                if (!enquiryType) {
                    showModernValidationDialog('Please select an enquiry type', 'warning');
                    return;
                }

                // If enquiry type is selected, check if at least one option field is filled
                const visibleTypeSection = document.querySelector('.type-fields.show');
                if (visibleTypeSection) {
                    const typeFields = visibleTypeSection.querySelectorAll('input, select, textarea');
                    let hasAtLeastOneValue = false;

                    typeFields.forEach(field => {
                        if (field.type === 'radio') {
                            if (field.checked) hasAtLeastOneValue = true;
                        } else if (field.type !== 'hidden' && field.value && field.value.trim() !== '') {
                            hasAtLeastOneValue = true;
                        }
                    });

                    // Only require at least one field if there are fillable fields (exclude info-only sections)
                    const fillableFields = visibleTypeSection.querySelectorAll('input:not([readonly]), select, textarea');
                    if (fillableFields.length > 0 && !hasAtLeastOneValue) {
                        showModernValidationDialog('Please fill at least one field for the selected enquiry type', 'warning');
                        return;
                    }
                }
            }

            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();

                if (currentStep === 5) {
                    generateReview();
                }
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
        const submitBtn = document.getElementById('submit_btn');

        prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
        nextBtn.style.display = currentStep < totalSteps ? 'block' : 'none';
        submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';

        // Scroll to top
        document.querySelector('.modern-form-container').scrollIntoView({ behavior: 'smooth' });
    }

    function validateCurrentStep() {
        const currentSection = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentSection.querySelectorAll('input[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Clear previous validation
        clearFieldError(field);

        if (field.hasAttribute('required') && !value) {
            errorMessage = 'This field is required';
            isValid = false;
        } else if (field.type === 'email' && value && !isValidEmail(value)) {
            errorMessage = 'Please enter a valid email address';
            isValid = false;
        } else if (field.id === 'phone' && value && !isValidPhone(value)) {
            errorMessage = 'Please enter a valid phone number (255XXXXXXXXX)';
            isValid = false;
        }

        if (!isValid) {
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

            // Auto-add 255 prefix
            if (value && !value.startsWith('255')) {
                value = '255' + value.replace(/^255/, '');
            }

            // Limit to 12 digits
            if (value.length > 12) {
                value = value.slice(0, 12);
            }

            this.value = value;

            // Update status
            if (value.length === 12) {
                phoneStatus.textContent = '✓ Valid phone number';
                phoneStatus.className = 'phone-status valid';
            } else if (value.length > 0) {
                phoneStatus.textContent = `${12 - value.length} more digits needed`;
                phoneStatus.className = 'phone-status invalid';
            } else {
                phoneStatus.textContent = '';
            }
        });
    }

    function initializeCheckNumberLookup() {
        let debounceTimer;
        const checkNumberInput = document.getElementById('check_number');

        checkNumberInput.addEventListener('input', function() {
            const checkNumber = this.value;

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (checkNumber.length > 3) {
                    lookupMemberData(checkNumber);
                }
            }, 500);
        });
    }

    function lookupMemberData(checkNumber) {
        fetch(`/enquiries/fetch-payroll/${checkNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.full_name) {
                    document.getElementById('full_name').value = data.full_name || '';
                    document.getElementById('account_number').value = data.account_number || '';
                    document.getElementById('bank_name').value = data.bank_name || '';

                    // Format monetary values
                    if (data.basic_salary) {
                        document.getElementById('basic_salary').value = parseFloat(data.basic_salary).toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                    if (data.allowance) {
                        document.getElementById('allowances').value = parseFloat(data.allowance).toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                    if (data.net_amount) {
                        document.getElementById('take_home').value = parseFloat(data.net_amount).toLocaleString('en-US', {minimumFractionDigits: 2});
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching member data:', error);
            });
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

    function updateDistricts(regionId) {
        const districtSelect = document.getElementById('district');
        districtSelect.innerHTML = '<option value="">Select District</option>';

        if (regionId) {
            @json($regions).forEach(region => {
                if (region.id == regionId && region.districts) {
                    region.districts.forEach(district => {
                        const option = new Option(district.name, district.id);
                        districtSelect.add(option);
                    });
                }
            });
        }
    }

    function initializeFileUpload() {
        const fileInput = document.getElementById('file_upload');
        const filePreview = document.getElementById('file_preview');
        const uploadArea = document.querySelector('.file-upload-area');

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('drag-over');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('drag-over');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        }

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (file.type === 'application/pdf') {
                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    showFieldError(fileInput, 'File size must be less than 10MB');
                    return;
                }

                const fileURL = URL.createObjectURL(file);
                filePreview.innerHTML = `
                    <div class="file-preview-container">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                            <div>
                                <div class="fw-bold">${file.name}</div>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.open('${fileURL}', '_blank')">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                            </div>
                        </div>
                        <div class="pdf-preview-frame">
                            <iframe src="${fileURL}" width="100%" height="300" style="border: 1px solid #dee2e6; border-radius: 8px;"></iframe>
                        </div>
                    </div>
                `;
                filePreview.classList.add('show');
                clearFieldError(fileInput);
            } else {
                showFieldError(fileInput, 'Please select a PDF file only');
                filePreview.classList.remove('show');
            }
        }
    }

    function generateReview() {
        const reviewContent = document.getElementById('review_content');

        // Create comprehensive review summary
        let html = `
            <div class="review-summary">
                <h5><i class="fas fa-clipboard-check me-2"></i>Enquiry Summary</h5>
                <p>Please review all information below before submitting your enquiry</p>
            </div>
            <div class="row g-3">
        `;

        // Member Information
        const fullName = document.getElementById('full_name').value;
        const checkNumber = document.getElementById('check_number').value;
        const phone = document.getElementById('phone').value;
        const accountNumber = document.getElementById('account_number').value;
        const bankName = document.getElementById('bank_name').selectedOptions[0]?.text || '';
        const dateReceived = document.getElementById('date_received').value;

        html += `
            <div class="col-12">
                <div class="review-card">
                    <h6 class="review-section-title"><i class="fas fa-user me-2"></i>Member Information</h6>
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
                        <span class="review-label">Account Number</span>
                        <span class="review-value">${accountNumber}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Bank</span>
                        <span class="review-value">${bankName}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-label">Date Received</span>
                        <span class="review-value">${dateReceived}</span>
                    </div>
                </div>
            </div>
        `;

        // Salary Information
        const basicSalary = document.getElementById('basic_salary').value;
        const allowances = document.getElementById('allowances').value;
        const takeHome = document.getElementById('take_home').value;

        if (basicSalary || allowances || takeHome) {
            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-money-bill-wave me-2"></i>Salary Information</h6>
                        <div class="review-item">
                            <span class="review-label">Basic Salary</span>
                            <span class="review-value">TSh ${basicSalary || '0.00'}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label">Allowances</span>
                            <span class="review-value">TSh ${allowances || '0.00'}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label">Take Home Pay</span>
                            <span class="review-value">TSh ${takeHome || '0.00'}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Enquiry Information
        const selectedType = document.getElementById('enquiry_type').selectedOptions[0];
        if (selectedType) {
            const enquiryTypeText = selectedType.text;
            const enquiryTypeValue = selectedType.value;

            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-question-circle me-2"></i>Enquiry Information</h6>
                        <div class="review-item">
                            <span class="review-label">Enquiry Type</span>
                            <span class="review-value">${enquiryTypeText}</span>
                        </div>
            `;

            // Add type-specific fields based on enquiry type
            const typeSpecificHtml = getTypeSpecificReviewHTML(enquiryTypeValue);
            if (typeSpecificHtml) {
                html += typeSpecificHtml;
            }

            html += `
                    </div>
                </div>
            `;
        }

        // Location Information
        const selectedRegion = document.getElementById('region').selectedOptions[0]?.text;
        const selectedDistrict = document.getElementById('district').selectedOptions[0]?.text;
        if (selectedRegion && selectedDistrict) {
            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h6>
                        <div class="review-item">
                            <span class="review-label">Region</span>
                            <span class="review-value">${selectedRegion}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label">District</span>
                            <span class="review-value">${selectedDistrict}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Documents Information
        const fileInput = document.getElementById('file_upload');
        const fileReference = document.getElementById('file_id');

        if ((fileInput && fileInput.files.length > 0) || (fileReference && fileReference.value)) {
            html += `
                <div class="col-12">
                    <div class="review-card">
                        <h6 class="review-section-title"><i class="fas fa-paperclip me-2"></i>Documents Information</h6>
            `;

            // Show file reference if selected
            if (fileReference && fileReference.value) {
                html += `
                        <div class="review-item">
                            <span class="review-label"><i class="fas fa-folder-open me-1" style="color: #17479e;"></i>File Reference</span>
                            <span class="review-value">${fileReference.selectedOptions[0]?.text || 'Selected File'}</span>
                        </div>
                `;
            }

            // Show uploaded file if exists
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                html += `
                        <div class="review-item">
                            <span class="review-label"><i class="fas fa-file-pdf me-1" style="color: #dc3545;"></i>Uploaded File</span>
                            <span class="review-value">${file.name}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label"><i class="fas fa-hdd me-1" style="color: #17479e;"></i>File Size</span>
                            <span class="review-value">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        </div>
                        <div class="review-item">
                            <span class="review-label"><i class="fas fa-file-alt me-1" style="color: #28a745;"></i>File Type</span>
                            <span class="review-value">${file.type === 'application/pdf' ? 'PDF Document' : file.type}</span>
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
                    <i class="fas fa-info-circle me-2"></i>
                    All information has been reviewed and is ready for submission
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
                    html += `<div class="review-item"><span class="review-label">Loan Category</span><span class="review-value">${loanCategory.replace('_', ' ').toUpperCase()}</span></div>`;
                }
                if (loanAmount) {
                    html += `<div class="review-item"><span class="review-label">Loan Amount</span><span class="review-value">TSh ${loanAmount}</span></div>`;
                }
                if (loanDuration) {
                    html += `<div class="review-item"><span class="review-label">Duration</span><span class="review-value">${loanDuration} months</span></div>`;
                }
                break;

            case 'withdraw_savings':
                const savingsAmount = document.querySelector('input[name="withdraw_saving_amount"]')?.value || '';
                const savingsReason = document.querySelector('input[name="withdraw_saving_reason"]')?.value || '';
                if (savingsAmount) {
                    html += `<div class="review-item"><span class="review-label">Amount</span><span class="review-value">TSh ${savingsAmount}</span></div>`;
                }
                if (savingsReason) {
                    html += `<div class="review-item"><span class="review-label">Reason</span><span class="review-value">${savingsReason}</span></div>`;
                }
                break;

            case 'withdraw_deposit':
                const depositAmount = document.querySelector('input[name="withdraw_deposit_amount"]')?.value || '';
                const depositReason = document.querySelector('input[name="withdraw_deposit_reason"]')?.value || '';
                if (depositAmount) {
                    html += `<div class="review-item"><span class="review-label">Amount</span><span class="review-value">TSh ${depositAmount}</span></div>`;
                }
                if (depositReason) {
                    html += `<div class="review-item"><span class="review-label">Reason</span><span class="review-value">${depositReason}</span></div>`;
                }
                break;

            case 'retirement':
                const retirementDate = document.querySelector('input[name="date_of_retirement"]')?.value || '';
                if (retirementDate) {
                    html += `<div class="review-item"><span class="review-label">Retirement Date</span><span class="review-value">${retirementDate}</span></div>`;
                }
                break;

            case 'refund':
                const refundAmount = document.querySelector('input[name="refund_amount"]')?.value || '';
                const refundDuration = document.querySelector('input[name="refund_duration"]')?.value || '';
                if (refundAmount) {
                    html += `<div class="review-item"><span class="review-label">Refund Amount</span><span class="review-value">TSh ${refundAmount}</span></div>`;
                }
                if (refundDuration) {
                    html += `<div class="review-item"><span class="review-label">Duration</span><span class="review-value">${refundDuration} months</span></div>`;
                }
                break;

            case 'share_enquiry':
                const shareAmount = document.querySelector('input[name="share_amount"]')?.value || '';
                if (shareAmount) {
                    html += `<div class="review-item"><span class="review-label">Share Amount</span><span class="review-value">TSh ${shareAmount}</span></div>`;
                }
                break;

            case 'deduction_add':
                const fromAmount = document.querySelector('input[name="from_amount"]')?.value || '';
                const toAmount = document.querySelector('input[name="to_amount"]')?.value || '';
                if (fromAmount && toAmount) {
                    html += `<div class="review-item"><span class="review-label">Deduction Change</span><span class="review-value">From TSh ${fromAmount} to TSh ${toAmount}</span></div>`;
                }
                break;

            case 'condolences':
                const dependentType = document.querySelector('select[name="dependent_member_type"]')?.value || '';
                const gender = document.querySelector('input[name="gender"]:checked')?.value || '';
                if (dependentType) {
                    html += `<div class="review-item"><span class="review-label">Dependent Type</span><span class="review-value">${dependentType.replace('_', ' ').toUpperCase()}</span></div>`;
                }
                if (gender) {
                    html += `<div class="review-item"><span class="review-label">Gender</span><span class="review-value">${gender.toUpperCase()}</span></div>`;
                }
                break;

            case 'injured_at_work':
                const injuryDescription = document.querySelector('textarea[name="description"]')?.value || '';
                if (injuryDescription) {
                    html += `<div class="review-item"><span class="review-label">Injury Description</span><span class="review-value">${injuryDescription}</span></div>`;
                }
                break;

            case 'sick_for_30_days':
                const startDate = document.querySelector('input[name="startdate"]')?.value || '';
                const endDate = document.querySelector('input[name="enddate"]')?.value || '';
                if (startDate && endDate) {
                    html += `<div class="review-item"><span class="review-label">Sick Leave Period</span><span class="review-value">From ${startDate} to ${endDate}</span></div>`;
                }
                break;
        }

        return html;
    }

    function submitForm() {
        if (!document.getElementById('confirm_submission').checked) {
            showModernValidationDialog('Please confirm that all information is accurate before submitting.', 'warning');
            return;
        }

        // Do NOT show loading state here - only show confirmation dialog
        // Loading state will be shown after user confirms

        // Convert monetary fields back to numeric values
        document.querySelectorAll('.monetary-input').forEach(input => {
            if (input.value) {
                input.value = input.value.replace(/,/g, '');
            }
        });

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
                    <i class="fas fa-paper-plane" style="font-size: 2rem; color: white;"></i>
                </div>
            </div>

            <h3 style="
                color: #17479e;
                margin-bottom: 1rem;
                font-weight: 700;
                font-size: 1.5rem;
            ">Submit Enquiry</h3>

            <p style="
                color: #64748b;
                margin-bottom: 2rem;
                font-size: 1.1rem;
                line-height: 1.6;
            ">Please confirm that all information is accurate before submitting your enquiry.</p>

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
                    <i class="fas fa-check me-2"></i>Submit Enquiry
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
            proceedWithSubmission();
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

    function proceedWithSubmission() {
        // Keep button in loading state
        const submitBtn = document.getElementById('submit_btn');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        try {
            // Submit the form
            document.getElementById('enquiryForm').submit();

            // Fallback timeout in case submission hangs
            setTimeout(() => {
                resetSubmitButton();
            }, 30000); // Reset after 30 seconds
        } catch (error) {
            console.error('Form submission error:', error);
            resetSubmitButton();
        }
    }

    // Add button reset function
    function resetSubmitButton() {
        const submitBtn = document.getElementById('submit_btn');
        if (submitBtn) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    }

    // Reset button if user navigates back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            resetSubmitButton();
        }
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^255\d{9}$/.test(phone);
    }
</script>

@endsection