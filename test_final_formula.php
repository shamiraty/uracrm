<?php

echo "=== Testing Final Formula for Imported Loans ===\n\n";

// Test with CHIBITA loan data
$initial_balance = 6397076.00;
$monthly_payment = 177697.00;
$installments_paid = 5;

// Step 1: Simple remaining balance
$simple_balance = $initial_balance - ($monthly_payment * $installments_paid);
echo "Initial Balance: " . number_format($initial_balance, 2) . "\n";
echo "Monthly Payment: " . number_format($monthly_payment, 2) . "\n";
echo "Installments Paid: $installments_paid\n";
echo "Simple Balance: " . number_format($simple_balance, 2) . "\n\n";

// Step 2: Apply net balance factor
$netBalanceFactor = 0.7745;
$net_balance = $simple_balance * $netBalanceFactor;
echo "Applying Net Balance Factor: $netBalanceFactor\n";
echo "Net Balance: " . number_format($net_balance, 2) . "\n\n";

// Step 3: Apply payoff discount
$payoffFactor = 0.885142;
$payoff = $net_balance * $payoffFactor;
echo "Applying Payoff Factor: $payoffFactor\n";
echo "Calculated Payoff: " . number_format($payoff, 2) . "\n";
echo "Expected Payoff: 3,774,876.79\n";
echo "Difference: " . number_format($payoff - 3774876.79, 2) . "\n";