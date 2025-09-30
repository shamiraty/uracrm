
@extends('layouts.app')

@section('title', 'View Enquiry')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
        <li class="breadcrumb-item active" aria-current="page">View Enquiry</li>
    </ol>
</nav>

<div class="row">
    <!-- Enquiry Details Column -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm border-light">
        <h5 class="card-header text-primary ">View Enquiry Details</h5>
            <div class="card-body">

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Date Received:</span>
                        <span class="text-muted">{{ $enquiry->date_received }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Force Number:</span>
                        <span class="text-muted">{{ $enquiry->force_no }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Account Number:</span>
                        <span class="text-muted">{{ $enquiry->account_number }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Name of the Bank:</span>
                        <span class="text-muted">{{ $enquiry->bank_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Check Number:</span>
                        <span class="text-muted">{{ $enquiry->check_number }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Full Name:</span>
                        <span class="text-muted">{{ $enquiry->full_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Enquiry Type:</span>
                        <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">District:</span>
                        <span class="text-muted">{{ $enquiry->district }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Phone:</span>
                        <span class="text-muted">{{ $enquiry->phone }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Region:</span>
                        <span class="text-muted">{{ $enquiry->region }}</span>
                    </li>
                </ul>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="{{ route('enquiries.edit', $enquiry->id) }}" class="btn btn-warning me-2 btn-sm">Edit</a>
                    <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <a href="{{ route('enquiries.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <!-- File Display Column -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm border-light">
        <h5 class="card-header  text-primary mb-4">Enquiry File</h5>
            <div class="card-body">
                @if ($enquiry->file_path)
                    <object data="{{ asset($enquiry->file_path) }}" type="application/pdf" width="100%" height="600px" class="border rounded">
                        <p class="text-muted">Your browser does not support PDFs.
                            <a href="{{ asset($enquiry->file_path) }}" class="text-decoration-underline">Download the PDF</a>.
                        </p>
                    </object>
                @else
                    <p class="text-muted">No file uploaded.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
