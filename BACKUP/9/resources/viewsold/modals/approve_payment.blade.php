{{-- >
<div class="modal fade" id="approvePaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title bg- text-uppercase text-white" id="approvePaymentModalLabel">Approve Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.approve', ['paymentId' => $enquiry->payment->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                <ul class="list-group">
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
        <strong>Amount to Approve:</strong>
        <span>${{ number_format($enquiry->payment->amount, 2) }}</span>
    </li>
</ul>

                    <div class="alert alert-danger mt-2">Are you sure you want to approve this payment?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<!-- Modal for Approving Payment -->
<div class="modal fade" id="approvePaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-uppercase text-white" id="approvePaymentModalLabel">Approve Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.approve', ['paymentId' => $enquiry->payment->id]) }}" method="POST"onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body row">
                    <!-- Enquiry Details Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <h5 class="card-header">Enquiry Details</h5>
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
                                        <strong>Amount to Approve:</strong>
                                        <span>TZS {{ number_format($enquiry->payment->amount, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

<div class="col-md-6">
    <div class="card">
    <h5 class="card-header">Note File</h5>
        <div class="card-body">
            @if($enquiry->payment->note_path)
                <div class="note-file-container">
                    <!-- Display an embedded PDF or a link to download other file types -->
                    @if(pathinfo($enquiry->payment->note_path, PATHINFO_EXTENSION) === 'pdf')
                        <embed src="{{ asset('/' . $enquiry->payment->note_path) }}" type="application/pdf" style="width:100%; height:400px;">
                    @else
                        <a href="{{ asset('/' . $enquiry->payment->note_path) }}" target="_blank">Download Note</a>
                    @endif
                </div>
            @else
                <p>No file attached.</p>
            @endif
        </div>
    </div>
</div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"id="submit-button-{{ $enquiry->id }}">Approve</button>
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
    }

</script>