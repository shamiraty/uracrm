<?php

echo "=== Testing Reducing Balance Calculation Based on Your Guidelines ===\n\n";

/**
 * Calculate loan details based on Excel structure
 */
function analyzeLoanFromExcel($initialBalance, $monthlyPayment, $dedBalanceAmount) {
    // Step 1: Calculate total tenure
    $totalTenure = round($initialBalance / $monthlyPayment);
    
    // Step 2: Calculate remaining tenure
    $remainingTenure = round($dedBalanceAmount / $monthlyPayment);
    
    // Step 3: Calculate installments paid
    $installmentsPaid = $totalTenure - $remainingTenure;
    
    return [
        'total_tenure' => $totalTenure,
        'remaining_tenure' => $remainingTenure,
        'installments_paid' => $installmentsPaid
    ];
}

/**
 * Calculate payoff using reducing balance formula
 * This gets the principal balance at the last paid installment
 */
function calculateReducingBalancePayoff($principal, $monthlyPayment, $tenure, $installmentsPaid, $annualRate = 12) {
    $monthlyRate = $annualRate / 100 / 12;
    
    // Build amortization schedule to find exact principal balance
    $balance = $principal;
    $schedule = [];
    
    for ($month = 1; $month <= $tenure; $month++) {
        $interestForMonth = $balance * $monthlyRate;
        $principalForMonth = $monthlyPayment - $interestForMonth;
        
        // Don't let balance go negative
        if ($principalForMonth > $balance) {
            $principalForMonth = $balance;
        }
        
        $balance = $balance - $principalForMonth;
        
        $schedule[] = [
            'month' => $month,
            'interest' => $interestForMonth,
            'principal' => $principalForMonth,
            'balance' => $balance
        ];
        
        // If this is the installment we're looking for
        if ($month == $installmentsPaid) {
            return [
                'payoff_amount' => round($balance, 2),
                'schedule_to_date' => array_slice($schedule, 0, 5) // First 5 for review
            ];
        }
        
        if ($balance <= 0) {
            break;
        }
    }
    
    return [
        'payoff_amount' => 0,
        'schedule_to_date' => $schedule
    ];
}

// Test Case 1: URL013572
echo "Test Case 1: URL013572\n";
echo str_repeat("=", 60) . "\n";

$initialBalance = 6397076.00;
$monthlyPayment = 177697.00;
$dedBalanceAmount = 5508591.00;
$expectedPayoff = 3774876.79;

echo "Excel Data:\n";
echo "  initial_balance: " . number_format($initialBalance, 2) . "\n";
echo "  amount (monthly): " . number_format($monthlyPayment, 2) . "\n";
echo "  ded_balance_amount: " . number_format($dedBalanceAmount, 2) . "\n";
echo "  Expected Payoff: " . number_format($expectedPayoff, 2) . "\n\n";

// Analyze the loan
$loanInfo = analyzeLoanFromExcel($initialBalance, $monthlyPayment, $dedBalanceAmount);
echo "Calculated Loan Info:\n";
echo "  Total Tenure: {$loanInfo['total_tenure']} months\n";
echo "  Remaining Tenure: {$loanInfo['remaining_tenure']} months\n";
echo "  Installments Paid: {$loanInfo['installments_paid']} months\n\n";

// Calculate payoff using reducing balance
$result = calculateReducingBalancePayoff($initialBalance, $monthlyPayment, $loanInfo['total_tenure'], $loanInfo['installments_paid']);

echo "Amortization Schedule (First 5 months):\n";
echo str_pad("Month", 8) . str_pad("Interest", 15) . str_pad("Principal", 15) . str_pad("Balance", 15) . "\n";
echo str_repeat("-", 53) . "\n";
foreach ($result['schedule_to_date'] as $row) {
    echo str_pad($row['month'], 8) . 
         str_pad(number_format($row['interest'], 2), 15) . 
         str_pad(number_format($row['principal'], 2), 15) . 
         str_pad(number_format($row['balance'], 2), 15) . "\n";
}

echo "\nCalculated Payoff (Principal Balance): " . number_format($result['payoff_amount'], 2) . "\n";
echo "Expected Payoff: " . number_format($expectedPayoff, 2) . "\n";

// Check if we need a factor
$factor = $expectedPayoff / $result['payoff_amount'];
echo "\nFactor needed: " . number_format($factor, 6) . "\n";

// Alternative: Maybe the payoff is based on ded_balance_amount
$factorFromDedBalance = $expectedPayoff / $dedBalanceAmount;
echo "Factor from ded_balance: " . number_format($factorFromDedBalance, 6) . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "CONCLUSION:\n";
echo "The expected payoff ({$expectedPayoff}) is:\n";
echo "  ded_balance_amount × " . number_format($factorFromDedBalance, 6) . " = " . 
     number_format($dedBalanceAmount * $factorFromDedBalance, 2) . "\n\n";

echo "This suggests that for imported loans:\n";
echo "  Payoff = ded_balance_amount × 0.685271\n";
echo "\nWhere ded_balance_amount is the outstanding balance from the Excel import.\n";