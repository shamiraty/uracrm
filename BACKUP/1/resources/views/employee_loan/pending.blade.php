@extends('employee_loan.base_template')

@section('page_title', 'Pending Loans')
@section('page_heading', 'Pending Loan Applications')
@section('page_description', 'Review and process pending loan applications')

@section('page_icon')
    <i class="fas fa-hourglass-half"></i>
@endsection

@section('kpi_section')
<!-- Modern KPI Dashboard with Glassmorphism - Pending Focused -->
<div class="kpi-dashboard-modern mb-3">
    <div class="kpi-backdrop"></div>
    <div class="row g-3">
        <!-- Total Pending -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-1" onclick="showKPIDetails('pending')" style="cursor: pointer;">
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
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ $stats['total'] ?? 0 }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-icon pending-trend">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span class="trend-text">Total Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-2" onclick="showKPIDetails('pending')" style="cursor: pointer;">
                <div class="kpi-glow info-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern info-icon">
                            <i class="fas fa-coins"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern info-badge">AMOUNT</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ number_format($stats['total_amount'] ?? 0, 0) }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-text text-info">Total TZS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Amount -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-3" onclick="showKPIDetails('pending')" style="cursor: pointer;">
                <div class="kpi-glow purple-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern purple-icon">
                            <i class="fas fa-chart-line"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern purple-badge">AVERAGE</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <h2 class="kpi-value-modern">
                            <span class="number-animate">{{ number_format($stats['average_amount'] ?? 0, 0) }}</span>
                        </h2>
                        <div class="kpi-trend-modern">
                            <span class="trend-text">Avg Amount TZS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-3">
            <div class="kpi-card-modern glass-card animate-float-4" onclick="showQuickActions()" style="cursor: pointer;">
                <div class="kpi-glow success-glow"></div>
                <div class="kpi-content-modern">
                    <div class="kpi-header-modern">
                        <div class="kpi-icon-modern success-icon">
                            <i class="fas fa-tasks"></i>
                            <div class="icon-pulse-ring"></div>
                        </div>
                        <div class="kpi-badge-modern success-badge">ACTIONS</div>
                    </div>
                    <div class="kpi-value-wrapper">
                        <div class="quick-action-buttons">
                            <button class="btn btn-sm btn-success" onclick="bulkApprove()">
                                <i class="fas fa-check-circle"></i> Bulk Approve
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                                <i class="fas fa-times-circle"></i> Bulk Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('search_section')
<!-- URASACCOS Modern Filter Section -->
<style>
/* URASACCOS Brand Colors - Correct Colors */
:root {
    --ura-primary: #17479E;      /* URASACCOS Blue */
    --ura-primary-dark: #003366;  /* Dark Blue */
    --ura-primary-light: #2196F3; /* Light Blue */
    --ura-accent: #FF8C00;        /* Orange Accent */
    --ura-gold: #FFA500;          /* Gold */
    --ura-success: #28a745;       /* Green */
    --ura-danger: #dc3545;        /* Red */
    --ura-warning: #FF8C00;       /* Warning Orange */
    --ura-info: #17a2b8;          /* Info Blue */
}

/* Modern Filter Container */
.ura-filter-section {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(255, 140, 0, 0.02) 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;  /* Reduced from 30px */
    border: 1px solid rgba(23, 71, 158, 0.1);
    position: relative;
    overflow: hidden;
}

.ura-filter-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(23, 71, 158, 0.05) 0%, transparent 70%);
    border-radius: 50%;
}

/* Search Input Styling */
.ura-search-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(23, 71, 158, 0.08);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.ura-search-box:focus-within {
    border-color: var(--ura-primary);
    box-shadow: 0 5px 20px rgba(23, 71, 158, 0.15);
    transform: translateY(-1px);
}

.ura-search-input {
    border: none;
    padding: 15px 50px 15px 55px;
    font-size: 15px;
    width: 100%;
    background: transparent;
    color: #2C3E50;
}

.ura-search-input:focus {
    outline: none;
}

.ura-search-input::placeholder {
    color: #95A5A6;
}

.ura-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ura-primary);
    font-size: 18px;
}

.ura-search-clear {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--ura-danger);
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ura-search-clear:hover {
    background: #C62828;
    transform: translateY(-50%) scale(1.1);
}

/* Quick Filter Pills */
.ura-quick-filters {
    display: flex;
    gap: 10px;
    padding: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
}

.ura-filter-pill {
    padding: 10px 20px;
    border-radius: 10px;
    border: 2px solid transparent;
    background: #F8F9FA;
    color: #495057;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.ura-filter-pill::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background: linear-gradient(90deg, var(--ura-primary), var(--ura-primary-light));
    transition: width 0.3s ease;
    z-index: -1;
}

.ura-filter-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 71, 158, 0.2);
    border-color: var(--ura-primary);
}

.ura-filter-pill.active {
    background: linear-gradient(135deg, var(--ura-primary), var(--ura-primary-light));
    color: white;
    border-color: var(--ura-primary);
    box-shadow: 0 5px 15px rgba(23, 71, 158, 0.3);
}

.ura-filter-pill.active i {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Advanced Filter Toggle */
.ura-advanced-toggle {
    background: linear-gradient(135deg, var(--ura-accent), var(--ura-gold));
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
}

.ura-advanced-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 111, 0, 0.4);
}

.ura-advanced-toggle .badge {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    padding: 4px 8px;
    animation: badge-pulse 2s infinite;
}

@keyframes badge-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<div class="ura-filter-section">
    <!-- Main Search and Quick Filters Row -->
    <div class="row g-3 align-items-center mb-3">
        <!-- Search Box -->
        <div class="col-lg-5">
            <div class="ura-search-box">
                <i class="fas fa-search ura-search-icon"></i>
                <input type="text" 
                       id="searchInput" 
                       class="ura-search-input" 
                       placeholder="Search loans by name, number, or check..."
                       value="{{ request('search') }}">
                @if(request('search'))
                    <button class="ura-search-clear" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Quick Date Filters -->
        <div class="col-lg-5">
            <div class="ura-quick-filters">
                <button class="ura-filter-pill {{ !request('date_range') ? 'active' : '' }}" 
                        onclick="filterByDateRange('')">
                    <i class="fas fa-infinity"></i>
                    <span>All Time</span>
                </button>
                <button class="ura-filter-pill {{ request('date_range') == 'today' ? 'active' : '' }}" 
                        onclick="filterByDateRange('today')">
                    <i class="fas fa-sun"></i>
                    <span>Today</span>
                </button>
                <button class="ura-filter-pill {{ request('date_range') == 'week' ? 'active' : '' }}" 
                        onclick="filterByDateRange('week')">
                    <i class="fas fa-calendar-week"></i>
                    <span>This Week</span>
                </button>
                <button class="ura-filter-pill {{ request('date_range') == 'month' ? 'active' : '' }}" 
                        onclick="filterByDateRange('month')">
                    <i class="fas fa-calendar-check"></i>
                    <span>This Month</span>
                </button>
            </div>
        </div>
        
        <!-- Advanced Filter Toggle -->
        <div class="col-lg-2 text-end">
            <button class="ura-advanced-toggle" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#advancedFilters" 
                    aria-expanded="false">
                <i class="fas fa-sliders-h"></i>
                <span>Advanced</span>
                @if(request('amount_min') || request('amount_max') || request('tenure') || request('bank') || request('date_from') || request('date_to'))
                    <span class="badge">Active</span>
                @endif
            </button>
        </div>
    </div>
    
    <!-- Advanced Filters Section (Collapsible) -->
    <style>
    /* Advanced Filter Styles */
    .ura-advanced-filters {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(23, 71, 158, 0.1);
    }
    
    .ura-filter-group {
        background: #F8F9FA;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 12px;
        border: 1px solid #E9ECEF;
        transition: all 0.3s ease;
    }
    
    .ura-filter-group:hover {
        background: white;
        border-color: var(--ura-primary-light);
        box-shadow: 0 3px 10px rgba(23, 71, 158, 0.1);
    }
    
    .ura-filter-label {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .ura-filter-label i {
        font-size: 14px;
        color: var(--ura-accent);
    }
    
    .ura-range-input {
        border: 2px solid #E9ECEF;
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .ura-range-input:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        outline: none;
    }
    
    .ura-select {
        border: 2px solid #E9ECEF;
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .ura-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        outline: none;
    }
    
    .ura-priority-option {
        padding: 8px 16px;
        border-radius: 8px;
        margin: 5px;
        cursor: pointer;
        background: white;
        border: 2px solid #E9ECEF;
        transition: all 0.3s ease;
    }
    
    .ura-priority-option:hover {
        border-color: var(--ura-primary);
        transform: translateY(-2px);
    }
    
    .ura-priority-option.selected {
        background: var(--ura-primary);
        color: white;
        border-color: var(--ura-primary);
    }
    
    .ura-apply-btn {
        background: linear-gradient(135deg, var(--ura-primary), var(--ura-primary-light));
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .ura-apply-btn:hover {
        box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        transform: translateY(-2px);
    }
    
    .ura-reset-btn {
        background: transparent;
        color: var(--ura-danger);
        border: 2px solid var(--ura-danger);
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .ura-reset-btn:hover {
        background: var(--ura-danger);
        color: white;
        box-shadow: 0 8px 20px rgba(229, 57, 53, 0.3);
    }
    
    .ura-batch-select-btn {
        background: linear-gradient(135deg, #667EEA, #764BA2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 48%;
    }
    
    .ura-batch-select-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .ura-export-btn {
        background: white;
        border: 2px solid #E9ECEF;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 32%;
    }
    
    .ura-export-btn:hover {
        border-color: var(--ura-primary);
        background: var(--ura-primary);
        color: white;
    }
    
    .ura-export-excel:hover { background: #1D8348; border-color: #1D8348; }
    .ura-export-pdf:hover { background: #C0392B; border-color: #C0392B; }
    .ura-export-csv:hover { background: #2980B9; border-color: #2980B9; }
    
    /* Active Filters Bar */
    .active-filters-bar {
        background: white;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .active-filters-label {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 14px;
    }
    
    .filter-tag {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.1), rgba(23, 71, 158, 0.05));
        border: 1px solid var(--ura-primary);
        color: var(--ura-primary-dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .filter-remove {
        color: var(--ura-danger);
        text-decoration: none;
        font-weight: bold;
        margin-left: 5px;
    }
    
    .filter-remove:hover {
        color: #a71d2a;
    }
    </style>
    
    <div class="collapse {{ request('amount_min') || request('amount_max') || request('tenure') || request('bank') || request('date_from') || request('date_to') ? 'show' : '' }}" id="advancedFilters">
        <div class="ura-advanced-filters">
            <form method="GET" action="{{ route('loans.pending') }}" id="advancedFilterForm">
                <!-- Preserve search if exists -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <div class="row g-3">
                    <!-- Financial Filters -->
                    <div class="col-md-6">
                        <div class="ura-filter-group">
                            <label class="ura-filter-label">
                                <i class="fas fa-coins"></i> Financial Filters
                            </label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <small class="text-muted">Amount Range (TZS)</small>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="ura-range-input" 
                                               name="amount_min" 
                                               placeholder="Minimum"
                                               value="{{ request('amount_min') }}">
                                        <span class="input-group-text" style="background: var(--ura-primary); color: white;">to</span>
                                        <input type="number" 
                                               class="ura-range-input" 
                                               name="amount_max" 
                                               placeholder="Maximum"
                                               value="{{ request('amount_max') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tenure</small>
                                    <select class="ura-select form-select" name="tenure">
                                        <option value="">All Durations</option>
                                        <option value="3" {{ request('tenure') == '3' ? 'selected' : '' }}>3 Months</option>
                                        <option value="6" {{ request('tenure') == '6' ? 'selected' : '' }}>6 Months</option>
                                        <option value="12" {{ request('tenure') == '12' ? 'selected' : '' }}>12 Months</option>
                                        <option value="18" {{ request('tenure') == '18' ? 'selected' : '' }}>18 Months</option>
                                        <option value="24" {{ request('tenure') == '24' ? 'selected' : '' }}>24 Months</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Bank</small>
                                    <select class="ura-select form-select" name="bank">
                                        <option value="">All Banks</option>
                                        @php
                                            $banks = \App\Models\LoanOffer::where(function($q) {
                                                $q->where('approval', 'PENDING')->orWhereNull('approval');
                                            })
                                            ->whereNotNull('swift_code')
                                            ->select('swift_code')
                                            ->distinct()
                                            ->pluck('swift_code');
                                        @endphp
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank }}" {{ request('bank') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date & Priority Filters -->
                    <div class="col-md-6">
                        <div class="ura-filter-group">
                            <label class="ura-filter-label">
                                <i class="fas fa-calendar-alt"></i> Date & Priority
                            </label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <small class="text-muted">Custom Date Range</small>
                                    <div class="input-group">
                                        <input type="date" 
                                               class="ura-range-input" 
                                               name="date_from" 
                                               value="{{ request('date_from') }}">
                                        <span class="input-group-text" style="background: var(--ura-warning); color: white;">to</span>
                                        <input type="date" 
                                               class="ura-range-input" 
                                               name="date_to" 
                                               value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Priority Level</small>
                                    <select class="ura-select form-select" name="priority">
                                        <option value="">All Priorities</option>
                                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>
                                            🔴 Urgent (7+ days waiting)
                                        </option>
                                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>
                                            🟠 High (4-6 days)
                                        </option>
                                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>
                                            🟡 Medium (2-3 days)
                                        </option>
                                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>
                                            🟢 Low (< 2 days)
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions Row -->
                <div class="row g-3 mt-3">
                    <!-- Quick Actions -->
                    <div class="col-md-4">
                        <div class="ura-filter-group">
                            <label class="ura-filter-label">
                                <i class="fas fa-bolt"></i> Quick Actions
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="ura-batch-select-btn" onclick="selectBatch('oldest')">
                                    <i class="fas fa-history"></i> Select Oldest 10
                                </button>
                                <button type="button" class="ura-batch-select-btn" onclick="selectBatch('highest')">
                                    <i class="fas fa-sort-amount-up"></i> Select Highest 10
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sort & Export -->
                    <div class="col-md-4">
                        <div class="ura-filter-group">
                            <label class="ura-filter-label">
                                <i class="fas fa-sort"></i> Sort & Export
                            </label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <select class="ura-select form-select" name="sort_by" onchange="this.form.submit()">
                                        <option value="created_asc" {{ request('sort_by') == 'created_asc' ? 'selected' : '' }}>
                                            📅 Date (Oldest First)
                                        </option>
                                        <option value="created_desc" {{ request('sort_by') == 'created_desc' ? 'selected' : '' }}>
                                            📅 Date (Newest First)
                                        </option>
                                        <option value="amount_asc" {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>
                                            💰 Amount (Low to High)
                                        </option>
                                        <option value="amount_desc" {{ request('sort_by') == 'amount_desc' ? 'selected' : '' }}>
                                            💰 Amount (High to Low)
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-1">
                                        <button type="button" class="ura-export-btn ura-export-excel" onclick="exportData('excel')">
                                            <i class="fas fa-file-excel"></i> Excel
                                        </button>
                                        <button type="button" class="ura-export-btn ura-export-pdf" onclick="exportData('pdf')">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </button>
                                        <button type="button" class="ura-export-btn ura-export-csv" onclick="exportData('csv')">
                                            <i class="fas fa-file-csv"></i> CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Apply/Reset Actions -->
                    <div class="col-md-4">
                        <div class="ura-filter-group" style="background: linear-gradient(135deg, rgba(46, 125, 50, 0.1), rgba(255, 111, 0, 0.05));">
                            <label class="ura-filter-label">
                                <i class="fas fa-check-circle"></i> Apply Filters
                            </label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="ura-apply-btn">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                                <a href="{{ route('loans.pending') }}" class="ura-reset-btn text-center text-decoration-none">
                                    <i class="fas fa-undo"></i> Reset All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    
<!-- Active Filters Display -->
@if(request()->hasAny(['search', 'date_range', 'amount_min', 'amount_max', 'tenure', 'bank', 'date_from', 'date_to', 'priority']))
<div class="active-filters-bar" style="margin-top: 10px; margin-bottom: 10px;">
        <span class="active-filters-label">Active Filters:</span>
        @if(request('search'))
            <span class="filter-tag">
                Search: {{ request('search') }}
                <a href="{{ route('loans.pending', array_merge(request()->except('search'))) }}" class="filter-remove">×</a>
            </span>
        @endif
        @if(request('date_range'))
            <span class="filter-tag">
                Period: {{ ucfirst(request('date_range')) }}
                <a href="{{ route('loans.pending', array_merge(request()->except('date_range'))) }}" class="filter-remove">×</a>
            </span>
        @endif
        @if(request('amount_min') || request('amount_max'))
            <span class="filter-tag">
                Amount: {{ number_format(request('amount_min', 0)) }} - {{ request('amount_max') ? number_format(request('amount_max')) : '∞' }}
                <a href="{{ route('loans.pending', array_merge(request()->except(['amount_min', 'amount_max']))) }}" class="filter-remove">×</a>
            </span>
        @endif
        @if(request('priority'))
            <span class="filter-tag">
                Priority: {{ ucfirst(request('priority')) }}
                <a href="{{ route('loans.pending', array_merge(request()->except('priority'))) }}" class="filter-remove">×</a>
            </span>
        @endif
        <a href="{{ route('loans.pending') }}" class="btn btn-sm btn-link text-danger">Clear All</a>
    </div>
    @endif
</div>
@endsection

@section('table_content')
@forelse($loanOffers as $offer)
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
        $daysWaiting = $offer->created_at ? now()->diffInDays($offer->created_at) : 0;
        $hoursWaiting = $offer->created_at ? now()->diffInHours($offer->created_at) : 0;
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
            <div class="d-flex flex-column align-items-center">
                <small class="text-muted">
                    {{ $offer->created_at ? $offer->created_at->format('d/m/Y H:i') : 'N/A' }}
                </small>
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
            </div>
        </td>
        <td class="text-center">
            <span class="modern-status-badge status-pending" title="Pending Approval" data-bs-toggle="tooltip">
                <i class="fas fa-clock"></i> Pending
            </span>
        </td>
        <td class="text-center">
            <span class="modern-status-badge status-new">
                <i class="fas fa-sparkles"></i> New
            </span>
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
                <a class="action-btn btn-preview"
                   href="{{ route('loan-offers.edit', $offer->id) }}"
                   title="Preview Loan">
                    <i class="fas fa-eye"></i>
                </a>
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
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12" class="text-center">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h5>No Pending Loan Applications</h5>
                <p>All loan applications have been processed</p>
            </div>
        </td>
    </tr>
@endforelse
@endsection

@section('pagination_section')
@if($loanOffers->hasPages())
    <div class="modern-table-footer">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <span class="text-muted">Showing {{ $loanOffers->firstItem() }} to {{ $loanOffers->lastItem() }} of {{ $loanOffers->total() }} entries</span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end">
                    {{ $loanOffers->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('additional_scripts')
<script>
// Real-time search functionality
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
    // Show/hide clear button
    this.nextElementSibling.style.display = this.value ? 'block' : 'none';
});

// Perform search
function performSearch() {
    const searchValue = document.getElementById('searchInput').value;
    const currentParams = new URLSearchParams(window.location.search);
    
    if (searchValue) {
        currentParams.set('search', searchValue);
    } else {
        currentParams.delete('search');
    }
    
    window.location.href = '{{ route("loans.pending") }}?' + currentParams.toString();
}

// Clear search
function clearSearch() {
    document.getElementById('searchInput').value = '';
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.delete('search');
    window.location.href = '{{ route("loans.pending") }}?' + currentParams.toString();
}

// Filter by date range
function filterByDateRange(range) {
    const currentParams = new URLSearchParams(window.location.search);
    
    if (range) {
        currentParams.set('date_range', range);
    } else {
        currentParams.delete('date_range');
    }
    
    // Remove custom date filters when using quick filters
    currentParams.delete('date_from');
    currentParams.delete('date_to');
    
    window.location.href = '{{ route("loans.pending") }}?' + currentParams.toString();
}

// Select batch of loans
function selectBatch(type) {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    const sortedLoans = Array.from(checkboxes);
    
    // Clear all selections first
    checkboxes.forEach(cb => cb.checked = false);
    
    if (type === 'oldest') {
        // Select first 10 (already sorted by oldest first)
        sortedLoans.slice(0, 10).forEach(cb => cb.checked = true);
    } else if (type === 'highest') {
        // Sort by amount and select top 10
        const loanRows = Array.from(document.querySelectorAll('.modern-table-row'));
        loanRows.sort((a, b) => {
            const amountA = parseFloat(JSON.parse(a.dataset.loan).requested_amount);
            const amountB = parseFloat(JSON.parse(b.dataset.loan).requested_amount);
            return amountB - amountA;
        });
        loanRows.slice(0, 10).forEach(row => {
            row.querySelector('.loan-checkbox').checked = true;
        });
    }
    
    updateBulkActionButtons();
}

// Export data functionality
function exportData(format) {
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.set('export', format);
    
    // Get selected loan IDs if any
    const selectedLoans = Array.from(document.querySelectorAll('.loan-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedLoans.length > 0) {
        currentParams.set('selected_ids', selectedLoans.join(','));
    }
    
    // Open in new window for download
    window.open('{{ route("loans.pending") }}?' + currentParams.toString(), '_blank');
}

// Update bulk action buttons based on selection
function updateBulkActionButtons() {
    const selectedCount = document.querySelectorAll('.loan-checkbox:checked').length;
    const bulkActions = document.querySelector('.bulk-actions');
    
    if (bulkActions) {
        if (selectedCount > 0) {
            bulkActions.style.display = 'block';
            bulkActions.querySelector('.selected-count').textContent = selectedCount;
        } else {
            bulkActions.style.display = 'none';
        }
    }
}

// Listen to checkbox changes
document.addEventListener('DOMContentLoaded', function() {
    // Individual checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButtons);
    });
    
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButtons();
        });
    }
    
    // Auto-submit sort dropdown
    const sortSelect = document.querySelector('[name="sort_by"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('advancedFilterForm').submit();
        });
    }
});

// Bulk approve function
function bulkApprove() {
    const selectedLoans = Array.from(document.querySelectorAll('.loan-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedLoans.length === 0) {
        alert('Please select loans to approve');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedLoans.length} loan(s)?`)) {
        // Send AJAX request to bulk approve
        fetch('{{ route("loans.bulk-approve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ loan_ids: selectedLoans })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Bulk reject function
function bulkReject() {
    const selectedLoans = Array.from(document.querySelectorAll('.loan-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedLoans.length === 0) {
        alert('Please select loans to reject');
        return;
    }
    
    const reason = prompt(`Enter rejection reason for ${selectedLoans.length} loan(s):`);
    
    if (reason) {
        // Send AJAX request to bulk reject
        fetch('{{ route("loans.bulk-reject") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                loan_ids: selectedLoans,
                reason: reason 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Show quick actions menu
function showQuickActions() {
    const modal = new bootstrap.Modal(document.getElementById('quickActionsModal'));
    modal.show();
}
</script>
@endsection