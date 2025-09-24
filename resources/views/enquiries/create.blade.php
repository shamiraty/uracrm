@extends('layouts.app')
@section('content')


<style>
    /* Bold text for error messages */
    .text-danger {
      color: red;
      font-weight: bold;
    }
    /* Bold text for success messages */
    .text-success {
      color: green;
      font-weight: bold;
    }
    /* Animation class that makes the message grow then shrink back */
    .grow {
      animation: growAnimation 0.5s ease-in-out;
    }
    @keyframes growAnimation {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); }
      100% { transform: scale(1); }
    }
  </style>
        <div class="card">
            <div class="card-body">
                <h6 class="mb-4 text-xl">Add Enquiry By Following Step</h6>
                <p class="text-neutral-500">Fill up your details and proceed next steps.</p>
                @if(session('message'))
    <div class="alert alert-{{ session('alert-type', 'success') }} bg-success-100 text-success-600 border-success-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between" role="alert">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
            {{ session('message') }}
        </div>
        <button class="remove-button text-success-600 text-xxl line-height-1"> 
            <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
        </button>
    </div>
@endif

        <div class="form-wizard">
            {{-- <form action="#" method="post"> --}}
                <form  method="POST" action="{{ route('enquiries.store') }}" enctype="multipart/form-data"id="myForm">
                    @csrf
                <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 my-32">
                    <ul class="list-unstyled form-wizard-list style-two">
                        <li class="form-wizard-list__item active">
                            <div class="form-wizard-list__line">
                                <span class="count">1</span>
                            </div>
                            <span class="text text-xs fw-semibold">Member Details </span>
                        </li>
                        <li class="form-wizard-list__item">
                            <div class="form-wizard-list__line">
                                <span class="count">2</span>
                            </div>
                            <span class="text text-xs fw-semibold">Enquiry Type</span>
                        </li>
                        <li class="form-wizard-list__item">
                            <div class="form-wizard-list__line">
                                <span class="count">3</span>
                            </div>
                            <span class="text text-xs fw-semibold">Address/Location</span>
                        </li>
                        <li class="form-wizard-list__item">
                            <div class="form-wizard-list__line">
                                <span class="count">4</span>
                            </div>
                            <span class="text text-xs fw-semibold">Attachment</span>
                        </li>
                    </ul>
                </div>

                <fieldset class="wizard-fieldset show">
                    <h6 class="text-md text-neutral-500">Personal Information</h6>
                    <div class="row gy-3">


                        <!-- Check Number -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Check Number</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" id="check_number" name="check_number" placeholder="Check Number">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Date Received -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Date Received</label>
                            <div class="position-relative">
                                <input type="date" class="form-control wizard-required" id="date_received" name="date_received">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Full Name</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" id="full_name" name="full_name" placeholder="Full Name">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Force Number -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Membershop No</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" id="force_no" name="force_no" placeholder="Member ID"value="None">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Bank Account Number -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Bank Account Number</label>
                            <div class="position-relative">
                                <input type="text" class="form-control wizard-required" id="account_number" name="account_number" placeholder="Bank Account Number">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>


                        <!-- Bank Name -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Bank Name</label>
                            <div class="position-relative">
                                <select class="form-select select2 wizard-required" id="bank_name" name="bank_name" required>
                                    <option value="">Select a Bank</option>
                                    <option value="NBC">NBC</option>
                                    <option value="NMB">NMB</option>
                                    <option value="CRDB">CRDB</option>
                                    <option value="Posta Bank">Posta Bank</option>
                                    <option value="Absa Bank">Absa Bank</option>
                                    <option value="DCB Commercial Bank">DCB Commercial Bank</option>
                                    <option value="Access Bank Tanzania">Access Bank Tanzania</option>
                                    <option value="Akiba Commercial Bank">Akiba Commercial Bank</option>
                                    <option value="Amana Bank">Amana Bank</option>
                                    <option value="Azania Bank">Azania Bank</option>
                                    <option value="Bank of Africa">Bank of Africa</option>
                                    <option value="Bank of Baroda">Bank of Baroda</option>
                                    <option value="Bank of India">Bank of India</option>
                                    <option value="Canara Bank">Canara Bank</option>
                                    <option value="Citibank Tanzania">Citibank Tanzania</option>
                                    <option value="Diamond Trust Bank">Diamond Trust Bank</option>
                                    <option value="Ecobank">Ecobank</option>
                                    <option value="Equity Bank">Equity Bank</option>
                                    <option value="Exim Bank">Exim Bank</option>
                                    <option value="GTBank Tanzania">GTBank Tanzania</option>
                                    <option value="Habib African Bank">Habib African Bank</option>
                                    <option value="I&M Bank">I&M Bank</option>
                                    <option value="ICBank">ICBank</option>
                                    <option value="KCB Bank">KCB Bank</option>
                                    <option value="Letshego Bank">Letshego Bank</option>
                                    <option value="Mkombozi Commercial Bank">Mkombozi Commercial Bank</option>
                                    <option value="Mwalimu Commercial Bank">Mwalimu Commercial Bank</option>
                                    <option value="NCBA Bank">NCBA Bank</option>
                                    <option value="People's Bank of Zanzibar">People's Bank of Zanzibar</option>
                                    <option value="Stanbic Bank Tanzania Limited">Stanbic Bank Tanzania Limited</option>
                                    <option value="Standard Chartered Bank">Standard Chartered Bank</option>
                                    <option value="Tanzania Commercial Bank">Tanzania Commercial Bank</option>
                                    <option value="UBA Bank">UBA Bank</option>
                                    <option value="Mwanga Hakika Bank">Mwanga Hakika Bank</option>
                                </select>
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Basic Salary -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Basic Salary</label>
                            <div class="position-relative">
                                <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Enter Basic Salary" step="0.01">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Allowances -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Allowances</label>
                            <div class="position-relative">
                                <input type="number" class="form-control" id="allowances" name="allowances" placeholder="Enter Allowances" step="0.01">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                        <!-- Take Home Pay -->
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Take Home Pay</label>
                            <div class="position-relative">
                                <input type="number" class="form-control" id="take_home" name="take_home" placeholder="Enter Take Home Pay" step="0.01">
                                <div class="wizard-form-error"></div>
                            </div>
                        </div>

                            <!-- Phone Number Input -->
        <div class="col-12 col-lg-4">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="number" class="form-control wizard-required" id="phone" name="phone" placeholder="255*********" required>
            <small id="phoneHelp" class="form-text text-muted mt-2 text-primary"></small>
            <div class="wizard-form-error text-primary"></div>
        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-end">
                            <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>

                        </div>
                    </div>
                </fieldset>

                <fieldset class="wizard-fieldset " >
                    <h5 class="mb-1">Enquiry Type</h5>
                    <p class="mb-4">Select the enquiry type and provide additional information</p>
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="type" class="form-label">Enquiry Type</label>
                            <select name="type" id="type" class="form-select select2 wizard-required custom-select-dropdown" onchange="toggleFields(this.value)" required>
                                <option value="">Select Type</option>
                                <option value="loan_application">Loan Application</option>
                                <option value="refund">Refund</option>
                                <option value="share_enquiry">Share Enquiry</option>
                                <option value="retirement">Retirement</option>
                                <option value="deduction_add">Add Deduction of Savings</option>
                                <option value="withdraw_savings">Withdraw Savings</option>
                                <option value="withdraw_deposit">Withdraw Deposit</option>
                                <option value="unjoin_membership">Unjoin Membership</option>
                                <option value="ura_mobile">Ura Mobile</option>
                                <option value="sick_for_30_days">Sick for 30 Days</option>
                                <option value="condolences">Condolences</option>
                                <option value="injured_at_work">Injured at Work</option>
                                <option value="residential_disaster">Residential Disaster</option>
                                <option value="join_membership">Join Membership</option>
                            </select>
                            <div class="wizard-form-error"></div>
                        </div>
                        <!-- Additional fields based on Enquiry Type can be added here -->
                        <!-- Loan Application Fields -->
<div id="loanFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Loan Category:</label>
                <select name="loan_category"  class="form-select"  >
                    <option value="">Select Type</option>
                    <option value="salary_loan">Salary loan</option>
                    <option value="cash_loan">Cash loan</option>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Loan Type/Reason:</label>
                <select name="loan_type"  class="form-select custom-select-dropdown" >
                <option value="">Select Type</option>
<option value="business">Business</option>
<option value="education">Education</option>
<option value="medical">Medical</option>
<option value="vehicle">Vehicle</option>
<option value="agriculture">Agriculture</option>
<option value="emergency">Emergency</option>
<option value="wedding">Wedding</option>
<option value="vacation">Vacation</option>
<option value="funeral">Funeral</option>
<option value="furniture">Furniture</option>
<option value="construction">Construction</option>
<option value="other">Other</option>


                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Loan Amount:</label>
                <input type="number" step="0.01" name="loan_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Loan Duration:</label>
                <input type="number" name="loan_duration" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Share Enquiry Fields -->
<div id="shareFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Share Amount:</label>
                <input type="number" step="0.01" name="share_amount" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Retirement Fields -->
<div id="retirementFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Date of Retirement:</label>
                <input type="date" name="date_of_retirement" class="form-control">
            </div>
        </div>

    </div>
</div>

<!-- Deduction Fields -->
<div id="deductionFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>From Amount:</label>
                <input type="number" step="0.01" name="from_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>To Amount:</label>
                <input type="number" step="0.01" name="to_amount" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Refund Fields -->
<div id="refundFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Refund Amount:</label>
                <input type="number" step="0.01" name="refund_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Refund Duration:</label>
                <input type="number" name="refund_duration" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Savings Fields -->
<div id="withdrawSavingsFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Withdraw Savings Amount:</label>
                <input type="number" step="0.01"value="0" name="withdraw_saving_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Reason:</label>
                <input type="text" name="withdraw_saving_reason"value="None" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Deposit Fields -->
<div id="withdrawDepositFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Withdraw Deposit Amount:</label>
                <input type="number" step="0.01" name="withdraw_deposit_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Reason:</label>
                <input type="text" name="withdraw_deposit_reason" class="form-control">
            </div>
        </div>
    </div>
</div>

<!-- Unjoin Membership Fields -->
<div id="unjoinMembershipFields" style="display: none;">
    <div class="row mb-3">

        <div class="col-md-6">
            <div class="form-group">
                <label>Category:</label>
                <select name="category" class="form-control">
                <option value="">Select Category</option>
                    <option value="normal">Normal</option>
                    <option value="job_termination">Job Termination</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Benefit from Disasters Fields -->
<div id="benefitFields" style="display: none;">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>Benefit Amount:</label>
                <input type="number" step="0.01" name="benefit_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Benefit Description:</label>
                <textarea name="benefit_description" class="form-control"></textarea>
            </div>
        </div>

    </div>
</div>

<!--Added fields ---------------------------------->
<div id="dependent_member" style="display:none;">
    <label for="dependent_member_type">Beneficiary</label>
    <select name="dependent_member_type" id="dependent_member_type" class="form-select w-50">
    <option value="">Select Beneficiary</option>
    <option value="dependent_child">Dependent Child</option>
        <option value="dependent_spouse">Dependent Spouse</option>
        <option value="member">Member</option>
    </select>
    <p class="mt-2">Choose gender</p>
    <div class="form-check">
        <input class="form-check-input" type="radio" id="gender_male" name="gender" value="male">
        <label class="form-check-l abel" for="gender_male">Male</label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="radio" id="gender_female" name="gender" value="female">
        <label class="form-check-label" for="gender_female">Female</label>
    </div>
</div>

<div id="disaster_type" style="display:none;">
    <label for="disaster_type">Disaster Cause</label>
    <select name="disaster_type" id="disaster_type" class="form-select w-50">
        <option value="">Select Disaster Cause</option>
        <option value="fire">Fire</option>
        <option value="hurricane">Hurricane</option>
        <option value="flood">Flood</option>
        <option value="earthquake">Earthquake</option>
    </select>
</div>

<div id="membership_status" style="display:none;">
    <label for="membership_status">Membership Status</label>
    <select name="membership_status" id="membership_status" class="form-select w-50">
    <option value="">Select Membership Status</option>
    <option value="police_officer">Police Officer</option>
        <option value="civilian">Civilian</option>
    </select>
</div>


<div id="injured_at_work_div" style="display:none;">
    <label for="description">Description (max 100 words)</label>
    <textarea name="description" id="description" class="form-control w-50" rows="4" maxlength="600"></textarea>
    <small class="form-text text-muted">Please enter up to 100 words.</small>
</div>


<div id="sick_for_30_days_div" style="display:none;">
    <label for="startdate">Start Date</label>
    <input type="date" name="startdate" id="startdate" class="form-control w-50">
    <label for="enddate" class="mt-3">End Date</label>
    <input type="date" name="enddate" id="enddate" class="form-control w-50">
</div>


                    <div class="form-group d-flex align-items-center justify-content-end gap-8">
                        <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                        <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
                    </div></div>
                </fieldset>

               <!-- Step 4: Address/Location -->
<fieldset class="wizard-fieldset" >
    <h5 class="mb-1">Address/Location</h5>
    <p class="mb-4">Enter the location details</p>
    <div class="row g-3">
        <!-- Region Selection -->
        <div class="col-12 col-lg-6">
            <label for="region" class="form-label">Region</label>
            <select class="form-control select2 wizard-required custom-select-dropdown" id="region" name="region_id" required onchange="updateDistricts()">
                <option value="">Select Region</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
            <div class="wizard-form-error"></div>
        </div>

        <!-- District Selection -->
        <div class="col-12 col-lg-6">
            <label for="district" class="form-label">District</label>
            <select class="form-control select2 wizard-required" id="district" name="district_id" required>
                <option value="">Select District</option>
                <!-- Districts will be populated here via JavaScript -->
            </select>
            <div class="wizard-form-error"></div>
        </div>

<!-- Command Selection (Only for admin, registrar_hq, and superuser) -->
       @if(auth()->user()->hasRole(['admin', 'registrar_hq', 'superadmin']))
        <div class="col-12 col-lg-6">
            <label for="command_id" class="form-label">Command</label>
            <select class="form-control custom-select-dropdown" id="command_id" name="command_id">
                <option value="">Select Command</option>
                @foreach ($commands as $command)
                    <option value="{{ $command->id }}">{{ $command->name }}</option>
                @endforeach
            </select>
            <div class="wizard-form-error"></div>
        </div>
        @endif

    

        <!-- Navigation Buttons -->
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end gap-3">
                <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                        <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
            </div>
        </div>
    </div>
</fieldset>
<!-- Step 5: Attachment -->
<fieldset class="wizard-fieldset" >
    <h5 class="mb-1">Attachment</h5>
    <p class="mb-4">Upload the supportive document</p>
    <div class="row g-3">
        <!-- File Upload -->
        <div class="col-12 col-lg-6">
            <label for="fileUpload" class="form-label">Upload File (PDF only)</label>
            <input type="file" class="form-control wizard-required" name="file_path" id="fileUpload" onchange="previewFile()" accept="application/pdf">
        </div>

        <!-- File Selection Dropdown -->
        <div class="col-12 col-lg-6">
            <label for="file_id" class="form-label">Select File</label>
            <select class="form-control select2 wizard-required custom-select-dropdown" id="file_id" name="file_id">
                <option value="">Select File</option>
                @foreach ($files as $file)
                    <option value="{{ $file->id }}">{{ $file->reference_number }}-{{$file->file_subject}}</option>
                @endforeach
            </select>
        </div>

        <!-- PDF Preview -->
        <div class="col-12">
            <label for="preview" class="form-label">Preview</label>
            <div id="previewContainer" class="card bg-light">
                <div class="card-body">
                    <object id="pdfPreview" style="width: 100%; height: 400px;" type="application/pdf" class="bg-secondary"></object>
                </div>
            </div>
        </div>

        <!-- Navigation and Submit Buttons -->
        <div class="col-12">
            <div class="form-group d-flex align-items-center justify-content-end gap-8">
                <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
            </div>
        </div>
    </div>
</fieldset>


                <fieldset class="wizard-fieldset">
                    <div class="text-center mb-40">
                        <img src="assets/images/gif/success-img3.gif" alt="" class="gif-image mb-24">
                        <h6 class="text-md text-neutral-600">Congratulations </h6>
                        <p class="text-neutral-400 text-sm mb-0">Well done! You have successfully completed.</p>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-end gap-8">
                        <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                        {{-- <button type="button" class="form-wizard-submit btn btn-primary-600 px-32">Publish</button> --}}
                        <button type="submit" class="form-wizard-submit btn btn-primary-600 px-32"id="publish_form">Publish</button>

                    </div>
                </fieldset></form></div></div></div>



                        </div><!---end row-->


                      </div>

            </div>
        </div>
    </div>
</div>

    <script>
        // Toggle fields based on the enquiry type selected
        function toggleFields(type) {
            const fields = {
                sick_for_30_days: 'sick_for_30_days_div',
                condolences: 'dependent_member',
                residential_disaster:'disaster_type',
                join_membership: 'membership_status',
                injured_at_work:'injured_at_work_div',


                loan_application: 'loanFields',
                refund: 'refundFields',
                share_enquiry: 'shareFields',
                retirement: 'retirementFields',
                withdraw_savings: 'withdrawSavingsFields',
                withdraw_deposit: 'withdrawDepositFields',
                unjoin_membership: 'unjoinMembershipFields',
                benefit_from_disasters: 'benefitFields',
                deduction_add: 'deductionFields'
            };

            // Hide all additional fields
            Object.keys(fields).forEach(key => {
                const field = document.getElementById(fields[key]);
                if (field) {
                    field.style.display = 'none';
                }
            });

            // Show the relevant fields based on the selected type
            if (type && fields[type]) {
                const fieldToShow = document.getElementById(fields[type]);
                if (fieldToShow) {
                    fieldToShow.style.display = 'block';
                }
            }
        }

        function previewFile() {
            const file = document.getElementById('fileUpload').files[0];
            const previewContainer = document.getElementById('previewContainer');

            if (file && /application\/pdf/i.test(file.type)) {
                const obj = document.createElement('object');
                obj.type = 'application/pdf';
                obj.data = URL.createObjectURL(file);
                obj.style.width = '100%';
                obj.style.height = '500px'; // Adjust height as needed
                previewContainer.innerHTML = ''; // Clear previous content
                previewContainer.appendChild(obj);
            } else {
                previewContainer.innerHTML = '<p>Please upload a PDF file.</p>'; // Error message or fallback content
            }
        }

        document.addEventListener('DOMContentLoaded', function() {

            // Handle enquiry type selection to toggle additional fields
            const enquiryTypeSelect = document.getElementById('type');
            if (enquiryTypeSelect) {
                toggleFields(enquiryTypeSelect.value); // Initial check when the page loads
                enquiryTypeSelect.addEventListener('change', function() {
                    toggleFields(this.value); // Update fields when enquiry type changes
                });
            }

            // Set up the file upload preview listener
            const fileInput = document.getElementById('fileUpload');
            if (fileInput) {
                fileInput.addEventListener('change', previewFile);
            }
        });
    </script>



<script>
        $(document).ready(function() {
            $('#bank_name').select2({
                theme: 'bootstrap', // Apply Bootstrap theme to Select2
                placeholder: "Select a Bank",
                allowClear: true
            });
        });
</script>
<script>
        $(document).ready(function() {
            $('#region').select2({
                theme: 'bootstrap', // Apply Bootstrap theme to Select2
                placeholder: "Select a Region",
                allowClear: true
            });
        });
    </script>
<script>
        $(document).ready(function() {
            $('#district').select2({
                theme: 'bootstrap', // Apply Bootstrap theme to Select2
                placeholder: "Select a District",
                allowClear: true
            });
        });
    </script>
<script>
    function updateDistricts() {
        const regionId = document.getElementById('region').value;
        const districtSelect = document.getElementById('district');
        districtSelect.innerHTML = '<option value="">Select District</option>'; // Clear existing options

        if (!regionId) return; // If no region is selected, stop here

        // Assuming you have all districts preloaded in a variable `districts`
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <script>


    $(document).ready(function() {
    $('.select2').select2(); // Ensure Select2 is initialized

    var debounceTimer;
    $('#check_number').on('keyup', function() {
        var checkNumber = $(this).val();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            if (checkNumber.length > 3) { // Assuming check number has a meaningful length to start search
                $.ajax({
                    url: '{{ url("enquiries/fetch-payroll") }}/' + checkNumber,
                    type: "GET",
                    success: function(data) {
                        if (data && data.bank_name) {
                            $('#full_name').val(data.full_name);
                            $('#account_number').val(data.account_number);
                            // Make sure the value matches exactly an option in the select
                            $('#bank_name').val(data.bank_name).trigger('change');
                            $('#basic_salary').val(data.basic_salary);
                            $('#allowances').val(data.allowance);
                            $('#take_home').val(data.net_amount);
                        } else {
                            $('#full_name, #account_number, #basic_salary, #allowances, #take_home').val('');
                            $('#bank_name').val('').trigger('change'); // Reset Select2
                        }
                    },
                    error: function() {
                        alert('Failed to retrieve data');
                    }
                });
            } else {
                $('#full_name, #account_number, #basic_salary, #allowances, #take_home').val('');
                $('#bank_name').val('').trigger('change'); // Reset Select2
            }
        }, 500); // 500 ms debounce period
    });
});

</script>
<script>
    $(document).ready(function() {
        var debounceTimer;

        $('#check_number').on('keyup', function() {
            var checkNumber = $(this).val();

            // Show loading placeholder when typing starts
            $('#full_name').attr('placeholder', 'Loading...');
            $('#account_number').attr('placeholder', 'Loading...');
            $('#bank_name').attr('placeholder', 'Loading...');
            $('#basic_salary').attr('placeholder', 'Loading...');
            $('#allowances').attr('placeholder', 'Loading...');
            $('#take_home').attr('placeholder', 'Loading...');

            // Clear the previous debounce timer
            clearTimeout(debounceTimer);

            // Set a new debounce timer (wait for user to stop typing for 500ms)
            debounceTimer = setTimeout(function() {
                if (checkNumber.length > 3) { // If input is long enough

                    $.ajax({
                        url: '{{ url("enquiries/fetch-payroll") }}/' + checkNumber,
                        type: "GET",
                        success: function(data) {
                            if (data) {
                                $('#full_name').val(data.full_name).attr('placeholder', '');
                                $('#account_number').val(data.account_number).attr('placeholder', '');
                                $('#bank_name').val(data.bank_name).trigger('change').attr('placeholder', ''); // For Select2
                                $('#basic_salary').val(data.basic_salary).attr('placeholder', '');
                                $('#allowances').val(data.allowance).attr('placeholder', '');
                                $('#take_home').val(data.net_amount).attr('placeholder', '');
                            } else {
                                $('#full_name, #account_number, #bank_name, #basic_salary, #allowances, #take_home').val('').trigger('change');
                                $('#full_name, #account_number, #bank_name, #basic_salary, #allowances, #take_home').attr('placeholder', '');
                            }
                        },
                        error: function() {
                            alert('Failed to retrieve data');
                            $('#full_name, #account_number, #bank_name, #basic_salary, #allowances, #take_home').attr('placeholder', '');
                        }
                    });

                } else {
                    // Clear fields and loading indicators if the input is too short
                    $('#full_name, #account_number, #bank_name, #basic_salary, #allowances, #take_home').val('').trigger('change');
                    $('#full_name, #account_number, #bank_name, #basic_salary, #allowances, #take_home').attr('placeholder', '');
                }
            }, 500); // Debounce time, meaning the user must stop typing for 500ms
        });
    });
</script>
<script>
    const phoneInput = document.getElementById('phone');
    const phoneHelp = document.getElementById('phoneHelp');

    // Function to trigger the grow animation with a slight delay
    function animateMessage() {
      // Remove the 'grow' class
      phoneHelp.classList.remove('grow');
      
      // Use a timeout to force reflow and then re-add the class
      setTimeout(() => {
        phoneHelp.classList.add('grow');
      }, 50); // 50ms delay should be enough
    }

    // This function validates the phone number
    function validatePhone() {
      let value = phoneInput.value;
      
      // Remove all non-digit characters (no special characters allowed)
      value = value.replace(/\D/g, '');
      
      // If the number does not start with "255", prepend it.
      if (!value.startsWith('255')) {
        value = '255' + value;
      }
      
      // Ensure maximum length is 12 digits
      if (value.length > 12) {
        value = value.slice(0, 12);
      }
      
      phoneInput.value = value;
      
      // Perform validations and update the message accordingly
      if (value.length !== 12) {
        phoneHelp.textContent = 'Phone number must be exactly 12 digits.';
        phoneHelp.className = 'form-text mt-2 text-danger';
      } else if (value.charAt(0) === '0') {
        phoneHelp.textContent = 'Phone number must not start with 0.';
        phoneHelp.className = 'form-text mt-2 text-danger';
      } else {
        phoneHelp.textContent = 'Phone number looks good!';
        phoneHelp.className = 'form-text mt-2 text-success';
      }
      
      // Trigger the grow animation
      animateMessage();
    }

    // Attach the event listener for realtime validation
    phoneInput.addEventListener('input', validatePhone);
  </script>

<script>
    $(document).ready(function(){
        // When the form is submitted
        $('#myForm').on('submit', function(e){
            // Optionally, you can perform some validations here
            // If validation passes, update the button:
            var $btn = $('#publish_form');

            // Disable the button to prevent multiple clicks
            $btn.prop('disabled', true);

            // Change the button text and add a spinner (if desired)
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Publishing...');

            // The form will now be submitted
        });
    });
</script>


@endsection
