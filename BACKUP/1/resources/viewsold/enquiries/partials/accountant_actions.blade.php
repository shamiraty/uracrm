

@if ($enquiry->payment)
    @switch($enquiry->payment->status)
        @case('initiated')

            <li>
                <!-- Button to trigger modal for approval -->
                <a href="#" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#approvePaymentModal-{{ $enquiry->payment->id }}">
                    <i class="mdi mdi-check me-2"></i>Approve Payment
                </a>
            </li>

            @can('reject', $enquiry->payment)
            <li>
                <!-- Button to trigger modal for rejection -->
                <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal-{{ $enquiry->payment->id }}">
                    <i class="mdi mdi-close me-2"></i>Reject Payment
                </a>
            </li>
            @endcan
            @break
        @case('approved')

       
            <li>
                <!-- Trigger button for OTP Dispatch Modal -->
                <button type="button" class="dropdown-item text-muted" data-bs-toggle="modal" data-bs-target="#payPaymentModal-{{ $enquiry->payment->id }}">
                    <i class="mdi mdi-check-circle me-2"></i>Pay Payment
                </button>
            </li>





            @break
        @case('rejected')
            <li>
                <span class="dropdown-item text-muted">
                    <i class="mdi mdi-close-circle me-2"></i>Payment Rejected
                </span>
            </li>
            @break
        @default
            {{-- <li>
                <span class="dropdown-item text-warning">
                    <i class="mdi mdi-alert me-2"></i>Unknown Payment Status
                </span>
            </li> --}}
    @endswitch
@else
    @if ($enquiry->status == 'assigned')
        <li>
            <!-- Button to trigger modal for initiating payment -->
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#initiatePaymentModal-{{ $enquiry->id }}">
                <i class="mdi mdi-cash-plus me-2"></i>Initiate Payment
            </a>
        </li>
<<<<<<< HEAD

=======
>>>>>>> parent of 0032dbe (otp)
    @endif
@endif

