
<div class="modal fade" id="approveLoanModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="approveLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Loan Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.approve', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST" onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Detail Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-check-circle-fill"></i> Loan Details</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Loan Amount:</strong> {{ number_format($enquiry->loanApplication->loan_amount) }}</li>
                                    <li class="list-group-item"><strong>Duration:</strong> {{ $enquiry->loanApplication->loan_duration }} months</li>
                                    <li class="list-group-item"><strong>Interest:</strong> {{ $enquiry->loanApplication->interest_rate }}%</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Attachment Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-file-earmark-text-fill"></i> Attachments</strong>
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
                    <button type="submit" class="btn btn-success btn-sm"id="submit-button-{{ $enquiry->id }}">Approve Loan</button>
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

