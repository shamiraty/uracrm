@extends('layouts.app')

@section('title', 'Access Denied - Unauthorized')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-accent: #00BCD4;
        --ura-danger: #f04141;
        --ura-warning: #ffce00;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
    }

    .unauthorized-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .unauthorized-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--ura-shadow);
        padding: 3rem;
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .unauthorized-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--ura-gradient);
    }

    .unauthorized-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(45deg, var(--ura-danger), #ff6b6b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .unauthorized-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 1rem;
    }

    .unauthorized-message {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .role-info {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem 0;
    }

    .role-info h4 {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .role-badge {
        display: inline-block;
        background: var(--ura-primary);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin: 0.2rem;
    }

    .current-role-badge {
        background: var(--ura-danger);
    }

    .required-roles {
        margin-top: 1rem;
    }

    .access-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 1rem;
        margin: 1.5rem 0;
        font-size: 0.9rem;
        color: #856404;
    }

    .btn-dashboard {
        background: var(--ura-gradient);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.4);
        color: white;
        text-decoration: none;
    }

    .attempted-route {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        color: #495057;
        word-break: break-all;
    }

    .security-notice {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1.5rem;
        font-size: 0.85rem;
        color: #0c5460;
    }

    .responsive-text {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .unauthorized-card {
            margin: 1rem;
            padding: 2rem;
        }

        .unauthorized-title {
            font-size: 2rem;
        }

        .unauthorized-message {
            font-size: 1rem;
        }

        .responsive-text {
            font-size: 0.9rem;
        }
    }
</style>

<div class="unauthorized-container">
    <div class="unauthorized-card">
        <div class="unauthorized-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <h1 class="unauthorized-title">Access Denied</h1>

        <p class="unauthorized-message">
            You don't have permission to access this resource. Your access attempt has been logged for security purposes.
        </p>

        @if(session('unauthorized_data'))
            @php
                $data = session('unauthorized_data');
            @endphp

            <div class="role-info">
                <h4><i class="fas fa-user-shield"></i> Role Information</h4>

                <div class="mb-3">
                    <strong>Your Current Role:</strong>
                    <span class="role-badge current-role-badge">
                        {{ $data['user_role'] ?? 'No Role Assigned' }}
                    </span>
                </div>

                <div class="required-roles">
                    <strong>Required Roles for this Resource:</strong><br>
                    @if(isset($data['required_roles']) && is_array($data['required_roles']))
                        @foreach($data['required_roles'] as $role)
                            <span class="role-badge">{{ $role }}</span>
                        @endforeach
                    @else
                        <span class="role-badge">N/A</span>
                    @endif
                </div>
            </div>

            <div class="access-info">
                <i class="fas fa-info-circle"></i>
                <strong>Attempted Resource:</strong> {{ $data['attempted_route'] ?? 'Unknown' }}
            </div>

            @if(isset($data['attempted_url']))
                <div class="attempted-route">
                    <strong>URL:</strong> {{ $data['attempted_url'] }}
                </div>
            @endif
        @endif

        <div class="security-notice">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Security Notice:</strong> This access attempt has been logged and administrators have been notified.
            Repeated unauthorized access attempts may result in account restrictions.
        </div>

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn-dashboard">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
        </div>

        <div class="mt-3 responsive-text">
            <small class="text-muted">
                If you believe this is an error, please contact your system administrator.
                <br>
                <strong>Timestamp:</strong> {{ now()->format('d/m/Y H:i:s T') }}
            </small>
        </div>
    </div>
</div>
@endsection