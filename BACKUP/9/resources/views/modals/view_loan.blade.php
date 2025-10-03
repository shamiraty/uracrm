{{--
 <div class="modal fade" id="viewLoanDetailsModal-{{ $enquiry->loanApplication->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Loan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.process', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST"onsubmit="showSpinner(this);">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Detail Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-person-fill"></i> Applicant Details</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Force Number:</strong> {{ $enquiry->force_no }}</li>
                                    <li class="list-group-item"><strong>Bank:</strong> {{ $enquiry->bank_name }} - {{ $enquiry->account_number }}</li>
                                    <li class="list-group-item"><strong>Region:</strong>  {{ $enquiry->region->name ?? 'No Region' }}</li>
                                    <li class="list-group-item"><strong>District:</strong> {{ $enquiry->district->name ?? 'No District' }}</li>
                                    <li class="list-group-item"><strong>Phone:</strong> {{ $enquiry->phone }}</li>
                                    <div class="card-header">
                                        <strong><i class="bi bi-person-fill"></i> Loan Details</strong>
                                    </div>

    <li class="list-group-item"><strong>Loan Amount:</strong> {{ $enquiry->loan_amount }}</li>
    <li class="list-group-item"><strong>Loan Duration:</strong> {{ $enquiry->loan_duration }}</li>
    <li class="list-group-item"><strong>Interest Rate:</strong> {{ $enquiry->loanApplication->interest_rate }}</li>
    <li class="list-group-item"><strong>Monthly Deduction:</strong> {{ $enquiry->loanApplication->monthly_deduction }}</li>
    <li class="list-group-item"><strong>Total Loan with Interest:</strong> {{ $enquiry->loanApplication->total_loan_with_interest }}</li>
    <li class="list-group-item"><strong>Total Interest:</strong> {{ $enquiry->loanApplication->total_interest }}</li>
    <li class="list-group-item"><strong>Processing Fee:</strong> {{ $enquiry->loanApplication->processing_fee }}</li>
    <li class="list-group-item"><strong>Insurance:</strong> {{ $enquiry->loanApplication->insurance }}</li>
    <li class="list-group-item"><strong>Disbursement Amount:</strong> {{ $enquiry->loanApplication->disbursement_amount }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $enquiry->status }}</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Attachment Card Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-file-earmark-text"></i> Attachments</strong>
                                </div>
                                <div class="card-body">
                                    @if ($enquiry->file_path)
                                    <object data="{{ asset($enquiry->file_path) }}" type="application/pdf" width="100%" height="500px" class="border rounded">
                                        <p class="text-muted">Your browser does not support PDFs.
                                            <a href="{{ asset($enquiry->file_path) }}" class="text-decoration-underline">Download the PDF</a>.
                                        </p>
                                    </object>
                                    @else
                                        <p class="text-muted">No file uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}



@if($enquiry->enquirable_type === 'App\Models\LoanApplication')
    <div class="modal fade" id="viewLoanDetailsModal-{{ $enquiry->enquirable->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Applicant Details</strong></div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->date_received }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Force Number:</strong> {{ $enquiry->force_no }}</li>
                                    <li class="list-group-item"><strong>Bank:</strong> {{ $enquiry->bank_name }} - {{ $enquiry->account_number }}</li>
                                    <li class="list-group-item"><strong>Region:</strong> {{ $enquiry->region->name ?? 'No Region' }}</li>
                                    <li class="list-group-item"><strong>District:</strong> {{ $enquiry->district->name ?? 'No District' }}</li>
                                    <li class="list-group-item"><strong>Phone:</strong> {{ $enquiry->phone }}</li>
                                </ul>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header"><strong>Loan Details</strong></div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Loan Amount:</strong> {{ number_format($enquiry->enquirable->loan_amount) }}</li>
                                    <li class="list-group-item"><strong>Loan Duration:</strong> {{ $enquiry->enquirable->loan_duration }} months</li>
                                    <li class="list-group-item"><strong>Interest Rate:</strong> {{ $enquiry->enquirable->interest_rate }}%</li>
                                    <li class="list-group-item"><strong>Monthly Deduction:</strong> {{ number_format($enquiry->enquirable->monthly_deduction) }}</li>
                                    <li class="list-group-item"><strong>Total Loan with Interest:</strong> {{ number_format($enquiry->enquirable->total_loan_with_interest) }}</li>
                                    <li class="list-group-item"><strong>Total Interest:</strong> {{ number_format($enquiry->enquirable->total_interest) }}</li>
                                    <li class="list-group-item"><strong>Processing Fee:</strong> {{ number_format($enquiry->enquirable->processing_fee) }}</li>
                                    <li class="list-group-item"><strong>Insurance:</strong> {{ number_format($enquiry->enquirable->insurance) }}</li>
                                    <li class="list-group-item"><strong>Disbursement Amount:</strong> {{ number_format($enquiry->enquirable->disbursement_amount) }}</li>
                                    <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($enquiry->enquirable->status) }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><strong>Attachments</strong></div>
                                <div class="card-body">
                                    @if($enquiry->enquirable->file_path)
                                        <object data="{{ asset('storage/' . $enquiry->enquirable->file_path) }}" type="application/pdf" style="width:100%; height:500px;" class="border rounded">
                                            <p>Your browser does not support PDFs. <a href="{{ asset('storage/' . $enquiry->enquirable->file_path) }}">Download the PDF</a>.</p>
                                        </object>
                                    @else
                                        <p>No file uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
