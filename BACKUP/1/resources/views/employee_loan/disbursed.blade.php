@extends('employee_loan.base_template')

@section('page_title', 'Disbursed Loans')
@section('page_heading', 'Disbursed Loan Applications')
@section('page_description', 'Track and manage disbursed loan funds')

@section('page_icon')
    <i class="fas fa-money-check-alt"></i>
@endsection

@section('kpi_section')
<!-- Modern KPI Dashboard with Glassmorphism - Disbursed Focused -->
<div class="kpi-dashboard-modern mb-3">
    <div class="kpi-backdrop"></div>
    <div class="row g-3">
        <!-- Total Disbursed -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-1" onclick="showKPIDetails('disbursed')" style="cursor: pointer;">
                <div class="kpi-glow success-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern success-icon">
                            <i class="fas fa-money-check-alt"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern success-badge">DISBURSED</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ $stats['total'] ?? 0 }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-icon approved-trend">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="trend-text text-success">Total Disbursed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-2" onclick="showKPIDetails('disbursed')" style="cursor: pointer;">
                <div class="kpi-glow info-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern info-icon">
                            <i class="fas fa-coins"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern info-badge">AMOUNT</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ number_format($stats['total_amount'] ?? 0, 0) }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-text text-info">Total TZS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-3" onclick="showKPIDetails('disbursed')" style="cursor: pointer;">
                <div class="kpi-glow purple-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern purple-icon">
                            <i class="fas fa-sync-alt"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern purple-badge">ACTIVE</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ $stats['active'] ?? 0 }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-text">Active Loans</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-4" onclick="showKPIDetails('disbursed')" style="cursor: pointer;">
                <div class="kpi-glow warning-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern warning-icon">
                            <i class="fas fa-calendar-check"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern warning-badge">MONTHLY</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ $stats['monthly'] ?? 0 }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-text">This Month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('search_section')
<!-- Search and Filter Section -->
<div class="modern-search-container mb-3">
    <div class="search-backdrop"></div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="search-input-group">
                <span class="search-icon">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" 
                       id="searchInput" 
                       class="modern-search-input" 
                       placeholder="Search by name, application number, or employee ID..."
                       value="{{ request('search') }}">
                <button class="search-clear-btn" onclick="clearSearch()" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterByStatus('disbursed')">
                    <i class="fas fa-money-check-alt"></i> All Disbursed
                </button>
                <button class="filter-btn" onclick="filterByDateRange('today')">
                    <i class="fas fa-calendar-day"></i> Today
                </button>
                <button class="filter-btn" onclick="filterByDateRange('week')">
                    <i class="fas fa-calendar-week"></i> This Week
                </button>
                <button class="filter-btn" onclick="filterByDateRange('month')">
                    <i class="fas fa-calendar-alt"></i> This Month
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('table_content')
@forelse($loanOffers as $offer)
    <tr class="table-row-modern clickable-row" data-loan='{{ json_encode($offer) }}'>
        <td class="checkbox-column">
            <div class="form-check modern-checkbox">
                <input class="form-check-input loan-checkbox" 
                       type="checkbox" 
                       value="{{ $offer->id }}"
                       id="loan{{ $offer->id }}">
                <label class="form-check-label" for="loan{{ $offer->id }}"></label>
            </div>
        </td>
        <td>
            <div class="employee-simple">
                <div class="employee-name fw-semibold">
                    {{ $offer->first_name ?? $offer->employee_name }} {{ $offer->last_name ?? '' }}
                </div>
                <div class="employee-id text-muted small">
                    Check #: {{ $offer->check_number ?? $offer->employee_number }}
                </div>
            </div>
        </td>
        <td class="text-end">
            <div class="amount-cell">
                <div class="amount-primary">{{ number_format($offer->basic_salary ?? 0, 0) }}</div>
                <div class="amount-secondary">Net: {{ number_format($offer->net_salary ?? 0, 0) }}</div>
            </div>
        </td>
        <td class="text-end">
            <div class="amount-deductible">{{ number_format($offer->desired_deductible_amount ?? 0, 0) }}</div>
        </td>
        <td class="text-end">
            <div class="amount-requested">{{ number_format($offer->requested_amount ?? 0, 0) }}</div>
        </td>
        <td class="text-end">
            <div class="amount-takehome">{{ number_format($offer->take_home_amount ?? $offer->net_loan_amount ?? 0, 0) }}</div>
        </td>
        <td class="text-center">
            @if($offer->tenure)
                <span class="modern-badge badge-tenure">{{ $offer->tenure }} mo</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center">
            <span class="modern-status-badge status-disbursed" title="Disbursed" data-bs-toggle="tooltip">
                <i class="fas fa-money-check-alt"></i> Disbursed
            </span>
        </td>
        <td class="text-center">
            @if($offer->disbursement_date)
                <span class="text-success small">
                    <i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($offer->disbursement_date)->format('d M Y') }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center">
            <div class="action-buttons">
                <button type="button"
                        class="action-btn btn-view view-loan-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#loanDetailsModal"
                        title="View Details"
                        data-loan='{{ json_encode($offer) }}'>
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn btn-info"
                        onclick="viewPaymentHistory({{ $offer->id }})"
                        title="Payment History">
                    <i class="fas fa-history"></i>
                </button>
                <button class="action-btn btn-success"
                        onclick="downloadStatement({{ $offer->id }})"
                        title="Download Statement">
                    <i class="fas fa-file-pdf"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <h5>No Disbursed Loans</h5>
                <p>No loans have been disbursed yet</p>
            </div>
        </td>
    </tr>
@endforelse
@endsection

@section('pagination_section')
@if($loanOffers->hasPages())
    <div class="modern-table-footer">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <span class="text-muted">Showing {{ $loanOffers->firstItem() }} to {{ $loanOffers->lastItem() }} of {{ $loanOffers->total() }} entries</span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end">
                    {{ $loanOffers->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('additional_scripts')
<script>
// Filter by status for disbursed page
function filterByStatus(status) {
    window.location.href = '{{ route("loans.disbursed") }}';
}

// Filter by date range
function filterByDateRange(range) {
    window.location.href = '{{ route("loans.disbursed") }}?date_range=' + range;
}

// View payment history
function viewPaymentHistory(id) {
    Swal.fire({
        title: 'Payment History',
        text: "Loading payment history for loan #" + id,
        icon: 'info',
        showCancelButton: false,
        confirmButtonColor: '#17479E',
        confirmButtonText: 'Close'
    });
}

// Download statement
function downloadStatement(id) {
    Swal.fire({
        title: 'Download Statement?',
        text: "This will download the loan statement as PDF.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Download',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Add download logic here
            window.location.href = '/loan-offers/' + id + '/statement';
            showToast('Statement download started', 'success');
        }
    });
}
</script>
@endsection