<?php

// Verify the corrected payoff calculation
$monthlyPayment = 177697.00;  // From desired_deductible_amount
$annualRate = 12;
$monthlyRate = $annualRate / 100 / 12; // 0.01
$totalTenure = 36;
$installmentsPaid = 5;
$remainingPayments = $totalTenure - $installmentsPaid; // 31

echo "=== Corrected Payoff Calculation ===\n";
echo "Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
echo "Annual Rate: $annualRate%\n";
echo "Monthly Rate: " . ($monthlyRate * 100) . "%\n";
echo "Total Tenure: $totalTenure months\n";
echo "Installments Paid: $installmentsPaid\n";
echo "Remaining Payments: $remainingPayments\n\n";

// Calculate payoff using monthly payment
function calculatePayoffFromMonthlyPayment($monthlyPayment, $monthlyRate, $remainingPayments) {
    if ($remainingPayments <= 0) {
        return 0.0;
    }
    if ($monthlyRate <= 0) {
        return $monthlyPayment * $remainingPayments;
    }
    
    // Present value of remaining payments
    return $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
}

$payoffAmount = calculatePayoffFromMonthlyPayment($monthlyPayment, $monthlyRate, $remainingPayments);

echo "Calculated Payoff Amount: " . number_format($payoffAmount, 2) . "\n";
echo "Expected Payoff Amount: 3,774,876.79\n";
echo "Difference: " . number_format($payoffAmount - 3774876.79, 2) . "\n\n";

// Also calculate what the original loan amount would have been
$totalPV = $monthlyPayment * (1 - pow(1 + $monthlyRate, -$totalTenure)) / $monthlyRate;
echo "Implied Original Loan Amount: " . number_format($totalPV, 2) . "\n";
echo "Actual Requested Amount in DB: 6,397,076.00\n";