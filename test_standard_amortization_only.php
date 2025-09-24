<?php

echo "=== Testing Standard Amortization for ALL Loans ===\n\n";

/**
 * Calculate payoff using standard amortization (same as MemberController)
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
    
    // Build the amortization schedule month by month (same as MemberController)
    $balance = $principal;
    $schedule = [];
    
    for ($period = 1; $period <= $installmentsPaid; $period++) {
        $interestPayment = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestPayment;
        
        // Ensure we don't pay more than the remaining balance
        if ($principalPayment > $balance) {
            $principalPayment = $balance;
        }
        
        $balance -= $principalPayment;
        
        $schedule[] = [
            'period' => $period,
            'interest' => round($interestPayment, 2),
            'principal' => round($principalPayment, 2),
            'balance' => round($balance, 2)
        ];
        
        if ($balance <= 0) {
            return 0;
        }
    }
    
    return round($balance, 2);
}

// Test Case 1: URL013572 (Imported Loan - Now using standard amortization)
echo "Test Case 1: URL013572 (Imported Loan)\n";
echo str_repeat("=", 60) . "\n";

$principal1 = 6397076.00;
$annualRate1 = 12;
$totalTenure1 = 36;
$installmentsPaid1 = 5;
$monthlyPayment1 = 177697.00;

echo "Loan Parameters:\n";
echo "  Principal: " . number_format($principal1, 2) . "\n";
echo "  Annual Rate: $annualRate1%\n";
echo "  Total Tenure: $totalTenure1 months\n";
echo "  Installments Paid: $installmentsPaid1\n";
echo "  Monthly Payment: " . number_format($monthlyPayment1, 2) . "\n\n";

$payoff1 = calculatePayoffUsingAmortization($principal1, $annualRate1, $totalTenure1, $installmentsPaid1, $monthlyPayment1);

echo "Calculated Payoff (Standard Amortization): " . number_format($payoff1, 2) . "\n";
echo "Previous Expected (with factor): 3,774,876.79\n";
echo "Now ALL loans use standard amortization.\n\n";

// Test Case 2: Regular Loan
echo "Test Case 2: Regular Loan\n";
echo str_repeat("=", 60) . "\n";

$principal2 = 1000000.00;
$annualRate2 = 12;
$totalTenure2 = 12;
$installmentsPaid2 = 3;

echo "Loan Parameters:\n";
echo "  Principal: " . number_format($principal2, 2) . "\n";
echo "  Annual Rate: $annualRate2%\n";
echo "  Total Tenure: $totalTenure2 months\n";
echo "  Installments Paid: $installmentsPaid2\n\n";

$payoff2 = calculatePayoffUsingAmortization($principal2, $annualRate2, $totalTenure2, $installmentsPaid2);

echo "Calculated Payoff (Standard Amortization): " . number_format($payoff2, 2) . "\n\n";

// Test Case 3: URL002224
echo "Test Case 3: URL002224 (If it existed)\n";
echo str_repeat("=", 60) . "\n";

$principal3 = 18980000.00;
$annualRate3 = 12;
$totalTenure3 = 49;
$installmentsPaid3 = 15;

echo "Loan Parameters:\n";
echo "  Principal: " . number_format($principal3, 2) . "\n";
echo "  Annual Rate: $annualRate3%\n";
echo "  Total Tenure: $totalTenure3 months\n";
echo "  Installments Paid: $installmentsPaid3\n\n";

$payoff3 = calculatePayoffUsingAmortization($principal3, $annualRate3, $totalTenure3, $installmentsPaid3);

echo "Calculated Payoff (Standard Amortization): " . number_format($payoff3, 2) . "\n\n";

// Summary
echo str_repeat("=", 60) . "\n";
echo "SUMMARY:\n";
echo "The system now uses STANDARD AMORTIZATION for ALL loans:\n";
echo "1. Builds the amortization schedule month by month\n";
echo "2. Calculates interest and principal for each payment\n";
echo "3. Returns the exact balance after the specified payments\n";
echo "4. No special factors or adjustments for imported loans\n";
echo "5. Same calculation as MemberController for consistency\n";