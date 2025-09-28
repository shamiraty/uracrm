@extends('layouts.app')

@section('content')
<style>
/* Style the tabs to look like arrows */
.arrow-tab {
    position: relative;
    padding-right: 30px; /* Space for the arrow */
    transition: all 0.3s ease;
}

.arrow-tab::after {
    content: '\2192'; /* Right arrow symbol */
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    transition: transform 0.3s ease;
}

/* Active tab style */
.arrow-tab.active {
    font-weight: bold;
    color: #fff;
    background-color: #007bff; /* Change background color for active tab */
}

.arrow-tab.active::after {
    transform: translateY(-50%) rotate(90deg); /* Rotate the arrow when active */
}

/* Hover effect */
.arrow-tab:hover {
    color: #007bff;
    background-color: #f1f1f1; /* Light background on hover */
}

/* Tab content */
.tab-content {
    padding: 20px;
    border: 1px solid #ddd;
    
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary">User Profile</h6>
                    
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
                <div class="card-body">
                    <!-- Tabs for Steps -->
                    <div class="nav nav-pills mb-4" id="stepTabs" role="tablist">
                        <a class="nav-link active arrow-tab" id="step1-tab" data-bs-toggle="pill" href="#step1" role="tab" aria-controls="step1" aria-selected="true">Personal Info</a>
                        <a class="nav-link arrow-tab" id="step2-tab" data-bs-toggle="pill" href="#step2" role="tab" aria-controls="step2" aria-selected="false">Location</a>
                        <a class="nav-link arrow-tab" id="step3-tab" data-bs-toggle="pill" href="#step3" role="tab" aria-controls="step3" aria-selected="false">Administrative Privileges</a>
                        <a class="nav-link arrow-tab" id="step4-tab" data-bs-toggle="pill" href="#step4" role="tab" aria-controls="step4" aria-selected="false">Password</a>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <!-- Tab content for Steps -->
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf

                        <div class="tab-content mt-3" id="stepTabsContent">
                            <!-- Step 1: Personal Information (List) -->
                            <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                <h5 class="text-primary">Personal Information</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Name</strong>: {{ $user->name }}</li>
                                    <li class="list-group-item"><strong>Email</strong>: {{ $user->email }}</li>
                                    <li class="list-group-item"><strong>Phone Number</strong>: {{ $user->phone_number }}</li>
                                    <li class="list-group-item"><strong>Rank</strong>: {{ $user->rank->name ?? 'N/A' }}</li>
                                    <li class="list-group-item"><strong>Designation</strong>: {{ $user->designation ?? 'N/A' }}</li>
                                </ul>
                            </div>

                            <!-- Step 2: Location Information (List) -->
                            <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                <h5 class="text-primary">Location Information</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Region</strong>: {{ $user->region->name ?? 'N/A' }}</li>
                                    <li class="list-group-item"><strong>District</strong>: {{ $user->district->name ?? 'N/A' }}</li>
                                    <li class="list-group-item"><strong>Department</strong>: {{ $user->department->name ?? 'N/A' }}</li>
                                </ul>
                            </div>

                            <!-- Step 3: Administrative Privileges (List) -->
                            <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                <h5 class="text-primary">Administrative Privileges</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Role</strong>: {{ $user->role->name ?? 'N/A' }}</li>
                                    <li class="list-group-item"><strong>Status</strong>: {{ $user->status ?? 'N/A' }}</li>
                                </ul>
                            </div>

                            <!-- Step 4: Change Password (Form) -->
                            <div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
                                <h5 class="text-primary">Change Password</h5>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                                    <input id="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" required>
                                    @error('new_password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-sm">{{ __('Update Password') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
