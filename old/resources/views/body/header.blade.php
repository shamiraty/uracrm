


    <div class="navbar-header">
        <div class="row align-items-center justify-content-between">
            <!-- Left Side: Sidebar Toggles and Search -->
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <!-- Sidebar Toggle Buttons -->
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                    </button>

                    <!-- Search Form -->
                    <form class="navbar-search">
                        <input type="text" name="search" placeholder="Search">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
            </div>

            <!-- Right Side: Theme Toggle, Language, Messages, Notifications, Profile -->
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center">
                        <!-- You can add an icon or image here for theme toggle -->
                        <iconify-icon icon="mdi:theme-light-dark" class="text-xl"></iconify-icon>
                    </button>

                    {{--
                    <!-- Language Dropdown -->
                    <div class="dropdown d-none d-sm-inline-block">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <img src="assets/images/lang-flag.png" alt="Language" class="w-24 h-24 object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
                                </div>
                            </div>
                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                                <!-- Language Options -->
                                @foreach(['English', 'Japan', 'France', 'Germany', 'South Korea', 'Bangladesh', 'India', 'Canada'] as $language)
                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="{{ strtolower(str_replace(' ', '_', $language)) }}">
                                        <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="assets/images/flags/flag{{ $loop->iteration }}.png" alt="{{ $language }} Flag" class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">{{ $language }}</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="language" id="{{ strtolower(str_replace(' ', '_', $language)) }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div><!-- Language dropdown end -->
                    --}}

                    
<!-----------------NOTIFICATION STARTS-------------------------------------------------------------------------------------->

                    <!-- Notifications Dropdown -->
<div class="dropdown dropdown-large">
    <button class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center position-relative" 
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
        @if($notifications->where('is_read', false)->count() > 0)
            <span class="badge bg-danger rounded-circle position-absolute top-0 end-0 p-1 text-white" 
                  style="font-size: 0.75rem;">
                {{ $notifications->where('is_read', false)->count() }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu to-top dropdown-menu-lg p-0" style="max-width: 350px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div class="m-3 py-3 px-4 rounded-top bg-primary bg-opacity-10 mb-4 d-flex justify-content-between align-items-center">
            <h6 class="text-secondary fw-semibold mb-0" style="font-size: 1rem;">Notifications</h6>
            <span class="text-white fw-bold bg-primary rounded-circle d-flex justify-content-center align-items-center" 
                  style="width: 30px; height: 30px; font-size: 0.75rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                {{ $notifications->where('is_read', false)->count() }} 
            </span>
        </div>

        <div class="header-notifications-list" style="max-height: 400px; overflow-y: auto;">
            @forelse($notifications as $notification)
                <a class="dropdown-item d-flex align-items-center p-2 border-bottom" href="javascript:;" 
                   style="transition: background-color 0.3s ease; border-radius: 10px; padding: 10px;">
                    <div class="notify bg-light-info rounded-circle p-1 me-2" style="width: 35px; height: 35px;">
                        <iconify-icon icon="bitcoin-icons:verify-outline" class="fs-6"></iconify-icon>
                    </div>
                    <div class="flex-grow-1">
                        <small class="msg-name mb-1 fw-semibold text-dark" style="font-size: 0.9rem;">{{ $notification->message }}</small>
                        <br>
                        <small class="msg-time text-muted mb-0" style="font-size: 0.8rem;">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ms-auto">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link p-0 text-success" style="font-size: 0.8rem; transition: transform 0.3s ease;">
                                <iconify-icon icon="bx:check-circle" class="fs-6"></iconify-icon>
                            </button>
                        </form>
                    @endif
                </a>
            @empty
                <a class="dropdown-item text-center p-2 text-muted" href="javascript:;">
                    <iconify-icon icon="bx:bell-off" class="fs-5"></iconify-icon>
                    <br>
                    <small>No new notifications</small>
                </a>
            @endforelse
        </div>

        <div class="text-center msg-footer p-3">
            {{-- Uncomment this line to allow the user to view all notifications --}}
            {{-- <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-sm w-100">View All Notifications</a> --}}
        </div>
    </div>
</div>


<!-----------------NOTIFICATION ENDS-------------------------------------------------------------------------------------->



                   <!-- User Profile Dropdown -->
<div class="dropdown">
    <button class="d-flex justify-content-center align-items-center rounded-circle w-40-px h-40-px bg-neutral-200" type="button" data-bs-toggle="dropdown">
        <iconify-icon icon="mingcute:user-follow-fill" class="icon text-secondary text-xl object-fit-cover rounded-circle"></iconify-icon>  
    </button>
    <ul class="dropdown-menu dropdown-menu-sm to-top">
        <li>
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                <div>
                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ Auth::user()->name }}</h6>
                    <span class="text-secondary-light fw-medium text-sm">{{ Auth::user()->designation }}</span>
                </div>
                <button type="button" class="hover-text-danger">
                    <iconify-icon icon="radix-icons:cross-1" class="text-xl"></iconify-icon>
                </button>
            </div>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center px-0 py-8 hover-bg-transparent hover-text-primary" href="{{ route('profile') }}">
                <iconify-icon icon="solar:user-linear" class="text-xl me-2"></iconify-icon>  My Profile
            </a>
        </li>
        <li>
            <div class="dropdown-divider mb-0"></div>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center px-0 py-8 hover-bg-transparent hover-text-danger" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <iconify-icon icon="lucide:power" class="text-xl me-2"></iconify-icon>  Log Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
    </div>
    </div>
    </div>
    </div>
</div><!-- Profile dropdown end -->
<!--end header -->

