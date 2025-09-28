{{-- Normalized Tables Information Display --}}

{{-- Bank Information from normalized banks table --}}
@if($loanOffer->bank)
<div class="info-section bank-info-normalized">
    <h6 class="section-title">
        <i class="fas fa-university me-2"></i>Bank Details (from Banks Table)
    </h6>
    <div class="row">
        <div class="col-md-6">
            <label class="text-muted">Bank Name</label>
            <p class="fw-bold">{{ $loanOffer->bank->name }}</p>
        </div>
        <div class="col-md-6">
            <label class="text-muted">SWIFT Code</label>
            <p class="fw-bold">{{ $loanOffer->bank->swift_code }}</p>
        </div>
        @if($loanOffer->bank->short_name)
        <div class="col-md-6">
            <label class="text-muted">Short Name</label>
            <p class="fw-bold">{{ $loanOffer->bank->short_name }}</p>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Approval History from loan_offer_approvals table --}}
@if($loanOffer->approvals && $loanOffer->approvals->count() > 0)
<div class="info-section approval-history-normalized mt-3">
    <h6 class="section-title">
        <i class="fas fa-check-circle me-2"></i>Approval History
    </h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Approved/Rejected By</th>
                    <th>Date</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loanOffer->approvals as $approval)
                <tr>
                    <td>
                        <span class="badge bg-info">{{ ucfirst($approval->approval_type) }}</span>
                    </td>
                    <td>
                        @if($approval->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($approval->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($approval->approved_by)
                            {{ $approval->approvedBy->name ?? 'User #'.$approval->approved_by }}
                        @elseif($approval->rejected_by)
                            {{ $approval->rejectedBy->name ?? 'User #'.$approval->rejected_by }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($approval->approved_at)
                            {{ \Carbon\Carbon::parse($approval->approved_at)->format('d M Y H:i') }}
                        @elseif($approval->rejected_at)
                            {{ \Carbon\Carbon::parse($approval->rejected_at)->format('d M Y H:i') }}
                        @else
                            <span class="text-muted">Pending</span>
                        @endif
                    </td>
                    <td>{{ $approval->comments ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Disbursement Information from loan_disbursements table --}}
@if($loanOffer->disbursements && $loanOffer->disbursements->count() > 0)
<div class="info-section disbursement-history-normalized mt-3">
    <h6 class="section-title">
        <i class="fas fa-hand-holding-usd me-2"></i>Disbursement History
    </h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Channel</th>
                    <th>Destination</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                    <th>Disbursed By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loanOffer->disbursements as $disbursement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($disbursement->created_at)->format('d M Y H:i') }}</td>
                    <td>{{ number_format($disbursement->amount, 2) }}</td>
                    <td>
                        @if($disbursement->channel_identifier)
                            <span class="badge bg-primary">{{ $disbursement->channel_identifier }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($disbursement->destination_code)
                            {{ $disbursement->destination_code }}
                            @if($disbursement->swift_code)
                                <br><small class="text-muted">SWIFT: {{ $disbursement->swift_code }}</small>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($disbursement->status == 'success')
                            <span class="badge bg-success">Success</span>
                        @elseif($disbursement->status == 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>{{ $disbursement->transaction_id ?: '-' }}</td>
                    <td>
                        @if($disbursement->disbursed_by)
                            {{ $disbursement->disbursedBy->name ?? 'User #'.$disbursement->disbursed_by }}
                        @else
                            <span class="text-muted">System</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Top-up Information if this is a top-up loan --}}
@if($loanOffer->loan_type == 'topup' && $loanOffer->topupAsNew)
<div class="info-section topup-info-normalized mt-3">
    <h6 class="section-title">
        <i class="fas fa-sync-alt me-2"></i>Top-up Loan Information
    </h6>
    <div class="row">
        <div class="col-md-6">
            <label class="text-muted">Original Loan</label>
            <p class="fw-bold">
                @if($loanOffer->topupAsNew->originalLoan)
                    {{ $loanOffer->topupAsNew->originalLoan->application_number }}
                @else
                    N/A
                @endif
            </p>
        </div>
        <div class="col-md-6">
            <label class="text-muted">Settlement Amount</label>
            <p class="fw-bold">TZS {{ number_format($loanOffer->topupAsNew->settlement_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-6">
            <label class="text-muted">Top-up Amount</label>
            <p class="fw-bold">TZS {{ number_format($loanOffer->topupAsNew->top_up_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-6">
            <label class="text-muted">New Total Loan</label>
            <p class="fw-bold">TZS {{ number_format($loanOffer->requested_amount, 2) }}</p>
        </div>
    </div>
</div>
@endif

{{-- Show if this loan has been topped up --}}
@if($loanOffer->topupAsOriginal)
<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    This loan has been topped up. New loan reference: 
    <strong>{{ $loanOffer->topupAsOriginal->newLoan->application_number ?? 'N/A' }}</strong>
</div>
@endif

<style>
.info-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid var(--ura-primary);
}

.section-title {
    color: var(--ura-primary);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
}

.table-sm {
    font-size: 13px;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
}
</style>