@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="page-header-compact mb-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact" style="background: linear-gradient(135deg, #17479E 0%, #2196F3 100%);">
                            <i class="fas fa-clock" style="color: white;"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0" style="color: #003366;">
                            Automated Disbursement Schedule
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Loans are automatically disbursed at 9:00 AM, 12:00 PM, and 3:00 PM daily
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                <button class="btn btn-primary btn-sm" onclick="runManualBatch()">
                    <i class="fas fa-play me-1"></i>Run Manual Batch
                </button>
                <button class="btn btn-info btn-sm" onclick="refreshSchedule()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Today's Schedule Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="schedule-card morning-slot {{ $currentSlot === 'morning' ? 'active' : '' }}">
                <div class="schedule-header">
                    <div class="schedule-time">
                        <i class="fas fa-sun"></i>
                        <span>9:00 AM</span>
                    </div>
                    <div class="schedule-label">Morning Batch</div>
                </div>
                <div class="schedule-body">
                    <div class="schedule-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $morningStats['pending'] ?? 0 }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value text-success">{{ $morningStats['processed'] ?? 0 }}</span>
                            <span class="stat-label">Processed</span>
                        </div>
                    </div>
                    @if($morningStats['last_run'])
                    <div class="last-run">
                        Last run: {{ \Carbon\Carbon::parse($morningStats['last_run'])->format('d/m/Y') }}
                    </div>
                    @endif
                    <div class="schedule-status">
                        @if($morningStats['status'] === 'completed')
                            <span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>
                        @elseif($morningStats['status'] === 'running')
                            <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Running</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-clock"></i> Scheduled</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="schedule-card afternoon-slot {{ $currentSlot === 'afternoon' ? 'active' : '' }}">
                <div class="schedule-header">
                    <div class="schedule-time">
                        <i class="fas fa-sun"></i>
                        <span>12:00 PM</span>
                    </div>
                    <div class="schedule-label">Afternoon Batch</div>
                </div>
                <div class="schedule-body">
                    <div class="schedule-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $afternoonStats['pending'] ?? 0 }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value text-success">{{ $afternoonStats['processed'] ?? 0 }}</span>
                            <span class="stat-label">Processed</span>
                        </div>
                    </div>
                    @if($afternoonStats['last_run'])
                    <div class="last-run">
                        Last run: {{ \Carbon\Carbon::parse($afternoonStats['last_run'])->format('d/m/Y') }}
                    </div>
                    @endif
                    <div class="schedule-status">
                        @if($afternoonStats['status'] === 'completed')
                            <span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>
                        @elseif($afternoonStats['status'] === 'running')
                            <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Running</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-clock"></i> Scheduled</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="schedule-card evening-slot {{ $currentSlot === 'evening' ? 'active' : '' }}">
                <div class="schedule-header">
                    <div class="schedule-time">
                        <i class="fas fa-cloud-sun"></i>
                        <span>3:00 PM</span>
                    </div>
                    <div class="schedule-label">Evening Batch</div>
                </div>
                <div class="schedule-body">
                    <div class="schedule-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $eveningStats['pending'] ?? 0 }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value text-success">{{ $eveningStats['processed'] ?? 0 }}</span>
                            <span class="stat-label">Processed</span>
                        </div>
                    </div>
                    @if($eveningStats['last_run'])
                    <div class="last-run">
                        Last run: {{ \Carbon\Carbon::parse($eveningStats['last_run'])->format('d/m/Y') }}
                    </div>
                    @endif
                    <div class="schedule-status">
                        @if($eveningStats['status'] === 'completed')
                            <span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>
                        @elseif($eveningStats['status'] === 'running')
                            <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Running</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-clock"></i> Scheduled</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Status -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Current Queue Status</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="queue-stat">
                        <div class="queue-number">{{ $queueStats['total_pending'] ?? 0 }}</div>
                        <div class="queue-label">Total in Queue</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="queue-stat">
                        <div class="queue-number text-info">{{ $queueStats['next_batch'] ?? 0 }}</div>
                        <div class="queue-label">Next Batch</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="queue-stat">
                        <div class="queue-number text-success">{{ $queueStats['processed_today'] ?? 0 }}</div>
                        <div class="queue-label">Processed Today</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="queue-stat">
                        <div class="queue-number text-warning">TZS {{ number_format($queueStats['total_amount'] ?? 0, 2) }}</div>
                        <div class="queue-label">Queue Value</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Batch History -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Batch History</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Processed</th>
                            <th>Success</th>
                            <th>Failed</th>
                            <th>Amount (TZS)</th>
                            <th>Batch IDs</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batchHistory as $batch)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($batch->batch_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($batch->time_slot === 'morning')
                                    <span class="badge bg-info">9:00 AM</span>
                                @elseif($batch->time_slot === 'afternoon')
                                    <span class="badge bg-primary">12:00 PM</span>
                                @else
                                    <span class="badge bg-secondary">3:00 PM</span>
                                @endif
                            </td>
                            <td>{{ $batch->total_processed }}</td>
                            <td><span class="text-success">{{ $batch->successful }}</span></td>
                            <td><span class="text-danger">{{ $batch->failed }}</span></td>
                            <td>{{ number_format($batch->total_amount ?? 0, 2) }}</td>
                            <td>
                                @if($batch->batch_ids)
                                    <small>{{ implode(', ', array_slice(json_decode($batch->batch_ids, true) ?? [], 0, 2)) }}...</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($batch->failed > 0)
                                    <span class="badge bg-warning">Partial</span>
                                @elseif($batch->successful > 0)
                                    <span class="badge bg-success">Success</span>
                                @else
                                    <span class="badge bg-secondary">No Loans</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">
                                No batch history available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Manual Batch Modal -->
<div class="modal fade" id="manualBatchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-play me-2"></i>Run Manual Batch</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Time Slot</label>
                    <select class="form-select" id="manualTimeSlot">
                        <option value="morning">Morning (9:00 AM)</option>
                        <option value="afternoon">Afternoon (12:00 PM)</option>
                        <option value="evening">Evening (3:00 PM)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Batch Limit</label>
                    <input type="number" class="form-control" id="manualBatchLimit" value="100" min="1" max="500">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="manualDryRun">
                    <label class="form-check-label" for="manualDryRun">
                        Dry Run (simulate without processing)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeManualBatch()">
                    <i class="fas fa-play me-1"></i>Run Batch
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.schedule-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.schedule-card.active {
    border-color: #17479E;
    box-shadow: 0 4px 15px rgba(23,71,158,0.2);
}

.schedule-card.morning-slot .schedule-header {
    background: linear-gradient(135deg, #FFA726 0%, #FFB74D 100%);
}

.schedule-card.afternoon-slot .schedule-header {
    background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
}

.schedule-card.evening-slot .schedule-header {
    background: linear-gradient(135deg, #7E57C2 0%, #9575CD 100%);
}

.schedule-header {
    color: white;
    padding: 15px;
    border-radius: 10px;
    margin: -20px -20px 20px -20px;
}

.schedule-time {
    font-size: 1.5rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.schedule-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-top: 5px;
}

.schedule-stats {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: bold;
    display: block;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    text-transform: uppercase;
}

.schedule-status {
    text-align: center;
    margin-top: 15px;
}

.last-run {
    text-align: center;
    font-size: 0.85rem;
    color: #6c757d;
    margin: 10px 0;
}

.queue-stat {
    text-align: center;
    padding: 20px;
}

.queue-number {
    font-size: 2.5rem;
    font-weight: bold;
    line-height: 1;
}

.queue-label {
    font-size: 0.9rem;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 10px;
}
</style>
@endpush

@push('scripts')
<script>
function runManualBatch() {
    $('#manualBatchModal').modal('show');
}

function executeManualBatch() {
    const timeSlot = document.getElementById('manualTimeSlot').value;
    const limit = document.getElementById('manualBatchLimit').value;
    const dryRun = document.getElementById('manualDryRun').checked;
    
    Swal.fire({
        title: 'Processing Batch',
        text: 'Running ' + timeSlot + ' batch...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("disbursements.manual") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            time_slot: timeSlot,
            limit: limit,
            dry_run: dryRun
        })
    })
    .then(response => response.json())
    .then(data => {
        $('#manualBatchModal').modal('hide');
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Batch Completed',
                html: `
                    <div>Processed: ${data.total}</div>
                    <div>Success: ${data.success}</div>
                    <div>Failed: ${data.failed}</div>
                `,
                confirmButtonColor: '#17479E'
            });
            
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Batch Failed',
                text: data.message || 'An error occurred',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to process batch',
            confirmButtonColor: '#dc3545'
        });
    });
}

function refreshSchedule() {
    location.reload();
}

// Auto-refresh every 60 seconds
setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 60000);
</script>
@endpush