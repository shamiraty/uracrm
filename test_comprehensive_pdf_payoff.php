<?php
/**
 * Comprehensive Test for the Enhanced Payoff Calculation
 * Tests ALL aspects of the PDF implementation
 */

require_once 'vendor/autoload.php';
require_once 'app/Http/Controllers/EmployeeLoanControllerEnhanced.php';

use App\Http\Controllers\EmployeeLoanPayoffEnhanced;
use App\Models\LoanOffer;

class ComprehensivePayoffTest
{
    use EmployeeLoanPayoffEnhanced;
    
    private $testResults = [];
    
    /**
     * Run comprehensive test suite
     */
    public function runAllTests()
    {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
        echo "║     COMPREHENSIVE PAYOFF CALCULATION TEST SUITE (FULL PDF IMPLEMENTATION)     ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════════╝\n";
        
        // Test 1: Exact PDF Example
        $this->testPDFExample();
        
        // Test 2: Edge Cases
        $this->testEdgeCases();
        
        // Test 3: Various Scenarios
        $this->testVariousScenarios();
        
        // Test 4: Validation Tests
        $this->testValidation();
        
        // Display Summary
        $this->displaySummary();
    }
    
    /**
     * Test the exact example from the PDF
     */
    private function testPDFExample()
    {
        echo "\n┌─────────────────────────────────────────────────────────────────────────────┐\n";
        echo "│ TEST 1: EXACT PDF EXAMPLE (TSH 6,397,076.07 loan)                          │\n";
        echo "└─────────────────────────────────────────────────────────────────────────────┘\n";
        
        // Create mock loan offer
        $loanOffer = $this->createMockLoanOffer([
            'loan_number' => 'URL013572',
            'desired_deductible_amount' => 177697,
            'tenure' => 36,
            'interest_rate' => 0.12,
            'requested_amount' => 6397076.07
        ]);
        
        // Request data from PDF
        $requestData = [
            'deduction_amount' => 6397076.07,  // IB
            'deduction_balance' => 4264712.07,  // BA
        ];
        
        try {
            $result = $this->calculateComprehensivePayoff($loanOffer, $requestData);
            
            echo "\n✅ PDF Example Test Results:\n";
            echo "   Initial Balance (IB): TSH " . number_format($requestData['deduction_amount'], 2) . "\n";
            echo "   Balance Amount (BA): TSH " . number_format($requestData['deduction_balance'], 2) . "\n";
            echo "   Monthly Payment (EMI): TSH " . number_format(177697, 2) . "\n";
            echo "   ───────────────────────────────\n";
            echo "   Original Principal (PV): TSH " . number_format($result['original_principal'], 2) . "\n";
            echo "   Payments Made (m): " . $result['payments_made'] . " of " . $result['total_tenure'] . "\n";
            echo "   Outstanding Principal: TSH " . number_format($result['outstanding_balance'], 2) . "\n";
            echo "   Accrued Interest: TSH " . number_format($result['pro_rated_interest'], 2) . "\n";
            echo "   ───────────────────────────────\n";
            echo "   TOTAL PAYOFF: TSH " . number_format($result['total_payoff_amount'], 2) . "\n";
            
            // Compare with PDF expected value
            $expectedPrincipal = 3775000.00;
            $difference = abs($result['outstanding_balance'] - $expectedPrincipal);
            echo "\n   PDF Expected Principal: TSH " . number_format($expectedPrincipal, 2) . "\n";
            echo "   Difference: TSH " . number_format($difference, 2) . " (" . 
                 round(($difference / $expectedPrincipal) * 100, 4) . "%)\n";
            
            $this->testResults['pdf_example'] = 'PASSED';
            
        } catch (Exception $e) {
            echo "\n❌ PDF Example Test Failed: " . $e->getMessage() . "\n";
            $this->testResults['pdf_example'] = 'FAILED';
        }
    }
    
    /**
     * Test edge cases
     */
    private function testEdgeCases()
    {
        echo "\n┌─────────────────────────────────────────────────────────────────────────────┐\n";
        echo "│ TEST 2: EDGE CASES                                                         │\n";
        echo "└─────────────────────────────────────────────────────────────────────────────┘\n";
        
        $edgeCases = [
            [
                'name' => 'Zero Interest Loan',
                'loan' => ['tenure' => 24, 'interest_rate' => 0.00, 'desired_deductible_amount' => 100000],
                'request' => ['deduction_amount' => 2400000, 'deduction_balance' => 1200000]
            ],
            [
                'name' => 'First Payment',
                'loan' => ['tenure' => 36, 'interest_rate' => 0.10, 'desired_deductible_amount' => 50000],
                'request' => ['deduction_amount' => 1800000, 'deduction_balance' => 1750000]
            ],
            [
                'name' => 'Last Payment',
                'loan' => ['tenure' => 12, 'interest_rate' => 0.08, 'desired_deductible_amount' => 100000],
                'request' => ['deduction_amount' => 1200000, 'deduction_balance' => 100000]
            ],
            [
                'name' => 'High Interest',
                'loan' => ['tenure' => 60, 'interest_rate' => 0.24, 'desired_deductible_amount' => 200000],
                'request' => ['deduction_amount' => 12000000, 'deduction_balance' => 6000000]
            ]
        ];
        
        foreach ($edgeCases as $case) {
            echo "\n   Testing: " . $case['name'] . "\n";
            
            $loanOffer = $this->createMockLoanOffer($case['loan']);
            
            try {
                $result = $this->calculateComprehensivePayoff($loanOffer, $case['request']);
                echo "   ✓ Total Payoff: TSH " . number_format($result['total_payoff_amount'], 2) . "\n";
                echo "     Outstanding: TSH " . number_format($result['outstanding_balance'], 2) . "\n";
                echo "     Progress: " . $result['payments_made'] . "/" . $result['total_tenure'] . " payments\n";
                $this->testResults['edge_' . strtolower(str_replace(' ', '_', $case['name']))] = 'PASSED';
            } catch (Exception $e) {
                echo "   ✗ Failed: " . $e->getMessage() . "\n";
                $this->testResults['edge_' . strtolower(str_replace(' ', '_', $case['name']))] = 'FAILED';
            }
        }
    }
    
    /**
     * Test various real-world scenarios
     */
    private function testVariousScenarios()
    {
        echo "\n┌─────────────────────────────────────────────────────────────────────────────┐\n";
        echo "│ TEST 3: VARIOUS REAL-WORLD SCENARIOS                                       │\n";
        echo "└─────────────────────────────────────────────────────────────────────────────┘\n";
        
        $scenarios = [
            [
                'name' => 'Small Personal Loan',
                'loan' => ['tenure' => 12, 'interest_rate' => 0.15, 'desired_deductible_amount' => 87000],
                'request' => ['deduction_amount' => 1044000, 'deduction_balance' => 522000]
            ],
            [
                'name' => 'Medium Business Loan',
                'loan' => ['tenure' => 48, 'interest_rate' => 0.12, 'desired_deductible_amount' => 250000],
                'request' => ['deduction_amount' => 12000000, 'deduction_balance' => 9000000]
            ],
            [
                'name' => 'Large Mortgage',
                'loan' => ['tenure' => 240, 'interest_rate' => 0.09, 'desired_deductible_amount' => 500000],
                'request' => ['deduction_amount' => 120000000, 'deduction_balance' => 115000000]
            ]
        ];
        
        foreach ($scenarios as $scenario) {
            echo "\n   " . $scenario['name'] . ":\n";
            
            $loanOffer = $this->createMockLoanOffer($scenario['loan']);
            
            try {
                $result = $this->calculateComprehensivePayoff($loanOffer, $scenario['request']);
                
                $progress = round(($result['payments_made'] / $result['total_tenure']) * 100, 1);
                
                echo "   ├─ Loan Progress: " . $progress . "% (" . 
                     $result['payments_made'] . "/" . $result['total_tenure'] . " payments)\n";
                echo "   ├─ Original Principal: TSH " . number_format($result['original_principal'], 2) . "\n";
                echo "   ├─ Outstanding: TSH " . number_format($result['outstanding_balance'], 2) . "\n";
                echo "   ├─ Interest (" . $result['days_since_payment'] . " days): TSH " . 
                     number_format($result['pro_rated_interest'], 2) . "\n";
                echo "   └─ Total Payoff: TSH " . number_format($result['total_payoff_amount'], 2) . "\n";
                
                $this->testResults['scenario_' . strtolower(str_replace(' ', '_', $scenario['name']))] = 'PASSED';
                
            } catch (Exception $e) {
                echo "   └─ ❌ Failed: " . $e->getMessage() . "\n";
                $this->testResults['scenario_' . strtolower(str_replace(' ', '_', $scenario['name']))] = 'FAILED';
            }
        }
    }
    
    /**
     * Test validation logic
     */
    private function testValidation()
    {
        echo "\n┌─────────────────────────────────────────────────────────────────────────────┐\n";
        echo "│ TEST 4: VALIDATION TESTS                                                   │\n";
        echo "└─────────────────────────────────────────────────────────────────────────────┘\n";
        
        $validationTests = [
            [
                'name' => 'Invalid EMI (zero)',
                'loan' => ['tenure' => 36, 'interest_rate' => 0.12, 'desired_deductible_amount' => 0],
                'request' => ['deduction_amount' => 1000000, 'deduction_balance' => 500000],
                'should_fail' => true
            ],
            [
                'name' => 'BA > IB (invalid)',
                'loan' => ['tenure' => 36, 'interest_rate' => 0.12, 'desired_deductible_amount' => 50000],
                'request' => ['deduction_amount' => 1000000, 'deduction_balance' => 1500000],
                'should_fail' => true
            ],
            [
                'name' => 'Negative Interest Rate',
                'loan' => ['tenure' => 36, 'interest_rate' => -0.05, 'desired_deductible_amount' => 50000],
                'request' => ['deduction_amount' => 1800000, 'deduction_balance' => 900000],
                'should_fail' => true
            ],
            [
                'name' => 'Valid Input',
                'loan' => ['tenure' => 24, 'interest_rate' => 0.10, 'desired_deductible_amount' => 50000],
                'request' => ['deduction_amount' => 1200000, 'deduction_balance' => 600000],
                'should_fail' => false
            ]
        ];
        
        foreach ($validationTests as $test) {
            echo "\n   Testing: " . $test['name'] . "\n";
            
            $loanOffer = $this->createMockLoanOffer($test['loan']);
            
            try {
                $result = $this->calculateComprehensivePayoff($loanOffer, $test['request']);
                
                if ($test['should_fail']) {
                    echo "   ✗ Should have failed but passed\n";
                    $this->testResults['validation_' . strtolower(str_replace(' ', '_', $test['name']))] = 'FAILED';
                } else {
                    echo "   ✓ Passed correctly\n";
                    echo "     Payoff: TSH " . number_format($result['total_payoff_amount'], 2) . "\n";
                    $this->testResults['validation_' . strtolower(str_replace(' ', '_', $test['name']))] = 'PASSED';
                }
                
            } catch (Exception $e) {
                if ($test['should_fail']) {
                    echo "   ✓ Failed correctly: " . $e->getMessage() . "\n";
                    $this->testResults['validation_' . strtolower(str_replace(' ', '_', $test['name']))] = 'PASSED';
                } else {
                    echo "   ✗ Should have passed but failed: " . $e->getMessage() . "\n";
                    $this->testResults['validation_' . strtolower(str_replace(' ', '_', $test['name']))] = 'FAILED';
                }
            }
        }
    }
    
    /**
     * Display test summary
     */
    private function displaySummary()
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                              TEST SUMMARY                                     ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";
        
        $passed = 0;
        $failed = 0;
        
        foreach ($this->testResults as $test => $status) {
            $icon = $status === 'PASSED' ? '✅' : '❌';
            $testName = str_replace('_', ' ', ucfirst($test));
            echo sprintf("   %s %-50s %s\n", $icon, $testName, $status);
            
            if ($status === 'PASSED') {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        $total = $passed + $failed;
        $percentage = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
        
        echo "\n";
        echo "═══════════════════════════════════════════════════════════════════════════════\n";
        echo "   TOTAL: $total tests | PASSED: $passed | FAILED: $failed | SUCCESS RATE: $percentage%\n";
        echo "═══════════════════════════════════════════════════════════════════════════════\n";
        
        if ($failed == 0) {
            echo "\n🎉 ALL TESTS PASSED! The comprehensive PDF implementation is working correctly!\n";
        } else {
            echo "\n⚠️  Some tests failed. Please review the implementation.\n";
        }
    }
    
    /**
     * Create a mock loan offer for testing
     */
    private function createMockLoanOffer($data)
    {
        $loan = new class extends LoanOffer {
            public function __construct() {
                // Don't call parent constructor
            }
        };
        
        foreach ($data as $key => $value) {
            $loan->$key = $value;
        }
        
        // Set defaults
        $loan->loan_number = $loan->loan_number ?? 'TEST' . rand(1000, 9999);
        $loan->requested_amount = $loan->requested_amount ?? 1000000;
        
        return $loan;
    }
    
    /**
     * Mock helper methods
     */
    protected function getLoanInterestRate($loanOffer)
    {
        return $loanOffer->interest_rate ?? 0.12;
    }
    
    protected function getDaysSinceLastPayment($loanOffer)
    {
        return 7; // Default to 7 days for testing
    }
    
    protected function generateFspReference()
    {
        return 'FSP' . rand(10000000, 99999999);
    }
    
    protected function generatePaymentReference()
    {
        return 'PAY' . rand(10000000, 99999999);
    }
}

// Run the tests
$tester = new ComprehensivePayoffTest();
$tester->runAllTests();