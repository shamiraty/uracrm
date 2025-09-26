@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="mb-1"><i class="fas fa-clipboard-list me-3"></i>Enquiries Management</h1>
                            <p class="mb-0 opacity-75">Manage and track all enquiry submissions</p>
                        </div>
                        <div>
                            <a href="{{ route('enquiries.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>New Enquiry
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['total'] ?? 0 }}</h3>
                    <p class="mb-0">Total Enquiries</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['status_breakdown']['pending'] ?? 0 }}</h3>
                    <p class="mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['status_breakdown']['assigned'] ?? 0 }}</h3>
                    <p class="mb-0">Assigned</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['status_breakdown']['approved'] ?? 0 }}</h3>
                    <p class="mb-0">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['status_breakdown']['rejected'] ?? 0 }}</h3>
                    <p class="mb-0">Rejected</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-dark text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h3 class="mb-1">{{ $analytics['overdue'] ?? 0 }}</h3>
                    <p class="mb-0">Overdue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search enquiries...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Type</label>
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="loan_application" {{ request('type') == 'loan_application' ? 'selected' : '' }}>Loan Application</option>
                                <option value="membership_change" {{ request('type') == 'membership_change' ? 'selected' : '' }}>Membership Change</option>
                                <option value="condolence" {{ request('type') == 'condolence' ? 'selected' : '' }}>Condolence</option>
                                <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="retirement" {{ request('type') == 'retirement' ? 'selected' : '' }}>Retirement</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="pending_overdue" {{ request('status') == 'pending_overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Per Page</label>
                            <select class="form-select" name="per_page">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 15) == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('enquiries.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulkActions" class="alert alert-primary d-none">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong><span id="selectedCount">0</span> enquiries selected</strong>
            </div>
            <div>
                @if(auth()->user()->hasRole('registrar_hq'))
                <button class="btn btn-success btn-sm me-2" onclick="showBulkAssignModal()">
                    <i class="fas fa-user-plus"></i> Bulk Assign
                </button>
                <button class="btn btn-warning btn-sm me-2" onclick="showBulkReassignModal()">
                    <i class="fas fa-exchange-alt"></i> Bulk Reassign
                </button>
                @endif
                <button class="btn btn-danger btn-sm me-2" onclick="confirmBulkDelete()">
                    <i class="fas fa-trash"></i> Bulk Delete
                </button>
                <button class="btn btn-secondary btn-sm" onclick="clearSelection()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2"></i>Enquiries List
                        <small class="text-muted">({{ $enquiries->total() }} total)</small>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Enquiry Details</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Registered By</th>
                                    <th>Assigned To</th>
                                    <th>Date</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enquiries as $enquiry)
                                @php
                                    $isOverdue = $enquiry->status == 'pending' &&
                                                $enquiry->created_at->diffInWeekdays(now()) >= 3;
                                    $daysDiff = $enquiry->created_at->diffInWeekdays(now());
                                @endphp
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input enquiry-checkbox" type="checkbox" value="{{ $enquiry->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $enquiry->full_name }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-hashtag me-1"></i>{{ $enquiry->check_number }}
                                                <br>
                                                <i class="fas fa-shield-alt me-1"></i>{{ $enquiry->force_no }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ ucwords(str_replace('_', ' ', $enquiry->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($isOverdue)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Overdue ({{ $daysDiff }} days)
                                            </span>
                                        @else
                                            @php
                                                $statusClass = match($enquiry->status) {
                                                    'pending' => 'bg-warning text-dark',
                                                    'assigned' => 'bg-info',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                $statusIcon = match($enquiry->status) {
                                                    'pending' => 'fas fa-clock',
                                                    'assigned' => 'fas fa-user-check',
                                                    'approved' => 'fas fa-check-circle',
                                                    'rejected' => 'fas fa-times-circle',
                                                    default => 'fas fa-question'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                <i class="{{ $statusIcon }} me-1"></i>
                                                {{ ucwords($enquiry->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $enquiry->registeredBy->name ?? 'N/A' }}</h6>
                                            @if($enquiry->registeredBy && $enquiry->registeredBy->district)
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $enquiry->registeredBy->district->name }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($enquiry->users->count() > 0)
                                            <div>
                                                <h6 class="mb-1">{{ $enquiry->users->first()->name }}</h6>
                                                <small class="badge bg-light text-dark">
                                                    {{ $enquiry->users->first()->getRoleNames()->implode(', ') }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-user-slash me-1"></i>Not assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $enquiry->created_at->format('M d, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $enquiry->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('enquiries.show', $enquiry->id) }}"
                                               class="btn btn-outline-primary btn-sm"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(auth()->user()->hasRole('registrar_hq'))
                                                <!-- Assign/Reassign Buttons -->
                                                @if(in_array($enquiry->status, ['pending', 'pending_overdue']))
                                                <button class="btn btn-outline-success btn-sm"
                                                        onclick="showAssignModal({{ $enquiry->id }})"
                                                        title="Assign">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                                @elseif(in_array($enquiry->status, ['assigned', 'pending_overdue']))
                                                <button class="btn btn-outline-warning btn-sm"
                                                        onclick="showReassignModal({{ $enquiry->id }})"
                                                        title="Reassign">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                @endif
                                            @endif

                                            @if($enquiry->registered_by == auth()->user()->id)
                                                <!-- Edit Button -->
                                                @if($enquiry->status == 'pending' && !$isOverdue)
                                                <a href="{{ route('enquiries.edit', $enquiry->id) }}"
                                                   class="btn btn-outline-secondary btn-sm"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                <!-- Delete Button -->
                                                @if(in_array($enquiry->status, ['pending', 'rejected']))
                                                <button class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete({{ $enquiry->id }})"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No enquiries found</h5>
                                            <p>Try adjusting your search criteria or create a new enquiry.</p>
                                            <a href="{{ route('enquiries.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Create New Enquiry
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($enquiries->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $enquiries->firstItem() }} to {{ $enquiries->lastItem() }}
                            of {{ $enquiries->total() }} results
                        </div>
                        <div>
                            {{ $enquiries->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Assign Enquiry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    <input type="hidden" id="assignEnquiryId">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        <select class="form-select" id="assignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="assignEnquiry()">
                    <i class="fas fa-check me-1"></i>Assign
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>Reassign Enquiry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reassignForm">
                    <input type="hidden" id="reassignEnquiryId">
                    <div class="mb-3">
                        <label class="form-label">Select New User</label>
                        <select class="form-select" id="reassignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="reassignEnquiry()">
                    <i class="fas fa-exchange-alt me-1"></i>Reassign
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2"></i>Bulk Assign Enquiries
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Assign <strong><span id="bulkAssignCount">0</span></strong> selected enquiries to:</p>
                <form id="bulkAssignForm">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        <select class="form-select" id="bulkAssignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="bulkAssignEnquiries()">
                    <i class="fas fa-check me-1"></i>Assign All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Reassign Modal -->
<div class="modal fade" id="bulkReassignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>Bulk Reassign Enquiries
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Reassign <strong><span id="bulkReassignCount">0</span></strong> selected enquiries to:</p>
                <form id="bulkReassignForm">
                    <div class="mb-3">
                        <label class="form-label">Select New User</label>
                        <select class="form-select" id="bulkReassignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="bulkReassignEnquiries()">
                    <i class="fas fa-exchange-alt me-1"></i>Reassign All
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCheckboxes();
});

// Initialize Checkboxes
function initializeCheckboxes() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const enquiryCheckboxes = document.querySelectorAll('.enquiry-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectAllCheckbox.addEventListener('change', function() {
        enquiryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    enquiryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
        const count = checkedBoxes.length;

        selectedCount.textContent = count;

        if (count > 0) {
            bulkActions.classList.remove('d-none');
        } else {
            bulkActions.classList.add('d-none');
        }

        selectAllCheckbox.checked = count === enquiryCheckboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < enquiryCheckboxes.length;
    }
}

// Modal Functions
function showAssignModal(enquiryId) {
    document.getElementById('assignEnquiryId').value = enquiryId;
    new bootstrap.Modal(document.getElementById('assignModal')).show();
}

function showReassignModal(enquiryId) {
    document.getElementById('reassignEnquiryId').value = enquiryId;
    new bootstrap.Modal(document.getElementById('reassignModal')).show();
}

function showBulkAssignModal() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }
    document.getElementById('bulkAssignCount').textContent = checkedBoxes.length;
    new bootstrap.Modal(document.getElementById('bulkAssignModal')).show();
}

function showBulkReassignModal() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }
    document.getElementById('bulkReassignCount').textContent = checkedBoxes.length;
    new bootstrap.Modal(document.getElementById('bulkReassignModal')).show();
}

// Assignment Functions
function assignEnquiry() {
    const enquiryId = document.getElementById('assignEnquiryId').value;
    const userId = document.getElementById('assignUserId').value;

    if (!userId) {
        alert('Please select a user');
        return;
    }

    fetch(`/enquiries/${enquiryId}/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_ids: [userId] })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
        if (data.success) {
            location.reload();
        } else {
            alert('Assignment failed');
        }
    });
}

function reassignEnquiry() {
    const enquiryId = document.getElementById('reassignEnquiryId').value;
    const userId = document.getElementById('reassignUserId').value;

    if (!userId) {
        alert('Please select a user');
        return;
    }

    fetch(`/enquiries/${enquiryId}/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_ids: [userId] })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('reassignModal')).hide();
        if (data.success) {
            location.reload();
        } else {
            alert('Reassignment failed');
        }
    });
}

function bulkAssignEnquiries() {
    const userId = document.getElementById('bulkAssignUserId').value;
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (!userId) {
        alert('Please select a user');
        return;
    }

    fetch('/enquiries/bulk-assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            enquiry_ids: enquiryIds,
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('bulkAssignModal')).hide();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Bulk assignment failed');
        }
    });
}

function bulkReassignEnquiries() {
    const userId = document.getElementById('bulkReassignUserId').value;
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (!userId) {
        alert('Please select a user');
        return;
    }

    fetch('/enquiries/bulk-reassign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            enquiry_ids: enquiryIds,
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('bulkReassignModal')).hide();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Bulk reassignment failed');
        }
    });
}

// Delete Functions
function confirmDelete(enquiryId) {
    if (confirm('Are you sure you want to delete this enquiry?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/enquiries/${enquiryId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmBulkDelete() {
    const checkedBoxes = document.querySelectorAll('.enquiry-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one enquiry');
        return;
    }

    if (confirm(`Delete ${checkedBoxes.length} selected enquiries?`)) {
        const enquiryIds = Array.from(checkedBoxes).map(cb => cb.value);

        fetch('/enquiries/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ enquiry_ids: enquiryIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Bulk delete failed');
            }
        });
    }
}

function clearSelection() {
    document.querySelectorAll('.enquiry-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActions').classList.add('d-none');
}
</script>

@endsection