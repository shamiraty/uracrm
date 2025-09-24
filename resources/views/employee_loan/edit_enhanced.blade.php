@extends('layouts.app')

@section('content')
<div class="ura-modern-wrapper">
    <!-- Animated Background Pattern -->
    <div class="ura-bg-pattern"></div>
    
    <!-- Modern Header with Glass Effect -->
    <div class="ura-header-section">
        <div class="container-fluid">
            <!-- Breadcrumb with Modern Style -->
            <nav aria-label="breadcrumb" class="ura-breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="ura-breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('loan-offers.index') }}" class="ura-breadcrumb-link">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>Loan Applications</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>{{ $loanOffer->application_number }}</span>
                    </li>
                </ol>
            </nav>

            <!-- Header Content with Animation -->
            <div class="ura-header-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="ura-title-group">
                            <div class="ura-icon-wrapper">
                                <div class="ura-icon-circle">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="ura-icon-pulse"></div>
                            </div>
                            <div class="ura-title-text">
                                <h1 class="ura-page-title">
                                    Loan Application Management
                                    @if($loanOffer->loan_type === 'topup' || $loanOffer->offer_type === 'TOP_UP')
                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5em; vertical-align: middle;">
                                            <i class="fas fa-sync-alt"></i> TOPUP LOAN
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white ms-2" style="font-size: 0.5em; vertical-align: middle;">
                                            <i class="fas fa-plus-circle"></i> NEW LOAN
                                        </span>
                                    @endif
                                </h1>
                                <p class="ura-page-subtitle">
                                    <span class="ura-status-badge {{ $loanOffer->approval == 'APPROVED' ? 'badge-success' : ($loanOffer->approval == 'REJECTED' ? 'badge-danger' : 'badge-warning') }}">
                                        <i class="fas fa-circle"></i>
                                        {{ $loanOffer->approval ?: 'PENDING' }}
                                    </span>
                                    <span class="ura-separator">•</span>
                                    <span class="ura-app-number">
                                        <i class="fas fa-hashtag"></i>
                                        {{ $loanOffer->application_number }}
                                    </span>
                                    <span class="ura-separator">•</span>
                                    <span class="ura-date">
                                        <i class="fas fa-calendar"></i>
                                        {{ $loanOffer->created_at->format('d M Y') }}
                                    </span>
                                    @if($loanOffer->loan_type === 'topup' && $loanOffer->topupAsNew && $loanOffer->topupAsNew->original_loan_number)
                                        <span class="ura-separator">•</span>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-link"></i> Settles: {{ $loanOffer->topupAsNew->original_loan_number }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ura-action-buttons">
                            <button class="ura-btn-icon" onclick="window.print()" data-tooltip="Print">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="ura-btn-icon" onclick="exportPDF()" data-tooltip="Export PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="ura-btn-icon" onclick="shareLoan()" data-tooltip="Share">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <a href="{{ route('loan-offers.index') }}" class="ura-btn-back">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Timeline with Animation -->
    <div class="ura-progress-section">
        <div class="container-fluid">
            <div class="ura-timeline-wrapper">
                <div class="ura-timeline-progress">
                    <div class="ura-timeline-track"></div>
                    <div class="ura-timeline-fill" style="width: {{ $loanOffer->status == 'disbursed' ? '100' : ($loanOffer->approval == 'APPROVED' ? '66' : '33') }}%"></div>
                </div>
                <div class="ura-timeline-steps">
                    <div class="ura-timeline-step {{ $loanOffer->created_at ? 'completed' : '' }}">
                        <div class="ura-step-bubble">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="ura-step-content">
                            <span class="ura-step-title">Application</span>
                            <span class="ura-step-date">{{ $loanOffer->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="ura-timeline-step {{ $loanOffer->approval == 'APPROVED' ? 'completed' : ($loanOffer->approval == 'REJECTED' ? 'rejected' : 'pending') }}">
                        <div class="ura-step-bubble">
                            <i class="fas {{ $loanOffer->approval == 'REJECTED' ? 'fa-times' : 'fa-check' }}"></i>
                        </div>
                        <div class="ura-step-content">
                            <span class="ura-step-title">Approval</span>
                            <span class="ura-step-date">{{ $loanOffer->approval ?: 'Pending' }}</span>
                        </div>
                    </div>
                    <div class="ura-timeline-step {{ $loanOffer->status == 'disbursed' ? 'completed' : 'pending' }}">
                        <div class="ura-step-bubble">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="ura-step-content">
                            <span class="ura-step-title">Disbursement</span>
                            <span class="ura-step-date">{{ $loanOffer->status == 'disbursed' ? 'Complete' : 'Pending' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container-fluid ura-content-area">
        <div class="row">
            <!-- Left Column - Employee & Loan Info -->
            <div class="col-lg-8">
                <!-- Employee Profile Card -->
                <div class="ura-card ura-employee-card">
                    <div class="ura-card-header">
                        <h3 class="ura-card-title">
                            <i class="fas fa-user-tie"></i>
                            Employee Information
                        </h3>
                        <div class="ura-card-actions">
                            <button class="ura-action-btn" onclick="toggleCardExpand(this)">
                                <i class="fas fa-expand-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ura-card-body">
                        <div class="ura-employee-header">
                            <div class="ura-avatar-wrapper">
                                <div class="ura-avatar">
                                    <span>{{ strtoupper(substr($loanOffer->first_name, 0, 1)) }}{{ strtoupper(substr($loanOffer->last_name, 0, 1)) }}</span>
                                </div>
                                <div class="ura-avatar-badge {{ $loanOffer->approval == 'APPROVED' ? 'success' : 'warning' }}">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="ura-employee-info">
                                <h2 class="ura-employee-name">
                                    {{ $loanOffer->first_name }} {{ $loanOffer->middle_name }} {{ $loanOffer->last_name }}
                                </h2>
                                <div class="ura-employee-meta">
                                    <span class="ura-meta-item">
                                        <i class="fas fa-id-badge"></i>
                                        {{ $loanOffer->check_number }}
                                    </span>
                                    <span class="ura-meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        {{ $loanOffer->designation_name }}
                                    </span>
                                    <span class="ura-meta-item">
                                        <i class="fas fa-building"></i>
                                        {{ $loanOffer->vote_name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ura-info-grid">
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-phone"></i>
                                    Mobile
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->mobile_number }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->email_address ?: 'N/A' }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-id-card"></i>
                                    NIN
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->nin ?: 'N/A' }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-venus-mars"></i>
                                    Gender
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->sex ?: 'N/A' }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-calendar-check"></i>
                                    Employment
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->employment_date ? \Carbon\Carbon::parse($loanOffer->employment_date)->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">
                                    <i class="fas fa-calendar-times"></i>
                                    Retirement
                                </span>
                                <span class="ura-info-value">{{ $loanOffer->retirement_date ? \Carbon\Carbon::parse($loanOffer->retirement_date)->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loan Details Card -->
                <div class="ura-card ura-loan-card">
                    <div class="ura-card-header">
                        <h3 class="ura-card-title">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Loan Details
                        </h3>
                        <div class="ura-card-badge">
                            <span class="ura-amount-badge">
                                {{ number_format($loanOffer->requested_amount, 0) }} TZS
                            </span>
                        </div>
                    </div>
                    <div class="ura-card-body">
                        <form id="loanEditForm" method="POST" action="{{ route('loan-offers.update', $loanOffer->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="ura-form-section">
                                <h4 class="ura-section-title">Financial Information <span class="text-muted small">(ESS Data - Read Only)</span></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Basic Salary
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       name="basic_salary" 
                                                       value="{{ number_format($loanOffer->basic_salary, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-hand-holding-usd"></i>
                                                Requested Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       name="requested_amount" 
                                                       value="{{ number_format($loanOffer->requested_amount, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-percentage"></i>
                                                Deductible Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       name="deductible_amount" 
                                                       value="{{ number_format($loanOffer->deductible_amount, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-wallet"></i>
                                                Take Home Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       name="take_home_amount" 
                                                       value="{{ number_format($loanOffer->take_home_amount ?: $loanOffer->net_salary - $loanOffer->deductible_amount, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ura-form-section">
                                <h4 class="ura-section-title">Loan Information <span class="text-muted small">(ESS Data - Read Only)</span></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-clipboard-list"></i>
                                                Loan Purpose
                                            </label>
                                            <textarea class="ura-form-control" 
                                                      name="loan_purpose" 
                                                      rows="3"
                                                      readonly
                                                      disabled>{{ $loanOffer->loan_purpose }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-clock"></i>
                                                Loan Tenure
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="number" 
                                                       class="ura-form-control" 
                                                       name="loan_tenure" 
                                                       value="{{ $loanOffer->loan_tenure }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">Months</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ura-form-section">
                                <h4 class="ura-section-title">Banking Information <span class="text-muted small">(ESS Data - Read Only)</span></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-university"></i>
                                                Bank
                                            </label>
                                            <input type="text" 
                                                   class="ura-form-control" 
                                                   value="{{ optional($loanOffer->bank)->short_name }} - {{ optional($loanOffer->bank)->name }}" 
                                                   readonly
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-credit-card"></i>
                                                Account Number
                                            </label>
                                            <input type="text" 
                                                   class="ura-form-control" 
                                                   value="{{ $loanOffer->bank_account_number }}" 
                                                   readonly
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Topup Information Section -->
                            @if($loanOffer->loan_type === 'topup' || $loanOffer->offer_type === 'TOP_UP')
                            <div class="ura-form-section" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border: 1px solid #ffc107;">
                                <h4 class="ura-section-title">
                                    <i class="fas fa-sync-alt text-warning"></i>
                                    Top-up Loan Information
                                </h4>
                                @if($loanOffer->topupAsNew)
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-file-alt"></i>
                                                Original Loan Number
                                            </label>
                                            <input type="text" 
                                                   class="ura-form-control" 
                                                   value="{{ $loanOffer->topupAsNew->original_loan_number }}" 
                                                   readonly
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-money-check-alt"></i>
                                                Settlement Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       value="{{ number_format($loanOffer->topupAsNew->settlement_amount ?? 0, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-chart-line"></i>
                                                Outstanding Balance
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       value="{{ number_format($loanOffer->topupAsNew->outstanding_balance ?? 0, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-info-circle"></i>
                                                Topup Status
                                            </label>
                                            <input type="text" 
                                                   class="ura-form-control" 
                                                   value="{{ ucfirst($loanOffer->topupAsNew->status ?? 'pending') }}"
                                                   readonly
                                                   disabled
                                                   style="font-weight: bold; color: {{ $loanOffer->topupAsNew->status === 'disbursed' ? '#28a745' : ($loanOffer->topupAsNew->status === 'approved' ? '#17a2b8' : '#ffc107') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-plus-circle"></i>
                                                New Loan Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control" 
                                                       value="{{ number_format($loanOffer->requested_amount ?? 0, 0) }}"
                                                       readonly
                                                       disabled>
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ura-form-group">
                                            <label class="ura-form-label">
                                                <i class="fas fa-calculator"></i>
                                                Net Top-up Amount
                                            </label>
                                            <div class="ura-input-wrapper">
                                                <input type="text" 
                                                       class="ura-form-control text-success" 
                                                       value="{{ number_format(($loanOffer->requested_amount ?? 0) - ($loanOffer->topupAsNew->settlement_amount ?? 0), 0) }}"
                                                       readonly
                                                       disabled
                                                       style="font-weight: bold;">
                                                <span class="ura-input-suffix">TZS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    This is marked as a top-up loan but no settlement details are available yet.
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            @php
                                $isLoanCancelled = in_array(strtoupper($loanOffer->approval ?? ''), ['CANCELLED', 'CANCELED']) ||
                                                  in_array(strtoupper($loanOffer->status ?? ''), ['CANCELLED', 'CANCELED']) || 
                                                  in_array(strtoupper($loanOffer->state ?? ''), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                            @endphp
                            
                            <!-- Loan Cancelled Notice -->
                            @if($isLoanCancelled)
                            <div class="ura-form-section" style="background: #f8d7da; border: 2px solid #dc3545; padding: 20px; border-radius: 10px;">
                                <h4 class="ura-section-title">
                                    <i class="fas fa-ban text-danger"></i>
                                    Loan Cancelled
                                </h4>
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle"></i>
                                    <strong>Cannot Modify:</strong> This loan application has been cancelled by the employee through ESS and cannot be approved, rejected, or modified.
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions & Status -->
            <div class="col-lg-4">
                <!-- Quick Actions Card -->
                <div class="ura-card ura-actions-card">
                    <div class="ura-card-header">
                        <h3 class="ura-card-title">
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="ura-card-body">
                        <div class="ura-action-grid">
                            @php
                                $isCancelled = in_array(strtoupper($loanOffer->approval ?? ''), ['CANCELLED', 'CANCELED']) ||
                                              in_array(strtoupper($loanOffer->status ?? ''), ['CANCELLED', 'CANCELED']) || 
                                              in_array(strtoupper($loanOffer->state ?? ''), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                            @endphp
                            
                            @if($isCancelled)
                                <div class="ura-cancelled-notice">
                                    <i class="fas fa-ban"></i>
                                    <span>This loan has been cancelled by the employee</span>
                                </div>
                            @elseif($loanOffer->approval !== 'APPROVED' && $loanOffer->approval !== 'REJECTED')
                            <button class="ura-action-item success" onclick="approveLoan({{ $loanOffer->id }})">
                                <i class="fas fa-check-circle"></i>
                                <span>Approve</span>
                            </button>
                            <button class="ura-action-item danger" onclick="rejectLoan({{ $loanOffer->id }})">
                                <i class="fas fa-times-circle"></i>
                                <span>Reject</span>
                            </button>
                            @endif
                            
                            @if(!$isCancelled && $loanOffer->approval == 'APPROVED' && $loanOffer->status !== 'disbursed')
                            <button class="ura-action-item primary" onclick="disburseLoan({{ $loanOffer->id }})">
                                <i class="fas fa-hand-holding-usd"></i>
                                <span>Disburse</span>
                            </button>
                            @endif
                            
                            <button class="ura-action-item secondary" onclick="printLoan()">
                                <i class="fas fa-print"></i>
                                <span>Print</span>
                            </button>
                            
                            <button class="ura-action-item info" onclick="viewHistory({{ $loanOffer->id }})">
                                <i class="fas fa-history"></i>
                                <span>History</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status Summary Card -->
                <div class="ura-card ura-status-card">
                    <div class="ura-card-header">
                        <h3 class="ura-card-title">
                            <i class="fas fa-chart-line"></i>
                            Status Summary
                        </h3>
                    </div>
                    <div class="ura-card-body">
                        <div class="ura-status-items">
                            <div class="ura-status-item">
                                <div class="ura-status-icon {{ $loanOffer->approval == 'APPROVED' ? 'success' : ($loanOffer->approval == 'REJECTED' ? 'danger' : 'warning') }}">
                                    <i class="fas {{ $loanOffer->approval == 'APPROVED' ? 'fa-check' : ($loanOffer->approval == 'REJECTED' ? 'fa-times' : 'fa-clock') }}"></i>
                                </div>
                                <div class="ura-status-content">
                                    <span class="ura-status-label">Approval Status</span>
                                    <span class="ura-status-value">{{ $loanOffer->approval ?: 'PENDING' }}</span>
                                </div>
                            </div>
                            
                            <div class="ura-status-item">
                                <div class="ura-status-icon {{ $loanOffer->status == 'disbursed' ? 'success' : 'secondary' }}">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="ura-status-content">
                                    <span class="ura-status-label">Disbursement</span>
                                    <span class="ura-status-value">{{ ucfirst($loanOffer->status ?: 'Pending') }}</span>
                                </div>
                            </div>

                            <div class="ura-status-item">
                                <div class="ura-status-icon primary">
                                    <i class="fas fa-code-branch"></i>
                                </div>
                                <div class="ura-status-content">
                                    <span class="ura-status-label">Branch</span>
                                    <span class="ura-status-value">{{ $loanOffer->nearest_branch_name ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loan Info Card -->
                <div class="ura-card ura-documents-card">
                    <div class="ura-card-header">
                        <h3 class="ura-card-title">
                            <i class="fas fa-info-circle"></i>
                            Additional Information
                        </h3>
                    </div>
                    <div class="ura-card-body">
                        <div class="ura-info-list">
                            <div class="ura-info-item">
                                <span class="ura-info-label">Application Number</span>
                                <span class="ura-info-value">{{ $loanOffer->application_number }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">ESS Reference</span>
                                <span class="ura-info-value">{{ $loanOffer->ess_reference ?: 'N/A' }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">Created Date</span>
                                <span class="ura-info-value">{{ $loanOffer->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="ura-info-item">
                                <span class="ura-info-label">Last Updated</span>
                                <span class="ura-info-value">{{ $loanOffer->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ura-modal">
            <div class="modal-header ura-modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Approve Loan Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body ura-modal-body">
                <p>Are you sure you want to approve this loan application?</p>
                <div class="ura-modal-info">
                    <div class="ura-info-row">
                        <span>Applicant:</span>
                        <strong>{{ $loanOffer->first_name }} {{ $loanOffer->last_name }}</strong>
                    </div>
                    <div class="ura-info-row">
                        <span>Amount:</span>
                        <strong>{{ number_format($loanOffer->requested_amount, 0) }} TZS</strong>
                    </div>
                </div>
                <div class="ura-form-group">
                    <label class="ura-form-label">Comments (Optional)</label>
                    <textarea class="ura-form-control" id="approvalComments" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer ura-modal-footer">
                <button type="button" class="ura-btn ura-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="ura-btn ura-btn-success" onclick="confirmApproval()">
                    <i class="fas fa-check"></i>
                    Confirm Approval
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* URASACCOS Brand Colors */
:root {
    --ura-primary: #003366;
    --ura-secondary: #17479E;
    --ura-tertiary: #2E5090;
    --ura-accent: #4A6FA5;
    --ura-light: #E8F0FE;
    --ura-dark: #001a33;
    --ura-success: #28a745;
    --ura-warning: #FF8C00;
    --ura-danger: #dc3545;
    --ura-info: #17a2b8;
    --ura-grey: #6c757d;
}

/* Modern Wrapper */
.ura-modern-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    position: relative;
    overflow-x: hidden;
}

/* Animated Background Pattern */
.ura-bg-pattern {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.03;
    background-image: 
        repeating-linear-gradient(45deg, var(--ura-primary) 0, var(--ura-primary) 1px, transparent 1px, transparent 15px),
        repeating-linear-gradient(-45deg, var(--ura-secondary) 0, var(--ura-secondary) 1px, transparent 1px, transparent 15px);
    animation: patternMove 20s linear infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes patternMove {
    0% { transform: translate(0, 0); }
    100% { transform: translate(30px, 30px); }
}

/* Header Section */
.ura-header-section {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    padding: 30px 0;
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 51, 102, 0.15);
    overflow: hidden;
}

.ura-header-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 50%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
    animation: float 15s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Breadcrumb Styling */
.ura-breadcrumb-nav {
    margin-bottom: 20px;
}

.ura-breadcrumb-nav .breadcrumb {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 10px 20px;
    border-radius: 50px;
    margin-bottom: 0;
}

.ura-breadcrumb-nav .breadcrumb-item {
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
}

.ura-breadcrumb-nav .breadcrumb-item.active {
    color: white;
    font-weight: 500;
}

.ura-breadcrumb-link {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.ura-breadcrumb-link:hover {
    color: white;
    transform: translateX(2px);
}

/* Title Group */
.ura-title-group {
    display: flex;
    align-items: center;
    gap: 20px;
}

.ura-icon-wrapper {
    position: relative;
    width: 60px;
    height: 60px;
}

.ura-icon-circle {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.ura-icon-circle i {
    font-size: 24px;
    color: white;
}

.ura-icon-pulse {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    animation: pulse 2s ease-out infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.ura-page-title {
    color: white;
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.5px;
}

.ura-page-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    margin: 5px 0 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.ura-status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.ura-status-badge.badge-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.ura-status-badge.badge-warning {
    background: rgba(255, 140, 0, 0.2);
    color: #FF8C00;
}

.ura-status-badge.badge-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.ura-status-badge i {
    font-size: 6px;
}

.ura-separator {
    color: rgba(255, 255, 255, 0.3);
}

/* Action Buttons */
.ura-action-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    align-items: center;
}

.ura-btn-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.ura-btn-icon:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.ura-btn-icon[data-tooltip]::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.ura-btn-icon:hover[data-tooltip]::after {
    opacity: 1;
}

.ura-btn-back {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.ura-btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-3px);
    color: white;
}

/* Progress Timeline */
.ura-progress-section {
    background: white;
    padding: 30px 0;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.05);
}

.ura-timeline-wrapper {
    position: relative;
}

.ura-timeline-progress {
    position: relative;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin: 40px 0;
}

.ura-timeline-fill {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: linear-gradient(90deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    border-radius: 2px;
    transition: width 1s ease;
}

.ura-timeline-steps {
    display: flex;
    justify-content: space-between;
    position: absolute;
    top: -38px;
    left: 0;
    right: 0;
}

.ura-timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.ura-step-bubble {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.ura-timeline-step.completed .ura-step-bubble {
    background: var(--ura-primary);
    border-color: var(--ura-primary);
    color: white;
    animation: stepComplete 0.5s ease;
}

.ura-timeline-step.rejected .ura-step-bubble {
    background: var(--ura-danger);
    border-color: var(--ura-danger);
    color: white;
}

.ura-timeline-step.pending .ura-step-bubble {
    background: white;
    border-color: var(--ura-warning);
    color: var(--ura-warning);
}

@keyframes stepComplete {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.ura-step-content {
    margin-top: 10px;
    text-align: center;
}

.ura-step-title {
    display: block;
    font-weight: 600;
    color: var(--ura-dark);
    font-size: 14px;
}

.ura-step-date {
    display: block;
    font-size: 12px;
    color: var(--ura-grey);
    margin-top: 2px;
}

/* Content Area */
.ura-content-area {
    padding: 0 15px 30px;
    position: relative;
    z-index: 1;
}

/* Modern Cards */
.ura-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.ura-card:hover {
    box-shadow: 0 8px 30px rgba(0, 51, 102, 0.12);
    transform: translateY(-2px);
}

.ura-card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid rgba(0, 51, 102, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ura-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--ura-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.ura-card-title i {
    color: var(--ura-secondary);
    font-size: 20px;
}

.ura-card-body {
    padding: 25px;
}

/* Employee Card Specific */
.ura-employee-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0, 51, 102, 0.05);
    margin-bottom: 20px;
}

.ura-avatar-wrapper {
    position: relative;
}

.ura-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
    position: relative;
}

.ura-avatar-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    font-size: 10px;
}

.ura-avatar-badge.success {
    background: var(--ura-success);
    color: white;
}

.ura-avatar-badge.warning {
    background: var(--ura-warning);
    color: white;
}

.ura-employee-name {
    font-size: 24px;
    font-weight: 700;
    color: var(--ura-dark);
    margin: 0 0 5px;
}

.ura-employee-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.ura-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--ura-grey);
    font-size: 14px;
}

.ura-meta-item i {
    color: var(--ura-secondary);
    font-size: 12px;
}

/* Info Grid */
.ura-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.ura-info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.ura-info-label {
    font-size: 12px;
    color: var(--ura-grey);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ura-info-label i {
    color: var(--ura-secondary);
    font-size: 12px;
}

.ura-info-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--ura-dark);
}

/* Form Styling */
.ura-form-section {
    margin-bottom: 30px;
}

.ura-section-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--ura-dark);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--ura-light);
}

.ura-form-group {
    margin-bottom: 20px;
}

.ura-form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    color: var(--ura-dark);
    margin-bottom: 8px;
}

.ura-form-label i {
    color: var(--ura-secondary);
    font-size: 14px;
}

.ura-input-wrapper {
    position: relative;
}

.ura-form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.ura-form-control:focus {
    outline: none;
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.1);
}

.ura-form-control.editable {
    background: #f8f9fa;
}

.ura-form-control.editable:focus {
    background: white;
}

.ura-form-control[readonly] {
    background: #f8f9fa;
    cursor: not-allowed;
}

.ura-input-suffix {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ura-grey);
    font-size: 14px;
    font-weight: 500;
}

/* Form Actions */
.ura-form-actions {
    display: flex;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 51, 102, 0.05);
}

.ura-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.ura-btn-primary {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
}

.ura-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
}

.ura-btn-secondary {
    background: #e9ecef;
    color: var(--ura-dark);
}

.ura-btn-secondary:hover {
    background: #dee2e6;
}

.ura-btn-success {
    background: linear-gradient(135deg, var(--ura-success) 0%, #20c997 100%);
    color: white;
}

.ura-btn-danger {
    background: linear-gradient(135deg, var(--ura-danger) 0%, #c82333 100%);
    color: white;
}

/* Action Grid */
.ura-action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.ura-action-item {
    padding: 15px;
    border-radius: 10px;
    border: 2px solid transparent;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-align: center;
}

.ura-action-item i {
    font-size: 24px;
}

.ura-action-item span {
    font-size: 12px;
    font-weight: 600;
}

.ura-action-item.success {
    color: var(--ura-success);
}

.ura-action-item.success:hover {
    background: rgba(40, 167, 69, 0.1);
    border-color: var(--ura-success);
}

.ura-action-item.danger {
    color: var(--ura-danger);
}

.ura-action-item.danger:hover {
    background: rgba(220, 53, 69, 0.1);
    border-color: var(--ura-danger);
}

.ura-action-item.primary {
    color: var(--ura-primary);
}

.ura-action-item.primary:hover {
    background: rgba(0, 51, 102, 0.1);
    border-color: var(--ura-primary);
}

.ura-action-item.secondary {
    color: var(--ura-grey);
}

.ura-action-item.secondary:hover {
    background: rgba(108, 117, 125, 0.1);
    border-color: var(--ura-grey);
}

.ura-action-item.info {
    color: var(--ura-info);
}

.ura-action-item.info:hover {
    background: rgba(23, 162, 184, 0.1);
    border-color: var(--ura-info);
}

/* Status Items */
.ura-status-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ura-status-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.ura-status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.ura-status-icon.success {
    background: rgba(40, 167, 69, 0.1);
    color: var(--ura-success);
}

.ura-status-icon.danger {
    background: rgba(220, 53, 69, 0.1);
    color: var(--ura-danger);
}

.ura-status-icon.warning {
    background: rgba(255, 140, 0, 0.1);
    color: var(--ura-warning);
}

.ura-status-icon.primary {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
}

.ura-status-icon.secondary {
    background: rgba(108, 117, 125, 0.1);
    color: var(--ura-grey);
}

.ura-status-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.ura-status-label {
    font-size: 12px;
    color: var(--ura-grey);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ura-status-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--ura-dark);
}

/* Document List */
.ura-document-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ura-document-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.ura-document-item:hover {
    background: #e9ecef;
}

.ura-document-item i {
    color: var(--ura-secondary);
    font-size: 18px;
}

.ura-document-item span {
    flex: 1;
    font-size: 14px;
    color: var(--ura-dark);
}

.ura-doc-action {
    background: transparent;
    border: none;
    color: var(--ura-grey);
    cursor: pointer;
    transition: all 0.3s ease;
}

.ura-doc-action:hover {
    color: var(--ura-primary);
}

/* Modal Styling */
.ura-modal .modal-content {
    border-radius: 15px;
    border: none;
    overflow: hidden;
}

.ura-modal-header {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    padding: 20px 25px;
    border: none;
}

.ura-modal-header .modal-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
}

.ura-modal-body {
    padding: 25px;
}

.ura-modal-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin: 15px 0;
}

.ura-info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.ura-info-row span {
    color: var(--ura-grey);
    font-size: 14px;
}

.ura-info-row strong {
    color: var(--ura-dark);
    font-weight: 600;
}

.ura-modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Responsive Design */
@media (max-width: 992px) {
    .ura-timeline-steps {
        position: relative;
        top: 0;
        margin-top: 20px;
    }
    
    .ura-employee-header {
        flex-direction: column;
        text-align: center;
    }
    
    .ura-action-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .ura-page-title {
        font-size: 22px;
    }
    
    .ura-info-grid {
        grid-template-columns: 1fr;
    }
    
    .ura-timeline-steps {
        flex-direction: column;
        gap: 20px;
    }
}

/* Print Styles */
@media print {
    .ura-action-buttons,
    .ura-actions-card,
    .ura-btn,
    .ura-card-actions {
        display: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Loan data for cancellation checks
const loanData = {
    approval: '{{ $loanOffer->approval }}',
    status: '{{ $loanOffer->status }}',
    state: '{{ $loanOffer->state }}'
};

// Check if loan is cancelled
function isLoanCancelled() {
    const cancelledValues = ['CANCELLED', 'CANCELED'];
    const cancellationStates = ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'];
    
    return cancelledValues.includes((loanData.approval || '').toUpperCase()) ||
           cancelledValues.includes((loanData.status || '').toUpperCase()) ||
           cancellationStates.includes((loanData.state || '').toUpperCase());
}

// Calculate loan metrics
function calculateLoanMetrics() {
    const requestedAmount = parseFloat(document.querySelector('[name="requested_amount"]').value.replace(/,/g, ''));
    const basicSalary = parseFloat(document.querySelector('[name="basic_salary"]').value.replace(/,/g, ''));
    
    // Calculate deductible (33% of basic salary)
    const deductible = basicSalary * 0.33;
    document.querySelector('[name="deductible_amount"]').value = numberWithCommas(deductible.toFixed(0));
    
    // Calculate take home
    const takeHome = basicSalary - deductible;
    document.querySelector('[name="take_home_amount"]').value = numberWithCommas(takeHome.toFixed(0));
}

// Number formatting
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Approve loan
function approveLoan(loanId) {
    // Check if loan is cancelled
    if (isLoanCancelled()) {
        Swal.fire({
            title: 'Cannot Approve',
            html: `
                <div class="text-center">
                    <i class="fas fa-ban mb-3" style="font-size: 48px; color: #dc3545;"></i>
                    <p>This loan has been <strong>cancelled by the employee</strong> through ESS.</p>
                    <small class="text-muted">Cancelled loans cannot be approved or processed.</small>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Check if already approved or rejected
    const approval = '{{ $loanOffer->approval }}';
    if (approval === 'APPROVED') {
        Swal.fire({
            title: 'Already Approved',
            text: 'This loan has already been approved.',
            icon: 'info',
            confirmButtonColor: '#003366'
        });
        return;
    }
    if (approval === 'REJECTED') {
        Swal.fire({
            title: 'Already Rejected',
            text: 'This loan has already been rejected and cannot be approved.',
            icon: 'warning',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    $('#approvalModal').modal('show');
}

// Confirm approval
function confirmApproval() {
    const comments = document.getElementById('approvalComments').value;
    const loanId = {{ $loanOffer->id }};
    
    // Show loading
    Swal.fire({
        title: 'Processing...',
        text: 'Approving loan application',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call using the existing updateLoanOffer endpoint
    fetch(`/loan-offers/${loanId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            approval: 'APPROVED',
            reason: comments || 'Approved by user',
            status: 'APPROVED'
        })
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then(data => {
                if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            });
        } else {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                return Promise.reject({
                    error: 'SERVER_ERROR',
                    message: 'Server returned an invalid response'
                });
            });
        }
    })
    .then(data => {
        $('#approvalModal').modal('hide');
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Approved!',
                text: data.message || 'Loan application has been approved successfully.',
                confirmButtonColor: '#003366'
            }).then(() => {
                location.reload();
            });
        } else {
            throw data;
        }
    })
    .catch(error => {
        $('#approvalModal').modal('hide');
        let errorMessage = 'Failed to approve loan application.';
        if (error.error === 'LOAN_CANCELLED') {
            errorMessage = 'This loan has been cancelled by the employee and cannot be approved.';
        } else if (error.message) {
            errorMessage = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonColor: '#003366'
        });
    });
}

// Reject loan
function rejectLoan(loanId) {
    // Check if loan is cancelled
    if (isLoanCancelled()) {
        Swal.fire({
            title: 'Cannot Reject',
            html: `
                <div class="text-center">
                    <i class="fas fa-ban mb-3" style="font-size: 48px; color: #dc3545;"></i>
                    <p>This loan has been <strong>cancelled by the employee</strong> through ESS.</p>
                    <small class="text-muted">Cancelled loans cannot be rejected or processed.</small>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Check if already approved or rejected
    const approval = '{{ $loanOffer->approval }}';
    if (approval === 'APPROVED') {
        Swal.fire({
            title: 'Already Approved',
            text: 'This loan has already been approved and cannot be rejected.',
            icon: 'warning',
            confirmButtonColor: '#003366'
        });
        return;
    }
    if (approval === 'REJECTED') {
        Swal.fire({
            title: 'Already Rejected',
            text: 'This loan has already been rejected.',
            icon: 'info',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    Swal.fire({
        title: 'Reject Loan Application',
        text: 'Please provide a reason for rejection:',
        input: 'textarea',
        inputPlaceholder: 'Enter rejection reason...',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Reject',
        inputValidator: (value) => {
            if (!value) {
                return 'Rejection reason is required!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Process rejection
            processRejection(loanId, result.value);
        }
    });
}

// Process rejection
function processRejection(loanId, reason) {
    Swal.fire({
        title: 'Processing...',
        text: 'Rejecting loan application',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Use the existing updateLoanOffer endpoint
    fetch(`/loan-offers/${loanId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            approval: 'REJECTED',
            reason: reason,
            status: 'REJECTED'
        })
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then(data => {
                if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            });
        } else {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                return Promise.reject({
                    error: 'SERVER_ERROR',
                    message: 'Server returned an invalid response'
                });
            });
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Rejected!',
                text: data.message || 'Loan application has been rejected.',
                confirmButtonColor: '#003366'
            }).then(() => {
                location.reload();
            });
        } else {
            throw data;
        }
    })
    .catch(error => {
        let errorMessage = 'Failed to reject loan application.';
        if (error.error === 'LOAN_CANCELLED') {
            errorMessage = 'This loan has been cancelled by the employee and cannot be rejected.';
        } else if (error.message) {
            errorMessage = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonColor: '#003366'
        });
    });
}

// Disburse loan
function disburseLoan(loanId) {
    // Check if loan is cancelled
    if (isLoanCancelled()) {
        Swal.fire({
            title: 'Cannot Disburse',
            html: `
                <div class="text-center">
                    <i class="fas fa-ban mb-3" style="font-size: 48px; color: #dc3545;"></i>
                    <p>This loan has been <strong>cancelled by the employee</strong> through ESS.</p>
                    <small class="text-muted">Cancelled loans cannot be disbursed.</small>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Disburse Loan',
        text: 'Are you sure you want to disburse this loan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#003366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Disburse'
    }).then((result) => {
        if (result.isConfirmed) {
            processDisbursement(loanId);
        }
    });
}

// Process disbursement
function processDisbursement(loanId) {
    Swal.fire({
        title: 'Processing...',
        text: 'Submitting loan for disbursement to NMB',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Use the updateLoanOffer endpoint with SUBMITTED_FOR_DISBURSEMENT status to trigger NMB disbursement
    fetch(`/loan-offers/${loanId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            status: 'SUBMITTED_FOR_DISBURSEMENT'
        })
    })
    .then(response => {
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json().then(data => {
                if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            });
        } else {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                return Promise.reject({
                    error: 'SERVER_ERROR',
                    message: 'Server returned an invalid response'
                });
            });
        }
    })
    .then(data => {
        if (data.success || data.message) {
            Swal.fire({
                icon: 'success',
                title: 'Submitted!',
                text: data.message || 'Loan has been submitted to NMB for disbursement. You will be notified once the disbursement is complete.',
                confirmButtonColor: '#003366'
            }).then(() => {
                location.reload();
            });
        } else {
            throw data;
        }
    })
    .catch(error => {
        let errorMessage = 'Failed to submit loan for disbursement.';
        if (error.error === 'LOAN_CANCELLED') {
            errorMessage = 'This loan has been cancelled by the employee and cannot be disbursed.';
        } else if (error.message) {
            errorMessage = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonColor: '#003366'
        });
    });
}

// View history - redirects to callbacks page
function viewHistory(loanId) {
    window.location.href = `/loan-offers/${loanId}/callbacks`;
}

// Print loan
function printLoan() {
    window.print();
}

// Export PDF
function exportPDF() {
    window.print();
}

// Share loan - removed as not needed
function shareLoan() {
    Swal.fire({
        icon: 'info',
        title: 'Feature Not Available',
        text: 'Share functionality is not available.',
        confirmButtonColor: '#003366'
    });
}

// Upload document - removed as not needed
function uploadDocument() {
    Swal.fire({
        icon: 'info',
        title: 'Feature Not Available',
        text: 'Document upload should be done through ESS.',
        confirmButtonColor: '#003366'
    });
}


// Toggle card expand
function toggleCardExpand(button) {
    const card = button.closest('.ura-card');
    card.classList.toggle('expanded');
    const icon = button.querySelector('i');
    if (card.classList.contains('expanded')) {
        icon.classList.remove('fa-expand-alt');
        icon.classList.add('fa-compress-alt');
    } else {
        icon.classList.remove('fa-compress-alt');
        icon.classList.add('fa-expand-alt');
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // Auto-update SWIFT code when bank changes
    const bankSelect = document.querySelector('select[name="bank_id"]');
    if (bankSelect) {
        bankSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const swiftCode = selectedOption.getAttribute('data-swift');
            // You can display the SWIFT code somewhere if needed
        });
    }
});
</script>
@endpush