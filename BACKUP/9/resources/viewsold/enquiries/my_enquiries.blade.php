


@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1>My Enquiries</h1>

    <!-- Include Loan Officer View -->
    @if(auth()->user()->hasRole('loanofficer'))
        <div class="loan-officer-section">
            <h2>Loan Applications</h2>
            @include('loans.loan_applications')  {{-- Including the Loan Applications view --}}
        </div>
    @endif

    <!-- Include Accountant View -->
    @if(auth()->user()->hasRole('accountant'))
        <div class="accountant-section">
            <h2>Accountant Actions</h2>
            @include('payments.accountant_actions')  {{-- Including the Accountant Actions view --}}
        </div>
    @endif
</div>
@endsection
