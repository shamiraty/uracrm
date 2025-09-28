

@extends('layouts.app')

@section('content')
<div id="stepper2" class="bs-stepper">
    <div class="card">
        <div class="card-header">
            <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between" role="tablist">
                <!-- Step 1: Member Details -->
                <div class="step" data-target="#test-nl-1">
                    <div class="step-trigger" role="tab" id="stepper2trigger1" aria-controls="test-nl-1">
                        <div class="bs-stepper-circle bg-primary text-white"><i class='bx bx-user fs-4'></i></div>
                        <div class="">
                            <h5 class="mb-0 steper-title">Member Details</h5>
                            <p class="mb-0 steper-sub-title">Enter Your Details</p>
                        </div>
                    </div>
                </div>

                <div class="bs-stepper-line"></div>
                <!-- Step 2: Enquiry Type -->
                <div class="step" data-target="#test-nl-2">
                    <div class="step-trigger" role="tab" id="stepper2trigger2" aria-controls="test-nl-2">
                        <div class="bs-stepper-circle bg-primary text-white"><i class='bx bx-file fs-4'></i></div>
                        <div class="">
                            <h5 class="mb-0 steper-title">Enquiry Type</h5>
                            <p class="mb-0 steper-sub-title">Select Enquiry Type</p>
                        </div>
                    </div>
                </div>
                <div class="bs-stepper-line"></div>

                <!-- Step 3: Address/Location -->
                <div class="step" data-target="#test-nl-3">
                    <div class="step-trigger" role="tab" id="stepper2trigger3" aria-controls="test-nl-3">
                        <div class="bs-stepper-circle bg-primary text-white"><i class='bx bxs-graduation fs-4'></i></div>
                        <div class="">
                            <h5 class="mb-0 steper-title">Address/Location</h5>
                            <p class="mb-0 steper-sub-title">Enter Address Details</p>
                        </div>
                    </div>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-nl-4">
                    <div class="step-trigger" role="tab" id="stepper2trigger4" aria-controls="test-nl-4">
                    <div class="bs-stepper-circle bg-primary text-white"><i class='bx bx-briefcase fs-4'></i></div>
                    <div class="">
                        <h5 class="mb-0 steper-title">Attachment</h5>
                        <p class="mb-0 steper-sub-title">Upload the supportive document</p>
                    </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="card-body">
        <div id="alertContainer"></div> <!-- Alert will be shown here -->
            <div class="bs-stepper-content">


                    <form id="wizard-form" method="POST" action="{{ route('enquiries.store') }}" enctype="multipart/form-data">
                        @csrf
                    <!-- Step 1: Member Details -->
                    <div id="test-nl-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper2trigger1">
                        <h5 class="mb-1 mt-3">Enter Member Details</h5>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" >
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="force_no" class="form-label">Force Number</label>
                                <input type="text" class="form-control" id="force_no" name="force_no" placeholder="Force Number">
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="check_number" class="form-label">Check Number</label>
                                <input type="text" class="form-control" id="check_number" name="check_number" placeholder="Check Number" >
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="account_number" class="form-label">Bank Account Number</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Bank Account Number" >
                            </div>
                            <div class="col-12 col-lg-6">
                                <!-- Include jQuery -->


                                <label for="bank_name" class="form-label">Bank Name</label>
                                <select class="form-select select2" id="bank_name" name="bank_name" >
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



                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="date_received" class="form-label">Date Received</label>
                                <input type="date" class="form-control" id="date_received" name="date_received" >
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="basic_salary" class="form-label">Basic Salary</label>
                                <input type="number" class="form-control" id="basic_salary" name="basic_salary" placeholder="Enter basic salary" step="0.01">
                            </div>

                            <!-- Allowances -->
                            <div class="col-12 col-lg-6">
                                <label for="allowances" class="form-label">Allowances</label>
                                <input type="number" class="form-control" id="allowances" name="allowances" placeholder="Enter allowances" step="0.01">
                            </div>

                            <!-- Take Home Pay -->
                            <div class="col-12 col-lg-6">
                                <label for="take_home" class="form-label">Take Home Pay</label>
                                <input type="number" class="form-control" id="take_home" name="take_home" placeholder="Enter take home pay" step="0.01">
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-primary btn-sm px-4 btn-next">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Enquiry Type -->
                    <div id="test-nl-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper2trigger2">
                        <h5 class="mb-1">Enquiry Type</h5>
                        <p class="mb-4">Select the enquiry type and provide additional information</p>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="type" class="form-label">Enquiry Type</label>
                                <select name="type" id="type" class="form-select" onchange="toggleFields(this.value)" >
                                    <option value="">Select Type</option>
                                    <option value="loan_application">Loan Application</option>
                                    <option value="refund">Refund</option>
                                    <option value="share_enquiry">Share Enquiry</option>
                                    <option value="retirement">Retirement</option>
                                    <option value="deduction_add">Add Deduction of Savings</option>
                                    <option value="withdraw_savings">Withdraw Savings</option>
                                    <option value="withdraw_deposit">Withdraw Deposit</option>
                                    <option value="join_membership">Join Membership</option>
                                    <option value="unjoin_membership">Unjoin Membership</option>
                                    <option value="benefit_from_disasters">Benefit from Disasters</option>
                                    <option value="ura_mobile">Ura Mobile</option>
                                </select>
                            </div>

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
                <select name="loan_type"  class="form-select" >
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
        <option value="other">Other</option> <

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
                <input type="number" step="0.01" name="withdraw_saving_amount" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Reason:</label>
                <input type="text" name="withdraw_saving_reason" class="form-control">
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
 <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4 btn-previous"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="button" class="btn btn-primary px-4 btn-next">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Address/Location -->
                    <div id="test-nl-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper2trigger3">
                        <h5 class="mb-1">Address/Location</h5>
                        <p class="mb-4">Enter the location details</p>
                        <div class="row g-3">

                            <div class="col-12 col-lg-6">
                                <label for="region" class="form-label">Region</label>
                                <select class="form-control select2" id="region" name="region" required onchange="updateDistricts()">
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="district" class="form-label">District</label>
                                <select class="form-control select2" id="district" name="district" required>
                                    <option value="">Select District</option>
                                    <!-- Districts will be populated here via JavaScript -->
                                </select>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="0656******" required>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4 btn-previous"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="button" class="btn btn-primary px-4 btn-next">Next<i class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="test-nl-4" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper2trigger4">
                        <h5 class="mb-1">Attachment</h5>
                        <p class="mb-4">Upload the supportive document</p>

                        <div class="row g-3">
   <!-- Column for file upload -->
   <div class="col-12 col-lg-6">
    <label for="fileUpload" class="form-label">Upload File (PDF only)</label>
    <input type="file" class="form-control" name="file_path" id="fileUpload" onchange="previewFile()" accept="application/pdf">
</div>

<!-- Column for PDF preview -->
<div class="col-12 col-lg-6">
    <label for="preview" class="form-label">Preview</label>
    <div id="previewContainer" class="card">
        <div class="card-body">
            <!-- PDF preview will be shown here -->
            <object id="pdfPreview" style="width: 100%; height: 400px;" type="application/pdf"></object>
        </div>
    </div>
</div>

                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4 btn-previous"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                    <button type="submit" class="btn btn-success px-4">Submit</button>
                                </div>
                            </div>
                        </div><!---end row-->


                      </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        // Toggle fields based on the enquiry type selected
        function toggleFields(type) {
            const fields = {
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
            const stepperElement = document.querySelector('#stepper2');
            const stepper = new Stepper(stepperElement, {
                linear: true,
                animation: true
            });

            // Event listeners for Next and Previous buttons
            document.querySelectorAll('.btn-next').forEach(button => {
                button.addEventListener('click', () => stepper.next());
            });

            document.querySelectorAll('.btn-previous').forEach(button => {
                button.addEventListener('click', () => stepper.previous());
            });

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


@endsection

