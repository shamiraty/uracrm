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
                <i class="fas fa-building me-2 text-primary" style=" "></i>Branch Management Dashboard
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
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
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
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #3dd5f3 100%); overflow: hidden;">
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
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
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
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
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
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #fd7e14 0%, #f39c12 100%); overflow: hidden;">
                <div class="card-body text-white p-3 position-relative">
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
                        <div class="bg-success text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
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
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-sliders-h me-1"></i>Advanced Filters
                    </button>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-file-excel me-1"></i>Export Data
                    </button>
                    @if(request()->hasAny(['region_id', 'district_id', 'type', 'status', 'date_from', 'date_to', 'search']))
                        <a href="{{ route('branches.manager.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-refresh me-1"></i>Reset Filters
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
                    <div class="mb-3">
                        <i class="fas fa-map-marked-alt fa-4x" style="color: #17479E;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Regional Enquiry Status</h5>
                    <p class="text-muted mb-3">View detailed statistics for {{ $analyticsByRegion->count() }} regions</p>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#regionalAnalyticsModal">
                        <i class="fas fa-chart-bar me-2"></i>View Regional Data
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($analyticsByDistrict->count() > 0)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-map-marker-alt fa-4x" style="color: #0d6efd;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">District Enquiry Status</h5>
                    <p class="text-muted mb-3">View detailed statistics for {{ $analyticsByDistrict->count() }} districts</p>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#districtAnalyticsModal">
                        <i class="fas fa-chart-line me-2"></i>View District Data
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-database fa-4x" style="color: #198754;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Enquiry Types</h5>
                    <p class="text-muted mb-3">View detailed type for all Enquiries</p>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#childTableStatsModal">
                        <i class="fas fa-table me-2"></i>View Types
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enquiries Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
            <h5 class="mb-0 text-white fw-bold"><i class="fas fa-table me-2"></i>Enquiries List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Check Ref</th>
                            <th class="px-4 py-3">Member</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Region</th>
                            <th class="px-4 py-3">District</th>
                            <th class="px-4 py-3">Registered By</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration + (($enquiries->currentPage() - 1) * $enquiries->perPage()) }}</td>
                            <td class="px-4 py-3">{{ $enquiry->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3"><span class="badge bg-primary">{{ $enquiry->check_number }}</span></td>
                            <td class="px-4 py-3">
                                <strong>{{ ucwords($enquiry->full_name) }}</strong><br>
                                <small class="text-muted">{{ $enquiry->force_no }}</small>
                            </td>
                            <td class="px-4 py-3"><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}</span></td>
                            <td class="px-4 py-3">{{ $enquiry->region->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $enquiry->district->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if($enquiry->registeredBy)
                                    <a href="{{ route('users.show', $enquiry->registeredBy->id) }}" class="text-decoration-none text-primary fw-semibold">
                                        <i class="fas fa-user me-1"></i>{{ $enquiry->registeredBy->name }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning text-dark',
                                        'assigned' => 'bg-info',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$enquiry->status] ?? 'bg-secondary' }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('enquiries.show', $enquiry->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No enquiries found</h5>
                                <p class="text-muted">Try adjusting your filters</p>
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
                                <i class="fas fa-map-marked-alt me-1 text-primary"></i>Region
                            </label>
                            <select class="form-select" name="region_id" id="regionSelect">
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
                                <i class="fas fa-map-marker-alt me-1 text-info"></i>District
                            </label>
                            <select class="form-select" name="district_id" id="districtSelect">
                                <option value="">All Districts</option>
                                @foreach($districtsInBranch as $district)
                                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                           
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tags me-1 text-success"></i>Enquiry Type
                            </label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                @foreach($enquiryTypes as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-flag me-1 text-warning"></i>Status
                            </label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>From Date
                            </label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>To Date
                            </label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-search me-1 text-info"></i>Search Keywords
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

<!-- Regional Analytics Modal -->
<div class="modal fade" id="regionalAnalyticsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-map-marked-alt me-2"></i>Regional Analytics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover" id="regionalTable">
                        <thead style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); color: white;">
                            <tr>
                                <th>#</th>
                                <th>Region Name</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">Assigned</th>
                                <th class="text-center">Approved</th>
                                <th class="text-center">Rejected</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analyticsByRegion as $regionId => $statuses)
                                @php
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
                                    <td><strong>{{ $region->name }}</strong></td>
                                    <td class="text-center"><span class="badge bg-primary">{{ $total }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $pending }}</span></td>
                                    <td class="text-center"><span class="badge bg-info">{{ $assigned }}</span></td>
                                    <td class="text-center"><span class="badge bg-success">{{ $approved }}</span></td>
                                    <td class="text-center"><span class="badge bg-danger">{{ $rejected }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('branches.manager.region.analytics', $region->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- District Analytics Modal -->
<div class="modal fade" id="districtAnalyticsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd 0%, #3dd5f3 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-map-marker-alt me-2"></i>District Analytics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover" id="districtTable">
                        <thead style="background: linear-gradient(135deg, #0d6efd 0%, #3dd5f3 100%); color: white;">
                            <tr>
                                <th>#</th>
                                <th>District Name</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">Assigned</th>
                                <th class="text-center">Approved</th>
                                <th class="text-center">Rejected</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analyticsByDistrict as $districtId => $statuses)
                                @php
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
                                    <td><strong>{{ $district->name }}</strong></td>
                                    <td class="text-center"><span class="badge bg-primary">{{ $total }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $pending }}</span></td>
                                    <td class="text-center"><span class="badge bg-info">{{ $assigned }}</span></td>
                                    <td class="text-center"><span class="badge bg-success">{{ $approved }}</span></td>
                                    <td class="text-center"><span class="badge bg-danger">{{ $rejected }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('branches.manager.district.analytics', $district->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Child Table Statistics Modal -->
<div class="modal fade" id="childTableStatsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-database me-2"></i>Child Table Statistics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <!-- Loan Applications -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <h6 class="text-primary small mb-2"><i class="fas fa-money-bill-wave me-1"></i>LOAN APPLICATIONS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['loan_applications']['total'] ?? 0) }}</h4>
                                <p class="mb-0 small text-success">TZS {{ number_format($childTableStats['loan_applications']['total_amount'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payments -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <h6 class="text-success small mb-2"><i class="fas fa-money-check me-1"></i>PAYMENTS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['payments']['total'] ?? 0) }}</h4>
                                <p class="mb-0 small text-success">TZS {{ number_format($childTableStats['payments']['total_amount'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Refunds -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <h6 class="text-warning small mb-2"><i class="fas fa-undo me-1"></i>REFUNDS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['refunds']['total'] ?? 0) }}</h4>
                                <p class="mb-0 small text-warning">TZS {{ number_format($childTableStats['refunds']['total_amount'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawals -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <h6 class="text-danger small mb-2"><i class="fas fa-hand-holding-usd me-1"></i>WITHDRAWALS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['withdrawals']['total'] ?? 0) }}</h4>
                                <p class="mb-0 small text-danger">TZS {{ number_format($childTableStats['withdrawals']['total_amount'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shares -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <h6 class="text-info small mb-2"><i class="fas fa-chart-line me-1"></i>SHARES</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['shares']['total'] ?? 0) }}</h4>
                                <p class="mb-0 small text-info">TZS {{ number_format($childTableStats['shares']['total_amount'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Retirements -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-secondary h-100">
                            <div class="card-body">
                                <h6 class="text-secondary small mb-2"><i class="fas fa-user-clock me-1"></i>RETIREMENTS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['retirements']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Condolences -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-dark h-100">
                            <div class="card-body">
                                <h6 class="text-dark small mb-2"><i class="fas fa-heart me-1"></i>CONDOLENCES</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['condolences']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Injuries -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <h6 class="text-danger small mb-2"><i class="fas fa-ambulance me-1"></i>INJURIES</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['injuries']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Sick Leaves -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <h6 class="text-warning small mb-2"><i class="fas fa-bed me-1"></i>SICK LEAVES</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['sick_leaves']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <h6 class="text-success small mb-2"><i class="fas fa-gift me-1"></i>BENEFITS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['benefits']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Changes -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <h6 class="text-primary small mb-2"><i class="fas fa-users me-1"></i>MEMBERSHIP CHANGES</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['membership_changes']['total'] ?? 0) }}</h4>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-success"><i class="fas fa-plus-circle"></i> {{ $childTableStats['membership_changes']['joins'] ?? 0 }}</small>
                                    <small class="text-danger"><i class="fas fa-minus-circle"></i> {{ $childTableStats['membership_changes']['unjoins'] ?? 0 }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <h6 class="text-info small mb-2"><i class="fas fa-minus-circle me-1"></i>DEDUCTIONS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['deductions']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- URA Mobile -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <h6 class="text-primary small mb-2"><i class="fas fa-mobile-alt me-1"></i>URA MOBILE</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['ura_mobile']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Residential Disasters -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <h6 class="text-danger small mb-2"><i class="fas fa-home me-1"></i>RESIDENTIAL DISASTERS</h6>
                                <h4 class="fw-bold mb-1">{{ number_format($childTableStats['residential_disasters']['total'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <span class="badge bg-success ms-2">Filters Applied</span>
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
</script>

@endsection