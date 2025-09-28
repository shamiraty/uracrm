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

    .keywords-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .keywords-header::before {
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
        font-size: 2.5rem;
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

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating-custom label {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        display: block;
    }

    .form-control {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(23, 71, 158, 0.05);
    }

    .form-control:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.15);
        outline: none;
    }

    .form-control:hover {
        border-color: var(--ura-accent);
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
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

    .form-help {
        background: var(--ura-gradient-light);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .form-help-title {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-help-text {
        color: #6c757d;
        font-size: 0.9rem;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-breadcrumb {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .breadcrumb-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-nav a {
        color: var(--ura-primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .breadcrumb-nav a:hover {
        color: var(--ura-accent);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="keywords-header">
        <h1 class="header-title">
            <i class="bx {{ isset($keyword) ? 'bx-edit' : 'bx-plus' }}"></i>
            {{ isset($keyword) ? 'Edit Keyword' : 'Create New Keyword' }}
        </h1>
        <p class="header-subtitle">
            {{ isset($keyword) ? 'Update keyword information and settings' : 'Add a new keyword to the system database' }}
        </p>
    </div>

    <!-- Breadcrumb -->
    <div class="modern-breadcrumb">
        <ul class="breadcrumb-nav">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="bx bx-home-alt"></i>
                    Dashboard
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li>
                <a href="{{ route('keywords.index') }}">
                    <i class="bx bx-key"></i>
                    Keywords
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">{{ isset($keyword) ? 'Edit' : 'Create' }}</li>
        </ul>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Keyword Form -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-form"></i>
                        Keyword {{ isset($keyword) ? 'Update' : 'Creation' }} Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($keyword) ? route('keywords.update', $keyword) : route('keywords.store') }}" method="POST">
                        @csrf
                        @if(isset($keyword))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating-custom">
                                    <label for="name">
                                        <i class="bx bx-key me-1"></i>
                                        Keyword Name *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $keyword->name ?? '') }}"
                                           placeholder="Enter keyword name" required>
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating-custom">
                                    <label for="code">
                                        <i class="bx bx-code me-1"></i>
                                        Keyword Code *
                                    </label>
                                    <input type="text" class="form-control" id="code" name="code"
                                           value="{{ old('code', $keyword->code ?? '') }}"
                                           placeholder="Enter keyword code" required>
                                    @error('code')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Help -->
                        <div class="form-help">
                            <div class="form-help-title">
                                <i class="bx bx-info-circle"></i>
                                Input Guidelines
                            </div>
                            <div class="form-help-text">
                                <ul class="mb-0">
                                    <li><strong>Keyword Name:</strong> A descriptive name for the keyword (e.g., "Payment Status")</li>
                                    <li><strong>Keyword Code:</strong> A unique identifier code (e.g., "PAY_STATUS")</li>
                                    <li>Codes should be uppercase and use underscores for spaces</li>
                                    <li>Both fields are required and must be unique</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <a href="{{ route('keywords.index') }}" class="modern-btn modern-btn-secondary">
                                <i class="bx bx-x"></i>
                                Cancel
                            </a>
                            <button type="submit" class="modern-btn modern-btn-primary">
                                <i class="bx {{ isset($keyword) ? 'bx-save' : 'bx-plus' }}"></i>
                                {{ isset($keyword) ? 'Update Keyword' : 'Create Keyword' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
