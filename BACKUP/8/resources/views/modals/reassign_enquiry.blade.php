<div class="modal fade" id="reassignUserModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="reassignUserModalLabel-{{ $enquiry->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="reassignUserModalLabel-{{ $enquiry->id }}">
                    <i class="mdi mdi-account-switch me-2"></i>
                    Reassign {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }} #{{ $enquiry->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('enquiries.reassign', $enquiry->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="mdi mdi-alert me-2"></i>
                        <div>
                            <strong>Current Assignment:</strong><br>
                            <small>{{ $enquiry->users->first()->name ?? 'Unassigned' }}</small>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="mdi mdi-information me-2"></i>
                        <div>
                            <strong>Enquiry Details:</strong><br>
                            <small>{{ $enquiry->full_name }} - {{ $enquiry->check_number }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="user_ids_reassign_{{ $enquiry->id }}" class="form-label fw-semibold">
                            <i class="mdi mdi-account me-1"></i>Select New User for Assignment
                        </label>
                        <select class="form-select" id="user_ids_reassign_{{ $enquiry->id }}" name="user_ids[]" required>
                            <option value="">Choose new user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->roles->pluck('name')->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="mdi mdi-information-outline me-1"></i>
                            This will remove the current assignment and assign to the selected user
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-account-switch me-1"></i>Reassign User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>