@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="ura-card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('loan-offers.index') }}">Loan Applications</a></li>
                            <li class="breadcrumb-item active">{{ $loanOffer->application_number }}</li>
                        </ol>
                    </nav>
                    <h3 class="mb-1">
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                        Loan Application #{{ $loanOffer->application_number }}
                    </h3>
                    <p class="text-muted mb-0">
                        ESS Reference: {{ $loanOffer->ess_reference ?? 'N/A' }} | 
                        Created: {{ $loanOffer->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <a href="{{ route('loan-offers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Information -->
        <div class="col-lg-8">
            <!-- Status Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="ura-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-clipboard-check me-2"></i>Approval Status
                            </h6>
                            @if ($loanOffer->approval == 'APPROVED')
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-success"></div>
                                    <div>
                                        <h4 class="mb-0 text-success">APPROVED</h4>
                                        <small class="text-muted">Ready for disbursement</small>
                                    </div>
                                </div>
                            @elseif ($loanOffer->approval == 'REJECTED')
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-danger"></div>
                                    <div>
                                        <h4 class="mb-0 text-danger">REJECTED</h4>
                                        <small class="text-muted">{{ $loanOffer->reason }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-warning"></div>
                                    <div>
                                        <h4 class="mb-0 text-warning">PENDING</h4>
                                        <small class="text-muted">Awaiting approval</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="ura-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-university me-2"></i>Disbursement Status
                            </h6>
                            @if ($loanOffer->status == 'disbursed')
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-success"></div>
                                    <div>
                                        <h4 class="mb-0 text-success">DISBURSED</h4>
                                        <small class="text-muted">Funds sent to account</small>
                                    </div>
                                </div>
                            @elseif ($loanOffer->status == 'disbursement_pending')
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-info animated-pulse"></div>
                                    <div>
                                        <h4 class="mb-0 text-info">PROCESSING</h4>
                                        <small class="text-muted">NMB Batch: {{ $loanOffer->nmb_batch_id }}</small>
                                    </div>
                                </div>
                            @elseif ($loanOffer->status == 'DISBURSEMENT_FAILED')
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-danger"></div>
                                    <div>
                                        <h4 class="mb-0 text-danger">FAILED</h4>
                                        <small class="text-danger">{{ $loanOffer->reason }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator bg-secondary"></div>
                                    <div>
                                        <h4 class="mb-0 text-secondary">NOT DISBURSED</h4>
                                        <small class="text-muted">Pending approval</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disbursement Action -->
            @if ($loanOffer->approval === 'APPROVED' && !in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED']))
                <div class="ura-card border-success mb-4">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-university text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">Ready for NMB Disbursement</h5>
                        <p class="text-muted mb-4">
                            Total Amount: <strong>TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}</strong><br>
                            Account: <strong>{{ $loanOffer->bank_account_number }}</strong>
                        </p>
                        <button id="disburse-btn" class="btn btn-ura-success btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i>Disburse via NMB
                        </button>
                    </div>
                </div>
            @endif

            <!-- Employee Information -->
            <div class="ura-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Employee Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0 fw-semibold">
                                {{ $loanOffer->first_name }} {{ $loanOffer->middle_name }} {{ $loanOffer->last_name }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Check Number</label>
                            <p class="mb-0 fw-semibold">{{ $loanOffer->check_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Bank Account (NMB)</label>
                            <p class="mb-0 fw-semibold">{{ $loanOffer->bank_account_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Amount</label>
                            <p class="mb-0 fw-semibold">TZS {{ number_format($loanOffer->requested_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Details Form -->
            <div class="ura-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Loan Processing Details
                    </h5>
                </div>
                <div class="card-body">
                    <form id="loan-update-form">
                        @csrf
                        @method('PUT')
                        
                        <fieldset @if(in_array($loanOffer->status, ['disbursement_pending', 'disbursed'])) disabled @endif>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Total Amount to Pay</label>
                                    <div class="input-group">
                                        <span class="input-group-text">TZS</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="total_amount_to_pay" 
                                               value="{{ $loanOffer->total_amount_to_pay }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Other Charges</label>
                                    <div class="input-group">
                                        <span class="input-group-text">TZS</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="other_charges" 
                                               value="{{ $loanOffer->other_charges }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Approval Status</label>
                                    <select class="form-select" name="approval">
                                        <option value="PENDING" {{ $loanOffer->approval == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                        <option value="APPROVED" {{ $loanOffer->approval == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                                        <option value="REJECTED" {{ $loanOffer->approval == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Manual Status Override</label>
                                    <select class="form-select" name="status">
                                        <option value="">-- No Change --</option>
                                        <option value="DISBURSEMENT_FAILED">Mark as Failed</option>
                                        <option value="FULL_SETTLED">Mark as Settled</option>
                                    </select>
                                    <small class="form-text text-muted">Use only for manual corrections</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Remarks / Reason</label>
                                <textarea class="form-control" name="reason" rows="3">{{ $loanOffer->reason }}</textarea>
                            </div>

                            @if(!in_array($loanOffer->status, ['disbursement_pending', 'disbursed']))
                                <button type="button" id="save-btn" class="btn btn-ura-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            @endif
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Activity & Info -->
        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="ura-card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>Quick Info
                    </h5>
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">Processing Time</small>
                        <strong>{{ $loanOffer->created_at->diffForHumans() }}</strong>
                    </div>
                    <div class="info-item mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <strong>{{ $loanOffer->updated_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    @if($loanOffer->nmb_batch_id)
                        <div class="info-item mb-3">
                            <small class="text-muted d-block">NMB Batch ID</small>
                            <strong>{{ $loanOffer->nmb_batch_id }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="ura-card">
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="fas fa-history me-2"></i>Activity Timeline
                    </h5>
                    <div class="timeline">
                        @foreach($activities ?? [] as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong>{{ $activity->description }}</strong>
                                    <p class="text-muted small mb-0">
                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Default timeline items -->
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Application Created</strong>
                                <p class="text-muted small mb-0">
                                    {{ $loanOffer->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 10px;
}

.animated-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    width: 2px;
    height: 100%;
    background: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--ura-primary);
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Configure toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // Save button handler
    $('#save-btn').on('click', function(e) {
        const button = $(this);
        const originalHtml = button.html();
        
        Swal.fire({
            title: 'Confirm Changes',
            text: "Save updates to this loan application?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#003366',
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
                        button.html(originalHtml).prop('disabled', false);
                    }
                });
            }
        });
    });

    // Disburse button handler
    $('#disburse-btn').on('click', function(e) {
        const button = $(this);
        
        Swal.fire({
            title: '<span class="text-danger">FINAL CONFIRMATION</span>',
            html: `
                <p>You are about to disburse <strong>TZS {{ number_format($loanOffer->total_amount_to_pay, 2) }}</strong> to:</p>
                <p><strong>{{ $loanOffer->first_name }} {{ $loanOffer->last_name }}</strong><br>
                Account: <strong>{{ $loanOffer->bank_account_number }}</strong></p>
                <hr>
                <p class="text-danger">This action cannot be undone!</p>
                <p>Type <strong>DISBURSE</strong> to confirm:</p>
            `,
            input: 'text',
            inputPlaceholder: 'DISBURSE',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm & Disburse',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            preConfirm: (inputValue) => {
                if (inputValue !== 'DISBURSE') {
                    Swal.showValidationMessage('Please type DISBURSE to confirm');
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Show processing
                Swal.fire({
                    title: 'Processing Disbursement',
                    html: '<div class="spinner-border text-primary" role="status"></div><br>Sending to NMB Bank...',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
                
                // Make disbursement request
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
                            html: response.message,
                            confirmButtonColor: '#1e8449'
                        }).then(() => {
                            location.reload();
                        });
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
});
</script>
@endpush