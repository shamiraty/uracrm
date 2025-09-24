<?php

// Test the discovered formula for payoff calculation
echo "=== Testing Payoff Formula for Imported Loans ===\n\n";

// Original loan data
$initial_balance = 6397076.00;
$monthly_payment = 177697.00;
$installments_paid = 5;
$tenure = 36;

// Calculate current balance
$paid_so_far = $installments_paid * $monthly_payment;
$current_balance = $initial_balance - $paid_so_far;

echo "Initial Balance: " . number_format($initial_balance, 2) . "\n";
echo "Monthly Payment: " . number_format($monthly_payment, 2) . "\n";
echo "Installments Paid: $installments_paid\n";
echo "Amount Paid So Far: " . number_format($paid_so_far, 2) . "\n";
echo "Current Balance: " . number_format($current_balance, 2) . "\n\n";

// Method 1: Deduct future interest (most likely)
// For imported loans, the payoff might exclude future interest
$monthly_rate = 0.12 / 12; // 1% per month
$remaining_payments = $tenure - $installments_paid;

// Calculate payoff as current balance minus future interest discount
// This assumes the current balance includes interest, but payoff doesn't
$discount_factor = 0.1149; // approximately 11.49% discount observed
$payoff_method1 = $current_balance * (1 - $discount_factor);

echo "Method 1 - Using observed discount factor (11.49%):\n";
echo "Payoff = Current Balance * (1 - 0.1149)\n";
echo "Payoff = " . number_format($payoff_method1, 2) . "\n";
echo "Expected: 3,774,876.79\n";
echo "Difference: " . number_format($payoff_method1 - 3774876.79, 2) . "\n\n";

// Method 2: Calculate as principal portion only
// Payoff might be remaining principal without interest
$total_interest = $initial_balance * 0.12 * ($tenure / 12);
$total_with_interest = $initial_balance + $total_interest;
$payment_per_month = $total_with_interest / $tenure;
$principal_per_payment = $initial_balance / $tenure;
$remaining_principal = $initial_balance - ($principal_per_payment * $installments_paid);

echo "Method 2 - Principal-only calculation:\n";
echo "Principal per payment = Initial / Tenure = " . number_format($principal_per_payment, 2) . "\n";
echo "Remaining Principal = " . number_format($remaining_principal, 2) . "\n";
echo "Expected: 3,774,876.79\n";
echo "Difference: " . number_format($remaining_principal - 3774876.79, 2) . "\n\n";

// Method 3: Check if ded_balance_amount from Excel relates
$ded_balance = 5508591; // from Excel
echo "Method 3 - Using ded_balance_amount from Excel:\n";
echo "Ded Balance Amount: " . number_format($ded_balance, 2) . "\n";
$diff_from_current = $current_balance - $ded_balance;
echo "Current Balance - Ded Balance: " . number_format($diff_from_current, 2) . "\n";

// Try another relationship
$payoff_from_ded = $ded_balance - ($ded_balance * 0.314); // testing different factor
echo "Testing: Ded Balance * factor = " . number_format($payoff_from_ded, 2) . "\n";