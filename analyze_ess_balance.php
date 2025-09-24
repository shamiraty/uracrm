<?php

/**
 * Analyze the ESS balance to understand what it represents
 */

echo "================================================\n";
echo "Analyzing ESS Balance Components\n";
echo "================================================\n\n";

$initialBalance = 6397076.07;
$monthlyPayment = 177697.00;
$currentBalance = 4264712.07;  // ESS balance
$annualRate = 0.12;
$monthlyRate = $annualRate / 12;
$tenure = 36;

// Calculate the original principal
$originalPrincipal = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
echo "Original Principal (PV): " . number_format($originalPrincipal, 2) . "\n\n";

// Method 1: ESS balance might be principal + accrued interest
echo "=== Method 1: ESS Balance = Principal + Accrued Interest ===\n";
$totalReduction = $initialBalance - $currentBalance;
$paymentsEstimated = round($totalReduction / $monthlyPayment);
echo "Payments made (estimated): $paymentsEstimated\n";

// Calculate outstanding principal after payments
$balance = $originalPrincipal;
for ($i = 1; $i <= $paymentsEstimated; $i++) {
    $interestForMonth = $balance * $monthlyRate;
    $principalPayment = $monthlyPayment - $interestForMonth;
    $balance -= $principalPayment;
}
$outstandingPrincipal = $balance;
echo "Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";

// How much interest would make up the difference?
$difference = $currentBalance - $outstandingPrincipal;
echo "Difference (ESS - Principal): " . number_format($difference, 2) . "\n";

// How many days of interest would this represent?
if ($outstandingPrincipal > 0) {
    $dailyInterest = $outstandingPrincipal * ($annualRate / 365);
    $daysOfInterest = $difference / $dailyInterest;
    echo "This represents " . round($daysOfInterest) . " days of accrued interest\n";
    echo "Daily interest amount: " . number_format($dailyInterest, 2) . "\n\n";
}

// Method 2: ESS balance might be total of remaining payments
echo "=== Method 2: ESS Balance = Sum of Remaining Payments ===\n";
$remainingPayments = $tenure - $paymentsEstimated;
$sumOfRemainingPayments = $monthlyPayment * $remainingPayments;
echo "Remaining payments: $remainingPayments\n";
echo "Sum of remaining payments: " . number_format($sumOfRemainingPayments, 2) . "\n";
$diff2 = $currentBalance - $sumOfRemainingPayments;
echo "Difference from ESS balance: " . number_format($diff2, 2) . "\n\n";

// Method 3: ESS balance might be simple deduction tracking
echo "=== Method 3: ESS Balance = Simple Deduction Tracking ===\n";
$simpleBalance = $initialBalance - ($monthlyPayment * $paymentsEstimated);
echo "Simple balance (Initial - Payments): " . number_format($simpleBalance, 2) . "\n";
$diff3 = $currentBalance - $simpleBalance;
echo "Difference from ESS balance: " . number_format($diff3, 2) . "\n\n";

// Method 4: Reverse engineer what the ESS balance represents
echo "=== Method 4: Reverse Engineering ESS Balance ===\n";

// If ESS balance includes some interest, what would the factor be?
$factor = $currentBalance / $simpleBalance;
echo "Factor (ESS Balance / Simple Balance): " . number_format($factor, 6) . "\n";

// Check if this matches any standard calculation
$possibleInterestMonths = $difference / ($outstandingPrincipal * $monthlyRate);
echo "Possible months of interest included: " . round($possibleInterestMonths, 2) . "\n\n";

// Final Analysis
echo "=== Final Analysis ===\n";
echo "ESS Balance (" . number_format($currentBalance, 2) . ") appears to be:\n";

$tolerance = 1000; // Allow 1000 TZS tolerance
if (abs($currentBalance - $simpleBalance) < $tolerance) {
    echo "✓ Simple deduction tracking (Initial - Payments made)\n";
    echo "  This is just tracking deductions without interest calculation\n";
} elseif (abs($difference - ($outstandingPrincipal * $monthlyRate * 2)) < $tolerance) {
    echo "✓ Principal + 2 months of accrued interest\n";
} elseif ($daysOfInterest > 30 && $daysOfInterest < 60) {
    echo "✓ Principal + " . round($daysOfInterest) . " days of accrued interest\n";
} else {
    echo "? Complex calculation - needs further investigation\n";
}

echo "\n=== Recommendation ===\n";
echo "For accurate payoff calculation:\n";
echo "1. Use the amortization method to find true outstanding principal\n";
echo "2. Add pro-rated interest for days since last payment\n";
echo "3. Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
echo "4. With 7 days interest: " . number_format($outstandingPrincipal + ($outstandingPrincipal * $annualRate / 365 * 7), 2) . "\n";

echo "\n================================================\n";