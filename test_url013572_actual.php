<?php

/**
 * Test the improved payoff formula against actual URL013572 loan data
 * Actual data from ESS:
 * - Initial Balance: 6,397,076.07
 * - Monthly Amount: 177,697
 * - Current Balance: 4,264,712.07
 */

echo "================================================\n";
echo "Testing URL013572 with Actual ESS Data\n";
echo "================================================\n\n";

// Actual values from ESS
$loanNumber = "URL013572";
$initialBalance = 6397076.07;  // Initial loan amount
$monthlyPayment = 177697.00;    // Monthly deduction
$currentBalance = 4264712.07;   // Current balance from ESS
$tenure = 36;                   // Standard tenure
$annualRate = 0.12;            // 12% annual rate
$monthlyRate = $annualRate / 12;

echo "=== Input Data from ESS ===\n";
echo "Loan Number: $loanNumber\n";
echo "Initial Balance: " . number_format($initialBalance, 2) . "\n";
echo "Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
echo "Current Balance: " . number_format($currentBalance, 2) . "\n";
echo "Tenure: $tenure months\n";
echo "Annual Rate: " . ($annualRate * 100) . "%\n\n";

// Step 1: Calculate how many payments have been made
$totalReduction = $initialBalance - $currentBalance;
$estimatedPayments = round($totalReduction / $monthlyPayment);
echo "=== Payment Analysis ===\n";
echo "Total Reduction: " . number_format($totalReduction, 2) . "\n";
echo "Estimated Payments Made: $estimatedPayments\n\n";

// Step 2: Calculate original principal (present value of loan)
$originalPrincipal = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
echo "=== Principal Calculation ===\n";
echo "Calculated Original Principal (PV): " . number_format($originalPrincipal, 2) . "\n\n";

// Step 3: Calculate outstanding principal using amortization
echo "=== Amortization Schedule ===\n";
echo "Payment | Interest | Principal | Balance\n";
echo "--------------------------------------------\n";

$balance = $originalPrincipal;
for ($i = 1; $i <= $estimatedPayments; $i++) {
    $interestForMonth = $balance * $monthlyRate;
    $principalPayment = $monthlyPayment - $interestForMonth;
    $balance -= $principalPayment;
    
    if ($i <= 3 || $i >= $estimatedPayments - 1) {
        echo sprintf("%7d | %8.2f | %9.2f | %12.2f\n", 
            $i, $interestForMonth, $principalPayment, $balance);
    } elseif ($i == 4) {
        echo "    ... | ...      | ...       | ...\n";
    }
}

$outstandingPrincipal = $balance;
echo "\nOutstanding Principal after $estimatedPayments payments: " . number_format($outstandingPrincipal, 2) . "\n\n";

// Step 4: Calculate payoff with pro-rated interest
echo "=== Payoff Calculation (Improved Formula) ===\n";
echo "Formula: Total Payoff = P + (P × R/365 × D)\n\n";

// Test with different days since payment
$daysOptions = [1, 7, 14, 30];
foreach ($daysOptions as $days) {
    $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $days;
    $totalPayoff = $outstandingPrincipal + $proRatedInterest;
    
    echo "Days since payment: $days\n";
    echo "  Pro-rated Interest: " . number_format($proRatedInterest, 2) . "\n";
    echo "  Total Payoff: " . number_format($totalPayoff, 2) . "\n\n";
}

// Step 5: Compare with old factor-based calculation
echo "=== Comparison with Old Method ===\n";
$oldFactor = 0.6852708415;
$dedBalance = $initialBalance - ($monthlyPayment * $estimatedPayments);
$oldPayoff = $dedBalance * $oldFactor;

echo "Old Method (Factor-based):\n";
echo "  Deduction Balance: " . number_format($dedBalance, 2) . "\n";
echo "  Factor: $oldFactor\n";
echo "  Calculated Payoff: " . number_format($oldPayoff, 2) . "\n\n";

// Calculate with 7 days (standard processing time)
$standardDays = 7;
$standardInterest = $outstandingPrincipal * ($annualRate / 365) * $standardDays;
$standardPayoff = $outstandingPrincipal + $standardInterest;

echo "New Method (Formula-based with 7 days):\n";
echo "  Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
echo "  Pro-rated Interest: " . number_format($standardInterest, 2) . "\n";
echo "  Total Payoff: " . number_format($standardPayoff, 2) . "\n\n";

$difference = $standardPayoff - $oldPayoff;
$percentDiff = ($difference / $oldPayoff) * 100;

echo "Difference: " . number_format($difference, 2) . " (" . round($percentDiff, 2) . "%)\n\n";

// Step 6: Verify against ESS balance
echo "=== ESS Balance Verification ===\n";
echo "ESS Current Balance: " . number_format($currentBalance, 2) . "\n";
echo "Our Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
$balanceDiff = $currentBalance - $outstandingPrincipal;
echo "Difference: " . number_format($balanceDiff, 2) . "\n\n";

// Step 7: Final recommendation
echo "=== Final Payoff Recommendation ===\n";
echo "For loan $loanNumber with standard 7-day processing:\n";
echo "✓ Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
echo "✓ Pro-rated Interest (7 days): " . number_format($standardInterest, 2) . "\n";
echo "✓ TOTAL PAYOFF AMOUNT: " . number_format($standardPayoff, 2) . "\n";
echo "\nThis amount should be sent in Message 12 (LOAN_TOP_UP_BALANCE_RESPONSE)\n";

echo "\n================================================\n";
echo "Test Complete\n";
echo "================================================\n";