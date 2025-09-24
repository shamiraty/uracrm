@extends('layouts.app')
@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">ASSIGNED</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          all Payments
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Payments</li>
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
            {{-- <h5 class="card-title mb-0"> <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                <i class="bx bxs-plus-square"></i> {{ $type }}
            </a></h5> --}}
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
                <thead class="">
                <tr>
                <th class="">#</th>
<th class="">Date Received</th>
<th class="">Check Number</th>
<th class="">Full Name</th>
<th class="">Account Number</th>
{{-- <th class="text-lowercase">Bank Name</th> --}}
<th class="">Type</th>
<th class="">Assigned By</th>
<th class="">Status</th>
<th class="">Actions</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($enquiries as $enquiry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $enquiry->date_received }}</td>
                    <td class="text-primary-600">{{ $enquiry->check_number }}</td>

                    <td class="text-lowercase">{{ ucwords($enquiry->full_name) }}</td>
                    <td>{{ $enquiry->account_number }}</td>
                    {{--<td>{{ $enquiry->bank_name }}</td>--}}

                    <td>{{ $enquiry->type }}</td>
                    <td>                   @foreach ($enquiry->assignedUsers as $user)
                        {{ ucwords($user->pivot->assigned_by ? App\Models\User::find($user->pivot->assigned_by)->name : 'Unknown') }}
                    @endforeach
        </td>
                    <td>
                    @if ($enquiry->payment)
                    @switch($enquiry->payment->status)
                    @case('initiated')

                        <div class="badge bg-info w-100"><i class='bx bxs-circle me-1'></i>Initiated</div></td>
                        @break
                    @case('approved')

                        <div class="badge bg-success w-100"><i class='bx bxs-circle me-1'></i>Approved</div></td>
                        @break
                    @case('rejected')

                        <div class="badge bg-danger w-100"><i class='bx bxs-circle me-1'></i>Rejected</div></td>
                        @break
                    @case('paid')

                        <div class="badge bg-primary w-100"><i class='bx bxs-circle me-1'></i>Paid</div></td>
                        @break
                    @default

                        <div class="badge bg-warning w-100"><i class='bx bxs-circle me-1'></i>{{ $enquiry->payment->status }}</div></td>
                @endswitch

@else
    @switch($enquiry->status)
        @case('pending')

            <div class="badge bg-light w-100"><i class='bx bxs-circle me-1'></i>Pending</div></td>
            @break
        @case('completed')

            <div class="badge bg-success w-100"><i class='bx bxs-circle me-1'></i>Completed</div></td>
            @break
        @default

            <div class="badge bg-warning w-100"><i class='bx bxs-circle me-1'></i>{{ $enquiry->status }}</div></td>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('initiatePaymentModal-{{ $enquiry->id }}');
        modalElement.addEventListener('show.bs.modal', function (event) {
         fetch('/payment-modal/{{ $enquiry->id }}/prepare', {
             method: 'GET',
             headers: {
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
             }
         })
         .then(response => response.json())
         .then(data => {
             if(data.success) {
                 console.log('OTP has been sent to your phone.');
             } else {
                 console.error('Failed to send OTP');
             }
         })
         .catch(error => console.error('Error preparing payment modal:', error));
        });
        });

             </script>
    @endforeach

</div>

@endsection

