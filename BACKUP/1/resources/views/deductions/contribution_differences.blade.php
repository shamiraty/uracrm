@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Contributions Differences Analysis</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Deduction Code 667 Differences</li>
        </ul>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong class="text-muted">Filter Options</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('deduction667.differences.index') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="month_range" class="form-label visually-hidden">Date Range</label>
                    <input type="text" name="month_range" id="month_range" class="form-control"
                           value="{{ request('month_range') }}"
                           placeholder="Select date range">
                    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="deptName" class="form-label visually-hidden">Department</label>
                    <select name="deptName" id="deptName" class="form-select wizard-required custom-select-dropdown">
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            <option class="text-uppercase" value="{{ $department }}" {{ request('deptName') === $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
                @if(count($differences) > 0)
                    <div class="col-md-auto">
                        <a href="{{ route('deduction667.differences.export', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if(count($differences) > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover border-primary-table" id="dataTable">
                        <thead class="table-primary">
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Monthly Salary</th>
                                <th>Vote Code</th>
                                <th>Department</th>
                                <th>Change</th>
                                @php
                                    $monthYears = collect($differences)->flatMap(fn($diff) => array_keys($diff['details']))->unique()->sort()->values()->toArray();
                                @endphp
                                @foreach($monthYears as $my)
                                    <th>{{ \Carbon\Carbon::parse($my . '-01')->format('M Y') }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($differences as $key => $difference)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $difference['firstName'] }} {{ $difference['middleName'] }} {{ $difference['lastName'] }}</td>
                                    <td>{{ number_format($difference['monthlySalary'], 2) }}</td>
                                    <td>{{ $difference['voteCode'] }}</td>
                                    <td>{{ $difference['deptName'] }}</td>
                                    <td>
                                        @if ($difference['change_comment'] === 'Increase')
                                            <span class="text-success"><i class="bi bi-arrow-up"></i> Increase</span>
                                        @elseif ($difference['change_comment'] === 'Decrease')
                                            <span class="text-danger"><i class="bi bi-arrow-down"></i> Decrease</span>
                                        @endif
                                    </td>
                                    @foreach($monthYears as $my)
                                        <td>{{ number_format($difference['details'][$my] ?? '', 2) }}</td>
                                    @endforeach
                                    <td>
                                        <a href="" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i> View
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
        <div class="alert alert-info">
            No differences in deduction amounts found for Deduction Code 667 within the selected date range and department (if specified).
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script>
    document.addEventListener('DOMContentLoaded', () => {
        flatpickr("#month_range", {
            mode: "range",
            maxDate: new Date().fp_incr(0),
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];

                    const startMonth = startDate.getMonth();
                    const startYear = startDate.getFullYear();
                    const endMonth = endDate.getMonth();
                    const endYear = endDate.getFullYear();

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
                } else if (selectedDates.length > 2) {
                    alert("Please select only two dates for the range.");
                    instance.clear();
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("input[name='month_range']").val('');
                } else {
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("input[name='month_range']").val('');
                }
            }
        });

        $('.select2').select2();
    });
</script>
@endsection