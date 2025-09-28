


@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Include Loan Officer View -->
    @if(auth()->user()->hasRole('loanofficer'))
        <div class="loan-officer-section">
            <h2>Loan Applications</h2>
            @include('loans.loan_applications')  {{-- Including the Loan Applications view --}}
        </div>
    @endif
   <!-- Include Superadmin View -->
   @if(auth()->user()->hasRole('superadmin'))
        <div class="superadmin-section">
            <h2>Superadmin Actions</h2>
            @include('uramobile.admin_actions')  {{-- Including theadmin Actions view --}}
        </div>
   @endif

    <!-- Include Accountant View -->
    @if(auth()->user()->hasRole('accountant'))
        <div class="accountant-section">
            <!-- Redirect to dedicated accountant dashboard -->
            <script>window.location.href = "{{ route('payment.accountant.dashboard') }}";</script>
        </div>
    @endif

    <!-- Include Manager View -->
    @if(auth()->user()->hasRole('general_manager'))
        <div class="manager-section">
            <!-- Redirect to dedicated manager dashboard -->
            <script>window.location.href = "{{ route('payment.manager.dashboard') }}";</script>
        </div>
    @endif

    @if(!auth()->user()->hasAnyRole(['loanofficer', 'superadmin', 'accountant', 'general_manager']))
    <!-- No Assigned Role Warning -->
    <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold radius-4 d-flex align-items-center justify-content-between" role="alert">
        <div class="d-flex align-items-center gap-2">
            <span>No Assigned Role - Please contact the administrator for role assignment.</span>
        </div>
        <button class="remove-button text-danger-600 text-xxl line-height-1">
            <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
        </button>
    </div>
@endif
     
</div>

@endsection
