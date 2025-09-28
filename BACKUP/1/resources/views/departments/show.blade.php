{{-- resources/views/departments/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Department Title -->
    <h5 class="mb-3">{{ $department->name }}</h5>

    <!-- Department Details Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Department Details</h5>
        </div>
        <div class="card-body">
            <p class="lead">{{ $department->description }}</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item" data-bs-toggle="tooltip" title="Department Created Date">
                    <strong>Created On:</strong> {{ $department->created_at->format('d M Y') }}
                </li>
                <li class="list-group-item" data-bs-toggle="tooltip" title="Department Last Updated">
                    <strong>Last Updated:</strong> {{ $department->updated_at->diffForHumans() }}
                </li>
            </ul>

            <!-- Edit Button inside the card -->
            <div class="mt-3">
                <button class="btn btn-secondary" id="editDepartmentBtn">
                    <i class="fa fa-edit"></i> Edit Department
                </button>
            </div>
        </div>
    </div>

    <!-- Back to Departments Button -->
    <div class="mt-3">
        <a href="{{ route('departments.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Back to Departments
        </a>
    </div>
</div>

@endsection

<!-- Push necessary scripts for tooltips -->
@push('scripts')
<script>
    // Initialize Bootstrap Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Placeholder function to handle edit button
    document.getElementById('editDepartmentBtn').addEventListener('click', function() {
        alert('Edit Department functionality can be implemented here.');
    });
</script>
@endpush
