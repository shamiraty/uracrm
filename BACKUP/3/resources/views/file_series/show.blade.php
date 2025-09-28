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

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--ura-primary);
        font-weight: 600;
        width: 150px;
        flex-shrink: 0;
    }

    .detail-value {
        color: #495057;
        font-weight: 500;
        flex: 1;
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 1rem;
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

    .modern-btn-success {
        background: var(--ura-success);
        color: white;
    }

    .modern-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modern-btn-danger {
        background: var(--ura-danger);
        color: white;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
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
            File Series Details
        </h1>
        <p class="header-subtitle">
            Comprehensive view of file series information and related files
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
            <li>
                <a href="{{ route('file_series.index') }}">
                    <i class="bx bx-folder-open"></i>
                    File Series
                </a>
            </li>
            <li><span class="text-muted">•</span></li>
            <li class="text-muted">{{ $fileSeries->name }}</li>
        </ul>
    </div>

    <!-- Statistics Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-folder"></i>
            </div>
            <div class="stat-value">{{ $fileSeries->id }}</div>
            <p class="stat-label">Series ID</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-file"></i>
            </div>
            <div class="stat-value">0</div>
            <p class="stat-label">Associated Files</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-calendar"></i>
            </div>
            <div class="stat-value">{{ $fileSeries->created_at ? $fileSeries->created_at->format('M Y') : 'N/A' }}</div>
            <p class="stat-label">Created</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-edit"></i>
            </div>
            <div class="stat-value">{{ $fileSeries->updated_at ? $fileSeries->updated_at->diffForHumans() : 'N/A' }}</div>
            <p class="stat-label">Last Updated</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- File Series Details -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-info-circle"></i>
                        Series Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-folder me-2"></i>
                            Series Name:
                        </div>
                        <div class="detail-value">
                            <strong>{{ $fileSeries->name }}</strong>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-code me-2"></i>
                            Series Code:
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                {{ $fileSeries->code }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-calendar me-2"></i>
                            Created:
                        </div>
                        <div class="detail-value">
                            {{ $fileSeries->created_at ? $fileSeries->created_at->format('F j, Y \a\t g:i A') : 'Unknown' }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-time me-2"></i>
                            Last Updated:
                        </div>
                        <div class="detail-value">
                            {{ $fileSeries->updated_at ? $fileSeries->updated_at->format('F j, Y \a\t g:i A') : 'Unknown' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Action Card -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-cog"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('file_series.edit', $fileSeries) }}" class="modern-btn modern-btn-primary">
                            <i class="bx bx-edit"></i>
                            Edit Series
                        </a>
                        <a href="{{ route('file_series.index') }}" class="modern-btn modern-btn-secondary">
                            <i class="bx bx-arrow-left"></i>
                            Back to List
                        </a>
                        <form action="{{ route('file_series.destroy', $fileSeries) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this file series? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="modern-btn modern-btn-danger w-100">
                                <i class="bx bx-trash"></i>
                                Delete Series
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-link"></i>
                        Related Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-file me-2"></i>
                            Associated Files:
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                0 files
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="bx bx-user me-2"></i>
                            Created By:
                        </div>
                        <div class="detail-value">
                            System
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection