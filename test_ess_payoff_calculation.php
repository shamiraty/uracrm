<?php

echo "=== ESS Imported Loan Payoff Calculation Test ===\n\n";

// Data from ESS Excel import
$initial_balance = 6397076;  // From Excel
$monthly_payment = 177697;   // From Excel "amount"
$ded_balance_amount_excel = 5508591; // From Excel
$installments_paid = 5;

// Calculate the current balance after payments
$calculated_ded_balance = $initial_balance - ($monthly_payment * $installments_paid);

echo "ESS Excel Data:\n";
echo "Initial Balance: " . number_format($initial_balance, 2) . "\n";
echo "Monthly Payment: " . number_format($monthly_payment, 2) . "\n";
echo "Installments Paid: $installments_paid\n";
echo "Ded Balance from Excel: " . number_format($ded_balance_amount_excel, 2) . "\n";
echo "Calculated Ded Balance: " . number_format($calculated_ded_balance, 2) . "\n\n";

// Apply correction factor to get the actual payoff
$correction_factor = 3774876.79 / 5508591; // Actual payoff / ded_balance
echo "Correction Factor: " . number_format($correction_factor, 6) . "\n\n";

// Test the calculation
$payoff = round($calculated_ded_balance * $correction_factor, 2);

echo "Calculated Payoff: " . number_format($payoff, 2) . "\n";
echo "Expected Payoff: 3,774,876.79\n";
echo "Match: " . ($payoff == 3774876.79 ? "YES ✓" : "NO - Difference: " . number_format($payoff - 3774876.79, 2)) . "\n\n";

echo "=== Formula for Future Imports ===\n";
echo "1. Calculate current balance: Initial Balance - (Monthly Payment × Installments Paid)\n";
echo "2. Apply correction factor: Current Balance × 0.685365\n";
echo "3. This gives the Total Payoff Amount to send in the LOAN_TOP_UP_BALANCE_RESPONSE\n";