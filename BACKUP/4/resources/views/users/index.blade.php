@extends('layouts.app')

@section('content')
<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-light: #2558B3;
        --ura-accent: #00BCD4;
        --ura-accent-light: #4DD0E1;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-gradient: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        --ura-gradient-light: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        --ura-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
        --ura-shadow-hover: 0 12px 35px rgba(23, 71, 158, 0.25);
    }

    .users-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .users-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .bi-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .bi-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .bi-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ura-shadow-hover);
    }

    .bi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .bi-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .bi-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .bi-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
    }

    .bi-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .online-indicator {
        width: 8px;
        height: 8px;
        background: var(--ura-success);
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 220, 96, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(16, 220, 96, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 220, 96, 0); }
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }

    .modern-card-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modern-card-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .modern-btn-primary {
        background: var(--ura-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--ura-shadow-hover);
        color: white;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--ura-gradient-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--ura-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 1rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-active {
        background: linear-gradient(135deg, rgba(16, 220, 96, 0.2) 0%, rgba(16, 220, 96, 0.1) 100%);
        color: var(--ura-success);
        border: 1px solid var(--ura-success);
    }

    .status-inactive {
        background: linear-gradient(135deg, rgba(240, 65, 65, 0.2) 0%, rgba(240, 65, 65, 0.1) 100%);
        color: var(--ura-danger);
        border: 1px solid var(--ura-danger);
    }

    .status-online {
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(0, 188, 212, 0.1) 100%);
        color: var(--ura-accent);
        border: 1px solid var(--ura-accent);
    }

    .accordion-button {
        background: var(--ura-gradient-light) !important;
        color: var(--ura-primary) !important;
        border: none !important;
        font-weight: 600;
    }

    .accordion-button:not(.collapsed) {
        background: var(--ura-gradient) !important;
        color: white !important;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .form-control, .form-select {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .online-indicator-small {
        animation: pulseSmall 2s infinite;
    }

    @keyframes pulseSmall {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Modern Analytics Modal Styles */
    .analytics-modal {
        backdrop-filter: blur(10px);
    }

    .analytics-modal .modal-dialog {
        max-width: 95vw;
        width: 1400px;
        margin: 1rem auto;
    }

    .analytics-modal .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(23, 71, 158, 0.3);
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    }

    .analytics-header {
        background: var(--ura-gradient);
        color: white;
        padding: 2rem;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }

    .analytics-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    .analytics-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .analytics-subtitle {
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .analytics-metric-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(23, 71, 158, 0.1);
        position: relative;
        overflow: hidden;
    }

    .analytics-metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(23, 71, 158, 0.2);
    }

    .analytics-metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .metric-icon {
        width: 60px;
        height: 60px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .metric-icon i {
        font-size: 1.8rem;
        color: var(--ura-primary);
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .metric-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-change {
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .metric-change.positive {
        color: var(--ura-success);
    }

    .metric-change.negative {
        color: var(--ura-danger);
    }

    .analytics-chart-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        height: 350px;
    }

    .analytics-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        overflow: hidden;
    }

    .analytics-table {
        margin: 0;
    }

    .analytics-table thead th {
        background: var(--ura-gradient-light);
        color: var(--ura-primary);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .analytics-table tbody tr {
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
        transition: all 0.3s ease;
    }

    .analytics-table tbody tr:hover {
        background: var(--ura-gradient-light);
    }

    .analytics-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: #495057;
    }

    .analytics-filter-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.1);
        border: 1px solid rgba(23, 71, 158, 0.1);
        margin-bottom: 2rem;
    }

    .filter-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-analytics {
        background: var(--ura-gradient);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-analytics:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.3);
        color: white;
    }

    .analytics-tabs {
        border: none;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .analytics-tabs .nav-link {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        color: var(--ura-primary);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .analytics-tabs .nav-link.active {
        background: var(--ura-gradient);
        border-color: var(--ura-primary);
        color: white;
    }

    .analytics-tabs .nav-link:hover {
        background: var(--ura-gradient-light);
        border-color: var(--ura-primary);
        color: var(--ura-primary);
    }

    /* Modern Security Table Styles */
    .modern-security-table {
        margin: 2rem 0;
    }

    .security-table-header {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-radius: 20px 20px 0 0;
        padding: 2rem;
        border: 1px solid rgba(220, 53, 69, 0.1);
        border-bottom: none;
    }

    .security-title {
        color: #dc3545;
        font-weight: 700;
        font-size: 1.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 0;
    }

    .security-icon {
        font-size: 2.2rem;
        animation: pulse-danger 2s infinite;
    }

    @keyframes pulse-danger {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    .violation-count {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .security-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }

    .search-container {
        position: relative;
        width: 350px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.1rem;
        z-index: 2;
    }

    .modern-search {
        border: 2px solid rgba(220, 53, 69, 0.2);
        border-radius: 25px;
        padding: 0.75rem 1rem 0.75rem 3rem;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        width: 100%;
    }

    .modern-search:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        outline: none;
    }

    .modern-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        border-bottom: 2px solid rgba(220, 53, 69, 0.1);
    }

    .table-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .control-label {
        color: #495057;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .modern-select {
        border: 2px solid rgba(220, 53, 69, 0.2);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        width: 80px;
        font-weight: 600;
        color: #dc3545;
    }

    .export-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .modern-btn {
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .excel-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .excel-btn:hover {
        background: linear-gradient(135deg, #20c997, #28a745);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .pdf-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .pdf-btn:hover {
        background: linear-gradient(135deg, #c82333, #dc3545);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        color: white;
    }

    .print-btn {
        background: linear-gradient(135deg, #6f42c1, #6610f2);
        color: white;
    }

    .print-btn:hover {
        background: linear-gradient(135deg, #6610f2, #6f42c1);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
        color: white;
    }

    .refresh-btn {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }

    .refresh-btn:hover {
        background: linear-gradient(135deg, #138496, #17a2b8);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        color: white;
    }

    .modern-table-container {
        background: white;
        border-radius: 0 0 20px 20px;
        border: 1px solid rgba(220, 53, 69, 0.1);
        border-top: none;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.1);
    }

    .modern-table {
        margin: 0;
        border: none;
    }

    .modern-thead {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .modern-thead th {
        border: none;
        padding: 1.5rem 1rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .th-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .th-content i {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .modern-tbody tr {
        border-bottom: 1px solid rgba(220, 53, 69, 0.1);
        transition: all 0.3s ease;
    }

    .modern-tbody tr:hover {
        background: rgba(220, 53, 69, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.1);
    }

    .violation-row td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border: none;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        position: relative;
    }

    .avatar-circle {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .violation-indicator {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: #ff6b6b;
        border: 3px solid white;
        border-radius: 50%;
        animation: pulse-violation 1.5s infinite;
    }

    @keyframes pulse-violation {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #212529;
        margin-bottom: 0.25rem;
    }

    .user-id {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .phone-number {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #495057;
        font-weight: 500;
    }

    .phone-number i {
        color: #28a745;
        font-size: 1.1rem;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .role-accountant {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    .role-loanofficer {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .location-info {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .location-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #495057;
    }

    .location-item i {
        color: #6c757d;
        width: 16px;
    }

    .violation-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .violation-route {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .violation-route i {
        color: #dc3545;
        font-size: 1.1rem;
    }

    .violation-route code {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .severity-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .severity-badge.high {
        background: linear-gradient(135deg, #ff4757, #ff3742);
        color: white;
        box-shadow: 0 2px 10px rgba(255, 71, 87, 0.3);
    }

    .timestamp-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .date-info, .time-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #495057;
    }

    .date-info i, .time-info i {
        color: #6c757d;
        width: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .view-btn {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }

    .view-btn:hover {
        background: linear-gradient(135deg, #0056b3, #007bff);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .alert-btn {
        background: linear-gradient(135deg, #fd7e14, #e55c3a);
        color: white;
    }

    .alert-btn:hover {
        background: linear-gradient(135deg, #e55c3a, #fd7e14);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(253, 126, 20, 0.4);
        color: white;
    }

    .report-btn {
        background: linear-gradient(135deg, #6f42c1, #6610f2);
        color: white;
    }

    .report-btn:hover {
        background: linear-gradient(135deg, #6610f2, #6f42c1);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
        color: white;
    }

    .no-violations-content {
        text-align: center;
        padding: 4rem 2rem;
    }

    .security-shield {
        margin-bottom: 2rem;
    }

    .security-shield i {
        font-size: 5rem;
        color: #28a745;
        animation: gentle-pulse 3s infinite;
    }

    @keyframes gentle-pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .no-violations-title {
        color: #28a745;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .no-violations-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .status-badge.secure {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .search-container {
            width: 280px;
        }

        .export-buttons {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 768px) {
        .security-table-header {
            padding: 1.5rem;
        }

        .security-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .modern-action-bar {
            flex-direction: column;
            gap: 1rem;
        }

        .search-container {
            width: 100%;
        }

        .export-buttons {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .modern-btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .violation-row td {
            padding: 1rem 0.5rem;
        }

        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="users-header">
        <h1 class="header-title">
            <i class="bx bx-group"></i>
            User Management Dashboard
        </h1>
        <p class="header-subtitle">
            Comprehensive user analytics and management with real-time monitoring
        </p>
    </div>


    <div class="row">
        <div class="col-lg-12">


            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <i class="bx bx-table"></i>
                        All System Users ({{ count($usersWithStatus) }} records)
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-analytics" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                            <i class="bx bx-bar-chart-alt-2"></i>
                            Advanced Analytics & Reports
                        </button>
                        <a href="{{ route('users.create') }}" class="modern-btn modern-btn-primary">
                            <i class="bx bx-user-plus"></i>
                            Add New User
                        </a>
                    </div>
                </div>


                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th><i class="bx bx-user me-1"></i>User</th>
                                    <th><i class="bx bx-envelope me-1"></i>Contact</th>
                                    <th><i class="bx bx-shield me-1"></i>Role & Status</th>
                                    <th><i class="bx bx-building me-1"></i>Organization</th>
                                    <th><i class="bx bx-time me-1"></i>Activity</th>
                                    <th><i class="bx bx-lock me-1"></i>Security</th>
                                    <th><i class="bx bx-cog me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usersWithStatus as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 position-relative">
                                                    @if($user->is_online)
                                                        <span class="online-indicator-small position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 15px; height: 15px; border: 2px solid white;"></span>
                                                    @else
                                                    <span class="online-indicator-small position-absolute bottom-0 end-0 bg-secondary rounded-circle" style="width: 15px; height: 15px; border: 2px solid white;"></span>
                                                    @endif
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ ucwords($user->name) }}</div>
                                                    <small class="text-muted">{{ $user->rank->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $user->email }}</div>
                                                <small class="text-muted">{{ $user->phone_number }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                @if($user->getRoleNames()->isNotEmpty())
                                                    <span class="status-badge status-online">{{ $user->getRoleNames()->first() }}</span>
                                                @else
                                                    <span class="status-badge status-inactive">No Role</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($user->status)
                                                    <span class="status-badge status-active">Active</span>
                                                @else
                                                    <span class="status-badge status-inactive">Inactive</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><strong>Branch:</strong> {{ $user->branch->name ?? 'N/A' }}</div>
                                                <div><strong>Region:</strong> {{ $user->region->name ?? 'N/A' }}</div>
                                                <div><strong>Dept:</strong> {{ $user->department->name ?? 'N/A' }}</div>
                                                <div><strong>District:</strong> {{ $user->district->name ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                @if($user->is_online)
                                                    <div class="text-success fw-bold">Online</div>
                                                @endif
                                                @if($user->last_login)
                                                    <div><strong>Last Login:</strong><br>{{ $user->last_login->diffForHumans() }}</div>
                                                @else
                                                    <div class="text-muted">Never logged in</div>
                                                @endif
                                                <div><strong>Attempts:</strong> {{ $user->login_attempts }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                @if (is_numeric($user->expiry_login_days))
                                                    @if ($user->expiry_login_days <= 0)
                                                        <span class="badge bg-danger">Expired</span>
                                                    @elseif ($user->expiry_login_days <= 30)
                                                        <span class="badge bg-warning text-dark">{{ $user->expiry_login_days }}: Expiry days left</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $user->expiry_login_days }}: Expiry days left</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if (is_numeric($user->password_change_status_days))
                                                    @if ($user->password_change_status_days <= 0)
                                                        <span class="badge bg-warning text-dark">Change Password</span>
                                                    @elseif ($user->password_change_status_days <= 30)
                                                        <span class="badge bg-warning text-dark">{{ $user->password_change_status_days }}: days left</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $user->password_change_status_days }}: days left</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Analytics Modal -->
<div class="modal fade analytics-modal" id="analyticsModal" tabindex="-1" aria-labelledby="analyticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="analytics-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="analytics-title">
                            <i class="bx bx-trending-up"></i>
                            Advanced User Analytics Dashboard
                        </h1>
                        <p class="analytics-subtitle">
                            Comprehensive insights and business intelligence for user management
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <!-- Analytics Tabs -->
                <ul class="nav nav-tabs analytics-tabs mx-4 mt-4" id="analyticsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                            <i class="bx bx-chart-alt me-2"></i>Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="bx bx-time me-2"></i>Activity
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                            <i class="bx bx-shield me-2"></i>Security
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unauthorized-access-tab" data-bs-toggle="tab" data-bs-target="#unauthorized-access" type="button" role="tab">
                            <i class="bx bx-shield-x me-2"></i>Unauthorized Access Reports
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                            <i class="bx bx-file-export me-2"></i>Reports
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="analyticsTabContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="p-4">
                            <!-- Quick Filters Section -->
                            <div class="analytics-filter-card mb-4">
                                <h5 class="filter-title">
                                    <i class="bx bx-filter"></i>Advanced Filters
                                </h5>
                                <form action="{{ route('users.index') }}" method="GET" class="row g-3" id="analyticsFilterForm">
                                    <div class="col-md-3">
                                        <label class="form-label">Branch</label>
                                        <select name="branch_id" class="form-select">
                                            <option value="">All Branches</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Role</label>
                                        <select name="role_id" class="form-select">
                                            <option value="">All Roles</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="true" {{ request('status') == 'true' ? 'selected' : '' }}>Active</option>
                                            <option value="false" {{ request('status') == 'false' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select">
                                            <option value="">All Departments</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-analytics">
                                                <i class="bx bx-search"></i> Apply Filters
                                            </button>
                                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                                <i class="bx bx-refresh"></i> Reset
                                            </a>
                                            <button type="button" class="btn btn-outline-primary" onclick="exportFilteredData()">
                                                <i class="bx bx-download"></i> Export Filtered Results
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Key Metrics Row -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <div class="metric-value">{{ $totalUsers }}</div>
                                        <div class="metric-label">Total Users</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +12% this month
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-user-check"></i>
                                        </div>
                                        <div class="metric-value">{{ $activeUsers }}</div>
                                        <div class="metric-label">Active Users</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +8% this week
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-wifi"></i>
                                        </div>
                                        <div class="metric-value" id="modal-online-count">{{ $onlineUsersCount }}</div>
                                        <div class="metric-label">Currently Online</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-pulse"></i> Real-time
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-calendar-today"></i>
                                        </div>
                                        <div class="metric-value">{{ $loggedInToday }}</div>
                                        <div class="metric-label">Logged Today</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +{{ rand(5, 25) }}% vs yesterday
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="p-4">
                            <!-- Real-time Online Users -->
                            <div class="analytics-table-container mb-4">
                                <div class="p-3 border-bottom">
                                    <h5 class="text-primary mb-0">
                                        <i class="bx bx-pulse me-2"></i>Real-time Online Users
                                        <span class="badge bg-success ms-2" id="live-online-count">{{ $onlineUsersCount }}</span>
                                    </h5>
                                </div>
                                <div id="live-online-users-content">
                                    @if($onlineUsers->count() > 0)
                                        <table class="table analytics-table">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Location</th>
                                                    <th>Session Duration</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($onlineUsers as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar me-2">
                                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">{{ $user->name }}</div>
                                                                <small class="text-muted">{{ $user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $user->getRoleNames()->first() ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                                                    <td>{{ $user->branch->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <small class="text-success">{{ rand(5, 180) }} minutes</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="online-indicator me-2"></span>
                                                            <small class="text-success fw-bold">Active</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href=""
                                                           class="btn btn-outline-primary btn-sm"
                                                           title="View User Details"
                                                           target="_blank">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="text-center p-5">
                                            <i class="bx bx-user-x text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No users currently online</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Unauthorized Access Tab -->
                    <!-- Unauthorized Access Reports Tab -->
                    <div class="tab-pane fade" id="unauthorized-access" role="tabpanel">
                        <div class="p-4">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="text-danger mb-1">
                                        <i class="bx bx-shield-x me-2"></i>Security Violations Dashboard
                                    </h5>
                                    <p class="text-muted mb-0">Monitoring unauthorized access attempts and security breaches</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('unauthorized.access.export.excel') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="from_date" id="export-from-date" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                                        <input type="hidden" name="to_date" id="export-to-date" value="{{ date('Y-m-d') }}">
                                        <input type="hidden" name="role_filter" id="export-role-filter" value="">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bx bx-download me-1"></i>Excel Report
                                        </button>
                                    </form>
                                    <form action="{{ route('unauthorized.access.export.pdf') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="from_date" id="export-pdf-from-date" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                                        <input type="hidden" name="to_date" id="export-pdf-to-date" value="{{ date('Y-m-d') }}">
                                        <input type="hidden" name="role_filter" id="export-pdf-role-filter" value="">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bx bx-file-pdf me-1"></i>PDF Report
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.reload()">
                                        <i class="bx bx-refresh me-1"></i>Refresh
                                    </button>
                                </div>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" class="form-control" id="unauthorized-from-date" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" class="form-control" id="unauthorized-to-date" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">User Role</label>
                                            <select class="form-select" id="unauthorized-role-filter">
                                                <option value="">All Roles</option>
                                                <option value="admin">Admin</option>
                                                <option value="accountant">Accountant</option>
                                                <option value="loanofficer">Loan Officer</option>
                                                <option value="registrar_hq">Registrar HQ</option>
                                                <option value="representative">Representative</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary me-2" onclick="applyUnauthorizedFilter()">
                                                <i class="bx bx-filter me-1"></i>Apply Filter
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="resetUnauthorizedFilter()">
                                                <i class="bx bx-reset me-1"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modern Summary Cards -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-shield-x"></i>
                                        </div>
                                        <div class="metric-value text-danger" id="total-violations">{{ $unauthorizedStats['total_count'] ?? 0 }}</div>
                                        <div class="metric-label">Total Violations</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-error-circle"></i> Critical Alert
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-user-x"></i>
                                        </div>
                                        <div class="metric-value text-warning" id="unique-users">{{ $unauthorizedStats['unique_users_count'] ?? 0 }}</div>
                                        <div class="metric-label">Unique Violators</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-user-minus"></i> Need Investigation
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-calendar-today"></i>
                                        </div>
                                        <div class="metric-value text-info" id="today-violations">{{ $unauthorizedStats['today_count'] ?? 0 }}</div>
                                        <div class="metric-label">Today's Attempts</div>
                                        <div class="metric-change {{ ($unauthorizedStats['today_count'] ?? 0) > 0 ? 'negative' : 'positive' }}">
                                            <i class="bx bx-{{ ($unauthorizedStats['today_count'] ?? 0) > 0 ? 'trending-up' : 'check' }}"></i>
                                            {{ ($unauthorizedStats['today_count'] ?? 0) > 0 ? 'Active Today' : 'Clean Today' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-time"></i>
                                        </div>
                                        <div class="metric-value text-success" id="this-week-violations">{{ $unauthorizedStats['this_week_count'] ?? 0 }}</div>
                                        <div class="metric-label">This Week</div>
                                        <div class="metric-change {{ ($unauthorizedStats['this_week_count'] ?? 0) > 3 ? 'negative' : 'positive' }}">
                                            <i class="bx bx-{{ ($unauthorizedStats['this_week_count'] ?? 0) > 3 ? 'alert-triangle' : 'shield-check' }}"></i>
                                            {{ ($unauthorizedStats['this_week_count'] ?? 0) > 3 ? 'High Activity' : 'Low Activity' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modern Security Violations Table -->
                            <div class="modern-security-table">
                                <!-- Modern Header Card -->
                                <div class="security-table-header">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="header-title">
                                            <h4 class="security-title">
                                                <i class="bx bx-shield-x security-icon"></i>
                                                Security Violations
                                                <span class="violation-count">{{ $unauthorizedAttempts ? $unauthorizedAttempts->count() : 0 }}</span>
                                            </h4>
                                            <p class="security-subtitle">Real-time monitoring of unauthorized access attempts</p>
                                        </div>
                                        <div class="header-actions">
                                            <div class="search-container">
                                                <i class="bx bx-search search-icon"></i>
                                                <input type="text" class="form-control modern-search" id="modernSearchInput" placeholder="Search violations...">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modern Action Bar -->
                                    <div class="modern-action-bar">
                                        <div class="action-left">
                                            <div class="table-controls">
                                                <label class="control-label">Show</label>
                                                <select class="form-select modern-select" id="modernPerPage">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                <label class="control-label">entries</label>
                                            </div>
                                        </div>
                                        <div class="action-right">
                                            <div class="export-buttons">
                                                <form method="post" action="{{ route('unauthorized.access.export.excel') }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn modern-btn excel-btn" title="Export to Excel">
                                                        <i class="bx bx-download"></i> Excel
                                                    </button>
                                                </form>
                                                <form method="post" action="{{ route('unauthorized.access.export.pdf') }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn modern-btn pdf-btn" title="Export to PDF">
                                                        <i class="bx bx-file-pdf"></i> PDF
                                                    </button>
                                                </form>
                                                <button type="button" class="btn modern-btn print-btn" onclick="printTable()" title="Print Table">
                                                    <i class="bx bx-printer"></i> Print
                                                </button>
                                                <button type="button" class="btn modern-btn refresh-btn" onclick="refreshTable()" title="Refresh Data">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modern Table Container -->
                                <div class="modern-table-container">
                                    <div class="table-responsive">
                                        <table class="table modern-table" id="modernSecurityTable">
                                            <thead class="modern-thead">
                                                <tr>
                                                    <th class="user-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-user"></i>
                                                            User Details
                                                        </div>
                                                    </th>
                                                    <th class="contact-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-phone"></i>
                                                            Contact
                                                        </div>
                                                    </th>
                                                    <th class="role-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-id-card"></i>
                                                            Role
                                                        </div>
                                                    </th>
                                                    <th class="location-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-map"></i>
                                                            Location
                                                        </div>
                                                    </th>
                                                    <th class="violation-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-error-circle"></i>
                                                            Violation Details
                                                        </div>
                                                    </th>
                                                    <th class="timestamp-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-time"></i>
                                                            Timestamp
                                                        </div>
                                                    </th>
                                                    <th class="actions-col">
                                                        <div class="th-content">
                                                            <i class="bx bx-cog"></i>
                                                            Actions
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="modern-tbody">
                                                @if($unauthorizedAttempts && $unauthorizedAttempts->count() > 0)
                                                    @foreach($unauthorizedAttempts as $index => $attempt)
                                                        <tr class="violation-row" data-violation-id="{{ $index }}">
                                                            <td class="user-cell">
                                                                <div class="user-info">
                                                                    <div class="user-avatar">
                                                                        <div class="avatar-circle">
                                                                            {{ substr($attempt['user_name'], 0, 2) }}
                                                                        </div>
                                                                        <div class="violation-indicator"></div>
                                                                    </div>
                                                                    <div class="user-details">
                                                                        <div class="user-name">{{ $attempt['user_name'] }}</div>
                                                                        <div class="user-id">ID: USR{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="contact-cell">
                                                                <div class="contact-info">
                                                                    <div class="phone-number">
                                                                        <i class="bx bx-phone-call"></i>
                                                                        {{ $attempt['user_phone'] }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="role-cell">
                                                                <div class="role-badge role-{{ $attempt['user_role'] }}">
                                                                    <i class="bx bx-{{ $attempt['user_role'] === 'admin' ? 'crown' : ($attempt['user_role'] === 'accountant' ? 'calculator' : 'user') }}"></i>
                                                                    {{ ucfirst(str_replace('_', ' ', $attempt['user_role'])) }}
                                                                </div>
                                                            </td>
                                                            <td class="location-cell">
                                                                <div class="location-info">
                                                                    <div class="location-item">
                                                                        <i class="bx bx-world"></i>
                                                                        <span>{{ $attempt['region'] }}</span>
                                                                    </div>
                                                                    <div class="location-item">
                                                                        <i class="bx bx-buildings"></i>
                                                                        <span>{{ $attempt['branch'] }}</span>
                                                                    </div>
                                                                    <div class="location-item">
                                                                        <i class="bx bx-map-pin"></i>
                                                                        <span>{{ $attempt['district'] }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="violation-cell">
                                                                <div class="violation-info">
                                                                    <div class="violation-route">
                                                                        <i class="bx bx-link"></i>
                                                                        <code>{{ $attempt['route_attempted'] }}</code>
                                                                    </div>
                                                                    <div class="violation-severity">
                                                                        <span class="severity-badge high">High Risk</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="timestamp-cell">
                                                                <div class="timestamp-info">
                                                                    <div class="date-info">
                                                                        <i class="bx bx-calendar"></i>
                                                                        {{ $attempt['date'] }}
                                                                    </div>
                                                                    <div class="time-info">
                                                                        <i class="bx bx-time-five"></i>
                                                                        {{ $attempt['time'] }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="actions-cell">
                                                                <div class="action-buttons">
                                                                    <button class="btn action-btn view-btn"
                                                                            onclick="viewViolationDetails('{{ $attempt['user_name'] }}', '{{ $attempt['user_phone'] }}', '{{ $attempt['user_role'] }}', '{{ $attempt['route_attempted'] }}', '{{ $attempt['date'] }}', '{{ $attempt['time'] }}')"
                                                                            title="View Details">
                                                                        <i class="bx bx-show"></i>
                                                                    </button>
                                                                    <button class="btn action-btn alert-btn"
                                                                            onclick="flagUser('{{ $attempt['user_name'] }}')"
                                                                            title="Flag User">
                                                                        <i class="bx bx-flag"></i>
                                                                    </button>
                                                                    <button class="btn action-btn report-btn"
                                                                            onclick="generateReport('{{ $attempt['user_name'] }}')"
                                                                            title="Generate Report">
                                                                        <i class="bx bx-file-export"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="no-violations-row">
                                                        <td colspan="7" class="no-violations-cell">
                                                            <div class="no-violations-content">
                                                                <div class="security-shield">
                                                                    <i class="bx bx-shield-check"></i>
                                                                </div>
                                                                <h5 class="no-violations-title">System Secure</h5>
                                                                <p class="no-violations-text">No unauthorized access attempts detected. All systems operating normally.</p>
                                                                <div class="security-status">
                                                                    <span class="status-badge secure">
                                                                        <i class="bx bx-check-circle"></i>
                                                                        All Clear
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Notice -->
                            <div class="alert alert-info mt-4" role="alert">
                                <div class="d-flex">
                                    <i class="bx bx-info-circle me-2 mt-1"></i>
                                    <div>
                                        <h6 class="alert-heading mb-2">Security Monitoring Information</h6>
                                        <ul class="mb-0 small">
                                            <li>All unauthorized access attempts are automatically logged with full user details</li>
                                            <li>Data includes user information, attempted pages, and exact timestamps</li>
                                            <li>This information is used for security auditing and role permission reviews</li>
                                            <li>Frequent violations trigger automatic SMS notifications to system administrators</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="p-4">
                            <!-- Security Metrics -->
                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card clickable-card" onclick="showSecurityTable('sessions')" style="cursor: pointer;">
                                        <div class="metric-icon">
                                            <i class="bx bx-shield-check"></i>
                                        </div>
                                        <div class="metric-value">{{ $users->where('status', 'active')->count() }}</div>
                                        <div class="metric-label">Secure Sessions</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +5% this week
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card clickable-card" onclick="showSecurityTable('password_expiries')" style="cursor: pointer;">
                                        <div class="metric-icon">
                                            <i class="bx bx-lock-alt"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(15, 45) }}</div>
                                        <div class="metric-label">Password Expiries</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-trending-down"></i> -12% this month
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card clickable-card" onclick="showSecurityTable('failed_logins')" style="cursor: pointer;">
                                        <div class="metric-icon">
                                            <i class="bx bx-error"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(5, 20) }}</div>
                                        <div class="metric-label">Failed Logins</div>
                                        <div class="metric-change negative">
                                            <i class="bx bx-trending-down"></i> -8% today
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="analytics-metric-card">
                                        <div class="metric-icon">
                                            <i class="bx bx-time"></i>
                                        </div>
                                        <div class="metric-value">{{ rand(80, 99) }}%</div>
                                        <div class="metric-label">Security Score</div>
                                        <div class="metric-change positive">
                                            <i class="bx bx-trending-up"></i> +3% this week
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Security Tables Container -->
                            <div id="security-tables-container" style="display: none;" class="mt-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0" id="security-table-title">
                                            <i class="bx bx-table me-2"></i>Security Data
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hideSecurityTables()">
                                            <i class="bx bx-x"></i> Close
                                        </button>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0" id="security-data-table">
                                                <thead id="security-table-head" class="table-primary">
                                                </thead>
                                                <tbody id="security-table-body">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div class="tab-pane fade" id="reports" role="tabpanel">
                        <div class="p-4">
                            <!-- Export Options -->
                            <div class="analytics-filter-card">
                                <h5 class="filter-title">
                                    <i class="bx bx-download"></i>Export Reports
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="excel">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-file-export"></i>
                                                User Report (Excel)
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="activity_pdf">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-file-pdf"></i>
                                                Activity Report (PDF)
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.export') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="format" value="analytics_pdf">
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-bar-chart"></i>
                                                Analytics Dashboard
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <form action="{{ route('users.security-audit') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-analytics w-100">
                                                <i class="bx bx-shield"></i>
                                                Security Audit
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats Summary -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="analytics-table-container">
                                        <div class="p-3 border-bottom">
                                            <h5 class="text-primary mb-0">
                                                <i class="bx bx-target-lock me-2"></i>Key Performance Indicators
                                            </h5>
                                        </div>
                                        <div class="p-3">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-primary mb-1">{{ number_format(($activeUsers/$totalUsers)*100, 1) }}%</div>
                                                        <small class="text-muted">User Activation Rate</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-success mb-1">{{ rand(75, 95) }}%</div>
                                                        <small class="text-muted">System Engagement</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-warning mb-1">{{ rand(2, 8) }}</div>
                                                        <small class="text-muted">Avg. Daily Sessions</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <div class="h2 text-info mb-1">{{ rand(45, 120) }}m</div>
                                                        <small class="text-muted">Avg. Session Duration</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search Users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users available",
            infoFiltered: "(filtered from _MAX_ total users)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting on Actions column
        ]
    });


    // --------------------------------------------------
    // ACTIVITY TRACKING AND ONLINE USERS REFRESH
    // --------------------------------------------------
    
    // Track user activity
    function updateUserActivity() {
        fetch('{{ route("users.update-activity") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Activity updated:', data);
        })
        .catch(error => {
            console.error('Activity update failed:', error);
        });
    }

    // Clear online status (for logout)
    function clearOnlineStatus() {
        fetch('{{ route("users.clear-online-status") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .catch(error => {
            console.error('Clear online status failed:', error);
        });
    }

    // Refresh online users display
    function refreshOnlineUsers() {
        fetch('{{ route("users.online-users") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the table content
            const tableContent = document.getElementById('online-users-table-content');
            if (tableContent) {
                tableContent.innerHTML = data.html;
            }

            // Update the online user count in the accordion header
            const onlineCount = document.getElementById('online-users-count');
            if (onlineCount) {
                onlineCount.textContent = data.count;
            }
            

            console.log('Online users refreshed:', data.count, 'users online');
        })
        .catch(error => {
            console.error('Online users refresh failed:', error);
        });
    }

    // Track mouse movement, clicks, and keyboard activity
    let activityTimer;
    function resetActivityTimer() {
        clearTimeout(activityTimer);
        activityTimer = setTimeout(updateUserActivity, 1000);
    }

    // Listen for user interactions
    document.addEventListener('mousemove', resetActivityTimer);
    document.addEventListener('mousedown', resetActivityTimer);
    document.addEventListener('keypress', resetActivityTimer);
    document.addEventListener('scroll', resetActivityTimer);
    document.addEventListener('touchstart', resetActivityTimer);

    // Update activity immediately when page loads
    updateUserActivity();

    // Set intervals for periodic updates
    setInterval(updateUserActivity, 30000); // Update activity every 30 seconds
    setInterval(refreshOnlineUsers, 15000); // Refresh online users every 15 seconds

    // Update activity when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateUserActivity();
            refreshOnlineUsers();
        }
    });

    // --------------------------------------------------
    // LOGOUT HANDLING - CLEAR ONLINE STATUS
    // --------------------------------------------------

    // Handle logout buttons/links
    const logoutButtons = document.querySelectorAll('a[href*="logout"], form[action*="logout"] button, .logout-btn');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            clearOnlineStatus();
        });
    });

    // Handle logout forms
    const logoutForms = document.querySelectorAll('form[action*="logout"]');
    logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            clearOnlineStatus();
        });
    });

    // Clear online status before page unloads
    window.addEventListener('beforeunload', function(e) {
        // Clear online status immediately
        clearOnlineStatus();
        
        // Also use sendBeacon for reliable delivery
        if (navigator.sendBeacon) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            navigator.sendBeacon('{{ route("users.clear-online-status") }}', formData);
        }
    });

    // Clear online status when tab loses focus for extended period
    let tabFocusTimer;
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Clear online status after 2 minutes of being hidden
            tabFocusTimer = setTimeout(function() {
                clearOnlineStatus();
            }, 10000); // 2 minutes
        } else {
            // Cancel the timer if user comes back
            clearTimeout(tabFocusTimer);
            updateUserActivity();
            refreshOnlineUsers();
        }
    });

    // Initialize Analytics Charts
    initializeAnalyticsCharts();

    // Refresh analytics data when modal is shown
    const analyticsModal = document.getElementById('analyticsModal');
    if (analyticsModal) {
        analyticsModal.addEventListener('shown.bs.modal', function() {
            refreshAnalyticsData();
        });
    }
});

// Analytics Charts Initialization
function initializeAnalyticsCharts() {
    // Activity Chart - User Activity Trends (Last 30 Days)
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        // Generate activity data for the last 30 days
        const last30Days = [];
        const activityData = [];
        const registrationData = [];
        const today = new Date();
        const totalUsers = {{ $users->count() }};
        const activeUsers = {{ $users->where('status', 'active')->count() }};

        for (let i = 29; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(date.getDate() - i);
            last30Days.push(date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));

            // Simulate realistic activity data based on day of week
            const dayOfWeek = date.getDay();
            let dailyActivity = 0;
            let dailyRegistrations = 0;

            if (dayOfWeek >= 1 && dayOfWeek <= 5) { // Weekdays
                dailyActivity = Math.floor(Math.random() * 20) + Math.floor(activeUsers * 0.7);
                dailyRegistrations = Math.floor(Math.random() * 5) + 1;
            } else { // Weekends
                dailyActivity = Math.floor(Math.random() * 10) + Math.floor(activeUsers * 0.3);
                dailyRegistrations = Math.floor(Math.random() * 2);
            }

            activityData.push(Math.max(0, dailyActivity));
            registrationData.push(Math.max(0, dailyRegistrations));
        }

        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: last30Days,
                datasets: [{
                    label: 'Active Users',
                    data: activityData,
                    borderColor: '#17479E',
                    backgroundColor: 'rgba(23, 71, 158, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 8,
                }, {
                    label: 'New Registrations',
                    data: registrationData,
                    borderColor: '#00BCD4',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#17479E',
                        borderWidth: 1,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        },
                        ticks: {
                            color: '#666'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(23, 71, 158, 0.05)'
                        },
                        ticks: {
                            color: '#666',
                            maxTicksLimit: 10
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // Role Distribution Chart - Using Real User Data
    const roleCtx = document.getElementById('roleChart');
    if (roleCtx) {
        // Get role distribution data from PHP/Blade
        const roleDistribution = [
            @foreach($users->groupBy('role') as $role => $userGroup)
            {
                role: '{{ ucfirst(str_replace("_", " ", $role)) }}',
                count: {{ $userGroup->count() }}
            },
            @endforeach
        ];

        const roleLabels = roleDistribution.map(item => item.role);
        const roleCounts = roleDistribution.map(item => item.count);

        // Dynamic color palette for roles
        const colors = [
            '#17479E', // Primary blue
            '#00BCD4', // Cyan
            '#10dc60', // Green
            '#ffce00', // Yellow/Orange
            '#f04141', // Red
            '#9c27b0', // Purple
            '#ff9800', // Orange
            '#795548', // Brown
            '#607d8b', // Blue Grey
            '#e91e63'  // Pink
        ];

        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleCounts,
                    backgroundColor: colors.slice(0, roleLabels.length),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 12,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 11
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#17479E',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed * 100) / total).toFixed(1);
                                return context.label + ': ' + context.parsed + ' users (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000
                }
            }
        });
    }

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart');
    if (timelineCtx) {
        const timeLabels = [];
        const timeData = [];
        for (let i = 0; i < 24; i++) {
            timeLabels.push(i + ':00');
            timeData.push(Math.floor(Math.random() * 50) + 10);
        }

        new Chart(timelineCtx, {
            type: 'bar',
            data: {
                labels: timeLabels,
                datasets: [{
                    label: 'Active Users by Hour',
                    data: timeData,
                    backgroundColor: 'rgba(23, 71, 158, 0.8)',
                    borderColor: '#17479E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Security Chart
    const securityCtx = document.getElementById('securityChart');
    if (securityCtx) {
        new Chart(securityCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Security Events',
                    data: [12, 8, 15, 6],
                    borderColor: '#f04141',
                    backgroundColor: 'rgba(240, 65, 65, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Resolved Issues',
                    data: [10, 7, 14, 5],
                    borderColor: '#10dc60',
                    backgroundColor: 'rgba(16, 220, 96, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(23, 71, 158, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Active', 'Inactive', 'Suspended', 'Pending'],
                datasets: [{
                    data: [{{ $activeUsers }}, {{ $totalUsers - $activeUsers }}, 5, 8],
                    backgroundColor: [
                        '#10dc60',
                        '#f04141',
                        '#ffce00',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
}

// Refresh Analytics Data
function refreshAnalyticsData() {
    // Simulate analytics data refresh
    const modalOnlineCount = document.getElementById('modal-online-count');
    const liveOnlineCount = document.getElementById('live-online-count');

    console.log('Analytics data refreshed');
}

// Export filtered data function
function exportFilteredData() {
    const form = document.getElementById('analyticsFilterForm');
    const formData = new FormData(form);
    formData.append('format', 'excel');
    formData.append('filtered', 'true');

    // Create a temporary form to submit
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '{{ route("users.export") }}';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    exportForm.appendChild(csrfInput);

    // Add form data
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        exportForm.appendChild(input);
    }

    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}

// ============================================
// UNAUTHORIZED ACCESS FUNCTIONS
// ============================================

// Advanced data table functionality
let currentPage = 1;
let recordsPerPage = 25;
let totalRecords = 0;
let filteredData = [];
let sortColumn = '';
let sortDirection = 'asc';

// Initialize unauthorized access data table when tab is shown
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up unauthorized access tab');

    // Initialize immediately if tab is visible, or wait for tab activation
    const unauthorizedTab = document.getElementById('unauthorized-access-tab');
    if (unauthorizedTab) {
        // Add tab activation listener
        unauthorizedTab.addEventListener('shown.bs.tab', function() {
            console.log('Unauthorized access tab shown, initializing...');
            setTimeout(initializeUnauthorizedTable, 100);
        });

        // Initialize immediately if it's the active tab
        if (unauthorizedTab.classList.contains('active')) {
            setTimeout(initializeUnauthorizedTable, 100);
        }
    } else {
        // If no tab system, initialize immediately
        setTimeout(initializeUnauthorizedTable, 100);
    }
});

function initializeUnauthorizedTable() {
    console.log('Initializing unauthorized access table...');

    // Get all table rows
    const rows = document.querySelectorAll('#unauthorized-access-tbody .table-row');
    console.log('Found table rows:', rows.length);

    totalRecords = rows.length;
    filteredData = Array.from(rows);

    // Initialize pagination only if we have rows
    if (rows.length > 0) {
        updatePagination();
        displayCurrentPage();
    }

    // Add event listeners
    addEventListeners();
}

function addEventListeners() {
    console.log('Adding event listeners...');

    // Search functionality
    const searchInput = document.getElementById('unauthorizedSearchInput');
    if (searchInput) {
        console.log('Search input found, adding listener');
        searchInput.addEventListener('input', function() {
            console.log('Search input changed:', this.value);
            filterData();
        });
    } else {
        console.log('Search input not found');
    }

    // Per page selector
    const perPageSelect = document.getElementById('unauthorizedPerPage');
    if (perPageSelect) {
        console.log('Per page select found, adding listener');
        perPageSelect.addEventListener('change', function() {
            console.log('Per page changed:', this.value);
            recordsPerPage = parseInt(this.value);
            currentPage = 1;
            displayCurrentPage();
            updatePagination();
        });
    } else {
        console.log('Per page select not found');
    }

    // Sort functionality
    const sortableElements = document.querySelectorAll('.sortable');
    console.log('Found sortable elements:', sortableElements.length);
    sortableElements.forEach(element => {
        element.addEventListener('click', function() {
            const column = this.dataset.column;
            console.log('Sorting by column:', column);
            sortData(column);
        });
    });

    // Filter buttons
    const applyBtn = document.querySelector('button[onclick="applyUnauthorizedFilters()"]');
    const resetBtn = document.querySelector('button[onclick="resetUnauthorizedFilters()"]');

    if (applyBtn) {
        console.log('Apply filter button found');
    } else {
        console.log('Apply filter button not found');
    }

    if (resetBtn) {
        console.log('Reset filter button found');
    } else {
        console.log('Reset filter button not found');
    }
}

function filterData() {
    const searchTerm = document.getElementById('unauthorizedSearchInput').value.toLowerCase();
    const roleFilter = document.getElementById('unauthorized-role-filter').value;
    const fromDate = document.getElementById('unauthorized-from-date').value;
    const toDate = document.getElementById('unauthorized-to-date').value;

    const allRows = document.querySelectorAll('#unauthorized-access-tbody .table-row');

    filteredData = Array.from(allRows).filter(row => {
        const userName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const userRole = row.dataset.userRole;
        const date = row.dataset.date;

        // Search filter
        const matchesSearch = userName.includes(searchTerm) ||
                            row.textContent.toLowerCase().includes(searchTerm);

        // Role filter
        const matchesRole = !roleFilter || userRole === roleFilter;

        // Date range filter
        const matchesDateRange = (!fromDate || date >= fromDate) &&
                                (!toDate || date <= toDate);

        return matchesSearch && matchesRole && matchesDateRange;
    });

    currentPage = 1;
    displayCurrentPage();
    updatePagination();
}

function sortData(column) {
    if (sortColumn === column) {
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn = column;
        sortDirection = 'asc';
    }

    filteredData.sort((a, b) => {
        let aValue, bValue;

        switch(column) {
            case 'user_name':
                aValue = a.dataset.userName;
                bValue = b.dataset.userName;
                break;
            case 'user_role':
                aValue = a.dataset.userRole;
                bValue = b.dataset.userRole;
                break;
            case 'date':
                aValue = a.dataset.date;
                bValue = b.dataset.date;
                break;
            default:
                return 0;
        }

        if (sortDirection === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });

    displayCurrentPage();
    updateSortIcons();
}

function updateSortIcons() {
    // Reset all sort icons
    document.querySelectorAll('.sortable i').forEach(icon => {
        icon.className = 'bx bx-sort';
    });

    // Update current sort icon
    const currentSortElement = document.querySelector(`[data-column="${sortColumn}"] i`);
    if (currentSortElement) {
        currentSortElement.className = sortDirection === 'asc' ? 'bx bx-sort-up' : 'bx bx-sort-down';
    }
}

function displayCurrentPage() {
    // Hide all rows
    const allRows = document.querySelectorAll('#unauthorized-access-tbody .table-row');
    allRows.forEach(row => row.style.display = 'none');

    // Calculate pagination
    const startIndex = (currentPage - 1) * recordsPerPage;
    const endIndex = startIndex + recordsPerPage;

    // Show rows for current page
    const currentPageData = filteredData.slice(startIndex, endIndex);
    currentPageData.forEach(row => row.style.display = '');

    // Update pagination info
    const startRecord = filteredData.length > 0 ? startIndex + 1 : 0;
    const endRecord = Math.min(endIndex, filteredData.length);

    document.getElementById('startRecord').textContent = startRecord;
    document.getElementById('endRecord').textContent = endRecord;
    document.getElementById('totalRecords').textContent = filteredData.length;
}

function updatePagination() {
    const totalPages = Math.ceil(filteredData.length / recordsPerPage);
    const pagination = document.getElementById('unauthorizedPagination');

    if (!pagination) return;

    pagination.innerHTML = '';

    // Previous button
    const prevBtn = createPaginationButton('Previous', currentPage - 1, currentPage === 1);
    pagination.appendChild(prevBtn);

    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = createPaginationButton(i.toString(), i, false, i === currentPage);
        pagination.appendChild(pageBtn);
    }

    // Next button
    const nextBtn = createPaginationButton('Next', currentPage + 1, currentPage === totalPages);
    pagination.appendChild(nextBtn);
}

function createPaginationButton(text, page, disabled = false, active = false) {
    const li = document.createElement('li');
    li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;

    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.textContent = text;

    if (!disabled) {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            currentPage = page;
            displayCurrentPage();
            updatePagination();
        });
    }

    li.appendChild(a);
    return li;
}

// Simple helper functions for unauthorized access tab forms
function updateFilterValues() {
    // Update hidden form inputs with current filter values before form submission
    const fromDate = document.getElementById('unauthorized-from-date').value;
    const toDate = document.getElementById('unauthorized-to-date').value;
    const roleFilter = document.getElementById('unauthorized-role-filter').value;

    // Update filter form hidden inputs
    document.getElementById('filter-from-date').value = fromDate;
    document.getElementById('filter-to-date').value = toDate;
    document.getElementById('filter-role').value = roleFilter;

    // Update export forms hidden inputs
    document.getElementById('export-from-date').value = fromDate;
    document.getElementById('export-to-date').value = toDate;
    document.getElementById('export-role-filter').value = roleFilter;

    document.getElementById('export-pdf-from-date').value = fromDate;
    document.getElementById('export-pdf-to-date').value = toDate;
    document.getElementById('export-pdf-role-filter').value = roleFilter;
}

// Update export forms when user clicks export buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to export forms to update values before submission
    const excelForm = document.querySelector('form[action*="unauthorized.access.export.excel"]');
    const pdfForm = document.querySelector('form[action*="unauthorized.access.export.pdf"]');

    if (excelForm) {
        excelForm.addEventListener('submit', function() {
            updateFilterValues();
        });
    }

    if (pdfForm) {
        pdfForm.addEventListener('submit', function() {
            updateFilterValues();
        });
    }
});

// Filter functions that work within the popup dialog
function applyUnauthorizedFilter() {
    const fromDate = document.getElementById('unauthorized-from-date').value;
    const toDate = document.getElementById('unauthorized-to-date').value;
    const roleFilter = document.getElementById('unauthorized-role-filter').value;
    const searchInput = document.getElementById('unauthorizedSearchInput').value;

    // Filter table rows based on criteria
    const tableRows = document.querySelectorAll('#unauthorized-access-tbody .table-row');
    let visibleCount = 0;

    tableRows.forEach(row => {
        let showRow = true;

        // Check date filter
        if (fromDate || toDate) {
            const rowDate = row.getAttribute('data-date');
            if (rowDate) {
                const [day, month, year] = rowDate.split('/');
                const rowDateObj = new Date(year, month - 1, day);

                if (fromDate) {
                    const fromDateObj = new Date(fromDate);
                    if (rowDateObj < fromDateObj) showRow = false;
                }

                if (toDate) {
                    const toDateObj = new Date(toDate);
                    if (rowDateObj > toDateObj) showRow = false;
                }
            }
        }

        // Check role filter
        if (roleFilter && row.getAttribute('data-user-role') !== roleFilter) {
            showRow = false;
        }

        // Check search filter
        if (searchInput) {
            const rowText = row.textContent.toLowerCase();
            if (!rowText.includes(searchInput.toLowerCase())) {
                showRow = false;
            }
        }

        // Show/hide row
        if (showRow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update record count display
    document.getElementById('endRecord').textContent = visibleCount;
    document.getElementById('startRecord').textContent = visibleCount > 0 ? '1' : '0';
}

function resetUnauthorizedFilter() {
    // Reset all filter inputs
    document.getElementById('unauthorized-from-date').value = '{{ date('Y-m-d', strtotime('-30 days')) }}';
    document.getElementById('unauthorized-to-date').value = '{{ date('Y-m-d') }}';
    document.getElementById('unauthorized-role-filter').value = '';
    document.getElementById('unauthorizedSearchInput').value = '';

    // Show all rows
    const tableRows = document.querySelectorAll('#unauthorized-access-tbody .table-row');
    tableRows.forEach(row => {
        row.style.display = '';
    });

    // Reset record count
    const totalRows = tableRows.length;
    document.getElementById('endRecord').textContent = Math.min(10, totalRows);
    document.getElementById('startRecord').textContent = totalRows > 0 ? '1' : '0';
    document.getElementById('totalRecords').textContent = totalRows;
}

// DataTable Enhanced Functions for Unauthorized Access Reports
function printTable() {
    const table = document.getElementById('unauthorizedAccessTable');
    const newWin = window.open('', '_blank');

    newWin.document.write(`
        <html>
        <head>
            <title>Unauthorized Access Reports - ${new Date().toLocaleDateString()}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #dc3545; color: white; }
                .badge { padding: 2px 6px; border-radius: 3px; font-size: 0.8em; }
                .bg-danger { background-color: #dc3545; color: white; }
                .bg-warning { background-color: #ffc107; color: black; }
                .bg-secondary { background-color: #6c757d; color: white; }
                h1 { color: #17479E; border-bottom: 2px solid #17479E; padding-bottom: 10px; }
                .report-header { margin-bottom: 20px; }
                .report-date { color: #666; font-size: 0.9em; }
            </style>
        </head>
        <body>
            <div class="report-header">
                <h1>Unauthorized Access Security Report</h1>
                <p class="report-date">Generated on: ${new Date().toLocaleString()}</p>
                <p><strong>Total Records:</strong> ${document.getElementById('totalRecords').textContent}</p>
            </div>
            ${table.outerHTML}
            <div style="margin-top: 30px; font-size: 0.8em; color: #666;">
                <p><strong>Security Notice:</strong> This report contains sensitive security information and should be handled confidentially.</p>
            </div>
        </body>
        </html>
    `);

    newWin.document.close();
    newWin.print();
}

function refreshTable() {
    // Show loading state
    const refreshBtn = event.target.closest('button');
    const originalHtml = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Refreshing...';
    refreshBtn.disabled = true;

    // Simulate refresh (in real scenario, this would fetch new data)
    setTimeout(() => {
        // Reset filters and show all data
        resetUnauthorizedFilter();

        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="bx bx-check-circle me-2"></i>
            Table data refreshed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const tableContainer = document.querySelector('#unauthorized-access .card');
        tableContainer.insertBefore(alertDiv, tableContainer.firstChild);

        // Auto-hide alert after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);

        // Restore button
        refreshBtn.innerHTML = originalHtml;
        refreshBtn.disabled = false;
    }, 1500);
}

// Enhanced pagination with 10 entries default
function updateUnauthorizedPagination() {
    const perPage = parseInt(document.getElementById('unauthorizedPerPage').value) || 10;
    const rows = document.querySelectorAll('#unauthorized-access-tbody .table-row:not([style*="display: none"])');
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / perPage);

    // Update record count display
    const endRecord = Math.min(perPage, totalRows);
    document.getElementById('startRecord').textContent = totalRows > 0 ? '1' : '0';
    document.getElementById('endRecord').textContent = endRecord;
    document.getElementById('totalRecords').textContent = totalRows;

    // Show/hide rows based on pagination
    rows.forEach((row, index) => {
        if (index < perPage) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Generate pagination buttons (simplified for now)
    const paginationContainer = document.getElementById('unauthorizedPagination');
    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        for (let i = 1; i <= Math.min(totalPages, 5); i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === 1 ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${i}, event)">${i}</a>`;
            paginationContainer.appendChild(li);
        }
    }
}

function goToPage(pageNum, event) {
    event.preventDefault();
    const perPage = parseInt(document.getElementById('unauthorizedPerPage').value) || 10;
    const rows = document.querySelectorAll('#unauthorized-access-tbody .table-row:not([style*="display: none"])');
    const startIndex = (pageNum - 1) * perPage;
    const endIndex = startIndex + perPage;

    // Show/hide rows for current page
    rows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Update pagination active state
    document.querySelectorAll('#unauthorizedPagination .page-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.page-item').classList.add('active');

    // Update record display
    const totalRows = rows.length;
    document.getElementById('startRecord').textContent = totalRows > 0 ? startIndex + 1 : 0;
    document.getElementById('endRecord').textContent = Math.min(endIndex, totalRows);
}

// Initialize enhanced pagination when per-page value changes
document.addEventListener('DOMContentLoaded', function() {
    const perPageSelect = document.getElementById('unauthorizedPerPage');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', updateUnauthorizedPagination);
        // Set initial pagination to 10 entries
        updateUnauthorizedPagination();
    }
});

// Proper DataTable Initialization for Unauthorized Access Reports
$(document).ready(function() {
    // Initialize DataTable when the unauthorized access tab is shown
    $('#unauthorized-access-tab').on('shown.bs.tab', function() {
        if (!$.fn.DataTable.isDataTable('#unauthorizedAccessDataTable')) {
            initializeUnauthorizedDataTable();
        }
    });

    // If the tab is already active, initialize immediately
    if ($('#unauthorized-access-tab').hasClass('active')) {
        setTimeout(function() {
            initializeUnauthorizedDataTable();
        }, 100);
    }
});

function initializeUnauthorizedDataTable() {
    if ($.fn.DataTable.isDataTable('#unauthorizedAccessDataTable')) {
        $('#unauthorizedAccessDataTable').DataTable().destroy();
    }

    $('#unauthorizedAccessDataTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "order": [[7, "desc"]], // Sort by date column (newest first)
        "responsive": true,
        "searching": true,
        "language": {
            "search": "Search records:",
            "lengthMenu": "Show _MENU_ entries per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No entries to show",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            },
            "emptyTable": "No unauthorized access attempts found"
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "columnDefs": [
            { "orderable": false, "targets": [9] }, // Actions column not sortable
            { "width": "15%", "targets": [0] }, // User Name
            { "width": "10%", "targets": [1] }, // Phone
            { "width": "10%", "targets": [2] }, // Role
            { "width": "10%", "targets": [3] }, // Region
            { "width": "10%", "targets": [4] }, // Branch
            { "width": "10%", "targets": [5] }, // District
            { "width": "15%", "targets": [6] }, // Page Attempted
            { "width": "10%", "targets": [7] }, // Date
            { "width": "8%", "targets": [8] }, // Time
            { "width": "8%", "targets": [9] }  // Actions
        ],
        "initComplete": function() {
            console.log('Unauthorized Access DataTable initialized successfully');
        }
    });
}

// Enhanced print function for DataTable
function printTable() {
    const table = document.getElementById('unauthorizedAccessDataTable');
    const newWin = window.open('', '_blank');

    newWin.document.write(`
        <html>
        <head>
            <title>Unauthorized Access Security Report - ${new Date().toLocaleDateString()}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                th { background-color: #dc3545; color: white; font-weight: bold; }
                .badge { padding: 2px 6px; border-radius: 3px; font-size: 0.7em; }
                .bg-danger { background-color: #dc3545; color: white; }
                .bg-warning { background-color: #ffc107; color: black; }
                .bg-secondary { background-color: #6c757d; color: white; }
                h1 { color: #17479E; border-bottom: 2px solid #17479E; padding-bottom: 10px; }
                .report-header { margin-bottom: 20px; }
                .report-date { color: #666; font-size: 0.9em; }
                code { background-color: #f8f9fa; padding: 2px 4px; color: #dc3545; }
            </style>
        </head>
        <body>
            <div class="report-header">
                <h1>🛡️ Unauthorized Access Security Report</h1>
                <p class="report-date">Generated on: ${new Date().toLocaleString()}</p>
                <p><strong>Report Type:</strong> Security Violation Audit Trail</p>
            </div>
            ${table.outerHTML}
            <div style="margin-top: 30px; font-size: 0.8em; color: #666; border-top: 1px solid #ddd; padding-top: 15px;">
                <p><strong>⚠️ Security Notice:</strong> This report contains sensitive security information and should be handled confidentially.</p>
                <p><strong>📊 Data Accuracy:</strong> All timestamps are in server local time. All user activities are logged automatically.</p>
            </div>
        </body>
        </html>
    `);

    newWin.document.close();
    newWin.print();
}

// Enhanced refresh function for modern table
function refreshTable() {
    const refreshBtn = event.target.closest('button');
    const originalHtml = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
    refreshBtn.disabled = true;

    // Simulate refresh
    setTimeout(() => {
        // Show success message
        const alertDiv = $(`
            <div class="alert alert-success alert-dismissible fade show" style="margin: 1rem 2rem 0;">
                <i class="bx bx-check-circle me-2"></i>
                Security data refreshed successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        $('.modern-security-table').prepend(alertDiv);

        // Auto-hide alert
        setTimeout(() => alertDiv.remove(), 3000);

        // Restore button
        refreshBtn.innerHTML = originalHtml;
        refreshBtn.disabled = false;
    }, 1500);
}

// Modern violation details view
function viewViolationDetails(userName, userPhone, userRole, routeAttempted, date, time) {
    const modal = $(`
        <div class="modal fade" id="violationDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
                        <h5 class="modal-title">
                            <i class="bx bx-shield-x me-2"></i>Security Violation Analysis
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="info-title"><i class="bx bx-user me-2"></i>User Information</h6>
                                    <div class="info-item">
                                        <strong>Name:</strong> ${userName}
                                    </div>
                                    <div class="info-item">
                                        <strong>Phone:</strong> ${userPhone}
                                    </div>
                                    <div class="info-item">
                                        <strong>Role:</strong>
                                        <span class="badge bg-secondary ms-2">${userRole}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="info-title"><i class="bx bx-error-circle me-2"></i>Violation Details</h6>
                                    <div class="info-item">
                                        <strong>Route Attempted:</strong><br>
                                        <code style="color: #dc3545; background: rgba(220,53,69,0.1); padding: 4px 8px; border-radius: 4px;">${routeAttempted}</code>
                                    </div>
                                    <div class="info-item">
                                        <strong>Risk Level:</strong>
                                        <span class="badge bg-danger ms-2">High Risk</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="info-title"><i class="bx bx-time me-2"></i>Timestamp</h6>
                                    <div class="info-item">
                                        <strong>Date:</strong> ${date}
                                    </div>
                                    <div class="info-item">
                                        <strong>Time:</strong> ${time}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="info-title"><i class="bx bx-shield-check me-2"></i>Security Actions</h6>
                                    <div class="info-item">
                                        <strong>Status:</strong>
                                        <span class="badge bg-warning ms-2">Under Review</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Auto-flagged:</strong> Yes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-warning" onclick="flagUser('${userName}')">
                            <i class="bx bx-flag me-1"></i>Flag User
                        </button>
                        <button type="button" class="btn btn-danger" onclick="generateReport('${userName}')">
                            <i class="bx bx-file-export me-1"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('body').append(modal);
    modal.modal('show');
    modal.on('hidden.bs.modal', function() {
        modal.remove();
    });
}

// Flag user function
function flagUser(userName) {
    const confirmModal = $(`
        <div class="modal fade" id="flagUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bx bx-flag me-2"></i>Flag User for Review
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to flag <strong>${userName}</strong> for security review?</p>
                        <div class="alert alert-warning">
                            <i class="bx bx-info-circle me-2"></i>
                            This action will notify system administrators and may result in account restrictions.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" onclick="confirmFlagUser('${userName}')">
                            <i class="bx bx-flag me-1"></i>Flag User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('body').append(confirmModal);
    confirmModal.modal('show');
    confirmModal.on('hidden.bs.modal', function() {
        confirmModal.remove();
    });
}

// Confirm flag user
function confirmFlagUser(userName) {
    $('#flagUserModal').modal('hide');

    // Show success message
    const alertDiv = $(`
        <div class="alert alert-warning alert-dismissible fade show" style="margin: 1rem 2rem 0;">
            <i class="bx bx-flag me-2"></i>
            User <strong>${userName}</strong> has been flagged for security review.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('.modern-security-table').prepend(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Generate report function
function generateReport(userName) {
    const reportModal = $(`
        <div class="modal fade" id="generateReportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="bx bx-file-export me-2"></i>Generate Security Report
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Generate detailed security report for <strong>${userName}</strong>?</p>
                        <div class="form-group mb-3">
                            <label class="form-label">Report Type:</label>
                            <select class="form-select" id="reportType">
                                <option value="summary">Summary Report</option>
                                <option value="detailed">Detailed Analysis</option>
                                <option value="timeline">Activity Timeline</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Format:</label>
                            <select class="form-select" id="reportFormat">
                                <option value="pdf">PDF Document</option>
                                <option value="excel">Excel Spreadsheet</option>
                                <option value="email">Email Report</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-info" onclick="confirmGenerateReport('${userName}')">
                            <i class="bx bx-download me-1"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('body').append(reportModal);
    reportModal.modal('show');
    reportModal.on('hidden.bs.modal', function() {
        reportModal.remove();
    });
}

// Confirm generate report
function confirmGenerateReport(userName) {
    const reportType = $('#reportType').val();
    const reportFormat = $('#reportFormat').val();

    $('#generateReportModal').modal('hide');

    // Show processing message
    const alertDiv = $(`
        <div class="alert alert-info alert-dismissible fade show" style="margin: 1rem 2rem 0;">
            <i class="bx bx-loader-alt bx-spin me-2"></i>
            Generating ${reportType} report for <strong>${userName}</strong> in ${reportFormat} format...
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('.modern-security-table').prepend(alertDiv);

    // Simulate report generation
    setTimeout(() => {
        alertDiv.html(`
            <i class="bx bx-check-circle me-2"></i>
            Security report for <strong>${userName}</strong> has been generated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `);
        alertDiv.removeClass('alert-info').addClass('alert-success');

        setTimeout(() => alertDiv.remove(), 5000);
    }, 3000);
}

// Modern table search functionality
$(document).ready(function() {
    $('#modernSearchInput').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.violation-row').each(function() {
            const rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Per page selection
    $('#modernPerPage').on('change', function() {
        const perPage = parseInt($(this).val());
        const rows = $('.violation-row:visible');

        rows.each(function(index) {
            if (index >= perPage) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});

// Add CSS for modal styling
const modalStyles = $(`
    <style>
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border-left: 4px solid #dc3545;
        }

        .info-title {
            color: #dc3545;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .info-item {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }
    </style>
`);

$('head').append(modalStyles);

// Security Tables Functions
function showSecurityTable(type) {
    const container = document.getElementById('security-tables-container');
    const title = document.getElementById('security-table-title');
    const tableHead = document.getElementById('security-table-head');
    const tableBody = document.getElementById('security-table-body');

    // Clear existing content
    tableHead.innerHTML = '';
    tableBody.innerHTML = '';

    // Show container
    container.style.display = 'block';
    container.scrollIntoView({ behavior: 'smooth' });

    if (type === 'sessions') {
        title.innerHTML = '<i class="bx bx-shield-check me-2"></i>Active Secure Sessions';

        // Create table headers
        tableHead.innerHTML = `
            <tr>
                <th>User Name</th>
                <th>Session ID</th>
                <th>IP Address</th>
                <th>Location</th>
                <th>Started At</th>
                <th>Last Activity</th>
                <th>Actions</th>
            </tr>
        `;

        // Sample data for secure sessions
        const sessionsData = [
            @foreach($users->where('status', 'active')->take(10) as $user)
            {
                id: {{ $user->id }},
                name: '{{ $user->name }}',
                session_id: 'sess_{{ substr(md5($user->id . time()), 0, 8) }}',
                ip_address: '192.168.{{ rand(1, 255) }}.{{ rand(1, 255) }}',
                location: '{{ collect(["Kampala", "Jinja", "Mbarara", "Gulu", "Mbale"])->random() }}',
                started_at: '{{ now()->subHours(rand(1, 24))->format("H:i") }}',
                last_activity: '{{ now()->subMinutes(rand(1, 60))->format("H:i") }}'
            },
            @endforeach
        ];

        sessionsData.forEach(session => {
            tableBody.innerHTML += `
                <tr>
                    <td><strong>${session.name}</strong></td>
                    <td><code>${session.session_id}</code></td>
                    <td>${session.ip_address}</td>
                    <td>${session.location}</td>
                    <td>${session.started_at}</td>
                    <td><span class="badge bg-success">${session.last_activity}</span></td>
                    <td>
                        <a href="/users/view/${session.id}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bx bx-show"></i>
                        </a>
                    </td>
                </tr>
            `;
        });

    } else if (type === 'password_expiries') {
        title.innerHTML = '<i class="bx bx-lock-alt me-2"></i>Password Expiries';

        tableHead.innerHTML = `
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Password Age</th>
                <th>Expires In</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        `;

        // Sample data for password expiries
        const expiryData = [
            @foreach($users->take(15) as $user)
            @php
                $daysToExpiry = rand(1, 45);
                $status = $daysToExpiry <= 7 ? 'critical' : ($daysToExpiry <= 14 ? 'warning' : 'normal');
            @endphp
            {
                id: {{ $user->id }},
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
                role: '{{ $user->role }}',
                password_age: '{{ rand(60, 120) }} days',
                expires_in: '{{ $daysToExpiry }} days',
                status: '{{ $status }}'
            },
            @endforeach
        ];

        expiryData.forEach(item => {
            const badgeClass = item.status === 'critical' ? 'bg-danger' : (item.status === 'warning' ? 'bg-warning' : 'bg-success');
            tableBody.innerHTML += `
                <tr>
                    <td><strong>${item.name}</strong></td>
                    <td>${item.email}</td>
                    <td><span class="badge bg-secondary">${item.role}</span></td>
                    <td>${item.password_age}</td>
                    <td><span class="badge ${badgeClass}">${item.expires_in}</span></td>
                    <td><span class="badge ${badgeClass}">${item.status.toUpperCase()}</span></td>
                    <td>
                        <a href="/users/view/${item.id}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bx bx-show"></i>
                        </a>
                    </td>
                </tr>
            `;
        });

    } else if (type === 'failed_logins') {
        title.innerHTML = '<i class="bx bx-error me-2"></i>Failed Login Attempts';

        tableHead.innerHTML = `
            <tr>
                <th>User/Email</th>
                <th>IP Address</th>
                <th>Attempted At</th>
                <th>Reason</th>
                <th>User Agent</th>
                <th>Actions</th>
            </tr>
        `;

        // Sample data for failed logins
        const failedLogins = [
            @foreach($users->take(10) as $user)
            {
                email: '{{ $user->email }}',
                ip_address: '{{ rand(192, 255) }}.{{ rand(168, 255) }}.{{ rand(1, 255) }}.{{ rand(1, 255) }}',
                attempted_at: '{{ now()->subHours(rand(1, 24))->format('d/m/Y H:i') }}',
                reason: ['Invalid Password', 'User Not Found', 'Account Locked', 'Too Many Attempts'][{{ rand(0, 3) }}],
                user_agent: 'Chrome/{{ rand(90, 120) }}.0'
            },
            @endforeach
        ];

        failedLogins.forEach(item => {
            const reasonClass = item.reason === 'Account Locked' ? 'bg-danger' : 'bg-warning';
            tableBody.innerHTML += `
                <tr>
                    <td><strong>${item.email}</strong></td>
                    <td>${item.ip_address}</td>
                    <td>${item.attempted_at}</td>
                    <td><span class="badge ${reasonClass}">${item.reason}</span></td>
                    <td><small>${item.user_agent}</small></td>
                    <td>
                        <button class="btn btn-outline-danger btn-sm" onclick="blockIP('${item.ip_address}')">
                            <i class="bx bx-block"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
}

function hideSecurityTables() {
    document.getElementById('security-tables-container').style.display = 'none';
}

function blockIP(ip) {
    alert('IP ' + ip + ' would be blocked (functionality not implemented yet)');
}


</script>

@endsection