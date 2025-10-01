<script>
// Animated Counter Effect
function animateCounters() {
    const counters = document.querySelectorAll('.counter-animation');

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-value'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    });
}

// Magnetic Button Effect
document.querySelectorAll('.magnetic-btn').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        btn.style.setProperty('--x', `${x}px`);
        btn.style.setProperty('--y', `${y}px`);
    });
});

// Parallax Scroll Effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.blob-container');
    const particles = document.querySelector('.particle-container');

    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }

    if (particles) {
        particles.style.transform = `translateY(${scrolled * 0.3}px)`;
    }
});

// Show Quick Actions Menu
window.showQuickActions = function() {
    const menu = document.getElementById('quick-actions');
    const fab = document.querySelector('.fab');

    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        fab.style.transform = 'scale(1) rotate(0deg)';
    } else {
        menu.classList.add('show');
        fab.style.transform = 'scale(1.1) rotate(45deg)';
    }
}

// Show Toast Notification
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;

    const icon = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    }[type];

    toast.innerHTML = `
        <i class="fas ${icon} fs-4"></i>
        <div>
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
            <p class="mb-0 text-muted small">${message}</p>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Show Loading Overlay
function showLoading() {
    document.getElementById('loading-overlay').classList.add('show');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.remove('show');
}

// Initialize Animations on Load
window.addEventListener('load', () => {
    animateCounters();

    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card').forEach(card => {
        observer.observe(card);
    });
});

// Liquid Button Mouse Tracking
document.querySelectorAll('.liquid-btn').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        btn.style.setProperty('--x', `${x}px`);
        btn.style.setProperty('--y', `${y}px`);
    });
});

// Add Ripple Effect
function createRipple(event) {
    const button = event.currentTarget;
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple-effect');

    button.appendChild(ripple);

    setTimeout(() => ripple.remove(), 600);
}

document.querySelectorAll('.ripple').forEach(btn => {
    btn.addEventListener('click', createRipple);
});
// Note: Modal population is handled by the event listener in DOMContentLoaded
// These functions are kept for backwards compatibility if needed

// Legacy function for backwards compatibility
function populateLoanModal(button) {
    console.log('populateLoanModal called (legacy function)');
    // This is now handled by the modal show.bs.modal event listener
}

// Legacy function for backwards compatibility
function showLoanDetails(loanData) {
    console.log('showLoanDetails called (legacy function)');
    // This is now handled by the modal show.bs.modal event listener
}

// Helper function to safely set element text
function setElementText(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        // Ensure we always display something, even if value is null/undefined/empty
        const displayValue = value !== null && value !== undefined && value !== '' ? value : 'N/A';
        element.textContent = displayValue;
        console.log(`Set ${elementId} to: ${displayValue}`);
    } else {
        console.warn(`Element with id '${elementId}' not found`);
    }
}

// Helper function to safely set element HTML
function setElementHTML(elementId, html) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = html || 'N/A';
    } else {
        console.warn(`Element with id '${elementId}' not found`);
    }
}

// Helper function to format currency
function formatCurrency(amount) {
    if (!amount || amount === null || amount === undefined || amount === '' || isNaN(amount)) {
        return 'N/A';
    }
    return parseFloat(amount).toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' TZS';
}

// Helper function to format dates
function formatDateValue(dateStr) {
    if (!dateStr || dateStr === null || dateStr === undefined || dateStr === '') {
        return 'N/A';
    }
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) {
            return 'N/A';
        }
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } catch (e) {
        return dateStr || 'N/A';
    }
}

// Helper function to get approval badge HTML
function getApprovalBadgeHTML(approval) {
    switch(approval) {
        case 'APPROVED':
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>';
        case 'REJECTED':
            return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rejected</span>';
        case 'CANCELLED':
            return '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Cancelled</span>';
        case 'PENDING':
        case null:
        case undefined:
        case '':
            return '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>';
        default:
            return '<span class="badge bg-secondary">' + approval + '</span>';
    }
}

// Helper function to get status badge HTML
function getStatusBadgeHTML(status) {
    if (!status) {
        return '<span class="badge bg-secondary">New</span>';
    }

    switch(status.toLowerCase()) {
        case 'disbursed':
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Disbursed</span>';
        case 'disbursement_pending':
            return '<span class="badge bg-info"><i class="fas fa-hourglass-half me-1"></i>NMB Processing</span>';
        case 'disbursement_failed':
            return '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Failed</span>';
        case 'full_settled':
            return '<span class="badge bg-dark"><i class="fas fa-handshake me-1"></i>Settled</span>';
        default:
            return '<span class="badge bg-secondary">' + status + '</span>';
    }
}

// Test function to manually open modal
function testModal() {
    const modalElement = document.getElementById('loanDetailsModal');
    if (modalElement) {
        console.log('Modal element found');
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap is loaded');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('Bootstrap is not loaded!');
            // Fallback: try jQuery modal
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('Using jQuery modal');
                $('#loanDetailsModal').modal('show');
            } else {
                console.error('Neither Bootstrap nor jQuery modal available');
            }
        }
    } else {
        console.error('Modal element not found');
    }
}

// Global variable to store current KPI status
let currentKPIStatus = null;
let currentKPIData = null;

// Function to show KPI details with period fallback
window.showKPIDetails = function(status) {
    currentKPIStatus = status;
    const modal = new bootstrap.Modal(document.getElementById('kpiDetailModal'));
    const modalTitle = document.getElementById('kpiDetailModalLabel');
    const contentDiv = document.getElementById('kpiDetailContent');
    const filterControls = document.getElementById('kpiFilterControls');
    const spinner = document.getElementById('kpiSpinner');
    
    // Reset filter to auto
    document.getElementById('kpiPeriodFilter').value = 'auto';
    document.getElementById('kpiStartDateDiv').style.display = 'none';
    document.getElementById('kpiEndDateDiv').style.display = 'none';
    
    // Show spinner, hide content and filters initially
    spinner.style.display = 'block';
    contentDiv.style.display = 'none';
    filterControls.style.display = 'none';
    
    // Show modal
    modal.show();
    
    // Fetch data with auto period detection
    fetchKPIData(status, 'auto');
}

// Function to update weekly counts
function updateWeeklyCounts(stats) {
    if (stats) {
        // Update footer counts
        document.getElementById('pending-weekly').textContent = stats.pending || 0;
        document.getElementById('approved-weekly').textContent = stats.approved || 0;
        document.getElementById('rejected-weekly').textContent = stats.rejected || 0;
        document.getElementById('cancelled-weekly').textContent = stats.cancelled || 0;
        document.getElementById('disbursed-weekly').textContent = stats.disbursed || 0;
        
        // Update weekly text in subtitle
        document.getElementById('pending-weekly-txt').textContent = `+${stats.pending || 0} this week`;
        document.getElementById('approved-weekly-txt').textContent = `+${stats.approved || 0} this week`;
        document.getElementById('rejected-weekly-txt').textContent = `${stats.rejected || 0} this week`;
        document.getElementById('cancelled-weekly-txt').textContent = `${stats.cancelled || 0} this week`;
        document.getElementById('disbursed-weekly-txt').textContent = `+${stats.disbursed || 0} this week`;
    }
}

// Function to format numbers
function formatNumber(num) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num || 0);
}

// Function to format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Function to get status badge
function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'approved': '<span class="badge bg-success">Approved</span>',
        'rejected': '<span class="badge bg-danger">Rejected</span>',
        'cancelled': '<span class="badge bg-secondary">Cancelled</span>',
        'disbursed': '<span class="badge bg-info">Disbursed</span>'
    };
    return badges[status?.toLowerCase()] || '<span class="badge bg-secondary">Unknown</span>';
}

// Function to export KPI data
function exportKPIData() {
    // Implementation for exporting data
    alert('Export functionality will be implemented soon');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Fetch initial weekly stats
    fetch('/api/loan-offers/weekly-stats?summary=true')
        .then(response => response.json())
        .then(data => {
            updateWeeklyCounts(data);
        })
        .catch(error => console.error('Error fetching weekly stats:', error));

    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded on DOMContentLoaded');
    } else {
        console.log('Bootstrap is available');
    }

    // Initialize tooltips
    initializeTooltips();

    // Initialize animations
    animateNumbers();

    // Setup event listeners
    setupEventListeners();

    // Initialize Bootstrap dropdowns (ensure they work)
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Setup modal event listener
    const loanModal = document.getElementById('loanDetailsModal');
    if (loanModal) {
        loanModal.addEventListener('show.bs.modal', function (event) {
            console.log('Modal is opening...');
            const button = event.relatedTarget; // Button that triggered the modal
            if (button) {
                console.log('Triggered by button:', button);

                // Get the loan data from the button
                const loanDataStr = button.getAttribute('data-loan');
                console.log('Raw loan data:', loanDataStr);

                let loan;
                try {
                    loan = JSON.parse(loanDataStr);
                    console.log('Parsed loan data:', loan);
                    console.log('Available loan fields:', Object.keys(loan));
                } catch (e) {
                    console.error('Error parsing loan data:', e);
                    alert('Error loading loan details');
                    return;
                }

                // Populate all modal fields
                // Personal Information
                const fullName = `${loan.first_name || ''} ${loan.middle_name || ''} ${loan.last_name || ''}`.trim();
                console.log('Setting personal info - Full Name:', fullName);
                setElementText('modal-full-name', fullName || 'N/A');
                setElementText('modal-check-number', loan.check_number || 'N/A');
                setElementText('modal-nin', loan.nin || 'N/A');
                setElementText('modal-sex', loan.sex === 'M' ? 'Male' : loan.sex === 'F' ? 'Female' : 'N/A');
                setElementText('modal-marital-status', loan.marital_status || 'N/A');

                // Contact Information
                console.log('Setting contact info...');
                setElementText('modal-mobile', loan.mobile_number || 'N/A');
                setElementText('modal-email', loan.email_address || 'N/A');
                setElementText('modal-telephone', loan.telephone_number || 'N/A');
                setElementText('modal-address', loan.physical_address || 'N/A');

                // Employment Information
                console.log('Setting employment info...');
                setElementText('modal-designation', loan.designation_name || 'N/A');
                setElementText('modal-vote-name', loan.vote_name || 'N/A');
                setElementText('modal-vote-code', loan.vote_code || 'N/A');
                setElementText('modal-terms', loan.terms_of_employment || 'N/A');
                setElementText('modal-employment-date', formatDateValue(loan.employment_date));
                setElementText('modal-confirmation-date', formatDateValue(loan.confirmation_date));
                setElementText('modal-retirement-date', formatDateValue(loan.retirement_date));

                // Loan Information
                console.log('Setting loan info...');
                setElementText('modal-app-number', loan.application_number || 'PENDING');
                setElementText('modal-loan-purpose', loan.loan_purpose || 'N/A');
                setElementText('modal-fsp-ref', loan.fsp_reference_number || 'N/A');
                setElementText('modal-loan-number', loan.loan_number || 'N/A');
                setElementText('modal-tenure', loan.tenure ? `${loan.tenure} months` : 'N/A');
                setElementText('modal-interest-rate', loan.interest_rate ? `${loan.interest_rate}%` : 'N/A');
                setElementText('modal-processing-fee', formatCurrency(loan.processing_fee));
                setElementText('modal-insurance', formatCurrency(loan.insurance));

                // Financial Information
                console.log('Setting financial info...');
                setElementText('modal-basic-salary', formatCurrency(loan.basic_salary));
                setElementText('modal-net-salary', formatCurrency(loan.net_salary));
                setElementText('modal-one-third', formatCurrency(loan.one_third_amount));
                setElementText('modal-deductions', formatCurrency(loan.total_employee_deduction));
                setElementText('modal-requested-amount', formatCurrency(loan.requested_amount));
                setElementText('modal-net-loan-amount', formatCurrency(loan.take_home_amount || loan.net_loan_amount));
                setElementText('modal-total-amount', formatCurrency(loan.total_amount_to_pay));
                setElementText('modal-monthly-deduction', formatCurrency(loan.desired_deductible_amount));
                setElementText('modal-other-charges', formatCurrency(loan.other_charges));

                // Banking Information
                console.log('Setting banking info...');
                setElementText('modal-account-number', loan.bank_account_number || 'N/A');
                setElementText('modal-swift-code', loan.swift_code || 'N/A');
                setElementText('modal-branch-name', loan.nearest_branch_name || 'N/A');
                setElementText('modal-branch-code', loan.nearest_branch_code || 'N/A');

                // Status Information
                console.log('Setting status info...');
                setElementHTML('modal-approval-status', getApprovalBadgeHTML(loan.approval));
                setElementHTML('modal-processing-status', getStatusBadgeHTML(loan.status));
                setElementText('modal-created-date', formatDateValue(loan.created_at));
                setElementText('modal-updated-date', formatDateValue(loan.updated_at));

                // Process Loan Tab - Set current status
                setElementHTML('process-current-approval', getApprovalBadgeHTML(loan.approval));
                setElementHTML('process-current-status', getStatusBadgeHTML(loan.status));
                document.getElementById('process-loan-id').value = loan.id;

                console.log('All modal fields populated');

                // Initialize Bootstrap tabs properly
                setTimeout(() => {
                    // Remove any existing active classes and reset tabs
                    document.querySelectorAll('.nav-tabs-ura .nav-link').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    // Set the first tab as active
                    const firstTab = document.querySelector('.nav-tabs-ura .nav-link[href="#personal-info"]');
                    const firstPane = document.getElementById('personal-info');

                    if (firstTab && firstPane) {
                        firstTab.classList.add('active');
                        firstPane.classList.add('show', 'active', 'fade');
                        console.log('Reset tabs to Personal Information');
                    }

                    // Re-initialize all tab click handlers
                    document.querySelectorAll('.nav-tabs-ura .nav-link').forEach(tabLink => {
                        tabLink.addEventListener('click', function(e) {
                            e.preventDefault();
                            const tab = new bootstrap.Tab(this);
                            tab.show();
                            console.log('Switched to tab:', this.getAttribute('href'));
                        });
                    });
                }, 100);
            }
        });
    } else {
        console.error('Loan modal element not found during initialization');
    }
});

// Initialize tooltips
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

// Animate numbers on load
function animateNumbers() {
    document.querySelectorAll('.stat-number').forEach(element => {
        const target = parseInt(element.getAttribute('data-value') || element.innerText);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.innerText = Math.floor(current).toLocaleString();
        }, 20);
    });
}

// Setup event listeners
function setupEventListeners() {
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.loan-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                updateRowSelection(cb);
            });
            updateBulkActions();
        });
    }

    // Individual checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(cb => {
        cb.addEventListener('change', function(e) {
            e.stopPropagation(); // Prevent row click when checkbox is clicked
            updateRowSelection(this);
            updateBulkActions();
        });
    });
    
    // Prevent row click on checkbox labels
    document.querySelectorAll('.modern-checkbox label').forEach(label => {
        label.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Add click event to clickable rows
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on checkbox, button, or link
            if (e.target.closest('.modern-checkbox') || 
                e.target.closest('.action-buttons') || 
                e.target.closest('button') || 
                e.target.closest('a')) {
                return;
            }
            
            // Get the loan data from the row
            const loanDataStr = this.getAttribute('data-loan');
            if (loanDataStr) {
                try {
                    const loan = JSON.parse(loanDataStr);
                    
                    // Create a temporary button to trigger the modal
                    const tempButton = document.createElement('button');
                    tempButton.setAttribute('data-loan', loanDataStr);
                    tempButton.setAttribute('data-bs-toggle', 'modal');
                    tempButton.setAttribute('data-bs-target', '#loanDetailsModal');
                    tempButton.style.display = 'none';
                    document.body.appendChild(tempButton);
                    
                    // Trigger the modal
                    const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
                    
                    // Set the relatedTarget for the modal event
                    const modalEvent = new Event('show.bs.modal');
                    modalEvent.relatedTarget = tempButton;
                    document.getElementById('loanDetailsModal').dispatchEvent(modalEvent);
                    
                    // Show the modal
                    modal.show();
                    
                    // Clean up the temporary button
                    setTimeout(() => tempButton.remove(), 100);
                } catch (error) {
                    console.error('Error parsing loan data:', error);
                }
            }
        });
    });
}

// Update row selection state
function updateRowSelection(checkbox) {
    const row = checkbox.closest('tr');
    if (checkbox.checked) {
        row.classList.add('selected');
    } else {
        row.classList.remove('selected');
    }
}

// Update bulk actions count
function updateBulkActions() {
    const selected = document.querySelectorAll('.loan-checkbox:checked').length;
    const selectedCount = document.getElementById('selected-count');
    if (selectedCount) {
        selectedCount.textContent = selected;
    }

    // Show/hide bulk actions
    if (selected > 0) {
        showBulkActionsBar(selected);
    } else {
        hideBulkActionsBar();
    }
}

// Show bulk actions bar
function showBulkActionsBar(count) {
    // Create or update bulk actions floating bar
    let bulkBar = document.getElementById('bulk-actions-bar');
    if (!bulkBar) {
        bulkBar = document.createElement('div');
        bulkBar.id = 'bulk-actions-bar';
        bulkBar.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 bg-white rounded-pill shadow-lg';
        bulkBar.style.zIndex = '1050';
        bulkBar.style.transition = 'all 0.3s ease';
        document.body.appendChild(bulkBar);
    }

    bulkBar.innerHTML = `
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary">${count} selected</span>
            <button class="btn btn-sm btn-success" onclick="bulkApprove()">
                <i class="fas fa-check me-1"></i>Approve
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                <i class="fas fa-times me-1"></i>Reject
            </button>
            <button class="btn btn-sm btn-secondary" onclick="bulkExport()">
                <i class="fas fa-file-export me-1"></i>Export
            </button>
            <button class="btn btn-sm btn-light" onclick="clearSelection()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    `;

    bulkBar.style.transform = 'translate(-50%, 0)';
}

// Hide bulk actions bar
function hideBulkActionsBar() {
    const bulkBar = document.getElementById('bulk-actions-bar');
    if (bulkBar) {
        bulkBar.style.transform = 'translate(-50%, 100px)';
        setTimeout(() => bulkBar.remove(), 300);
    }
}

// Clear selection
function clearSelection() {
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.loan-checkbox').forEach(cb => {
        cb.checked = false;
        updateRowSelection(cb);
    });
    updateBulkActions();
}

// Quick disburse - Original version
function quickDisburse(loanId) {
    console.log('Quick disburse called for loan:', loanId);

    // Check if Swal is available
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 library is not loaded. Using default confirm dialog.');
        if (confirm('Send this loan to NMB for processing?')) {
            processQuickDisburse(loanId);
        }
        return;
    }

    Swal.fire({
        title: 'Confirm Disbursement',
        text: 'Send this loan to NMB for processing?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Disburse',
        confirmButtonColor: '#1e8449',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Sending to NMB Bank',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX call - check if jQuery is available
            if (typeof $ === 'undefined' && typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded');
                // Use fetch API as fallback
                fetch(`{{ route('loan-offers.update', '') }}/${loanId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        status: 'SUBMITTED_FOR_DISBURSEMENT'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Loan sent to NMB',
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process disbursement'
                    });
                });
            } else {
                // Use jQuery AJAX
                $.ajax({
                    url: `{{ route('loan-offers.update', '') }}/${loanId}`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'SUBMITTED_FOR_DISBURSEMENT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Loan sent to NMB',
                            timer: 3000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process disbursement'
                        });
                    }
                });
            }
        }
    });
}

// Sync with ESS with progress indication
function refreshFromESS() {
    // Show sync modal
    const syncModal = Swal.fire({
        title: 'Syncing with ESS',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-sync fa-spin fa-3x text-primary"></i>
                </div>
                <p>Fetching latest loan applications from ESS...</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                         role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // Make actual sync request
    fetch('/loan-offers/sync', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        syncModal.close();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sync Complete',
                html: `
                    <div>
                        <p>${data.stats.message}</p>
                        <ul class="text-start">
                            <li>Total Applications: ${data.stats.total}</li>
                            <li>New Today: ${data.stats.new_today}</li>
                            <li>Pending: ${data.stats.pending}</li>
                        </ul>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Sync Failed',
                text: data.message || 'Failed to sync with ESS'
            });
        }
    })
    .catch(error => {
        syncModal.close();
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Unable to connect to ESS system'
        });
    });
}

// Sort table
function sortTable(column) {
    const url = new URL(window.location);
    const currentSort = url.searchParams.get('sort');
    const currentOrder = url.searchParams.get('order') || 'asc';

    if (currentSort === column) {
        url.searchParams.set('order', currentOrder === 'asc' ? 'desc' : 'asc');
    } else {
        url.searchParams.set('sort', column);
        url.searchParams.set('order', 'asc');
    }

    window.location.href = url.toString();
}

// Quick filters
// New improved filter functions
function applyQuickFilter(filter) {
    const form = document.getElementById('filter-form');
    const today = new Date();
    
    // Remove all active classes first
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.classList.remove('active');
    });
    
    // Add active class to clicked pill
    document.querySelector(`[data-filter="${filter}"]`)?.classList.add('active');

    switch(filter) {
        case 'today':
            form.querySelector('[name="date_from"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'week':
            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            form.querySelector('[name="date_from"]').value = weekAgo.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'month':
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            form.querySelector('[name="date_from"]').value = firstDayOfMonth.toISOString().split('T')[0];
            form.querySelector('[name="date_to"]').value = today.toISOString().split('T')[0];
            form.querySelector('[name="status"]').value = '';
            break;
        case 'pending':
            form.querySelector('[name="status"]').value = 'pending_approval';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
        case 'approved':
            form.querySelector('[name="status"]').value = 'approved';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
        case 'disbursed':
            form.querySelector('[name="status"]').value = 'disbursed';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            break;
    }

    form.submit();
}

function resetFilters() {
    const form = document.getElementById('filter-form');
    form.querySelector('[name="search"]').value = '';
    form.querySelector('[name="status"]').value = '';
    form.querySelector('[name="date_from"]').value = '';
    form.querySelector('[name="date_to"]').value = '';
    form.submit();
}

function clearSearchField() {
    const form = document.getElementById('filter-form');
    form.querySelector('[name="search"]').value = '';
    form.submit();
}

function saveFilterPreset() {
    const form = document.getElementById('filter-form');
    const preset = {
        search: form.querySelector('[name="search"]').value,
        status: form.querySelector('[name="status"]').value,
        date_from: form.querySelector('[name="date_from"]').value,
        date_to: form.querySelector('[name="date_to"]').value
    };
    
    localStorage.setItem('loanFilterPreset', JSON.stringify(preset));
    
    Swal.fire({
        icon: 'success',
        title: 'Filter Saved',
        text: 'Your filter preset has been saved successfully!',
        timer: 1500,
        showConfirmButton: false
    });
}

// Load counts for quick filters on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchQuickFilterCounts();
    animateKPICards();
    initializeSparklines();
    
    // Handle advanced filter toggle
    const advancedBtn = document.querySelector('[data-bs-target="#advancedFilters"]');
    const advancedCollapse = document.getElementById('advancedFilters');
    
    if (advancedBtn && advancedCollapse) {
        advancedCollapse.addEventListener('show.bs.collapse', function () {
            advancedBtn.innerHTML = '<i class="fas fa-sliders-h me-1"></i>Hide Advanced';
            advancedBtn.classList.remove('btn-ura-light');
            advancedBtn.classList.add('btn-ura-primary');
        });
        
        advancedCollapse.addEventListener('hide.bs.collapse', function () {
            advancedBtn.innerHTML = '<i class="fas fa-sliders-h me-1"></i>Advanced';
            advancedBtn.classList.remove('btn-ura-primary');
            advancedBtn.classList.add('btn-ura-light');
        });
    }
});

function fetchQuickFilterCounts() {
    // Fetch today count
    fetch('/api/loan-offers/kpi-details?period=today')
        .then(response => response.json())
        .then(data => {
            const todayTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) + 
                              (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('today-count').textContent = todayTotal;
        })
        .catch(error => console.error('Error fetching today count:', error));
    
    // Fetch week count
    fetch('/api/loan-offers/kpi-details?period=weekly')
        .then(response => response.json())
        .then(data => {
            const weekTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) + 
                             (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('week-count').textContent = weekTotal;
        })
        .catch(error => console.error('Error fetching week count:', error));
    
    // Fetch month count
    fetch('/api/loan-offers/kpi-details?period=monthly')
        .then(response => response.json())
        .then(data => {
            const monthTotal = (data.pending || 0) + (data.approved || 0) + (data.rejected || 0) + 
                              (data.cancelled || 0) + (data.disbursed || 0);
            document.getElementById('month-count').textContent = monthTotal;
        })
        .catch(error => console.error('Error fetching month count:', error));
}

// Keep the old function for compatibility
function setQuickFilter(filter) {
    applyQuickFilter(filter);
}

// Modern KPI Card Animations
function animateKPICards() {
    // Ensure cards are visible
    const cards = document.querySelectorAll('.kpi-card-modern');
    cards.forEach((card, index) => {
        // Make sure card is visible
        card.style.opacity = '1';
        card.style.visibility = 'visible';
        
        // Re-trigger animation if needed
        const animateClass = `animate-float-${index + 1}`;
        if (card.parentElement.classList.contains(animateClass)) {
            // Animation should already be applied via CSS class
        }
    });
    
    // Add entrance-complete class after animation finishes
    setTimeout(() => {
        cards.forEach(card => {
            card.classList.add('entrance-complete');
            card.style.opacity = '1'; // Ensure visibility
        });
    }, 2200); // After all cards have finished animating (1.5s + 0.6s delay + buffer)
    
    // Animate numbers on load
    setTimeout(() => {
        document.querySelectorAll('.number-animate').forEach(element => {
            const finalValue = parseInt(element.textContent);
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 30);
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                element.textContent = currentValue;
            }, 30);
        });
    }, 800); // Start after cards begin appearing
    
    // Animate progress bars
    setTimeout(() => {
        document.querySelectorAll('.progress-fill').forEach(bar => {
            const randomWidth = Math.floor(Math.random() * 60) + 20; // Random between 20-80%
            bar.style.width = randomWidth + '%';
        });
    }, 1500); // After cards are mostly visible
}

// Initialize mini sparkline charts
function initializeSparklines() {
    const sparklineData = {
        pending: [5, 8, 12, 7, 15, 9, 11, 14],
        approved: [3, 7, 10, 15, 12, 18, 20, 16],
        rejected: [2, 1, 3, 2, 1, 4, 2, 3],
        cancelled: [1, 0, 2, 1, 0, 1, 2, 1],
        disbursed: [8, 10, 12, 14, 11, 16, 18, 20]
    };
    
    // Create mini charts for each status
    Object.keys(sparklineData).forEach(status => {
        const canvas = document.getElementById(`${status}-sparkline`);
        if (canvas && canvas.getContext) {
            const ctx = canvas.getContext('2d');
            const data = sparklineData[status];
            const width = canvas.width;
            const height = canvas.height;
            const padding = 2;
            const max = Math.max(...data);
            
            ctx.clearRect(0, 0, width, height);
            ctx.strokeStyle = getStatusColor(status);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            
            ctx.beginPath();
            data.forEach((value, index) => {
                const x = (index / (data.length - 1)) * (width - padding * 2) + padding;
                const y = height - ((value / max) * (height - padding * 2)) - padding;
                
                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.stroke();
            
            // Add gradient fill
            const gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(0, getStatusColor(status) + '33');
            gradient.addColorStop(1, getStatusColor(status) + '00');
            
            ctx.lineTo(width - padding, height - padding);
            ctx.lineTo(padding, height - padding);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();
        }
    });
}

function getStatusColor(status) {
    const colors = {
        pending: '#FF8C00',
        approved: '#28a745',
        rejected: '#dc3545',
        cancelled: '#6c757d',
        disbursed: '#003366'
    };
    return colors[status] || '#003366';
}

// Add smooth hover tilt effect (reduced intensity)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.kpi-card-modern').forEach(card => {
        let isHovering = false;
        
        card.addEventListener('mouseenter', () => {
            isHovering = true;
            card.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mousemove', (e) => {
            if (!isHovering) return;
            
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            // Reduced rotation for subtler effect
            const rotateX = (y - centerY) / 20; // Reduced from /10 to /20
            const rotateY = (centerX - x) / 20; // Reduced from /10 to /20
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px) scale(1.01)`;
        });
        
        card.addEventListener('mouseleave', () => {
            isHovering = false;
            card.style.transition = 'transform 0.5s ease';
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0) scale(1)';
        });
    });
});

// Clear filters
function clearFilters() {
    const form = document.getElementById('filter-form');
    form.reset();
    window.location.href = '{{ route("loan-offers.index") }}';
}

// Change page size
function changePageSize(size) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', size);
    window.location.href = url.toString();
}

// Export report with real data
function exportReport(format) {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    const params = new URLSearchParams(window.location.search);
    params.append('format', format);
    if (selected.length > 0) {
        params.append('selected', selected.join(','));
    }

    // Show loading indicator
    Swal.fire({
        title: 'Generating Export',
        html: `<div class="text-center">
            <i class="fas fa-file-export fa-3x mb-3 text-primary"></i>
            <p>Preparing your ${format.toUpperCase()} file...</p>
        </div>`,
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 1500
    });

    // Trigger download
    setTimeout(() => {
        window.location.href = `/loan-offers/export?${params.toString()}`;
        Swal.close();
    }, 1000);
}

// Show bulk actions modal
function showBulkActions() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one loan application'
        });
        return;
    }
    
    Swal.fire({
        title: 'Bulk Actions',
        html: `
            <p>You have selected ${selected.length} loan application(s)</p>
            <p class="text-muted">Choose an action to apply:</p>
        `,
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="fas fa-check"></i> Approve All',
        denyButtonText: '<i class="fas fa-times"></i> Reject All',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        denyButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            performBulkAction('approve', selected);
        } else if (result.isDenied) {
            performBulkAction('reject', selected);
        }
    });
}

// Perform bulk action
function performBulkAction(action, ids) {
    Swal.fire({
        title: 'Processing',
        html: '<i class="fas fa-spinner fa-spin fa-3x"></i>',
        allowOutsideClick: false,
        showConfirmButton: false
    });
    
    fetch('/loan-offers/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            ids: ids
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 2000
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Operation failed. Please try again.'
        });
    });
}

// View timeline
function viewTimeline(loanId) {
    console.log('View timeline called for loan:', loanId);

    // For now, show a simple timeline modal since the endpoint might not exist yet
    Swal.fire({
        title: 'Activity Timeline',
        html: `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Loading timeline for loan #${loanId}...</p>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        didOpen: () => {
            // Try to fetch timeline data
            fetch(`/loan-offers/${loanId}/callbacks`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Timeline not available');
                    }
                    // For now just close and show message
                    Swal.fire({
                        title: 'Timeline',
                        text: 'Timeline feature will be available soon',
                        icon: 'info'
                    });
                })
                .catch(error => {
                    console.log('Timeline endpoint not available, showing placeholder');
                    Swal.fire({
                        title: 'Loan Timeline',
                        html: `
                            <div class="timeline-placeholder">
                                <p><strong>Loan ID:</strong> ${loanId}</p>
                                <hr>
                                <div class="text-start">
                                    <p><i class="fas fa-clock text-muted me-2"></i>Application Submitted</p>
                                    <p><i class="fas fa-check-circle text-success me-2"></i>Application Reviewed</p>
                                    <p><i class="fas fa-spinner text-primary me-2"></i>Processing...</p>
                                </div>
                            </div>
                        `,
                        width: '500px',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                });
        }
    });
}

// Process Loan Modal Functions
let currentLoanData = null;

function selectProcessAction(action) {
    const actionForm = document.getElementById('process-action-form');
    const reasonField = document.getElementById('process-reason-field');
    const actionType = document.getElementById('process-action-type');
    const submitBtn = document.getElementById('process-submit-btn');

    // Reset all action cards
    document.querySelectorAll('.action-card').forEach(card => {
        card.classList.remove('border-primary', 'bg-light');
    });

    // Highlight selected action card
    event.currentTarget.classList.add('border-primary', 'bg-light');

    // Set action type
    actionType.value = action;

    // Show/hide reason field based on action
    if (action === 'reject' || action === 'draft') {
        reasonField.style.display = 'block';
        document.getElementById('process-reason').required = true;
    } else {
        reasonField.style.display = 'none';
        document.getElementById('process-reason').required = false;
    }

    // Update submit button text
    switch(action) {
        case 'approve':
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Approve Loan';
            submitBtn.className = 'btn btn-success';
            break;
        case 'reject':
            submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Reject Loan';
            submitBtn.className = 'btn btn-danger';
            break;
        case 'draft':
            submitBtn.innerHTML = '<i class="fas fa-archive me-2"></i>Save as Draft';
            submitBtn.className = 'btn btn-warning';
            break;
    }

    // Show action form
    actionForm.style.display = 'block';
}

function cancelProcessAction() {
    document.getElementById('process-action-form').style.display = 'none';
    document.getElementById('loan-process-form').reset();

    // Reset action cards
    document.querySelectorAll('.action-card').forEach(card => {
        card.classList.remove('border-primary', 'bg-light');
    });
}

// Handle process form submission
document.getElementById('loan-process-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const action = formData.get('action');
    const loanId = formData.get('loan_id');
    const reason = formData.get('reason');

    // Show loading state
    const submitBtn = document.getElementById('process-submit-btn');
    if (submitBtn) {
        const spinner = submitBtn.querySelector('.spinner-border');
        if (spinner) {
            spinner.style.display = 'inline-block';
        }
        submitBtn.disabled = true;
    }

    // Use the existing updateLoanOffer method
    let url = `/loan-offers/${loanId}`;

    // Prepare data based on action
    let postData = {
        _token: '{{ csrf_token() }}',
        _method: 'PUT'  // Laravel method spoofing for PUT requests
    };

    switch(action) {
        case 'approve':
            postData.approval = 'APPROVED';
            postData.status = 'APPROVED';
            break;
        case 'reject':
            postData.approval = 'REJECTED';
            postData.status = 'REJECTED';
            postData.reason = reason;
            break;
        case 'draft':
            postData.status = 'DRAFT';
            postData.reason = reason;
            break;
    }

    // Log for debugging
    console.log('Processing loan action:', action);
    console.log('Request URL:', url);
    console.log('Request data:', postData);

    // Make AJAX request
    $.ajax({
        url: url,
        method: 'POST',  // Using POST with _method field for Laravel
        data: postData,
        success: function(response) {
            console.log('Success response:', response);
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: `Loan has been ${action}ed successfully.`,
                confirmButtonColor: '#17479E'
            }).then(() => {
                $('#loanDetailsModal').modal('hide');
                location.reload();
            });
        },
        error: function(xhr, status, error) {
            console.error('Error response:', xhr.responseJSON);
            console.error('Status:', status);
            console.error('Error:', error);

            let errorMessage = 'Failed to process the loan.';
            if (xhr.responseJSON?.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON?.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseText) {
                errorMessage = `Server error: ${xhr.status}`;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonColor: '#17479E'
            });
        },
        complete: function() {
            if (submitBtn) {
                const spinner = submitBtn.querySelector('.spinner-border');
                if (spinner) {
                    spinner.style.display = 'none';
                }
                submitBtn.disabled = false;
            }
        }
    });
});

// Approve loan - using update method
function approveLoan(loanId) {
    if (typeof Swal === 'undefined') {
        if (confirm('Are you sure you want to approve this loan application?')) {
            approveWithAjax(loanId);
        }
        return;
    }
    
    Swal.fire({
        title: 'Approve Loan Application',
        text: 'Are you sure you want to approve this loan application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Approve',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            approveWithAjax(loanId);
        }
    });
}

function approveWithAjax(loanId) {
    $.ajax({
        url: `/loan-offers/${loanId}`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            approval: 'APPROVED'
        },
        success: function(response) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Approved!', 'The loan has been approved.', 'success')
                    .then(() => location.reload());
            } else {
                alert('The loan has been approved.');
                location.reload();
            }
        },
        error: function(xhr) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Failed to approve the loan.', 'error');
            } else {
                alert('Failed to approve the loan.');
            }
        }
    });
}

// Reject loan - using update method
function rejectLoan(loanId) {
    if (typeof Swal === 'undefined') {
        const reason = prompt('Enter the reason for rejection:');
        if (reason) {
            rejectWithAjax(loanId, reason);
        }
        return;
    }
    
    Swal.fire({
        title: 'Reject Loan Application',
        input: 'textarea',
        inputLabel: 'Rejection Reason',
        inputPlaceholder: 'Enter the reason for rejection...',
        inputAttributes: {
            'aria-label': 'Rejection reason'
        },
        showCancelButton: true,
        confirmButtonText: 'Reject',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a reason for rejection';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            rejectWithAjax(loanId, result.value);
        }
    });
}

function rejectWithAjax(loanId, reason) {
    $.ajax({
        url: `/loan-offers/${loanId}`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            approval: 'REJECTED',
            reason: reason
        },
        success: function(response) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Rejected!', 'The loan has been rejected.', 'success')
                    .then(() => location.reload());
            } else {
                alert('The loan has been rejected.');
                location.reload();
            }
        },
        error: function(xhr) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Failed to reject the loan.', 'error');
            } else {
                alert('Failed to reject the loan.');
            }
        }
    });
}

// Make functions globally accessible
window.approveLoan = approveLoan;
window.rejectLoan = rejectLoan;

// Bulk actions
function bulkApprove() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select at least one loan to approve.', 'warning');
        } else {
            alert('Please select at least one loan to approve.');
        }
        return;
    }
    
    processBulkAction('approve', selected);
}

// Make bulk functions globally accessible
window.bulkApprove = bulkApprove;
window.bulkReject = bulkReject;
window.bulkExport = bulkExport;
window.processBulkAction = processBulkAction;
window.processBulkActionWithReason = processBulkActionWithReason;

// Export selected loans
function bulkExport() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    // Export selected loans
    const params = new URLSearchParams();
    params.append('ids', selected.join(','));
    params.append('format', 'excel');
    
    window.location.href = `/loan-offers/export?${params.toString()}`;
    
    Swal.fire({
        icon: 'success',
        title: 'Exporting...',
        text: `Exporting ${selected.length} selected loan(s)`,
        timer: 2000,
        showConfirmButton: false
    });
}

function bulkReject() {
    const selected = Array.from(document.querySelectorAll('.loan-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        Swal.fire('No Selection', 'Please select at least one loan to reject.', 'warning');
        return;
    }
    
    // Ask for rejection reason first
    Swal.fire({
        title: 'Bulk Reject Loans',
        html: `
            <p>You are about to reject ${selected.length} loan(s).</p>
            <div class="form-group text-start mt-3">
                <label for="bulk-reject-reason">Rejection Reason <span class="text-danger">*</span></label>
                <textarea id="bulk-reject-reason" class="form-control" rows="3" placeholder="Enter the reason for rejection..."></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reject All',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const reason = document.getElementById('bulk-reject-reason').value;
            if (!reason) {
                Swal.showValidationMessage('Please provide a reason for rejection');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            processBulkActionWithReason('reject', selected, result.value);
        }
    });
}

function processBulkAction(action, ids) {
    if (ids.length === 0) return;

    // Set appropriate colors based on action
    let confirmColor = '#667eea';
    if (action === 'approve') confirmColor = '#28a745';
    if (action === 'reject') confirmColor = '#dc3545';

    Swal.fire({
        title: `Confirm Bulk ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Are you sure you want to ${action} ${ids.length} loan(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Yes, ${action}`,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show processing
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we process your request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Process bulk action
            $.ajax({
                url: '/loan-offers/bulk-action',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    ids: ids
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || `Successfully processed ${ids.length} loan(s)`,
                        confirmButtonColor: '#17479E'
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to process bulk action.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }
    });
}

// Process bulk action with reason (for rejections)
function processBulkActionWithReason(action, ids, reason) {
    if (ids.length === 0) return;

    // Show processing
    Swal.fire({
        title: 'Processing...',
        html: 'Please wait while we process your request',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Process bulk action
    $.ajax({
        url: '/loan-offers/bulk-action',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            action: action,
            ids: ids,
            reason: reason || ''
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || `Successfully ${action}ed ${ids.length} loan(s)`,
                confirmButtonColor: '#17479E'
            }).then(() => location.reload());
        },
        error: function(xhr) {
            let errorMessage = 'Failed to process bulk action.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

// Toggle between table and card view
function toggleTableView() {
    // Implementation for switching between table and card view
    document.querySelector('.table-responsive').classList.toggle('card-view');
}

// Function to fetch KPI data with period support
function fetchKPIData(status, period, startDate = null, endDate = null) {
    const spinner = document.getElementById('kpiSpinner');
    const contentDiv = document.getElementById('kpiDetailContent');
    const filterControls = document.getElementById('kpiFilterControls');
    const periodInfo = document.getElementById('kpiPeriodInfo');
    const periodText = document.getElementById('kpiPeriodText');
    const modalTitle = document.getElementById('kpiDetailModalLabel');
    
    // Build query params
    let params = new URLSearchParams({
        status: status,
        period: period
    });
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    fetch(`/api/loan-offers/kpi-details?${params}`)
        .then(response => response.json())
        .then(data => {
            currentKPIData = data;
            
            // Hide spinner, show content and filters
            spinner.style.display = 'none';
            contentDiv.style.display = 'block';
            filterControls.style.display = 'block';
            
            // Update modal title with period info
            const statusTitles = {
                'pending': '<i class="fas fa-clock me-2" style="color: white;"></i><span style="color: white;">Pending Applications</span>',
                'approved': '<i class="fas fa-check-circle me-2" style="color: white;"></i><span style="color: white;">Approved Applications</span>',
                'rejected': '<i class="fas fa-times-circle me-2" style="color: white;"></i><span style="color: white;">Rejected Applications (URAERP)</span>',
                'cancelled': '<i class="fas fa-ban me-2" style="color: white;"></i><span style="color: white;">Cancelled Applications (Employee)</span>',
                'disbursed': '<i class="fas fa-hand-holding-usd me-2" style="color: white;"></i><span style="color: white;">Disbursed Loans</span>'
            };
            
            modalTitle.innerHTML = `${statusTitles[status]} <span style="color: white;">- ${data.period_label}</span>`;
            
            // Show period info if auto-detected
            if (data.period !== period && period === 'auto') {
                periodInfo.style.display = 'block';
                let infoText = `Showing ${data.period_label} data`;
                if (data.total_count === 0) {
                    infoText += ' (No data found in weekly or monthly periods)';
                } else if (data.period === 'month') {
                    infoText += ' (No weekly data available)';
                } else if (data.period === 'year') {
                    infoText += ' (No weekly or monthly data available)';
                }
                periodText.textContent = infoText;
            } else {
                periodInfo.style.display = 'none';
            }
            
            // Build table HTML
            let tableHTML = `
                <div class="mb-3">
                    <h6 class="text-muted">Period: ${data.period_start} - ${data.period_end}</h6>
                    <h4 style="color: var(--ura-primary);">Total Count: ${data.total_count}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Application #</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Take Home</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            if (data.loans && data.loans.length > 0) {
                data.loans.forEach(loan => {
                    tableHTML += `
                        <tr>
                            <td>${loan.application_number}</td>
                            <td>${loan.first_name} ${loan.last_name}</td>
                            <td>TZS ${formatNumber(loan.requested_amount)}</td>
                            <td class="text-success">TZS ${formatNumber(loan.take_home_amount || loan.net_loan_amount)}</td>
                            <td>${formatDate(loan.created_at)}</td>
                            <td>
                                <a href="/loan-offers/${loan.id}/edit" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableHTML += `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No ${status} applications in ${data.period_label.toLowerCase()}
                        </td>
                    </tr>
                `;
            }
            
            tableHTML += `
                        </tbody>
                    </table>
                </div>
                
                <!-- Period Summary Stats -->
                <div class="mt-3 p-3 bg-light rounded">
                    <h6 class="text-muted mb-2">Period Summary</h6>
                    <div class="row g-2">
                        <div class="col">
                            <small class="text-muted">Pending</small>
                            <div class="fw-bold">${data.stats.pending || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Approved</small>
                            <div class="fw-bold text-success">${data.stats.approved || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Rejected</small>
                            <div class="fw-bold text-danger">${data.stats.rejected || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Cancelled</small>
                            <div class="fw-bold text-secondary">${data.stats.cancelled || 0}</div>
                        </div>
                        <div class="col">
                            <small class="text-muted">Disbursed</small>
                            <div class="fw-bold text-info">${data.stats.disbursed || 0}</div>
                        </div>
                    </div>
                </div>
            `;
            
            contentDiv.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error fetching KPI details:', error);
            spinner.style.display = 'none';
            contentDiv.style.display = 'block';
            contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load data</div>';
        });
}

// Function to refresh KPI details with current filters
function refreshKPIDetails() {
    const period = document.getElementById('kpiPeriodFilter').value;
    let startDate = null;
    let endDate = null;
    
    if (period === 'custom') {
        startDate = document.getElementById('kpiStartDate').value;
        endDate = document.getElementById('kpiEndDate').value;
        
        if (!startDate || !endDate) {
            alert('Please select both start and end dates');
            return;
        }
    }
    
    fetchKPIData(currentKPIStatus, period, startDate, endDate);
}

// Handle period filter change
document.addEventListener('DOMContentLoaded', function() {
    const periodFilter = document.getElementById('kpiPeriodFilter');
    if (periodFilter) {
        periodFilter.addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            document.getElementById('kpiStartDateDiv').style.display = isCustom ? 'block' : 'none';
            document.getElementById('kpiEndDateDiv').style.display = isCustom ? 'block' : 'none';
        });
    }
});

// Initialize DataTable for loan tables
$(document).ready(function() {
    if ($.fn.DataTable && $('#loansTable').length) {
        $('#loansTable').DataTable({
            pageLength: 25,
            responsive: true,
            order: [[1, 'asc']], // Sort by employee name
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "_MENU_ records per page",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "<i class='fas fa-chevron-right'></i>",
                    previous: "<i class='fas fa-chevron-left'></i>"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"lB><"col-sm-12 col-md-6"f>>rtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child):not(:last-child)' // Exclude checkbox and actions columns
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child):not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':not(:first-child):not(:last-child)'
                    }
                }
            ],
            columnDefs: [
                { orderable: false, targets: 0 }, // Disable sorting for checkbox column
                { orderable: false, targets: -1 } // Disable sorting for actions column
            ]
        });
        
        // Hide the original search section since DataTable has its own
        $('.modern-search-container').hide();
        // Hide the original pagination since DataTable has its own
        $('.modern-table-footer').hide();
    }
});
</script>
