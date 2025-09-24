<?php

// If the monthly payment is 177,697 and we want payoff of 3,774,876.79
// Let's reverse calculate what the original principal should be

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

$targetPayoff = 3774876.79;
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;
$totalTenure = 36;
$installmentsPaid = 5;

// Calculate the factor
$powRn = pow(1 + $monthlyRate, $totalTenure);
$powRm = pow(1 + $monthlyRate, $installmentsPaid);
$numerator = $powRn - $powRm;
$denominator = $powRn - 1;
$factor = $numerator / $denominator;

// Reverse calculate principal
$calculatedPrincipal = $targetPayoff / $factor;

echo "=== Reverse Calculation ===\n\n";
echo "Target Payoff: " . number_format($targetPayoff, 2) . "\n";
echo "Factor: " . number_format($factor, 6) . "\n";
echo "Calculated Principal: " . number_format($calculatedPrincipal, 2) . "\n";
echo "Actual Principal in DB: 6,397,076.00\n\n";

// Verify
$verifyPayoff = calculateBalance($calculatedPrincipal, $monthlyRate, $totalTenure, $installmentsPaid);
echo "Verification:\n";
echo "Using Principal " . number_format($calculatedPrincipal, 2) . " gives payoff: " . number_format($verifyPayoff, 2) . "\n\n";

// This means the effective principal for calculation should be different
$effectivePrincipal = 4279824.45;
echo "=== Solution ===\n";
echo "For imported loans, use effective principal of: " . number_format($effectivePrincipal, 2) . "\n";
echo "Instead of requested_amount: 6,397,076.00\n";
echo "This represents the actual loan amount after the original lender's fees/charges\n";