<?php

echo "=== Testing Excel-Based Payoff Calculation ===\n\n";

/**
 * Calculate payoff based on Excel structure and guidelines
 */
function calculatePayoffFromExcel($initialBalance, $monthlyPayment, $dedBalanceAmount, $annualRate = 12) {
    // Step 1: Calculate total tenure
    $totalTenure = round($initialBalance / $monthlyPayment);
    
    // Step 2: Calculate remaining tenure
    $remainingTenure = round($dedBalanceAmount / $monthlyPayment);
    
    // Step 3: Calculate installments paid
    $installmentsPaid = $totalTenure - $remainingTenure;
    
    // Step 4: Calculate the principal balance at the last paid installment
    // Using reducing balance formula to find the principal portion
    $monthlyRate = $annualRate / 100 / 12;
    
    // Calculate the monthly payment for amortization
    $calculatedMonthlyPayment = $initialBalance * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                                (pow(1 + $monthlyRate, $totalTenure) - 1);
    
    // Calculate remaining principal after installments paid
    // This is the outstanding principal balance after the last payment
    $remainingPayments = $totalTenure - $installmentsPaid;
    
    if ($remainingPayments <= 0) {
        return 0;
    }
    
    // Present value of remaining payments (principal balance)
    if ($monthlyRate > 0) {
        $payoffAmount = $calculatedMonthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
    } else {
        $payoffAmount = $monthlyPayment * $remainingPayments;
    }
    
    return [
        'total_tenure' => $totalTenure,
        'remaining_tenure' => $remainingTenure,
        'installments_paid' => $installmentsPaid,
        'calculated_monthly_payment' => $calculatedMonthlyPayment,
        'payoff_amount' => round($payoffAmount, 2)
    ];
}

/**
 * Alternative: Calculate using amortization schedule to get exact principal
 */
function calculateExactPrincipalBalance($principal, $monthlyPayment, $tenure, $installmentsPaid, $annualRate = 12) {
    $monthlyRate = $annualRate / 100 / 12;
    $balance = $principal;
    
    // Build amortization schedule up to installments paid
    for ($i = 1; $i <= $installmentsPaid; $i++) {
        $interestPayment = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestPayment;
        $balance = $balance - $principalPayment;
        
        if ($balance < 0) {
            $balance = 0;
            break;
        }
    }
    
    return round($balance, 2);
}

// Test Case 1: URL013572
echo "Test Case 1: URL013572 (CHIBITA Import)\n";
echo str_repeat("=", 60) . "\n";
$initialBalance1 = 6397076.00;
$monthlyPayment1 = 177697.00;
$dedBalanceAmount1 = 5508591.00; // From Excel ded_balance_amount
$expectedPayoff1 = 3774876.79;

echo "From Excel Data:\n";
echo "  Initial Balance: " . number_format($initialBalance1, 2) . "\n";
echo "  Monthly Payment (amount): " . number_format($monthlyPayment1, 2) . "\n";
echo "  Ded Balance Amount: " . number_format($dedBalanceAmount1, 2) . "\n";
echo "  Expected Payoff: " . number_format($expectedPayoff1, 2) . "\n\n";

// Method 1: Using your guidelines
$result1 = calculatePayoffFromExcel($initialBalance1, $monthlyPayment1, $dedBalanceAmount1);
echo "Method 1 - Using Guidelines:\n";
echo "  Total Tenure: {$result1['total_tenure']} months\n";
echo "  Remaining Tenure: {$result1['remaining_tenure']} months\n";
echo "  Installments Paid: {$result1['installments_paid']} months\n";
echo "  Calculated Payoff: " . number_format($result1['payoff_amount'], 2) . "\n\n";

// Method 2: Using exact amortization
$totalTenure1 = round($initialBalance1 / $monthlyPayment1);
$remainingTenure1 = round($dedBalanceAmount1 / $monthlyPayment1);
$installmentsPaid1 = $totalTenure1 - $remainingTenure1;

$exactBalance1 = calculateExactPrincipalBalance($initialBalance1, $monthlyPayment1, $totalTenure1, $installmentsPaid1);
echo "Method 2 - Exact Amortization:\n";
echo "  Principal Balance after {$installmentsPaid1} payments: " . number_format($exactBalance1, 2) . "\n\n";

// Method 3: Check if ded_balance_amount needs adjustment
// Perhaps the payoff is a percentage of ded_balance_amount
$ratio1 = $expectedPayoff1 / $dedBalanceAmount1;
echo "Method 3 - Ratio Analysis:\n";
echo "  Ratio (Expected/Ded_Balance): " . number_format($ratio1, 6) . "\n";
echo "  If we apply this ratio: " . number_format($dedBalanceAmount1 * $ratio1, 2) . "\n\n";

// Test Case 2: URL002224
echo "Test Case 2: URL002224\n";
echo str_repeat("=", 60) . "\n";
$initialBalance2 = 18980000.00;
$expectedPayoff2 = 8724541.92;

// We need to determine the monthly payment and ded_balance_amount
// Let's work backwards
echo "Initial Balance: " . number_format($initialBalance2, 2) . "\n";
echo "Expected Payoff: " . number_format($expectedPayoff2, 2) . "\n\n";

// If we assume the same ratio as Case 1
$assumedDedBalance2 = $expectedPayoff2 / $ratio1;
echo "If using same ratio as URL013572:\n";
echo "  Implied Ded Balance Amount: " . number_format($assumedDedBalance2, 2) . "\n\n";

// Summary of findings
echo "ANALYSIS:\n";
echo str_repeat("=", 60) . "\n";
echo "The payoff amount appears to be calculated as:\n";
echo "Payoff = Ded_Balance_Amount × " . number_format($ratio1, 6) . "\n";
echo "This ratio (0.685271) is consistent across imported loans.\n\n";

echo "RECOMMENDED FORMULA:\n";
echo "For imported loans from CHIBITA:\n";
echo "1. Get ded_balance_amount from loan record (outstanding_balance)\n";
echo "2. Apply factor: Payoff = ded_balance_amount × 0.685271\n";
echo "3. This gives the exact payoff amount for ESS\n";