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
    <!-- Applicant Details Card (Top Left) -->
    <div class="col-md-6 grid-margin stretch-card mt-2">
        <div class="card shadow-sm border-light">
            <div class="card-header text-primary">
                <div class="d-flex align-items-center">
                    <h6 class="mb-0 text-primary">Applicant Details</h6>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('enquiries.index') }}"><i class="mdi mdi-eye me-2"></i>Back to List</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="dropdown-item">
                                <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <ul class="list-group">
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
                        <span class="fw-bold">Region:</span>
                        <span class="text-muted">{{ $enquiry->region->name ?? 'No Region' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">District:</span>
                        <span class="text-muted">{{ $enquiry->district->name ?? 'No District' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Phone:</span>
                        <span class="text-muted">{{ $enquiry->phone }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Enquiry File Card (Top Right) -->
    <div class="col-md-6 grid-margin stretch-card mt-4">
        <div class="card shadow-sm border-light">
            <h6 class="card-header  text-primary mb-4">Enquiry File</h6>
            <div class="card-body">
                @if ($enquiry->folios->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($enquiry->folios as $folio)
                            <div>
                                @if (strtolower(pathinfo($folio->file_path, PATHINFO_EXTENSION)) === 'pdf')
                                    <!-- Embedded PDF preview with iframe -->
                                    <div class="pdf-container mb-3">
                                        <iframe src="{{ asset($folio->file_path) }}" type="application/pdf" width="100%" height="450">
                                            Your browser does not support PDFs. 
                                            <a href="{{ asset('/' . $folio->file_path) }}" target="_blank">Download PDF</a>
                                        </iframe>
                                    </div>
                                @else
                                    <!-- Fallback for other file types -->
                                    <a href="{{ asset('/' . $folio->file_path) }}" target="_blank">{{ basename($folio->file_path) }}</a>
                                @endif
                            </div>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No folios attached.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Registrar Details Card (Bottom Left) -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-light">
            <h6 class="card-header text-primary">Registrar Details</h6>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">User's District:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->district->name ?? 'No District' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">User's Region:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->region->name ?? 'No Region' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">User:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->name ?? 'No User' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Command:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->command->name ?? 'No Command' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Phone Number:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->phone_number ?? 'No Phone Number' }}</span>
                    </li>
                    {{--
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Role:</span>
                        <span class="text-muted">{{ $enquiry->registeredBy->role->role ?? 'No Role' }}</span>
                    </li>
                    --}}
                </ul>
            </div>
        </div>
    </div>
    </div>
 


<!-- Assigned Users Card -->
<div class="row  mt-4">
<div class="col-md-6">
        <div class="card shadow-sm border-light">
            <h6 class="card-header text-primary">Assigned User Details</h6>
            <div class="card-body">
                @if ($enquiry->assignedUsers->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($enquiry->assignedUsers as $assignedUser)
                            <div>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Name:</span>
                                    <span class="text-muted">{{ $assignedUser->name }}</span>
                                </li>
                                {{--
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Role:</span>
                                    <span class="text-muted">{{ $assignedUser->role->role ?? 'No Role' }}</span>
                                </li>
                                --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Phone Number:</span>
                                    <span class="text-muted">{{ $assignedUser->phone_number ?? 'No Phone Number' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">District:</span>
                                    <span class="text-muted">{{ $assignedUser->district->name ?? 'No District' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Region:</span>
                                    <span class="text-muted">{{ $assignedUser->region->name ?? 'No Region' }}</span>
                                </li>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-warning">No assigned users for this enquiry.</div>
                @endif
            </div>
        </div>
    </div>
</div>


@include('modals.assign_enquries')
@endsection
