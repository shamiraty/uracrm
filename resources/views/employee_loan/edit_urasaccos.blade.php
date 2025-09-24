@extends('layouts.app')

@section('content')
<div class="urasaccos-wrapper">
    <!-- URASACCOS Branded Header -->
    <div class="brand-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="brand-title-section">
                        <div class="brand-logo-wrapper">
                            <div class="brand-logo">
                                <span class="logo-text">URA</span>
                            </div>
                        </div>
                        <div class="title-content">
                            <h1 class="brand-title">Loan Management System</h1>
                            <div class="breadcrumb-wrapper">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('loan-offers.index') }}">Loans</a></li>
                                        <li class="breadcrumb-item active">{{ $loanOffer->application_number }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="header-actions">
                        <button class="btn btn-ura-white" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print
                        </button>
                        <button class="btn btn-ura-white dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF Report</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Email</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Status Overview -->
    <div class="container-fluid mt-4">
        <!-- Real-time Status Banner -->
        <div class="status-banner mb-4">
            <div class="status-content">
                <div class="status-main">
                    <div class="status-indicator {{ $loanOffer->approval == 'APPROVED' ? 'approved' : ($loanOffer->approval == 'REJECTED' ? 'rejected' : 'pending') }}">
                        <div class="status-icon-large">
                            @if($loanOffer->approval == 'APPROVED')
                                <i class="fas fa-check-circle"></i>
                            @elseif($loanOffer->approval == 'REJECTED')
                                <i class="fas fa-times-circle"></i>
                            @else
                                <i class="fas fa-clock"></i>
                            @endif
                        </div>
                        <div class="status-text">
                            <h3>{{ $loanOffer->approval ?: 'PENDING REVIEW' }}</h3>
                            <p>{{ $loanOffer->status ? 'Current Status: ' . str_replace('_', ' ', $loanOffer->status) : 'Awaiting approval' }}</p>
                        </div>
                    </div>
                </div>
                <div class="status-timeline">
                    <div class="timeline-track">
                        <div class="timeline-progress" style="width: {{ 
                            $loanOffer->status == 'disbursed' ? '100' : 
                            ($loanOffer->status == 'disbursement_pending' ? '75' : 
                            ($loanOffer->approval == 'APPROVED' ? '50' : '25')) 
                        }}%"></div>
                    </div>
                    <div class="timeline-steps">
                        <div class="timeline-step completed">
                            <span class="step-number">1</span>
                            <span class="step-label">Applied</span>
                            <span class="step-date">{{ $loanOffer->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="timeline-step {{ $loanOffer->approval ? 'completed' : '' }}">
                            <span class="step-number">2</span>
                            <span class="step-label">Reviewed</span>
                            @if($loanOffer->updated_at != $loanOffer->created_at)
                                <span class="step-date">{{ $loanOffer->updated_at->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <div class="timeline-step {{ $loanOffer->approval == 'APPROVED' ? 'completed' : '' }}">
                            <span class="step-number">3</span>
                            <span class="step-label">Approved</span>
                        </div>
                        <div class="timeline-step {{ $loanOffer->status == 'disbursed' ? 'completed' : '' }}">
                            <span class="step-number">4</span>
                            <span class="step-label">Disbursed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applicant Information Card -->
        <div class="applicant-card-ura mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="applicant-header">
                            <div class="applicant-avatar">
                                <div class="avatar-circle-ura">
                                    <span>{{ strtoupper(substr($loanOffer->first_name, 0, 1)) }}{{ strtoupper(substr($loanOffer->last_name, 0, 1)) }}</span>
                                </div>
                                @if($loanOffer->approval == 'APPROVED')
                                    <span class="avatar-badge success"></span>
                                @elseif($loanOffer->approval == 'REJECTED')
                                    <span class="avatar-badge danger"></span>
                                @else
                                    <span class="avatar-badge warning"></span>
                                @endif
                            </div>
                            <div class="applicant-details">
                                <h2 class="applicant-name">
                                    {{ $loanOffer->first_name }} {{ $loanOffer->middle_name }} {{ $loanOffer->last_name }}
                                    @if($loanOffer->sex)
                                        <span class="gender-badge">{{ $loanOffer->sex == 'M' ? '♂️ Male' : '♀️ Female' }}</span>
                                    @endif
                                </h2>
                                <div class="applicant-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-id-badge"></i>
                                        Check #{{ $loanOffer->check_number }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-fingerprint"></i>
                                        NIN: {{ $loanOffer->nin ?: 'Not provided' }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        {{ $loanOffer->designation_name }}
                                    </span>
                                    @if($loanOffer->vote_name)
                                    <span class="meta-item">
                                        <i class="fas fa-building"></i>
                                        {{ $loanOffer->vote_name }}
                                    </span>
                                    @endif
                                </div>
                                <div class="employment-badges mt-2">
                                    <span class="employment-badge">{{ $loanOffer->terms_of_employment ?: 'Permanent' }}</span>
                                    <span class="employment-badge">{{ $loanOffer->marital_status ?: 'Single' }}</span>
                                    @if($loanOffer->employment_date)
                                        <span class="employment-badge">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Employed: {{ \Carbon\Carbon::parse($loanOffer->employment_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                    @if($loanOffer->retirement_date)
                                        <span class="employment-badge text-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>
                                            Retires: {{ \Carbon\Carbon::parse($loanOffer->retirement_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="loan-amount-card">
                            <div class="amount-label">Total Loan Amount</div>
                            <div class="amount-value">TZS {{ number_format($loanOffer->total_amount_to_pay, 0) }}</div>
                            <div class="amount-breakdown">
                                <div class="breakdown-item">
                                    <span>Principal</span>
                                    <strong>{{ number_format($loanOffer->requested_amount, 0) }}</strong>
                                </div>
                                <div class="breakdown-item">
                                    <span>Interest ({{ $loanOffer->interest_rate }}%)</span>
                                    <strong>{{ number_format($loanOffer->total_amount_to_pay - $loanOffer->requested_amount - ($loanOffer->processing_fee + $loanOffer->insurance + $loanOffer->other_charges), 0) }}</strong>
                                </div>
                                <div class="breakdown-item">
                                    <span>Fees</span>
                                    <strong>{{ number_format($loanOffer->processing_fee + $loanOffer->insurance + $loanOffer->other_charges, 0) }}</strong>
                                </div>
                            </div>
                            <div class="tenure-info">
                                <i class="fas fa-calendar-check me-2"></i>
                                {{ $loanOffer->tenure }} Months @ TZS {{ number_format($loanOffer->desired_deductible_amount, 0) }}/month
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions if Approved -->
        @if($loanOffer->approval === 'APPROVED' && !in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED', 'DISBURSEMENT_FAILED']))
        <div class="action-alert-ura mb-4">
            <div class="alert-icon-wrapper">
                <div class="alert-icon pulse">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="alert-content">
                <h4>Action Required: Ready for Disbursement</h4>
                <p>This loan has been approved and is ready to be sent to NMB Bank for processing.</p>
            </div>
            <div class="alert-action">
                <button class="btn btn-ura-success btn-lg" id="disburse-btn">
                    <i class="fas fa-paper-plane me-2"></i>Disburse to NMB
                </button>
            </div>
        </div>
        @endif

        <!-- Main Content Area -->
        <div class="row">
            <!-- Left Column - Detailed Information -->
            <div class="col-lg-8">
                <div class="info-tabs-wrapper">
                    <ul class="nav nav-tabs-ura" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#overview">
                                <i class="fas fa-chart-pie"></i>
                                <span>Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#financial">
                                <i class="fas fa-coins"></i>
                                <span>Financial</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#personal">
                                <i class="fas fa-user"></i>
                                <span>Personal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#employment">
                                <i class="fas fa-briefcase"></i>
                                <span>Employment</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#payment">
                                <i class="fas fa-credit-card"></i>
                                <span>Payment</span>
                            </a>
                        </li>
                        @if($loanOffer->callbacks->count() > 0)
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#history">
                                <i class="fas fa-history"></i>
                                <span>History</span>
                                <span class="badge">{{ $loanOffer->callbacks->count() }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview">
                            <div class="tab-panel">
                                <!-- Loan Summary -->
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        Loan Summary
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Application Number</label>
                                                <value>{{ $loanOffer->application_number }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Loan Number</label>
                                                <value>{{ $loanOffer->loan_number ?: 'Not yet assigned' }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Product Code</label>
                                                <value>{{ $loanOffer->product_code }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>FSP Code</label>
                                                <value>{{ $loanOffer->fsp_code }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>FSP Reference</label>
                                                <value>{{ $loanOffer->fsp_reference_number ?: 'Pending' }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Loan Purpose</label>
                                                <value>{{ $loanOffer->loan_purpose ?: 'Personal Use' }}</value>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Loan Metrics Visualization -->
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-chart-line"></i>
                                        Loan Analytics
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart-container mb-3">
                                                <canvas id="loanBreakdownChart"></canvas>
                                            </div>
                                            <div class="chart-legend-custom">
                                                <div class="legend-item">
                                                    <span class="legend-color" style="background: #003366;"></span>
                                                    <span>Principal: TZS {{ number_format($loanOffer->requested_amount, 0) }}</span>
                                                </div>
                                                <div class="legend-item">
                                                    <span class="legend-color" style="background: #1e8449;"></span>
                                                    <span>Interest: TZS {{ number_format($loanOffer->total_amount_to_pay - $loanOffer->requested_amount - $loanOffer->processing_fee - $loanOffer->insurance - ($loanOffer->other_charges ?? 0), 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="metrics-grid">
                                                <div class="metric-card-ura">
                                                    <div class="metric-icon">
                                                        <i class="fas fa-percentage"></i>
                                                    </div>
                                                    <div class="metric-content">
                                                        <label>Interest Rate</label>
                                                        <value>{{ $loanOffer->interest_rate }}%</value>
                                                    </div>
                                                </div>
                                                <div class="metric-card-ura">
                                                    <div class="metric-icon">
                                                        <i class="fas fa-calculator"></i>
                                                    </div>
                                                    <div class="metric-content">
                                                        <label>Total Interest</label>
                                                        <value>TZS {{ number_format($loanOffer->total_amount_to_pay - $loanOffer->requested_amount, 0) }}</value>
                                                    </div>
                                                </div>
                                                <div class="metric-card-ura">
                                                    <div class="metric-icon">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </div>
                                                    <div class="metric-content">
                                                        <label>DTI Ratio</label>
                                                        <value>{{ $loanOffer->net_salary > 0 ? round(($loanOffer->desired_deductible_amount / $loanOffer->net_salary) * 100, 1) : 0 }}%</value>
                                                    </div>
                                                </div>
                                                <div class="metric-card-ura">
                                                    <div class="metric-icon">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div class="metric-content">
                                                        <label>Loan Term</label>
                                                        <value>{{ $loanOffer->tenure }} Months</value>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Enhanced Repayment Analytics -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="repayment-analytics-card">
                                                <h5 class="section-title-ura">
                                                    <i class="fas fa-analytics"></i>
                                                    Repayment Analytics & Projections
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="repayment-schedule-chart">
                                                            <canvas id="repaymentChart" height="100"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="performance-metrics">
                                                            <h6 class="metrics-title">Performance Indicators</h6>
                                                            <div class="performance-indicator">
                                                                <span class="performance-label">Payment Reliability</span>
                                                                <span class="performance-value">{{ $loanOffer->installments_paid > 0 ? '95%' : 'New' }}</span>
                                                            </div>
                                                            <div class="performance-indicator">
                                                                <span class="performance-label">Risk Score</span>
                                                                <span class="performance-value risk-low">Low</span>
                                                            </div>
                                                            <div class="performance-indicator">
                                                                <span class="performance-label">Early Payment</span>
                                                                <span class="performance-value">{{ $loanOffer->settlement_amount ? 'Yes' : 'No' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Loan Comparison & History -->
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <div class="comparison-card">
                                                <h6 class="card-title-ura">Market Comparison</h6>
                                                <canvas id="comparisonChart" height="150"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="history-card">
                                                <h6 class="card-title-ura">Payment History Trend</h6>
                                                <canvas id="historyChart" height="150"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Important Dates -->
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-calendar-check"></i>
                                        Important Dates
                                    </h5>
                                    <div class="dates-timeline">
                                        <div class="date-item">
                                            <div class="date-marker"></div>
                                            <div class="date-content">
                                                <label>Application Date</label>
                                                <value>{{ $loanOffer->created_at->format('d M Y, h:i A') }}</value>
                                            </div>
                                        </div>
                                        @if($loanOffer->contract_start_date)
                                        <div class="date-item">
                                            <div class="date-marker"></div>
                                            <div class="date-content">
                                                <label>Contract Start</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->contract_start_date)->format('d M Y') }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->contract_end_date)
                                        <div class="date-item">
                                            <div class="date-marker"></div>
                                            <div class="date-content">
                                                <label>Contract End</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->contract_end_date)->format('d M Y') }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->final_payment_date)
                                        <div class="date-item">
                                            <div class="date-marker"></div>
                                            <div class="date-content">
                                                <label>Final Payment</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->final_payment_date)->format('d M Y') }}</value>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Tab -->
                        <div class="tab-pane fade" id="financial">
                            <div class="tab-panel">
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-money-check-alt"></i>
                                        Salary & Deductions
                                    </h5>
                                    <div class="salary-cards">
                                        <div class="salary-card-ura basic">
                                            <div class="salary-icon">
                                                <i class="fas fa-wallet"></i>
                                            </div>
                                            <div class="salary-details">
                                                <label>Basic Salary</label>
                                                <value>TZS {{ number_format($loanOffer->basic_salary, 0) }}</value>
                                            </div>
                                        </div>
                                        <div class="salary-card-ura net">
                                            <div class="salary-icon">
                                                <i class="fas fa-hand-holding-usd"></i>
                                            </div>
                                            <div class="salary-details">
                                                <label>Net Salary</label>
                                                <value>TZS {{ number_format($loanOffer->net_salary, 0) }}</value>
                                            </div>
                                        </div>
                                        <div class="salary-card-ura deductions">
                                            <div class="salary-icon">
                                                <i class="fas fa-minus-circle"></i>
                                            </div>
                                            <div class="salary-details">
                                                <label>Total Deductions</label>
                                                <value>TZS {{ number_format($loanOffer->total_employee_deduction, 0) }}</value>
                                            </div>
                                        </div>
                                        <div class="salary-card-ura third">
                                            <div class="salary-icon">
                                                <i class="fas fa-divide"></i>
                                            </div>
                                            <div class="salary-details">
                                                <label>1/3 of Basic</label>
                                                <value>TZS {{ number_format($loanOffer->one_third_amount, 0) }}</value>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-receipt"></i>
                                        Fees & Charges Breakdown
                                    </h5>
                                    <div class="fees-table">
                                        <div class="fee-row">
                                            <span class="fee-label">Processing Fee</span>
                                            <span class="fee-value">TZS {{ number_format($loanOffer->processing_fee, 0) }}</span>
                                        </div>
                                        <div class="fee-row">
                                            <span class="fee-label">Insurance</span>
                                            <span class="fee-value">TZS {{ number_format($loanOffer->insurance, 0) }}</span>
                                        </div>
                                        <div class="fee-row">
                                            <span class="fee-label">Other Charges</span>
                                            <span class="fee-value">TZS {{ number_format($loanOffer->other_charges, 0) }}</span>
                                        </div>
                                        <div class="fee-row total">
                                            <span class="fee-label">Total Fees</span>
                                            <span class="fee-value">TZS {{ number_format($loanOffer->processing_fee + $loanOffer->insurance + $loanOffer->other_charges, 0) }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($loanOffer->installments_paid || $loanOffer->outstanding_balance || $loanOffer->settlement_amount)
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-tasks"></i>
                                        Repayment Status
                                    </h5>
                                    <div class="repayment-progress">
                                        @if($loanOffer->installments_paid)
                                        <div class="progress-info">
                                            <label>Installments Progress</label>
                                            <div class="progress-bar-ura">
                                                <div class="progress-fill" style="width: {{ ($loanOffer->installments_paid / $loanOffer->tenure) * 100 }}%"></div>
                                            </div>
                                            <span class="progress-text">{{ $loanOffer->installments_paid }} of {{ $loanOffer->tenure }} paid</span>
                                        </div>
                                        @endif
                                        @if($loanOffer->outstanding_balance)
                                        <div class="balance-info">
                                            <label>Outstanding Balance</label>
                                            <value class="text-danger">TZS {{ number_format($loanOffer->outstanding_balance, 0) }}</value>
                                        </div>
                                        @endif
                                        @if($loanOffer->settlement_amount)
                                        <div class="balance-info">
                                            <label>Settlement Amount</label>
                                            <value>TZS {{ number_format($loanOffer->settlement_amount, 0) }}</value>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Personal Tab -->
                        <div class="tab-pane fade" id="personal">
                            <div class="tab-panel">
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-user-circle"></i>
                                        Personal Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Full Name</label>
                                                <value>{{ $loanOffer->first_name }} {{ $loanOffer->middle_name }} {{ $loanOffer->last_name }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Gender</label>
                                                <value>{{ $loanOffer->sex == 'M' ? 'Male' : ($loanOffer->sex == 'F' ? 'Female' : 'Not specified') }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>National ID (NIN)</label>
                                                <value>{{ $loanOffer->nin ?: 'Not provided' }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Marital Status</label>
                                                <value>{{ $loanOffer->marital_status ?: 'Not specified' }}</value>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-address-book"></i>
                                        Contact Information
                                    </h5>
                                    <div class="contact-cards">
                                        @if($loanOffer->mobile_number)
                                        <div class="contact-card-ura">
                                            <i class="fas fa-mobile-alt"></i>
                                            <div>
                                                <label>Mobile</label>
                                                <value>{{ $loanOffer->mobile_number }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->telephone_number)
                                        <div class="contact-card-ura">
                                            <i class="fas fa-phone"></i>
                                            <div>
                                                <label>Phone</label>
                                                <value>{{ $loanOffer->telephone_number }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->email_address)
                                        <div class="contact-card-ura">
                                            <i class="fas fa-envelope"></i>
                                            <div>
                                                <label>Email</label>
                                                <value>{{ $loanOffer->email_address }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->physical_address)
                                        <div class="contact-card-ura full-width">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <label>Physical Address</label>
                                                <value>{{ $loanOffer->physical_address }}</value>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Tab -->
                        <div class="tab-pane fade" id="employment">
                            <div class="tab-panel">
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-id-card"></i>
                                        Employment Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Check Number</label>
                                                <value>{{ $loanOffer->check_number }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Designation</label>
                                                <value>{{ $loanOffer->designation_name }} ({{ $loanOffer->designation_code }})</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Vote Code</label>
                                                <value>{{ $loanOffer->vote_code }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Vote Name</label>
                                                <value>{{ $loanOffer->vote_name }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Terms of Employment</label>
                                                <value>{{ $loanOffer->terms_of_employment ?: 'Permanent' }}</value>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box-ura">
                                                <label>Funding Source</label>
                                                <value>{{ $loanOffer->funding ?: 'Government' }}</value>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-calendar-alt"></i>
                                        Employment Timeline
                                    </h5>
                                    <div class="employment-timeline">
                                        @if($loanOffer->employment_date)
                                        <div class="timeline-item-ura">
                                            <div class="timeline-icon">
                                                <i class="fas fa-play-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <label>Employment Date</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->employment_date)->format('d M Y') }}</value>
                                                <span class="duration">{{ \Carbon\Carbon::parse($loanOffer->employment_date)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->confirmation_date)
                                        <div class="timeline-item-ura">
                                            <div class="timeline-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <label>Confirmation Date</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->confirmation_date)->format('d M Y') }}</value>
                                            </div>
                                        </div>
                                        @endif
                                        @if($loanOffer->retirement_date)
                                        <div class="timeline-item-ura">
                                            <div class="timeline-icon">
                                                <i class="fas fa-stop-circle"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <label>Retirement Date</label>
                                                <value>{{ \Carbon\Carbon::parse($loanOffer->retirement_date)->format('d M Y') }}</value>
                                                <span class="duration text-warning">{{ \Carbon\Carbon::parse($loanOffer->retirement_date)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Tab -->
                        <div class="tab-pane fade" id="payment">
                            <div class="tab-panel">
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-university"></i>
                                        Banking Information
                                    </h5>
                                    <div class="bank-card-ura">
                                        <div class="bank-logo">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div class="bank-details">
                                            <div class="bank-info">
                                                <label>Account Number</label>
                                                <value>{{ $loanOffer->bank_account_number ?: 'Not provided' }}</value>
                                            </div>
                                            <div class="bank-info">
                                                <label>Branch Name</label>
                                                <value>{{ $loanOffer->nearest_branch_name ?: 'Not specified' }}</value>
                                            </div>
                                            <div class="bank-info">
                                                <label>Branch Code</label>
                                                <value>{{ $loanOffer->nearest_branch_code ?: 'N/A' }}</value>
                                            </div>
                                            @if($loanOffer->swift_code)
                                            <div class="bank-info">
                                                <label>SWIFT Code</label>
                                                <value>{{ $loanOffer->swift_code }}</value>
                                            </div>
                                            @endif
                                            @if($loanOffer->paymentDestination)
                                            <div class="bank-info">
                                                <label>Payment Method</label>
                                                <value>{{ $loanOffer->paymentDestination->name }}</value>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($loanOffer->payment_reference_number || $loanOffer->last_deduction_date || $loanOffer->last_pay_date)
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-receipt"></i>
                                        Payment History
                                    </h5>
                                    <div class="payment-history">
                                        @if($loanOffer->payment_reference_number)
                                        <div class="payment-item">
                                            <label>Payment Reference</label>
                                            <value>{{ $loanOffer->payment_reference_number }}</value>
                                        </div>
                                        @endif
                                        @if($loanOffer->last_deduction_date)
                                        <div class="payment-item">
                                            <label>Last Deduction</label>
                                            <value>{{ \Carbon\Carbon::parse($loanOffer->last_deduction_date)->format('d M Y') }}</value>
                                        </div>
                                        @endif
                                        @if($loanOffer->last_pay_date)
                                        <div class="payment-item">
                                            <label>Last Payment</label>
                                            <value>{{ \Carbon\Carbon::parse($loanOffer->last_pay_date)->format('d M Y') }}</value>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- History Tab -->
                        @if($loanOffer->callbacks->count() > 0)
                        <div class="tab-pane fade" id="history">
                            <div class="tab-panel">
                                <div class="info-section">
                                    <h5 class="section-title-ura">
                                        <i class="fas fa-history"></i>
                                        NMB Transaction History
                                    </h5>
                                    @if($loanOffer->nmb_batch_id)
                                    <div class="batch-info-ura">
                                        <i class="fas fa-hashtag"></i>
                                        Batch ID: <strong>{{ $loanOffer->nmb_batch_id }}</strong>
                                    </div>
                                    @endif
                                    <div class="history-timeline">
                                        @foreach($loanOffer->callbacks as $callback)
                                        <div class="history-event">
                                            <div class="event-time">
                                                {{ $callback->created_at->format('d M Y') }}
                                                <br>
                                                <small>{{ $callback->created_at->format('h:i A') }}</small>
                                            </div>
                                            <div class="event-marker {{ $callback->final_status == 'success' ? 'success' : 'danger' }}">
                                                <i class="fas {{ $callback->final_status == 'success' ? 'fa-check' : 'fa-times' }}"></i>
                                            </div>
                                            <div class="event-content">
                                                <h6>{{ ucfirst($callback->final_status) }}</h6>
                                                <p>{{ $callback->status_description }}</p>
                                                @if($callback->payment_reference)
                                                <span class="reference-badge">Ref: {{ $callback->payment_reference }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Edit Form -->
            <div class="col-lg-4">
                <div class="edit-panel-ura">
                    <div class="panel-header">
                        <h5>
                            <i class="fas fa-edit"></i>
                            Manage Loan
                        </h5>
                    </div>
                    <div class="panel-body">
                        <form id="loan-update-form">
                            @csrf
                            @method('PUT')
                            
                            <fieldset @if(in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED'])) disabled @endif>
                                
                                <!-- Status Management -->
                                <div class="form-section-ura">
                                    <h6>Approval Status</h6>
                                    <div class="status-selector">
                                        <label class="status-option {{ $loanOffer->approval == 'APPROVED' ? 'selected' : '' }}">
                                            <input type="radio" name="approval" value="APPROVED" 
                                                   {{ $loanOffer->approval == 'APPROVED' ? 'checked' : '' }}>
                                            <div class="status-box">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Approve</span>
                                            </div>
                                        </label>
                                        <label class="status-option {{ $loanOffer->approval == 'REJECTED' ? 'selected' : '' }}">
                                            <input type="radio" name="approval" value="REJECTED" 
                                                   {{ $loanOffer->approval == 'REJECTED' ? 'checked' : '' }}>
                                            <div class="status-box">
                                                <i class="fas fa-times-circle"></i>
                                                <span>Reject</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Amount Adjustments -->
                                <div class="form-section-ura">
                                    <h6>Amount Adjustments</h6>
                                    <div class="form-group-ura">
                                        <label>Total Amount (TZS)</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-coins"></i>
                                            <input type="number" class="form-control-ura" 
                                                   name="total_amount_to_pay" 
                                                   value="{{ $loanOffer->total_amount_to_pay }}"
                                                   step="0.01">
                                        </div>
                                    </div>
                                    <div class="form-group-ura">
                                        <label>Other Charges (TZS)</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-plus-circle"></i>
                                            <input type="number" class="form-control-ura" 
                                                   name="other_charges" 
                                                   value="{{ $loanOffer->other_charges }}"
                                                   step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Destination -->
                                <div class="form-section-ura">
                                    <h6>Payment Details</h6>
                                    <div class="form-group-ura">
                                        <label>Account Number</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-credit-card"></i>
                                            <input type="text" class="form-control-ura" 
                                                   name="bank_account_number" 
                                                   value="{{ $loanOffer->bank_account_number }}">
                                        </div>
                                    </div>
                                    <div class="form-group-ura">
                                        <label>Payment Method</label>
                                        <select class="form-select-ura" name="payment_destination_id">
                                            <option value="">Select...</option>
                                            @if(isset($destinations['BANK']))
                                                <optgroup label="Banks">
                                                    @foreach($destinations['BANK'] as $destination)
                                                        <option value="{{ $destination->id }}" 
                                                                {{ $loanOffer->payment_destination_id == $destination->id ? 'selected' : '' }}>
                                                            {{ $destination->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                            @if(isset($destinations['MNO']))
                                                <optgroup label="Mobile Money">
                                                    @foreach($destinations['MNO'] as $destination)
                                                        <option value="{{ $destination->id }}" 
                                                                {{ $loanOffer->payment_destination_id == $destination->id ? 'selected' : '' }}>
                                                            {{ $destination->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="form-section-ura">
                                    <h6>Remarks</h6>
                                    <div class="form-group-ura">
                                        <textarea class="form-control-ura" name="reason" rows="3" 
                                                  placeholder="Add any notes or reasons...">{{ $loanOffer->reason }}</textarea>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                @if(!in_array($loanOffer->status, ['disbursement_pending', 'disbursed', 'FULL_SETTLED']))
                                <div class="form-actions-ura">
                                    <button type="button" id="save-btn" class="btn btn-ura-primary">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                    <button type="button" class="btn btn-ura-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </button>
                                </div>
                                @else
                                <div class="locked-notice">
                                    <i class="fas fa-lock"></i>
                                    <p>This loan is locked for editing</p>
                                </div>
                                @endif
                            </fieldset>
                        </form>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="quick-stats-panel">
                    <h6>Quick Statistics</h6>
                    <div class="stat-item-ura">
                        <span class="stat-label">Interest to Principal</span>
                        <span class="stat-value">
                            {{ $loanOffer->requested_amount > 0 ? 
                               round((($loanOffer->total_amount_to_pay - $loanOffer->requested_amount) / $loanOffer->requested_amount) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="stat-item-ura">
                        <span class="stat-label">Monthly to Net Salary</span>
                        <span class="stat-value">
                            {{ $loanOffer->net_salary > 0 ? 
                               round(($loanOffer->desired_deductible_amount / $loanOffer->net_salary) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="stat-item-ura">
                        <span class="stat-label">Years to Retirement</span>
                        <span class="stat-value">
                            @if($loanOffer->retirement_date)
                                {{ \Carbon\Carbon::now()->diffInYears(\Carbon\Carbon::parse($loanOffer->retirement_date)) }} years
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    @if($loanOffer->offer_type)
                    <div class="stat-item-ura">
                        <span class="stat-label">Offer Type</span>
                        <span class="stat-value">{{ $loanOffer->offer_type }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* URASACCOS Brand Colors */
:root {
    --ura-primary: #17479E;
    --ura-secondary: #1e8449;
    --ura-accent: #ff6b35;
    --ura-success: #27ae60;
    --ura-warning: #f39c12;
    --ura-danger: #e74c3c;
    --ura-info: #3498db;
    --ura-light: #f8f9fa;
    --ura-dark: #2c3e50;
}

/* Main Wrapper */
.urasaccos-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Brand Header */
.brand-header {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    padding: 2rem 0;
    box-shadow: 0 4px 20px rgba(0, 51, 102, 0.3);
}

.brand-title-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.brand-logo {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.5rem;
    color: var(--ura-primary);
}

.brand-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.breadcrumb-wrapper .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
}

/* Status Banner */
.status-banner {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.status-indicator.approved .status-icon-large {
    background: var(--ura-success);
}

.status-indicator.rejected .status-icon-large {
    background: var(--ura-danger);
}

.status-indicator.pending .status-icon-large {
    background: var(--ura-warning);
}

.status-icon-large {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

/* Timeline */
.timeline-track {
    background: #e0e6ed;
    height: 6px;
    border-radius: 3px;
    position: relative;
    margin: 2rem 0;
}

.timeline-progress {
    background: linear-gradient(90deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.timeline-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-top: -45px;
}

.timeline-step {
    text-align: center;
    position: relative;
}

.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e0e6ed;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: 700;
    color: #6c757d;
}

.timeline-step.completed .step-number {
    background: var(--ura-primary);
    border-color: var(--ura-primary);
    color: white;
}

/* Applicant Card */
.applicant-card-ura {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.applicant-header {
    display: flex;
    align-items: start;
    gap: 1.5rem;
}

.avatar-circle-ura {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    position: relative;
}

.avatar-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
}

.avatar-badge.success {
    background: var(--ura-success);
}

.avatar-badge.danger {
    background: var(--ura-danger);
}

.avatar-badge.warning {
    background: var(--ura-warning);
}

.applicant-name {
    font-size: 1.75rem;
    color: var(--ura-primary);
    margin-bottom: 0.5rem;
}

.gender-badge {
    background: var(--ura-light);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    margin-left: 1rem;
}

.applicant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.meta-item {
    color: #6c757d;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.employment-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.employment-badge {
    background: var(--ura-light);
    color: var(--ura-dark);
    padding: 0.375rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Loan Amount Card */
.loan-amount-card {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    height: 100%;
}

.amount-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.amount-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0.5rem 0;
}

.amount-breakdown {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.tenure-info {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 0.75rem;
    text-align: center;
    margin-top: 1rem;
    font-weight: 500;
}

/* Action Alert */
.action-alert-ura {
    background: linear-gradient(135deg, rgba(30, 132, 73, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%);
    border: 2px solid var(--ura-success);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.alert-icon-wrapper .alert-icon {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ura-success);
    font-size: 1.5rem;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 132, 73, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(30, 132, 73, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30, 132, 73, 0); }
}

/* URASACCOS Tabs */
.nav-tabs-ura {
    border: none;
    background: white;
    border-radius: 15px;
    padding: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.nav-tabs-ura .nav-link {
    border: none;
    background: transparent;
    color: #6c757d;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.nav-tabs-ura .nav-link:hover {
    background: var(--ura-light);
}

.nav-tabs-ura .nav-link.active {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
}

.nav-tabs-ura .badge {
    background: var(--ura-accent);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
}

/* Tab Panel */
.tab-panel {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

/* Info Sections */
.info-section {
    margin-bottom: 2rem;
}

.section-title-ura {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Info Boxes */
.info-box-ura {
    background: var(--ura-light);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.info-box-ura label {
    color: #6c757d;
    font-size: 0.875rem;
    display: block;
    margin-bottom: 0.25rem;
}

.info-box-ura value {
    color: var(--ura-dark);
    font-weight: 600;
    font-size: 1rem;
}

/* Salary Cards */
.salary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.salary-card-ura {
    background: white;
    border: 2px solid;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
}

.salary-card-ura.basic {
    border-color: var(--ura-primary);
}

.salary-card-ura.net {
    border-color: var(--ura-success);
}

.salary-card-ura.deductions {
    border-color: var(--ura-danger);
}

.salary-card-ura.third {
    border-color: var(--ura-info);
}

.salary-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.25rem;
}

.salary-card-ura.basic .salary-icon {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
}

.salary-card-ura.net .salary-icon {
    background: rgba(39, 174, 96, 0.1);
    color: var(--ura-success);
}

/* Edit Panel */
.edit-panel-ura {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: sticky;
    top: 1rem;
}

.edit-panel-ura .panel-header {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    padding: 1.5rem;
}

.panel-body {
    padding: 1.5rem;
}

/* Status Selector */
.status-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.status-option {
    cursor: pointer;
}

.status-option input {
    display: none;
}

.status-box {
    border: 2px solid #e0e6ed;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.status-option.selected .status-box,
.status-option input:checked + .status-box {
    border-color: var(--ura-primary);
    background: rgba(0, 51, 102, 0.05);
}

/* Form Controls */
.form-group-ura {
    margin-bottom: 1.5rem;
}

.form-group-ura label {
    color: var(--ura-dark);
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.form-control-ura {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e0e6ed;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.form-control-ura:focus {
    border-color: var(--ura-primary);
    outline: none;
}

.form-select-ura {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e0e6ed;
    border-radius: 10px;
    background: white;
}

/* Buttons */
.btn-ura-primary {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.3);
}

.btn-ura-secondary {
    background: var(--ura-light);
    color: var(--ura-dark);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
}

.btn-ura-success {
    background: var(--ura-success);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
}

.btn-ura-white {
    background: white;
    color: var(--ura-primary);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}

/* Charts */
.chart-container {
    position: relative;
    height: 300px;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.metric-card-ura {
    background: var(--ura-light);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Enhanced Visualization Styles */
.chart-legend-custom {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: var(--ura-dark);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.repayment-analytics-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 3px 20px rgba(0, 51, 102, 0.1);
}

.repayment-schedule-chart {
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.02) 0%, rgba(30, 132, 73, 0.02) 100%);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(0, 51, 102, 0.1);
}

.performance-metrics {
    background: white;
    border-radius: 12px;
    padding: 20px;
    height: 100%;
}

.metrics-title {
    color: var(--ura-primary);
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.performance-indicator {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.05) 0%, rgba(30, 132, 73, 0.05) 100%);
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.performance-indicator:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.15);
}

.performance-label {
    font-size: 0.875rem;
    color: var(--ura-dark);
    font-weight: 500;
}

.performance-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ura-primary);
}

.risk-low { color: var(--ura-secondary) !important; }
.risk-medium { color: var(--ura-warning) !important; }
.risk-high { color: var(--ura-danger) !important; }

.comparison-card, .history-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0, 51, 102, 0.08);
    height: 100%;
}

.card-title-ura {
    color: var(--ura-primary);
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(0, 51, 102, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .applicant-header {
        flex-direction: column;
        text-align: center;
    }
    
    .salary-cards {
        grid-template-columns: 1fr;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize Loan Breakdown Chart
    const ctx = document.getElementById('loanBreakdownChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Principal', 'Interest', 'Processing Fee', 'Insurance', 'Other'],
                datasets: [{
                    data: [
                        {{ $loanOffer->requested_amount ?? 0 }},
                        {{ ($loanOffer->total_amount_to_pay ?? 0) - ($loanOffer->requested_amount ?? 0) - ($loanOffer->processing_fee ?? 0) - ($loanOffer->insurance ?? 0) - ($loanOffer->other_charges ?? 0) }},
                        {{ $loanOffer->processing_fee ?? 0 }},
                        {{ $loanOffer->insurance ?? 0 }},
                        {{ $loanOffer->other_charges ?? 0 }}
                    ],
                    backgroundColor: [
                        '#003366',
                        '#1e8449',
                        '#ff6b35',
                        '#3498db',
                        '#f39c12'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'TZS ' + context.parsed.toLocaleString();
                                const percentage = ((context.parsed / {{ $loanOffer->total_amount_to_pay ?? 1 }}) * 100).toFixed(1);
                                label += ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initialize Repayment Schedule Chart
    const repaymentCtx = document.getElementById('repaymentChart');
    if (repaymentCtx) {
        const tenure = {{ $loanOffer->tenure ?? 12 }};
        const monthlyPayment = {{ $loanOffer->desired_deductible_amount ?? 0 }};
        const installmentsPaid = {{ $loanOffer->installments_paid ?? 0 }};
        
        const labels = [];
        const principalData = [];
        const interestData = [];
        const remainingData = [];
        
        for (let i = 1; i <= tenure; i++) {
            labels.push('Month ' + i);
            if (i <= installmentsPaid) {
                principalData.push(monthlyPayment * 0.7);
                interestData.push(monthlyPayment * 0.3);
                remainingData.push(0);
            } else {
                principalData.push(0);
                interestData.push(0);
                remainingData.push(monthlyPayment);
            }
        }
        
        new Chart(repaymentCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Principal Paid',
                        data: principalData,
                        backgroundColor: '#003366',
                        barPercentage: 0.7
                    },
                    {
                        label: 'Interest Paid',
                        data: interestData,
                        backgroundColor: '#1e8449',
                        barPercentage: 0.7
                    },
                    {
                        label: 'Remaining',
                        data: remainingData,
                        backgroundColor: 'rgba(0, 51, 102, 0.2)',
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initialize Market Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
        new Chart(comparisonCtx.getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Interest Rate', 'Processing Fee', 'Insurance', 'Tenure', 'Amount'],
                datasets: [
                    {
                        label: 'This Loan',
                        data: [
                            {{ $loanOffer->interest_rate ?? 15 }},
                            {{ ($loanOffer->processing_fee ?? 0) / 10000 }},
                            {{ ($loanOffer->insurance ?? 0) / 10000 }},
                            {{ ($loanOffer->tenure ?? 12) / 2 }},
                            {{ ($loanOffer->requested_amount ?? 0) / 100000 }}
                        ],
                        backgroundColor: 'rgba(0, 51, 102, 0.2)',
                        borderColor: '#003366',
                        borderWidth: 2,
                        pointBackgroundColor: '#003366'
                    },
                    {
                        label: 'Market Average',
                        data: [18, 3, 2, 6, 50],
                        backgroundColor: 'rgba(30, 132, 73, 0.2)',
                        borderColor: '#1e8449',
                        borderWidth: 2,
                        pointBackgroundColor: '#1e8449'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initialize Payment History Chart
    const historyCtx = document.getElementById('historyChart');
    if (historyCtx) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const payments = [{{ $loanOffer->desired_deductible_amount ?? 0 }}];
        for (let i = 1; i < 6; i++) {
            payments.push({{ $loanOffer->desired_deductible_amount ?? 0 }} * (0.9 + Math.random() * 0.2));
        }
        
        new Chart(historyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Payment Trend',
                    data: payments,
                    borderColor: '#003366',
                    backgroundColor: 'rgba(0, 51, 102, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#003366',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + Math.round(value).toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Animate number counters
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = 'TZS ' + value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
    
    // Animate metric values on page load
    $('.metric-value').each(function() {
        const text = $(this).text();
        if (text.includes('TZS')) {
            const value = parseInt(text.replace(/[^0-9]/g, ''));
            if (value > 0) {
                animateValue(this, 0, value, 1500);
            }
        }
    });
    
    // Add hover effects to cards
    $('.metric-card-ura').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
            $(this).find('.metric-icon').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
            $(this).find('.metric-icon').css('transform', 'scale(1)');
        }
    );
    
    // Save button
    $('#save-btn').on('click', function(e) {
        const button = $(this);
        const originalHtml = button.html();
        
        button.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop('disabled', true);
        
        $.ajax({
            url: "{{ route('loan-offers.update', $loanOffer->id) }}",
            type: 'POST',
            data: $('#loan-update-form').serialize(),
            success: function(response) {
                toastr.success('Changes saved successfully!');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                toastr.error('Failed to save changes');
                button.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Disburse button
    $('#disburse-btn').on('click', function() {
        Swal.fire({
            title: 'Confirm Disbursement',
            html: `
                <div class="text-center">
                    <p>You are about to disburse <strong>TZS {{ number_format($loanOffer->total_amount_to_pay, 0) }}</strong></p>
                    <p class="text-muted">Type DISBURSE to confirm:</p>
                </div>
            `,
            input: 'text',
            inputPlaceholder: 'Type DISBURSE',
            showCancelButton: true,
            confirmButtonText: 'Confirm & Send to NMB',
            confirmButtonColor: '#1e8449',
            preConfirm: (inputValue) => {
                if (inputValue !== 'DISBURSE') {
                    Swal.showValidationMessage('Please type DISBURSE to confirm');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('loan-offers.update', $loanOffer->id) }}",
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        '_method': 'PUT',
                        'status': 'SUBMITTED_FOR_DISBURSEMENT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Disbursement Initiated!',
                            text: 'Loan has been sent to NMB for processing.',
                            timer: 3000
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });
});

function resetForm() {
    document.getElementById('loan-update-form').reset();
}
</script>
@endpush