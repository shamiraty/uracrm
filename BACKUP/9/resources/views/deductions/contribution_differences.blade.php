@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2" style="color: #17479E;">
                <i class="fas fa-chart-bar me-2"></i>Contribution Differences Analysis
            </h2>
            <p class="text-muted mb-0">Compare deduction code 667 contributions across consecutive periods</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm rounded-pill shadow-sm" style="background: #17479E; color: white;" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i>Filters
            </button>
            @if(count($differences) > 0)
                <a href="{{ route('deduction667.differences.export', request()->query()) }}"
                   class="btn btn-success btn-sm rounded-pill shadow-sm">
                    <i class="fas fa-download me-1"></i>Export CSV
                </a>
            @endif
        </div>
    </div>

    @if(count($differences) > 0)
        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(23, 71, 158, 0.1);">
                                    <i class="fas fa-users" style="color: #17479E; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold" style="color: #17479E;">{{ count($differences) }}</h3>
                                <p class="text-muted small mb-0">Total Records</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(16, 220, 96, 0.1);">
                                    <i class="fas fa-arrow-up" style="color: #10dc60; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold text-success">{{ collect($differences)->where('change_comment', 'Increase')->count() }}</h3>
                                <p class="text-muted small mb-0">Increased</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(240, 65, 65, 0.1);">
                                    <i class="fas fa-arrow-down" style="color: #f04141; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold text-danger">{{ collect($differences)->where('change_comment', 'Decrease')->count() }}</h3>
                                <p class="text-muted small mb-0">Decreased</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(0, 188, 212, 0.1);">
                                    <i class="fas fa-building" style="color: #00BCD4; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold" style="color: #00BCD4;">{{ collect($differences)->unique('deptName')->count() }}</h3>
                                <p class="text-muted small mb-0">Departments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Differences Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1.25rem 1.5rem;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-table me-2"></i>Contribution Differences Results
                    </h5>
                    <span class="badge bg-white px-3 py-2 fw-bold" style="color: #17479E;">
                        {{ count($differences) }} Records
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="differencesTable" class="table table-hover mb-0 modern-table">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">SN</th>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Employee Name</th>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Monthly Salary</th>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Vote Code</th>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Department</th>
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Change Type</th>
                                @php
                                    $monthYears = collect($differences)->flatMap(fn($diff) => array_keys($diff['details']))->unique()->sort()->values()->toArray();
                                @endphp
                                @foreach($monthYears as $my)
                                    <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($my . '-01')->format('M Y') }}</th>
                                @endforeach
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($differences as $key => $difference)
                                <tr>
                                    <td class="align-middle px-4">{{ $key + 1 }}</td>
                                    <td class="align-middle px-4">{{ trim("{$difference['firstName']} {$difference['middleName']} {$difference['lastName']}") }}</td>
                                    <td class="align-middle px-4">{{ number_format($difference['monthlySalary'], 2) }}</td>
                                    <td class="align-middle px-4">{{ $difference['voteCode'] }}</td>
                                    <td class="align-middle px-4">{{ $difference['deptName'] }}</td>
                                    <td class="align-middle px-4">
                                        @if ($difference['change_comment'] === 'Increase')
                                            <span class="text-success fw-semibold">↑ Increase</span>
                                        @elseif ($difference['change_comment'] === 'Decrease')
                                            <span class="text-danger fw-semibold">↓ Decrease</span>
                                        @endif
                                    </td>
                                    @foreach($monthYears as $my)
                                        <td class="align-middle px-4">{{ number_format($difference['details'][$my] ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="align-middle px-4 text-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->filled('start_date') && request()->filled('end_date'))
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="fw-bold mb-2">No Differences Found</h5>
                <p class="text-muted">No differences in deduction amounts found for the selected date range and department.</p>
            </div>
        </div>
    @endif
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-filter me-2"></i>Analysis Filters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('deduction667.differences.index') }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="month_range" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1" style="color: #17479E;"></i>Month Range (Two Consecutive Months)
                            </label>
                            <input type="text" name="month_range" id="month_range" class="form-control rounded-pill"
                                   value="{{ request('month_range') }}"
                                   placeholder="Select two consecutive months">
                            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                            <small class="text-muted">Must select two consecutive months for comparison</small>
                        </div>

                        <div class="col-md-12">
                            <label for="deptName" class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-info"></i>Department Filter
                            </label>
                            <select name="deptName" id="deptName" class="form-select rounded-pill">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" {{ request('deptName') === $department ? 'selected' : '' }}>
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
                        <i class="fas fa-search me-1"></i>Analyze Differences
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
    // Enhanced date range picker for consecutive months
    flatpickr("#month_range", {
        mode: "range",
        maxDate: new Date(),
        dateFormat: "Y-m-d",
        allowInput: true,
        clickOpens: true,
        yearSelectorRange: 100,
        monthSelectorType: "dropdown",
        locale: {
            rangeSeparator: " to "
        },
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0];
                const endDate = selectedDates[1];

                const startMonth = startDate.getMonth();
                const startYear = startDate.getFullYear();
                const endMonth = endDate.getMonth();
                const endYear = endDate.getFullYear();

                // Check if dates are in consecutive months
                const isConsecutive =
                    (startYear === endYear && endMonth === startMonth + 1) ||
                    (endYear === startYear + 1 && startMonth === 11 && endMonth === 0);

                if (!isConsecutive) {
                    alert("Please select a date range within two consecutive months.");
                    instance.clear();
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("input[name='month_range']").val('');
                } else {
                    const formattedStartDate = instance.formatDate(startDate, 'Y-m-d');
                    const formattedEndDate = instance.formatDate(endDate, 'Y-m-d');
                    $("#start_date").val(formattedStartDate);
                    $("#end_date").val(formattedEndDate);
                    $("input[name='month_range']").val(dateStr);
                }
            }
        }
    });

    // Initialize DataTable
    @if(count($differences) > 0)
    $('#differencesTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            emptyTable: "No difference records available",
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
