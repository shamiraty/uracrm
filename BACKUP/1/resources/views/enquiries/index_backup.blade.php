@extends('layouts.app')

@section('title', 'Enquiries Management')

@section('content')
<style>
    :root {
        --ura-primary: #17479e;
        --ura-primary-dark: #0d2c5f;
        --ura-primary-light: #1f5bb8;
        --ura-accent: #87CEEB;
        --ura-accent-light: #a4d9ee;
        --ura-success: #10dc60;
        --ura-warning: #ffce00;
        --ura-danger: #f04141;
        --ura-white: #ffffff;
        --ura-bg-light: #f8f9fa;
        --ura-shadow: 0 4px 15px rgba(23, 71, 158, 0.1);
    }

    /* Modern Card Styles */
    .modern-card {
        background: var(--ura-white);
        border-radius: 16px;
        box-shadow: var(--ura-shadow);
        border: 1px solid rgba(23, 71, 158, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 71, 158, 0.15);
    }

    /* Analytics Cards */
    .analytics-card {
        background: linear-gradient(135deg, var(--ura-primary) 0%, var(--ura-primary-light) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease;
        cursor: pointer;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .analytics-card:hover {
        transform: translateY(-3px);
    }

    .analytics-card.success {
        background: linear-gradient(135deg, var(--ura-success) 0%, #0bb347 100%);
    }

    .analytics-card.warning {
        background: linear-gradient(135deg, var(--ura-warning) 0%, #e6b800 100%);
        color: #2d3436;
    }

    .analytics-card.danger {
        background: linear-gradient(135deg, var(--ura-danger) 0%, #d63031 100%);
    }

    .analytics-card.info {
        background: linear-gradient(135deg, var(--ura-accent) 0%, #74b9ff 100%);
        color: #2d3436;
    }

    /* REST OF THE FILE CONTENT FROM ORIGINAL... */