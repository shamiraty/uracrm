@forelse($loanOffers as $offer)
<tr class="table-row-modern" data-id="{{ $offer->id }}" data-amount="{{ $offer->take_home_amount ?? $offer->net_loan_amount ?? $offer->requested_amount }}">
    <td class="checkbox-cell">
        <div class="form-check modern-checkbox">
            <input class="form-check-input loan-checkbox" 
                   type="checkbox" 
                   value="{{ $offer->id }}"
                   id="loan-{{ $offer->id }}"
                   {{ $offer->isDisbursed() ? 'disabled' : '' }}>
            <label class="form-check-label" for="loan-{{ $offer->id }}"></label>
        </div>
    </td>
    <td class="employee-cell">
        <div class="employee-info-modern">
            <div class="employee-name">
                {{ $offer->first_name }} {{ $offer->middle_name }} {{ $offer->last_name }}
                @if($offer->loan_type === 'topup' || $offer->offer_type === 'TOP_UP')
                    <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">
                        <i class="fas fa-sync-alt"></i> TOPUP
                    </span>
                @else
                    <span class="badge bg-success ms-2" style="font-size: 0.7rem;">
                        <i class="fas fa-plus-circle"></i> NEW
                    </span>
                @endif
            </div>
            <div class="employee-meta">
                <span class="meta-badge">
                    <i class="fas fa-id-badge"></i> {{ $offer->check_number }}
                </span>
                @if($offer->loan_type === 'topup' && $offer->topupAsNew && $offer->topupAsNew->original_loan_number)
                    <span class="meta-badge ms-2" style="background: #ffc107;">
                        <i class="fas fa-link"></i> Settles: {{ $offer->topupAsNew->original_loan_number }}
                    </span>
                @endif
            </div>
        </div>
    </td>
    <td class="amount-cell">
        <div class="amount-wrapper">
            <div class="amount-value">{{ number_format($offer->requested_amount ?? 0, 0) }}</div>
            <div class="amount-label">
                TZS • {{ $offer->tenure ?? 12 }} months
                @if($offer->loan_type === 'topup' && $offer->topupAsNew && $offer->topupAsNew->settlement_amount)
                    <br><small class="text-warning">
                        <i class="fas fa-minus-circle"></i> Settlement: {{ number_format($offer->topupAsNew->settlement_amount, 0) }}
                    </small>
                @endif
            </div>
        </div>
    </td>
    <td class="amount-cell">
        <div class="amount-wrapper highlight">
            <div class="amount-value text-success">{{ number_format($offer->take_home_amount ?? $offer->net_loan_amount ?? 0, 0) }}</div>
            <div class="amount-label">Take Home</div>
        </div>
    </td>
    <td class="bank-cell">
        <div class="bank-info-modern">
            <div class="bank-name">
                <span class="bank-badge-large" style="background: linear-gradient(135deg, #003366, #17479E);">
                    @if($offer->bank)
                        {{ $offer->bank->short_name ?? substr($offer->bank->name, 0, 4) }}
                    @else
                        {{ substr($offer->swift_code ?? 'BANK', 0, 4) }}
                    @endif
                </span>
            </div>
            <div class="bank-swift">{{ $offer->swift_code ?? '-' }}</div>
        </div>
    </td>
    <td class="account-cell">
        <div class="account-info">
            <div class="account-number">{{ $offer->bank_account_number ?? '-' }}</div>
            <div class="account-type">{{ $offer->account_type ?? 'Savings' }}</div>
        </div>
    </td>
    <td class="status-cell">
        <div class="status-badge-modern {{ $offer->getStatusClass() }}">
            @if($offer->isDisbursed())
                <i class="fas fa-check-circle"></i> Disbursed
            @elseif($offer->disbursements()->where('status', 'pending')->exists())
                <i class="fas fa-spinner fa-spin"></i> Processing
            @elseif($offer->disbursements()->where('status', 'failed')->exists())
                <i class="fas fa-exclamation-triangle"></i> Failed
            @else
                <i class="fas fa-clock"></i> Ready
            @endif
        </div>
        @if($offer->disbursements()->where('status', 'failed')->exists())
            @php
                $lastFailure = $offer->disbursements()->where('status', 'failed')->latest()->first();
            @endphp
            <div class="failure-reason">
                {{ Str::limit($lastFailure->failure_reason ?? 'Unknown error', 50) }}
            </div>
        @endif
    </td>
    <td class="channel-cell text-center">
        @php
            $latestDisbursement = $offer->disbursements()->latest()->first();
            $channelIdentifier = $latestDisbursement->channel_identifier ?? null;
        @endphp
        @if($channelIdentifier)
            <span class="channel-badge {{ strtolower($channelIdentifier) }}">
                {{ $channelIdentifier }}
            </span>
        @else
            <span class="channel-badge pending">-</span>
        @endif
    </td>
    <td class="action-cell">
        <div class="action-buttons">
            <button class="action-btn view" 
                    onclick="viewLoanDetails({{ $offer->id }})"
                    title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            @if(!$offer->isDisbursed())
            <button class="action-btn disburse" 
                    onclick="initiateDisbursement({{ $offer->id }})"
                    title="Disburse">
                <i class="fas fa-paper-plane"></i>
            </button>
            @endif
            @php
                $hasFailedDisbursement = $offer->disbursements()->where('status', 'failed')->exists();
            @endphp
            @if($hasFailedDisbursement)
            <button class="action-btn cancel" 
                    onclick="retryDisbursement({{ $offer->id }})"
                    title="Retry">
                <i class="fas fa-redo"></i>
            </button>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="empty-state">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Approved Loans</h5>
            <p class="text-muted">There are no approved loans matching your filter criteria</p>
        </div>
    </td>
</tr>
@endforelse