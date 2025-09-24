<?php

echo "=== Analyzing Payoff Calculation Issue ===\n\n";

// Test Case 1: URL013572
echo "LOAN URL013572:\n";
echo str_repeat("=", 60) . "\n";
$principal1 = 6397076.00;
$monthlyPayment1 = 177697.00;
$tenure1 = 36;
$installmentsPaid1 = 5;
$expectedPayoff1 = 3774876.79;

// What would be the simple balance?
$simpleBalance1 = $principal1 - ($monthlyPayment1 * $installmentsPaid1);
echo "Principal: " . number_format($principal1, 2) . "\n";
echo "Monthly Payment: " . number_format($monthlyPayment1, 2) . "\n";
echo "Installments Paid: $installmentsPaid1\n";
echo "Simple Balance (Principal - Payments): " . number_format($simpleBalance1, 2) . "\n";
echo "Expected Payoff: " . number_format($expectedPayoff1, 2) . "\n";

// What's the ratio?
$ratio1 = $expectedPayoff1 / $simpleBalance1;
echo "Ratio (Expected/Simple): " . number_format($ratio1, 6) . "\n\n";

// Test Case 2: URL002224  
echo "LOAN URL002224:\n";
echo str_repeat("=", 60) . "\n";
$principal2 = 18980000.00;
$expectedPayoff2 = 8724541.92; // Correcting the decimal point
$tenure2 = 49;
$installmentsPaid2 = 15;

echo "Principal: " . number_format($principal2, 2) . "\n";
echo "Tenure: $tenure2 months\n";
echo "Installments Paid: $installmentsPaid2\n";
echo "Expected Payoff: " . number_format($expectedPayoff2, 2) . "\n";

// Try to reverse engineer the monthly payment
// If we know the payoff, we can work backwards
$remainingPayments2 = $tenure2 - $installmentsPaid2;
echo "Remaining Payments: $remainingPayments2\n";

// What would the monthly payment need to be?
// Using 12% annual rate
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;

// For a standard loan, monthly payment would be:
$standardMonthlyPayment2 = $principal2 * ($monthlyRate * pow(1 + $monthlyRate, $tenure2)) / 
                           (pow(1 + $monthlyRate, $tenure2) - 1);
echo "Standard Monthly Payment (at 12%): " . number_format($standardMonthlyPayment2, 2) . "\n";

// What would the standard payoff be?
$standardPayoff2 = $standardMonthlyPayment2 * (1 - pow(1 + $monthlyRate, -$remainingPayments2)) / $monthlyRate;
echo "Standard Payoff (at 12%): " . number_format($standardPayoff2, 2) . "\n";

// What's the ratio?
$ratio2 = $expectedPayoff2 / $standardPayoff2;
echo "Ratio (Expected/Standard): " . number_format($ratio2, 6) . "\n\n";

// Analysis
echo "ANALYSIS:\n";
echo str_repeat("=", 60) . "\n";
echo "For URL013572:\n";
echo "  Simple balance method gives: " . number_format($simpleBalance1, 2) . "\n";
echo "  But expected is: " . number_format($expectedPayoff1, 2) . "\n";
echo "  Factor needed: " . number_format($ratio1, 6) . "\n\n";

echo "For URL002224:\n";
echo "  Standard reducing balance gives: " . number_format($standardPayoff2, 2) . "\n";
echo "  But expected is: " . number_format($expectedPayoff2, 2) . "\n";
echo "  Factor needed: " . number_format($ratio2, 6) . "\n\n";

// Test if there's a consistent pattern
echo "PATTERN TESTING:\n";
echo str_repeat("-", 60) . "\n";

// For imported loans, it seems they use a different calculation
// Let's test if it's based on the original balance method
$testPayoff1 = $principal1 * (pow(1 + $monthlyRate, $tenure1) - pow(1 + $monthlyRate, $installmentsPaid1)) / 
               (pow(1 + $monthlyRate, $tenure1) - 1);
echo "Test Formula 1 for URL013572: " . number_format($testPayoff1, 2) . "\n";

// Or perhaps they use a fixed factor on the reducing balance
$fixedFactor = 0.6695;
$testPayoff1b = $testPayoff1 * $fixedFactor;
echo "Test Formula 1b (with factor): " . number_format($testPayoff1b, 2) . "\n";

// Check if expected matches
if (abs($testPayoff1b - $expectedPayoff1) < 10) {
    echo "✓ MATCH FOUND! Using reducing balance with factor " . $fixedFactor . "\n";
} else {
    echo "Still not matching. Difference: " . number_format(abs($testPayoff1b - $expectedPayoff1), 2) . "\n";
}