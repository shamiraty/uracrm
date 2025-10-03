{{-- <!-- Modal for Rejecting Payment -->
<div class="modal fade" id="rejectPaymentModal-{{ $paymentId }}" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectPaymentModalLabel">Reject Payment for {{ $enquiry->full_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.reject', ['paymentId' => $paymentId]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="remarks-{{ $paymentId }}" class="col-form-label">Remarks:</label>
                        <textarea class="form-control" id="remarks-{{ $paymentId }}" name="remarks" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
<!-- Modal for Rejecting Payment -->
<div class="modal fade" id="rejectPaymentModal-{{ $enquiry->payment->id }}" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white text-uppercase" id="rejectPaymentModalLabel">Reject Payment for {{ $enquiry->full_name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.reject', ['paymentId' => $enquiry->payment->id]) }}" method="POST">
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
        <strong>Amount to Reject:</strong>
        <span>TZS {{ number_format($enquiry->payment->amount, 2) }}</span>
    </li>
</ul>

                    <div class="mb-3">
                        <label for="remarks-{{ $enquiry->payment->id }}" class="col-form-label">Remarks for Rejection:</label>
                        <textarea class="form-control" id="remarks-{{ $enquiry->payment->id }}" name="remarks" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type of button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
