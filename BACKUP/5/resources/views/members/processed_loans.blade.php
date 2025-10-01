<!-- resources/views/members/processed_loans.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Loan Application</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          all Loan applications

        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Loan applications</li>
    </ul>
  </div>
{{--
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
--}}
    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0"> <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                <i class="bx bxs-plus-square"></i> loan applications
            </a></h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
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
                <td  class="text-primary-600">{{ $member->checkNumber }}</td>
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
                        <a href="{{ route('members.details', ['member' => $member->id]) }}" class="btn btn-primary btn-sm" title="View Details">
                            Details
                        </a>

                        <form action="{{ route('members.updateStatus', ['member' => $member->id, 'status' => 'processed']) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Process">
                                Process
                            </button>
                        </form>


                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table></div></div></div>
{{--
<script>
        document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
</script>
--}}
@endsection


