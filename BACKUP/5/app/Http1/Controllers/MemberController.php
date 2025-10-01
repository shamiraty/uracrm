<?php

// app/Http/Controllers/EmployeeController.php
namespace App\Http\Controllers;

use App\Imports\MembersImport;
use App\Models\Member;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{

    public function showUploadForm()
    {
        return view('members.upload_form');
    }
    public function store(Request $request)
    {
        $file = $request->file('excel');
        Excel::import(new MembersImport, $file);

        return redirect()->back()->with('success', 'Employees data imported and loan calculations performed successfully!');
    }

    public function updateStatus($memberId, $status)
{
    $member = Member::find($memberId);
    $member->status = $status;
    $member->save();

    return redirect()->back()->with('success', 'Member status updated to ' . $status . '!');
}


    public function showProcessedLoans()
    {
        // Fetch all employees with their loan details
        $members = Member::whereNotNull('loanableAmount')->get();

        // Return the view with the list of processed loans
        return view('members.processed_loans', ['members' => $members]);
    }

    public function showDetails($memberId)
{
    $member = Member::findOrFail($memberId);

    // Assuming annual interest rate and total periods (months) are fixed or can be retrieved from the member object
    $annualInterestRate = 12; // Example fixed rate
    $totalPeriods = 48; // Example fixed period

    // Dynamically generate amortization schedule
    $amortizationSchedule = $this->generateAmortizationSchedule(
        $member->loanableAmount,
        $annualInterestRate,
        $totalPeriods
    );

    return view('members.details', [
        'member' => $member,
        'amortizationSchedule' => $amortizationSchedule
    ]);
}

private function generateAmortizationSchedule($loanAmount, $annualInterestRate, $totalPeriods)
{
    $monthlyInterestRate = $annualInterestRate / 12 / 100;
    $emi = $loanAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPeriods)) / (pow(1 + $monthlyInterestRate, $totalPeriods) - 1);

    $balance = $loanAmount;
    $amortizationSchedule = [];

    for ($period = 1; $period <= $totalPeriods; $period++) {
        $interestPayment = $balance * $monthlyInterestRate;
        $principalPayment = $emi - $interestPayment;
        $balance -= $principalPayment;

        $amortizationSchedule[] = [
            'Period' => $period,
            'EMI' => round($emi, 2),
            'Interest' => round($interestPayment, 2),
            'Principal' => round($principalPayment, 2),
            'Balance' => round($balance, 2)
        ];
    }

    return $amortizationSchedule;
}
}

