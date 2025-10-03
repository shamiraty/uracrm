

@extends('layouts.app')

@section('content')
{{-- <style>
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
<div class="container-fluid">

    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Payments of</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">

                    <li class="breadcrumb-item active" aria-current="page">
                          {{ $type }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">

        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 radius-30" placeholder="Search Enquiry">
                    <span class="position-absolute top-50 translate-middle-y" style="left: 20px;"><i class="bx bx-search"></i></span>
                </div>
                <div class="ms-auto">

                </div>
            </div>

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered"> --}}

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                        <h6 class="fw-semibold mb-0">Payments</h6>
                        <ul class="d-flex align-items-center gap-2">
                          <li class="fw-medium">
                            <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                              all Payments

                            </a>
                          </li>
                          <li>-</li>
                          <li class="fw-medium">Payments</li>
                        </ul>
                      </div>

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

                        <div class="card basic-data-table">
                            <div class="card-header">
                                <h5 class="card-title mb-0"> <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                    <i class="bx bxs-plus-square"></i> {{ $type }}
                                </a></h5>
                          </div>
                          <div class="card-body">
                            <div class="table-responsive">
                            <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
            <tr>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
                @if ($columnsToShow['initiated'])<th>Initiated By</th>@endif
                @if ($columnsToShow['approved'])<th>Approved By</th>@endif
                @if ($columnsToShow['rejected'])<th>Rejected By</th>@endif

                @if ($columnsToShow['paid'])<th>Paid By</th>@endif
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->status }}</td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    @if ($columnsToShow['initiated'])<td>{{ optional($payment->initiatedBy)->name }}</td>@endif
                    @if ($columnsToShow['approved'])<td>{{ optional($payment->approvedBy)->name }}</td>@endif
                    @if ($columnsToShow['rejected'])<td>{{ optional($payment->rejectedBy)->name }}</td>@endif

                    @if ($columnsToShow['paid'])<td>{{ optional($payment->paidBy)->name }}</td>@endif
                    <td>
                        <a href="{{ route('payments.timeline', ['paymentId' => $payment->id]) }}" class="btn btn-outline-primary px-5 radius-30 btn-sm"><i class='fa fa-eye mr-1'></i>View Timeline</a>

                </tr>
            @endforeach
        </tbody>
    </table></div></div>
</div>
@endsection
