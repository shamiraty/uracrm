
 <div class="modal fade" id="rejectLoanModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="rejectLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Loan Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.reject', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST"onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Detail Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-x-circle-fill"></i> Applicant Details</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Loan Amount:</strong> {{ number_format($enquiry->loanApplication->loan_amount) }}</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Decision and Attachment Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Rejection Reason</strong>
                                </div>
                                <div class="card-body">
                                    <select class="form-control" id="rejection_reason" name="rejection_reason">
                                        <option value="">Select Reason</option>
                                        <option value="incomplete_application">Incomplete Application</option>
                                        <option value="credit_history">Poor Credit History</option>
                                        <option value="not_eligible">Not Eligible</option>
                                        <option value="other">Other (Specify)</option>
                                    </select>
                                    <textarea class="form-control mt-2" id="rejection_detail" name="rejection_detail" placeholder="Details (if 'Other')" style="display: none;"></textarea>
                                    <!-- Attachments -->
                                    <div class="mt-3">
                                        <h6 class="card-title"><strong>Attachments:</strong></h6>
                                        @if ($enquiry->file_path)
                                            <object data="{{ asset($enquiry->file_path) }}" type="application/pdf" width="100%" height="200px" class="border rounded">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-sm"id="submit-button-{{ $enquiry->id }}">Reject Loan</button>
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

