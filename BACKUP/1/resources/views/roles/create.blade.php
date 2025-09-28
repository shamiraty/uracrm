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
<div class="container-fluid">
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Role</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('enquiries.index') }}">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Role</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header">
                Add New Role
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.store') }}" id="roleForm">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name">Role Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required aria-describedby="roleNameHelp">
                            <small id="roleNameHelp" class="form-text text-muted">Enter the unique name for the role.</small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="permissions">Assign Permissions:</label>
                            <select  class="form-control select2" id="permissions" name="permissions[]">
                                @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-sm">
                            <i class="fas fa-plus-circle"></i> Create Role
                        </button>
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
