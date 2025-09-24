<?php

echo "=== Finding the Correct Payoff Pattern ===\n\n";

// From the screenshot and Excel:
$initial_balance = 6397076.07;  // Initial Balance from screenshot
$balance_amount = 4264712.07;   // Balance Amount from screenshot (NOT ded_balance)
$total_payoff = 3774876.79;     // Total Payoff Amount from screenshot
$ded_balance_excel = 5508591;   // ded_balance_amount from Excel
$monthly_payment = 177697;
$installments_paid = 5;

echo "Known Values:\n";
echo "Initial Balance: " . number_format($initial_balance, 2) . "\n";
echo "Balance Amount (Screenshot): " . number_format($balance_amount, 2) . "\n";
echo "Total Payoff (Screenshot): " . number_format($total_payoff, 2) . "\n";
echo "Ded Balance (Excel): " . number_format($ded_balance_excel, 2) . "\n\n";

// The pattern seems to be:
// Balance Amount - Total Payoff = Some discount/interest
$discount_amount = $balance_amount - $total_payoff;
$discount_percentage = ($discount_amount / $balance_amount) * 100;

echo "Pattern Analysis:\n";
echo "Balance Amount - Total Payoff = " . number_format($discount_amount, 2) . "\n";
echo "This represents " . number_format($discount_percentage, 2) . "% of Balance Amount\n\n";

// Test if this is a standard discount rate
echo "Testing Formula: Payoff = Balance Amount * 0.885\n";
$calculated_payoff = $balance_amount * 0.885;
echo "Calculated: " . number_format($calculated_payoff, 2) . "\n";
echo "Expected: " . number_format($total_payoff, 2) . "\n";
echo "Difference: " . number_format(abs($calculated_payoff - $total_payoff), 2) . "\n\n";

// More precise factor
$exact_factor = $total_payoff / $balance_amount;
echo "Exact Factor: " . number_format($exact_factor, 6) . "\n";
echo "So: Payoff = Balance Amount * " . number_format($exact_factor, 6) . "\n\n";

// Now figure out where Balance Amount comes from
echo "=== How to get Balance Amount ===\n";
$paid_so_far = $installments_paid * $monthly_payment;
$simple_balance = $initial_balance - $paid_so_far;
echo "Initial - Paid So Far = " . number_format($simple_balance, 2) . "\n";
echo "This equals ded_balance from Excel: " . number_format($ded_balance_excel, 2) . "\n\n";

// So Balance Amount is different from simple calculation
$diff_balance = $ded_balance_excel - $balance_amount;
echo "Ded Balance - Balance Amount = " . number_format($diff_balance, 2) . "\n";
echo "This could be accumulated interest\n\n";

// Final formula
echo "=== FINAL FORMULA ===\n";
echo "1. Calculate remaining balance: Initial Balance - (Monthly Payment * Installments Paid)\n";
echo "2. Reduce by accumulated interest to get Balance Amount\n";
echo "3. Total Payoff = Balance Amount * 0.885098\n";