<!-- Modal for Viewing Enquiry Details -->
<div class="modal fade" id="viewDetailsModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="viewDetailsModalLabel-{{ $enquiry->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel-{{ $enquiry->id }}">Enquiry Details: {{ $enquiry->full_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Date Received: {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                    <li>Full Name: {{ $enquiry->full_name }}</li>
                    <li>Force Number: {{ $enquiry->force_no }}</li>
                    <li>Check Number: {{ $enquiry->check_number }}</li>
                    <li>Account Number: {{ $enquiry->account_number }}</li>
                    <li>Bank Name: {{ $enquiry->bank_name }}</li>
                    <li>District: {{ $enquiry->district }}</li>
                    <li>Phone Number: {{ $enquiry->phone }}</li>
                    <li>Region: {{ $enquiry->region }}</li>
                    <li>Type of Enquiry: {{ $enquiry->type }}</li>
                    <li>Status: {{ $enquiry->status }}</li>
                    <!-- Include type-specific details -->
                    @include('enquiries.partials._type_details', ['enquiry' => $enquiry])
                    <!-- Payment Details -->
                    @if ($enquiry->payment)
                        <li>Payment Status: {{ $enquiry->payment->status }}</li>
                        <li>Payment Amount: ${{ number_format($enquiry->payment->amount, 2) }}</li>
                        <li>Payment Date: {{ optional($enquiry->payment->payment_date)->format('d/m/Y') }}</li>
                    @else
                        <li>No Payment Details Available</li>
                    @endif
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
