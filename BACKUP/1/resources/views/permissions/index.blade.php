@extends('layouts.app')
@section('content')


<!-- Breadcrumb section -->
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Permissions</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('enquiries.index') }}">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Permissions</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Add new permission button -->
<div class="container">
    <button class="btn btn-primary mb-3 btn-sm" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
        <i class="fas fa-plus-square"></i> Add New Permission
    </button>

    <!-- Permissions List -->
    <div class="card shadow-sm basic-data-table">
    <div class="card-header">
                    <h6 class="mb-0 text-primary">Permissions List</h6>
                </div>
                <div class="card-body p-24 d-flex flex-column gap-4">
                <div class="table-responsive">
                <table class="table border-primary-table mb-0" id="dataTable">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-id="{{ $permission->id }}" data-name="{{ $permission->name }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-id="{{ $permission->id }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Permission Modal -->
<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="createPermissionModalLabel">Create Permission</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permission_name">Permission Name</label>
                        <input type="text" class="form-control" name="name" id="permission_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editPermissionForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="editPermissionModalLabel">Edit Permission</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_permission_name">Permission Name</label>
                        <input type="text" class="form-control" name="name" id="edit_permission_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Permission Modal -->
<div class="modal fade" id="deletePermissionModal" tabindex="-1" aria-labelledby="deletePermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="deletePermissionForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="deletePermissionModalLabel">Delete Permission</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this permission?</p>
                    <input type="hidden" name="permission_id" id="delete_permission_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Edit Permission Modal
    $('#editPermissionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var permissionId = button.data('id');
        var permissionName = button.data('name');
        
        var modal = $(this);
        modal.find('#edit_permission_name').val(permissionName);
        modal.find('#editPermissionForm').attr('action', '/permissions/' + permissionId);
    });

    // Delete Permission Modal
    $('#deletePermissionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var permissionId = button.data('id');
        
        var modal = $(this);
        modal.find('#delete_permission_id').val(permissionId);
        modal.find('#deletePermissionForm').attr('action', '/permissions/' + permissionId);
    });
</script>
@endsection
