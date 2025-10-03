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

    .mortgage-header {
        background: var(--ura-gradient);
        padding: 2rem 0;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .mortgage-header::before {
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

    .calculator-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--ura-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .calculator-header {
        background: var(--ura-gradient-light);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(23, 71, 158, 0.1);
    }

    .calculator-title {
        color: var(--ura-primary);
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .calculator-body {
        padding: 2rem;
    }

    .form-label {
        color: var(--ura-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        border: 2px solid rgba(23, 71, 158, 0.1);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--ura-primary);
        box-shadow: 0 0 0 0.2rem rgba(23, 71, 158, 0.25);
    }

    .input-group-text {
        background: var(--ura-gradient-light);
        border: 2px solid rgba(23, 71, 158, 0.1);
        color: var(--ura-primary);
        font-weight: 600;
        border-radius: 12px 0 0 12px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .modern-btn {
        border: none;
        border-radius: 10px;
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

    .modern-btn-outline {
        background: transparent;
        color: var(--ura-primary);
        border: 2px solid var(--ura-primary);
    }

    .modern-btn-outline:hover {
        background: var(--ura-primary);
        color: white;
        transform: translateY(-1px);
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #f04141 0%, #ff5252 100%);
        color: white;
        border: none;
    }

    .modern-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(240, 65, 65, 0.3);
        color: white;
    }

    .modern-btn-warning {
        background: linear-gradient(135deg, #ffce00 0%, #ffd54f 100%);
        color: #333;
        border: none;
    }

    .modern-btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 206, 0, 0.3);
        color: #333;
    }

    .allowance-field {
        background: var(--ura-gradient-light);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .allowance-field:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 71, 158, 0.1);
    }

    .allowance-counter {
        position: absolute;
        top: -8px;
        left: 1rem;
        background: var(--ura-primary);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--ura-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--ura-shadow-hover);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ura-gradient);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--ura-gradient-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--ura-primary);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ura-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        margin: 0;
    }
</style>

<div class="container-fluid">
    <!-- Modern Header -->
    <div class="mortgage-header">
        <h1 class="header-title">
            <i class="bx bx-calculator"></i>
            Mortgage Loan Calculator
        </h1>
        <p class="header-subtitle">
            Calculate your loan eligibility with advanced financial modeling
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-wallet"></i>
            </div>
            <div class="stat-value">TZS</div>
            <div class="stat-label">Currency</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-trending-up"></i>
            </div>
            <div class="stat-value">Smart</div>
            <div class="stat-label">Calculator</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-time"></i>
            </div>
            <div class="stat-value">Real-time</div>
            <div class="stat-label">Results</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bx bx-check-shield"></i>
            </div>
            <div class="stat-value">Secure</div>
            <div class="stat-label">Calculation</div>
        </div>
    </div>

    <!-- Calculator Card -->
    <div class="calculator-card">
        <div class="calculator-header">
            <h5 class="calculator-title">
                <i class="bx bx-calculator"></i>
                Loan Calculation Parameters
            </h5>
        </div>
        <div class="calculator-body">
            <form method="post" action="{{ url('/calculate-loanable-amount') }}" id="mortgageForm">
                @csrf

                <!-- Basic Salary -->
                <div class="mb-4">
                    <label for="basic_salary" class="form-label">
                        <i class="bx bx-money"></i>
                        Basic Salary
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">TSHS</span>
                        <input type="number" name="basic_salary" id="basic_salary"
                               placeholder="Enter your basic salary" required
                               class="form-control" aria-label="Basic Salary">
                    </div>
                </div>

                <!-- Allowances Section -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bx bx-plus-circle"></i>
                        Allowances
                    </label>
                    <div id="allowanceFields">
                        <!-- Dynamic allowance fields will be added here -->
                        <div class="allowance-field">
                            <div class="allowance-counter">1</div>
                            <div class="input-group">
                                <input type="number" name="allowances[]" placeholder="Enter allowance amount"
                                       required class="form-control">
                                <button type="button" class="modern-btn modern-btn-danger remove-field">
                                    <i class="bx bx-trash"></i>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" id="addAllowance" class="modern-btn modern-btn-outline">
                            <i class="bx bx-plus"></i>
                            Add Another Allowance
                        </button>
                    </div>
                </div>

                <!-- Take Home Pay -->
                <div class="mb-4">
                    <label for="take_home" class="form-label">
                        <i class="bx bx-wallet"></i>
                        Take Home Pay
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">TSHS</span>
                        <input type="number" name="take_home" id="take_home"
                               placeholder="Enter your take home pay" required
                               class="form-control" aria-label="Take Home Pay">
                    </div>
                </div>

                <!-- Repayment Period -->
                <div class="mb-4">
                    <label for="number_of_months" class="form-label">
                        <i class="bx bx-calendar"></i>
                        Repayment Period
                    </label>
                    <div class="input-group">
                        <input type="number" name="number_of_months" id="number_of_months"
                               placeholder="Number of months" required
                               class="form-control" aria-label="Repayment Period">
                        <span class="input-group-text">Months</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="modern-btn modern-btn-primary">
                        <i class="bx bx-calculator"></i>
                        Calculate Loan Amount
                    </button>
                    <button type="reset" class="modern-btn modern-btn-warning">
                        <i class="bx bx-refresh"></i>
                        Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let allowanceCount = 1;

        // Update counter numbers
        function updateCounters() {
            $('.allowance-field').each(function(index) {
                $(this).find('.allowance-counter').text(index + 1);
            });
        }

        // Add new allowance field
        $('#addAllowance').click(function() {
            allowanceCount++;
            var newField = `
                <div class="allowance-field">
                    <div class="allowance-counter">${allowanceCount}</div>
                    <div class="input-group">
                        <input type="number" name="allowances[]" placeholder="Enter allowance amount"
                               required class="form-control">
                        <button type="button" class="modern-btn modern-btn-danger remove-field">
                            <i class="bx bx-trash"></i>
                            Remove
                        </button>
                    </div>
                </div>`;
            $('#allowanceFields').append(newField);
            updateCounters();

            // Animate new field
            $('.allowance-field').last().hide().fadeIn(300);
        });

        // Remove allowance field
        $(document).on('click', '.remove-field', function() {
            const field = $(this).closest('.allowance-field');

            // Only allow removal if more than one field exists
            if ($('.allowance-field').length > 1) {
                field.fadeOut(300, function() {
                    $(this).remove();
                    updateCounters();
                });
            } else {
                // Show warning if trying to remove the last field
                $(this).addClass('shake');
                setTimeout(() => {
                    $(this).removeClass('shake');
                }, 500);
            }
        });

        // Add CSS animation for shake effect
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
                .shake {
                    animation: shake 0.5s ease-in-out;
                }
            `)
            .appendTo('head');

        // Form validation enhancement
        $('#mortgageForm').on('submit', function(e) {
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            // Add loading state
            submitBtn.html('<i class="bx bx-loader-alt bx-spin"></i> Calculating...');
            submitBtn.prop('disabled', true);

            // Re-enable after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }, 3000);
        });

        // Real-time validation
        $('input[type="number"]').on('input', function() {
            const value = parseFloat($(this).val());
            if (value < 0) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Smooth focus transitions
        $('.form-control').on('focus', function() {
            $(this).parent().parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().parent().removeClass('focused');
        });
    });
</script>

@endsection
