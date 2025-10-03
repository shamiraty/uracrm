@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2" style="color: #17479E;">
                <i class="fas fa-coins me-2"></i>Member Contributions
            </h2>
            <p class="text-muted mb-0">Track and manage member contribution records</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm rounded-pill shadow-sm" style="background: #17479E; color: white;" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i>Filters
            </button>
        </div>
    </div>

    <!-- Contributions List Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fas fa-table me-2"></i>Member Contributions
                </h5>
                <span class="badge bg-white px-3 py-2 fw-bold" style="color: #17479E;">
                    {{ count($contributions) }} records
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 modern-table">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Check Number</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">National ID</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Full Name</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Monthly Salary</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Contribution</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Vote Code</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Department</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contributions as $record)
                        <tr>
                            <td class="align-middle px-4">
                                <span class="badge  px-3 py-2" style="background: #17479E;">{{ $record->checkNumber }}</span>
                            </td>
                            <td class="align-middle px-4">
                                <span class="text-muted">{{ $record->nationalId }}</span>
                            </td>
                            <td class="align-middle px-4">
                                <strong class="text-muted">{{ $record->firstName }} {{ $record->middleName }} {{ $record->lastName }}</strong>
                            </td>
                            <td class="align-middle px-4">
                                <span class="text-success">
                                    {{ number_format($record->monthlySalary, 2) }}
                                </span>
                            </td>
                            <td class="align-middle px-4">
                                <span class="" style="color: #17479E;">
                                    {{ number_format($record->deductionAmount, 2) }}
                                </span>
                            </td>
                            <td class="align-middle px-4">
                                <span class="badge bg-secondary  px-3 py-2">{{ $record->voteCode }}</span>
                            </td>
                            <td class="align-middle px-4">
                                <span class="badge bg-secondary  px-3 py-2">{{ $record->deptName }}</span>
                            </td>
                            <td class="align-middle px-4 text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill" type="button"
                                            id="dropdownMenuButton-{{ $record->checkNumber }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" aria-labelledby="dropdownMenuButton-{{ $record->checkNumber }}">
                                        <li>
                                            <a class="dropdown-item rounded-2" href="{{ route('deductions.contributiondetails', ['checkNumber' => $record->checkNumber]) }}">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>View Details
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item rounded-2" href="{{ route('deductions.details', ['checkNumber' => $record->checkNumber]) }}">
                                                <i class="fas fa-calculator me-2 text-info"></i>Loan Analysis
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($contributions->hasPages())
        <div class="card-footer bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $contributions->firstItem() }} to {{ $contributions->lastItem() }} of {{ number_format($contributions->total()) }} results
                </div>
                <div>
                    {{ $contributions->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-filter me-2"></i>Advanced Search & Filter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('deductions.contributions.handle') }}" method="GET">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1" style="color: #17479E;"></i>Date
                            </label>
                            <input type="date" name="date" class="form-control rounded-pill" value="{{ request('date', '2025-01-31') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag me-1 text-info"></i>Check Number
                            </label>
                            <input type="text" name="checkNumber" class="form-control rounded-pill" placeholder="Enter check number" value="{{ request('checkNumber') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-success"></i>First Name
                            </label>
                            <input type="text" name="firstName" class="form-control rounded-pill" placeholder="Enter first name" value="{{ request('firstName') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-success"></i>Middle Name
                            </label>
                            <input type="text" name="middleName" class="form-control rounded-pill" placeholder="Enter middle name" value="{{ request('middleName') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-success"></i>Last Name
                            </label>
                            <input type="text" name="lastName" class="form-control rounded-pill" placeholder="Enter last name" value="{{ request('lastName') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-cog me-1 text-secondary"></i>Action
                            </label>
                            <select name="action" class="form-select rounded-pill">
                                <option value="view">View Data</option>
                                <option value="export">Export CSV</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn rounded-pill px-4" style="background: #17479E; color: white;">
                        <i class="fas fa-search me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modern-table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background-color: rgba(23, 71, 158, 0.03);
    transform: scale(1.001);
}

.form-control:focus, .form-select:focus {
    border-color: #17479E;
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.15);
}
</style>

@endsection
