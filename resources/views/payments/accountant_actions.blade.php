@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb breadcrumb-style" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 12px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: #17479E;">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="" class="text-decoration-none" style="color: #17479E;">
                    <i class="fas fa-file-alt me-1"></i>Enquiries
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                <i class="fas fa-money-bill-wave me-1"></i>Payment Management (Accountant)
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-2">
                <i class="fas fa-calculator me-2" style="color: #17479E;"></i>Payment Management Dashboard
            </h2>
           
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-soft text-primary px-3 py-2">
                <i class="fas fa-user me-1"></i>Accountant Dashboard
            </span>
        </div>
    </div>

    <!-- Business Intelligence Analytics Dashboard -->
  <div class="row g-4 mb-4">
    
    @php
        // Class kwa ukubwa sawa kwa kadi 5 kwenye skrini kubwa (col-xl inagawa upana sawa kwa kadi 5)
        $col_class = 'col-xl col-lg-4 col-md-6'; 
    @endphp

    <div class="{{ $col_class }}">
        <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #007bff 100%); overflow: hidden;">
            <div class="card-body text-white p-3 position-relative">
                <i class="fas fa-chart-line fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                <div class="d-flex flex-column">
                    <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Total  </p>
                    <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['total']) }}</h4>
                   
                </div>
                
             
            </div>
        </div>
    </div>

    <div class="{{ $col_class }}">
        <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); overflow: hidden;">
            <div class="card-body text-white p-3 position-relative">
                <i class="fas fa-hourglass-half fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                <div class="d-flex flex-column">
                    <p class="mb-1 opacity-90 small fw-semibold text-uppercase">Pending Initiation</p>
                    <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['assigned_no_payment']) }}</h4>
                    <p class="mb-0 small text-white mt-1">{{ $analytics['total'] > 0 ? round(($analytics['assigned_no_payment'] / $analytics['total']) * 100, 1) : 0 }}% of total portfolio</p>
                </div>
                
                <hr class="my-3 border-light opacity-50">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="progress flex-grow-1 me-3" style="height: 5px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-light" role="progressbar" style="width: {{ $analytics['total'] > 0 ? ($analytics['assigned_no_payment'] / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="{{ $col_class }}">
        <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); overflow: hidden;">
            <div class="card-body text-white p-3 position-relative">
                <i class="fas fa-check-double fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                <div class="d-flex flex-column">
                    <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Ready to Pay</p>
                    <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['approved']) }}</h4>
                    <p class="mb-0 small opacity-75 mt-1 text-white">Approved and awaiting disbursement</p>
                </div>
                
                <hr class="my-3 border-light opacity-50">

                <div class="d-flex justify-content-between align-items-center">
                    <small class="opacity-75">
                        <i class="fas fa-percent me-1"></i> {{ $analytics['total'] > 0 ? round(($analytics['approved'] / $analytics['total']) * 100, 1) : 0 }}% Approved
                    </small>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="{{ $col_class }}">
        <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); overflow: hidden;">
            <div class="card-body text-white p-3 position-relative">
                <i class="fas fa-check-circle fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                <div class="d-flex flex-column">
                    <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Paid</p>
                    <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['paid']) }}</h4>
                    <p class="mb-0 small opacity-75 mt-1 text-white">{{ $analytics['total'] > 0 ? round(($analytics['paid'] / $analytics['total']) * 100, 1) : 0 }}% of total  </p>
                </div>
                
                <hr class="my-3 border-light opacity-50">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="progress flex-grow-1 me-3" style="height: 5px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-light" role="progressbar" style="width: {{ $analytics['total'] > 0 ? ($analytics['paid'] / $analytics['total']) * 100 : 0 }}%"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="{{ $col_class }}"> 
        <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%); overflow: hidden;">
            <div class="card-body text-white p-3 position-relative">
                <i class="fas fa-times-circle fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                <div class="d-flex flex-column">
                    <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Rejected</p>
                    <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['rejected']) }}</h4>
                    <p class="mb-0 small opacity-75 mt-1 text-white">{{ $analytics['total'] > 0 ? round(($analytics['rejected'] / $analytics['total']) * 100, 1) : 0 }}% of total portfolio</p>
                </div>
                
                 
            </div>
        </div>
    </div>

</div>

    <!-- Quick Actions Bar -->
    <div class="card border-0 shadow-sm mb-4" style="background-color: #f5f5f5;">
        <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter fa-lg me-2 text-primary"></i>
                    <span class="fw-bold text-primary">Quick Actions</span>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-check-circle me-1 text-primary"></i>Filters 
                        </span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-sliders-h me-1"></i>Advanced Filters
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('payment.accountant.dashboard', ['export' => 'excel_general']) }}'">
                        <i class="fas fa-file-excel me-1"></i>General Report
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i>Custom Report
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf me-1"></i>PDF Report
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('payment.accountant.dashboard') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-times me-1"></i>Clear All
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulkActions" class="card border-0 shadow-sm mb-4" style="display: none;">
        <div class="card-body bg-primary-soft">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium text-primary">
                    <span id="selectedCount">0</span> payments selected
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkReject()">
                        <i class="fas fa-times me-1"></i>Bulk Reject
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                        <i class="fas fa-times me-1"></i>Clear Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern BI Data Table -->
    <div class="card border-0 shadow-lg">
       

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 modern-table">
                    <thead style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                        <tr class="text-white">
                            <th width="50" class="text-center border-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" style="border-color: white;">
                                </div>
                            </th>
                            <th width="60" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-hashtag me-1"></i>
                                    <span class="fw-bold">#</span>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar me-2"></i>
                                    <span class="fw-bold">Date & Time</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt me-2"></i>
                                    <span class="fw-bold">Check Reference</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span class="fw-bold">Member Details</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-university me-2"></i>
                                    <span class="fw-bold">Bank Information</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tags me-2"></i>
                                    <span class="fw-bold">Category</span>
                                </div>
                            </th>
                            <th class="border-0 sortable" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    <span class="fw-bold">Status & Progress</span>
                                    <i class="fas fa-sort ms-1 opacity-75"></i>
                                </div>
                            </th>
                            <th width="150" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tools me-2"></i>
                                    <span class="fw-bold">Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                        @php
                            // Enhanced BI logic for overdue status and performance indicators
                            $isOverdue = false;
                            $overdueStatus = '';
                            $priorityLevel = 'normal';
                            $statusColor = 'primary';
                            $progressPercentage = 0;

                            if($enquiry->payment) {
                                $daysSinceCreated = $enquiry->payment->created_at->diffInDays(now());
                                switch($enquiry->payment->status) {
                                    case 'initiated':
                                        $progressPercentage = 25;
                                        $statusColor = 'info';
                                        if($daysSinceCreated >= 3) {
                                            $isOverdue = true;
                                            $overdueStatus = 'Overdue Approval';
                                            $priorityLevel = 'high';
                                        }
                                        break;
                                    case 'approved':
                                        $progressPercentage = 75;
                                        $statusColor = 'success';
                                        if($daysSinceCreated >= 2) {
                                            $isOverdue = true;
                                            $overdueStatus = 'Overdue Payment';
                                            $priorityLevel = 'critical';
                                        }
                                        break;
                                    case 'paid':
                                        $progressPercentage = 100;
                                        $statusColor = 'success';
                                        break;
                                    case 'rejected':
                                        $progressPercentage = 0;
                                        $statusColor = 'danger';
                                        break;
                                }
                            } else {
                                if($enquiry->created_at->diffInWeekdays(now()) >= 3) {
                                    $isOverdue = true;
                                    $overdueStatus = 'Overdue Initiation';
                                    $priorityLevel = 'critical';
                                }
                            }
                        @endphp
                        <tr class="table-row {{ $isOverdue ? 'table-warning-subtle' : '' }} {{ $priorityLevel === 'critical' ? 'border-start border-danger border-3' : '' }}">
                            <td class="text-center align-middle">
                                <div class="form-check">
                                    <input class="form-check-input payment-checkbox" type="checkbox"
                                           value="{{ $enquiry->id }}"
                                           style="transform: scale(1.1);">
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="badge badge-counter" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white; font-weight: bold;">
                                    {{ $loop->iteration + (($enquiries->currentPage() - 1) * $enquiries->perPage()) }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong class="text-dark">{{ $enquiry->date_received ?? $enquiry->created_at->format('M d, Y') }}</strong>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        <small class="text-muted">{{ $enquiry->created_at->format('H:i A') }}</small>
                                        <small class="text-muted ms-2">{{ $enquiry->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($isOverdue)
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                            <small class="text-danger fw-bold">{{ $priorityLevel === 'critical' ? 'CRITICAL' : 'High Priority' }}</small>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="check-number-container">
                                    <div class="badge bg-gradient-primary px-3 py-2 text-white fw-bold fs-6">
                                        <i class="fas fa-receipt me-1"></i>{{ $enquiry->check_number }}
                                    </div>
                                    <small class="text-muted d-block mt-1">Reference ID</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="member-info-card p-2 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-placeholder bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($enquiry->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block">{{ ucwords($enquiry->full_name) }}</strong>
                                        </div>
                                    </div>
                                    <div class="member-details">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-id-badge text-info me-1"></i>{{ $enquiry->force_no ?? 'No Force Number' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-phone text-success me-1"></i>{{ $enquiry->phone ?? 'No Phone' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="bank-info-card">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-university text-primary me-2 fa-lg"></i>
                                        <strong class="text-dark">{{ strtoupper($enquiry->bank_name ?? 'N/A') }}</strong>
                                    </div>
                                    <div class="account-details bg-light p-2 rounded">
                                        <small class="text-muted d-block">Account Number</small>
                                        <code class="text-dark fw-bold">{{ $enquiry->account_number ?? 'Not Available' }}</code>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="category-badge-container">
                                    <span class="badge bg-info-subtle text-info px-3 py-2 fw-bold">
                                        <i class="fas fa-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}
                                    </span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="status-progress-container">
                                    @if($isOverdue)
                                        <div class="status-badge-with-progress mb-2">
                                            <span class="badge bg-danger fs-6 text-white px-3 py-2 fw-bold">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $overdueStatus }}
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                        </div>
                                    @elseif($enquiry->payment)
                                        <div class="status-badge-with-progress mb-2">
                                            @switch($enquiry->payment->status)
                                                @case('initiated')
                                                    <span class="badge bg-info fs-6 px-3 py-2 fw-bold">
                                                        <i class="fas fa-rocket me-1"></i>In Progress
                                                    </span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success fs-6 px-3 py-2 fw-bold">
                                                        <i class="fas fa-check-double me-1"></i>Approved
                                                    </span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-primary fs-6 px-3 py-2 fw-bold">
                                                        <i class="fas fa-trophy me-1"></i>Completed
                                                    </span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                                                        <i class="fas fa-ban me-1"></i>Rejected
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning fs-6 px-3 py-2 fw-bold">
                                                        {{ ucfirst($enquiry->payment->status) }}
                                                    </span>
                                            @endswitch
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ $progressPercentage }}% Complete</small>
                                    @else
                                        <div class="status-badge-with-progress mb-2">
                                            <span class="badge bg-warning fs-6 px-3 py-2 fw-bold">
                                                <i class="fas fa-hourglass-start me-1"></i>Awaiting Start
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-warning progress-bar-striped" style="width: 10%"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1">Ready to initiate</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog me-1"></i>Actions
                                        </button>
                                        <ul class="dropdown-menu shadow border-0" style="min-width: 200px;">
                                            <!-- View Action -->
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewModal-{{ $enquiry->id }}">
                                                    <i class="fas fa-eye text-info me-2"></i>View Details
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>

                                            @if(!$enquiry->payment)
                                                <!-- No payment yet - show initiate button -->
                                                <li>
                                                    <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#initiateModal-{{ $enquiry->id }}">
                                                        <i class="fas fa-play me-2"></i>Initiate Payment
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                            @else
                                                @switch($enquiry->payment->status)
                                                    @case('initiated')
                                                        <li>
                                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $enquiry->payment->id }}">
                                                                <i class="fas fa-times me-2"></i>Reject Payment
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        @break
                                                    @case('approved')
                                                        <li>
                                                            <button type="button" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#payModal-{{ $enquiry->payment->id }}">
                                                                <i class="fas fa-money-bill-wave me-2"></i>Pay
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $enquiry->payment->id }}">
                                                                <i class="fas fa-times me-2"></i>Reject Payment
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        @break
                                                    @default
                                                        <li>
                                                            <span class="dropdown-item-text">
                                                                <span class="badge bg-secondary px-2 py-1">
                                                                    Completed
                                                                </span>
                                                            </span>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                @endswitch
                                            @endif

                                            <!-- View More Details -->
                                            <li>
                                                <a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}">
                                                    <i class="fas fa-external-link-alt text-secondary me-2"></i>View More Details
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                    <h5>No payment enquiries found</h5>
                                    <p>No enquiries assigned to you at this time.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($enquiries->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="text-muted fw-medium">
                        <i class="fas fa-info-circle me-2"></i>
                        Showing <span class="fw-bold text-primary">{{ $enquiries->firstItem() }}</span> to
                        <span class="fw-bold text-primary">{{ $enquiries->lastItem() }}</span> of
                        <span class="fw-bold text-primary">{{ number_format($enquiries->total()) }}</span> results
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-end">
                        {{ $enquiries->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@include('payments.modals.accountant_modals')

<style>
/* ===== BUSINESS INTELLIGENCE DASHBOARD STYLES ===== */

/* Enhanced Analytics Cards */
.analytics-card:hover {
    transform: translateY(-3px) scale(1.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 20px 40px rgba(23, 71, 158, 0.15) !important;
}

.analytics-card {
    transition: all 0.3s ease;
    border-radius: 16px !important;
    overflow: hidden;
}

/* Modern Table Styling */
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    border: none !important;
    padding: 1rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.modern-table tbody td {
    border: none !important;
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    padding: 1.25rem 1rem !important;
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(135, 206, 235, 0.03) 100%);
    transform: scale(1.001);
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.08);
}

/* Enhanced Member Info Cards */
.member-info-card {
    transition: all 0.2s ease;
    border-radius: 10px !important;
    border: 1px solid rgba(23, 71, 158, 0.1);
}

.member-info-card:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    box-shadow: 0 4px 8px rgba(23, 71, 158, 0.1);
}

.avatar-placeholder {
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    font-weight: bold;
    font-size: 14px;
}

/* Bank Info Cards */
.bank-info-card .account-details {
    border-left: 3px solid #17479E;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

.bank-info-card code {
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    color: white !important;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

/* Status Progress Containers */
.status-progress-container {
    min-width: 120px;
}

.status-badge-with-progress .badge {
    border-radius: 20px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Enhanced Badges */
.badge-counter {
    border-radius: 50% !important;
    width: 35px;
    height: 35px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 12px !important;
    box-shadow: 0 2px 8px rgba(23, 71, 158, 0.3);
}

.check-number-container .badge {
    border-radius: 25px !important;
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
    box-shadow: 0 3px 6px rgba(23, 71, 158, 0.3);
    font-size: 13px !important;
}

.category-badge-container .badge {
    border-radius: 15px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Priority Indicators */
.table-warning-subtle {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0.02) 100%) !important;
}

/* Progress Bars */
.progress {
    border-radius: 10px !important;
    background: rgba(0,0,0,0.05) !important;
}

.progress-bar {
    border-radius: 10px !important;
}

/* Soft Background Colors */
.bg-primary-soft {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.08) 0%, rgba(79, 172, 254, 0.05) 100%) !important;
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-success-subtle {
    background: linear-gradient(135deg, rgba(32, 201, 151, 0.08) 0%, rgba(25, 135, 84, 0.05) 100%) !important;
}

.bg-danger-subtle {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.08) 0%, rgba(220, 53, 69, 0.05) 100%) !important;
}

.bg-info-subtle {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(13, 202, 240, 0.05) 100%) !important;
}

.bg-warning-subtle {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.05) 100%) !important;
}

/* Action Buttons Enhancement */
.btn-sm {
    border-radius: 8px !important;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Card Enhancements */
.card {
    border-radius: 16px !important;
    border: none !important;
}

.card-header {
    border-radius: 16px 16px 0 0 !important;
}

/* Text Enhancements */
.text-info-soft {
    color: #0dcaf0 !important;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.table-row.border-start.border-danger {
    animation: pulse 2s infinite;
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .modern-table thead th,
    .modern-table tbody td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.875rem;
    }

    .member-info-card {
        padding: 0.75rem !important;
    }
}

/* Dropdown enhancements */
.dropdown-menu {
    border: none;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-radius: 12px;
    padding: 8px 0;
    min-width: 200px;
    z-index: 1060;
}

.dropdown-item {
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin: 2px 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #87CEEB 0%, #17479e 100%);
    color: white;
    transform: translateX(8px);
}

.dropdown-item i {
    margin-right: 10px;
    width: 16px;
    text-align: center;
}

.dropdown-divider {
    margin: 8px 16px;
    border-color: #e9ecef;
}

/* Fix dropdown positioning in table */
.table td .dropdown {
    position: static;
}

.table .dropdown-menu {
    position: absolute !important;
    z-index: 1060;
}

.table tbody tr:last-child .dropdown-menu {
    bottom: 100%;
    top: auto;
    transform: translateY(-8px);
}
</style>

<script>
// Bulk selection functionality
let selectedPayments = [];

document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkboxes
    document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectedPayments = Array.from(checkedBoxes).map(cb => cb.value);

    if (selectedPayments.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedPayments.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

function bulkReject() {
    updateBulkActions(); // Refresh selected payments
    if (selectedPayments.length === 0) {
        alert('Please select payments to reject.');
        return;
    }

    // Update count in modal
    document.getElementById('bulkRejectCount').textContent = selectedPayments.length;

    // Show bulk reject modal
    $('#bulkRejectModal').modal('show');
}

function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("payment.accountant.dashboard") }}?' + params.toString();
}
</script>

<!-- Modern Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-sliders-h me-2"></i>Advanced Search & Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="GET" action="{{ route('payment.accountant.dashboard') }}" id="filterForm">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-search me-1 text-primary"></i>Search Terms
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control form-control-lg" placeholder="Enter check number, full name, or account number...">
                            <small class="form-text text-muted">Search across check numbers, member names, and account numbers</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-info-circle me-1 text-info"></i>Payment Status
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value=""> All Payment Status</option>
                                <option value="assigned_no_payment" {{ request('status') === 'assigned_no_payment' ? 'selected' : '' }}>
                                    Awaiting Initiation
                                </option>
                                <option value="initiated" {{ request('status') === 'initiated' ? 'selected' : '' }}>
                                    Initiated (Pending Approval)
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                    Approved (Ready for Payment)
                                </option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>
                                    Paid (Completed)
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                     Rejected
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-clock me-1 text-warning"></i>Quick Date Ranges
                            </label>
                            <select class="form-select form-select-lg" id="quickDateRange">
                                <option value="">📅 Custom Date Range</option>
                                <option value="today">📆 Today</option>
                                <option value="yesterday">📆 Yesterday</option>
                                <option value="this_week">📅 This Week</option>
                                <option value="last_week">📅 Last Week</option>
                                <option value="this_month">📅 This Month</option>
                                <option value="last_month">📅 Last Month</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar me-1 text-success"></i>From Date
                            </label>
                            <input type="date" name="date_from" id="dateFrom" value="{{ request('date_from') }}"
                                   class="form-control form-control-lg">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-check me-1 text-success"></i>To Date
                            </label>
                            <input type="date" name="date_to" id="dateTo" value="{{ request('date_to') }}"
                                   class="form-control form-control-lg">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-list-alt me-1 text-primary"></i>Enquiry Type (Accountant)
                            </label>
                         <select name="type" class="form-select form-select-lg">
    <option value="">ALL ENQUIRY TYPES</option>
    <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>REFUND (KUREJESHEWA FEDHA)</option>
    <option value="share_enquiry" {{ request('type') === 'share_enquiry' ? 'selected' : '' }}>SHARE ENQUIRY (KUNUNUA HISA)</option>
    <option value="retirement" {{ request('type') === 'retirement' ? 'selected' : '' }}>RETIREMENT (KUSTAAFU KAZI)</option>
    <option value="deduction_add" {{ request('type') === 'deduction_add' ? 'selected' : '' }}>ADD DEDUCTION OF SAVINGS (KUONGEZA/KUPUNGUZA AKIBA)</option>
    <option value="withdraw_savings" {{ request('type') === 'withdraw_savings' ? 'selected' : '' }}>WITHDRAW SAVINGS (KUOMBA SEHEMU YA AKIBA)</option>
    <option value="withdraw_deposit" {{ request('type') === 'withdraw_deposit' ? 'selected' : '' }}>WITHDRAW DEPOSIT (KUTOA AMANA)</option>
    <option value="unjoin_membership" {{ request('type') === 'unjoin_membership' ? 'selected' : '' }}>UNJOIN MEMBERSHIP (KUJITOA UANACHAMA)</option>
    <option value="ura_mobile" {{ request('type') === 'ura_mobile' ? 'selected' : '' }}>URA MOBILE (URA MOBILE)</option>
    <option value="sick_for_30_days" {{ request('type') === 'sick_for_30_days' ? 'selected' : '' }}>SICK LEAVE 30+ DAYS (UGONJWA SIKU 30)</option>
    <option value="condolences" {{ request('type') === 'condolences' ? 'selected' : '' }}>CONDOLENCES (RAMBIRAMBI)</option>
    <option value="injured_at_work" {{ request('type') === 'injured_at_work' ? 'selected' : '' }}>WORK INJURY (KUUMIA KAZINI)</option>
    <option value="residential_disaster" {{ request('type') === 'residential_disaster' ? 'selected' : '' }}>RESIDENTIAL DISASTER (MAJANGA YA ASILI)</option>
    <option value="join_membership" {{ request('type') === 'join_membership' ? 'selected' : '' }}>JOIN MEMBERSHIP (KUJIUNGA UANACHAMA)</option>
</select>
                            <small class="form-text text-muted">Filter by specific enquiry type to see detailed child table fields in exports</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <a href="{{ route('payment.accountant.dashboard') }}" class="btn btn-outline-warning">
                    <i class="fas fa-refresh me-1"></i>Reset All Filters
                </a>
                <button type="button" class="btn btn-primary" style="background: #17479E;" onclick="applyFilters()">
                    <i class="fas fa-search me-1"></i>Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Quick date range selection
document.getElementById('quickDateRange').addEventListener('change', function() {
    const range = this.value;
    const fromDate = document.getElementById('dateFrom');
    const toDate = document.getElementById('dateTo');

    const today = new Date();
    const formatDate = (date) => date.toISOString().split('T')[0];

    switch(range) {
        case 'today':
            fromDate.value = formatDate(today);
            toDate.value = formatDate(today);
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            fromDate.value = formatDate(yesterday);
            toDate.value = formatDate(yesterday);
            break;
        case 'this_week':
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            fromDate.value = formatDate(startOfWeek);
            toDate.value = formatDate(today);
            break;
        case 'last_week':
            const lastWeekStart = new Date(today);
            lastWeekStart.setDate(today.getDate() - today.getDay() - 7);
            const lastWeekEnd = new Date(today);
            lastWeekEnd.setDate(today.getDate() - today.getDay() - 1);
            fromDate.value = formatDate(lastWeekStart);
            toDate.value = formatDate(lastWeekEnd);
            break;
        case 'this_month':
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            fromDate.value = formatDate(startOfMonth);
            toDate.value = formatDate(today);
            break;
        case 'last_month':
            const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
            fromDate.value = formatDate(lastMonthStart);
            toDate.value = formatDate(lastMonthEnd);
            break;
    }
});

function applyFilters() {
    document.getElementById('filterForm').submit();
}

function exportToPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.location.href = `{{ route('payment.accountant.dashboard') }}?${params.toString()}`;
}
</script>

@endsection