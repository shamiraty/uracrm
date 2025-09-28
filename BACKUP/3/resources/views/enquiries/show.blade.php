@extends('layouts.app')

@section('title', 'View Enquiry')

@section('content')
<style>
    /* Enhanced Modern Styling */
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: box-shadow 0.3s ease;
        border: none;
    }

    .card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);
        color: white;
        font-weight: 600;
        border-radius: 12px 12px 0 0 !important;
        border: none;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f1f3f4;
        padding: 12px 16px;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .timeline-marker {
        background: linear-gradient(135deg, #87CEEB, #17479e) !important;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Breadcrumb styling */
    .breadcrumb-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>

<!-- Enhanced Breadcrumb Navigation -->
<div class="card border-0 shadow-sm mb-4 breadcrumb-card">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-eye text-primary fs-5"></i>
                <h5 class="fw-bold mb-0 text-dark">View Enquiry Details</h5>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('enquiries.index') }}" class="text-decoration-none">All Enquiries</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="badge" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%); color: white;">
                            #{{ $enquiry->check_number }}
                        </span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="mb-2 mb-md-0">
                        <h1 class="h3 mb-1 fw-bold text-white">
                            <i class="fas fa-clipboard-check me-3"></i>{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }} Details
                        </h1>
                        <p class="mb-0 text-white opacity-75">Complete enquiry information and processing status</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('enquiries.index') }}" class="btn btn-light btn-lg fw-semibold shadow">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        @if($enquiry->registered_by == auth()->user()->id && $enquiry->status == 'pending')
                            <a href="{{ route('enquiries.edit', $enquiry->id) }}" class="btn btn-warning btn-lg fw-semibold shadow">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Applicant Details Card (Top Left) -->
    <div class="col-md-6 grid-margin stretch-card mt-2">
        <div class="card shadow-lg border-0">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h6 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Applicant Details</h6>
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
        <div class="card shadow-lg border-0">
            <h6 class="card-header text-white mb-0"><i class="fas fa-folder-open me-2"></i>Enquiry File</h6>
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
        <div class="card shadow-lg border-0">
            <h6 class="card-header text-white"><i class="fas fa-user-tie me-2"></i>Registrar Details</h6>
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
<div class="row mt-4">
<div class="col-md-6">
        <div class="card shadow-lg border-0">
            <h6 class="card-header text-white"><i class="fas fa-user-check me-2"></i>Assigned User Details</h6>
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


<!-- Enquiry Type Specific Information -->
@if($enquiry->childData)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <h6 class="mb-0 text-white">{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }} Specific Details</h6>
                </div>
            </div>
            <div class="card-body">
                @switch($enquiry->type)
                    @case('loan_application')
                        @if($enquiry->loanApplication)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-money-bill-wave text-success fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Loan Amount</span>
                                            </div>
                                            <h5 class="mb-0 text-success fw-bold">{{ number_format($enquiry->loanApplication->loan_amount ?? 0, 0) }} TZS</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar text-info fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Loan Duration</span>
                                            </div>
                                            <h5 class="mb-0 text-info fw-bold">{{ $enquiry->loanApplication->loan_duration ?? 'N/A' }} months</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-percentage text-warning fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Interest Rate</span>
                                            </div>
                                            <h5 class="mb-0 text-dark fw-bold">{{ $enquiry->loanApplication->interest_rate ?? 'N/A' }}%</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calculator text-primary fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Monthly Deduction</span>
                                            </div>
                                            <h5 class="mb-0 text-primary fw-bold">{{ number_format($enquiry->loanApplication->monthly_deduction ?? 0, 0) }} TZS</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-tag text-info fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Loan Type</span>
                                            </div>
                                            <h5 class="mb-0 text-info fw-bold">{{ ucfirst($enquiry->loanApplication->loan_type ?? 'N/A') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('refund')
                        @if($enquiry->refund)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-money-check text-success fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Refund Amount</span>
                                            </div>
                                            <h5 class="mb-0 text-success fw-bold">{{ number_format($enquiry->refund->refund_amount ?? 0, 0) }} TZS</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar text-info fa-lg me-2"></i>
                                                <span class="fw-bold text-muted">Refund Duration</span>
                                            </div>
                                            <h5 class="mb-0 text-info fw-bold">{{ $enquiry->refund->refund_duration ?? 'N/A' }} months</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('retirement')
                        @if($enquiry->retirement)
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-calendar-check me-2 text-info"></i>Date of Retirement:</span>
                                        <span class="text-info">{{ $enquiry->retirement->date_of_retirement ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('condolences')
                        @if($enquiry->condolence)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-venus-mars me-2 text-info"></i>Gender:</span>
                                        <span class="text-info">{{ ucfirst($enquiry->condolence->gender ?? 'N/A') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-heart me-2 text-danger"></i>Dependent Member Type:</span>
                                        <span class="text-danger">{{ ucfirst($enquiry->condolence->dependent_member_type ?? 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('deduction_add')
                        @if($enquiry->deduction)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-arrow-down me-2 text-success"></i>From Amount:</span>
                                        <span class="text-success fw-bold">{{ number_format($enquiry->deduction->from_amount ?? 0, 0) }} TZS</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-arrow-up me-2 text-danger"></i>To Amount:</span>
                                        <span class="text-danger fw-bold">{{ number_format($enquiry->deduction->to_amount ?? 0, 0) }} TZS</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('injured_at_work')
                        @if($enquiry->injury)
                            <div class="row g-3">
                                @if($enquiry->injury->description)
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <span class="fw-bold d-block mb-2"><i class="fas fa-file-medical me-2 text-primary"></i>Injury Description:</span>
                                        <span class="text-dark">{{ $enquiry->injury->description }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                        @break

                    @case('share_enquiry')
                        @if($enquiry->share)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-chart-line me-2 text-success"></i>Share Amount:</span>
                                        <span class="text-success fw-bold">{{ number_format($enquiry->share->amount ?? 0, 0) }} TZS</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-percentage me-2 text-info"></i>Share Type:</span>
                                        <span class="text-info">{{ ucfirst($enquiry->share->type ?? 'N/A') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-calendar me-2 text-warning"></i>Request Date:</span>
                                        <span class="text-warning">{{ $enquiry->share->request_date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('withdraw_savings')
                    @case('withdraw_deposit')
                        @if($enquiry->withdrawal)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-money-bill-alt me-2 text-success"></i>Withdrawal Amount:</span>
                                        <span class="text-success fw-bold">{{ number_format($enquiry->withdrawal->amount ?? 0, 0) }} TZS</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-university me-2 text-info"></i>Withdrawal Type:</span>
                                        <span class="text-info">{{ ucfirst($enquiry->withdrawal->type ?? str_replace('_', ' ', $enquiry->type)) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-calendar me-2 text-warning"></i>Request Date:</span>
                                        <span class="text-warning">{{ $enquiry->withdrawal->request_date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('join_membership')
                    @case('unjoin_membership')
                        @if($enquiry->membershipChange)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-tag me-2 text-info"></i>Category:</span>
                                        <span class="text-info">{{ ucfirst($enquiry->membershipChange->category ?? 'N/A') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-exchange-alt me-2 text-primary"></i>Action:</span>
                                        <span class="text-primary">{{ ucfirst($enquiry->membershipChange->action ?? 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('sick_for_30_days')
                        @if($enquiry->sickLeave)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-calendar-plus me-2 text-warning"></i>Start Date:</span>
                                        <span class="text-warning">{{ $enquiry->sickLeave->start_date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-calendar-minus me-2 text-danger"></i>End Date:</span>
                                        <span class="text-danger">{{ $enquiry->sickLeave->end_date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-clock me-2 text-info"></i>Total Days:</span>
                                        <span class="text-info fw-bold">{{ $enquiry->sickLeave->total_days ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('ura_mobile')
                        @if($enquiry->uraMobile)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-mobile-alt me-2 text-primary"></i>Service Type:</span>
                                        <span class="text-primary">{{ $enquiry->uraMobile->service_type ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <span class="fw-bold"><i class="fas fa-phone me-2 text-info"></i>Phone Number:</span>
                                        <span class="text-info">{{ $enquiry->uraMobile->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @break

                    @default
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No specific details available for this enquiry type</h5>
                            <p class="text-muted">General enquiry information is displayed in the sections above.</p>
                        </div>
                @endswitch
            </div>
        </div>
    </div>
</div>
@endif

<!-- Enhanced Status Timeline -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history me-2"></i>
                    <h6 class="mb-0 text-white">Enquiry Timeline & Status History</h6>
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
