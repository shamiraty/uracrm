<?php

require_once 'vendor/autoload.php';
use App\Models\LoanOffer;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Verifying Loan Data for Payoff Calculation ===\n\n";

// Test loans
$testLoans = [
    'URL013572' => [
        'expected_initial_balance' => 6397076.00,
        'expected_monthly_payment' => 177697.00,
        'expected_ded_balance' => 5508591.00,
        'expected_payoff' => 3774876.79
    ],
    'URL002224' => [
        'expected_initial_balance' => 18980000.00,
        'expected_ded_balance' => 12731520.70, // Calculated from payoff
        'expected_payoff' => 8724541.92
    ]
];

foreach ($testLoans as $loanNumber => $expected) {
    echo "Loan: $loanNumber\n";
    echo str_repeat("-", 60) . "\n";
    
    $loan = LoanOffer::where('loan_number', $loanNumber)->first();
    
    if ($loan) {
        echo "Database Values:\n";
        echo "  requested_amount: " . number_format($loan->requested_amount, 2) . "\n";
        echo "  desired_deductible_amount: " . number_format($loan->desired_deductible_amount, 2) . "\n";
        echo "  outstanding_balance: " . number_format($loan->outstanding_balance, 2) . "\n";
        echo "  settlement_amount: " . number_format($loan->settlement_amount, 2) . "\n";
        echo "  installments_paid: " . $loan->installments_paid . "\n";
        echo "  tenure: " . $loan->tenure . "\n";
        echo "  state: " . $loan->state . "\n";
        echo "  loan_purpose: " . $loan->loan_purpose . "\n\n";
        
        echo "Expected Values:\n";
        foreach ($expected as $key => $value) {
            echo "  $key: " . number_format($value, 2) . "\n";
        }
        
        // Calculate payoff
        $dedBalanceAmount = (float)$loan->outstanding_balance;
        $payoffFactor = 0.685271;
        $calculatedPayoff = round($dedBalanceAmount * $payoffFactor, 2);
        
        echo "\nPayoff Calculation:\n";
        echo "  Outstanding Balance: " . number_format($dedBalanceAmount, 2) . "\n";
        echo "  × Factor: $payoffFactor\n";
        echo "  = Calculated Payoff: " . number_format($calculatedPayoff, 2) . "\n";
        
        if (isset($expected['expected_payoff'])) {
            $expectedPayoff = $expected['expected_payoff'];
            echo "  Expected Payoff: " . number_format($expectedPayoff, 2) . "\n";
            $diff = abs($calculatedPayoff - $expectedPayoff);
            if ($diff < 1) {
                echo "  ✓ MATCH! Difference: " . number_format($diff, 2) . "\n";
            } else {
                echo "  ✗ MISMATCH! Difference: " . number_format($diff, 2) . "\n";
                
                // Check what outstanding_balance should be
                $requiredOutstanding = $expectedPayoff / $payoffFactor;
                echo "\n  To get expected payoff, outstanding_balance should be: " . number_format($requiredOutstanding, 2) . "\n";
                
                // Check if it matches expected_ded_balance
                if (isset($expected['expected_ded_balance'])) {
                    $dedBalanceDiff = abs($requiredOutstanding - $expected['expected_ded_balance']);
                    if ($dedBalanceDiff < 1) {
                        echo "  This matches the expected ded_balance_amount from Excel!\n";
                        echo "  → The outstanding_balance needs to be updated to: " . number_format($expected['expected_ded_balance'], 2) . "\n";
                    }
                }
            }
        }
    } else {
        echo "  LOAN NOT FOUND IN DATABASE\n";
    }
    
    echo "\n";
}

echo "=== RECOMMENDATION ===\n";
echo "If the payoff calculation is incorrect, check that:\n";
echo "1. outstanding_balance = ded_balance_amount from Excel\n";
echo "2. The import process correctly maps ded_balance_amount → outstanding_balance\n";
echo "3. No manual updates have changed the outstanding_balance value\n";