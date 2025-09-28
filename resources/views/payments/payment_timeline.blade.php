{{-- resources/views/payments/payment_timeline.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 12px 35px rgba(23, 71, 158, 0.25);
    }

    .timeline-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .timeline-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        border-left: 4px solid;
        position: relative;
        overflow: hidden;
    }

    .timeline-card.success {
        border-left-color: var(--ura-success);
    }

    .timeline-card.pending {
        border-left-color: var(--ura-warning);
    }

    .timeline-card.failed {
        border-left-color: var(--ura-danger);
    }

    .timeline-card.info {
        border-left-color: var(--ura-accent);
    }

    .timeline-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--ura-shadow-hover);
    }

    .timeline-container {
        position: relative;
        padding: 2rem 0;
    }

    .timeline-connector {
        position: absolute;
        left: 50px;
        top: 0;
        width: 4px;
        height: 100%;
        background: var(--ura-gradient);
        z-index: 1;
        border-radius: 2px;
    }

    .timeline-badge {
        position: absolute;
        left: 32px;
        width: 36px;
        height: 36px;
        background: var(--ura-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
    }

    .timeline-badge.success {
        background: linear-gradient(135deg, #10dc60 0%, #0bb24c 100%);
    }

    .timeline-badge.pending {
        background: linear-gradient(135deg, #ffce00 0%, #e6b800 100%);
    }

    .timeline-badge.failed {
        background: linear-gradient(135deg, #f04141 0%, #d32f2f 100%);
    }

    .timeline-badge i {
        color: white;
        font-size: 1.2rem;
    }

    .timeline-content {
        padding: 1.5rem;
        margin-left: 90px;
        position: relative;
    }

    .timeline-date {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .timeline-title {
        color: var(--ura-primary);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .timeline-body {
        color: #6c757d;
        line-height: 1.6;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.2) 0%, rgba(16, 220, 96, 0.1) 100%);
        color: var(--ura-success);
        border: 1px solid var(--ura-success);
    }

    .status-pending {
        background: linear-gradient(135deg, rgba(255, 206, 0, 0.2) 0%, rgba(255, 206, 0, 0.1) 100%);
        color: var(--ura-warning);
        border: 1px solid var(--ura-warning);
    }

    .status-failed {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.2) 0%, rgba(240, 65, 65, 0.1) 100%);
        color: var(--ura-danger);
        border: 1px solid var(--ura-danger);
    }

    .status-info {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(0, 188, 212, 0.1) 100%);
        color: var(--ura-accent);
        border: 1px solid var(--ura-accent);
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .modern-btn-outline {
        background: transparent;
        color: var(--ura-primary);
        border: 2px solid var(--ura-primary);
    }

    .modern-btn-outline:hover {
        background: var(--ura-primary);
        color: white;
        transform: translateY(-1px);
    }

    .details-section {
        background: var(--ura-gradient-light);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-label {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        color: #495057;
        font-weight: 500;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    @media (max-width: 768px) {
        .timeline-content {
            margin-left: 70px;
        }

        .timeline-badge {
            left: 20px;
            width: 30px;
            height: 30px;
        }

        .timeline-connector {
            left: 32px;
            width: 3px;
        }

        .timeline-card {
            padding: 1rem;
        }

        .timeline-title h6 {
            font-size: 1rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="timeline-header">
        <h1 class="header-title">
            <i class="bx bx-time-five"></i>
            Payment Timeline
        </h1>
        <p class="header-subtitle">
            Track payment progression with detailed chronological history
        </p>
    </div>

    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-history"></i>
                Payment Activity Log
                <span class="badge bg-primary ms-2">{{ count($payment->logs) }} events</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="timeline-container">
                <div class="timeline-connector"></div>
                @foreach ($payment->logs as $index => $log)
                    @php
                        $statusClass = 'info';
                        $icon = 'bx-info-circle';
                        if ($log->payment) {
                            if (strpos(strtolower($log->payment->status), 'success') !== false || strpos(strtolower($log->payment->status), 'paid') !== false) {
                                $statusClass = 'success';
                                $icon = 'bx-check';
                            } elseif (strpos(strtolower($log->payment->status), 'pending') !== false) {
                                $statusClass = 'pending';
                                $icon = 'bx-time';
                            } elseif (strpos(strtolower($log->payment->status), 'failed') !== false || strpos(strtolower($log->payment->status), 'rejected') !== false) {
                                $statusClass = 'failed';
                                $icon = 'bx-x';
                            }
                        }
                    @endphp
                    <div class="timeline-item" style="margin-bottom: {{ $index < count($payment->logs) - 1 ? '3rem' : '0' }};">
                        <!-- Timeline Badge -->
                        <div class="timeline-badge {{ $statusClass }}" style="top: 1.5rem;">
                            <i class="bx {{ $icon }}"></i>
                        </div>

                        <!-- Timeline Content -->
                        <div class="timeline-content">
                            <div class="timeline-card {{ $statusClass }}">
                                <div class="timeline-date">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ $log->created_at->format('M j, Y \a\t g:i A') }}
                                </div>

                                <div class="timeline-title">
                                    @if ($log->payment)
                                        <h6 class="mb-2">{{ ucfirst($log->payment->status) }} Payment</h6>
                                        @if (strpos(strtolower($log->payment->status), 'success') !== false || strpos(strtolower($log->payment->status), 'paid') !== false)
                                            <span class="status-badge status-success">
                                                <i class="bx bx-check"></i> Completed Successfully
                                            </span>
                                        @elseif (strpos(strtolower($log->payment->status), 'pending') !== false)
                                            <span class="status-badge status-pending">
                                                <i class="bx bx-time"></i> Processing
                                            </span>
                                        @elseif (strpos(strtolower($log->payment->status), 'failed') !== false)
                                            <span class="status-badge status-failed">
                                                <i class="bx bx-x"></i> Payment Failed
                                            </span>
                                        @else
                                            <span class="status-badge status-info">
                                                <i class="bx bx-info-circle"></i> {{ $log->payment->status }}
                                            </span>
                                        @endif
                                    @else
                                        <h6 class="mb-2">Payment Log Entry</h6>
                                        <span class="status-badge status-info">
                                            <i class="bx bx-question-mark"></i> Status Unknown
                                        </span>
                                    @endif
                                </div>

                            <div class="timeline-body">
                                <div class="details-grid">
                                    @if($log->initiator)
                                        <div class="detail-item">
                                            <div class="detail-label">Initiated By</div>
                                            <div class="detail-value">{{ $log->initiator->name }}</div>
                                        </div>
                                    @endif

                                    @if($log->approver)
                                        <div class="detail-item">
                                            <div class="detail-label">Approved By</div>
                                            <div class="detail-value">{{ $log->approver->name }}</div>
                                        </div>
                                    @endif

                                    @if($log->payer)
                                        <div class="detail-item">
                                            <div class="detail-label">Paid By</div>
                                            <div class="detail-value">{{ $log->payer->name }}</div>
                                        </div>
                                    @endif

                                    @if($log->rejector)
                                        <div class="detail-item">
                                            <div class="detail-label">Rejected By</div>
                                            <div class="detail-value">{{ $log->rejector->name }}</div>
                                        </div>
                                    @endif
                                </div>

                                <button class="modern-btn modern-btn-outline mt-3"
                                        type="button"
                                        data-bs-target="#details-{{ $index }}"
                                        data-bs-toggle="collapse">
                                    <i class="bx bx-detail"></i>
                                    Show Details
                                </button>

                                <div class="collapse" id="details-{{ $index }}">
                                    <div class="details-section">
                                        <div class="details-grid">
                                            <div class="detail-item">
                                                <div class="detail-label">Full Timestamp</div>
                                                <div class="detail-value">{{ $log->created_at->format('Y-m-d H:i:s T') }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Event ID</div>
                                                <div class="detail-value">#{{ $log->id ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Payment Reference</div>
                                                <div class="detail-value">{{ $log->payment ? $log->payment->reference ?? 'N/A' : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Status Code</div>
                                                <div class="detail-value">{{ $log->payment ? $log->payment->status : 'Unknown' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
