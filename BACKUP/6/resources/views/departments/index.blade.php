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

    .departments-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .departments-header::before {
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

    .dept-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-8px);
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

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        justify-content: between;
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

    .btn-view {
        background: linear-gradient(135deg, #10dc60 0%, #00e676 100%);
        color: white;
        border: none;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 220, 96, 0.3);
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

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    /* Enhanced Pagination Styles */
    .pagination-wrapper .pagination {
        --bs-pagination-color: var(--ura-primary);
        --bs-pagination-border-color: rgba(23, 71, 158, 0.2);
        --bs-pagination-hover-color: white;
        --bs-pagination-hover-bg: var(--ura-primary);
        --bs-pagination-active-bg: var(--ura-gradient);
        --bs-pagination-active-border-color: var(--ura-primary);
    }

    .pagination-wrapper .page-link {
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
        color: var(--ura-primary);
        border: 1px solid rgba(23, 71, 158, 0.2);
    }

    .pagination-wrapper .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 71, 158, 0.2);
        background: var(--ura-primary);
        border-color: var(--ura-primary);
        color: white;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--ura-gradient);
        border-color: var(--ura-primary);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="departments-header">
        <h1 class="header-title">
            <i class="bx bx-sitemap"></i>
            Departments Management
        </h1>
        <p class="header-subtitle">
            Organize and manage organizational departments with comprehensive oversight
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="dept-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-sitemap"></i>
            </div>
            <div class="stat-value">{{ count($departments) }}</div>
            <div class="stat-label">Total Departments</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-group"></i>
            </div>
            <div class="stat-value">{{ count($departments) * 15 }}</div>
            <div class="stat-label">Estimated Employees</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="stat-value">{{ count($departments) }}</div>
            <div class="stat-label">Active Departments</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">100%</div>
            <div class="stat-label">Operational Rate</div>
        </div>
    </div>
    <!-- Departments Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-list-ul"></i>
                Departments Directory
            </h5>
            <button type="button" class="modern-btn modern-btn-primary modern-btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bx bx-plus"></i> Add Department
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th><i class="bx bx-hash me-2"></i>ID</th>
                            <th><i class="bx bx-sitemap me-2"></i>Department Name</th>
                            <th><i class="bx bx-file-blank me-2"></i>Description</th>
                            <th><i class="bx bx-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bx bx-sitemap text-primary"></i>
                                        </div>
                                        <strong>{{ $department->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $department->description ?? 'No description available' }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-view" data-bs-toggle="modal" data-bs-target="#viewModal{{ $department->id }}" title="View Department">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $department->id }}" title="Edit Department">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <button class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $department->id }}" title="Delete Department">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h6 class="modal-title text-secondary" id="editModalLabel">Edit Department</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('departments.update', $department->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Department Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description">{{ $department->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteModal{{ $department->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h6 class="modal-title text-secondary" id="deleteModalLabel">Delete Department</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this department?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn- btn-sm" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn- btn-sm">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    @if(method_exists($departments, 'links'))
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-wrapper">
            {{ $departments->links() }}
        </div>
    </div>
    @endif

    <!-- Enhanced Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: var(--ura-shadow-hover);">
                <div class="modal-header" style="background: var(--ura-gradient); color: white; border-bottom: none; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" style="font-weight: 600; color: white; display: flex; align-items: center; gap: 0.5rem;" id="createModalLabel">
                        <i class="bx bx-plus-circle"></i>
                        Create New Department
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
                </div>
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding: 2rem;">
                        <div class="mb-3">
                            <label for="name" class="form-label" style="color: var(--ura-primary); font-weight: 600;">
                                <i class="bx bx-sitemap me-2"></i>Department Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                   style="border: 2px solid rgba(23, 71, 158, 0.1); border-radius: 8px; padding: 0.75rem 1rem;"
                                   placeholder="Enter department name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label" style="color: var(--ura-primary); font-weight: 600;">
                                <i class="bx bx-file-blank me-2"></i>Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      style="border: 2px solid rgba(23, 71, 158, 0.1); border-radius: 8px; padding: 0.75rem 1rem;"
                                      placeholder="Enter department description (optional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid rgba(23, 71, 158, 0.1); background: var(--ura-gradient-light);">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-2"></i>Cancel
                        </button>
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <i class="bx bx-plus me-2"></i>Create Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewModal{{ $department->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $department->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="modal-title text-secondary" id="viewModalLabel{{ $department->id }}">View Department</>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Name: {{ $department->name }}</p>
                <p>Description: {{ $department->description }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
            search: "Search Departments:",
            lengthMenu: "Show _MENU_ departments per page",
            info: "Showing _START_ to _END_ of _TOTAL_ departments",
            infoEmpty: "No departments available",
            infoFiltered: "(filtered from _MAX_ total departments)",
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
            { orderable: false, targets: [3] } // Disable sorting on Actions column
        ]
    });
});
</script>

@endsection
