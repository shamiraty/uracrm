<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\LoanOffer;
use Exception;

/**
 * Enhanced Loan Payoff Calculation Implementation
 * Fully implements all corrections and best practices from the PDF documentation
 */
trait EmployeeLoanPayoffEnhanced
{
    /**
     * COMPREHENSIVE PAYOFF CALCULATION - FULL PDF IMPLEMENTATION
     * 
     * This method implements ALL corrections and best practices from the PDF:
     * 1. Correct variable definitions (IB, BA, EMI, PV, n, m, r)
     * 2. Proper formulas for PV and Payoff calculations
     * 3. Critical correction: m = n - (BA / EMI), NOT (IB - BA) / EMI
     * 4. Comprehensive validation and error handling
     * 5. Detailed logging for debugging and audit trail
     * 
     * @param LoanOffer $loanOffer The loan record
     * @param array $requestData Data from ESS TOP_UP_PAY_OFF_BALANCE_REQUEST
     * @return array Calculated payoff details
     * @throws Exception On validation errors
     */
    protected function calculateComprehensivePayoff(LoanOffer $loanOffer, array $requestData): array
    {
        Log::info('════════════════════════════════════════════════════════════════', [
            'method' => 'COMPREHENSIVE PAYOFF CALCULATION (FULL PDF IMPLEMENTATION)',
            'loan_number' => $loanOffer->loan_number,
            'timestamp' => now()->toIso8601String()
        ]);

        try {
            // ═══════════════════════════════════════════════════════════════
            // PHASE 1: DATA EXTRACTION, VALIDATION & PREPARATION
            // ═══════════════════════════════════════════════════════════════
            
            $validatedData = $this->validateAndPrepareData($loanOffer, $requestData);
            
            $IB = $validatedData['IB'];   // Initial Balance (with interest)
            $BA = $validatedData['BA'];   // Balance Amount (remaining with interest)
            $EMI = $validatedData['EMI']; // Equated Monthly Installment
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 2: DETERMINE LOAN PARAMETERS (n, r)
            // ═══════════════════════════════════════════════════════════════
            
            $loanParams = $this->determineLoanParameters($loanOffer, $IB, $EMI);
            
            $n = $loanParams['n'];               // Total tenure in months
            $r = $loanParams['r'];               // Monthly interest rate
            $annualRate = $loanParams['annual']; // Annual interest rate
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 3: CALCULATE ORIGINAL PRINCIPAL (PV)
            // PDF Formula: PV = EMI × [1 - (1 + r)^-n] / r
            // ═══════════════════════════════════════════════════════════════
            
            $PV = $this->calculateOriginalPrincipalPV($EMI, $r, $n);
            
            $this->validateOriginalPrincipal($PV, $IB, $EMI, $n);
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 4: DETERMINE PAYMENTS MADE (m)
            // Critical PDF Correction: m = n - (BA / EMI)
            // NOT m = (IB - BA) / EMI (which is INCORRECT)
            // ═══════════════════════════════════════════════════════════════
            
            $m = $this->calculatePaymentsMade($n, $BA, $EMI, $IB);
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 5: CALCULATE OUTSTANDING PRINCIPAL
            // PDF Formula: Payoff = PV × [(1 + r)^n - (1 + r)^m] / [(1 + r)^n - 1]
            // ═══════════════════════════════════════════════════════════════
            
            $outstandingPrincipal = $this->calculateOutstandingPrincipal($PV, $r, $n, $m);
            
            $this->validateOutstandingPrincipal($outstandingPrincipal, $PV, $m, $n);
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 6: CALCULATE ACCRUED INTEREST
            // ═══════════════════════════════════════════════════════════════
            
            $interestDetails = $this->calculateAccruedInterest(
                $outstandingPrincipal, 
                $annualRate, 
                $loanOffer
            );
            
            $accruedInterest = $interestDetails['interest'];
            $daysSincePayment = $interestDetails['days'];
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 7: CALCULATE FINAL PAYOFF AMOUNT
            // Final Payoff = Outstanding Principal + Accrued Interest
            // ═══════════════════════════════════════════════════════════════
            
            $totalPayoff = round($outstandingPrincipal + $accruedInterest, 2);
            
            // ═══════════════════════════════════════════════════════════════
            // PHASE 8: COMPREHENSIVE LOGGING & METRICS
            // ═══════════════════════════════════════════════════════════════
            
            $this->logComprehensiveResults([
                'loan_number' => $loanOffer->loan_number,
                'input' => ['IB' => $IB, 'BA' => $BA, 'EMI' => $EMI],
                'parameters' => ['n' => $n, 'r' => $r, 'm' => $m],
                'calculated' => [
                    'PV' => $PV,
                    'outstanding' => $outstandingPrincipal,
                    'interest' => $accruedInterest
                ],
                'result' => $totalPayoff,
                'metrics' => [
                    'progress' => round(($m / $n) * 100, 2),
                    'principal_paid' => round($PV - $outstandingPrincipal, 2),
                    'remaining_percent' => round(($outstandingPrincipal / $PV) * 100, 2)
                ]
            ]);
            
            // Return comprehensive result
            return [
                'total_payoff_amount' => $totalPayoff,
                'outstanding_balance' => round($outstandingPrincipal, 2),
                'pro_rated_interest' => round($accruedInterest, 2),
                'days_since_payment' => $daysSincePayment,
                'original_principal' => round($PV, 2),
                'payments_made' => $m,
                'total_tenure' => $n,
                'fsp_reference_number' => $this->generateFspReference(),
                'payment_reference_number' => $this->generatePaymentReference(),
                'final_payment_date' => now()->addDays(7),
                'calculation_method' => 'REDUCING_BALANCE_PDF_CORRECTED',
                'validation_status' => 'PASSED'
            ];
            
        } catch (Exception $e) {
            Log::error('PAYOFF CALCULATION FAILED', [
                'loan_number' => $loanOffer->loan_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Validate and prepare input data with comprehensive checks
     */
    private function validateAndPrepareData(LoanOffer $loanOffer, array $requestData): array
    {
        // Extract with proper naming from PDF
        $IB = (float)($requestData['deduction_amount'] ?? 0);
        $BA = (float)($requestData['deduction_balance'] ?? 0);
        $EMI = (float)($loanOffer->desired_deductible_amount ?? $loanOffer->monthly_payment ?? 0);
        
        // Critical validations
        if ($EMI <= 0) {
            throw new Exception("Invalid EMI (Monthly Payment): $EMI. Must be greater than zero.");
        }
        
        // Apply fallbacks with logging
        if ($IB <= 0) {
            Log::warning('Invalid Initial Balance, using database value', [
                'provided' => $IB,
                'fallback' => $loanOffer->requested_amount
            ]);
            $IB = (float)$loanOffer->requested_amount;
        }
        
        if ($BA <= 0) {
            Log::warning('Invalid Balance Amount, using database value', [
                'provided' => $BA,
                'fallback' => $loanOffer->outstanding_balance
            ]);
            $BA = (float)($loanOffer->outstanding_balance ?? 0);
        }
        
        // Logical validations
        if ($BA >= $IB) {
            throw new Exception("Balance Amount ($BA) cannot exceed Initial Balance ($IB)");
        }
        
        if ($IB <= 0 || $BA < 0) {
            throw new Exception("Invalid balance values: IB=$IB, BA=$BA");
        }
        
        Log::info('DATA VALIDATION COMPLETED', [
            'Initial_Balance_IB' => $IB,
            'Balance_Amount_BA' => $BA,
            'Monthly_Payment_EMI' => $EMI,
            'reduction_amount' => $IB - $BA,
            'reduction_percent' => round((($IB - $BA) / $IB) * 100, 2) . '%'
        ]);
        
        return ['IB' => $IB, 'BA' => $BA, 'EMI' => $EMI];
    }
    
    /**
     * Determine loan parameters (n, r) with validation
     */
    private function determineLoanParameters(LoanOffer $loanOffer, float $IB, float $EMI): array
    {
        // Get tenure
        $n = (int)$loanOffer->tenure;
        
        if ($n <= 0) {
            // PDF approximation: n ≈ IB / EMI
            $n = round($IB / $EMI);
            Log::info('Tenure estimated from IB/EMI', [
                'calculation' => "n = $IB / $EMI = $n"
            ]);
        }
        
        // Validate reasonable tenure (1-480 months)
        if ($n < 1 || $n > 480) {
            throw new Exception("Unreasonable tenure: $n months. Expected 1-480.");
        }
        
        // Get interest rate
        $annualRate = $this->getLoanInterestRate($loanOffer);
        
        if ($annualRate < 0 || $annualRate > 1) {
            throw new Exception("Invalid annual rate: $annualRate. Expected 0-1.");
        }
        
        $r = $annualRate / 12; // Monthly rate
        
        Log::info('LOAN PARAMETERS DETERMINED', [
            'tenure_n' => $n,
            'annual_rate' => ($annualRate * 100) . '%',
            'monthly_rate_r' => $r
        ]);
        
        return ['n' => $n, 'r' => $r, 'annual' => $annualRate];
    }
    
    /**
     * Calculate Original Principal using PDF formula
     * PV = EMI × [1 - (1 + r)^-n] / r
     */
    private function calculateOriginalPrincipalPV(float $EMI, float $r, int $n): float
    {
        if ($r == 0) {
            // Zero interest case
            $PV = $EMI * $n;
        } else {
            // Standard formula
            $PV = $EMI * ((1 - pow(1 + $r, -$n)) / $r);
        }
        
        Log::info('ORIGINAL PRINCIPAL CALCULATED', [
            'formula' => 'PV = EMI × [1 - (1 + r)^-n] / r',
            'EMI' => $EMI,
            'r' => $r,
            'n' => $n,
            'PV' => round($PV, 2)
        ]);
        
        return $PV;
    }
    
    /**
     * Validate original principal calculation
     */
    private function validateOriginalPrincipal(float $PV, float $IB, float $EMI, int $n): void
    {
        if ($PV <= 0) {
            throw new Exception("Invalid Original Principal calculated: $PV");
        }
        
        // PV should be less than IB (since IB includes interest)
        if ($PV >= $IB) {
            Log::warning('Original Principal exceeds/equals Initial Balance', [
                'PV' => $PV,
                'IB' => $IB,
                'ratio' => round($PV / $IB, 4)
            ]);
        }
        
        // Sanity check: PV should be less than EMI × n
        $maxPossible = $EMI * $n;
        if ($PV > $maxPossible) {
            throw new Exception("PV ($PV) exceeds maximum possible ($maxPossible)");
        }
    }
    
    /**
     * Calculate payments made using CORRECTED formula
     * m = n - (BA / EMI)
     */
    private function calculatePaymentsMade(int $n, float $BA, float $EMI, float $IB): int
    {
        // Critical correction from PDF
        $remainingPayments = $BA / $EMI;
        $m = $n - round($remainingPayments);
        
        // Ensure within bounds
        $m = max(0, min($m, $n));
        
        // Additional validation
        $reductionRatio = ($IB - $BA) / $IB;
        $expectedProgress = $m / $n;
        
        // Check for consistency
        if (abs($reductionRatio - $expectedProgress) > 0.3) {
            Log::warning('Payment progress inconsistency detected', [
                'reduction_ratio' => $reductionRatio,
                'expected_progress' => $expectedProgress,
                'difference' => abs($reductionRatio - $expectedProgress)
            ]);
        }
        
        Log::info('PAYMENTS MADE CALCULATED', [
            'formula' => 'm = n - (BA / EMI)',
            'n' => $n,
            'BA' => $BA,
            'EMI' => $EMI,
            'remaining_payments' => round($remainingPayments, 2),
            'payments_made_m' => $m,
            'progress' => round(($m / $n) * 100, 2) . '%'
        ]);
        
        return $m;
    }
    
    /**
     * Calculate outstanding principal using Reducing Balance formula
     * Payoff = PV × [(1 + r)^n - (1 + r)^m] / [(1 + r)^n - 1]
     */
    private function calculateOutstandingPrincipal(float $PV, float $r, int $n, int $m): float
    {
        if ($m <= 0) {
            return $PV; // No payments made
        }
        
        if ($m >= $n) {
            return 0; // Loan fully paid
        }
        
        if ($r == 0) {
            // Zero interest case
            $remainingPayments = $n - $m;
            return $PV * ($remainingPayments / $n);
        }
        
        // Apply the Reducing Balance Formula
        $onePlusR_n = pow(1 + $r, $n);
        $onePlusR_m = pow(1 + $r, $m);
        
        $numerator = $onePlusR_n - $onePlusR_m;
        $denominator = $onePlusR_n - 1;
        
        if ($denominator == 0) {
            throw new Exception("Division by zero in payoff calculation");
        }
        
        $payoff = $PV * ($numerator / $denominator);
        
        Log::info('OUTSTANDING PRINCIPAL CALCULATED', [
            'formula' => 'Payoff = PV × [(1+r)^n - (1+r)^m] / [(1+r)^n - 1]',
            'PV' => $PV,
            'r' => $r,
            'n' => $n,
            'm' => $m,
            'payoff' => round($payoff, 2)
        ]);
        
        return $payoff;
    }
    
    /**
     * Validate outstanding principal
     */
    private function validateOutstandingPrincipal(float $outstanding, float $PV, int $m, int $n): void
    {
        if ($outstanding < 0) {
            Log::error('Negative outstanding principal', ['value' => $outstanding]);
            throw new Exception("Negative outstanding principal: $outstanding");
        }
        
        if ($outstanding > $PV) {
            Log::error('Outstanding exceeds original', [
                'outstanding' => $outstanding,
                'original' => $PV
            ]);
            throw new Exception("Outstanding ($outstanding) exceeds original ($PV)");
        }
        
        // Progress check
        $principalPaidRatio = ($PV - $outstanding) / $PV;
        $paymentProgressRatio = $m / $n;
        
        if (abs($principalPaidRatio - $paymentProgressRatio) > 0.4) {
            Log::warning('Principal reduction inconsistent with payment progress', [
                'principal_paid_ratio' => $principalPaidRatio,
                'payment_progress_ratio' => $paymentProgressRatio
            ]);
        }
    }
    
    /**
     * Calculate accrued interest since last payment
     */
    private function calculateAccruedInterest(float $principal, float $annualRate, LoanOffer $loanOffer): array
    {
        $daysSincePayment = $this->getDaysSinceLastPayment($loanOffer);
        
        // Validate days
        if ($daysSincePayment < 0) {
            Log::warning('Negative days since payment, setting to 0');
            $daysSincePayment = 0;
        } elseif ($daysSincePayment > 365) {
            Log::warning('Days since payment exceeds 1 year', ['days' => $daysSincePayment]);
        }
        
        $dailyRate = $annualRate / 365;
        $accruedInterest = $principal * $dailyRate * $daysSincePayment;
        
        Log::info('ACCRUED INTEREST CALCULATED', [
            'principal' => $principal,
            'annual_rate' => $annualRate,
            'days' => $daysSincePayment,
            'daily_rate' => $dailyRate,
            'interest' => round($accruedInterest, 2)
        ]);
        
        return [
            'interest' => max(0, $accruedInterest),
            'days' => $daysSincePayment
        ];
    }
    
    /**
     * Log comprehensive results for audit and debugging
     */
    private function logComprehensiveResults(array $data): void
    {
        Log::info('════════════════════════════════════════════════════════════════');
        Log::info('PAYOFF CALCULATION COMPLETE - COMPREHENSIVE SUMMARY', $data);
        Log::info('════════════════════════════════════════════════════════════════');
    }
}