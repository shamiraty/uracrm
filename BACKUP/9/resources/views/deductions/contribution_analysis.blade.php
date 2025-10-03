@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2" style="color: #17479E;">
                <i class="fas fa-chart-pie me-2"></i>Contribution Analysis Dashboard
            </h2>
            <p class="text-muted mb-0">Comprehensive deduction analysis with advanced filtering (Code: 667)</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm rounded-pill shadow-sm" style="background: #17479E; color: white;" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i>Filters
            </button>
            <a href="{{ route('deductions.export_analysis', request()->query()) }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
                <i class="fas fa-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Analytics Data Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fas fa-table me-2"></i>Deduction Analysis Results
                </h5>
                <span class="badge bg-white px-3 py-2 fw-bold" style="color: #17479E;">{{ number_format($count) }} Records</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="deductionsTable" class="table table-hover mb-0 modern-table">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Check Number</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Employee Name</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Monthly Salary</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Deduction Amount</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Balance Amount</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Description</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Vote Code</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Department</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Check Date</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deductions as $deduction)
                            <tr>
                                <td class="align-middle px-4">{{ $deduction->checkNumber }}</td>
                                <td class="align-middle px-4">{{ trim("{$deduction->firstName} {$deduction->middleName} {$deduction->lastName}") }}</td>
                                <td class="align-middle px-4">{{ number_format($deduction->monthlySalary, 2) }}</td>
                                <td class="align-middle px-4">{{ number_format($deduction->deductionAmount, 2) }}</td>
                                <td class="align-middle px-4">{{ number_format(($deduction->balanceAmount ?? ($deduction->monthlySalary - $deduction->deductionAmount)), 2) }}</td>
                                <td class="align-middle px-4">{{ $deduction->deductionDesc ?? 'Contribution Deduction' }}</td>
                                <td class="align-middle px-4">{{ $deduction->voteCode ?? 'N/A' }}</td>
                                <td class="align-middle px-4">{{ $deduction->deptName }}</td>
                                <td class="align-middle px-4">{{ \Carbon\Carbon::parse($deduction->checkDate)->format('d M Y') }}</td>
                                <td class="align-middle px-4 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill" type="button"
                                                id="dropdownMenuButton-{{ $deduction->checkNumber }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3" aria-labelledby="dropdownMenuButton-{{ $deduction->checkNumber }}">
                                            <li>
                                                <a class="dropdown-item rounded-2" href="{{ route('deductions.contributiondetails', ['checkNumber' => $deduction->checkNumber]) }}">
                                                    <i class="fas fa-file-alt me-2 text-primary"></i>View Details
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <a class="dropdown-item rounded-2" href="{{ route('deductions.details', ['checkNumber' => $deduction->checkNumber]) }}">
                                                    <i class="fas fa-calculator me-2 text-info"></i>Loan Analysis
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x d-block mb-3 opacity-25"></i>
                                        <h5 class="fw-bold mb-2">No Records Found</h5>
                                        <p>Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-filter me-2"></i>Advanced Analytics Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('deductions.contribution_analysis') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="checkDate" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1" style="color: #17479E;"></i>Check Date
                            </label>
                            <input type="text" name="checkDate" id="checkDate" class="form-control rounded-pill"
                                   value="{{ request('checkDate') }}"
                                   placeholder="Select check date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="deptName" class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-info"></i>Department
                            </label>
                            <select name="deptName" id="deptName" class="form-select rounded-pill">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" {{ request('deptName') == $department ? 'selected' : '' }}>
                                        {{ $department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="deduction_filter" class="form-label fw-semibold">
                                <i class="fas fa-calculator me-1 text-success"></i>Amount Filter
                            </label>
                            <select name="deduction_filter" id="deduction_filter" class="form-select rounded-pill">
                                <option value="">All Amounts</option>
                                <option value="greater" {{ request('deduction_filter') == 'greater' ? 'selected' : '' }}>Greater Than</option>
                                <option value="greater_or_equal" {{ request('deduction_filter') == 'greater_or_equal' ? 'selected' : '' }}>Greater Than or Equal</option>
                                <option value="less" {{ request('deduction_filter') == 'less' ? 'selected' : '' }}>Less Than</option>
                                <option value="less_or_equal" {{ request('deduction_filter') == 'less_or_equal' ? 'selected' : '' }}>Less Than or Equal</option>
                                <option value="between" {{ request('deduction_filter') == 'between' ? 'selected' : '' }}>In Between</option>
                                <option value="exact" {{ request('deduction_filter') == 'exact' ? 'selected' : '' }}>Exactly</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="deduction_min" class="form-label fw-semibold">
                                <i class="fas fa-money-bill-wave me-1 text-warning"></i>Minimum Amount
                            </label>
                            <input type="number" name="deduction_min" id="deduction_min" class="form-control rounded-pill"
                                   placeholder="Enter minimum amount" value="{{ request('deduction_min') }}" step="0.01">
                        </div>

                        <div class="col-md-12" id="deduction_max_div" style="display: none;">
                            <label for="deduction_max" class="form-label fw-semibold">
                                <i class="fas fa-money-bill-wave me-1 text-warning"></i>Maximum Amount
                            </label>
                            <input type="number" name="deduction_max" id="deduction_max" class="form-control rounded-pill"
                                   placeholder="Enter maximum amount" value="{{ request('deduction_max') }}" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <a href="{{ route('deductions.contribution_analysis') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-redo me-1"></i>Clear
                    </a>
                    <button type="submit" class="btn rounded-pill px-4" style="background: #17479E; color: white;">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    console.log('DOM Ready');
    console.log('jQuery version:', $.fn.jquery);
    console.log('DataTable available:', typeof $.fn.DataTable);

    // Initialize flatpickr
    flatpickr("#checkDate", {
        dateFormat: "Y-m-d",
        maxDate: new Date(),
        minDate: null,
        allowInput: true,
        clickOpens: true,
        yearSelectorRange: 100,
        monthSelectorType: "dropdown",
    });

    // Deduction filter logic
    const deductionFilter = document.getElementById('deduction_filter');
    const deductionMaxDiv = document.getElementById('deduction_max_div');
    const deductionMinInput = document.getElementById('deduction_min');

    function updateFilterInputs() {
        const filterValue = deductionFilter.value;

        if (filterValue === 'between') {
            deductionMaxDiv.style.display = 'block';
        } else {
            deductionMaxDiv.style.display = 'none';
        }

        switch(filterValue) {
            case 'greater':
                deductionMinInput.placeholder = 'Amount must be greater than...';
                break;
            case 'greater_or_equal':
                deductionMinInput.placeholder = 'Amount must be greater than or equal to...';
                break;
            case 'less':
                deductionMinInput.placeholder = 'Amount must be less than...';
                break;
            case 'less_or_equal':
                deductionMinInput.placeholder = 'Amount must be less than or equal to...';
                break;
            case 'exact':
                deductionMinInput.placeholder = 'Exact amount...';
                break;
            case 'between':
                deductionMinInput.placeholder = 'Minimum amount...';
                break;
            default:
                deductionMinInput.placeholder = 'Enter amount...';
        }
    }

    deductionFilter.addEventListener('change', updateFilterInputs);
    updateFilterInputs();

    // Initialize DataTable
    @if(count($deductions) > 0)
    console.log('Initializing DataTable...');
    var table = $('#deductionsTable');
    console.log('Table found:', table.length);
    console.log('Table rows:', table.find('tbody tr').length);

    if (table.length > 0) {
        table.DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, 'asc']],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                emptyTable: "No deduction records available",
                zeroRecords: "No matching records found"
            },
            processing: true,
            responsive: true
        });
        console.log('DataTable initialized successfully');
    }
    @else
    console.log('No data available, skipping DataTable initialization');
    @endif
});
</script>

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

.dropdown-item:hover {
    background-color: rgba(23, 71, 158, 0.1);
}
</style>

@endsection
