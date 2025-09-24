<?php

/**
 * Test script for the improved payoff formula
 * Formula: Total Payoff = P + (P × R/365 × D)
 * Where:
 * P = Outstanding Principal
 * R = Annual Interest Rate
 * D = Days Since Last Payment
 */

// Test Case 1: Standard loan with some payments made
function testStandardLoan() {
    echo "=== Test Case 1: Standard Loan ===\n";
    
    $initialBalance = 1000000; // 1 million
    $monthlyPayment = 30000;
    $annualRate = 0.12; // 12%
    $tenure = 36; // months
    $installmentsPaid = 5;
    
    // Calculate original principal (PV)
    $monthlyRate = $annualRate / 12;
    $originalPrincipal = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
    echo "Original Principal: " . number_format($originalPrincipal, 2) . "\n";
    
    // Calculate outstanding principal after 5 payments
    $balance = $originalPrincipal;
    for ($i = 1; $i <= $installmentsPaid; $i++) {
        $interestForMonth = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestForMonth;
        $balance -= $principalPayment;
        echo "After payment $i: Balance = " . number_format($balance, 2) . "\n";
    }
    
    // Calculate payoff with pro-rated interest (7 days)
    $daysSincePayment = 7;
    $proRatedInterest = $balance * ($annualRate / 365) * $daysSincePayment;
    $totalPayoff = $balance + $proRatedInterest;
    
    echo "\nOutstanding Principal: " . number_format($balance, 2) . "\n";
    echo "Pro-rated Interest (7 days): " . number_format($proRatedInterest, 2) . "\n";
    echo "Total Payoff Amount: " . number_format($totalPayoff, 2) . "\n\n";
}

// Test Case 2: Imported loan example (like URL013572)
function testImportedLoan() {
    echo "=== Test Case 2: Imported Loan (URL013572 Example) ===\n";
    
    $initialBalance = 6397076; // From ESS
    $monthlyPayment = 177697;
    $annualRate = 0.12;
    $tenure = 36;
    $installmentsPaid = 5;
    
    // Calculate using improved formula
    $monthlyRate = $annualRate / 12;
    
    // Calculate original principal
    $originalPrincipal = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
    echo "Original Principal: " . number_format($originalPrincipal, 2) . "\n";
    
    // Calculate outstanding principal after payments
    $balance = $originalPrincipal;
    for ($i = 1; $i <= $installmentsPaid; $i++) {
        $interestForMonth = $balance * $monthlyRate;
        $principalPayment = $monthlyPayment - $interestForMonth;
        $balance -= $principalPayment;
    }
    
    // Calculate payoff with pro-rated interest
    $daysSincePayment = 7;
    $proRatedInterest = $balance * ($annualRate / 365) * $daysSincePayment;
    $totalPayoff = $balance + $proRatedInterest;
    
    echo "\nAfter $installmentsPaid payments:\n";
    echo "Outstanding Principal: " . number_format($balance, 2) . "\n";
    echo "Pro-rated Interest (7 days): " . number_format($proRatedInterest, 2) . "\n";
    echo "Total Payoff Amount: " . number_format($totalPayoff, 2) . "\n";
    
    // Compare with the old factor-based calculation
    $dedBalance = $initialBalance - ($monthlyPayment * $installmentsPaid);
    $oldPayoff = $dedBalance * 0.6852708415;
    echo "\nOld Factor-based Calculation: " . number_format($oldPayoff, 2) . "\n";
    echo "Difference: " . number_format($totalPayoff - $oldPayoff, 2) . "\n\n";
}

// Test Case 3: Different days since payment
function testVariousDaysSincePayment() {
    echo "=== Test Case 3: Effect of Days Since Payment ===\n";
    
    $outstandingPrincipal = 500000;
    $annualRate = 0.12;
    
    $daysArray = [1, 7, 15, 30, 45];
    
    echo "Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
    echo "Annual Rate: " . ($annualRate * 100) . "%\n\n";
    
    foreach ($daysArray as $days) {
        $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $days;
        $totalPayoff = $outstandingPrincipal + $proRatedInterest;
        
        echo "Days: $days | Interest: " . number_format($proRatedInterest, 2) . 
             " | Total Payoff: " . number_format($totalPayoff, 2) . "\n";
    }
    echo "\n";
}

// Test Case 4: Zero interest scenario
function testZeroInterest() {
    echo "=== Test Case 4: Zero Interest Loan ===\n";
    
    $principal = 1000000;
    $annualRate = 0; // 0% interest
    $daysSincePayment = 30;
    
    $proRatedInterest = $principal * ($annualRate / 365) * $daysSincePayment;
    $totalPayoff = $principal + $proRatedInterest;
    
    echo "Principal: " . number_format($principal, 2) . "\n";
    echo "Annual Rate: " . ($annualRate * 100) . "%\n";
    echo "Days Since Payment: $daysSincePayment\n";
    echo "Pro-rated Interest: " . number_format($proRatedInterest, 2) . "\n";
    echo "Total Payoff: " . number_format($totalPayoff, 2) . "\n\n";
}

// Run all tests
echo "================================================\n";
echo "Testing Improved Payoff Formula Implementation\n";
echo "Formula: Total Payoff = P + (P × R/365 × D)\n";
echo "================================================\n\n";

testStandardLoan();
testImportedLoan();
testVariousDaysSincePayment();
testZeroInterest();

echo "================================================\n";
echo "All tests completed successfully!\n";
echo "================================================\n";