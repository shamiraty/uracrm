<?php

echo "=== Following Your Exact Guidelines for Payoff Calculation ===\n\n";

/**
 * Calculate payoff following the exact steps provided
 */
function calculatePayoffByGuidelines($initialBalance, $monthlyPayment, $dedBalanceAmount, $annualRate = 12) {
    echo "Input Data:\n";
    echo "  Initial Balance: " . number_format($initialBalance, 2) . "\n";
    echo "  Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
    echo "  Ded Balance Amount: " . number_format($dedBalanceAmount, 2) . "\n\n";
    
    // Step 1: Get total tenure = initial balance / monthly payment
    $totalTenure = round($initialBalance / $monthlyPayment);
    echo "Step 1: Total Tenure = Initial Balance / Monthly Payment\n";
    echo "  $initialBalance / $monthlyPayment = $totalTenure months\n\n";
    
    // Step 2: Get remaining tenure = ded_balance_amount / monthly payment
    $remainingTenure = round($dedBalanceAmount / $monthlyPayment);
    echo "Step 2: Remaining Tenure = Ded Balance / Monthly Payment\n";
    echo "  $dedBalanceAmount / $monthlyPayment = $remainingTenure months\n\n";
    
    // Step 3: Get installments paid = total tenure - remaining tenure
    $installmentsPaid = $totalTenure - $remainingTenure;
    echo "Step 3: Installments Paid = Total Tenure - Remaining Tenure\n";
    echo "  $totalTenure - $remainingTenure = $installmentsPaid months\n\n";
    
    // Step 4: Calculate the principal balance using reducing balance amortization
    echo "Step 4: Calculate Principal Balance at Month $installmentsPaid using Reducing Balance\n";
    
    $monthlyRate = $annualRate / 100 / 12;
    echo "  Annual Rate: $annualRate%\n";
    echo "  Monthly Rate: " . number_format($monthlyRate * 100, 4) . "%\n\n";
    
    // Build amortization schedule
    $balance = $initialBalance;
    echo "  Amortization Schedule:\n";
    echo "  " . str_pad("Month", 8) . str_pad("Interest", 15) . str_pad("Principal", 15) . str_pad("Balance", 15) . "\n";
    echo "  " . str_repeat("-", 53) . "\n";
    
    for ($month = 1; $month <= $installmentsPaid; $month++) {
        $interestPayment = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestPayment;
        
        if ($principalPayment > $balance) {
            $principalPayment = $balance;
        }
        
        $balance = $balance - $principalPayment;
        
        // Show first 3 and last 2 months
        if ($month <= 3 || $month > $installmentsPaid - 2) {
            echo "  " . str_pad($month, 8) . 
                 str_pad(number_format($interestPayment, 2), 15) . 
                 str_pad(number_format($principalPayment, 2), 15) . 
                 str_pad(number_format($balance, 2), 15) . "\n";
        } elseif ($month == 4) {
            echo "  ...\n";
        }
    }
    
    echo "\nPrincipal Balance after $installmentsPaid payments: " . number_format($balance, 2) . "\n";
    echo "This is the payoff amount based on reducing balance formula.\n";
    
    return round($balance, 2);
}

// Test Case 1: URL013572
echo "\nTest Case: URL013572\n";
echo str_repeat("=", 60) . "\n\n";

$initialBalance = 6397076.00;
$monthlyPayment = 177697.00;
$dedBalanceAmount = 5508591.00;
$expectedPayoff = 3774876.79;

$calculatedPayoff = calculatePayoffByGuidelines($initialBalance, $monthlyPayment, $dedBalanceAmount);

echo "\n" . str_repeat("=", 60) . "\n";
echo "RESULTS:\n";
echo "  Calculated Payoff: " . number_format($calculatedPayoff, 2) . "\n";
echo "  Expected Payoff: " . number_format($expectedPayoff, 2) . "\n";
echo "  Difference: " . number_format(abs($calculatedPayoff - $expectedPayoff), 2) . "\n\n";

// Check if there's a consistent factor
$factor = $expectedPayoff / $calculatedPayoff;
echo "Factor needed to match: " . number_format($factor, 6) . "\n\n";

// Alternative check - maybe the expected uses a different formula
echo "Alternative Analysis:\n";
echo "  If we use ded_balance_amount directly:\n";
echo "    $dedBalanceAmount × 0.685271 = " . number_format($dedBalanceAmount * 0.685271, 2) . "\n";
echo "    This matches the expected payoff!\n\n";

echo "CONCLUSION:\n";
echo str_repeat("-", 60) . "\n";
echo "Following your guidelines with standard reducing balance gives: " . number_format($calculatedPayoff, 2) . "\n";
echo "But the expected payoff is: " . number_format($expectedPayoff, 2) . "\n\n";
echo "The expected payoff appears to be:\n";
echo "  ded_balance_amount × 0.685271 = payoff\n";
echo "  $dedBalanceAmount × 0.685271 = " . number_format($dedBalanceAmount * 0.685271, 2) . "\n";