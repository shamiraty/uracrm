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

    .files-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .files-header::before {
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

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(23, 71, 158, 0.05);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.25rem rgba(23, 71, 158, 0.15);
        outline: none;
    }

    .form-control:hover, .form-select:hover {
        border-color: var(--ura-accent);
    }

    .form-control[readonly] {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
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

    .reference-preview {
        background: var(--ura-gradient-light);
        border: 2px solid var(--ura-primary);
        border-radius: 8px;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ura-primary);
        text-align: center;
        margin-bottom: 1rem;
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

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .form-section-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--ura-gradient-light);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="files-header">
        <h1 class="header-title">
            <i class="bx {{ isset($file) ? 'bx-edit' : 'bx-plus' }}"></i>
            {{ isset($file) ? 'Edit File Record' : 'Create New File' }}
        </h1>
        <p class="header-subtitle">
            {{ isset($file) ? 'Update file information and reference details' : 'Add a new file to the document management system' }}
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
                <a href="{{ route('files.index') }}">
                    <i class="bx bx-file"></i>
                    Files
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">{{ isset($file) ? 'Edit' : 'Create' }}</li>
        </ul>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- File Form -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-form"></i>
                        File {{ isset($file) ? 'Update' : 'Creation' }} Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($file) ? route('files.update', $file) : route('files.store') }}" method="POST" id="fileForm">
                        @csrf
                        @if(isset($file))
                            @method('PUT')
                        @endif

                        <!-- Reference Number Preview -->
                        <div class="reference-preview" id="referencePreview">
                            Reference Number: <span id="referenceDisplay">Auto-generated</span>
                        </div>

                        <!-- File Classification Section -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="bx bx-category"></i>
                                File Classification
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="file_series_id">
                                            <i class="bx bx-folder me-1"></i>
                                            File Series *
                                        </label>
                                        <select class="form-select" id="file_series_id" name="file_series_id" onchange="updateReferenceNumber();" required>
                                            <option value="">Select File Series</option>
                                            @foreach($fileSeries as $series)
                                                <option value="{{ $series->id }}" data-code="{{ $series->code }}"{{ (isset($file) && $file->file_series_id == $series->id) ? ' selected' : '' }}>
                                                    {{ $series->name }} ({{ $series->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('file_series_id')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="department_id">
                                            <i class="bx bx-buildings me-1"></i>
                                            Department *
                                        </label>
                                        <select class="form-select" id="department_id" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}"{{ (isset($file) && $file->department_id == $department->id) ? ' selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keywords Section -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="bx bx-key"></i>
                                File Keywords
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="keyword1_id">
                                            <i class="bx bx-tag me-1"></i>
                                            Primary Keyword
                                        </label>
                                        <select class="form-select" id="keyword1_id" name="keyword1_id" onchange="updateReferenceNumber();">
                                            <option value="">Select Primary Keyword</option>
                                            @foreach($keywords as $keyword)
                                                <option value="{{ $keyword->id }}" data-code="{{ $keyword->code }}"{{ (isset($file) && $file->keyword1_id == $keyword->id) ? ' selected' : '' }}>
                                                    {{ $keyword->name }} ({{ $keyword->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="keyword2_id">
                                            <i class="bx bx-tag me-1"></i>
                                            Secondary Keyword
                                        </label>
                                        <select class="form-select" id="keyword2_id" name="keyword2_id" onchange="updateReferenceNumber();">
                                            <option value="">Select Secondary Keyword</option>
                                            @foreach($keywords as $keyword)
                                                <option value="{{ $keyword->id }}" data-code="{{ $keyword->code }}"{{ (isset($file) && $file->keyword2_id == $keyword->id) ? ' selected' : '' }}>
                                                    {{ $keyword->name }} ({{ $keyword->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Details Section -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="bx bx-detail"></i>
                                File Details
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating-custom">
                                        <label for="running_number">
                                            <i class="bx bx-hash me-1"></i>
                                            Running Number *
                                        </label>
                                        <input type="number" class="form-control" id="running_number" name="running_number"
                                               value="{{ old('running_number', $file->running_number ?? '') }}"
                                               placeholder="001" required oninput="updateReferenceNumber();">
                                        @error('running_number')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating-custom">
                                        <label for="file_part">
                                            <i class="bx bx-layer me-1"></i>
                                            File Part *
                                        </label>
                                        <input type="text" class="form-control" id="file_part" name="file_part"
                                               value="{{ old('file_part', $file->file_part ?? '1') }}"
                                               placeholder="1" required oninput="updateReferenceNumber();">
                                        @error('file_part')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <label for="reference_number">
                                            <i class="bx bx-barcode me-1"></i>
                                            Reference Number
                                        </label>
                                        <input type="text" class="form-control" id="reference_number" name="reference_number"
                                               value="{{ $file->reference_number ?? '' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating-custom">
                                        <label for="file_subject">
                                            <i class="bx bx-text me-1"></i>
                                            File Subject *
                                        </label>
                                        <input type="text" class="form-control" id="file_subject" name="file_subject"
                                               value="{{ old('file_subject', $file->file_subject ?? '') }}"
                                               placeholder="Enter the file subject or title" required>
                                        @error('file_subject')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Help -->
                        <div class="form-help">
                            <div class="form-help-title">
                                <i class="bx bx-info-circle"></i>
                                File Creation Guidelines
                            </div>
                            <div class="form-help-text">
                                <ul class="mb-0">
                                    <li><strong>File Series:</strong> Select the appropriate category for your file</li>
                                    <li><strong>Keywords:</strong> Choose relevant classification keywords (optional but recommended)</li>
                                    <li><strong>Running Number:</strong> Sequential number for file tracking</li>
                                    <li><strong>File Part:</strong> Use '1' for single-part files, or specify part number for multi-part files</li>
                                    <li><strong>Reference Number:</strong> Auto-generated based on your selections</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <a href="{{ route('files.index') }}" class="modern-btn modern-btn-secondary">
                                <i class="bx bx-x"></i>
                                Cancel
                            </a>
                            <button type="submit" class="modern-btn modern-btn-primary">
                                <i class="bx {{ isset($file) ? 'bx-save' : 'bx-plus' }}"></i>
                                {{ isset($file) ? 'Update File' : 'Create File' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateReferenceNumber() {
    let fileSeriesElement = document.querySelector('#file_series_id option:checked');
    let keyword1Element = document.querySelector('#keyword1_id option:checked');
    let keyword2Element = document.querySelector('#keyword2_id option:checked');

    let fileSeriesCode = fileSeriesElement ? fileSeriesElement.dataset.code || '' : '';
    let keyword1Code = keyword1Element ? keyword1Element.dataset.code || '' : '';
    let keyword2Code = keyword2Element ? keyword2Element.dataset.code || '' : '';
    let runningNumber = document.getElementById('running_number').value || '000';
    let filePart = document.getElementById('file_part').value || '1';

    // Pad running number to 3 digits
    runningNumber = runningNumber.padStart(3, '0');

    let refNumber = '';
    if (fileSeriesCode) {
        refNumber = fileSeriesCode;
        if (keyword1Code) {
            refNumber += '.' + keyword1Code;
            if (keyword2Code) {
                refNumber += '/' + keyword2Code;
            }
        }
        refNumber += '/' + runningNumber + filePart;
    }

    document.getElementById('reference_number').value = refNumber;
    document.getElementById('referenceDisplay').textContent = refNumber || 'Auto-generated';
}

// Initialize reference number on page load
document.addEventListener('DOMContentLoaded', function() {
    updateReferenceNumber();
});
</script>

@endsection