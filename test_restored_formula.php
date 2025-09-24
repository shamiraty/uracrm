<?php

echo "=== Testing Restored Partial Payment Formula ===\n\n";

/**
 * Calculate partial payment balance using the formula from EmployeeLoanController13
 * B = P₀ * [ (1+r)^n - (1+r)^m ] / [ (1+r)^n - 1 ]
 */
function calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid) {
    // No balance left if all payments made
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    
    // Early exit for invalid rate or tenure
    if ($monthlyRate <= 0 || $totalTenure <= 0) {
        return 0.0;
    }
    
    // Calculate the compound interest factors
    $powRn = pow(1 + $monthlyRate, $totalTenure);
    $powRm = pow(1 + $monthlyRate, $installmentsPaid);
    
    // Use the formula to calculate the remaining balance
    $numerator = $powRn - $powRm;
    $denominator = $powRn - 1;
    
    // Avoid division by zero
    if ($denominator == 0) {
        return 0.0;
    }
    
    return $principal * ($numerator / $denominator);
}

// Test Case 1: URL013572 (Imported Loan)
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║ Test Case 1: URL013572 (Using Restored Formula)             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$principal1 = 6397076.00;
$annualRate1 = 12;
$monthlyRate1 = $annualRate1 / 100 / 12;
$totalTenure1 = 36;
$installmentsPaid1 = 5;

echo "Loan Parameters:\n";
echo "├─ Principal: " . number_format($principal1, 2) . "\n";
echo "├─ Annual Rate: $annualRate1%\n";
echo "├─ Monthly Rate: " . number_format($monthlyRate1, 6) . "\n";
echo "├─ Total Tenure: $totalTenure1 months\n";
echo "└─ Installments Paid: $installmentsPaid1\n\n";

$payoff1 = calculatePartialPaymentBalance($principal1, $monthlyRate1, $totalTenure1, $installmentsPaid1);

echo "Calculation Details:\n";
$powRn = pow(1 + $monthlyRate1, $totalTenure1);
$powRm = pow(1 + $monthlyRate1, $installmentsPaid1);
echo "├─ (1 + r)^n = " . number_format($powRn, 6) . "\n";
echo "├─ (1 + r)^m = " . number_format($powRm, 6) . "\n";
echo "├─ Numerator: (1+r)^n - (1+r)^m = " . number_format($powRn - $powRm, 6) . "\n";
echo "├─ Denominator: (1+r)^n - 1 = " . number_format($powRn - 1, 6) . "\n";
echo "└─ Ratio: " . number_format(($powRn - $powRm) / ($powRn - 1), 6) . "\n\n";

echo "Results:\n";
echo "├─ Calculated Payoff: " . number_format($payoff1, 2) . "\n";
echo "├─ Previous Expected: 3,774,876.79\n";
echo "└─ Method: Partial Payment Formula B = P₀ * [(1+r)^n - (1+r)^m] / [(1+r)^n - 1]\n\n";

// Test Case 2: Regular Loan
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║ Test Case 2: Regular URA Loan                               ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$principal2 = 1000000.00;
$annualRate2 = 12;
$monthlyRate2 = $annualRate2 / 100 / 12;
$totalTenure2 = 12;
$installmentsPaid2 = 3;

echo "Loan Parameters:\n";
echo "├─ Principal: " . number_format($principal2, 2) . "\n";
echo "├─ Annual Rate: $annualRate2%\n";
echo "├─ Total Tenure: $totalTenure2 months\n";
echo "└─ Installments Paid: $installmentsPaid2\n\n";

$payoff2 = calculatePartialPaymentBalance($principal2, $monthlyRate2, $totalTenure2, $installmentsPaid2);

echo "Results:\n";
echo "├─ Calculated Payoff: " . number_format($payoff2, 2) . "\n";
echo "└─ Method: Partial Payment Formula\n\n";

// Test Case 3: Large Loan
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║ Test Case 3: Large Loan (URL002224 type)                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$principal3 = 18980000.00;
$annualRate3 = 12;
$monthlyRate3 = $annualRate3 / 100 / 12;
$totalTenure3 = 49;
$installmentsPaid3 = 15;

echo "Loan Parameters:\n";
echo "├─ Principal: " . number_format($principal3, 2) . "\n";
echo "├─ Annual Rate: $annualRate3%\n";
echo "├─ Total Tenure: $totalTenure3 months\n";
echo "└─ Installments Paid: $installmentsPaid3\n\n";

$payoff3 = calculatePartialPaymentBalance($principal3, $monthlyRate3, $totalTenure3, $installmentsPaid3);

echo "Results:\n";
echo "├─ Calculated Payoff: " . number_format($payoff3, 2) . "\n";
echo "└─ Method: Partial Payment Formula\n\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                           SUMMARY                           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "✓ Implementation: RESTORED from EmployeeLoanController13.php\n";
echo "✓ Formula: B = P₀ * [(1+r)^n - (1+r)^m] / [(1+r)^n - 1]\n";
echo "  Where:\n";
echo "  - P₀ = Original principal (requested_amount)\n";
echo "  - r = Monthly interest rate\n";
echo "  - n = Total tenure\n";
echo "  - m = Installments paid\n\n";

echo "Controller Path: app/Http/Controllers/EmployeeLoanController.php\n";
echo "Method: handleTopUpPayoffBalance() -> calculatePartialPaymentBalance()\n\n";

echo "=== Test Complete ===\n";