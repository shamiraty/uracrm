{{-- resources/views/branches/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h6 class="mb-0 text-uppercase">Branch Details</h6>
    <hr/>

    <!-- Toggle Button to Show/Hide Branch Details -->
    <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#branchDetails" aria-expanded="false" aria-controls="branchDetails">
        Show/Hide Branch Details
    </button>

    <!-- Branch Details Card with Collapse -->
    <div class="collapse show" id="branchDetails">
        <div class="card">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" data-bs-toggle="tooltip" title="Branch Name">
                        <strong>Name:</strong> {{ $branch->name }}
                    </li>
                    <li class="list-group-item" data-bs-toggle="tooltip" title="Branch Location">
                        <strong>Location:</strong> {{ $branch->location }}
                    </li>
                    <li class="list-group-item" data-bs-toggle="tooltip" title="District Name">
                        <strong>District:</strong> {{ $branch->district->name }}
                    </li>
                    <li class="list-group-item" data-bs-toggle="tooltip" title="Region Name">
                        <strong>Region:</strong> {{ $branch->region->name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Back Button with Hover Effect -->
    <div class="mt-3">
        <a href="{{ route('branches.index') }}" class="btn btn-primary" data-bs-toggle="tooltip" title="Go back to the list">
            <i class="fa fa-arrow-left"></i> Back to list
        </a>
    </div>
</div>
@endsection

<!-- Include Bootstrap's JavaScript for Tooltips and Collapse functionality -->
@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
