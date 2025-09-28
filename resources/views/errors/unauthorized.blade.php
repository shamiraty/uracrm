<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - URA SACCOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --ura-primary: #17479E;
            --ura-accent: #00BCD4;
            --ura-danger: #DC3545;
            --ura-warning: #FFC107;
            --ura-success: #28A745;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .unauthorized-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .unauthorized-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--ura-primary), var(--ura-accent));
        }

        .error-icon {
            font-size: 4rem;
            color: var(--ura-danger);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .error-title {
            color: var(--ura-primary);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .error-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .access-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 5px solid var(--ura-danger);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--ura-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            color: #495057;
            font-weight: 500;
        }

        .role-badge {
            background: var(--ura-danger);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .required-roles {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .required-role {
            background: var(--ura-success);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-dashboard {
            background: linear-gradient(45deg, var(--ura-primary), var(--ura-accent));
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 71, 158, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-back {
            background: transparent;
            border: 2px solid var(--ura-primary);
            color: var(--ura-primary);
            padding: 10px 23px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--ura-primary);
            color: white;
            text-decoration: none;
        }

        .security-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 25px;
            color: #856404;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 20px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .unauthorized-container {
                padding: 25px;
                margin: 10px;
            }

            .error-title {
                font-size: 2rem;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .required-roles {
                justify-content: flex-start;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-dashboard, .btn-back {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="unauthorized-container">
        <div class="error-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <h1 class="error-title">Access Denied</h1>
        <p class="error-subtitle">
            You don't have permission to access this resource. This incident has been logged for security purposes.
        </p>

        <div class="access-details">
            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-user"></i>
                    Your Role:
                </span>
                <span class="detail-value">
                    <span class="role-badge">{{ session('unauthorized_data.user_role') ?? 'Unknown' }}</span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-key"></i>
                    Required Roles:
                </span>
                <div class="required-roles">
                    @if(session('unauthorized_data.required_roles'))
                        @foreach(session('unauthorized_data.required_roles') as $role)
                            <span class="required-role">{{ $role }}</span>
                        @endforeach
                    @else
                        <span class="required-role">Not specified</span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-route"></i>
                    Attempted Page:
                </span>
                <span class="detail-value">{{ session('unauthorized_data.attempted_route') ?? 'Unknown' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-clock"></i>
                    Time:
                </span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-user-circle"></i>
                    User:
                </span>
                <span class="detail-value">{{ auth()->user()->name ?? 'Unknown' }}</span>
            </div>
        </div>

        <div class="security-note">
            <i class="fas fa-exclamation-triangle"></i>
            <span>
                For security reasons, this access attempt has been recorded. If you believe you should have access to this resource, please contact your system administrator.
            </span>
        </div>

        <div class="action-buttons">
            <a href="{{ route('dashboard') }}" class="btn-dashboard">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>

        <div class="timestamp">
            URA SACCOS Security System - {{ now()->format('d F Y, H:i:s') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>