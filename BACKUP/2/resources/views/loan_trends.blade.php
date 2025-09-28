@extends('layouts.app')

@section('content')

<style>
    /* Custom styling for tables */
    #example2 th, #example2 td {
        border: 1px solid #dee2e6; /* Light border for table cells */
        padding: 12px; /* Added padding for better readability */
        text-align: left; /* Align text to the left */
    }

    .grand-total {
        font-weight: bold; /* Make grand total bold */
        background-color: #f8f9fa; /* Light background for grand total */
    }

    .table-header {
        background-color: gray; /* Primary color for the header */
        color: white; /* White text for contrast */
    }

    .alert {
        margin-bottom: 20px; /* Space between alerts */
    }
</style>

<div class="container-fluid">
    <!-- Header message indicating trends are for the current date -->
    @if (isset($startDate) && isset($endDate))
        <div class="alert alert-success text-center">
            <strong>Showing trends from {{ \Carbon\Carbon::parse($startDate)->format('l, F j, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('l, F j, Y') }}</strong>
        </div>
    @else
        <div class="alert alert-info text-center">
            <strong>Trends are for today: {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</strong>
        </div>
    @endif

    <!-- Date Range Form -->
    <form action="{{ route('loan_trends') }}" method="GET" class="mb-4 p-4 border rounded shadow-sm bg-light">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" 
                       value="{{ request('start_date', today()->toDateString()) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" 
                       value="{{ request('end_date', today()->toDateString()) }}">
            </div>
            <div class="col-md-4 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 btn-sm btn-block">Filter</button>
            </div>
        </div>
    </form>

    @if ($statusMetrics->isEmpty())
        <div class="alert alert-warning">No data available for the selected date range.</div>
    @else
        <!-- Table for Detailed Status Metrics -->
        <table class="table table-bordered table-hover" id="example2">
            <thead class="table-header">
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Loan Amount</th>
                    <th>Total Interest Rate</th>
                    <th>Total Monthly Deduction</th>
                    <th>Total Loan with Interest</th>
                    <th>Total Interest</th>
                    <th>Total Processing Fee</th>
                    <th>Total Insurance</th>
                    <th>Total Disbursement Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statusMetrics as $metric)
                    <tr>
                        <td>{{ $metric->status }}</td>
                        <td>{{ $metric->count }}</td>
                        <td>{{ number_format($metric->total_loan_amount, 2) }}</td>
                        <td>{{ number_format($metric->total_interest_rate, 2) }}</td>
                        <td>{{ number_format($metric->total_monthly_deduction, 2) }}</td>
                        <td>{{ number_format($metric->total_loan_with_interest, 2) }}</td>
                        <td>{{ number_format($metric->total_interest, 2) }}</td>
                        <td>{{ number_format($metric->total_processing_fee, 2) }}</td>
                        <td>{{ number_format($metric->total_insurance, 2) }}</td>
                        <td>{{ number_format($metric->total_disbursement_amount, 2) }}</td>
                    </tr>
                @endforeach

                <!-- Grand Total Row -->
                <tr class="grand-total">
                    <th colspan="2">Grand Total</th>
                    <td>{{ number_format($statusMetrics->sum('total_loan_amount'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_interest_rate'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_monthly_deduction'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_loan_with_interest'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_interest'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_processing_fee'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_insurance'), 2) }}</td>
                    <td>{{ number_format($statusMetrics->sum('total_disbursement_amount'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>

@endsection
