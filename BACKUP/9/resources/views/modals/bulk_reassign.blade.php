<div class="modal fade" id="bulkReassignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exchange-alt me-2"></i>Bulk Reassign Enquiries
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>
                        <strong>Bulk Reassignment</strong><br>
                        <small>Reassign <span id="bulkReassignCount" class="fw-bold">0</span> selected enquiries to a new user</small>
                    </div>
                </div>
 

                <form id="bulkReassignForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="mdi mdi-account me-1"></i>Select New User for Assignment
                        </label>
                        <select class="form-select text-uppercase text-primary fw-bold" id="bulkReassignUserId" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->getRoleNames()->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="mdi mdi-information-outline me-1"></i>
                            All selected enquiries will be reassigned to this user
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="bulkReassignEnquiries()">
                    <i class="fas fa-exchange-alt me-1"></i>Reassign All Selected
                </button>
            </div>
        </div>
    </div>
</div>