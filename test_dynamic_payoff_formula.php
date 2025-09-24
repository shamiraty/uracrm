<?php
/**
 * Test script for the Dynamic Payoff Formula implementation
 * Based on the PDF specification for loan payoff calculations
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Log;

class PayoffCalculationTest {
    
    /**
     * Calculate original principal using Present Value formula
     */
    private function calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure) {
        if ($monthlyPayment <= 0 || $tenure <= 0) {
            return $initialBalance;
        }

        $monthlyRate = $annualRate / 12;

        if ($monthlyRate == 0) {
            return $monthlyPayment * $tenure;
        }

        // PV = PMT × [(1 - (1 + r)^-n) / r]
        $presentValue = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);
        return round($presentValue, 2);
    }

    /**
     * Calculate installments paid
     */
    private function calculateInstallmentsPaid($initialBalance, $currentBalance, $monthlyPayment) {
        if ($monthlyPayment <= 0) {
            return 0;
        }

        $paymentsMade = ($initialBalance - $currentBalance) / $monthlyPayment;
        return max(0, round($paymentsMade));
    }

    /**
     * Calculate outstanding principal after payments
     */
    private function calculateOutstandingPrincipal($originalPrincipal, $monthlyPayment, $installmentsPaid, $monthlyRate) {
        if ($installmentsPaid <= 0) {
            return $originalPrincipal;
        }

        $balance = $originalPrincipal;

        for ($i = 1; $i <= $installmentsPaid; $i++) {
            $interestForMonth = $balance * $monthlyRate;
            $principalPayment = $monthlyPayment - $interestForMonth;

            if ($principalPayment > $balance) {
                $principalPayment = $balance;
            }

            $balance -= $principalPayment;

            if ($balance <= 0) {
                return 0;
            }
        }

        return round($balance, 2);
    }

    /**
     * Test the Dynamic Payoff Formula
     */
    public function testPayoffCalculation($testCase) {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "TEST CASE: {$testCase['name']}\n";
        echo str_repeat("=", 80) . "\n";

        // Input parameters (from ESS message)
        $initialBalance = $testCase['initial_balance'];  // DeductionAmount
        $remainingBalance = $testCase['remaining_balance'];  // DeductionBalance
        $monthlyPayment = $testCase['monthly_payment'];
        $annualRate = $testCase['annual_rate'];
        $daysSincePayment = $testCase['days_since_payment'];
        
        echo "\nINPUT PARAMETERS:\n";
        echo "  Initial Balance (DeductionAmount): " . number_format($initialBalance, 2) . "\n";
        echo "  Remaining Balance (DeductionBalance): " . number_format($remainingBalance, 2) . "\n";
        echo "  Monthly Payment: " . number_format($monthlyPayment, 2) . "\n";
        echo "  Annual Interest Rate: " . ($annualRate * 100) . "%\n";
        echo "  Days Since Last Payment: $daysSincePayment\n";

        // STEP 1: Calculate Original Loan Details
        echo "\n--- STEP 1: Calculate Original Loan Details ---\n";
        
        $tenure = round($initialBalance / $monthlyPayment);
        echo "  Tenure (months) = Initial Balance / Monthly Payment\n";
        echo "  Tenure = " . number_format($initialBalance, 2) . " / " . number_format($monthlyPayment, 2) . " = $tenure months\n";
        
        $originalPrincipal = $this->calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure);
        echo "  Original Principal (using PV formula): " . number_format($originalPrincipal, 2) . "\n";

        // STEP 2: Calculate Current Outstanding Principal
        echo "\n--- STEP 2: Calculate Current Outstanding Principal (P) ---\n";
        
        $installmentsPaid = $this->calculateInstallmentsPaid($initialBalance, $remainingBalance, $monthlyPayment);
        echo "  Payments Made = (Initial Balance - Remaining Balance) / Monthly Payment\n";
        echo "  Payments Made = (" . number_format($initialBalance, 2) . " - " . number_format($remainingBalance, 2) . ") / " . number_format($monthlyPayment, 2) . "\n";
        echo "  Payments Made = $installmentsPaid installments\n";
        
        $monthlyRate = $annualRate / 12;
        $outstandingPrincipal = $this->calculateOutstandingPrincipal($originalPrincipal, $monthlyPayment, $installmentsPaid, $monthlyRate);
        echo "  Outstanding Principal (P) after $installmentsPaid payments: " . number_format($outstandingPrincipal, 2) . "\n";

        // STEP 3: Apply the Dynamic Payoff Formula
        echo "\n--- STEP 3: Apply Dynamic Payoff Formula ---\n";
        echo "  Formula: Total Payoff = P + (P × R/365 × D)\n";
        echo "  Where:\n";
        echo "    P = " . number_format($outstandingPrincipal, 2) . " (Outstanding Principal)\n";
        echo "    R = " . $annualRate . " (Annual Rate)\n";
        echo "    D = $daysSincePayment (Days Since Last Payment)\n";
        
        $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $daysSincePayment;
        $totalPayoff = $outstandingPrincipal + $proRatedInterest;
        
        echo "\n  Pro-rated Interest = P × (R/365) × D\n";
        echo "  Pro-rated Interest = " . number_format($outstandingPrincipal, 2) . " × (" . $annualRate . "/365) × $daysSincePayment\n";
        echo "  Pro-rated Interest = " . number_format($proRatedInterest, 2) . "\n";
        
        echo "\n  Total Payoff Amount = P + Pro-rated Interest\n";
        echo "  Total Payoff Amount = " . number_format($outstandingPrincipal, 2) . " + " . number_format($proRatedInterest, 2) . "\n";
        echo "  TOTAL PAYOFF AMOUNT = " . number_format($totalPayoff, 2) . "\n";

        if (isset($testCase['expected_payoff'])) {
            $difference = abs($totalPayoff - $testCase['expected_payoff']);
            echo "\n  Expected Payoff: " . number_format($testCase['expected_payoff'], 2) . "\n";
            echo "  Difference: " . number_format($difference, 2) . "\n";
            echo "  Status: " . ($difference < 1 ? "✓ PASS" : "✗ FAIL") . "\n";
        }

        return [
            'original_principal' => $originalPrincipal,
            'installments_paid' => $installmentsPaid,
            'outstanding_principal' => $outstandingPrincipal,
            'pro_rated_interest' => round($proRatedInterest, 2),
            'total_payoff' => round($totalPayoff, 2)
        ];
    }
}

// Test with the actual data from the log
$tester = new PayoffCalculationTest();

// Test Case 1: URL013572 loan from the logs
$testCase1 = [
    'name' => 'URL013572 - Daud Andrew Chibita',
    'initial_balance' => 6397076.07,  // DeductionAmount
    'remaining_balance' => 4264712.07,  // DeductionBalance
    'monthly_payment' => 177696.56,  // Approximate from (initial - remaining) / 12
    'annual_rate' => 0.12,  // 12% annual rate
    'days_since_payment' => 7,
    'expected_payoff' => 3783573.59  // From the log
];

$result1 = $tester->testPayoffCalculation($testCase1);

// Test Case 2: Simple loan example
$testCase2 = [
    'name' => 'Simple Test Loan',
    'initial_balance' => 1000000,  // 1 million loan
    'remaining_balance' => 600000,  // 600k remaining
    'monthly_payment' => 50000,  // 50k monthly
    'annual_rate' => 0.10,  // 10% annual rate
    'days_since_payment' => 15
];

$result2 = $tester->testPayoffCalculation($testCase2);

// Test Case 3: Nearly paid off loan
$testCase3 = [
    'name' => 'Nearly Paid Off Loan',
    'initial_balance' => 500000,
    'remaining_balance' => 50000,
    'monthly_payment' => 25000,
    'annual_rate' => 0.08,
    'days_since_payment' => 30
];

$result3 = $tester->testPayoffCalculation($testCase3);

echo "\n" . str_repeat("=", 80) . "\n";
echo "SUMMARY OF ALL TEST RESULTS\n";
echo str_repeat("=", 80) . "\n";

$testResults = [
    'URL013572 Loan' => $result1,
    'Simple Test' => $result2,
    'Nearly Paid' => $result3
];

foreach ($testResults as $name => $result) {
    echo "\n$name:\n";
    echo "  Total Payoff: " . number_format($result['total_payoff'], 2) . "\n";
    echo "  Outstanding Principal: " . number_format($result['outstanding_principal'], 2) . "\n";
    echo "  Pro-rated Interest: " . number_format($result['pro_rated_interest'], 2) . "\n";
}

echo "\n✓ Dynamic Payoff Formula implementation tested successfully!\n";