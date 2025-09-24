<?php

echo "=== Testing Actual Reducing Balance Formula ===\n\n";

/**
 * Calculate monthly payment using reducing balance formula
 */
function calculateMonthlyPayment($principal, $annualRate, $tenure) {
    $monthlyRate = $annualRate / 100 / 12;
    
    if ($monthlyRate <= 0) {
        return $principal / $tenure;
    }
    
    return $principal * ($monthlyRate * pow(1 + $monthlyRate, $tenure)) / 
           (pow(1 + $monthlyRate, $tenure) - 1);
}

/**
 * Calculate remaining balance after n payments
 */
function calculateRemainingBalance($principal, $annualRate, $tenure, $paymentsMade) {
    $monthlyRate = $annualRate / 100 / 12;
    
    if ($paymentsMade >= $tenure) {
        return 0;
    }
    
    // Calculate monthly payment
    $monthlyPayment = calculateMonthlyPayment($principal, $annualRate, $tenure);
    
    // Calculate remaining payments
    $remainingPayments = $tenure - $paymentsMade;
    
    if ($monthlyRate > 0) {
        // Present value of remaining payments
        return $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
    } else {
        return $monthlyPayment * $remainingPayments;
    }
}

// Test Case 1: URL013572 - The loan from logs
echo "Test Case 1: URL013572 (CHIBITA Import)\n";
echo str_repeat("-", 60) . "\n";
$principal = 6397076.00;
$annualRate = 12;
$tenure = 36;
$installmentsPaid = 5;

$monthlyPayment = calculateMonthlyPayment($principal, $annualRate, $tenure);
$remainingBalance = calculateRemainingBalance($principal, $annualRate, $tenure, $installmentsPaid);

echo "Principal: " . number_format($principal, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Tenure: $tenure months\n";
echo "Installments Paid: $installmentsPaid\n";
echo "Calculated Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
echo "Remaining Balance (Payoff): " . number_format($remainingBalance, 2) . "\n";
echo "Expected from logs: 3,774,876.79\n";

// Check if we need to adjust for the actual monthly payment from import
$actualMonthlyPayment = 177697.00; // From CHIBITA data
echo "\nActual Monthly Payment from Import: " . number_format($actualMonthlyPayment, 2) . "\n";

// Recalculate with actual payment
$remainingPayments = $tenure - $installmentsPaid;
$monthlyRate = $annualRate / 100 / 12;
$payoffWithActualPayment = $actualMonthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
echo "Payoff with Actual Payment: " . number_format($payoffWithActualPayment, 2) . "\n\n";

// Test Case 2: URL002224 - From the error log
echo "Test Case 2: URL002224 (From Error Log)\n";
echo str_repeat("-", 60) . "\n";
$principal2 = 18980000.00;
$tenure2 = 49;
$installmentsPaid2 = 15;

$monthlyPayment2 = calculateMonthlyPayment($principal2, $annualRate, $tenure2);
$remainingBalance2 = calculateRemainingBalance($principal2, $annualRate, $tenure2, $installmentsPaid2);

echo "Principal: " . number_format($principal2, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Tenure: $tenure2 months\n";
echo "Installments Paid: $installmentsPaid2\n";
echo "Calculated Monthly Payment: " . number_format($monthlyPayment2, 2) . "\n";
echo "Remaining Balance (Payoff): " . number_format($remainingBalance2, 2) . "\n\n";

// Test Case 3: Verify the formula with a simple example
echo "Test Case 3: Simple Verification\n";
echo str_repeat("-", 60) . "\n";
$principal3 = 1000000.00;
$tenure3 = 12;
$installmentsPaid3 = 3;

$monthlyPayment3 = calculateMonthlyPayment($principal3, $annualRate, $tenure3);
$remainingBalance3 = calculateRemainingBalance($principal3, $annualRate, $tenure3, $installmentsPaid3);

echo "Principal: " . number_format($principal3, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Tenure: $tenure3 months\n";
echo "Installments Paid: $installmentsPaid3\n";
echo "Calculated Monthly Payment: " . number_format($monthlyPayment3, 2) . "\n";
echo "Remaining Balance (Payoff): " . number_format($remainingBalance3, 2) . "\n";

// Verify with manual calculation
$remainingPayments3 = $tenure3 - $installmentsPaid3;
echo "Remaining Payments: $remainingPayments3\n";
echo "Monthly Payment × Remaining: " . number_format($monthlyPayment3 * $remainingPayments3, 2) . " (if no interest)\n";

echo "\n=== Formula Summary ===\n";
echo "The actual reducing balance formula calculates:\n";
echo "1. Monthly Payment = P × [r(1+r)^n] / [(1+r)^n - 1]\n";
echo "2. Remaining Balance = Monthly Payment × [1 - (1+r)^(-remaining)] / r\n";
echo "Where: P = Principal, r = Monthly Rate, n = Total Tenure\n";