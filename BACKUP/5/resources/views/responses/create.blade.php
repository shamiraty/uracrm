@extends('layouts.app')

@section('title', 'Respond to Enquiry')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Respond to {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }} Enquiry</h4>
                <form method="POST" action="{{ route('responses.store', $enquiry->id) }}">
                    @csrf
                    @if(in_array($enquiry->type, ['loan_application', 'refund', 'withdraw_savings', 'deduction_add', 'retirement', 'benefit_from_disasters', 'withdraw_deposit', 'buy_shares']))
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                    @endif

                    @if($enquiry->type == 'loan_application' || $enquiry->type == 'refund')
                        <div class="form-group">
                            <label for="duration">Duration:</label>
                            <input type="number" name="duration" class="form-control" required>
                        </div>
                    @endif

                    @if($enquiry->type == 'loan_application')
                        <div class="form-group">
                            <label for="interest">Interest:</label>
                            <input type="number" step="0.01" name="interest" class="form-control" required>
                        </div>
                    @endif

                    @if($enquiry->type == 'deduction_add')
                        <div class="form-group">
                            <label for="from_amount">From Amount:</label>
                            <input type="number" step="0.01" name="from_amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="to_amount">To Amount:</label>
                            <input type="number" step="0.01" name="to_amount" class="form-control" required>
                        </div>
                    @endif

                    @if($enquiry->type == 'retirement')
                        <div class="form-group">
                            <label for="date_of_retirement">Date of Retirement:</label>
                            <input type="date" name="date_of_retirement" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                    @endif

                    @if($enquiry->type == 'benefit_from_disasters')
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    @endif

                    @if($enquiry->type == 'unjoin_membership')
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea name="remarks" class="form-control" required></textarea>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Submit Response</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
