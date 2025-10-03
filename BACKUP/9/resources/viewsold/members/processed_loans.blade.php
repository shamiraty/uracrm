<!-- resources/views/members/processed_loans.blade.php -->
@extends('layouts.app')

@section('content')
<style>
    #example th, #example td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
       /* Add space between the export buttons and the table */
       .dt-buttons {
        margin-bottom: 15px; /* Adjust this value as needed */
    }
</style>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Processed Loans</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
            </nav>
        </div>
        </div>
        <div class="card">
        <div class="card-body">
        <div class="table-responsive">
<table id="example" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Check Number</th>
            <th>Full Name</th>
            <th>Account Number</th>
            <th>Bank Name</th>
            <th>Basic Salary</th>
            <th>Loanable Amount</th>
            <th>Total Loan With Interest</th>
            <th>Total Interest</th>
            <th>Monthly Deduction</th>
            <th>Processing Fee</th>
            <th>Insurance</th>
            <th>Disbursement Amount</th>
            <th>Status</th>
            <th>Action</th> <!-- Added for navigation link -->
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $member->checkNumber }}</td>
                <td>{{ $member->fullName }}</td>
                <td>{{ $member->accountNumber }}</td>
                <td>{{ $member->bankName }}</td>
                <td>{{ number_format($member->basicSalary, 2) }}</td>
                <td>{{ number_format($member->loanableAmount, 2) }}</td>
                <td>{{ number_format($member->totalLoanWithInterest, 2) }}</td>
                <td>{{ number_format($member->totalInterest, 2) }}</td>
                <td>{{ number_format($member->monthlyDeduction, 2) }}</td>
                <td>{{ number_format($member->processingFee, 2) }}</td>
                <td>{{ number_format($member->insurance, 2) }}</td>
                <td>{{ number_format($member->disbursementAmount, 2) }}</td>
                <td>{{ $member->status }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('members.details', ['member' => $member->id]) }}" class="btn btn-info btn-sm" title="View Details">
                            <i class="bx bx-show"></i>
                        </a>

                        <form action="{{ route('members.updateStatus', ['member' => $member->id, 'status' => 'processed']) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Process">
                                <i class="bx bx-check"></i>
                            </button>
                        </form>
                

                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection


