

@extends('layouts.app')
@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Assigned</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          all Loan applications

        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Loan applications</li>
    </ul>
  </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0"> <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                <i class="bx bxs-plus-square"></i> loan applications
            </a></h5>




            <!-- Button to export enquiries to CSV -->
            <a href="{{ route('export.loan.applications') }}" class="btn btn-success">
    Export Loan Applications as CSV
</a>







      </div>
      <div class="card-body">
        <div class="table-responsive">
        <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Check Number</th>
                            <th>Full Name</th>
                            <th>Loan Amount</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enquiries as $enquiry)
                        @if($enquiry->enquirable && $enquiry->enquirable_type === 'App\Models\LoanApplication')
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-primary-600">{{ $enquiry->check_number }}</td>
                                    <td>{{ $enquiry->full_name }}</td>
                                    <td>{{ number_format($enquiry->enquirable->loan_amount, 2) }}</td>
                                    <td>{{ $enquiry->enquirable->loan_duration }} months</td>
                                    <td>
                                        @switch($enquiry->enquirable->status)
                                            @case('rejected')
                                                <div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
                                                    <i class='bx bxs-x-circle align-middle me-1'></i>Rejected
                                                </div>
                                                @break
                                            @case('approved')
                                                <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                    <i class='bx bxs-check-circle align-middle me-1'></i>Approved
                                                </div>
                                                @break
                                            @case('pending')
                                                <div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">
                                                    <i class='bx bxs-hourglass align-middle me-1'></i>Pending
                                                </div>
                                                @break
                                            @case('processed')
                                                <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                    <i class='bx bxs-circle align-middle me-1'></i>Processed
                                                </div>
                                                @break
                                            @default
                                                <div class="badge rounded-pill text-secondary bg-light-secondary p-2 text-uppercase px-3">
                                                    <i class='bx bxs-info-circle align-middle me-1'></i>Unknown Status
                                                </div>
                                        @endswitch
                                    </td>

                                        <td>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                                    <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    @if($enquiry->enquirable_type === 'App\Models\LoanApplication')
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewLoanDetailsModal-{{ $enquiry->enquirable->id }}">
                                                                <i class="fa fa-eye me-2"></i> View
                                                            </a>
                                                        </li>
                                                        <hr class="dropdown-divider">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#processLoanModal-{{ $enquiry->enquirable->id }}">
                                                                <i class="fa fa-cogs me-2"></i> Process Loan
                                                            </a>
                                                        </li>
                                                        <hr class="dropdown-divider">
                                                        <li>
                                                            <a href="#" class="dropdown-item" onclick="triggerLoanApprovalSend({{ $enquiry->enquirable->id }})">
                                                                <i class="fa fa-check-circle me-2"></i>Approve Loan
                                                            </a>
                                                        </li>
                                                        <hr class="dropdown-divider">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectLoanModal-{{ $enquiry->enquirable->id }}">
                                                                <i class="fa fa-times-circle me-2"></i> Reject Loan
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>


                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  <!-- Modals for each loan application -->
  @foreach ($enquiries as $enquiry)
  @if($enquiry->enquirable && $enquiry->enquirable_type === 'App\Models\LoanApplication')
      @include('modals.approve_loan', ['loanApplication' => $enquiry->enquirable])
      @include('modals.process_loan', ['loanApplication' => $enquiry->enquirable])
      @include('modals.reject_loan', ['loanApplication' => $enquiry->enquirable])
      @include('modals.view_loan', ['loanApplication' => $enquiry->enquirable])
  @endif
@endforeach

@endsection
