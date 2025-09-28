@extends('layouts.app')

@section('content')

<!-- Loan Reports Page -->
<div class="loan-reports-container">
    <!-- Page Header -->
    <div class="page-header-ura mb-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="bx bx-bar-chart-alt-2 gradient-icon-chart me-2"></i>
                        <span class="gradient-text-ura">Loan Reports</span>
                    </h1>
                    <p class="page-description">Comprehensive loan portfolio analytics and insights</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="header-actions">
                    <button class="btn btn-ura-secondary me-2" onclick="printReport()">
                        <i class="bx bx-printer me-1"></i>Print
                    </button>
                    <button class="btn btn-ura-primary" onclick="exportReport()">
                        <i class="bx bx-download me-1"></i>Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card gradient-primary">
                <div class="metric-icon">
                    <i class="bx bx-file"></i>
                </div>
                <div class="metric-content">
                    <h3>{{ $data['total_loans'] ?? 0 }}</h3>
                    <p>Total Applications</p>
                    <div class="metric-footer">
                        <small>All time</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="metric-card gradient-success">
                <div class="metric-icon">
                    <i class="bx bx-wallet"></i>
                </div>
                <div class="metric-content">
                    <h3>TZS {{ number_format($data['total_portfolio'] ?? 0) }}</h3>
                    <p>Portfolio Value</p>
                    <div class="metric-footer">
                        <small>Total disbursed</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="metric-card gradient-info">
                <div class="metric-icon">
                    <i class="bx bx-trending-up"></i>
                </div>
                <div class="metric-content">
                    <h3>{{ number_format($data['approval_rate'] ?? 0, 1) }}%</h3>
                    <p>Approval Rate</p>
                    <div class="metric-footer">
                        <small>Success ratio</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="metric-card gradient-warning">
                <div class="metric-icon">
                    <i class="bx bx-calendar-check"></i>
                </div>
                <div class="metric-content">
                    <h3>TZS {{ number_format($data['monthly_disbursements'] ?? 0) }}</h3>
                    <p>This Month</p>
                    <div class="metric-footer">
                        <small>Disbursements</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Loan Status Distribution -->
        <div class="col-lg-6">
            <div class="card shadow-ura">
                <div class="card-header bg-gradient-ura">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-pie-chart-alt-2 me-2"></i>Loan Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="col-lg-6">
            <div class="card shadow-ura">
                <div class="card-header bg-gradient-ura">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-line-chart me-2"></i>Monthly Loan Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-ura">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bx bx-check-circle me-2"></i>Approval Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-row">
                        <span class="stat-label">Approved Loans</span>
                        <span class="stat-value text-success">{{ $data['approved_loans'] ?? 0 }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Rejected Loans</span>
                        <span class="stat-value text-danger">{{ $data['rejected_loans'] ?? 0 }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Pending Review</span>
                        <span class="stat-value text-warning">{{ $data['pending_loans'] ?? 0 }}</span>
                    </div>
                    <hr>
                    <div class="progress-section">
                        <label>Approval Rate</label>
                        <div class="progress">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $data['approval_rate'] ?? 0 }}%">
                                {{ number_format($data['approval_rate'] ?? 0, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-ura">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bx bx-money me-2"></i>Disbursement Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-row">
                        <span class="stat-label">Disbursed Loans</span>
                        <span class="stat-value text-info">{{ $data['disbursed_loans'] ?? 0 }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Pending Disbursement</span>
                        <span class="stat-value text-warning">
                            {{ ($data['approved_loans'] ?? 0) - ($data['disbursed_loans'] ?? 0) }}
                        </span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Monthly Volume</span>
                        <span class="stat-value text-primary">
                            TZS {{ number_format($data['monthly_disbursements'] ?? 0) }}
                        </span>
                    </div>
                    <hr>
                    <div class="progress-section">
                        <label>Disbursement Progress</label>
                        <div class="progress">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $data['approved_loans'] ? ($data['disbursed_loans'] / $data['approved_loans'] * 100) : 0 }}%">
                                {{ $data['approved_loans'] ? number_format($data['disbursed_loans'] / $data['approved_loans'] * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-ura">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bx bx-calculator me-2"></i>Portfolio Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-row">
                        <span class="stat-label">Total Portfolio</span>
                        <span class="stat-value text-primary">
                            TZS {{ number_format($data['total_portfolio'] ?? 0) }}
                        </span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Average Loan Size</span>
                        <span class="stat-value text-secondary">
                            TZS {{ $data['total_loans'] ? number_format(($data['total_portfolio'] ?? 0) / $data['total_loans']) : 0 }}
                        </span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Active Loans</span>
                        <span class="stat-value text-success">{{ $data['disbursed_loans'] ?? 0 }}</span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetailedReport()">
                            <i class="bx bx-detail me-1"></i>View Detailed Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Actions -->
    <div class="card shadow-ura">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="bx bx-file-find me-2"></i>Generate Custom Reports
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <button class="report-action-btn w-100" onclick="generateReport('monthly')">
                        <i class="bx bx-calendar"></i>
                        <span>Monthly Report</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="report-action-btn w-100" onclick="generateReport('quarterly')">
                        <i class="bx bx-calendar-week"></i>
                        <span>Quarterly Report</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="report-action-btn w-100" onclick="generateReport('annual')">
                        <i class="bx bx-calendar-event"></i>
                        <span>Annual Report</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="report-action-btn w-100" onclick="generateReport('custom')">
                        <i class="bx bx-customize"></i>
                        <span>Custom Period</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* URA SACCOS Brand Styles */
.gradient-text-ura {
    background: linear-gradient(135deg, #17479E, #00BCD4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.gradient-icon-chart {
    background: linear-gradient(135deg, #764ba2, #f093fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.page-header-ura {
    padding: 20px 0;
    border-bottom: 2px solid #f0f2f5;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
}

/* Metric Cards */
.metric-card {
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

.metric-card:hover {
    transform: translateY(-5px);
}

.metric-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.gradient-primary {
    background: linear-gradient(135deg, #17479E, #2558B3);
}

.gradient-success {
    background: linear-gradient(135deg, #10dc60, #4facfe);
}

.gradient-info {
    background: linear-gradient(135deg, #00BCD4, #4DD0E1);
}

.gradient-warning {
    background: linear-gradient(135deg, #FFA500, #FFD700);
}

.metric-icon {
    font-size: 2.5rem;
    margin-right: 20px;
    opacity: 0.8;
}

.metric-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.metric-content p {
    font-size: 0.95rem;
    margin: 0;
    opacity: 0.9;
}

.metric-footer {
    margin-top: 10px;
    opacity: 0.8;
}

/* Statistics Rows */
.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f2f5;
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.stat-value {
    font-weight: 600;
    font-size: 1rem;
}

/* Progress Section */
.progress-section {
    margin-top: 15px;
}

.progress-section label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.progress {
    height: 25px;
    border-radius: 10px;
    background-color: #f0f2f5;
}

.progress-bar {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 10px;
}

/* Report Action Buttons */
.report-action-btn {
    padding: 20px;
    border: 2px solid #e0e6ed;
    background: white;
    border-radius: 10px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.report-action-btn:hover {
    background: linear-gradient(135deg, #17479E, #00BCD4);
    color: white;
    transform: translateY(-3px);
}

.report-action-btn i {
    font-size: 2rem;
}

/* Card Styles */
.shadow-ura {
    box-shadow: 0 5px 20px rgba(23, 71, 158, 0.1);
}

.bg-gradient-ura {
    background: linear-gradient(135deg, #17479E, #2558B3);
    color: white;
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

.btn-ura-secondary {
    background: white;
    color: #17479E;
    border: 2px solid #17479E;
    transition: all 0.3s ease;
}

.btn-ura-secondary:hover {
    background: #17479E;
    color: white;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Distribution Chart
const ctx1 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Approved', 'Rejected', 'Pending', 'Disbursed'],
        datasets: [{
            data: [
                {{ $data['approved_loans'] ?? 0 }},
                {{ $data['rejected_loans'] ?? 0 }},
                {{ $data['pending_loans'] ?? 0 }},
                {{ $data['disbursed_loans'] ?? 0 }}
            ],
            backgroundColor: [
                '#10dc60',
                '#f04141',
                '#FFA500',
                '#00BCD4'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Trends Chart
const ctx2 = document.getElementById('trendsChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Applications',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: '#17479E',
            backgroundColor: 'rgba(23, 71, 158, 0.1)',
            tension: 0.4
        }, {
            label: 'Approvals',
            data: [10, 15, 13, 20, 18, 25],
            borderColor: '#00BCD4',
            backgroundColor: 'rgba(0, 188, 212, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function exportReport() {
    window.location.href = '{{ route("loan-offers.export") }}';
}

function printReport() {
    window.print();
}

function generateReport(type) {
    alert('Generating ' + type + ' report...');
}

function viewDetailedReport() {
    alert('Opening detailed portfolio report...');
}
</script>

@endsection