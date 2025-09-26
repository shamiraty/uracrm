@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Breadcrumb -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Loan Repayment Tracing</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Loan Repayment Tracing</li>
    </ul>
  </div>
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong class="text-muted">Filter Options</strong>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('deductions.variance') }}" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label for="check_date" class="form-label visually-hidden">Date Range</label>
                        <input type="text" name="check_date" id="check_date" class="form-control"
                               value="{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}"
                               placeholder="Select date range">
                    </div>
                    <div class="col-md-3">
                        <label for="deptName" class="form-label visually-hidden">Department</label>
                        <select name="deptName" id="deptName" class="form-select wizard-required custom-select-dropdown">
                            <option value="All" {{ $departmentFilter === 'All' ? 'selected' : '' }}>All Departments</option>
                            @foreach ($departments as $department)
                                <option class="text-uppercase" value="{{ $department }}" {{ $departmentFilter === $department ? 'selected' : '' }}>
                                    {{ $department }}<hr>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-auto">
                        <a href="{{ route('deductions.export_csv', ['check_date' => request('check_date'), 'deptName' => $departmentFilter]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </a>
                    </div>
                </form>
                <div class="mt-2">
                    <small class="text-muted"><strong>Filtered Records:</strong> {{ $filteredCount }}</small>
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
                                <th>Deduction ({{ \Carbon\Carbon::parse($startDate)->format('M Y') }})</th>
                                <th>Deduction ({{ \Carbon\Carbon::parse($endDate)->format('M Y') }})</th>
                                <th class="">Difference</th>
                                <th>Balance</th>
                                <th>Description</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredData as $data)
                                <tr>
                                    <td>{{ $data['check_number'] }}</td>
                                    <td>{{ $data['name'] }}</td>
                                    <td>{{ $data['deduction_month_1'] }}</td>
                                    <td>{{ $data['deduction_month_2'] }}</td>
                                    <td class="text-danger"><strong>{{ $data['difference'] }}</strong></td>
                                    <td>{{ $data['balance'] }}</td>
                                    <td>{{ $data['deduction_description'] }}</td>
                                    <td>{{ $data['month_computed'] }}</td>
                                    <td>
                                        <a href="{{ route('deductions.details', ['checkNumber' => $data['check_number']]) }}" class="btn btn-sm btn-primary">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr("#check_date", {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('check_date', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}".split(' to '),
                onChange: function(selectedDates) {
                    if (selectedDates.length > 0) {
                        const startDate = selectedDates[0];
                        const firstMonthEnd = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
                        const secondMonthEnd = new Date(startDate.getFullYear(), startDate.getMonth() + 2, 0);
                        this.set('minDate', startDate);
                        this.set('maxDate', secondMonthEnd);
                    }
                }
            });

            // Initialize Select2 if you are using it
            if (typeof $('.select2').select2 === 'function') {
                $('.select2').select2();
            }
        });
    </script>
@endsection