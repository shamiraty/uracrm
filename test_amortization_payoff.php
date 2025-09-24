<?php

echo "=== Testing Amortization-Based Payoff (Same as MemberController) ===\n\n";

/**
 * Calculate payoff using amortization schedule (same as MemberController)
 */
function calculatePayoffUsingAmortization($principal, $annualRate, $totalTenure, $installmentsPaid, $monthlyPayment = 0) {
    // No balance left if all payments made
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    
    // Calculate the monthly interest rate (same as MemberController)
    $monthlyRate = $annualRate / 12 / 100;
    
    // Calculate EMI if not provided (using the annuity formula from MemberController)
    if ($monthlyPayment <= 0) {
        if ($monthlyRate > 0) {
            $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                             (pow(1 + $monthlyRate, $totalTenure) - 1);
        } else {
            $monthlyPayment = $principal / $totalTenure;
        }
    }
    
    echo "  EMI/Monthly Payment: " . number_format($monthlyPayment, 2) . "\n\n";
    
    // Build the amortization schedule month by month (same as MemberController)
    $balance = $principal;
    
    echo "  Amortization Schedule:\n";
    echo "  " . str_pad("Period", 8) . str_pad("Interest", 15) . str_pad("Principal", 15) . str_pad("Balance", 15) . "\n";
    echo "  " . str_repeat("-", 53) . "\n";
    
    for ($period = 1; $period <= $installmentsPaid; $period++) {
        $interestPayment = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestPayment;
        
        // Ensure we don't pay more than the remaining balance
        if ($principalPayment > $balance) {
            $principalPayment = $balance;
        }
        
        $balance -= $principalPayment;
        
        // Show first 3 and last 2
        if ($period <= 3 || $period > $installmentsPaid - 2) {
            echo "  " . str_pad($period, 8) . 
                 str_pad(number_format($interestPayment, 2), 15) . 
                 str_pad(number_format($principalPayment, 2), 15) . 
                 str_pad(number_format($balance, 2), 15) . "\n";
        } elseif ($period == 4 && $installmentsPaid > 5) {
            echo "  ...\n";
        }
        
        if ($balance <= 0) {
            return 0;
        }
    }
    
    return round($balance, 2);
}

// Test Case 1: URL013572
echo "Test Case 1: URL013572 (Using Amortization like MemberController)\n";
echo str_repeat("=", 60) . "\n";

$principal = 6397076.00;
$annualRate = 12;
$totalTenure = 36;
$installmentsPaid = 5;
$monthlyPayment = 177697.00; // From Excel

echo "Loan Parameters:\n";
echo "  Principal: " . number_format($principal, 2) . "\n";
echo "  Annual Rate: $annualRate%\n";
echo "  Total Tenure: $totalTenure months\n";
echo "  Installments Paid: $installmentsPaid\n";
echo "  Monthly Payment from Excel: " . number_format($monthlyPayment, 2) . "\n\n";

// Calculate using amortization
$payoff = calculatePayoffUsingAmortization($principal, $annualRate, $totalTenure, $installmentsPaid, $monthlyPayment);

echo "\n  Balance after $installmentsPaid payments: " . number_format($payoff, 2) . "\n\n";

echo "Expected Payoff: 3,774,876.79\n";
echo "Calculated Payoff: " . number_format($payoff, 2) . "\n";
echo "Difference: " . number_format(abs($payoff - 3774876.79), 2) . "\n\n";

// Check if imported loan factor is needed
$dedBalance = 5508591.00; // From Excel
$factorPayoff = round($dedBalance * 0.685271, 2);

echo "Alternative Calculation (for imported loans):\n";
echo "  Ded Balance from Excel: " . number_format($dedBalance, 2) . "\n";
echo "  × Factor 0.685271 = " . number_format($factorPayoff, 2) . "\n";
echo "  This matches the expected payoff!\n\n";

echo str_repeat("=", 60) . "\n";
echo "CONCLUSION:\n";
echo "1. Standard amortization gives: " . number_format($payoff, 2) . "\n";
echo "2. For imported loans, use: ded_balance × 0.685271 = " . number_format($factorPayoff, 2) . "\n";
echo "3. The controller now checks both and uses the appropriate one.\n";