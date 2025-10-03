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
                <a href="{{ route('enquiries.index') }}" class="text-decoration-none" style="color: #17479E;">
                    <i class="fas fa-file-alt me-1"></i>Enquiries
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                <i class="fas fa-building me-1"></i>Branch Manager Dashboard
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-2">
                 
            </h2>
             
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                <i class="fas fa-user-tie me-1"></i>{{ $branch->name }} Branch Manager
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        @php
            $col_class = 'col-xl col-lg-4 col-md-6';
        @endphp

        <div class="{{ $col_class }}">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #007bff 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-clipboard-list fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Total Enquiries</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['total']) }}</h4>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-75">
                            <i class="fas fa-file-alt me-1"></i> All Applications
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $col_class }}">
<div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%); overflow: hidden;">                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-clock fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-90 small fw-semibold text-uppercase">Pending Review</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['pending']) }}</h4>
                        <p class="mb-0 small text-white mt-1">{{ $analytics['total'] > 0 ? round(($analytics['pending'] / $analytics['total']) * 100, 1) : 0 }}% of total</p>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-90 fw-bold text-white">
                            <i class="fas fa-exclamation-circle me-1"></i> Action Needed
                        </small>
                        <div class="progress flex-grow-1 ms-3" style="height: 5px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: {{ $analytics['total'] > 0 ? ($analytics['pending'] / $analytics['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $col_class }}">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #17479E 0%, #007bff 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-user-check fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Assigned</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['assigned']) }}</h4>
                        <p class="mb-0 small opacity-75 mt-1 text-white">{{ $analytics['total'] > 0 ? round(($analytics['assigned'] / $analytics['total']) * 100, 1) : 0 }}% assigned</p>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-75">
                            <i class="fas fa-percent me-1"></i> {{ $analytics['total'] > 0 ? round(($analytics['assigned'] / $analytics['total']) * 100, 1) : 0 }}% Progress
                        </small>
                        <div class="badge bg-light text-info px-2 py-1 small">IN PROGRESS</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $col_class }}">
<div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); overflow: hidden;">                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-check-circle fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Approved</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['approved']) }}</h4>
                        <p class="mb-0 small opacity-75 mt-1 text-white">{{ $analytics['total'] > 0 ? round(($analytics['approved'] / $analytics['total']) * 100, 1) : 0 }}% success rate</p>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-75">
                            <i class="fas fa-thumbs-up me-1"></i> Completion Rate
                        </small>
                        <div class="badge bg-light text-success px-2 py-1 small">COMPLETED</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $col_class }}">
<div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b77 100%); overflow: hidden;">                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-times-circle fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Rejected</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['rejected']) }}</h4>
                        <p class="mb-0 small opacity-75 mt-1 text-white">{{ $analytics['total'] > 0 ? round(($analytics['rejected'] / $analytics['total']) * 100, 1) : 0 }}% rejected</p>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-75">
                            <i class="fas fa-ban me-1"></i> Rejection Rate
                        </small>
                        <div class="progress flex-grow-1 ms-3" style="height: 5px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: {{ $analytics['total'] > 0 ? ($analytics['rejected'] / $analytics['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $col_class }}">
<div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b77 100%); overflow: hidden;">                <div class="card-body text-white p-3 position-relative">
                    <i class="fas fa-exclamation-triangle fa-3x position-absolute top-0 end-0 me-3 mt-3 opacity-25"></i>
                    <div class="d-flex flex-column">
                        <p class="mb-1 opacity-75 small fw-semibold text-uppercase">Overdue</p>
                        <h4 class="fw-bold mb-0 display-6 text-white">{{ number_format($analytics['pending_overdue']) }}</h4>
                        <p class="mb-0 small opacity-75 mt-1 text-white">Needs urgent attention</p>
                    </div>

                    <hr class="my-3 border-light opacity-50">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="opacity-75">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $analytics['total'] > 0 ? round(($analytics['pending_overdue'] / $analytics['total']) * 100, 1) : 0 }}% Overdue
                        </small>
                        <div class="progress flex-grow-1 ms-3" style="height: 5px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: {{ $analytics['total'] > 0 ? ($analytics['pending_overdue'] / $analytics['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtered Analytics (Show when filters applied) -->
    @if(request()->hasAny(['region_id', 'district_id', 'type', 'status', 'date_from', 'date_to', 'search']))
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <h5 class="alert-heading mb-3"><i class="fas fa-filter me-2"></i>Filtered Results</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0 fw-bold">{{ number_format($filteredAnalytics['total']) }}</h4>
                        <small class="text-muted">Total Filtered</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0 fw-bold">{{ number_format($filteredAnalytics['pending']) }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0 fw-bold">{{ number_format($filteredAnalytics['assigned']) }}</h4>
                        <small class="text-muted">Assigned</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-dark text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0 fw-bold">{{ number_format($filteredAnalytics['approved']) }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Manager Actions Bar -->
    <div class="card border-0 shadow-sm mb-4" style="background-color: #f5f5f5;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tools fa-lg me-2" style="color: #17479E;"></i>
                    <span class="fw-bold text-dark">Quick Actions</span>
                    @if(request()->hasAny(['region_id', 'district_id', 'type', 'status', 'date_from', 'date_to', 'search']))
                        <span class="badge bg-success text-white ms-2">
                            <i class="fas fa-filter me-1"></i>Filters Active
                        </span>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-wrap">
    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fas fa-sliders-h me-1 text-white"></i><span class="d-none d-md-inline">
    </button>

    <div class="dropdown">
        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-file-export me-1"></i> Reports
        </button>
        <ul class="dropdown-menu">
            <li>
                <button type="button" class="dropdown-item" onclick="window.location.href='{{ route('branches.manager.dashboard', ['export' => 'excel_general']) }}'">
                    <i class="fas fa-file-excel me-1"></i> General Report
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item" onclick="exportCustomExcel()">
                    <i class="fas fa-file-excel me-1"></i> Custom Report (Excel)
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item" onclick="exportCustomPDF()">
                    <i class="fas fa-file-pdf me-1"></i> Summary Report
                </button>
            </li>
        </ul>
    </div>

    @if(request()->hasAny(['region_id', 'district_id', 'type', 'status', 'date_from', 'date_to', 'search']))
        <a href="{{ route('branches.manager.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-refresh me-1"></i><span class="d-none d-md-inline">Reset</span>
        </a>
    @endif
</div>
            </div>
        </div>
    </div>

    <!-- Regional, District & Child Table Analytics Buttons -->
    <div class="row g-3 mb-4">
        @if($analyticsByRegion->count() > 0)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                  
                    <h5 class="fw-bold mb-2 text-primary">Regions</h5>
                    {{-- - <p class="text-muted mb-3 text-primary">View detailed statistics for {{ $analyticsByRegion->count() }} regions</p>--}}
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#regionalAnalyticsModal">
                        <i class="fas fa-eye me-2"></i>View 
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($analyticsByDistrict->count() > 0)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                  
                    <h5 class="fw-bold mb-2 text-primary">Districts</h5>
                   {{--   <p class="text-muted mb-3 text-primary">View detailed statistics for {{ $analyticsByDistrict->count() }} districts</p>--}}
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#districtAnalyticsModal">
                        <i class="fas fa-eye me-2"></i>View 
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                   
                    <h5 class="fw-bold mb-2 text-primary">Categories</h5>
                    {{-- <p class="text-muted mb-3 text-primary">View detailed type for all Enquiries</p>--}}
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#childTableStatsModal">
                        <i class="fas fa-eye me-2"></i>View 
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Enquiries Table -->
    <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 modern-enquiry-table">
                    <thead style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                        <tr class="text-white">
                            <th width="60" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-hashtag me-1"></i>
                                    <span class="fw-bold">SN</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar me-2"></i>
                                    <span class="fw-bold">Date</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-receipt me-2"></i>
                                    <span class="fw-bold">CheckNo</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span class="fw-bold">Member</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <span class="fw-bold">Type</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marked-alt me-2"></i>
                                    <span class="fw-bold">Region</span>
                                </div>
                            </th>
                  
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie me-2"></i>
                                    <span class="fw-bold">Registered By</span>
                                </div>
                            </th>
                            <th class="border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span class="fw-bold">Status</span>
                                </div>
                            </th>
                            <th width="120" class="text-center border-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tools me-2"></i>
                                    <span class="fw-bold">Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                        <tr>
                            <td class="text-center align-middle">
                                <div class="badge badge-counter" style="background: gray; color: white; font-weight: bold; padding: 0.5rem 0.75rem; border-radius: 8px;">
                                    {{ $loop->iteration + (($enquiries->currentPage() - 1) * $enquiries->perPage()) }} 
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-primary">{{ $enquiry->created_at->format('d/m/Y') }}</span>
                                    <small class="text-muted">{{ $enquiry->created_at->format('H:i') }} HRS</small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-secondary text-white" style="">
                                    {{ $enquiry->check_number }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <strong class="text-muted text-uppercase text-primary">{{ ucwords($enquiry->full_name) }}</strong>
                                    <small class="text-muted text-primary">
                                        <i class="fas fa-id-badge me-1"></i>{{ $enquiry->phone }}
                                    </small>
                                </div>
                            </td>
                            <td class="align-middle">
                                <strong class="text-muted text-uppercase text-primary">
                                    {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}
                                </strong>
                            </td>
                    
                               <td class="align-middle">
                                <div class="d-flex flex-column">
                                    <strong class="text-muted text-uppercase text-primary">{{ $enquiry->region->name ?? 'N/A' }}</strong>
                                    <small class="text-muted">
                                        <i class="fas fa-id-badge me-1 text-primary"></i>{{ $enquiry->district->name ?? 'N/A' }}
                                    </small>
                                </div>
                            </td> 

                          
                            <td class="align-middle">
                                @if($enquiry->registeredBy)
                                    <a href="{{ route('users.show', $enquiry->registeredBy->id) }}" class="text-decoration-none d-flex align-items-center" style="color: #17479E; font-weight: 600; transition: all 0.2s;">
                                        <i class="fas fa-user-circle me-2" style="font-size: 1.2rem;"></i>
                                        <span class="text-muted text-uppercase text-primary">{{ $enquiry->registeredBy->name }}</span>
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user-slash me-1"></i>N/A
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'bg-warning', 'icon' => 'fa-clock', 'text' => 'text-dark'],
                                        'assigned' => ['class' => 'bg-info', 'icon' => 'fa-user-check', 'text' => 'text-white'],
                                        'approved' => ['class' => 'bg-success', 'icon' => 'fa-check-circle', 'text' => 'text-white'],
                                        'rejected' => ['class' => 'bg-danger', 'icon' => 'fa-times-circle', 'text' => 'text-white'],
                                    ];
                                    $config = $statusConfig[$enquiry->status] ?? ['class' => 'bg-secondary', 'icon' => 'fa-question', 'text' => 'text-white'];
                                @endphp
                                <span class="badge {{ $config['class'] }} {{ $config['text'] }}" style="padding: 0.5rem 0.75rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; letter-spacing: 0.5px;">
                                    <i class="fas {{ $config['icon'] }} me-1"></i>{{ strtoupper($enquiry->status) }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('enquiries.show', $enquiry->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; transition: all 0.2s; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; padding: 0.5rem 1rem;">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 3rem 0;">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                                    <h5 class="text-muted fw-bold mb-2">No Enquiries Found</h5>
                                    <p class="text-muted">Try adjusting your filters or search criteria</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($enquiries->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $enquiries->firstItem() }} to {{ $enquiries->lastItem() }} of {{ number_format($enquiries->total()) }} results
                </div>
                <div>
                    {{ $enquiries->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-filter me-2"></i>Advanced Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('branches.manager.dashboard') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Region
                            </label>
                            <select class="form-select text-uppercase" name="region_id" id="regionSelect">
                                <option value="">All Regions</option>
                                @foreach($regionsInBranch as $region)
                                    <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                           
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                District
                            </label>
                            <select class="form-select text-uppercase" name="district_id" id="districtSelect">
                                <option value="">All Districts</option>
                                @foreach($districtsInBranch as $district)
                                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                           
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                               Enquiry Type
                            </label>
                            <select class="form-select form-select-md" name="type">
                                <option value="">ALL ENQUIRY TYPES</option>
                                <option value="loan_application" {{ request('type') === 'loan_application' ? 'selected' : '' }}>LOAN APPLICATION (KUOMBA MKOPO)</option>
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
                                <option value="benefit_from_disasters" {{ request('type') === 'benefit_from_disasters' ? 'selected' : '' }}>RESIDENTIAL DISASTER (MAJANGA YA ASILI)</option>
                                <option value="join_membership" {{ request('type') === 'join_membership' ? 'selected' : '' }}>JOIN MEMBERSHIP (KUJIUNGA UANACHAMA)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                               Status
                            </label>
                            <select class="form-select text-uppercase" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                               From Date
                            </label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                To Date
                            </label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                               Search Keywords
                            </label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name, check number, force number, phone...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="regionalAnalyticsModal" tabindex="-1" aria-labelledby="regionalAnalyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            
            {{-- Header: Tumia gradient class na icon inayoelezea zaidi --}}
            <div class="modal-header text-white bg-gradient-primary">
                <h5 class="modal-title fw-bold text-white" id="regionalAnalyticsModalLabel">
                    <i class="fas fa-chart-line me-2"></i>REGIONAL ANALYTICS
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Funga dirisha"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="table-responsive">
                    {{-- Hapa ndio tumebadilisha: table-hover, table-striped, na table-bordered --}}
                    <table class="table table-hover table-striped table-bordered align-middle mb-0" id="regionalTable">
                        <thead class="table-primary"> {{-- Kichwa cha jedwali kimetumika rangi nyeusi kwa utofauti zaidi --}}
                            <tr>
                                <th>SN</th>
                                <th>REGION</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center">PENDING</th>
                                <th class="text-center">ASSIGNED</th>
                                <th class="text-center">APPROVED</th>
                                <th class="text-center">REJECTED</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analyticsByRegion as $regionId => $statuses)
                                @php
                                    // LOGIC YAKO YA ASILI IMEBAKI HAPA
                                    $region = $regionsInBranch->firstWhere('id', $regionId);
                                    $total = $statuses->sum('count');
                                    $pending = $statuses->where('status', 'pending')->sum('count');
                                    $assigned = $statuses->where('status', 'assigned')->sum('count');
                                    $approved = $statuses->where('status', 'approved')->sum('count');
                                    $rejected = $statuses->where('status', 'rejected')->sum('count');
                                @endphp
                                
                                @if($region)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-nowrap text-uppercase">{{ $region->name }}</td>
                                        {{-- Tumia rounded-pill kwa muonekano wa kisasa --}}
                                        <td class="text-center"><span class="badge rounded-pill bg-primary">{{ $total }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-warning text-dark">{{ $pending }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-info text-white">{{ $assigned }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-success">{{ $approved }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-danger">{{ $rejected }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('branches.manager.region.analytics', $region->id) }}" class="btn btn-sm btn-outline-secondary" title="Angalia Maelezo">
                                                <i class="fas fa-external-link-alt"></i> Tazama
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            
                            {{-- Ongeza row ikiwa hakuna data --}}
                            @if(count($analyticsByRegion) == 0)
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Hakuna data ya uchambuzi wa mikoa iliyopatikana.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS Fupi kwa ajili ya gradient (inapendekezwa kuiweka kwenye faili lako la CSS) */
.bg-gradient-primary {
    background: linear-gradient(135deg, #17479E 0%, #4facfe 100%) !important;
}
</style>

<div class="modal fade" id="districtAnalyticsModal" tabindex="-1" aria-labelledby="districtAnalyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            
            {{-- Modal Header: Use a custom utility class for the gradient and improve the title --}}
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title fw-bold" id="districtAnalyticsModalLabel">
                    <i class="fas fa-chart-area me-2 text-white"></i>DISTRICTS ANALYTICS
                </h5>
                {{-- Ensure the close button is accessible and visible --}}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">

                <div class="table-responsive">
                    {{-- Added table-striped, table-bordered, and align-middle for clarity and structure --}}
                    <table class="table table-hover table-striped table-bordered align-middle mb-0" id="districtTable">
                        <thead class="table-primary "> {{-- Using table-dark for high contrast header --}}
                            <tr>
                                <th>#</th>
                                <th>NAME</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center">PENDING</th>
                                <th class="text-center">ASSIGNED</th>
                                <th class="text-center">APPROVED</th>
                                <th class="text-center">REJECTED</th>
                                <th class="text-center">DETAILS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analyticsByDistrict as $districtId => $statuses)
                                @php
                                    // Your existing data logic remains here
                                    $district = $districtsInBranch->firstWhere('id', $districtId);
                                    $total = $statuses->sum('count');
                                    $pending = $statuses->where('status', 'pending')->sum('count');
                                    $assigned = $statuses->where('status', 'assigned')->sum('count');
                                    $approved = $statuses->where('status', 'approved')->sum('count');
                                    $rejected = $statuses->where('status', 'rejected')->sum('count');
                                @endphp
                                
                                @if($district)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-nowrap text-uppercase">{{ $district->name }}</td>
                                        {{-- Updated badges to rounded-pill for modern look --}}
                                        <td class="text-center"><span class="badge rounded-pill bg-primary">{{ $total }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-warning text-dark">{{ $pending }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-info text-white">{{ $assigned }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-success">{{ $approved }}</span></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-danger">{{ $rejected }}</span></td>
                                        <td class="text-center">
                                            {{-- Simplified action button for cleaner cell presentation --}}
                                            <a href="{{ route('branches.manager.district.analytics', $district->id) }}" class="btn btn-sm btn-outline-secondary" title="View detailed analytics for {{ $district->name }}">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            
                            {{-- Fallback message for empty data --}}
                            @if(count($analyticsByDistrict) == 0)
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No district analytics data available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom CSS for the modal header gradient (Recommended to move this to your main CSS file) */
.bg-gradient-secondary {
    background: linear-gradient(135deg, #0d6efd 0%, #3dd5f3 100%) !important;
}
</style>
</div>

<div class="modal fade" id="childTableStatsModal" tabindex="-1" aria-labelledby="childTableStatsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            
            {{-- Modal Header: Use a custom utility class for the gradient --}}
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title fw-bold text-white" id="childTableStatsModalLabel">
                    ENQUIRY SUMMARY
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">

                <div class="table-responsive">
                    {{-- Table with table-hover, table-striped, table-bordered, and align-middle --}}
                    <table class="table table-hover table-striped table-bordered align-middle mb-0" id="childTableStatsTable">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 30%;">ENQUIRY</th>
                                <th class="text-center">RECORDS</th>
                                <th class="text-end">REQUESTED AMOUNT (GRAND)</th>
                      
                            </tr>
                        </thead>
                        <tbody>
                            
                            {{-- Loan Applications --}}
                            <tr>
                                <td><i class="fas fa-money-bill-wave me-2 text-primary"></i><strong>LOAN APPLICATIONS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['loan_applications']['total'] ?? 0) }}</td>
                                <td class="text-end fw-bold text-primary">TZS {{ number_format($childTableStats['loan_applications']['total_amount'] ?? 0) }}</td>
                                 
                            </tr>
                            
                            {{-- Payments --}}
                            <tr>
                                <td><i class="fas fa-money-check me-2 text-success"></i><strong>PAYMENTS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['payments']['total'] ?? 0) }}</td>
                                <td class="text-end fw-bold text-success">TZS {{ number_format($childTableStats['payments']['total_amount'] ?? 0) }}</td>
                            
                            </tr>
                            
                            {{-- Refunds --}}
                            <tr>
                                <td><i class="fas fa-undo me-2 text-warning"></i><strong>REFUNDS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['refunds']['total'] ?? 0) }}</td>
                                <td class="text-end fw-bold text-warning">TZS {{ number_format($childTableStats['refunds']['total_amount'] ?? 0) }}</td>
                                
                            </tr>
                            
                            {{-- Withdrawals --}}
                            <tr>
                                <td><i class="fas fa-hand-holding-usd me-2 text-danger"></i><strong>WITHDRAWALS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['withdrawals']['total'] ?? 0) }}</td>
                                <td class="text-end fw-bold text-danger">TZS {{ number_format($childTableStats['withdrawals']['total_amount'] ?? 0) }}</td>
                               
                            </tr>
                            
                            {{-- Shares --}}
                            <tr>
                                <td><i class="fas fa-chart-line me-2 text-info"></i><strong>SHARES</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['shares']['total'] ?? 0) }}</td>
                                <td class="text-end fw-bold text-info">TZS {{ number_format($childTableStats['shares']['total_amount'] ?? 0) }}</td>
                              
                            </tr>
                            
                            {{-- Membership Changes --}}
                            <tr>
                                <td><i class="fas fa-users me-2 text-primary"></i><strong>MEMBERSHIP CHANGES</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['membership_changes']['total'] ?? 0) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <small class="text-success me-3"><i class="fas fa-plus-circle"></i> Joins: {{ $childTableStats['membership_changes']['joins'] ?? 0 }}</small>
                                        <small class="text-danger"><i class="fas fa-minus-circle"></i> Unjoins: {{ $childTableStats['membership_changes']['unjoins'] ?? 0 }}</small>
                                    </div>
                                </td>
                          
                            </tr>
                            
                            {{-- Retirements (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-user-clock me-2 text-secondary"></i><strong>RETIREMENTS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['retirements']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                              
                            </tr>
                            
                            {{-- Condolences (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-heart me-2 text-dark"></i><strong>CONDOLENCES</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['condolences']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                                
                            </tr>
                            
                            {{-- Injuries (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-ambulance me-2 text-danger"></i><strong>INJURIES</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['injuries']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                               
                            </tr>
                            
                            {{-- Sick Leaves (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-bed me-2 text-warning"></i><strong>SICK LEAVES</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['sick_leaves']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                              
                            </tr>
                            
                            {{-- Benefits (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-gift me-2 text-success"></i><strong>BENEFITS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['benefits']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                              
                            </tr>

                            {{-- Deductions (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-minus-circle me-2 text-info"></i><strong>DEDUCTIONS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['deductions']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                                
                            </tr>

                            {{-- URA Mobile (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-mobile-alt me-2 text-primary"></i><strong>URA MOBILE</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['ura_mobile']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                                
                            </tr>
                            
                            {{-- Residential Disasters (No Amount) --}}
                            <tr>
                                <td><i class="fas fa-home me-2 text-danger"></i><strong>RESIDENTIAL DISASTERS</strong></td>
                                <td class="text-center fw-bold">{{ number_format($childTableStats['residential_disasters']['total'] ?? 0) }}</td>
                                <td class="text-end text-muted">N/A</td>
                            
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Optional: Add a subtle footer --}}
            <div class="modal-footer d-flex justify-content-end py-2 border-top">
                <small class="text-muted">Statistics reflect overall data volumes.</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom CSS for the modal header gradient (Recommended to move this to your main CSS file) */
.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important;
}
</style>
<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-download me-2"></i>Export Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Export will include current filtered data
                    @if(request()->hasAny(['region_id', 'district_id', 'type', 'status', 'date_from', 'date_to', 'search']))
                        <span class="badge bg-primary ms-2">Filters Applied</span>
                    @endif
                </p>
                <div class="d-grid gap-3">
                    <a href="{{ route('branches.manager.export.excel', request()->query()) }}" class="btn btn-success btn-lg" onclick="showExportProgress()">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </a>
                    <a href="{{ route('branches.manager.export.pdf', request()->query()) }}" class="btn btn-danger btn-lg" onclick="showExportProgress()">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Progress Overlay -->
<div id="exportProgress" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 40px; text-align: center; min-width: 400px;">
        <div style="width: 80px; height: 80px; border: 8px solid #f3f3f3; border-top: 8px solid #17479E; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
        <h5 class="mb-2">Generating Export...</h5>
        <p class="text-muted mb-0">Please wait while we prepare your data</p>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Modern Enquiry Table Styling */
.modern-enquiry-table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-enquiry-table thead th {
    border: none !important;
    padding: 1rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    color: white !important;
}

.modern-enquiry-table tbody td {
    border: none !important;
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    padding: 1.25rem 1rem !important;
    vertical-align: middle;
}

.modern-enquiry-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(135, 206, 235, 0.03) 100%);
    transform: scale(1.001);
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.08);
}

.modern-enquiry-table .btn-sm {
    border-radius: 8px !important;
    font-weight: 600;
    transition: all 0.2s ease;
    text-transform: uppercase;
    font-size: 11px !important;
    letter-spacing: 0.5px;
}

.modern-enquiry-table .btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
}

.modern-enquiry-table .badge {
    transition: all 0.2s ease;
}

.modern-enquiry-table .badge:hover {
    transform: scale(1.05);
}
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>

// Dynamic District Loading
document.getElementById('regionSelect').addEventListener('change', function() {
    const regionId = this.value;
    const districtSelect = document.getElementById('districtSelect');

    if (!regionId) {
        districtSelect.innerHTML = '<option value="">All Districts</option>';
        districtSelect.disabled = false;
        return;
    }

    districtSelect.innerHTML = '<option value="">Loading...</option>';
    districtSelect.disabled = true;

    fetch(`{{ route('branches.manager.districts.by.region') }}?region_id=${regionId}`)
        .then(response => response.json())
        .then(districts => {
            districtSelect.innerHTML = '<option value="">All Districts</option>';
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            districtSelect.innerHTML = '<option value="">Error loading districts</option>';
        });
});

// Export Progress
function showExportProgress() {
    document.getElementById('exportProgress').style.display = 'flex';
    setTimeout(() => {
        document.getElementById('exportProgress').style.display = 'none';
    }, 3000);
}

// Initialize DataTables when modals are shown
$('#regionalAnalyticsModal').on('shown.bs.modal', function () {
    if (!$.fn.DataTable.isDataTable('#regionalTable')) {
        $('#regionalTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[2, 'desc']], // Sort by Total column
            language: {
                search: "Search regions:",
                lengthMenu: "Show _MENU_ regions",
                info: "Showing _START_ to _END_ of _TOTAL_ regions",
                infoEmpty: "No regions found",
                zeroRecords: "No matching regions found"
            }
        });
    }
});

$('#districtAnalyticsModal').on('shown.bs.modal', function () {
    if (!$.fn.DataTable.isDataTable('#districtTable')) {
        $('#districtTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[2, 'desc']], // Sort by Total column
            language: {
                search: "Search districts:",
                lengthMenu: "Show _MENU_ districts",
                info: "Showing _START_ to _END_ of _TOTAL_ districts",
                infoEmpty: "No districts found",
                zeroRecords: "No matching districts found"
            }
        });
    }
});

// Custom Excel Export
function exportCustomExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("branches.manager.dashboard") }}?' + params.toString();
}

// Custom PDF Export
function exportCustomPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.location.href = '{{ route("branches.manager.dashboard") }}?' + params.toString();
}
</script>

@endsection