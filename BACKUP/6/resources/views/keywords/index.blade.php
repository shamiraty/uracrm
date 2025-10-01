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

    .keywords-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .keywords-header::before {
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

    .keywords-stats {
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
        text-decoration: none;
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

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="keywords-header">
        <h1 class="header-title">
            <i class="bx bx-key"></i>
            Keywords Management
        </h1>
        <p class="header-subtitle">
            Manage system keywords and codes for efficient classification
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="keywords-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-key"></i>
            </div>
            <div class="stat-value">{{ count($keywords) }}</div>
            <div class="stat-label">Total Keywords</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-code-alt"></i>
            </div>
            <div class="stat-value">{{ $keywords->where('code', '!=', null)->count() }}</div>
            <div class="stat-label">Coded Keywords</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="stat-value">{{ count($keywords) }}</div>
            <div class="stat-label">Active Keywords</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">100%</div>
            <div class="stat-label">Usage Rate</div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert modern-alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert modern-alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Keywords Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="modern-card-title">
                <i class="bx bx-list-ul"></i>
                Keywords Directory
            </h5>
            <a href="#" class="modern-btn modern-btn-primary modern-btn-sm">
                <i class="bx bx-plus"></i> Add Keywords
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th><i class="bx bx-key me-2"></i>Keyword Name</th>
                            <th><i class="bx bx-code-alt me-2"></i>Code</th>
                            <th><i class="bx bx-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keywords as $keyword)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bx bx-key text-primary"></i>
                                        </div>
                                        <strong>{{ $keyword->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                        {{ $keyword->code ?? 'No Code' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('keywords.edit', $keyword) }}" class="btn-edit" title="Edit Keyword">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('keywords.destroy', $keyword) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Delete Keyword"
                                                    onclick="return confirm('Are you sure you want to delete this keyword?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-search-alt-2 text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mt-2">No Keywords Found</h5>
                                        <p class="text-muted">Start by creating your first keyword</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enhanced Pagination -->
    @if(method_exists($keywords, 'links'))
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-wrapper">
            {{ $keywords->links() }}
        </div>
    </div>
    @endif
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
            search: "Search Keywords:",
            lengthMenu: "Show _MENU_ keywords per page",
            info: "Showing _START_ to _END_ of _TOTAL_ keywords",
            infoEmpty: "No keywords available",
            infoFiltered: "(filtered from _MAX_ total keywords)",
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
            { orderable: false, targets: [2] } // Disable sorting on Actions column
        ]
    });
});
</script>

@endsection
