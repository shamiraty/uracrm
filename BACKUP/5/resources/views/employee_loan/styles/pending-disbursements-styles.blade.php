@push('styles')
<style>
/* Pending Disbursements Page Styles */
.pending-disbursements-container {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* Modern Header Section */
.page-header-modern {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.page-header-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: -200px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float-slow 20s ease-in-out infinite;
}

@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.header-content {
    position: relative;
    z-index: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-header {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* KPI Dashboard */
.kpi-dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.kpi-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.kpi-card.urgent {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
    border-color: #ff4444;
}

.kpi-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.kpi-icon.primary {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
}

.kpi-icon.success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.kpi-icon.warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: white;
}

.kpi-icon.danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.kpi-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0.5rem;
}

.kpi-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-trend {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.kpi-trend.up {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.kpi-trend.down {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Data Table */
.table-container {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.table-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a2e;
}

.table-filters {
    display: flex;
    gap: 1rem;
}

.filter-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.filter-badge.active {
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    color: white;
}

.filter-badge:not(.active) {
    background: #f0f0f0;
    color: #6c757d;
}

.filter-badge:not(.active):hover {
    background: #e0e0e0;
}

/* Modern Table Styling */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table thead th {
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6c757d;
    border: none;
}

.modern-table tbody tr {
    transition: all 0.3s;
    border-bottom: 1px solid #f0f0f0;
}

.modern-table tbody tr:hover {
    background: rgba(23, 71, 158, 0.05);
    transform: scale(1.01);
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.pending {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.2) 100%);
    color: #ff8c00;
    border: 1px solid #ffc107;
}

.status-badge.urgent {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.2) 100%);
    color: #dc3545;
    border: 1px solid #dc3545;
    animation: pulse-urgent 2s infinite;
}

@keyframes pulse-urgent {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.5rem;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: #6c757d;
    transition: all 0.3s;
    cursor: pointer;
}

.btn-action:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.btn-action.view {
    color: #17a2b8;
}

.btn-action.approve {
    color: #28a745;
}

.btn-action.reject {
    color: #dc3545;
}

/* Responsive Design */
@media (max-width: 768px) {
    .kpi-dashboard {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .table-filters {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-badge {
        width: 100%;
        text-align: center;
    }
    
    .modern-table {
        font-size: 0.875rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}

/* Loading States */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid #f0f0f0;
    border-top-color: #17479E;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #6c757d;
}
</style>
@endpush