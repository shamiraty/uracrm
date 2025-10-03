{{--

 @extends('layouts.app')

@push('styles')
<!-- Plugin css for this page -->
<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        @if($type)
                            {{ ucfirst(str_replace('_', ' ', $type)) }} Enquiries
                        @else
                            All Enquiries
                        @endif
                    </h6>
                    <a href="{{ $type ? route('enquiries.create', ['type' => $type]) : route('enquiries.create') }}" class="btn btn-primary mb-3">
                        Add {{ $type ? ucfirst(str_replace('_', ' ', $type)) : 'Enquiry' }}
                    </a>
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date Received</th>
                                    <th>Force No</th>
                                    <th>Account Number</th>
                                    <th>Bank Name</th>
                                    <th>Full Name</th>
                                    <th>District</th>
                                    <th>Phone</th>
                                    <th>Region</th>
                                    <th>Status</th> <!-- New Status Column -->
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($enquiries as $enquiry)
                                    <tr>
                                        <td>{{ $enquiry->date_received }}</td>
                                        <td>{{ $enquiry->force_no }}</td>
                                        <td>{{ $enquiry->account_number }}</td>
                                        <td>{{ $enquiry->bank_name }}</td>
                                        <td>{{ $enquiry->full_name }}</td>
                                        <td>{{ $enquiry->district }}</td>
                                        <td>{{ $enquiry->phone }}</td>
                                        <td>{{ $enquiry->region }}</td>
                                        <td>
                                            @if($enquiry->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($enquiry->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @elseif($enquiry->status == 'assigned')
                                                <span class="badge bg-warning">Assigned</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>

                                            <div class="dropdown ms-auto">
                                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                                    <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <!-- View Action -->
                                                    <li><a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}"><i class="mdi mdi-eye me-2"></i>View</a></li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Edit Action -->
                                                    <li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Assign Action -->
                                                    <li>
                                                        <form action="{{ route('enquiries.changeStatus', $enquiry->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="assign">
                                                            <button type="submit" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assignUserModal"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Approve Action -->
                                                    <li>
                                                        <form action="{{ route('enquiries.changeStatus', $enquiry->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="dropdown-item text-success"><i class="mdi mdi-check me-2"></i>Approve</button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Reject Action -->
                                                    <li>
                                                        <form action="{{ route('enquiries.changeStatus', $enquiry->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-close me-2"></i>Reject</button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Delete Action -->
                                                    <li>
                                                        <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Delete</button>
                                                        </form>
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
            </div>
        </div>
    </div>

    @foreach($enquiries as $enquiry)
    <!-- Response Modal -->
    <div class="modal fade" id="responseModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="responseModalLabel-{{ $enquiry->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Response for Enquiry #{{ $enquiry->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($enquiry->response)
                        <p><strong>Amount:</strong> {{ $enquiry->response->amount }}</p>
                        <p><strong>Interest:</strong> {{ $enquiry->response->interest }}</p>
                        <p><strong>Remarks:</strong> {{ $enquiry->response->remarks }}</p>
                        <p><strong>Description:</strong> {{ $enquiry->response->description }}</p>
                        <p><strong>From Amount:</strong> {{ $enquiry->response->from_amount }}</p>
                        <p><strong>To Amount:</strong> {{ $enquiry->response->to_amount }}</p>
                        <p><strong>Duration:</strong> {{ $enquiry->response->duration }}</p>
                        <p><strong>Date of Retirement:</strong> {{ $enquiry->response->date_of_retirement }}</p>
                    @else
                        <p>No response available for this enquiry.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @endforeach





  <!-- Modal -->
  <div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assignUserModalLabel">Assign User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('enquiries.assign', $enquiry->id) }}" method="POST">
          @csrf
          <div class="modal-body">
            <select class="form-select" name="user_ids[]" multiple aria-label="Select users">
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Assign</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection --}}

@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
@endpush

@section('content')
<style>
    #Loan_application_Enquiries th, #Loan_application_Enquiries td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
       /* Add space between the export buttons and the table */
       .dt-buttons {
        margin-bottom: 15px; /* Adjust this value as needed */
    }
</style>
<div class="container-fluid">
<div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Enquiries</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $type ? ucfirst(str_replace('_', ' ', $type)) . ' Enquiries' : 'All Enquiries' }}
                    </li>
                </ol>
            </nav>
        </div>
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

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 radius-30" placeholder="Search Enquiry">
                    <span class="position-absolute top-50 translate-middle-y" style="left: 20px;"><i class="bx bx-search"></i></span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('enquiries.create', ['type' => $type ?? null]) }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                        <i class="bx bxs-plus-square"></i> Add {{ $type ? ucfirst(str_replace('_', ' ', $type)) : 'Enquiry' }}
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="Loan_application_Enquiries" class="table table-striped table-bordered">
                    <thead>
                <tr>
                    <th>Date Received</th>
                    <th>CHECK NUMBER</th>
                    <th>Full Name</th>
                    <th>Account Number</th>
                    <th>Bank Name</th>
                    <th>Region</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $enquiry)
                <tr>
                    <td>{{ $enquiry->date_received }}</td>
                    <td>{{ $enquiry->check_number }}</td>
                    <td>{{ $enquiry->full_name }}</td>
                    <td>{{ $enquiry->account_number }}</td>
                    <td>{{ $enquiry->bank_name }}</td>
                    <td>{{ $enquiry->region }}</td>

                    <td>{{ $enquiry->phone }}</td>

                    <td>
                        <span class="badge bg-{{ $enquiry->status == 'approved' ? 'success' : ($enquiry->status == 'rejected' ? 'danger' : ($enquiry->status == 'assigned' ? 'warning' : 'secondary')) }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </td>
                    <td>

                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- View Action -->
                                <li><a class="dropdown-item" href="{{ route('enquiries.show', $enquiry->id) }}"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                                <li><hr class="dropdown-divider"></li>



                                <!-- Assign Action (triggers modal) -->
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $enquiry->id }}"><i class="mdi mdi-account-arrow-right me-2"></i>Assign</a></li>
                                <li><hr class="dropdown-divider"></li>


<!-- Edit Action -->
<li><a class="dropdown-item" href="{{ route('enquiries.edit', $enquiry->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
<li><hr class="dropdown-divider"></li>


                                <!-- Delete Action -->
                                <li>
                                    <form action="{{ route('enquiries.destroy', $enquiry->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div></div></div>

<!-- Modals for each enquiry to assign users -->
@foreach($enquiries as $enquiry)
<div class="modal fade" id="assignUserModal-{{ $enquiry->id }}" tabindex="-1" aria-labelledby="assignUserModalLabel-{{ $enquiry->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white text-uppercase" id="assignUserModalLabel-{{ $enquiry->id }}">Assign User to Enquiry #{{ $enquiry->id }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('enquiries.assign', $enquiry->id) }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="user_ids">Assign Users</label>
            <select class="form-select" id="user_ids" name="user_ids[]" aria-label="Select users">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
    </div>
</form>

        </div>
    </div>
</div>
@endforeach

@endsection


