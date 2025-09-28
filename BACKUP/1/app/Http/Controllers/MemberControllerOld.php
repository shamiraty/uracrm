<?php

namespace App\Http\Controllers;

use App\Imports\MembersImport;
use App\Models\Member;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    /**
     * Display the form for uploading an Excel file.
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm()
    {
        return view('members.upload_form');
    }

    /**
     * Process the uploaded Excel file and import member data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
     // Store the uploaded file and import its data
     public function store(Request $request)
     {
         // Validate the request to ensure a file is provided and is of the correct type.
         $request->validate([
             'excel' => 'required|file|mimes:xls,xlsx,csv'
         ]);
 
         try {
             // Import the Excel file using the MembersImport class.
             Excel::import(new MembersImport, $request->file('excel'));
 
             return redirect()->back()->with('success', 'Members data imported successfully!');
         } catch (\Exception $e) {
             // Log the error or handle it as needed.
             return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
         }
     }
    /**
     * Update the status of a member.
     *
     * @param  int     $memberId
     * @param  string  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($memberId, $status)
    {
        $member = Member::find($memberId);

        if (!$member) {
            return redirect()->back()->with('error', 'Member not found.');
        }

        $member->update(['status' => $status]);

        return redirect()->back()->with('success', "Member status updated to {$status}!");
    }

    /**
     * Display a list of members with processed loan details.
     *
     * @return \Illuminate\View\View
     */
    public function showProcessedLoans()
    {
        // Retrieve all members with a non-null loanableAmount.
        $members = Member::whereNotNull('loanableAmount')->get();

        return view('members.processed_loans', compact('members'));
    }

    /**
     * Show the details and amortization schedule for a specific member.
     *
     * @param  int  $memberId
     * @return \Illuminate\View\View
     */
    public function showDetails($memberId)
    {
        $member = Member::findOrFail($memberId);

        // Define fixed parameters for the amortization schedule.
        $annualInterestRate = 12; // Annual interest rate (in percent)
        $totalPeriods       = 48; // Total number of periods (months)

        // Generate the amortization schedule based on the member's loanable amount.
        $amortizationSchedule = $this->generateAmortizationSchedule(
            $member->loanableAmount,
            $annualInterestRate,
            $totalPeriods
        );

        return view('members.details', compact('member', 'amortizationSchedule'));
    }

    /**
     * Generate an amortization schedule for a given loan.
     *
     * @param  float  $loanAmount
     * @param  float  $annualInterestRate
     * @param  int    $totalPeriods
     * @return array
     */
    private function generateAmortizationSchedule($loanAmount, $annualInterestRate, $totalPeriods)
    {
        // Calculate the monthly interest rate.
        $monthlyInterestRate = $annualInterestRate / 12 / 100;

        // Calculate EMI using the annuity formula.
        $emi = $loanAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPeriods))
               / (pow(1 + $monthlyInterestRate, $totalPeriods) - 1);

        $balance = $loanAmount;
        $amortizationSchedule = [];

        // Build the amortization schedule month by month.
        for ($period = 1; $period <= $totalPeriods; $period++) {
            $interestPayment  = $balance * $monthlyInterestRate;
            $principalPayment = $emi - $interestPayment;
            $balance         -= $principalPayment;

            $amortizationSchedule[] = [
                'period'    => $period,
                'emi'       => round($emi, 2),
                'interest'  => round($interestPayment, 2),
                'principal' => round($principalPayment, 2),
                'balance'   => round($balance, 2)
            ];
        }

        return $amortizationSchedule;
    }
}
