@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2" style="color: #17479E;">
                <i class="fas fa-chart-bar me-2"></i>Deduction Variance Analysis
            </h2>
            <p class="text-muted mb-0">Track and analyze deduction differences across periods</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm rounded-pill shadow-sm" style="background: #17479E; color: white;" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i>Filters
            </button>
            <a href="{{ route('deductions.export_csv', ['check_date' => request('check_date'), 'deptName' => $departmentFilter]) }}"
               class="btn btn-success btn-sm rounded-pill shadow-sm">
                <i class="fas fa-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Variance Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fas fa-table me-2"></i>Deduction Variance Analysis Results
                </h5>
                <span class="badge bg-white px-3 py-2 fw-bold" style="color: #17479E;">
                    {{ $filteredCount }} Records Found
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="varianceTable" class="table table-hover mb-0 modern-table">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Check Number</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Employee Name</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($startDate)->format('M Y') }} Deduction</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($endDate)->format('M Y') }} Deduction</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Variance</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Balance</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Description</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Period</th>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filteredData as $data)
                            <tr>
                                <td class="align-middle px-4">{{ $data['check_number'] }}</td>
                                <td class="align-middle px-4">{{ $data['name'] }}</td>
                                <td class="align-middle px-4">{{ number_format((float)($data['deduction_month_1'] ?? 0), 2) }}</td>
                                <td class="align-middle px-4">{{ number_format((float)($data['deduction_month_2'] ?? 0), 2) }}</td>
                                <td class="align-middle px-4">
                                    @php
                                        $difference = (float)($data['difference'] ?? 0);
                                        $indicator = $difference > 0 ? '↑' : ($difference < 0 ? '↓' : '=');
                                    @endphp
                                    {{ $indicator }} {{ number_format(abs($difference), 2) }}
                                </td>
                                <td class="align-middle px-4">{{ number_format((float)($data['balance'] ?? 0), 2) }}</td>
                                <td class="align-middle px-4">{{ $data['deduction_description'] }}</td>
                                <td class="align-middle px-4">{{ $data['month_computed'] }}</td>
                                <td class="align-middle px-4 text-center">
                                    <a href="{{ route('deductions.details', ['checkNumber' => $data['check_number']]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x d-block mb-3 opacity-25"></i>
                                        <h5 class="fw-bold mb-2">No Variance Data Found</h5>
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
                    <i class="fas fa-filter me-2"></i>Advanced Analysis Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('deductions.variance') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="check_date" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1" style="color: #17479E;"></i>Date Range Comparison
                            </label>
                            <input type="text" name="check_date" id="check_date" class="form-control rounded-pill"
                                   value="{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}"
                                   placeholder="Select date range for comparison">
                            <small class="text-muted">Compare deductions between two periods</small>
                        </div>

                        <div class="col-md-12">
                            <label for="deptName" class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-info"></i>Department Filter
                            </label>
                            <select name="deptName" id="deptName" class="form-select rounded-pill">
                                <option value="All" {{ $departmentFilter === 'All' ? 'selected' : '' }}>All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" {{ $departmentFilter === $department ? 'selected' : '' }}>
                                        {{ $department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn rounded-pill px-4" style="background: #17479E; color: white;">
                        <i class="fas fa-search me-1"></i>Analyze Variance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize flatpickr
    flatpickr("#check_date", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: "{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}".split(' to '),
        minDate: null,
        maxDate: null,
        allowInput: true,
        clickOpens: true,
        yearSelectorRange: 100,
        monthSelectorType: "dropdown",
        showMonths: 1,
        locale: {
            rangeSeparator: " to "
        }
    });

    // Initialize DataTable
    @if(count($filteredData) > 0)
    $('#varianceTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            emptyTable: "No variance records available",
            zeroRecords: "No matching records found"
        },
        processing: true,
        responsive: true
    });
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
</style>

@endsection
