<?php

// Function to calculate balance with different rates
function calculateBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid) {
    if ($installmentsPaid >= $totalTenure) return 0.0;
    if ($monthlyRate <= 0 || $totalTenure <= 0) return 0.0;
    
    $powRn = pow(1 + $monthlyRate, $totalTenure);
    $powRm = pow(1 + $monthlyRate, $installmentsPaid);
    
    $numerator = $powRn - $powRm;
    $denominator = $powRn - 1;
    
    if ($denominator == 0) return 0.0;
    
    return $principal * ($numerator / $denominator);
}

// Known values
$principal = 6397076.00;
$totalTenure = 36;
$installmentsPaid = 5;
$targetPayoff = 3774876.79;

echo "=== Finding the correct interest rate ===\n\n";
echo "Principal: " . number_format($principal, 2) . "\n";
echo "Total Tenure: $totalTenure months\n";
echo "Installments Paid: $installmentsPaid\n";
echo "Target Payoff: " . number_format($targetPayoff, 2) . "\n\n";

// Try different annual rates
echo "Testing different annual interest rates:\n";
for ($annualRate = 1; $annualRate <= 30; $annualRate += 0.5) {
    $monthlyRate = $annualRate / 100 / 12;
    $balance = calculateBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);
    $diff = abs($balance - $targetPayoff);
    
    if ($diff < 100) {
        echo sprintf("Annual Rate: %.1f%% => Payoff: %s (Diff: %.2f)\n", 
            $annualRate, 
            number_format($balance, 2), 
            $balance - $targetPayoff
        );
    }
}

// Try with different principal amounts (maybe net amount after fees?)
echo "\n=== Testing if principal should be different ===\n";
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;

// Calculate what principal would give us the target
for ($testPrincipal = 3000000; $testPrincipal <= 7000000; $testPrincipal += 100000) {
    $balance = calculateBalance($testPrincipal, $monthlyRate, $totalTenure, $installmentsPaid);
    $diff = abs($balance - $targetPayoff);
    
    if ($diff < 10000) {
        echo sprintf("Principal: %s => Payoff: %s (Diff: %.2f)\n", 
            number_format($testPrincipal, 2), 
            number_format($balance, 2), 
            $balance - $targetPayoff
        );
    }
}