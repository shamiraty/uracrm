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

                            @if(auth()->user()->hasRole('registrar_hq'))
                                <li><hr class="dropdown-divider"></li>
                                @if(in_array($enquiry->status, ['pending', 'pending_overdue']))
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</a></li>
                                @elseif(in_array($enquiry->status, ['assigned', 'pending_overdue']))
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reassignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-switch me-2"></i>Reassign</a></li>
                                @endif
                            @endif

                            @if($enquiry->registered_by == auth()->user()->id)
                                @if($enquiry->status == 'pending')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                @endif

                                @if(in_array($enquiry->status, ['pending', 'rejected']) && $enquiry->status != 'assigned')
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-item">
                                        <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </li>
                                @endif
                            @endif
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


<!-- Enhanced Status Timeline -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-light">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history me-2"></i>
                    <h6 class="mb-0">Enquiry Timeline & Status History</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    <!-- Registration Step -->
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold text-success">Enquiry Registered</h6>
                                <small class="text-muted">{{ $enquiry->created_at->format('M d, Y - H:i') }}</small>
                            </div>
                            <div class="timeline-body">
                                <p class="mb-1">Enquiry submitted by <strong>{{ $enquiry->registeredBy->name ?? 'System' }}</strong></p>
                                <small class="text-muted">Type: {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</small>
                            </div>
                        </div>
                    </div>

                    @php
                        $now = now();
                        $isOverdue = $enquiry->status == 'pending' && $enquiry->created_at->diffInWeekdays($now) >= 3;
                        $hasAssignment = $enquiry->users->count() > 0;
                    @endphp

                    <!-- Assignment Step -->
                    <div class="timeline-item {{ $hasAssignment ? 'active' : 'pending' }}">
                        <div class="timeline-marker {{ $hasAssignment ? 'bg-info' : 'bg-secondary' }}">
                            <i class="fas fa-user-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold {{ $hasAssignment ? 'text-info' : 'text-muted' }}">
                                    @if($hasAssignment)
                                        Assigned to User
                                    @else
                                        Awaiting Assignment
                                    @endif
                                </h6>
                                @if($hasAssignment)
                                    <small class="text-muted">{{ $enquiry->updated_at->format('M d, Y - H:i') }}</small>
                                @endif
                            </div>
                            @if($hasAssignment)
                                <div class="timeline-body">
                                    <p class="mb-1">Assigned to <strong>{{ $enquiry->users->first()->name }}</strong></p>
                                    <small class="text-muted">Role: {{ $enquiry->users->first()->getRoleNames()->first() }}</small>
                                </div>
                            @else
                                <div class="timeline-body">
                                    <p class="mb-1 text-muted">Waiting for assignment to appropriate officer</p>
                                    @if($isOverdue)
                                        <span class="badge bg-danger">Overdue ({{ $enquiry->created_at->diffInWeekdays($now) }} days)</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Processing Step -->
                    <div class="timeline-item {{ in_array($enquiry->status, ['approved', 'rejected', 'processed']) ? 'active' : 'pending' }}">
                        <div class="timeline-marker {{ $enquiry->status == 'approved' ? 'bg-success' : ($enquiry->status == 'rejected' ? 'bg-danger' : ($enquiry->status == 'processed' ? 'bg-primary' : 'bg-secondary')) }}">
                            @if($enquiry->status == 'approved')
                                <i class="fas fa-check text-white"></i>
                            @elseif($enquiry->status == 'rejected')
                                <i class="fas fa-times text-white"></i>
                            @elseif($enquiry->status == 'processed')
                                <i class="fas fa-cog text-white"></i>
                            @else
                                <i class="fas fa-hourglass-half text-white"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold
                                    {{ $enquiry->status == 'approved' ? 'text-success' : '' }}
                                    {{ $enquiry->status == 'rejected' ? 'text-danger' : '' }}
                                    {{ $enquiry->status == 'processed' ? 'text-primary' : '' }}
                                    {{ !in_array($enquiry->status, ['approved', 'rejected', 'processed']) ? 'text-muted' : '' }}">
                                    @switch($enquiry->status)
                                        @case('approved')
                                            Enquiry Approved
                                            @break
                                        @case('rejected')
                                            Enquiry Rejected
                                            @break
                                        @case('processed')
                                            Enquiry Processed
                                            @break
                                        @default
                                            Under Review
                                    @endswitch
                                </h6>
                                @if(in_array($enquiry->status, ['approved', 'rejected', 'processed']))
                                    <small class="text-muted">{{ $enquiry->updated_at->format('M d, Y - H:i') }}</small>
                                @endif
                            </div>
                            <div class="timeline-body">
                                @if($enquiry->status == 'approved')
                                    <p class="mb-1 text-success">Enquiry has been approved and is ready for processing</p>
                                @elseif($enquiry->status == 'rejected')
                                    <p class="mb-1 text-danger">Enquiry was rejected during review</p>
                                @elseif($enquiry->status == 'processed')
                                    <p class="mb-1 text-primary">Enquiry has been fully processed</p>
                                @else
                                    <p class="mb-1 text-muted">Enquiry is being reviewed by assigned officer</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Completion Step -->
                    @if($enquiry->status == 'processed' || ($enquiry->type == 'loan_application' && $enquiry->status == 'approved'))
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-flag-checkered text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold text-success">Process Completed</h6>
                                    <small class="text-muted">{{ $enquiry->updated_at->format('M d, Y - H:i') }}</small>
                                </div>
                                <div class="timeline-body">
                                    <p class="mb-1">Enquiry has been successfully completed</p>
                                    <small class="text-muted">All required actions have been taken</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="timeline-item pending">
                            <div class="timeline-marker bg-light border">
                                <i class="fas fa-flag-checkered text-muted"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold text-muted">Awaiting Completion</h6>
                                </div>
                                <div class="timeline-body">
                                    <p class="mb-1 text-muted">Process will be completed after review</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Current Status Summary -->
                <div class="mt-4 p-3 rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0 fw-bold">Current Status</h6>
                            <small class="text-muted">Last updated: {{ $enquiry->updated_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <span class="badge {{
                                $enquiry->status == 'approved' ? 'bg-success' :
                                ($enquiry->status == 'rejected' ? 'bg-danger' :
                                ($enquiry->status == 'assigned' ? 'bg-info' :
                                ($enquiry->status == 'processed' ? 'bg-primary' :
                                ($isOverdue ? 'bg-danger' : 'bg-warning text-dark')))) }} fs-6 px-3 py-2">
                                @if($isOverdue)
                                    Overdue ({{ $enquiry->created_at->diffInWeekdays($now) }} days)
                                @else
                                    {{ ucwords($enquiry->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #17a2b8, #28a745);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
}

.timeline-content {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    margin-left: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateX(5px);
}

.timeline-item.active .timeline-content {
    border-left: 4px solid #007bff;
}

.timeline-item.pending .timeline-content {
    background-color: #f8f9fa;
    border-style: dashed;
}

.timeline-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 8px;
}

.timeline-header h6 {
    flex: 1;
}

.timeline-body p {
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Responsive timeline */
@media (max-width: 768px) {
    .timeline {
        padding-left: 20px;
    }

    .timeline-marker {
        left: -15px;
        width: 24px;
        height: 24px;
    }

    .timeline-content {
        margin-left: 15px;
    }
}
</style>

@include('modals.assign_enquries')
@if($enquiry->users->count() > 0)
    @include('modals.reassign_enquiry')
@endif
@endsection
