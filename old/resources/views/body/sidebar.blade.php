

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
      <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div class="">
      <a href="{{ route('dashboard') }}" class="sidebar-logo">
       {{-- <img src="{{ asset('assets/images/uralogo.pn') }}" alt="CRM " class="light-logo">
        <img src="{{ asset('assets/images/uralogo-light.pn') }}" alt="CRM" class="dark-logo">
        <img src="{{ asset('assets/images/uralogo-icon.pn') }}" alt="CRM" class="logo-icon">--}}
        <h6 class="fw-bold">URA-CRM</h6>
      </a>
    </div>
    <div class="sidebar-menu-area">
      <ul class="sidebar-menu" id="sidebar-menu">
        <li>
          <a href="{{ route('dashboard') }}">
            <iconify-icon icon="bx:bx-home-alt" class="menu-icon"></iconify-icon>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('enquiries.my') }}">
          <iconify-icon icon="mdi:message-text-outline" class="menu-icon" style=""></iconify-icon>
            <span>My Enquiries</span>
          </a>
        </li>

        @if(auth()->user()->hasRole(['Registrar','registrar_hq', 'general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
        <li class="dropdown">
          <a href="javascript:void(0)" class="text-l">
            <iconify-icon icon="bx:bx-category" class="menu-icon"></iconify-icon>
            <span>Enquiries Management</span>
          </a>
        
          <ul class="sidebar-submenu">
        
            <li>
              <a href="{{ route('enquiries.create') }}"style="color:#2980b9;">
                <iconify-icon icon="mdi:arrow-right"></iconify-icon> New Enquiry
              </a>
            </li>
            <li>
              <a href="{{ route('enquiries.index') }}"style="color:#2980b9;">
                <iconify-icon icon="mdi:arrow-right"></iconify-icon> All Enquiries
              </a>
            </li>  
            @if(!auth()->user()->hasRole('Registrar'))
            <li>
  <a href="{{ route('enquiries.index', ['type' => 'loan_application']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Loan Applications
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'share_enquiry']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Share Enquiries
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'retirement']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Retirement Enquiries
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'deduction_add']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Deduction Adjustment
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'refund']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Refund Enquiries
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'withdraw_savings']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Withdraw Savings
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'join_membership']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Join Membership
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'unjoin_membership']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Unjoin Membership
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'benefit_from_disasters']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Benefit from Disasters
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'sick_for_30_days']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Sick 30 Days
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'condolences']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Condolences
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'injured_at_work']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Work Injury
  </a>
</li>
<li>
  <a href="{{ route('enquiries.index', ['type' => 'ura_mobile']) }}">
    <iconify-icon icon="mdi:arrow-right"></iconify-icon> Ura Mobile
  </a>
</li>


            @endif
          </ul>
        </li>
        @endif

        @if(auth()->user()->hasAnyRole(['loanofficer', 'general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
        <li class="dropdown">
          <a href="javascript:void(0)"class="text-sm">
            <iconify-icon icon="bx:bx-category" class="menu-icon"></iconify-icon>
            <span>Loan Management</span>
          </a>
          <ul class="sidebar-submenu">

          
          <li>
              <a href="{{ route('deductions.salary.loans') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Salary Loans
              </a>
            </li>

            <li>
              <a href="{{ route('deductions.variance') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Repayment Tracing
              </a>
            </li>

      
            <li>
              <a href="{{ route('mortgage.form') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Calculator
              </a>
            </li>
            <li>
              <a href="{{ route('members.processedLoans') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Pending Loan
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Rejected Loan
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Payed Loans
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Approved Loans
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Interest
              </a>
            </li>
            <li>
              <a href="{{ route('members.uploadForm') }}">
                <iconify-icon icon="bx:bx-upload"></iconify-icon> Upload Loan Application
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Processed Loans
              </a>
            </li>
          </ul>
        </li>
        @endif

        @if(auth()->user()->hasAnyRole(['accountant', 'general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-bookmark-heart" class="menu-icon"></iconify-icon>
            <span>Payments Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li class="{{ request()->is('payments/refund') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'refund']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Refund
              </a>
            </li>
            <li class="{{ request()->is('payments/retirement') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'retirement']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Retirement
              </a>
            </li>
            <li class="{{ request()->is('payments/withdraw_savings') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'withdraw_savings']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Withdraw Savings
              </a>
            </li>
            <li class="{{ request()->is('payments/benefit_from_disasters') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'benefit_from_disasters']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Benefit from Disasters
              </a>
            </li>
            <li class="{{ request()->is('payments/deduction_add') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'deduction_add']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Deduction Adjustment
              </a>
            </li>
            <li class="{{ request()->is('payments/share_enquiry') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'share_enquiry']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Share
              </a>
            </li>
            <li class="{{ request()->is('payments/withdraw_deposit') ? 'active' : '' }}">
              <a href="{{ route('payments.type', ['type' => 'withdraw_deposit']) }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Withdraw Deposit
              </a>
            </li>
          </ul>
        </li>
        @endif

        @if(auth()->user()->hasRole(['general_manager', 'assistant_general_manager', 'superadmin','system_admin']))
        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-repeat" class="menu-icon"></iconify-icon>
            <span>Member Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('uramembers.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> New Member
              </a>
            </li>
            <li>
              <a href="{{ route('deductions.members.list') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Members
              </a>
            </li>
            
            <li>
              <a href="{{ route('deductions.contributions.handle') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Members Contributions
              </a>
            </li>

            <li>
              <a href="{{ route('deduction667.differences.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Contributions Changes
              </a>
            </li>

            <li>
              <a href="{{ route('deductions.contribution_analysis') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Contributions Analysis
              </a>
            </li>
            
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Unjoin Member
              </a>
            </li>
            <li>
              <a href="#">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Retired Member
              </a>
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-shield" class="menu-icon"></iconify-icon>
            <span>Access Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('roles.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Roles
              </a>
            </li>
            <li>
              <a href="{{ route('permissions.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Permissions
              </a>
            </li>
            <li>
              <a href="{{ route('ranks.create') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Ranks
              </a>
            </li>
            <li>
              <a href="{{ route('users.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Users
              </a>
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-buildings" class="menu-icon"></iconify-icon>
            <span>Branch Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('branches.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Branches
              </a>
            </li>
        
            <li>
              <a href="{{ route('departments.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Departments
              </a>
            </li>
			
			 <li>
              <a href="{{ route('commands.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Commands
              </a>
            </li>
          
            <li>
              <a href="{{ route('payroll.showUpload') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Import Payroll
              </a>
            </li>
          </ul>
        </li>

        
        @endif

        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-archive" class="menu-icon"></iconify-icon>
            <span>Document Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('files.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> List Files
              </a>
            </li>
            <li>
              <a href="{{ route('files.create') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Create File
              </a>
            </li>
            <li>
              <a href="{{ route('file_series.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> List File Series
              </a>
            </li>
            <li>
              <a href="{{ route('file_series.create') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Create File Series
              </a>
            </li>
            <li>
              <a href="{{ route('keywords.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> List Keywords
              </a>
            </li>
            <li>
              <a href="{{ route('keywords.create') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Create Keyword
              </a>
            </li>
            <li>
              <a href="{{ route('keywords.showImportForm') }}">
                <iconify-icon icon="bx:bx-import"></iconify-icon> Import Keywords
              </a>
            </li>
          </ul>
        </li>


         <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-archive" class="menu-icon"></iconify-icon>
            <span>Payroll Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('deductions.import.form') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Import Deductions
              </a>
            </li>
         
          </ul>
        </li>



        
         <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-archive" class="menu-icon"></iconify-icon>
            <span>Member ID Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('card-details.index') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon>Member Cards
              </a>
            </li>
         
          </ul>
        </li>
        
         <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="bx:bx-archive" class="menu-icon"></iconify-icon>
            <span>Campain Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="{{ route('bulk.sms.form') }}">
              <iconify-icon icon="mdi:arrow-right"></iconify-icon> Send Bulk sms
              </a>
            </li>
         
          </ul>
        </li>
      </ul>
    </div>
</aside>

