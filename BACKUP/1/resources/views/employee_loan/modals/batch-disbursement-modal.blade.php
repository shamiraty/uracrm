<!-- Batch Disbursement Modal -->
<div class="modal fade" id="batchDisbursementModal" tabindex="-1" role="dialog" aria-labelledby="batchDisbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="batchDisbursementModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>Batch Process Disbursements
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="batchDisbursementForm" method="POST" action="{{ route('disbursements.batch.process') }}">
                @csrf
                <div class="modal-body">
                    <!-- Summary Section -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Batch Processing Summary</h6>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <small>Selected Loans:</small>
                                        <strong id="batchSelectedCount">0</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Total Amount:</small>
                                        <strong id="batchTotalAmount">0</strong> TZS
                                    </div>
                                    <div class="col-md-4">
                                        <small>Processing Bank:</small>
                                        <strong id="batchBankCount">Multiple</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Loans Table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Take Home</th>
                                    <th>Processing Fee</th>
                                    <th>Insurance</th>
                                    <th>Bank</th>
                                    <th>Account</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="batchLoansTableBody">
                                <!-- Dynamically populated -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Processing Options -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Processing Options</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="batchChannel">Disbursement Channel</label>
                                        <select class="form-control" id="batchChannel" name="channel" required>
                                            <option value="BANK_TRANSFER">Bank Transfer</option>
                                            <option value="MOBILE_MONEY">Mobile Money</option>
                                            <option value="CASH">Cash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="batchPriority">Processing Priority</label>
                                        <select class="form-control" id="batchPriority" name="priority">
                                            <option value="NORMAL">Normal</option>
                                            <option value="HIGH">High Priority</option>
                                            <option value="URGENT">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="batchReference">Batch Reference Number</label>
                                <input type="text" class="form-control" id="batchReference" name="batch_reference" 
                                       placeholder="Auto-generated if empty" readonly value="BATCH-{{ date('YmdHis') }}">
                            </div>

                            <div class="form-group">
                                <label for="batchNotes">Processing Notes (Optional)</label>
                                <textarea class="form-control" id="batchNotes" name="notes" rows="2" 
                                          placeholder="Add any notes for this batch processing..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for selected loan IDs -->
                    <input type="hidden" id="batchLoanIds" name="loan_ids">

                    <!-- Warning for large batches -->
                    <div id="batchWarning" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Large Batch Warning:</strong> Processing more than 50 loans at once may take longer. Consider processing in smaller batches.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-info" onclick="validateBatchSelection()">
                        <i class="fas fa-check-circle me-1"></i>Validate Selection
                    </button>
                    <button type="submit" class="btn btn-primary" id="batchProcessBtn">
                        <i class="fas fa-paper-plane me-1"></i>Process Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Options Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-download me-2"></i>Export Options
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Export Format</label>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success" onclick="executeExport('excel')">
                            <i class="fas fa-file-excel me-2"></i>Excel (.xlsx)
                        </button>
                        <button class="btn btn-outline-primary" onclick="executeExport('csv')">
                            <i class="fas fa-file-csv me-2"></i>CSV (.csv)
                        </button>
                        <button class="btn btn-outline-danger" onclick="executeExport('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>PDF (.pdf)
                        </button>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label>Export Options</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportSelected" checked>
                        <label class="form-check-label" for="exportSelected">
                            Export only selected items
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportWithDetails" checked>
                        <label class="form-check-label" for="exportWithDetails">
                            Include loan details
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Date Range</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="exportDateFrom" placeholder="From">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="exportDateTo" placeholder="To">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>