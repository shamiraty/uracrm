<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Audit Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #17479E;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #17479E;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        .metric-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        .metric-title {
            font-weight: bold;
            color: #17479E;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #495057;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #17479E;
            color: white;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>URA SACCOS Security Audit Report</h1>
        <p>Generated on: {{ $audit_date }}</p>
        <p>System Security Assessment</p>
    </div>

    <!-- Security Alerts -->
    @if($users_with_expired_passwords > 0)
    <div class="alert alert-warning">
        <strong>⚠️ Warning:</strong> {{ $users_with_expired_passwords }} users have expired passwords (older than 3 months).
    </div>
    @endif

    @if($failed_login_attempts > 0)
    <div class="alert alert-danger">
        <strong>🚨 Alert:</strong> {{ $failed_login_attempts }} users have 3+ failed login attempts.
    </div>
    @endif

    @if($users_never_logged_in > 0)
    <div class="alert alert-warning">
        <strong>📢 Notice:</strong> {{ $users_never_logged_in }} users have never logged in.
    </div>
    @endif

    <!-- Security Metrics -->
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-cell">
                <div class="metric-card">
                    <div class="metric-title">Total Users</div>
                    <div class="metric-value">{{ number_format($total_users) }}</div>
                </div>
            </div>
            <div class="summary-cell">
                <div class="metric-card">
                    <div class="metric-title">Active Users</div>
                    <div class="metric-value">{{ number_format($active_users) }}</div>
                </div>
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-cell">
                <div class="metric-card">
                    <div class="metric-title">Currently Online</div>
                    <div class="metric-value">{{ number_format($online_users) }}</div>
                </div>
            </div>
            <div class="summary-cell">
                <div class="metric-card">
                    <div class="metric-title">Inactive Users</div>
                    <div class="metric-value">{{ number_format($inactive_users) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Score -->
    @php
        $securityScore = 100;
        if($users_with_expired_passwords > 0) $securityScore -= 20;
        if($failed_login_attempts > 0) $securityScore -= 15;
        if($users_never_logged_in > 0) $securityScore -= 10;
        if($inactive_users > ($total_users * 0.3)) $securityScore -= 10;
    @endphp

    <div class="alert {{ $securityScore >= 80 ? 'alert-success' : ($securityScore >= 60 ? 'alert-warning' : 'alert-danger') }}">
        <strong>Security Score: {{ $securityScore }}/100</strong>
        @if($securityScore >= 80)
            - Excellent security posture
        @elseif($securityScore >= 60)
            - Good security with minor issues
        @else
            - Security improvements needed
        @endif
    </div>

    <!-- Recent Login Activity -->
    <h3 style="color: #17479E; border-bottom: 1px solid #17479E; padding-bottom: 5px;">Recent Login Activity (Last 7 Days)</h3>

    @if($recent_logins->count() > 0)
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Branch</th>
                <th>Rank</th>
                <th>Last Login</th>
                <th>Login Attempts</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_logins as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->branch->name ?? 'N/A' }}</td>
                <td>{{ $user->rank->name ?? 'N/A' }}</td>
                <td>{{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}</td>
                <td style="{{ $user->login_attempts >= 3 ? 'color: red; font-weight: bold;' : '' }}">{{ $user->login_attempts }}</td>
                <td style="{{ $user->status === 'active' ? 'color: green;' : 'color: red;' }}">{{ ucfirst($user->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No recent login activity found.</p>
    @endif

    <!-- Recommendations -->
    <h3 style="color: #17479E; border-bottom: 1px solid #17479E; padding-bottom: 5px; margin-top: 30px;">Security Recommendations</h3>
    <ul>
        @if($users_with_expired_passwords > 0)
        <li>Force password reset for {{ $users_with_expired_passwords }} users with expired passwords</li>
        @endif
        @if($failed_login_attempts > 0)
        <li>Review and potentially unlock {{ $failed_login_attempts }} accounts with failed login attempts</li>
        @endif
        @if($users_never_logged_in > 0)
        <li>Follow up on {{ $users_never_logged_in }} users who have never logged in</li>
        @endif
        @if($inactive_users > 10)
        <li>Review {{ $inactive_users }} inactive user accounts for potential deactivation</li>
        @endif
        <li>Implement regular password rotation policy</li>
        <li>Monitor failed login attempts and implement account lockout policies</li>
        <li>Conduct quarterly security audits</li>
    </ul>

    <div class="footer">
        URA SACCOS Security Audit Report - Confidential Document - Page 1 of 1
    </div>
</body>
</html>