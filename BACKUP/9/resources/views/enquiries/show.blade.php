@extends('layouts.app')

@section('title', 'View Enquiry')

@section('content')
<style>
    /* Enhanced Modern Styling */
    .card {
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: box-shadow 0.3s ease;
        border: none;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #5a9fd4 0%, #2c5aa0 100%);
        color: white;
        font-weight: 600;
        border-radius: 12px 12px 0 0 !important;
        border: none;
        padding: 1rem 1.25rem;
    }

    .info-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
        margin-bottom: 0.5rem;
        border-radius: 6px;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .list-group-item {
        border: 1px solid #e9ecef;
    }

    .personnel-info .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .personnel-info .info-row:last-child {
        border-bottom: none;
    }

    .personnel-info .info-row span {
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .breadcrumb-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .pdf-preview iframe {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        background: white;
    }

    .pdf-preview-container {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid #dee2e6;
    }

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
        justify-content: space-between;
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
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg,  #17479e 0%, #87CEEB 100%);">
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

<!-- Main Content Grid -->
<div class="row g-4">
    <!-- Applicant Details -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white"><i class="fas fa-user me-2"></i>APPLICANT INFORMATION</h6>
                <div class="d-flex gap-2">
                    @if(auth()->user()->hasRole('registrar_hq'))
                        @if(in_array($enquiry->status, ['pending', 'pending_overdue']))
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}">
                                <i class="mdi mdi-account-arrow-right me-1"></i>Assign
                            </button>
                        @elseif(in_array($enquiry->status, ['assigned', 'pending_overdue']))
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#reassignUserModal-{{ $enquiry->id }}">
                                <i class="mdi mdi-account-switch me-1"></i>Reassign
                            </button>
                        @endif
                    @endif
                    @if($enquiry->registered_by == auth()->user()->id && $enquiry->status == 'pending')
                        <a href="{{ route('enquiries.edit', $enquiry->id) }}" class="btn btn-light btn-sm">
                            <i class="mdi mdi-pencil me-1"></i>Edit
                        </a>
                    @endif
                    @if($enquiry->registered_by == auth()->user()->id && in_array($enquiry->status, ['pending', 'rejected']) && $enquiry->status != 'assigned')
                        <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm" onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Full Name</span>
                                <strong>{{ $enquiry->full_name }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Force Number</span>
                                <strong>{{ $enquiry->force_no }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Phone</span>
                                <strong>{{ $enquiry->phone }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Date Received</span>
                                <strong>{{ $enquiry->date_received }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Check Number</span>
                                <strong>{{ $enquiry->check_number }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Enquiry Type</span>
                                <strong>{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Account Number</span>
                                <strong>{{ $enquiry->account_number }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Bank Name</span>
                                <strong>{{ $enquiry->bank_name }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Region</span>
                                <strong>{{ $enquiry->region->name ?? 'No Region' }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">District</span>
                                <strong>{{ $enquiry->district->name ?? 'No District' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-secondary mb-3 fw-semibold border-bottom pb-2">Attached Documents</h6>
                        @if ($enquiry->folios->isNotEmpty())
                            <div class="d-grid gap-2">
                                @foreach ($enquiry->folios as $index => $folio)
                                    @if (strtolower(pathinfo($folio->file_path, PATHINFO_EXTENSION)) === 'pdf')
                                        <div class="border rounded p-2 text-center">
                                            <i class="fas fa-file-pdf text-danger fa-2x mb-2"></i>
                                            <p class="small mb-2 text-truncate">{{ basename($folio->file_path) }}</p>
                                            <button class="btn btn-primary btn-sm w-100 mb-1" data-bs-toggle="modal" data-bs-target="#pdfModal{{ $index }}">
                                                <i class="fas fa-eye me-1"></i>View
                                            </button>
                                            <a href="{{ asset($folio->file_path) }}" class="btn btn-success btn-sm w-100" download>
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="border rounded p-2 text-center">
                                            <i class="fas fa-file text-primary fa-2x mb-2"></i>
                                            <p class="small mb-2 text-truncate">{{ basename($folio->file_path) }}</p>
                                            <a href="{{ asset('/' . $folio->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-file-circle-xmark fa-2x text-muted mb-2"></i>
                                <p class="text-muted small mb-0">No documents</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personnel Information Section -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white"><i class="fas fa-users me-2"></i>REGISTRAR & ASSIGNED USERS</h6>
            </div>
            <div class="card-body">
                <!-- Registrar Details -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3 fw-semibold border-bottom pb-2">REGISTRAR</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Name</span>
                            <strong>{{ $enquiry->registeredBy->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Command</span>
                            <strong>{{ $enquiry->registeredBy->command->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Phone</span>
                            <strong>{{ $enquiry->registeredBy->phone_number ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Region</span>
                            <strong>{{ $enquiry->registeredBy->region->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">District</span>
                            <strong>{{ $enquiry->registeredBy->district->name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Assigned User Details -->
                <div>
                    <h6 class="text-primary mb-3 fw-semibold border-bottom pb-2">ASSIGNED TO</h6>
                    @if ($enquiry->assignedUsers->isNotEmpty())
                        @foreach ($enquiry->assignedUsers as $assignedUser)
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-muted">Name</span>
                                    <strong>{{ $assignedUser->name }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-muted">Phone</span>
                                    <strong>{{ $assignedUser->phone_number ?? 'N/A' }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-muted">Region</span>
                                    <strong>{{ $assignedUser->region->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span class="text-muted">District</span>
                                    <strong>{{ $assignedUser->district->name ?? 'N/A' }}</strong>
                                </div>
                                
                                                             
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info mb-0 border">
                            <i class="fas fa-info-circle me-2"></i>Not yet assigned
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF Viewer Modals -->
@if ($enquiry->folios->isNotEmpty())
    @foreach ($enquiry->folios as $index => $folio)
        @if (strtolower(pathinfo($folio->file_path, PATHINFO_EXTENSION)) === 'pdf')
            <div class="modal fade" id="pdfModal{{ $index }}" tabindex="-1" aria-labelledby="pdfModalLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white" id="pdfModalLabel{{ $index }}">
                                <i class="fas fa-file-pdf me-2 text-white"></i>{{ basename($folio->file_path) }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="{{ asset($folio->file_path) }}" class="w-100" style="height: 80vh; border: none;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ asset($folio->file_path) }}" class="btn btn-success" download>
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                            <a href="{{ asset($folio->file_path) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-external-link-alt me-2"></i>Open in New Tab
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif

@if($enquiry->childData)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i>{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }} Specific Details
                </h6>
            </div>
            <div class="card-body">
                @switch($enquiry->type)
                    @case('loan_application')
                        @if($enquiry->loanApplication)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Loan Amount</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->loanApplication->loan_amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Loan Duration</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->loanApplication->loan_duration ?? 'N/A' }} months</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Interest Rate</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->loanApplication->interest_rate ?? 'N/A' }}%</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Monthly Deduction</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->loanApplication->monthly_deduction ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Loan Type</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->loanApplication->loan_type ?? 'N/A') }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('refund')
                        @if($enquiry->refund)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Refund Amount</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->refund->refund_amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold text-secondary">Refund Duration</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->refund->refund_duration ?? 'N/A' }} months</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('retirement')
                        @if($enquiry->retirement)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-calendar-check me-2 text-secondary"></i>Date of Retirement:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->retirement->date_of_retirement ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('condolences')
                        @if($enquiry->condolence)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-venus-mars me-2 text-secondary"></i>Gender:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->condolence->gender ?? 'N/A') }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-heart me-2 text-secondary"></i>Dependent Member Type:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->condolence->dependent_member_type ?? 'N/A') }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('deduction_add')
                        @if($enquiry->deduction)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-arrow-down me-2 text-secondary"></i>From Amount:</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->deduction->from_amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-arrow-up me-2 text-secondary"></i>To Amount:</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->deduction->to_amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-arrows-alt-h me-2 text-secondary"></i>Change:</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->deduction->changes ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-check-circle me-2 text-secondary"></i>Status:</span>
                                    <span class="text-dark fw-bold">{{$enquiry->deduction->status ?? 'N/A' }} </span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('injured_at_work')
                        @if($enquiry->injury)
                            <div class="list-group">
                                @if($enquiry->injury->description)
                                <div class="list-group-item p-3 bg-light rounded">
                                    <span class="fw-semibold d-block mb-2"><i class="fas fa-file-medical me-2 text-secondary"></i>Injury Description:</span>
                                    <span class="text-dark">{{ $enquiry->injury->description }}</span>
                                </div>
                                @endif
                            </div>
                        @endif
                        @break

                    @case('share_enquiry')
                    @case('withdraw_savings')
                    @case('withdraw_deposit')
                        {{-- Hizi kesi zina data nyingi, List Group Items zinafaa zaidi kutumika bila gridi ili zionekane vizuri wima. Lakini kwa kuwa tayari ulikuwa na gridi, nitaziweka kwenye List Group Items za kiwango kimoja kwa kila kisa cha kujirudia ili kufanya iwe wima. --}}

                        {{-- Kwa case ya 'share_enquiry' --}}
                        @if($enquiry->type == 'share_enquiry' && $enquiry->share)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-chart-line me-2 text-secondary"></i>Share Amount:</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->share->amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-percentage me-2 text-secondary"></i>Share Type:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->share->type ?? 'N/A') }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-calendar me-2 text-secondary"></i>Request Date:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->share->created_at ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- Kwa case ya 'withdraw_savings' au 'withdraw_deposit' (Zote zina data sawa) --}}
                        @if(($enquiry->type == 'withdraw_savings' || $enquiry->type == 'withdraw_deposit') && $enquiry->withdrawal)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-money-bill-alt me-2 text-secondary"></i>Withdrawal Amount:</span>
                                    <span class="text-dark fw-bold">{{ number_format($enquiry->withdrawal->amount ?? 0, 0) }} TZS</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-university me-2 text-secondary"></i>Withdrawal Type:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->withdrawal->type ?? str_replace('_', ' ', $enquiry->type)) }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-calendar me-2 text-secondary"></i>Request Date:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->withdrawal->created_at?? 'N/A' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-comment-dots me-2 text-secondary"></i>Reason:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->withdrawal->reason?? 'N/A' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-clock me-2 text-secondary"></i>Days:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->withdrawal->days?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                        @break {{-- break inafaa hapa baada ya kumaliza 'withdraw' cases zote --}}


                    @case('join_membership')
                    @case('unjoin_membership')
                        @if($enquiry->membershipChange)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-tag me-2 text-secondary"></i>Category:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->membershipChange->category ?? 'N/A') }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-exchange-alt me-2 text-secondary"></i>Action:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->membershipChange->action ?? 'N/A') }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('sick_for_30_days')
                        @if($enquiry->sickLeave)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-calendar-plus me-2 text-secondary"></i>Start Date:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->sickLeave->start_date ?? 'N/A' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-calendar-minus me-2 text-secondary"></i>End Date:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->sickLeave->end_date ?? 'N/A' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-clock me-2 text-secondary"></i>Total Days:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->sickLeave->total_days ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('ura_mobile')
                        @if($enquiry->uraMobile)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-mobile-alt me-2 text-secondary"></i>Service Type:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->uraMobile->service_type ?? 'N/A' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-phone me-2 text-secondary"></i>Phone Number:</span>
                                    <span class="text-dark fw-bold">{{ $enquiry->uraMobile->phone_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                        @break

                    @case('residential_disaster')
                        @if($enquiry->residentialDisaster)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                    <span class="fw-semibold"><i class="fas fa-house-damage me-2 text-secondary"></i>Disaster Type:</span>
                                    <span class="text-dark fw-bold">{{ ucfirst($enquiry->residentialDisaster->disaster_type ?? 'N/A') }}</span>
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
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-history me-2"></i>Enquiry Timeline & Status History
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    <!-- Registration Step -->
                    <div class="timeline-item active">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="fas fa-check-circle me-1"></i>Enquiry Registered
                                </h6>
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
                        <div class="timeline-marker {{ $hasAssignment ? 'bg-success' : 'bg-secondary' }}">
                            <i class="fas {{ $hasAssignment ? 'fa-check' : 'fa-user-check' }} text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold {{ $hasAssignment ? 'text-success' : 'text-muted' }}">
                                    @if($hasAssignment)
                                        <i class="fas fa-check-circle me-1"></i>Assigned to User
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

                    @php
                        // Define enquiry types that require review completion
                        $reviewCompletionTypes = [
                            'refund', 'share_enquiry', 'retirement', 'deduction_add',
                            'withdraw_savings', 'withdraw_deposit', 'unjoin_membership',
                            'ura_mobile', 'sick_for_30_days', 'condolences',
                            'injured_at_work', 'residential_disaster', 'join_membership'
                        ];
                        $isReviewType = in_array($enquiry->type, $reviewCompletionTypes);
                        $isLoanType = $enquiry->type == 'loan_application';
                    @endphp

                    <!-- Review/Processing Step -->
                    <div class="timeline-item {{ in_array($enquiry->status, ['approved', 'rejected', 'completed', 'processed']) ? 'active' : 'pending' }}">
                        <div class="timeline-marker 
                            {{ $enquiry->status == 'approved' ? 'bg-success' : '' }}
                            {{ $enquiry->status == 'rejected' ? 'bg-danger' : '' }}
                            {{ $enquiry->status == 'completed' ? 'bg-success' : '' }}
                            {{ $enquiry->status == 'processed' ? 'bg-success' : '' }}
                            {{ !in_array($enquiry->status, ['approved', 'rejected', 'completed', 'processed']) ? 'bg-secondary' : '' }}">
                            @if($enquiry->status == 'approved')
                                <i class="fas fa-check text-white"></i>
                            @elseif($enquiry->status == 'rejected')
                                <i class="fas fa-times text-white"></i>
                            @elseif($enquiry->status == 'completed')
                                <i class="fas fa-check text-white"></i>
                            @elseif($enquiry->status == 'processed')
                                <i class="fas fa-check text-white"></i>
                            @else
                                <i class="fas fa-hourglass-half text-white"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold
                                    {{ $enquiry->status == 'approved' ? 'text-success' : '' }}
                                    {{ $enquiry->status == 'rejected' ? 'text-danger' : '' }}
                                    {{ $enquiry->status == 'completed' ? 'text-success' : '' }}
                                    {{ $enquiry->status == 'processed' ? 'text-success' : '' }}
                                    {{ !in_array($enquiry->status, ['approved', 'rejected', 'completed', 'processed']) ? 'text-muted' : '' }}">
                                    @if(in_array($enquiry->status, ['approved', 'completed', 'processed']))
                                        <i class="fas fa-check-circle me-1"></i>
                                    @elseif($enquiry->status == 'rejected')
                                        <i class="fas fa-times-circle me-1"></i>
                                    @endif
                                    @if($enquiry->status == 'approved')
                                        @if($isLoanType)
                                            Loan Approved
                                        @else
                                            Enquiry Approved
                                        @endif
                                    @elseif($enquiry->status == 'rejected')
                                        Enquiry Rejected
                                    @elseif($enquiry->status == 'completed')
                                        Review Completed
                                    @elseif($enquiry->status == 'processed')
                                        Processing Completed
                                    @else
                                        Awaiting Review
                                    @endif
                                </h6>
                                @if(in_array($enquiry->status, ['approved', 'rejected', 'completed', 'processed']))
                                    <small class="text-muted">{{ $enquiry->updated_at->format('M d, Y - H:i') }}</small>
                                @endif
                            </div>
                            <div class="timeline-body">
                                @if($enquiry->status == 'approved')
                                    @if($isLoanType)
                                        <p class="mb-1 text-muted">Loan application has been approved for processing</p>
                                    @else
                                        <p class="mb-1 text-success">Enquiry approved and ready for final processing</p>
                                    @endif
                                @elseif($enquiry->status == 'rejected')
                                    <p class="mb-1 text-danger">Enquiry was rejected by the reviewing officer</p>
                                @elseif($enquiry->status == 'completed')
                                    @if($isReviewType)
                                        <p class="mb-1 text-success">Review process completed successfully</p>
                                    @else
                                        <p class="mb-1 text-success">Enquiry has been reviewed and completed</p>
                                    @endif
                                @elseif($enquiry->status == 'processed')
                                    <p class="mb-1 text-success">Enquiry has been fully processed</p>
                                @elseif($enquiry->status == 'assigned')
                                    <p class="mb-1 text-muted">Awaiting review by assigned officer</p>
                                @else
                                    <p class="mb-1 text-muted">Pending review and decision</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Final Completion Step -->
                    @if(
                        ($isLoanType && in_array($enquiry->status, ['approved', 'processed'])) ||
                        ($isReviewType && $enquiry->status == 'completed') ||
                        (!$isLoanType && !$isReviewType && $enquiry->status == 'processed')
                    )
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold text-success">
                                        <i class="fas fa-check-circle me-1"></i>Process Completed
                                    </h6>
                                    <small class="text-muted">{{ $enquiry->updated_at->format('M d, Y - H:i') }}</small>
                                </div>
                                <div class="timeline-body">
                                    @if($isLoanType)
                                        <p class="mb-1">Loan application successfully approved</p>
                                        <small class="text-muted">All approval steps completed</small>
                                    @elseif($isReviewType)
                                        <p class="mb-1">Enquiry review completed successfully</p>
                                        <small class="text-muted">All required actions have been taken</small>
                                    @else
                                        <p class="mb-1">Enquiry processing completed</p>
                                        <small class="text-muted">All required actions have been taken</small>
                                    @endif
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
                                    @if($isLoanType)
                                        <p class="mb-1 text-muted">Awaiting loan approval decision</p>
                                    @elseif($isReviewType)
                                        <p class="mb-1 text-muted">Awaiting review completion</p>
                                    @else
                                        <p class="mb-1 text-muted">Awaiting final processing</p>
                                    @endif
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
                                ($enquiry->status == 'completed' ? 'bg-success' :
                                ($enquiry->status == 'processed' ? 'bg-primary' :
                                ($isOverdue ? 'bg-danger' : 'bg-warning text-dark'))))) }} fs-6 px-3 py-2">
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

@include('modals.assign_enquries')
@if($enquiry->users->count() > 0)
    @include('modals.reassign_enquiry')
@endif
@endsection