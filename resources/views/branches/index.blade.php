{{-- resources/views/branches/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Branch</h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Branch
            </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Branch List</li>
    </ul>
</div>
{{--
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
--}}
<div class="card basic-data-table">
    <div class="card-header">
        <h6 class="card-title mb-0 text-primary">
            <button class="btn btn-primary btn-sm radius-30 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bx bxs-plus-square"></i> Create New Branch
            </button>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border-primary-table mb-0" id="dataTable" data-page-length="10">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Branch Name</th>
                        <th>District Name</th>
                        <th>Region Name</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="bg-light text-primary">{{ ucwords($branch->name) }}</td>
                            <td>{{ $branch->district->name ?? 'N/A' }}</td>
                            <td>{{ $branch->region->name ?? 'N/A' }}</td>
                            <td>{{ $branch->created_at->format('F j, Y') }}</td>

                            <td>
                                <button 
                                    class="btn btn-primary btn-sm editBranchBtn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal" 
                                    data-id="{{ $branch->id }}" 
                                    data-name="{{ $branch->name }}" 
                                    data-district="{{ $branch->district_id }}" 
                                    data-region="{{ $branch->region_id }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button 
                                    class="btn btn-danger btn-sm deleteBranchBtn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-id="{{ $branch->id }}" 
                                    data-name="{{ $branch->name }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Insert Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('branches.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title text-secondary" id="createModalLabel">Create New Branch</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select class="form-control" id="district_id" name="district_id" required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Region</label>
                        <select class="form-control" id="region_id" name="region_id" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title text-secondary" id="editModalLabel">Edit Branch</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_district_id" class="form-label">District</label>
                        <select class="form-control" id="edit_district_id" name="district_id" required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_region_id" class="form-label">Region</label>
                        <select class="form-control" id="region" name="region_id" required onchange="updateDistricts() required >
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title text-secondary" id="deleteModalLabel">Delete Branch</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the branch <strong id="deleteBranchName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editBranchBtns = document.querySelectorAll('.editBranchBtn');
        const deleteBranchBtns = document.querySelectorAll('.deleteBranchBtn');
        const editForm = document.getElementById('editForm');
        const deleteForm = document.getElementById('deleteForm');

        // Handle edit modal
        editBranchBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const district = btn.getAttribute('data-district');
                const region = btn.getAttribute('data-region');

                editForm.action = `/branches/${id}`;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_district_id').value = district;
                document.getElementById('edit_region_id').value = region;
            });
        });

        // Handle delete modal
        deleteBranchBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');

                deleteForm.action = `/branches/${id}`;
                document.getElementById('deleteBranchName').textContent = name;
            });
        });
    });
</script>
@endsection
