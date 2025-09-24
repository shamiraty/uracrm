<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal for Initiating Payment -->
<div class="modal fade" id="initiatePaymentModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="initiatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white text-uppercase" id="initiatePaymentModalLabel">Initiate Payment for [{{ $enquiry->full_name }}]</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.initiate', ['enquiryId' => $enquiry->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body row">
                    <!-- Details Column in Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <h6 class="card-header bg-light">Enquiry Details</h6>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Full Name:</strong>
                                        <span>{{ $enquiry->full_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Check Number:</strong>
                                        <span>{{ $enquiry->check_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Account:</strong>
                                        <span>{{ $enquiry->account_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Bank Name:</strong>
                                        <span>{{ $enquiry->bank_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Phone Number:</strong>
                                        <span>{{ $enquiry->phone }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Amount:</strong>
                                        <span>
                                            @switch($enquiry->type)
                                                @case('loan')
                                                    TSHS {{ number_format($enquiry->loan_amount, 2) }}
                                                    @break
                                                @case('refund')
                                                    TSHS {{ number_format($enquiry->refund_amount, 2) }}
                                                    @break
                                                @case('withdraw_savings')
                                                    TSHS {{ number_format($enquiry->withdraw_saving_amount, 2) }}
                                                    @break
                                                @case('withdraw_deposit')
                                                    TSHS {{ number_format($enquiry->withdraw_deposit_amount, 2) }}
                                                    @break
                                                @case('retirement')
                                                    TSHS {{ number_format($enquiry->retirement_amount, 2) }}
                                                    @break
                                                @case('benefit')
                                                    TSHS {{ number_format($enquiry->benefit_amount, 2) }}
                                                    @break
                                                @default
                                                    Not applicable
                                            @endswitch
                                        </span>
                                    </li>
                                </ul>
                                <div class="mb-3">
                                    <label for="amount-{{ $enquiry->id }}" class="col-form-label">Enter Amount:</label>
                                    <input type="number" class="form-control" id="amount-{{ $enquiry->id }}" name="amount" required>
                                </div>
                                <div class="mb-3">
                                    <label for="note-{{ $enquiry->id }}" class="form-label">Upload Note File:</label>
                                    <input type="file" class="form-control" id="note-{{ $enquiry->id }}" name="note" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- File Upload Column in Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <h6 class="card-header bg-light">Uploaded Note</h6>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div id="preview-{{ $enquiry->id }}" class="preview-container mt-2">
                                        <!-- Preview will be displayed here after file selection -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="submit-button-{{ $enquiry->id }}">Initiate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('note-{{ $enquiry->id }}').addEventListener('change', function(event) {
        var output = document.getElementById('preview-{{ $enquiry->id }}');
        output.innerHTML = ''; // Clear existing previews
        var file = event.target.files[0];

        if (file.type.indexOf('image') > -1) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '200px'; // Limiting the preview size
                output.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            var pdfFrame = document.createElement('embed');
            pdfFrame.type = 'application/pdf';
            pdfFrame.style.width = '100%';
            pdfFrame.style.height = '300px'; // Adjust height as needed
            pdfFrame.src = URL.createObjectURL(file);
            output.appendChild(pdfFrame);
        } else {
            var textNode = document.createTextNode('Uploaded file: ' + file.name);
            output.appendChild(textNode);
        }
    });

    function showSpinner(form) {
        // Disable the submit button
        var submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Processing... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
