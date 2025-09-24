<?php

echo "=== Testing Loan Topup Payoff Balance Calculation ===\n\n";

/**
 * Calculate payoff balance using reducing balance formula
 * This matches the implementation in handleTopUpPayoffBalance
 */
function calculatePayoffBalance($principal, $monthlyPayment, $totalTenure, $installmentsPaid, $annualRate = 12) {
    $monthlyRate = $annualRate / 100 / 12;
    
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    
    $remainingPayments = $totalTenure - $installmentsPaid;
    
    // If monthly payment is not available, calculate it
    if ($monthlyPayment <= 0 && $monthlyRate > 0) {
        $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                         (pow(1 + $monthlyRate, $totalTenure) - 1);
    } elseif ($monthlyPayment <= 0) {
        $monthlyPayment = $principal / $totalTenure;
    }
    
    // Calculate remaining balance (payoff amount)
    if ($monthlyRate > 0) {
        // Present value of remaining payments
        $balance = $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
    } else {
        // Simple calculation if no interest
        $balance = $monthlyPayment * $remainingPayments;
    }
    
    return round($balance, 2);
}

// Test Case 1: URL013572
echo "Test Case 1: URL013572\n";
echo str_repeat("-", 60) . "\n";
$principal1 = 6397076.00;
$monthlyPayment1 = 177697.00; // From CHIBITA import
$totalTenure1 = 36;
$installmentsPaid1 = 5;
$annualRate1 = 12;

$payoff1 = calculatePayoffBalance($principal1, $monthlyPayment1, $totalTenure1, $installmentsPaid1, $annualRate1);

echo "Principal: " . number_format($principal1, 2) . "\n";
echo "Monthly Payment: " . number_format($monthlyPayment1, 2) . "\n";
echo "Annual Rate: $annualRate1%\n";
echo "Total Tenure: $totalTenure1 months\n";
echo "Installments Paid: $installmentsPaid1\n";
echo "Remaining Payments: " . ($totalTenure1 - $installmentsPaid1) . "\n";
echo "Calculated Payoff: " . number_format($payoff1, 2) . "\n\n";

// Test Case 2: URL002224
echo "Test Case 2: URL002224\n";
echo str_repeat("-", 60) . "\n";
$principal2 = 18980000.00;
$monthlyPayment2 = 0; // Will be calculated
$totalTenure2 = 49;
$installmentsPaid2 = 15;
$annualRate2 = 12;

$payoff2 = calculatePayoffBalance($principal2, $monthlyPayment2, $totalTenure2, $installmentsPaid2, $annualRate2);

// Calculate what the monthly payment would be
$monthlyRate2 = $annualRate2 / 100 / 12;
$calculatedMonthlyPayment2 = $principal2 * ($monthlyRate2 * pow(1 + $monthlyRate2, $totalTenure2)) / 
                             (pow(1 + $monthlyRate2, $totalTenure2) - 1);

echo "Principal: " . number_format($principal2, 2) . "\n";
echo "Calculated Monthly Payment: " . number_format($calculatedMonthlyPayment2, 2) . "\n";
echo "Annual Rate: $annualRate2%\n";
echo "Total Tenure: $totalTenure2 months\n";
echo "Installments Paid: $installmentsPaid2\n";
echo "Remaining Payments: " . ($totalTenure2 - $installmentsPaid2) . "\n";
echo "Calculated Payoff: " . number_format($payoff2, 2) . "\n\n";

// Test Case 3: Simple loan
echo "Test Case 3: Simple Loan (1M, 12 months, 3 paid)\n";
echo str_repeat("-", 60) . "\n";
$principal3 = 1000000.00;
$monthlyPayment3 = 0; // Will be calculated
$totalTenure3 = 12;
$installmentsPaid3 = 3;
$annualRate3 = 12;

$payoff3 = calculatePayoffBalance($principal3, $monthlyPayment3, $totalTenure3, $installmentsPaid3, $annualRate3);

$monthlyRate3 = $annualRate3 / 100 / 12;
$calculatedMonthlyPayment3 = $principal3 * ($monthlyRate3 * pow(1 + $monthlyRate3, $totalTenure3)) / 
                             (pow(1 + $monthlyRate3, $totalTenure3) - 1);

echo "Principal: " . number_format($principal3, 2) . "\n";
echo "Calculated Monthly Payment: " . number_format($calculatedMonthlyPayment3, 2) . "\n";
echo "Annual Rate: $annualRate3%\n";
echo "Total Tenure: $totalTenure3 months\n";
echo "Installments Paid: $installmentsPaid3\n";
echo "Remaining Payments: " . ($totalTenure3 - $installmentsPaid3) . "\n";
echo "Calculated Payoff: " . number_format($payoff3, 2) . "\n\n";

echo "=== Summary ===\n";
echo "The payoff calculation uses the standard reducing balance formula:\n";
echo "Payoff = Monthly Payment × [1 - (1+r)^(-remaining)] / r\n";
echo "Where r = monthly interest rate, remaining = remaining payments\n";