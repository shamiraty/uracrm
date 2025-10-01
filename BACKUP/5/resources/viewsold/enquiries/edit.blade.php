@extends('layouts.app')

@section('title', 'Edit Enquiry')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-light text-primary">
                    <h5 class="text-primary">Edit Enquiry</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form id="wizard-form" method="POST" action="{{ route('enquiries.update', $enquiry->id) }}">
                        @csrf
                        @method('PUT')
                        <div id="wizard">
                            
                            <!-- Member Details Section -->
                            <h4>Member Details</h4>
                            <section>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="date_received">Date Received:</label>
                                            <input type="date" name="date_received" class="form-control" value="{{ $enquiry->date_received }}" required>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="force_no">Force Number:</label>
                                            <input type="text" name="force_no" class="form-control" value="{{ $enquiry->force_no }}" required>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="account_number">Account Number:</label>
                                            <input type="text" name="account_number" class="form-control" value="{{ $enquiry->account_number }}" required>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="bank_name">Name of the Bank:</label>
                                            <input type="text" name="bank_name" class="form-control" value="{{ $enquiry->bank_name }}" required>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="check_number">Check Number:</label>
                                            <input type="text" name="check_number" class="form-control" value="{{ $enquiry->check_number }}" required>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="full_name">Full Name:</label>
                                            <input type="text" name="full_name" class="form-control" value="{{ $enquiry->full_name }}" required>
                                        </div>
                                    </li>
                                </ul>
                            </section>

                            <!-- Enquiry Type Section -->
                            <h4>Enquiry Type</h4>
                            <section>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="type">Enquiry Type:</label>
                                            <select name="type" id="type" class="form-control" required onchange="toggleFields(this.value)">
                                                <option value="">Select Type</option>
                                                <option value="loan_application" {{ $enquiry->type === 'loan_application' ? 'selected' : '' }}>Loan Application</option>
                                                <option value="refund" {{ $enquiry->type === 'refund' ? 'selected' : '' }}>Refund</option>
                                                <option value="new_member" {{ $enquiry->type === 'new_member' ? 'selected' : '' }}>New Member Registration</option>
                                                <option value="withdraw_savings" {{ $enquiry->type === 'withdraw_savings' ? 'selected' : '' }}>Withdraw Savings</option>
                                                <option value="inheritance" {{ $enquiry->type === 'inheritance' ? 'selected' : '' }}>Inheritance</option>
                                                <option value="deduction_add" {{ $enquiry->type === 'deduction_add' ? 'selected' : '' }}>Add Deduction of Savings</option>
                                                <option value="termination" {{ $enquiry->type === 'termination' ? 'selected' : '' }}>Member Termination</option>
                                                <option value="retirement" {{ $enquiry->type === 'retirement' ? 'selected' : '' }}>Retirement</option>
                                                <option value="share_enquiry" {{ $enquiry->type === 'share_enquiry' ? 'selected' : '' }}> Share Enquiry</option>
                                                <option value="withdraw_deposit" {{ $enquiry->type === 'withdraw_deposit' ? 'selected' : '' }}>Withdraw Deposit</option>
                                                <option value="unjoin_membership" {{ $enquiry->type === 'unjoin_membership' ? 'selected' : '' }}>Unjoin Membership</option>
                                                <option value="benefit_from_disasters" {{ $enquiry->type === 'benefit_from_disasters' ? 'selected' : '' }}>Benefit from Disasters</option>
                                            </select>
                                        </div>
                                    </li>

                                    <!-- Conditional fields that will show based on the type selected -->
                                    <div id="loanFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="loan_type_reason">Loan Type/Reason:</label>
                                                <input type="text" name="loan_type" class="form-control" value="{{ $enquiry->loan_type_reason }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="amount">Loan Amount:</label>
                                                <input type="number" name="loan_amount" class="form-control" value="{{ $enquiry->amount }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="duration">Loan Duration:</label>
                                                <input type="number" name="loan_duration" class="form-control" value="{{ $enquiry->duration }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="shareFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="amount">Share Amount:</label>
                                                <input type="number" name="amount" class="form-control" value="{{ $enquiry->amount }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="retirementFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="date_of_retirement">Date of Retirement:</label>
                                                <input type="date" name="date_of_retirement" class="form-control" value="{{ $enquiry->date_of_retirement }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="amount">Amount:</label>
                                                <input type="number" name="amount" class="form-control" value="{{ $enquiry->amount }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="deductionFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="from_amount">From Amount:</label>
                                                <input type="number" name="from_amount" class="form-control" value="{{ $enquiry->from_amount }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="to_amount">To Amount:</label>
                                                <input type="number" name="to_amount" class="form-control" value="{{ $enquiry->to_amount }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="refundFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="amount">Amount:</label>
                                                <input type="number" name="amount" class="form-control" value="{{ $enquiry->amount }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="duration">Duration:</label>
                                                <input type="number" name="duration" class="form-control" value="{{ $enquiry->duration }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="withdrawFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="amount">Amount:</label>
                                                <input type="number" name="amount" class="form-control" value="{{ $enquiry->amount }}">
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="reason">Reason:</label>
                                                <input type="text" name="reason" class="form-control" value="{{ $enquiry->reason }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="unjoinFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="reason">Reason for Unjoining:</label>
                                                <input type="text" name="reason" class="form-control" value="{{ $enquiry->reason }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="inheritanceFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="relation">Relation:</label>
                                                <input type="text" name="relation" class="form-control" value="{{ $enquiry->relation }}">
                                            </div>
                                        </li>
                                    </div>

                                    <div id="membershipFields" style="display: none;">
                                        <li class="list-group-item">
                                            <div class="form-group">
                                                <label for="reason">Reason for Membership Termination:</label>
                                                <input type="text" name="reason" class="form-control" value="{{ $enquiry->reason }}">
                                            </div>
                                        </li>
                                    </div>
                                </ul>
                            </section>
                            
                            <!-- Submit Section -->
                            
                            <section>
                                <button type="submit" class="btn btn-primary btn-sm">Update Enquiry</button>
                            </section>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
function toggleFields(type) {
    document.getElementById('loanFields').style.display = (type === 'loan_application') ? 'block' : 'none';
    document.getElementById('shareFields').style.display = (type === 'share_enquiry') ? 'block' : 'none';
    document.getElementById('retirementFields').style.display = (type === 'retirement') ? 'block' : 'none';
    document.getElementById('deductionFields').style.display = (type === 'deduction_add') ? 'block' : 'none';
    document.getElementById('refundFields').style.display = (type === 'refund') ? 'block' : 'none';
    document.getElementById('withdrawFields').style.display = (type === 'withdraw_savings' || type === 'withdraw_deposit') ? 'block' : 'none';
    document.getElementById('unjoinFields').style.display = (type === 'unjoin_membership') ? 'block' : 'none';
    document.getElementById('benefitFields').style.display = (type === 'benefit_from_disasters') ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    toggleFields(typeSelect.value);
    typeSelect.addEventListener('change', function() {
        toggleFields(this.value);
    });
});
</script>
@endsection

@push('scripts')
<!-- Plugin js for this page -->
<script src="{{ asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
<script>
$(document).ready(function() {
    $("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "fade",
        autoFocus: true,
        onFinishing: function (event, currentIndex) {
            $('#wizard-form').submit();
        }
    });
});
</script>
@endpush

