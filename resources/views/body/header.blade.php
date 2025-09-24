<!--start header -->
<style>
    /* Modern Header Styling with Enhanced Gradient */
    .topbar {
        background: linear-gradient(135deg, #17479E 0%, #0F3470 50%, #17479E 100%);
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(15px);
        border-bottom: 3px solid rgba(0, 188, 212, 0.3);
        height: 65px;
        padding: 0 25px;
        position: relative;
        overflow: hidden;
    }

    /* Animated background effect */
    .topbar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 188, 212, 0.1), transparent);
        animation: shine 3s ease-in-out infinite;
    }

    @keyframes shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .topbar .navbar {
        height: 100%;
    }

    /* Enhanced Search Bar with Glass Effect */
    .search-bar {
        position: relative;
    }

    .search-bar input {
        background: rgba(255, 255, 255, 0.12);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        color: white;
        padding: 12px 45px;
        font-size: 14px;
        width: 300px;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .search-bar input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .search-bar .search-show {
        color: rgba(255, 255, 255, 0.7);
    }

    .search-bar input:focus {
        background: rgba(255, 255, 255, 0.18);
        border-color: #00BCD4;
        box-shadow: 0 0 0 4px rgba(0, 188, 212, 0.25), 0 8px 25px rgba(0, 188, 212, 0.15);
        outline: none;
        transform: translateY(-2px);
        width: 350px;
    }

    .search-bar .search-show {
        color: rgba(255, 255, 255, 0.8);
        font-size: 18px;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .search-bar input:focus + .search-show {
        color: #00BCD4;
        transform: scale(1.1);
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

    /* Enhanced Dropdown with Modern Glass Effect */
    .dropdown-menu {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        border: 2px solid rgba(23, 71, 158, 0.15);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        border-radius: 16px;
        margin-top: 12px;
        z-index: 9999 !important;
        overflow: hidden;
        animation: dropdownFadeIn 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes dropdownFadeIn {
        0% {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .dropdown-item {
        padding: 12px 20px;
        color: #2c3e50;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .dropdown-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.08) 0%, rgba(0, 188, 212, 0.08) 100%);
        transition: left 0.3s ease;
        z-index: -1;
    }

    .dropdown-item:hover::before {
        left: 0;
    }

    .dropdown-item:hover {
        color: #17479E;
        padding-left: 25px;
        transform: translateX(5px);
        background: transparent;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }
    
    .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    
    .dropdown-divider {
        margin: 0;
        border-color: rgba(23, 71, 158, 0.1);
    }


    /* Enhanced User Profile Section */
    .user-box {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(0, 188, 212, 0.1) 100%);
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 8px 15px !important;
        margin-left: 15px;
        position: relative;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .user-box .dropdown-toggle {
        cursor: pointer;
        text-decoration: none;
    }
    
    .user-box .user-name {
        color: white;
        font-size: 13px;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
        pointer-events: none;
    }
    
    .user-box .designation {
        color: rgba(255, 255, 255, 0.7);
        font-size: 11px;
        margin: 0;
        pointer-events: none;
    }
    
    .user-box .dropdown-toggle::after {
        color: rgba(255, 255, 255, 0.6);
    }

    .user-box:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(0, 188, 212, 0.15) 100%);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 188, 212, 0.25);
        border-color: rgba(0, 188, 212, 0.4);
    }
    
    /* Ensure user dropdown menu works */
    .user-box .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        margin-top: 5px;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #00BCD4 0%, #17479E 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .user-avatar::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        opacity: 0;
    }

    .user-box:hover .user-avatar::before {
        opacity: 1;
        animation: shimmer 1.5s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }

    /* Enhanced Notification Styling */
    .top-menu .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        margin: 0 4px;
    }

    .top-menu .nav-link:hover {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(23, 71, 158, 0.2) 100%);
        color: white !important;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3);
        border-color: rgba(0, 188, 212, 0.4);
    }

    .notify {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.15) 0%, rgba(23, 71, 158, 0.15) 100%);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .notify i {
        font-size: 16px;
    }

    .msg-header {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
    }

    .msg-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shine 2s ease-in-out infinite;
    }
    
    .msg-header-title {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .msg-header-badge {
        background: rgba(255, 255, 255, 0.95) !important;
        color: #17479E !important;
        font-weight: 700;
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .header-notifications-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .header-notifications-list::-webkit-scrollbar {
        width: 4px;
    }
    
    .header-notifications-list::-webkit-scrollbar-thumb {
        background: rgba(23, 71, 158, 0.2);
        border-radius: 2px;
    }
    
    .msg-name {
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }
    
    .msg-time {
        font-size: 11px;
        color: #999;
    }
    
    .msg-footer {
        background: #f8f9fa;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Icon Buttons - White for dark header */
    .top-menu .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .top-menu .nav-link i {
        font-size: 20px;
    }
    
    /* Enhanced Mobile Toggle */
    .mobile-toggle-menu {
        color: white;
        font-size: 28px;
        cursor: pointer;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        backdrop-filter: blur(10px);
    }

    .mobile-toggle-menu:hover {
        transform: translateY(-2px) scale(1.1);
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
        box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3);
        border-color: rgba(0, 188, 212, 0.4);
    }

    .top-menu .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateY(-1px);
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
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .topbar {
            padding: 0 10px;
        }
        
        .search-bar {
            display: none !important;
        }
        
        .user-box .user-info {
            display: none;
        }
        
        .nav-link {
            width: 32px;
            height: 32px;
        }
    }
    
    /* Enhanced Button Styling */
    .btn-primary {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        border: none;
        border-radius: 12px;
        padding: 10px 25px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 30px rgba(0, 188, 212, 0.4);
        background: linear-gradient(135deg, #00BCD4 0%, #17479E 100%);
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
</style>

<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu">
                <i class='bx bx-menu'></i>
            </div>

            <!-- Search Bar - Show for specific roles -->
            @if(auth()->check() && auth()->user()->hasAnyRole(['superadmin', 'system_admin', 'general_manager', 'assistant_general_manager']))
            <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                <input class="form-control px-5" disabled type="search" placeholder="Search">
                <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5">
                    <i class='bx bx-search'></i>
                </span>
            </div>
            @endif

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <!-- Mobile Search -->
                    @if(auth()->check() && auth()->user()->hasAnyRole(['superadmin', 'system_admin', 'general_manager', 'assistant_general_manager']))
                    <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <a class="nav-link" href="javascript:;">
                            <i class='bx bx-search'></i>
                        </a>
                    </li>
                    @endif

                    <!-- Dark Mode Toggle -->
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;">
                            <i class='bx bx-moon'></i>
                        </a>
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
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 400px;">
                            <div class="msg-header p-3 d-flex justify-content-between align-items-center">
                                <p class="msg-header-title mb-0 fw-bold">Notifications</p>
                                @if(isset($notifications))
                                <p class="msg-header-badge rounded-pill px-2 py-1">
                                    {{ $notifications->where('is_read', false)->count() }} New
                                </p>
                                @endif
                            </div>
                            <div class="header-notifications-list" style="max-height: 350px; overflow-y: auto;">
                                @if(isset($notifications))
                                    @forelse($notifications as $notification)
                                        <a class="dropdown-item d-flex align-items-center p-3 border-bottom" href="javascript:;">
                                            <div class="notify bg-light-primary text-primary rounded-circle p-2 me-3">
                                                <i class='bx bx-bell fs-5'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name mb-1">{{ $notification->message }}</h6>
                                                <p class="msg-time text-muted mb-0 small">
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

            <!-- User Profile Dropdown -->
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                        <p class="designation mb-0 small text-muted">
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
                            <i class="bx bx-user fs-5 me-2"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->hasAnyRole(['superadmin', 'system_admin', 'general_manager']))
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                            <i class="bx bx-home-circle fs-5 me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @endif
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:;">
                            <i class="bx bx-cog fs-5 me-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center text-danger" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-log-out-circle fs-5 me-2"></i>
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
<!--end header -->