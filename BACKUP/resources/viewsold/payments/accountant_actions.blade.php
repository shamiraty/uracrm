


 @extends('layouts.app')

@section('content')
<style>
    #example th, #example td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
       /* Add space between the export buttons and the table */
       .dt-buttons {
        margin-bottom: 15px; /* Adjust this value as needed */
    }
</style>

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Enquiries</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                   ASSIGNED ENQUIRIES
                </li>
            </ol>
        </nav>
    </div>
</div>

 <!--cards  starts here------------------------------------------>

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
 </div><!--end row-->
<!---cards ends here--------------------------------------------->
<!--date filter start-------------------------------------------->
<div class="d-flex align-items-end mb-3">
    <!-- From Date Picker -->
    <div class="input-group me-2" style="flex: 1;">
        <label for="fromDate" class="form-label">From:</label>
        <input type="date" id="fromDate" class="form-control">
    </div>

    <!-- To Date Picker with Filter Button -->
    <div class="input-group me-2" style="flex: 1;">
        <label for="toDate" class="form-label">To:</label>
        <input type="date" id="toDate" class="form-control">
        <button type="button" class="btn btn-primary" style="height: 38px; margin-bottom: 0;">Filter</button>
    </div>
</div>
<!--date filter ends-------------------------------------------->




<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5 radius-30" placeholder="Search Enquiry">
                <span class="position-absolute top-50 translate-middle-y" style="left: 20px;"><i class="bx bx-search"></i></span>
            </div>
            <div class="ms-auto">

            </div>
        </div>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered">
                <thead class="text-uppercase">
                <tr>
                    <th>#</th>
                    <th>Date Received</th>
                    <th>CHECK NUMBER</th>
                    <th>Full Name</th>
                    <th>ACCOUNT NUMBER</th>
                    <th>BANK NAME</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enquiries as $enquiry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $enquiry->date_received }}</td>
                    <td>{{ $enquiry->check_number }}</td>

                    <td>{{ $enquiry->full_name }}</td>
                    <td>{{ $enquiry->account_number }}</td>
                    <td>{{ $enquiry->bank_name }}</td>

                    <td>{{ $enquiry->type }}</td>

                    <td>
                    @if ($enquiry->payment)
                    @switch($enquiry->payment->status)
                    @case('initiated')

                        <div class="badge rounded-pill text-light bg-info p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Initiated</div></td>
                        @break
                    @case('approved')

                        <div class="badge rounded-pill text-light bg-success p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Approved</div></td>
                        @break
                    @case('rejected')

                        <div class="badge rounded-pill text-light bg-danger p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Rejected</div></td>
                        @break
                    @case('paid')

                        <div class="badge rounded-pill text-light bg-primary p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Paid</div></td>
                        @break
                    @default

                        <div class="badge rounded-pill text-light bg-warning p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>{{ $enquiry->payment->status }}</div></td>
                @endswitch

@else
    @switch($enquiry->status)
        @case('pending')

            <div class="badge rounded-pill text-light bg-dark p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Pending</div></td>
            @break
        @case('completed')

            <div class="badge rounded-pill text-success bg-success p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>Completed</div></td>
            @break
        @default

            <div class="badge rounded-pill text-light bg-warning p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>{{ $enquiry->status }}</div></td>
    @endswitch
@endif

</td>




                    <td>
                        <!-- Dropdown for actions -->
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-{{ $enquiry->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink-{{ $enquiry->id }}">
                                <!-- View Details Action (accessible to all) -->
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewDetailsModal-{{ $enquiry->id }}">
                                    <i class='bx bx-show font-22'></i> View Details
                                </a></li>

                                <!-- Loan Officer Specific Actions -->
                                @if(auth()->user()->hasRole('loanofficer'))

                                        @include('enquiries.partials.loan_officer_actions', ['enquiry' => $enquiry])

                                    @endif

                                <!-- Accountant Specific Actions -->
                                @role('accountant')
                                    @include('enquiries.partials.accountant_actions', ['enquiry' => $enquiry])
                                @endrole
                            </ul>
                        </div>
                    </td>


                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div></div>
    @foreach ($enquiries as $enquiry)

        @includeWhen($enquiry->status == 'assigned' && !$enquiry->payment , 'modals.initiate_payment', ['enquiry' => $enquiry])
        @if ($enquiry->payment)
            @includeWhen($enquiry->payment->status == 'initiated' , 'modals.approve_payment', ['paymentId' => $enquiry->payment->id])
            @includeWhen($enquiry->payment->status == 'initiated' , 'modals.reject_payment', ['paymentId' => $enquiry->payment->id])
            @includeWhen($enquiry->payment->status == 'approved' , 'modals.pay_payment', ['paymentId' => $enquiry->payment->id])
        @endif
        @include('modals.modal_details', ['enquiry' => $enquiry])

<<<<<<< HEAD
      
=======

>>>>>>> parent of 0032dbe (otp)
    @endforeach

</div>

@endsection
