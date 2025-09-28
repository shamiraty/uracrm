
 <div class="modal fade" id="processLoanModal-{{ $enquiry->loanApplication->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Loan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.process', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST"onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Detail Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-person-fill"></i> Applicant Details</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Force Number:</strong> {{ $enquiry->force_no }}</li>
                                    <li class="list-group-item"><strong>Bank:</strong> {{ $enquiry->bank_name }} - {{ $enquiry->account_number }}</li>
                                    <li class="list-group-item"><strong>District:</strong> {{ $enquiry->district }}</li>
                                    <li class="list-group-item"><strong>Phone:</strong> {{ $enquiry->phone }}</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Attachment Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-file-earmark-text"></i> Attachments</strong>
                                </div>
                                <div class="card-body">
                                    @if ($enquiry->file_path)
                                    <object data="{{ asset($enquiry->file_path) }}" type="application/pdf" width="100%" height="500px" class="border rounded">
                                        <p class="text-muted">Your browser does not support PDFs.
                                            <a href="{{ asset($enquiry->file_path) }}" class="text-decoration-underline">Download the PDF</a>.
                                        </p>
                                    </object>
                                    @else
                                        <p class="text-muted">No file uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"id="submit-button-{{ $enquiry->id }}">Process Loan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
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




