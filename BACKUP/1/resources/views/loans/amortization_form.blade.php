{{-- resources/views/loans/amortization_form.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Amortization Calculation for {{ $member->fullName }}</h2>
    <form action="{{ route('loans.calculate', $member->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="loanAmount">Loan Amount (TSh):</label>
            <input type="number" class="form-control" id="loanAmount" value="{{ $member->loanableAmount }}" disabled>
        </div>
        <div class="form-group">
            <label for="interestRate">Annual Interest Rate (%):</label>
            <input type="number" class="form-control" id="interestRate" name="interestRate" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="totalPeriods">Loan Period (Months):</label>
            <input type="number" class="form-control" id="totalPeriods" name="totalPeriods" required>
        </div>
        <button type="submit" class="btn btn-primary">Calculate Amortization</button>
    </form>
</div>
@endsection
