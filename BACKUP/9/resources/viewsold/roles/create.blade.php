{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Role</h1>
    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Role Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="permissions">Assign Permissions:</label>
            <select multiple class="form-control" id="permissions" name="permissions[]">
                @foreach($permissions as $permission)
                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Role</button>
    </form>
</div>
@endsection --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>Add New Role</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.store') }}" id="roleForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Role Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required aria-describedby="roleNameHelp">
                            <small id="roleNameHelp" class="form-text text-muted">Enter the unique name for the role.</small>
                        </div>
                        <div class="form-group">
                            <label for="permissions">Assign Permissions:</label>
                            <select multiple class="form-control select2" id="permissions" name="permissions[]">
                                @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select permissions",
            allowClear: true
        });

        $('#roleForm').submit(function() {
            $(this).find(':submit').prop('disabled', true).append(' <i class="fa fa-spinner fa-spin"></i>');
        });
    });
</script>
@endpush
