<style>
/* URASACCOS Brand Colors */
:root {
    --ura-primary: #003366;
    --ura-secondary: #17479E;
    --ura-tertiary: #2E5090;
    --ura-accent: #4A6FA5;
    --ura-light: #E8F0FE;
    --ura-success: #28a745;
    --ura-warning: #FF8C00;
    --ura-danger: #dc3545;
    --ura-grey: #6c757d;
    --primary-gradient: linear-gradient(135deg, #003366 0%, #17479E 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    --grey-gradient: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
}

/* Modern Table Wrapper */
.modern-table-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.08);
    margin-bottom: 30px;
}

/* Modern Table Header Section */
.modern-table-header-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    padding: 20px 25px;
    position: relative;
    overflow: hidden;
}

.modern-table-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #17479E 0%, #2E5090 50%, #17479E 100%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.table-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

/* Table Title Group */
.table-title-group {
    display: flex;
    align-items: center;
    gap: 15px;
}

.table-icon-wrapper {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(23, 71, 158, 0.2);
    position: relative;
    animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.table-icon-wrapper i {
    color: white;
    font-size: 20px;
}

.table-title-text {
    flex: 1;
}

.table-main-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #003366;
    letter-spacing: -0.5px;
}

.table-subtitle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
    font-size: 14px;
}

.record-count {
    display: flex;
    align-items: center;
    gap: 5px;
}

.count-number {
    font-weight: 700;
    color: #17479E;
    font-size: 16px;
}

.count-text {
    color: #6c757d;
}

.separator {
    color: #dee2e6;
}

.filter-status {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6c757d;
}

.filter-active {
    color: #FF8C00;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* Quick Stats */
.table-actions-group {
    display: flex;
    align-items: center;
}

.quick-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 20px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
}

.stat-pending .stat-value {
    color: #FF8C00;
}

.stat-pending {
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
    border-color: rgba(255, 140, 0, 0.2);
}

.stat-approved .stat-value {
    color: #28a745;
}

.stat-approved {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    border-color: rgba(40, 167, 69, 0.2);
}

.stat-amount .stat-value {
    color: #17479E;
}

.stat-amount {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    border-color: rgba(23, 71, 158, 0.2);
}

/* Modern Table Body Wrapper */
.modern-table-body-wrapper {
    position: relative;
    background: white;
}

/* Modern Table Footer - Blue Pagination */
.modern-table-footer {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    border-top: 2px solid rgba(255, 255, 255, 0.1);
    padding: 20px 25px;
    color: white;
}

.modern-table-footer .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.modern-table-footer .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
}

.modern-table-footer .form-select:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    color: white;
}

.modern-table-footer .form-select option {
    background-color: #17479E;
    color: white;
}

.modern-table-footer label,
.modern-table-footer span,
.modern-table-footer small {
    color: rgba(255, 255, 255, 0.9) !important;
}

.modern-table-footer strong {
    color: white !important;
    font-weight: 700;
}

/* Custom Pagination Styles */
.modern-table-footer .pagination {
    margin-bottom: 0;
}

/* Hide only the Laravel pagination text, keep the pagination links */
.modern-table-footer nav p.text-sm.text-gray-700,
.modern-table-footer nav .d-none.flex-sm-fill.d-sm-flex p {
    display: none !important;
}

/* Ensure pagination links are visible */
.modern-table-footer .pagination {
    display: flex !important;
    justify-content: center;
}

.modern-table-footer .page-link {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    margin: 0 2px;
    border-radius: 8px;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.modern-table-footer .page-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modern-table-footer .page-item.active .page-link {
    background-color: white;
    border-color: white;
    color: #17479E;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

.modern-table-footer .page-item.disabled .page-link {
    background-color: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.4);
}

.modern-table-footer .page-link:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    color: white;
}

/* Responsive Design for Table Header */
@media (max-width: 768px) {
    .table-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-icon-wrapper {
        width: 40px;
        height: 40px;
    }
    
    .table-icon-wrapper i {
        font-size: 16px;
    }
    
    .table-main-title {
        font-size: 20px;
    }
    
    .quick-stats {
        width: 100%;
        justify-content: space-between;
    }
    
    .stat-item {
        padding: 8px 12px;
    }
    
    .stat-value {
        font-size: 16px;
    }
}

/* Modern Data Table Styles */
.modern-table-container {
    position: relative;
    overflow-x: auto;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 51, 102, 0.08);
}

.modern-data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

/* Modern Table Header */
.modern-table-header {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table-header tr th {
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding: 16px 12px;
    border: none;
    position: relative;
}

.modern-table-header tr th:first-child {
    border-top-left-radius: 15px;
}

.modern-table-header tr th:last-child {
    border-top-right-radius: 15px;
}

.checkbox-column {
    width: 50px;
    padding: 12px !important;
}

.action-column {
    width: 120px;
}

/* Modern Checkbox */
.modern-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modern-checkbox input[type="checkbox"] {
    display: none;
}

.modern-checkbox label {
    position: relative;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #17479E;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
}

.modern-checkbox label:hover {
    border-color: #003366;
    box-shadow: 0 0 5px rgba(23, 71, 158, 0.2);
}

.modern-checkbox input[type="checkbox"]:checked + label {
    background: #17479E;
    border-color: #17479E;
}

.modern-checkbox input[type="checkbox"]:checked + label::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 14px;
}

/* Header checkbox style */
.modern-table-header .modern-checkbox label {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid white;
}

.modern-table-header .modern-checkbox label:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label {
    background: white;
    border-color: white;
}

.modern-table-header .modern-checkbox input[type="checkbox"]:checked + label::after {
    color: #17479E;
}

/* Sortable Columns */
.sortable-column {
    cursor: pointer;
    transition: background 0.3s ease;
}

.sortable-column:hover {
    background: rgba(255, 255, 255, 0.1);
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-icon {
    opacity: 0.5;
    font-size: 12px;
    transition: opacity 0.3s ease;
}

.sortable-column:hover .sort-icon {
    opacity: 1;
}

/* Modern Table Body */
.modern-table-body {
    background: white;
}

.modern-table-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 51, 102, 0.05);
}

.clickable-row {
    cursor: pointer;
}

.modern-table-row:hover {
    background: linear-gradient(90deg, rgba(23, 71, 158, 0.05) 0%, rgba(23, 71, 158, 0.02) 100%);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 51, 102, 0.1);
}

.modern-table-row td {
    padding: 16px 12px;
    vertical-align: middle;
    border: none;
}

/* Employee Simple Style */
.employee-simple {
    line-height: 1.4;
    padding: 0.25rem 0;
}

.employee-simple .employee-name {
    color: var(--ura-dark);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.employee-simple .employee-id {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Employee Info Cell */
.employee-column {
    min-width: 200px;
}

.employee-info {
    display: flex;
    align-items: center;
}

.employee-details {
    flex: 1;
    min-width: 0;
}

.employee-name {
    font-weight: 600;
    color: #003366;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
    font-size: 14px;
}

.employee-id {
    font-size: 12px;
    color: #6c757d;
}

/* Amount Cells */
.amount-cell {
    line-height: 1.3;
}

.amount-primary {
    font-weight: 600;
    color: #003366;
    font-size: 14px;
}

.amount-secondary {
    font-size: 11px;
    color: #6c757d;
}

.amount-deductible {
    font-weight: 600;
    color: #FF8C00;
    font-size: 14px;
}

.amount-requested {
    font-weight: 700;
    color: #17479E;
    font-size: 15px;
}

.amount-takehome {
    font-weight: 700;
    color: #28a745;
    font-size: 15px;
}

/* Modern Badges */
.modern-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-tenure {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
}

/* Modern Status Badges - Enhanced Pills */
.modern-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
}

.modern-status-badge::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.modern-status-badge:hover::before {
    width: 100px;
    height: 100px;
}

.modern-status-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modern-status-badge i {
    font-size: 10px;
}

/* Approval Status Pills */
.status-approved {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-color: #059669;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-approved:hover {
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}

.status-rejected {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-color: #dc2626;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.status-rejected:hover {
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

.status-cancelled {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    border-color: #475569;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
}

.status-cancelled:hover {
    box-shadow: 0 4px 16px rgba(100, 116, 139, 0.4);
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-color: #d97706;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    animation: pendingPulse 2s ease-in-out infinite;
}

@keyframes pendingPulse {
    0%, 100% { box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3); }
    50% { box-shadow: 0 2px 16px rgba(245, 158, 11, 0.5); }
}

.status-pending:hover {
    animation: none;
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
}

/* Process Status Pills */
.status-processing {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-color: #7c3aed;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    position: relative;
}

.status-processing::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 8px;
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    transform: translateY(-50%);
    animation: processingDot 1.5s ease-in-out infinite;
}

@keyframes processingDot {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

.status-processing:hover {
    box-shadow: 0 4px 16px rgba(139, 92, 246, 0.4);
}

.status-disbursed {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
    border-color: #0891b2;
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
}

.status-disbursed:hover {
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.4);
}

.status-failed {
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    color: white;
    border-color: #ef4444;
    box-shadow: 0 2px 8px rgba(248, 113, 113, 0.3);
}

.status-failed:hover {
    box-shadow: 0 4px 16px rgba(248, 113, 113, 0.4);
}

.status-settled {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    color: white;
    border-color: #111827;
    box-shadow: 0 2px 8px rgba(31, 41, 55, 0.3);
}

.status-settled:hover {
    box-shadow: 0 4px 16px rgba(31, 41, 55, 0.4);
}

.status-new {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.status-new:hover {
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: rgba(0, 51, 102, 0.05);
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.action-btn:hover::before {
    width: 40px;
    height: 40px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
}

.btn-view {
    background: rgba(23, 71, 158, 0.1);
    color: #17479E;
}

.btn-view:hover {
    background: #17479E;
    color: white;
}

.btn-preview {
    background: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
    text-decoration: none;
}

.btn-preview:hover {
    background: #17a2b8;
    color: white;
}

.btn-approve {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.btn-approve:hover {
    background: #28a745;
    color: white;
}

.btn-reject {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-reject:hover {
    background: #dc3545;
    color: white;
}

/* Row Selection */
.modern-table-row.selected {
    background: rgba(23, 71, 158, 0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #6c757d;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.empty-icon i {
    font-size: 48px;
    color: #17479E;
    opacity: 0.5;
}

.empty-state h5 {
    color: #003366;
    margin-bottom: 15px;
    font-weight: 600;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 30px;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

/* Modern Buttons */
.modern-btn {
    padding: 10px 24px;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #17479E 0%, #1e5bb8 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 71, 158, 0.3);
}

.btn-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.btn-secondary:hover {
    background: rgba(108, 117, 125, 0.2);
    transform: translateY(-2px);
}

/* Table Loading Animation */
@keyframes tableRowSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-table-row {
    animation: tableRowSlide 0.5s ease backwards;
}

.modern-table-row:nth-child(1) { animation-delay: 0.05s; }
.modern-table-row:nth-child(2) { animation-delay: 0.1s; }
.modern-table-row:nth-child(3) { animation-delay: 0.15s; }
.modern-table-row:nth-child(4) { animation-delay: 0.2s; }
.modern-table-row:nth-child(5) { animation-delay: 0.25s; }
.modern-table-row:nth-child(6) { animation-delay: 0.3s; }
.modern-table-row:nth-child(7) { animation-delay: 0.35s; }
.modern-table-row:nth-child(8) { animation-delay: 0.4s; }
.modern-table-row:nth-child(9) { animation-delay: 0.45s; }
.modern-table-row:nth-child(10) { animation-delay: 0.5s; }

/* Responsive Design */
@media (max-width: 768px) {
    .modern-data-table {
        font-size: 12px;
    }
    
    .modern-table-header tr th {
        padding: 12px 8px;
        font-size: 10px;
    }
    
    .modern-table-row td {
        padding: 12px 8px;
    }
    
    .employee-name {
        font-size: 13px;
    }
    
    .employee-id {
        font-size: 11px;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}

/* Modern KPI Dashboard with Glassmorphism */
.kpi-dashboard-modern {
    position: relative;
    padding: 20px 0;
    margin: -10px -15px 20px -15px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.03) 0%, rgba(23, 71, 158, 0.03) 100%);
    border-radius: 20px;
}

.kpi-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(0, 51, 102, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 50%, rgba(23, 71, 158, 0.05) 0%, transparent 50%);
    border-radius: 20px;
    z-index: 0;
}

/* Glassmorphism Card */
.kpi-card-modern {
    position: relative;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 20px;
    padding: 0;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    min-height: 200px;
    opacity: 1 !important; /* Ensure cards are always visible */
    visibility: visible !important;
}

.glass-card {
    box-shadow: 
        0 8px 32px 0 rgba(31, 38, 135, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.kpi-card-modern:hover {
    transform: translateY(-8px) scale(1.02);
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 
        0 20px 40px 0 rgba(31, 38, 135, 0.25),
        inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

/* Glow Effect */
.kpi-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 0;
}

.pending-glow {
    background: radial-gradient(circle, rgba(255, 140, 0, 0.2) 0%, transparent 70%);
}

.approved-glow {
    background: radial-gradient(circle, rgba(40, 167, 69, 0.2) 0%, transparent 70%);
}

.rejected-glow {
    background: radial-gradient(circle, rgba(220, 53, 69, 0.2) 0%, transparent 70%);
}

.cancelled-glow {
    background: radial-gradient(circle, rgba(108, 117, 125, 0.2) 0%, transparent 70%);
}

.disbursed-glow {
    background: radial-gradient(circle, rgba(0, 51, 102, 0.2) 0%, transparent 70%);
}

.kpi-card-modern:hover .kpi-glow {
    opacity: 1;
}

/* Content Layout */
.kpi-content-modern {
    position: relative;
    z-index: 1;
    padding: 20px;
}

/* Header Section */
.kpi-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

/* Modern Icon Style */
.kpi-icon-modern {
    position: relative;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    font-size: 20px;
    color: white;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.pending-icon {
    --color-primary: #FF8C00;
    --color-secondary: #FFA500;
}

.approved-icon {
    --color-primary: #28a745;
    --color-secondary: #20c997;
}

.rejected-icon {
    --color-primary: #dc3545;
    --color-secondary: #f56565;
}

.cancelled-icon {
    --color-primary: #6c757d;
    --color-secondary: #868e96;
}

.disbursed-icon {
    --color-primary: #003366;
    --color-secondary: #17479E;
}

/* Pulse Ring Animation */
.icon-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border-radius: 15px;
    border: 2px solid currentColor;
    opacity: 0.3;
    transform: translate(-50%, -50%);
    animation: pulseRing 2s infinite;
}

@keyframes pulseRing {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0;
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
}

/* Badge */
.kpi-badge-modern {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.1) 0%, rgba(23, 71, 158, 0.1) 100%);
    color: var(--ura-primary);
}

.success-badge {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    color: #28a745;
}

.danger-badge {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(245, 101, 101, 0.1) 100%);
    color: #dc3545;
}

.secondary-badge {
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(134, 142, 150, 0.1) 100%);
    color: #6c757d;
}

.primary-badge {
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.1) 0%, rgba(23, 71, 158, 0.1) 100%);
    color: var(--ura-primary);
}

/* Value Section */
.kpi-value-wrapper {
    margin: 20px 0;
}

.kpi-value-modern {
    font-size: 36px;
    font-weight: 800;
    color: var(--ura-primary);
    margin: 0;
    line-height: 1;
    letter-spacing: -1px;
}

.number-animate {
    display: inline-block;
    transition: all 0.3s ease;
}

.kpi-card-modern:hover .number-animate {
    transform: scale(1.1);
}

/* Trend Section */
.kpi-trend-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
}

.trend-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    background: rgba(0, 51, 102, 0.05);
}

.pending-trend {
    background: rgba(255, 140, 0, 0.1);
    color: #FF8C00;
}

.approved-trend {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.rejected-trend {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.cancelled-trend {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.disbursed-trend {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
}

.trend-text {
    font-size: 12px;
    font-weight: 600;
}

/* Mini Chart */
.kpi-chart-modern {
    margin: 15px 0;
    height: 30px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.kpi-card-modern:hover .kpi-chart-modern {
    opacity: 1;
}

/* Progress Bar */
.kpi-footer-modern {
    margin-top: 15px;
}

.progress-bar-modern {
    height: 4px;
    background: rgba(0, 51, 102, 0.05);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 1s ease;
    position: relative;
    overflow: hidden;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.pending-progress {
    background: linear-gradient(90deg, #FF8C00, #FFA500);
}

.approved-progress {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.rejected-progress {
    background: linear-gradient(90deg, #dc3545, #f56565);
}

.cancelled-progress {
    background: linear-gradient(90deg, #6c757d, #868e96);
}

.disbursed-progress {
    background: linear-gradient(90deg, #003366, #17479E);
}

.footer-text {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
}

/* Shine Effect */
.card-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
    transform: rotate(45deg) translateY(-100%);
    transition: transform 0.6s;
    pointer-events: none;
}

.kpi-card-modern:hover .card-shine {
    transform: rotate(45deg) translateY(100%);
}

/* Entrance Animation - 3D Rotate In */
@keyframes rotateIn3D {
    0% {
        opacity: 0;
        transform: perspective(1200px) rotateY(-90deg) translateZ(100px) scale(0.8);
    }
    40% {
        opacity: 1;
        transform: perspective(1200px) rotateY(10deg) translateZ(0) scale(1.05);
    }
    70% {
        transform: perspective(1200px) rotateY(-5deg) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: perspective(1200px) rotateY(0deg) scale(1);
    }
}

/* Alternative: Flip and Zoom Entrance */
@keyframes flipZoomIn {
    0% {
        opacity: 0;
        transform: perspective(1000px) rotateX(-180deg) scale(0.5);
    }
    50% {
        opacity: 1;
        transform: perspective(1000px) rotateX(-90deg) scale(0.8);
    }
    75% {
        transform: perspective(1000px) rotateX(10deg) scale(1.05);
    }
    100% {
        transform: perspective(1000px) rotateX(0deg) scale(1);
    }
}

/* Cube Rotate Entrance - Enhanced */
@keyframes cubeRotateIn {
    0% {
        opacity: 0;
        transform: perspective(1000px) rotateX(-90deg) rotateY(-90deg) translateZ(150px) scale(0.5);
    }
    25% {
        opacity: 0.5;
        transform: perspective(1000px) rotateX(-45deg) rotateY(-45deg) translateZ(75px) scale(0.7);
    }
    50% {
        opacity: 1;
        transform: perspective(1000px) rotateX(10deg) rotateY(10deg) translateZ(0) scale(1.05);
    }
    75% {
        transform: perspective(1000px) rotateX(-5deg) rotateY(-5deg) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: perspective(1000px) rotateX(0) rotateY(0) translateZ(0) scale(1);
    }
}

/* Subtle Breathing Effect - After entrance */
@keyframes gentleBreath {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.01);
    }
}

/* Apply Cube Rotate entrance with stagger - Option 2 */
.animate-float-1 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0s;
}

.animate-float-2 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.15s;
}

.animate-float-3 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.3s;
}

.animate-float-4 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.45s;
}

.animate-float-5 {
    transform-style: preserve-3d;
    animation: cubeRotateIn 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
    animation-delay: 0.6s;
}

/* Remove conflicting animation from base card */
.kpi-card-modern {
    /* Removed the gentleBreath animation to prevent conflicts */
    transform-style: preserve-3d;
    backface-visibility: hidden;
}

/* Add breathing effect only after entrance via JavaScript or separate class */
.kpi-card-modern.entrance-complete {
    animation: gentleBreath 4s ease-in-out infinite;
}

/* Alternative: Flip In Animation */
@keyframes flipInX {
    0% {
        transform: perspective(800px) rotateX(-90deg);
        opacity: 0;
    }
    40% {
        transform: perspective(800px) rotateX(20deg);
    }
    70% {
        transform: perspective(800px) rotateX(-10deg);
    }
    100% {
        transform: perspective(800px) rotateX(0deg);
        opacity: 1;
    }
}

/* Alternative: Zoom In Bounce */
@keyframes zoomInBounce {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.95);
    }
    100% {
        transform: scale(1);
    }
}

/* You can switch to these by changing the class names:
.animate-flip-1 { animation: flipInX 0.8s ease-out; animation-delay: 0s; }
.animate-flip-2 { animation: flipInX 0.8s ease-out; animation-delay: 0.1s; }
.animate-flip-3 { animation: flipInX 0.8s ease-out; animation-delay: 0.2s; }
.animate-flip-4 { animation: flipInX 0.8s ease-out; animation-delay: 0.3s; }
.animate-flip-5 { animation: flipInX 0.8s ease-out; animation-delay: 0.4s; }

.animate-zoom-1 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0s; }
.animate-zoom-2 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.1s; }
.animate-zoom-3 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.2s; }
.animate-zoom-4 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.3s; }
.animate-zoom-5 { animation: zoomInBounce 0.6s ease-out; animation-delay: 0.4s; }
*/

/* Old KPI Dashboard - keeping for fallback */
.kpi-dashboard {
    padding: 0;
}

/* New URASACCOS Branded KPI Cards with Animation */
.kpi-card-ura {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 51, 102, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 51, 102, 0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.kpi-card-ura::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.4s ease;
    transform-origin: left;
}

.kpi-card-ura:hover::before {
    transform: scaleX(1);
}

.kpi-card-ura:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 28px rgba(0, 51, 102, 0.18);
    border-color: var(--ura-secondary);
}

.kpi-card-body {
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.kpi-icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.kpi-icon-circle::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at top left, rgba(255,255,255,0.2), transparent);
}

/* Icon Circle Brand Colors */
.kpi-icon-circle.ura-pending {
    background: var(--warning-gradient);
    color: white;
}

.kpi-icon-circle.ura-approved {
    background: var(--success-gradient);
    color: white;
}

.kpi-icon-circle.ura-rejected {
    background: var(--danger-gradient);
    color: white;
}

.kpi-icon-circle.ura-cancelled {
    background: var(--grey-gradient);
    color: white;
}

.kpi-icon-circle.ura-disbursed {
    background: var(--primary-gradient);
    color: white;
}

.kpi-details {
    flex: 1;
    min-width: 0;
}

.kpi-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--ura-primary);
    margin: 0;
    line-height: 1;
    letter-spacing: -0.5px;
}

.kpi-title {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    margin: 4px 0 2px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-subtitle {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.kpi-period {
    font-size: 11px;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.kpi-weekly {
    font-size: 11px;
    font-weight: 600;
}

.kpi-footer {
    padding: 8px 16px;
    font-size: 11px;
    font-weight: 600;
    color: white;
    text-align: center;
    letter-spacing: 0.3px;
}

/* Footer Background Colors */
.kpi-footer.ura-pending-bg {
    background: linear-gradient(90deg, #FF8C00 0%, #FFA500 100%);
}

.kpi-footer.ura-approved-bg {
    background: linear-gradient(90deg, #20c997 0%, #28a745 100%);
}

.kpi-footer.ura-rejected-bg {
    background: linear-gradient(90deg, #c82333 0%, #dc3545 100%);
}

.kpi-footer.ura-cancelled-bg {
    background: linear-gradient(90deg, #5a6268 0%, #6c757d 100%);
}

.kpi-footer.ura-disbursed-bg {
    background: linear-gradient(90deg, #003366 0%, #17479E 100%);
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    .kpi-number {
        font-size: 24px;
    }
    .kpi-title {
        font-size: 13px;
    }
    .kpi-icon-circle {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }
}

@media (max-width: 1200px) {
    .kpi-card-body {
        padding: 14px;
    }
    .kpi-number {
        font-size: 22px;
    }
    .kpi-footer {
        padding: 6px 12px;
        font-size: 10px;
    }
}

@media (max-width: 768px) {
    .kpi-dashboard .col {
        min-width: 100%;
        margin-bottom: 10px;
    }
}

/* URASACCOS Filter Card */
.filter-card-ura {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0, 51, 102, 0.08);
    border: 1px solid rgba(0, 51, 102, 0.08);
}

.filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 20px 24px;
    border-bottom: 2px solid var(--ura-primary);
}

.filter-title {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-icon {
    width: 32px;
    height: 32px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.filter-subtitle {
    color: #6c757d;
    font-size: 13px;
    margin-left: 42px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-ura-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 6px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-light {
    background: white;
    color: var(--ura-primary);
    border: 1px solid rgba(0, 51, 102, 0.2);
    padding: 6px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-light:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
}

.filter-body {
    padding: 24px;
}

/* Filter Input Styles */
.filter-input-group {
    position: relative;
}

.filter-input {
    padding-left: 40px;
    padding-right: 40px;
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

.filter-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    z-index: 1;
}

.filter-clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 4px;
    font-size: 14px;
}

.filter-clear-btn:hover {
    color: var(--ura-danger);
}

/* Filter Select */
.filter-select {
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    padding: 0 12px;
    transition: all 0.3s ease;
    background-color: white;
}

.filter-select:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

/* Date Filter */
.filter-date-group {
    position: relative;
}

.filter-date {
    height: 44px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    padding: 0 12px;
    transition: all 0.3s ease;
}

.filter-date:focus {
    border-color: var(--ura-secondary);
    box-shadow: 0 0 0 3px rgba(23, 71, 158, 0.1);
}

.filter-date-label {
    position: absolute;
    top: -10px;
    left: 12px;
    background: white;
    padding: 0 6px;
    font-size: 11px;
    color: var(--ura-primary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Apply Button */
.btn-filter-apply {
    width: 100%;
    height: 44px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter-apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 51, 102, 0.3);
    color: white;
}

/* Advanced Filter Container */
.advanced-filter-container {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    margin-top: 15px;
    border: 1px solid rgba(0, 51, 102, 0.08);
}

/* Collapse transition */
#advancedFilters {
    transition: all 0.35s ease;
}

#advancedFilters.collapsing {
    transition: height 0.35s ease;
}

/* Filter Pills - No border when at top */
.filter-pills {
    padding-bottom: 0;
    margin-bottom: 0;
}

/* Filter Pills when advanced is open */
.filter-pills.with-advanced {
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 15px;
}

.filter-pills-label {
    font-size: 13px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-right: 12px;
}

.filter-pills-group {
    display: inline-flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-pill {
    background: white;
    border: 2px solid #e9ecef;
    color: #495057;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    position: relative;
    overflow: hidden;
}

.filter-pill::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(0, 51, 102, 0.1);
    transition: width 0.6s ease, height 0.6s ease;
    transform: translate(-50%, -50%);
}

.filter-pill:hover::before {
    width: 100px;
    height: 100px;
}

.filter-pill:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
    color: var(--ura-primary);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
}

.filter-pill.active {
    background: var(--primary-gradient);
    color: white;
    border-color: var(--ura-primary);
}

.filter-pill-count {
    background: rgba(0, 51, 102, 0.1);
    color: var(--ura-primary);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 4px;
}

.filter-pill.active .filter-pill-count {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

/* Special colored pills */
.filter-pill-pending {
    border-color: var(--ura-warning);
    background: linear-gradient(135deg, rgba(255, 140, 0, 0.05) 0%, rgba(255, 165, 0, 0.05) 100%);
}

.filter-pill-pending:hover {
    background: var(--warning-gradient);
    color: white;
    border-color: var(--ura-warning);
}

.filter-pill-approved {
    border-color: var(--ura-success);
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
}

.filter-pill-approved:hover {
    background: var(--success-gradient);
    color: white;
    border-color: var(--ura-success);
}

.filter-pill-disbursed {
    border-color: var(--ura-primary);
    background: linear-gradient(135deg, rgba(0, 51, 102, 0.05) 0%, rgba(23, 71, 158, 0.05) 100%);
}

.filter-pill-disbursed:hover {
    background: var(--primary-gradient);
    color: white;
    border-color: var(--ura-primary);
}

/* Active Filters */
.active-filters {
    padding: 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.active-filters-label {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.active-filter-tag {
    background: white;
    border: 1px solid var(--ura-secondary);
    color: var(--ura-primary);
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-tag-remove {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.2s ease;
}

.filter-tag-remove:hover {
    color: var(--ura-danger);
}

/* Page Background */
.bg-gradient {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    min-height: 100vh;
}

/* Compact Page Header */
.page-header-compact {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 51, 102, 0.05);
    transition: all 0.3s ease;
}

.page-header-compact:hover {
    box-shadow: 0 4px 16px rgba(0, 51, 102, 0.08);
}

.icon-box-compact {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    transition: all 0.3s ease;
}

.icon-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.page-title-compact {
    color: var(--ura-primary);
    font-weight: 700;
    font-size: 1.1rem;
    letter-spacing: -0.3px;
}

.btn-ura-primary-sm {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ura-primary-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-light-sm {
    background: white;
    color: var(--ura-primary);
    border: 1px solid rgba(0, 51, 102, 0.15);
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.3s ease;
}

.btn-ura-light-sm:hover {
    background: var(--ura-light);
    border-color: var(--ura-secondary);
}

/* Animations */
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

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes dropdownSlide {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-fade-in-delayed {
    animation: fadeIn 0.8s ease-out;
    animation-delay: 0.2s;
    animation-fill-mode: both;
}

.animate-dropdown {
    animation: dropdownSlide 0.3s ease-out;
}

.animate-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-hover:hover {
    transform: translateY(-2px) scale(1.02);
}

/* Stagger Animations for KPI Cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-stagger-1 {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.animate-stagger-2 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.1s;
    animation-fill-mode: both;
}

.animate-stagger-3 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.2s;
    animation-fill-mode: both;
}

.animate-stagger-4 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.3s;
    animation-fill-mode: both;
}

.animate-stagger-5 {
    animation: fadeInUp 0.6s ease-out;
    animation-delay: 0.4s;
    animation-fill-mode: both;
}

/* Breadcrumb styling */
.breadcrumb {
    font-size: 13px;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: var(--ura-secondary);
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--ura-primary);
}

.breadcrumb-item.active {
    color: var(--ura-primary);
}

/* Old Page Header - keeping for reference */
.page-header-wrapper {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
}

.page-title {
    font-size: 1.875rem;
    font-weight: 800;
    background: linear-gradient(135deg, #17479E 0%, #2563c7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.gradient-primary {
    background: var(--primary-gradient);
}

.gradient-success {
    background: var(--success-gradient);
}

/* Stat Cards */
.stat-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 2px solid rgba(23, 71, 158, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(23, 71, 158, 0.08);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    right: -100%;
    height: 4px;
    background: linear-gradient(90deg,
        transparent,
        #17479E 40%,
        #2563c7 50%,
        #17479E 60%,
        transparent);
    animation: slideAcross 3s ease-in-out infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

@keyframes slideAcross {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center,
        rgba(23, 71, 158, 0.03) 0%,
        transparent 70%);
    animation: rotateGradient 20s linear infinite;
}

@keyframes rotateGradient {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card:hover {
    border-color: rgba(23, 71, 158, 0.2);
}

.shadow-hover:hover {
    transform: translateY(-12px) scale(1.02) rotate(0.5deg);
    box-shadow: 0 20px 50px rgba(23, 71, 158, 0.25);
}

.stat-icon-wrapper {
    position: relative;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.bg-gradient-warning {
    background: var(--warning-gradient);
}

.bg-gradient-success {
    background: var(--success-gradient);
}

.bg-gradient-info {
    background: var(--info-gradient);
}

.bg-gradient-primary {
    background: var(--primary-gradient);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1d23;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.progress-sm {
    height: 4px;
    border-radius: 2px;
    background: #f0f2f5;
}

/* Enhanced Search Box */
.search-box {
    position: relative;
}

.search-box input {
    padding-left: 3rem;
    border-radius: 12px;
    font-size: 1rem;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Quick Filters */
.quick-filters button {
    border-radius: 20px;
    padding: 0.25rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.quick-filters button:hover {
    transform: translateY(-2px);
}

/* Enhanced Button Styles */
.btn-gradient-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Table Enhancements */
.table-hover tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 3px solid transparent;
}

.table-hover tbody tr:hover {
    background-color: rgba(23, 71, 158, 0.05);
    border-left: 3px solid #17479E;
    transform: translateX(2px);
}

/* Row selection styling */
.table-hover tbody tr.table-active {
    background-color: rgba(23, 71, 158, 0.1);
    border-left: 3px solid #17479E;
}

.sortable {
    cursor: pointer;
    user-select: none;
}

.sortable:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Avatar Styles */
.avatar-wrapper {
    position: relative;
}

.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.avatar-circle.avatar-sm {
    width: 28px;
    height: 28px;
}

.avatar-circle.avatar-sm .avatar-text {
    font-size: 0.7rem;
}

.avatar-text {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.avatar-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

/* Badge Enhancements */
.badge {
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.badge.rounded-pill {
    border-radius: 20px;
}

/* Status Indicators */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}

/* Empty State */
.empty-state {
    padding: 3rem;
}

.empty-icon {
    opacity: 0.5;
}

/* Dropdown Menu */
.dropdown-menu {
    border: none;
    border-radius: 12px;
    padding: 0.5rem;
    min-width: 200px;
    margin-top: 0.5rem !important;
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.dropdown-divider {
    margin: 0.5rem;
}

/* Fix dropdown overlap */
.table td:last-child {
    position: relative;
    overflow: visible;
}

.dropdown .dropdown-menu {
    position: absolute;
    inset: 0px auto auto 0px;
    margin: 0px;
    transform: translate3d(0px, 38px, 0px);
}

.dropdown-menu.dropdown-menu-end {
    right: 0;
    left: auto;
}

/* Remove dropdown arrow if not needed */
.dropdown-toggle::after {
    display: none;
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #e0e6ed;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animations */
@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.loan-row {
    animation: slideInUp 0.5s ease forwards;
}

.loan-row:nth-child(1) { animation-delay: 0.05s; }
.loan-row:nth-child(2) { animation-delay: 0.1s; }
.loan-row:nth-child(3) { animation-delay: 0.15s; }
.loan-row:nth-child(4) { animation-delay: 0.2s; }
.loan-row:nth-child(5) { animation-delay: 0.25s; }

/* Modern Interactive Elements */
.btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

/* Modern Table Design */
.table-container {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(23, 71, 158, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(23, 71, 158, 0.08);
}

.table {
    margin-bottom: 0;
}

/* Compact table for better space utilization */
.table td {
    padding: 0.4rem 0.5rem;
    vertical-align: middle;
    font-size: 0.813rem;
}

/* Alternating row colors with URA brand */
.table tbody tr:nth-child(even) {
    background-color: rgba(23, 71, 158, 0.02);
}

.table th {
    padding: 0.5rem;
    font-size: 0.813rem;
    font-weight: 600;
}

/* Smaller text for secondary information */
.table small {
    font-size: 0.75rem;
}

/* Compact badges */
.table .badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}

/* Compact dropdown */
.dropdown-menu {
    font-size: 0.813rem;
    min-width: 120px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 9999 !important;
    background-color: white;
}

.dropdown-item {
    padding: 0.4rem 1rem;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: rgba(23, 71, 158, 0.1);
}

.dropdown-item i {
    font-size: 0.75rem;
    width: 16px;
}

/* Fix dropdown positioning */
.table-responsive {
    min-height: 300px;
}

/* Ensure dropdown menu appears on top */
.dropdown-menu {
    z-index: 99999 !important;
    background-color: white !important;
    border: 1px solid rgba(0,0,0,0.15) !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
}

/* Process Loan Action Cards */
.action-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #17479E !important;
}

.action-card.border-primary {
    border-color: #17479E !important;
    background-color: rgba(23, 71, 158, 0.05) !important;
}

.action-icon {
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Action button hover effects */
.btn-sm:hover {
    transform: scale(1.2);
    transition: all 0.2s ease;
}

.btn-sm:hover i.fa-eye {
    color: #0056b3 !important;
}

.btn-sm:hover i.fa-edit {
    color: #545b62 !important;
}

.btn-sm:hover i.fa-check {
    color: #218838 !important;
}

.btn-sm:hover i.fa-times {
    color: #c82333 !important;
}

.table th {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
    color: #17479E;
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(23, 71, 158, 0.05);
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: linear-gradient(90deg,
        rgba(23, 71, 158, 0.02) 0%,
        rgba(23, 71, 158, 0.05) 50%,
        rgba(23, 71, 158, 0.02) 100%);
}

/* Status Badges with Modern Style */
.badge {
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
}

.badge::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent);
    animation: badgeShimmer 3s ease-in-out infinite;
}

@keyframes badgeShimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(300%); }
}

/* Action Buttons with Micro-interactions */
.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid transparent;
    position: relative;
    margin: 0 2px;
}

.btn-action:hover {
    transform: translateY(-3px) scale(1.1);
    border-color: currentColor;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.btn-action::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 10px;
    background: linear-gradient(45deg, currentColor, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.btn-action:hover::after {
    opacity: 0.2;
}

/* URASACCOS Brand Enhancements */
.text-primary {
    color: #17479E !important;
}

.bg-primary {
    background: #17479E !important;
}

.border-primary {
    border-color: #17479E !important;
}

/* Enhanced Table Headers with URASACCOS Style */
.table thead th {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(23, 71, 158, 0.3);
}

/* Status Colors with URASACCOS Theme */
.status-approved {
    color: #1e8449;
    background: rgba(30, 132, 73, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
}

.status-pending {
    color: #f39c12;
    background: rgba(243, 156, 18, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
}

/* Dropdown Menu URASACCOS Style */
.dropdown-menu {
    border: 1px solid rgba(23, 71, 158, 0.1);
    box-shadow: 0 5px 20px rgba(23, 71, 158, 0.15);
}

.dropdown-item:hover {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(23, 71, 158, 0.05) 100%);
    color: #17479E;
}

/* Pagination URASACCOS Style */
.pagination .page-link {
    color: #17479E;
    border-color: rgba(23, 71, 158, 0.2);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #17479E 0%, #2563c7 100%);
    border-color: #17479E;
}

/* Add URASACCOS branding watermark */
.page-header-wrapper::before {
    content: 'URASACCOS';
    position: absolute;
    top: 50%;
    right: 2rem;
    transform: translateY(-50%);
    font-size: 3rem;
    font-weight: 900;
    color: rgba(23, 71, 158, 0.05);
    letter-spacing: 3px;
    pointer-events: none;
}

/* Advanced Glassmorphism Effects */
.glass-effect {
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.1) 0%,
        rgba(255, 255, 255, 0.05) 100%
    );
    backdrop-filter: blur(10px) saturate(150%);
    -webkit-backdrop-filter: blur(10px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow:
        0 8px 32px 0 rgba(31, 38, 135, 0.37),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

/* Neon Glow Effects */
.neon-glow {
    animation: neonPulse 2s ease-in-out infinite;
}

@keyframes neonPulse {
    0%, 100% {
        text-shadow:
            0 0 5px var(--ura-primary),
            0 0 10px var(--ura-primary),
            0 0 15px var(--ura-primary),
            0 0 20px var(--ura-primary-light);
    }
    50% {
        text-shadow:
            0 0 10px var(--ura-primary),
            0 0 20px var(--ura-primary),
            0 0 30px var(--ura-primary),
            0 0 40px var(--ura-primary-light);
    }
}

/* 3D Card Tilt Effect */
.tilt-card {
    transform-style: preserve-3d;
    transition: transform 0.6s;
}

.tilt-card:hover {
    transform:
        perspective(1000px)
        rotateX(10deg)
        rotateY(-10deg)
        scale(1.05);
}

/* Liquid Button Effect */
.liquid-btn {
    position: relative;
    padding: 20px 40px;
    display: block;
    text-decoration: none;
    overflow: hidden;
    transition: all 0.3s;
}

.liquid-btn span {
    position: relative;
    z-index: 1;
}

.liquid-btn::before {
    content: '';
    position: absolute;
    top: var(--y, 50%);
    left: var(--x, 50%);
    width: 0;
    height: 0;
    border-radius: 50%;
    background: var(--ura-primary-light);
    transition: width 0.5s, height 0.5s;
    transform: translate(-50%, -50%);
}

.liquid-btn:hover::before {
    width: 400px;
    height: 400px;
}

/* Skeleton Loading Animation */
.skeleton {
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: skeletonLoading 1.5s infinite;
}

@keyframes skeletonLoading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Morphing Search Bar */
.search-morphing {
    position: relative;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.search-morphing:focus-within {
    transform: scale(1.05);
    box-shadow:
        0 10px 40px rgba(23, 71, 158, 0.2),
        inset 0 0 0 2px var(--ura-primary);
}

/* Gradient Text Animation */
.gradient-text-animated {
    background: linear-gradient(
        270deg,
        var(--ura-primary),
        var(--ura-primary-light),
        var(--ura-secondary),
        var(--ura-accent)
    );
    background-size: 400% 400%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Floating Action Button */
.fab {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--ura-gradient);
    box-shadow:
        0 10px 30px rgba(23, 71, 158, 0.3),
        0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 1000;
}

.fab:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow:
        0 15px 40px rgba(23, 71, 158, 0.4),
        0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Ripple Effect */
.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ripple:active::before {
    width: 300px;
    height: 300px;
}

/* Quick Actions Menu */
.quick-actions-menu {
    position: fixed;
    bottom: 5rem;
    right: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    opacity: 0;
    pointer-events: none;
    transform: scale(0.8) translateY(20px);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 999;
}

.quick-actions-menu.show {
    opacity: 1;
    pointer-events: all;
    transform: scale(1) translateY(0);
}

.quick-action-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    color: var(--ura-primary);
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.quick-action-btn:hover {
    background: var(--ura-gradient);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
}

.quick-action-btn[data-tooltip]::before {
    content: attr(data-tooltip);
    position: absolute;
    right: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}

.quick-action-btn:hover[data-tooltip]::before {
    opacity: 1;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-overlay.show {
    display: flex;
}

.loading-spinner {
    text-align: center;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3rem;
}

/* Toast Notifications */
.toast-notification {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideInRight 0.3s ease;
    max-width: 350px;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast-notification.success {
    border-left: 4px solid var(--ura-secondary);
}

.toast-notification.error {
    border-left: 4px solid #e74c3c;
}

.toast-notification.info {
    border-left: 4px solid var(--ura-primary);
}

/* Simplified Table Styling */
.avatar-initials {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.bg-gradient-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.btn-ura-primary {
    background: var(--ura-primary);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-ura-primary:hover {
    background: var(--ura-primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 71, 158, 0.3);
}

/* Enhanced Modal Styling with URASACCOS Branding */
.modal-ura-enhanced {
    border-radius: 20px;
    overflow: hidden;
    background: white;
}

.shadow-2xl {
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(23, 71, 158, 0.05);
}

/* Modal Pattern Background */
.modal-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 200px;
    background:
        repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(23, 71, 158, 0.01) 10px,
            rgba(23, 71, 158, 0.01) 20px
        );
    pointer-events: none;
    z-index: 0;
}

/* Modal Header Styling */
.modal-header-ura {
    background: var(--ura-gradient);
    padding: 2rem;
    position: relative;
}

.header-decoration {
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    animation: floatBubble 20s infinite ease-in-out;
}

@keyframes floatBubble {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

.modal-icon-wrapper {
    position: relative;
}

.modal-icon-circle {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    backdrop-filter: blur(10px);
    animation: pulseIcon 2s infinite;
}

@keyframes pulseIcon {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
    }
    50% {
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
    }
}

/* Enhanced Tab Navigation */
.tab-navigation-wrapper {
    background: linear-gradient(to bottom, #f8f9fa, white);
    padding: 0.5rem;
    border-bottom: 1px solid rgba(23, 71, 158, 0.1);
}

.nav-tabs-ura {
    gap: 0.5rem;
}

.nav-link-ura {
    position: relative;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
    background: transparent;
    color: #6c757d;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.nav-link-ura:hover {
    background: rgba(23, 71, 158, 0.05);
    color: var(--ura-primary);
    transform: translateY(-2px);
}

.nav-link-ura.active {
    background: white;
    color: var(--ura-primary);
    box-shadow:
        0 -2px 10px rgba(23, 71, 158, 0.1),
        0 2px 4px rgba(0, 0, 0, 0.05);
}

.tab-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.1), rgba(23, 71, 158, .05));
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.nav-link-ura.active .tab-icon-box {
    background: var(--ura-gradient);
    color: white;
    animation: rotateIcon 0.5s ease;
}

@keyframes rotateIcon {
    from { transform: rotate(0deg) scale(0.8); }
    to { transform: rotate(360deg) scale(1); }
}

.tab-label {
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.tab-indicator {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 50px;
    height: 3px;
    background: var(--ura-gradient);
    border-radius: 3px 3px 0 0;
    transition: transform 0.3s ease;
}

.nav-link-ura.active .tab-indicator {
    transform: translateX(-50%) scaleX(1);
}

/* Enhanced Info Cards */
.info-card-enhanced {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(23, 71, 158, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.info-card-enhanced:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 24px rgba(23, 71, 158, 0.12),
        0 4px 8px rgba(0, 0, 0, 0.05);
    border-color: var(--ura-primary);
}

.info-card-header {
    background: linear-gradient(135deg, rgba(23, 71, 158, 0.05), rgba(23, 71, 158, 0.02));
    padding: 1rem;
    border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-card-icon {
    width: 35px;
    height: 35px;
    background: var(--ura-gradient);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.info-card-title {
    margin: 0;
    color: var(--ura-primary);
    font-weight: 600;
    font-size: 1rem;
}

.info-card-body {
    padding: 1rem;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px dashed rgba(23, 71, 158, 0.1);
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    transition: all 0.2s ease;
}

.info-item:hover {
    padding-left: 0.5rem;
    background: rgba(23, 71, 158, 0.02);
    margin: 0 -0.5rem;
    padding-right: 0.5rem;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item small {
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.info-item div {
    color: #2c3e50;
    font-weight: 500;
}

/* Enhanced Modal Footer */
.modal-footer-ura {
    background: linear-gradient(to right, #f8f9fa, white);
    padding: 1.5rem;
    border-top: 2px solid rgba(23, 71, 158, 0.1);
}

.btn-ura-gradient {
    background: var(--ura-gradient);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-ura-gradient::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-ura-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(23, 71, 158, 0.3);
    color: white;
}

.btn-ura-gradient:hover::before {
    width: 300px;
    height: 300px;
}

/* Loading States */
.modal-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.modal-loading.show {
    opacity: 1;
    pointer-events: all;
}

/* Badge Enhancements */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Tab Content Animation */
.tab-pane {
    animation: fadeInUp 0.4s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-ura-gradient {
    background: var(--ura-gradient);
    color: white;
}

/* Scrollbar Styling for Modal */
.modal-dialog-scrollable .modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
    background: rgba(23, 71, 158, 0.05);
    border-radius: 4px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
    background: rgba(23, 71, 158, 0.3);
    border-radius: 4px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--ura-primary);
}

.bg-ura-gradient th {
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem;
}

/* Glitch Effect */
.glitch {
    position: relative;
    color: var(--ura-primary);
    font-weight: bold;
}

.glitch::before,
.glitch::after {
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.glitch::before {
    animation: glitch-1 0.3s infinite;
    color: var(--ura-accent);
    z-index: -1;
}

.glitch::after {
    animation: glitch-2 0.3s infinite;
    color: var(--ura-secondary);
    z-index: -2;
}

@keyframes glitch-1 {
    0%, 100% {
        clip: rect(0, 900px, 0, 0);
        transform: skew(0deg);
    }
    20% {
        clip: rect(20px, 900px, 30px, 0);
        transform: skew(0.5deg);
    }
}

@keyframes glitch-2 {
    0%, 100% {
        clip: rect(0, 900px, 0, 0);
        transform: skew(0deg);
    }
    50% {
        clip: rect(50px, 900px, 60px, 0);
        transform: skew(-0.5deg);
    }
}
</style>
