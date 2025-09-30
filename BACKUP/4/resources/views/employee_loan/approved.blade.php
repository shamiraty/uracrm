@extends('layouts.app')

@section('content')
<style>
/* URASACCOS Brand Colors */
:root {
    --ura-primary: #003366;
    --ura-secondary: #17479E;
    --ura-accent: #2196F3;
    --ura-success: #28a745;
    --ura-warning: #FFA726;
    --ura-danger: #dc3545;
    --ura-info: #17a2b8;
    --ura-dark: #1a1a2e;
    --ura-light: #f8f9fa;
    --ura-gradient: linear-gradient(135deg, #003366 0%, #17479E 100%);
    --ura-gradient-light: linear-gradient(135deg, #17479E 0%, #2196F3 100%);
}

/* Modern KPI Dashboard */
.kpi-dashboard-modern {
    position: relative;
    padding: 15px 0;
    margin: -10px -15px 20px -15px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.03) 0%, rgba(23, 71, 158, 0.03) 100%);
    border-radius: 20px;
}

.kpi-dashboard-modern .row {
    display: flex;
    flex-wrap: wrap;
}

@media (min-width: 576px) {
    .kpi-dashboard-modern .row {
        flex-wrap: nowrap;
    }
}

.kpi-dashboard-modern .col {
    flex: 1 1 0%;
    min-width: 0;
}

@media (max-width: 575px) {
    .kpi-dashboard-modern .col {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* Remove old disbursement styles - using kpi-dashboard-modern now */

.kpi-card-disbursement {
    position: relative;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 15px;
    padding: 15px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 
        0 10px 25px 0 rgba(31, 38, 135, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
}

.kpi-card-disbursement:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 51, 102, 0.2);
}

.kpi-icon-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-bottom: 0.5rem;
}

.kpi-icon-wrapper.primary {
    background: var(--ura-gradient);
    color: white;
}

.kpi-icon-wrapper.success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.kpi-icon-wrapper.warning {
    background: linear-gradient(135deg, #FFA726, #FFB74D);
    color: white;
}

.kpi-icon-wrapper.info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

/* Responsive adjustments for KPI cards */
@media (max-width: 768px) {
    .kpi-value {
        font-size: 1.25rem;
    }
    
    .kpi-label {
        font-size: 0.65rem;
    }
    
    .kpi-card-disbursement {
        padding: 12px;
        min-height: 100px;
    }
    
    .kpi-icon-wrapper {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .kpi-trend {
        font-size: 0.6rem;
    }
}

@media (min-width: 1400px) {
    .kpi-dashboard-modern .row {
        max-width: 100%;
        margin: 0 auto;
    }
}

.kpi-label {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.kpi-trend {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.4rem;
    border-radius: 10px;
    font-size: 0.65rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

.kpi-trend.positive {
    background: rgba(40, 167, 69, 0.1);
    color: var(--ura-success);
}

.kpi-trend.negative {
    background: rgba(220, 53, 69, 0.1);
    color: var(--ura-danger);
}

/* Modern Reject Disbursement Modal Styles */
.modal-backdrop {
    background: rgba(0, 33, 66, 0.7);
    backdrop-filter: blur(5px);
}

#rejectDisbursementModal .modal-dialog {
    max-width: 1140px; /* xl size */
}

#rejectDisbursementModal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 51, 102, 0.3);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.modal-header-ura-reject {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white;
    padding: 1.5rem 2rem;
    border: none;
    position: relative;
    overflow: hidden;
}

.modal-header-ura-reject::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    animation: rotate 15s linear infinite;
}

.modal-header-ura-reject .modal-title {
    font-weight: 700;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    z-index: 1;
    position: relative;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.5px;
}

.modal-header-ura-reject .modal-title i {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
}

.modal-body-ura {
    padding: 2.5rem;
}

.loan-details-card {
    background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-secondary) 100%);
    color: white !important;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.3);
    position: relative;
    overflow: hidden;
}

.loan-details-card * {
    color: white !important;
}

.loan-details-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
    animation: rotate 20s linear infinite;
}

.loan-details-card .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    position: relative;
    z-index: 1;
}

.loan-details-card .detail-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.loan-details-card .detail-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9) !important;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    font-weight: 500;
}

.loan-details-card .detail-label i {
    margin-right: 0.3rem;
    color: rgba(255, 255, 255, 0.8) !important;
}

.loan-details-card .detail-value {
    font-weight: 700;
    font-size: 1.05rem;
    text-align: right;
    color: white !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.form-control-ura {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control-ura:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.15);
    outline: none;
}

.form-select-ura {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23003366' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
}

.form-select-ura:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.15);
    outline: none;
}

.form-label-ura {
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.75rem;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    display: flex;
    align-items: center;
}

.form-label-ura i {
    color: var(--ura-secondary);
    margin-right: 0.5rem;
}

.alert-ura-info {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(21, 101, 192, 0.1) 100%);
    border: 1px solid rgba(33, 150, 243, 0.3);
    color: #1565C0;
    border-radius: 10px;
    padding: 1rem;
}

.alert-ura-info i {
    font-size: 1.25rem;
}

.modal-footer-ura {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    padding: 1.25rem;
}

.btn-ura-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-ura-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-ura-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-ura-reject:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.character-counter {
    font-size: 0.75rem;
    color: #6c757d;
    text-align: right;
    margin-top: 0.25rem;
}

/* Modal XL Specific Styles */
@media (min-width: 1200px) {
    #rejectDisbursementModal .modal-dialog {
        max-width: 1200px;
    }
    
    .modal-body-ura .container-fluid {
        padding: 0 1rem;
    }
}

/* Three Column Layout Styling */
#rejectDisbursementModal .col-lg-4 {
    position: relative;
}

@media (min-width: 992px) {
    #rejectDisbursementModal .col-lg-4:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -1rem;
        top: 0;
        bottom: 0;
        width: 1px;
        background: linear-gradient(180deg, transparent, #e9ecef 20%, #e9ecef 80%, transparent);
    }
}

/* Quick Template Buttons */
.btn-outline-primary {
    border-color: var(--ura-secondary);
    color: var(--ura-primary);
}

.btn-outline-primary:hover {
    background: var(--ura-secondary);
    border-color: var(--ura-secondary);
    color: white;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    #rejectDisbursementModal .modal-dialog {
        max-width: 95%;
    }
    
    .modal-body-ura {
        padding: 1.5rem;
    }
    
    #rejectDisbursementModal .col-lg-4 {
        margin-bottom: 2rem;
    }
    
    #rejectDisbursementModal .col-lg-4:not(:last-child)::after {
        display: none;
    }
}

/* Batch Actions Bar */
.batch-actions-bar {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.1);
    display: none;
    animation: slideDown 0.3s ease;
}

.batch-actions-bar.active {
    display: block;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.batch-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.batch-count {
    background: var(--ura-gradient);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 600;
}

.batch-actions {
    display: flex;
    gap: 0.5rem;
}

.batch-btn {
    padding: 0.5rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.batch-btn.primary {
    background: var(--ura-gradient);
    color: white;
}

.batch-btn.primary:hover {
    background: var(--ura-secondary);
    transform: translateY(-2px);
}

.batch-btn.secondary {
    background: #f8f9fa;
    color: var(--ura-primary);
    border: 1px solid #dee2e6;
}

.batch-btn.secondary:hover {
    background: #e9ecef;
}

/* Enhanced Search Section */
.search-section-disbursement {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.1);
}

.search-input-disbursement {
    position: relative;
}

.search-input-disbursement input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.search-input-disbursement input:focus {
    outline: none;
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.1);
}

.search-input-disbursement .search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Filter Pills */
.filter-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-pill {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: white;
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-pill:hover {
    border-color: var(--ura-secondary);
    color: var(--ura-secondary);
    background: rgba(23, 71, 158, 0.05);
}

.filter-pill.active {
    background: linear-gradient(135deg, var(--ura-primary), var(--ura-secondary));
    color: white;
    border-color: var(--ura-primary);
    box-shadow: 0 4px 15px rgba(0, 51, 102, 0.2);
}

.filter-pill.active:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 51, 102, 0.3);
}

/* Enhanced Table */
.disbursement-table {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.1);
}

.disbursement-table thead {
    background: var(--ura-gradient);
    color: white;
}

.disbursement-table thead th {
    padding: 1.25rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
    border: none;
}

.disbursement-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.disbursement-table tbody tr:hover {
    background: #f8f9fa;
}

.disbursement-table tbody tr.selected {
    background: rgba(23, 71, 158, 0.05);
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--ura-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.employee-details .name {
    font-weight: 600;
    color: var(--ura-dark);
    margin-bottom: 0.25rem;
}

.employee-details .id {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Amount Display */
.amount-display {
    text-align: right;
}

.amount-primary {
    font-weight: 700;
    color: var(--ura-primary);
    font-size: 1rem;
}

.amount-secondary {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Status Badges */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.approved {
    background: rgba(40, 167, 69, 0.1);
    color: var(--ura-success);
}

.status-badge.disbursed {
    background: rgba(23, 71, 158, 0.1);
    color: var(--ura-secondary);
}

.status-badge.pending {
    background: rgba(255, 167, 38, 0.1);
    color: var(--ura-warning);
}

.status-badge.failed {
    background: rgba(220, 53, 69, 0.1);
    color: var(--ura-danger);
}

/* Bank Info */
.bank-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.bank-logo {
    width: 30px;
    height: 30px;
    border-radius: 5px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ura-primary);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-btn.view {
    background: rgba(23, 162, 184, 0.1);
    color: var(--ura-info);
}

.action-btn.view:hover {
    background: var(--ura-info);
    color: white;
}

.action-btn.disburse {
    background: rgba(40, 167, 69, 0.1);
    color: var(--ura-success);
}

.action-btn.disburse:hover {
    background: var(--ura-success);
    color: white;
}

.action-btn.cancel {
    background: rgba(220, 53, 69, 0.1);
    color: var(--ura-danger);
}

.action-btn.cancel:hover {
    background: var(--ura-danger);
    color: white;
}

/* Disbursement Modal */
.disbursement-modal .modal-header {
    background: var(--ura-gradient);
    color: white !important;
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
}

.disbursement-modal .modal-title {
    color: white !important;
}

.disbursement-modal .btn-close-white {
    filter: brightness(0) invert(1);
}

/* Loan Details Modal - Ensure white title */
#loanDetailsModal .modal-header {
    background: linear-gradient(135deg, #003366 0%, #17479E 100%) !important;
    color: white !important;
}

#loanDetailsModal .modal-title {
    color: white !important;
}

#loanDetailsModal .btn-close-white {
    filter: brightness(0) invert(1);
}

.disbursement-modal .modal-body {
    padding: 0;
}

.disbursement-modal .modal-dialog {
    max-width: 1200px;
}

#disbursementContent {
    padding: 2rem;
}

/* Disbursement Summary Styling */
.disbursement-summary {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Modern Disbursement Modal Styles */
.disbursement-header-modern {
    background: linear-gradient(135deg, #003366 0%, #17479E 100%);
    border-radius: 0;
    padding: 1.5rem 1rem;
    margin: 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.disbursement-header-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 15s linear infinite;
}

.disbursement-amount-display {
    position: relative;
    z-index: 1;
}

.amount-label-modern {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 0.5rem;
}

.amount-value-modern {
    color: white;
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
}

.currency-symbol {
    font-size: 1.25rem;
    opacity: 0.9;
}

.amount-number {
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.3);
    letter-spacing: -1px;
}

/* Modern Info Cards */
.info-card-modern {
    background: white;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.info-card-modern:hover {
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.15);
    transform: translateY(-3px);
}

.card-header-modern {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ura-primary);
    min-height: 45px;
}

.card-header-modern i {
    font-size: 1rem;
}

.badge-modern {
    margin-left: auto;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-body-modern {
    padding: 0.75rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Disbursement Cards Row */
.disbursement-cards-row {
    padding: 1rem;
    background: #f8f9fa;
    margin: 0 -2rem;
    border-top: 1px solid #e9ecef;
}

.disbursement-cards-row .row {
    margin: 0 -0.5rem;
}

.disbursement-cards-row .col-md-4 {
    padding: 0 0.5rem;
    margin-bottom: 1rem;
}

/* Compact Info Items */
.info-item-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.3rem 0;
    border-bottom: 1px solid #f1f3f5;
}

.info-item-compact:last-child {
    border-bottom: none;
}

.info-item-compact .info-label {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-weight: 500;
    margin: 0;
}

.info-item-compact .info-value {
    font-size: 0.8rem;
    color: #2c3e50;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.info-item-compact .info-value-small {
    font-size: 0.7rem;
    color: #495057;
    font-weight: 500;
    text-align: right;
}

/* Bank Badge */
.bank-badge-container {
    display: flex;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.bank-badge-large {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
}

/* Channel Badge */
.channel-badge-container {
    display: flex;
    justify-content: center;
}

.channel-badge-large {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.channel-info-compact {
    text-align: center;
}

.channel-name-compact {
    font-size: 0.9rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.2rem;
}

.channel-desc-compact {
    font-size: 0.7rem;
    color: #6c757d;
}

.channel-meta-info {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.info-row-modern {
    display: flex;
    gap: 3rem;
    margin-bottom: 1.25rem;
}

.info-row-modern:last-child {
    margin-bottom: 0;
}

.info-item-modern {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.info-value {
    display: block;
    font-size: 1.1rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Bank Display Modern */
.bank-display-modern {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 0.5rem 0;
}

.bank-logo-modern {
    width: 100px;
    height: 100px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.bank-details-modern {
    flex: 1;
}

.bank-name-full {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.75rem;
}

.bank-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.meta-item {
    font-size: 0.95rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.meta-item i {
    font-size: 0.75rem;
}

/* Channel Card Modern */
.channel-card-modern {
    border: 2px solid;
    transition: all 0.3s ease;
}

.channel-display-modern {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 0.5rem 0;
}

.channel-icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
    animation: pulse 2s infinite;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.channel-details {
    flex: 1;
}

.channel-name-modern {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.25rem;
}

.channel-desc-modern {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.channel-reason {
    font-size: 0.8rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

/* Confirmation Section */
.confirmation-section-modern {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.confirmation-message {
    background: linear-gradient(135deg, #28a74510, #28a74520);
    border: 1px solid #28a745;
    border-radius: 6px;
    padding: 0.4rem 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #28a745;
    font-size: 0.75rem;
    margin-bottom: 0.4rem;
}

.confirmation-message i {
    font-size: 0.9rem;
}

.warning-message-modern {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 6px;
    padding: 0.3rem 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: #856404;
    font-size: 0.7rem;
}

.warning-message-modern i {
    color: #ffc107;
    font-size: 0.85rem;
}

/* Modal Footer Update */
.disbursement-modal .modal-footer {
    background: #f8f9fa;
    border-top: 2px solid #e9ecef;
    padding: 1.25rem;
}

.disbursement-modal .btn-primary {
    background: var(--ura-gradient);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.disbursement-modal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
}

.disbursement-modal .btn-secondary {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    font-weight: 500;
    color: #6c757d;
}

.summary-value {
    font-weight: 600;
    color: #2c3e50;
}

.summary-value.large {
    font-size: 1.5rem;
    color: var(--ura-success);
}

/* Channel Info Styling */
.channel-info .alert {
    border: 2px solid var(--ura-primary);
    background: rgba(0, 51, 102, 0.05);
}

.channel-icon-large {
    font-size: 2.5rem;
    width: 60px;
    text-align: center;
}

.disbursement-summary {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Modern Disbursement Modal Styles */
.disbursement-header-modern {
    background: linear-gradient(135deg, #003366 0%, #17479E 100%);
    border-radius: 0;
    padding: 1.5rem 1rem;
    margin: 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.disbursement-header-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 15s linear infinite;
}

.disbursement-amount-display {
    position: relative;
    z-index: 1;
}

.amount-label-modern {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 0.5rem;
}

.amount-value-modern {
    color: white;
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
}

.currency-symbol {
    font-size: 1.25rem;
    opacity: 0.9;
}

.amount-number {
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.3);
    letter-spacing: -1px;
}

/* Modern Info Cards */
.info-card-modern {
    background: white;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.info-card-modern:hover {
    box-shadow: 0 5px 20px rgba(0, 51, 102, 0.15);
    transform: translateY(-3px);
}

.card-header-modern {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ura-primary);
    min-height: 45px;
}

.card-header-modern i {
    font-size: 1rem;
}

.badge-modern {
    margin-left: auto;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-body-modern {
    padding: 0.75rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Disbursement Cards Row */
.disbursement-cards-row {
    padding: 1rem;
    background: #f8f9fa;
    margin: 0 -2rem;
    border-top: 1px solid #e9ecef;
}

.disbursement-cards-row .row {
    margin: 0 -0.5rem;
}

.disbursement-cards-row .col-md-4 {
    padding: 0 0.5rem;
    margin-bottom: 1rem;
}

/* Compact Info Items */
.info-item-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.3rem 0;
    border-bottom: 1px solid #f1f3f5;
}

.info-item-compact:last-child {
    border-bottom: none;
}

.info-item-compact .info-label {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-weight: 500;
    margin: 0;
}

.info-item-compact .info-value {
    font-size: 0.8rem;
    color: #2c3e50;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.info-item-compact .info-value-small {
    font-size: 0.7rem;
    color: #495057;
    font-weight: 500;
    text-align: right;
}

/* Bank Badge */
.bank-badge-container {
    display: flex;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.bank-badge-large {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
}

/* Channel Badge */
.channel-badge-container {
    display: flex;
    justify-content: center;
}

.channel-badge-large {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.channel-info-compact {
    text-align: center;
}

.channel-name-compact {
    font-size: 0.9rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.2rem;
}

.channel-desc-compact {
    font-size: 0.7rem;
    color: #6c757d;
}

.channel-meta-info {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.info-row-modern {
    display: flex;
    gap: 3rem;
    margin-bottom: 1.25rem;
}

.info-row-modern:last-child {
    margin-bottom: 0;
}

.info-item-modern {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.info-value {
    display: block;
    font-size: 1.1rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Bank Display Modern */
.bank-display-modern {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 0.5rem 0;
}

.bank-logo-modern {
    width: 100px;
    height: 100px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.bank-details-modern {
    flex: 1;
}

.bank-name-full {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.75rem;
}

.bank-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.meta-item {
    font-size: 0.95rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.meta-item i {
    font-size: 0.75rem;
}

/* Channel Card Modern */
.channel-card-modern {
    border: 2px solid;
    transition: all 0.3s ease;
}

.channel-display-modern {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 0.5rem 0;
}

.channel-icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
    animation: pulse 2s infinite;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.channel-details {
    flex: 1;
}

.channel-name-modern {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin-bottom: 0.25rem;
}

.channel-desc-modern {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.channel-reason {
    font-size: 0.8rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

/* Confirmation Section */
.confirmation-section-modern {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.confirmation-message {
    background: linear-gradient(135deg, #28a74510, #28a74520);
    border: 1px solid #28a745;
    border-radius: 6px;
    padding: 0.4rem 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #28a745;
    font-size: 0.75rem;
    margin-bottom: 0.4rem;
}

.confirmation-message i {
    font-size: 0.9rem;
}

.warning-message-modern {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 6px;
    padding: 0.3rem 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: #856404;
    font-size: 0.7rem;
}

.warning-message-modern i {
    color: #ffc107;
    font-size: 0.85rem;
}

/* Modal Footer Update */
.disbursement-modal .modal-footer {
    background: #f8f9fa;
    border-top: 2px solid #e9ecef;
    padding: 1.25rem;
}

.disbursement-modal .btn-primary {
    background: var(--ura-gradient);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.disbursement-modal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
}

.disbursement-modal .btn-secondary {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 600;
    color: var(--ura-primary);
}

.summary-value.large {
    font-size: 1.25rem;
    color: var(--ura-success);
}

/* Channel Selection */
.channel-selection {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.channel-option {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Responsive Design for Cards */
@media (max-width: 992px) {
    .disbursement-cards-row .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .info-card-modern {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .disbursement-cards-row {
        padding: 1rem;
        margin: 0 -1rem;
    }
    
    .disbursement-header-modern {
        padding: 2rem 1rem;
    }
    
    .amount-number {
        font-size: 2.5rem;
    }
    
    .currency-symbol {
        font-size: 1.5rem;
    }
}

.channel-option:hover {
    border-color: var(--ura-secondary);
}

.channel-option.selected {
    background: var(--ura-gradient);
    color: white;
    border-color: var(--ura-primary);
}

.channel-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.channel-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.channel-description {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Loading State */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none;
}

.loading-overlay.active {
    display: flex;
}

.loading-content {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--ura-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Modern Page Header */
.page-header-modern {
    background: linear-gradient(135deg, #003366 0%, #17479E 100%);
    border-radius: 20px;
    padding: 1.25rem;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.15);
    position: relative;
    overflow: hidden;
}

.page-header-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 40%;
    height: 200%;
    background: rgba(255, 255, 255, 0.05);
    transform: rotate(35deg);
}

.page-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 1;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-icon-wrapper {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
}

.page-title {
    color: white;
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.5px;
}

.page-breadcrumb {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.875rem;
}

.breadcrumb-separator {
    margin: 0 0.5rem;
    color: rgba(255, 255, 255, 0.5);
}

.breadcrumb-item.active {
    color: white;
    font-weight: 500;
}

.page-header-right {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.header-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-modern {
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-modern.btn-refresh {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 45px;
    height: 45px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-modern.btn-export {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.btn-modern.btn-primary {
    background: white;
    color: var(--ura-primary);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Modern Table Styles */
.table-row-modern {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.table-row-modern:hover {
    background: linear-gradient(90deg, rgba(23, 71, 158, 0.05) 0%, rgba(33, 150, 243, 0.05) 100%);
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.1);
}

.table-row-modern.disabled-row {
    cursor: not-allowed;
    opacity: 0.7;
}

.table-row-modern.disabled-row:hover {
    transform: none;
    background: transparent;
    box-shadow: none;
}

.table-row-modern.clickable-row {
    cursor: pointer;
}

.table-row-modern.clickable-row:hover td:first-child::before {
    content: '→';
    position: absolute;
    left: -20px;
    color: var(--ura-primary);
    font-weight: bold;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        left: -30px;
        opacity: 0;
    }
    to {
        left: -20px;
        opacity: 1;
    }
}

.checkbox-cell {
    width: 50px;
}

.modern-checkbox .form-check-input {
    width: 20px;
    height: 20px;
    border: 2px solid #dee2e6;
    cursor: pointer;
}

.modern-checkbox .form-check-input:checked {
    background-color: var(--ura-primary);
    border-color: var(--ura-primary);
}

.employee-info-modern {
    padding: 0.5rem 0;
}

.employee-name {
    font-weight: 600;
    color: var(--ura-dark);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.employee-meta {
    display: flex;
    gap: 0.75rem;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: #f8f9fa;
    border-radius: 5px;
    font-size: 0.75rem;
    color: #6c757d;
}

.meta-badge i {
    font-size: 0.7rem;
}

.amount-cell {
    text-align: right;
}

.amount-wrapper {
    padding: 0.5rem 0;
}

.amount-wrapper.highlight .amount-value {
    font-size: 1.1rem;
}

.amount-value {
    font-weight: 700;
    color: var(--ura-primary);
    font-size: 1rem;
    margin-bottom: 0.125rem;
}

.amount-label {
    font-size: 0.75rem;
    color: #6c757d;
}

.bank-info-modern {
    padding: 0.5rem 0;
}

.bank-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: var(--ura-dark);
    margin-bottom: 0.25rem;
}

.bank-badge-large {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    background: var(--ura-gradient);
    color: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    min-width: 60px;
    letter-spacing: 0.5px;
}

.bank-swift {
    font-size: 0.75rem;
    color: #6c757d;
}

.account-info {
    padding: 0.5rem 0;
}

.account-number {
    font-weight: 600;
    color: var(--ura-dark);
    font-size: 0.9rem;
    margin-bottom: 0.125rem;
}

.account-name {
    font-size: 0.75rem;
    color: #6c757d;
}

.status-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.75rem;
    background: #f8f9fa;
    border-radius: 20px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-dot.pulse {
    animation: pulse-dot 1.5s infinite;
}

@keyframes pulse-dot {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.5); }
    100% { opacity: 1; transform: scale(1); }
}

.status-modern.success {
    background: rgba(40, 167, 69, 0.1);
    color: var(--ura-success);
}

.status-modern.success .status-dot {
    background: var(--ura-success);
}

.status-modern.danger {
    background: rgba(220, 53, 69, 0.1);
    color: var(--ura-danger);
}

.status-modern.danger .status-dot {
    background: var(--ura-danger);
}

.status-modern.warning {
    background: rgba(255, 167, 38, 0.1);
    color: var(--ura-warning);
}

.status-modern.warning .status-dot {
    background: var(--ura-warning);
}

.status-modern.info {
    background: rgba(23, 162, 184, 0.1);
    color: var(--ura-info);
}

.status-modern.info .status-dot {
    background: var(--ura-info);
}

.status-text {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.channel-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.channel-badge.internal {
    background: linear-gradient(135deg, #003366, #17479E);
    color: white;
}

.channel-badge.domestic {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.channel-badge.tiss {
    background: linear-gradient(135deg, #6f42c1, #a855f7);
    color: white;
}

.channel-badge i {
    font-size: 0.7rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .page-header-content {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .page-header-left,
    .page-header-right {
        width: 100%;
    }
    
    .header-stats {
        display: none;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .header-actions {
        flex-wrap: wrap;
    }
    
    .meta-badge {
        font-size: 0.7rem;
    }
}
</style>

<div class="container-fluid py-3">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h5>Processing Disbursement...</h5>
            <p class="text-muted">Please wait while we process your request</p>
        </div>
    </div>

    <!-- Modern Page Header with Gradient -->
    <div class="page-header-modern mb-4">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-icon-wrapper">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="page-title-section">
                    <h2 class="page-title">Ready for Disbursement</h2>
                    <div class="page-breadcrumb">
                        <span class="breadcrumb-item">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </span>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item">Loan Management</span>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-item active">Employer Approved - Ready for Disbursement</span>
                    </div>
                </div>
            </div>
            <div class="page-header-right">
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $stats['pending_disbursement'] ?? 0 }}</span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ date('M d') }}</span>
                        <span class="stat-label">Today</span>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-modern btn-refresh" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn-modern btn-export" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-2 text-success"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-2 text-danger"></i>PDF</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('loan-offers.index') }}" class="btn-modern btn-primary">
                        <i class="fas fa-th-list me-2"></i>All Loans
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Dashboard -->
    <div class="kpi-dashboard-modern">
        <div class="row g-2">
            <div class="col">
                <div class="kpi-card-disbursement">
                    <div class="kpi-icon-wrapper primary">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="kpi-value">{{ $stats['ready_to_disburse'] ?? 0 }}</div>
                    <div class="kpi-label">Ready to Disburse</div>
                    <div class="kpi-trend positive">
                        <i class="fas fa-arrow-up me-1"></i> {{ $stats['growth'] ?? '12' }}% this month
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="kpi-card-disbursement">
                    <div class="kpi-icon-wrapper info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="kpi-label">Employer Approved</div>
                    <div class="kpi-trend positive">
                        <i class="fas fa-chart-line me-1"></i> Total loans approved by employer
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="kpi-card-disbursement">
                    <div class="kpi-icon-wrapper success">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="kpi-value">{{ $stats['disbursed'] ?? 0 }}</div>
                    <div class="kpi-label">Successfully Disbursed</div>
                    <div class="kpi-trend positive">
                        <i class="fas fa-check-circle me-1"></i> Completed
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="kpi-card-disbursement">
                    <div class="kpi-icon-wrapper info">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="kpi-value">{{ number_format($stats['total_amount'] ?? 0, 0) }}</div>
                    <div class="kpi-label">Total Amount (TZS)</div>
                    <div class="kpi-trend positive">
                        <i class="fas fa-chart-line me-1"></i> Portfolio value
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional KPI Row for Rejected/Failed -->
        @if(($stats['rejected'] ?? 0) > 0 || ($stats['failed'] ?? 0) > 0)
        <div class="row mt-3">
            @if(($stats['rejected'] ?? 0) > 0)
            <div class="col-md-6">
                <div class="kpi-card-disbursement" style="border-left: 3px solid var(--ura-danger);">
                    <div class="kpi-icon-wrapper" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="kpi-value text-danger">{{ $stats['rejected'] ?? 0 }}</div>
                    <div class="kpi-label">Rejected Disbursements</div>
                    <div class="kpi-trend negative">
                        <i class="fas fa-info-circle me-1"></i> Review rejection reasons
                    </div>
                </div>
            </div>
            @endif
            
            @if(($stats['failed'] ?? 0) > 0)
            <div class="col-md-6">
                <div class="kpi-card-disbursement" style="border-left: 3px solid #6c757d;">
                    <div class="kpi-icon-wrapper" style="background: linear-gradient(135deg, #6c757d, #5a6268); color: white;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="kpi-value text-secondary">{{ $stats['failed'] ?? 0 }}</div>
                    <div class="kpi-label">Failed Disbursements</div>
                    <div class="kpi-trend negative">
                        <i class="fas fa-redo me-1"></i> Can be retried
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Batch Actions Bar -->
    <div id="batchActionsBar" class="batch-actions-bar">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="batch-info">
                    <div class="batch-count">
                        <span id="selectedCount">0</span> loans selected
                    </div>
                    <div class="text-muted">
                        Total Amount: <strong id="selectedAmount">0</strong> TZS
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="batch-actions justify-content-end">
                    <button class="batch-btn secondary" onclick="clearSelection()">
                        <i class="fas fa-times me-2"></i>Clear Selection
                    </button>
                    <button class="batch-btn primary" onclick="processBatchDisbursement()">
                        <i class="fas fa-paper-plane me-2"></i>Process Batch Disbursement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-section-disbursement">
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="search-input-disbursement">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search by name, application number, or check number..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="filter-pills">
                    <button class="filter-pill filter-btn {{ !request('filter') ? 'active' : '' }}" 
                            onclick="applyFilter('')">
                        All Approved
                    </button>
                    <button class="filter-pill filter-btn {{ request('filter') == 'ready' ? 'active' : '' }}" 
                            onclick="applyFilter('ready')">
                        Ready to Disburse
                    </button>
                    <button class="filter-pill filter-btn {{ request('filter') == 'disbursed' ? 'active' : '' }}" 
                            onclick="applyFilter('disbursed')">
                        Disbursed
                    </button>
                    <button class="filter-pill filter-btn {{ request('filter') == 'failed' ? 'active' : '' }}" 
                            onclick="applyFilter('failed')">
                        Failed
                    </button>
                    <button class="filter-pill filter-btn {{ request('filter') == 'rejected' ? 'active' : '' }}" 
                            onclick="applyFilter('rejected')">
                        <i class="fas fa-times-circle me-1"></i>Rejected
                    </button>
                    <button class="filter-pill filter-btn {{ request('filter') == 'today' ? 'active' : '' }}" 
                            onclick="applyFilter('today')">
                        Today's Approvals
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="disbursement-table">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="40">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                        </div>
                    </th>
                    <th>Employee</th>
                    <th class="text-end">Requested Amount</th>
                    <th class="text-end">Take Home</th>
                    <th>Bank</th>
                    <th>Account</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Channel</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="loanTableBody">
                @forelse($loanOffers as $offer)
                <tr class="table-row-modern {{ $offer->isDisbursed() ? 'disabled-row' : 'clickable-row' }}" 
                    data-id="{{ $offer->id }}" 
                    data-amount="{{ $offer->take_home_amount ?? $offer->net_loan_amount ?? $offer->requested_amount }}"
                    data-disbursed="{{ $offer->isDisbursed() ? 'true' : 'false' }}"
                    onclick="handleRowClick(event, {{ $offer->id }})">
                    <td class="checkbox-cell">
                        <div class="form-check modern-checkbox">
                            <input class="form-check-input loan-checkbox" 
                                   type="checkbox" 
                                   value="{{ $offer->id }}"
                                   id="loan-{{ $offer->id }}"
                                   {{ $offer->isDisbursed() ? 'disabled' : '' }}>
                            <label class="form-check-label" for="loan-{{ $offer->id }}"></label>
                        </div>
                    </td>
                    <td class="employee-cell">
                        <div class="employee-info-modern">
                            <div class="employee-name">{{ $offer->first_name }} {{ $offer->middle_name }} {{ $offer->last_name }}</div>
                            <div class="employee-meta">
                                <span class="meta-badge">
                                    <i class="fas fa-id-badge"></i> {{ $offer->check_number }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="amount-cell">
                        <div class="amount-wrapper">
                            <div class="amount-value">{{ number_format($offer->requested_amount ?? 0, 0) }}</div>
                            <div class="amount-label">TZS • {{ $offer->tenure ?? 12 }} months</div>
                        </div>
                    </td>
                    <td class="amount-cell">
                        <div class="amount-wrapper highlight">
                            <div class="amount-value text-success">{{ number_format($offer->take_home_amount ?? $offer->net_loan_amount ?? 0, 0) }}</div>
                            <div class="amount-label">Take Home</div>
                        </div>
                    </td>
                    <td class="bank-cell">
                        <div class="bank-info-modern">
                            <div class="bank-name">
                                <span class="bank-badge-large" style="background: linear-gradient(135deg, #003366, #17479E);">
                                    @if($offer->bank)
                                        {{ $offer->bank->short_name ?? substr($offer->bank->name, 0, 4) }}
                                    @else
                                        {{ substr($offer->swift_code ?? 'BANK', 0, 4) }}
                                    @endif
                                </span>
                            </div>
                            <div class="bank-swift">{{ $offer->swift_code ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="account-cell">
                        <div class="account-info">
                            <div class="account-number">{{ $offer->bank_account_number ?? 'Not Provided' }}</div>
                            <div class="account-name">{{ $offer->bank_account_name ?? $offer->first_name . ' ' . $offer->last_name }}</div>
                        </div>
                    </td>
                    <td class="status-cell">
                        @php
                            $latestDisbursement = $offer->disbursements()->latest()->first();
                            $isDisbursed = $offer->isDisbursed();
                            // All loans shown here have employer approval (filtered in controller)
                        @endphp
                        
                        @if($isDisbursed)
                            <div class="status-modern success">
                                <span class="status-dot"></span>
                                <span class="status-text">Disbursed</span>
                            </div>
                        @elseif($latestDisbursement && $latestDisbursement->status == 'failed')
                            <div class="status-modern danger">
                                <span class="status-dot"></span>
                                <span class="status-text">Failed</span>
                            </div>
                        @elseif($latestDisbursement && $latestDisbursement->status == 'pending')
                            <div class="status-modern warning">
                                <span class="status-dot pulse"></span>
                                <span class="status-text">Processing</span>
                            </div>
                        @else
                            <div class="status-modern info">
                                <span class="status-dot"></span>
                                <span class="status-text">Ready</span>
                                <small class="d-block text-success mt-1">
                                    <i class="fas fa-check-circle"></i> ESS Approved
                                </small>
                            </div>
                        @endif
                    </td>
                    <td class="channel-cell">
                        @php
                            $channel = null;
                            if($offer->disbursements && $offer->disbursements->isNotEmpty()) {
                                $channel = $offer->disbursements->first()->channel_identifier;
                            } elseif($offer->swift_code === 'NMIBTZTZ') {
                                $channel = 'INTERNAL';
                            } elseif(($offer->take_home_amount ?? $offer->requested_amount) >= 20000000) {
                                $channel = 'TISS';
                            } else {
                                $channel = 'DOMESTIC';
                            }
                        @endphp
                        @if($channel)
                            <span class="channel-badge {{ strtolower($channel) }}">
                                @if($channel == 'INTERNAL')
                                    <i class="fas fa-building"></i>
                                @elseif($channel == 'TISS')
                                    <i class="fas fa-globe"></i>
                                @else
                                    <i class="fas fa-exchange-alt"></i>
                                @endif
                                {{ $channel }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view view-loan-btn" 
                                    onclick="event.stopPropagation();"
                                    data-bs-toggle="modal"
                                    data-bs-target="#loanDetailsModal"
                                    data-loan='{{ json_encode($offer) }}'
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(!$offer->isDisbursed())
                            <button class="action-btn disburse" 
                                    id="disburse-btn-{{ $offer->id }}"
                                    onclick="handleDisburseClick(this, {{ $offer->id }})"
                                    title="Disburse">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            @endif
                            @php
                                $hasFailedDisbursement = $offer->disbursements()->where('status', 'failed')->exists();
                            @endphp
                            @if($hasFailedDisbursement)
                            <button class="action-btn cancel" 
                                    onclick="event.stopPropagation(); retryDisbursement({{ $offer->id }})"
                                    title="Retry">
                                <i class="fas fa-redo"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Loans Ready for Disbursement</h5>
                            <p class="text-muted">There are no employer-approved loans in "Submitted for disbursement" state</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($loanOffers->hasPages())
    <div class="mt-3">
        {{ $loanOffers->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- View Details Modal -->
@include('employee_loan.partials.loan-details-modal')

<!-- Include Reusable Modals -->
@include('employee_loan.modals.disbursement-modal')
@include('employee_loan.modals.reject-disbursement-modal')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Global variables
let selectedLoans = new Set();
let currentLoanId = null;

// Validate message in real-time
function validateMessage(textarea) {
    const messageLength = textarea.value.trim().length;
    const parentDiv = textarea.closest('.mb-3');
    
    // Remove any existing validation feedback
    const existingFeedback = parentDiv.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Add validation state
    if (messageLength === 0 && textarea.value.length > 0) {
        textarea.classList.add('is-invalid');
        textarea.classList.remove('is-valid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Message cannot be empty or only spaces';
        textarea.parentNode.insertBefore(feedback, textarea.nextSibling);
    } else if (messageLength > 0 && messageLength < 20) {
        textarea.classList.add('is-invalid');
        textarea.classList.remove('is-valid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        feedback.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>Please provide more detail (${20 - messageLength} more characters needed)`;
        textarea.parentNode.insertBefore(feedback, textarea.nextSibling);
    } else if (messageLength >= 20) {
        textarea.classList.remove('is-invalid');
        textarea.classList.add('is-valid');
    } else {
        textarea.classList.remove('is-invalid');
        textarea.classList.remove('is-valid');
    }
}

// Quick template function for rejection messages
// Handle rejection reason dropdown change
function handleRejectionReasonChange(selectElement) {
    const selectedValue = selectElement.value;
    const messageTextarea = document.getElementById('rejectionMessage');
    
    // Define short template messages for each category (API compliant - max 150 chars)
    const templateMessages = {
        'Insufficient funds': 'Insufficient funds for disbursement. Please try again later.',
        'Incomplete documentation': 'Missing required documents. Submit via ESS portal.',
        'Account verification pending': 'Account verification in progress. Will notify once complete.',
        'Customer cancellation': 'Loan cancelled as per your request.',
        'Technical error': 'Technical error occurred. Resolution in progress.',
        'Compliance check failed': 'Compliance requirements not met. Contact support for details.',
        'Invalid bank details': 'Bank account details incorrect. Please update and resubmit.'
    };
    
    // If a template message exists for the selected category, populate it
    if (templateMessages[selectedValue]) {
        messageTextarea.value = templateMessages[selectedValue];
        
        // Update character counter
        const charCount = document.getElementById('charCount');
        if (charCount) {
            charCount.textContent = messageTextarea.value.length;
            
            // Update color based on length
            const count = messageTextarea.value.length;
            if (count > 140) {
                charCount.parentElement.style.color = '#dc3545';
            } else if (count > 120) {
                charCount.parentElement.style.color = '#ffc107';
            } else {
                charCount.parentElement.style.color = '#6c757d';
            }
        }
    } else if (selectedValue === 'other' || selectedValue === '') {
        // Clear the message for 'other' or empty selection
        messageTextarea.value = '';
        const charCount = document.getElementById('charCount');
        if (charCount) {
            charCount.textContent = '0';
            charCount.parentElement.style.color = '#6c757d';
        }
    }
}

function setTemplate(type) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const reasonSelect = document.getElementById('rejectionReason');
    
    const templates = {
        'verification': {
            reason: 'Account verification pending',
            message: 'Account verification in progress. Will notify once complete.'
        },
        'documents': {
            reason: 'Incomplete documentation',
            message: 'Missing required documents. Submit via ESS portal.'
        },
        'technical': {
            reason: 'Technical error',
            message: 'Technical error occurred. Resolution in progress.'
        }
    };
    
    if (templates[type]) {
        reasonSelect.value = templates[type].reason;
        messageTextarea.value = templates[type].message;
        
        // Update character counter
        const charCount = document.getElementById('charCount');
        if (charCount) {
            charCount.textContent = messageTextarea.value.length;
            
            // Update color based on length (adjusted for 150 char limit)
            const count = messageTextarea.value.length;
            if (count > 140) {
                charCount.parentElement.style.color = '#dc3545';
            } else if (count > 120) {
                charCount.parentElement.style.color = '#ffc107';
            } else {
                charCount.parentElement.style.color = '#6c757d';
            }
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initializeCheckboxes();
    updateBatchActionsBar();
    initializeRowClickHandlers();
    
    // Initialize character counter for reject modal
    const textarea = document.getElementById('rejectionMessage');
    const charCount = document.getElementById('charCount');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            // Change color when approaching limit (adjusted for 150 char limit)
            if (count > 140) {
                charCount.parentElement.style.color = '#dc3545';
            } else if (count > 120) {
                charCount.parentElement.style.color = '#ffc107';
            } else {
                charCount.parentElement.style.color = '#6c757d';
            }
        });
    }
    
    // Initialize disbursement modal buttons
    const confirmBtn = document.getElementById('confirmDisbursement');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const loanId = document.getElementById('selectedLoanId')?.value || currentLoanId;
            const channel = document.getElementById('selectedChannel')?.value;
            
            if (loanId) {
                processDisbursement(loanId, channel);
            } else {
                showToast('No loan selected for disbursement', 'error');
            }
        });
    }
    
    // Initialize reject button
    const rejectDisbBtn = document.getElementById('rejectDisbursement');
    if (rejectDisbBtn) {
        rejectDisbBtn.addEventListener('click', handleRejectDisbursement);
    }
    
    // Initialize confirm rejection button
    const confirmRejectBtn = document.getElementById('confirmRejectDisbursement');
    if (confirmRejectBtn) {
        confirmRejectBtn.addEventListener('click', confirmRejectDisbursement);
    }
    
    // Auto-populate message based on reason selection
    const reasonSelect = document.getElementById('rejectionReason');
    if (reasonSelect) {
        reasonSelect.addEventListener('change', function() {
            const messageTextarea = document.getElementById('rejectionMessage');
            
            // If 'other' is selected, clear and focus the textarea
            if (this.value === 'other') {
                messageTextarea.value = '';
                messageTextarea.placeholder = 'Please specify the reason for rejection...';
                messageTextarea.focus();
            } else if (this.value) {
                // Use the selected value as the message since it's already a full sentence
                messageTextarea.value = this.value + '. Please contact our customer support for further assistance.';
            } else {
                messageTextarea.value = '';
                messageTextarea.placeholder = 'Enter detailed reason for rejection...';
            }
        });
    }
    
    // Initialize search input
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        // Set initial value from URL
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        if (searchQuery) {
            searchInput.value = searchQuery;
        }
        
        // Handle enter key for search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch(this.value);
            }
        });
    }
});

// Initialize event listeners
function initializeEventListeners() {
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.loan-checkbox:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                if (this.checked) {
                    selectedLoans.add(checkbox.value);
                } else {
                    selectedLoans.delete(checkbox.value);
                }
            });
            updateBatchActionsBar();
        });
    }

    // Individual checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedLoans.add(this.value);
            } else {
                selectedLoans.delete(this.value);
            }
            updateBatchActionsBar();
        });
    });

    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', debounce(function(e) {
            performSearch(e.target.value);
        }, 500));
    }

    // Confirm disbursement button
    const confirmBtn = document.getElementById('confirmDisbursement');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!currentLoanId) return;
            
            const selectedChannel = document.querySelector('.channel-option.selected');
            const channel = selectedChannel ? selectedChannel.dataset.channel : 'AUTO';
            
            processDisbursement(currentLoanId, channel);
        });
    }

    // View loan buttons
    document.querySelectorAll('.view-loan-btn').forEach(button => {
        button.addEventListener('click', function() {
            const loanData = JSON.parse(this.dataset.loan || '{}');
            populateLoanDetailsModal(loanData);
        });
    });
}

// Update batch actions bar
function updateBatchActionsBar() {
    const batchBar = document.getElementById('batchActionsBar');
    const selectedCount = selectedLoans.size;
    
    if (selectedCount > 0) {
        batchBar.classList.add('active');
        document.getElementById('selectedCount').textContent = selectedCount;
        
        // Calculate total amount
        let totalAmount = 0;
        selectedLoans.forEach(id => {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                totalAmount += parseFloat(row.dataset.amount || 0);
            }
        });
        document.getElementById('selectedAmount').textContent = number_format(totalAmount, 0);
    } else {
        batchBar.classList.remove('active');
    }
}

// Clear selection
function clearSelection() {
    selectedLoans.clear();
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBatchActionsBar();
}

// Track current filter
let currentFilter = '{{ request()->get('filter', '') }}';

// Apply filter with AJAX
function applyFilter(filter) {
    // Update active button state
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (filter) {
        const activeBtn = document.querySelector(`[onclick="applyFilter('${filter}')"]`);
        if (activeBtn) activeBtn.classList.add('active');
    }
    
    // Show loading state
    showLoading(true);
    
    // Build URL with filter
    const currentUrl = new URL(window.location);
    if (filter) {
        currentUrl.searchParams.set('filter', filter);
    } else {
        currentUrl.searchParams.delete('filter');
    }
    
    // Fetch filtered data via AJAX
    fetch(currentUrl.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update table body with the HTML from response
            const tableBody = document.getElementById('loanTableBody');
            if (tableBody && data.html) {
                tableBody.innerHTML = data.html;
            }
            
            // Update statistics if present
            if (data.stats) {
                // Update the KPI cards with new stats
                const totalElement = document.querySelector('.kpi-value.total-count');
                if (totalElement) totalElement.textContent = data.stats.total.toLocaleString();
                
                const totalAmountElement = document.querySelector('.kpi-value.total-amount');
                if (totalAmountElement) totalAmountElement.textContent = 'TZS ' + data.stats.total_amount.toLocaleString();
                
                const disbursedElement = document.querySelector('.kpi-value.disbursed-count');
                if (disbursedElement) disbursedElement.textContent = data.stats.disbursed.toLocaleString();
                
                const disbursedAmountElement = document.querySelector('.kpi-value.disbursed-amount');
                if (disbursedAmountElement) disbursedAmountElement.textContent = 'TZS ' + data.stats.disbursed_amount.toLocaleString();
            }
            
            // Update URL without reload
            window.history.pushState({}, '', currentUrl.toString());
            
            // Update current filter
            currentFilter = filter || '';
            
            // Re-initialize checkboxes
            initializeCheckboxes();
            
            showLoading(false);
            
            // Show success toast
            const filterNames = {
                'ready': 'Ready to Disburse',
                'disbursed': 'Disbursed',
                'failed': 'Failed',
                'rejected': 'Rejected Disbursements',
                'today': "Today's Approvals",
                'processing': 'Processing'
            };
            
            const filterMessage = filter ? `Showing ${filterNames[filter] || filter} loans` : 'Showing all approved loans';
            showToast(filterMessage, 'success');
        } else {
            // If response is not successful, show error and reload
            showToast('Error applying filter', 'error');
            setTimeout(() => {
                window.location.href = currentUrl.toString();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error applying filter:', error);
        showLoading(false);
        // Fallback to page reload
        window.location.href = currentUrl.toString();
    });
}

// Perform search with AJAX
function performSearch(query) {
    showLoading(true);
    
    const currentUrl = new URL(window.location);
    if (query) {
        currentUrl.searchParams.set('search', query);
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    // Fetch searched data via AJAX
    fetch(currentUrl.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse and update same as filter
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update table body
        const newTableBody = doc.querySelector('#loanTableBody');
        if (newTableBody) {
            document.getElementById('loanTableBody').innerHTML = newTableBody.innerHTML;
        }
        
        // Update statistics
        const newStats = doc.querySelector('.header-stats');
        if (newStats) {
            const currentStats = document.querySelector('.header-stats');
            if (currentStats) {
                currentStats.innerHTML = newStats.innerHTML;
            }
        }
        
        // Update pagination
        const newPagination = doc.querySelector('.pagination-wrapper');
        if (newPagination) {
            const currentPagination = document.querySelector('.pagination-wrapper');
            if (currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            }
        }
        
        // Update URL without reload
        window.history.pushState({}, '', currentUrl.toString());
        
        // Re-initialize checkboxes
        initializeCheckboxes();
        
        showLoading(false);
        
        const message = query ? `Search results for "${query}"` : 'Showing all loans';
        showToast(message, 'success');
    })
    .catch(error => {
        console.error('Error performing search:', error);
        showLoading(false);
        // Fallback to page reload
        window.location.href = currentUrl.toString();
    });
}

// Initialize checkbox functionality
function initializeCheckboxes() {
    // Re-attach event listeners to checkboxes
    document.querySelectorAll('.loan-checkbox').forEach(checkbox => {
        checkbox.removeEventListener('change', handleCheckboxChange);
        checkbox.addEventListener('change', handleCheckboxChange);
    });
    
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.removeEventListener('change', handleSelectAll);
        selectAllCheckbox.addEventListener('change', handleSelectAll);
    }
}

// Handle individual checkbox change
function handleCheckboxChange(e) {
    const loanId = e.target.value;
    if (e.target.checked) {
        selectedLoans.add(loanId);
    } else {
        selectedLoans.delete(loanId);
    }
    updateBatchActionsBar();
}

// Handle select all checkbox
function handleSelectAll(e) {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
        if (e.target.checked) {
            selectedLoans.add(checkbox.value);
        } else {
            selectedLoans.delete(checkbox.value);
        }
    });
    updateBatchActionsBar();
}

// Handle row click
function handleRowClick(event, loanId) {
    // Check if click is on an action button or checkbox
    const target = event.target;
    const isActionButton = target.closest('.action-btn, .form-check-input, .dropdown, button, a');
    
    if (isActionButton) {
        // If clicking on action buttons or checkboxes, don't trigger row click
        event.stopPropagation();
        return;
    }
    
    // Check if the row is for a disbursed loan
    const row = event.currentTarget;
    if (row.dataset.disbursed === 'true') {
        // Show toast that loan is already disbursed
        showToast('This loan has already been disbursed', 'info');
        return;
    }
    
    // Open disbursement modal - the validation will happen on submission
    initiateDisbursement(loanId);
}

// Initialize row click handlers
function initializeRowClickHandlers() {
    // Add tooltips to rows
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.title = 'Click to process disbursement';
    });
    
    document.querySelectorAll('.disabled-row').forEach(row => {
        row.title = 'Already disbursed';
    });
}

// Handle disburse button click
function handleDisburseClick(button, loanId) {
    // Prevent row click from firing
    event.stopPropagation();
    
    // Check if loan is already disbursed
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row && row.dataset.disbursed === 'true') {
        Swal.fire({
            icon: 'info',
            title: 'Already Disbursed',
            text: 'This loan has already been disbursed.',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    // Disable the button to prevent double-clicks
    button.disabled = true;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    // Call the initiate disbursement function
    initiateDisbursement(loanId);
    
    // Re-enable button after modal opens (with a small delay)
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = originalContent;
    }, 1000);
}

// Initiate single disbursement
function initiateDisbursement(loanId) {
    currentLoanId = loanId;
    
    // Check if loan is already disbursed
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row && row.dataset.disbursed === 'true') {
        Swal.fire({
            icon: 'info',
            title: 'Already Disbursed',
            text: 'This loan has already been disbursed.',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    // Reset button state when opening modal
    const confirmBtn = document.getElementById('confirmDisbursement');
    if (confirmBtn) {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Confirm Disbursement';
    }
    
    // Fetch loan details
    fetch(`/loan-offers/${loanId}`)
        .then(response => response.json())
        .then(data => {
            populateDisbursementModal(data);
            const modal = new bootstrap.Modal(document.getElementById('disbursementModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load loan details', 'error');
            
            // Re-enable the table button if there was an error
            const tableBtn = document.getElementById(`disburse-btn-${loanId}`);
            if (tableBtn) {
                tableBtn.disabled = false;
                tableBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            }
        });
}

// Populate disbursement modal
function populateDisbursementModal(loan) {
    // Check if loan is already disbursed
    const row = document.querySelector(`tr[data-id="${loan.id}"]`);
    const isDisbursed = row && row.dataset.disbursed === 'true';
    
    // Update button states based on disbursement status
    const confirmBtn = document.getElementById('confirmDisbursement');
    const rejectBtn = document.getElementById('rejectDisbursement');
    
    if (isDisbursed) {
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Already Disbursed';
        }
        if (rejectBtn) {
            rejectBtn.disabled = true;
            rejectBtn.title = 'Cannot reject - already disbursed';
        }
    } else {
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Confirm Disbursement';
        }
        if (rejectBtn) {
            rejectBtn.disabled = false;
            rejectBtn.title = 'Reject this disbursement';
        }
    }
    
    // Auto-determine channel based on bank and amount
    const amount = loan.take_home_amount || loan.net_loan_amount || loan.requested_amount;
    const swiftCode = loan.swift_code || loan.bank?.swift_code || '';
    const bankName = loan.bank?.name || loan.bank_name || '';
    const bankShortName = loan.bank?.short_name || swiftCode.substring(0, 4) || 'BANK';
    const isNMB = swiftCode === 'NMIBTZTZ' || bankName.toUpperCase().includes('NMB');
    
    let selectedChannel = '';
    let channelDescription = '';
    let channelColor = '';
    let channelIcon = '';
    
    if (isNMB) {
        selectedChannel = 'INTERNAL';
        channelDescription = 'Same Bank Transfer';
        channelColor = '#28a745';
        channelIcon = 'fa-building-columns';
    } else if (amount < 20000000) {
        selectedChannel = 'DOMESTIC';
        channelDescription = 'Local Bank Transfer';
        channelColor = '#17a2b8';
        channelIcon = 'fa-money-bill-transfer';
    } else {
        selectedChannel = 'TISS';
        channelDescription = 'Large Amount Transfer';
        channelColor = '#ffc107';
        channelIcon = 'fa-globe';
    }
    
    const content = `
        <!-- Modern Header Section -->
        <div class="disbursement-header-modern">
            <div class="disbursement-amount-display">
                <div class="amount-label-modern">DISBURSEMENT AMOUNT</div>
                <div class="amount-value-modern">
                    <span class="currency-symbol">TZS</span>
                    <span class="amount-number">${number_format(amount, 0)}</span>
                </div>
            </div>
        </div>
        
        <!-- Three Column Layout -->
        <div class="disbursement-cards-row">
            <div class="row g-3">
                <!-- Application Details Card -->
                <div class="col-md-4">
                    <div class="info-card-modern h-100">
                        <div class="card-header-modern">
                            <i class="fas fa-file-invoice"></i>
                            <span>Application Details</span>
                        </div>
                        <div class="card-body-modern">
                            <div class="info-item-compact">
                                <span class="info-label">Applicant</span>
                                <span class="info-value">${loan.first_name} ${loan.middle_name || ''} ${loan.last_name}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Check Number</span>
                                <span class="info-value">${loan.check_number}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Application No.</span>
                                <span class="info-value text-primary">${loan.application_number}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Loan Tenure</span>
                                <span class="info-value">${loan.tenure || 12} months</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Monthly Deduction</span>
                                <span class="info-value">${number_format(loan.desired_deductible_amount || 0, 0)} TZS</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Banking Information Card -->
                <div class="col-md-4">
                    <div class="info-card-modern h-100">
                        <div class="card-header-modern">
                            <i class="fas fa-university"></i>
                            <span>Banking Information</span>
                        </div>
                        <div class="card-body-modern">
                            <div class="bank-badge-container">
                                <div class="bank-badge-large" style="background: linear-gradient(135deg, ${channelColor}15, ${channelColor}30); border: 2px solid ${channelColor};">
                                    <span style="color: ${channelColor}; font-weight: bold; font-size: 1.8rem;">${bankShortName}</span>
                                </div>
                            </div>
                            <div class="info-item-compact mt-3">
                                <span class="info-label">Bank Name</span>
                                <span class="info-value">${bankName}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">SWIFT Code</span>
                                <span class="info-value">${swiftCode || 'N/A'}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Account Number</span>
                                <span class="info-value text-primary">${loan.bank_account_number}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label">Account Type</span>
                                <span class="info-value">${loan.account_type || 'Savings'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transfer Channel Card -->
                <div class="col-md-4">
                    <div class="info-card-modern h-100" style="border: 2px solid ${channelColor};">
                        <div class="card-header-modern" style="background: linear-gradient(135deg, ${channelColor}10, ${channelColor}20);">
                            <i class="fas fa-route" style="color: ${channelColor};"></i>
                            <span>Transfer Channel</span>
                            <span class="badge-modern" style="background: ${channelColor};">AUTO</span>
                        </div>
                        <div class="card-body-modern">
                            <div class="channel-badge-container">
                                <div class="channel-badge-large" style="background: ${channelColor};">
                                    <i class="fas ${channelIcon}"></i>
                                </div>
                            </div>
                            <div class="channel-info-compact mt-3">
                                <div class="channel-name-compact">${selectedChannel}</div>
                                <div class="channel-desc-compact">${channelDescription}</div>
                            </div>
                            <div class="channel-meta-info">
                                <div class="info-item-compact">
                                    <span class="info-label">Reason</span>
                                    <span class="info-value-small">
                                        ${isNMB ? 'Same bank (NMB)' : amount < 20000000 ? `Amount < 20M TZS` : `Amount ≥ 20M TZS`}
                                    </span>
                                </div>
                                <div class="info-item-compact">
                                    <span class="info-label">Processing Time</span>
                                    <span class="info-value-small">
                                        ${selectedChannel === 'INTERNAL' ? 'Instant' : selectedChannel === 'DOMESTIC' ? '1-2 hours' : '2-4 hours'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Confirmation -->
        <div class="confirmation-section-modern">
            <div class="confirmation-message">
                <i class="fas fa-shield-check"></i>
                <span>Ready to process disbursement of <strong>${number_format(amount, 0)} TZS</strong> to <strong>${bankShortName}</strong> account</span>
            </div>
            <div class="warning-message-modern">
                <i class="fas fa-exclamation-circle"></i>
                This action cannot be reversed once confirmed
            </div>
        </div>
        
        <input type="hidden" id="selectedChannel" value="${selectedChannel}" />
        <input type="hidden" id="selectedLoanId" value="${loan.id}" />
    `;
    
    document.getElementById('disbursementContent').innerHTML = content;
    
    // Store loan data for later use
    window.currentDisbursementLoan = loan;
}

// Process disbursement (using updateLoanOffer with disbursement flag)
function processDisbursement(loanId, channel = null) {
    // Check if loan is already disbursed
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row && row.dataset.disbursed === 'true') {
        Swal.fire({
            icon: 'info',
            title: 'Already Disbursed',
            text: 'This loan has already been disbursed.',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    // Prevent multiple submissions
    const confirmBtn = document.getElementById('confirmDisbursement');
    if (confirmBtn && confirmBtn.disabled) {
        return; // Already processing
    }
    
    // Disable the button immediately
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    }
    
    // Get the auto-selected channel if not provided
    if (!channel) {
        channel = document.getElementById('selectedChannel')?.value || 'AUTO';
    }
    
    showLoading(true);
    
    // Use PUT method to update loan offer with SUBMITTED_FOR_DISBURSEMENT status
    fetch(`/loan-offers/${loanId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            status: 'SUBMITTED_FOR_DISBURSEMENT',
            channel: channel 
        })
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('disbursementModal')).hide();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Disbursement Successful!',
                text: data.message || 'The loan has been successfully disbursed.',
                showConfirmButton: false,
                timer: 3000
            });
            
            // Reload page after delay
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        } else {
            // Re-enable button on error
            const confirmBtn = document.getElementById('confirmDisbursement');
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Confirm Disbursement';
            }
            
            // Check for specific error types
            if (data.error === 'DUPLICATE_DISBURSEMENT') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Disbursement',
                    text: data.message || 'This loan has already been disbursed or is being processed.',
                });
            } else if (data.error === 'NO_EMPLOYER_APPROVAL') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Employer Approval Required',
                    html: '<i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #FFA726;"></i><br>' + 
                          (data.message || 'This loan requires final approval from the employer through ESS before disbursement.'),
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'Understood'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Disbursement Failed',
                    text: data.message || 'An error occurred during disbursement.',
                });
            }
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Error:', error);
        
        // Re-enable button on error
        const confirmBtn = document.getElementById('confirmDisbursement');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Confirm Disbursement';
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Failed to process disbursement. Please try again.',
        });
    });
}

// Process batch disbursement
function processBatchDisbursement() {
    if (selectedLoans.size === 0) {
        showToast('Please select loans to disburse', 'warning');
        return;
    }
    
    // Check if any selected loans are already disbursed
    const alreadyDisbursed = [];
    selectedLoans.forEach(loanId => {
        const row = document.querySelector(`tr[data-id="${loanId}"]`);
        if (row && row.dataset.disbursed === 'true') {
            alreadyDisbursed.push(loanId);
        }
    });
    
    if (alreadyDisbursed.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Already Disbursed Loans',
            html: `<p>${alreadyDisbursed.length} loan(s) in your selection have already been disbursed.</p>
                   <p>Please unselect these loans and try again.</p>`,
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    Swal.fire({
        title: 'Process Batch Disbursement?',
        html: `You are about to disburse <strong>${selectedLoans.size}</strong> loans.<br>This action cannot be undone.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#003366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Process All',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading(true);
            
            fetch('/loan-offers/batch-disburse', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ loan_ids: Array.from(selectedLoans) })
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Batch Disbursement Complete!',
                        html: `
                            <div class="text-left">
                                <p><strong>Results:</strong></p>
                                <ul>
                                    <li>Successful: ${data.successful || 0}</li>
                                    <li>Failed: ${data.failed || 0}</li>
                                    <li>Total Amount: ${number_format(data.total_amount || 0, 0)} TZS</li>
                                </ul>
                                ${data.failed > 0 ? '<p class="text-muted mt-3"><small>ESS has been notified of all failed disbursements.</small></p>' : ''}
                                ${data.errors && data.errors.length > 0 ? 
                                    `<details class="text-start mt-3">
                                        <summary class="cursor-pointer">View Error Details</summary>
                                        <ul class="mt-2 text-danger small">
                                            ${data.errors.map(error => `<li>${error}</li>`).join('')}
                                        </ul>
                                    </details>` : ''
                                }
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonColor: '#003366'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Batch Processing Failed',
                        html: '<i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #dc3545;"></i><br>' + 
                              '<strong>' + (data.message || 'An error occurred during batch processing.') + '</strong><br>' +
                              '<small class="text-muted mt-2">ESS has been notified of this failure.</small>',
                        confirmButtonColor: '#003366'
                    });
                }
            })
            .catch(error => {
                showLoading(false);
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to process batch disbursement.',
                });
            });
        }
    });
}

// Retry failed disbursement
function retryDisbursement(loanId) {
    Swal.fire({
        title: 'Retry Disbursement?',
        text: 'Do you want to retry this failed disbursement?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#003366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Retry',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processDisbursement(loanId);
        }
    });
}

// Handle reject disbursement
function handleRejectDisbursement() {
    const loanId = currentLoanId;
    if (!loanId) {
        showToast('No loan selected', 'error');
        return;
    }
    
    // Check if loan is already disbursed
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row && row.dataset.disbursed === 'true') {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Reject',
            text: 'This loan has already been successfully disbursed and cannot be rejected.',
            confirmButtonColor: '#003366'
        });
        return;
    }
    
    // Get loan details from the current modal
    const loan = window.currentDisbursementLoan;
    
    // Close disbursement modal
    bootstrap.Modal.getInstance(document.getElementById('disbursementModal')).hide();
    
    // Set up reject modal
    document.getElementById('rejectLoanId').value = loanId;
    
    // Populate loan details with modern card design
    if (loan) {
        document.getElementById('rejectLoanDetails').innerHTML = `
            <div class="detail-row">
                <span class="detail-label">Application No.</span>
                <span class="detail-value">${loan.application_number}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Customer Name</span>
                <span class="detail-value">${loan.first_name} ${loan.last_name}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Loan Amount</span>
                <span class="detail-value">${number_format(loan.take_home_amount || loan.net_loan_amount || loan.requested_amount, 0)} TZS</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check Number</span>
                <span class="detail-value">${loan.check_number}</span>
            </div>
        `;
    }
    
    // Clear previous inputs
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionMessage').value = '';
    document.getElementById('charCount').textContent = '0';
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('rejectDisbursementModal'));
    modal.show();
}

// Confirm rejection
function confirmRejectDisbursement() {
    const loanId = document.getElementById('rejectLoanId').value;
    const reason = document.getElementById('rejectionReason').value;
    const message = document.getElementById('rejectionMessage').value.trim();
    
    // Check if loan is already disbursed before proceeding
    const row = document.querySelector(`tr[data-id="${loanId}"]`);
    if (row && row.dataset.disbursed === 'true') {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Reject',
            text: 'This loan has already been successfully disbursed and cannot be rejected.',
            confirmButtonColor: '#003366'
        });
        bootstrap.Modal.getInstance(document.getElementById('rejectDisbursementModal')).hide();
        return;
    }
    
    // Validate inputs
    if (!reason || reason === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Rejection Category Required',
            text: 'Please select a rejection category from the dropdown.',
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    if (!message || message === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Customer Message Required',
            html: `
                <div class="text-center">
                    <i class="fas fa-comment-alt mb-3" style="font-size: 48px; color: #ffc107;"></i>
                    <p>Please enter a customer-friendly message explaining the reason for rejection.</p>
                    <small class="text-muted">This message will be sent to the customer through ESS.</small>
                </div>
            `,
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK',
            didClose: () => {
                // Focus on the message textarea after closing
                document.getElementById('rejectionMessage').focus();
            }
        });
        return;
    }
    
    // Check minimum message length
    if (message.length < 20) {
        Swal.fire({
            icon: 'warning',
            title: 'Message Too Short',
            html: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle mb-3" style="font-size: 48px; color: #ffc107;"></i>
                    <p>Please provide a more detailed explanation for the customer.</p>
                    <small class="text-muted">Minimum 20 characters required. Current: ${message.length} characters.</small>
                </div>
            `,
            confirmButtonColor: '#17479E',
            confirmButtonText: 'OK',
            didClose: () => {
                document.getElementById('rejectionMessage').focus();
            }
        });
        return;
    }
    
    // Use the message as the reason if 'other' is selected, otherwise use the selected reason value
    const finalReason = (reason === 'other') ? message : reason;
    
    // Show confirmation before proceeding
    Swal.fire({
        title: 'Confirm Rejection',
        html: `
            <div class="text-start">
                <p><strong>You are about to reject this loan disbursement.</strong></p>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">Category:</small><br>
                    <strong>${reason === 'other' ? 'Other (Custom Reason)' : reason}</strong><br><br>
                    <small class="text-muted">Message to Customer:</small><br>
                    <div class="mt-2 p-2 border rounded bg-white" style="max-height: 150px; overflow-y: auto;">
                        ${message}
                    </div>
                </div>
                <p class="mt-3 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times-circle me-2"></i>Yes, Reject Loan',
        cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('rejectDisbursementModal')).hide();
            
            // Show loading
            showLoading(true);
            
            // Send request to backend
            fetch(`/loan-offers/${loanId}/reject-disbursement`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reason: finalReason,
            detailed_message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Disbursement Rejected',
                html: `
                    <div class="text-center">
                        <i class="fas fa-times-circle mb-3" style="font-size: 48px; color: #dc3545;"></i>
                        <p>${data.message}</p>
                        <small class="text-muted">ESS has been notified with the rejection reason.</small>
                    </div>
                `,
                confirmButtonColor: '#003366'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Operation Failed',
                text: data.message || 'Failed to process the rejection',
                confirmButtonColor: '#003366'
            });
        }
    })
            .catch(error => {
                showLoading(false);
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to process the rejection. Please try again.',
                    confirmButtonColor: '#003366'
                });
            });
        }
    });
}

// Show/hide loading overlay
function showLoading(show) {
    const overlay = document.getElementById('loadingOverlay');
    if (show) {
        overlay.classList.add('active');
    } else {
        overlay.classList.remove('active');
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Number format helper
function number_format(number, decimals = 0) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

// Debounce helper
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// View loan details in modal
function viewLoanDetails(loanId) {
    // Fetch loan details from the server
    fetch(`/loan-offers/${loanId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(loan => {
        if (loan && !loan.error) {
            // Call the populate function from the modal partial
            if (typeof populateLoanDetailsModal === 'function') {
                populateLoanDetailsModal(loan);
            }
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
            modal.show();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load loan details',
                confirmButtonColor: '#003366'
            });
        }
    })
    .catch(error => {
        console.error('Error fetching loan details:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load loan details',
            confirmButtonColor: '#003366'
        });
    });
}

// Alternative: If loan data is already available in the row
function viewLoanDetailsFromData(button) {
    const row = button.closest('tr');
    const loanData = row.dataset.loan ? JSON.parse(row.dataset.loan) : null;
    
    if (loanData) {
        // Call the populate function from the modal partial
        if (typeof populateLoanDetailsModal === 'function') {
            populateLoanDetailsModal(loanData);
        }
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
        modal.show();
    } else {
        // Fallback to fetching from server
        const loanId = row.dataset.id;
        if (loanId) {
            viewLoanDetails(loanId);
        }
    }
}
</script>
@endpush