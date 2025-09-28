<!--start header -->
<style>
    /* Modern Header Styling with Primary Color */
    .topbar {
        background: #17479E;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        height: 60px;
        padding: 0 20px;
    }
    
    .topbar .navbar {
        height: 100%;
    }

    /* Notification Badge - More visible */
    .alert-count {
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        background: linear-gradient(135deg, #ff5252 0%, #f04141 100%);
        border: 2px solid #17479E;
        animation: pulse 2s infinite;
        pointer-events: none;
        z-index: 1;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Modern Dropdown - Glass effect */
    .dropdown-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(23, 71, 158, 0.1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        margin-top: 10px;
        z-index: 9999 !important;
    }
    
    .dropdown-item {
        padding: 12px 20px;
        color: #333;
        font-size: 14px;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin: 2px 8px;
    }
    
    .dropdown-item:hover {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        color: var(--ura-primary);
        transform: translateX(5px);
    }
    
    .dropdown-item i {
        width: 24px;
        text-align: center;
        font-size: 18px;
    }
    
    .dropdown-divider {
        margin: 8px 0;
        border-color: rgba(23, 71, 158, 0.1);
    }

    /* User Profile Section - Glass effect - Consistent on all devices */
    .user-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 8px 15px !important;
        margin-left: 15px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .user-box .dropdown-toggle {
        cursor: pointer;
        text-decoration: none;
    }
    
    .user-box .user-name {
        color: white;
        font-size: 14px;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
        pointer-events: none;
    }
    
    .user-box .designation {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        margin: 0;
        pointer-events: none;
    }
    
    .user-box .dropdown-toggle::after {
        color: rgba(255, 255, 255, 0.6);
        margin-left: 10px;
    }

    .user-box:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Ensure user dropdown menu works */
    .user-box .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        margin-top: 8px;
        min-width: 280px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #00BCD4 0%, #17479E 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Notification Item Styling - Modern */
    .notify {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
    }
    
    .notify i {
        font-size: 18px;
    }

    .msg-header {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        padding: 18px;
        border-radius: 12px 12px 0 0;
    }
    
    .msg-header-title {
        font-size: 16px;
        font-weight: 600;
    }

    .msg-header-badge {
        background: rgba(255, 255, 255, 0.9) !important;
        color: #17479E !important;
        font-weight: 700;
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 15px;
    }
    
    .header-notifications-list {
        max-height: 350px;
        overflow-y: auto;
    }
    
    .header-notifications-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .header-notifications-list::-webkit-scrollbar-thumb {
        background: rgba(23, 71, 158, 0.3);
        border-radius: 3px;
    }
    
    .msg-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    
    .msg-time {
        font-size: 12px;
        color: #999;
    }
    
    .msg-footer {
        background: #f8f9fa;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 0 0 12px 12px;
    }

    /* Icon Buttons - White for dark header */
    .top-menu .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .top-menu .nav-link i {
        font-size: 22px;
    }
    
    .top-menu .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Dark Mode Toggle with Working Functionality */
    .dark-mode-toggle {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 8px 16px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .dark-mode-toggle:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }
    
    .dark-mode-toggle i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Dark mode styles */
    body.dark-mode {
        background-color: #1a1a1a;
        color: #ffffff;
    }
    
    body.dark-mode .topbar {
        background: #0d1421;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    body.dark-mode .dropdown-menu {
        background: rgba(26, 26, 26, 0.95);
        color: white;
    }
    
    body.dark-mode .dropdown-item {
        color: #ffffff;
    }
    
    body.dark-mode .dropdown-item:hover {
        background: rgba(23, 71, 158, 0.2);
        color: #00BCD4;
    }
    
    /* Fix dropdown toggle */
    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
    
    .dropdown-toggle-nocaret::after {
        display: none;
    }

    /* Role-based Display */
    .role-based {
        display: none;
    }

    .role-admin,
    .role-manager {
        display: block;
    }
    
    /* Responsive adjustments - Keep profile consistent */
    @media (max-width: 768px) {
        .topbar {
            padding: 0 15px;
        }
        
        .user-box {
            padding: 8px 12px !important;
            margin-left: 10px;
        }
        
        .user-box .user-info {
            display: block; /* Keep visible on mobile */
        }
        
        .user-name {
            font-size: 13px !important;
        }
        
        .designation {
            font-size: 11px !important;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
        
        .top-menu .nav-link {
            width: 40px;
            height: 40px;
        }
        
        .top-menu .nav-link i {
            font-size: 20px;
        }
    }
    
    /* Additional modern effects */
    .btn-primary {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
    }
    
    /* Remove pointer-events issues */
    .dropdown-toggle {
        cursor: pointer !important;
        pointer-events: auto !important;
    }
    
    .dropdown-menu.show {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Profile dropdown styling */
    .user-box .dropdown-menu .dropdown-item:first-child {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .text-danger:hover {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
    }
</style>

<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-2">
                    <!-- Dark Mode Toggle with Working Functionality -->
                    <li class="nav-item">
                        <button class="dark-mode-toggle btn" onclick="toggleDarkMode()" id="darkModeToggle">
                            <i class='bx bx-moon' id="darkModeIcon"></i>
                            <span id="darkModeText">Dark</span>
                        </button>
                    </li>

                    <!-- Notifications - Role Based -->
                    @if(auth()->check())
                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown">
                            @if(isset($notifications) && $notifications->where('is_read', false)->count() > 0)
                            <span class="alert-count badge bg-danger rounded-circle">
                                {{ $notifications->where('is_read', false)->count() }}
                            </span>
                            @endif
                            <i class='bx bx-bell'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 420px;">
                            <div class="msg-header d-flex justify-content-between align-items-center">
                                <p class="msg-header-title mb-0 fw-bold">Notifications</p>
                                @if(isset($notifications))
                                <span class="msg-header-badge rounded-pill">
                                    {{ $notifications->where('is_read', false)->count() }} New
                                </span>
                                @endif
                            </div>
                            <div class="header-notifications-list">
                                @if(isset($notifications))
                                    @forelse($notifications as $notification)
                                        <a class="dropdown-item d-flex align-items-center p-3 border-bottom" href="javascript:;">
                                            <div class="notify me-3">
                                                <i class='bx bx-bell text-primary'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name mb-1">{{ $notification->message }}</h6>
                                                <p class="msg-time mb-0">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if(!$notification->is_read)
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ms-auto">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-link p-0 text-success">
                                                    <i class="bx bx-check-circle fs-5"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="text-center p-4">
                                            <i class="bx bx-bell-off fs-1 text-muted"></i>
                                            <p class="text-muted mt-2">No new notifications</p>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="text-center p-4">
                                        <i class="bx bx-bell-off fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No notifications</p>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center msg-footer p-3">
                                <a href="javascript:;" class="btn btn-primary btn-sm w-100">View All Notifications</a>
                            </div>
                        </div>
                    </li>
                    @endif

                    <!-- Admin Tools - Only for Admins -->
                    @if(auth()->check() && auth()->user()->hasAnyRole(['superadmin', 'system_admin']))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}" title="User Management">
                            <i class='bx bx-cog'></i>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- User Profile Dropdown - Consistent on all devices -->
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                        <p class="designation mb-0">
                            @if(Auth::user()->roles->isNotEmpty())
                                {{ Auth::user()->roles->first()->name }}
                            @else
                                {{ Auth::user()->designation ?? 'User' }}
                            @endif
                        </p>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="p-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <p class="mb-0 small text-muted">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:;">
                            <i class="bx bx-user me-3"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->hasAnyRole(['superadmin', 'system_admin', 'general_manager']))
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                            <i class="bx bx-home-circle me-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @endif
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:;">
                            <i class="bx bx-cog me-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center text-danger" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-log-out-circle me-3"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </nav>
    </div>
</header>

<script>
// Dark Mode Toggle Functionality
function toggleDarkMode() {
    const body = document.body;
    const darkModeIcon = document.getElementById('darkModeIcon');
    const darkModeText = document.getElementById('darkModeText');
    
    body.classList.toggle('dark-mode');
    
    if (body.classList.contains('dark-mode')) {
        darkModeIcon.className = 'bx bx-sun';
        darkModeText.textContent = 'Light';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        darkModeIcon.className = 'bx bx-moon';
        darkModeText.textContent = 'Dark';
        localStorage.setItem('darkMode', 'disabled');
    }
}

// Load dark mode preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const darkMode = localStorage.getItem('darkMode');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const darkModeText = document.getElementById('darkModeText');
    
    if (darkMode === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeIcon.className = 'bx bx-sun';
        darkModeText.textContent = 'Light';
    }
});
</script>

<!--end header -->