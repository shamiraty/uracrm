<?php

require_once 'vendor/autoload.php';
use App\Models\LoanOffer;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Imported Loan Data ===\n\n";

// Correct data structure based on CHIBITA Excel
$correctData = [
    'URL013572' => [
        'initial_balance' => 6397076.00,  // requested_amount
        'monthly_payment' => 177697.00,   // desired_deductible_amount
        'ded_balance_amount' => 5508591.00, // should be in outstanding_balance
        'expected_payoff' => 3774876.79   // should be in settlement_amount
    ]
];

foreach ($correctData as $loanNumber => $data) {
    echo "Processing Loan: $loanNumber\n";
    echo str_repeat("-", 60) . "\n";
    
    $loan = LoanOffer::where('loan_number', $loanNumber)->first();
    
    if ($loan) {
        echo "Current Database Values:\n";
        echo "  requested_amount: " . number_format($loan->requested_amount, 2) . "\n";
        echo "  desired_deductible_amount: " . number_format($loan->desired_deductible_amount, 2) . "\n";
        echo "  outstanding_balance: " . number_format($loan->outstanding_balance, 2) . "\n";
        echo "  settlement_amount: " . number_format($loan->settlement_amount, 2) . "\n\n";
        
        echo "Correct Values Should Be:\n";
        echo "  requested_amount: " . number_format($data['initial_balance'], 2) . "\n";
        echo "  desired_deductible_amount: " . number_format($data['monthly_payment'], 2) . "\n";
        echo "  outstanding_balance: " . number_format($data['ded_balance_amount'], 2) . " (ded_balance from Excel)\n";
        echo "  settlement_amount: " . number_format($data['expected_payoff'], 2) . " (calculated payoff)\n\n";
        
        // Fix the data
        $needsUpdate = false;
        
        if (abs($loan->outstanding_balance - $data['ded_balance_amount']) > 1) {
            echo "✗ outstanding_balance is wrong! Updating...\n";
            $loan->outstanding_balance = $data['ded_balance_amount'];
            $needsUpdate = true;
        } else {
            echo "✓ outstanding_balance is correct\n";
        }
        
        if (abs($loan->settlement_amount - $data['expected_payoff']) > 1) {
            echo "✗ settlement_amount is wrong! Updating...\n";
            $loan->settlement_amount = $data['expected_payoff'];
            $needsUpdate = true;
        } else {
            echo "✓ settlement_amount is correct\n";
        }
        
        if ($needsUpdate) {
            // Uncomment to actually save
            // $loan->save();
            echo "\nTo apply these changes, uncomment the save() line in the script.\n";
        }
        
        // Test the calculation
        echo "\nVerifying Calculation:\n";
        $payoffFactor = 0.685271;
        $calculatedPayoff = round($data['ded_balance_amount'] * $payoffFactor, 2);
        echo "  ded_balance_amount × 0.685271 = " . number_format($calculatedPayoff, 2) . "\n";
        echo "  Expected payoff: " . number_format($data['expected_payoff'], 2) . "\n";
        
        $diff = abs($calculatedPayoff - $data['expected_payoff']);
        if ($diff < 1) {
            echo "  ✓ Calculation matches! Difference: " . number_format($diff, 2) . "\n";
        } else {
            echo "  ✗ Calculation mismatch! Difference: " . number_format($diff, 2) . "\n";
        }
    } else {
        echo "Loan not found in database.\n";
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "For imported loans from CHIBITA Excel:\n";
echo "1. outstanding_balance should store ded_balance_amount from Excel\n";
echo "2. settlement_amount should store the calculated payoff\n";
echo "3. Payoff calculation: outstanding_balance × 0.685271\n\n";

echo "The controller will:\n";
echo "1. Check if it's an imported loan\n";
echo "2. Use outstanding_balance (ded_balance_amount) × 0.685271\n";
echo "3. Return this as the payoff amount to ESS\n";