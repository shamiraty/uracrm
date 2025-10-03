{{-- @extends('layouts.app')

@section('title', 'Create Enquiry')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Enquiry</h4>
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <form id="wizard-form" method="POST" action="{{ route('enquiries.store') }}">
                    @csrf
                    <div id="wizard">
                        <h2>Member Details</h2>
                        <section>
                            <div class="row mb-3">
                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_received"></label>
                                <input type="date" name="date_received"   class="form-control" class="feather feather-plus" required>
                            </div></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">Full Name:</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div></div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="force_no">Force Number:</label>
                                <input type="text" name="force_no" class="form-control" required>
                            </div></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_number">Check Number:</label>
                                    <input type="text" name="check_number" class="form-control" required>
                                </div></div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="account_number">Account Number:</label>
                                <input type="text" name="account_number" class="form-control" required>
                            </div></div>

                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_name">Name of the Bank:</label>
                                <input type="text" name="bank_name" class="form-control" required>
                            </div></div>

                           </div>
                        </section>

                        <h2>Enquiry Type</h2>
                        <section>
                            <div class="row mb-3">
                                <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Enquiry Type:</label>
                                <select name="type" id="type" class="form-control" required onchange="toggleFields(this.value)">
                                    <option value="">Select Type</option>
                                    <option value="loan_application">Loan Application</option>
                                    <option value="refund">Refund</option>
                                    <option value="share_enquiry">Share Enquiry</option>
                                    <option value="retirement">Retirement</option>
                                    <option value="deduction_add">Add Deduction of Savings</option>
                                    <option value="withdraw_savings">Withdraw Savings</option>
                                    <option value="withdraw_deposit">Withdraw Deposit</option>
                                    <option value="unjoin_membership">Unjoin Membership</option>
                                    <option value="benefit_from_disasters">Benefit from Disasters</option>
                                </select>
                            </div></div></div>

                            <!-- Conditional fields that will show based on the type selected -->

                            <div id="additionalFields" style="display: none;">
                                <div id="loanFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loan Type/Reason:</label>
                                        <input type="text" name="loan_type" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loan Amount:</label>
                                        <input type="number" step="0.01" name="loan_amount" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loan Duration:</label>
                                        <input type="number" name="loan_duration" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="shareFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Share Amount:</label>
                                        <input type="number" step="0.01" name="amount" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="retirementFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Retirement:</label>
                                        <input type="date" name="date_of_retirement" class="form-control">
                                    </div></div><div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount:</label>
                                        <input type="number" step="0.01" name="amount" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="deductionFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From Amount:</label>
                                        <input type="number" step="0.01" name="from_amount" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To Amount:</label>
                                        <input type="number" step="0.01" name="to_amount" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="refundFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount:</label>
                                        <input type="number" step="0.01" name="amount" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration:</label>
                                        <input type="number" name="duration" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="withdrawSavingsFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount:</label>
                                        <input type="number" step="0.01" name="amount" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reason:</label>
                                        <input type="text" name="reason" class="form-control">
                                    </div></div>
                                </div></div>

                                <div id="withdrawDepositFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6"><div class="form-group">
                                        <label>Amount:</label>
                                        <input type="number" step="0.01" name="withdraw_deposit_amount" class="form-control">
                                    </div></div><div class="col-md-6">
                                    </div>
                                </div></div>

                                <div id="unjoinMembershipFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reason:</label>
                                        <input type="text" name="reason" class="form-control">
                                    </div></div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category:</label>
                                        <select name="category" class="form-control">
                                            <option value="normal">Normal</option>
                                            <option value="job_termination">Job Termination</option>
                                        </select>
                                    </div></div>
                                </div></div>

                                <div id="benefitFields" style="display: none;">
                                    <div class="row mb-3">
                                    <div class="col-md-6">
                                    {{-- <div class="form-group">
                                        <label>Amount:</label>
                                        <input type="hidden" step="0.01" name="amount" class="form-control">
                                    </div></div> --}}
                                    <div class="col-md-6">
                                   </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category:</label>
                                            <select name="benefit_group" class="form-control">
                                                <option value="ajari">ajari</option>
                                                <option value="kuugua">kuugua</option>
                                            </select>
                                        </div></div>
                                </div>
                            </div>
                            </div>
                        </section>

                        <h2>Address/Location</h2>
                        <section>
                            <div class="row mb-3">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="district">District:</label>
                                <input type="text" name="district" class="form-control" required>
                            </div></div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div></div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="region">Region:</label>
                                <input type="text" name="region" class="form-control" required>
                            </div></div></div>
                        </section>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFields(type) {
    const fields = {
        loan_application: 'loanFields',
        refund: 'refundFields',
        share_enquiry: 'shareFields',
        retirement: 'retirementFields',
        deduction_add: 'deductionFields',
        withdraw_savings: 'withdrawSavingsFields',
        withdraw_deposit: 'withdrawDepositFields',
        unjoin_membership: 'unjoinMembershipFields',
        benefit_from_disasters: 'benefitFields'
    };

    document.getElementById('additionalFields').style.display = 'block';

    Object.keys(fields).forEach(field => {
        document.getElementById(fields[field]).style.display = field === type ? 'block' : 'none';
    });
}

</script>
@endsection

@push('scripts')
<!-- Plugin js for this page -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> --}}

<script src="{{ asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>



<script>
    $(document).ready(function() {
        // Initialize the wizard
        $("#wizard").steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "fade",
            autoFocus: true,
            onFinishing: function (event, currentIndex) {
                $('#wizard-form').submit();
            }
        });

     // Initialize Flatpickr for date inputs
     $('input[name="date_received"], input[name="date_of_retirement"]').flatpickr({
        dateFormat: 'Y-m-d',
        enableTime: false,
        altInput: true,
        altFormat: "F j, Y",
    });
    });
    </script>
@endpush --}}
