{{-- <div class="modal fade" id="processLoanModal-{{ $enquiry->loanApplication->id }}" tabindex="-1" aria-hidden="true"> --}}
    @if($enquiry->enquirable_type === 'App\Models\LoanApplication')
    <div class="modal fade" id="processLoanModal-{{ $enquiry->enquirable->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="modal-title">Process Loan Details for [ {{ $enquiry->full_name }} ]</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form action="{{ route('loans.process', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST" onsubmit="showSpinner(this);"> --}}
                {{-- <form action="{{ route('loans.process', ['loanApplication' => $enquiry->loanApplication->id]) }}" method="POST"> --}}
                    <form action="{{ route('loans.process', ['loanApplication' => $enquiry->enquirable->id]) }}" method="POST">
                        @csrf

                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Detail Card Column -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-person-fill"></i> Applicant Details</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Date Received:</strong> {{ $enquiry->getDateReceivedAttribute($enquiry->date_received) }}</li>
                                    <li class="list-group-item"><strong>Name:</strong> {{ $enquiry->full_name }}</li>
                                    <li class="list-group-item"><strong>Force Number:</strong> {{ $enquiry->force_no }}</li>
                                    <li class="list-group-item"><strong>Bank:</strong> {{ $enquiry->bank_name }} - {{ $enquiry->account_number }}</li>
                                    <li class="list-group-item"><strong>District:</strong> {{ $enquiry->district->name ?? 'No District' }}</li>
                                    <li class="list-group-item"><strong>Phone:</strong> {{ $enquiry->phone }}</li>
                                    <li class="list-group-item"><strong>Check Number:</strong> {{ $enquiry->check_number }}</li>


                                    <li class="list-group-item">
                                        <strong>Basic Salary:</strong>
                                        <input type="number" class="form-control loan-input" data-type="basic_salary" value="{{ $enquiry->basic_salary }}">
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Allowances:</strong>
                                        <input type="number" class="form-control loan-input" data-type="allowances" value="{{ $enquiry->allowances }}">
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Take Home:</strong>
                                        <input type="number" class="form-control loan-input" data-type="take_home" value="{{ $enquiry->take_home }}">
                                    </li>

                                    <!-- Static displays for other loan details -->

                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong><i class="bi bi-file-earmark-text"></i> Loan detail</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">

                        <li class="list-group-item"><strong>Loan Type:</strong> {{ $enquiry->loan_type }}</li>

                                    <li class="list-group-item"><strong>Loan Category:</strong> {{ $enquiry->loan_category }}</li>

                                    <li class="list-group-item">
                                        <strong>Loan Amount requested:</strong>
                                        <input type="number" class="form-control loan-input" data-type="requested_loan_amount" value="{{ $enquiry->loan_amount }}">
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Loan Duration:</strong>
                                        <input type="number" name="loan_duration" class="form-control" value="{{ $enquiry->loan_duration }}" required>
                                        months
                                    </li>

                                    <li class="list-group-item">
                                        <strong>Loan Possible Amount:</strong>
                                        <input type="hidden" name="loan_amount" value="0" id="possible_amount">
                                        <span class="loan-detail" data-detail-type="loan_amount">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Monthly Deduction:</strong>
                                        <input type="hidden" name="monthly_deduction" value="0" id="monthly_deduction">
                                        <span class="loan-detail" data-detail-type="monthly_deduction">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total Loan with Interest:</strong>
                                        <input type="hidden" name="total_loan_with_interest" value="0" id="total_with_interest">
                                        <span class="loan-detail" data-detail-type="total_loan_with_interest">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total Interest:</strong>
                                        <input type="hidden" name="total_interest" value="0" id="total_interest">
                                        <span class="loan-detail" data-detail-type="total_interest">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Processing Fee:</strong>
                                        <input type="hidden" name="processing_fee" value="0" id="processing_fee">
                                        <span class="loan-detail" data-detail-type="processing_fee">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Insurance:</strong>
                                        <input type="hidden" name="insurance" value="0" id="insurance">
                                        <span class="loan-detail" data-detail-type="insurance">0</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Disbursement Amount:</strong>
                                        <input type="hidden" name="disbursement_amount" value="0" id="disbursement_amount">
                                        <span class="loan-detail" data-detail-type="disbursement_amount">0</span>
                                    </li>

                                </ul>
                                </div></div></div>
                        <!-- Attachment Card Column -->
                        <div class="col-md-4">
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
                    <button type="submit" class="btn btn-primary btn-sm" id="submit-button-{{ $enquiry->id }}">Process Loan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif



        <script>

document.addEventListener('DOMContentLoaded', function() {
    const modalBodies = document.querySelectorAll('.modal-body');

    modalBodies.forEach(modalBody => {
        // Listen for input on any relevant input field within the modal
        modalBody.addEventListener('input', function(event) {
            console.log("Input changed for: ", event.target.getAttribute('name')); // Log which input was changed
            if (event.target.classList.contains('loan-input')) {
                recalculateLoanDetails(modalBody);
            }
        });
    });
});

function recalculateLoanDetails(modalBody) {

    console.log("Recalculating loan details...");

    // Fetching values again in case they have been updated
    const basicSalary = parseFloat(modalBody.querySelector('[data-type="basic_salary"]').value) || 0;
    const allowances = parseFloat(modalBody.querySelector('[data-type="allowances"]').value) || 0;
    const takeHome = parseFloat(modalBody.querySelector('[data-type="take_home"]').value) || 0;
    const requestedLoanAmount = parseFloat(modalBody.querySelector('[data-type="requested_loan_amount"]').value) || 0;
    const loanDuration = parseInt(modalBody.querySelector('[name="loan_duration"]').value) || 12; // Defaulting to 12 if not specified

    console.log("Loan Duration: ", loanDuration);

    // Assuming calculations
    const loanAmount = requestedLoanAmount || (basicSalary + allowances);
    const monthlyDeduction = loanAmount / loanDuration; // Using updated loan duration
    const interestRateAnnual = 0.1; // Example annual interest rate
    const totalLoanWithInterest = loanAmount * (1 + (interestRateAnnual * (loanDuration / 12)));
    const totalInterest = totalLoanWithInterest - loanAmount;
    const processingFee = loanAmount * 0.02;
    const insurance = loanAmount * 0.05;
    const disbursementAmount = loanAmount - (processingFee + insurance);

    updateDisplayAndHidden(modalBody, 'loan_amount', loanAmount);
    updateDisplayAndHidden(modalBody, 'monthly_deduction', monthlyDeduction);
    updateDisplayAndHidden(modalBody, 'total_loan_with_interest', totalLoanWithInterest);
    updateDisplayAndHidden(modalBody, 'total_interest', totalInterest);
    updateDisplayAndHidden(modalBody, 'processing_fee', processingFee);
    updateDisplayAndHidden(modalBody, 'insurance', insurance);
    updateDisplayAndHidden(modalBody, 'disbursement_amount', disbursementAmount);
}

function updateDisplayAndHidden(modalBody, detailType, value) {
    const formattedValue = value.toFixed(2);
    modalBody.querySelector(`[data-detail-type="${detailType}"]`).textContent = formattedValue;
    modalBody.querySelector(`input[name="${detailType}"]`).value = formattedValue;
    console.log(`${detailType} updated to: `, formattedValue);
}

        </script>





