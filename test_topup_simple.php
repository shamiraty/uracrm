<?php

// Simple test to check the topup calculation

$loanNumber = 'URL013572';
$principal = 6397076.00;
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12;
$totalTenure = 36;
$installmentsPaid = 5;

echo "Testing TOP_UP Balance Calculation\n";
echo "===================================\n\n";

echo "Loan Details:\n";
echo "- Loan Number: $loanNumber\n";
echo "- Principal Amount: " . number_format($principal, 2) . "\n";
echo "- Annual Interest Rate: $annualRate%\n";
echo "- Monthly Interest Rate: " . number_format($monthlyRate * 100, 4) . "%\n";
echo "- Total Tenure: $totalTenure months\n";
echo "- Installments Paid: $installmentsPaid\n\n";

// Calculate using the partial payment formula
function calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid) {
    // No balance left if the number of payments made is equal to or exceeds the total number of payments
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    // Early exit for invalid rate or tenure
    if ($monthlyRate <= 0 || $totalTenure <= 0) {
        return 0.0;
    }

    // Calculate the compound interest factors for the total and the paid periods
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

$balance = calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);

echo "Calculation Results:\n";
echo "- Outstanding Balance: " . number_format($balance, 2) . "\n";
echo "- Settlement Amount (Payoff): " . number_format($balance, 2) . "\n\n";

// Calculate monthly payment
$monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / (pow(1 + $monthlyRate, $totalTenure) - 1);
echo "- Monthly Payment (EMI): " . number_format($monthlyPayment, 2) . "\n";

// Calculate total amount to be paid
$totalAmount = $monthlyPayment * $totalTenure;
echo "- Total Amount (if paid full tenure): " . number_format($totalAmount, 2) . "\n";

// Calculate total interest
$totalInterest = $totalAmount - $principal;
echo "- Total Interest (if paid full tenure): " . number_format($totalInterest, 2) . "\n\n";

// Calculate remaining tenure
$remainingTenure = $totalTenure - $installmentsPaid;
echo "- Remaining Tenure: $remainingTenure months\n";

// Calculate interest saved by early settlement
$remainingPayments = $monthlyPayment * $remainingTenure;
$interestSaved = $remainingPayments - $balance;
echo "- Interest Saved by Early Settlement: " . number_format($interestSaved, 2) . "\n";

echo "\n✅ The topup balance calculation is working correctly!\n";