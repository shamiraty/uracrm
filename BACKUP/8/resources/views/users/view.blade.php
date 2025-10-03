@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-dark: #0D2A5A;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
    }

    * {
        box-sizing: border-box;
    }

    .user-view-container {
        padding: 20px 30px;
    }

    /* Profile Card */
    .profile-card {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .profile-header {
        background: var(--ura-gradient);
        padding: 40px 20px;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin: 0 auto 15px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .online-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        background: var(--ura-success);
        border: 3px solid white;
        border-radius: 50%;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
        margin: 0 0 5px 0;
    }

    .profile-designation {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 1rem;
        margin: 0 0 5px 0;
    }

    .profile-location {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .info-card-header {
        background: var(--ura-gradient-light);
        padding: 15px 20px;
        border-bottom: 2px solid var(--ura-primary);
    }

    .info-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ura-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-title i {
        font-size: 1.3rem;
    }

    .info-card-body {
        padding: 0;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(23, 71, 158, 0.05);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #5a6c7d;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .info-label i {
        color: var(--ura-primary);
        font-size: 1.1rem;
    }

    .info-value {
        font-weight: 500;
        color: #2c3e50;
        text-align: right;
        font-size: 0.95rem;
    }

    /* Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge.active {
        background: #10dc60;
        color: white;
    }

    .status-badge.inactive {
        background: #6c757d;
        color: white;
    }

    .role-badge {
        background: var(--ura-primary);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-block;
        margin: 2px;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid var(--ura-primary);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--ura-gradient-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .stat-label {
        color: #5a6c7d;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding: 20px 0 20px 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--ura-primary);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 25px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -33px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--ura-primary);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--ura-primary);
    }

    .timeline-content {
        background: var(--ura-gradient-light);
        padding: 12px 15px;
        border-radius: 8px;
        border-left: 3px solid var(--ura-primary);
    }

    .timeline-title {
        font-weight: 600;
        color: var(--ura-primary);
        margin-bottom: 4px;
        font-size: 0.95rem;
    }

    .timeline-time {
        color: #5a6c7d;
        font-size: 0.85rem;
    }

    /* Buttons */
    .action-btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .action-btn-primary {
        background: var(--ura-gradient);
        color: white;
    }

    .action-btn-primary:hover {
        opacity: 0.9;
        color: white;
    }

    .action-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .action-btn-secondary:hover {
        opacity: 0.9;
        color: white;
    }

    /* Chart Container */
    .chart-container {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
        padding: 25px;
        margin-bottom: 20px;
    }

    .chart-wrapper {
        max-width: 400px;
        margin: 0 auto;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: var(--ura-gradient);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid rgba(23, 71, 158, 0.1);
    }

    /* Table in Modal */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 12px 15px;
        font-size: 0.9rem;
    }

    .table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid rgba(23, 71, 158, 0.05);
        font-size: 0.9rem;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .user-view-container {
            padding: 15px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }

        .stat-card {
            margin-bottom: 15px;
        }

        .chart-wrapper {
            max-width: 100%;
        }
    }
</style>

<div class="container-fluid user-view-container">
    <!-- Back Button and PDF Download -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        @if(auth()->user()->hasRole(['superadmin', 'system_admin']))
        <a href="{{ route('users.index') }}" class="action-btn action-btn-secondary">
            <i class="bx bx-arrow-back"></i>
            Back to Users
        </a>
        @else
        <div></div>
        @endif
        <button type="button" class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#pdfFilterModal">
            <i class="bx bx-download"></i>
            Download PDF Report
        </button>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                        @if($user->status === 'active')
                        <span class="online-indicator"></span>
                        @endif
                    </div>
                    <h4 class="profile-name">{{ $user->name }}</h4>
                    <p class="profile-designation">{{ $user->designation }}</p>
                    <p class="profile-location">
                        <i class="bx bx-buildings"></i> {{ $user->branch->name ?? 'N/A' }}, {{ $user->region->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="info-card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-envelope"></i> Email
                            </span>
                            <span class="info-value">{{ $user->email }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-phone"></i> Phone
                            </span>
                            <span class="info-value">{{ $user->phone_number }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-medal"></i> Rank
                            </span>
                            <span class="info-value">{{ $user->rank->name ?? 'N/A' }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-check-circle"></i> Status
                            </span>
                            <span class="info-value">
                                <span class="status-badge {{ $user->status === 'active' ? 'active' : 'inactive' }}">
                                    <i class="bx bx-{{ $user->status === 'active' ? 'check' : 'x' }}"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-shield"></i> Role
                            </span>
                            <span class="info-value">
                                @if($user->getRoleNames()->isNotEmpty())
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="role-badge">{{ $role }}</span>
                                    @endforeach
                                @else
                                    <span style="color: #999;">No roles</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                        <div class="stat-value">{{ $user->created_at->format('M Y') }}</div>
                        <div class="stat-label">Member Since</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bx bx-error-circle"></i>
                        </div>
                        <div class="stat-value">{{ $user->login_attempts }}</div>
                        <div class="stat-label">Login Attempts</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Location Information -->
            <div class="info-card">
                <div class="info-card-header">
                    <h6 class="info-card-title">
                        <i class="bx bx-map"></i>
                        Location & Organization
                    </h6>
                </div>
                <div class="info-card-body">
                    <ul class="info-list">
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-building"></i> Branch
                            </span>
                            <span class="info-value">{{ $user->branch->name ?? 'N/A' }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-world"></i> Region
                            </span>
                            <span class="info-value">{{ $user->region->name ?? 'N/A' }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-sitemap"></i> Department
                            </span>
                            <span class="info-value">{{ $user->department->name ?? 'N/A' }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-map-pin"></i> District
                            </span>
                            <span class="info-value">{{ $user->district->name ?? 'N/A' }}</span>
                        </li>
                        <li class="info-item">
                            <span class="info-label">
                                <i class="bx bx-command"></i> Command
                            </span>
                            <span class="info-value">{{ $user->command->name ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Access History -->
            <div class="info-card">
                <div class="info-card-header">
                    <h6 class="info-card-title">
                        <i class="bx bx-time"></i>
                        Access History & Security
                    </h6>
                </div>
                <div class="info-card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <i class="bx bx-log-in"></i> First Login
                                </div>
                                <div class="timeline-time">
                                    {{ $user->first_login ? $user->first_login : 'Never logged in' }}
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <i class="bx bx-log-in-circle"></i> Last Login
                                </div>
                                <div class="timeline-time">
                                    @if($user->last_login)
                                        {{ $user->last_login->format('Y-m-d H:i:s') }}
                                        <span style="color: #999;">({{ $user->last_login->diffForHumans() }})</span>
                                    @else
                                        Never logged in
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <i class="bx bx-lock-alt"></i> Last Password Change
                                </div>
                                <div class="timeline-time">
                                    @if($user->last_password_change)
                                        {{ $user->last_password_change->format('Y-m-d H:i:s') }}
                                        <span style="color: #999;">({{ $user->last_password_change->diffForHumans() }})</span>
                                    @else
                                        Never changed
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item" style="padding-bottom: 0;">
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <i class="bx bx-error-circle"></i> Failed Login Attempts
                                </div>
                                <div class="timeline-time">
                                    <span class="badge {{ $user->login_attempts > 3 ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.9rem; padding: 6px 12px;">
                                        {{ $user->login_attempts }} attempts
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enquiry Statistics -->
            @if($totalEnquiries > 0)
            <div class="info-card">
                <div class="info-card-header">
                    <h6 class="info-card-title">
                        <i class="bx bx-file-find"></i>
                        Enquiry Statistics
                    </h6>
                </div>
                <div class="info-card-body" style="padding: 20px;">
                    <div class="row mb-4">
                        <!-- Total & Last Enquiry -->
                        <div class="col-md-6 mb-3">
                            <div class="stat-card" style="margin-bottom: 0;">
                                <div class="stat-icon">
                                    <i class="bx bx-clipboard"></i>
                                </div>
                                <div class="stat-value">{{ $totalEnquiries }}</div>
                                <div class="stat-label">Total Enquiries</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-card" style="margin-bottom: 0; border-left-color: #00BCD4;">
                                <div class="stat-icon">
                                    <i class="bx bx-time-five"></i>
                                </div>
                                <div class="stat-value" style="font-size: 1rem;">
                                    @if($lastEnquiry)
                                        {{ $lastEnquiry->created_at->format('d M Y') }}
                                    @else
                                        No enquiries
                                    @endif
                                </div>
                                <div class="stat-label">
                                    Last Enquiry
                                    @if($lastEnquiry)
                                        <br>
                                        <span class="badge bg-primary" style="margin-top: 5px; font-size: 0.8rem;">
                                            {{ ucwords(str_replace('_', ' ', $lastEnquiry->type)) }}
                                        </span>
                                        <span class="badge bg-{{ $lastEnquiry->status === 'approved' ? 'success' : ($lastEnquiry->status === 'rejected' ? 'danger' : 'warning') }}" style="margin-top: 5px; font-size: 0.8rem;">
                                            {{ ucfirst($lastEnquiry->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="chart-container" style="margin-bottom: 0;">
                                <h6 style="color: var(--ura-primary); font-weight: 600; margin-bottom: 20px; text-align: center;">
                                    Enquiry Distribution
                                </h6>
                                <div class="chart-wrapper">
                                    <canvas id="enquiryDonutChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="chart-container" style="margin-bottom: 0;">
                                <h6 style="color: var(--ura-primary); font-weight: 600; margin-bottom: 15px;">
                                    <i class="bx bx-bar-chart-alt-2"></i> Enquiry Breakdown
                                </h6>
                                <ul class="info-list" style="max-height: 280px; overflow-y: auto;">
                                    @foreach($enquiriesByType as $type)
                                    <li class="info-item" style="padding: 10px 0;">
                                        <span class="info-label" style="font-size: 0.9rem;">
                                            <i class="bx bx-right-arrow-alt"></i> {{ $type['type'] }}
                                        </span>
                                        <span class="info-value">
                                            <span class="badge bg-primary">{{ $type['count'] }}</span>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- View Details Button -->
                    <div class="text-center mt-3">
                        <button type="button" class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#enquiryDetailsModal">
                            <i class="bx bx-detail"></i>
                            View Detailed Report
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            @if(auth()->user()->hasRole(['superadmin', 'system_admin']))
            <div class="text-end mt-3">
                <a href="{{ route('users.edit', $user->id) }}" class="action-btn action-btn-primary">
                    <i class="bx bx-edit"></i>
                    Edit User
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Enquiry Details Modal -->
<div class="modal fade" id="enquiryDetailsModal" tabindex="-1" aria-labelledby="enquiryDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enquiryDetailsModalLabel">
                    <i class="bx bx-file-find"></i> Detailed Enquiry Report - {{ $user->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Status Overview -->
                <h6 style="color: var(--ura-primary); font-weight: 600; margin-bottom: 15px;">
                    <i class="bx bx-check-circle"></i> Enquiry Status Overview
                </h6>
                <div class="row mb-4">
                    @foreach($enquiriesByStatus as $status)
                    <div class="col-md-3 mb-3">
                        <div class="stat-card" style="border-left-color: {{ $status['status'] === 'Approved' ? '#10dc60' : ($status['status'] === 'Rejected' ? '#f04141' : '#ffce00') }};">
                            <div class="stat-value">{{ $status['count'] }}</div>
                            <div class="stat-label">{{ $status['status'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Recent Enquiries Table -->
                <h6 style="color: var(--ura-primary); font-weight: 600; margin-bottom: 15px;">
                    <i class="bx bx-history"></i> Recent Enquiries (Last 5)
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bx bx-hash"></i> Check Number</th>
                                <th><i class="bx bx-category"></i> Type</th>
                                <th><i class="bx bx-map"></i> Region</th>
                                <th><i class="bx bx-map-pin"></i> District</th>
                                <th><i class="bx bx-check-circle"></i> Status</th>
                                <th><i class="bx bx-calendar"></i> Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEnquiries as $enquiry)
                            <tr>
                                <td><strong>{{ $enquiry->check_number }}</strong></td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucwords(str_replace('_', ' ', $enquiry->type)) }}
                                    </span>
                                </td>
                                <td>{{ $enquiry->region->name ?? 'N/A' }}</td>
                                <td>{{ $enquiry->district->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $enquiry->status === 'approved' ? 'success' : ($enquiry->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($enquiry->status) }}
                                    </span>
                                </td>
                                <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center" style="color: #999;">No enquiries found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn action-btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($totalEnquiries > 0 && $enquiriesByType->isNotEmpty())
    const ctx = document.getElementById('enquiryDonutChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($enquiriesByType as $type)
                        '{{ $type["type"] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Enquiries',
                    data: [
                        @foreach($enquiriesByType as $type)
                            {{ $type["count"] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#17479E', '#00BCD4', '#10dc60', '#ffce00', '#f04141',
                        '#9C27B0', '#FF9800', '#009688', '#E91E63', '#3F51B5',
                        '#8BC34A', '#FF5722', '#607D8B', '#795548'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(23, 71, 158, 0.95)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        },
                        borderColor: '#00BCD4',
                        borderWidth: 2
                    }
                }
            }
        });
    }
    @endif
});
</script>

<!-- PDF Filter Modal -->
<div class="modal fade" id="pdfFilterModal" tabindex="-1" aria-labelledby="pdfFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: var(--ura-gradient); border: none;">
                <h5 class="modal-title text-white fw-bold" id="pdfFilterModalLabel">
                    <i class="bx bx-filter-alt me-2"></i>PDF Report Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('users.pdf', $user->id) }}" id="pdfFilterForm">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 mb-4" style="background: rgba(23, 71, 158, 0.1);">
                        <i class="bx bx-info-circle me-2"></i>
                        <small>Select filters to customize your PDF report. The report will only include enquiries matching your selected criteria.</small>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-calendar me-2" style="color: var(--ura-primary);"></i>Date Range
                        </label>
                        <select class="form-select" name="date_range" id="dateRangeSelect" style="border: 2px solid rgba(23, 71, 158, 0.2); border-radius: 8px;">
                            <option value="lifetime">Lifetime (All Time)</option>
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="jan_to_june">January to June</option>
                            <option value="july_to_dec">July to December</option>
                            <option value="this_year">This Year</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-check-circle me-2" style="color: var(--ura-accent);"></i>Enquiry Status
                        </label>
                        <select class="form-select" name="status" style="border: 2px solid rgba(23, 71, 158, 0.2); border-radius: 8px;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="assigned">Assigned</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Preview Summary -->
                    <div class="p-3" style="background: rgba(0, 188, 212, 0.1); border-radius: 8px; border-left: 4px solid var(--ura-accent);">
                        <h6 class="mb-2 fw-bold" style="color: var(--ura-primary);">
                            <i class="bx bx-file-find me-2"></i>Report Preview
                        </h6>
                        <p class="mb-1 small">
                            <strong>User:</strong> {{ $user->name }}
                        </p>
                        <p class="mb-1 small">
                            <strong>Total Enquiries:</strong> {{ $totalEnquiries }}
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="bx bx-info-circle me-1"></i>Filters will be applied to this data
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        <i class="bx bx-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" style="background: var(--ura-gradient); border: none; border-radius: 8px;">
                        <i class="bx bx-download me-1"></i>Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
