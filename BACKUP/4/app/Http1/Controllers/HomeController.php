<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
         $monthlyLoanData = LoanApplication::getMonthlyDataForCurrentYear();
        // Metrics for the dashboard
        $enquiryFrequencyApproved = $this->getMonthlyFrequency('Enquiry', 'approved');
        $loanApplicationFrequencyPending = $this->getLoanApplicationFrequency('pending');
        $monthlyLoanApplications = $this->getMonthlyLoanApplicationFrequencies(); // New data
        $enquiryTypeFrequency = $this->getEnquiryTypeFrequency();
        $loanApplicationStatusFrequency = $this->getLoanApplicationStatusFrequency();
        
        // Metrics Cards
        $enquiryFrequencyAllTime = $this->getEnquiryFrequencyAllTime();
        $loanApplicationFrequencyAllTime = $this->getLoanApplicationFrequencyAllTime();
        $enquiryTypeMembership = $this->getEnquiryTypeFrequencyByType('join_membership');
        $enquiryTypeShare = $this->getEnquiryTypeFrequencyByType('share_enquiry');
        $enquiryTypeDeduction = $this->getEnquiryTypeFrequencyByType('deduction_add');

        //last 10  enquires,  
         $enquiries = Enquiry::orderBy('date_received', 'desc')->limit(10)->get();

        // Pass the data to the view
        return view('dashboard', compact(
            'enquiryFrequencyApproved',
            'loanApplicationFrequencyPending',
            'monthlyLoanApplications', // Include monthly data
            'enquiryTypeFrequency',
            'loanApplicationStatusFrequency',
            'enquiryFrequencyAllTime',
            'loanApplicationFrequencyAllTime',
            'enquiryTypeMembership',
            'enquiryTypeShare',
            'enquiryTypeDeduction',
            'enquiries'
        ));
    }

    private function getMonthlyFrequency($model, $status)
    {
        return Enquiry::where('status', $status)
            ->whereYear('date_received', Carbon::now()->year)
            ->selectRaw('MONTH(date_received) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();
    }

    private function getLoanApplicationFrequency($status)
    {
        return LoanApplication::whereIn('status', [$status, 'paid'])
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('COUNT(*) as frequency')
            ->first();
    }

    private function getMonthlyLoanApplicationFrequencies()
    {
        // Initialize arrays for the counts
        $monthlyPaidFrequencies = array_fill(0, 12, 0);
        $monthlyPendingFrequencies = array_fill(0, 12, 0);
        $currentYear = Carbon::now()->year;

        // Get paid loan applications by month
        $paidApplications = LoanApplication::where('status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();

        foreach ($paidApplications as $application) {
            $monthlyPaidFrequencies[$application->month - 1] = $application->frequency; // month is 1-indexed
        }

        // Get pending loan applications by month
        $pendingApplications = LoanApplication::where('status', 'pending')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();

        foreach ($pendingApplications as $application) {
            $monthlyPendingFrequencies[$application->month - 1] = $application->frequency; // month is 1-indexed
        }

        return [
            'paid' => $monthlyPaidFrequencies,
            'pending' => $monthlyPendingFrequencies,
        ];
    }

    private function getEnquiryTypeFrequency()
    {
        return Enquiry::select('type')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('type')
            ->get();
    }

    private function getLoanApplicationStatusFrequency()
    {
        return LoanApplication::select('status')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('status')
            ->get();
    }

    private function getEnquiryFrequencyAllTime()
    {
        return Enquiry::selectRaw('COUNT(*) as frequency')->first();
    }

    private function getLoanApplicationFrequencyAllTime()
    {
        return LoanApplication::selectRaw('COUNT(*) as frequency')->first();
    }

    private function getEnquiryTypeFrequencyByType($type)
    {
        return Enquiry::where('type', $type)
            ->selectRaw('COUNT(*) as frequency')
            ->first();
    }
}
