@extends('layouts.app')

@section('content')
<style>
/* Simple Table Styles */
#pendingDisbursementsTable {
    font-size: 0.9rem;
}

#pendingDisbursementsTable thead {
    background-color: #003366 !important;
}

#pendingDisbursementsTable thead th {
    color: white !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 12px 8px;
    border: none;
}

#pendingDisbursementsTable tbody td {
    padding: 10px 8px;
    vertical-align: middle;
}

#pendingDisbursementsTable .btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.table-warning {
    background-color: #fff3cd !important;
}

/* Container styles */
.container-fluid {
    max-width: 100%;
    padding: 15px;
}
/* URASACCOS Brand Colors - Enhanced */
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
    --ura-gold: #FFD700;
    --ura-orange: #FF8C00;
}

/* Modern Background Pattern */
.page-background {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    z-index: -1;
}

.page-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(0, 51, 102, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(23, 71, 158, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(33, 150, 243, 0.03) 0%, transparent 50%);
}

/* Remove infinite animations */
.particle-container,
.blob-container {
    display: none !important;
}

/* Page Header Section */
.page-header-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.08);
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--ura-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 5px;
}

/* Modern KPI Dashboard - Enhanced */
.kpi-dashboard-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* KPI Cards Container */
.kpi-cards-wrapper {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

@media (max-width: 768px) {
    .kpi-cards-wrapper {
        flex-direction: column;
    }
}

.kpi-card-modern {
    flex: 1;
    min-width: 200px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.06);
    display: flex;
    align-items: center;
    gap: 16px;
}

.kpi-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.12);
}

.kpi-content {
    flex: 1;
}

.kpi-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #6c757d;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.kpi-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--ura-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.kpi-subtitle {
    font-size: 0.8rem;
    color: #6c757d;
}

.kpi-card-disbursement::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: var(--ura-gradient);
    border-radius: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.kpi-card-disbursement:hover,
.kpi-card-modern:hover,
.kpi-card-compact:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
        0 20px 40px rgba(0, 51, 102, 0.15),
        0 10px 20px rgba(0, 51, 102, 0.1);
}

.kpi-card-disbursement:hover::before {
    opacity: 0.1;
}

.kpi-icon-modern,
.kpi-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 12px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.kpi-icon-wrapper::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 14px;
    background: inherit;
    filter: blur(8px);
    opacity: 0.4;
    z-index: -1;
}

.pending-icon,
.kpi-icon-wrapper.primary {
    background: var(--ura-gradient);
    color: white;
}

.amount-icon,
.kpi-icon-wrapper.success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.kpi-icon-wrapper.warning,
.today-icon {
    background: linear-gradient(135deg, #FFA726, #FFB74D);
    color: white;
}

.kpi-icon-wrapper.info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.urgent-icon,
.kpi-icon-wrapper.danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.kpi-value-modern,
.kpi-value {
    font-size: 1.875rem;
    font-weight: 800;
    background: var(--ura-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    line-height: 1;
    letter-spacing: -0.5px;
}

.kpi-label-modern,
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

/* Page Header - Modern Design */
.page-header-compact {
    background: var(--ura-gradient);
    color: white;
    padding: 2rem;
    border-radius: 25px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(0, 51, 102, 0.2);
    position: relative;
    overflow: hidden;
}

.page-header-compact::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 40%;
    height: 200%;
    background: rgba(255, 255, 255, 0.05);
    transform: rotate(35deg);
}

.page-header-compact::after {
    content: 'URASACCOS';
    position: absolute;
    bottom: 10px;
    right: 20px;
    font-size: 4rem;
    font-weight: 900;
    opacity: 0.03;
    letter-spacing: -2px;
}

.page-title-compact {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: -0.5px;
}

.icon-box-compact {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.icon-pulse {
    animation: pulse-soft 2s ease-in-out infinite;
}

@keyframes pulse-soft {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Glass Card Effect */
.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
}

/* Table Container Styles */
.table-section {
    background: white;
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 2px 10px rgba(0, 51, 102, 0.08);
    overflow: hidden;
}

.table-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background: #f8f9fa;
}

.table-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--ura-primary);
    margin: 0;
}

/* Enhanced Table - Modern Design */
.modern-table-container {
    overflow-x: auto;
    padding: 0;
}

.modern-table-wrapper::before,
.modern-table-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--ura-gradient);
    z-index: 1;
}

/* Table Styles - Complete Reset */
.modern-table-container {
    overflow-x: auto;
    background: white;
    border-radius: 8px;
}

.modern-data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

/* Fixed Column Layout */
.modern-data-table thead tr,
.modern-data-table tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.modern-data-table th,
.modern-data-table td {
    padding: 12px 8px;
    text-align: left;
    vertical-align: middle;
}

/* Column widths - must match exactly */
.modern-data-table th:nth-child(1),
.modern-data-table td:nth-child(1) {
    width: 40px; /* Checkbox */
    text-align: center;
}

.modern-data-table th:nth-child(2),
.modern-data-table td:nth-child(2) {
    width: 120px; /* Reference */
}

.modern-data-table th:nth-child(3),
.modern-data-table td:nth-child(3) {
    width: 180px; /* Employee */
}

.modern-data-table th:nth-child(4),
.modern-data-table td:nth-child(4) {
    width: 100px; /* Amount */
    text-align: right;
}

.modern-data-table th:nth-child(5),
.modern-data-table td:nth-child(5) {
    width: 80px; /* Bank */
    text-align: center;
}

.modern-data-table th:nth-child(6),
.modern-data-table td:nth-child(6) {
    width: 120px; /* Account */
    text-align: center;
}

.modern-data-table th:nth-child(7),
.modern-data-table td:nth-child(7) {
    width: 90px; /* Days Waiting */
    text-align: center;
}

.modern-data-table th:nth-child(8),
.modern-data-table td:nth-child(8) {
    width: 110px; /* Date */
    text-align: center;
}

.modern-data-table th:nth-child(9),
.modern-data-table td:nth-child(9) {
    width: 100px; /* Actions */
    text-align: center;
}

.modern-data-table thead {
    background: #003366;
    color: white;
}

.modern-data-table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    border: none;
    color: white;
    background: transparent;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.th-text {
    font-weight: 700;
}

.sort-icon {
    opacity: 0.5;
    transition: opacity 0.3s;
    color: white;
}

.sortable-column {
    cursor: pointer;
    user-select: none;
}

.sortable-column:hover .sort-icon {
    opacity: 1;
}

/* Table specific styles */
.checkbox-column {
    width: 50px;
}

.action-column {
    width: 120px;
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.employee-avatar {
    width: 40px;
    height: 40px;
    background: var(--ura-gradient);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
}

.employee-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.employee-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.85rem;
    line-height: 1.2;
}

.employee-id {
    font-size: 0.7rem;
    color: #6c757d;
}

.reference-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.reference-primary {
    font-weight: 600;
    font-size: 0.85rem;
    color: #2c3e50;
}

.reference-secondary {
    font-size: 0.7rem;
    color: #6c757d;
}

.amount-cell {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

.amount-primary {
    font-weight: 700;
    color: var(--ura-primary);
    font-size: 0.9rem;
}

.amount-secondary {
    font-size: 0.7rem;
    color: #6c757d;
}

.date-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.date-primary {
    font-weight: 500;
    font-size: 0.85rem;
    color: #495057;
}

.date-secondary {
    font-size: 0.7rem;
    color: #6c757d;
}

.bank-info {
    display: flex;
    align-items: center;
    gap: 6px;
}

.bank-logo {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    background: #f0f0f0;
    padding: 2px;
}

.account-number {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    color: #495057;
    letter-spacing: 0.5px;
}

.modern-data-table tbody tr {
    transition: background-color 0.2s ease;
    background: white;
}

.modern-data-table tbody tr:hover {
    background-color: #f8f9fa;
}

.disbursement-table tbody tr::before,
.modern-table tbody tr::before,
.modern-data-table tbody tr::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--ura-gradient);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.disbursement-table tbody tr:hover,
.modern-table tbody tr:hover,
.modern-data-table tbody tr:hover {
    background: linear-gradient(90deg, rgba(23, 71, 158, 0.03) 0%, rgba(23, 71, 158, 0.01) 100%) !important;
    transform: translateX(3px);
}

.disbursement-table tbody tr:hover::before,
.modern-table tbody tr:hover::before,
.modern-data-table tbody tr:hover::before {
    transform: scaleY(1);
}

.disbursement-table tbody tr.selected,
.modern-table tbody tr.selected,
.modern-data-table tbody tr.selected {
    background: linear-gradient(90deg, rgba(23, 71, 158, 0.08) 0%, rgba(23, 71, 158, 0.03) 100%) !important;
}

/* Modern checkbox */
.modern-checkbox {
    position: relative;
    display: inline-block;
}

.modern-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    opacity: 0;
    position: absolute;
}

.modern-checkbox label {
    position: relative;
    display: inline-block;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.modern-checkbox input[type="checkbox"]:checked + label {
    background: var(--ura-gradient);
    border-color: var(--ura-primary);
}

.modern-checkbox input[type="checkbox"]:checked + label::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.urgent-row {
    animation: urgent-pulse 2s ease-in-out infinite;
}

@keyframes urgent-pulse {
    0%, 100% { background-color: transparent; }
    50% { background-color: rgba(255, 140, 0, 0.05); }
}

.modern-data-table tbody td {
    padding: 12px 8px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
    color: #495057;
    font-size: 0.85rem;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
}

.modern-data-table tbody td.text-center {
    text-align: center;
}

.modern-data-table tbody td.text-end {
    text-align: right;
}

.modern-data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Status Badges - Enhanced */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.status-badge::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.3s ease;
}

.status-badge:hover::before {
    transform: translate(-50%, -50%) scale(2);
}

.status-badge.pending {
    background: linear-gradient(135deg, #FFF3CD 0%, #FFE69C 100%);
    color: #856404;
    border: 2px solid #FFD700;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
}

.status-badge.urgent {
    background: linear-gradient(135deg, #F8D7DA 0%, #F5C2C7 100%);
    color: #721C24;
    border: 2px solid #F5C2C7;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    animation: urgent-badge 1.5s ease-in-out infinite;
}

@keyframes urgent-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.days-waiting {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.25rem 0.5rem;
    background: rgba(0, 51, 102, 0.05);
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ura-primary);
}

/* Action Buttons - Modern Style */
.action-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
}

/* Remove old action button styles - now using action-btn-modern */

.btn-action-view {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    color: #1565C0;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
}

.btn-action-view:hover {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
}

.btn-action-approve {
    background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
    color: #2E7D32;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
}

.btn-action-approve:hover {
    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
    color: white;
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
}

.btn-action-reject {
    background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
    color: #C62828;
    box-shadow: 0 2px 8px rgba(244, 67, 54, 0.2);
}

.btn-action-reject:hover {
    background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
    color: white;
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4);
}

/* Action Button Group */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

/* Remove floating animations */
@keyframes none {}

/* Simple hover effect for cards */
.animate-fade-in {
    opacity: 1;
}

.animate-slide-down {
    transform: translateY(0);
}

/* Additional styles for elements */
.kpi-badge-modern {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
}

.warning-badge {
    background: rgba(255, 152, 0, 0.2);
    color: #FF9800;
}

.success-badge {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.danger-badge {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.info-badge {
    background: rgba(23, 71, 158, 0.2);
    color: #17479E;
}

/* Button styles - Enhanced */
.btn-ura-primary-sm {
    background: var(--ura-gradient);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn-ura-primary-sm::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-ura-primary-sm:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 24px rgba(0, 51, 102, 0.3);
    color: white;
}

.btn-ura-primary-sm:hover::before {
    left: 100%;
}

.btn-ura-light-sm {
    background: white;
    color: #6c757d;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s;
}

/* Table header styles */
.table-header-modern {
    padding: 1rem;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 1rem;
}

.table-title-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.table-icon-wrapper {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #17479E 0%, #2E5090 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.table-main-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0.25rem;
}

.table-subtitle {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Progress bars */
.progress-bar-compact {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.pending-progress {
    background: linear-gradient(90deg, #FFA726, #FF9800);
}

.amount-progress {
    background: linear-gradient(90deg, #17479E, #2E5090);
}

.success-progress {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.danger-progress {
    background: linear-gradient(90deg, #dc3545, #c82333);
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

/* Timeline Styles for Audit Trail */
.timeline-container {
    position: relative;
    padding-left: 50px;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item.current .timeline-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
    }
}

.timeline-icon {
    position: absolute;
    left: -30px;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 3px solid #dee2e6;
    z-index: 1;
}

.timeline-icon i {
    font-size: 14px;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.timeline-header {
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.timeline-header strong {
    color: #495057;
    font-size: 0.95rem;
}

.timeline-body {
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Compact audit trail badges */
.approval-status .badge {
    margin: 2px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Modern Action Buttons */
.action-buttons-group {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.action-btn-modern {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-btn-modern:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.4s, height 0.4s;
}

.action-btn-modern:hover:before {
    width: 50px;
    height: 50px;
}

.action-btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.action-btn-modern:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* View Action Button */
.action-btn-modern.view-action {
    background: linear-gradient(135deg, #00BCD4 0%, #00ACC1 100%);
    color: white;
}

.action-btn-modern.view-action:hover {
    background: linear-gradient(135deg, #00ACC1 0%, #0097A7 100%);
}

/* Approve/Process Action Button */
.action-btn-modern.approve-action {
    background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%);
    color: white;
}

.action-btn-modern.approve-action:hover {
    background: linear-gradient(135deg, #45A049 0%, #388E3C 100%);
}

/* Reject Action Button */
.action-btn-modern.reject-action {
    background: linear-gradient(135deg, #F44336 0%, #E53935 100%);
    color: white;
}

.action-btn-modern.reject-action:hover {
    background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%);
}

/* Disabled state */
.action-btn-modern:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

/* Tooltip enhancement */
.action-btn-modern[title] {
    position: relative;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-btn-modern {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }

    .action-buttons-group {
        gap: 4px;
    }
}

/* Clickable Row Styles */
.clickable-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.clickable-row:hover {
    background-color: rgba(0, 51, 102, 0.05) !important;
}

.clickable-row.table-warning:hover {
    background-color: rgba(255, 193, 7, 0.2) !important;
}

/* Prevent text selection on row click */
.clickable-row {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Allow text selection in input fields */
.clickable-row input,
.clickable-row button {
    -webkit-user-select: auto;
    -moz-user-select: auto;
    -ms-user-select: auto;
    user-select: auto;
}
</style>

<!-- Modern Background Pattern -->
<div class="page-background"></div>

<!-- Animated Particle Background -->
<div class="particle-container">
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
</div>

<!-- Morphing Blob Background -->
<div class="blob-container">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item">Disbursement Management</li>
            <li class="breadcrumb-item active">Pending Disbursements</li>
        </ol>
    </nav>

    <!-- Page Header Section -->
    <div class="page-header-section">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="page-title">
                    <i class="fas fa-hourglass-half"></i>
                    Pending Disbursements
                </h1>
                <p class="page-subtitle">
                    Approved loans awaiting disbursement processing
                </p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <button class="btn btn-primary btn-sm me-2" onclick="refreshPendingLoans()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
                <button class="btn btn-success btn-sm me-2" onclick="showBatchDisbursement()">
                    <i class="fas fa-paper-plane me-1"></i>Batch Disburse
                </button>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel me-2 text-success"></i>Export Excel</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>Export PDF</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="showBulkActions()">
                            <i class="fas fa-tasks me-2"></i>Bulk Actions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-cards-wrapper">
        <!-- Pending Card -->
        <div class="kpi-card-modern">
            <div class="kpi-icon-wrapper primary">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Pending</div>
                <div class="kpi-value">{{ $stats['pending'] ?? 0 }}</div>
                <div class="kpi-subtitle text-muted">Awaiting disbursement</div>
            </div>
        </div>

        <!-- Total Amount Card -->
        <div class="kpi-card-modern">
            <div class="kpi-icon-wrapper success">
                <i class="fas fa-coins"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Total Amount</div>
                <div class="kpi-value">{{ number_format($stats['total_amount'] ?? 0, 0) }}</div>
                <div class="kpi-subtitle text-muted">TZS</div>
            </div>
        </div>

        <!-- Today's Approvals Card -->
        <div class="kpi-card-modern">
            <div class="kpi-icon-wrapper warning">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Today's Queue</div>
                <div class="kpi-value">{{ $stats['today'] ?? 0 }}</div>
                <div class="kpi-subtitle text-muted">Approved today</div>
            </div>
        </div>

        <!-- Urgent Card -->
        <div class="kpi-card-modern">
            <div class="kpi-icon-wrapper danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Urgent</div>
                <div class="kpi-value">{{ $stats['urgent'] ?? 0 }}</div>
                <div class="kpi-subtitle text-muted">>3 days waiting</div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-clipboard-check me-2"></i>
                Approved Loans - Pending Disbursement
            </h3>
            <p class="text-muted mb-0">
                Showing {{ $loanOffers->count() }} of {{ $loanOffers->total() ?? 0 }} records
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="pendingDisbursementsTable" style="width: 100%; table-layout: auto;">
                <thead class="bg-primary text-white">
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th style="width: 20%;">Employee</th>
                        <th style="width: 12%; text-align: right;">Take Home</th>
                        <th style="width: 10%; text-align: right;">Processing Fee</th>
                        <th style="width: 10%; text-align: right;">Insurance</th>
                        <th style="width: 10%; text-align: center;">Bank</th>
                        <th style="width: 12%; text-align: center;">Account</th>
                        <th style="width: 8%; text-align: center;">Days</th>
                        <th style="width: 10%; text-align: center;">Date</th>
                        <th style="width: 10%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loanOffers as $loan)
                        @php
                            $daysWaiting = $loan->updated_at->diffInDays(now());
                            $isUrgent = $daysWaiting > 3;
                        @endphp
                        <tr class="{{ $isUrgent ? 'table-warning' : '' }} clickable-row"
                            data-loan='@json($loan)'
                            onclick="handleRowClick(event, this)">
                            <td style="text-align: center;">
                                <input type="checkbox" class="loan-checkbox" value="{{ $loan->id }}" onclick="event.stopPropagation()">
                            </td>
                            <td>
                                <div>
                                    {{ $loan->first_name ?? '' }} {{ $loan->last_name ?? '' }}
                                    @if($loan->loan_type === 'topup' || $loan->offer_type === 'TOP_UP')
                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">
                                            <i class="fas fa-sync-alt"></i> TOPUP
                                        </span>
                                    @else
                                        <span class="badge bg-success ms-1" style="font-size: 0.65rem;">
                                            <i class="fas fa-plus-circle"></i> NEW
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    ID: {{ $loan->check_number ?? '' }}
                                    @if($loan->loan_type === 'topup' && $loan->topupAsNew && $loan->topupAsNew->original_loan_number)
                                        • Settles: {{ $loan->topupAsNew->original_loan_number }}
                                    @endif
                                </small>
                            </td>
                            <td style="text-align: right;">
                                <strong>{{ number_format($loan->take_home_amount ?? 0, 0) }}</strong>
                                <div><small class="text-muted">TZS</small></div>
                            </td>
                            <td style="text-align: right;">
                                {{ number_format($loan->processing_fee ?? 0, 0) }}
                                <div><small class="text-muted">TZS</small></div>
                            </td>
                            <td style="text-align: right;">
                                {{ number_format($loan->insurance ?? 0, 0) }}
                                <div><small class="text-muted">TZS</small></div>
                            </td>
                            <td style="text-align: center;">
                                @if($loan->bank)
                                    <span class="badge bg-info">{{ $loan->bank->short_name ?: $loan->bank->name }}</span>
                                @elseif($loan->swift_code)
                                    <small>{{ $loan->swift_code }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{ $loan->bank_account_number ?? '-' }}
                            </td>
                            <td style="text-align: center;">
                                @if($isUrgent)
                                    <span class="badge bg-danger">{{ $daysWaiting }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $daysWaiting }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div>{{ $loan->updated_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $loan->updated_at->format('H:i') }}</small>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-buttons-group">
                                    <button class="action-btn-modern view-action" data-loan='@json($loan)' onclick="showLoanDetails(this)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn-modern approve-action" data-loan='@json($loan)' onclick="showDisbursementModal(this)" title="Process Disbursement">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="action-btn-modern reject-action" data-loan='@json($loan)' onclick="showRejectModal(this)" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Pending Disbursements</h5>
                                <p class="text-muted">All approved loans have been processed.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        @if($loanOffers->hasPages())
        <div class="modern-table-footer">
            <div class="pagination-info">
                Showing {{ $loanOffers->firstItem() ?? 0 }} to {{ $loanOffers->lastItem() ?? 0 }} of {{ $loanOffers->total() }} entries
            </div>
            <div class="pagination-wrapper">
                {{ $loanOffers->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Include Modals -->
@include('employee_loan.modals.disbursement-modal')
@include('employee_loan.modals.reject-disbursement-modal')

@endsection

{{-- Include Modals --}}
@include('employee_loan.partials.loan-details-modal')
@include('employee_loan.modals.disbursement-modal')
@include('employee_loan.modals.reject-disbursement-modal')
@include('employee_loan.modals.batch-disbursement-modal')

@push('scripts')
<script>
let selectedLoans = [];
let currentLoan = null;

// Handle row click
function handleRowClick(event, row) {
    // Don't trigger if clicking on buttons or checkboxes
    if (event.target.closest('button') || event.target.closest('input')) {
        return;
    }

    // Get loan data from row
    const loan = JSON.parse(row.dataset.loan);

    // Show disbursement modal - all loans in this view are actionable
    showDisbursementModal({dataset: {loan: JSON.stringify(loan)}});
}

// Show loan details modal
function showLoanDetails(btn) {
    // Prevent row click when button is clicked
    event.stopPropagation();

    const loan = JSON.parse(btn.dataset.loan);
    currentLoan = loan;

    // Call the modal's populate function if it exists
    if (typeof populateLoanDetailsModal === 'function') {
        populateLoanDetailsModal(loan);
    }

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('loanDetailsModal'));
    modal.show();
}

// Show disbursement modal
function showDisbursementModal(btn) {
    const loan = JSON.parse(btn.dataset.loan);
    currentLoan = loan;
    selectedLoans = [loan.id];

    // Populate disbursement content
    const contentDiv = document.getElementById('disbursementContent');
    if (contentDiv) {
        contentDiv.innerHTML = `
            <div class="row g-4">
                <!-- Employee Information -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-user me-2"></i>Employee Information
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Name:</div>
                                <div class="col-7 fw-bold">${getFullName(loan)}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Employee No:</div>
                                <div class="col-7 fw-bold">${loan.check_number || 'N/A'}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Check Number:</div>
                                <div class="col-7 fw-bold">${loan.check_number || 'N/A'}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Vote Code:</div>
                                <div class="col-7">${loan.vote_code || 'N/A'}</div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">Department:</div>
                                <div class="col-7">${loan.vote_name || 'N/A'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-money-bill-wave me-2"></i>Loan Details
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Processing Fee:</div>
                                <div class="col-7">${formatCurrency(loan.processing_fee || 0)}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Insurance:</div>
                                <div class="col-7">${formatCurrency(loan.insurance || 0)}</div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">Take Home:</div>
                                <div class="col-7 fw-bold text-success">${formatCurrency(loan.take_home_amount || 0)}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-university me-2"></i>Bank Information
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Bank Name:</div>
                                <div class="col-7 fw-bold">${loan.bank?.name || loan.bank_name || 'N/A'}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Account No:</div>
                                <div class="col-7 fw-bold">${loan.bank_account_number || 'N/A'}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Account Name:</div>
                                <div class="col-7">${loan.bank_account_name || getFullName(loan)}</div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">SWIFT Code:</div>
                                <div class="col-7">${loan.swift_code || 'N/A'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disbursement Summary -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <i class="fas fa-clipboard-check me-2"></i>Disbursement Summary
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <h6 class="alert-heading">Amount to Disburse:</h6>
                                <h4 class="mb-2 text-dark">${formatCurrency(loan.take_home_amount || 0)}</h4>
                                <hr>
                                <p class="mb-0 small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    This amount will be transferred to the employee's bank account
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internal Approval History -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-check-circle me-2"></i>Internal Approval History
                        </div>
                        <div class="card-body">
                            <div class="timeline-audit">
                                ${generateInternalAuditTrail(loan)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Please verify all details before confirming disbursement. This action will initiate the transfer process.
                    </div>
                </div>
            </div>
        `;
    }

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('disbursementModal'));
    modal.show();

    // Set up confirm button handler
    const confirmBtn = document.getElementById('confirmDisbursement');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            confirmDisbursementAction();
        };
    }

    // Set up reject from disbursement modal button
    const rejectBtn = document.getElementById('rejectDisbursement');
    if (rejectBtn) {
        rejectBtn.onclick = function() {
            // Close disbursement modal
            bootstrap.Modal.getInstance(document.getElementById('disbursementModal')).hide();
            // Open reject modal
            setTimeout(() => showRejectModal({dataset: {loan: JSON.stringify(currentLoan)}}), 300);
        };
    }
}

// Confirm disbursement action
function confirmDisbursementAction() {
    Swal.fire({
        title: 'Confirm Disbursement',
        html: `Are you sure you want to disburse <strong>${formatCurrency(currentLoan.take_home_amount || 0)}</strong> to ${getFullName(currentLoan)}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Disburse',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Process disbursement
            const formData = new FormData();
            formData.append('loan_ids[]', currentLoan.id);

            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Initiating disbursement',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("disbursements.process") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('disbursementModal')).hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Disbursement initiated successfully',
                        confirmButtonColor: '#17479e'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to process disbursement');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// Generate compact approval history for display
function generateCompactApprovalHistory(loan) {
    let html = '<div class="d-flex flex-wrap gap-1">';

    if (loan.approvals && loan.approvals.length > 0) {
        loan.approvals.forEach(approval => {
            if (approval.status === 'approved') {
                const typeLabel = approval.approval_type === 'initial' ? 'Initial' : 'Final';
                html += `<span class="badge bg-success bg-opacity-75" style="font-size: 0.75rem;">
                    <i class="fas fa-check fa-xs"></i> ${typeLabel}
                </span>`;
            } else if (approval.status === 'rejected') {
                const typeLabel = approval.approval_type === 'initial' ? 'Initial' : 'Final';
                html += `<span class="badge bg-danger bg-opacity-75" style="font-size: 0.75rem;">
                    <i class="fas fa-times fa-xs"></i> ${typeLabel} Rejected
                </span>`;
            }
        });
    } else {
        // If no approvals, check the loan status
        if (loan.approval === 'APPROVED') {
            html += '<span class="badge bg-success bg-opacity-75" style="font-size: 0.75rem;">Approved</span>';
        } else if (loan.approval === 'REJECTED') {
            html += '<span class="badge bg-danger bg-opacity-75" style="font-size: 0.75rem;">Rejected</span>';
        } else {
            html += '<span class="badge bg-warning bg-opacity-75" style="font-size: 0.75rem;">Pending</span>';
        }
    }

    html += '</div>';
    return html;
}

// Show reject modal
function showRejectModal(btn) {
    try {
        const loan = JSON.parse(btn.dataset.loan);
        currentLoan = loan;
        selectedLoans = [loan.id];

        // Set loan ID
        const loanIdInput = document.getElementById('rejectLoanId');
        if (loanIdInput) {
            loanIdInput.value = loan.id;
        }

    // Populate loan details in reject modal
    const detailsDiv = document.getElementById('rejectLoanDetails');
    if (detailsDiv) {
        detailsDiv.innerHTML = `
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Loan ID:</span>
                    <span class="fw-bold">#${loan.id}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Employee:</span>
                    <span class="fw-bold">${getFullName(loan)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Employee No:</span>
                    <span>${loan.check_number || 'N/A'}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Check No:</span>
                    <span>${loan.check_number || 'N/A'}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Bank:</span>
                    <span>${loan.bank?.name || loan.bank_name || 'N/A'}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Account:</span>
                    <span>${loan.bank_account_number || 'N/A'}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Processing Fee:</span>
                    <span>${formatCurrency(loan.processing_fee || 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Insurance:</span>
                    <span>${formatCurrency(loan.insurance || 0)}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Take Home:</span>
                    <span class="fw-bold text-danger">${formatCurrency(loan.take_home_amount || 0)}</span>
                </div>
                <hr>
                <div class="approval-status mb-2">
                    <strong class="text-muted d-block mb-2">Approval History:</strong>
                    ${generateCompactApprovalHistory(loan)}
                </div>
                ${loan.approvals && loan.approvals.filter(a => a.approval_type === 'final' && a.status === 'rejected').length > 0 ? `
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-history me-2"></i>
                        <small><strong>Previous Rejection:</strong><br>
                        ${loan.approvals.find(a => a.approval_type === 'final' && a.status === 'rejected')?.reason || 'No reason provided'}</small>
                    </div>
                ` : ''}
            </div>
            <div class="alert alert-danger mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <small>This loan will be rejected for disbursement</small>
            </div>
        `;
    }

    // Reset form fields
    const reasonSelect = document.getElementById('rejectionReason');
    if (reasonSelect) reasonSelect.value = '';

    const messageTextarea = document.getElementById('rejectionMessage');
    if (messageTextarea) {
        messageTextarea.value = '';
        updateCharCount();
    }

    // Show the modal
    const modalElement = document.getElementById('rejectDisbursementModal');
    if (!modalElement) {
        console.error('Reject modal element not found!');
        alert('Error: Reject modal not found. Please refresh the page.');
        return;
    }

    try {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } catch (error) {
        console.error('Error showing modal:', error);
        alert('Error opening reject modal: ' + error.message);
    }

    // Add event listener for character counter
    if (messageTextarea) {
        messageTextarea.addEventListener('input', updateCharCount);
    }
    } catch (error) {
        console.error('Error in showRejectModal:', error);
        alert('Error: ' + error.message);
    }
}

// Character counter for rejection message
function updateCharCount() {
    const textarea = document.getElementById('rejectionMessage');
    const counter = document.getElementById('charCount');
    if (textarea && counter) {
        counter.textContent = textarea.value.length;
    }
}

// Alias for consistency
function updateCharacterCount() {
    updateCharCount();
}

// Handle rejection reason change
function handleRejectionReasonChange(select) {
    const messageTextarea = document.getElementById('rejectionMessage');
    if (!messageTextarea) return;

    if (select.value && select.value !== 'other') {
        // Pre-fill message based on selection
        const messages = {
            'Insufficient funds': 'Unable to process disbursement due to insufficient funds. Please try again later.',
            'Incomplete documentation': 'Missing required documentation. Please submit all necessary documents.',
            'Account verification pending': 'Bank account verification is still pending. Please wait for verification completion.',
            'Customer cancellation': 'Disbursement cancelled as per customer request.',
            'Technical error': 'Technical error occurred during processing. Our team is working to resolve this.',
            'Compliance check failed': 'Loan failed compliance verification. Please contact support for details.',
            'Invalid bank details': 'The provided bank account details are invalid. Please verify and update.'
        };
        messageTextarea.value = messages[select.value] || '';
        updateCharCount();
    }
}

// Set template for rejection message
function setTemplate(templateType) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const reasonSelect = document.getElementById('rejectionReason');

    if (!messageTextarea || !reasonSelect) return;

    const templates = {
        'verification': {
            reason: 'Account verification pending',
            message: 'Your bank account verification is pending. Please allow 24-48 hours for completion.'
        },
        'documents': {
            reason: 'Incomplete documentation',
            message: 'Required documents are missing. Please upload all necessary documents to proceed.'
        },
        'technical': {
            reason: 'Technical error',
            message: 'A technical issue prevented disbursement. Our team is resolving this urgently.'
        }
    };

    const template = templates[templateType];
    if (template) {
        reasonSelect.value = template.reason;
        messageTextarea.value = template.message;
        updateCharCount();
    }
}

// Confirm rejection
function confirmRejectDisbursement() {
    const reasonSelect = document.getElementById('rejectionReason');
    const messageTextarea = document.getElementById('rejectionMessage');

    if (!reasonSelect.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please select a rejection category',
            confirmButtonColor: '#17479e'
        });
        return;
    }

    if (!messageTextarea.value.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Information',
            text: 'Please provide a customer message',
            confirmButtonColor: '#17479e'
        });
        return;
    }

    // Show confirmation dialog
    Swal.fire({
        title: 'Confirm Rejection',
        html: `
            <div class="text-start">
                <p>Are you sure you want to reject this loan disbursement?</p>
                <div class="mt-3">
                    <strong>Employee:</strong> ${getFullName(currentLoan)}<br>
                    <strong>Amount:</strong> ${formatCurrency(currentLoan.take_home_amount || 0)}<br>
                    <strong>Reason:</strong> ${reasonSelect.value}<br>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone. The customer will be notified immediately.
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject Disbursement',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Prepare form data
            const formData = new FormData();
            formData.append('loan_ids[]', currentLoan.id);
            formData.append('reason', reasonSelect.value);
            formData.append('detailed_message', messageTextarea.value);

            // Show loading
            const confirmBtn = document.getElementById('confirmRejectDisbursement');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            // Show loading dialog
            Swal.fire({
                title: 'Processing Rejection',
                text: 'Please wait while we process the rejection...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send request
            fetch('{{ route("disbursements.reject") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('rejectDisbursementModal')).hide();

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Disbursement Rejected',
                        text: 'The disbursement has been rejected successfully',
                        confirmButtonColor: '#17479e'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to reject disbursement');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonColor: '#dc3545'
                });
            })
            .finally(() => {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
            });
        }
    });
}

// Process disbursement (form submission)
function processDisbursement(event) {
    if (event) event.preventDefault();

    const form = document.getElementById('disbursementForm');
    const formData = new FormData(form);

    // Add selected loan IDs
    selectedLoans.forEach(id => {
        formData.append('loan_ids[]', id);
    });

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

    // Send AJAX request
    fetch('{{ route("disbursements.process") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Disbursement initiated successfully',
                confirmButtonColor: '#17479e'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to process disbursement');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to process disbursement',
            confirmButtonColor: '#dc3545'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });

    return false;
}

// Reject disbursement (form submission)
function rejectDisbursement(event) {
    if (event) event.preventDefault();

    const form = document.getElementById('rejectForm');
    const formData = new FormData(form);

    // Add selected loan IDs
    selectedLoans.forEach(id => {
        formData.append('loan_ids[]', id);
    });

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

    // Send AJAX request
    fetch('{{ route("disbursements.reject") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Disbursement rejected successfully',
                confirmButtonColor: '#17479e'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to reject disbursement');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to reject disbursement',
            confirmButtonColor: '#dc3545'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });

    return false;
}

// Format currency
function formatCurrency(amount) {
    return 'TZS ' + new Intl.NumberFormat('en-US').format(amount);
}

// Get full name from loan object
function getFullName(loan) {
    const parts = [];
    if (loan.first_name) parts.push(loan.first_name);
    if (loan.middle_name) parts.push(loan.middle_name);
    if (loan.last_name) parts.push(loan.last_name);

    // If we have name parts, use them
    if (parts.length > 0) {
        return parts.join(' ');
    }

    // Otherwise fall back to employee_name
    return loan.employee_name || 'N/A';
}

// Generate internal audit trail (for internal approvals only)
function generateInternalAuditTrail(loan) {
    let html = '<div class="timeline-container">';

    // Application submitted
    if (loan.created_at) {
        html += `
            <div class="timeline-item">
                <div class="timeline-icon bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Application Submitted</strong>
                        <span class="text-muted ms-2">${formatDateTime(loan.created_at)}</span>
                    </div>
                    <div class="timeline-body text-muted">
                        Loan application received for ${formatCurrency(loan.requested_amount || 0)}
                        ${loan.member ? `<br><small>Submitted by: ${loan.member.first_name} ${loan.member.last_name}</small>` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Internal approvals with enhanced details
    if (loan.approvals && loan.approvals.length > 0) {
        loan.approvals.forEach(approval => {
            const typeLabels = {
                'initial': 'Initial Review',
                'final': 'Final Approval',
                'disbursement': 'Disbursement Approval'
            };

            if (approval.status === 'approved') {
                html += `
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <strong>${typeLabels[approval.approval_type] || approval.approval_type}</strong>
                                <span class="text-muted ms-2">${formatDateTime(approval.approved_at)}</span>
                            </div>
                            <div class="timeline-body text-muted">
                                <strong>Approved by:</strong> ${getApproverName(approval, loan)}
                                ${approval.fsp_reference_number ? `<br><small>Reference: ${approval.fsp_reference_number}</small>` : ''}
                                ${approval.total_amount_to_pay ? `<br><small>Amount: ${formatCurrency(approval.total_amount_to_pay)}</small>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else if (approval.status === 'rejected') {
                html += `
                    <div class="timeline-item">
                        <div class="timeline-icon bg-danger">
                            <i class="fas fa-times text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <strong>${typeLabels[approval.approval_type] || approval.approval_type} - Rejected</strong>
                                <span class="text-muted ms-2">${formatDateTime(approval.rejected_at)}</span>
                            </div>
                            <div class="timeline-body text-muted">
                                <strong>Rejected by:</strong> ${getRejectorName(approval, loan)}
                                ${approval.reason ? `<br><small class="text-danger">Reason: ${approval.reason}</small>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    }

    html += '</div>';
    return html;
}

// Generate external audit trail (ESS/FSP approvals)
function generateExternalAuditTrail(loan) {
    let html = '<div class="timeline-container">';

    // Employer approval
    if (loan.approvals && loan.approvals.length > 0) {
        loan.approvals.forEach(approval => {
            if (approval.approval_type === 'employer') {
                if (approval.status === 'approved') {
                    html += `
                        <div class="timeline-item">
                            <div class="timeline-icon bg-info">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <strong>Employer Approval</strong>
                                    <span class="text-muted ms-2">${formatDateTime(approval.approved_at)}</span>
                                </div>
                                <div class="timeline-body text-muted">
                                    Approved via ESS Employer System
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        });
    }

    // State: Submitted for disbursement
    if (loan.state === 'Submitted for disbursement') {
        html += `
            <div class="timeline-item">
                <div class="timeline-icon bg-info">
                    <i class="fas fa-paper-plane text-white"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Submitted for Disbursement</strong>
                        <span class="text-muted ms-2">${formatDateTime(loan.updated_at)}</span>
                    </div>
                    <div class="timeline-body text-muted">
                        Employer approved and submitted for processing
                    </div>
                </div>
            </div>
        `;
    }

    if (html === '<div class="timeline-container">') {
        html += '<p class="text-muted">No external approvals yet</p>';
    }

    html += '</div>';
    return html;
}

// Generate disbursement audit trail
function generateDisbursementAuditTrail(loan) {
    let html = '<div class="timeline-container">';

    // Check for disbursements
    if (loan.disbursements && loan.disbursements.length > 0) {
        loan.disbursements.forEach(disbursement => {
            const statusIcon = disbursement.status === 'success' ? 'check-circle' :
                             disbursement.status === 'failed' ? 'times-circle' : 'hourglass-half';
            const statusColor = disbursement.status === 'success' ? 'success' :
                              disbursement.status === 'failed' ? 'danger' : 'warning';

            html += `
                <div class="timeline-item">
                    <div class="timeline-icon bg-${statusColor}">
                        <i class="fas fa-${statusIcon} text-white"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong>Disbursement ${disbursement.status}</strong>
                            <span class="text-muted ms-2">${formatDateTime(disbursement.created_at)}</span>
                        </div>
                        <div class="timeline-body text-muted">
                            Amount: ${formatCurrency(disbursement.amount)}
                            ${disbursement.failure_reason ? '<br>Reason: ' + disbursement.failure_reason : ''}
                            ${disbursement.reference_number ? '<br>Reference: ' + disbursement.reference_number : ''}
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p class="text-muted">No disbursement activities yet</p>';
    }

    html += '</div>';
    return html;
}

// Generate internal approval badges
function generateInternalApprovalBadges(loan) {
    let html = '<div class="d-flex flex-wrap gap-2">';

    if (loan.approvals && loan.approvals.length > 0) {
        loan.approvals.forEach(approval => {
            if ((approval.approval_type === 'initial' || approval.approval_type === 'final') &&
                approval.status === 'approved') {
                const approverName = getApproverName(approval, loan);
                const typeLabel = approval.approval_type === 'initial' ? 'Initial' : 'Final';
                html += `<span class="badge bg-success">
                    <i class="fas fa-check-circle me-1"></i>${typeLabel}: ${approverName}
                </span>`;
            }
        });
    }

    if (html === '<div class="d-flex flex-wrap gap-2">') {
        html += '<span class="badge bg-secondary">No internal approvals recorded</span>';
    }

    html += '</div>';
    return html;
}

// Generate audit trail HTML (full trail for backwards compatibility)
function generateAuditTrail(loan) {
    let html = '<div class="timeline-container">';

    // Application submitted
    if (loan.created_at) {
        html += `
            <div class="timeline-item">
                <div class="timeline-icon bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Application Submitted</strong>
                        <span class="text-muted ms-2">${formatDateTime(loan.created_at)}</span>
                    </div>
                    <div class="timeline-body text-muted">
                        Loan application received for ${formatCurrency(loan.requested_amount || 0)}
                    </div>
                </div>
            </div>
        `;
    }

    // Check for approvals
    if (loan.approvals && loan.approvals.length > 0) {
        // Debug: Log the structure to console
        console.log('Loan approvals:', loan.approvals);

        loan.approvals.forEach(approval => {
            const approvalType = approval.approval_type || 'unknown';
            const typeLabels = {
                'initial': 'Initial Approval',
                'final': 'Final Approval',
                'employer': 'Employer Approval',
                'fsp': 'FSP Approval'
            };

            if (approval.status === 'approved') {
                html += `
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <strong>${typeLabels[approvalType] || 'Approval'}</strong>
                                <span class="text-muted ms-2">${formatDateTime(approval.approved_at)}</span>
                            </div>
                            <div class="timeline-body text-muted">
                                Approved by ${getApproverName(approval, loan)}
                                ${approval.fsp_reference_number ? '<br>Reference: ' + approval.fsp_reference_number : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else if (approval.status === 'rejected') {
                html += `
                    <div class="timeline-item">
                        <div class="timeline-icon bg-danger">
                            <i class="fas fa-times text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <strong>${typeLabels[approvalType] || 'Approval'} Rejected</strong>
                                <span class="text-muted ms-2">${formatDateTime(approval.rejected_at)}</span>
                            </div>
                            <div class="timeline-body text-muted">
                                Rejected by ${getRejectorName(approval, loan)}
                                ${approval.reason ? '<br>Reason: ' + approval.reason : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    }

    // If no approvals in array but loan is approved, show from loan fields
    if (loan.approval === 'APPROVED' && (!loan.approvals || loan.approvals.length === 0)) {
        const approvedDate = loan.approved_at || loan.updated_at;
        let approverName = '';

        // Check who approved this loan internally
        if (loan.approved_by_user) {
            approverName = loan.approved_by_user.name ||
                          `${loan.approved_by_user.first_name || ''} ${loan.approved_by_user.last_name || ''}`.trim() ||
                          loan.approved_by_user.email;
        } else if (loan.approved_by_name) {
            approverName = loan.approved_by_name;
        } else if (loan.approved_by) {
            approverName = `User #${loan.approved_by}`;
        } else if (loan.processed_by_name) {
            approverName = loan.processed_by_name;
        } else if (loan.processed_by) {
            approverName = `User #${loan.processed_by}`;
        }

        html += `
            <div class="timeline-item">
                <div class="timeline-icon bg-success">
                    <i class="fas fa-thumbs-up text-white"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Internal Approval</strong>
                        <span class="text-muted ms-2">${formatDateTime(approvedDate)}</span>
                    </div>
                    <div class="timeline-body text-muted">
                        Approved internally${approverName ? ' by ' + approverName : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Employer submission
    if (loan.state === 'Submitted for disbursement') {
        html += `
            <div class="timeline-item">
                <div class="timeline-icon bg-info">
                    <i class="fas fa-building text-white"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <strong>Submitted for Disbursement</strong>
                        <span class="text-muted ms-2">${formatDateTime(loan.updated_at)}</span>
                    </div>
                    <div class="timeline-body text-muted">
                        Employer approved and submitted for disbursement
                    </div>
                </div>
            </div>
        `;
    }

    // Current status - pending disbursement
    html += `
        <div class="timeline-item current">
            <div class="timeline-icon bg-warning pulse">
                <i class="fas fa-hourglass-half text-white"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-header">
                    <strong>Pending Disbursement</strong>
                    <span class="text-muted ms-2">Current Status</span>
                </div>
                <div class="timeline-body text-muted">
                    Awaiting disbursement processing
                </div>
            </div>
        </div>
    `;

    html += '</div>';
    return html;
}

// Generate compact audit trail for reject modal
function generateCompactAuditTrail(loan) {
    let html = '<div class="small">';

    // Show approval stages
    const stages = [];

    if (loan.created_at) {
        stages.push(`<span class="badge bg-secondary">Applied: ${formatDate(loan.created_at)}</span>`);
    }

    if (loan.approvals && loan.approvals.length > 0) {
        loan.approvals.forEach(approval => {
            if (approval.status === 'approved') {
                const typeLabel = approval.approval_type ?
                    approval.approval_type.charAt(0).toUpperCase() + approval.approval_type.slice(1) :
                    'Approved';
                stages.push(`<span class="badge bg-success">${typeLabel}: ${formatDate(approval.approved_at)}</span>`);
            }
        });
    }

    if (loan.approval === 'APPROVED') {
        stages.push(`<span class="badge bg-primary">Final Approval</span>`);
    }

    if (loan.state === 'Submitted for disbursement') {
        stages.push(`<span class="badge bg-info">Employer Approved</span>`);
    }

    stages.push(`<span class="badge bg-warning">Pending Disbursement</span>`);

    html += stages.join(' → ');
    html += '</div>';

    return html;
}

// Format date and time
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Format date only
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
}

// Get rejector name from approval record
function getRejectorName(approval, loan) {
    // Similar to getApproverName but for rejections
    if (approval.rejected_by && typeof approval.rejected_by === 'object') {
        const user = approval.rejected_by;
        return user.name || `${user.first_name || ''} ${user.last_name || ''}`.trim() || 'System Admin';
    } else if (approval.rejected_by) {
        return `User #${approval.rejected_by}`;
    }
    return 'System';
}

// Get approver name from approval record
function getApproverName(approval, loan) {
    // For internal approvals, show the actual user who approved
    if (approval.approval_type === 'initial' || approval.approval_type === 'final') {
        // Laravel eager loading puts the relationship in 'approved_by' field when serialized
        if (approval.approved_by && typeof approval.approved_by === 'object') {
            // The approved_by field contains the user object
            const user = approval.approved_by;
            return user.name ||
                   `${user.first_name || ''} ${user.last_name || ''}`.trim() ||
                   user.email ||
                   `User #${user.id || approval.approved_by_id || '?'}`;
        }

        // Check if we just have the ID as a string
        if (approval.approved_by && (typeof approval.approved_by === 'string' || typeof approval.approved_by === 'number')) {
            // Try to parse if it's a string number
            const userId = parseInt(approval.approved_by);
            if (!isNaN(userId)) {
                // For now, just show the user ID since we don't have the name
                // In production, you'd want to load the user names separately
                return `User #${userId}`;
            }
        }

        // Check for direct approver name field
        if (approval.approved_by_name) {
            return approval.approved_by_name;
        }

        // Default for internal approval
        return 'Internal Approver';
    }

    // For employer approval - this comes from ESS
    if (approval.approval_type === 'employer') {
        return 'ESS Employer System';
    }

    // For FSP approval
    if (approval.approval_type === 'fsp') {
        if (approval.approved_by_name) {
            return approval.approved_by_name;
        }
        if (loan.bank?.name) {
            return loan.bank.name + ' (FSP)';
        }
        return 'FSP System';
    }

    // Check if approver relationship is loaded (fallback for any type)
    if (approval.approved_by_user) {
        return approval.approved_by_user.name ||
               `${approval.approved_by_user.first_name || ''} ${approval.approved_by_user.last_name || ''}`.trim() ||
               approval.approved_by_user.email;
    }

    // Direct name field
    if (approval.approved_by_name) {
        return approval.approved_by_name;
    }

    // Show user ID if available
    if (approval.approved_by) {
        return `User #${approval.approved_by}`;
    }

    // Default based on approval type
    const typeDefaults = {
        'initial': 'Internal Approver',
        'final': 'Final Approver',
        'employer': 'ESS Employer',
        'fsp': 'FSP System'
    };

    return typeDefaults[approval.approval_type] || 'System';
}

// Get rejector name from approval record
function getRejectorName(approval, loan) {
    // Similar logic for rejected_by
    if (approval.rejected_by_name) {
        return approval.rejected_by_name;
    }

    if (approval.rejected_by_user) {
        return approval.rejected_by_user.name ||
               `${approval.rejected_by_user.first_name || ''} ${approval.rejected_by_user.last_name || ''}`.trim() ||
               approval.rejected_by_user.email;
    }

    if (approval.rejected_by && loan.users) {
        const user = loan.users.find(u => u.id === approval.rejected_by);
        if (user) {
            return user.name || `${user.first_name || ''} ${user.last_name || ''}`.trim() || user.email;
        }
    }

    if (approval.rejected_by) {
        return `User #${approval.rejected_by}`;
    }

    return 'Authorized Officer';
}

// Get final approver information
function getFinalApproverInfo(loan) {
    // Check for final approval in approvals array
    if (loan.approvals && loan.approvals.length > 0) {
        const finalApproval = loan.approvals.find(a => a.approval_type === 'final' && a.status === 'approved');
        if (finalApproval) {
            const approverName = getApproverName(finalApproval, loan);
            return ` by ${approverName}`;
        }
    }

    // Check for approved_by field on loan
    if (loan.approved_by_name) {
        return ` by ${loan.approved_by_name}`;
    }

    if (loan.approved_by_user) {
        const name = loan.approved_by_user.name ||
                    `${loan.approved_by_user.first_name || ''} ${loan.approved_by_user.last_name || ''}`.trim() ||
                    loan.approved_by_user.email;
        return ` by ${name}`;
    }

    if (loan.approved_by) {
        return ` by User #${loan.approved_by}`;
    }

    // Return empty string if no approver info available
    return '';
}

// Refresh pending loans
function refreshPendingLoans() {
    location.reload();
}

// Show batch disbursement modal
function showBatchDisbursement() {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    const selectedLoanIds = Array.from(checkboxes).map(cb => cb.value);

    if (selectedLoanIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Selection',
            text: 'Please select at least one loan to process',
            confirmButtonColor: '#17479e'
        });
        return;
    }

    // Get loan data for selected items
    const selectedLoanData = [];
    let totalAmount = 0;
    const banks = new Set();

    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const loanData = JSON.parse(row.dataset.loan);
        selectedLoanData.push(loanData);
        totalAmount += parseFloat(loanData.take_home_amount || loanData.requested_amount || 0);
        if (loanData.bank) {
            banks.add(loanData.bank.short_name || loanData.bank.name);
        }
    });

    // Update batch modal summary
    document.getElementById('batchSelectedCount').textContent = selectedLoanIds.length;
    document.getElementById('batchTotalAmount').textContent = formatCurrency(totalAmount);
    document.getElementById('batchBankCount').textContent = banks.size > 1 ? `${banks.size} Banks` : (banks.values().next().value || 'N/A');

    // Populate table
    const tableBody = document.getElementById('batchLoansTableBody');
    tableBody.innerHTML = selectedLoanData.map(loan => `
        <tr>
            <td>${getFullName(loan)}<br><small class="text-muted">ID: ${loan.check_number || 'N/A'}</small></td>
            <td class="text-end">${formatCurrency(loan.take_home_amount || 0)}</td>
            <td class="text-end">${formatCurrency(loan.processing_fee || 0)}</td>
            <td class="text-end">${formatCurrency(loan.insurance || 0)}</td>
            <td>${loan.bank ? (loan.bank.short_name || loan.bank.name) : 'N/A'}</td>
            <td>${loan.bank_account_number || 'N/A'}</td>
            <td><span class="badge bg-warning">Pending</span></td>
        </tr>
    `).join('');

    // Store selected IDs
    document.getElementById('batchLoanIds').value = selectedLoanIds.join(',');

    // Show warning for large batches
    const warningDiv = document.getElementById('batchWarning');
    if (selectedLoanIds.length > 50) {
        warningDiv.classList.remove('d-none');
    } else {
        warningDiv.classList.add('d-none');
    }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('batchDisbursementModal'));
    modal.show();
}

// Validate batch selection
function validateBatchSelection() {
    const loanIds = document.getElementById('batchLoanIds').value;
    const channel = document.getElementById('batchChannel').value;

    if (!loanIds) {
        Swal.fire('Error', 'No loans selected', 'error');
        return;
    }

    // Show validation in progress
    Swal.fire({
        title: 'Validating...',
        text: 'Checking selected loans for eligibility',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Simulate validation (replace with actual API call)
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Validation Complete',
            text: 'All selected loans are eligible for disbursement',
            confirmButtonColor: '#28a745'
        });
    }, 2000);
}

// Export report functionality
function exportReport(format) {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

// Execute export
function executeExport(format) {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    const exportSelected = document.getElementById('exportSelected').checked;
    const includeDetails = document.getElementById('exportWithDetails').checked;
    const dateFrom = document.getElementById('exportDateFrom').value;
    const dateTo = document.getElementById('exportDateTo').value;

    // Build export URL with parameters
    const params = new URLSearchParams();
    params.append('format', format);

    if (exportSelected && selectedIds.length > 0) {
        selectedIds.forEach(id => params.append('loan_ids[]', id));
    }

    if (includeDetails) {
        params.append('include_details', '1');
    }

    if (dateFrom) {
        params.append('date_from', dateFrom);
    }

    if (dateTo) {
        params.append('date_to', dateTo);
    }

    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();

    // Show loading
    Swal.fire({
        title: 'Exporting...',
        text: `Generating ${format.toUpperCase()} file`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Download file
    window.location.href = `{{ route('disbursements.export') }}?${params.toString()}`;

    // Hide loading after delay
    setTimeout(() => {
        Swal.close();
    }, 2000);
}

// Select all checkbox handler
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectionCount();
});

// Update selection count
function updateSelectionCount() {
    const checkboxes = document.querySelectorAll('.loan-checkbox:checked');
    const count = checkboxes.length;

    // Update batch button text if needed
    const batchBtn = document.querySelector('button[onclick="showBatchDisbursement()"]');
    if (batchBtn && count > 0) {
        batchBtn.innerHTML = `<i class="fas fa-paper-plane me-1"></i>Batch Disburse (${count})`;
    } else if (batchBtn) {
        batchBtn.innerHTML = `<i class="fas fa-paper-plane me-1"></i>Batch Disburse`;
    }
}

// Add event listeners to loan checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.loan-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });
});
</script>
@endpush

@push('scripts')
<script>
// Handle rejection reason change
function handleRejectionReasonChange(select) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const templates = {
        'Insufficient funds': 'Insufficient funds for disbursement. Please try again later.',
        'Incomplete documentation': 'Missing required documents. Submit via ESS portal.',
        'Account verification pending': 'Account verification in progress. Will notify once complete.',
        'Customer cancellation': 'Loan cancelled as per your request.',
        'Technical error': 'Technical error occurred. Resolution in progress.',
        'Compliance check failed': 'Compliance requirements not met. Contact support for details.',
        'Invalid bank details': 'Bank account details incorrect. Please update and resubmit.'
    };

    if (templates[select.value]) {
        messageTextarea.value = templates[select.value];
        updateCharacterCount();
    }
}

// Set template message
function setTemplate(type) {
    const messageTextarea = document.getElementById('rejectionMessage');
    const templates = {
        'verification': 'Account verification pending. We will notify you once completed.',
        'documents': 'Required documents missing. Please submit via ESS portal.',
        'technical': 'Technical issue preventing disbursement. Our team is working on it.'
    };

    if (templates[type]) {
        messageTextarea.value = templates[type];
        updateCharacterCount();
    }
}


// Add event listener for rejection message textarea
document.addEventListener('DOMContentLoaded', function() {
    const rejectionMessage = document.getElementById('rejectionMessage');
    if (rejectionMessage) {
        rejectionMessage.addEventListener('input', updateCharacterCount);
    }
});
</script>
@endpush

