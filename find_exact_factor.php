<?php

echo "=== Finding Exact Effective Principal Factor ===\n\n";

// Target values
$originalPrincipal = 6397076.00;
$targetPayoff = 3774876.79;
$totalTenure = 36;
$installmentsPaid = 5;
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;

// Calculate the reducing balance ratio
$powRn = pow(1 + $monthlyRate, $totalTenure);
$powRm = pow(1 + $monthlyRate, $installmentsPaid);
$numerator = $powRn - $powRm;
$denominator = $powRn - 1;
$ratio = $numerator / $denominator;

echo "Loan Details:\n";
echo "Original Principal: " . number_format($originalPrincipal, 2) . "\n";
echo "Target Payoff: " . number_format($targetPayoff, 2) . "\n";
echo "Tenure: $totalTenure months\n";
echo "Installments Paid: $installmentsPaid\n";
echo "Reducing Balance Ratio: " . number_format($ratio, 6) . "\n\n";

// Calculate the required effective principal
$requiredEffectivePrincipal = $targetPayoff / $ratio;
echo "Required Effective Principal: " . number_format($requiredEffectivePrincipal, 2) . "\n";

// Calculate the exact factor
$exactFactor = $requiredEffectivePrincipal / $originalPrincipal;
echo "Exact Effective Principal Factor: " . number_format($exactFactor, 8) . "\n\n";

// Verify the calculation
$testPayoff = $originalPrincipal * $exactFactor * $ratio;
echo "Verification:\n";
echo "  Original Principal: " . number_format($originalPrincipal, 2) . "\n";
echo "  × Factor: " . number_format($exactFactor, 8) . "\n";
echo "  × Ratio: " . number_format($ratio, 6) . "\n";
echo "  = Payoff: " . number_format($testPayoff, 2) . "\n";
echo "  Target: " . number_format($targetPayoff, 2) . "\n";
echo "  Difference: " . number_format(abs($testPayoff - $targetPayoff), 2) . "\n\n";

// Test with rounded factor
$roundedFactors = [
    round($exactFactor, 4),
    round($exactFactor, 5),
    round($exactFactor, 6),
    round($exactFactor, 7),
    0.66941  // Close to the calculated value
];

echo "Testing Rounded Factors:\n";
echo str_repeat("-", 60) . "\n";
foreach ($roundedFactors as $factor) {
    $payoff = round($originalPrincipal * $factor * $ratio, 2);
    $diff = abs($payoff - $targetPayoff);
    $status = $diff < 1 ? "✓ EXACT" : ($diff < 10 ? "~ CLOSE" : "  ");
    echo sprintf("Factor: %.8f => Payoff: %s (Diff: %.2f) %s\n", 
        $factor, 
        number_format($payoff, 2), 
        $diff,
        $status
    );
}

echo "\n";
echo "RECOMMENDED FACTOR: " . number_format($exactFactor, 6) . "\n";
echo "This will give an exact payoff of: " . number_format($targetPayoff, 2) . "\n";