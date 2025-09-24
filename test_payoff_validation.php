<?php

/**
 * Validation test for the improved payoff formula
 * This test validates the formula against known correct values
 */

echo "================================================\n";
echo "Validating Improved Payoff Formula\n";
echo "Formula: Total Payoff = P + (P × R/365 × D)\n";
echo "================================================\n\n";

/**
 * Test Case 1: Manual Calculation Verification
 * Let's manually verify the formula with simple numbers
 */
function testManualCalculation() {
    echo "=== Test Case 1: Manual Verification ===\n";
    
    // Simple test case
    $principal = 1000000; // 1 million outstanding principal
    $annualRate = 0.12;   // 12% annual interest
    $days = 30;           // 30 days since last payment
    
    // Manual calculation
    // Daily interest rate = 0.12 / 365 = 0.000328767
    // Interest for 30 days = 1,000,000 × 0.000328767 × 30 = 9,863.01
    // Total payoff = 1,000,000 + 9,863.01 = 1,009,863.01
    
    $dailyRate = $annualRate / 365;
    $interest = $principal * $dailyRate * $days;
    $totalPayoff = $principal + $interest;
    
    echo "Principal: " . number_format($principal, 2) . "\n";
    echo "Annual Rate: " . ($annualRate * 100) . "%\n";
    echo "Days: $days\n";
    echo "Daily Rate: " . number_format($dailyRate, 9) . "\n";
    echo "Interest: " . number_format($interest, 2) . "\n";
    echo "Total Payoff: " . number_format($totalPayoff, 2) . "\n";
    
    // Verify calculation
    $expectedInterest = 9863.01;
    $expectedPayoff = 1009863.01;
    
    $interestMatch = abs($interest - $expectedInterest) < 0.01;
    $payoffMatch = abs($totalPayoff - $expectedPayoff) < 0.01;
    
    echo "\nValidation:\n";
    echo "Interest calculation: " . ($interestMatch ? "✓ PASS" : "✗ FAIL") . "\n";
    echo "Payoff calculation: " . ($payoffMatch ? "✓ PASS" : "✗ FAIL") . "\n\n";
    
    return $interestMatch && $payoffMatch;
}

/**
 * Test Case 2: Amortization Schedule Verification
 * Verify that the outstanding principal calculation is correct
 */
function testAmortizationCalculation() {
    echo "=== Test Case 2: Amortization Verification ===\n";
    
    // Loan parameters
    $loanAmount = 1000000;
    $annualRate = 0.12;
    $monthlyRate = $annualRate / 12; // 0.01
    $tenure = 12; // 12 months
    
    // Calculate monthly payment using annuity formula
    // PMT = P × [r(1+r)^n] / [(1+r)^n - 1]
    $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $tenure)) / 
                     (pow(1 + $monthlyRate, $tenure) - 1);
    
    echo "Loan Amount: " . number_format($loanAmount, 2) . "\n";
    echo "Monthly Rate: " . ($monthlyRate * 100) . "%\n";
    echo "Tenure: $tenure months\n";
    echo "Monthly Payment: " . number_format($monthlyPayment, 2) . "\n\n";
    
    // Build amortization schedule for first 3 months
    echo "Amortization Schedule:\n";
    echo "Month | Interest | Principal | Balance\n";
    echo "----------------------------------------------\n";
    
    $balance = $loanAmount;
    $expectedBalances = [
        1 => 921151.21,  // After 1st payment
        2 => 841513.93,  // After 2nd payment
        3 => 761080.29   // After 3rd payment
    ];
    
    $allMatch = true;
    for ($month = 1; $month <= 3; $month++) {
        $interestPayment = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestPayment;
        $balance -= $principalPayment;
        
        echo sprintf("%5d | %8.2f | %9.2f | %10.2f", 
            $month, $interestPayment, $principalPayment, $balance);
        
        // Verify balance
        if (isset($expectedBalances[$month])) {
            $match = abs($balance - $expectedBalances[$month]) < 1.0;
            echo $match ? " ✓" : " ✗";
            $allMatch = $allMatch && $match;
        }
        echo "\n";
    }
    
    echo "\nValidation: " . ($allMatch ? "✓ PASS" : "✗ FAIL") . "\n\n";
    return $allMatch;
}

/**
 * Test Case 3: URL013572 Loan Verification
 * Test with the known loan data
 */
function testURL013572Loan() {
    echo "=== Test Case 3: URL013572 Loan Verification ===\n";
    
    // Known values for URL013572
    $requestedAmount = 6397076.00;
    $monthlyPayment = 177697.00;
    $tenure = 36;
    $installmentsPaid = 5;
    $annualRate = 0.12;
    $monthlyRate = $annualRate / 12;
    
    // Calculate original principal (present value)
    $originalPrincipal = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
    
    echo "Requested Amount: " . number_format($requestedAmount, 2) . "\n";
    echo "Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
    echo "Tenure: $tenure months\n";
    echo "Installments Paid: $installmentsPaid\n";
    echo "Calculated Original Principal: " . number_format($originalPrincipal, 2) . "\n\n";
    
    // Calculate outstanding principal after 5 payments
    $balance = $originalPrincipal;
    for ($i = 1; $i <= $installmentsPaid; $i++) {
        $interestForMonth = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestForMonth;
        $balance -= $principalPayment;
    }
    
    echo "Outstanding Principal after $installmentsPaid payments: " . number_format($balance, 2) . "\n";
    
    // Calculate payoff with 7 days interest
    $daysSincePayment = 7;
    $proRatedInterest = $balance * ($annualRate / 365) * $daysSincePayment;
    $totalPayoff = $balance + $proRatedInterest;
    
    echo "Pro-rated Interest (7 days): " . number_format($proRatedInterest, 2) . "\n";
    echo "Total Payoff: " . number_format($totalPayoff, 2) . "\n\n";
    
    // Expected values based on correct formula
    $expectedPrincipal = 5350013.32;
    $expectedOutstanding = 4716484.48;
    $expectedInterest = 10854.38;
    $expectedPayoff = 4727338.86;
    
    echo "Validation:\n";
    $principalMatch = abs($originalPrincipal - $expectedPrincipal) < 1.0;
    $outstandingMatch = abs($balance - $expectedOutstanding) < 1.0;
    $interestMatch = abs($proRatedInterest - $expectedInterest) < 1.0;
    $payoffMatch = abs($totalPayoff - $expectedPayoff) < 1.0;
    
    echo "Original Principal: " . ($principalMatch ? "✓ PASS" : "✗ FAIL") . "\n";
    echo "Outstanding Principal: " . ($outstandingMatch ? "✓ PASS" : "✗ FAIL") . "\n";
    echo "Pro-rated Interest: " . ($interestMatch ? "✓ PASS" : "✗ FAIL") . "\n";
    echo "Total Payoff: " . ($payoffMatch ? "✓ PASS" : "✗ FAIL") . "\n\n";
    
    return $principalMatch && $outstandingMatch && $interestMatch && $payoffMatch;
}

/**
 * Test Case 4: Edge Cases
 */
function testEdgeCases() {
    echo "=== Test Case 4: Edge Cases ===\n";
    
    $tests = [
        [
            'name' => 'Zero Interest Rate',
            'principal' => 100000,
            'rate' => 0,
            'days' => 30,
            'expected_interest' => 0,
            'expected_payoff' => 100000
        ],
        [
            'name' => 'One Day Interest',
            'principal' => 100000,
            'rate' => 0.12,
            'days' => 1,
            'expected_interest' => 32.88,
            'expected_payoff' => 100032.88
        ],
        [
            'name' => 'Full Year Interest',
            'principal' => 100000,
            'rate' => 0.12,
            'days' => 365,
            'expected_interest' => 12000,
            'expected_payoff' => 112000
        ]
    ];
    
    $allPass = true;
    foreach ($tests as $test) {
        echo "Test: {$test['name']}\n";
        
        $interest = $test['principal'] * ($test['rate'] / 365) * $test['days'];
        $payoff = $test['principal'] + $interest;
        
        $interestMatch = abs($interest - $test['expected_interest']) < 0.01;
        $payoffMatch = abs($payoff - $test['expected_payoff']) < 0.01;
        
        echo "  Interest: " . number_format($interest, 2) . 
             " (expected: " . number_format($test['expected_interest'], 2) . ") " .
             ($interestMatch ? "✓" : "✗") . "\n";
        echo "  Payoff: " . number_format($payoff, 2) . 
             " (expected: " . number_format($test['expected_payoff'], 2) . ") " .
             ($payoffMatch ? "✓" : "✗") . "\n";
        
        $allPass = $allPass && $interestMatch && $payoffMatch;
    }
    
    echo "\nValidation: " . ($allPass ? "✓ PASS" : "✗ FAIL") . "\n\n";
    return $allPass;
}

// Run all tests
$results = [];
$results[] = testManualCalculation();
$results[] = testAmortizationCalculation();
$results[] = testURL013572Loan();
$results[] = testEdgeCases();

// Summary
echo "================================================\n";
echo "Test Summary\n";
echo "================================================\n";
$totalTests = count($results);
$passedTests = array_sum($results);
$failedTests = $totalTests - $passedTests;

echo "Total Tests: $totalTests\n";
echo "Passed: $passedTests\n";
echo "Failed: $failedTests\n";

if ($failedTests === 0) {
    echo "\n✓ All tests PASSED! The improved formula is working correctly.\n";
} else {
    echo "\n✗ Some tests FAILED. Please review the implementation.\n";
}

echo "================================================\n";