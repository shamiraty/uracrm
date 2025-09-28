<!-- Enhanced Loan Details Modal -->
<div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl modal-ura-enhanced">
            <!-- Animated Background Pattern -->
            <div class="modal-pattern"></div>

            <!-- Modal Header with Gradient -->
            <div class="modal-header modal-header-ura border-0 position-relative overflow-hidden">
                <div class="header-content position-relative z-index-1">
                    <div class="d-flex align-items-center">
                        <div class="modal-icon-wrapper me-3">
                            <div class="modal-icon-circle">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="modal-title mb-1 fw-bold text-white">Loan Application Details</h4>
                            <p class="mb-0 small text-white" style="opacity: 0.9;">Comprehensive loan information and status</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-relative z-index-1" data-bs-dismiss="modal"></button>
                <div class="header-decoration"></div>
            </div>
            <div class="modal-body p-0">
                <!-- Enhanced Tab Navigation -->
                <div class="tab-navigation-wrapper">
                    <ul class="nav nav-tabs nav-tabs-ura nav-fill border-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura active" data-bs-toggle="tab" href="#personal-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="tab-label">Personal</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#employment-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <span class="tab-label">Employment</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#loan-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <span class="tab-label">Loan</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#financial-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <span class="tab-label">Financial</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#bank-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-university"></i>
                                </div>
                                <span class="tab-label">Banking</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#status-info">
                                <div class="tab-icon-box">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <span class="tab-label">Status</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ura" data-bs-toggle="tab" href="#process-loan">
                                <div class="tab-icon-box">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <span class="tab-label">Process Loan</span>
                                <div class="tab-indicator"></div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    @include('employee_loan.partials.modal-tabs')
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select an action for <span id="selected-count">0</span> selected loans:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="bulkApprove()">
                        <i class="fas fa-check me-2"></i>Approve Selected
                    </button>
                    <button class="btn btn-danger" onclick="bulkReject()">
                        <i class="fas fa-times me-2"></i>Reject Selected
                    </button>
                    <button class="btn btn-secondary" onclick="bulkExport()">
                        <i class="fas fa-file-export me-2"></i>Export Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="fab" onclick="showQuickActions()">
    <i class="fas fa-plus"></i>
</div>

<!-- Quick Actions Menu -->
<div id="quick-actions" class="quick-actions-menu">
    <button class="quick-action-btn" onclick="refreshFromESS()" data-tooltip="Sync with ESS">
        <i class="fas fa-sync-alt"></i>
    </button>
    <button class="quick-action-btn" onclick="exportReport('excel')" data-tooltip="Export Excel">
        <i class="fas fa-file-excel"></i>
    </button>
    <button class="quick-action-btn" onclick="exportReport('pdf')" data-tooltip="Export PDF">
        <i class="fas fa-file-pdf"></i>
    </button>
    <button class="quick-action-btn" onclick="showFilters()" data-tooltip="Filter">
        <i class="fas fa-filter"></i>
    </button>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Processing your request...</p>
    </div>
</div>

<!-- KPI Detail Modals -->
<div class="modal fade" id="kpiDetailModal" tabindex="-1" aria-labelledby="kpiDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #003366 0%, #17479E 100%); color: white;">
                <h5 class="modal-title" id="kpiDetailModalLabel" style="color: white;">
                    <i class="fas fa-chart-line me-2"></i>Weekly Loan Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Filter Controls -->
                <div id="kpiFilterControls" class="mb-3" style="display: none;">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Period</label>
                            <select id="kpiPeriodFilter" class="form-select form-select-sm">
                                <option value="auto">Auto (Best Available)</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="all">All Time</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="kpiStartDateDiv" style="display: none;">
                            <label class="form-label small text-muted">Start Date</label>
                            <input type="date" id="kpiStartDate" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="kpiEndDateDiv" style="display: none;">
                            <label class="form-label small text-muted">End Date</label>
                            <input type="date" id="kpiEndDate" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="refreshKPIDetails()">
                                <i class="fas fa-sync-alt me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                    <div id="kpiPeriodInfo" class="alert alert-info mt-2 py-2 px-3 small" style="display: none;">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="kpiPeriodText"></span>
                    </div>
                </div>
                
                <!-- Loading Spinner -->
                <div class="text-center py-3" id="kpiSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <!-- Content Area -->
                <div id="kpiDetailContent" style="display: none;">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" style="background: var(--ura-primary); border-color: var(--ura-primary);" onclick="exportKPIData()">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>