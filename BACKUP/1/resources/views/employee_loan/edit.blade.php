@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 bg-gradient">
    <!-- Modern Page Header -->
    <div class="page-header-wrapper mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent px-0 mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loan-offers.index') }}">Loan Applications</a></li>
                        <li class="breadcrumb-item active">Edit #{{ $loanOffer->application_number }}</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Edit Loan Application
                </h1>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('loan-offers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
                @php
                    $isCancelled = in_array(strtoupper($loanOffer->status ?? ''), ['CANCELLED', 'CANCELED']) || 
                                  in_array(strtoupper($loanOffer->state ?? ''), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                @endphp
                @if($loanOffer->approval !== 'REJECTED' && $loanOffer->status !== 'disbursed' && $loanOffer->status !== 'FULL_SETTLED' && !$isCancelled)
                    <button class="btn btn-danger ms-2" onclick="rejectLoanApplication({{ $loanOffer->id }})">
                        <i class="fas fa-times-circle me-2"></i>Reject
                    </button>
                @endif
                @if($isCancelled)
                    <span class="badge bg-secondary ms-2" style="padding: 10px 20px; font-size: 14px;">
                        <i class="fas fa-ban me-1"></i>
                        Cancelled by Employee
                    </span>
                @endif
                <button class="btn btn-gradient-primary ms-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>

    <!-- Applicant Info Card -->
    <div class="card border-0 shadow-sm mb-4 applicant-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-large">
                        <span class="avatar-initials">
                            {{ strtoupper(substr($loanOffer->first_name, 0, 1)) }}{{ strtoupper(substr($loanOffer->last_name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1">{{ $loanOffer->first_name }} {{ $loanOffer->middle_name }} {{ $loanOffer->last_name }}</h3>
                    <div class="text-muted">
                        <span class="me-3"><i class="fas fa-id-badge me-1"></i>Check #{{ $loanOffer->check_number }}</span>
                        <span class="me-3"><i class="fas fa-file-alt me-1"></i>Application #{{ $loanOffer->application_number }}</span>
                        @if($loanOffer->bank_account_number)
                            <span><i class="fas fa-university me-1"></i>{{ $loanOffer->bank_account_number }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="text-end">
                        <h4 class="mb-0 text-primary">TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}</h4>
                        <small class="text-muted">Loan Amount</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="status-card h-100">
                <div class="status-icon-wrapper">
                    @if($loanOffer->approval == 'APPROVED')
                        <div class="status-icon bg-gradient-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    @elseif($loanOffer->approval == 'REJECTED')
                        <div class="status-icon bg-gradient-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    @else
                        <div class="status-icon bg-gradient-warning">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    @endif
                </div>
                <div class="status-content">
                    <h6 class="text-muted mb-1">Approval Status</h6>
                    <h4 class="mb-0">
                        @if($loanOffer->approval == 'APPROVED')
                            <span class="text-success">APPROVED</span>
                        @elseif($loanOffer->approval == 'REJECTED')
                            <span class="text-danger">REJECTED</span>
                        @else
                            <span class="text-warning">PENDING</span>
                        @endif
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="status-card h-100">
                <div class="status-icon-wrapper">
                    @if($loanOffer->status == 'disbursed')
                        <div class="status-icon bg-gradient-success">
                            <i class="fas fa-check-double"></i>
                        </div>
                    @elseif($loanOffer->status == 'disbursement_pending')
                        <div class="status-icon bg-gradient-info">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    @elseif($loanOffer->status == 'DISBURSEMENT_FAILED')
                        <div class="status-icon bg-gradient-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    @else
                        <div class="status-icon bg-gradient-secondary">
                            <i class="fas fa-clock"></i>
                        </div>
                    @endif
                </div>
                <div class="status-content">
                    <h6 class="text-muted mb-1">Processing Status</h6>
                    <h4 class="mb-0">
                        @if($loanOffer->status == 'disbursed')
                            <span class="text-success">DISBURSED</span>
                        @elseif($loanOffer->status == 'disbursement_pending')
                            <span class="text-info">PROCESSING</span>
                        @elseif($loanOffer->status == 'DISBURSEMENT_FAILED')
                            <span class="text-danger">FAILED</span>
                        @elseif($loanOffer->status == 'FULL_SETTLED')
                            <span class="text-dark">SETTLED</span>
                        @else
                            <span class="text-secondary">{{ strtoupper(str_replace('_', ' ', $loanOffer->status ?: 'NEW')) }}</span>
                        @endif
                    </h4>
                    @if($loanOffer->nmb_batch_id)
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="view-callbacks-btn" 
                                data-url="{{ route('loan-offers.callbacks.ajax', $loanOffer->id) }}">
                            <i class="fas fa-history me-1"></i>View History
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="status-card h-100">
                <div class="status-icon-wrapper">
                    <div class="status-icon bg-gradient-primary">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="status-content">
                    <h6 class="text-muted mb-1">Timeline</h6>
                    <p class="mb-0">Created: {{ $loanOffer->created_at->format('d M Y') }}</p>
                    <small class="text-muted">Updated {{ $loanOffer->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Disbursement Action Panel -->
    @if($loanOffer->approval === 'APPROVED' && !in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED', 'DISBURSEMENT_FAILED']) && !$isCancelled)
        <div class="alert alert-gradient-success mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-check-circle me-2"></i>Ready for Disbursement
                    </h5>
                    <p class="mb-0">This loan has been approved and is ready to be sent to NMB Bank for processing.</p>
                    <p class="mb-0 mt-1"><strong>Amount to Disburse:</strong> TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button id="disburse-btn" class="btn btn-lg btn-success shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i>Disburse via NMB
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                Loan Details
            </h5>
        </div>
        <div class="card-body p-4">
            <form id="loan-update-form" novalidate>
                @csrf
                @method('PUT')
                
                @php
                    $isFormDisabled = in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED']) || $isCancelled;
                @endphp
                <fieldset @if($isFormDisabled) disabled @endif>
                    
                    <!-- Financial Details Section -->
                    <div class="form-section mb-4">
                        <h6 class="section-title">Financial Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" id="total_amount_to_pay" 
                                           name="total_amount_to_pay" value="{{ $loanOffer->total_amount_to_pay }}" 
                                           placeholder="Total Amount" required>
                                    <label for="total_amount_to_pay">Total Amount to Pay (TZS)</label>
                                    <div class="invalid-feedback">Please enter a valid amount</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" id="other_charges" 
                                           name="other_charges" value="{{ $loanOffer->other_charges }}" 
                                           placeholder="Other Charges">
                                    <label for="other_charges">Other Charges (TZS)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Beneficiary Details Section -->
                    <div class="form-section mb-4">
                        <h6 class="section-title">Beneficiary Account Details</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="bank_account_number" 
                                           name="bank_account_number" value="{{ $loanOffer->bank_account_number }}" 
                                           placeholder="Account Number" required>
                                    <label for="bank_account_number">Account / Wallet Number</label>
                                    <div class="invalid-feedback">Please enter account number</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="payment_destination_id" name="payment_destination_id" required>
                                        <option value="">Select Destination...</option>
                                        @if(isset($destinations['BANK']))
                                            <optgroup label="Banks">
                                                @foreach($destinations['BANK'] as $destination)
                                                    <option value="{{ $destination->id }}" 
                                                            {{ $loanOffer->payment_destination_id == $destination->id ? 'selected' : '' }}>
                                                        {{ $destination->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if(isset($destinations['MNO']))
                                            <optgroup label="Mobile Money">
                                                @foreach($destinations['MNO'] as $destination)
                                                    <option value="{{ $destination->id }}" 
                                                            {{ $loanOffer->payment_destination_id == $destination->id ? 'selected' : '' }}>
                                                        {{ $destination->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                    <label for="payment_destination_id">Payment Destination</label>
                                    <div class="invalid-feedback">Please select a payment destination</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management Section -->
                    <div class="form-section mb-4">
                        <h6 class="section-title">Status Management</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="approval" name="approval">
                                        <option value="">Select Status...</option>
                                        <option value="APPROVED" {{ $loanOffer->approval === 'APPROVED' ? 'selected' : '' }}>
                                            ✓ APPROVED
                                        </option>
                                        <option value="REJECTED" {{ $loanOffer->approval === 'REJECTED' ? 'selected' : '' }}>
                                            ✗ REJECTED
                                        </option>
                                        <option value="PENDING" {{ (!$loanOffer->approval || $loanOffer->approval === 'PENDING') ? 'selected' : '' }}>
                                            ⏳ PENDING
                                        </option>
                                    </select>
                                    <label for="approval">Approval Status</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="status" name="status">
                                        <option value="">No Change</option>
                                        <option value="DISBURSEMENT_FAILED">Mark as Failed</option>
                                        <option value="FULL_SETTLED">Mark as Settled</option>
                                    </select>
                                    <label for="status">Manual Status Override</label>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>For manual correction only
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <div class="form-section mb-4">
                        <h6 class="section-title">Notes & Remarks</h6>
                        <div class="form-floating">
                            <textarea class="form-control" id="reason" name="reason" 
                                      placeholder="Enter remarks" style="height: 100px">{{ $loanOffer->reason }}</textarea>
                            <label for="reason">Reason / Remarks</label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    @if(!$isFormDisabled)
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" id="save-btn" class="btn btn-gradient-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Last saved {{ $loanOffer->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    @elseif($isCancelled)
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-ban me-2"></i>
                            This loan has been <strong>cancelled by the employee</strong> and cannot be edited or approved.
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-lock me-2"></i>
                            This loan is locked for editing due to its current status.
                        </div>
                    @endif
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Additional Information Accordion -->
    <div class="accordion mt-4" id="additionalInfoAccordion">
        <div class="accordion-item border-0 shadow-sm">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#loanHistory" aria-expanded="false">
                    <i class="fas fa-history me-2"></i>Loan History & Timeline
                </button>
            </h2>
            <div id="loanHistory" class="accordion-collapse collapse" data-bs-parent="#additionalInfoAccordion">
                <div class="accordion-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-badge bg-primary">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Application Created</h6>
                                <p class="mb-0 text-muted">{{ $loanOffer->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @if($loanOffer->approval)
                            <div class="timeline-item">
                                <div class="timeline-badge {{ $loanOffer->approval == 'APPROVED' ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas {{ $loanOffer->approval == 'APPROVED' ? 'fa-check' : 'fa-times' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ ucfirst(strtolower($loanOffer->approval)) }}</h6>
                                    <p class="mb-0 text-muted">Status changed</p>
                                </div>
                            </div>
                        @endif
                        @if($loanOffer->status == 'disbursed')
                            <div class="timeline-item">
                                <div class="timeline-badge bg-success">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Disbursed</h6>
                                    <p class="mb-0 text-muted">Funds sent successfully</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Callback History Modal -->
<div class="modal fade" id="callbackHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-history text-primary me-2"></i>
                    NMB Callback History
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="callbackHistoryBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Include Reject Loan Modal -->
@include('partials.reject-loan-modal')

@endsection

@push('styles')
<style>
/* Page Background */
.bg-gradient {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header-wrapper {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 1.5rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1d23;
}

/* Gradient Button */
.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Applicant Card */
.applicant-card {
    border-radius: 16px;
    overflow: hidden;
}

.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-initials {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
}

/* Status Cards */
.status-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.status-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.status-icon-wrapper {
    margin-right: 1.5rem;
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #23d5ab 0%, #23a455 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #c3cfe2 0%, #c3cfe2 100%);
}

/* Alert Gradient */
.alert-gradient-success {
    background: linear-gradient(135deg, rgba(35, 213, 171, 0.1) 0%, rgba(35, 164, 85, 0.1) 100%);
    border: 1px solid rgba(35, 164, 85, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
}

/* Form Sections */
.form-section {
    padding: 1.5rem 0;
    border-bottom: 1px solid #f0f2f5;
}

.form-section:last-child {
    border-bottom: none;
}

.section-title {
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

/* Form Controls */
.form-floating .form-control,
.form-floating .form-select {
    border-radius: 10px;
    border: 2px solid #e0e6ed;
    transition: all 0.2s ease;
}

.form-floating .form-control:focus,
.form-floating .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

/* Timeline */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 25px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e6ed;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 20px;
}

.timeline-badge {
    position: absolute;
    left: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.timeline-content h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

/* Accordion */
.accordion-item {
    border-radius: 12px !important;
    overflow: hidden;
}

.accordion-button {
    background: white;
    font-weight: 600;
    border: none;
}

.accordion-button:not(.collapsed) {
    background: #f8f9fa;
    color: #667eea;
}

.accordion-button:focus {
    box-shadow: none;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.status-card,
.applicant-card,
.card {
    animation: fadeInUp 0.5s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .status-card {
        margin-bottom: 1rem;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
    
    .avatar-large {
        width: 60px;
        height: 60px;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
    
    @if(session('status'))
        toastr.info("{{ session('status') }}");
    @endif
    
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    // Form validation
    $('#loan-update-form').on('submit', function(e) {
        e.preventDefault();
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return false;
        }
    });

    // Save button click
    $('#save-btn').on('click', function(e) {
        // Check if loan is cancelled
        const loanStatus = '{{ $loanOffer->status ?? '' }}'.toUpperCase();
        const loanState = '{{ $loanOffer->state ?? '' }}'.toUpperCase();
        const isCancelled = ['CANCELLED', 'CANCELED'].includes(loanStatus) || 
                          ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes(loanState);
        
        if (isCancelled) {
            Swal.fire({
                title: 'Cannot Save Changes',
                html: `
                    <div class="text-center">
                        <i class="fas fa-ban mb-3" style="font-size: 48px; color: #dc3545;"></i>
                        <p>This loan has been <strong>cancelled by the employee</strong> through ESS.</p>
                        <small class="text-muted">Cancelled loans cannot be edited or approved.</small>
                    </div>
                `,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const form = $('#loan-update-form')[0];
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const button = $(this);
        const originalHtml = button.html();
        
        Swal.fire({
            title: 'Confirm Changes',
            text: "Save updates to this loan application?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, save changes'
        }).then((result) => {
            if (result.isConfirmed) {
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop('disabled', true);
                
                $.ajax({
                    url: "{{ route('loan-offers.update', $loanOffer->id) }}",
                    type: 'POST',
                    data: $('#loan-update-form').serialize(),
                    success: function(response) {
                        toastr.success(response.message || 'Changes saved successfully!');
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        toastr.error('Failed to save changes. Please try again.');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        button.html(originalHtml).prop('disabled', false);
                    }
                });
            }
        });
    });

    // Reject loan application
    window.rejectLoanApplication = function(loanId) {
        // Prepare loan data for the modal
        const loanData = {
            application_number: '{{ $loanOffer->application_number }}',
            name: '{{ $loanOffer->first_name }} {{ $loanOffer->last_name }}',
            amount: 'TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}',
            date: '{{ $loanOffer->created_at->format("d M Y") }}'
        };
        
        // Show the rejection modal
        showRejectLoanModal(loanId, loanData);
    };
    
    // Process loan rejection after modal confirmation
    window.processLoanRejection = function(loanId, reason, message) {
        Swal.fire({
            title: 'Processing...',
            text: 'Rejecting loan application',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: `/loan-offers/${loanId}`,
            type: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                '_method': 'PUT',
                'approval': 'REJECTED',
                'reason': message
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Loan Rejected',
                    text: response.message || 'The loan application has been rejected successfully.',
                    timer: 3000
                }).then(() => {
                    window.location.href = "{{ route('loan-offers.index') }}";
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to reject the loan application. Please try again.'
                });
            }
        });
    };
    
    // Disburse button click
    $('#disburse-btn').on('click', function(e) {
        Swal.fire({
            title: 'Confirm Disbursement',
            html: `
                <div class="text-center">
                    <i class="fas fa-university fa-3x text-primary mb-3"></i>
                    <p>You are about to disburse <strong>TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}</strong></p>
                    <p class="text-muted">This action cannot be undone.</p>
                    <div class="mt-3">
                        <p>Type <strong>DISBURSE</strong> to confirm:</p>
                    </div>
                </div>
            `,
            input: 'text',
            inputPlaceholder: 'Type DISBURSE',
            showCancelButton: true,
            confirmButtonText: 'Confirm & Disburse',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            preConfirm: (inputValue) => {
                if (inputValue !== 'DISBURSE') {
                    Swal.showValidationMessage('Please type DISBURSE to confirm');
                    return false;
                }
                return true;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('loan-offers.update', $loanOffer->id) }}",
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        '_method': 'PUT',
                        'status': 'SUBMITTED_FOR_DISBURSEMENT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Disbursement Initiated!',
                            text: response.message || 'Loan has been submitted to NMB for processing.',
                            timer: 3000
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Disbursement Failed',
                            text: 'An error occurred. Please try again.'
                        });
                    }
                });
            }
        });
    });

    // View callbacks button
    $('#view-callbacks-btn').on('click', function() {
        const url = $(this).data('url');
        const modal = new bootstrap.Modal(document.getElementById('callbackHistoryModal'));
        const modalBody = $('#callbackHistoryBody');

        modalBody.html(`
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading callback history...</p>
            </div>
        `);
        
        modal.show();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                modalBody.html(response);
            },
            error: function(xhr) {
                modalBody.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Could not load callback history. Please try again.
                    </div>
                `);
                console.error(xhr.responseText);
            }
        });
    });
});

// Reset form function
function resetForm() {
    document.getElementById('loan-update-form').reset();
    document.getElementById('loan-update-form').classList.remove('was-validated');
}
</script>
@endpush