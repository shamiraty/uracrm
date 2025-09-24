<div class="modal fade" id="assignUserModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="assignUserModalLabel-{{ $enquiry->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <p class="modal-title text-primary text-uppercase fw-bold" id="assignUserModalLabel-{{ $enquiry->id }}">Assign User to {{ $enquiry->type }} no {{ $enquiry->id }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('enquiries.assign', $enquiry->id) }}" method="POST">
    @csrf
    {{--
    <div class="modal-body">
        <div class="form-group">
            <label for="user_ids">Assign Users</label>
            <select class="form-select custom-select-dropdown" id="user_ids" name="user_ids[]" aria-label="Select users">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    --}}
    <div class="modal-body">
    <div class="form-group">
        <label for="user_ids">Assign User</label>
        <select class="form-select custom-select-dropdown" id="user_ids" name="user_ids[]" aria-label="Select users"required>
            <option value="">Select</option>
            @foreach($users as $user)
                @if($user->hasRole(['accountant', 'loanofficer','superadmin']))
                    <option value="{{ $user->id }}"class="text-lowercase"> {{ $user->name }} [ {{ $user->roles->pluck('name')->implode(', ') }} ]</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
    </div>
</form>

        </div>
    </div>
</div>
