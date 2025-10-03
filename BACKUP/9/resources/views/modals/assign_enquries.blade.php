<div class="modal fade" id="assignUserModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="assignUserModalLabel-{{ $enquiry->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold text-white text-uppercase" id="assignUserModalLabel-{{ $enquiry->id }}">
                    <i class="mdi mdi-account-arrow-right me-2"></i>
                    Assign {{ ucfirst(str_replace('_', ' ', $enquiry->type)) }}  
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('enquiries.assign', $enquiry->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="mdi mdi-information me-2"></i>
                        <div>
                            <strong>Enquiry Details:</strong><br>
                            <small>{{ $enquiry->full_name }} - {{ $enquiry->check_number }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="user_ids_{{ $enquiry->id }}" class="form-label fw-semibold">
                            <i class="mdi mdi-account me-1"></i>Select User to Assign
                        </label>
                        <select class="form-select text-uppercase text-primary fw-bold" id="user_ids_{{ $enquiry->id }}" name="user_ids[]" required>
                            <option value="">Choose user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->roles->pluck('name')->implode(', ') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Only users with appropriate roles are shown
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check me-1"></i>Assign User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
