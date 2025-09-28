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

    .file-series-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .file-series-header::before {
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
        font-size: 0.9rem;
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

    .modern-btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    .modern-btn-success {
        background: var(--ura-success);
        color: white;
    }

    .modern-btn-danger {
        background: var(--ura-danger);
        color: white;
    }

    .modern-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modern-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        padding: 1rem;
        border: none;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modern-table th:first-child {
        border-top-left-radius: 12px;
    }

    .modern-table th:last-child {
        border-top-right-radius: 12px;
    }

    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        vertical-align: middle;
        color: #495057;
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
    }

    .modern-alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: none;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .modern-alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.1) 0%, rgba(16, 220, 96, 0.05) 100%);
        color: var(--ura-success);
        border-left: 4px solid var(--ura-success);
    }

    .modern-alert-danger {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.1) 0%, rgba(240, 65, 65, 0.05) 100%);
        color: var(--ura-danger);
        border-left: 4px solid var(--ura-danger);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        border-left: 4px solid var(--ura-primary);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-breadcrumb {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .breadcrumb-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-nav a {
        color: var(--ura-primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .breadcrumb-nav a:hover {
        color: var(--ura-accent);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="file-series-header">
        <h1 class="header-title">
            <i class="bx bx-folder-open"></i>
            File Series Management
        </h1>
        <p class="header-subtitle">
            Manage and organize your file series database with advanced tools
        </p>
    </div>

    <!-- Breadcrumb -->
    <div class="modern-breadcrumb">
        <ul class="breadcrumb-nav">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="bx bx-home-alt"></i>
                    Dashboard
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">File Series</li>
        </ul>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="modern-alert modern-alert-success">
            <i class="bx bx-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="modern-alert modern-alert-danger">
            <i class="bx bx-error-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ count($fileSeries) }}</div>
                <p class="stats-label">Total File Series</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ count($fileSeries->where('created_at', '>=', now()->startOfMonth())) }}</div>
                <p class="stats-label">This Month</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ count($fileSeries->where('created_at', '>=', now()->startOfWeek())) }}</div>
                <p class="stats-label">This Week</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ count($fileSeries->where('created_at', '>=', now()->startOfDay())) }}</div>
                <p class="stats-label">Today</p>
            </div>
        </div>
    </div>

    <!-- File Series Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="modern-card-title">
                    <i class="bx bx-list-ul"></i>
                    File Series Database
                    <span class="badge bg-primary ms-2">{{ count($fileSeries) }} records</span>
                </h5>
                <a href="{{ route('file_series.create') }}" class="modern-btn modern-btn-primary">
                    <i class="bx bx-plus"></i>
                    Add New Series
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="modern-table" id="fileSeriesTable">
                    <thead>
                        <tr>
                            <th>
                                <i class="bx bx-folder me-1"></i>
                                Series Name
                            </th>
                            <th>
                                <i class="bx bx-code me-1"></i>
                                Series Code
                            </th>
                            <th>
                                <i class="bx bx-calendar me-1"></i>
                                Created Date
                            </th>
                            <th>
                                <i class="bx bx-cog me-1"></i>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fileSeries as $series)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bx bx-folder text-primary"></i>
                                        <strong>{{ $series->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $series->code }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $series->created_at ? $series->created_at->format('M j, Y') : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('file_series.show', $series) }}"
                                           class="modern-btn modern-btn-sm modern-btn-secondary"
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                            View
                                        </a>
                                        <a href="{{ route('file_series.edit', $series) }}"
                                           class="modern-btn modern-btn-sm modern-btn-success"
                                           title="Edit Series">
                                            <i class="bx bx-edit"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('file_series.destroy', $series) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this file series?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="modern-btn modern-btn-sm modern-btn-danger"
                                                    title="Delete Series">
                                                <i class="bx bx-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <i class="bx bx-folder-open text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mb-2">No file series found</p>
                                        <a href="{{ route('file_series.create') }}" class="modern-btn modern-btn-primary">
                                            <i class="bx bx-plus"></i>
                                            Create First Series
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Initialize DataTable if needed
    $(document).ready(function() {
        $('#fileSeriesTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[2, 'desc']], // Sort by created date
            language: {
                search: "Search file series:",
                lengthMenu: "Show _MENU_ series per page",
                info: "Showing _START_ to _END_ of _TOTAL_ file series",
                infoEmpty: "No file series available",
                zeroRecords: "No matching file series found"
            }
        });
    });
</script>
@endsection

@endsection