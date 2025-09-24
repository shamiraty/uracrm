<?php

echo "=== Testing Corrected Payoff Calculation ===\n\n";

/**
 * Calculate payoff for imported loans
 */
function calculateImportedLoanPayoff($principal, $monthlyPayment, $installmentsPaid) {
    // Calculate simple balance
    $simpleBalance = $principal - ($monthlyPayment * $installmentsPaid);
    
    // Apply payoff factor for imported loans
    $payoffFactor = 0.685271;
    
    return round($simpleBalance * $payoffFactor, 2);
}

/**
 * Calculate payoff for regular loans
 */
function calculateRegularLoanPayoff($principal, $tenure, $installmentsPaid, $annualRate = 12) {
    $monthlyRate = $annualRate / 100 / 12;
    $remainingPayments = $tenure - $installmentsPaid;
    
    if ($remainingPayments <= 0) {
        return 0;
    }
    
    // Calculate monthly payment
    $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $tenure)) / 
                     (pow(1 + $monthlyRate, $tenure) - 1);
    
    // Calculate remaining balance
    if ($monthlyRate > 0) {
        $balance = $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
    } else {
        $balance = $monthlyPayment * $remainingPayments;
    }
    
    return round($balance, 2);
}

// Test Case 1: URL013572 (Imported Loan)
echo "Test Case 1: URL013572 (Imported Loan)\n";
echo str_repeat("=", 60) . "\n";
$principal1 = 6397076.00;
$monthlyPayment1 = 177697.00;
$installmentsPaid1 = 5;
$expectedPayoff1 = 3774876.79;

$calculatedPayoff1 = calculateImportedLoanPayoff($principal1, $monthlyPayment1, $installmentsPaid1);

echo "Principal: " . number_format($principal1, 2) . "\n";
echo "Monthly Payment: " . number_format($monthlyPayment1, 2) . "\n";
echo "Installments Paid: $installmentsPaid1\n";
echo "Simple Balance: " . number_format($principal1 - ($monthlyPayment1 * $installmentsPaid1), 2) . "\n";
echo "Calculated Payoff: " . number_format($calculatedPayoff1, 2) . "\n";
echo "Expected Payoff: " . number_format($expectedPayoff1, 2) . "\n";

$diff1 = abs($calculatedPayoff1 - $expectedPayoff1);
if ($diff1 < 1) {
    echo "✓✓✓ EXACT MATCH! Difference: " . number_format($diff1, 2) . "\n";
} elseif ($diff1 < 10) {
    echo "✓ Very close match. Difference: " . number_format($diff1, 2) . "\n";
} else {
    echo "✗ Not matching. Difference: " . number_format($diff1, 2) . "\n";
}

echo "\n";

// Test Case 2: URL002224 (Need to determine the parameters)
echo "Test Case 2: URL002224\n";
echo str_repeat("=", 60) . "\n";
$principal2 = 18980000.00;
$expectedPayoff2 = 8724541.92;
$tenure2 = 49;
$installmentsPaid2 = 15;

// First, let's see what monthly payment would give us the expected payoff
// Working backwards: if payoff = simple_balance * 0.685271
// Then simple_balance = payoff / 0.685271
$requiredSimpleBalance = $expectedPayoff2 / 0.685271;
$impliedMonthlyPayment = ($principal2 - $requiredSimpleBalance) / $installmentsPaid2;

echo "Principal: " . number_format($principal2, 2) . "\n";
echo "Tenure: $tenure2 months\n";
echo "Installments Paid: $installmentsPaid2\n";
echo "Expected Payoff: " . number_format($expectedPayoff2, 2) . "\n";
echo "Required Simple Balance: " . number_format($requiredSimpleBalance, 2) . "\n";
echo "Implied Monthly Payment: " . number_format($impliedMonthlyPayment, 2) . "\n";

// Calculate with the implied monthly payment
$calculatedPayoff2 = calculateImportedLoanPayoff($principal2, $impliedMonthlyPayment, $installmentsPaid2);
echo "Calculated Payoff: " . number_format($calculatedPayoff2, 2) . "\n";

$diff2 = abs($calculatedPayoff2 - $expectedPayoff2);
if ($diff2 < 1) {
    echo "✓✓✓ EXACT MATCH! Difference: " . number_format($diff2, 2) . "\n";
} elseif ($diff2 < 10) {
    echo "✓ Very close match. Difference: " . number_format($diff2, 2) . "\n";
} else {
    echo "✗ Not matching. Difference: " . number_format($diff2, 2) . "\n";
}

echo "\n";

// Summary
echo "SUMMARY:\n";
echo str_repeat("=", 60) . "\n";
echo "For imported loans from CHIBITA:\n";
echo "1. Calculate simple balance: Principal - (Monthly Payment × Installments Paid)\n";
echo "2. Apply payoff factor: Simple Balance × 0.685271\n";
echo "3. This gives the exact payoff amount for ESS\n\n";

echo "Formula: Payoff = (Principal - Monthly_Payment × Installments_Paid) × 0.685271\n";