<!-- resources/views/monthly_deductions/list.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Monthly Deductions</h1>

    @if($deductions->count())
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Loan Number</th>
                <th>Check Number</th>
                <th>Employee Name</th>
                <th>National ID</th>
                <th>Vote Code</th>
                <th>Vote Name</th>
                <th>Department</th>
                <th>Deduction</th>
                <th>Balance Amount</th>
                <th>Deduction Amount</th>
                <th>Has Stop Pay</th>
                <th>Stop Pay Reason</th>
                <th>Check Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deductions as $deduction)
            <tr>
                <td>{{ $deduction->loan_number }}</td>
                <td>{{ $deduction->check_number }}</td>
                <td>{{ $deduction->first_name }} {{ $deduction->middle_name }} {{ $deduction->last_name }}</td>
                <td>{{ $deduction->national_id }}</td>
                <td>{{ $deduction->vote_code }}</td>
                <td>{{ $deduction->vote_name }}</td>
                <td>{{ $deduction->department_code }} - {{ $deduction->department_name }}</td>
                <td>{{ $deduction->deduction_code }} - {{ $deduction->deduction_description }}</td>
                <td>{{ number_format($deduction->balance_amount, 2) }}</td>
                <td>{{ number_format($deduction->deduction_amount, 2) }}</td>
                <td>{{ $deduction->has_stop_pay ? 'Yes' : 'No' }}</td>
                <td>{{ $deduction->stop_pay_reason ?? '-' }}</td>
                <td>{{ $deduction->check_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $deductions->links() }}
    </div>
    @else
    <p>No monthly deductions found.</p>
    @endif
</div>
@endsection
