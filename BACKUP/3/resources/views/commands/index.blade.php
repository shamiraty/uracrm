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

    .modern-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 12px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 15s ease-in-out infinite;
    }

    .modern-header h1 {
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .modern-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--ura-shadow);
    }

    .modern-alert.alert-success {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.1) 0%, rgba(16, 220, 96, 0.05) 100%);
        border-left: 4px solid var(--ura-success);
        color: #0d5e2d;
    }

    .modern-alert.alert-danger {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.1) 0%, rgba(240, 65, 65, 0.05) 100%);
        border-left: 4px solid var(--ura-danger);
        color: #721c24;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
        background: white;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
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
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
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
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn-edit {
        background: linear-gradient(135deg, #00BCD4 0%, #4DD0E1 100%);
        color: white;
        border: none;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #f04141 0%, #ff5252 100%);
        color: white;
        border: none;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(240, 65, 65, 0.3);
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .stat-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 3rem;
        height: 3rem;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ura-primary);
        font-size: 1.5rem;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="modern-header text-center">
        <h1><i class="bx bx-command me-3"></i>Commands Management</h1>
        <p>Manage and organize command structure across regions and branches</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{
                is_object($commands) && method_exists($commands, 'total')
                    ? $commands->total()
                    : (is_object($commands) && method_exists($commands, 'count')
                        ? $commands->count()
                        : count($commands ?? []))
            }}</div>
            <p class="stat-label">Total Commands</p>
            <div class="stat-icon">
                <i class="bx bx-command"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{
                is_object($regions) && method_exists($regions, 'count')
                    ? $regions->count()
                    : count($regions ?? [])
            }}</div>
            <p class="stat-label">Active Regions</p>
            <div class="stat-icon">
                <i class="bx bx-world"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{
                is_object($branches) && method_exists($branches, 'count')
                    ? $branches->count()
                    : count($branches ?? [])
            }}</div>
            <p class="stat-label">Total Branches</p>
            <div class="stat-icon">
                <i class="bx bx-buildings"></i>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{
                is_object($districts) && method_exists($districts, 'count')
                    ? $districts->count()
                    : count($districts ?? [])
            }}</div>
            <p class="stat-label">Districts Covered</p>
            <div class="stat-icon">
                <i class="bx bx-map"></i>
            </div>
        </div>
    </div>

    <!-- Alert for success or error -->
    @if (session('success'))
    <div class="alert modern-alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert modern-alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>{{ implode(', ', $errors->all()) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Commands Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-list-ul"></i>
                Commands Directory
            </h5>
            <button class="modern-btn modern-btn-primary modern-btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bx bx-plus"></i>Add Command
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th><i class="bx bx-user me-2"></i>Command Name</th>
                            <th><i class="bx bx-world me-2"></i>Region</th>
                            <th><i class="bx bx-buildings me-2"></i>Branch</th>
                            <th><i class="bx bx-map me-2"></i>District</th>
                            <th><i class="bx bx-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commands as $command)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="bx bx-command text-primary"></i>
                                    </div>
                                    <strong>{{ ucwords($command->name) }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    {{ ucwords($command->region->name) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                    {{ ucwords($command->branch->name) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    {{ ucwords($command->district->name) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#updateModal"
                                            data-id="{{ $command->id }}"
                                            data-name="{{ $command->name }}"
                                            data-region-id="{{ $command->region_id }}"
                                            data-branch-id="{{ $command->branch_id }}"
                                            data-district-id="{{ $command->district_id }}"
                                            title="Edit Command">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-delete delete-btn" data-id="{{ $command->id }}" title="Delete Command">
                                        <i class="bx bx-trash"></i>
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
    </div>
</div>

<style>
    .modern-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow-hover);
    }

    .modern-modal .modal-header {
        background: var(--ura-gradient);
        color: white;
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
    }

    .modern-modal .modal-title {
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-modal .btn-close {
        filter: brightness(0) invert(1);
    }

    .modern-modal .modal-body {
        padding: 2rem;
    }

    .modern-modal .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .modern-modal .form-control,
    .modern-modal .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-modal .form-control:focus,
    .modern-modal .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .modern-modal .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(23, 71, 158, 0.1);
        background: var(--ura-gradient-light);
    }
</style>

<!-- Create Modal -->
<div class="modal fade modern-modal" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('commands.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-plus-circle"></i>
                        Add New Command
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bx bx-user me-2"></i>Command Name
                                </label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter command name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="region_id" class="form-label">
                                    <i class="bx bx-world me-2"></i>Region
                                </label>
                                <select name="region_id" id="region_id" class="form-select" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">
                                    <i class="bx bx-buildings me-2"></i>Branch
                                </label>
                                <select name="branch_id" id="branch_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="district_id" class="form-label">
                                    <i class="bx bx-map me-2"></i>District
                                </label>
                                <select name="district_id" id="district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-2"></i>Cancel
                    </button>
                    <button type="submit" class="modern-btn modern-btn-primary">
                        <i class="bx bx-plus me-2"></i>Add Command
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade modern-modal" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="updateForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit"></i>
                        Edit Command
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">
                                    <i class="bx bx-user me-2"></i>Command Name
                                </label>
                                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter command name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_region_id" class="form-label">
                                    <i class="bx bx-world me-2"></i>Region
                                </label>
                                <select name="region_id" id="edit_region_id" class="form-select" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_branch_id" class="form-label">
                                    <i class="bx bx-buildings me-2"></i>Branch
                                </label>
                                <select name="branch_id" id="edit_branch_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_district_id" class="form-label">
                                    <i class="bx bx-map me-2"></i>District
                                </label>
                                <select name="district_id" id="edit_district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-2"></i>Cancel
                    </button>
                    <button type="submit" class="modern-btn modern-btn-primary">
                        <i class="bx bx-save me-2"></i>Update Command
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const regionId = button.getAttribute('data-region-id');
        const branchId = button.getAttribute('data-branch-id');
        const districtId = button.getAttribute('data-district-id');

        document.getElementById('updateForm').setAttribute('action', `/commands/${id}`);
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_region_id').value = regionId;
        document.getElementById('edit_branch_id').value = branchId;
        document.getElementById('edit_district_id').value = districtId;
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                //title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/commands/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
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
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Commands:",
            lengthMenu: "Show _MENU_ commands per page",
            info: "Showing _START_ to _END_ of _TOTAL_ commands",
            infoEmpty: "No commands available",
            infoFiltered: "(filtered from _MAX_ total commands)",
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
            { orderable: false, targets: [4] } // Disable sorting on Actions column
        ]
    });
});
</script>
</div>

@endsection
