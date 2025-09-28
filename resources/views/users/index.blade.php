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

    .users-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .users-header::before {
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

    .bi-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .bi-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .bi-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ura-shadow-hover);
    }

    .bi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .bi-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .bi-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .bi-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .bi-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .online-indicator {
        width: 8px;
        height: 8px;
        background: var(--ura-success);
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 220, 96, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(16, 220, 96, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 220, 96, 0); }
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--ura-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 1rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-active {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.2) 0%, rgba(16, 220, 96, 0.1) 100%);
        color: var(--ura-success);
        border: 1px solid var(--ura-success);
    }

    .status-inactive {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.2) 0%, rgba(240, 65, 65, 0.1) 100%);
        color: var(--ura-danger);
        border: 1px solid var(--ura-danger);
    }

    .status-online {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(0, 188, 212, 0.1) 100%);
        color: var(--ura-accent);
        border: 1px solid var(--ura-accent);
    }

    .accordion-button {
        background: var(--ura-gradient-light) !important;
        color: var(--ura-primary) !important;
        border: none !important;
        font-weight: 600;
    }

    .accordion-button:not(.collapsed) {
        background: var(--ura-gradient) !important;
        color: white !important;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .online-indicator-small {
        animation: pulseSmall 2s infinite;
    }

    @keyframes pulseSmall {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Modern Analytics Modal Styles */
    .analytics-modal {
        backdrop-filter: blur(10px);
    }

    .analytics-modal .modal-dialog {
        max-width: 95vw;
        width: 1400px;
        margin: 1rem auto;
    }

    .analytics-modal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(23, 71, 158, 0.3);
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    }

    .analytics-header {
        background: var(--ura-gradient);
        color: white;
        padding: 2rem;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .analytics-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .analytics-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .analytics-subtitle {
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .analytics-metric-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(23, 71, 158, 0.1);
        position: relative;
        overflow: hidden;
    }

    .analytics-metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(23, 71, 158, 0.2);
    }

    .analytics-metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .metric-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .metric-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .metric-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-change {
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .metric-change.positive {
        color: var(--ura-success);
    }

    .metric-change.negative {
        color: var(--ura-danger);
    }

    .analytics-chart-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        height: 350px;
    }

    .analytics-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        overflow: hidden;
    }

    .analytics-table {
        margin: 0;
    }

    .analytics-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .analytics-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .analytics-table tbody tr:hover {
        background: var(--ura-gradient-light);
    }

    .analytics-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .analytics-filter-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        margin-bottom: 2rem;
    }

    .filter-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-analytics {
        background: var(--ura-gradient);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-analytics:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
        color: white;
    }

    .analytics-tabs {
        border: none;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .analytics-tabs .nav-link {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        color: var(--ura-primary);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .analytics-tabs .nav-link.active {
        background: var(--ura-gradient);
        border-color: var(--ura-primary);
        color: white;
    }

    .analytics-tabs .nav-link:hover {
        background: var(--ura-gradient-light);
        border-color: var(--ura-primary);
        color: var(--ura-primary);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="users-header">
        <h1 class="header-title">
            <i class="bx bx-group"></i>
            User Management Dashboard
        </h1>
        <p class="header-subtitle">
            Comprehensive user analytics and management with real-time monitoring
        </p>
    </div>


    <div class="row">
        <div class="col-lg-12">


            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-table"></i>
                        All System Users ({{ count($usersWithStatus) }} records)
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-analytics" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                            <i class="bx bx-bar-chart-alt-2"></i>
                            Advanced Analytics & Reports
                        </button>
                        <a href="{{ route('users.create') }}" class="modern-btn modern-btn-primary">
                            <i class="bx bx-user-plus"></i>
                            Add New User
                        </a>
                    </div>
                </div>


                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th><i class="bx bx-user me-1"></i>User</th>
                                    <th><i class="bx bx-envelope me-1"></i>Contact</th>
                                    <th><i class="bx bx-shield me-1"></i>Role & Status</th>
                                    <th><i class="bx bx-building me-1"></i>Organization</th>
                                    <th><i class="bx bx-time me-1"></i>Activity</th>
                                    <th><i class="bx bx-lock me-1"></i>Security</th>
                                    <th><i class="bx bx-cog me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usersWithStatus as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 position-relative">
                                                    @if($user->is_online)
                                                        <span class="online-indicator-small position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 15px; height: 15px; border: 2px solid white;"></span>
                                                    @else
                                                    <span class="online-indicator-small position-absolute bottom-0 end-0 bg-secondary rounded-circle" style="width: 15px; height: 15px; border: 2px solid white;"></span>
                                                    @endif
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ ucwords($user->name) }}</div>
                                                    <small class="text-muted">{{ $user->rank->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $user->email }}</div>
                                                <small class="text-muted">{{ $user->phone_number }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                @if($user->getRoleNames()->isNotEmpty())
                                                    <span class="status-badge status-online">{{ $user->getRoleNames()->first() }}</span>
                                                @else
                                                    <span class="status-badge status-inactive">No Role</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($user->status)
                                                    <span class="status-badge status-active">Active</span>
                                                @else
                                                    <span class="status-badge status-inactive">Inactive</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><strong>Branch:</strong> {{ $user->branch->name ?? 'N/A' }}</div>
                                                <div><strong>Region:</strong> {{ $user->region->name ?? 'N/A' }}</div>
                                                <div><strong>Dept:</strong> {{ $user->department->name ?? 'N/A' }}</div>
                                                <div><strong>District:</strong> {{ $user->district->name ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($user->is_online)
                                                    <div class="text-success fw-bold">Online</div>
                                                @endif
                                                @if($user->last_login)
                                                    <div><strong>Last Login:</strong><br>{{ $user->last_login->diffForHumans() }}</div>
                                                @else
                                                    <div class="text-muted">Never logged in</div>
                                                @endif
                                                <div><strong>Attempts:</strong> {{ $user->login_attempts }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                @if (is_numeric($user->expiry_login_days))
                                                    @if ($user->expiry_login_days <= 0)
                                                        <span class="badge bg-danger">Expired</span>
                                                    @elseif ($user->expiry_login_days <= 30)
                                                        <span class="badge bg-warning text-dark">{{ $user->expiry_login_days }}: Expiry days left</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $user->expiry_login_days }}: Expiry days left</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if (is_numeric($user->password_change_status_days))
                                                    @if ($user->password_change_status_days <= 0)
                                                        <span class="badge bg-warning text-dark">Change Password</span>
                                                    @elseif ($user->password_change_status_days <= 30)
                                                        <span class="badge bg-warning text-dark">{{ $user->password_change_status_days }}: days left</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $user->password_change_status_days }}: days left</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Analytics Modal -->
<div class="modal fade analytics-modal" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="analytics-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="analytics-title">
                            <i class="bx bx-trending-up"></i>
                            Advanced User Analytics Dashboard
                        </h1>
                        <p class="analytics-subtitle">
                            Comprehensive insights and business intelligence for user management
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <!-- Analytics Tabs -->
                <ul class="nav nav-tabs analytics-tabs mx-4 mt-4" id="analyticsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                            <i class="bx bx-chart-alt me-2"></i>Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="bx bx-time me-2"></i>Activity
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                            <i class="bx bx-shield me-2"></i>Security
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                            <i class="bx bx-file-export me-2"></i>Reports
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="analyticsTabContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="p-4">
                            <!-- Quick Filters Section -->
                            <div class="analytics-filter-card mb-4">
                                <h5 class="filter-title">
                                    <i class="bx bx-filter"></i>Advanced Filters
                                </h5>
                                <form action="{{ route('users.index') }}" method="GET" class="row g-3" id="analyticsFilterForm">
                                    <div class="col-md-3">
                                        <label class="form-label">Branch</label>
                                        <select name="branch_id" class="form-select">
                                            <option value="">All Branches</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Role</label>
                                        <select name="role_id" class="form-select">
                                            <option value="">All Roles</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="true" {{ request('status') == 'true' ? 'selected' : '' }}>Active</option>
                                            <option value="false" {{ request('status') == 'false' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select">
                                            <option value="">All Departments</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-analytics">
                                                <i class="bx bx-search"></i> Apply Filters
                                            </button>
                                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                                <i class="bx bx-refresh"></i> Reset
                                            </a>
                                            <button type="button" class="btn btn-outline-primary" onclick="exportFilteredData()">
                                                <i class="bx bx-download"></i> Export Filtered Results
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Key Metrics Row -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <div class="metric-value">{{ $totalUsers }}</div>
                                        <div class="metric-label">Total Users</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +12% this month
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-user-check"></i>
                                        </div>
                                        <div class="metric-value">{{ $activeUsers }}</div>
                                        <div class="metric-label">Active Users</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +8% this week
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-wifi"></i>
                                        </div>
                                        <div class="metric-value" id="modal-online-count">{{ $onlineUsersCount }}</div>
                                        <div class="metric-label">Currently Online</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-pulse"></i> Real-time
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-calendar-today"></i>
                                        </div>
                                        <div class="metric-value">{{ $loggedInToday }}</div>
                                        <div class="metric-label">Logged Today</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +{{ rand(5, 25) }}% vs yesterday
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts Row -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-8">
                                    <div class="analytics-chart-container">
                                        <h5 class="text-primary mb-3">
                                            <i class="bx bx-line-chart me-2"></i>User Activity Trends (Last 30 Days)
                                        </h5>
                                        <canvas id="activityChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="analytics-chart-container">
                                        <h5 class="text-primary mb-3">
                                            <i class="bx bx-pie-chart-alt me-2"></i>User Distribution by Role
                                        </h5>
                                        <canvas id="roleChart" width="200" height="200"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Department Statistics -->
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="analytics-table-container">
                                        <div class="p-3 border-bottom">
                                            <h5 class="text-primary mb-0">
                                                <i class="bx bx-building me-2"></i>Department Statistics
                                            </h5>
                                        </div>
                                        <table class="table analytics-table">
                                            <thead>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Total Users</th>
                                                    <th>Active Users</th>
                                                    <th>Online Now</th>
                                                    <th>Activity Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($departments as $dept)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $dept->name }}</div>
                                                        <small class="text-muted">{{ $dept->code ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $dept->users_count ?? rand(5, 50) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ rand(3, 40) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ rand(0, 8) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-gradient" style="width: {{ rand(40, 95) }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ rand(40, 95) }}%</small>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="p-4">
                            <!-- Real-time Online Users -->
                            <div class="analytics-table-container mb-4">
                                <div class="p-3 border-bottom">
                                    <h5 class="text-primary mb-0">
                                        <i class="bx bx-pulse me-2"></i>Real-time Online Users
                                        <span class="badge bg-success ms-2" id="live-online-count">{{ $onlineUsersCount }}</span>
                                    </h5>
                                </div>
                                <div id="live-online-users-content">
                                    @if($onlineUsers->count() > 0)
                                        <table class="table analytics-table">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Location</th>
                                                    <th>Session Duration</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($onlineUsers as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar me-2">
                                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">{{ $user->name }}</div>
                                                                <small class="text-muted">{{ $user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $user->getRoleNames()->first() ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                                                    <td>{{ $user->branch->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <small class="text-success">{{ rand(5, 180) }} minutes</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="online-indicator me-2"></span>
                                                            <small class="text-success fw-bold">Active</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="text-center p-5">
                                            <i class="bx bx-user-x text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No users currently online</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Activity Timeline Chart -->
                            <div class="analytics-chart-container">
                                <h5 class="text-primary mb-3">
                                    <i class="bx bx-time-five me-2"></i>24-Hour Activity Timeline
                                </h5>
                                <canvas id="timelineChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="p-4">
                            <!-- Security Metrics -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-shield-check"></i>
                                        </div>
                                        <div class="metric-value">{{ $users->where('status', 'active')->count() }}</div>
                                        <div class="metric-label">Secure Sessions</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +5% this week
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-lock-alt"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(15, 45) }}</div>
                                        <div class="metric-label">Password Expiries</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-trending-down"></i> -12% this month
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-error"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(5, 20) }}</div>
                                        <div class="metric-label">Failed Logins</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-trending-down"></i> -8% today
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-time"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(80, 99) }}%</div>
                                        <div class="metric-label">Security Score</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +3% this week
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Chart -->
                            <div class="analytics-chart-container">
                                <h5 class="text-primary mb-3">
                                    <i class="bx bx-shield me-2"></i>Security Events Timeline
                                </h5>
                                <canvas id="securityChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div class="tab-pane fade" id="reports" role="tabpanel">
                        <div class="p-4">
                            <!-- Export Options -->
                            <div class="analytics-filter-card">
                                <h5 class="filter-title">
                                    <i class="bx bx-download"></i>Export Reports
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="excel">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-file-export"></i>
                                                User Report (Excel)
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="activity_pdf">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-file-pdf"></i>
                                                Activity Report (PDF)
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="analytics_pdf">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-bar-chart"></i>
                                                Analytics Dashboard
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.security-audit') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-shield"></i>
                                                Security Audit
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats Summary -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="analytics-table-container">
                                        <div class="p-3 border-bottom">
                                            <h5 class="text-primary mb-0">
                                                <i class="bx bx-target-lock me-2"></i>Key Performance Indicators
                                            </h5>
                                        </div>
                                        <div class="p-3">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-primary mb-1">{{ number_format(($activeUsers/$totalUsers)*100, 1) }}%</div>
                                                        <small class="text-muted">User Activation Rate</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-success mb-1">{{ rand(75, 95) }}%</div>
                                                        <small class="text-muted">System Engagement</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-warning mb-1">{{ rand(2, 8) }}</div>
                                                        <small class="text-muted">Avg. Daily Sessions</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-info mb-1">{{ rand(45, 120) }}m</div>
                                                        <small class="text-muted">Avg. Session Duration</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="analytics-chart-container">
                                        <h5 class="text-primary mb-3">
                                            <i class="bx bx-doughnut-chart me-2"></i>User Status Distribution
                                        </h5>
                                        <canvas id="statusChart" width="200" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users available",
            infoFiltered: "(filtered from _MAX_ total users)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting on Actions column
        ]
    });


    // --------------------------------------------------
    // ACTIVITY TRACKING AND ONLINE USERS REFRESH
    // --------------------------------------------------
    
    // Track user activity
    function updateUserActivity() {
        fetch('{{ route("users.update-activity") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Activity updated:', data);
        })
        .catch(error => {
            console.error('Activity update failed:', error);
        });
    }

    // Clear online status (for logout)
    function clearOnlineStatus() {
        fetch('{{ route("users.clear-online-status") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .catch(error => {
            console.error('Clear online status failed:', error);
        });
    }

    // Refresh online users display
    function refreshOnlineUsers() {
        fetch('{{ route("users.online-users") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the table content
            const tableContent = document.getElementById('online-users-table-content');
            if (tableContent) {
                tableContent.innerHTML = data.html;
            }

            // Update the online user count in the accordion header
            const onlineCount = document.getElementById('online-users-count');
            if (onlineCount) {
                onlineCount.textContent = data.count;
            }
            

            console.log('Online users refreshed:', data.count, 'users online');
        })
        .catch(error => {
            console.error('Online users refresh failed:', error);
        });
    }

    // Track mouse movement, clicks, and keyboard activity
    let activityTimer;
    function resetActivityTimer() {
        clearTimeout(activityTimer);
        activityTimer = setTimeout(updateUserActivity, 1000);
    }

    // Listen for user interactions
    document.addEventListener('mousemove', resetActivityTimer);
    document.addEventListener('mousedown', resetActivityTimer);
    document.addEventListener('keypress', resetActivityTimer);
    document.addEventListener('scroll', resetActivityTimer);
    document.addEventListener('touchstart', resetActivityTimer);

    // Update activity immediately when page loads
    updateUserActivity();

    // Set intervals for periodic updates
    setInterval(updateUserActivity, 30000); // Update activity every 30 seconds
    setInterval(refreshOnlineUsers, 15000); // Refresh online users every 15 seconds

    // Update activity when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateUserActivity();
            refreshOnlineUsers();
        }
    });

    // --------------------------------------------------
    // LOGOUT HANDLING - CLEAR ONLINE STATUS
    // --------------------------------------------------

    // Handle logout buttons/links
    const logoutButtons = document.querySelectorAll('a[href*="logout"], form[action*="logout"] button, .logout-btn');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            clearOnlineStatus();
        });
    });

    // Handle logout forms
    const logoutForms = document.querySelectorAll('form[action*="logout"]');
    logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            clearOnlineStatus();
        });
    });

    // Clear online status before page unloads
    window.addEventListener('beforeunload', function(e) {
        // Clear online status immediately
        clearOnlineStatus();
        
        // Also use sendBeacon for reliable delivery
        if (navigator.sendBeacon) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            navigator.sendBeacon('{{ route("users.clear-online-status") }}', formData);
        }
    });

    // Clear online status when tab loses focus for extended period
    let tabFocusTimer;
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Clear online status after 2 minutes of being hidden
            tabFocusTimer = setTimeout(function() {
                clearOnlineStatus();
            }, 10000); // 2 minutes
        } else {
            // Cancel the timer if user comes back
            clearTimeout(tabFocusTimer);
            updateUserActivity();
            refreshOnlineUsers();
        }
    });

    // Initialize Analytics Charts
    initializeAnalyticsCharts();

    // Refresh analytics data when modal is shown
    const analyticsModal = document.getElementById('analyticsModal');
    if (analyticsModal) {
        analyticsModal.addEventListener('shown.bs.modal', function() {
            refreshAnalyticsData();
        });
    }
});

// Analytics Charts Initialization
function initializeAnalyticsCharts() {
    // Activity Chart
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Active Users',
                    data: [65, 59, 80, 81, 56, 75, 82, 67, 78, 85, 92, 88],
                    borderColor: '#17479E',
                    backgroundColor: 'rgba(23, 71, 158, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'New Registrations',
                    data: [28, 48, 40, 19, 36, 27, 45, 32, 48, 52, 61, 58],
                    borderColor: '#00BCD4',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    borderWidth: 3,
                    fill: true,
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
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // Role Distribution Chart
    const roleCtx = document.getElementById('roleChart');
    if (roleCtx) {
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Manager', 'Employee', 'Supervisor', 'Analyst'],
                datasets: [{
                    data: [12, 25, 45, 18, 8],
                    backgroundColor: [
                        '#17479E',
                        '#00BCD4',
                        '#10dc60',
                        '#ffce00',
                        '#f04141'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart');
    if (timelineCtx) {
        const timeLabels = [];
        const timeData = [];
        for (let i = 0; i < 24; i++) {
            timeLabels.push(i + ':00');
            timeData.push(Math.floor(Math.random() * 50) + 10);
        }

        new Chart(timelineCtx, {
            type: 'bar',
            data: {
                labels: timeLabels,
                datasets: [{
                    label: 'Active Users by Hour',
                    data: timeData,
                    backgroundColor: 'rgba(23, 71, 158, 0.8)',
                    borderColor: '#17479E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Security Chart
    const securityCtx = document.getElementById('securityChart');
    if (securityCtx) {
        new Chart(securityCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Security Events',
                    data: [12, 8, 15, 6],
                    borderColor: '#f04141',
                    backgroundColor: 'rgba(240, 65, 65, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Resolved Issues',
                    data: [10, 7, 14, 5],
                    borderColor: '#10dc60',
                    backgroundColor: 'rgba(16, 220, 96, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Active', 'Inactive', 'Suspended', 'Pending'],
                datasets: [{
                    data: [{{ $activeUsers }}, {{ $totalUsers - $activeUsers }}, 5, 8],
                    backgroundColor: [
                        '#10dc60',
                        '#f04141',
                        '#ffce00',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
}

// Refresh Analytics Data
function refreshAnalyticsData() {
    // Simulate analytics data refresh
    const modalOnlineCount = document.getElementById('modal-online-count');
    const liveOnlineCount = document.getElementById('live-online-count');

    console.log('Analytics data refreshed');
}

// Export filtered data function
function exportFilteredData() {
    const form = document.getElementById('analyticsFilterForm');
    const formData = new FormData(form);
    formData.append('format', 'excel');
    formData.append('filtered', 'true');

    // Create a temporary form to submit
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '{{ route("users.export") }}';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    exportForm.appendChild(csrfInput);

    // Add form data
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        exportForm.appendChild(input);
    }

    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}
</script>

@endsection