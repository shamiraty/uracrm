@forelse ($callbacks as $callback)
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between">
            <strong>Callback Received: {{ $callback->created_at->format('d-M-Y h:i A') }}</strong>
            @if (strtolower($callback->final_status) === 'success')
                <span class="badge bg-success">Success</span>
            @else
                <span class="badge bg-danger">Failed</span>
            @endif
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>Description:</strong>
                <span class="text-danger fw-bold">{{ $callback->status_description ?? 'N/A' }}</span>
            </li>
             <li class="list-group-item">
                <strong>Batch ID:</strong>
                <code>{{ $callback->batch_id }}</code>
            </li>
            <li class="list-group-item">
                <strong>NMB Payment Reference:</strong>
                <code>{{ $callback->payment_reference ?? 'N/A' }}</code>
            </li>
        </ul>
    </div>
@empty
    <div class="alert alert-warning text-center">
        No callback history has been recorded for this loan offer yet.
    </div>
@endforelse