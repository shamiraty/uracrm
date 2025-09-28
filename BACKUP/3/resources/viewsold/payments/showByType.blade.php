

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
<div class="container-fluid">

    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Payments of</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    {{-- <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li> --}}
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
                <table id="example" class="table table-striped table-bordered">
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
    </table>
</div>
@endsection
