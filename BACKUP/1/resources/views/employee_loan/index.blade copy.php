@extends('layouts.app')

@section('content')
<!-- Animated Particle Background -->
<div class="particle-container">
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
</div>

<!-- Morphing Blob Background -->
<div class="blob-container">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="container-fluid py-2 bg-gradient position-relative">
    <!-- Breadcrumb at the top -->
    <nav aria-label="breadcrumb" class="mb-2 animate-slide-down">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item">Employee Loan</li>
            <li class="breadcrumb-item active fw-bold">ESS Loans</li>
        </ol>
    </nav>

    <!-- Compact Page Header with Animation -->
    <div class="page-header-compact mb-3 animate-fade-in">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center">
                    <div class="icon-pulse me-2">
                        <div class="icon-box-compact">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="page-title-compact mb-0">
                            ESS Loan Applications
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            Member employee loan requests from ESS portal
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-2 mt-lg-0">
                <div class="action-buttons-compact">
                    <button class="btn btn-ura-primary-sm animate-hover" onclick="refreshFromESS()">
                        <i class="fas fa-sync-alt me-1"></i>Sync ESS
                    </button>
                    <div class="btn-group ms-2">
                        <button class="btn btn-ura-light-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                            <li><a class="dropdown-item" href="#" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-2 text-success"></i>Export Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="showBulkActions()">
                                <i class="fas fa-tasks me-2"></i>Bulk Actions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern KPI Dashboard with Glassmorphism -->
    <div class="kpi-dashboard-modern mb-3">
        <div class="kpi-backdrop"></div>
        <div class="row g-3">
            <!-- Pending Card -->
            <div class="col">
                <div class="kpi-card-modern glass-card animate-float-1" onclick="showKPIDetails('pending')" data-status="pending">
                    <div class="kpi-glow pending-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern pending-icon">
                                <i class="fas fa-hourglass-half"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern">PENDING</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $pendingCount ?? 0 }}">
                                <span class="number-animate">{{ $pendingCount ?? 0 }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon pending-trend">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <span class="trend-text" id="pending-weekly-txt">+0 this week</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="pending-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill pending-progress" style="width: 0%"></div>
                            </div>
                            <span class="footer-text">Processing <span id="pending-weekly">0</span> applications</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="col">
                <div class="kpi-card-modern glass-card animate-float-2" onclick="showKPIDetails('approved')" data-status="approved">
                    <div class="kpi-glow approved-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern approved-icon">
                                <i class="fas fa-check-circle"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern success-badge">APPROVED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $approvedCount ?? 0 }}">
                                <span class="number-animate">{{ $approvedCount ?? 0 }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon approved-trend">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
                                <span class="trend-text text-success" id="approved-weekly-txt">+0 this week</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="approved-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill approved-progress" style="width: 0%"></div>
                            </div>
                            <span class="footer-text">Completed <span id="approved-weekly">0</span> approvals</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="col">
                <div class="kpi-card-modern glass-card animate-float-3" onclick="showKPIDetails('rejected')" data-status="rejected">
                    <div class="kpi-glow rejected-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern rejected-icon">
                                <i class="fas fa-times-circle"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern danger-badge">REJECTED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $rejectedCount ?? 0 }}">
                                <span class="number-animate">{{ $rejectedCount ?? 0 }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon rejected-trend">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span class="trend-text text-danger" id="rejected-weekly-txt">0 this week</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="rejected-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill rejected-progress" style="width: 0%"></div>
                            </div>
                            <span class="footer-text">Declined <span id="rejected-weekly">0</span> requests</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Cancelled Card -->
            <div class="col">
                <div class="kpi-card-modern glass-card animate-float-4" onclick="showKPIDetails('cancelled')" data-status="cancelled">
                    <div class="kpi-glow cancelled-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern cancelled-icon">
                                <i class="fas fa-ban"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern secondary-badge">CANCELLED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $cancelledCount ?? 0 }}">
                                <span class="number-animate">{{ $cancelledCount ?? 0 }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon cancelled-trend">
                                    <i class="fas fa-ban"></i>
                                </span>
                                <span class="trend-text text-secondary" id="cancelled-weekly-txt">0 this week</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="cancelled-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill cancelled-progress" style="width: 0%"></div>
                            </div>
                            <span class="footer-text">Withdrawn <span id="cancelled-weekly">0</span> applications</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>

            <!-- Disbursed Card -->
            <div class="col">
                <div class="kpi-card-modern glass-card animate-float-5" onclick="showKPIDetails('disbursed')" data-status="disbursed">
                    <div class="kpi-glow disbursed-glow"></div>
                    <div class="kpi-content-modern">
                        <div class="kpi-header-modern">
                            <div class="kpi-icon-modern disbursed-icon">
                                <i class="fas fa-money-bill-wave"></i>
                                <div class="icon-pulse-ring"></div>
                            </div>
                            <div class="kpi-badge-modern primary-badge">DISBURSED</div>
                        </div>
                        <div class="kpi-value-wrapper">
                            <h2 class="kpi-value-modern" data-value="{{ $disbursedCount ?? 0 }}">
                                <span class="number-animate">{{ $disbursedCount ?? 0 }}</span>
                            </h2>
                            <div class="kpi-trend-modern">
                                <span class="trend-icon disbursed-trend">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <span class="trend-text" style="color: #17479E;" id="disbursed-weekly-txt">+0 this week</span>
                            </div>
                        </div>
                        <div class="kpi-chart-modern">
                            <canvas id="disbursed-sparkline" width="100" height="30"></canvas>
                        </div>
                        <div class="kpi-footer-modern">
                            <div class="progress-bar-modern">
                                <div class="progress-fill disbursed-progress" style="width: 0%"></div>
                            </div>
                            <span class="footer-text">Paid out <span id="disbursed-weekly">0</span> loans</span>
                        </div>
                    </div>
                    <div class="card-shine"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced URASACCOS Filter Section with Animation -->
    <div class="filter-card-ura mb-3 animate-fade-in-delayed">
        <div class="filter-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="filter-title mb-0">
                        <i class="fas fa-filter filter-icon"></i>
                        Smart Filters
                    </h5>
                    <p class="filter-subtitle mb-0">Quick access to filter options</p>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-sm btn-ura-light" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                        <i class="fas fa-sliders-h me-1"></i>Advanced
                    </button>
                    <button class="btn btn-sm btn-ura-light" onclick="resetFilters()">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                    <button class="btn btn-sm btn-ura-primary" onclick="saveFilterPreset()">
                        <i class="fas fa-save me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>

        <div class="filter-body">
            <form method="GET" action="{{ route('loan-offers.index') }}" id="filter-form">
                <!-- Quick Filter Pills - Always Visible -->
                <div class="filter-pills mb-3">
                    <span class="filter-pills-label">Quick Filters:</span>
                    <div class="filter-pills-group">
                        <button type="button" class="filter-pill" onclick="applyQuickFilter('today')" data-filter="today">
                            <i class="fas fa-calendar-day me-1"></i>Today
                            <span class="filter-pill-count" id="today-count">0</span>
                        </button>
                        <button type="button" class="filter-pill" onclick="applyQuickFilter('week')" data-filter="week">
                            <i class="fas fa-calendar-week me-1"></i>This Week
                            <span class="filter-pill-count" id="week-count">0</span>
                        </button>
                        <button type="button" class="filter-pill" onclick="applyQuickFilter('month')" data-filter="month">
                            <i class="fas fa-calendar-alt me-1"></i>This Month
                            <span class="filter-pill-count" id="month-count">0</span>
                        </button>
                        <button type="button" class="filter-pill filter-pill-pending" onclick="applyQuickFilter('pending')" data-filter="pending">
                            <i class="fas fa-clock me-1"></i>Pending
                            <span class="filter-pill-count">{{ $pendingCount }}</span>
                        </button>
                        <button type="button" class="filter-pill filter-pill-approved" onclick="applyQuickFilter('approved')" data-filter="approved">
                            <i class="fas fa-check-circle me-1"></i>Approved
                            <span class="filter-pill-count">{{ $approvedCount }}</span>
                        </button>
                        <button type="button" class="filter-pill filter-pill-disbursed" onclick="applyQuickFilter('disbursed')" data-filter="disbursed">
                            <i class="fas fa-money-bill-wave me-1"></i>Disbursed
                            <span class="filter-pill-count">{{ $disbursedCount }}</span>
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters - Collapsible -->
                <div class="collapse" id="advancedFilters">
                    <div class="advanced-filter-container">
                        <!-- Main Filter Row -->
                        <div class="row g-3 mb-3">
                    <!-- Search Box -->
                    <div class="col-lg-4 col-md-6">
                        <div class="filter-input-group">
                            <span class="filter-input-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search"
                                   class="form-control filter-input"
                                   placeholder="Search by name, application #, check #..."
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <button type="button" class="filter-clear-btn" onclick="clearSearchField()">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Status Dropdown with Counts -->
                    <div class="col-lg-3 col-md-6">
                        <select name="status" class="form-select filter-select" id="status-filter">
                            <option value="">All Status ({{ $loanOffers->total() }})</option>
                            <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>
                                🕐 Pending ({{ $pendingCount }})
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                ✅ Approved ({{ $approvedCount }})
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                ❌ Rejected ({{ $rejectedCount }})
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                🚫 Cancelled ({{ $cancelledCount }})
                            </option>
                            <option value="disbursement_pending" {{ request('status') == 'disbursement_pending' ? 'selected' : '' }}>
                                ⏳ Processing ({{ $pendingNMBCount ?? 0 }})
                            </option>
                            <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>
                                ✔️ Disbursed ({{ $disbursedCount }})
                            </option>
                        </select>
                    </div>

                    <!-- Date Range Picker -->
                    <div class="col-lg-2 col-md-6">
                        <div class="filter-date-group">
                            <input type="date" name="date_from"
                                   class="form-control filter-date"
                                   value="{{ request('date_from') }}"
                                   id="date-from">
                            <label class="filter-date-label">From</label>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="filter-date-group">
                            <input type="date" name="date_to"
                                   class="form-control filter-date"
                                   value="{{ request('date_to') }}"
                                   id="date-to">
                            <label class="filter-date-label">To</label>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <div class="col-lg-1 col-md-12">
                        <button type="submit" class="btn btn-ura-primary btn-filter-apply">
                            <i class="fas fa-filter"></i>
                            <span class="d-lg-none ms-2">Apply</span>
                        </button>
                    </div>
                </div>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <div class="active-filters mt-3">
                    <span class="active-filters-label">Active Filters:</span>
                    @if(request('search'))
                        <span class="active-filter-tag">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('loan-offers.index', array_diff_key(request()->all(), ['search' => ''])) }}" class="filter-tag-remove">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="active-filter-tag">
                            Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                            <a href="{{ route('loan-offers.index', array_diff_key(request()->all(), ['status' => ''])) }}" class="filter-tag-remove">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('date_from'))
                        <span class="active-filter-tag">
                            From: {{ request('date_from') }}
                            <a href="{{ route('loan-offers.index', array_diff_key(request()->all(), ['date_from' => ''])) }}" class="filter-tag-remove">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('date_to'))
                        <span class="active-filter-tag">
                            To: {{ request('date_to') }}
                            <a href="{{ route('loan-offers.index', array_diff_key(request()->all(), ['date_to' => ''])) }}" class="filter-tag-remove">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Enhanced Loan Applications Table -->
    <div class="modern-table-wrapper">
        <div class="modern-table-header-section">
            <div class="table-header-content">
                <div class="table-title-group">
                    <div class="table-icon-wrapper">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="table-title-text">
                        <h4 class="table-main-title">Loan Applications</h4>
                        <div class="table-subtitle">
                            <span class="record-count">
                                <span class="count-number">{{ $loanOffers->count() }}</span>
                                <span class="count-text">of {{ $loanOffers->total() ?? 0 }}</span>
                            </span>
                            <span class="separator">•</span>
                            <span class="filter-status">
                                @if(request()->has('status_filter') || request()->has('approval_filter') || request()->has('date_from') || request()->has('date_to'))
                                    <i class="fas fa-filter filter-active"></i> Filtered
                                @else
                                    <i class="fas fa-list"></i> All Records
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="table-actions-group">
                    <div class="quick-stats">
                        <div class="stat-item stat-pending">
                            <span class="stat-value">{{ $loanOffers->whereIn('approval', ['PENDING', null])->count() }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                        <div class="stat-item stat-approved">
                            <span class="stat-value">{{ $loanOffers->where('approval', 'APPROVED')->count() }}</span>
                            <span class="stat-label">Approved</span>
                        </div>
                        <div class="stat-item stat-amount">
                            <span class="stat-value">{{ number_format($loanOffers->sum('requested_amount') / 1000000, 1) }}M</span>
                            <span class="stat-label">Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modern-table-body-wrapper">
            <div class="modern-table-container">
                <table class="modern-data-table" id="loanApplicationsTable">
                    <thead class="modern-table-header">
                        <tr>
                            <th class="checkbox-column">
                                <div class="modern-checkbox">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all"></label>
                                </div>
                            </th>
                            <th class="sortable-column" onclick="sortTable('name')">
                                <div class="th-content">
                                    <span class="th-text">Employee</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column text-end" onclick="sortTable('salary')">
                                <div class="th-content justify-content-end">
                                    <span class="th-text">Salary</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column text-end" onclick="sortTable('deductible')">
                                <div class="th-content justify-content-end">
                                    <span class="th-text">Deductible</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column text-end" onclick="sortTable('requested')">
                                <div class="th-content justify-content-end">
                                    <span class="th-text">Requested</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="sortable-column text-end" onclick="sortTable('net_loan')">
                                <div class="th-content justify-content-end">
                                    <span class="th-text">Take Home</span>
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th class="text-center">Employee Bank</th>
                            <th class="text-center">Tenure</th>
                            <th class="text-center">Submitted</th>
                            <th class="text-center">Approval</th>
                            <th class="text-center">Status</th>
                            <th class="text-center action-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="modern-table-body">
                        @forelse ($loanOffers as $offer)
                            @php
                                $loanData = $offer->toArray();
                                if ($offer->bank) {
                                    $loanData['bank'] = [
                                        'id' => $offer->bank->id,
                                        'name' => $offer->bank->name,
                                        'short_name' => $offer->bank->short_name,
                                        'swift_code' => $offer->bank->swift_code
                                    ];
                                } else {
                                    $loanData['bank'] = null;
                                }
                            @endphp
                            <tr class="modern-table-row clickable-row" data-id="{{ $offer->id }}" data-loan='{{ json_encode($loanData) }}'>
                                <td class="checkbox-column">
                                    <div class="modern-checkbox">
                                        <input type="checkbox" class="loan-checkbox" value="{{ $offer->id }}" id="check-{{ $offer->id }}">
                                        <label for="check-{{ $offer->id }}"></label>
                                    </div>
                                </td>
                                <td class="employee-column">
                                    <div class="employee-info">
                                        <div class="employee-details">
                                            <div class="employee-name" title="{{ $offer->first_name }} {{ $offer->middle_name }} {{ $offer->last_name }}">
                                                {{ $offer->first_name }} {{ $offer->last_name }}
                                            </div>
                                            <div class="employee-id">{{ $offer->check_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="amount-cell">
                                        <div class="amount-primary">{{ number_format($offer->basic_salary ?? 0, 0) }}</div>
                                        <div class="amount-secondary">Net: {{ number_format($offer->net_salary ?? 0, 0) }}</div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="amount-deductible">{{ number_format($offer->desired_deductible_amount ?? 0, 0) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="amount-requested">{{ number_format($offer->requested_amount ?? 0, 0) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="amount-takehome">{{ number_format($offer->take_home_amount ?? $offer->net_loan_amount ?? 0, 0) }}</div>
                                </td>
                                <td class="text-center">
                                    @if($offer->bank)
                                        <span class="modern-badge badge-info" title="Employee's Salary Bank: {{ $offer->bank->name }} ({{ $offer->bank->swift_code }})">
                                            <i class="fas fa-university me-1"></i>
                                            {{ $offer->bank->short_name ?: $offer->bank->name }}
                                        </span>
                                    @elseif($offer->swift_code)
                                        <span class="text-muted small" title="SWIFT Code">
                                            <i class="fas fa-barcode me-1"></i>{{ $offer->swift_code }}
                                        </span>
                                    @else
                                        <span class="text-muted">No bank info</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($offer->tenure)
                                        <span class="modern-badge badge-tenure">{{ $offer->tenure }} mo</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $isPending = in_array($offer->approval, ['PENDING', null]);
                                        $daysWaiting = $offer->created_at ? now()->diffInDays($offer->created_at) : 0;
                                        $hoursWaiting = $offer->created_at ? now()->diffInHours($offer->created_at) : 0;
                                    @endphp
                                    
                                    <div class="d-flex flex-column align-items-center">
                                        <small class="text-muted">
                                            {{ $offer->created_at ? $offer->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                        @if($isPending)
                                            @if($daysWaiting > 7)
                                                <span class="badge bg-danger mt-1" title="Waiting for {{ $daysWaiting }} days">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $daysWaiting }}d waiting
                                                </span>
                                            @elseif($daysWaiting > 3)
                                                <span class="badge bg-warning mt-1" title="Waiting for {{ $daysWaiting }} days">
                                                    <i class="fas fa-clock me-1"></i>{{ $daysWaiting }}d waiting
                                                </span>
                                            @elseif($daysWaiting >= 1)
                                                <span class="badge bg-info mt-1" title="Waiting for {{ $daysWaiting }} days">
                                                    <i class="fas fa-hourglass-half me-1"></i>{{ $daysWaiting }}d waiting
                                                </span>
                                            @elseif($hoursWaiting < 24)
                                                <span class="badge bg-success mt-1" title="Submitted {{ $hoursWaiting }} hours ago">
                                                    <i class="fas fa-clock me-1"></i>{{ $hoursWaiting }}h ago
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @switch($offer->approval)
                                        @case('APPROVED')
                                            <span class="modern-status-badge status-approved" title="Approved by URAERP" data-bs-toggle="tooltip">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </span>
                                            @break
                                        @case('REJECTED')
                                            <span class="modern-status-badge status-rejected" title="Rejected by URAERP" data-bs-toggle="tooltip">
                                                <i class="fas fa-times-circle"></i> Rejected
                                            </span>
                                            @break
                                        @case('CANCELLED')
                                            <span class="modern-status-badge status-cancelled" title="Cancelled by Employee" data-bs-toggle="tooltip">
                                                <i class="fas fa-ban"></i> Cancelled
                                            </span>
                                            @break
                                        @case('PENDING')
                                            <span class="modern-status-badge status-pending" title="Pending Approval" data-bs-toggle="tooltip">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                            @break
                                        @default
                                            <span class="modern-status-badge status-pending" title="Pending" data-bs-toggle="tooltip">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @php
                                        $state = $offer->state ?? $offer->status ?? 'Initiated';
                                    @endphp
                                    @switch(strtolower($state))
                                        @case('initiated')
                                            <span class="modern-status-badge status-new" title="Created but not submitted">
                                                <i class="fas fa-file-alt"></i> Initiated
                                            </span>
                                            @break
                                        @case('loan offer at fsp')
                                            <span class="modern-status-badge status-processing" title="Loan submitted to FSP">
                                                <i class="fas fa-building"></i> At FSP
                                            </span>
                                            @break
                                        @case('fsp rejected')
                                            <span class="modern-status-badge status-rejected" title="Loan rejected by FSP">
                                                <i class="fas fa-times-circle"></i> FSP Rejected
                                            </span>
                                            @break
                                        @case('loan offer at employee')
                                            <span class="modern-status-badge status-approved" title="FSP approved - waiting for employee acceptance">
                                                <i class="fas fa-user-check"></i> FSP Approved - At Employee
                                            </span>
                                            @break
                                        @case('employee rejected')
                                            <span class="modern-status-badge status-cancelled" title="Loan rejected by employee">
                                                <i class="fas fa-user-times"></i> Employee Rejected
                                            </span>
                                            @break
                                        @case('pending for approval')
                                            <span class="modern-status-badge status-warning" title="Employee accept offer and submit to employer">
                                                <i class="fas fa-hourglass-half"></i> Pending Approval
                                            </span>
                                            @break
                                        @case('employee canceled')
                                        @case('employee cancelled')
                                            <span class="modern-status-badge status-cancelled" title="Loan request cancelled by employee">
                                                <i class="fas fa-user-slash"></i> Employee Cancelled
                                            </span>
                                            @break
                                        @case('employer rejected')
                                            <span class="modern-status-badge status-rejected" title="Loan closed by employer">
                                                <i class="fas fa-ban"></i> Employer Rejected
                                            </span>
                                            @break
                                        @case('submitted for disbursement')
                                        @case('submitted_for_disbursement')
                                            <span class="modern-status-badge status-processing" title="Loan approved waiting disbursement">
                                                <i class="fas fa-paper-plane"></i> Submitted for Disbursement
                                            </span>
                                            @break
                                        @case('fsp canceled')
                                        @case('fsp cancelled')
                                            <span class="modern-status-badge status-cancelled" title="Loan request cancelled by FSP">
                                                <i class="fas fa-building"></i> FSP Cancelled
                                            </span>
                                            @break
                                        @case('completed')
                                        @case('disbursed')
                                            <span class="modern-status-badge status-disbursed" title="Loan completed">
                                                <i class="fas fa-check-circle"></i> Completed
                                            </span>
                                            @break
                                        @case('waiting for liquidation')
                                            <span class="modern-status-badge status-info" title="Loan waiting to be liquidated">
                                                <i class="fas fa-clock"></i> Waiting Liquidation
                                            </span>
                                            @break
                                        @case('disbursement failure')
                                        @case('disbursement_failed')
                                            <span class="modern-status-badge status-failed" title="Loan disbursement failure">
                                                <i class="fas fa-exclamation-triangle"></i> Disbursement Failed
                                            </span>
                                            @break
                                        @case('liquidated')
                                        @case('full_settled')
                                            <span class="modern-status-badge status-settled" title="Loan fully settled">
                                                <i class="fas fa-check-double"></i> Liquidated
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="modern-status-badge status-approved" title="Loan approved by employer">
                                                <i class="fas fa-thumbs-up"></i> Employer Approved
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="modern-status-badge status-cancelled" title="Loan cancelled">
                                                <i class="fas fa-ban"></i> Cancelled
                                            </span>
                                            @break
                                        @default
                                            <span class="modern-status-badge status-new">
                                                <i class="fas fa-info-circle"></i> {{ ucfirst(str_replace('_', ' ', $state)) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button type="button"
                                                class="action-btn btn-view view-loan-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#loanDetailsModal"
                                                title="View Details"
                                                data-loan='{{ json_encode($loanData) }}'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a class="action-btn btn-edit"
                                           href="{{ route('loan-offers.edit', $offer->id) }}"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @php
                                            $isCancelled = in_array(strtoupper($offer->approval ?? ''), ['CANCELLED', 'CANCELED']) ||
                                                          in_array(strtoupper($offer->status ?? ''), ['CANCELLED', 'CANCELED']) ||
                                                          in_array(strtoupper($offer->state ?? ''), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                                        @endphp

                                        @if($isCancelled)
                                            <span class="text-danger small" title="This loan has been cancelled by the employee">
                                                <i class="fas fa-ban me-1"></i>Cancelled
                                            </span>
                                        @else
                                            @php
                                                $isLoanCancelled = in_array(strtoupper($offer->approval ?? ''), ['CANCELLED', 'CANCELED']) ||
                                                                  in_array(strtoupper($offer->status ?? ''), ['CANCELLED', 'CANCELED']) ||
                                                                  in_array(strtoupper($offer->state ?? ''), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                                            @endphp
                                            @if($offer->approval !== 'APPROVED' && $offer->approval !== 'REJECTED' && $offer->status !== 'disbursed' && !$isLoanCancelled)
                                                <button class="action-btn btn-approve"
                                                        onclick="approveLoan({{ $offer->id }})"
                                                        title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="action-btn btn-reject"
                                                        onclick="rejectLoan({{ $offer->id }})"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($isLoanCancelled)
                                                <button class="action-btn btn-secondary" disabled
                                                        title="Cannot approve - Loan cancelled by employee">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-folder-open"></i>
                                        </div>
                                        <h5>No Loan Applications Found</h5>
                                        <p>Start by syncing data from ESS or adjusting your filters</p>
                                        <div class="empty-actions">
                                            <button class="modern-btn btn-primary" onclick="refreshFromESS()">
                                                <i class="fas fa-sync-alt"></i> Sync from ESS
                                            </button>
                                            <button class="modern-btn btn-secondary" onclick="clearFilters()">
                                                <i class="fas fa-filter"></i> Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($loanOffers->hasPages())
            <div class="modern-table-footer">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <label class="me-2 text-muted">Show:</label>
                                <select class="form-select form-select-sm w-auto" onchange="changePageSize(this.value)">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="ms-2 text-muted">entries</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted">
                                Showing <strong>{{ $loanOffers->firstItem() ?? 0 }}</strong> to <strong>{{ $loanOffers->lastItem() ?? 0 }}</strong>
                                of <strong>{{ $loanOffers->total() }}</strong> records
                            </small>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            {{ $loanOffers->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Enhanced Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl modal-ura-enhanced">
            <!-- Animated Background Pattern -->
            <div class="modal-pattern"></div>

            <!-- Modal Header with Gradient -->
            <div class="modal-header modal-header-ura border-0 position-relative overflow-hidden">
                <div class="header-content position-relative z-index-1">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-wrapper me-3">
                            <div class="modal-icon-circle">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1 fw-bold text-white">Loan Application Details</h4>
                            <p class="mb-0 small text-white" style="opacity: 0.9;">Comprehensive loan information and status</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-relative z-index-1" data-bs-dismiss="modal"></button>
                <div class="header-decoration"></div>
            </div>
            <div class="modal-body p-0">
                <!-- Enhanced Tab Navigation -->
                <div class="tab-navigation-wrapper">
                    <ul class="nav nav-tabs nav-tabs-ura nav-fill border-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura active" data-bs-toggle="tab" href="#personal-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="tab-label">Personal</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#employment-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <span class="tab-label">Employment</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#loan-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <span class="tab-label">Loan</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#financial-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <span class="tab-label">Financial</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#bank-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-university"></i>
                                </div>
                                <span class="tab-label">Banking</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#status-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <span class="tab-label">Status</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#process-loan">
                                <div class="tab-icon-box">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <span class="tab-label">Process Loan</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal-info">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <h6 class="info-card-title">Basic Information</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Full Name</small>
                                        <div class="fw-semibold" id="modal-full-name"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Check Number</small>
                                        <div class="fw-semibold" id="modal-check-number"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">NIN</small>
                                        <div class="fw-semibold" id="modal-nin"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Gender</small>
                                        <div class="fw-semibold" id="modal-sex"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Marital Status</small>
                                        <div class="fw-semibold" id="modal-marital-status"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-address-book"></i>
                                        </div>
                                        <h6 class="info-card-title">Contact Information</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Mobile Number</small>
                                        <div class="fw-semibold" id="modal-mobile"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Email Address</small>
                                        <div class="fw-semibold" id="modal-email"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Telephone</small>
                                        <div class="fw-semibold" id="modal-telephone"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Physical Address</small>
                                        <div class="fw-semibold" id="modal-address"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Tab -->
                    <div class="tab-pane fade" id="employment-info">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <h6 class="info-card-title">Employment Details</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Designation</small>
                                        <div class="fw-semibold" id="modal-designation"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Vote Name</small>
                                        <div class="fw-semibold" id="modal-vote-name"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Vote Code</small>
                                        <div class="fw-semibold" id="modal-vote-code"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Terms of Employment</small>
                                        <div class="fw-semibold" id="modal-terms"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <h6 class="info-card-title">Employment Dates</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Employment Date</small>
                                        <div class="fw-semibold" id="modal-employment-date"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Confirmation Date</small>
                                        <div class="fw-semibold" id="modal-confirmation-date"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Retirement Date</small>
                                        <div class="fw-semibold" id="modal-retirement-date"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Details Tab -->
                    <div class="tab-pane fade" id="loan-info">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <h6 class="info-card-title">Loan Information</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Application Number</small>
                                        <div class="fw-semibold text-primary" id="modal-app-number"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Loan Purpose</small>
                                        <div class="fw-semibold" id="modal-loan-purpose"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">FSP Reference</small>
                                        <div class="fw-semibold" id="modal-fsp-ref"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Loan Number</small>
                                        <div class="fw-semibold" id="modal-loan-number"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-percentage"></i>
                                        </div>
                                        <h6 class="info-card-title">Loan Terms</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Tenure</small>
                                        <div class="fw-semibold" id="modal-tenure"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Interest Rate</small>
                                        <div class="fw-semibold" id="modal-interest-rate"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Processing Fee</small>
                                        <div class="fw-semibold" id="modal-processing-fee"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Insurance</small>
                                        <div class="fw-semibold" id="modal-insurance"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information Tab -->
                    <div class="tab-pane fade" id="financial-info">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <h6 class="info-card-title">Salary Information</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Basic Salary</small>
                                        <div class="fw-semibold text-success" id="modal-basic-salary"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Net Salary</small>
                                        <div class="fw-semibold text-success" id="modal-net-salary"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">One Third Amount</small>
                                        <div class="fw-semibold" id="modal-one-third"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Total Deductions</small>
                                        <div class="fw-semibold text-danger" id="modal-deductions"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <h6 class="info-card-title">Loan Amounts</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Requested Amount</small>
                                        <div class="fw-semibold text-primary fs-5" id="modal-requested-amount"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Take Home Amount (Net)</small>
                                        <div class="fw-semibold text-success fs-5" id="modal-net-loan-amount"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Total Amount to Pay</small>
                                        <div class="fw-semibold text-danger fs-5" id="modal-total-amount"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Monthly Deduction</small>
                                        <div class="fw-semibold text-warning" id="modal-monthly-deduction"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Other Charges</small>
                                        <div class="fw-semibold" id="modal-other-charges"></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banking Information Tab -->
                    <div class="tab-pane fade" id="bank-info">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <h6 class="info-card-title">Employee's Salary Bank</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Bank Name</small>
                                        <div class="fw-semibold" id="modal-bank-name"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">SWIFT Code</small>
                                        <div class="fw-semibold" id="modal-swift-code"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Account Number</small>
                                        <div class="fw-semibold" id="modal-account-number"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted d-block text-info"><i class="fas fa-info-circle me-1"></i>Salary & loan disbursement account</small>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <h6 class="info-card-title">URASACCOS Branch</h6>
                                    </div>
                                    <div class="info-card-body">
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Branch Name</small>
                                        <div class="fw-semibold" id="modal-branch-name"></div>
                                    </div>
                                    <div class="info-item mb-2">
                                        <small class="text-muted">Branch Code</small>
                                        <div class="fw-semibold" id="modal-branch-code"></div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted d-block text-info"><i class="fas fa-info-circle me-1"></i>Loan application branch</small>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information Tab -->
                    <div class="tab-pane fade" id="status-info">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <h6 class="info-card-title">Loan Status Information</h6>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-item mb-3">
                                                    <small class="text-muted">Approval Status</small>
                                                    <div class="fw-semibold" id="modal-approval-status"></div>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <small class="text-muted">Processing Status</small>
                                                    <div class="fw-semibold" id="modal-processing-status"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item mb-3">
                                                    <small class="text-muted">Created Date</small>
                                                    <div class="fw-semibold" id="modal-created-date"></div>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <small class="text-muted">Last Updated</small>
                                                    <div class="fw-semibold" id="modal-updated-date"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Process Loan Tab -->
                    <div class="tab-pane fade" id="process-loan">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="info-card-enhanced">
                                    <div class="info-card-header">
                                        <div class="info-card-icon">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <h6 class="info-card-title">Loan Processing Actions</h6>
                                    </div>
                                    <div class="info-card-body p-4">
                                        <!-- Current Status Display -->
                                        <div class="alert alert-info mb-4" id="process-status-alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <div>
                                                    <strong>Current Status:</strong>
                                                    <span id="process-current-approval" class="ms-2"></span>
                                                    <span id="process-current-status" class="ms-2"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cancellation Warning (Hidden by default) -->
                                        <div class="alert alert-danger mb-4" id="process-cancelled-warning" style="display: none;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-ban me-2"></i>
                                                <div>
                                                    <strong>Loan Cancelled:</strong>
                                                    <span class="ms-2">This loan has been cancelled by the employee through ESS and cannot be approved or rejected.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Processing Actions -->
                                        <div class="processing-actions">
                                            <h6 class="mb-3">Select Action:</h6>
                                            <div class="row g-3">
                                                <!-- Approve Action -->
                                                <div class="col-md-6">
                                                    <div class="action-card p-3 border rounded hover-shadow" role="button" onclick="selectProcessAction('approve')">
                                                        <div class="d-flex align-items-center">
                                                            <div class="action-icon text-success me-3">
                                                                <i class="fas fa-check-circle fa-2x"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Approve Loan</h6>
                                                                <small class="text-muted">Approve this loan application</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Reject Action -->
                                                <div class="col-md-6">
                                                    <div class="action-card p-3 border rounded hover-shadow" role="button" onclick="selectProcessAction('reject')">
                                                        <div class="d-flex align-items-center">
                                                            <div class="action-icon text-danger me-3">
                                                                <i class="fas fa-times-circle fa-2x"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Reject Loan</h6>
                                                                <small class="text-muted">Reject this loan application</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Draft/Archive Action -->
                                                <div class="col-md-6">
                                                    <div class="action-card p-3 border rounded hover-shadow" role="button" onclick="selectProcessAction('draft')">
                                                        <div class="d-flex align-items-center">
                                                            <div class="action-icon text-warning me-3">
                                                                <i class="fas fa-archive fa-2x"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Save as Draft</h6>
                                                                <small class="text-muted">Save for later review</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Form (Hidden by default) -->
                                            <div id="process-action-form" class="mt-4" style="display: none;">
                                                <hr>
                                                <form id="loan-process-form">
                                                    <input type="hidden" id="process-loan-id" name="loan_id">
                                                    <input type="hidden" id="process-action-type" name="action">

                                                    <!-- Reason/Comments Field -->
                                                    <div class="mb-3" id="process-reason-field" style="display: none;">
                                                        <label class="form-label">Reason/Comments <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="process-reason" name="reason" rows="3" placeholder="Enter reason or comments..."></textarea>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-secondary" onclick="cancelProcessAction()">Cancel</button>
                                                        <button type="submit" class="btn btn-primary" id="process-submit-btn">
                                                            <span class="spinner-border spinner-border-sm me-2" style="display: none;"></span>
                                                            Submit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select an action for <span id="selected-count">0</span> selected loans:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="bulkApprove()">
                        <i class="fas fa-check me-2"></i>Approve Selected
                    </button>
                    <button class="btn btn-danger" onclick="bulkReject()">
                        <i class="fas fa-times me-2"></i>Reject Selected
                    </button>
                    <button class="btn btn-secondary" onclick="bulkExport()">
                        <i class="fas fa-file-export me-2"></i>Export Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="fab" onclick="showQuickActions()">
    <i class="fas fa-plus"></i>
</div>

<!-- Quick Actions Menu -->
<div id="quick-actions" class="quick-actions-menu">
    <button class="quick-action-btn" onclick="refreshFromESS()" data-tooltip="Sync with ESS">
        <i class="fas fa-sync-alt"></i>
    </button>
    <button class="quick-action-btn" onclick="exportReport('excel')" data-tooltip="Export Excel">
        <i class="fas fa-file-excel"></i>
    </button>
    <button class="quick-action-btn" onclick="exportReport('pdf')" data-tooltip="Export PDF">
        <i class="fas fa-file-pdf"></i>
    </button>
    <button class="quick-action-btn" onclick="showFilters()" data-tooltip="Filter">
        <i class="fas fa-filter"></i>
    </button>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Processing your request...</p>
    </div>
</div>

<!-- KPI Detail Modals -->
<div class="modal fade" id="kpiDetailModal" tabindex="-1" aria-labelledby="kpiDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #003366 0%, #17479E 100%); color: white;">
                <h5 class="modal-title" id="kpiDetailModalLabel" style="color: white;">
                    <i class="fas fa-chart-line me-2"></i>Weekly Loan Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Filter Controls -->
                <div id="kpiFilterControls" class="mb-3" style="display: none;">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Period</label>
                            <select id="kpiPeriodFilter" class="form-select form-select-sm">
                                <option value="auto">Auto (Best Available)</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="all">All Time</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="kpiStartDateDiv" style="display: none;">
                            <label class="form-label small text-muted">Start Date</label>
                            <input type="date" id="kpiStartDate" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="kpiEndDateDiv" style="display: none;">
                            <label class="form-label small text-muted">End Date</label>
                            <input type="date" id="kpiEndDate" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="refreshKPIDetails()">
                                <i class="fas fa-sync-alt me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                    <div id="kpiPeriodInfo" class="alert alert-info mt-2 py-2 px-3 small" style="display: none;">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="kpiPeriodText"></span>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div class="text-center py-3" id="kpiSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Content Area -->
                <div id="kpiDetailContent" style="display: none;">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" style="background: var(--ura-primary); border-color: var(--ura-primary);" onclick="exportKPIData()">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Reject Loan Modal -->
@include('partials.reject-loan-modal')

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
    --ura-success: #28a745;
    --ura-warning: #FF8C00;
    --ura-danger: #dc3545;
    --ura-grey: #6c757d;
    --primary-gradient: linear-gradient(135deg, #003366 0%, #17479E 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --grey-gradient: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
}

/* Modern Table Wrapper */
.modern-table-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.08);
    margin-bottom: 30px;
}

/* Modern Table Header Section */
.modern-table-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    padding: 20px 25px;
    position: relative;
    overflow: hidden;
}

.modern-table-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #17479E 0%, #2E5090 50%, #17479E 100%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.table-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

/* Table Title Group */
.table-title-group {
    display: flex;
    align-items: center;
    gap: 15px;
}

.table-icon-wrapper {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(23, 71, 158, 0.2);
    position: relative;
    animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.table-icon-wrapper i {
    color: white;
    font-size: 20px;
}

.table-title-text {
    flex: 1;
}

.table-main-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #003366;
    letter-spacing: -0.5px;
}

.table-subtitle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
    font-size: 14px;
}

.record-count {
    display: flex;
    align-items: center;
    gap: 5px;
}

.count-number {
    font-weight: 700;
    color: #17479E;
    font-size: 16px;
}

.count-text {
    color: #6c757d;
}

.separator {
    color: #dee2e6;
}

.filter-status {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6c757d;
}

.filter-active {
    color: #FF8C00;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* Quick Stats */
.table-actions-group {
    display: flex;
    align-items: center;
}

.quick-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
}

.stat-pending .stat-value {
    color: #FF8C00;
}

.stat-pending {
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
    border-color: rgba(255, 140, 0, 0.2);
}

.stat-approved .stat-value {
    color: #28a745;
}

.stat-approved {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    border-color: rgba(40, 167, 69, 0.2);
}

.stat-amount .stat-value {
    color: #17479E;
}

.stat-amount {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    border-color: rgba(23, 71, 158, 0.2);
}

/* Modern Table Body Wrapper */
.modern-table-body-wrapper {
    position: relative;
    background: white;
}

/* Modern Table Footer - Blue Pagination */
.modern-table-footer {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    border-top: 2px solid rgba(255, 255, 255, 0.1);
    padding: 20px 25px;
    color: white;
}

.modern-table-footer .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.modern-table-footer .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
}

.modern-table-footer .form-select:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    color: white;
}

.modern-table-footer .form-select option {
    background-color: #17479E;
    color: white;
}

.modern-table-footer label,
.modern-table-footer span,
.modern-table-footer small {
    color: rgba(255, 255, 255, 0.9) !important;
}

.modern-table-footer strong {
    color: white !important;
    font-weight: 700;
}

/* Custom Pagination Styles */
.modern-table-footer .pagination {
    margin-bottom: 0;
}

/* Hide only the Laravel pagination text, keep the pagination links */
.modern-table-footer nav p.text-sm.text-gray-700,
.modern-table-footer nav .d-none.flex-sm-fill.d-sm-flex p {
    display: none !important;
}

/* Ensure pagination links are visible */
.modern-table-footer .pagination {
    display: flex !important;
    justify-content: center;
}

.modern-table-footer .page-link {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    margin: 0 2px;
    border-radius: 8px;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.modern-table-footer .page-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modern-table-footer .page-item.active .page-link {
    background-color: white;
    border-color: white;
    color: #17479E;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

.modern-table-footer .page-item.disabled .page-link {
    background-color: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.4);
}

.modern-table-footer .page-link:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    color: white;
}

/* Responsive Design for Table Header */
@media (max-width: 768px) {
    .table-header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .table-icon-wrapper {
        width: 40px;
        height: 40px;
    }

    .table-icon-wrapper i {
        font-size: 16px;
    }

    .table-main-title {
        font-size: 20px;
    }

    .quick-stats {
        width: 100%;
        justify-content: space-between;
    }

    .stat-item {
        padding: 8px 12px;
    }

    .stat-value {
        font-size: 16px;
    }
}

/* Modern Data Table Styles */
.modern-table-container {
    position: relative;
    overflow-x: auto;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 51, 102, 0.08);
}

.modern-data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

/* Modern Table Header */
.modern-table-header {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table-header tr th {
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border: none;
    position: relative;
}

.modern-table-header tr th:first-child {
    border-top-left-radius: 15px;
}

.modern-table-header tr th:last-child {
    border-top-right-radius: 15px;
}

.checkbox-column {
    width: 50px;
    padding: 12px !important;
}

.action-column {
    width: 120px;
}

/* Modern Checkbox */
.modern-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modern-checkbox input[type="checkbox"] {
    display: none;
}

.modern-checkbox label {
    position: relative;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #17479E;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
}

.modern-checkbox label:hover {
    border-color: #003366;
    box-shadow: 0 0 5px rgba(23, 71, 158, 0.2);
}

.modern-checkbox input[type="checkbox"]:checked + label {
    background: #17479E;
    border-color: #17479E;
}

.modern-checkbox input[type="checkbox"]:checked + label::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 14px;
}

/* Header checkbox style */
.modern-table-header .modern-checkbox label {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid white;
}

.modern-table-header .modern-checkbox label:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label {
    background: white;
    border-color: white;
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label::after {
    color: #17479E;
}

/* Sortable Columns */
.sortable-column {
    cursor: pointer;
    transition: background 0.3s ease;
}

.sortable-column:hover {
    background: rgba(255, 255, 255, 0.1);
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-icon {
    opacity: 0.5;
    font-size: 12px;
    transition: opacity 0.3s ease;
}

.sortable-column:hover .sort-icon {
    opacity: 1;
}

/* Modern Table Body */
.modern-table-body {
    background: white;
}

.modern-table-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 51, 102, 0.05);
}

.clickable-row {
    cursor: pointer;
}

.modern-table-row:hover {
    background: linear-gradient(90deg, rgba(23, 71, 158, 0.05) 0%, rgba(23, 71, 158, 0.02) 100%);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 51, 102, 0.1);
}

.modern-table-row td {
    padding: 16px 12px;
    vertical-align: middle;
    border: none;
}

/* Employee Info Cell */
.employee-column {
    min-width: 200px;
}

.employee-info {
    display: flex;
    align-items: center;
}

.employee-details {
    flex: 1;
    min-width: 0;
}

.employee-name {
    font-weight: 600;
    color: #003366;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
    font-size: 14px;
}

.employee-id {
    font-size: 12px;
    color: #6c757d;
}

/* Amount Cells */
.amount-cell {
    line-height: 1.3;
}

.amount-primary {
    font-weight: 600;
    color: #003366;
    font-size: 14px;
}

.amount-secondary {
    font-size: 11px;
    color: #6c757d;
}

.amount-deductible {
    font-weight: 600;
    color: #FF8C00;
    font-size: 14px;
}

.amount-requested {
    font-weight: 700;
    color: #17479E;
    font-size: 15px;
}

.amount-takehome {
    font-weight: 700;
    color: #28a745;
    font-size: 15px;
}

/* Modern Badges */
.modern-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-tenure {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
}

/* Modern Status Badges - Enhanced Pills */
.modern-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
}

.modern-status-badge::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.modern-status-badge:hover::before {
    width: 100px;
    height: 100px;
}

.modern-status-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modern-status-badge i {
    font-size: 10px;
}

/* Approval Status Pills */
.status-approved {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-color: #059669;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-approved:hover {
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}

.status-rejected {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-color: #dc2626;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.status-rejected:hover {
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

.status-cancelled {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    border-color: #475569;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
}

.status-cancelled:hover {
    box-shadow: 0 4px 16px rgba(100, 116, 139, 0.4);
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-color: #d97706;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    animation: pendingPulse 2s ease-in-out infinite;
}

@keyframes pendingPulse {
    0%, 100% { box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3); }
    50% { box-shadow: 0 2px 16px rgba(245, 158, 11, 0.5); }
}

.status-pending:hover {
    animation: none;
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
}

/* Process Status Pills */
.status-processing {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-color: #7c3aed;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    position: relative;
}

.status-processing::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 8px;
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    transform: translateY(-50%);
    animation: processingDot 1.5s ease-in-out infinite;
}

@keyframes processingDot {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

.status-processing:hover {
    box-shadow: 0 4px 16px rgba(139, 92, 246, 0.4);
}

.status-disbursed {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
    border-color: #0891b2;
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
}

.status-disbursed:hover {
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.4);
}

.status-failed {
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    color: white;
    border-color: #ef4444;
    box-shadow: 0 2px 8px rgba(248, 113, 113, 0.3);
}

.status-failed:hover {
    box-shadow: 0 4px 16px rgba(248, 113, 113, 0.4);
}

.status-settled {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    color: white;
    border-color: #111827;
    box-shadow: 0 2px 8px rgba(31, 41, 55, 0.3);
}

.status-settled:hover {
    box-shadow: 0 4px 16px rgba(31, 41, 55, 0.4);
}

.status-new {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.status-new:hover {
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: rgba(0, 51, 102, 0.05);
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.action-btn:hover::before {
    width: 40px;
    height: 40px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
}

.btn-view {
    background: rgba(23, 71, 158, 0.1);
    color: #17479E;
}

.btn-view:hover {
    background: #17479E;
    color: white;
}

.btn-edit {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    text-decoration: none;
}

.btn-edit:hover {
    background: #6c757d;
    color: white;
}

.btn-approve {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.btn-approve:hover {
    background: #28a745;
    color: white;
}

.btn-reject {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-reject:hover {
    background: #dc3545;
    color: white;
}

/* Row Selection */
.modern-table-row.selected {
    background: rgba(23, 71, 158, 0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #6c757d;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.empty-icon i {
    font-size: 48px;
    color: #17479E;
    opacity: 0.5;
}

.empty-state h5 {
    color: #003366;
    margin-bottom: 15px;
    font-weight: 600;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 30px;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

/* Modern Buttons */
.modern-btn {
    padding: 10px 24px;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 71, 158, 0.3);
}

.btn-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.btn-secondary:hover {
    background: rgba(108, 117, 125, 0.2);
    transform: translateY(-2px);
}

/* Table Loading Animation */
@keyframes tableRowSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-table-row {
    animation: tableRowSlide 0.5s ease backwards;
}

.modern-table-row:nth-child(1) { animation-delay: 0.05s; }
.modern-table-row:nth-child(2) { animation-delay: 0.1s; }
.modern-table-row:nth-child(3) { animation-delay: 0.15s; }
.modern-table-row:nth-child(4) { animation-delay: 0.2s; }
.modern-table-row:nth-child(5) { animation-delay: 0.25s; }
.modern-table-row:nth-child(6) { animation-delay: 0.3s; }
.modern-table-row:nth-child(7) { animation-delay: 0.35s; }
.modern-table-row:nth-child(8) { animation-delay: 0.4s; }
.modern-table-row:nth-child(9) { animation-delay: 0.45s; }
.modern-table-row:nth-child(10) { animation-delay: 0.5s; }

/* Responsive Design */
@media (max-width: 768px) {
    .modern-data-table {
        font-size: 12px;
    }

    .modern-table-header tr th {
        padding: 12px 8px;
        font-size: 10px;
    }

    .modern-table-row td {
        padding: 12px 8px;
    }

    .employee-name {
        font-size: 13px;
    }

    .employee-id {
        font-size: 11px;
    }

    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}

/* Modern KPI Dashboard with Glassmorphism */
.kpi-dashboard-modern {
    position: relative;
    padding: 20px 0;
    margin: -10px -15px 20px -15px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.03) 0%, rgba(23, 71, 158, 0.03) 100%);
    border-radius: 20px;
}

.kpi-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 50%, rgba(0, 51, 102, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 50%, rgba(23, 71, 158, 0.05) 0%, transparent 50%);
    border-radius: 20px;
    z-index: 0;
}

/* Glassmorphism Card */
.kpi-card-modern {
    position: relative;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 20px;
    padding: 0;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    min-height: 200px;
    opacity: 1 !important; /* Ensure cards are always visible */
    visibility: visible !important;
}

.glass-card {
    box-shadow:
        0 8px 32px 0 rgba(31, 38, 135, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.kpi-card-modern:hover {
    transform: translateY(-8px) scale(1.02);
    background: rgba(255, 255, 255, 0.95);
    box-shadow:
        0 20px 40px 0 rgba(31, 38, 135, 0.25),
        inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

/* Glow Effect */
.kpi-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 0;
}

.pending-glow {
    background: radial-gradient(circle, rgba(255, 140, 0, 0.2) 0%, transparent 70%);
}

.approved-glow {
    background: radial-gradient(circle, rgba(40, 167, 69, 0.2) 0%, transparent 70%);
}

.rejected-glow {
    background: radial-gradient(circle, rgba(220, 53, 69, 0.2) 0%, transparent 70%);
}

.cancelled-glow {
    background: radial-gradient(circle, rgba(108, 117, 125, 0.2) 0%, transparent 70%);
}

.disbursed-glow {
    background: radial-gradient(circle, rgba(0, 51, 102, 0.2) 0%, transparent 70%);
}

.kpi-card-modern:hover .kpi-glow {
    opacity: 1;
}

/* Content Layout */
.kpi-content-modern {
    position: relative;
    z-index: 1;
    padding: 20px;
}

/* Header Section */
.kpi-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

/* Modern Icon Style */
.kpi-icon-modern {
    position: relative;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    font-size: 20px;
    color: white;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.pending-icon {
    --color-primary: #FF8C00;
    --color-secondary: #FFA500;
}

.approved-icon {
    --color-primary: #28a745;
    --color-secondary: #20c997;
}

.rejected-icon {
    --color-primary: #dc3545;
    --color-secondary: #f56565;
}

.cancelled-icon {
    --color-primary: #6c757d;
    --color-secondary: #868e96;
}

.disbursed-icon {
    --color-primary: #003366;
    --color-secondary: #17479E;
}

/* Pulse Ring Animation */
.icon-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border-radius: 15px;
    border: 2px solid currentColor;
    opacity: 0.3;
    transform: translate(-50%, -50%);
    animation: pulseRing 2s infinite;
}

@keyframes pulseRing {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0;
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
}

/* Badge */
.kpi-badge-modern {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.1) 0%, rgba(23, 71, 158, 0.1) 100%);
    color: var(--ura-primary);
}

.success-badge {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    color: #28a745;
}

.danger-badge {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(245, 101, 101, 0.1) 100%);
    color: #dc3545;
}

.secondary-badge {
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(134, 142, 150, 0.1) 100%);
    color: #6c757d;
}

.primary-badge {
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.1) 0%, rgba(23, 71, 158, 0.1) 100%);
    color: var(--ura-primary);
}

/* Value Section */
.kpi-value-wrapper {
    margin: 20px 0;
}

.kpi-value-modern {
    font-size: 36px;
    font-weight: 800;
    color: var(--ura-primary);
    margin: 0;
    line-height: 1;
    letter-spacing: -1px;
}

.number-animate {
    display: inline-block;
    transition: all 0.3s ease;
}

.kpi-card-modern:hover .number-animate {
    transform: scale(1.1);
}

/* Trend Section */
.kpi-trend-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
}

.trend-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    background: rgba(0, 51, 102, 0.05);
}

.pending-trend {
    background: rgba(255, 140, 0, 0.1);
    color: #FF8C00;
}

.approved-trend {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.rejected-trend {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.cancelled-trend {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.disbursed-trend {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
}

.trend-text {
    font-size: 12px;
    font-weight: 600;
}

/* Mini Chart */
.kpi-chart-modern {
    margin: 15px 0;
    height: 30px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.kpi-card-modern:hover .kpi-chart-modern {
    opacity: 1;
}

/* Progress Bar */
.kpi-footer-modern {
    margin-top: 15px;
}

.progress-bar-modern {
    height: 4px;
    background: rgba(0, 51, 102, 0.05);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 1s ease;
    position: relative;
    overflow: hidden;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.pending-progress {
    background: linear-gradient(90deg, #FF8C00, #FFA500);
}

.approved-progress {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.rejected-progress {
    background: linear-gradient(90deg, #dc3545, #f56565);
}

.cancelled-progress {
    background: linear-gradient(90deg, #6c757d, #868e96);
}

.disbursed-progress {
    background: linear-gradient(90deg, #003366, #17479E);
}

.footer-text {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
}

/* Shine Effect */
.card-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
    transform: rotate(45deg) translateY(-100%);
    transition: transform 0.6s;
    pointer-events: none;
}

.kpi-card-modern:hover .card-shine {
    transform: rotate(45deg) translateY(100%);
}

/* Entrance Animation - 3D Rotate In */
@keyframes rotateIn3D {
    0% {
        opacity: 0;
        transform: perspective(1200px) rotateY(-90deg) translateZ(100px) scale(0.8);
    }
    40% {
        opacity: 1;
        transform: perspective(1200px) rotateY(10deg) translateZ(0) scale(1.05);
    }
    70% {
        transform: perspective(1200px) rotateY(-5deg) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: perspective(1200px) rotateY(0deg) scale(1);
    }
}

/* Alternative: Flip and Zoom Entrance */
@keyframes flipZoomIn {
    0% {
        opacity: 0;
        transform: perspective(1000px) rotateX(-180deg) scale(0.5);
    }
    50% {
        opacity: 1;
        transform: perspective(1000px) rotateX(-90deg) scale(0.8);
    }
    75% {
        transform: perspective(1000px) rotateX(10deg) scale(1.05);
    }
    100% {
        transform: perspective(1000px) rotateX(0deg) scale(1);
    }
}

/* Cube Rotate Entrance - Enhanced */
@keyframes cubeRotateIn {
    0% {
        opacity: 0;
        transform: perspective(1000px) rotateX(-90deg) rotateY(-90deg) translateZ(150px) scale(0.5);
    }
    25% {
        opacity: 0.5;
        transform: perspective(1000px) rotateX(-45deg) rotateY(-45deg) translateZ(75px) scale(0.7);
    }
    50% {
        opacity: 1;
        transform: perspective(1000px) rotateX(10deg) rotateY(10deg) translateZ(0) scale(1.05);
    }
    75% {
        transform: perspective(1000px) rotateX(-5deg) rotateY(-5deg) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: perspective(1000px) rotateX(0) rotateY(0) translateZ(0) scale(1);
    }
}

/* Subtle Breathing Effect - After entrance */
@keyframes gentleBreath {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.01);
    }
}

/* Apply Cube Rotate entrance with stagger - Option 2 */
.animate-float-1 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0s;
}

.animate-float-2 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.15s;
}

.animate-float-3 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.3s;
}

.animate-float-4 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.45s;
}

.animate-float-5 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.6s;
}

/* Remove conflicting animation from base card */
.kpi-card-modern {
    /* Removed the gentleBreath animation to prevent conflicts */
    transform-style: preserve-3d;
    backface-visibility: hidden;
}

/* Add breathing effect only after entrance via JavaScript or separate class */
.kpi-card-modern.entrance-complete {
    animation: gentleBreath 4s ease-in-out infinite;
}

/* Alternative: Flip In Animation */
@keyframes flipInX {
    0% {
        transform: perspective(800px) rotateX(-90deg);
        opacity: 0;
    }
    40% {
        transform: perspective(800px) rotateX(20deg);
    }
    70% {
        transform: perspective(800px) rotateX(-10deg);
    }
    100% {
        transform: perspective(800px) rotateX(0deg);
        opacity: 1;
    }
}

/* Alternative: Zoom In Bounce */
@keyframes zoomInBounce {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.95);
    }
    100% {
        transform: scale(1);
    }
}

/* You can switch to these by changing the class names:
.animate-flip-1 { animation: flipInX 0.8s ease-out; animation-delay: 0s; }
.animate-flip-2 { animation: flipInX 0.8s ease-out; animation-delay: 0.1s; }
.animate-flip-3 { animation: flipInX 0.8s ease-out; animation-delay: 0.2s; }
.animate-flip-4 { animation: flipInX 0.8s ease-out; animation-delay: 0.3s; }
.animate-flip-5 { animation: flipInX 0.8s ease-out; animation-delay: 0.4s; }

.animate-zoom-1 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0s; }
.animate-zoom-2 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.1s; }
.animate-zoom-3 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.2s; }
.animate-zoom-4 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.3s; }
.animate-zoom-5 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.4s; }
*/

/* Old KPI Dashboard - keeping for fallback */
.kpi-dashboard {
    padding: 0;
}

/* New URASACCOS Branded KPI Cards with Animation */
.kpi-card-ura {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 51, 102, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 51, 102, 0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.kpi-card-ura::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.4s ease;
    transform-origin: left;
}

.kpi-card-ura:hover::before {
    transform: scaleX(1);
}

.kpi-card-ura:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 28px rgba(0, 51, 102, 0.18);
    border-color: var(--ura-secondary);
}

.kpi-card-body {
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.kpi-icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.kpi-icon-circle::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at top left, rgba(255,255,255,0.2), transparent);
}

/* Icon Circle Brand Colors */
.kpi-icon-circle.ura-pending {
    background: var(--warning-gradient);
    color: white;
}

.kpi-icon-circle.ura-approved {
    background: var(--success-gradient);
    color: white;
}

.kpi-icon-circle.ura-rejected {
    background: var(--danger-gradient);
    color: white;
}

.kpi-icon-circle.ura-cancelled {
    background: var(--grey-gradient);
    color: white;
}

.kpi-icon-circle.ura-disbursed {
    background: var(--primary-gradient);
    color: white;
}

.kpi-details {
    flex: 1;
    min-width: 0;
}

.kpi-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--ura-primary);
    margin: 0;
    line-height: 1;
    letter-spacing: -0.5px;
}

.kpi-title {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    margin: 4px 0 2px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-subtitle {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.kpi-period {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.kpi-weekly {
    font-size: 11px;
    font-weight: 600;
}

.kpi-footer {
    padding: 8px 16px;
    font-size: 11px;
    font-weight: 600;
    color: white;
    text-align: center;
    letter-spacing: 0.3px;
}

/* Footer Background Colors */
.kpi-footer.ura-pending-bg {
    background: linear-gradient(90deg, #FF8C00 0%, #FFA500 100%);
}

.kpi-footer.ura-approved-bg {
    background: linear-gradient(90deg, #20c997 0%, #28a745 100%);
}

.kpi-footer.ura-rejected-bg {
    background: linear-gradient(90deg, #c82333 0%, #dc3545 100%);
}

.kpi-footer.ura-cancelled-bg {
    background: linear-gradient(90deg, #5a6268 0%, #6c757d 100%);
}

.kpi-footer.ura-disbursed-bg {
    background: linear-gradient(90deg, #003366 0%, #17479E 100%);
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    .kpi-number {
        font-size: 24px;
    }
    .kpi-title {
        font-size: 13px;
    }
    .kpi-icon-circle {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }
}

@media (max-width: 1200px) {
    .kpi-card-body {
        padding: 14px;
    }
    .kpi-number {
        font-size: 22px;
    }
    .kpi-footer {
        padding: 6px 12px;
        font-size: 10px;
    }
}

@media (max-width: 768px) {
    .kpi-dashboard .col {
        min-width: 100%;
        margin-bottom: 10px;
    }
}

/* URASACCOS Filter Card */
.filter-card-ura {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0, 51, 102, 0.08);
    border: 1px solid rgba(0, 51, 102, 0.08);
}

.filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 20px 24px;
    border-bottom: 2px solid var(--ura-primary);
}

.filter-title {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-icon {
    width: 32px;
    height: 32px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.filter-subtitle {
    color: #6c757d;
    font-size: 13px;
    margin-left: 42px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-ura-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 6px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-light {
    background: white;
    color: var(--ura-primary);
    border: 1px solid rgba(0, 51, 102, 0.2);
    padding: 6px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-light:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
}

.filter-body {
    padding: 24px;
}

/* Filter Input Styles */
.filter-input-group {
    position: relative;
}

.filter-input {
    padding-left: 40px;
    padding-right: 40px;
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

.filter-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    z-index: 1;
}

.filter-clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 4px;
    font-size: 14px;
}

.filter-clear-btn:hover {
    color: var(--ura-danger);
}

/* Filter Select */
.filter-select {
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    padding: 0 12px;
    transition: all 0.3s ease;
    background-color: white;
}

.filter-select:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

/* Date Filter */
.filter-date-group {
    position: relative;
}

.filter-date {
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    padding: 0 12px;
    transition: all 0.3s ease;
}

.filter-date:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

.filter-date-label {
    position: absolute;
    top: -10px;
    left: 12px;
    background: white;
    padding: 0 6px;
    font-size: 11px;
    color: var(--ura-primary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Apply Button */
.btn-filter-apply {
    width: 100%;
    height: 44px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter-apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 51, 102, 0.3);
    color: white;
}

/* Advanced Filter Container */
.advanced-filter-container {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    margin-top: 15px;
    border: 1px solid rgba(0, 51, 102, 0.08);
}

/* Collapse transition */
#advancedFilters {
    transition: all 0.35s ease;
}

#advancedFilters.collapsing {
    transition: height 0.35s ease;
}

/* Filter Pills - No border when at top */
.filter-pills {
    padding-bottom: 0;
    margin-bottom: 0;
}

/* Filter Pills when advanced is open */
.filter-pills.with-advanced {
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 15px;
}

.filter-pills-label {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-right: 12px;
}

.filter-pills-group {
    display: inline-flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-pill {
    background: white;
    border: 2px solid #e9ecef;
    color: #495057;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    position: relative;
    overflow: hidden;
}

.filter-pill::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(0, 51, 102, 0.1);
    transition: width 0.6s ease, height 0.6s ease;
    transform: translate(-50%, -50%);
}

.filter-pill:hover::before {
    width: 100px;
    height: 100px;
}

.filter-pill:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
    color: var(--ura-primary);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
}

.filter-pill.active {
    background: var(--primary-gradient);
    color: white;
    border-color: var(--ura-primary);
}

.filter-pill-count {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 4px;
}

.filter-pill.active .filter-pill-count {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

/* Special colored pills */
.filter-pill-pending {
    border-color: var(--ura-warning);
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.05) 0%, rgba(255, 165, 0, 0.05) 100%);
}

.filter-pill-pending:hover {
    background: var(--warning-gradient);
    color: white;
    border-color: var(--ura-warning);
}

.filter-pill-approved {
    border-color: var(--ura-success);
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
}

.filter-pill-approved:hover {
    background: var(--success-gradient);
    color: white;
    border-color: var(--ura-success);
}

.filter-pill-disbursed {
    border-color: var(--ura-primary);
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.05) 0%, rgba(23, 71, 158, 0.05) 100%);
}

.filter-pill-disbursed:hover {
    background: var(--primary-gradient);
    color: white;
    border-color: var(--ura-primary);
}

/* Active Filters */
.active-filters {
    padding: 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.active-filters-label {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.active-filter-tag {
    background: white;
    border: 1px solid var(--ura-secondary);
    color: var(--ura-primary);
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-tag-remove {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.2s ease;
}

.filter-tag-remove:hover {
    color: var(--ura-danger);
}

/* Page Background */
.bg-gradient {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    min-height: 100vh;
}

/* Compact Page Header */
.page-header-compact {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 51, 102, 0.05);
    transition: all 0.3s ease;
}

.page-header-compact:hover {
    box-shadow: 0 4px 16px rgba(0, 51, 102, 0.08);
}

.icon-box-compact {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    transition: all 0.3s ease;
}

.icon-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.page-title-compact {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 1.1rem;
    letter-spacing: -0.3px;
}

.btn-ura-primary-sm {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-light-sm {
    background: white;
    color: var(--ura-primary);
    border: 1px solid rgba(0, 51, 102, 0.15);
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.3s ease;
}

.btn-ura-light-sm:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
}

/* Animations */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes dropdownSlide {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-fade-in-delayed {
    animation: fadeIn 0.8s ease-out;
    animation-delay: 0.2s;
    animation-fill-mode: both;
}

.animate-dropdown {
    animation: dropdownSlide 0.3s ease-out;
}

.animate-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-hover:hover {
    transform: translateY(-2px) scale(1.02);
}

/* Stagger Animations for KPI Cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-stagger-1 {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.animate-stagger-2 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.1s;
    animation-fill-mode: both;
}

.animate-stagger-3 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.2s;
    animation-fill-mode: both;
}

.animate-stagger-4 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.3s;
    animation-fill-mode: both;
}

.animate-stagger-5 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.4s;
    animation-fill-mode: both;
}

/* Breadcrumb styling */
.breadcrumb {
    font-size: 13px;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: var(--ura-secondary);
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--ura-primary);
}

.breadcrumb-item.active {
    color: var(--ura-primary);
}

/* Old Page Header - keeping for reference */
.page-header-wrapper {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
}

.page-title {
    font-size: 1.875rem;
    font-weight: 800;
    background: linear-gradient(135deg, #17479E 0%, #2563c7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.gradient-primary {
    background: var(--primary-gradient);
}

.gradient-success {
    background: var(--success-gradient);
}

/* Stat Cards */
.stat-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 2px solid rgba(23, 71, 158, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(23, 71, 158, 0.08);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    right: -100%;
    height: 4px;
    background: linear-gradient(90deg,
        transparent,
        #17479E 40%,
        #2563c7 50%,
        #17479E 60%,
        transparent);
    animation: slideAcross 3s ease-in-out infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

@keyframes slideAcross {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center,
        rgba(23, 71, 158, 0.03) 0%,
        transparent 70%);
    animation: rotateGradient 20s linear infinite;
}

@keyframes rotateGradient {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover {
    border-color: rgba(23, 71, 158, 0.2);
}

.shadow-hover:hover {
    transform: translateY(-12px) scale(1.02) rotate(0.5deg);
    box-shadow: 0 20px 50px rgba(23, 71, 158, 0.25);
}

.stat-icon-wrapper {
    position: relative;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.bg-gradient-warning {
    background: var(--warning-gradient);
}

.bg-gradient-success {
    background: var(--success-gradient);
}

.bg-gradient-info {
    background: var(--info-gradient);
}

.bg-gradient-primary {
    background: var(--primary-gradient);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1d23;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.progress-sm {
    height: 4px;
    border-radius: 2px;
    background: #f0f2f5;
}

/* Enhanced Search Box */
.search-box {
    position: relative;
}

.search-box input {
    padding-left: 3rem;
    border-radius: 12px;
    font-size: 1rem;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Quick Filters */
.quick-filters button {
    border-radius: 20px;
    padding: 0.25rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.quick-filters button:hover {
    transform: translateY(-2px);
}

/* Enhanced Button Styles */
.btn-gradient-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Table Enhancements */
.table-hover tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 3px solid transparent;
}

.table-hover tbody tr:hover {
    background-color: rgba(23, 71, 158, 0.05);
    border-left: 3px solid #17479E;
    transform: translateX(2px);
}

/* Row selection styling */
.table-hover tbody tr.table-active {
    background-color: rgba(23, 71, 158, 0.1);
    border-left: 3px solid #17479E;
}

.sortable {
    cursor: pointer;
    user-select: none;
}

.sortable:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Avatar Styles */
.avatar-wrapper {
    position: relative;
}

.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.avatar-circle.avatar-sm {
    width: 28px;
    height: 28px;
}

.avatar-circle.avatar-sm .avatar-text {
    font-size: 0.7rem;
}

.avatar-text {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.avatar-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

/* Badge Enhancements */
.badge {
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.badge.rounded-pill {
    border-radius: 20px;
}

/* Status Indicators */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}

/* Empty State */
.empty-state {
    padding: 3rem;
}

.empty-icon {
    opacity: 0.5;
}

/* Dropdown Menu */
.dropdown-menu {
    border: none;
    border-radius: 12px;
    padding: 0.5rem;
    min-width: 200px;
    margin-top: 0.5rem !important;
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.dropdown-divider {
    margin: 0.5rem;
}

/* Fix dropdown overlap */
.table td:last-child {
    position: relative;
    overflow: visible;
}

.dropdown .dropdown-menu {
    position: absolute;
    inset: 0px auto auto 0px;
    margin: 0px;
    transform: translate3d(0px, 38px, 0px);
}

.dropdown-menu.dropdown-menu-end {
    right: 0;
    left: auto;
}

/* Remove dropdown arrow if not needed */
.dropdown-toggle::after {
    display: none;
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #e0e6ed;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animations */
@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.loan-row {
    animation: slideInUp 0.5s ease forwards;
}

.loan-row:nth-child(1) { animation-delay: 0.05s; }
.loan-row:nth-child(2) { animation-delay: 0.1s; }
.loan-row:nth-child(3) { animation-delay: 0.15s; }
.loan-row:nth-child(4) { animation-delay: 0.2s; }
.loan-row:nth-child(5) { animation-delay: 0.25s; }

/* Modern Interactive Elements */
.btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

/* Modern Table Design */
.table-container {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(23, 71, 158, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(23, 71, 158, 0.08);
}

.table {
    margin-bottom: 0;
}

/* Compact table for better space utilization */
.table td {
    padding: 0.4rem 0.5rem;
    vertical-align: middle;
    font-size: 0.813rem;
}

/* Alternating row colors with URA brand */
.table tbody tr:nth-child(even) {
    background-color: rgba(23, 71, 158, 0.02);
}

.table th {
    padding: 0.5rem;
    font-size: 0.813rem;
    font-weight: 600;
}

/* Smaller text for secondary information */
.table small {
    font-size: 0.75rem;
}

/* Compact badges */
.table .badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}

/* Compact dropdown */
.dropdown-menu {
    font-size: 0.813rem;
    min-width: 120px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 9999 !important;
    background-color: white;
}

.dropdown-item {
    padding: 0.4rem 1rem;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: rgba(23, 71, 158, 0.1);
}

.dropdown-item i {
    font-size: 0.75rem;
    width: 16px;
}

/* Fix dropdown positioning */
.table-responsive {
    min-height: 300px;
}

/* Ensure dropdown menu appears on top */
.dropdown-menu {
    z-index: 99999 !important;
    background-color: white !important;
    border: 1px solid rgba(0,0,0,0.15) !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
}

/* Process Loan Action Cards */
.action-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #17479E !important;
}

.action-card.border-primary {
    border-color: #17479E !important;
    background-color: rgba(23, 71, 158, 0.05) !important;
}

.action-icon {
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Action button hover effects */
.btn-sm:hover {
    transform: scale(1.2);
    transition: all 0.2s ease;
}

.btn-sm:hover i.fa-eye {
    color: #0056b3 !important;
}

.btn-sm:hover i.fa-edit {
    color: #545b62 !important;
}

.btn-sm:hover i.fa-check {
    color: #218838 !important;
}

.btn-sm:hover i.fa-times {
    color: #c82333 !important;
}

.table th {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
    color: #17479E;
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(23, 71, 158, 0.05);
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: linear-gradient(90deg,
        rgba(23, 71, 158, 0.02) 0%,
        rgba(23, 71, 158, 0.05) 50%,
        rgba(23, 71, 158, 0.02) 100%);
}

/* Status Badges with Modern Style */
.badge {
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
}

.badge::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent);
    animation: badgeShimmer 3s ease-in-out infinite;
}

@keyframes badgeShimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(300%); }
}

/* Action Buttons with Micro-interactions */
.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid transparent;
    position: relative;
    margin: 0 2px;
}

.btn-action:hover {
    transform: translateY(-3px) scale(1.1);
    border-color: currentColor;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.btn-action::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 10px;
    background: linear-gradient(45deg, currentColor, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.btn-action:hover::after {
    opacity: 0.2;
}

/* URASACCOS Brand Enhancements */
.text-primary {
    color: #17479E !important;
}

.bg-primary {
    background: #17479E !important;
}

.border-primary {
    border-color: #17479E !important;
}

/* Enhanced Table Headers with URASACCOS Style */
.table thead th {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(23, 71, 158, 0.3);
}

/* Status Colors with URASACCOS Theme */
.status-approved {
    color: #1e8449;
    background: rgba(30, 132, 73, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
}

.status-pending {
    color: #f39c12;
    background: rgba(243, 156, 18, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
}

/* Dropdown Menu URASACCOS Style */
.dropdown-menu {
    border: 1px solid rgba(23, 71, 158, 0.1);
    box-shadow: 0 5px 20px rgba(23, 71, 158, 0.15);
}

.dropdown-item:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
}

/* Pagination URASACCOS Style */
.pagination .page-link {
    color: #17479E;
    border-color: rgba(23, 71, 158, 0.2);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #17479E 0%, #2563c7 100%);
    border-color: #17479E;
}

/* Add URASACCOS branding watermark */
.page-header-wrapper::before {
    content: 'URASACCOS';
    position: absolute;
    top: 50%;
    right: 2rem;
    transform: translateY(-50%);
    font-size: 3rem;
    font-weight: 900;
    color: rgba(23, 71, 158, 0.05);
    letter-spacing: 3px;
    pointer-events: none;
}

/* Advanced Glassmorphism Effects */
.glass-effect {
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.1) 0%,
        rgba(255, 255, 255, 0.05) 100%
    );
    backdrop-filter: blur(10px) saturate(150%);
    -webkit-backdrop-filter: blur(10px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow:
        0 8px 32px 0 rgba(31, 38, 135, 0.37),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

/* Neon Glow Effects */
.neon-glow {
    animation: neonPulse 2s ease-in-out infinite;
}

@keyframes neonPulse {
    0%, 100% {
        text-shadow:
            0 0 5px var(--ura-primary),
            0 0 10px var(--ura-primary),
            0 0 15px var(--ura-primary),
            0 0 20px var(--ura-primary-light);
    }
    50% {
        text-shadow:
            0 0 10px var(--ura-primary),
            0 0 20px var(--ura-primary),
            0 0 30px var(--ura-primary),
            0 0 40px var(--ura-primary-light);
    }
}

/* 3D Card Tilt Effect */
.tilt-card {
    transform-style: preserve-3d;
    transition: transform 0.6s;
}

.tilt-card:hover {
    transform:
        perspective(1000px)
        rotateX(10deg)
        rotateY(-10deg)
        scale(1.05);
}

/* Liquid Button Effect */
.liquid-btn {
    position: relative;
    padding: 20px 40px;
    display: block;
    text-decoration: none;
    overflow: hidden;
    transition: all 0.3s;
}

.liquid-btn span {
    position: relative;
    z-index: 1;
}

.liquid-btn::before {
    content: '';
    position: absolute;
    top: var(--y, 50%);
    left: var(--x, 50%);
    width: 0;
    height: 0;
    border-radius: 50%;
    background: var(--ura-primary-light);
    transition: width 0.5s, height 0.5s;
    transform: translate(-50%, -50%);
}

.liquid-btn:hover::before {
    width: 400px;
    height: 400px;
}

/* Skeleton Loading Animation */
.skeleton {
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: skeletonLoading 1.5s infinite;
}

@keyframes skeletonLoading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Morphing Search Bar */
.search-morphing {
    position: relative;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.search-morphing:focus-within {
    transform: scale(1.05);
    box-shadow:
        0 10px 40px rgba(23, 71, 158, 0.2),
        inset 0 0 0 2px var(--ura-primary);
}

/* Gradient Text Animation */
.gradient-text-animated {
    background: linear-gradient(
        270deg,
        var(--ura-primary),
        var(--ura-primary-light),
        var(--ura-secondary),
        var(--ura-accent)
    );
    background-size: 400% 400%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Floating Action Button */
.fab {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--ura-gradient);
    box-shadow:
        0 10px 30px rgba(23, 71, 158, 0.3),
        0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 1000;
}

.fab:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow:
        0 15px 40px rgba(23, 71, 158, 0.4),
        0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Ripple Effect */
.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ripple:active::before {
    width: 300px;
    height: 300px;
}

/* Quick Actions Menu */
.quick-actions-menu {
    position: fixed;
    bottom: 5rem;
    right: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    opacity: 0;
    pointer-events: none;
    transform: scale(0.8) translateY(20px);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 999;
}

.quick-actions-menu.show {
    opacity: 1;
    pointer-events: all;
    transform: scale(1) translateY(0);
}

.quick-action-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    color: var(--ura-primary);
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.quick-action-btn:hover {
    background: var(--ura-gradient);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
}

.quick-action-btn[data-tooltip]::before {
    content: attr(data-tooltip);
    position: absolute;
    right: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}

.quick-action-btn:hover[data-tooltip]::before {
    opacity: 1;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-overlay.show {
    display: flex;
}

.loading-spinner {
    text-align: center;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3rem;
}

/* Toast Notifications */
.toast-notification {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideInRight 0.3s ease;
    max-width: 350px;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast-notification.success {
    border-left: 4px solid var(--ura-secondary);
}

.toast-notification.error {
    border-left: 4px solid #e74c3c;
}

.toast-notification.info {
    border-left: 4px solid var(--ura-primary);
}

/* Simplified Table Styling */
.avatar-initials {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.bg-gradient-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.btn-ura-primary {
    background: var(--ura-primary);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    background: var(--ura-primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 71, 158, 0.3);
}

/* Enhanced Modal Styling with URASACCOS Branding */
.modal-ura-enhanced {
    border-radius: 20px;
    overflow: hidden;
    background: white;
}

.shadow-2xl {
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(23, 71, 158, 0.05);
}

/* Modal Pattern Background */
.modal-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 200px;
    background:
        repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(23, 71, 158, 0.01) 10px,
            rgba(23, 71, 158, 0.01) 20px
        );
    pointer-events: none;
    z-index: 0;
}

/* Modal Header Styling */
.modal-header-ura {
    background: var(--ura-gradient);
    padding: 2rem;
    position: relative;
}

.header-decoration {
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    animation: floatBubble 20s infinite ease-in-out;
}

@keyframes floatBubble {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

.modal-icon-wrapper {
    position: relative;
}

.modal-icon-circle {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    backdrop-filter: blur(10px);
    animation: pulseIcon 2s infinite;
}

@keyframes pulseIcon {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
    }
    50% {
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
    }
}

/* Enhanced Tab Navigation */
.tab-navigation-wrapper {
    background: linear-gradient(to bottom, #f8f9fa, white);
    padding: 0.5rem;
    border-bottom: 1px solid rgba(23, 71, 158, 0.1);
}

.nav-tabs-ura {
    gap: 0.5rem;
}

.nav-link-ura {
    position: relative;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
    background: transparent;
    color: #6c757d;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.nav-link-ura:hover {
    background: rgba(23, 71, 158, 0.05);
    color: var(--ura-primary);
    transform: translateY(-2px);
}

.nav-link-ura.active {
    background: white;
    color: var(--ura-primary);
    box-shadow:
        0 -2px 10px rgba(23, 71, 158, 0.1),
        0 2px 4px rgba(0, 0, 0, 0.05);
}

.tab-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1), rgba(23, 71, 158, .05));
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.nav-link-ura.active .tab-icon-box {
    background: var(--ura-gradient);
    color: white;
    animation: rotateIcon 0.5s ease;
}

@keyframes rotateIcon {
    from { transform: rotate(0deg) scale(0.8); }
    to { transform: rotate(360deg) scale(1); }
}

.tab-label {
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.tab-indicator {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 50px;
    height: 3px;
    background: var(--ura-gradient);
    border-radius: 3px 3px 0 0;
    transition: transform 0.3s ease;
}

.nav-link-ura.active .tab-indicator {
    transform: translateX(-50%) scaleX(1);
}

/* Enhanced Info Cards */
.info-card-enhanced {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(23, 71, 158, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.info-card-enhanced:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 24px rgba(23, 71, 158, 0.12),
        0 4px 8px rgba(0, 0, 0, 0.05);
    border-color: var(--ura-primary);
}

.info-card-header {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.05), rgba(23, 71, 158, 0.02));
    padding: 1rem;
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-card-icon {
    width: 35px;
    height: 35px;
    background: var(--ura-gradient);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.info-card-title {
    margin: 0;
    color: var(--ura-primary);
    font-weight: 600;
    font-size: 1rem;
}

.info-card-body {
    padding: 1rem;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px dashed rgba(23, 71, 158, 0.1);
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    transition: all 0.2s ease;
}

.info-item:hover {
    padding-left: 0.5rem;
    background: rgba(23, 71, 158, 0.02);
    margin: 0 -0.5rem;
    padding-right: 0.5rem;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item small {
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.info-item div {
    color: #2c3e50;
    font-weight: 500;
}

/* Enhanced Modal Footer */
.modal-footer-ura {
    background: linear-gradient(to right, #f8f9fa, white);
    padding: 1.5rem;
    border-top: 2px solid rgba(23, 71, 158, 0.1);
}

.btn-ura-gradient {
    background: var(--ura-gradient);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-ura-gradient::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-ura-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(23, 71, 158, 0.3);
    color: white;
}

.btn-ura-gradient:hover::before {
    width: 300px;
    height: 300px;
}

/* Loading States */
.modal-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.modal-loading.show {
    opacity: 1;
    pointer-events: all;
}

/* Badge Enhancements */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Tab Content Animation */
.tab-pane {
    animation: fadeInUp 0.4s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-ura-gradient {
    background: var(--ura-gradient);
    color: white;
}

/* Scrollbar Styling for Modal */
.modal-dialog-scrollable .modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
    background: rgba(23, 71, 158, 0.05);
    border-radius: 4px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
    background: rgba(23, 71, 158, 0.3);
    border-radius: 4px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--ura-primary);
}

.bg-ura-gradient th {
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem;
}

/* Glitch Effect */
.glitch {
    position: relative;
    color: var(--ura-primary);
    font-weight: bold;
}

.glitch::before,
.glitch::after {
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.glitch::before {
    animation: glitch-1 0.3s infinite;
    color: var(--ura-accent);
    z-index: -1;
}

.glitch::after {
    animation: glitch-2 0.3s infinite;
    color: var(--ura-secondary);
    z-index: -2;
}

@keyframes glitch-1 {
    0%, 100% {
        clip: rect(0, 900px, 0, 0);
        transform: skew(0deg);
    }
    20% {
        clip: rect(20px, 900px, 30px, 0);
        transform: skew(0.5deg);
    }
}

@keyframes glitch-2 {
    0%, 100% {
        clip: rect(0, 900px, 0, 0);
        transform: skew(0deg);
    }
    50% {
        clip: rect(50px, 900px, 60px, 0);
        transform: skew(-0.5deg);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Animated Counter Effect
function animateCounters() {
    const counters = document.querySelectorAll('.counter-animation');

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-value'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    });
}

// Magnetic Button Effect
document.querySelectorAll('.magnetic-btn').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        btn.style.setProperty('--x', `${x}px`);
        btn.style.setProperty('--y', `${y}px`);
    });
});

// Parallax Scroll Effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.blob-container');
    const particles = document.querySelector('.particle-container');

    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }

    if (particles) {
        particles.style.transform = `translateY(${scrolled * 0.3}px)`;
    }
});

// Show Quick Actions Menu
function showQuickActions() {
    const menu = document.getElementById('quick-actions');
    const fab = document.querySelector('.fab');

    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        fab.style.transform = 'scale(1) rotate(0deg)';
    } else {
        menu.classList.add('show');
        fab.style.transform = 'scale(1.1) rotate(45deg)';
    }
}

// Show Toast Notification
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;

    const icon = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    }[type];

    toast.innerHTML = `
        <i class="fas ${icon} fs-4"></i>
        <div>
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
            <p class="mb-0 text-muted small">${message}</p>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Show Loading Overlay
function showLoading() {
    document.getElementById('loading-overlay').classList.add('show');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.remove('show');
}

// Initialize Animations on Load
window.addEventListener('load', () => {
    animateCounters();

    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card').forEach(card => {
        observer.observe(card);
    });
});

// Liquid Button Mouse Tracking
document.querySelectorAll('.liquid-btn').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        btn.style.setProperty('--x', `${x}px`);
        btn.style.setProperty('--y', `${y}px`);
    });
});

// Add Ripple Effect
function createRipple(event) {
    const button = event.currentTarget;
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple-effect');

    button.appendChild(ripple);

    setTimeout(() => ripple.remove(), 600);
}

document.querySelectorAll('.ripple').forEach(btn => {
    btn.addEventListener('click', createRipple);
});
// Note: Modal population is handled by the event listener in DOMContentLoaded
// These functions are kept for backwards compatibility if needed

// Legacy function for backwards compatibility
function populateLoanModal(button) {
    console.log('populateLoanModal called (legacy function)');
    // This is now handled by the modal show.bs.modal event listener
}

// Legacy function for backwards compatibility
function showLoanDetails(loanData) {
    console.log('showLoanDetails called (legacy function)');
    // This is now handled by the modal show.bs.modal event listener
}

// Helper function to safely set element text
function setElementText(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        // Ensure we always display something, even if value is null/undefined/empty
        const displayValue = value !== null && value !== undefined && value !== '' ? value : 'N/A';
        element.textContent = displayValue;
        console.log(`Set ${elementId} to: ${displayValue}`);
    } else {
        console.warn(`Element with id '${elementId}' not found`);
    }
}

// Helper function to safely set element HTML
function setElementHTML(elementId, html) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = html || 'N/A';
    } else {
        console.warn(`Element with id '${elementId}' not found`);
    }
}

// Helper function to format currency
function formatCurrency(amount) {
    if (!amount || amount === null || amount === undefined || amount === '' || isNaN(amount)) {
        return 'N/A';
    }
    return parseFloat(amount).toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' TZS';
}

// Helper function to format dates
function formatDateValue(dateStr) {
    if (!dateStr || dateStr === null || dateStr === undefined || dateStr === '') {
        return 'N/A';
    }
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) {
            return 'N/A';
        }
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } catch (e) {
        return dateStr || 'N/A';
    }
}

// Helper function to get approval badge HTML
function getApprovalBadgeHTML(approval) {
    switch(approval) {
        case 'APPROVED':
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>';
        case 'REJECTED':
            return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rejected</span>';
        case 'CANCELLED':
            return '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Cancelled</span>';
        case 'PENDING':
        case null:
        case undefined:
        case '':
            return '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>';
        default:
            return '<span class="badge bg-secondary">' + approval + '</span>';
    }
}

// Helper function to get status badge HTML
function getStatusBadgeHTML(status) {
    if (!status) {
        return '<span class="badge bg-secondary">New</span>';
    }

    switch(status.toLowerCase()) {
        case 'disbursed':
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Disbursed</span>';
        case 'disbursement_pending':
            return '<span class="badge bg-info"><i class="fas fa-hourglass-half me-1"></i>NMB Processing</span>';
        case 'disbursement_failed':
            return '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Failed</span>';
        case 'full_settled':
            return '<span class="badge bg-dark"><i class="fas fa-handshake me-1"></i>Settled</span>';
        default:
            return '<span class="badge bg-secondary">' + status + '</span>';
    }
}

// Test function to manually open modal
function testModal() {
    const modalElement = document.getElementById('loanDetailsModal');
    if (modalElement) {
        console.log('Modal element found');
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap is loaded');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('Bootstrap is not loaded!');
            // Fallback: try jQuery modal
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('Using jQuery modal');
                $('#loanDetailsModal').modal('show');
            } else {
                console.error('Neither Bootstrap nor jQuery modal available');
            }
        }
    } else {
        console.error('Modal element not found');
    }
}

// Global variable to store current KPI status
let currentKPIStatus = null;
let currentKPIData = null;

// Function to show KPI details with period fallback
function showKPIDetails(status) {
    currentKPIStatus = status;
    const modal = new bootstrap.Modal(document.getElementById('kpiDetailModal'));
    const modalTitle = document.getElementById('kpiDetailModalLabel');
    const contentDiv = document.getElementById('kpiDetailContent');
    const filterControls = document.getElementById('kpiFilterControls');
    const spinner = document.getElementById('kpiSpinner');

    // Reset filter to auto
    document.getElementById('kpiPeriodFilter').value = 'auto';
    document.getElementById('kpiStartDateDiv').style.display = 'none';
    document.getElementById('kpiEndDateDiv').style.display = 'none';

    // Show spinner, hide content and filters initially
    spinner.style.display = 'block';
    contentDiv.style.display = 'none';
    filterControls.style.display = 'none';

    // Show modal
    modal.show();

    // Fetch data with auto period detection
    fetchKPIData(status, 'auto');
}

// Function to update weekly counts
function updateWeeklyCounts(stats) {
    if (stats) {
        // Update footer counts
        document.getElementById('pending-weekly').textContent = stats.pending || 0;
        document.getElementById('approved-weekly').textContent = stats.approved || 0;
        document.getElementById('rejected-weekly').textContent = stats.rejected || 0;
        document.getElementById('cancelled-weekly').textContent = stats.cancelled || 0;
        document.getElementById('disbursed-weekly').textContent = stats.disbursed || 0;

        // Update weekly text in subtitle
        document.getElementById('pending-weekly-txt').textContent = `+${stats.pending || 0} this week`;
        document.getElementById('approved-weekly-txt').textContent = `+${stats.approved || 0} this week`;
        document.getElementById('rejected-weekly-txt').textContent = `${stats.rejected || 0} this week`;
        document.getElementById('cancelled-weekly-txt').textContent = `${stats.cancelled || 0} this week`;
        document.getElementById('disbursed-weekly-txt').textContent = `+${stats.disbursed || 0} this week`;
    }
}

// Function to format numbers
function formatNumber(num) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num || 0);
}

// Function to format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Function to get status badge
function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'approved': '<span class="badge bg-success">Approved</span>',
        'rejected': '<span class="badge bg-danger">Rejected</span>',
        'cancelled': '<span class="badge bg-secondary">Cancelled</span>',
        'disbursed': '<span class="badge bg-info">Disbursed</span>'
    };
    return badges[status?.toLowerCase()] || '<span class="badge bg-secondary">Unknown</span>';
}

// Function to export KPI data
function exportKPIData() {
    // Implementation for exporting data
    alert('Export functionality will be implemented soon');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');

    // Fetch initial weekly stats
    fetch('/api/loan-offers/weekly-stats?summary=true')
        .then(response => response.json())
        .then(data => {
            updateWeeklyCounts(data);
        })
        .catch(error => console.error('Error fetching weekly stats:', error));

    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded on DOMContentLoaded');
    } else {
        console.log('Bootstrap is available');
    }

    // Initialize tooltips
    initializeTooltips();

    // Initialize animations
    animateNumbers();

    // Setup event listeners
    setupEventListeners();

    // Initialize Bootstrap dropdowns (ensure they work)
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Setup modal event listener
    const loanModal = document.getElementById('loanDetailsModal');
    if (loanModal) {
        loanModal.addEventListener('show.bs.modal', function (event) {
            console.log('Modal is opening...');
            const button = event.relatedTarget; // Button that triggered the modal
            if (button) {
                console.log('Triggered by button:', button);

                // Get the loan data from the button
                const loanDataStr = button.getAttribute('data-loan');
                console.log('Raw loan data:', loanDataStr);

                let loan;
                try {
                    loan = JSON.parse(loanDataStr);
                    console.log('Parsed loan data:', loan);
                    console.log('Available loan fields:', Object.keys(loan));
                } catch (e) {
                    console.error('Error parsing loan data:', e);
                    alert('Error loading loan details');
                    return;
                }

                // Populate all modal fields
                // Personal Information
                const fullName = `${loan.first_name || ''} ${loan.middle_name || ''} ${loan.last_name || ''}`.trim();
                console.log('Setting personal info - Full Name:', fullName);
                setElementText('modal-full-name', fullName || 'N/A');
                setElementText('modal-check-number', loan.check_number || 'N/A');
                setElementText('modal-nin', loan.nin || 'N/A');
                setElementText('modal-sex', loan.sex === 'M' ? 'Male' : loan.sex === 'F' ? 'Female' : 'N/A');
                setElementText('modal-marital-status', loan.marital_status || 'N/A');

                // Contact Information
                console.log('Setting contact info...');
                setElementText('modal-mobile', loan.mobile_number || 'N/A');
                setElementText('modal-email', loan.email_address || 'N/A');
                setElementText('modal-telephone', loan.telephone_number || 'N/A');
                setElementText('modal-address', loan.physical_address || 'N/A');

                // Employment Information
                console.log('Setting employment info...');
                setElementText('modal-designation', loan.designation_name || 'N/A');
                setElementText('modal-vote-name', loan.vote_name || 'N/A');
                setElementText('modal-vote-code', loan.vote_code || 'N/A');
                setElementText('modal-terms', loan.terms_of_employment || 'N/A');
                setElementText('modal-employment-date', formatDateValue(loan.employment_date));
                setElementText('modal-confirmation-date', formatDateValue(loan.confirmation_date));
                setElementText('modal-retirement-date', formatDateValue(loan.retirement_date));

                // Loan Information
                console.log('Setting loan info...');
                setElementText('modal-app-number', loan.application_number || 'PENDING');
                setElementText('modal-loan-purpose', loan.loan_purpose || 'N/A');
                setElementText('modal-fsp-ref', loan.fsp_reference_number || 'N/A');
                setElementText('modal-loan-number', loan.loan_number || 'N/A');
                setElementText('modal-tenure', loan.tenure ? `${loan.tenure} months` : 'N/A');
                setElementText('modal-interest-rate', loan.interest_rate ? `${loan.interest_rate}%` : 'N/A');
                setElementText('modal-processing-fee', formatCurrency(loan.processing_fee));
                setElementText('modal-insurance', formatCurrency(loan.insurance));

                // Financial Information
                console.log('Setting financial info...');
                setElementText('modal-basic-salary', formatCurrency(loan.basic_salary));
                setElementText('modal-net-salary', formatCurrency(loan.net_salary));
                setElementText('modal-one-third', formatCurrency(loan.one_third_amount));
                setElementText('modal-deductions', formatCurrency(loan.total_employee_deduction));
                setElementText('modal-requested-amount', formatCurrency(loan.requested_amount));
                setElementText('modal-net-loan-amount', formatCurrency(loan.take_home_amount || loan.net_loan_amount));
                setElementText('modal-total-amount', formatCurrency(loan.total_amount_to_pay));
                setElementText('modal-monthly-deduction', formatCurrency(loan.desired_deductible_amount));
                setElementText('modal-other-charges', formatCurrency(loan.other_charges));

                // Banking Information
                console.log('Setting banking info...');
                console.log('Full loan data:', loan); // Debug full loan object
                console.log('Bank data:', loan.bank); // Debug bank object
                console.log('SWIFT code from loan:', loan.swift_code); // Debug swift code

                // Employee's Salary Bank Information
                if (loan.bank && loan.bank.name) {
                    // Bank relationship exists and has data
                    const bankDisplay = loan.bank.short_name || loan.bank.name;
                    const fullDisplay = loan.bank.short_name ?
                        `${loan.bank.short_name} (${loan.bank.name})` :
                        loan.bank.name;
                    setElementText('modal-bank-name', fullDisplay);
                    setElementText('modal-swift-code', loan.bank.swift_code);
                    console.log('Bank found:', fullDisplay);
                } else if (loan.swift_code) {
                    // No bank relationship but has SWIFT code
                    setElementText('modal-bank-name', 'Bank (See SWIFT Code)');
                    setElementText('modal-swift-code', loan.swift_code);
                    console.log('No bank relationship, using SWIFT:', loan.swift_code);
                } else {
                    // No bank information at all
                    setElementText('modal-bank-name', 'Not specified');
                    setElementText('modal-swift-code', 'N/A');
                    console.log('No bank information available');
                }
                setElementText('modal-account-number', loan.bank_account_number || 'N/A');

                // URASACCOS Branch Information
                setElementText('modal-branch-name', loan.nearest_branch_name || 'N/A');
                setElementText('modal-branch-code', loan.nearest_branch_code || 'N/A');

                // Status Information
                console.log('Setting status info...');
                setElementHTML('modal-approval-status', getApprovalBadgeHTML(loan.approval));
                setElementHTML('modal-processing-status', getStatusBadgeHTML(loan.status));
                setElementText('modal-created-date', formatDateValue(loan.created_at));
                setElementText('modal-updated-date', formatDateValue(loan.updated_at));

                // Process Loan Tab - Set current status
                setElementHTML('process-current-approval', getApprovalBadgeHTML(loan.approval));
                setElementHTML('process-current-status', getStatusBadgeHTML(loan.status));
                document.getElementById('process-loan-id').value = loan.id;

                // Check if loan is cancelled and update Process Loan tab accordingly
                const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());

                const statusAlert = document.getElementById('process-status-alert');
                const cancelledWarning = document.getElementById('process-cancelled-warning');
                const actionCards = document.querySelectorAll('.action-card');

                if (isCancelled) {
                    // Show cancellation warning and hide normal status
                    if (statusAlert) statusAlert.style.display = 'none';
                    if (cancelledWarning) cancelledWarning.style.display = 'block';

                    // Disable approve and reject action cards
                    actionCards.forEach(card => {
                        const isApprove = card.innerHTML.includes('Approve Loan');
                        const isReject = card.innerHTML.includes('Reject Loan');

                        if (isApprove || isReject) {
                            card.style.opacity = '0.5';
                            card.style.cursor = 'not-allowed';
                            card.setAttribute('disabled', 'true');
                        }
                    });
                } else {
                    // Show normal status and hide cancellation warning
                    if (statusAlert) statusAlert.style.display = 'block';
                    if (cancelledWarning) cancelledWarning.style.display = 'none';

                    // Enable all action cards
                    actionCards.forEach(card => {
                        card.style.opacity = '1';
                        card.style.cursor = 'pointer';
                        card.removeAttribute('disabled');
                    });
                }

                console.log('All modal fields populated');

                // Initialize Bootstrap tabs properly
                setTimeout(() => {
                    // Remove any existing active classes and reset tabs
                    document.querySelectorAll('.nav-tabs-ura .nav-link').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    // Set the first tab as active
                    const firstTab = document.querySelector('.nav-tabs-ura .nav-link[href="#personal-info"]');
                    const firstPane = document.getElementById('personal-info');

                    if (firstTab && firstPane) {
                        firstTab.classList.add('active');
                        firstPane.classList.add('show', 'active', 'fade');
                        console.log('Reset tabs to Personal Information');
                    }

                    // Re-initialize all tab click handlers
                    document.querySelectorAll('.nav-tabs-ura .nav-link').forEach(tabLink => {
                        tabLink.addEventListener('click', function(e) {
                            e.preventDefault();
                            const tab = new bootstrap.Tab(this);
                            tab.show();
                            console.log('Switched to tab:', this.getAttribute('href'));
                        });
                    });
                }, 100);
            }
        });
        
        // Fix for close buttons not working
        console.log('Setting up modal close button handlers...');
        
        // Handle modal close buttons explicitly
        const modalElement = document.getElementById('loanDetailsModal');
        if (modalElement) {
            // Find all close buttons in the modal
            const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            console.log('Found ' + closeButtons.length + ' close buttons');
            
            closeButtons.forEach(button => {
                // Remove any existing click handlers
                button.replaceWith(button.cloneNode(true));
            });
            
            // Re-select the buttons after cloning
            const newCloseButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            
            newCloseButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Close button clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Get the modal instance
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                        console.log('Modal hidden using instance');
                    } else {
                        // If no instance exists, create one and hide it
                        const newModal = new bootstrap.Modal(modalElement);
                        newModal.hide();
                        console.log('Modal hidden using new instance');
                    }
                });
            });
        }
        
        // Also handle ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modalElement = document.getElementById('loanDetailsModal');
                if (modalElement && modalElement.classList.contains('show')) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            }
        });
        
    } else {
        console.error('Loan modal element not found during initialization');
    }
});

// Initialize tooltips
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

// Animate numbers on load
function animateNumbers() {
    document.querySelectorAll('.stat-number').forEach(element => {
        const target = parseInt(element.getAttribute('data-value') || element.innerText);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.innerText = Math.floor(current).toLocaleString();
        }, 20);
    });
}

// Setup event listeners
function setupEventListeners() {
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.loan-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                updateRowSelection(cb);
            });
            updateBulkActions();
        });
    }

    // Individual checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(cb => {
        cb.addEventListener('change', function(e) {
            e.stopPropagation(); // Prevent row click when checkbox is clicked
            updateRowSelection(this);
            updateBulkActions();
        });
    });

    // Prevent row click on checkbox labels
    document.querySelectorAll('.modern-checkbox label').forEach(label => {
        label.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Add click event to clickable rows
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on checkbox, button, or link
            if (e.target.closest('.modern-checkbox') ||
                e.target.closest('.action-buttons') ||
                e.target.closest('button') ||
                e.target.closest('a')) {
                return;
            }

            // Get the loan data from the row
            const loanDataStr = this.getAttribute('data-loan');
            if (loanDataStr) {
                try {
                    const loan = JSON.parse(loanDataStr);

                    // Create a temporary button to trigger the modal
                    const tempButton = document.createElement('button');
                    tempButton.setAttribute('data-loan', loanDataStr);
                    tempButton.setAttribute('data-bs-toggle', 'modal');
                    tempButton.setAttribute('data-bs-target', '#loanDetailsModal');
                    tempButton.style.display = 'none';
                    document.body.appendChild(tempButton);

                    // Trigger the modal
                    const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));

                    // Set the relatedTarget for the modal event
                    const modalEvent = new Event('show.bs.modal');
                    modalEvent.relatedTarget = tempButton;
                    document.getElementById('loanDetailsModal').dispatchEvent(modalEvent);

                    // Show the modal
                    modal.show();

                    // Clean up the temporary button
                    setTimeout(() => tempButton.remove(), 100);
                } catch (error) {
                    console.error('Error parsing loan data:', error);
                }
            }
        });
    });
}

// Update row selection state
function updateRowSelection(checkbox) {
    const row = checkbox.closest('tr');
    if (checkbox.checked) {
        row.classList.add('selected');
    } else {
        row.classList.remove('selected');
    }
}

// Update bulk actions count
function updateBulkActions() {
    const selected = document.querySelectorAll('.loan-checkbox:checked').length;
    const selectedCount = document.getElementById('selected-count');
    if (selectedCount) {
        selectedCount.textContent = selected;
    }

    // Show/hide bulk actions
    if (selected > 0) {
        showBulkActionsBar(selected);
    } else {
        hideBulkActionsBar();
    }
}

// Show bulk actions bar
function showBulkActionsBar(count) {
    // Create or update bulk actions floating bar
    let bulkBar = document.getElementById('bulk-actions-bar');
    if (!bulkBar) {
        bulkBar = document.createElement('div');
        bulkBar.id = 'bulk-actions-bar';
        bulkBar.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 bg-white rounded-pill shadow-lg';
        bulkBar.style.zIndex = '1050';
        bulkBar.style.transition = 'all 0.3s ease';
        document.body.appendChild(bulkBar);
    }

    bulkBar.innerHTML = `
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary">${count} selected</span>
            <button class="btn btn-sm btn-success" onclick="bulkApprove()">
                <i class="fas fa-check me-1"></i>Approve
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                <i class="fas fa-times me-1"></i>Reject
            </button>
            <button class="btn btn-sm btn-secondary" onclick="bulkExport()">
                <i class="fas fa-file-export me-1"></i>Export
            </button>
            <button class="btn btn-sm btn-light" onclick="clearSelection()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    `;

    bulkBar.style.transform = 'translate(-50%, 0)';
}

// Hide bulk actions bar
function hideBulkActionsBar() {
    const bulkBar = document.getElementById('bulk-actions-bar');
    if (bulkBar) {
        bulkBar.style.transform = 'translate(-50%, 100px)';
        setTimeout(() => bulkBar.remove(), 300);
    }
}

// Clear selection
function clearSelection() {
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.loan-checkbox').forEach(cb => {
        cb.checked = false;
        updateRowSelection(cb);
    });
    updateBulkActions();
}

// Quick disburse - Original version
function quickDisburse(loanId) {
    console.log('Quick disburse called for loan:', loanId);

    // Check if Swal is available
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 library is not loaded. Using default confirm dialog.');
        if (confirm('Send this loan to NMB for processing?')) {
            processQuickDisburse(loanId);
        }
        return;
    }

    Swal.fire({
        title: 'Confirm Disbursement',
        text: 'Send this loan to NMB for processing?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Disburse',
        confirmButtonColor: '#1e8449',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Sending to NMB Bank',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX call - check if jQuery is available
            if (typeof $ === 'undefined' && typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded');
                // Use fetch API as fallback
                fetch(`{{ route('loan-offers.update', '') }}/${loanId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        status: 'SUBMITTED_FOR_DISBURSEMENT'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Loan sent to NMB',
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process disbursement'
                    });
                });
            } else {
                // Use jQuery AJAX
                $.ajax({
                    url: `{{ route('loan-offers.update', '') }}/${loanId}`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'SUBMITTED_FOR_DISBURSEMENT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Loan sent to NMB',
                            timer: 3000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process disbursement'
                        });
                    }
                });
            }
        }
    });
}

// Sync with ESS with progress indication
function refreshFromESS() {
    // Show sync modal
    const syncModal = Swal.fire({
        title: 'Syncing with ESS',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-sync fa-spin fa-3x text-primary"></i>
                </div>
                <p>Fetching latest loan applications from ESS...</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // Make actual sync request
    fetch('/loan-offers/sync', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        syncModal.close();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sync Complete',
                html: `
                    <div>
                        <p>${data.stats.message}</p>
                        <ul class="text-start">
                            <li>Total Applications: ${data.stats.total}</li>
                            <li>New Today: ${data.stats.new_today}</li>
                            <li>Pending: ${data.stats.pending}</li>
                        </ul>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Sync Failed',
                text: data.message || 'Failed to sync with ESS'
            });
        }
    })
    .catch(error => {
        syncModal.close();
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Unable to connect to ESS system'
        });
    });
}

// Sort table
function sortTable(column) {
    const url = new URL(window.location);
    const currentSort = url.searchParams.get('sort');
    const currentOrder = url.searchParams.get('order') || 'asc';

    if (currentSort === column) {
        url.searchParams.set('order', currentOrder === 'asc' ? 'desc' : 'asc');
    } else {
        url.searchParams.set('sort', column);
        url.searchParams.set('order', 'asc');
    }

    window.location.href = url.toString();
}

// Quick filters
// New improved filter functions
function applyQuickFilter(filter) {
    const form = document.getElementById('filter-form');
    const today = new Date();

    // Remove all active classes first
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.classList.remove('active');
    });

    // Add active class to clicked pill
    document.querySelector(`[data-filter="${filter}"]`)?.classList.add('active');

    switch(filter) {
        case 'today':
            form.querySelector('[name="date_from"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'week':
            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            form.querySelector('[name="date_from"]').value = weekAgo.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'month':
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            form.querySelector('[name="date_from"]').value = firstDayOfMonth.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'pending':
            form.querySelector('[name="status"]').value = 'pending_approval';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
        case 'approved':
            form.querySelector('[name="status"]').value = 'approved';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
        case 'disbursed':
            form.querySelector('[name="status"]').value = 'disbursed';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
    }

    form.submit();
}

function resetFilters() {
    const form = document.getElementById('filter-form');
    form.querySelector('[name="search"]').value = '';
    form.querySelector('[name="status"]').value = '';
    form.querySelector('[name="date_from"]').value = '';
    form.querySelector('[name="date_to"]').value = '';
    form.submit();
}

function clearSearchField() {
    const form = document.getElementById('filter-form');
    form.querySelector('[name="search"]').value = '';
    form.submit();
}

function saveFilterPreset() {
    const form = document.getElementById('filter-form');
    const preset = {
        search: form.querySelector('[name="search"]').value,
        status: form.querySelector('[name="status"]').value,
        date_from: form.querySelector('[name="date_from"]').value,
        date_to: form.querySelector('[name="date_to"]').value
    };

    localStorage.setItem('loanFilterPreset', JSON.stringify(preset));

    Swal.fire({
        icon: 'success',
        title: 'Filter Saved',
        text: 'Your filter preset has been saved successfully!',
        timer: 1500,
        showConfirmButton: false
    });
}

// Load counts for quick filters on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchQuickFilterCounts();
    animateKPICards();
    initializeSparklines();

    // Handle advanced filter toggle
    const advancedBtn = document.querySelector('[data-bs-target="#advancedFilters"]');
    const advancedCollapse = document.getElementById('advancedFilters');

    if (advancedBtn && advancedCollapse) {
        advancedCollapse.addEventListener('show.bs.collapse', function () {
            advancedBtn.innerHTML = '<i class="fas fa-sliders-h me-1"></i>Hide Advanced';
            advancedBtn.classList.remove('btn-ura-light');
            advancedBtn.classList.add('btn-ura-primary');
        });

        advancedCollapse.addEventListener('hide.bs.collapse', function () {
            advancedBtn.innerHTML = '<i class="fas fa-sliders-h me-1"></i>Advanced';
            advancedBtn.classList.remove('btn-ura-primary');
            advancedBtn.classList.add('btn-ura-light');
        });
    }
});

function fetchQuickFilterCounts() {
    // Fetch today count
    fetch('/api/loan-offers/kpi-details?period=today')
        .then(response => response.json())
        .then(data => {
            const todayTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) +
                              (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('today-count').textContent = todayTotal;
        })
        .catch(error => console.error('Error fetching today count:', error));

    // Fetch week count
    fetch('/api/loan-offers/kpi-details?period=weekly')
        .then(response => response.json())
        .then(data => {
            const weekTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) +
                             (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('week-count').textContent = weekTotal;
        })
        .catch(error => console.error('Error fetching week count:', error));

    // Fetch month count
    fetch('/api/loan-offers/kpi-details?period=monthly')
        .then(response => response.json())
        .then(data => {
            const monthTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) +
                              (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('month-count').textContent = monthTotal;
        })
        .catch(error => console.error('Error fetching month count:', error));
}

// Keep the old function for compatibility
function setQuickFilter(filter) {
    applyQuickFilter(filter);
}

// Modern KPI Card Animations
function animateKPICards() {
    // Ensure cards are visible
    const cards = document.querySelectorAll('.kpi-card-modern');
    cards.forEach((card, index) => {
        // Make sure card is visible
        card.style.opacity = '1';
        card.style.visibility = 'visible';

        // Re-trigger animation if needed
        const animateClass = `animate-float-${index + 1}`;
        if (card.parentElement.classList.contains(animateClass)) {
            // Animation should already be applied via CSS class
        }
    });

    // Add entrance-complete class after animation finishes
    setTimeout(() => {
        cards.forEach(card => {
            card.classList.add('entrance-complete');
            card.style.opacity = '1'; // Ensure visibility
        });
    }, 2200); // After all cards have finished animating (1.5s + 0.6s delay + buffer)

    // Animate numbers on load
    setTimeout(() => {
        document.querySelectorAll('.number-animate').forEach(element => {
            const finalValue = parseInt(element.textContent);
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 30);
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                element.textContent = currentValue;
            }, 30);
        });
    }, 800); // Start after cards begin appearing

    // Animate progress bars
    setTimeout(() => {
        document.querySelectorAll('.progress-fill').forEach(bar => {
            const randomWidth = Math.floor(Math.random() * 60) + 20; // Random between 20-80%
            bar.style.width = randomWidth + '%';
        });
    }, 1500); // After cards are mostly visible
}

// Initialize mini sparkline charts
function initializeSparklines() {
    const sparklineData = {
        pending: [5, 8, 12, 7, 15, 9, 11, 14],
        approved: [3, 7, 10, 15, 12, 18, 20, 16],
        rejected: [2, 1, 3, 2, 1, 4, 2, 3],
        cancelled: [1, 0, 2, 1, 0, 1, 2, 1],
        disbursed: [8, 10, 12, 14, 11, 16, 18, 20]
    };

    // Create mini charts for each status
    Object.keys(sparklineData).forEach(status => {
        const canvas = document.getElementById(`${status}-sparkline`);
        if (canvas && canvas.getContext) {
            const ctx = canvas.getContext('2d');
            const data = sparklineData[status];
            const width = canvas.width;
            const height = canvas.height;
            const padding = 2;
            const max = Math.max(...data);

            ctx.clearRect(0, 0, width, height);
            ctx.strokeStyle = getStatusColor(status);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            ctx.beginPath();
            data.forEach((value, index) => {
                const x = (index / (data.length - 1)) * (width - padding * 2) + padding;
                const y = height - ((value / max) * (height - padding * 2)) - padding;

                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.stroke();

            // Add gradient fill
            const gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(0, getStatusColor(status) + '33');
            gradient.addColorStop(1, getStatusColor(status) + '00');

            ctx.lineTo(width - padding, height - padding);
            ctx.lineTo(padding, height - padding);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();
        }
    });
}

function getStatusColor(status) {
    const colors = {
        pending: '#FF8C00',
        approved: '#28a745',
        rejected: '#dc3545',
        cancelled: '#6c757d',
        disbursed: '#003366'
    };
    return colors[status] || '#003366';
}

// Add smooth hover tilt effect (reduced intensity)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.kpi-card-modern').forEach(card => {
        let isHovering = false;

        card.addEventListener('mouseenter', () => {
            isHovering = true;
            card.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mousemove', (e) => {
            if (!isHovering) return;

            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Reduced rotation for subtler effect
            const rotateX = (y - centerY) / 20; // Reduced from /10 to /20
            const rotateY = (centerX - x) / 20; // Reduced from /10 to /20

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px) scale(1.01)`;
        });

        card.addEventListener('mouseleave', () => {
            isHovering = false;
            card.style.transition = 'transform 0.5s ease';
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0) scale(1)';
        });
    });
});

// Clear filters
function clearFilters() {
    const form = document.getElementById('filter-form');
    form.reset();
    window.location.href = '{{ route("loan-offers.index") }}';
}

// Change page size
function changePageSize(size) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', size);
    window.location.href = url.toString();
}

// Export report with real data
function exportReport(format) {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    const params = new URLSearchParams(window.location.search);
    params.append('format', format);
    if (selected.length > 0) {
        params.append('selected', selected.join(','));
    }

    // Show loading indicator
    Swal.fire({
        title: 'Generating Export',
        html: `<div class="text-center">
            <i class="fas fa-file-export fa-3x mb-3 text-primary"></i>
            <p>Preparing your ${format.toUpperCase()} file...</p>
        </div>`,
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 1500
    });

    // Trigger download
    setTimeout(() => {
        window.location.href = `/loan-offers/export?${params.toString()}`;
        Swal.close();
    }, 1000);
}

// Show bulk actions modal
function showBulkActions() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one loan application'
        });
        return;
    }

    Swal.fire({
        title: 'Bulk Actions',
        html: `
            <p>You have selected ${selected.length} loan application(s)</p>
            <p class="text-muted">Choose an action to apply:</p>
        `,
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="fas fa-check"></i> Approve All',
        denyButtonText: '<i class="fas fa-times"></i> Reject All',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        denyButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            performBulkAction('approve', selected);
        } else if (result.isDenied) {
            performBulkAction('reject', selected);
        }
    });
}

// Perform bulk action
function performBulkAction(action, ids) {
    Swal.fire({
        title: 'Processing',
        html: '<i class="fas fa-spinner fa-spin fa-3x"></i>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    fetch('/loan-offers/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            ids: ids
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 2000
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Operation failed. Please try again.'
        });
    });
}

// View timeline
function viewTimeline(loanId) {
    console.log('View timeline called for loan:', loanId);

    // For now, show a simple timeline modal since the endpoint might not exist yet
    Swal.fire({
        title: 'Activity Timeline',
        html: `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Loading timeline for loan #${loanId}...</p>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        didOpen: () => {
            // Try to fetch timeline data
            fetch(`/loan-offers/${loanId}/callbacks`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Timeline not available');
                    }
                    // For now just close and show message
                    Swal.fire({
                        title: 'Timeline',
                        text: 'Timeline feature will be available soon',
                        icon: 'info'
                    });
                })
                .catch(error => {
                    console.log('Timeline endpoint not available, showing placeholder');
                    Swal.fire({
                        title: 'Loan Timeline',
                        html: `
                            <div class="timeline-placeholder">
                                <p><strong>Loan ID:</strong> ${loanId}</p>
                                <hr>
                                <div class="text-start">
                                    <p><i class="fas fa-clock text-muted me-2"></i>Application Submitted</p>
                                    <p><i class="fas fa-check-circle text-success me-2"></i>Application Reviewed</p>
                                    <p><i class="fas fa-spinner text-primary me-2"></i>Processing...</p>
                                </div>
                            </div>
                        `,
                        width: '500px',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                });
        }
    });
}

// Process Loan Modal Functions
let currentLoanData = null;

function selectProcessAction(action) {
    // Check if loan is cancelled before allowing any action
    const loanId = document.getElementById('process-loan-id').value;
    const row = document.querySelector(`tr[data-id="${loanId}"]`);

    if (row) {
        const dataLoanAttr = row.getAttribute('data-loan');
        if (dataLoanAttr) {
            try {
                const loan = JSON.parse(dataLoanAttr);
                const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());

                if (isCancelled && (action === 'approve' || action === 'reject')) {
                    Swal.fire({
                        title: action === 'approve' ? 'Cannot Approve' : 'Cannot Reject',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-ban mb-3" style="font-size: 48px; color: #dc3545;"></i>
                                <p>This loan has been <strong>cancelled by the employee</strong> through ESS.</p>
                                <small class="text-muted">Cancelled loans cannot be ${action === 'approve' ? 'approved' : 'rejected'} or processed.</small>
                            </div>
                        `,
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            } catch (e) {
                console.error('Error parsing loan data:', e);
            }
        }
    }

    const actionForm = document.getElementById('process-action-form');
    const reasonField = document.getElementById('process-reason-field');
    const actionType = document.getElementById('process-action-type');
    const submitBtn = document.getElementById('process-submit-btn');

    // Reset all action cards
    document.querySelectorAll('.action-card').forEach(card => {
        card.classList.remove('border-primary', 'bg-light');
    });

    // Highlight selected action card
    event.currentTarget.classList.add('border-primary', 'bg-light');

    // Set action type
    actionType.value = action;

    // Show/hide reason field based on action
    if (action === 'reject' || action === 'draft') {
        reasonField.style.display = 'block';
        document.getElementById('process-reason').required = true;
    } else {
        reasonField.style.display = 'none';
        document.getElementById('process-reason').required = false;
    }

    // Update submit button text
    switch(action) {
        case 'approve':
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Approve Loan';
            submitBtn.className = 'btn btn-success';
            break;
        case 'reject':
            submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Reject Loan';
            submitBtn.className = 'btn btn-danger';
            break;
        case 'draft':
            submitBtn.innerHTML = '<i class="fas fa-archive me-2"></i>Save as Draft';
            submitBtn.className = 'btn btn-warning';
            break;
    }

    // Show action form
    actionForm.style.display = 'block';
}

function cancelProcessAction() {
    document.getElementById('process-action-form').style.display = 'none';
    document.getElementById('loan-process-form').reset();

    // Reset action cards
    document.querySelectorAll('.action-card').forEach(card => {
        card.classList.remove('border-primary', 'bg-light');
    });
}

// Handle process form submission
document.getElementById('loan-process-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const action = formData.get('action');
    const loanId = formData.get('loan_id');
    const reason = formData.get('reason');

    // Show loading state
    const submitBtn = document.getElementById('process-submit-btn');
    if (submitBtn) {
        const spinner = submitBtn.querySelector('.spinner-border');
        if (spinner) {
            spinner.style.display = 'inline-block';
        }
        submitBtn.disabled = true;
    }

    // Use the existing updateLoanOffer method
    let url = `/loan-offers/${loanId}`;

    // Prepare data based on action
    let postData = {
        _token: '{{ csrf_token() }}',
        _method: 'PUT'  // Laravel method spoofing for PUT requests
    };

    switch(action) {
        case 'approve':
            postData.approval = 'APPROVED';
            postData.status = 'APPROVED';
            break;
        case 'reject':
            postData.approval = 'REJECTED';
            postData.status = 'REJECTED';
            postData.reason = reason;
            break;
        case 'draft':
            postData.status = 'DRAFT';
            postData.reason = reason;
            break;
    }

    // Log for debugging
    console.log('Processing loan action:', action);
    console.log('Request URL:', url);
    console.log('Request data:', postData);

    // Make AJAX request
    $.ajax({
        url: url,
        method: 'POST',  // Using POST with _method field for Laravel
        data: postData,
        success: function(response) {
            console.log('Success response:', response);
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: `Loan has been ${action}ed successfully.`,
                confirmButtonColor: '#17479E'
            }).then(() => {
                $('#loanDetailsModal').modal('hide');
                location.reload();
            });
        },
        error: function(xhr, status, error) {
            console.error('Error response:', xhr.responseJSON);
            console.error('Status:', status);
            console.error('Error:', error);

            let errorMessage = 'Failed to process the loan.';
            if (xhr.responseJSON?.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON?.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseText) {
                errorMessage = `Server error: ${xhr.status}`;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonColor: '#17479E'
            });
        },
        complete: function() {
            if (submitBtn) {
                const spinner = submitBtn.querySelector('.spinner-border');
                if (spinner) {
                    spinner.style.display = 'none';
                }
                submitBtn.disabled = false;
            }
        }
    });
});

// Approve loan - using update method
function approveLoan(loanId) {
    // First check if the loan is cancelled or already approved/rejected
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row) {
        const dataLoanAttr = row.getAttribute('data-loan');
        if (dataLoanAttr) {
            try {
                const loan = JSON.parse(dataLoanAttr);
                const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());

                if (isCancelled) {
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

                // Check if already approved
                if ((loan.approval || '').toUpperCase() === 'APPROVED') {
                    Swal.fire({
                        title: 'Already Approved',
                        text: 'This loan has already been approved.',
                        icon: 'info',
                        confirmButtonColor: '#003366'
                    });
                    return;
                }

                // Check if already rejected
                if ((loan.approval || '').toUpperCase() === 'REJECTED') {
                    Swal.fire({
                        title: 'Already Rejected',
                        text: 'This loan has already been rejected and cannot be approved.',
                        icon: 'warning',
                        confirmButtonColor: '#003366'
                    });
                    return;
                }
            } catch (e) {
                console.error('Error parsing loan data:', e);
            }
        }
    }

    if (typeof Swal === 'undefined') {
        if (confirm('Are you sure you want to approve this loan application?')) {
            approveWithAjax(loanId);
        }
        return;
    }

    Swal.fire({
        title: 'Approve Loan Application',
        text: 'Are you sure you want to approve this loan application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Approve',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            approveWithAjax(loanId);
        }
    });
}

function approveWithAjax(loanId) {
    $.ajax({
        url: `/loan-offers/${loanId}`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            approval: 'APPROVED'
        },
        success: function(response) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Approved!', 'The loan has been approved.', 'success')
                    .then(() => location.reload());
            } else {
                alert('The loan has been approved.');
                location.reload();
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to approve the loan.';
            if (xhr.responseJSON) {
                if (xhr.responseJSON.error === 'LOAN_CANCELLED') {
                    errorMessage = 'This loan has been cancelled by the employee and cannot be approved.';
                } else if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        }
    });
}

// Reject loan - using update method
function rejectLoan(loanId) {
    // Get loan data from the row if available
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    let loanData = {};

    if (row) {
        // Try to get data from data-loan attribute first
        const dataLoanAttr = row.getAttribute('data-loan');
        if (dataLoanAttr) {
            try {
                const loan = JSON.parse(dataLoanAttr);

                // Check if loan is cancelled first
                const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                   ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());

                if (isCancelled) {
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

                // Check if already approved
                if ((loan.approval || '').toUpperCase() === 'APPROVED') {
                    Swal.fire({
                        title: 'Already Approved',
                        text: 'This loan has already been approved and cannot be rejected.',
                        icon: 'warning',
                        confirmButtonColor: '#003366'
                    });
                    return;
                }

                // Check if already rejected
                if ((loan.approval || '').toUpperCase() === 'REJECTED') {
                    Swal.fire({
                        title: 'Already Rejected',
                        text: 'This loan has already been rejected.',
                        icon: 'info',
                        confirmButtonColor: '#003366'
                    });
                    return;
                }

                loanData = {
                    application_number: loan.application_number || loan.fsp_code || 'N/A',
                    name: `${loan.first_name || ''} ${loan.last_name || ''}`.trim() || 'N/A',
                    amount: loan.requested_amount ? `TZS ${Number(loan.requested_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` : 'N/A',
                    date: loan.created_at ? new Date(loan.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'
                };
            } catch (e) {
                console.error('Error parsing loan data:', e);
            }
        }

        // Fallback to extracting from cells if data-loan parsing fails
        if (!loanData.application_number || loanData.application_number === 'N/A') {
            const cells = row.querySelectorAll('td');
            loanData = {
                application_number: cells[1]?.querySelector('.employee-id')?.textContent?.trim() || 'N/A',
                name: cells[1]?.querySelector('.employee-name')?.textContent?.trim() || 'N/A',
                amount: cells[3]?.querySelector('.amount-primary')?.textContent?.trim() || 'N/A',
                date: cells[6]?.textContent?.trim() || 'N/A'
            };
        }
    }

    // Use the new modal
    showRejectLoanModal(loanId, loanData);
}

// Process the loan rejection after modal confirmation
function processLoanRejection(loanId, reason, message) {
    rejectWithAjax(loanId, message);
}

function rejectWithAjax(loanId, reason) {
    $.ajax({
        url: `/loan-offers/${loanId}`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            approval: 'REJECTED',
            reason: reason
        },
        success: function(response) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Rejected!', 'The loan has been rejected.', 'success')
                    .then(() => location.reload());
            } else {
                alert('The loan has been rejected.');
                location.reload();
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to reject the loan.';
            if (xhr.responseJSON) {
                if (xhr.responseJSON.error === 'LOAN_CANCELLED') {
                    errorMessage = 'This loan has been cancelled by the employee and cannot be rejected.';
                } else if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        }
    });
}

// Make functions globally accessible
window.approveLoan = approveLoan;
window.rejectLoan = rejectLoan;

// Bulk actions
function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select at least one loan to approve.', 'warning');
        } else {
            alert('Please select at least one loan to approve.');
        }
        return;
    }

    // Check if any selected loans are cancelled, already approved, or rejected
    let cancelledLoans = [];
    let approvedLoans = [];
    let rejectedLoans = [];
    selected.forEach(loanId => {
        const row = document.querySelector(`tr[data-id="${loanId}"]`);
        if (row) {
            const dataLoanAttr = row.getAttribute('data-loan');
            if (dataLoanAttr) {
                try {
                    const loan = JSON.parse(dataLoanAttr);
                    const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                       ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                       ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());
                    if (isCancelled) {
                        cancelledLoans.push(loan.application_number || loan.fsp_code || loanId);
                    } else if ((loan.approval || '').toUpperCase() === 'APPROVED') {
                        approvedLoans.push(loan.application_number || loan.fsp_code || loanId);
                    } else if ((loan.approval || '').toUpperCase() === 'REJECTED') {
                        rejectedLoans.push(loan.application_number || loan.fsp_code || loanId);
                    }
                } catch (e) {
                    console.error('Error parsing loan data:', e);
                }
            }
        }
    });

    if (cancelledLoans.length > 0 || approvedLoans.length > 0 || rejectedLoans.length > 0) {
        let errorHtml = '<div class="text-center">';
        errorHtml += '<i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #dc3545;"></i>';

        if (cancelledLoans.length > 0) {
            errorHtml += '<p>The following loans have been <strong>cancelled</strong> and cannot be approved:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${cancelledLoans.join('<br>')}</div>`;
        }

        if (approvedLoans.length > 0) {
            errorHtml += '<p class="mt-3">The following loans are <strong>already approved</strong>:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${approvedLoans.join('<br>')}</div>`;
        }

        if (rejectedLoans.length > 0) {
            errorHtml += '<p class="mt-3">The following loans have been <strong>rejected</strong> and cannot be approved:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${rejectedLoans.join('<br>')}</div>`;
        }

        errorHtml += '<small class="text-muted mt-3 d-block">Please unselect these loans and try again.</small>';
        errorHtml += '</div>';

        Swal.fire({
            title: 'Cannot Approve Selected Loans',
            html: errorHtml,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        return;
    }

    processBulkAction('approve', selected);
}

// Make bulk functions globally accessible
window.bulkApprove = bulkApprove;
window.bulkReject = bulkReject;
window.bulkExport = bulkExport;
window.processBulkAction = processBulkAction;
window.processBulkActionWithReason = processBulkActionWithReason;

// Export selected loans
function bulkExport() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;

    // Export selected loans
    const params = new URLSearchParams();
    params.append('ids', selected.join(','));
    params.append('format', 'excel');

    window.location.href = `/loan-offers/export?${params.toString()}`;

    Swal.fire({
        icon: 'success',
        title: 'Exporting...',
        text: `Exporting ${selected.length} selected loan(s)`,
        timer: 2000,
        showConfirmButton: false
    });
}

function bulkReject() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        Swal.fire('No Selection', 'Please select at least one loan to reject.', 'warning');
        return;
    }

    // Check if any selected loans are cancelled, already approved, or rejected
    let cancelledLoans = [];
    let approvedLoans = [];
    let rejectedLoans = [];
    selected.forEach(loanId => {
        const row = document.querySelector(`tr[data-id="${loanId}"]`);
        if (row) {
            const dataLoanAttr = row.getAttribute('data-loan');
            if (dataLoanAttr) {
                try {
                    const loan = JSON.parse(dataLoanAttr);
                    const isCancelled = ['CANCELLED', 'CANCELED'].includes((loan.approval || '').toUpperCase()) ||
                                       ['CANCELLED', 'CANCELED'].includes((loan.status || '').toUpperCase()) ||
                                       ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'].includes((loan.state || '').toUpperCase());
                    if (isCancelled) {
                        cancelledLoans.push(loan.application_number || loan.fsp_code || loanId);
                    } else if ((loan.approval || '').toUpperCase() === 'APPROVED') {
                        approvedLoans.push(loan.application_number || loan.fsp_code || loanId);
                    } else if ((loan.approval || '').toUpperCase() === 'REJECTED') {
                        rejectedLoans.push(loan.application_number || loan.fsp_code || loanId);
                    }
                } catch (e) {
                    console.error('Error parsing loan data:', e);
                }
            }
        }
    });

    if (cancelledLoans.length > 0 || approvedLoans.length > 0 || rejectedLoans.length > 0) {
        let errorHtml = '<div class="text-center">';
        errorHtml += '<i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #dc3545;"></i>';

        if (cancelledLoans.length > 0) {
            errorHtml += '<p>The following loans have been <strong>cancelled</strong> and cannot be rejected:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${cancelledLoans.join('<br>')}</div>`;
        }

        if (approvedLoans.length > 0) {
            errorHtml += '<p class="mt-3">The following loans are <strong>already approved</strong> and cannot be rejected:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${approvedLoans.join('<br>')}</div>`;
        }

        if (rejectedLoans.length > 0) {
            errorHtml += '<p class="mt-3">The following loans are <strong>already rejected</strong>:</p>';
            errorHtml += `<div class="mt-2 p-2 bg-light rounded">${rejectedLoans.join('<br>')}</div>`;
        }

        errorHtml += '<small class="text-muted mt-3 d-block">Please unselect these loans and try again.</small>';
        errorHtml += '</div>';

        Swal.fire({
            title: 'Cannot Reject Selected Loans',
            html: errorHtml,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Use the new modal for bulk rejection
    showBulkRejectModal(selected);
}

// Process bulk loan rejection after modal confirmation
function processBulkLoanRejection(loanIds, reason, message) {
    processBulkActionWithReason('reject', loanIds, message);
}

function processBulkAction(action, ids) {
    if (ids.length === 0) return;

    // Set appropriate colors based on action
    let confirmColor = '#667eea';
    if (action === 'approve') confirmColor = '#28a745';
    if (action === 'reject') confirmColor = '#dc3545';

    Swal.fire({
        title: `Confirm Bulk ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Are you sure you want to ${action} ${ids.length} loan(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Yes, ${action}`,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show processing
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we process your request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Process bulk action
            $.ajax({
                url: '/loan-offers/bulk-action',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    ids: ids
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || `Successfully processed ${ids.length} loan(s)`,
                        confirmButtonColor: '#17479E'
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to process bulk action.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }
    });
}

// Process bulk action with reason (for rejections)
function processBulkActionWithReason(action, ids, reason) {
    if (ids.length === 0) return;

    // Show processing
    Swal.fire({
        title: 'Processing...',
        html: 'Please wait while we process your request',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Process bulk action
    $.ajax({
        url: '/loan-offers/bulk-action',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            action: action,
            ids: ids,
            reason: reason || ''
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || `Successfully ${action}ed ${ids.length} loan(s)`,
                confirmButtonColor: '#17479E'
            }).then(() => location.reload());
        },
        error: function(xhr) {
            let errorMessage = 'Failed to process bulk action.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

// Toggle between table and card view
function toggleTableView() {
    // Implementation for switching between table and card view
    document.querySelector('.table-responsive').classList.toggle('card-view');
}

// Function to fetch KPI data with period support
function fetchKPIData(status, period, startDate = null, endDate = null) {
    const spinner = document.getElementById('kpiSpinner');
    const contentDiv = document.getElementById('kpiDetailContent');
    const filterControls = document.getElementById('kpiFilterControls');
    const periodInfo = document.getElementById('kpiPeriodInfo');
    const periodText = document.getElementById('kpiPeriodText');
    const modalTitle = document.getElementById('kpiDetailModalLabel');

    // Build query params
    let params = new URLSearchParams({
        status: status,
        period: period
    });

    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);

    fetch(`/api/loan-offers/kpi-details?${params}`)
        .then(response => response.json())
        .then(data => {
            currentKPIData = data;

            // Hide spinner, show content and filters
            spinner.style.display = 'none';
            contentDiv.style.display = 'block';
            filterControls.style.display = 'block';

            // Update modal title with period info
            const statusTitles = {
                'pending': '<i class="fas fa-clock me-2" style="color: white;"></i><span style="color: white;">Pending Applications</span>',
                'approved': '<i class="fas fa-check-circle me-2" style="color: white;"></i><span style="color: white;">Approved Applications</span>',
                'rejected': '<i class="fas fa-times-circle me-2" style="color: white;"></i><span style="color: white;">Rejected Applications (URAERP)</span>',
                'cancelled': '<i class="fas fa-ban me-2" style="color: white;"></i><span style="color: white;">Cancelled Applications (Employee)</span>',
                'disbursed': '<i class="fas fa-hand-holding-usd me-2" style="color: white;"></i><span style="color: white;">Disbursed Loans</span>'
            };

            modalTitle.innerHTML = `${statusTitles[status]} <span style="color: white;">- ${data.period_label}</span>`;

            // Show period info if auto-detected
            if (data.period !== period && period === 'auto') {
                periodInfo.style.display = 'block';
                let infoText = `Showing ${data.period_label} data`;
                if (data.total_count === 0) {
                    infoText += ' (No data found in weekly or monthly periods)';
                } else if (data.period === 'month') {
                    infoText += ' (No weekly data available)';
                } else if (data.period === 'year') {
                    infoText += ' (No weekly or monthly data available)';
                }
                periodText.textContent = infoText;
            } else {
                periodInfo.style.display = 'none';
            }

            // Build table HTML
            let tableHTML = `
                <div class="mb-3">
                    <h6 class="text-muted">Period: ${data.period_start} - ${data.period_end}</h6>
                    <h4 style="color: var(--ura-primary);">Total Count: ${data.total_count}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Application #</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Take Home</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            if (data.loans && data.loans.length > 0) {
                data.loans.forEach(loan => {
                    tableHTML += `
                        <tr>
                            <td>${loan.application_number}</td>
                            <td>${loan.first_name} ${loan.last_name}</td>
                            <td>TZS ${formatNumber(loan.requested_amount)}</td>
                            <td class="text-success">TZS ${formatNumber(loan.take_home_amount || loan.net_loan_amount)}</td>
                            <td>${formatDate(loan.created_at)}</td>
                            <td>
                                <a href="/loan-offers/${loan.id}/edit" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableHTML += `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No ${status} applications in ${data.period_label.toLowerCase()}
                        </td>
                    </tr>
                `;
            }

            tableHTML += `
                        </tbody>
                    </table>
                </div>

                <!-- Period Summary Stats -->
                <div class="mt-3 p-3 bg-light rounded">
                    <h6 class="text-muted mb-2">Period Summary</h6>
                    <div class="row g-2">
                        <div class="col">
                            <small class="text-muted">Pending</small>
                            <div class="fw-bold">${data.stats.pending || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Approved</small>
                            <div class="fw-bold text-success">${data.stats.approved || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Rejected</small>
                            <div class="fw-bold text-danger">${data.stats.rejected || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Cancelled</small>
                            <div class="fw-bold text-secondary">${data.stats.cancelled || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Disbursed</small>
                            <div class="fw-bold text-info">${data.stats.disbursed || 0}</div>
                        </div>
                    </div>
                </div>
            `;

            contentDiv.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error fetching KPI details:', error);
            spinner.style.display = 'none';
            contentDiv.style.display = 'block';
            contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load data</div>';
        });
}

// Function to refresh KPI details with current filters
function refreshKPIDetails() {
    const period = document.getElementById('kpiPeriodFilter').value;
    let startDate = null;
    let endDate = null;

    if (period === 'custom') {
        startDate = document.getElementById('kpiStartDate').value;
        endDate = document.getElementById('kpiEndDate').value;

        if (!startDate || !endDate) {
            alert('Please select both start and end dates');
            return;
        }
    }

    fetchKPIData(currentKPIStatus, period, startDate, endDate);
}

// Handle period filter change
document.addEventListener('DOMContentLoaded', function() {
    const periodFilter = document.getElementById('kpiPeriodFilter');
    if (periodFilter) {
        periodFilter.addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            document.getElementById('kpiStartDateDiv').style.display = isCustom ? 'block' : 'none';
            document.getElementById('kpiEndDateDiv').style.display = isCustom ? 'block' : 'none';
        });
    }
});
</script>
@endpush
