
@if($enquiry->loanApplication)
    @switch($enquiry->loanApplication->status)
        @case('pending')
            <li>
                @include('modals.process_loan')

            </li>

            @break
        @case('processed')
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#approveLoanModal-{{ $enquiry->loanApplication->id }}">
                    <i class="mdi mdi-check me-2"></i>Approve Loan
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectLoanModal-{{ $enquiry->loanApplication->id }}">
                    <i class="mdi mdi-close me-2"></i>Reject Loan
                </a>
            </li>
            @break
        @default
            <!-- Optionally handle other statuses with a generic message or action -->
            <li>
                <a class="dropdown-item" href="#">
                    <i class="mdi mdi-alert me-2"></i>Check Status
                </a>
            </li>
    @endswitch
@endif


