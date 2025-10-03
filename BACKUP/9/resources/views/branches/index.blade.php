{{-- resources/views/branches/index.blade.php --}}

@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 12px 35px rgba(23, 71, 158, 0.25);
    }

    .branches-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .branches-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, #10dc60 0%, #0bb24c 100%);
        color: white;
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #f04141 0%, #d32f2f 100%);
        color: white;
    }

    .modern-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: none;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .modern-table th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        padding: 1rem;
        border: none;
        text-align: center;
    }

    .modern-table td {
        padding: 0.75rem 1rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
        text-align: center;
        font-weight: 500;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
    }

    .modern-breadcrumb {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .breadcrumb-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    .breadcrumb-nav {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-nav a {
        color: var(--ura-primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .breadcrumb-nav a:hover {
        color: var(--ura-accent);
    }

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow-hover);
    }

    .modal-header {
        background: var(--ura-gradient-light);
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        border-radius: 16px 16px 0 0;
    }

    .modal-title {
        color: var(--ura-primary);
        font-weight: 600;
    }

    .form-control {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .stats-card {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-weight: 500;
    }

    .branch-badge {
        background: var(--ura-gradient);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>

<!-- Modern Header -->
<div class="branches-header">
    <h1 class="header-title">
        <i class="bx bx-buildings"></i>
        Branch Management
    </h1>
    <p class="header-subtitle">
        Comprehensive branch directory and management system
    </p>
</div>

<!-- Modern Breadcrumb -->
<div class="modern-breadcrumb d-flex flex-wrap align-items-center justify-content-between">
    <h6 class="breadcrumb-title">
        <i class="bx bx-home me-2"></i>
        Branch Directory
    </h6>
    <ul class="breadcrumb-nav">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="bx bx-home-alt"></i>
                Dashboard
            </a>
        </li>
        <li><span class="text-muted">•</span></li>
        <li class="text-muted">Branch List</li>
    </ul>
</div>
<!-- Branch Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ count($branches) }}</div>
            <div class="stats-label">Total Branches</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $branches->unique('district_id')->count() }}</div>
            <div class="stats-label">Districts Covered</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $branches->unique('region_id')->count() }}</div>
            <div class="stats-label">Regions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $branches->where('created_at', '>=', now()->startOfYear())->count() }}</div>
            <div class="stats-label">New This Year</div>
        </div>
    </div>
</div>

<!-- Branch Management Table -->
<div class="modern-card">
    <div class="modern-card-header">
        <h5 class="modern-card-title">
            <i class="bx bx-table"></i>
            Branch Directory
            <span class="badge bg-primary ms-2">{{ count($branches) }} branches</span>
        </h5>
        <button class="modern-btn modern-btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus"></i>
            Create New Branch
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table mb-0" id="dataTable" data-page-length="10">
                <thead>
                    <tr>
                        <th><i class="bx bx-hash me-1"></i>#</th>
                        <th><i class="bx bx-buildings me-1"></i>Branch Name</th>
                        <th><i class="bx bx-map-alt me-1"></i>District</th>
                        <th><i class="bx bx-world me-1"></i>Region</th>
                        <th><i class="bx bx-calendar me-1"></i>Registered</th>
                        <th><i class="bx bx-cog me-1"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td><span class="branch-badge">{{ ucwords($branch->name) }}</span></td>
                            <td>{{ $branch->district->name ?? 'N/A' }}</td>
                            <td>{{ $branch->region->name ?? 'N/A' }}</td>
                            <td>{{ $branch->created_at->format('F j, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button
                                        class="modern-btn modern-btn-success modern-btn-sm editBranchBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}"
                                        data-district="{{ $branch->district_id }}"
                                        data-region="{{ $branch->region_id }}">
                                        <i class="bx bx-edit"></i>
                                        Edit
                                    </button>
                                    <button
                                        class="modern-btn modern-btn-danger modern-btn-sm deleteBranchBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}">
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>
                                </div>
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
                    <button type="button" class="modern-btn btn-secondary modern-btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        Close
                    </button>
                    <button type="submit" class="modern-btn modern-btn-primary modern-btn-sm">
                        <i class="bx bx-save"></i>
                        Save Branch
                    </button>
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
                    <button type="button" class="modern-btn btn-secondary modern-btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        Close
                    </button>
                    <button type="submit" class="modern-btn modern-btn-primary modern-btn-sm">
                        <i class="bx bx-save"></i>
                        Update Branch
                    </button>
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
                    <button type="button" class="modern-btn btn-secondary modern-btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i>
                        Cancel
                    </button>
                    <button type="submit" class="modern-btn modern-btn-danger modern-btn-sm">
                        <i class="bx bx-trash"></i>
                        Delete Branch
                    </button>
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

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Branches:",
            lengthMenu: "Show _MENU_ branches per page",
            info: "Showing _START_ to _END_ of _TOTAL_ branches",
            infoEmpty: "No branches available",
            infoFiltered: "(filtered from _MAX_ total branches)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4], className: 'text-center' },
            { targets: 5, orderable: false }
        ]
    });
});
</script>

@endsection
