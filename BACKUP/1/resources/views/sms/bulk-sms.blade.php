@extends('layouts.app')

@section('content')

<div class="container mt-1">
    <div class="card">
        <div class="card-header">Bulk SMS Composer</div>
        <div class="card-body">
            {{-- Status Messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Display Failed Sends Summary (New) --}}
            @if(session('failedSendsSummary') && count(session('failedSendsSummary')) > 0)
                <div class="alert alert-warning mt-3">
                    <p><strong>Warning: Some SMS sending jobs could not be queued:</strong></p>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ffc107; padding: 10px; background-color: #fff3cd;">
                        <ul>
                            @foreach(session('failedSendsSummary') as $failed)
                                <li>
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
                        <button type="submit" class="btn btn-warning btn-sm">Download Failed SMS Data CSV</button>
                    </form>
                </div>
            @endif

            {{-- Step 1: Upload CSV File --}}
            @if(empty($headers))
                <form action="{{ route('bulk.sms.parse') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Upload CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control p-4" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Load Composer Header</button>
                </form>
            @else
                {{-- Step 2: Compose & Send SMS - Only if there's clean data --}}
                @if(!empty($cleanData) && count($cleanData) > 0)
                    <div class="alert alert-info">
                        <p>You have <strong class="text-lg">[ {{ count($cleanData) }} ]</strong> valid phone numbers ready for SMS sending.
                            Do you wish to proceed?</p>
                        <button class="btn btn-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#composeForm" aria-expanded="false" aria-controls="composeForm">
                            Yes, Compose Message
                        </button>
                    </div>

                    <div class="collapse" id="composeForm">
                        <form action="{{ route('bulk.sms.send') }}" method="POST" id="sendSmsForm">
                            @csrf
                            {{-- Pass only the clean CSV data --}}
                            <input type="hidden" name="csv_data" value="{{ $cleanRawCsv }}">
                            <input type="hidden" name="message_template" id="message_template">

                            <div class="mb-3">
                                <label class="form-label">Compose Message</label>
                                <div id="sms-builder" class="d-flex flex-wrap gap-2 mb-2 p-4 border rounded bg-light align-items-center">
                                    {{-- Initial text input and field select --}}
                                    <div class="input-group flex-grow-1" data-type="text-group">
                                        <input type="text" class="form-control" placeholder="Type text here..." oninput="buildTemplate()" data-type="text" maxlength="100">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeElement(this)">x</button>
                                    </div>
                                    <div class="input-group flex-grow-1" data-type="field-group">
                                        <select class="form-select" onchange="buildTemplate()" data-type="field">
                                            <option value="">-- Choose field --</option>
                                            @foreach($headers as $h)
                                                <option value="{{ $h }}" class="text-uppercase">{{ $h }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeElement(this)">x</button>
                                    </div>

                                    <button type="button" onclick="addTextInput()" class="btn btn-outline-secondary btn-sm ms-auto">+ Text</button>
                                    <button type="button" onclick="addFieldSelect()" class="btn btn-outline-info btn-sm">+ Field</button>
                                </div>

                            </div>

                            {{-- Real-time Message Preview --}}
                            <div class="mb-3">
                                <label for="message_preview" class="form-label">Message Preview</label>
                                <textarea id="message_preview" class="form-control" rows="3" readonly style="background-color: #e9ecef;"></textarea>

                            </div>

                            <div class="mb-3">
                                <label for="phone_field" class="form-label">Phone Number Column</label>
                                <select name="phone_field" id="phone_field" class="form-select" required onchange="validatePhoneField()">
                                    @foreach($headers as $header)
                                        <option class="text-uppercase text-danger" value="{{ $header }}" @if($loop->last) selected @endif>{{ $header }}</option>
                                    @endforeach
                                </select>
                                <div id="phone-field-feedback" class="invalid-feedback d-block"></div>
                            </div>

                            <button type="submit" class="btn btn-success btn-sm" id="sendSmsButton" onclick="return prepareAndSendSms()">Send SMS</button>
                        </form>
                    </div>
                @endif

                <hr>

                {{-- Display Clean Data --}}
                @if(!empty($cleanData) && count($cleanData) > 0)
                    <div class="card shadow-lg basic-data-table mt-3">
                        <div class="card-header">Preview Valid CSV Data ({{ count($cleanData) }} rows)</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-primary-table mb-0 w-100" id="dataTable">
                                    <thead class="table-primary">
                                        <tr>
                                            @foreach($headers as $h)
                                                <th>{{ $h }}</th>
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
                    <div class="card shadow-lg basic-data-table mt-4">
                        <div class="card-header bg-warning text-dark">Problematic CSV Data ({{ count($problematicData) }} rows)</div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <p>The following rows contain invalid phone numbers or missing data.
                                    You can download this data to correct it.</p>
                                <form action="{{ route('bulk.sms.export-problematic') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="problematic_csv_data" value="{{ base64_encode(json_encode(['headers' => $headers, 'data' => $problematicData])) }}">
                                    <button type="submit" class="btn btn-warning btn-sm">Download Problematic Data CSV</button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table border-primary-table mb-0 w-100" id="problematicDataTable">
                                    <thead class="table-warning">
                                        <tr>
                                            @foreach($headers as $h)
                                                <th>{{ $h }}</th>
                                            @endforeach
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($problematicData as $row)
                                            <tr>
                                                @foreach($row['data'] as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                                <td>{{ $row['reason'] }}</td>
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
        document.getElementById('message_template').value = message.trim(); // Update the hidden input for form submission
        document.getElementById('message_preview').value = message.trim(); // Update the real-time preview textarea
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