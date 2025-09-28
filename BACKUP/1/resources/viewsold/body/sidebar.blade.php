<ul class="metismenu" id="menu">
    <li>
        <a href="{{ route('dashboard') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-category"></i></div>
            <div class="menu-title">Enquiries Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
            <li><a href="{{ route('enquiries.my') }}"><i class='bx bx-folder'></i>My Enquiries</a></li>
            <li><a href="{{ route('enquiries.create') }}"><i class='bx bx-plus-circle'></i>New Enquiry</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'loan_application']) }}"><i class='bx bx-notepad'></i>Loan Applications</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'share_enquiry']) }}"><i class='bx bx-share-alt'></i>Share Enquiries</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'retirement']) }}"><i class='bx bx-user-check'></i>Retirement Enquiries</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'deduction_add']) }}"><i class='bx bx-plus'></i>Add Deductions</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'refund']) }}"><i class='bx bx-undo'></i>Refund Enquiries</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'withdraw_savings']) }}"><i class='bx bx-money-withdraw'></i>Withdraw Savings</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'withdraw_deposit']) }}"><i class='bx bx-wallet'></i>Withdraw Deposits</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'unjoin_membership']) }}"><i class='bx bx-user-x'></i>Unjoin Membership</a></li>
            <li><a href="{{ route('enquiries.index', ['type' => 'benefit_from_disasters']) }}"><i class='bx bx-support'></i>Benefit from Disasters</a></li>
        </ul>
    </li>

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-category"></i></div>
            <div class="menu-title">Loan Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
            <li><a href="{{ route('mortgage.form') }}"><i class='bx bx-calculator'></i>Calculator</a></li>
            <li><a href="{{ route('members.processedLoans') }}"><i class='bx bx-time'></i>Pending loan</a></li>
            <li><a href="app-file-manager.html"><i class='bx bx-block'></i>Rejected loan</a></li>
            <li><a href="app-contact-list.html"><i class='bx bx-money'></i>Payment loan</a></li>
            <li><a href="app-to-do.html"><i class='bx bx-check-circle'></i>Approved loan</a></li>
            <li><a href="app-invoice.html"><i class='bx bx-dollar-circle'></i>Interest</a></li>
            <li><a href="{{ route('members.uploadForm') }}"><i class='bx bx-upload'></i>Upload loan application</a></li>
            <li><a href="#"><i class='bx bx-check'></i>Processed Loans</a></li>
        </ul>
    </li>

    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
            <div class="menu-title">Payments Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
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

    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-repeat"></i></div>
            <div class="menu-title">Member Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
            <li><a href="content-grid-system.html"><i class='bx bx-user-plus'></i>New Member</a></li>
            <li><a href="content-typography.html"><i class='bx bx-group'></i>Members</a></li>
            <li><a href="content-text-utilities.html"><i class='bx bx-user-x'></i>Unjoin Member</a></li>
            <li><a href="content-text-utilities.html"><i class='bx bx-user-check'></i>Retired Member</a></li>
        </ul>
    </li>

    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bx bx-shield"></i></div>
            <div class="menu-title">Access Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
            <li><a href="{{ route('roles.index') }}"><i class='bx bx-user-pin'></i>Roles</a></li>
            <li><a href="{{ route('permissions.index') }}"><i class='bx bx-key'></i>Permissions</a></li>
            <li><a href="{{ route('users.index') }}"><i class='bx bx-user'></i>Users</a></li>
        </ul>
    </li>

    <!-- Branch management menu item -->
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-buildings"></i></div>
            <div class="menu-title">Branch Management</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
            <li><a href="{{ route('branches.index') }}"><i class='bx bx-list-ul'></i>List Branches</a></li>
            <li><a href="{{ route('branches.create') }}"><i class='bx bx-plus-circle'></i>Add Branch</a></li>
            <li><a href="{{ route('departments.index') }}"><i class='bx bx-layer'></i>Departments</a></li>
            <li><a href="{{ route('representatives.index') }}"><i class='bx bx-user-pin'></i>Representatives</a></li>
            <li><a href="{{ url('/posts/create') }}"><i class='bx bx-plus-circle'></i>Create Post</a></li>
        </ul>
    </li>

    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-bar-chart"></i></div>
            <div class="menu-title">Trends</div>
        </a>
        <ul style="display: none;"> <!-- Collapsed by default -->
        <li><a href="{{ route('trends') }}"><i class='bx bx-file-blank'></i> Registered Enquiries</a></li>
        <li><a href="{{ route('loan_trends') }}"><i class='bx bx-briefcase'></i> Loan Applications</a></li>


        </ul>
    </li>
</ul>

<!-- JavaScript for toggle functionality -->
<script>
    document.querySelectorAll('.has-arrow').forEach(item => {
        item.addEventListener('click', event => {
            const submenu = item.nextElementSibling;
            if (submenu.style.display === "none" || submenu.style.display === "") {
                submenu.style.display = "block"; // Expand the submenu
            } else {
                submenu.style.display = "none"; // Collapse the submenu
            }
        });
    });
</script>
