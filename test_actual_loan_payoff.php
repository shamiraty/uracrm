<?php

require_once 'vendor/autoload.php';

use App\Models\LoanOffer;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/**
 * Test the improved payoff formula with actual loan data
 * Formula: Total Payoff = P + (P × R/365 × D)
 */

class PayoffCalculationTester {
    
    private const DEFAULT_ANNUAL_RATE = 0.12; // 12% default
    private const DEFAULT_DAYS_SINCE_PAYMENT = 7;
    
    /**
     * Calculate original principal from initial balance
     */
    private function calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure) {
        if ($monthlyPayment <= 0 || $tenure <= 0) {
            return $initialBalance;
        }
        
        $monthlyRate = $annualRate / 12;
        
        if ($monthlyRate == 0) {
            return $initialBalance;
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
        
        $reduction = $initialBalance - $currentBalance;
        $estimatedPayments = round($reduction / $monthlyPayment);
        
        return max(0, $estimatedPayments);
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
     * Test specific loan calculation
     */
    public function testLoanPayoff($loanNumber) {
        echo "\n=== Testing Loan: $loanNumber ===\n";
        
        // Find the loan
        $loanOffer = LoanOffer::where('loan_number', $loanNumber)->first();
        
        if (!$loanOffer) {
            echo "Loan not found: $loanNumber\n";
            return;
        }
        
        // Display loan details
        echo "Loan Details:\n";
        echo "- Requested Amount: " . number_format($loanOffer->requested_amount, 2) . "\n";
        echo "- Monthly Payment: " . number_format($loanOffer->desired_deductible_amount, 2) . "\n";
        echo "- Tenure: {$loanOffer->tenure} months\n";
        echo "- Installments Paid: {$loanOffer->installments_paid}\n";
        echo "- Current Outstanding: " . number_format($loanOffer->outstanding_balance, 2) . "\n";
        echo "- Current Settlement: " . number_format($loanOffer->settlement_amount, 2) . "\n";
        
        // Get parameters
        $initialBalance = (float)$loanOffer->requested_amount;
        $monthlyPayment = (float)($loanOffer->desired_deductible_amount ?? $loanOffer->monthly_payment ?? 0);
        $tenure = (int)$loanOffer->tenure;
        $annualRate = $loanOffer->interest_rate ? (float)$loanOffer->interest_rate / 100 : self::DEFAULT_ANNUAL_RATE;
        $monthlyRate = $annualRate / 12;
        
        // Calculate original principal
        $originalPrincipal = $this->calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure);
        echo "\nCalculations:\n";
        echo "- Annual Rate: " . ($annualRate * 100) . "%\n";
        echo "- Original Principal: " . number_format($originalPrincipal, 2) . "\n";
        
        // Estimate installments paid if not provided
        $installmentsPaid = $loanOffer->installments_paid;
        if (!$installmentsPaid && $loanOffer->outstanding_balance > 0) {
            $installmentsPaid = $this->calculateInstallmentsPaid($initialBalance, $loanOffer->outstanding_balance, $monthlyPayment);
            echo "- Estimated Installments Paid: $installmentsPaid\n";
        }
        
        // Calculate outstanding principal
        $outstandingPrincipal = $this->calculateOutstandingPrincipal($originalPrincipal, $monthlyPayment, $installmentsPaid, $monthlyRate);
        echo "- Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
        
        // Calculate days since last payment
        $daysSincePayment = self::DEFAULT_DAYS_SINCE_PAYMENT;
        if ($loanOffer->last_payment_date) {
            $daysSincePayment = max(1, Carbon::parse($loanOffer->last_payment_date)->diffInDays(now()));
        }
        echo "- Days Since Last Payment: $daysSincePayment\n";
        
        // Apply the formula: Total Payoff = P + (P × R/365 × D)
        $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $daysSincePayment;
        $totalPayoff = $outstandingPrincipal + $proRatedInterest;
        
        echo "\nPayoff Calculation:\n";
        echo "- Pro-rated Interest: " . number_format($proRatedInterest, 2) . "\n";
        echo "- Total Payoff Amount: " . number_format($totalPayoff, 2) . "\n";
        
        // Compare with stored values
        if ($loanOffer->settlement_amount > 0) {
            $difference = $totalPayoff - $loanOffer->settlement_amount;
            echo "\nComparison:\n";
            echo "- Stored Settlement: " . number_format($loanOffer->settlement_amount, 2) . "\n";
            echo "- Calculated Payoff: " . number_format($totalPayoff, 2) . "\n";
            echo "- Difference: " . number_format($difference, 2) . " (" . round(abs($difference / $loanOffer->settlement_amount * 100), 2) . "%)\n";
        }
        
        return [
            'loan_number' => $loanNumber,
            'total_payoff' => $totalPayoff,
            'outstanding_principal' => $outstandingPrincipal,
            'pro_rated_interest' => $proRatedInterest
        ];
    }
    
    /**
     * Test multiple loans
     */
    public function testMultipleLoans() {
        // Test with specific loan numbers
        $testLoans = [
            'URL013572',  // Known imported loan
            'URL002224',  // Another known loan
        ];
        
        // Also test some recent loans
        $recentLoans = LoanOffer::whereNotNull('loan_number')
            ->where('outstanding_balance', '>', 0)
            ->limit(3)
            ->pluck('loan_number')
            ->toArray();
        
        $allLoans = array_unique(array_merge($testLoans, $recentLoans));
        
        echo "================================================\n";
        echo "Testing Improved Payoff Formula with Actual Data\n";
        echo "Formula: Total Payoff = P + (P × R/365 × D)\n";
        echo "================================================\n";
        
        $results = [];
        foreach ($allLoans as $loanNumber) {
            if ($loanNumber) {
                $result = $this->testLoanPayoff($loanNumber);
                if ($result) {
                    $results[] = $result;
                }
            }
        }
        
        // Summary
        echo "\n================================================\n";
        echo "Summary of Payoff Calculations\n";
        echo "================================================\n";
        echo sprintf("%-15s | %-15s | %-15s | %-15s\n", 
            "Loan Number", "Outstanding", "Interest", "Total Payoff");
        echo str_repeat("-", 70) . "\n";
        
        foreach ($results as $result) {
            echo sprintf("%-15s | %15s | %15s | %15s\n",
                $result['loan_number'],
                number_format($result['outstanding_principal'], 2),
                number_format($result['pro_rated_interest'], 2),
                number_format($result['total_payoff'], 2)
            );
        }
    }
    
    /**
     * Test with simulated ESS request data
     */
    public function testWithESSData() {
        echo "\n================================================\n";
        echo "Testing with Simulated ESS Message 11 Data\n";
        echo "================================================\n\n";
        
        // Simulate data from ESS Message 11
        $essData = [
            [
                'loan_number' => 'URL013572',
                'deduction_amount' => 6397076.00,  // Initial loan amount
                'deduction_balance' => 5508591.00,  // Current balance from ESS
                'check_number' => '123456789',
                'payment_option' => 'Full payment'
            ],
            [
                'loan_number' => 'URL002224',
                'deduction_amount' => 3000000.00,
                'deduction_balance' => 2500000.00,
                'check_number' => '987654321',
                'payment_option' => 'Full payment'
            ]
        ];
        
        foreach ($essData as $data) {
            echo "Testing ESS Data for Loan: {$data['loan_number']}\n";
            echo "- Deduction Amount: " . number_format($data['deduction_amount'], 2) . "\n";
            echo "- Deduction Balance: " . number_format($data['deduction_balance'], 2) . "\n";
            
            $loanOffer = LoanOffer::where('loan_number', $data['loan_number'])->first();
            
            if ($loanOffer) {
                // Use ESS provided values
                $initialBalance = $data['deduction_amount'];
                $currentBalance = $data['deduction_balance'];
                $monthlyPayment = (float)($loanOffer->desired_deductible_amount ?? 0);
                
                // Calculate installments paid
                $installmentsPaid = $this->calculateInstallmentsPaid($initialBalance, $currentBalance, $monthlyPayment);
                
                // Calculate payoff
                $annualRate = self::DEFAULT_ANNUAL_RATE;
                $monthlyRate = $annualRate / 12;
                $tenure = (int)$loanOffer->tenure;
                
                $originalPrincipal = $this->calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure);
                $outstandingPrincipal = $this->calculateOutstandingPrincipal($originalPrincipal, $monthlyPayment, $installmentsPaid, $monthlyRate);
                
                $daysSincePayment = 7; // Default for weekly processing
                $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $daysSincePayment;
                $totalPayoff = $outstandingPrincipal + $proRatedInterest;
                
                echo "\nCalculated Payoff:\n";
                echo "- Installments Paid: $installmentsPaid\n";
                echo "- Outstanding Principal: " . number_format($outstandingPrincipal, 2) . "\n";
                echo "- Pro-rated Interest (7 days): " . number_format($proRatedInterest, 2) . "\n";
                echo "- Total Payoff: " . number_format($totalPayoff, 2) . "\n\n";
            } else {
                echo "Loan not found in database\n\n";
            }
        }
    }
}

// Run the tests
$tester = new PayoffCalculationTester();

// Test multiple loans from database
$tester->testMultipleLoans();

// Test with simulated ESS data
$tester->testWithESSData();

echo "\n================================================\n";
echo "All tests completed!\n";
echo "================================================\n";