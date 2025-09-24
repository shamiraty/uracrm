@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Deduction Analysis (Code: 667)</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Deduction Analysis (Code: 667)</li>
        </ul>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong class="text-muted">Filter Options</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('deductions.contribution_analysis') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="checkDate" class="form-label">Check Date</label>
                    <input type="text" name="checkDate" id="checkDate" class="form-control"
                           value="{{ request('checkDate') }}"
                           placeholder="Select check date" required>
                </div>
                <div class="col-md-3">
                    <label for="deptName" class="form-label">Department</label>
                    <select name="deptName" id="deptName" class="form-select wizard-required custom-select-dropdown">
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            <option class="text-uppercase" value="{{ $department }}" {{ request('deptName') == $department ? 'selected' : '' }}>
                                {{ $department }}<hr class="border-primary">
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="deduction_filter" class="form-label">Deduction Amount</label>
                    <select name="deduction_filter" id="deduction_filter" class="form-control wizard-required custom-select-dropdown">
                        <option value="">All</option>
                        <option value="greater" {{ request('deduction_filter') == 'greater' ? 'selected' : '' }}>Greater Than</option>
                        <option value="greater_or_equal" {{ request('deduction_filter') == 'greater_or_equal' ? 'selected' : '' }}>Greater Than or Equal</option>
                        <option value="less" {{ request('deduction_filter') == 'less' ? 'selected' : '' }}>Less Than</option>
                        <option value="less_or_equal" {{ request('deduction_filter') == 'less_or_equal' ? 'selected' : '' }}>Less Than or Equal</option>
                        <option value="between" {{ request('deduction_filter') == 'between' ? 'selected' : '' }}>In Between</option>
                        <option value="exact" {{ request('deduction_filter') == 'exact' ? 'selected' : '' }}>Exactly</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="deduction_min" class="form-label visually-hidden">Min Amount</label>
                    <input type="number" name="deduction_min" id="deduction_min" class="form-control" placeholder="Min Amount" value="{{ request('deduction_min') }}">
                </div>

                <div class="col-md-3" id="deduction_max_div" style="display: none;">
                    <label for="deduction_max" class="form-label visually-hidden">Max Amount</label>
                    <input type="number" name="deduction_max" id="deduction_max" class="form-control" placeholder="Max Amount" value="{{ request('deduction_max') }}">
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-auto">
                    <a href="{{ route('deductions.export_analysis', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </a>
                </div>
            </form>
            <div class="mt-2">
                <small class="text-muted"><strong>Filtered Records:</strong> {{ $count }}</small>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover border-primary-table" id="dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th>Check No.</th>
                            <th>Name</th>
                            <th>Monthly Salary</th>
                            <th>Deduction Amount</th>
                            <th>Department</th>
                            <th>Check Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deductions as $deduction)
                            <tr>
                                <td>{{ $deduction->checkNumber }}</td>
                                <td>{{ trim("{$deduction->firstName} {$deduction->middleName} {$deduction->lastName}") }}</td>
                                <td>{{ number_format($deduction->monthlySalary, 2) }}</td>
                                <td>{{ number_format($deduction->deductionAmount, 2) }}</td>
                                <td>{{ $deduction->deptName }}</td>
                                <td>{{ \Carbon\Carbon::parse($deduction->checkDate)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        flatpickr("#checkDate", {
            dateFormat: "Y-m-d",
            maxDate: new Date(),
            minDate: (() => {
                const today = new Date();
                const twoMonthsAgo = new Date(today);
                twoMonthsAgo.setMonth(today.getMonth() - 2);
                return twoMonthsAgo;
            })(),
        });

        const deductionFilter = document.getElementById('deduction_filter');
        const deductionMaxDiv = document.getElementById('deduction_max_div');

        deductionFilter.addEventListener('change', function () {
            deductionMaxDiv.style.display = (this.value === 'between') ? 'block' : 'none';
        });

        if (typeof $('.select2').select2 === 'function') {
            $('.select2').select2();
        }
    });
</script>
@endsection