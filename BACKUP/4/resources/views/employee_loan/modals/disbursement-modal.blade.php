<!-- Disbursement Modal -->
<div class="modal fade disbursement-modal" id="disbursementModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: white !important;">
                    <i class="fas fa-money-check-alt me-2"></i>Process Disbursement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="disbursementContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="rejectDisbursement" style="margin-right: auto;">
                    <i class="fas fa-times-circle me-2"></i>Reject Disbursement
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDisbursement">
                    <i class="fas fa-paper-plane me-2"></i>Confirm Disbursement
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Disbursement Modal Styles */
.disbursement-modal .modal-header {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
    border-bottom: 3px solid #FF8C00;
    padding: 1.25rem;
}

.disbursement-modal .modal-title {
    font-weight: 700;
}

.disbursement-modal .btn-close-white {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: opacity 0.3s;
}

.disbursement-modal .btn-close-white:hover {
    opacity: 1;
}

.disbursement-modal .modal-body {
    padding: 2rem;
}

.disbursement-modal .modal-dialog {
    max-width: 1200px;
}

.disbursement-modal .modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.disbursement-modal .btn-primary {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border: none;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s;
}

.disbursement-modal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
}

.disbursement-modal .btn-secondary {
    background: #6c757d;
    border: none;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
}

.disbursement-modal .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s;
}

.disbursement-modal .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}
</style>
@endpush