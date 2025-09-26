@extends('layouts.app')

@section('content')
<style>
    /* Retain Online Users Animation CSS */
    .online-indicator {
        width: 8px;
        height: 8px;
        background: #48bb78;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(72, 187, 120, 0); }
        100% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0); }
    }

    .online-users-card {
        position: sticky;
        top: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .online-user-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .online-user-item:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 1rem;
    }

    /* CSS for Add User button on the right */
    .card-header-with-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
    }
</style>

<div class="container-fluid">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('enquiries.index') }}" class="text-decoration-none">
                                    <i class="bi bi-house-door"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Users Management</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add New User
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-4 g-4">
                <div class="col-lg-6">
                    <div class="accordion" id="onlineUsersAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOnlineUsers">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnlineUsers" aria-expanded="false" aria-controls="collapseOnlineUsers">
                                    <p class="mb-0 fw-bold"><i class="bi bi-person-check me-2"></i> Online Users (<span id="online-users-count">{{ $onlineUsersCount }}</span>)</p>
                                </button>
                            </h2>
                            <div id="collapseOnlineUsers" class="accordion-collapse collapse" aria-labelledby="headingOnlineUsers" data-bs-parent="#onlineUsersAccordion">
                                <div class="accordion-body">
                                    <div id="online-users-table-content">
                                        {{-- Online Users Table --}}
                                        @if($onlineUsers->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Branch</th>
                                                            <th>Last Activity</th>
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
                                                                            <div class="fw-semibold">{{ $user->name }}</div>
                                                                            <small class="text-muted">{{ $user->rank->name ?? 'N/A' }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $user->branch->name ?? 'N/A' }}</td>
                                                                <td>
                                                                    <small class="text-success">
                                                                        @if($user->last_activity)
                                                                            {{ \Carbon\Carbon::parse($user->last_activity)->diffForHumans() }}
                                                                        @else
                                                                            Active now
                                                                        @endif
                                                                    </small>
                                                                </td>
                                                                <td>
                                                                    <span class="online-indicator"></span>
                                                                    <small class="text-success fw-bold">Online</small>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <i class="bi bi-person-slash fs-1 opacity-50"></i>
                                                <p class="mt-2">No users currently online</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="accordion" id="summaryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSummary">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSummary" aria-expanded="false" aria-controls="collapseSummary">
                                    <p class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i> User Statistics Summary</p>
                                </button>
                            </h2>
                            <div id="collapseSummary" class="accordion-collapse collapse" aria-labelledby="headingSummary" data-bs-parent="#summaryAccordion">
                                <div class="accordion-body">
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-4">
                                            <div class="card text-center shadow-sm h-100">
                                                <div class="card-body">
                                                    <p class="fw-bold mb-1">{{ $totalUsers }}</p>
                                                    <p class="text-muted mb-0">Total Users</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center shadow-sm h-100">
                                                <div class="card-body">
                                                    <p class="fw-bold mb-1">{{ $activeUsers }}</p>
                                                    <p class="text-muted mb-0">Active Users</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card text-center shadow-sm h-100">
                                                <div class="card-body">
                                                    <p class="fw-bold mb-1"><span id="summary-online-count">{{ $onlineUsersCount }}</span></p>
                                                    <p class="text-muted mb-0">Online Now</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <div class="card p-3 text-center shadow-sm">
                                                <div class="fw-bold text-info fs-4">{{ $loggedInToday }}</div>
                                                <small class="text-muted">Today</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="card p-3 text-center shadow-sm">
                                                <div class="fw-bold text-warning fs-4">{{ $loggedInYesterday }}</div>
                                                <small class="text-muted">Yesterday</small>
                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="card p-3 text-center shadow-sm">
                                                <div class="fw-bold text-success fs-4">{{ $loggedInThisWeek }}</div>
                                                <small class="text-muted">This Week</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="card p-3 text-center shadow-sm">
                                                <div class="fw-bold text-primary fs-4">{{ $loggedInThisMonth }}</div>
                                                <small class="text-muted">This Month</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion mb-4" id="filterAccordion">
                <div class="accordion-item ">
                    <h2 class="accordion-header" id="headingFilter">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                            <p class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i> Filter All Users</p>
                        </button>
                    </h2>
                    <div id="collapseFilter" class="accordion-collapse collapse" aria-labelledby="headingFilter" data-bs-parent="#filterAccordion">
                        <div class="accordion-body">
                            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Branch</label>
                                    <select name="branch_id" class="form-select">
                                        <option value="">All Branches</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Role</label>
                                    <select name="role_id" class="form-select">
                                        <option value="">All Roles</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="true" {{ request('status') == 'true' ? 'selected' : '' }}>Active</option>
                                        <option value="false" {{ request('status') == 'false' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Region</label>
                                    <select name="region_id" class="form-select">
                                        <option value="">All Regions</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Department</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">District</label>
                                    <select name="district_id" class="form-select">
                                        <option value="">All Districts</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-funnel"></i> Apply
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg basic-data-table">
                <div class="card-header card-header-with-btn">
                    <h5 class="card-title mb-0">All System Users ({{ count($usersWithStatus) }} rows)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-primary-table mb-0 w-100" id="dataTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>User</th>
                                    <th>Contact</th>
                                    <th>Role & Status</th>
                                    <th>Organization</th>
                                    <th>Activity</th>
                                    <th>Security</th>
                                    <th>Actions</th>
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
                                                    <span class="badge bg-info text-white">{{ $user->getRoleNames()->first() }}</span>
                                                @else
                                                    <span class="badge bg-secondary">No Role</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($user->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
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

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle accordion state
    const onlineUsersAccordion = document.getElementById('onlineUsersAccordion');
    const summaryAccordion = document.getElementById('summaryAccordion');

    if (onlineUsersAccordion && summaryAccordion) {
        onlineUsersAccordion.addEventListener('shown.bs.collapse', function () {
            const summaryCollapse = document.getElementById('collapseSummary');
            if (summaryCollapse.classList.contains('show')) {
                const summaryButton = summaryAccordion.querySelector('.accordion-button');
                const summaryCollapseInstance = new bootstrap.Collapse(summaryCollapse, {
                    toggle: false
                });
                summaryCollapseInstance.hide();
                summaryButton.classList.add('collapsed');
                summaryButton.setAttribute('aria-expanded', 'false');
            }
        });

        summaryAccordion.addEventListener('shown.bs.collapse', function () {
            const onlineUsersCollapse = document.getElementById('collapseOnlineUsers');
            if (onlineUsersCollapse.classList.contains('show')) {
                const onlineUsersButton = onlineUsersAccordion.querySelector('.accordion-button');
                const onlineUsersCollapseInstance = new bootstrap.Collapse(onlineUsersCollapse, {
                    toggle: false
                });
                onlineUsersCollapseInstance.hide();
                onlineUsersButton.classList.add('collapsed');
                onlineUsersButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

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
            
            const summaryCount = document.getElementById('summary-online-count');
            if (summaryCount) {
                summaryCount.textContent = data.count;
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
});
</script>

@endsection