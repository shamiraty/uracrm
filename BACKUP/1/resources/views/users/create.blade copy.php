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
    background-color:rgb(143, 190, 240); /* Change background color for active tab */
}

.arrow-tab.active::after {
    transform: translateY(-50%) rotate(90deg); /* Rotate the arrow when active */
}

/* Hover effect */
.arrow-tab:hover {
    color:rgb(159, 199, 241);
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
    <div class="page-breadcrumb d-flex align-items-center"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-primary">Create User</h6>
                    <a href="{{ route('users.index') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <!-- Tabs for Steps -->
                    <div class="nav nav-pills mb-4" id="stepTabs" role="tablist">
    <a class="nav-link active arrow-tab" id="step1-tab" data-bs-toggle="pill" href="#step1" role="tab" aria-controls="step1" aria-selected="true">Step 1: Personal Info</a>
    <a class="nav-link arrow-tab" id="step2-tab" data-bs-toggle="pill" href="#step2" role="tab" aria-controls="step2" aria-selected="false">Step 2: Administrative Info</a>
    <a class="nav-link arrow-tab" id="step3-tab" data-bs-toggle="pill" href="#step3" role="tab" aria-controls="step3" aria-selected="false">Step 3: Location Info</a>
</div>
 
                    
                    <!-- Tab content for Steps -->
                    <form id="multistepForm" action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- Step 1: Personal Information -->
                        <div class="tab-content mt-3" id="stepTabsContent">
                            <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                <h5 class="text-primary">Step 1: Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control wizard-required" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control wizard-required" id="email" name="email" value="{{ old('email') }}" required>
                                        
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="phone_number">Phone Number:</label>
                                        <input type="text" class="form-control wizard-required" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required oninput="validatePhoneNumber()">
                                        <small id="phoneError" class="text-danger d-none">Phone number must start with 255 and be exactly 12 digits long.</small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="password">Password:</label>
                                        <input type="password" class="form-control wizard-required" id="password" name="password" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="confirm_password">Confirm Password:</label>
                                        <input type="password" class="form-control wizard-required" id="confirm_password" name="confirm_password" required oninput="validatePassword()">
                                        <small id="passwordError" class="text-danger d-none">Passwords must match and be at least 8 characters long.</small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="rank">Rank:</label>
                                        <select class="form-control wizard-required custom-select-dropdown" id="rank" name="rank" required>
                                            <option value="">Select Rank</option>
                                            @foreach($ranks as $rank)
                                                <option value="{{ $rank->id }}" {{ old('rank') == $rank->id ? 'selected' : '' }}>
                                                    {{ $rank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Administrative Information -->
                            <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                <h5 class="text-primary">Step 2: Administrative Information</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="status">Status:</label>
                                        <select class="form-control wizard-required" id="status" name="status" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="role">Role:</label>
                                        <select class="form-control wizard-required custom-select-dropdown" id="role" name="role" required>
                                            <option value="">Select Role</option>
                                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="designation">Designation:</label>
                                        <input type="text" class="form-control wizard-required" id="designation" name="designation" value="{{ old('designation') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Location Information -->
                            <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                <h5 class="text-primary">Step 3: Location Information</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="region_id">Region:</label>
                                        <select class="form-control wizard-required custom-select-dropdown" id="region_id" name="region_id" required onchange="updateDistricts()">
                                            <option value="">Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                                    {{ $region->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="district_id">District:</label>
                                        <select class="form-control wizard-required" id="district_id" name="district_id" required>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="department_id">Department:</label>
                                        <select class="form-control wizard-required custom-select-dropdown" id="department_id" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    

                                    <div class="col-md-4 mb-3">
    <label for="branch_id">Branch:</label>
    <select class="form-control wizard-required custom-select-dropdown" id="branch_id" name="branch_id" required>
        <option value="">Select Branch</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4 mb-3">
    <label for="command_id">Command:</label>
    <select class="form-control wizard-required custom-select-dropdown" id="command_id" name="command_id" required>
        <option value="">Select Command</option>
        @foreach($commands as $command)
            <option value="{{ $command->id }}" {{ old('command_id') == $command->id ? 'selected' : '' }}>
                {{ $command->name }}
            </option>
        @endforeach
    </select>
</div>



                          </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                            <button type="submit" class="btn btn-success d-none" id="submitBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 0;
    showStep(currentStep);

    function showStep(n) {
        const steps = document.querySelectorAll('.tab-pane');
        steps.forEach((step, index) => {
            step.classList.remove('show', 'active');
            if (index === n) step.classList.add('show', 'active');
        });

        document.getElementById('prevBtn').style.display = n === 0 ? 'none' : 'inline';
        document.getElementById('nextBtn').style.display = n === steps.length - 1 ? 'none' : 'inline';
        document.getElementById('submitBtn').classList.toggle('d-none', n !== steps.length - 1);

        updateTabs(n);
    }

    function nextPrev(n) {
        const steps = document.querySelectorAll('.tab-pane');
        if (n === 1 && !validateForm()) return false;
        currentStep += n;
        if (currentStep >= steps.length) return false;
        showStep(currentStep);
    }

    function updateTabs(n) {
        const tabs = document.querySelectorAll('.nav-link');
        tabs.forEach((tab, index) => {
            tab.classList.remove('active');
            if (index === n) tab.classList.add('active');
        });
    }

    function validateForm() {
        const phoneNumber = document.getElementById('phone_number').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        const isPhoneValid = validatePhoneNumber();
        const isPasswordValid = validatePassword();

        return isPhoneValid && isPasswordValid;
    }

    function validatePhoneNumber() {
        const phoneNumber = document.getElementById('phone_number').value;
        const phoneError = document.getElementById('phoneError');
        const regex = /^255\d{9}$/;

        if (!regex.test(phoneNumber)) {
            phoneError.classList.remove('d-none');
            return false;
        }

        phoneError.classList.add('d-none');
        return true;
    }

    function validatePassword() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const passwordError = document.getElementById('passwordError');

        if (password !== confirmPassword || password.length < 8) {
            passwordError.classList.remove('d-none');
            return false;
        }

        passwordError.classList.add('d-none');
        return true;
    }

    // JavaScript for dynamically updating districts
    function updateDistricts() {
        const regionId = document.getElementById('region_id').value;
        const districtSelect = document.getElementById('district_id');
        districtSelect.innerHTML = '<option value="">Select District</option>'; // Clear existing options

        if (!regionId) return; // If no region is selected, stop here

        // Populate districts based on selected region
        @json($regions).forEach(region => {
            if (region.id == regionId) {
                region.districts.forEach(district => {
                    let option = new Option(district.name, district.id);
                    districtSelect.add(option);
                });
            }
        });
    }
</script>

@endsection
