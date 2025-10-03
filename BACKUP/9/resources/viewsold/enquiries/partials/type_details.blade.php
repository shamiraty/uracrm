{{-- Partial view for displaying type-specific enquiry details --}}
@switch($enquiry->type)
    @case('loan_application')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Loan Type:</strong> <span>{{ $enquiry->loan_type }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Loan Amount:</strong> <span>{{ number_format($enquiry->loan_amount, 2) }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Loan Duration:</strong> <span>{{ $enquiry->loan_duration }} months</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Loan Category:</strong> <span>{{ $enquiry->loan_category }}</span>
        </li>
        @break

    @case('refund')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Refund Amount:</strong> <span>{{ number_format($enquiry->refund_amount, 2) }}</span>
        </li>
        @break

    @case('share_enquiry')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Share Amount:</strong> <span>{{ number_format($enquiry->share_amount, 2) }}</span>
        </li>
        @break

    @case('retirement')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Date of Retirement:</strong> <span>{{ $enquiry->date_of_retirement }}</span>
        </li>
        @break

    @case('withdraw_savings')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Withdraw Saving Amount:</strong> <span>{{ number_format($enquiry->withdraw_saving_amount, 2) }}</span>
        </li>
        @break

    @case('withdraw_deposit')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Withdraw Deposit Amount:</strong> <span>{{ number_format($enquiry->withdraw_deposit_amount, 2) }}</span>
        </li>
        @break

    @case('unjoin_membership')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Category:</strong> <span>{{ $enquiry->category }}</span>
        </li>
        @break

    @case('benefit_from_disasters')
        <li class="list-group-item d-flex justify-content-between">
            <strong>Benefit Amount:</strong> <span>{{ number_format($enquiry->benefit_amount, 2) }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Benefit Description:</strong> <span>{{ $enquiry->benefit_description }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Benefit Remarks:</strong> <span>{{ $enquiry->benefit_remarks }}</span>
        </li>
        @break
@endswitch
