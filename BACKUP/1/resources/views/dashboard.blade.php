@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    :root {
        --ura-primary: #17479E;
        --ura-primary-dark: #1e3c72;
        --ura-primary-light: #2a5298;
        --ura-accent: #00BCD4;
        --ura-success: #15ca20;
        --ura-warning: #fd7e14;
        --ura-danger: #d63384;
        --ura-info: #0dcaf0;
        --ura-purple: #6f42c1;
    }

    /* Modern KPI Card Styling with URA SACCOS Animations */
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, rgba(23, 71, 158, 0.04) 50%, rgba(0, 188, 212, 0.04) 100%);
        border-radius: 24px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        box-shadow: 0 15px 40px rgba(23, 71, 158, 0.12), inset 0 1px 0 rgba(255,255,255,0.9);
        animation: fadeInUp 0.8s ease-out backwards;
        backdrop-filter: blur(10px);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.9) rotateX(10deg);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1) rotateX(0);
        }
    }
    
    /* Animated shimmer effect */
    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -75%;
        width: 50%;
        height: 200%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
        pointer-events: none;
    }
    
    @keyframes shimmer {
        0% { left: -75%; }
        100% { left: 125%; }
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 188, 212, 0.15) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(23, 71, 158, 0.15) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02) rotateX(5deg);
        box-shadow: 0 20px 40px rgba(23, 71, 158, 0.2);
        border-color: rgba(23, 71, 158, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, rgba(23, 71, 158, 0.06) 50%, rgba(0, 188, 212, 0.06) 100%);
    }

    /* Staggered animation delays */
    .row > div:nth-child(1) .stat-card { animation-delay: 0.1s; }
    .row > div:nth-child(2) .stat-card { animation-delay: 0.2s; }
    .row > div:nth-child(3) .stat-card { animation-delay: 0.3s; }
    .row > div:nth-child(4) .stat-card { animation-delay: 0.4s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
        box-shadow: 0 4px 15px rgba(23, 71, 158, 0.3);
        position: relative;
        animation: iconBounce 2s ease-in-out infinite;
        transition: all 0.4s ease;
    }

    @keyframes iconBounce {
        0%, 100% { 
            transform: translateY(0) rotate(0deg);
        }
        25% { 
            transform: translateY(-2px) rotate(2deg);
        }
        75% { 
            transform: translateY(2px) rotate(-2deg);
        }
    }

    .stat-card:hover .stat-icon {
        transform: rotate(360deg) scale(1.15);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.4);
        background: linear-gradient(135deg, var(--ura-accent) 0%, var(--ura-primary) 100%);
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--ura-primary-dark);
        margin: 8px 0;
    }

    .stat-label {
        font-size: 13px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stat-change {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .stat-change.positive {
        background: rgba(21, 202, 32, 0.1);
        color: var(--ura-success);
    }

    .stat-change.negative {
        background: rgba(214, 51, 132, 0.1);
        color: var(--ura-danger);
    }

    /* Advanced floating gradient orbs for KPI cards */
    .floating-orb {
        position: absolute;
        border-radius: 50%;
        opacity: 0.3;
        filter: blur(40px);
        pointer-events: none;
        z-index: 1;
    }
    
    .orb-primary {
        width: 120px;
        height: 120px;
        background: radial-gradient(circle at 30% 30%, var(--ura-primary) 0%, rgba(23, 71, 158, 0.3) 40%, transparent 70%);
        top: -30px;
        right: -30px;
        animation: morphOrb 15s ease-in-out infinite;
    }
    
    .orb-accent {
        width: 80px;
        height: 80px;
        background: radial-gradient(circle at 70% 70%, var(--ura-accent) 0%, rgba(0, 188, 212, 0.3) 40%, transparent 70%);
        bottom: -20px;
        left: -20px;
        animation: morphOrb2 18s ease-in-out infinite;
    }
    
    @keyframes morphOrb {
        0%, 100% { 
            transform: translate(0, 0) scale(1) rotate(0deg);
            filter: blur(40px) hue-rotate(0deg);
        }
        25% { 
            transform: translate(-20px, 10px) scale(1.2) rotate(90deg);
            filter: blur(35px) hue-rotate(20deg);
        }
        50% { 
            transform: translate(10px, -20px) scale(0.8) rotate(180deg);
            filter: blur(45px) hue-rotate(-20deg);
        }
        75% { 
            transform: translate(-10px, -10px) scale(1.1) rotate(270deg);
            filter: blur(38px) hue-rotate(10deg);
        }
    }
    
    @keyframes morphOrb2 {
        0%, 100% { 
            transform: translate(0, 0) scale(1) rotate(0deg);
            filter: blur(40px) saturate(1);
        }
        33% { 
            transform: translate(15px, -15px) scale(1.15) rotate(120deg);
            filter: blur(35px) saturate(1.2);
        }
        66% { 
            transform: translate(-10px, 10px) scale(0.85) rotate(240deg);
            filter: blur(45px) saturate(0.8);
        }
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(23, 71, 158, 0.08);
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 8px 24px rgba(23, 71, 158, 0.12);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f3f5;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--ura-primary-dark);
        margin: 0;
    }

    .chart-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    /* KPI Section */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .kpi-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(23, 71, 158, 0.12);
        border-color: var(--ura-accent);
    }

    .kpi-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .kpi-card:hover::after {
        transform: scaleX(1);
    }

    /* Data Table Styling */
    .data-table-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(23, 71, 158, 0.08);
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-primary-light) 100%);
        color: white;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border: none;
    }

    .table-modern thead th:first-child {
        border-top-left-radius: 8px;
    }

    .table-modern thead th:last-child {
        border-top-right-radius: 8px;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: rgba(0, 188, 212, 0.05);
        transform: scale(1.01);
    }

    .table-modern tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f5;
        font-size: 14px;
    }

    /* Badge Styling */
    .badge-modern {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Compact Pipeline Design */
    .pipeline-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(23, 71, 158, 0.08);
        padding: 1.5rem;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .pipeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    }

    .pipeline-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .pipeline-title {
        margin: 0;
        color: var(--ura-primary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .text-ura-primary {
        color: var(--ura-primary);
    }

    .pipeline-badge {
        background: linear-gradient(135deg, #00BCD4 0%, #17479E 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }

    .pipeline-controls {
        display: flex;
        gap: 0.5rem;
    }

    .btn-ura-outline {
        background: white;
        border: 1px solid rgba(23, 71, 158, 0.2);
        color: var(--ura-primary);
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-ura-outline:hover,
    .btn-ura-outline.active {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-1px);
    }

    .pipeline-stats-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(23, 71, 158, 0.03);
        border-radius: 12px;
    }

    .stat-pill {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        background: white;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.15);
    }

    .stat-pill.total {
        background: linear-gradient(135deg, #17479E 0%, #2a5298 100%);
        color: white;
    }

    .stat-pill.processing {
        background: linear-gradient(135deg, #00BCD4 0%, #00acc1 100%);
        color: white;
    }

    .stat-pill.waiting {
        background: linear-gradient(135deg, #FFC107 0%, #ffb300 100%);
        color: white;
    }

    .stat-pill.success-rate {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .stat-pill-icon {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .stat-pill-content {
        display: flex;
        flex-direction: column;
    }

    .stat-pill-value {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-pill-label {
        font-size: 0.75rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }

    .pipeline-flow-arrow {
        color: rgba(23, 71, 158, 0.3);
        font-size: 1.5rem;
    }

    .pipeline-content {
        min-height: 200px;
    }

    .content-view {
        display: none;
    }

    .content-view.active {
        display: block;
    }

    .status-toggles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .status-toggle {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .status-toggle:hover {
        border-color: var(--ura-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .status-toggle.active {
        border-color: var(--ura-accent);
        background: linear-gradient(135deg, rgba(0, 188, 212, 0.05) 0%, rgba(23, 71, 158, 0.05) 100%);
    }

    .toggle-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .toggle-header i {
        font-size: 1.25rem;
    }

    .toggle-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #495057;
    }

    .toggle-count {
        margin-left: auto;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ura-primary);
    }

    .toggle-progress {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 0.6s ease;
    }

    .toggle-percentage {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(0, 188, 212, 0.03) 100%);
        border-radius: 10px;
        border-left: 3px solid var(--ura-primary);
    }

    .detail-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ura-primary);
    }

    /* Overdue Management Styles */
    .overdue-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(23, 71, 158, 0.08);
        border: 1px solid rgba(23, 71, 158, 0.05);
        margin-bottom: 2rem;
    }

    .overdue-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(23, 71, 158, 0.1);
    }

    .overdue-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .overdue-icon-wrapper {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #DC3545 0%, #FFC107 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        animation: pulse-alert 2s infinite;
    }

    @keyframes pulse-alert {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.9; }
    }

    .overdue-title {
        margin: 0;
        color: var(--ura-primary);
        font-weight: 600;
    }

    .overdue-subtitle {
        margin: 0;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .overdue-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-ura-action {
        background: white;
        border: 2px solid rgba(23, 71, 158, 0.2);
        color: var(--ura-primary);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-ura-action:hover {
        background: rgba(23, 71, 158, 0.05);
        transform: translateY(-2px);
    }

    .btn-ura-action.primary {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        border: none;
    }

    .btn-ura-action.primary:hover {
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.3);
    }

    .overdue-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .summary-card.critical {
        border-color: rgba(220, 53, 69, 0.2);
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(255, 193, 7, 0.05) 100%);
    }

    .summary-card.warning {
        border-color: rgba(255, 193, 7, 0.2);
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 152, 0, 0.05) 100%);
    }

    .summary-card.pending {
        border-color: rgba(23, 71, 158, 0.2);
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
    }

    .summary-card.resolution {
        border-color: rgba(40, 167, 69, 0.2);
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
    }

    .summary-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .summary-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(23, 71, 158, 0.1);
        color: var(--ura-primary);
    }

    .summary-badge.pulse-red {
        background: rgba(220, 53, 69, 0.15);
        color: #DC3545;
        animation: pulse 1.5s infinite;
    }

    .summary-badge.pulse-warning {
        background: rgba(255, 193, 7, 0.15);
        color: #FFA500;
        animation: pulse 2s infinite;
    }

    .summary-badge.success {
        background: rgba(40, 167, 69, 0.15);
        color: #28A745;
    }

    .summary-value {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .value-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
    }

    .value-unit {
        font-size: 1rem;
        color: #6c757d;
    }

    .value-trend {
        font-size: 0.75rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .value-trend.neutral {
        color: #6c757d;
    }

    .summary-progress {
        height: 6px;
        background: rgba(0,0,0,0.05);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }

    .critical-fill {
        background: linear-gradient(90deg, #DC3545 0%, #FFC107 100%);
    }

    .warning-fill {
        background: linear-gradient(90deg, #FFC107 0%, #FF9800 100%);
    }

    .pending-fill {
        background: linear-gradient(90deg, #17479E 0%, #00BCD4 100%);
    }

    .success-fill {
        background: linear-gradient(90deg, #28A745 0%, #20C997 100%);
    }

    .overdue-chart-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .chart-controls {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .chart-tabs {
        display: flex;
        gap: 0.5rem;
    }

    .chart-tab {
        padding: 0.5rem 1rem;
        background: transparent;
        border: 1px solid rgba(23, 71, 158, 0.2);
        border-radius: 8px;
        color: #6c757d;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chart-tab:hover {
        background: rgba(23, 71, 158, 0.05);
    }

    .chart-tab.active {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        border-color: transparent;
    }

    .chart-view-toggle {
        display: flex;
        gap: 0.25rem;
    }

    .view-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid rgba(23, 71, 158, 0.2);
        background: white;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .view-btn.active {
        background: var(--ura-primary);
        color: white;
        border-color: var(--ura-primary);
    }

    .chart-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .chart-legend {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }

    .legend-color.critical {
        background: linear-gradient(135deg, #DC3545 0%, #FFC107 100%);
    }

    .legend-color.warning {
        background: linear-gradient(135deg, #FFC107 0%, #FF9800 100%);
    }

    .legend-color.pending {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
    }

    .leaderboard-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .leaderboard-header {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .leaderboard-title {
        margin: 0;
        color: white;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .leaderboard-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .leaderboard-content {
        padding: 1rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .leaderboard-item:hover {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
        transform: translateX(4px);
    }

    .leaderboard-item.top-three {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 193, 7, 0.1) 100%);
    }

    .rank-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 1rem;
        background: white;
        color: var(--ura-primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .rank-badge.rank-1 {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
        font-size: 1.2rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        color: #212529;
        font-size: 0.875rem;
    }

    .user-department {
        color: #6c757d;
        font-size: 0.75rem;
    }

    .user-metrics {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-right: 1rem;
    }

    .metric-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #DC3545;
    }

    .metric-label {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .btn-notify {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid rgba(23, 71, 158, 0.2);
        background: white;
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-notify:hover {
        background: var(--ura-primary);
        color: white;
        transform: scale(1.1);
    }

    .leaderboard-footer {
        padding: 1rem;
        text-align: center;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .view-all-link {
        color: var(--ura-primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .view-all-link:hover {
        color: var(--ura-accent);
        transform: translateX(4px);
    }

    .no-data-message {
        text-align: center;
        padding: 3rem;
        color: #28A745;
    }

    .no-data-message i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .insights-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.03) 0%, rgba(0, 188, 212, 0.03) 100%);
        border-radius: 12px;
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .insight-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: #495057;
    }

    .insight-item i {
        font-size: 1.25rem;
        color: var(--ura-primary);
    }

    .insight-item strong {
        color: var(--ura-primary);
        margin-right: 0.25rem;
    }

    .status-item {
        padding: 0.75rem;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    /* Data Table Card */
    .data-table-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 2rem;
        position: relative;
        z-index: 1;
    }

    .table-modern {
        margin-bottom: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.05) 0%, rgba(0, 188, 212, 0.05) 100%);
    }

    .table-modern th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: var(--ura-primary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .table-modern tbody tr:hover {
        background: rgba(23, 71, 158, 0.02);
        transition: background 0.3s ease;
    }

    .status-item:hover {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .status-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .status-item:hover .status-icon-wrapper {
        transform: scale(1.1);
    }

    .status-label {
        font-size: 13px;
        font-weight: 500;
        color: #495057;
    }

    .status-count {
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .status-percentage {
        font-size: 11px;
    }

    .status-progress {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .status-progress .progress-bar {
        transition: width 0.6s ease;
        border-radius: 3px;
    }

    /* Modern Performance Metrics URA SACCOS Design */
    .performance-metrics-container {
        background: linear-gradient(135deg, #ffffff 0%, rgba(23, 71, 158, 0.02) 100%);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(23, 71, 158, 0.08);
        border: 1px solid rgba(23, 71, 158, 0.1);
    }

    .metrics-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid linear-gradient(90deg, #17479E 0%, #00BCD4 100%);
        background: linear-gradient(90deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }

    .metrics-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .metrics-icon-wrapper {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        animation: pulse-icon 3s infinite;
    }

    @keyframes pulse-icon {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .metrics-title {
        margin: 0;
        color: var(--ura-primary);
        font-weight: 700;
        font-size: 1.2rem;
    }

    .metrics-subtitle {
        margin: 0;
        color: #6c757d;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .live-indicator {
        width: 8px;
        height: 8px;
        background: #00ff00;
        border-radius: 50%;
        display: inline-block;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .metrics-controls {
        display: flex;
        gap: 0.5rem;
    }

    .btn-metric-view {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid rgba(23, 71, 158, 0.2);
        background: white;
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-metric-view:hover,
    .btn-metric-view.active {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        border-color: transparent;
        transform: scale(1.1);
    }

    .btn-metric-action {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: 2px solid rgba(23, 71, 158, 0.2);
        background: white;
        color: var(--ura-primary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-metric-action:hover {
        background: var(--ura-primary);
        color: white;
        border-color: var(--ura-primary);
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    .metric-item {
        position: relative;
    }

    .metric-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, rgba(23, 71, 158, 0.02) 100%);
        border-radius: 18px;
        padding: 1.5rem;
        height: 100%;
        min-height: 190px;
        box-shadow: 0 8px 32px rgba(23, 71, 158, 0.08);
        border: 2px solid rgba(23, 71, 158, 0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        animation: cardSlideIn 0.6s ease-out backwards;
    }

    @keyframes cardSlideIn {
        from {
            opacity: 0;
            transform: translateX(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    /* Advanced animated background patterns with particle effects */
    .metric-card-modern::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2317479E' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"),
                    radial-gradient(circle at 20% 80%, rgba(0, 188, 212, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(23, 71, 158, 0.05) 0%, transparent 50%);
        animation: patternMove 25s linear infinite;
        opacity: 0.7;
    }

    @keyframes patternMove {
        0% { 
            transform: translate(0, 0) rotate(0deg) scale(1);
            filter: hue-rotate(0deg);
        }
        33% {
            transform: translate(30px, 30px) rotate(120deg) scale(1.1);
            filter: hue-rotate(60deg);
        }
        66% {
            transform: translate(-30px, 60px) rotate(240deg) scale(0.9);
            filter: hue-rotate(-60deg);
        }
        100% { 
            transform: translate(0, 0) rotate(360deg) scale(1);
            filter: hue-rotate(0deg);
        }
    }
    
    /* Floating particles for metric cards */
    .metric-card-modern .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: linear-gradient(135deg, var(--ura-accent) 0%, var(--ura-primary) 100%);
        border-radius: 50%;
        opacity: 0;
        animation: floatParticle 8s infinite;
    }
    
    .metric-card-modern .particle:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .metric-card-modern .particle:nth-child(2) {
        top: 80%;
        right: 20%;
        animation-delay: 2s;
    }
    
    .metric-card-modern .particle:nth-child(3) {
        bottom: 10%;
        left: 50%;
        animation-delay: 4s;
    }
    
    @keyframes floatParticle {
        0% {
            opacity: 0;
            transform: translateY(20px) scale(0);
        }
        20% {
            opacity: 1;
            transform: translateY(-10px) scale(1);
        }
        80% {
            opacity: 1;
            transform: translateY(-40px) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateY(-60px) scale(0);
        }
    }

    .metric-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(180deg, transparent 0%, rgba(23, 71, 158, 0.03) 100%);
        transform: translateY(-100%);
        transition: transform 0.5s ease;
    }

    .metric-card-modern:hover::before {
        transform: translateY(0);
    }

    .metric-card-modern:hover {
        transform: translateY(-10px) scale(1.03) rotateX(3deg);
        box-shadow: 0 20px 50px rgba(23, 71, 158, 0.2);
        border-color: rgba(0, 188, 212, 0.3);
    }

    /* Individual card backgrounds with URA SACCOS gradients */
    .metric-card-modern.pending { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(23, 71, 158, 0.06) 50%, rgba(0, 188, 212, 0.04) 100%);
        border-left: 4px solid #17479E;
    }
    .metric-card-modern.shares { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(40, 167, 69, 0.06) 50%, rgba(0, 188, 212, 0.04) 100%);
        border-left: 4px solid #28A745;
    }
    .metric-card-modern.loans { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 193, 7, 0.06) 50%, rgba(23, 71, 158, 0.04) 100%);
        border-left: 4px solid #FFC107;
    }
    .metric-card-modern.revenue { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(0, 188, 212, 0.06) 50%, rgba(23, 71, 158, 0.04) 100%);
        border-left: 4px solid #00BCD4;
    }
    .metric-card-modern.approval { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(156, 39, 176, 0.06) 50%, rgba(0, 188, 212, 0.04) 100%);
        border-left: 4px solid #9C27B0;
    }
    .metric-card-modern.deductions { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(220, 53, 69, 0.06) 50%, rgba(23, 71, 158, 0.04) 100%);
        border-left: 4px solid #DC3545;
    }
    .metric-card-modern.completed { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(0, 150, 136, 0.06) 50%, rgba(0, 188, 212, 0.04) 100%);
        border-left: 4px solid #009688;
    }
    .metric-card-modern.new { 
        background: linear-gradient(135deg, #ffffff 0%, rgba(33, 33, 33, 0.06) 50%, rgba(23, 71, 158, 0.04) 100%);
        border-left: 4px solid #212121;
    }

    /* Staggered animations */
    .metric-item:nth-child(1) .metric-card-modern { animation-delay: 0.1s; }
    .metric-item:nth-child(2) .metric-card-modern { animation-delay: 0.2s; }
    .metric-item:nth-child(3) .metric-card-modern { animation-delay: 0.3s; }
    .metric-item:nth-child(4) .metric-card-modern { animation-delay: 0.4s; }
    .metric-item:nth-child(5) .metric-card-modern { animation-delay: 0.5s; }
    .metric-item:nth-child(6) .metric-card-modern { animation-delay: 0.6s; }
    .metric-item:nth-child(7) .metric-card-modern { animation-delay: 0.7s; }
    .metric-item:nth-child(8) .metric-card-modern { animation-delay: 0.8s; }

    .metric-header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: linear-gradient(135deg, rgba(23, 71, 158, 0.1) 0%, rgba(0, 188, 212, 0.1) 100%);
        color: var(--ura-primary);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
        animation: iconWobble 4s ease-in-out infinite;
    }

    @keyframes iconWobble {
        0%, 100% { transform: rotate(0deg) scale(1); }
        25% { transform: rotate(-3deg) scale(1.05); }
        75% { transform: rotate(3deg) scale(1.05); }
    }

    .metric-card-modern:hover .metric-icon-box {
        transform: rotate(360deg) scale(1.2);
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(23, 71, 158, 0.3);
    }

    .metric-icon-box i {
        animation: iconPulse 2s ease-in-out infinite;
    }

    @keyframes iconPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .metric-status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(23, 71, 158, 0.08);
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .pending-dot { background: #17479E; }
    .success-dot { background: #28A745; }
    .warning-dot { background: #FFC107; }
    .info-dot { background: #00BCD4; }
    .purple-dot { background: #9C27B0; }
    .danger-dot { background: #DC3545; }
    .teal-dot { background: #009688; }
    .dark-dot { background: #212121; }

    .metric-value-section {
        margin-bottom: 1rem;
    }

    .metric-main-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin: 0;
        line-height: 1.2;
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        animation: valueShimmer 3s ease-in-out infinite;
    }
    
    @keyframes valueShimmer {
        0%, 100% { 
            filter: brightness(1);
            text-shadow: 0 0 0px rgba(23, 71, 158, 0);
        }
        50% { 
            filter: brightness(1.2);
            text-shadow: 0 0 20px rgba(0, 188, 212, 0.3);
        }
    }
    
    .metric-card-modern:hover .metric-main-value {
        animation: valueGlowPulse 1s ease-in-out;
    }
    
    @keyframes valueGlowPulse {
        0%, 100% { 
            transform: scale(1);
            filter: brightness(1);
        }
        50% { 
            transform: scale(1.08);
            filter: brightness(1.3);
        }
    }

    .metric-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0.25rem 0 0 0;
    }

    .metric-footer-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(23, 71, 158, 0.08);
    }

    .metric-sparkline {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .spark-value {
        font-weight: 600;
        color: var(--ura-primary);
    }

    .metric-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(23, 71, 158, 0.05);
        color: var(--ura-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .metric-action-btn:hover {
        background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);
        color: white;
        transform: scale(1.2);
    }

    .metric-mini-chart {
        width: 60px;
        height: 30px;
    }

    .metric-circular-progress {
        position: relative;
    }

    .text-teal { color: #009688; }

    .gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .gradient-success {
        background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
        color: white;
    }

    .gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .gradient-purple {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }

    .gradient-danger {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #333;
    }

    .gradient-teal {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .gradient-dark {
        background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        color: white;
    }

    .metric-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2.5rem;
        opacity: 0.3;
        transition: all 0.3s ease;
    }

    .metric-card:hover .metric-icon {
        opacity: 0.5;
        transform: rotate(10deg) scale(1.1);
    }

    .metric-content {
        position: relative;
        z-index: 2;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .metric-label {
        font-size: 0.875rem;
        margin: 0.5rem 0;
        opacity: 0.9;
        font-weight: 500;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        opacity: 0.8;
    }

    .metric-trend i {
        font-size: 1rem;
    }

    .metric-trend.positive {
        color: inherit;
    }

    .metric-trend.negative {
        color: inherit;
    }

    .metric-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,0.2);
    }

    .metric-progress .progress-bar {
        height: 100%;
        background: rgba(255,255,255,0.5);
        transition: width 1s ease;
    }

    .badge-success-modern {
        background: linear-gradient(135deg, #15ca20 0%, #0fb919 100%);
        color: white;
    }

    .badge-warning-modern {
        background: linear-gradient(135deg, #fd7e14 0%, #ff6b00 100%);
        color: white;
    }

    .badge-danger-modern {
        background: linear-gradient(135deg, #d63384 0%, #c21e70 100%);
        color: white;
    }

    .badge-info-modern {
        background: linear-gradient(135deg, #0dcaf0 0%, #00a8cc 100%);
        color: white;
    }

    /* Responsive Grid */
    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-value {
            font-size: 24px;
        }
    }

    /* Dark mode support */
    html.dark-theme .stat-card,
    html.dark-theme .chart-card,
    html.dark-theme .kpi-card,
    html.dark-theme .data-table-card {
        background: #12181a;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
    }

    html.dark-theme .stat-value,
    html.dark-theme .chart-title {
        color: #e4e5e6;
    }

    html.dark-theme .table-modern tbody tr:hover {
        background: rgba(0, 188, 212, 0.1);
    }

    html.dark-theme .table-modern tbody td {
        border-bottom-color: rgba(255, 255, 255, 0.1);
        color: #e4e5e6;
    }

    /* Loading Animation */
    .chart-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 300px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(23, 71, 158, 0.1);
        border-top-color: var(--ura-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Trend Indicator */
    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .trend-up {
        color: var(--ura-success);
    }

    .trend-down {
        color: var(--ura-danger);
    }
</style>

<div class="page-content-wrapper">
    <!-- Page Title Section -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Business Intelligence</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-primary">
                    <i class='bx bx-filter'></i> Filter
                </button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="javascript:;">Today</a>
                    <a class="dropdown-item" href="javascript:;">This Week</a>
                    <a class="dropdown-item" href="javascript:;">This Month</a>
                    <a class="dropdown-item" href="javascript:;">This Year</a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stat-card">
                <!-- Floating gradient orbs -->
                <div class="floating-orb orb-primary"></div>
                <div class="floating-orb orb-accent"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Total Members</p>
                        <h2 class="stat-value">{{ number_format($totalMembers ?? 0) }}</h2>
                        <span class="trend-indicator {{ $membersTrend >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class='bx bx-trending-{{ $membersTrend >= 0 ? 'up' : 'down' }}'></i> {{ abs($membersTrend) }}%
                        </span>
                        <span class="text-muted small">vs last month</span>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #17479E 0%, #2a5298 100%);">
                        <i class='bx bx-group'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stat-card">
                <!-- Floating gradient orbs -->
                <div class="floating-orb orb-primary"></div>
                <div class="floating-orb orb-accent"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Active Loans</p>
                        <h2 class="stat-value">{{ number_format($activeLoans ?? 0) }}</h2>
                        <span class="trend-indicator {{ $loansTrend >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class='bx bx-trending-{{ $loansTrend >= 0 ? 'up' : 'down' }}'></i> {{ abs($loansTrend) }}%
                        </span>
                        <span class="text-muted small">vs last month</span>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #00BCD4 0%, #0097a7 100%);">
                        <i class='bx bx-credit-card'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stat-card">
                <!-- Floating gradient orbs -->
                <div class="floating-orb orb-primary"></div>
                <div class="floating-orb orb-accent"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Total Shares</p>
                        <h2 class="stat-value">TSHS {{ number_format($totalShares ?? 0) }}</h2>
                        <span class="trend-indicator {{ $sharesTrend >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class='bx bx-trending-{{ $sharesTrend >= 0 ? 'up' : 'down' }}'></i> {{ abs($sharesTrend) }}%
                        </span>
                        <span class="text-muted small">vs last month</span>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #15ca20 0%, #0fb919 100%);">
                        <i class='bx bx-pie-chart-alt-2'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stat-card">
                <!-- Floating gradient orbs -->
                <div class="floating-orb orb-primary"></div>
                <div class="floating-orb orb-accent"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Monthly Revenue</p>
                        <h2 class="stat-value">TSHS {{ number_format($monthlyRevenue ?? 0) }}</h2>
                        <span class="trend-indicator {{ $revenueTrend >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class='bx bx-trending-{{ $revenueTrend >= 0 ? 'up' : 'down' }}'></i> {{ abs($revenueTrend) }}%
                        </span>
                        <span class="text-muted small">vs last month</span>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #fd7e14 0%, #ff6b00 100%);">
                        <i class='bx bx-dollar'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Enquiries Distribution -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h6 class="chart-title">Enquiries Distribution</h6>
                        <p class="chart-subtitle">By type for current period</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded'></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('trends') }}">View Details</a></li>
                            <li><a class="dropdown-item" href="#">Export Data</a></li>
                        </ul>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="enquiriesChart"></canvas>
                </div>
                <div class="mt-3">
                    @foreach($enquiryTypeFrequency as $type)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">{{ ucfirst(str_replace('_', ' ', $type->type)) }}</span>
                        <span class="badge badge-modern" style="background: linear-gradient(135deg, #17479E 0%, #00BCD4 100%);">
                            {{ $type->frequency }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-xl-8 col-lg-12 mb-4">
            <div class="performance-metrics-container">
                <div class="metrics-header">
                    <div class="metrics-title-section">
                        <div class="metrics-icon-wrapper">
                            <i class='bx bx-pulse'></i>
                        </div>
                        <div>
                            <h5 class="metrics-title">Performance Command Center</h5>
                            <p class="metrics-subtitle">
                                <span class="live-indicator"></span>
                                Real-time operational metrics
                            </p>
                        </div>
                    </div>
                    <div class="metrics-controls">
                        <button class="btn-metric-view active" data-view="grid">
                            <i class='bx bx-grid-alt'></i>
                        </button>
                        <button class="btn-metric-view" data-view="list">
                            <i class='bx bx-list-ul'></i>
                        </button>
                        <button class="btn-metric-action">
                            <i class='bx bx-refresh'></i> Refresh
                        </button>
                    </div>
                </div>
                
                <div class="metrics-grid">
                    <!-- Pending Enquiries -->
                    <div class="metric-item">
                        <div class="metric-card-modern pending">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-hourglass'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot pending-dot"></span>
                                    Pending
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ \App\Models\Enquiry::where('status', 'pending')->count() }}</h2>
                                <p class="metric-description">Awaiting Action</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-mini-chart">
                                    <canvas id="pendingMiniChart"></canvas>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share Purchases -->
                    <div class="metric-item">
                        <div class="metric-card-modern shares">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-purchase-tag'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot success-dot"></span>
                                    Shares
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ \App\Models\Payment::whereHas('enquiry', function($q) {
                                    $q->where('type', 'share_enquiry');
                                })->whereMonth('payment_date', \Carbon\Carbon::now()->month)->count() }}</h2>
                                <p class="metric-description">Monthly Purchases</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">+12%</span>
                                    <i class='bx bx-trending-up text-success'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan Disbursements -->
                    <div class="metric-item">
                        <div class="metric-card-modern loans">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-transfer'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot warning-dot"></span>
                                    Loans
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ \App\Models\LoanApplication::where('status', 'disbursed')->count() }}</h2>
                                <p class="metric-description">Disbursed</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">Active</span>
                                    <i class='bx bx-check-circle text-warning'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Today's Collections -->
                    <div class="metric-item">
                        <div class="metric-card-modern revenue">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-wallet-alt'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot info-dot"></span>
                                    Revenue
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ number_format(\App\Models\Payment::whereDate('payment_date', \Carbon\Carbon::today())->sum('amount') / 1000, 0) }}K</h2>
                                <p class="metric-description">Today's Total</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">TSHS</span>
                                    <i class='bx bx-money text-info'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approval Rate -->
                    <div class="metric-item">
                        <div class="metric-card-modern approval">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-check-shield'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot purple-dot"></span>
                                    Rate
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ round(($loanPipelineData['approved'] / max(1, $loanPipelineData['in_progress'])) * 100) }}%</h2>
                                <p class="metric-description">Approval Rate</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-circular-progress">
                                    <svg width="40" height="40">
                                        <circle cx="20" cy="20" r="15" fill="none" stroke="#e9ecef" stroke-width="3"/>
                                        <circle cx="20" cy="20" r="15" fill="none" stroke="#17479E" stroke-width="3"
                                                stroke-dasharray="{{ round(($loanPipelineData['approved'] / max(1, $loanPipelineData['in_progress'])) * 100) * 0.94 }} 94"
                                                transform="rotate(-90 20 20)"/>
                                    </svg>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Deductions -->
                    <div class="metric-item">
                        <div class="metric-card-modern deductions">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-receipt'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot danger-dot"></span>
                                    Process
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ $enquiryTypeDeduction->frequency ?? 0 }}</h2>
                                <p class="metric-description">Deductions</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">+8%</span>
                                    <i class='bx bx-trending-up text-danger'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completed Today -->
                    <div class="metric-item">
                        <div class="metric-card-modern completed">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot teal-dot"></span>
                                    Today
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ \App\Models\Enquiry::where('status', 'completed')->whereDate('updated_at', \Carbon\Carbon::today())->count() }}</h2>
                                <p class="metric-description">Completed</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">Good</span>
                                    <i class='bx bx-badge-check text-teal'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Applications -->
                    <div class="metric-item">
                        <div class="metric-card-modern new">
                            <!-- Floating particles -->
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <span class="particle"></span>
                            <div class="metric-header-section">
                                <div class="metric-icon-box">
                                    <i class='bx bx-file-plus'></i>
                                </div>
                                <div class="metric-status-badge">
                                    <span class="status-dot dark-dot"></span>
                                    Week
                                </div>
                            </div>
                            <div class="metric-value-section">
                                <h2 class="metric-main-value">{{ \App\Models\Enquiry::whereDate('created_at', '>=', \Carbon\Carbon::now()->subDays(7))->count() }}</h2>
                                <p class="metric-description">New Applications</p>
                            </div>
                            <div class="metric-footer-section">
                                <div class="metric-sparkline">
                                    <span class="spark-value">7 Days</span>
                                    <i class='bx bx-calendar text-dark'></i>
                                </div>
                                <div class="metric-action-btn">
                                    <i class='bx bx-right-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trends Chart -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h6 class="chart-title">Monthly Trends Analysis</h6>
                        <p class="chart-subtitle">Enquiries vs Loan Applications</p>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active">Monthly</button>
                        <button type="button" class="btn btn-outline-primary">Quarterly</button>
                        <button type="button" class="btn btn-outline-primary">Yearly</button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <div id="trendsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Application Pipeline - Compact Modern Design -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="pipeline-container">
                <!-- Header Section -->
                <div class="pipeline-header">
                    <div class="pipeline-title-section">
                        <h5 class="pipeline-title">
                            <i class='bx bx-git-branch text-ura-primary'></i>
                            Loan Application Pipeline
                        </h5>
                        <span class="pipeline-badge">Live</span>
                    </div>
                    <div class="pipeline-controls">
                        <button class="btn btn-sm btn-ura-outline active" data-view="stats">
                            <i class='bx bx-stats'></i> Stats
                        </button>
                        <button class="btn btn-sm btn-ura-outline" data-view="chart">
                            <i class='bx bx-bar-chart-alt'></i> Chart
                        </button>
                        <button class="btn btn-sm btn-ura-outline" data-view="detail">
                            <i class='bx bx-list-ul'></i> Details
                        </button>
                    </div>
                </div>

                <!-- Compact Stats Bar -->
                <div class="pipeline-stats-bar">
                    <div class="stat-pill total">
                        <div class="stat-pill-icon">
                            <i class='bx bx-package'></i>
                        </div>
                        <div class="stat-pill-content">
                            <span class="stat-pill-value">{{ $loanPipelineData['total_enquiries'] }}</span>
                            <span class="stat-pill-label">Total Received</span>
                        </div>
                    </div>
                    
                    <div class="pipeline-flow-arrow">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                    
                    <div class="stat-pill processing">
                        <div class="stat-pill-icon">
                            <i class='bx bx-loader-circle'></i>
                        </div>
                        <div class="stat-pill-content">
                            <span class="stat-pill-value">{{ $loanPipelineData['in_progress'] }}</span>
                            <span class="stat-pill-label">In Process</span>
                        </div>
                    </div>
                    
                    <div class="pipeline-flow-arrow">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                    
                    <div class="stat-pill waiting">
                        <div class="stat-pill-icon">
                            <i class='bx bx-time-five'></i>
                        </div>
                        <div class="stat-pill-content">
                            <span class="stat-pill-value">{{ $loanPipelineData['not_processed'] }}</span>
                            <span class="stat-pill-label">Waiting</span>
                        </div>
                    </div>
                    
                    <div class="pipeline-flow-arrow">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                    
                    <div class="stat-pill success-rate">
                        <div class="stat-pill-icon">
                            <i class='bx bx-trophy'></i>
                        </div>
                        <div class="stat-pill-content">
                            <span class="stat-pill-value">{{ round(($loanPipelineData['approved'] / max(1, $loanPipelineData['in_progress'])) * 100) }}%</span>
                            <span class="stat-pill-label">Success Rate</span>
                        </div>
                    </div>
                </div>

                <!-- Expandable Content Area -->
                <div class="pipeline-content" id="pipelineContent">
                    <!-- Stats View (Default) -->
                    <div class="content-view active" id="statsView">
                        <div class="status-toggles">
                            @php
                                $statuses = [
                                    ['key' => 'approved', 'label' => 'Approved', 'value' => $loanPipelineData['approved'], 'icon' => 'bx-check-circle', 'color' => '#00BCD4'],
                                    ['key' => 'pending', 'label' => 'Pending', 'value' => $loanPipelineData['pending'], 'icon' => 'bx-hourglass', 'color' => '#FFC107'],
                                    ['key' => 'rejected', 'label' => 'Rejected', 'value' => $loanPipelineData['rejected'], 'icon' => 'bx-x-circle', 'color' => '#DC3545'],
                                    ['key' => 'disbursed', 'label' => 'Disbursed', 'value' => $loanPipelineData['disbursed'], 'icon' => 'bx-money', 'color' => '#17479E'],
                                    ['key' => 'paid', 'label' => 'Paid', 'value' => $loanPipelineData['paid'], 'icon' => 'bx-badge-check', 'color' => '#28A745'],
                                ];
                                $totalProcessed = max(1, $loanPipelineData['in_progress']);
                            @endphp
                            
                            @foreach($statuses as $status)
                                @php
                                    $percentage = round(($status['value'] / $totalProcessed) * 100, 1);
                                @endphp
                                <div class="status-toggle {{ $status['value'] > 0 ? 'active' : '' }}" data-status="{{ $status['key'] }}">
                                    <div class="toggle-header">
                                        <i class='bx {{ $status['icon'] }}' style="color: {{ $status['color'] }}"></i>
                                        <span class="toggle-label">{{ $status['label'] }}</span>
                                        <span class="toggle-count">{{ $status['value'] }}</span>
                                    </div>
                                    <div class="toggle-progress">
                                        <div class="progress-fill" style="width: {{ $percentage }}%; background: {{ $status['color'] }}"></div>
                                    </div>
                                    <div class="toggle-percentage">{{ $percentage }}%</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Chart View -->
                    <div class="content-view" id="chartView">
                        <div style="height: 250px; padding: 1rem;">
                            <canvas id="loanPipelineCompactChart"></canvas>
                        </div>
                    </div>

                    <!-- Detail View -->
                    <div class="content-view" id="detailView">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Average Processing Time</span>
                                <span class="detail-value">2.5 days</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">This Month Progress</span>
                                <span class="detail-value">+18%</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Conversion Rate</span>
                                <span class="detail-value">67%</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Peak Processing Day</span>
                                <span class="detail-value">Wednesday</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Management Section - Modern URA SACCOS Design -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="overdue-container">
                <!-- Overdue Header -->
                <div class="overdue-header">
                    <div class="overdue-title-section">
                        <div class="overdue-icon-wrapper">
                            <i class='bx bx-alarm-exclamation'></i>
                        </div>
                        <div>
                            <h5 class="overdue-title">Overdue Management Center</h5>
                            <p class="overdue-subtitle">Real-time tracking of pending enquiries exceeding 3-day threshold</p>
                        </div>
                    </div>
                    <div class="overdue-actions">
                        <button class="btn-ura-action" onclick="sendReminders()">
                            <i class='bx bx-bell'></i> Send Reminders
                        </button>
                        <button class="btn-ura-action primary" onclick="exportOverdueReport()">
                            <i class='bx bx-download'></i> Export Report
                        </button>
                    </div>
                </div>

                <!-- Overdue Summary Cards -->
                <div class="overdue-summary-grid">
                    <div class="summary-card critical">
                        <div class="summary-card-header">
                            <span class="summary-label">Critical (>7 days)</span>
                            <span class="summary-badge pulse-red">Urgent</span>
                        </div>
                        <div class="summary-value">
                            @php
                                $criticalCount = 0;
                                // Calculate critical items (mock data - replace with actual)
                            @endphp
                            <span class="value-number">12</span>
                            <span class="value-trend">
                                <i class='bx bx-trending-up'></i> +3 today
                            </span>
                        </div>
                        <div class="summary-progress">
                            <div class="progress-fill critical-fill" style="width: 80%"></div>
                        </div>
                    </div>

                    <div class="summary-card warning">
                        <div class="summary-card-header">
                            <span class="summary-label">Warning (4-6 days)</span>
                            <span class="summary-badge pulse-warning">Attention</span>
                        </div>
                        <div class="summary-value">
                            <span class="value-number">{{ isset($overdueData['data']) ? min(8, array_sum($overdueData['data'])) : 8 }}</span>
                            <span class="value-trend">
                                <i class='bx bx-trending-down'></i> -2 today
                            </span>
                        </div>
                        <div class="summary-progress">
                            <div class="progress-fill warning-fill" style="width: 60%"></div>
                        </div>
                    </div>

                    <div class="summary-card pending">
                        <div class="summary-card-header">
                            <span class="summary-label">Pending (3 days)</span>
                            <span class="summary-badge">Monitor</span>
                        </div>
                        <div class="summary-value">
                            <span class="value-number">{{ isset($overdueData['data']) ? array_sum($overdueData['data']) : 0 }}</span>
                            <span class="value-trend neutral">
                                <i class='bx bx-minus'></i> No change
                            </span>
                        </div>
                        <div class="summary-progress">
                            <div class="progress-fill pending-fill" style="width: 40%"></div>
                        </div>
                    </div>

                    <div class="summary-card resolution">
                        <div class="summary-card-header">
                            <span class="summary-label">Avg Resolution</span>
                            <span class="summary-badge success">Improving</span>
                        </div>
                        <div class="summary-value">
                            <span class="value-number">4.2</span>
                            <span class="value-unit">days</span>
                        </div>
                        <div class="summary-progress">
                            <div class="progress-fill success-fill" style="width: 70%"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="row mt-4">
                    <!-- Top Users Chart -->
                    <div class="col-xl-7 col-lg-12">
                        <div class="overdue-chart-card">
                            <div class="chart-controls">
                                <div class="chart-tabs">
                                    <button class="chart-tab active" data-period="week">This Week</button>
                                    <button class="chart-tab" data-period="month">This Month</button>
                                    <button class="chart-tab" data-period="quarter">Quarter</button>
                                </div>
                                <div class="chart-view-toggle">
                                    <button class="view-btn active" data-view="bar">
                                        <i class='bx bx-bar-chart-alt-2'></i>
                                    </button>
                                    <button class="view-btn" data-view="line">
                                        <i class='bx bx-line-chart'></i>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="overdueChart" style="height: 320px;"></canvas>
                            </div>
                            <div class="chart-footer">
                                <div class="chart-legend">
                                    <span class="legend-item">
                                        <span class="legend-color critical"></span>
                                        Critical
                                    </span>
                                    <span class="legend-item">
                                        <span class="legend-color warning"></span>
                                        Warning
                                    </span>
                                    <span class="legend-item">
                                        <span class="legend-color pending"></span>
                                        Pending
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Leaderboard -->
                    <div class="col-xl-5 col-lg-12">
                        <div class="leaderboard-card">
                            <div class="leaderboard-header">
                                <h6 class="leaderboard-title">
                                    <i class='bx bx-trophy'></i> User Performance Board
                                </h6>
                                <span class="leaderboard-badge">Live Rankings</span>
                            </div>
                            <div class="leaderboard-content">
                                @php
                                    $topUsers = isset($overdueData['labels']) ? array_combine($overdueData['labels'], $overdueData['data']) : [];
                                    $counter = 1;
                                @endphp
                                @foreach($topUsers as $userName => $count)
                                    @if($counter <= 5)
                                    <div class="leaderboard-item {{ $counter <= 3 ? 'top-three' : '' }}">
                                        <div class="rank-badge rank-{{ $counter }}">
                                            @if($counter == 1)
                                                <i class='bx bx-crown'></i>
                                            @else
                                                {{ $counter }}
                                            @endif
                                        </div>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                {{ substr($userName, 0, 2) }}
                                            </div>
                                            <div class="user-details">
                                                <span class="user-name">{{ $userName }}</span>
                                                <span class="user-department">Loan Officer</span>
                                            </div>
                                        </div>
                                        <div class="user-metrics">
                                            <span class="metric-value">{{ $count }}</span>
                                            <span class="metric-label">overdue</span>
                                        </div>
                                        <div class="user-action">
                                            <button class="btn-notify" onclick="notifyUser('{{ $userName }}')">
                                                <i class='bx bx-bell'></i>
                                            </button>
                                        </div>
                                    </div>
                                    @php $counter++; @endphp
                                    @endif
                                @endforeach
                                
                                @if(empty($topUsers))
                                    <div class="no-data-message">
                                        <i class='bx bx-check-circle'></i>
                                        <p>No overdue items! Great job!</p>
                                    </div>
                                @endif
                            </div>
                            <div class="leaderboard-footer">
                                <a href="#" class="view-all-link">
                                    View All Users <i class='bx bx-chevron-right'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Insights -->
                <div class="insights-strip">
                    <div class="insight-item">
                        <i class='bx bx-bulb'></i>
                        <span><strong>Insight:</strong> Most overdues occur on Mondays - Consider adjusting deadlines</span>
                    </div>
                    <div class="insight-item">
                        <i class='bx bx-target-lock'></i>
                        <span><strong>Goal:</strong> Reduce average resolution time to under 3 days</span>
                    </div>
                    <div class="insight-item">
                        <i class='bx bx-trending-up'></i>
                        <span><strong>Trend:</strong> 15% improvement in response time this month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Enquiries Table -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="data-table-card">
                <div class="chart-header">
                    <div>
                        <h6 class="chart-title">Recent Enquiries</h6>
                        <p class="chart-subtitle">Latest customer enquiries and their status</p>
                    </div>
                    <button class="btn btn-sm btn-primary">
                        <i class='bx bx-export'></i> Export
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" id="enquiriesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Force No</th>
                                <th>Name</th>
                                <th>Account</th>
                                <th>Bank</th>
                                <th>Region</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enquiries->take(10) as $index => $enquiry)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        try {
                                            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $enquiry->date_received);
                                        } catch (\Exception $e) {
                                            try {
                                                $date = \Carbon\Carbon::parse($enquiry->date_received);
                                            } catch (\Exception $e2) {
                                                $date = \Carbon\Carbon::now();
                                            }
                                        }
                                    @endphp
                                    {{ $date->format('d M Y') }}
                                </td>
                                <td>{{ $enquiry->force_no }}</td>
                                <td>{{ ucwords(strtolower($enquiry->full_name)) }}</td>
                                <td>{{ $enquiry->account_number }}</td>
                                <td>{{ strtoupper($enquiry->bank_name) }}</td>
                                <td>{{ $enquiry->region->name ?? 'N/A' }}</td>
                                <td>{{ $enquiry->phone }}</td>
                                <td>
                                    @if($enquiry->status == 'approved')
                                        <span class="badge badge-success-modern">
                                            <i class="bx bx-check-circle"></i> Approved
                                        </span>
                                    @elseif($enquiry->status == 'rejected')
                                        <span class="badge badge-danger-modern">
                                            <i class="bx bx-x-circle"></i> Rejected
                                        </span>
                                    @elseif($enquiry->status == 'assigned')
                                        <span class="badge badge-warning-modern">
                                            <i class="bx bx-user-check"></i> Assigned
                                        </span>
                                    @else
                                        <span class="badge badge-info-modern">
                                            <i class="bx bx-time"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class='bx bx-dots-vertical-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
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

<script>
    // URA SACCOS Brand Colors
    const brandColors = {
        primary: '#17479E',
        primaryDark: '#1e3c72',
        primaryLight: '#2a5298',
        accent: '#00BCD4',
        success: '#15ca20',
        warning: '#fd7e14',
        danger: '#d63384',
        info: '#0dcaf0',
        purple: '#6f42c1'
    };

    // Enquiries Chart
    const enquiryLabels = @json($enquiryTypeFrequency->pluck('type'));
    const enquiryData = @json($enquiryTypeFrequency->pluck('frequency'));

    const enquiriesCtx = document.getElementById('enquiriesChart').getContext('2d');
    const enquiriesChart = new Chart(enquiriesCtx, {
        type: 'doughnut',
        data: {
            labels: enquiryLabels.map(label => label.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: enquiryData,
                backgroundColor: [
                    brandColors.primary,
                    brandColors.accent,
                    brandColors.success,
                    brandColors.warning,
                    brandColors.danger,
                    brandColors.purple
                ],
                borderWidth: 0,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Removed Loan Applications Chart - replaced with Performance Metrics

    // Trends Chart using ApexCharts
    const trendsOptions = {
        series: [{
            name: 'Enquiries',
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 69, 73, 78]
        }, {
            name: 'Loan Applications',
            data: [35, 41, 36, 26, 45, 48, 52, 53, 41, 45, 48, 55]
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: [brandColors.primary, brandColors.accent],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                }
            }
        },
        tooltip: {
            theme: 'dark',
            x: {
                format: 'MMM'
            }
        },
        grid: {
            borderColor: '#f1f3f5',
            strokeDashArray: 4
        }
    };

    const trendsChart = new ApexCharts(document.querySelector("#trendsChart"), trendsOptions);
    trendsChart.render();

    // Pipeline View Toggles
    const pipelineControls = document.querySelectorAll('.btn-ura-outline');
    const contentViews = document.querySelectorAll('.content-view');
    
    pipelineControls.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            pipelineControls.forEach(b => b.classList.remove('active'));
            // Add active to clicked button
            this.classList.add('active');
            
            // Hide all views
            contentViews.forEach(v => v.classList.remove('active'));
            
            // Show selected view
            const viewType = this.getAttribute('data-view');
            const viewId = viewType + 'View';
            const targetView = document.getElementById(viewId);
            if (targetView) {
                targetView.classList.add('active');
                
                // Initialize chart if chart view is selected
                if (viewType === 'chart' && !window.pipelineCompactChart) {
                    initializePipelineCompactChart();
                }
            }
        });
    });

    // Initialize Compact Pipeline Chart
    function initializePipelineCompactChart() {
        const compactCtx = document.getElementById('loanPipelineCompactChart');
        if (compactCtx) {
            window.pipelineCompactChart = new Chart(compactCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Not Processed', 'Approved', 'Pending', 'Rejected', 'Disbursed', 'Paid'],
                    datasets: [{
                        label: 'Status',
                        data: [
                            {{ $loanPipelineData['not_processed'] }},
                            {{ $loanPipelineData['approved'] }},
                            {{ $loanPipelineData['pending'] }},
                            {{ $loanPipelineData['rejected'] }},
                            {{ $loanPipelineData['disbursed'] }},
                            {{ $loanPipelineData['paid'] }}
                        ],
                        backgroundColor: [
                            '#6c757d',
                            '#00BCD4',
                            '#FFC107',
                            '#DC3545',
                            '#17479E',
                            '#28A745'
                        ],
                        borderRadius: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(23, 71, 158, 0.9)',
                            padding: 10,
                            cornerRadius: 8,
                            titleFont: { size: 12 },
                            bodyFont: { size: 11 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [3, 3],
                                color: 'rgba(23, 71, 158, 0.05)'
                            },
                            ticks: {
                                font: { size: 10 },
                                color: '#666'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10 },
                                color: '#666',
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }
    }

    // Status Toggle Interactions
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            // You can add click actions here if needed
            console.log('Status clicked:', this.getAttribute('data-status'));
        });
    });

    // Modern Overdue Chart with URA SACCOS Branding
    const overdueLabels = @json($overdueData['labels'] ?? []);
    const overdueDataValues = @json($overdueData['data'] ?? []);
    const overdueCtx = document.getElementById('overdueChart');
    
    if (overdueCtx) {
        const overdueChart = new Chart(overdueCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: overdueLabels.length > 0 ? overdueLabels : ['No Data'],
                datasets: [{
                    label: 'Critical',
                    data: overdueDataValues.map(v => Math.floor(v * 0.3)), // Mock critical data
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: '#DC3545',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.8
                }, {
                    label: 'Warning',
                    data: overdueDataValues.map(v => Math.floor(v * 0.3)), // Mock warning data
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: '#FFC107',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.8
                }, {
                    label: 'Pending',
                    data: overdueDataValues.map(v => Math.ceil(v * 0.4)), // Mock pending data
                    backgroundColor: 'rgba(23, 71, 158, 0.8)',
                    borderColor: '#17479E',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const value = context.raw || 0;
                                const total = overdueDataValues.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `Overdue: ${value} enquiries (${percentage}% of total)`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            }
        });

        // Update total overdue count
        const totalOverdue = overdueDataValues.reduce((a, b) => a + b, 0);
        document.getElementById('totalOverdue').textContent = totalOverdue;
    }

    // Overdue Management Functions
    function sendReminders() {
        // Send reminder notifications
        alert('Reminder notifications sent to users with overdue enquiries');
    }

    function exportOverdueReport() {
        // Export detailed overdue report
        window.print(); // Simple implementation - replace with actual export logic
    }

    function notifyUser(userName) {
        // Send notification to specific user
        alert(`Notification sent to ${userName} regarding overdue enquiries`);
    }

    // Chart period toggle
    document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            // Refresh chart data based on period
            const period = this.getAttribute('data-period');
            console.log('Loading data for period:', period);
            // Implement data refresh logic here
        });
    });

    // Chart view toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const viewType = this.getAttribute('data-view');
            // Change chart type
            if (viewType === 'line' && window.overdueChart) {
                window.overdueChart.config.type = 'line';
                window.overdueChart.update();
            } else if (viewType === 'bar' && window.overdueChart) {
                window.overdueChart.config.type = 'bar';
                window.overdueChart.update();
            }
        });
    });

    // Legacy export function for compatibility
    function exportOverdueChart() {
        const canvas = document.getElementById('overdueChart');
        const url = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = url;
        a.download = 'overdue_enquiries_chart.png';
        a.click();
    }

    function refreshOverdueData() {
        location.reload();
    }

    // Initialize DataTable
    $(document).ready(function() {
        $('#enquiriesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn btn-sm btn-primary'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-success'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-danger'
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-info'
                }
            ],
            pageLength: 10,
            responsive: true,
            order: [[1, 'desc']],
            columnDefs: [
                {
                    targets: -1,
                    orderable: false
                }
            ]
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.6s ease';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card, .chart-card, .kpi-card').forEach(el => {
            observer.observe(el);
        });
    });

    // Auto-refresh dashboard data every 5 minutes for real-time updates
    setInterval(() => {
        // Reload the page to get fresh data from database
        location.reload();
    }, 300000); // 5 minutes
</script>

@endsection