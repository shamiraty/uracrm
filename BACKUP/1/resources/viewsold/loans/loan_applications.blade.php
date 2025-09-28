{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loanApplications as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td>{{ $loan->enquiry->full_name }}</td>
                <td>{{ $loan->loan_amount }}</td>
                <td>{{ $loan->loan_duration }} months</td>
                <td>{{ $loan->status }}</td>
                <td>
                    <button class="btn btn-info" onclick="openModal({{ $loan->id }})">Manage</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="loanActionModal" tabindex="-1" role="dialog" aria-labelledby="loanActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanActionModalLabel">Loan Application Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <form id="loanActionForm" action="" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="patch">
                    <button type="submit" class="btn btn-success" formaction="{{ url('loan/process') }}">Process</button>
                    <button type="submit" class="btn btn-primary" formaction="{{ url('loan/approve') }}">Approve</button>
                    <button type="submit" class="btn btn-danger" formaction="{{ url('loan/reject') }}">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(loanId) {
        var formAction = $('#loanActionForm').attr('action');
        $('#loanActionForm').attr('action', formAction + '/' + loanId);
        $('#loanActionModal').modal('show');
    }
</script>
@endsection --}}
{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $enquiry)
            @if($enquiry->loanApplication)
            <tr>
                <td>{{ $enquiry->loanApplication->id }}</td>
                <td>{{ $enquiry->full_name }}</td>
                <td>{{ $enquiry->loanApplication->loan_amount }}</td>
                <td>{{ $enquiry->loanApplication->loan_duration }} months</td>
                <td>{{ $enquiry->loanApplication->status }}</td>
                <td>
                    <button class="btn btn-info" onclick="openModal({{ $enquiry->loanApplication->id }})">Manage</button>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="loanActionModal" tabindex="-1" role="dialog" aria-labelledby="loanActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanActionModalLabel">Loan Application Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <form id="loanActionForm" action="" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="patch">
                    <button type="submit" class="btn btn-success" formaction="{{ url('loan/process') }}">Process</button>
                    <button type="submit" class="btn btn-primary" formaction="{{ url('loan/approve') }}">Approve</button>
                    <button type="submit" class="btn btn-danger" formaction="{{ url('loan/reject') }}">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function openModal(loanId) {
        var formAction = $('#loanActionForm').attr('action');
        $('#loanActionForm').attr('action', formAction + '/' + loanId);
        $('#loanActionModal').modal('show');
    }
</script>


@endsection --}}

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $enquiry)
            @if($enquiry->loanApplication)
            <tr>
                <td>{{ $enquiry->loanApplication->id }}</td>
                <td>{{ $enquiry->full_name }}</td>
                <td>{{ $enquiry->loanApplication->loan_amount }}</td>
                <td>{{ $enquiry->loanApplication->loan_duration }} months</td>
                <td>{{ $enquiry->loanApplication->status }}</td>
                <td>
                    <button class="btn btn-info" onclick="openModal({{ $enquiry->loanApplication->id }}, '{{ $enquiry->loanApplication->status }}')">Manage</button>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="loanActionModal" tabindex="-1" role="dialog" aria-labelledby="loanActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanActionModalLabel">Loan Application Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <form id="loanActionForm" action="" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="patch">
                    <button type="submit" class="btn btn-success" formaction="">Process</button>
                    <button type="submit" class="btn btn-primary" formaction="">Approve</button>
                    <button type="submit" class="btn btn-danger" formaction="">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function openModal(loanId, status) {
        let form = $('#loanActionForm');
        form.find('button[type="submit"]').hide(); // Hide all buttons initially

        // Show buttons based on current status
        if (status === 'pending') {
            form.find('button.btn-success').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/process");
        } else if (status === 'processed') {
            form.find('button.btn-primary').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/approve");
        } else if (status === 'approved') {
            form.find('button.btn-danger').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/reject");
        }

        $('#loanActionModal').modal('show');
    }
</script>
@endsection --}}

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $enquiry)
            @if($enquiry->loanApplication)
            <tr>
                <td>{{ $enquiry->loanApplication->id }}</td>
                <td>{{ $enquiry->full_name }}</td>
                <td>{{ $enquiry->loanApplication->loan_amount }}</td>
                <td>{{ $enquiry->loanApplication->loan_duration }} months</td>
                <td>{{ $enquiry->loanApplication->status }}</td>
                <td>
                    <button class="btn btn-info" onclick="openModal({{ $enquiry->loanApplication->id }}, '{{ $enquiry->loanApplication->status }}')">Manage</button>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="loanActionModal" tabindex="-1" role="dialog" aria-labelledby="loanActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanActionModalLabel">Loan Application Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <form id="loanActionForm" method="post">
                    @csrf
                    <button type="submit" class="btn btn-success" formaction="">Process</button>
                    <button type="submit" class="btn btn-primary" formaction="">Approve</button>
                    <button type="submit" class="btn btn-danger" formaction="">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(loanId, status) {
        let form = $('#loanActionForm');
        form.find('button[type="submit"]').hide(); // Hide all buttons initially

        // Show buttons based on current status
        if (status === 'pending') {
            form.find('button.btn-success').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/process");
        } else if (status === 'processed') {
            form.find('button.btn-primary').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/approve");
        } else if (status === 'approved') {
            form.find('button.btn-danger').show().attr('formaction', "{{ url('loans') }}/" + loanId + "/reject");
        }

        $('#loanActionModal').modal('show');
    }
</script>
@endsection --}}

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Applications</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $enquiry)
            @if($enquiry->loanApplication)
            <tr>
                <td>{{ $enquiry->loanApplication->id }}</td>
                <td>{{ $enquiry->full_name }}</td>
                <td>{{ $enquiry->loanApplication->loan_amount }}</td>
                <td>{{ $enquiry->loanApplication->loan_duration }} months</td>
                <td>{{ $enquiry->loanApplication->status }}</td>
                <td>
                    <button class="btn btn-info" onclick="openModal({{ $enquiry->loanApplication->id }}, '{{ $enquiry->loanApplication->status }}')">Manage</button>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="loanActionModal" tabindex="-1" role="dialog" aria-labelledby="loanActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanActionModalLabel">Loan Application Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <form id="loanActionForm" method="post">
                    @csrf
                    <button type="submit" class="btn btn-success" formaction="{{ route('loans.process', ['loanApplication' => 'id_placeholder']) }}">Process</button>
                    <button type="submit" class="btn btn-primary" formaction="{{ route('loans.approve', ['loanApplication' => 'id_placeholder']) }}">Approve</button>
                    <button type="submit" class="btn btn-danger" formaction="{{ route('loans.reject', ['loanApplication' => 'id_placeholder']) }}">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    function openModal(loanId, status) {
        let form = $('#loanActionForm');

        // Define the base URLs with placeholders
        let processUrl = "{{ route('loans.process', ['loanApplication' => 'placeholder']) }}";
        let approveUrl = "{{ route('loans.approve', ['loanApplication' => 'placeholder']) }}";
        let rejectUrl = "{{ route('loans.reject', ['loanApplication' => 'placeholder']) }}";

        // Replace 'placeholder' in URLs with the actual loanId
        processUrl = processUrl.replace('placeholder', loanId);
        approveUrl = approveUrl.replace('placeholder', loanId);
        rejectUrl = rejectUrl.replace('placeholder', loanId);

        form.find('button[type="submit"]').hide(); // Hide all buttons initially

        // Show and set the correct URL for buttons based on the current status
        if (status === 'pending') {
            form.find('button.btn-success').attr('formaction', processUrl).show();
        } else if (status === 'processed') {
            form.find('button.btn-primary').attr('formaction', approveUrl).show();
        } else if (status === 'approved') {
            form.find('button.btn-danger').attr('formaction', rejectUrl).show();
        }

        $('#loanActionModal').modal('show');
    }
</script>


@endsection --}}

@extends('layouts.app')

@section('content')

<style>

    /* Custom styling for tables */
    #example2 th, #example2 td {
        border: 1px solid #dee2e6; /* Light border for table cells */
        padding: 12px; /* Added padding for better readability */
        text-align: left; /* Align text to the left */
    }

</style>

<div class="container-fluid">
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">LOAN APPLICATIONS</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $type ? ucfirst(str_replace('_', ' ', $type)) . ' Enquiries' : 'All Enquiries' }}
                    </li> --}}
                </ol>
            </nav>
        </div>
    </div>
    <!--cards  starts here------------------------------------------>
<!--
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
      <div class="card radius-2 border-start border-0 border-4 border-primary">
         <div class="card-body">
             <div class="d-flex align-items-center">
                 <div>
                     <h4 class="my-1 text-secondary">Approved: 0</h4>
                 </div>
                 <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto">
                     <i class='fas fa-check-circle text-primary'></i>
                 </div>
             </div>
         </div>
      </div>
    </div>
    <div class="col">
     <div class="card radius-2 border-start border-0 border-4 border-primary">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h4 class="my-1 text-secondary">Paid: 0</h4>
                </div>
                <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto">
                    <i class='fas fa-coins text-primary'></i>
                </div>
            </div>
        </div>
     </div>
   </div>
   <div class="col">
     <div class="card radius-2 border-start border-0 border-4 border-primary">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h4 class="my-1 text-secondary">Pending: 0</h4>
                </div>
                <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto">
                    <i class='fas fa-hourglass-half text-warning'></i>
                </div>
            </div>
        </div>
     </div>
   </div>
   <div class="col">
     <div class="card radius-2 border-start border-0 border-4 border-primary">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>

                    <h4 class="my-1 text-secondary">Assigned: 0</h4>
                </div>
                <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto">
                    <i class='fas fa-user-check text-primary'></i>
                </div>
            </div>
        </div>
     </div>
   </div>
 </div>

<div class="d-flex align-items-end mb-3">
    <div class="input-group me-2" style="flex: 1;">
        <label for="fromDate" class="form-label">From:</label>
        <input type="date" id="fromDate" class="form-control">
    </div>

    <div class="input-group me-2" style="flex: 1;">
        <label for="toDate" class="form-label">To:</label>
        <input type="date" id="toDate" class="form-control">
        <button type="button" class="btn btn-primary" style="height: 38px; margin-bottom: 0;">Filter</button>
    </div>
</div>
-->





    {{-- <h1>Loan Applications</h1> --}}

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Loan Amount</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enquiries as $enquiry)
            @if($enquiry->loanApplication)  {{-- Ensure there is a loan application --}}
            <tr>
                <td>{{ $enquiry->loanApplication->id }}</td>
                <td>{{ $enquiry->full_name }}</td>
                <td>{{ number_format($enquiry->loanApplication->loan_amount) }}</td>
                <td>{{ $enquiry->loanApplication->loan_duration }} months</td>
                <td>
    @if ($enquiry->loanApplication->status == 'rejected')
        <div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
            <i class='bx bxs-x-circle align-middle me-1'></i>Rejected
        </div>
    @elseif ($enquiry->loanApplication->status == 'approved')
        <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
            <i class='bx bxs-check-circle align-middle me-1'></i>Approved
        </div>
    @elseif ($enquiry->loanApplication->status == 'pending')
        <div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">
            <i class='bx bxs-hourglass align-middle me-1'></i>Pending
        </div>
    @elseif ($enquiry->loanApplication->status == 'processed')
        <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
            <i class='bx bxs-circle align-middle me-1'></i>Processed
        </div>
    @else
        <div class="badge rounded-pill text-secondary bg-light-secondary p-2 text-uppercase px-3">
            <i class='bx bxs-info-circle align-middle me-1'></i>Unknown Status
        </div>
    @endif
</td>

                <td>
                    <!-- Action buttons that trigger modals -->
                    {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processLoanModal-{{ $enquiry->loanApplication->id }}">Process</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveLoanModal-{{ $enquiry->id }}">Approve</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectLoanModal-{{ $enquiry->id }}">Reject</button> --}}
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#processLoanModal-{{ $enquiry->loanApplication->id }}">Process Loan</a>
                            </li>
                            <hr class="dropdown-divider">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#approveLoanModal-{{ $enquiry->id }}">Approve Loan</a>
                            </li>
                            <hr class="dropdown-divider">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectLoanModal-{{ $enquiry->id }}">Reject Loan</a>
                            </li>
                            <hr class="dropdown-divider">

                        </ul>
                    </div>

                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div></div></div></div>

<!-- Include the modals for each loan application -->
@foreach ($enquiries as $enquiry)
    @if($enquiry->loanApplication)
        @include('modals.approve_loan', ['enquiry' => $enquiry])
        @include('modals.process_loan', ['enquiry' => $enquiry])
        @include('modals.reject_loan', ['enquiry' => $enquiry])
    @endif
@endforeach

@endsection




