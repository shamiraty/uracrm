<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\LoanOffer;

echo "=== Testing ESS Loan Topup Payoff Calculation ===\n\n";

// Test data for different loan scenarios
$testCases = [
    [
        'name' => 'Imported Loan (CHIBITA)',
        'loan_number' => 'URL013572',
        'requested_amount' => 6397076.00,
        'desired_deductible_amount' => 177697.00,
        'installments_paid' => 5,
        'tenure' => 36,
        'state' => 'Active Loan - Imported',
        'expected_payoff' => 3774876.79
    ],
    [
        'name' => 'Regular Loan with Reducing Balance',
        'loan_number' => 'TEST001',
        'requested_amount' => 1000000.00,
        'desired_deductible_amount' => 30000.00,
        'installments_paid' => 10,
        'tenure' => 48,
        'interest_rate' => 12,
        'state' => 'Active Loan',
        'expected_payoff' => null // Will be calculated
    ]
];

foreach ($testCases as $test) {
    echo "Testing: {$test['name']}\n";
    echo "Loan Number: {$test['loan_number']}\n";
    echo "Principal: " . number_format($test['requested_amount'], 2) . "\n";
    echo "Monthly Payment: " . number_format($test['desired_deductible_amount'], 2) . "\n";
    echo "Installments Paid: {$test['installments_paid']}\n";
    echo "Tenure: {$test['tenure']} months\n\n";
    
    // Check if imported loan
    $isImported = str_contains(strtolower($test['state']), 'imported');
    
    if ($isImported) {
        // Imported loan calculation
        $initialBalance = $test['requested_amount'];
        $monthlyPayment = $test['desired_deductible_amount'];
        $installmentsPaid = $test['installments_paid'];
        
        // Calculate ded_balance
        $dedBalanceAmount = $initialBalance - ($monthlyPayment * $installmentsPaid);
        
        // Apply payoff factor
        $payoffFactor = 0.685271;
        $calculatedPayoff = round($dedBalanceAmount * $payoffFactor, 2);
        
        echo "Calculation Method: Imported Loan (Payoff Factor)\n";
        echo "Ded Balance Amount: " . number_format($dedBalanceAmount, 2) . "\n";
        echo "Payoff Factor: $payoffFactor\n";
        echo "Calculated Payoff: " . number_format($calculatedPayoff, 2) . "\n";
        
        if ($test['expected_payoff']) {
            echo "Expected Payoff: " . number_format($test['expected_payoff'], 2) . "\n";
            $difference = abs($calculatedPayoff - $test['expected_payoff']);
            if ($difference < 0.01) {
                echo "✓ SUCCESS: Calculation matches expected value\n";
            } else {
                echo "✗ ERROR: Difference of " . number_format($difference, 2) . "\n";
            }
        }
    } else {
        // Regular loan with reducing balance
        $principal = $test['requested_amount'];
        $annualRate = $test['interest_rate'] ?? 12;
        $monthlyRate = $annualRate / 100 / 12;
        $totalTenure = $test['tenure'];
        $installmentsPaid = $test['installments_paid'];
        
        // Calculate monthly payment using amortization formula
        $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $totalTenure)) / 
                          (pow(1 + $monthlyRate, $totalTenure) - 1);
        
        // Calculate remaining balance
        $remainingPayments = $totalTenure - $installmentsPaid;
        $remainingBalance = $monthlyPayment * (1 - pow(1 + $monthlyRate, -$remainingPayments)) / $monthlyRate;
        
        echo "Calculation Method: Reducing Balance (Amortization)\n";
        echo "Annual Interest Rate: $annualRate%\n";
        echo "Monthly Rate: " . number_format($monthlyRate * 100, 4) . "%\n";
        echo "Calculated Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
        echo "Remaining Payments: $remainingPayments\n";
        echo "Calculated Payoff: " . number_format($remainingBalance, 2) . "\n";
    }
    
    echo "\n" . str_repeat("-", 60) . "\n\n";
}

// Test the actual controller method if running in Laravel context
if (class_exists('App\Http\Controllers\EmployeeLoanController')) {
    echo "Testing Controller Methods:\n\n";
    
    $controller = new \App\Http\Controllers\EmployeeLoanController();
    
    // Use reflection to test private methods
    $reflector = new ReflectionClass($controller);
    
    // Test calculatePartialPaymentBalance
    $method = $reflector->getMethod('calculatePartialPaymentBalance');
    $method->setAccessible(true);
    
    $principal = 1000000;
    $monthlyRate = 0.01; // 1% per month (12% annual)
    $totalTenure = 36;
    $installmentsPaid = 10;
    
    $result = $method->invoke($controller, $principal, $monthlyRate, $totalTenure, $installmentsPaid);
    
    echo "Controller Method Test:\n";
    echo "Principal: " . number_format($principal, 2) . "\n";
    echo "Monthly Rate: " . ($monthlyRate * 100) . "%\n";
    echo "Total Tenure: $totalTenure months\n";
    echo "Installments Paid: $installmentsPaid\n";
    echo "Calculated Balance: " . number_format($result, 2) . "\n";
}

echo "\n=== Test Complete ===\n";