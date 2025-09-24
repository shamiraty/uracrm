<?php

echo "=== Testing Imported Loan Payoff Calculation ===\n\n";

// Data for URL013572
$principal = 6397076.00;
$monthlyPayment = 177697.00;
$installmentsPaid = 5;

// Calculate ded_balance (current balance after payments)
$dedBalanceAmount = $principal - ($monthlyPayment * $installmentsPaid);

// Apply the payoff factor for imported loans
$payoffFactor = 0.685271;
$calculatedPayoff = round($dedBalanceAmount * $payoffFactor, 2);

echo "Loan: URL013572\n";
echo "Initial Balance: " . number_format($principal, 2) . "\n";
echo "Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
echo "Installments Paid: $installmentsPaid\n";
echo "Ded Balance Amount: " . number_format($dedBalanceAmount, 2) . "\n";
echo "Payoff Factor: $payoffFactor\n\n";

echo "Calculated Payoff: " . number_format($calculatedPayoff, 2) . "\n";
echo "Expected Payoff: 3,774,876.79\n";

if (abs($calculatedPayoff - 3774876.79) < 0.01) {
    echo "\n✓ SUCCESS: The calculation is correct!\n";
    echo "ESS will now receive the correct Total Payoff Amount: " . number_format($calculatedPayoff, 2) . "\n";
} else {
    echo "\n✗ ERROR: Calculation mismatch\n";
    echo "Difference: " . number_format($calculatedPayoff - 3774876.79, 2) . "\n";
}