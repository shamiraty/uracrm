<!-- Ultra-Modern URASACCOS Sidebar -->
<style>
    /* Fix page content positioning - Restored original layout */
    .wrapper {
        position: relative;
        min-height: 100vh;
    }

    /* Topbar positioning */
    .topbar {
        position: fixed;
        top: 0;
        left: 250px;
        right: 0;
        height: 60px;
        z-index: 998;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .sidebar-wrapper {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 250px !important;
        height: 100vh;
        z-index: 999;
    }

    .page-wrapper {
        position: relative;
        min-height: 100vh;
        padding-top: 0 !important;
        margin-top: 60px !important;
        margin-left: 250px !important;
        width: calc(100% - 250px) !important;
    }

    .page-content {
        padding: 20px 30px !important;
        min-height: calc(100vh - 120px);
        position: relative;
        scroll-behavior: smooth;
    }

    /* Ensure proper scrolling */
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 80px;
    }

    body {
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* Reset any conflicting styles */
    .page-content > *:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* When sidebar is toggled/collapsed */
    .wrapper.toggled .sidebar-wrapper {
        width: 70px !important;
    }

    .wrapper.toggled .topbar {
        left: 70px !important;
    }

    .wrapper.toggled .page-wrapper {
        margin-left: 70px !important;
        width: calc(100% - 70px) !important;
    }

    .wrapper.toggled .page-footer {
        left: 70px !important;
    }

    /* Enhanced URASACCOS Brand Colors */
    :root {
        --ura-primary: #17479E;
        --ura-primary-dark: #0F3470;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-purple: #764ba2;
        --ura-pink: #f093fb;
        --ura-gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --ura-gradient-2: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-3: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --ura-gradient-hover: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-info: #0575e6;
        --ura-text: #6c7293;
        --ura-text-light: #8f95b2;
        --ura-white: #ffffff;
        --ura-bg-light: #f8f9fa;
        --ura-shadow: 0 10px 30px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 15px 40px rgba(23, 71, 158, 0.25);
    }

    /* Enhanced Sidebar Header - Compact */
    .sidebar-header {
        background: #17479E;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.2);
        position: sticky;
        top: 0;
        z-index: 10;
        flex-shrink: 0;
    }

    .sidebar-header img {
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        background: white;
        padding: 3px;
        width: 45px;
        height: 45px;
    }

    .sidebar-header .logo-text {
        font-size: 16px;
        font-weight: 600;
        background: linear-gradient(135deg, #ffffff 0%, #e0f7fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header .toggle-icon {
        color: white;
        opacity: 0.8;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .sidebar-header .toggle-icon:hover {
        opacity: 1;
        transform: rotate(180deg);
    }

    /* Enhanced Sidebar Container - Primary color background */
    .sidebar-wrapper {
        background: #17479E;
        backdrop-filter: blur(10px);
        border-right: 2px solid rgba(255, 255, 255, 0.1);
        overflow-x: hidden !important;
        overflow-y: auto !important;
        /* Ensure smooth scrolling */
        scroll-behavior: smooth;
        /* Ensure it takes full height */
        height: 100vh !important;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Remove any SimpleBar elements */
    .sidebar-wrapper .simplebar-content-wrapper,
    .sidebar-wrapper .simplebar-content {
        overflow: hidden !important;
    }

    .sidebar-wrapper .simplebar-scrollbar,
    .sidebar-wrapper .simplebar-track {
        display: none !important;
    }

    .sidebar-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 188, 212, 0.05) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    /* Sidebar Styling - Toggle-like compact */
    #menu {
        padding: 2px 8px 10px 8px;
        margin: 0;
        position: relative;
        z-index: 1;
        /* No scrolling on menu */
        overflow: visible !important;
        height: auto !important;
        flex: 1;
        /* Ensure menu doesn't create its own scroll context */
        max-height: none !important;
    }

    #menu > li {
        margin-bottom: 0;
        transition: all 0.3s ease;
    }

    #menu > li > a {
        padding: 8px 12px;
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        border-radius: 0;
        margin: 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.03);
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }

    /* First and last item rounded corners */
    #menu > li:first-child > a {
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
    }

    #menu > li:last-child > a {
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        border-bottom: none;
    }

    #menu > li > a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: var(--ura-gradient-hover);
        transition: left 0.5s ease;
        z-index: -1;
    }

    #menu > li > a:hover::before {
        left: 0;
    }

    #menu > li > a:hover {
        color: white;
        background: rgba(255, 255, 255, 0.08);
        border-left: 3px solid var(--ura-accent);
        padding-left: 9px;
    }

    #menu > li > a.active,
    #menu > li.active > a {
        background: linear-gradient(90deg, rgba(0, 188, 212, 0.15) 0%, rgba(23, 71, 158, 0.15) 100%);
        color: var(--ura-white);
        border-left: 3px solid var(--ura-accent);
        padding-left: 9px;
        font-weight: 600;
    }

    @keyframes glow {
        from { box-shadow: var(--ura-shadow-hover); }
        to { box-shadow: 0 20px 50px rgba(0, 188, 212, 0.3); }
    }

    .parent-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        margin-right: 8px;
        transition: all 0.3s ease;
        position: relative;
        border: none;
    }

    .parent-icon::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 12px;
        background: var(--ura-gradient-2);
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.4s ease;
        z-index: -1;
    }

    #menu > li > a:hover .parent-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    #menu > li > a:hover .parent-icon i {
        color: var(--ura-accent-light);
    }

    #menu > li > a.active .parent-icon,
    #menu > li.active > a .parent-icon {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .parent-icon i {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.4s ease;
        position: relative;
        z-index: 1;
    }

    #menu > li > a.active .parent-icon i,
    #menu > li.active > a .parent-icon i {
        background: none;
        -webkit-text-fill-color: var(--ura-white);
    }

    .menu-title {
        font-size: 13px;
        font-weight: 500;
        flex-grow: 1;
        letter-spacing: 0.2px;
        color: rgba(255, 255, 255, 0.95);
    }

    /* Enhanced Submenu Styling - Toggle style */
    #menu ul {
        padding: 0;
        margin: 0;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 0;
        border: none;
        transition: all 0.3s ease;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    #menu ul li {
        list-style: none;
        margin-bottom: 1px;
    }

    #menu ul li a {
        padding: 7px 12px 7px 35px;
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 12px;
        font-weight: 400;
        transition: all 0.2s ease;
        position: relative;
        border-radius: 0;
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    #menu ul li a::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--ura-gradient-2);
        opacity: 0;
        transition: all 0.3s ease;
    }

    #menu ul li a:hover {
        color: rgba(255, 255, 255, 0.95);
        background: rgba(255, 255, 255, 0.05);
        padding-left: 38px;
    }

    #menu ul li a:hover::before {
        opacity: 1;
        animation: blink 1s ease-in-out infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    #menu ul li a i {
        margin-right: 8px;
        font-size: 12px;
        color: var(--ura-accent-light);
        transition: all 0.3s ease;
    }

    #menu ul li a:hover i {
        transform: scale(1.2) rotate(10deg);
    }

    #menu ul li.active a {
        color: var(--ura-primary);
        font-weight: 600;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.12) 0%, rgba(0, 188, 212, 0.12) 100%);
        border-left: 2px solid var(--ura-accent);
        padding-left: 48px;
    }

    /* Enhanced Arrow Icon with Animation */
    .has-arrow {
        position: relative;
    }

    .has-arrow::after {
        content: '';
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-right: 2px solid var(--ura-accent);
        border-bottom: 2px solid var(--ura-accent);
        transform: translateY(-50%) rotate(-45deg);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .has-arrow:hover::after {
        right: 22px;
        border-color: var(--ura-primary);
    }

    .has-arrow.expanded::after {
        transform: translateY(-50%) rotate(45deg);
        border-color: var(--ura-primary);
    }

    /* Modern Badge Styling with Animation */
    .menu-badge {
        background: var(--ura-gradient-3);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        margin-left: auto;
        box-shadow: 0 2px 8px rgba(240, 147, 251, 0.4);
        animation: badge-pulse 2s ease-in-out infinite;
        letter-spacing: 0.5px;
    }

    @keyframes badge-pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 2px 8px rgba(240, 147, 251, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 4px 12px rgba(240, 147, 251, 0.6); }
    }

    .menu-badge.success {
        background: linear-gradient(135deg, var(--ura-success) 0%, #00e676 100%);
        box-shadow: 0 2px 8px rgba(16, 220, 96, 0.4);
    }

    .menu-badge.warning {
        background: linear-gradient(135deg, var(--ura-warning) 0%, #ffd54f 100%);
        box-shadow: 0 2px 8px rgba(255, 206, 0, 0.4);
    }

    .menu-badge.danger {
        background: linear-gradient(135deg, var(--ura-danger) 0%, #ff5252 100%);
        box-shadow: 0 2px 8px rgba(240, 65, 65, 0.4);
    }

    /* Modern Scrollbar Styling - Only for sidebar wrapper */
    .sidebar-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-wrapper::-webkit-scrollbar-track {
        background: linear-gradient(180deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
        border-radius: 3px;
        margin: 80px 0 10px 0; /* Add top margin for header */
    }

    .sidebar-wrapper::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
        border-radius: 3px;
        box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.1);
    }

    .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--ura-accent) 0%, var(--ura-purple) 100%);
    }

    /* Hide all potential duplicate scrollbars */
    #menu::-webkit-scrollbar,
    .sidebar-wrapper > div::-webkit-scrollbar,
    .simplebar-content-wrapper::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /* Hide SimpleBar completely if it exists */
    .simplebar-scrollbar,
    .simplebar-track,
    .simplebar-horizontal,
    .simplebar-vertical {
        display: none !important;
    }

    /* Ensure only sidebar-wrapper shows scrollbar */
    .sidebar-wrapper {
        scrollbar-width: thin;
        scrollbar-color: var(--ura-primary) transparent;
    }

    /* Tooltip Styling */
    .menu-tooltip {
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 10px;
        background: var(--ura-gradient-2);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
        z-index: 1000;
    }

    .menu-tooltip::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: #17479E;
    }

    #menu > li > a:hover .menu-tooltip {
        opacity: 1;
        transform: translateY(-50%) translateX(5px);
    }

    /* Notification Dot */
    .notification-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: var(--ura-danger);
        border-radius: 50%;
        animation: notification-pulse 1.5s ease-in-out infinite;
        box-shadow: 0 0 0 0 rgba(240, 65, 65, 0.7);
    }

    @keyframes notification-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(240, 65, 65, 0.7);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(240, 65, 65, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(240, 65, 65, 0);
        }
    }

    /* Divider Line */
    .menu-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.05);
        margin: 2px 0;
    }

    /* Menu Header */
    .menu-header {
        padding: 5px 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: rgba(255, 255, 255, 0.3);
        margin: 5px 0 2px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Mini Sidebar Mode */
    .sidebar-wrapper.sidebar-mini #menu .menu-title,
    .sidebar-wrapper.sidebar-mini #menu .menu-badge,
    .sidebar-wrapper.sidebar-mini #menu .menu-header,
    .sidebar-wrapper.sidebar-mini .sidebar-header h2 {
        display: none;
    }

    .sidebar-wrapper.sidebar-mini {
        width: 80px !important;
    }

    .sidebar-wrapper.sidebar-mini .sidebar-header {
        padding: 15px 10px;
        justify-content: center;
    }

    .sidebar-wrapper.sidebar-mini #menu > li > a {
        justify-content: center;
        padding: 12px;
    }

    .sidebar-wrapper.sidebar-mini .parent-icon {
        margin-right: 0;
    }

    .sidebar-wrapper.sidebar-mini #menu ul {
        position: absolute;
        left: 80px;
        top: 0;
        min-width: 200px;
        background: white;
        box-shadow: var(--ura-shadow-hover);
        border-radius: 8px;
        display: none;
    }

    .sidebar-wrapper.sidebar-mini #menu li:hover > ul {
        display: block !important;
        max-height: none !important;
        opacity: 1 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar-wrapper {
            transform: translateX(-250px);
            transition: transform 0.3s ease;
        }

        .sidebar-wrapper.mobile-open {
            transform: translateX(0);
        }

        .page-wrapper {
            margin-left: 0 !important;
            width: 100% !important;
        }

        .menu-title {
            display: none;
        }

        #menu > li > a {
            justify-content: center;
            padding: 12px;
        }

        .parent-icon {
            margin-right: 0;
        }

        .menu-tooltip {
            display: block !important;
            opacity: 0 !important;
        }

        #menu > li > a:hover .menu-tooltip {
            opacity: 1 !important;
        }

        .sidebar-header h2 {
            display: none;
        }

        .sidebar-header {
            padding: 15px 10px;
        }
    }

    /* Enhanced Dark Mode Support */
    body.dark-theme .sidebar-wrapper {
        background: linear-gradient(180deg, rgba(26, 43, 74, 0.95) 0%, rgba(15, 52, 112, 0.95) 100%);
    }

    body.dark-theme #menu > li > a {
        color: rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.05);
    }

    body.dark-theme #menu > li > a:hover {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(23, 71, 158, 0.2) 100%);
        color: var(--ura-accent-light);
    }

    body.dark-theme #menu > li > a.active,
    body.dark-theme #menu > li.active > a {
        background: var(--ura-gradient-2);
        color: white;
    }

    body.dark-theme #menu ul {
        background: linear-gradient(135deg, rgba(26, 43, 74, 0.7) 0%, rgba(15, 52, 112, 0.7) 100%);
        border-color: rgba(0, 188, 212, 0.2);
    }

    body.dark-theme .parent-icon {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.1) 0%, rgba(23, 71, 158, 0.1) 100%);
        border-color: rgba(0, 188, 212, 0.2);
    }

    /* Ripple Effect */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>

<ul class="metismenu" id="menu">
    <!-- Main Navigation -->
    <div class="menu-header">MAIN NAVIGATION</div>

    <!-- Dashboard - Available to all roles -->
    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <div class="parent-icon">
                <i class='bx bx-home-alt'></i>
                <span class="notification-dot"></span>
            </div>
            <div class="menu-title">Dashboard</div>
            <span class="menu-badge success">New</span>
        </a>
    </li>

    <!-- My Enquiries - Available to all roles except some restrictions -->
    @if(auth()->check() && !auth()->user()->hasRole('branch_manager'))
    <li class="{{ request()->routeIs('enquiries.my') ? 'active' : '' }}">
        <a href="{{ route('enquiries.my') }}">
            <div class="parent-icon"><i class='bx bx-folder'></i></div>
            <div class="menu-title">My Enquiries</div>
            <span class="menu-badge">5</span>
        </a>
    </li>
    @endif

    <div class="menu-divider"></div>

    <!-- OPERATIONS SECTION -->
    <div class="menu-header">OPERATIONS</div>

    <!-- ENQUIRY MANAGEMENT - Only for Registrar and management roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['Registrar', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-category"></i></div>
            <div class="menu-title">Enquiries Management</div>
        </a>
        <ul>
            <!-- Show "New Enquiry" for all roles in this section -->
            <li><a href="{{ route('enquiries.create') }}"><i class='bx bx-plus-circle'></i>New Enquiry</a></li>
            <li><a href="{{ route('enquiries.index') }}"><i class='bx bx-folder-open'></i>All Enquiries</a></li>
            <!-- Show all other items only for non-Registrar roles -->
            @if(!auth()->user()->hasRole('Registrar'))
                <li><a href="{{ route('enquiries.index', ['type' => 'share_enquiry']) }}"><i class='bx bx-share-alt'></i>Share Enquiries</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'retirement']) }}"><i class='bx bx-user-check'></i>Retirement Enquiries</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'deduction_add']) }}"><i class='bx bx-plus'></i>Deduction Adjustment</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'refund']) }}"><i class='bx bx-undo'></i>Refund Enquiries</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'withdraw_savings']) }}"><i class='bx bx-money-withdraw'></i>Withdraw Savings</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'join_membership']) }}"><i class='bx bx-user-plus'></i>Join Membership</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'unjoin_membership']) }}"><i class='bx bx-user-x'></i>Unjoin Membership</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'benefit_from_disasters']) }}"><i class='bx bx-support'></i>Benefit from Disasters</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'sick_for_30_days']) }}"><i class='bx bx-plus-medical'></i>Sick 30 Days</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'condolences']) }}"><i class='bx bx-heart'></i>Condolences</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'injured_at_work']) }}"><i class='bx bx-first-aid'></i>Work Injury</a></li>
                <li><a href="{{ route('enquiries.index', ['type' => 'ura_mobile']) }}"><i class='bx bx-mobile-alt'></i>Ura Mobile</a></li>
            @endif
        </ul>
    </li>
    @endif

    <div class="menu-divider"></div>

    <!-- SALARY LOANS SECTION - Only for loan officers and management -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-wallet"></i></div>
            <div class="menu-title">Salary Loans</div>
        </a>
        <ul>
            <li><a href="{{ route('loan-offers.index') }}"><i class='bx bx-dashboard'></i>Employee Loans</a></li>
            <li><a href="{{ route('loans.pending') }}"><i class='bx bx-time'></i>Pending Loans</a></li>
            <li><a href="{{ route('loans.approved') }}"><i class='bx bx-check-circle'></i>Approved Loans</a></li>
            <li><a href="{{ route('loans.rejected') }}"><i class='bx bx-x-circle'></i>Rejected Loans</a></li>
            <li><a href="{{ route('loans.disbursed') }}"><i class='bx bx-money'></i>Disbursed Loans</a></li>
            <li><a href="{{ route('mortgage.form') }}"><i class='bx bx-calculator'></i>Loan Calculator</a></li>
            <li><a href="{{ route('members.uploadForm') }}"><i class='bx bx-upload'></i>Upload Applications</a></li>
            <li><a href="{{ route('loans.reports') }}"><i class='bx bx-bar-chart-alt-2'></i>Reports</a></li>
            <li><a href="{{ route('loans.collections') }}"><i class='bx bx-dollar-circle'></i>Collections</a></li>
        </ul>
    </li>
    @endif

    <!-- BUSINESS LOANS SECTION - Only for loan officers and management -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-briefcase"></i></div>
            <div class="menu-title">Business Loans</div>
        </a>
        <ul>
            <li><a href="{{ route('deductions.salary.loans') }}"><i class='bx bx-credit-card'></i>Salary Loans</a></li>
            <li><a href="{{ route('deductions.variance') }}"><i class='bx bx-search-alt'></i>Repayment Tracing</a></li>
            <li><a href="{{ route('mortgage.form') }}"><i class='bx bx-calculator'></i>Calculator</a></li>
            <li><a href="{{ route('members.processedLoans') }}"><i class='bx bx-hourglass'></i>Pending Loan</a></li>
            <li><a href="#"><i class='bx bx-x-circle'></i>Rejected Loan</a></li>
            <li><a href="#"><i class='bx bx-check-double'></i>Payed Loans</a></li>
            <li><a href="#"><i class='bx bx-badge-check'></i>Approved Loans</a></li>
            <li><a href="#"><i class='bx bx-trending-up'></i>Interest</a></li>
            <li><a href="{{ route('members.uploadForm') }}"><i class='bx bx-cloud-upload'></i>Upload Loan Application</a></li>
            <li><a href="#"><i class='bx bx-cog'></i>Processed Loans</a></li>
        </ul>
    </li>
    @endif

    <!-- LOAN DEDUCTIONS SECTION - Available to multiple roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'branch_manager']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-calculator"></i></div>
            <div class="menu-title">Loan Deductions</div>
        </a>
        <ul>
            <li><a href="{{ route('deductions.salary.loans') }}"><i class='bx bx-money'></i>Salary Loans</a></li>
            <li><a href="{{ route('deductions.variance') }}"><i class='bx bx-search-alt'></i>Repayment Tracing</a></li>
        </ul>
    </li>
    @endif

    <div class="menu-divider"></div>

    <!-- DISBURSEMENT MANAGEMENT SECTION - Only for loan officers and management -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-money"></i></div>
            <div class="menu-title">Disbursement Management</div>
        </a>
        <ul>
            <li><a href="{{ route('disbursements.pending') }}"><i class='bx bx-hourglass-half'></i>Pending Disbursement</a></li>
            <li><a href="{{ route('disbursements.index', ['status' => 'rejected']) }}"><i class='bx bx-x-circle'></i>Rejected</a></li>
            <li><a href="{{ route('disbursements.index', ['status' => 'failed']) }}"><i class='bx bx-error-circle'></i>Failed (NMB)</a></li>
            <li><a href="{{ route('disbursements.index', ['status' => 'disbursed']) }}"><i class='bx bx-check-double'></i>Completed</a></li>
        </ul>
    </li>
    @endif

    <!-- PAYMENT MANAGEMENT SECTION - Only for accountants and management -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['accountant', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class='bx bx-credit-card'></i></div>
            <div class="menu-title">Payments Management</div>
        </a>
        <ul>
            <li class="{{ request()->is('payments/refund') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'refund']) }}">
                    <i class='bx bx-undo'></i> Refund
                </a>
            </li>
            <li class="{{ request()->is('payments/retirement') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'retirement']) }}">
                    <i class='bx bx-user-check'></i> Retirement
                </a>
            </li>
            <li class="{{ request()->is('payments/withdraw_savings') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'withdraw_savings']) }}">
                    <i class='bx bx-money-withdraw'></i> Withdraw Savings
                </a>
            </li>
            <li class="{{ request()->is('payments/benefit_from_disasters') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'benefit_from_disasters']) }}">
                    <i class='bx bx-support'></i> Benefit from Disasters
                </a>
            </li>
            <li class="{{ request()->is('payments/deduction_add') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'deduction_add']) }}">
                    <i class='bx bx-plus'></i> Deduction Adjustment
                </a>
            </li>
            <li class="{{ request()->is('payments/share_enquiry') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'share_enquiry']) }}">
                    <i class='bx bx-share-alt'></i> Share
                </a>
            </li>
            <li class="{{ request()->is('payments/withdraw_deposit') ? 'active' : '' }}">
                <a href="{{ route('payments.type', ['type' => 'withdraw_deposit']) }}">
                    <i class='bx bx-wallet'></i> Withdraw Deposit
                </a>
            </li>
        </ul>
    </li>
    @endif

    <div class="menu-divider"></div>

    <!-- MANAGEMENT SECTION -->
    <div class="menu-header">MANAGEMENT</div>

    <!-- MEMBER MANAGEMENT SECTION - Available to multiple roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'accountant', 'loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq', 'branch_manager']))
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-group"></i></div>
            <div class="menu-title">Member Management</div>
        </a>
        <ul>
            <li><a href="{{ route('uramembers.index') }}"><i class="bx bx-user-plus"></i>New Member</a></li>
            <li><a href="{{ route('deductions.members.list') }}"><i class="bx bx-users"></i>Members</a></li>
            <li><a href="{{ route('deductions.contributions.handle') }}"><i class="bx bx-donate-heart"></i>Members Contributions</a></li>
            <li><a href="{{ route('deduction667.differences.index') }}"><i class="bx bx-transfer"></i>Contributions Changes</a></li>
            <li><a href="{{ route('deductions.contribution_analysis') }}"><i class="bx bx-pie-chart-alt-2"></i>Contributions Analysis</a></li>
            <li><a href="#"><i class="bx bx-user-x"></i>Unjoin Member</a></li>
            <li><a href="#"><i class="bx bx-user-check"></i>Retired Member</a></li>
        </ul>
    </li>
    @endif

    <!-- BRANCH MANAGEMENT SECTION - Only for admin and system roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin']))
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-buildings"></i></div>
            <div class="menu-title">Branch Management</div>
        </a>
        <ul>
            <li><a href="{{ route('branches.index') }}"><i class='bx bx-building'></i>Branches</a></li>
            <li><a href="{{ route('departments.index') }}"><i class='bx bx-sitemap'></i>Departments</a></li>
            <li><a href="{{ route('commands.index') }}"><i class='bx bx-command'></i>Commands</a></li>
            <li><a href="{{ route('representatives.index') }}"><i class='bx bx-user-pin'></i>Representatives</a></li>
            <li><a href="{{ url('/posts/create') }}"><i class='bx bx-plus-circle'></i>Create Post</a></li>
            <li><a href="{{ route('payroll.showUpload') }}"><i class='bx bx-cloud-upload'></i>Import Payroll</a></li>
        </ul>
    </li>
    @endif

    <!-- ACCESS MANAGEMENT SECTION - Only for admin and system roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-shield"></i></div>
            <div class="menu-title">Access Management</div>
        </a>
        <ul>
            <li><a href="{{ route('roles.index') }}"><i class='bx bx-user-pin'></i>Roles</a></li>
            <li><a href="{{ route('permissions.index') }}"><i class='bx bx-key'></i>Permissions</a></li>
            <li><a href="{{ route('users.index') }}"><i class='bx bx-user'></i>Users</a></li>
        </ul>
    </li>
    @endif

    <!-- TRENDS SECTION - Only for management and admin -->
    @if(auth()->check() && auth()->user()->hasRole(['general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-bar-chart"></i></div>
            <div class="menu-title">Trends</div>
        </a>
        <ul>
            <li><a href="{{ route('trends') }}"><i class='bx bx-file-blank'></i>Registered Enquiries</a></li>
            <li><a href="{{ route('loan_trends') }}"><i class='bx bx-briefcase'></i>Loan Applications</a></li>
        </ul>
    </li>
    @endif

    <div class="menu-divider"></div>

    <!-- SYSTEM SECTION -->
    <div class="menu-header">SYSTEM</div>

    <!-- DOCUMENT MANAGEMENT SECTION -->
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-archive"></i></div>
            <div class="menu-title">Document Management</div>
        </a>
        <ul>
            <li><a href="{{ route('files.index') }}"><i class='bx bx-radio-circle'></i>List Files</a></li>
            <li><a href="{{ route('files.create') }}"><i class='bx bx-radio-circle'></i>Create File</a></li>
            <li><a href="{{ route('file_series.index') }}"><i class='bx bx-radio-circle'></i>List File Series</a></li>
            <li><a href="{{ route('file_series.create') }}"><i class='bx bx-radio-circle'></i>Create File Series</a></li>
            <li><a href="{{ route('keywords.index') }}"><i class='bx bx-radio-circle'></i>List Keywords</a></li>
            <li><a href="{{ route('keywords.create') }}"><i class='bx bx-radio-circle'></i>Create Keyword</a></li>
            <li><a href="{{ route('keywords.showImportForm') }}"><i class='bx bx-import'></i>Import Keywords</a></li>
            <li><a href="{{ route('test.api') }}"><i class='bx bx-import'></i>Test api</a></li>
        </ul>
    </li>

    <!-- PAYROLL MANAGEMENT SECTION -->
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-archive"></i></div>
            <div class="menu-title">Payroll Management</div>
        </a>
        <ul>
            <li><a href="{{ route('deductions.import.form') }}"><i class='bx bx-radio-circle'></i>Import Deductions</a></li>
        </ul>
    </li>

    <!-- MEMBER ID MANAGEMENT SECTION - Available to all roles -->
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-id-card"></i></div>
            <div class="menu-title">Member ID Management</div>
        </a>
        <ul>
            <li><a href="{{ route('card-details.index') }}"><i class='bx bx-credit-card'></i>Member Cards</a></li>
        </ul>
    </li>

    <!-- CAMPAIGN MANAGEMENT SECTION - Available to multiple roles -->
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'accountant', 'loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin', 'system_admin', 'public_relation_officer', 'registrar_hq']))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-bullhorn"></i></div>
            <div class="menu-title">Campaign Management</div>
        </a>
        <ul>
            <li><a href="{{ route('bulk.sms.form') }}"><i class='bx bx-message'></i>Send Bulk SMS</a></li>
        </ul>
    </li>
    @endif

</ul>

<!-- Enhanced JavaScript for Modern Toggle Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Sidebar script loaded');

        // Initialize all submenus as collapsed with smooth animation
        document.querySelectorAll('#menu ul').forEach(submenu => {
            submenu.style.maxHeight = '0';
            submenu.style.overflow = 'hidden';
            submenu.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
            submenu.style.opacity = '0';
        });

        // Handle menu toggle with smooth animations
        document.querySelectorAll('.has-arrow').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                const submenu = this.nextElementSibling;
                const isExpanded = this.classList.contains('expanded');

                // Close all other submenus
                document.querySelectorAll('.has-arrow').forEach(otherItem => {
                    if (otherItem !== this && otherItem.classList.contains('expanded')) {
                        otherItem.classList.remove('expanded');
                        const otherSubmenu = otherItem.nextElementSibling;
                        if (otherSubmenu) {
                            otherSubmenu.style.maxHeight = '0';
                            otherSubmenu.style.opacity = '0';
                        }
                    }
                });

                // Toggle current submenu
                if (!isExpanded) {
                    this.classList.add('expanded');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    submenu.style.opacity = '1';
                } else {
                    this.classList.remove('expanded');
                    submenu.style.maxHeight = '0';
                    submenu.style.opacity = '0';
                }
            });
        });

        // Auto-expand active menu item's parent
        document.querySelectorAll('#menu li.active').forEach(activeItem => {
            const parentUl = activeItem.closest('ul');
            if (parentUl && parentUl.id !== 'menu') {
                const parentLi = parentUl.previousElementSibling;
                if (parentLi && parentLi.classList.contains('has-arrow')) {
                    parentLi.classList.add('expanded');
                    parentUl.style.maxHeight = parentUl.scrollHeight + 'px';
                    parentUl.style.opacity = '1';
                }
            }
        });

        // Add ripple effect on click (only for visual effect, don't prevent navigation)
        document.querySelectorAll('#menu > li > a').forEach(link => {
            // Skip ripple effect for has-arrow items as they have their own click handler
            if (!link.classList.contains('has-arrow')) {
                link.addEventListener('click', function(e) {
                    console.log('Link clicked:', this.href);

                    // Scroll to top before navigation
                    window.scrollTo(0, 0);
                    document.querySelector('.page-content')?.scrollTo(0, 0);

                    // Don't prevent default for regular links - allow navigation
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    this.appendChild(ripple);

                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';

                    setTimeout(() => ripple.remove(), 600);

                    // Ensure navigation happens
                    if (this.href && this.href !== 'javascript:;' && this.href !== '#') {
                        // Allow the browser to navigate naturally
                        return true;
                    }
                });
            }
        });

        // Ensure submenu links work properly
        document.querySelectorAll('#menu ul li a').forEach(link => {
            link.addEventListener('click', function(e) {
                console.log('Submenu link clicked:', this.href);

                // Scroll to top before navigation
                window.scrollTo(0, 0);
                document.querySelector('.page-content')?.scrollTo(0, 0);

                // Stop propagation but allow default navigation
                e.stopPropagation();

                // Ensure navigation happens for submenu items
                if (this.href && this.href !== 'javascript:;' && this.href !== '#') {
                    // Navigate to the URL
                    return true;
                }
            });
        });

        // Force scroll to top on page load
        window.scrollTo(0, 0);
        document.querySelector('.page-content')?.scrollTo(0, 0);
    });
</script>