<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MortgageCalculatorController extends Controller
{


    public function showForm() {
        return view('mortgage.calculate');
    }


//reduce balance loan calculation
    public function calculateLoanableAmount(Request $request) {
        $basicSalary = $request->basic_salary;
        $allowances = $request->allowances; // This should be an array of allowances
        $takeHome = $request->take_home;
        $numberOfMonths = $request->number_of_months; // Duration of the loan repayment in months

        // Calculate one third of the salary
        $oneThirdSalary = $basicSalary / 3;
        $totalAllowances = array_sum($allowances);

        // Calculate the loanable take home amount (this will be treated as the monthly payment M)
        $loanableTakeHome = $takeHome - ($oneThirdSalary + $totalAllowances);

        // Fixed annual interest rate
        $annualInterestRate = 12; // Interest rate as per your earlier message
        $monthlyInterestRate = $annualInterestRate / 100 / 12; // Convert annual interest rate to monthly

        // Using the formula to calculate the principal (loanApplicable)
        // P = M × ( (1 + r)^n - 1 ) / (r × (1 + r)^n)
        $loanApplicable = ($loanableTakeHome * (pow(1 + $monthlyInterestRate, $numberOfMonths) - 1)) / ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfMonths));

        // Calculate Monthly Deduction (fixed monthly payment)
        $monthlyDeduction = $loanableTakeHome; // Since loanableTakeHome is the monthly payment (M)

        // Now, Total Loan with Interest is simply the monthly deduction multiplied by the number of months
        $totalLoanWithInterest = $monthlyDeduction * $numberOfMonths;

        // Calculate Total Interest
        $totalInterest = $totalLoanWithInterest - $loanApplicable;

        // Calculate Processing Fee (Assume 0.25% of the loan applicable)
        $processingFee = $loanApplicable * 0.0025;

        // Calculate Insurance (Assume 1% of the loan applicable)
        $insurance = $loanApplicable * 0.01;

        // Calculate Disbursement Amount (The amount received after fees)
        $disbursementAmount = $loanApplicable - ($processingFee + $insurance);

        return view('mortgage.result', [
            'loanableTakeHome' => $loanableTakeHome,
            'loanableAmount' => $loanApplicable,
            'totalLoanWithInterest' => $totalLoanWithInterest,
            'totalInterest' => $totalInterest,
            'monthlyDeduction' => $monthlyDeduction,
            'processingFee' => $processingFee,
            'insurance' => $insurance,
            'disbursementAmount' => $disbursementAmount // The net amount disbursed to the borrower
        ]);
    }



}
