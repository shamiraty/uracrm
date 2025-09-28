@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-history me-2"></i>NMB Callback History for Loan #{{ $loanOffer->application_number }}
            </h4>
            <a href="{{ route('loan-offers.edit', $loanOffer->id) }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Loan Details
            </a>
        </div>

        <div class="card-body">
            @forelse ($loanOffer->callbacks as $callback)
                <div class="card mb-3">
                    <div class="card-header">
                        Callback Received: {{ $callback->created_at->format('d-M-Y h:i A') }}
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Final Status:</strong>
                            @if (strtolower($callback->final_status) === 'success')
                                <span class="badge bg-success">Success</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Status Description:</strong>
                            <span>{{ $callback->status_description ?? 'N/A' }}</span>
                        </li>
                         <li class="list-group-item d-flex justify-content-between">
                            <strong>Batch ID:</strong>
                            <code>{{ $callback->batch_id }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>NMB Payment Reference:</strong>
                            <code>{{ $callback->payment_reference ?? 'N/A' }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>NMB File Reference:</strong>
                            <code>{{ $callback->file_ref_id ?? 'N/A' }}</code>
                        </li>
                    </ul>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    No callback history has been recorded for this loan offer yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection