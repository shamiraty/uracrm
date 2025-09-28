@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Loan Applications</h4>
                <small class="text-muted">Manage and review all loan offers</small>
            </div>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Application
            </a>
        </div>

        <div class="card-body">
           
            <div class="mb-3">
                <form method="GET" action="{{ route('loan-offers.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, application #, or check #" value="{{ request('search') }}">
                        
                        <select name="status" class="form-select" style="max-width: 200px;">
                            <option value="">All Statuses</option>
                            <option value="disbursement_pending" {{ request('status') == 'disbursement_pending' ? 'selected' : '' }}>Pending Disbursement</option>
                            <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                            <option value="DISBURSEMENT_FAILED" {{ request('status') == 'DISBURSEMENT_FAILED' ? 'selected' : '' }}>Disbursement Failed</option>
                            <option value="FULL_SETTLED" {{ request('status') == 'FULL_SETTLED' ? 'selected' : '' }}>Full Settled</option>
                        </select>
                        
                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Applicant</th>
                            <th>Application #</th>
                            <th>Amount (TZS)</th>
                            <th>Approval</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loanOffers as $offer)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $offer->first_name }} {{ $offer->last_name }}</div>
                                    <small class="text-muted">{{ $offer->check_number }}</small>
                                </td>
                                <td>{{ $offer->application_number }}</td>
                                <td class="text-end">{{ number_format($offer->requested_amount, 2) }}</td>
                                <td>
                                    @switch($offer->approval)
                                        @case('APPROVED')
                                            <span class="badge bg-success-soft text-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                            @break
                                        @case('REJECTED')
                                            <span class="badge bg-danger-soft text-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                            @break
                                        @default
                                            <span class="badge bg-warning-soft text-warning"><i class="fas fa-hourglass-half me-1"></i> Pending</span>
                                    @endswitch
                                </td>
                                <td>
                                    @switch($offer->status)
                                        @case('disbursement_pending')
                                            <span class="badge bg-info-soft text-info"><i class="fas fa-spinner fa-spin me-1"></i> Pending Bank</span>
                                            @break
                                        @case('disbursed')
                                            <span class="badge bg-success-soft text-success"><i class="fas fa-check-double me-1"></i> Disbursed</span>
                                            @break
                                        @case('DISBURSEMENT_FAILED')
                                            <span class="badge bg-danger-soft text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Failed</span>
                                            @break
                                        @case('FULL_SETTLED')
                                             <span class="badge bg-dark-soft text-dark"><i class="fas fa-handshake me-1"></i> Settled</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-soft text-secondary">{{ $offer->status ? Str::title(str_replace('_', ' ', $offer->status)) : 'New' }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $offer->created_at->format('d-M-Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('loan-offers.edit', $offer->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Preview Loan Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="mb-0 text-muted">No loan offers found.</p>
                                    @if(request('search') || request('status'))
                                       <a href="{{ route('loan-offers.index') }}" class="btn btn-sm btn-link mt-2">Clear Filters</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

          
            <div class="mt-3 d-flex justify-content-center">
                {{ $loanOffers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
 
