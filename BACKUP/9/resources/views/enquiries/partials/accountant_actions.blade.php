

@if ($enquiry->payment)
    @switch($enquiry->payment->status)
        @case('initiated')

        
            <li>
                <!-- Directly trigger OTP sending for approval verification -->
                <a href="#" class="dropdown-item text-muted" onclick="triggerApprovalSend({{ $enquiry->payment->id }})">
                    <i class="mdi mdi-check-circle me-2"></i>Approve Payment
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
                <!-- Directly trigger OTP sending for payment verification -->
                <a href="#" class="dropdown-item text-muted" onclick="triggerOtpSend({{ $enquiry->payment->id }})">
                    <i class="mdi mdi-check-circle me-2"></i>Pay Payment
                </a>
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

    @endswitch
@else
    @if ($enquiry->status == 'assigned')
        <li>
            <!-- Button to trigger modal for initiating payment -->
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#initiatePaymentModal-{{ $enquiry->id }}">
                <i class="mdi mdi-cash-plus me-2"></i>Initiate Payment
            </a>
        </li>

    @endif
@endif

