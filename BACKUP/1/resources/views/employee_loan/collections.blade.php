@extends('layouts.app')

@section('content')

<!-- Collections Page -->
<div class="collections-container">
    <!-- Page Header -->
    <div class="page-header-ura mb-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="bx bx-dollar-circle gradient-icon-dollar me-2"></i>
                        <span class="gradient-text-gold">Loan Collections</span>
                    </h1>
                    <p class="page-description">Track loan repayments and collection performance</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="header-actions">
                    <button class="btn btn-ura-warning me-2" onclick="sendReminders()">
                        <i class="bx bx-bell me-1"></i>Send Reminders
                    </button>
                    <button class="btn btn-ura-primary" onclick="exportCollections()">
                        <i class="bx bx-download me-1"></i>Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card gradient-gold">
                <div class="stat-icon">
                    <i class="bx bx-target-lock"></i>
                </div>
                <div class="stat-content">
                    <h3>TZS {{ number_format($data['total_expected'] ?? 0) }}</h3>
                    <p>Total Expected</p>
                    <div class="stat-footer">
                        <span class="badge bg-white text-dark">All active loans</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card gradient-green">
                <div class="stat-icon">
                    <i class="bx bx-check-double"></i>
                </div>
                <div class="stat-content">
                    <h3>TZS {{ number_format($data['total_collected'] ?? 0) }}</h3>
                    <p>Total Collected</p>
                    <div class="stat-footer">
                        <span class="badge bg-white text-dark">Received payments</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card gradient-blue">
                <div class="stat-icon">
                    <i class="bx bx-trending-up"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($data['collection_rate'] ?? 0, 1) }}%</h3>
                    <p>Collection Rate</p>
                    <div class="stat-footer">
                        <span class="badge bg-white text-dark">Performance</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card gradient-red">
                <div class="stat-icon">
                    <i class="bx bx-error-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['overdue_loans'] ?? 0 }}</h3>
                    <p>Overdue Loans</p>
                    <div class="stat-footer">
                        <span class="badge bg-white text-dark">Need attention</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Performance Chart -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-ura">
                <div class="card-header bg-gradient-gold">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-line-chart me-2"></i>Collection Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="collectionTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-ura">
                <div class="card-header bg-gradient-gold">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-pie-chart-alt-2 me-2"></i>Payment Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentStatusChart" height="250"></canvas>
                    <div class="mt-3">
                        <div class="legend-item">
                            <span class="legend-dot bg-success"></span>
                            <span>On Track (65%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot bg-warning"></span>
                            <span>Late (20%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot bg-danger"></span>
                            <span>Defaulted (15%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Loans for Collection -->
    <div class="card shadow-ura">
        <div class="card-header bg-gradient-gold d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bx bx-list-check me-2"></i>Active Loans for Collection
            </h5>
            <div class="header-filters">
                <select class="form-select form-select-sm" onchange="filterCollections(this.value)">
                    <option value="">All Status</option>
                    <option value="current">Current</option>
                    <option value="overdue">Overdue</option>
                    <option value="defaulted">Defaulted</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Loan #</th>
                            <th>Employee</th>
                            <th>Outstanding</th>
                            <th>Monthly Payment</th>
                            <th>Last Payment</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loanOffers as $loan)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input loan-select" value="{{ $loan->id }}">
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ $loan->application_number }}</span>
                            </td>
                            <td>
                                <div class="employee-info">
                                    <div class="fw-semibold">{{ $loan->employee_name }}</div>
                                    <small class="text-muted">{{ $loan->employee_number }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="amount-badge text-danger">
                                    TZS {{ number_format($loan->total_amount_to_pay ?? 0) }}
                                </span>
                            </td>
                            <td>
                                <span class="amount-badge">
                                    TZS {{ number_format(($loan->total_amount_to_pay ?? 0) / ($loan->duration ?: 1)) }}
                                </span>
                            </td>
                            <td>
                                <span class="date-text">
                                    {{ $loan->updated_at->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $status = rand(0, 2);
                                @endphp
                                @if($status == 0)
                                    <span class="badge bg-success">
                                        <i class="bx bx-check-circle me-1"></i>Current
                                    </span>
                                @elseif($status == 1)
                                    <span class="badge bg-warning">
                                        <i class="bx bx-time-five me-1"></i>Late
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bx bx-x-circle me-1"></i>Overdue
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info me-1" 
                                            onclick="viewPaymentHistory({{ $loan->id }})"
                                            title="Payment History">
                                        <i class="bx bx-history"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1" 
                                            onclick="sendReminder({{ $loan->id }})"
                                            title="Send Reminder">
                                        <i class="bx bx-bell"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" 
                                            onclick="recordPayment({{ $loan->id }})"
                                            title="Record Payment">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bx bx-folder-open empty-icon"></i>
                                    <p class="empty-text">No active loans for collection</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($loanOffers->hasPages())
            <div class="mt-4">
                {{ $loanOffers->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="selected-count">0 loans selected</span>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-warning me-2" onclick="bulkSendReminders()">
                        <i class="bx bx-bell me-1"></i>Send Reminders
                    </button>
                    <button class="btn btn-sm btn-success" onclick="bulkRecordPayments()">
                        <i class="bx bx-check me-1"></i>Record Payments
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* URA SACCOS Brand Styles */
.gradient-text-gold {
    background: linear-gradient(135deg, #FFD700, #FFA500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.gradient-icon-dollar {
    background: linear-gradient(135deg, #10dc60, #FFD700);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.page-header-ura {
    padding: 20px 0;
    border-bottom: 2px solid #f0f2f5;
}

/* Statistics Cards */
.stat-card {
    padding: 25px;
    border-radius: 15px;
    color: white;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.gradient-gold {
    background: linear-gradient(135deg, #FFD700, #FFA500);
}

.gradient-green {
    background: linear-gradient(135deg, #10dc60, #4facfe);
}

.gradient-blue {
    background: linear-gradient(135deg, #17479E, #00BCD4);
}

.gradient-red {
    background: linear-gradient(135deg, #f04141, #ff6b6b);
}

.stat-icon {
    font-size: 2.5rem;
    margin-right: 20px;
    opacity: 0.8;
}

.stat-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-footer {
    margin-top: 10px;
}

/* Card Styles */
.shadow-ura {
    box-shadow: 0 5px 20px rgba(23, 71, 158, 0.1);
}

.bg-gradient-gold {
    background: linear-gradient(135deg, #FFD700, #FFA500);
    color: white;
}

/* Legend Items */
.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 10px;
}

/* Table Styles */
.employee-info {
    line-height: 1.3;
}

.amount-badge {
    font-weight: 600;
    font-size: 0.95rem;
}

.date-text {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Action Buttons */
.action-buttons .btn {
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

/* Bulk Actions Bar */
.bulk-actions-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 15px 0;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
    z-index: 1000;
}

.selected-count {
    font-weight: 600;
    color: #17479E;
}

/* Empty State */
.empty-state {
    padding: 40px;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
}

/* Buttons */
.btn-ura-primary {
    background: linear-gradient(135deg, #17479E, #00BCD4);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 71, 158, 0.3);
    color: white;
}

.btn-ura-warning {
    background: linear-gradient(135deg, #FFA500, #FFD700);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-ura-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
    color: white;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Collection Trends Chart
const ctx1 = document.getElementById('collectionTrendsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Expected',
            data: [450, 520, 480, 590, 650, 580],
            borderColor: '#FFD700',
            backgroundColor: 'rgba(255, 215, 0, 0.1)',
            tension: 0.4
        }, {
            label: 'Collected',
            data: [420, 480, 460, 550, 600, 560],
            borderColor: '#10dc60',
            backgroundColor: 'rgba(16, 220, 96, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'TZS ' + value + 'M';
                    }
                }
            }
        }
    }
});

// Payment Status Chart
const ctx2 = document.getElementById('paymentStatusChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['On Track', 'Late', 'Defaulted'],
        datasets: [{
            data: [65, 20, 15],
            backgroundColor: ['#10dc60', '#FFA500', '#f04141'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Checkbox handling
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.loan-select');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

document.querySelectorAll('.loan-select').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const selected = document.querySelectorAll('.loan-select:checked').length;
    const bar = document.getElementById('bulkActionsBar');
    
    if (selected > 0) {
        bar.style.display = 'block';
        bar.querySelector('.selected-count').textContent = `${selected} loan(s) selected`;
    } else {
        bar.style.display = 'none';
    }
}

function exportCollections() {
    window.location.href = '{{ route("loan-offers.export") }}?type=collections';
}

function sendReminders() {
    alert('Sending payment reminders to all overdue loans...');
}

function sendReminder(loanId) {
    alert('Sending reminder for loan #' + loanId);
}

function recordPayment(loanId) {
    alert('Opening payment recording form for loan #' + loanId);
}

function viewPaymentHistory(loanId) {
    alert('Opening payment history for loan #' + loanId);
}

function filterCollections(status) {
    window.location.href = '{{ route("loans.collections") }}?status=' + status;
}

function bulkSendReminders() {
    const selected = document.querySelectorAll('.loan-select:checked');
    alert('Sending reminders to ' + selected.length + ' selected loans');
}

function bulkRecordPayments() {
    const selected = document.querySelectorAll('.loan-select:checked');
    alert('Opening bulk payment recording for ' + selected.length + ' loans');
}
</script>

@endsection