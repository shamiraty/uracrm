<?php

// Test the payoff calculation for loan URL013572
$principal = 6397076.00;
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12; // 0.01
$totalTenure = 36;
$installmentsPaid = 5;

echo "=== Loan Payoff Calculation Test ===\n";
echo "Loan Number: URL013572\n";
echo "Principal: " . number_format($principal, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Monthly Rate: " . ($monthlyRate * 100) . "%\n";
echo "Total Tenure: $totalTenure months\n";
echo "Installments Paid: $installmentsPaid\n\n";

// Current formula (seems to be wrong)
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

// Standard amortization formula for remaining balance
function calculateRemainingBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid) {
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    
    // Calculate monthly payment
    $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                      (pow(1 + $monthlyRate, $totalTenure) - 1);
    
    // Calculate remaining balance after m payments
    $remainingPayments = $totalTenure - $installmentsPaid;
    $remainingBalance = $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
    
    return $remainingBalance;
}

// Calculate with current formula
$currentBalance = calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);
echo "Current Formula Result: " . number_format($currentBalance, 2) . "\n";

// Calculate with standard amortization formula
$standardBalance = calculateRemainingBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);
echo "Standard Amortization Formula: " . number_format($standardBalance, 2) . "\n";

echo "\nExpected Payoff Amount: 3,774,876.79\n";
echo "Difference (Current): " . number_format($currentBalance - 3774876.79, 2) . "\n";
echo "Difference (Standard): " . number_format($standardBalance - 3774876.79, 2) . "\n";

// Let's also calculate the monthly payment for verification
$monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                  (pow(1 + $monthlyRate, $totalTenure) - 1);
echo "\nMonthly Payment: " . number_format($monthlyPayment, 2) . "\n";

// According to the screenshot, monthly amount is 177,697
echo "Expected Monthly Payment (from screenshot): 177,697.00\n";