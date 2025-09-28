<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoanCalculationService
{
    const INSURANCE_RATE = 0.01;        // 1%
    const PROCESSING_FEE_RATE = 0.0025;  // 0.25%
    const OTHER_CHARGES_RATE = 0.00;     // 0%
    const ANNUAL_INTEREST_RATE = 12;     // 12% per annum
    
    /**
     * Calculate loan charges based on different scenarios
     * Compatible with ESS API requirements
     */
    public function calculateLoanCharges(array $params)
    {
        $basicSalary = $params['basic_salary'] ?? 0;
        $netSalary = $params['net_salary'] ?? 0;
        $oneThirdAmount = $params['one_third_amount'] ?? 0;
        $deductibleAmount = $params['deductible_amount'] ?? 0;
        $requestedAmount = $params['requested_amount'] ?? 0;
        $desiredDeductibleAmount = $params['desired_deductible_amount'] ?? 0;
        $tenure = $params['tenure'] ?? 0;
        $retirementDate = $params['retirement_date'] ?? 0;
        
        $maxTenureAllowed = min(48, $retirementDate > 0 ? $retirementDate : 48);
        
        $totalChargesRate = self::INSURANCE_RATE + self::PROCESSING_FEE_RATE + self::OTHER_CHARGES_RATE;
        $monthlyInterestRate = self::ANNUAL_INTEREST_RATE / 100 / 12;
        
        // Calculate principal amount
        $principalAmount = $requestedAmount > 0 ? $requestedAmount : 0;
        
        // Determine monthly payment or deductible amount
        $monthlyPayment = $desiredDeductibleAmount > 0 ? $desiredDeductibleAmount : $deductibleAmount;
        
        // Adjust tenure if needed
        if ($tenure > 0) {
            $tenure = min($tenure, $maxTenureAllowed);
        }
        
        // Calculate based on scenarios
        $result = $this->calculateScenario([
            'principal' => $principalAmount,
            'tenure' => $tenure,
            'monthly_payment' => $monthlyPayment,
            'interest_rate' => $monthlyInterestRate,
            'max_tenure' => $maxTenureAllowed
        ]);
        
        // Calculate charges
        $insurance = $result['principal'] * self::INSURANCE_RATE;
        $processingFee = $result['principal'] * self::PROCESSING_FEE_RATE;
        $otherCharges = $result['principal'] * self::OTHER_CHARGES_RATE;
        $totalCharges = $insurance + $processingFee + $otherCharges;
        
        // Calculate net loan amount (ESS expects this)
        $netLoanAmount = $result['principal'];
        
        // Calculate take-home amount (actual disbursement)
        $takeHomeAmount = $result['principal'] - $totalCharges;
        
        // Total amount to pay back
        $totalAmountToPay = $result['monthly_payment'] * $result['tenure'];
        $totalInterest = $totalAmountToPay - $result['principal'];
        
        return [
            'principal_amount' => $result['principal'],
            'monthly_payment' => $result['monthly_payment'],
            'tenure' => $result['tenure'],
            'insurance' => $insurance,
            'processing_fee' => $processingFee,
            'other_charges' => $otherCharges,
            'total_charges' => $totalCharges,
            'net_loan_amount' => $netLoanAmount,
            'take_home_amount' => $takeHomeAmount,
            'total_amount_to_pay' => $totalAmountToPay,
            'total_interest' => $totalInterest,
            'eligible_amount' => $result['principal']
        ];
    }
    
    /**
     * Calculate loan parameters based on given scenario
     */
    private function calculateScenario(array $params)
    {
        $P = $params['principal'];
        $N = $params['tenure'];
        $M = $params['monthly_payment'];
        $r = $params['interest_rate'];
        $maxTenure = $params['max_tenure'];
        
        // Scenario 1: Given Principal and Tenure => Calculate Monthly Payment
        if ($P > 0 && $N > 0) {
            $powResult = pow(1 + $r, $N);
            if ($powResult == 1 && $r == 0) {
                throw new \Exception('Invalid interest rate or tenure');
            }
            $numerator = $r * $powResult;
            $denominator = $powResult - 1;
            if ($denominator == 0) {
                throw new \Exception('Invalid interest rate or tenure');
            }
            $M = $P * ($numerator / $denominator);
            
            return [
                'principal' => $P,
                'tenure' => $N,
                'monthly_payment' => $M
            ];
        }
        
        // Scenario 2: Given Principal and Monthly Payment => Calculate Tenure
        if ($P > 0 && $N == 0 && $M > 0) {
            $minPayment = $P * $r;
            if ($M <= $minPayment) {
                throw new \Exception('Monthly payment too low for the loan amount');
            }
            
            $logNumerator = log($M / ($M - $P * $r));
            $logDenominator = log(1 + $r);
            $N = ceil($logNumerator / $logDenominator);
            $N = min($N, $maxTenure);
            
            return [
                'principal' => $P,
                'tenure' => $N,
                'monthly_payment' => $M
            ];
        }
        
        // Scenario 3: Given Monthly Payment and Tenure => Calculate Principal
        if ($P == 0 && $M > 0 && $N > 0) {
            $powResult = pow(1 + $r, $N);
            $denominator = $r * $powResult;
            $numerator = $powResult - 1;
            
            if ($denominator == 0) {
                throw new \Exception('Invalid interest rate');
            }
            
            $P = $M * ($numerator / $denominator);
            
            return [
                'principal' => $P,
                'tenure' => $N,
                'monthly_payment' => $M
            ];
        }
        
        // Default scenario
        return [
            'principal' => $P,
            'tenure' => $N ?: 12,
            'monthly_payment' => $M
        ];
    }
    
    /**
     * Calculate top-up loan parameters
     */
    public function calculateTopupLoan(array $newLoanParams, array $existingLoanBalance)
    {
        // Calculate new loan charges
        $newLoanCharges = $this->calculateLoanCharges($newLoanParams);
        
        // Deduct settlement amount from new loan
        $settlementAmount = $existingLoanBalance['settlement_amount'] ?? 0;
        $netDisbursement = $newLoanCharges['take_home_amount'] - $settlementAmount;
        
        return array_merge($newLoanCharges, [
            'settlement_amount' => $settlementAmount,
            'net_disbursement' => $netDisbursement,
            'original_loan_number' => $existingLoanBalance['loan_number'] ?? null,
            'outstanding_balance' => $existingLoanBalance['outstanding_balance'] ?? 0
        ]);
    }
}