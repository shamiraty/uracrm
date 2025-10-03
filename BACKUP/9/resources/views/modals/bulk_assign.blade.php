<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold text-white">
                    <i class="fas fa-users me-2"></i>Bulk Assign Enquiries
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <div>
                       
                        <small>Assign <span id="bulkAssignCount" class="fw-bold">0</span> selected enquiries to a user</small>
                    </div>
                </div>

                <form id="bulkAssignForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-account me-1"></i>Select User for Assignment
                        </label>
                        <select class="form-select text-uppercase text-primary" id="bulkAssignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="mdi mdi-information-outline me-1"></i>
                            All selected enquiries will be assigned to this user
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="bulkAssignEnquiries()">
                    <i class="fas fa-check me-1"></i>Assign All Selected
                </button>
            </div>
        </div>
    </div>
</div>