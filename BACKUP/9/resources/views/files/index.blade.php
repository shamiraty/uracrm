{{-- @extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Files</h1>
            <a href="{{ route('files.create') }}" class="btn btn-primary">Create New File</a>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($files as $file)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- View Action -->
                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                            <li><hr class="dropdown-divider"></li>








<!-- Edit Action -->
<li><a class="dropdown-item"   href="{{ route('files.edit', $file) }}" ><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
<li><hr class="dropdown-divider"></li>


                            <!-- Delete Action -->
                            <li>
                                <form action="{{ route('files.destroy', $file) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="card h-100 text-dark">
                        <!-- Image covering the whole card background -->
                        <img src="{{ asset('assets/images/folder.png') }}" class="card-img" alt="Folder Image" style="opacity: 0.3;">
                        <div class="card-img-overlay d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <h5 class="card-title">{{ $file->reference_number }}</h5>
                                <p class="card-text">{{ $file->file_subject }}</p>
                                <div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection

 --}}
 {{-- @extends('layouts.app')

 @section('content')
 <div class="container mt-4">
     <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
             <h1>Files</h1>
             <a href="{{ route('files.create') }}" class="btn btn-primary">Create New File</a>
         </div>
         <div class="card-body">
             <div class="row">
                 @foreach ($files as $file)
                 <div class="col-md-4 col-sm-6 mb-4">
                     <div class="card h-100 text-dark">
                         <img src="{{ asset('assets/images/folder.png') }}" class="card-img" alt="Folder Image" style="opacity: 0.5;">
                         <div class="card-img-overlay d-flex align-items-center justify-content-center p-0">
                             <div class="text-center p-2" style="background: rgba(0, 0, 0, 0.5); width: 100%; height: 100%;">
                                 <h5 class="card-title text-white">{{ $file->reference_number }}</h5>
                                 <p class="card-text text-white">{{ $file->file_subject }}</p>
                                 <div class="dropdown position-absolute top-0 end-0 p-2">
                                     <a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                         Actions
                                     </a>
                                     <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                         <li><a class="dropdown-item" href="#"><i class="bx bx-show me-2"></i>View Detail</a></li>
                                         <li><a class="dropdown-item" href="{{ route('files.edit', $file) }}"><i class="bx bx-edit me-2"></i>Edit</a></li>
                                         <li>
                                             <form action="{{ route('files.destroy', $file) }}" method="POST">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-2"></i>Delete</button>
                                             </form>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 @endforeach
             </div>
         </div>
     </div>
 </div>
 @endsection --}}
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

    .files-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .files-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .header-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
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
        background: rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(10px);
    }

    .modern-btn:hover {
        background: white;
        color: var(--ura-primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
    }

    .files-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .file-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .file-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ura-shadow-hover);
    }

    .file-card-header {
        background: var(--ura-gradient-light);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: between;
        align-items: center;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .file-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .file-icon i {
        font-size: 2rem;
        color: white;
    }

    .file-card-body {
        padding: 1.5rem;
    }

    .file-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .file-subject {
        color: #6c757d;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .file-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .folio-badge {
        background: var(--ura-gradient);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .file-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #10dc60 0%, #00e676 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 220, 96, 0.3);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #00BCD4 0%, #4DD0E1 100%);
        color: white;
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

    /* Enhanced Pagination */
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
    }

    .pagination-wrapper .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 71, 158, 0.2);
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="files-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="bx bx-folder"></i>
                    Files Management
                </h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">
                    Organize and manage document files with comprehensive tracking
                </p>
            </div>
            <a href="{{ route('files.create') }}" class="modern-btn">
                <i class="bx bx-plus"></i>
                Create New File
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="files-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-folder"></i>
            </div>
            <div class="stat-value">{{ count($files) }}</div>
            <div class="stat-label">Total Files</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-file"></i>
            </div>
            <div class="stat-value">{{ $files->sum(function($file) { return $file->folios->count(); }) }}</div>
            <div class="stat-label">Total Folios</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="stat-value">{{ count($files) }}</div>
            <div class="stat-label">Active Files</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">{{ $files->avg(function($file) { return $file->folios->count(); }) ? number_format($files->avg(function($file) { return $file->folios->count(); }), 1) : '0' }}</div>
            <div class="stat-label">Avg Folios per File</div>
        </div>
    </div>

    <!-- Files Grid -->
    <div class="files-grid">
        @forelse ($files as $file)
        <div class="file-card">
            <div class="file-card-body">
                <div class="file-icon">
                    <i class="bx bx-folder-open"></i>
                </div>

                <h5 class="file-title">{{ $file->reference_number }}</h5>
                <p class="file-subject">{{ $file->file_subject }}</p>

                <div class="file-meta">
                    <span class="folio-badge">{{ $file->folios->count() }} Folios</span>
                    <small class="text-muted">
                        <i class="bx bx-time me-1"></i>
                        {{ $file->created_at->format('M d, Y') }}
                    </small>
                </div>

                <div class="file-actions">
                    <a href="{{ route('files.show', $file->id) }}" class="action-btn btn-view" title="View Details">
                        <i class="bx bx-show"></i>
                    </a>
                    <a href="{{ route('files.edit', $file) }}" class="action-btn btn-edit" title="Edit File">
                        <i class="bx bx-edit"></i>
                    </a>
                    <form action="{{ route('files.destroy', $file) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" title="Delete File"
                                onclick="return confirm('Are you sure you want to delete this file?')">
                            <i class="bx bx-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bx bx-folder-open" style="font-size: 4rem; color: #ccc;"></i>
                <h5 class="text-muted mt-3">No Files Found</h5>
                <p class="text-muted">Start by creating your first file</p>
                <a href="{{ route('files.create') }}" class="modern-btn" style="background: var(--ura-gradient); color: white;">
                    <i class="bx bx-plus"></i>
                    Create First File
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Enhanced Pagination -->
    @if(method_exists($files, 'links'))
    <div class="d-flex justify-content-center">
        <div class="pagination-wrapper">
            {{ $files->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
