@extends('layouts.app')
@section('content')
<!-- Add hover effects for buttons -->
<style>
    .btn:hover {
        background-color: #007bff; /* Change the background color on hover */
        color: #fff; /* Change text color on hover */
        transform: scale(1.05); /* Slightly increase the button size */
        transition: transform 0.3s ease, background-color 0.3s ease; /* Smooth hover effect */
    }

    .btn.disabled, .btn:disabled {
        background-color: #6c757d; /* Gray background when disabled */
        color: #fff; /* White text when disabled */
    }
/* Style all select elements */
select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    border: 1px solid #ced4da;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M0 0l2 2 2-2z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 8px 10px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    overflow: hidden; /* Important for consistent hover effect */
}

/* Style the dropdown list (options container) */
select::-webkit-scrollbar {
    width: 8px; /* Adjust scrollbar width as needed */
}

select::-webkit-scrollbar-track {
    background: #f1f1f1; /* Scrollbar track background */
}

select::-webkit-scrollbar-thumb {
    background: #888; /* Scrollbar thumb color */
    border-radius: 4px; /* Rounded corners for the thumb */
}


/* Hover effect on options */
select option:hover {
    background-color: #e9ecef;
}

/* Active/selected option style */
select option:checked {
    background-color: #007bff;
    color: white;
}

/* Focus style for select */
select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Fix for Firefox to show hover on scroll */
select:-moz-focusring {
    color: transparent;
    text-shadow: 0 0 0 #000;
}

</style>
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0"> Enquiries</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="{{ route('enquiries.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          all Enquiries
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">{{ $type ? ucfirst(str_replace('_', ' ', $type)) . ' Enquiries' : 'All Enquiries' }}</li>
    </ul>
  </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center">
  

    {{-- EXPORT ALL OLD
    <!-- Right export record to CSV button -->
  <h5 class="card-title mb-0">
    <a href="{{ 
            match($type) {
                'loan_application' => route('exportLoanApplication'),
                'refund' => route('exportRefund'),
                'share_enquiry' => route('exportShare'),
                'retirement' => route('exportRetirement'),
                'deduction_add' => route('deductions.export'),
                'withdraw_savings' => route('withdrawalExport'),
                'withdraw_deposit' => route('withdrawalExport'),
                'unjoin_membership' => route('exportEnquiriesUnjoinMembership'),
                'benefit_from_disasters' => route('residential_disasters'),
                default => '#',  // Default to a fallback route or current page if no match
            }
        }}" 
        id="export-btn" 
        class="btn btn-primary radius-30 mt-2 mt-lg-0 d-flex align-items-center gap-2" 
        onclick="startExport()">
        <span id="export-text">Export {{ $type ? ucfirst(str_replace('_', ' ', $type)) : 'CSV' }}</span>
        <iconify-icon icon="mdi:file-excel" class="text-xl mr-4"></iconify-icon>
    </a>
</h5>
--}}

<div class="accordion mb-4 w-100" id="summaryAccordion">
    <div class="accordion-item ">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
               Export CSV Data
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#summaryAccordion">
            <div class="accordion-body">

<h5 class="card-title mb-0">
    <form method="GET" 
          action="{{ 
              match($type) {
                  'loan_application' => route('exportLoanApplication'),
                  'refund' => route('exportRefund'),
                  'share_enquiry' => route('exportShare'),
                  'retirement' => route('exportRetirement'),
                  'deduction_add' => route('deductions.export'),
                  'withdraw_savings' => route('withdrawalExport'),
                  'withdraw_deposit' => route('withdrawalExport'),
                  'unjoin_membership' => route('exportEnquiriesUnjoinMembership'),
                  'benefit_from_disasters' => route('residential_disasters'),
                  'sick_for_30_days' => route('exportSickLeave'),
                  'condolences' => route('exportCondolences'),
                  'injured_at_work' => route('injuryExport'),
                  'join_membership' => route('membershipExport'),
                  default => route('allEnquiriesExport'), // Default to a fallback route or current page if no match
              }
          }}" 
          id="export-form">
        <div class="row">


  <!-- Left add record button -->
  <div class="col-sm">
  <h5 class="card-title mb-0">
        <a href="{{ route('enquiries.create', ['type' => $type ?? null]) }}" class="btn btn-primary btn-sm radius-30 mt-2 mt-lg-0 d-flex align-items-center gap-2">
            <span>Add {{ $type ? ucfirst(str_replace('_', ' ', $type)) : 'Enquiry' }}</span>
            <iconify-icon icon="mingcute:add-circle-fill" class="text-xl"></iconify-icon>
        </a>
    </h5>
    </div>


            <div class="col-sm">
                <label for="start_date"><small class="text-primary">Start Date</small></label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="col-sm">
                <label for="end_date"><small class="text-primary">End Date</small></label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="col-sm">
                <label for="frequency"><small class="text-primary">Frequency</small></label>
                <select name="frequency" id="frequency" class="form-control">
                    <option value="">All</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="quarterly_q1">Quarterly (Q1: Jan - Mar)</option>
<option value="quarterly_q2">Quarterly (Q2: Apr - Jun)</option>
<option value="quarterly_q3">Quarterly (Q3: Jul - Sep)</option>
<option value="quarterly_q4">Quarterly (Q4: Oct - Dec)</option>
                    <option value="half_year_1_6">Half Year (1-6 months)</option>
                    <option value="half_year_6_12">Half Year (6-12 months)</option>
                </select>
            </div>



            @if ($type === 'condolences')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Dependent</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="dependent_child">Dependent Child</option>
            <option value="dependent_spouse">Dependent Spouse</option>
        </select>
    </div>
@endif
      


 


@if ($type === 'loan_application')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Loan Status</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="processed">Processed</option>
        </select>
    </div>
@endif


@if ($type === 'refund')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Duration</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="1">1 month</option>
    <option value="2">2 months</option>
    <option value="3">3 months</option>
    <option value="4">4 months</option>
    <option value="5">5 months</option>
    <option value="6">6 months</option>
    <option value="7">7 months</option>
    <option value="8">8 months</option>
    <option value="9">9 months</option>
    <option value="10">10 months</option>
    <option value="11">11 months</option>
    <option value="12">12 months</option>
        </select>
    </div>
@endif



@if ($type === 'join_membership')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Member Type</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="police_officer">Police</option>
            <option value="civilian">Civilian</option>
        </select>
    </div>
@endif


@if ($type === 'benefit_from_disasters')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Cause</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="fire">Fire</option>
        <option value="hurricane">Hurricane</option>
        <option value="flood">Flood</option>
        <option value="earthquake">Earthquake</option>
        </select>
    </div>
@endif


@if ($type === 'unjoin_membership')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Category</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="normal">Normal</option>
            <option value="job_termination">Job Termination</option>
        </select>
    </div>
@endif

@if ($type === 'withdraw_savings')
    <div class="col-sm">
        <label for="status"><small class="text-primary">Category</small></label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="savings">Savings</option>
            <option value="deposit">Deposit</option>
        </select>
    </div>
@endif

<!-- Default Type -->
@if ($type === 'default' || !$type) 
            <div class="col-sm">
                <label for="status"><small class="text-primary">Type</small></label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
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
            </div>
        @endif

        <div class="col-sm">
    <label for="branch"><small class="text-primary">Branch</small></label>
    <select name="branch" id="branch" class="form-control" onchange="updateCommands()">
        <option value="">All</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-sm">
    <label for="command"><small class="text-primary">Command</small></label>
    <select name="command" id="command" class="form-control">
        <option value="">All</option>
        {{--
        @foreach($commands as $command)
            <option value="{{ $command->id }}">{{ $command->name }}</option>
        @endforeach
        --}}
    </select>
</div>

            <div class="col-sm d-flex align-items-end mt-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    Export 
                    {{ 
                        match($type) {
                            'loan_application' => 'Loan Application',
                            'refund' => 'Refund',
                            'share_enquiry' => 'Share Enquiry',
                            'retirement' => 'Retirement',
                            'deduction_add' => 'Deductions',
                            'withdraw_savings' => 'Withdraw Savings',
                            'withdraw_deposit' => 'Withdraw Deposit',
                            'unjoin_membership' => 'Unjoin Membership',
                            'benefit_from_disasters' => 'Benefit from Disasters',
                            'sick_for_30_days' => 'Sick Leave',
                            'condolences' => 'condolences',
                            'injured_at_work' => 'injury',
                            'join_membership' => 'New Membership',
                            

                            default => 'Data'
                        }
                    }}
                </button>
            </div>
        </div>

    </form>
</h5>
</div>
</div>
 </div>
 </div>


</div>
<div class="card-body">
<div class="table-responsive">
        <table class="table border-primary-table mb-0 mt-4 w-100" id="dataTable" data-page-length='10'>
          <thead>

                                <tr>
                                <th scope="col"> S/N</th>
                                <th scope="col">Date Received</th>
                                <th scope="col">Check Number</th>
                                <th scope="col">Full Name</th>
                                 <th scope="col">Account Number</th>
                                <th scope="col">Bank Name</th>
                                <th scope="col">Region</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Status</th>
                               {{-- <th scope="col">Assigned User</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enquiries as $enquiry)
                            <tr>
                                <!-- Serial Number -->
                                <td><div class=" d-flex align-items-center">

                                    {{ $loop->iteration }}
                                </div></td>

                                <!-- Existing Columns -->
                                <td><div class="d-flex align-items-center">{{ $enquiry->date_received }}</div></td>
                                <td><div class="d-flex align-items-center   text-primary-600">{{ $enquiry->check_number }}</div></td>
                                <td><div class="d-flex align-items-center text-lowercase">{{ucwords($enquiry->full_name) }}</div></td>
                                <td><div class="d-flex align-items-center">{{$enquiry->account_number }}</div></td>
                                <td><div class="d-flex align-items-center text-uppercase">{{ $enquiry->bank_name }}</div></td>
                                <td><div class="d-flex align-items-center">{{ ucwords($enquiry->region->name ?? 'No Region')}}</div></td>
                                 <td><div class="d-flex align-items-center">{{ $enquiry->phone }}</div></td>
                                <td>
                                    <span class="badge bg-{{ $enquiry->status == 'approved' ? 'success' : ($enquiry->status == 'rejected' ? 'danger' : ($enquiry->status == 'assigned' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($enquiry->status) }}
                                    </span>
                                </td>
                                {{--
                                <td>
                @if($enquiry->assignedUsers->isNotEmpty())
                 {{ $enquiry->assignedUsers->pluck('name')->join(', ') }}
                @else
                    Not Assigned
                @endif
            </td>
            --}}
           
                                <td>
                                    <div class="dropdown ms-auto">
                                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <!-- View Action -->
                                            <li><a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                                            <li><hr class="dropdown-divider"></li>

                                            <!-- Assign Action (triggers modal) -->
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</a></li>
                                            <li><hr class="dropdown-divider"></li>

                                            <!-- Edit Action -->
                                            <li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>

                                            <!-- Delete Action -->
                                            <li>
                                                <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
    <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $enquiry->check_number]) }}">
        <i class='bx bx-show font-22'></i> Loan Details
    </a>
</li>
<li><hr class="dropdown-divider"></li>
<li>
    <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $enquiry->check_number]) }}">
        <i class='bx bx-show font-22'></i> Contribution Details
    </a>
</li>


                                            
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table></div>
                </div>
            </div> <!-- Fixed the stray '<' here -->


    <!-- Modals for each enquiry to assign users -->
    @foreach($enquiries as $enquiry)
        @include('modals.assign_enquries')
    @endforeach

    <script>
    function startExport() {
        const exportText = document.getElementById('export-text');
        const exportBtn = document.getElementById('export-btn');
        
        // Change button text to 'Exporting...' and disable the button
        exportText.textContent = 'Exporting...'; // Set text to 'Exporting'
        exportBtn.classList.add('disabled'); // Add 'disabled' class to button
        exportBtn.setAttribute('disabled', true); // Disable the button

        // Simulate export process with a timeout (Replace with actual export logic)
        setTimeout(function() {
            // After export is finished, revert the button text and enable the button
            exportText.textContent = 'Export {{ $type ? ucfirst(str_replace('_', ' ', $type)) : 'Enquiry' }}'; // Revert to original text
            exportBtn.classList.remove('disabled'); // Remove 'disabled' class from button
            exportBtn.removeAttribute('disabled'); // Enable the button
        }, 3000); // Assume the export takes 3 seconds, adjust accordingly
    }
</script>

<script>
    function updateCommands() {
        const branchId = document.getElementById('branch').value;
        const commandSelect = document.getElementById('command');
        commandSelect.innerHTML = '<option value="">Select Command</option>'; // Clear existing options

        if (!branchId) return; // If no branch is selected, stop here

        // Assuming you pass the commands as a JSON array from the backend
        const allCommands = @json($commands);

        // Filter commands by the selected branch
        const filteredCommands = allCommands.filter(command => command.branch_id == branchId);

        // Populate the command dropdown
        filteredCommands.forEach(command => {
            let option = new Option(command.name, command.id);
            commandSelect.add(option);
        });
    }
</script>
@endsection