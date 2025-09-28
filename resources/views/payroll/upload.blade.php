{{-- resources/views/payroll/upload.blade.php --}}

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

    .payroll-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .payroll-header::before {
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

    .upload-zone {
        border: 3px dashed rgba(23, 71, 158, 0.3);
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        background: var(--ura-gradient-light);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: 2rem;
    }

    .upload-zone:hover {
        border-color: var(--ura-primary);
        background: rgba(23, 71, 158, 0.05);
        transform: translateY(-2px);
    }

    .upload-zone.dragover {
        border-color: var(--ura-accent);
        background: rgba(0, 188, 212, 0.1);
        transform: scale(1.02);
    }

    .upload-icon {
        font-size: 4rem;
        color: var(--ura-primary);
        margin-bottom: 1rem;
    }

    .upload-text {
        color: var(--ura-primary);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .upload-subtext {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
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

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-info {
        background: var(--ura-gradient-light);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
    }

    .file-name {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .file-size {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.15) 0%, rgba(16, 220, 96, 0.05) 100%);
        color: var(--ura-success);
        border: 1px solid rgba(16, 220, 96, 0.3);
    }

    .requirements-list {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .requirements-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .requirements-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirements-list li {
        padding: 0.5rem 0;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .requirements-list li i {
        color: var(--ura-success);
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .progress-bar {
        background: var(--ura-gradient);
        transition: width 0.3s ease;
    }

    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .loading-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 16px;
        text-align: center;
        box-shadow: var(--ura-shadow-hover);
    }

    .spinner {
        width: 3rem;
        height: 3rem;
        border: 4px solid rgba(23, 71, 158, 0.1);
        border-left: 4px solid var(--ura-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="payroll-header">
        <h1 class="header-title">
            <i class="bx bx-upload"></i>
            Payroll Data Upload
        </h1>
        <p class="header-subtitle">
            Upload and process payroll data files with advanced validation and security
        </p>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="bx bx-check-circle fs-4"></i>
            <div>
                <strong>Success!</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Upload Form -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-cloud-upload"></i>
                        File Upload
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="uploadForm" action="{{ route('payroll.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-zone" id="uploadZone">
                            <input type="file" class="file-input" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="upload-content">
                                <i class="bx bx-cloud-upload upload-icon"></i>
                                <div class="upload-text">Drop your payroll file here</div>
                                <div class="upload-subtext">or click to browse files</div>
                                <div class="upload-subtext">
                                    <small>Supported formats: Excel (.xlsx, .xls), CSV (.csv)</small>
                                </div>
                            </div>
                        </div>

                        <div class="file-info" id="fileInfo">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="modern-btn modern-btn-primary" id="uploadBtn" disabled>
                                <i class="bx bx-upload"></i>
                                Upload Payroll Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Upload Requirements -->
            <div class="requirements-list">
                <h6 class="requirements-title">
                    <i class="bx bx-info-circle"></i>
                    Upload Requirements
                </h6>
                <ul>
                    <li>
                        <i class="bx bx-check"></i>
                        File must be in Excel (.xlsx, .xls) or CSV format
                    </li>
                    <li>
                        <i class="bx bx-check"></i>
                        Maximum file size: 10MB
                    </li>
                    <li>
                        <i class="bx bx-check"></i>
                        Required columns: Employee ID, Name, Salary, Department
                    </li>
                    <li>
                        <i class="bx bx-check"></i>
                        Data should be properly formatted
                    </li>
                    <li>
                        <i class="bx bx-check"></i>
                        No empty rows or columns
                    </li>
                    <li>
                        <i class="bx bx-check"></i>
                        Unicode encoding supported
                    </li>
                </ul>
            </div>

            <!-- Recent Uploads (if needed) -->
            <div class="modern-card mt-4">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-history"></i>
                        Upload History
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-0">
                        <i class="bx bx-time me-2"></i>
                        Recent uploads will appear here
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <h5 class="text-primary">Processing Upload...</h5>
        <p class="text-muted mb-0">Please wait while we process your payroll data</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadForm = document.getElementById('uploadForm');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            displayFileInfo(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            displayFileInfo(e.target.files[0]);
        }
    });

    // Display file information
    function displayFileInfo(file) {
        const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
        fileName.textContent = file.name;
        fileSize.textContent = `File size: ${sizeInMB} MB`;
        fileInfo.style.display = 'block';
        uploadBtn.disabled = false;

        // Validate file size
        if (file.size > 10 * 1024 * 1024) { // 10MB limit
            fileName.innerHTML = `<span class="text-danger">${file.name} (File too large)</span>`;
            uploadBtn.disabled = true;
        }
    }

    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        loadingOverlay.style.display = 'block';
    });
});
</script>

@endsection
