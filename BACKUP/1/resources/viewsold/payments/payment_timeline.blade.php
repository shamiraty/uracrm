{{-- resources/views/payments/payment_timeline.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
    <h5 class="card-header text-primary">Payment Timeline</h5>
    <div class="card-body">
    @foreach ($payment->logs as $index => $log)
        <div class="row g-0 mb-4">
            @if ($index % 2 == 0) <!-- Even index for left alignment -->
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <div class="row h-50">
                        <div class="col">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                    <h5 class="m-2">
                        <span class="badge rounded-pill {{ $log->payment ? 'bg-primary' : 'bg-light border' }}" 
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top" 
                              title="{{ $log->payment ? $log->payment->status : 'Unknown Payment' }}">&nbsp;</span>
                    </h5>
                    <div class="row h-50">
                        <div class="col border-end">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card radius-15 {{ $log->payment ? 'border-primary shadow' : '' }} transition">
                        <div class="card-body">
                            <div class="float-end text-muted small">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                            <h4 class="card-title {{ $log->payment ? 'text-primary' : 'text-muted' }}">
                                @if ($log->payment)
                                    {{ $log->payment->status }} 
                                    <i class="bi bi-check-circle text-success" title="Payment successful"></i>
                                @else
                                    Unknown Payment
                                @endif
                            </h4>
                            <div class="timeline-body">
                                <p>{{ $log->initiator ? 'Initiated by: ' . $log->initiator->name : '' }}</p>
                                <p>{{ $log->approver ? 'Approved by: ' . $log->approver->name : '' }}</p>
                                <p>{{ $log->payer ? 'Paid by: ' . $log->payer->name : '' }}</p>
                                <p>{{ $log->rejector ? 'Rejected by: ' . $log->rejector->name : '' }}</p>
                                <button class="btn btn-sm btn-outline-secondary" 
                                        type="button" 
                                        data-bs-target="#details-{{ $index }}" 
                                        data-bs-toggle="collapse">Show Details ▼</button>
                                <div class="collapse border mt-2" id="details-{{ $index }}">
                                    <div class="p-2 text-monospace">
                                        <div><strong>Timestamp:</strong> {{ $log->created_at }}</div>
                                        <div><strong>Details:</strong> Additional information about this log entry.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else <!-- Odd index for right alignment -->
                <div class="col-sm py-2">
                    <div class="card radius-15 {{ $log->payment ? 'border-primary shadow' : '' }} transition">
                        <div class="card-body">
                            <div class="float-end text-muted small">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                            <h4 class="card-title {{ $log->payment ? 'text-primary' : 'text-muted' }}">
                                @if ($log->payment)
                                    {{ $log->payment->status }} 
                                    <i class="bi bi-check-circle text-success" title="Payment successful"></i>
                                @else
                                    Unknown Payment
                                @endif
                            </h4>
                            <div class="timeline-body">
                                <p>{{ $log->initiator ? 'Initiated by: ' . $log->initiator->name : '' }}</p>
                                <p>{{ $log->approver ? 'Approved by: ' . $log->approver->name : '' }}</p>
                                <p>{{ $log->payer ? 'Paid by: ' . $log->payer->name : '' }}</p>
                                <p>{{ $log->rejector ? 'Rejected by: ' . $log->rejector->name : '' }}</p>
                                <button class="btn btn-sm btn-outline-secondary" 
                                        type="button" 
                                        data-bs-target="#details-{{ $index }}" 
                                        data-bs-toggle="collapse">Show Details ▼</button>
                                <div class="collapse border" id="details-{{ $index }}">
                                    <div class="p-2 text-monospace">
                                        <div><strong>Timestamp:</strong> {{ $log->created_at }}</div>
                                        <div><strong>Details:</strong> Additional information about this log entry.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <div class="row h-50">
                        <div class="col border-end">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                    <h5 class="m-2">
                        <span class="badge rounded-pill {{ $log->payment ? 'bg-primary' : 'bg-light border' }}" 
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top" 
                              title="{{ $log->payment ? $log->payment->status : 'Unknown Payment' }}">&nbsp;</span>
                    </h5>
                    <div class="row h-50">
                        <div class="col border-end">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

@endsection

{{-- Add the following styles to your CSS file or within a <style> tag in the blade file --}}
<style>
    .timeline {
        list-style-type: none;
        padding: 0;
    }

    .transition {
        transition: all 0.3s ease;
    }

    .timeline-title {
        font-weight: bold;
    }

    .badge {
        cursor: pointer;
    }
</style>

{{-- Include Bootstrap icons and tooltips --}}
@section('scripts')
<script>
    $(document).ready(function(){
        $('[data-bs-toggle="tooltip"]').tooltip(); // Initialize tooltips
    });
</script>
@endsection
