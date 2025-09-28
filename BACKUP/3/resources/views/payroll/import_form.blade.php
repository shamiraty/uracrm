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

    .upload-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .upload-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .upload-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .upload-body {
        padding: 2rem;
    }

    .file-upload-area {
        border: 3px dashed rgba(23, 71, 158, 0.3);
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        background: var(--ura-gradient-light);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .file-upload-area:hover {
        border-color: var(--ura-primary);
        background: rgba(23, 71, 158, 0.05);
        transform: translateY(-2px);
    }

    .file-upload-area.dragover {
        border-color: var(--ura-accent);
        background: rgba(0, 188, 212, 0.1);
        transform: scale(1.02);
    }

    .upload-icon {
        width: 80px;
        height: 80px;
        background: var(--ura-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover .upload-icon {
        transform: scale(1.1);
    }

    .upload-icon i {
        font-size: 2.5rem;
        color: white;
    }

    .upload-text {
        color: var(--ura-primary);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .upload-subtext {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-info {
        background: white;
        border: 2px solid var(--ura-success);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
    }

    .file-info.show {
        display: block;
        animation: slideIn 0.3s ease;
    }

    .file-info-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        background: var(--ura-success);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .file-details h6 {
        margin: 0 0 0.25rem 0;
        color: var(--ura-primary);
        font-weight: 600;
    }

    .file-details span {
        color: #6c757d;
        font-size: 0.9rem;
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

    .modern-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .requirements-section {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .requirements-header {
        background: linear-gradient(135deg, #10dc60 0%, #00e676 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .requirements-body {
        padding: 1.5rem;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        color: #495057;
    }

    .requirement-item i {
        color: var(--ura-success);
        font-weight: 600;
    }

    .progress-section {
        margin-top: 2rem;
        display: none;
    }

    .progress-section.show {
        display: block;
        animation: slideIn 0.3s ease;
    }

    .progress-bar-custom {
        height: 8px;
        background: rgba(23, 71, 158, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background: var(--ura-gradient);
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 4px;
    }

    .progress-text {
        text-align: center;
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        margin: 0;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="payroll-header">
        <h1 class="header-title">
            <i class="bx bx-upload"></i>
            Payroll File Import
        </h1>
        <p class="header-subtitle">
            Upload and process payroll data with advanced validation
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-file-blank"></i>
            </div>
            <div class="stat-value">Excel</div>
            <div class="stat-label">File Format</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-shield-check"></i>
            </div>
            <div class="stat-value">Secure</div>
            <div class="stat-label">Upload</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">Fast</div>
            <div class="stat-label">Processing</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-check-double"></i>
            </div>
            <div class="stat-value">Validated</div>
            <div class="stat-label">Data</div>
        </div>
    </div>

    <!-- Requirements Section -->
    <div class="requirements-section">
        <div class="requirements-header">
            <i class="bx bx-info-circle"></i>
            File Requirements
        </div>
        <div class="requirements-body">
            <div class="requirement-item">
                <i class="bx bx-check"></i>
                <span>File format must be Excel (.xlsx or .xls)</span>
            </div>
            <div class="requirement-item">
                <i class="bx bx-check"></i>
                <span>Maximum file size: 10MB</span>
            </div>
            <div class="requirement-item">
                <i class="bx bx-check"></i>
                <span>First row should contain column headers</span>
            </div>
            <div class="requirement-item">
                <i class="bx bx-check"></i>
                <span>Required columns: Employee ID, Name, Salary, Department</span>
            </div>
            <div class="requirement-item">
                <i class="bx bx-check"></i>
                <span>Data should be in the first worksheet</span>
            </div>
        </div>
    </div>

    <!-- Upload Card -->
    <div class="upload-card">
        <div class="upload-header">
            <h5 class="upload-title">
                <i class="bx bx-cloud-upload"></i>
                Upload Payroll File
            </h5>
        </div>
        <div class="upload-body">
            <form action="{{ route('payroll.import') }}" method="POST" enctype="multipart/form-data" id="payrollForm">
                @csrf

                <div class="file-upload-area" id="uploadArea">
                    <div class="upload-icon">
                        <i class="bx bx-cloud-upload"></i>
                    </div>
                    <div class="upload-text">Click to browse or drag & drop your file</div>
                    <div class="upload-subtext">Supported formats: .xlsx, .xls (Max 10MB)</div>
                    <input type="file" class="file-input" id="payroll_file" name="payroll_file"
                           accept=".xlsx,.xls" required>
                </div>

                <div class="file-info" id="fileInfo">
                    <div class="file-info-content">
                        <div class="file-icon">
                            <i class="bx bx-file-blank"></i>
                        </div>
                        <div class="file-details">
                            <h6 id="fileName"></h6>
                            <span id="fileSize"></span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="modern-btn modern-btn-primary" id="submitBtn" disabled>
                        <i class="bx bx-upload"></i>
                        Import Payroll Data
                    </button>
                </div>

                <div class="progress-section" id="progressSection">
                    <div class="progress-bar-custom">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-text" id="progressText">Uploading...</div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('payroll_file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const submitBtn = document.getElementById('submitBtn');
    const progressSection = document.getElementById('progressSection');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const form = document.getElementById('payrollForm');

    // File size formatter
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Validate file
    function validateFile(file) {
        const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                           'application/vnd.ms-excel'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!validTypes.includes(file.type)) {
            alert('Please select a valid Excel file (.xlsx or .xls)');
            return false;
        }

        if (file.size > maxSize) {
            alert('File size must be less than 10MB');
            return false;
        }

        return true;
    }

    // Handle file selection
    function handleFileSelect(file) {
        if (validateFile(file)) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.add('show');
            submitBtn.disabled = false;

            // Update upload area style
            uploadArea.style.borderColor = 'var(--ura-success)';
            uploadArea.querySelector('.upload-text').textContent = 'File selected successfully!';
            uploadArea.querySelector('.upload-icon i').className = 'bx bx-check';
            uploadArea.querySelector('.upload-icon').style.background = 'var(--ura-success)';
        }
    }

    // File input change event
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Drag and drop events
    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Form submission with progress
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        // Show progress section
        progressSection.classList.add('show');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Processing...';

        // Upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressFill.style.width = percentComplete + '%';
                progressText.textContent = `Uploading... ${Math.round(percentComplete)}%`;
            }
        });

        // Handle response
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                progressFill.style.width = '100%';
                progressText.textContent = 'Upload completed successfully!';
                progressText.style.color = 'var(--ura-success)';

                setTimeout(() => {
                    // Handle successful response (redirect or show success message)
                    window.location.reload();
                }, 1500);
            } else {
                progressText.textContent = 'Upload failed. Please try again.';
                progressText.style.color = 'var(--ura-danger)';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bx bx-upload"></i> Import Payroll Data';
            }
        });

        // Handle errors
        xhr.addEventListener('error', function() {
            progressText.textContent = 'Upload failed. Please try again.';
            progressText.style.color = 'var(--ura-danger)';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bx-upload"></i> Import Payroll Data';
        });

        // Send the request
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.send(formData);
    });
});
</script>

@endsection
