<?php

// Test what the current formula produces
function calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid) {
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    if ($monthlyRate <= 0 || $totalTenure <= 0) {
        return 0.0;
    }
    
    $powRn = pow(1 + $monthlyRate, $totalTenure);
    $powRm = pow(1 + $monthlyRate, $installmentsPaid);
    
    $numerator = $powRn - $powRm;
    $denominator = $powRn - 1;
    
    if ($denominator == 0) {
        return 0.0;
    }
    
    return $principal * ($numerator / $denominator);
}

// Loan URL013572 data
$principal = 6397076.00;  // requested_amount
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;  // 0.01
$totalTenure = 36;
$installmentsPaid = 5;

echo "=== Current Payoff Calculation for URL013572 ===\n\n";
echo "Principal (requested_amount): " . number_format($principal, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Monthly Rate: " . ($monthlyRate * 100) . "%\n";
echo "Total Tenure: $totalTenure months\n";
echo "Installments Paid: $installmentsPaid\n\n";

$payoff = calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);

echo "Calculated Total Payoff Amount: " . number_format($payoff, 2) . "\n";
echo "Expected Total Payoff Amount: 3,774,876.79\n\n";

if (abs($payoff - 3774876.79) < 0.01) {
    echo "✓ SUCCESS: The formula is producing the correct result!\n";
} else {
    echo "✗ ERROR: The formula is NOT producing the correct result\n";
    echo "Difference: " . number_format($payoff - 3774876.79, 2) . "\n";
}