@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Breadcrumb -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Salary Loan List</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Salary Loan List</li>
    </ul>
  </div>

  <!-- Filter Form (placed immediately after breadcrumb) -->
  <form action="{{ route('deductions.salary.loans') }}" method="GET">
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="row w-100">
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date', $date) }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="checkNumber" class="form-control form-control-sm" placeholder="Check Number" value="{{ request('checkNumber', $checkNumber) }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="firstName" class="form-control form-control-sm" placeholder="First Name" value="{{ request('firstName', $firstName) }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="middleName" class="form-control form-control-sm" placeholder="Middle Name" value="{{ request('middleName', $middleName) }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="lastName" class="form-control form-control-sm" placeholder="Last Name" value="{{ request('lastName', $lastName) }}">
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <select name="action" class="form-control form-control-sm">
                            <option value="view" {{ request('action', 'view') == 'view' ? 'selected' : '' }}>View Data</option>
                            <option value="export" {{ request('action', 'view') == 'export' ? 'selected' : '' }}>Export CSV</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

  <!-- Salary Loan List Table -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table border-primary-table mb-0"id="dataTable">
          <thead>
            <tr>
              
              <th scope="col">Check Number</th>
              <th scope="col">Name</th>
              <th scope="col">Monthly Salary</th>
              <th scope="col">Deduction Amount</th>
              <th scope="col">Balance Amount</th>
              <th scope="col">Deduction Description</th>
              <th scope="col">Vote Code</th>
              <th scope="col">Department</th>
              <th scope="col">Month</th>  <!--(Balance - Deduction)-->
              <th scope="col">More</th>
              
              
            </tr>
          </thead>
          <tbody>
            @foreach($salaryLoans as $index => $record)
            <tr>
            
              <td>{{ $record->checkNumber }}</td>
              <td>{{ $record->firstName }} {{ $record->middleName }} {{ $record->lastName }}</td>
              <td>{{ number_format($record->monthlySalary, 2) }}</td>
              <td>{{ number_format($record->deductionAmount, 2) }}</td>
              <td>{{ number_format($record->balanceAmount, 2) }}</td>
              <td>{{ $record->deductionDesc }}</td>
              <td>{{ $record->voteCode }}</td>
              <td>{{ $record->deptName }}</td>
              <td>{{ number_format($record->month, 2) }}</td> <!-- Display the calculated month -->
              <td>
    <!-- Dropdown for actions -->
    <div class="dropdown">
        <a class="dropdown-toggle btn btn-primary btn-sm" href="#" role="button" id="dropdownMenuLink-{{ $record->checkNumber }}" data-bs-toggle="dropdown" aria-expanded="false">
            Actions
        </a>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink-{{ $record->checkNumber }}">
            <li>
                <a class="dropdown-item" href="{{ route('deductions.details', ['checkNumber' => $record->checkNumber]) }}">
                    <i class='bx bx-show font-22'></i> Loan Details
                </a>
            </li><hr>
            <li>
                <a class="dropdown-item" href="{{ route('deductions.contributiondetails', ['checkNumber' => $record->checkNumber]) }}">
                    <i class='bx bx-show font-22'></i>Contribution Details
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
    </div><!-- card-body end -->
  </div><!-- card end -->

  <div class="mt-3">
    {{ $salaryLoans->appends(request()->query())->links() }}
  </div>
</div>
@endsection
