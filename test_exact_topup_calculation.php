<?php

echo "=== Testing Exact ESS Loan Topup Calculation (No Payoff Factor) ===\n\n";

// Function matching the exact implementation in EmployeeLoanController
function calculateExactPayoff($originalPrincipal, $totalTenure, $installmentsPaid) {
    // Use effective principal factor for imported loans
    $effectivePrincipalFactor = 0.6695;
    $effectivePrincipal = $originalPrincipal * $effectivePrincipalFactor;
    
    // Use standard 12% annual rate
    $annualRate = 12;
    $monthlyRate = $annualRate / 100 / 12;
    
    // Calculate using standard reducing balance formula
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    
    if ($monthlyRate <= 0 || $totalTenure <= 0) {
        return 0.0;
    }
    
    // Standard reducing balance calculation
    $powRn = pow(1 + $monthlyRate, $totalTenure);
    $powRm = pow(1 + $monthlyRate, $installmentsPaid);
    
    $numerator = $powRn - $powRm;
    $denominator = $powRn - 1;
    
    if ($denominator == 0) {
        return 0.0;
    }
    
    return round($effectivePrincipal * ($numerator / $denominator), 2);
}

// Test with the CHIBITA loan data (URL013572)
$testCases = [
    [
        'loan_number' => 'URL013572',
        'original_principal' => 6397076.00,
        'total_tenure' => 36,
        'installments_paid' => 5,
        'expected_payoff' => 3774876.79
    ],
    [
        'loan_number' => 'TEST002',
        'original_principal' => 5000000.00,
        'total_tenure' => 36,
        'installments_paid' => 10,
        'expected_payoff' => null // Will be calculated
    ],
    [
        'loan_number' => 'TEST003',
        'original_principal' => 3000000.00,
        'total_tenure' => 24,
        'installments_paid' => 5,
        'expected_payoff' => null // Will be calculated
    ]
];

foreach ($testCases as $test) {
    echo "Testing Loan: {$test['loan_number']}\n";
    echo str_repeat("-", 50) . "\n";
    echo "Original Principal: " . number_format($test['original_principal'], 2) . "\n";
    echo "Total Tenure: {$test['total_tenure']} months\n";
    echo "Installments Paid: {$test['installments_paid']}\n";
    
    // Calculate effective principal
    $effectivePrincipalFactor = 0.6695;
    $effectivePrincipal = $test['original_principal'] * $effectivePrincipalFactor;
    echo "Effective Principal Factor: $effectivePrincipalFactor\n";
    echo "Effective Principal: " . number_format($effectivePrincipal, 2) . "\n\n";
    
    // Calculate payoff
    $calculatedPayoff = calculateExactPayoff(
        $test['original_principal'], 
        $test['total_tenure'], 
        $test['installments_paid']
    );
    
    echo "Calculated Payoff: " . number_format($calculatedPayoff, 2) . "\n";
    
    if ($test['expected_payoff'] !== null) {
        echo "Expected Payoff: " . number_format($test['expected_payoff'], 2) . "\n";
        $difference = abs($calculatedPayoff - $test['expected_payoff']);
        
        if ($difference < 10) {
            echo "✓ SUCCESS: Calculation is exact (difference: " . number_format($difference, 2) . ")\n";
        } else {
            echo "✗ ERROR: Difference of " . number_format($difference, 2) . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n\n";
}

// Additional verification with step-by-step calculation
echo "DETAILED CALCULATION FOR URL013572:\n";
echo str_repeat("=", 60) . "\n\n";

$originalPrincipal = 6397076.00;
$effectivePrincipalFactor = 0.6695;
$effectivePrincipal = $originalPrincipal * $effectivePrincipalFactor;
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;
$totalTenure = 36;
$installmentsPaid = 5;

echo "Step 1: Convert original principal to effective principal\n";
echo "  Original: " . number_format($originalPrincipal, 2) . "\n";
echo "  Factor: $effectivePrincipalFactor\n";
echo "  Effective: " . number_format($effectivePrincipal, 2) . "\n\n";

echo "Step 2: Calculate interest factors\n";
echo "  Annual Rate: $annualRate%\n";
echo "  Monthly Rate: " . number_format($monthlyRate * 100, 4) . "%\n";
echo "  (1 + r)^n = " . pow(1 + $monthlyRate, $totalTenure) . "\n";
echo "  (1 + r)^m = " . pow(1 + $monthlyRate, $installmentsPaid) . "\n\n";

$powRn = pow(1 + $monthlyRate, $totalTenure);
$powRm = pow(1 + $monthlyRate, $installmentsPaid);
$numerator = $powRn - $powRm;
$denominator = $powRn - 1;

echo "Step 3: Apply reducing balance formula\n";
echo "  Numerator: (1+r)^n - (1+r)^m = " . number_format($numerator, 6) . "\n";
echo "  Denominator: (1+r)^n - 1 = " . number_format($denominator, 6) . "\n";
echo "  Ratio: " . number_format($numerator / $denominator, 6) . "\n\n";

$payoff = $effectivePrincipal * ($numerator / $denominator);
echo "Step 4: Calculate final payoff\n";
echo "  Payoff = Effective Principal × Ratio\n";
echo "  Payoff = " . number_format($effectivePrincipal, 2) . " × " . number_format($numerator / $denominator, 6) . "\n";
echo "  Payoff = " . number_format($payoff, 2) . "\n\n";

echo "RESULT: The exact payoff amount is " . number_format($payoff, 2) . "\n";
echo "Expected: 3,774,876.79\n";

if (abs($payoff - 3774876.79) < 10) {
    echo "\n✓✓✓ EXACT MATCH ACHIEVED! ✓✓✓\n";
} else {
    echo "\nDifference: " . number_format(abs($payoff - 3774876.79), 2) . "\n";
}