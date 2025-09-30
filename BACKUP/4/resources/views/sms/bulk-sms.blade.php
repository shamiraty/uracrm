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

    .sms-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .sms-header::before {
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

    .analytics-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .analytics-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .analytics-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ura-shadow-hover);
    }

    .analytics-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .card-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .card-label {
        color: #6c757d;
        font-weight: 500;
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
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .modern-alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--ura-shadow);
    }

    .modern-alert.alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.1) 0%, rgba(16, 220, 96, 0.05) 100%);
        border-left: 4px solid var(--ura-success);
        color: #0d5e2d;
    }

    .modern-alert.alert-danger {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.1) 0%, rgba(240, 65, 65, 0.05) 100%);
        border-left: 4px solid var(--ura-danger);
        color: #721c24;
    }

    .modern-alert.alert-warning {
        background: linear-gradient(135deg, rgba(255, 206, 0, 0.1) 0%, rgba(255, 206, 0, 0.05) 100%);
        border-left: 4px solid var(--ura-warning);
        color: #856404;
    }

    .modern-alert.alert-info {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.1) 0%, rgba(0, 188, 212, 0.05) 100%);
        border-left: 4px solid var(--ura-accent);
        color: #0c5460;
    }

    .file-upload-area {
        border: 3px dashed rgba(23, 71, 158, 0.3);
        border-radius: 16px;
        padding: 2rem;
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

    .upload-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .upload-icon i {
        font-size: 2rem;
        color: white;
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
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

    .modern-btn-warning {
        background: linear-gradient(135deg, var(--ura-warning) 0%, #ffd54f 100%);
        color: #333;
        box-shadow: 0 4px 15px rgba(255, 206, 0, 0.3);
    }

    .modern-btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 206, 0, 0.4);
        color: #333;
    }

    .sms-builder {
        background: white;
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .message-preview {
        background: var(--ura-gradient-light) !important;
        border: 2px solid rgba(23, 71, 158, 0.2) !important;
        border-radius: 12px !important;
        font-family: 'Courier New', monospace;
    }

    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
    }

    .step.active {
        background: var(--ura-gradient);
    }

    .step.completed {
        background: var(--ura-success);
    }

    .step.pending {
        background: #ccc;
    }

    .step-line {
        width: 60px;
        height: 2px;
        background: #ccc;
    }

    .step-line.completed {
        background: var(--ura-success);
    }

    .btn-sm {
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="sms-header">
        <h1 class="header-title">
            <i class="bx bx-message-dots"></i>
            Bulk SMS Campaign Manager
        </h1>
        <p class="header-subtitle">
            Send personalized SMS messages to multiple recipients with advanced analytics
        </p>
    </div>

    <!-- Analytics Dashboard -->
    <div class="analytics-dashboard">
        <div class="analytics-card">
            <div class="card-icon">
                <i class="bx bx-file-plus"></i>
            </div>
            <div class="card-value">CSV</div>
            <div class="card-label">Upload Format</div>
        </div>
        <div class="analytics-card">
            <div class="card-icon">
                <i class="bx bx-check-shield"></i>
            </div>
            <div class="card-value">{{ !empty($cleanData) ? count($cleanData) : '0' }}</div>
            <div class="card-label">Valid Numbers</div>
        </div>
        <div class="analytics-card">
            <div class="card-icon">
                <i class="bx bx-error-circle"></i>
            </div>
            <div class="card-value">{{ !empty($problematicData) ? count($problematicData) : '0' }}</div>
            <div class="card-label">Invalid Numbers</div>
        </div>
        <div class="analytics-card">
            <div class="card-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="card-value">{{ !empty($cleanData) && !empty($problematicData) ? number_format((count($cleanData) / (count($cleanData) + count($problematicData))) * 100, 1) : '0' }}%</div>
            <div class="card-label">Success Rate</div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="step-indicator">
        <div class="step {{ empty($headers) ? 'active' : 'completed' }}">1</div>
        <div class="step-line {{ empty($headers) ? '' : 'completed' }}"></div>
        <div class="step {{ empty($headers) ? 'pending' : (!empty($cleanData) ? 'completed' : 'active') }}">2</div>
        <div class="step-line {{ !empty($cleanData) ? 'completed' : '' }}"></div>
        <div class="step {{ !empty($cleanData) ? 'active' : 'pending' }}">3</div>
    </div>

    <!-- Main Content Card -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-message-square-edit"></i>
                {{ empty($headers) ? 'Upload CSV File' : 'SMS Campaign Composer' }}
            </h5>
        </div>
        <div class="card-body p-0" style="padding: 2rem !important;">
            {{-- Status Messages --}}
            @if(session('success'))
                <div class="alert modern-alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert modern-alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert modern-alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- Display Failed Sends Summary --}}
            @if(session('failedSendsSummary') && count(session('failedSendsSummary')) > 0)
                <div class="alert modern-alert alert-warning">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Warning: Some SMS sending jobs could not be queued</strong>
                    </div>
                    <div style="max-height: 200px; overflow-y: auto; border: 2px solid var(--ura-warning); border-radius: 8px; padding: 1rem; background: rgba(255, 206, 0, 0.05);">
                        <ul class="mb-0">
                            @foreach(session('failedSendsSummary') as $failed)
                                <li class="mb-2">
                                    <strong>Phone Number:</strong> {{ $failed['phone'] ?? 'N/A' }} <br>
                                    <strong>Reason:</strong> {{ $failed['reason'] ?? 'Unknown' }} <br>
                                    @if(isset($failed['message']))
                                        <strong>Message:</strong> "{{ Str::limit($failed['message'], 50) }}"
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <form action="{{ route('bulk.sms.export-failed') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="failed_sms_data" value="{{ base64_encode(json_encode(session('failedSendsSummary'))) }}">
                        <button type="submit" class="modern-btn modern-btn-warning btn-sm">
                            <i class="bx bx-download"></i>
                            Download Failed SMS Data CSV
                        </button>
                    </form>
                </div>
            @endif

            {{-- Step 1: Upload CSV File --}}
            @if(empty($headers))
                <form action="{{ route('bulk.sms.parse') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="file-upload-area" onclick="document.getElementById('csv_file').click()">
                        <div class="upload-icon">
                            <i class="bx bx-cloud-upload"></i>
                        </div>
                        <h5 class="mb-2" style="color: var(--ura-primary);">Upload Data File</h5>
                        <p class="mb-3" style="color: #6c757d;">Click to browse or drag & drop your Excel/CSV file</p>
                        <input type="file" name="csv_file" id="csv_file" class="form-control"
                               style="position: absolute; opacity: 0; pointer-events: none;"
                               accept=".csv,.xlsx,.xls" required>
                        <small class="text-muted">Supported formats: .csv, .xlsx, .xls (Max 5MB)</small>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <i class="bx bx-upload"></i>
                            Load Composer Header
                        </button>
                    </div>
                </form>
            @else
                {{-- Step 2: Compose & Send SMS - Only if there's clean data --}}
                @if(!empty($cleanData) && count($cleanData) > 0)
                    <div class="alert modern-alert alert-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="bx bx-check-circle me-2"></i>
                                <strong>Ready to Send!</strong> You have <span class="badge bg-primary">{{ count($cleanData) }}</span> valid phone numbers ready for SMS sending.
                            </div>
                            <button class="modern-btn modern-btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#composeForm" aria-expanded="false" aria-controls="composeForm">
                                <i class="bx bx-edit"></i>
                                Compose Message
                            </button>
                        </div>
                    </div>

                    <div class="collapse" id="composeForm">
                        <form action="{{ route('bulk.sms.send') }}" method="POST" id="sendSmsForm">
                            @csrf
                            {{-- Pass only the clean CSV data --}}
                            <input type="hidden" name="csv_data" value="{{ $cleanRawCsv }}">
                            <input type="hidden" name="message_template" id="message_template">

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bx bx-message-square-edit me-2"></i>
                                    Compose SMS Message
                                </label>
                                <div id="sms-builder" class="sms-builder d-flex flex-wrap gap-2 align-items-center">
                                    {{-- Initial text input and field select --}}
                                    <div class="input-group flex-grow-1" data-type="text-group">
                                        <input type="text" class="form-control" placeholder="Type your text here..." oninput="buildTemplate()" data-type="text" maxlength="100">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeElement(this)" title="Remove this element">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                    <div class="input-group flex-grow-1" data-type="field-group">
                                        <select class="form-select" onchange="buildTemplate()" data-type="field">
                                            <option value="">-- Choose field --</option>
                                            @foreach($headers as $h)
                                                <option value="{{ $h }}" class="text-uppercase">{{ $h }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeElement(this)" title="Remove this element">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex gap-2 ms-auto">
                                        <button type="button" onclick="addTextInput()" class="modern-btn btn-sm" style="background: var(--ura-gradient-light); color: var(--ura-primary);">
                                            <i class="bx bx-plus"></i> Text
                                        </button>
                                        <button type="button" onclick="addFieldSelect()" class="modern-btn btn-sm" style="background: var(--ura-gradient-light); color: var(--ura-primary);">
                                            <i class="bx bx-plus"></i> Field
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Real-time Message Preview --}}
                            <div class="mb-4">
                                <label for="message_preview" class="form-label">
                                    <i class="bx bx-show me-2"></i>
                                    Live Message Preview
                                </label>
                                <textarea id="message_preview" class="form-control message-preview" rows="4" readonly placeholder="Your SMS message will appear here as you compose it..."></textarea>
                                <small class="text-muted mt-1 d-block">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Character count: <span id="charCount">0</span>/160 (Standard SMS)
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="phone_field" class="form-label">
                                    <i class="bx bx-phone me-2"></i>
                                    Phone Number Column
                                </label>
                                <select name="phone_field" id="phone_field" class="form-select" required onchange="validatePhoneField()">
                                    @foreach($headers as $header)
                                        <option value="{{ $header }}" @if($loop->last) selected @endif>{{ strtoupper($header) }}</option>
                                    @endforeach
                                </select>
                                <div id="phone-field-feedback" class="invalid-feedback d-block"></div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="modern-btn modern-btn-success" id="sendSmsButton" onclick="return prepareAndSendSms()">
                                    <i class="bx bx-send"></i>
                                    Send SMS Campaign
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <hr>

                {{-- Display Clean Data --}}
                @if(!empty($cleanData) && count($cleanData) > 0)
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h5 class="modern-card-title">
                                <i class="bx bx-check-shield"></i>
                                Valid Recipients Data ({{ count($cleanData) }} rows)
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table modern-table mb-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            @foreach($headers as $h)
                                                <th><i class="bx bx-data me-1"></i>{{ strtoupper($h) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cleanData as $row)
                                            <tr>
                                                @foreach($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Display Problematic Data --}}
                @if(!empty($problematicData) && count($problematicData) > 0)
                    <div class="modern-card">
                        <div class="modern-card-header" style="background: linear-gradient(135deg, rgba(255, 206, 0, 0.1) 0%, rgba(255, 206, 0, 0.05) 100%);">
                            <h5 class="modern-card-title" style="color: var(--ura-warning);">
                                <i class="bx bx-error-circle"></i>
                                Problematic Data ({{ count($problematicData) }} rows)
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 2rem;">
                            <div class="alert modern-alert alert-warning">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Data Quality Issues:</strong> The following rows contain invalid phone numbers or missing data.
                                You can download this data to correct it and re-upload.
                                <form action="{{ route('bulk.sms.export-problematic') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="problematic_csv_data" value="{{ base64_encode(json_encode(['headers' => $headers, 'data' => $problematicData])) }}">
                                    <button type="submit" class="modern-btn modern-btn-warning btn-sm">
                                        <i class="bx bx-download"></i>
                                        Download Problematic Data CSV
                                    </button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table modern-table mb-0" id="problematicDataTable">
                                    <thead>
                                        <tr>
                                            @foreach($headers as $h)
                                                <th><i class="bx bx-data me-1"></i>{{ strtoupper($h) }}</th>
                                            @endforeach
                                            <th><i class="bx bx-error me-1"></i>Issue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($problematicData as $row)
                                            <tr>
                                                @foreach($row['data'] as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                                <td>
                                                    <span class="badge" style="background: var(--ura-warning); color: #333;">
                                                        {{ $row['reason'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            @endif
        </div>
    </div>
</div>

{{-- JavaScript for dynamic SMS builder --}}
<script>
    // Clean data from server, used for client-side phone field validation
    const cleanCsvData = @json($cleanData ?? []);
    const headers = @json($headers ?? []);

    /**
     * Adds a new text input field to the SMS builder.
     */
    function addTextInput() {
        const builder = document.getElementById('sms-builder');
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group flex-grow-1';
        wrapper.setAttribute('data-type', 'text-group');

        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.placeholder = 'Type text here...';
        input.setAttribute('data-type', 'text');
        input.setAttribute('maxlength', '100'); // Limit to 100 characters
        input.oninput = buildTemplate; // Call buildTemplate on every input

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm';
        removeBtn.textContent = 'x';
        removeBtn.onclick = function() { removeElement(this); };

        wrapper.appendChild(input);
        wrapper.appendChild(removeBtn);
        builder.insertBefore(wrapper, builder.querySelector('button.ms-auto')); // Insert before the add buttons to maintain order
        buildTemplate(); // Rebuild template after adding a new element
    }

    /**
     * Adds a new field select dropdown to the SMS builder.
     */
    function addFieldSelect() {
        const builder = document.getElementById('sms-builder');
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group flex-grow-1';
        wrapper.setAttribute('data-type', 'field-group');

        const select = document.createElement('select');
        select.className = 'form-select';
        select.setAttribute('data-type', 'field');
        select.onchange = buildTemplate; // Call buildTemplate on every change

        const defaultOption = new Option('-- Choose field --', '');
        select.add(defaultOption);

        if (headers.length > 0) {
            headers.forEach(h => {
                select.add(new Option(h.toUpperCase(), h));
            });
        }

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm';
        removeBtn.textContent = 'x';
        removeBtn.onclick = function() { removeElement(this); };

        wrapper.appendChild(select);
        wrapper.appendChild(removeBtn);
        builder.insertBefore(wrapper, builder.querySelector('button.ms-auto')); // Insert before the add buttons to maintain order
        buildTemplate(); // Rebuild template after adding a new element
    }

    /**
     * Removes an element (text input or field select) from the SMS builder.
     * @param {HTMLElement} button The remove button clicked.
     */
    function removeElement(button) {
        // Find the parent input-group and remove it
        button.closest('.input-group').remove();
        buildTemplate(); // Rebuild template after removing an element
    }

    /**
     * Builds the SMS message template from all text inputs and selected fields
     * and updates both the hidden input and the real-time preview textarea.
     */
    function buildTemplate() {
        const builder = document.getElementById('sms-builder');
        const elements = Array.from(builder.querySelectorAll('[data-type="text"], [data-type="field"]'));
        let message = '';

        elements.forEach(element => {
            if (element.dataset.type === 'text') {
                message += element.value + ' ';
            } else if (element.dataset.type === 'field' && element.value) {
                message += '{' + element.value + '} ';
            }
        });

        const finalMessage = message.trim();
        document.getElementById('message_template').value = finalMessage; // Update the hidden input for form submission
        document.getElementById('message_preview').value = finalMessage; // Update the real-time preview textarea

        // Update character count
        const charCountElement = document.getElementById('charCount');
        if (charCountElement) {
            charCountElement.textContent = finalMessage.length;

            // Color coding for character count
            if (finalMessage.length > 160) {
                charCountElement.style.color = 'var(--ura-danger)';
                charCountElement.style.fontWeight = 'bold';
            } else if (finalMessage.length > 140) {
                charCountElement.style.color = 'var(--ura-warning)';
                charCountElement.style.fontWeight = '600';
            } else {
                charCountElement.style.color = 'var(--ura-success)';
                charCountElement.style.fontWeight = '500';
            }
        }
    }

    /**
     * Validates the selected phone number column based on the loaded CSV data.
     * Checks if numbers start with '255' and are 12 digits long.
     * @returns {boolean} True if the phone field is valid, false otherwise.
     */
    function validatePhoneField() {
        const phoneFieldSelect = document.getElementById('phone_field');
        const feedbackDiv = document.getElementById('phone-field-feedback');
        const selectedColumnName = phoneFieldSelect.value;

        // Reset feedback styles
        phoneFieldSelect.classList.remove('is-invalid');
        feedbackDiv.classList.remove('text-success');
        feedbackDiv.classList.add('invalid-feedback'); // Ensure default invalid styling is there

        if (!selectedColumnName) {
            feedbackDiv.textContent = 'Please select a phone number column.';
            phoneFieldSelect.classList.add('is-invalid');
            return false;
        }

        const columnIndex = headers.indexOf(selectedColumnName);
        if (columnIndex === -1) {
            feedbackDiv.textContent = 'Selected column not found.';
            phoneFieldSelect.classList.add('is-invalid');
            return false;
        }

        let hasInvalidNumbers = false;
        let invalidCount = 0;
        const totalRows = cleanCsvData.length;

        // Check if cleanCsvData exists and has rows
        if (!cleanCsvData || cleanCsvData.length === 0) {
             feedbackDiv.textContent = 'No valid CSV data loaded for phone number validation.';
             phoneFieldSelect.classList.add('is-invalid');
             return false;
        }

        for (const row of cleanCsvData) {
            if (row[columnIndex] !== undefined && row[columnIndex] !== null) { // Check for undefined/null
                const phoneNumber = String(row[columnIndex]).trim();
                // Validation logic (12 digits, starts with 255)
                if (!/^255\d{9}$/.test(phoneNumber)) {
                    hasInvalidNumbers = true;
                    invalidCount++;
                }
            } else {
                // Consider empty cell in selected phone column as invalid for strictness
                hasInvalidNumbers = true;
                invalidCount++;
            }
        }

        if (hasInvalidNumbers) {
            feedbackDiv.textContent = `Column '${selectedColumnName.toUpperCase()}' has ${invalidCount} invalid phone numbers (out of ${totalRows} rows).
Ensure all numbers start with '255' and are 12 digits long.`;
            phoneFieldSelect.classList.add('is-invalid');
            return false;
        } else {
            feedbackDiv.textContent = 'Phone number column appears valid.';
            feedbackDiv.classList.remove('invalid-feedback'); // Remove invalid styling
            feedbackDiv.classList.add('text-success'); // Add success styling
            phoneFieldSelect.classList.remove('is-invalid');
            return true;
        }
    }

    /**
     * Prepares the form for submission, performs final validations,
     * and shows a loading indicator.
     * @returns {boolean} True if the form should be submitted, false otherwise.
     */
    function prepareAndSendSms() {
        buildTemplate(); // Ensure the template is updated one last time
        const messageTemplate = document.getElementById('message_template').value;
        const sendButton = document.getElementById('sendSmsButton');
        const smsForm = document.getElementById('sendSmsForm');

        if (!messageTemplate.trim()) {
            alert("Please compose a message before sending.");
            return false; // Return false to prevent form submission
        }

        // Validate phone field before sending
        if (!validatePhoneField()) {
            alert("Please correct the phone number column before sending SMS.");
            return false; // Return false to prevent form submission
        }

        // Show progress indicator
        sendButton.disabled = true;
        sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

        // Explicitly submit the form
        smsForm.submit();
        // After explicitly submitting, return false to prevent the button's default action
        // from attempting to submit the form again (which might not happen, but is good practice)
        return false;
    }

    // Call buildTemplate on page load to initialize the hidden message_template field and the preview textarea
    document.addEventListener('DOMContentLoaded', () => {
        buildTemplate();
        // Also validate phone field on page load if cleanData is available
        @if(!empty($cleanData) && count($cleanData) > 0)
            validatePhoneField();
        @endif
    });

    // File upload drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const fileUploadArea = document.querySelector('.file-upload-area');
        const fileInput = document.getElementById('csv_file');

        if (fileUploadArea && fileInput) {
            // Drag and drop functionality
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.style.backgroundColor = 'rgba(0, 188, 212, 0.1)';
                fileUploadArea.style.borderColor = 'var(--ura-accent)';
                fileUploadArea.style.transform = 'scale(1.02)';
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                resetUploadAreaStyle();
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                resetUploadAreaStyle();

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    displayFileInfo(files[0]);
                }
            });

            // File input change handler
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    displayFileInfo(e.target.files[0]);
                }
            });

            function resetUploadAreaStyle() {
                fileUploadArea.style.backgroundColor = '';
                fileUploadArea.style.borderColor = '';
                fileUploadArea.style.transform = '';
            }

            function displayFileInfo(file) {
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                const uploadIcon = fileUploadArea.querySelector('.upload-icon');
                const title = fileUploadArea.querySelector('h5');
                const description = fileUploadArea.querySelector('p');

                // Update the display to show file info
                uploadIcon.innerHTML = '<i class="bx bx-file-doc"></i>';
                title.textContent = file.name;
                title.style.color = 'var(--ura-success)';
                description.innerHTML = `<strong>File size:</strong> ${sizeInMB} MB<br><strong>Type:</strong> ${file.type || 'Unknown'}`;

                // Validate file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    title.style.color = 'var(--ura-danger)';
                    description.innerHTML += '<br><span style="color: var(--ura-danger);">⚠️ File too large (max 5MB)</span>';
                }
            }
        }
    });
</script>

{{-- DataTables initializations --}}
<script>
    // Initialize the first DataTable for clean data
    @if(!empty($cleanData) && count($cleanData) > 0)
        let cleanDataTableInstance = new DataTable('#dataTable');
    @endif
</script>

@if(!empty($problematicData) && count($problematicData) > 0)
    <script>
        // Initialize the second DataTable for problematic data
        let problematicDataTableInstance = new DataTable('#problematicDataTable');
    </script>
@endif

@endsection